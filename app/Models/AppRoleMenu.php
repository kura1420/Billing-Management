<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppRoleMenu extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];

    public function children()
    {
        return $this->hasMany(AppRoleMenu::class, 'parent', 'app_menu_id');
    }

    public function childrenActive()
    {
        return $this->hasMany(AppRoleMenu::class, 'parent', 'app_menu_id')
            ->whereActive(1);
    }
}
