<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Exceptions\InternalServerErrorException;
use App\Liquid;
use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    private $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Liquid $liquid)
    {
        return response()->json($liquid->comments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Liquid $liquid)
    {
        $data = $this->validate($request, [
            'comment' => 'required'
        ]);

        $data['liquid_id'] = $liquid->id;
        $data['author_id'] = auth()->id();

        try {
            $comment = $this->commentService->create($data);
        } catch (\Exception $ex) {
            throw new InternalServerErrorException($ex->getMessage());
        }

        return response()->json($comment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Liquid $liquid, Comment $comment)
    {
        try {
            $comment->delete();
        } catch (\Exception $ex) {
            throw new InternalServerErrorException($ex->getMessage());
        }

        return response(null, 204);
    }
}
