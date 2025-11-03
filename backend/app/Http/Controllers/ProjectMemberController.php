<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProjectMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $members = Project::with('members.user')->findOrFail($request->project_id)->members;

        return response()->json($members, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $project = Project::findOrFail($request->project_id);
        Gate::authorize('create', [ProjectMember::class, $project]);

        $fields = $request->validate(
            [
                'user_id' => 'required|exists:users,id',
                'project_id' => 'required|exists:projects,id',
                'role' => 'sometimes|string',
            ]
        );

        $exist = ProjectMember::where('project_id', $fields['project_id'])->where('user_id', $fields['user_id'])->exists();

        if ($exist) {
            $data = ['message' => 'User is already a member'];
            return response()->json($data, 422);
        }

        $fields['invited_by'] = $request->user()->only(['id', 'first_name', 'last_name', 'avatar_url']);
        $projectMember = ProjectMember::create($fields);

        return response()->json($projectMember, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProjectMember $projectMember)
    {
        return response()->json($projectMember->load('user'), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProjectMember $projectMember)
    {
        Gate::authorize('update', $projectMember);
        $fields = $request->validate(
            [
                'role' => 'sometimes|string',
            ]
        );

        $projectMember->update($fields);

        return response()->json($projectMember->load('user'), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProjectMember $projectMember)
    {
        Gate::authorize('delete', $projectMember);
        $projectMember->delete();
        $data = ['message' => 'Deleted successfully'];

        return response()->json($data, 200);
    }
}
