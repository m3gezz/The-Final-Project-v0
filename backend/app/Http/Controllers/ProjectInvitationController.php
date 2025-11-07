<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectInvitation;
use App\Models\ProjectMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProjectInvitationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->has('project_id')) {
            $request->validate([
                'project_id' => 'required|exists:projects,id'
            ]);

            $projectInvitations = ProjectInvitation::where('project_id', $request->project_id)->with(['project', 'user'])->get();
            return response()->json($projectInvitations, 200);
        }

        $projectInvitations = $request->user()->projectInvitationsReceived()->with(['project', 'user'])->get();

        return response()->json($projectInvitations, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $project = Project::findOrFail($request->project_id);
        Gate::authorize('create', [ProjectInvitation::class, $project]);

        $fields = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|min:5',
        ]);

        $alreadyMember = ProjectMember::where('project_id', $fields['project_id'])->where('user_id', $fields['receiver_id'])->exists();
        $hasInvitation = ProjectInvitation::where('project_id', $fields['project_id'])->where('receiver_id', $fields['receiver_id'])->exists();

        if ($alreadyMember || $hasInvitation) {
            return response()->json(['message' => 'The receiver is either already a member, or has an invitation'], 400);
        }

        $projectInvitation = $request->user()->projectInvitations()->create($fields);
        return response()->json($projectInvitation, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProjectInvitation $projectInvitation)
    {
        return response()->json($projectInvitation->load(['project', 'user']), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProjectInvitation $projectInvitation)
    {
        Gate::authorize('update', $projectInvitation);

        $fields = $request->validate([
            'status' => 'required|string',
            'seen' => 'sometimes|boolean'
        ]);

        $projectInvitation->update($fields);

        if ($fields['status'] === 'accepted') {
            $projectMember = [
                'project_id' => $projectInvitation->project_id,
                'invited_by' => User::findOrFail($projectInvitation->user_id)->only(['id', 'first_name', 'last_name', 'avatar_url'])
            ];

            $request->user()->projectMemberships()->create($projectMember);
        }

        if ($fields['status'] != 'pending') {
            $projectInvitation->delete();
            $data = ['message' => 'Invitation ' . $fields['status'] . ' successfully'];

            return response()->json($data, 200);
        }

        return response()->json($projectInvitation->load('user'), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProjectInvitation $projectInvitation)
    {
        Gate::authorize('delete', $projectInvitation);
        $projectInvitation->delete();

        $data = ['message' => 'Deleted successfully'];

        return response()->json($data, 200);
    }
}
