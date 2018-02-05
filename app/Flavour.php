<?php

namespace App;

class Flavour extends BaseModel
{
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function liquids()
    {
        return $this->belongsToMany(Liquid::class)->withPivot('percent');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function toArray()
    {
        $data = parent::toArray();
        $data['vendor'] = $this->vendor;

        return $data;
    }
}
