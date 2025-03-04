<?php

namespace App\Http\Controllers\Task;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Validated;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $task = Task::with('user')->get();
        if (!$task) {
            return response()->json([
                'message' => 'Task not found',
            ],404);
        }
        return response()->json([
            'message' => 'Task successfully retrieved',
            'tasks' => $task,
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        $task = Task::create($request->validated());
        if (!$task) {
            return response()->json([
                'message' => 'Task not created',
            ],400);
        }
        return response()->json([
            'message' => 'Task successfully created',
            'task' => $task,
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, string $id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json([
                'message' => 'Task not found',
            ], 404);
        }

        $task->update($request->except(['_method']));
        return response()->json([
            'message' => 'Task successfully updated',
            'task' => $task,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json([
                'message' => 'Task not found',
            ],404);
        }
        $task->delete();
        return response()->json([
            'message' => 'Task successfully deleted',
        ],200);
    }
}
