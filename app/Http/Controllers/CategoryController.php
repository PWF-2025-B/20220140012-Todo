<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CategoryController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the categories.
     */
    public function index(): View
    {
         $categories = Category::with('todos')->where('user_id', Auth::id())->get();
         return view('category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create(): View
    {
        return view('category.create');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $request->user()->categories()->create($validated);

        return redirect(route('category.index'))->with('success', 'Category created successfully!');
    }

    public function edit(Category $category): View
    {
        if (auth()->user()->id !== $category->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('category.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        // Match the authorization approach used in edit
        if (auth()->user()->id !== $category->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $category->update($validated);

        return redirect(route('category.index'))->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        // Match the authorization approach used in edit
        if (auth()->user()->id !== $category->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        $category->delete();

        return redirect(route('category.index'))->with('success', 'Category deleted successfully!');
    }
}