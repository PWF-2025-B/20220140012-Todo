<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;


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
        $categories = Category::where('user_id', auth()->user()->id)->get();
        return view('todo.create', compact('categories'));
    }

    public function store(Request $request, Todo $todo)
    {
        $request->validate([
            'title' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        $todo = Todo::create([
            'title' => ucfirst($request->title),
            'user_id' => auth()->user()->id,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('todo.index')->with('success', 'Todo created successfully!');
    }

    public function complete(Todo $todo)
    {
        if (auth()->user()->id == $todo->user_id) {
            $todo->update([
                'is_done' => true,
            ]);
            return redirect()->route('todo.index')->with('success', 'Todo completed successfully!');
        } else {
            return redirect()->route('todo.index')->with('danger', 'You are not authorized to complete this todo!');
        }
    }

    public function uncomplete(Todo $todo)
    {
        if (auth()->user()->id == $todo->user_id) {
            $todo->update([
                'is_done' => false,
            ]);
            return redirect()->route('todo.index')->with('success', 'Todo uncompleted successfully!');
        } else {
            return redirect()->route('todo.index')->with('danger', 'You are not authorized to uncomplete this todo!');
        }
    }

    public function edit(Todo $todo)
    {
        if (auth()->id() == $todo->user_id) {
            $categories = Category::where('user_id', auth()->user()->id)->get();
            return view('todo.edit', compact('todo', 'categories'));
        } else{
            return redirect()->route('todo.index')->with('danger', 'You are not authorized to edit this todo!');
        }
    }

    public function update(Request $request, Todo $todo)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

         $todo->update([
            'title' => ucfirst($request->title),
            'category_id' => $request->category_id,
        ]);
        return redirect()->route('todo.index')->with('success', 'Todo updated successfully!');
    }


    public function destroy(Todo $todo)
    {
        if (auth()->id() == $todo->user_id) {
            $todo->delete();
            return redirect()->route('todo.index')->with('success', 'Todo deleted successfully.');
        }  else {
        return redirect()->route('todo.index')->with('danger', 'Unauthorized action.');
    }
}

    public function deleteAllCompleted()
    {
        $todosCompleted = Todo::where('user_id', auth()->user()->id)
            ->where('is_done', true)
            ->get();

        foreach ($todosCompleted as $todo) {
            $todo->delete();
        }
        
        return redirect()->route('todo.index')->with('success', 'All completed todos deleted successfully!');
}
}