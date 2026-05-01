<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model {
    protected $table = 'menus';
    protected $guarded = ['id'];
    public function recipes() { return $this->hasMany(MenuRecipe::class, 'menu_id'); }
}