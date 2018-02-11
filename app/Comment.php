<?php

namespace App;

class Comment extends BaseModel
{
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function liquid()
    {
        return $this->belongsTo(Liquid::class);
    }

    public function toArray()
    {
        $data = parent::toArray();
        $data['author'] = $this->author;

        return $data;
    }
}
