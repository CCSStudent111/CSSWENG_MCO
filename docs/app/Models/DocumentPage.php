<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentPage extends Model
{
    protected $fillable = ['document_id', 'document_name', 'file_path'];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
