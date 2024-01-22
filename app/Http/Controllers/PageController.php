<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::where('user_id', Auth::id())->get();
        return view('dashboard', compact('pages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pageSection' => 'required|string',
            'pageTitle' => 'required|string',
        ]);

        $page = new Page([
            'content' => $request->input('pageSection'),
            'title' => $request->input('pageTitle'),
            'user_id' => Auth::id(),
        ]);

        $page->save();

        return redirect()->route('dashboard')->with('success', 'Page section saved successfully!');
    }

    public function edit(Page $page)
    {
        return view('dashboard', compact('page'));
    }

    public function updateContent(Request $request, Page $page)
    {
        $request->validate([
            'updatedContent' => 'required|string',
        ]);

        $page->content = $request->input('updatedContent');
        $page->save();

        return response()->json(['message' => 'Content updated successfully']);
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return redirect()->route('dashboard')->with('success', 'Page section deleted successfully!');
    }
}
