<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Project::all();

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
                // 'category' => 'required|integer',
                // 'images' => 'sometimes|json',
            ]
        );

        $project = $request->user()->projects()->create($fields);

        return response()->json($project, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
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
                // 'category' => 'sometimes|integer',
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
