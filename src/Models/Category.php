<?php


namespace Gavan4eg\HouseBank\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_warehouse',
        'parent_id',
        'name',
        'archived'
    ];
}
