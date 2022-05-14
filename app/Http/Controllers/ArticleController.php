<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Resources\Article as ArticleResource;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get articles
        $articles = Article::orderBy('created_at', 'DESC')->paginate(5);

        //return collection of articles as a resource
        return ArticleResource::collection($articles);
    }

    public function store(Request $request)
    {
        //
        $article = $request->isMethod('put') ? Article::findOrFail($request->article_id) : new Article;

        $article->id = $request->input('article_id');
        $article->title = $request->input('title');
        $article->body = $request->input('body');

        if($article->save()){
            return new ArticleResource($article);
        }
    }

    public function show($id)
    {
        //get article
        $article = Article::findOrfail($id);

        //return single article as a resource
        return new ArticleResource($article);

    }
    public function destroy($id)
    {
        //get article
        $article = Article::findOrfail($id);

        if($article->delete()){
            return new ArticleResource($article);
        }
    }
}
