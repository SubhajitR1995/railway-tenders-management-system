<?php

namespace App\Http\Controllers;

use App\Models\TenderCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TenderCategoryController extends Controller
{
    public function index(): View
    {
        $categories = TenderCategory::withCount('tenders')->orderBy('name')->paginate(15);

        return view('categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:tender_categories'],
            'description' => ['nullable', 'string'],
        ]);

        TenderCategory::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function show(TenderCategory $category): View
    {
        $tenders = $category->tenders()->with('creator')->latest()->paginate(10);

        return view('categories.show', compact('category', 'tenders'));
    }

    public function edit(TenderCategory $category): View
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, TenderCategory $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:tender_categories,name,'.$category->id],
            'description' => ['nullable', 'string'],
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(TenderCategory $category): RedirectResponse
    {
        if ($category->tenders()->exists()) {
            return back()->with('error', 'Cannot delete a category that has tenders assigned to it.');
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
