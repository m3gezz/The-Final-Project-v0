<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $comments = Project::findOrFail($request->project_id)->comments;
        return response()->json($comments, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate(
            [
                'project_id' => 'required|exists:projects,id',
                'content' => 'required|string|min:1'
            ]
        );

        $fields['owner'] = $request->user()->only(['id', 'first_name', 'last_name']);

        $comment = Comment::create($fields);
        return response()->json($comment, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        return response()->json($comment, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        Gate::authorize('update', $comment);
        $fields = $request->validate(
            [
                'content' => 'required|string|min:1'
            ]
        );

        $comment->update($fields);

        return response()->json($comment, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        Gate::authorize('delete', $comment);
        $comment->delete();

        $data = ['message' => 'Deleted successfully'];

        return response()->json($data, 200);
    }
}
