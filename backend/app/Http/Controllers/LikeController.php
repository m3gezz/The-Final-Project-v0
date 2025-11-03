<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $likes = Like::where('project_id', $request->project_id)->count();
        return response()->json($likes, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate(
            [
                'project_id' => 'required|exists:projects,id'
            ]
        );

        $exists = Like::where('project_id', $fields['project_id'])->where('user_id', $request->user()->id)->exists();

        if ($exists) {
            $data = ['message' => 'Already liked this project'];
            return response()->json($data, 200);
        }

        $fields['owner'] = $request->user()->only(['id', 'first_name', 'last_name', 'avatar_url']);

        $like = Like::create($fields);
        return response()->json($like, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Like $like)
    {
        return response()->json($like, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Like $like)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Like $like)
    {
        Gate::authorize('delete', $like);
        $like->delete();

        $data = ['message' => 'Deleted successfully'];

        return response()->json($data, 200);
    }
}
