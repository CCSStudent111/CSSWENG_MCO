<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'original_name',
        'file_path',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
