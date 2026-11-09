<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    use Notifiable;

    protected $table = 'students';

    /**
     * Primary key is NIM (string, non-incrementing).
     */
    protected $primaryKey = 'nim';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'nim',
        'name',
        'study_program',
        'class_group',
        'academic_year',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Relasi ke status partisipasi pemilihan.
     */
    public function participations()
    {
        return $this->hasMany(VoterParticipation::class, 'student_nim', 'nim');
    }
}
