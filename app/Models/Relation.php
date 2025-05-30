<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{
     use HasFactory;

    protected $guarded =[];
    public $timestamps = false;
    protected $primaryKey = 'relations_dtl_id';

    public function documents()
    {
    return $this->hasMany(Document::class, 'ref_id', 'relations_dtl_id');
    }
}
