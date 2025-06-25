<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{

    use SoftDeletes;
    protected $fillable = ['name', 'summary', 'document_type_id', 'created_by'];

    public function type()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }


    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'document_tags');
    }

    public function pages()
    {
        return $this->hasMany(DocumentPage::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
