<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Directory extends Model
{
    use HasFactory;

    protected $table = 'directory';

    protected $fillable = ['id', 'code', 'name', 'parent_id'];

    public function childs() {
        return $this->hasMany(Directory::class,'parent_id','id') ;
    }
}
