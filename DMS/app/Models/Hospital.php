<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Hospital extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = ['name', 'branch'];
    protected static $recordEvents = ['created', 'updated', 'deleted', 'restored'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'branch'])
            ->useLogName('hospital')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Hospital was {$eventName}");
    }

    public function tapActivity(\Spatie\Activitylog\Models\Activity $activity, string $eventName)
{
    $activity->causer_id = auth()->id();
    $activity->causer_type = auth()->check() ? get_class(auth()->user()) : null;
}

    public function documents()
    {
        return $this->belongsToMany(Document::class, 'hospital_documents');
    }
}