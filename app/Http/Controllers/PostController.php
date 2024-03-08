<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        try {
            $pagination_limit = (int) $request->paginationLimit ?? 10;
            $posts = DB::table('hp_posts')
                ->when($request->keyword, function ($query) use ($request) {
                    $pattern = '%' . $request->keyword . '%';
                    $query->where('title', 'like', $pattern)
                        ->orWhere('description', 'like', $pattern);
                })
                ->orderBy('id', 'desc')
                ->paginate($pagination_limit)
                ->through(function ($post) {
                    $views = DB::table('post_saved')
                        ->where('post_unique_id', $post->unique_id)
                        ->count() + $post->number_of_likes + $post->number_of_comments;
                    $image = null;
                    if ($post->type == 'books')
                        $image = DB::table('hp_posts_books')->where('post_id', $post->id)->first()?->book_path;
                    if ($post->type == 'images')
                        $image = DB::table('hp_posts_images')->where('post_id', $post->id)->first()?->picture_path;
                    if ($post->type == 'video')
                        $image = DB::table('hp_posts_videos')->where('post_id', $post->id)->first()?->video_url;
                     $author = DB::table('authors')->where('id', $post->author_id)
                     ->select(['id', 'author_name', 'picture_path'])
                     ->first();

                        return [
                        'id' => $post->id,
                        'title' => $post->title,
                        'added_by' => $post->added_by,
                        'description' => $post->description,
                        'type' => $post->type,
                        'number_of_likes' => $post->number_of_likes,
                        'number_of_comments' => $post->number_of_comments,
                        'buys' => $post->buys,
                        'price' => $post->price,
                        'image' => $image,
                        'filter_age_to' => $post->filter_age_to,
                        'filter_age_from' => $post->filter_age_from,
                        'filter_race' => $post->filter_race,
                        'filter_nationality' => $post->filter_nationality,
                        'filter_gender' => $post->filter_gender,
                        'filter_faith' => $post->filter_faith,
                        'category' => $post->category,
                        'created_at' => $post->created_at,
                        'views' => $views,
                        'author' => $author,
                        'user' => DB::table('users')
                            ->select(['id', 'username', 'first_name', 'last_name', 'email'])
                            ->first()
                    ];
                });

            return response()->json(['status' => true, 'data' => $posts]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}
