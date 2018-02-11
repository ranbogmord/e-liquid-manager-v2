<?php
namespace App\Services;

use App\Comment;

class CommentService
{
    public function create(array $data)
    {
        $comment = new Comment($data);
        $comment->save();

        return $comment;
    }
}
