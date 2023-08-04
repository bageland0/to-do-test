<?php

namespace App\Observers;

use App\Models\Task;
use Illuminate\Support\Facades\Storage;

class TaskObserver
{
    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        //
        if ($task->isDirty('image')) {
            Storage::delete($task->getOriginal('image'));
            Storage::delete($task->getOriginal('thumbnail'));
        }
    }

    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task): void
    {
        if (!is_null($task->image)) {
            Storage::delete($task->image);
            Storage::delete($task->getOriginal('thumbnail'));
        }
    }
}
