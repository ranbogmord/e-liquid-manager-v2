<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Liquid
 * @package App
 */
class Liquid extends BaseModel
{
    /**
     * @param Builder $query
     * @param $id
     */
    public function scopeForUser($query, $id)
    {
        return $query->where('author_id', $id);
    }

    /**
     * @return BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * @return BelongsToMany
     */
    public function flavours()
    {
        return $this->belongsToMany(Flavour::class)->withPivot(['percent']);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'desc');
    }

    public function jsonSerialize()
    {
        $data = parent::jsonSerialize();

        $flavours = $this->flavours->map(function ($f) {
            $data = $f->toArray();
            $data['percent'] = $f->pivot->percent;
            unset($data['pivot']);

            return $data;
        });

        return array_merge($data, [
            'flavours' => $flavours
        ]);
    }
}
