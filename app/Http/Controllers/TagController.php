<?php

namespace App\Http\Controllers;

use App\Actions\ValidateUserRequest;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;
use App\Models\Task;
use Illuminate\Http\Request;

class TagController extends Controller
{

    public function unique(Request $request)
    {
        return Tag::select('name')->where('user_id', $request->user()->id)->distinct()->get();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Tag::all()->where('user_id', $request->user()->id);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request, ValidateUserRequest $validateUserRequest)
    {
        if (Task::find($request['task_id'])->user_id != $request->user()->id) {
            return response([
                'message' => 'Unauthorized'
            ],403);
        }
        $validatedRequest = $validateUserRequest($request);

        $tag = Tag::create($validatedRequest);
        return response([
            'data' => $tag
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        $this->authorize('view', $tag);
        return $tag;
    }

    /**
     * Display a listing of the resource.
     */
    public function showByTask(Request $request)
    {
        return Tag::query()
                ->where('user_id', $request->user()->id)
                ->where('task_id', $request->task_id)
                ->get();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tag $tag, ValidateUserRequest $validateUserRequest)
    {
        $this->authorize('update', $tag);
        $validatedRequest = $validateUserRequest($request);
        $tag->update($validatedRequest);
        return response([
            'data' => $tag
        ],201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        $this->authorize('delete', $tag);
        $tag->delete();
        return response(null, 204);
    }
}
