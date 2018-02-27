<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::paginate(5);

        return $this->sendResponse('success', $articles, 'Articles fetched successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'title' => 'required|max:255',
                'content' => 'required',
                'imageUrl' => 'required|url',
                'category_id' => 'required'
            ]);
        } catch (ValidationException $e) {
            return $e->getResponse();
        }

        $article = Article::create([
            'title' => $request->title,
            'content' => $request->content,
            'imageUrl' => $request->imageUrl,
            'category_id' => $request->category_id,
            'slug' => str_slug($request->title) . '-' . time(),
            'user_id' => $request->user()->id
        ]);

        return $this->sendResponse('success', $article->fresh(), 'Article created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = Article::find($id);

        if (!$article) {
            $articleBySlug = Article::where('slug', $id)->first();

            if (!$articleBySlug) {
                return $this->sendResponse('fail', [], 'Article not found.', 404);
            }

            return $this->sendResponse('success', $articleBySlug->fresh(), 'Article found successfully.', 200);            
        }

        return $this->sendResponse('success', $article->fresh(), 'Article found successfully.', 200);   
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $article = Article::find($id);

        if (!$article) {
            return $this->sendResponse('fail', [], 'Article not found.', 404);
        }

        if ($article->user_id !== $request->user()->id) {
            return $this->sendResponse('fail', [], 'Unauthorized.', 401);
        }

        $article->title = $request->title ?: $article->title;
        $article->content = $request->content ?: $article->content;

        $article->imageUrl = $request->imageUrl ?: $article->imageUrl;
        $article->category_id = $request->category_id ?: $article->category_id;
        if ($request->title) {
            $article->slug = str_slug($request->title) . '-' . time();
        }

        $article->save();
        
        return $this->sendResponse('success', $article->fresh(), 'Article updated successfully.');        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $article = Article::find($id);

        if (!$article) {
            return $this->sendResponse('fail', [], 'Article not found.', 404);
        }

        if ($article->user_id !== $request->user()->id) {
            return $this->sendResponse('fail', [], 'Unauthorized.', 401);
        }

        $article->delete();

        return $this->sendResponse('success', [], 'Article deleted successfully.', 200);        
    }
}
