<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        try {
            $authors = DB::table('authors')
                ->when($request->keyword, function ($query) use ($request) {
                    $pattern = '%' . $request->keyword . '%';
                    $query->where('author_name', 'like', $pattern)
                        ->orWhere('description', 'like', $pattern)
                        ->orWhere('category', 'like', $pattern);
                })
                ->orderBy('id', 'desc')
                ->latest()
                ->paginate(10)
                ->through(function ($author) {
                    return [
                        'id' => $author->id,
                        'author_name' => $author->author_name,
                        'description' => $author->description,
                        'category' => $author->category,
                        'picture_path' => $author->picture_path
                    ];
                });
            return response()->json(['status' => true, 'data' => $authors]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}
