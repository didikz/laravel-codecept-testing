<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // validate request first
        $this->validate($request, [
            'title' => 'required',
            'content' => 'required',
            'is_published' => 'required|in:y,n'
        ]);

        // store article data
        $article = new Article();
        $article->user_id = $request->user()->id;
        $article->title = $request->get('title');
        $article->content = $request->get('content');
        $article->is_published = $request->get('is_published');
        $article->save();

        // return response with article data
        return response()->json($article);
    }
}
