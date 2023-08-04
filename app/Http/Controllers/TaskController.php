<?php

namespace App\Http\Controllers;

use App\Actions\ValidateUserRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class TaskController extends Controller
{
    public function searchByTags(Request $request)
    {
        $request->validate([
            'search' => 'string|min:1|max:256'
        ]);
        return new TaskCollection(
            Task::query()
                ->join('tags', 'tasks.id', '=', 'tags.task_id')
                ->select('tasks.*')
                ->where('tasks.user_id', $request->user()->id)
                ->where('tags.user_id', $request->user()->id)
                ->where(DB::raw('lower(tags.name)'), 'like', strtolower($request->search))
                ->get()
        );
    }
    /**
     * Display a listing of the resource.
     */
    public function search(Request $request)
    {
        $request->validate([
            'search' => 'string|min:1|max:526'
        ]);
        return new TaskCollection(
            Task::query()
                ->where('user_id', $request->user()->id)
                ->where('text', 'like', "%{$request->search}%")
                ->get()
        );
    }

    /**
     * Display a listing of the resource.
     */
    public function showByTodo(Request $request)
    {
        return new TaskCollection(
            Task::query()
                ->where('user_id', $request->user()->id)
                ->where('todo_id', $request->todo_id)
                ->get()
        );
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return new TaskCollection(Task::all()->where('user_id', $request->user()->id));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request, ValidateUserRequest $validateUserRequest)
    {
        if (Todo::find($request['todo_id'])->user_id != $request->user()->id) {
            return response([
                'message' => 'Unauthorized'
            ],403);
        }

        $validatedRequest = $validateUserRequest($request);
        $task = Task::create($validatedRequest);
        return response([
            'data' => new TaskResource($task)
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);
        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task, ValidateUserRequest $validateUserRequest)
    {
        $this->authorize('update', $task);
        $validatedRequest = $validateUserRequest($request);
        $task->update($validatedRequest);
        return response([
            'data' => new TaskResource($task)
        ],201);
    }

    public function addImage(UpdateTaskRequest $request, Task $task, ValidateUserRequest $validateUserRequest)
    {
        $this->authorize('update', $task);
        $validatedRequest = $validateUserRequest($request);

        $path = 'images/';
        $thumbnailsPath = 'images/thumbnails/';


        !Storage::exists($path) &&
            Storage::makeDirectory($path);
        !Storage::exists($thumbnailsPath) &&
            Storage::makeDirectory($thumbnailsPath);

        $file = $request->file('image');
        $filename = $file->hashName();
        Storage::put('images', $file);

        Image::make(storage_path('app/public/'.$path.$filename))
            ->fit(150, 150)
            ->save(storage_path('app/public/'.$thumbnailsPath.$filename));

        $validatedRequest['image'] = $path.$filename;
        $validatedRequest['thumbnail'] = $thumbnailsPath.$filename;
        $task->update($validatedRequest);
        return response([
            'data' => new TaskResource($task)
        ],201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();
        return response(null, 204);
    }
}
