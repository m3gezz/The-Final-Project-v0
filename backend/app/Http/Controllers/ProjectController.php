<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    /**
     * Display a listing of the user's resources.
     */

    public function userProjects(Request $request)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);

        $projects = Project::with(['members', 'category', 'user'])->withCount('likes')
                        ->whereHas('members', function ($query) use ($request) {
                            $query->where('user_id', $request->user_id);
                        })
                        ->paginate(20);

        return response()->json($projects, 200);
    }

    public function userOwnedProjects(Request $request)
    {
        $projects = $request->user()->projects()->with(['members', 'category', 'user'])->withCount('likes')->paginate(20);
                        
        return response()->json($projects, 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Project::with(['user','members.user','category'])->withCount(['comments', 'likes']);

        if ($request->has('search')) {
            $query->where('title', 'like', '%'.$request->search.'%');
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('sort') && $request->sort === 'likes') {
            $query->orderBy('likes_count', 'desc');
        }

        $data = $query->paginate(20);

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
