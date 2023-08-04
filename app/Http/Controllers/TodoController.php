<?php

namespace App\Http\Controllers;

use App\Actions\ValidateUserRequest;
use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Http\Resources\TodoCollection;
use App\Http\Resources\TodoResource;
use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return new TodoCollection($request->user()->todos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTodoRequest $request, ValidateUserRequest $validateUserRequest)
    {
        $validatedRequest = $validateUserRequest($request);
        $todo = ToDo::create($validatedRequest);
        return response([
            'data' => new TodoResource($todo)
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Todo $todo)
    {
        $this->authorize('view', $todo);
        return new TodoResource($todo);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTodoRequest $request, Todo $todo, ValidateUserRequest $validateUserRequest)
    {
        $this->authorize('update', $todo);
        $validatedRequest = $validateUserRequest($request);
        $todo->update($validatedRequest);
        return response([
            'data' => new TodoResource($todo)
        ],201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo)
    {
        $this->authorize('delete', $todo);
        $todo->delete();
        return response(null, 204);
    }
}
