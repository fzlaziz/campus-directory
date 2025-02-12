<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsComment;
use Illuminate\Http\Request;

class NewsCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(News $news)
    {
        if(!$news) {
            return response()->json([
                'message' => 'News not found',
            ], 404);
        }

        $newsComments = $news->news_comments()->with('user')->get();
        $newsComments = $news->news_comments()
            ->select('id', 'text', 'news_id', 'user_id', 'created_at')
            ->with(['user:id,name,email,profile_image'])
            ->get();

        return response()->json($newsComments, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, News $news)
    {
        if(!$news) {
            return response()->json([
                'message' => 'News not found',
            ], 404);
        }
        $request->validate([
            'text' => 'required|string',
        ]);

        $newsComment = new NewsComment();
        $newsComment->text = $request->text;
        $newsComment->news_id = $news->id;
        $newsComment->user_id = $request->user()->id;
        $newsComment->save();

        return response()->json([
            'message' => 'Comment created successfully',
            'data' => $newsComment,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(NewsComment $newsComment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NewsComment $newsComment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NewsComment $newsComment)
    {
        try {
            if ($newsComment->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk menghapus komentar ini.',
                ], 403);
            }

            $newsComment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Komentar berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus komentar.',
            ], 500);
        }
    }
}
