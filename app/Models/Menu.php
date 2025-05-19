<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'new_menus';

    protected $primaryKey = 'id_menu';

    public $timestamps = false;

    public function hijos()
    {
        return $this->hasMany(Menu::class, 'padre', 'id_menu')->orderBy('orden');
    }
}
