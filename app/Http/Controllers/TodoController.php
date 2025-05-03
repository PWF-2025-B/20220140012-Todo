<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::where('user_id', auth()->id())
            ->orderBy('is_done', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        $todosCompleted = $todos->where('is_done', true)->count();

        return view('todo.index', compact('todos', 'todosCompleted'));
    }

    public function create()
    {
        return view('todo.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        Todo::create([
            'title' => ucfirst($request->title),
            'user_id' => Auth::id(),
            'is_done' => false,
        ]);

        return redirect()->route('todo.index')->with('success', 'Todo created successfully.');
    }

    public function complete(Todo $todo)
    {
        if (Auth::id() == $todo->user_id) {
            $todo->update(['is_done' => true]);
            return redirect()->route('todo.index')->with('success', 'Todo marked as completed.');
        }
        return redirect()->route('todo.index')->with('danger', 'Unauthorized action.');
    }

    public function uncomplete(Todo $todo)
    {
        if (Auth::id() == $todo->user_id) {
            $todo->update(['is_done' => false]);
            return redirect()->route('todo.index')->with('success', 'Todo marked as uncompleted.');
        }
        return redirect()->route('todo.index')->with('danger', 'Unauthorized action.');
    }

    public function edit(Todo $todo)
    {
        if (auth()->id() == $todo->user_id) {
            return view('todo.edit', compact('todo'));
        }
        return redirect()->route('todo.index')->with('danger', 'Unauthorized action.');
    }

    public function update(Request $request, Todo $todo)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $todo->update(['title' => ucfirst($request->title)]);

        return redirect()->route('todo.index')->with('success', 'Todo updated successfully.');
    }

    public function destroy(Todo $todo)
    {
        if (auth()->id() == $todo->user_id) {
            $todo->delete();
            return redirect()->route('todo.index')->with('success', 'Todo deleted successfully.');
        }
        return redirect()->route('todo.index')->with('danger', 'Unauthorized action.');
    }

    public function deleteAllCompleted()
    {
        Todo::where('user_id', auth()->id())
            ->where('is_done', true)
            ->delete();

        return redirect()->route('todo.index')->with('success', 'All completed todos deleted.');
    }
}
