<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppMenu extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];

    public function children()
    {
        return $this->hasMany(AppMenu::class, 'parent', 'id');
    }

    public function parents()
    {
        return $this->hasOne(AppMenu::class, 'id', 'parent');
    }

    public function childrenActive()
    {
        return $this->hasMany(AppMenu::class, 'parent', 'id')
            ->where('active', 1)
            ->select('id', 'text', 'title', 'url', 'parent');
    }
}
