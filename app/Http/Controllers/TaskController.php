<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        
        

        $query = Task::where('user_id', Auth::id());
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }
        
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        switch ($request->sort) {
            case 'deadline_asc':
                $query->orderBy('deadline', 'asc');
                break;
            case 'deadline_desc':
                $query->orderBy('deadline', 'desc');
                break;
            case 'created_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'created_desc':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
        dd(vars: $query->get());
        $tasks = $query->paginate(3);
        $trashedTasks = Task::where('user_id', Auth::id())->onlyTrashed()->count();
        dd($tasks);
        return view('tasks.index', compact('tasks', 'trashedTasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function bulkCreate()
    {
        return view('tasks.bulk-create');
    }

    public function store(Request $request)
    {
        $taskData = $request->all();
        $taskData['user_id'] = Auth::id();
        
        Task::create($taskData);
        return redirect('Task')->with('success', 'Task created successfully.');
    }

    public function bulkStore(Request $request)
    {
        $tasks = $request->input('tasks', []);
        
        foreach ($tasks as $taskData) {
            if (!empty($taskData['title'])) {
                $taskData['user_id'] = Auth::id();
                Task::create($taskData);
            }
        }
        
        return redirect('Task')->with('success', 'Tasks created successfully.');
    }

    public function edit($id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, $id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);
        $task->update($request->all());
        return redirect('Task')->with('success', 'Task updated successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);
        $task->update(['status' => $request->input('status')]);
        return redirect('Task')->with('success', 'Task status updated successfully.');
    }

    public function destroy($id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);
        $task->delete();
        return redirect('Task')->with('success', 'Task moved to trash successfully.');
    }

    public function trash()
    {
        $trashedTasks = Task::where('user_id', Auth::id())
                           ->onlyTrashed()
                           ->orderBy('deleted_at', 'desc')
                           ->paginate(10);
        
        return view('tasks.trash', compact('trashedTasks'));
    }

    public function restore($id)
    {
        $task = Task::where('user_id', Auth::id())->onlyTrashed()->findOrFail($id);
        $task->restore();
        return redirect('Task/trash')->with('success', 'Task restored successfully.');
    }

    public function forceDelete($id)
    {
        $task = Task::where('user_id', Auth::id())->onlyTrashed()->findOrFail($id);
        $task->forceDelete();
        return redirect('Task/trash')->with('success', 'Task permanently deleted successfully.');
    }
}
