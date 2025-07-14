<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;

class Document extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'name',
        'summary',
        'document_type_id',
        'created_by',
        'approved_by',
        'issued_at',
        'status',
        'approved_at',
        'rejected_at',
    ];

    protected $dates = ['issued_at'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('document')
            ->logOnly(['name', 'summary', 'document_type_id', 'issued_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
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

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }


    public function employees()
    {
        return $this->belongsToMany(User::class, 'employee_documents', 'document_id', 'employee_id');
    }

    public function hospitals()
    {
        return $this->belongsToMany(Hospital::class, 'hospital_documents');
    }


    public function activities()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
