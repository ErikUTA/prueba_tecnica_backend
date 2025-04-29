<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\User;
use App\Services\PayUService\Exception;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    public function getTasks()
    {
        $tasks = Task::with('users')->get();
        
        return response()->json([
            'success' => true,
            'tasks' => $tasks
        ], 200);
    }

    public function getTasksByPriority(Request $request)
    {
        $tasks = Task::where('priority_id', $request->input('priority'))
            ->get();
        
        return response()->json([
            'success' => true,
            'tasks' => $tasks
        ], 200);
    }

    public function getTaskByUser($userId)
    {
        $user = User::with('tasks')->findOrFail($userId);
        if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'tasks' => $user
        ], 200);
    }

    public function changeTaskStatus(Request $request, $taskId)
    {
        \DB::beginTransaction();
        try {
            $task = Task::whereId($taskId);
            if(!$task) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tarea no encontrada'
                ], 500);
            }

            $task->update(['status_id' => $request->status]);
            \DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Estatus actualizado correctamente',
            ], 200);
        } catch(\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);        
        }
    }
    
    public function createTask(Request $request)
    {
        \DB::beginTransaction();
        try {
            $rules = [
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'expiration_date' => 'required|string|max:255',
                'status_id' => 'required|exists:statuses,id',
                'priority_id' => 'required|exists:priority,id',
            ];
            
            $validated = $request->validate($rules, [
                'required' => 'Todos los campos son requeridos'
            ]);

            Task::create($validated);
            
            \DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Tarea creada correctamente',
            ], 200);
        } catch(\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);        
        }
    }

    public function updateTask(Request $request, $taskId)
    {
        \DB::beginTransaction();
        try {
            $task = Task::find($taskId);
            if(!$task) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tarea no encontrada'
                ], 500);
            }
            $rules = [
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'expiration_date' => 'required|string|max:255',
                'status_id' => 'required|exists:statuses,id',
                'priority_id' => 'required|exists:priority,id',
            ];
            
            $validated = $request->validate($rules, [
                'required' => 'Todos los campos son requeridos'
            ]);

            $task->update($validated);
            
            \DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Tarea actualizada correctamente',
            ], 200);
        } catch(\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);        
        }
    }

    public function deleteTask($taskId)
    {
        \DB::beginTransaction();
        try {
            $task = Task::find($taskId);
            if(!$task) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tarea no encontrada'
                ], 500);
            }

            $task->delete();
            
            \DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Tarea eliminada correctamente',
            ], 200);
        } catch(\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);        
        }
    }

    public function assignTaskToUser(Request $request, $taskId)
    {
        \DB::beginTransaction();
        try {
            $task = Task::find($taskId);
            if(!$task) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tarea no encontrada'
                ], 500);
            }

            $task->users()->attach($request->users);
            
            \DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Tareas asignadas correctamente',
            ], 200);
        } catch(\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);        
        }
    }
}
