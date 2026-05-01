<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MenuRecipe extends Model {
    protected $table = 'menu_recipes';
    protected $guarded = ['id'];
    public $timestamps = false;
    public function material() { return $this->belongsTo(RawMaterial::class, 'material_id'); }
}