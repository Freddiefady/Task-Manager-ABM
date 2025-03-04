<?php

namespace App\Http\Controllers\Task;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Validator;

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
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:60',
            'status' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ]);

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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:60',
            'status' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()->all(),
            ], 422);
        }

        $task = Task::find($id);
        if (!$task) {
            return response()->json([
                'message' => 'Task not found',
            ], 404);
        }

        $task->update($request->all());
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
