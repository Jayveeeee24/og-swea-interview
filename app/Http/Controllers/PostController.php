<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Models\Post;

class PostController extends Controller
{
    public function index(): JsonResponse
    {
        //gets all posts in [Post] model along the corresponding user and store it into posts variable 
        //and return it in descending order
        $posts = Post::with('user')->orderBy('id', 'desc')->get();

        // returns the actual json response that accepts the posts variable as a parameter
        return response()->json($posts);
    }
}
