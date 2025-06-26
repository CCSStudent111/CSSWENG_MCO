<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'summary',
        'document_type_id',
        'created_by',
        'issued_at',
    ];

    protected $dates = ['issued_at'];

    public function type()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

    public function pages()
    {
        return $this->hasMany(DocumentPage::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'document_tags');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function employees()
    {
        return $this->belongsToMany(User::class, 'employee_documents', 'document_id', 'employee_id');
    }

    public function hospitals()
    {
        return $this->belongsToMany(Hospital::class, 'hospital_documents');
    }
}