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
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('only-latest-version', function (Builder $builder) {
            $builder->whereNull('next_version_id');
        });

        static::addGlobalScope('for-user', function (Builder $builder) {
            $builder->where('author_id', auth()->id());
        });
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
