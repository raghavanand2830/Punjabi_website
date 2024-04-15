<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Subcategory;
class Category extends Model
{
    protected $fillable = ['name'];
    // Add any other properties or methods here
    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

    public function topsubcategories()
    {
        return $this->hasMany(Subcategory::class);
    }
    
}
