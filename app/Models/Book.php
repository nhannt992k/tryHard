<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $fillable = ['name','price','quantity','description'];

    public function images(){
        return $this->hasMany(Image::class);
    }
}
