<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'is_hospital'];

    public function documents()
    {

        return $this->hasMany(Document::class, 'document_type_id'); // Add the foreign key explicitly
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'department_document_types');
    }
}