<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\ProjectRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProjectRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $projectRequests = ProjectRequest::with('user')->where('project_id', $request->project_id)->where('status', 'pending')->get();
        
        return response()->json($projectRequests, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate(
            [
                'project_id' => 'required|exists:projects,id',
                'message' => 'required|string|min:10|max:255'
            ]
        );

        $alreadyMember = ProjectMember::where('project_id', $fields['project_id'])->where('user_id', $request->user()->id)->exists();
        $haRequest = ProjectRequest::where('project_id', $fields['project_id'])->where('user_id', $request->user()->id)->exists();

        if ($alreadyMember || $haRequest) {
            return ['message' => 'You either already a member, or you have a request'];
        }

        $projectRequest = $request->user()->projectRequests()->create($fields);

        return response()->json($projectRequest->load('user'), 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProjectRequest $projectRequest)
    {
        return response()->json($projectRequest->load('user'), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProjectRequest $projectRequest)
    {
        Gate::authorize('update', $projectRequest);
        $fields = $request->validate(
            [
                'status' => 'required|string',
                'seen' => 'sometimes|boolean'
            ]
        );

        $projectRequest->update($fields);

        if ($fields['status'] === 'accepted') {
            $inviter = $request->user()->only(['id', 'first_name', 'last_name']);
            $projectMember = [
                'project_id' => $projectRequest->project_id,
                'user_id' => $projectRequest->user_id,
                'invited_by' => $inviter
            ];

            ProjectMember::create($projectMember);
        }

        if ($fields['status'] != 'pending') {
            $projectRequest->delete();
            $data = ['message' => 'Request ' . $fields['status'] . ' successfully'];

            return response()->json($data, 200);
        }

        return response()->json($projectRequest->load('user'), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProjectRequest $projectRequest)
    {
        Gate::authorize('delete', $projectRequest);
        $projectRequest->delete();

        $data = ['message' => 'Deleted successfully'];

        return response()->json($data, 200);
    }
}
