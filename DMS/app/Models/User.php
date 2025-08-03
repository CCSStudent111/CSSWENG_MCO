<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'department_id',
        'is_admin',
        'role',  // Remove is_manager from fillable
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            // Remove is_manager cast
        ];
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    public function isManager(): bool
    {
        return $this->attributes['role'] === 'Manager';  // Check the actual role field
    }

    public function isEmployee(): bool
    {
        return !$this->is_admin && $this->attributes['role'] !== 'Manager';
    }

    public function getRoleDisplayAttribute(): string
    {
        if ($this->is_admin) {
            return 'Administrator';
        } elseif ($this->attributes['role'] === 'Manager') {
            return 'Manager';
        } else {
            return 'Employee';
        }
    }
}
