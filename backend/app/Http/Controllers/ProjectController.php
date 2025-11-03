<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Project::with(['user','members.user','category'])->withCount(['comments', 'likes'])->paginate(20);
        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate(
            [
                'title' => 'required|string|min:5|max:255',
                'description' => 'required|string|min:10',
                'category_id' => 'required|exists:categories,id',
                // 'images' => 'sometimes|json',
            ]
        );

        $project = $request->user()->projects()->create($fields);
        
        $projectMember = [
            'project_id' => $project->id,
            'user_id' => $project->user_id,
            'role' => "admin",
        ];
        ProjectMember::create($projectMember);

        $project->load(['user', 'members.user', 'category']);

        return response()->json($project, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $project->load(['user', 'members.user','category', 'comments'])->loadCount(['likes']);

        return response()->json($project, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        Gate::authorize('update', $project);
        $fields = $request->validate(
            [
                'title' => 'sometimes|string|min:5|max:255',
                'description' => 'sometimes|string|min:10',
                'category' => 'sometimes|exists:categories,id',
                // 'images' => 'sometimes|json',
            ]
        );

        $project->update($fields);

        return response()->json($project, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        Gate::authorize('delete', $project);
        $project->delete();
        $data = ['message' => 'Deleted successfully'];

        return response()->json($data, 200);
    }
}
