<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $primaryKey = 'doc_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'precedence',
        'file_type',
        'table_name',
        'ref_id',
        'uploaded_file_desc',
        'random_file_name',
        'url',
        'publication',
        'user_file_name',
        'is_disabled',
        'uploaded_by_user',
    ];

    protected $casts = [
        'is_disabled' => 'boolean',
        'precedence' => 'integer',
    ];

    public function relation()
{
    return $this->belongsTo(Relation::class, 'ref_id');
}


}
