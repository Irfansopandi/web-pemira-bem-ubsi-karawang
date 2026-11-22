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
        'class_name',
        'semester',
        'asal_kampus',
        'email_kampus',
        'is_active',
    ];

    protected $hidden = [
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'semester' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Mahasiswa login murni menggunakan NIM & verifikasi buku tamu TPS (tanpa password).
     */
    public function getAuthPasswordName(): ?string
    {
        return null;
    }

    /**
     * Alias accessor & mutator agar prodi & kelas mudah diakses secara intuitif
     */
    public function getKelasAttribute(): ?string
    {
        return $this->class_name;
    }

    public function setKelasAttribute($value): void
    {
        $this->attributes['class_name'] = $value;
    }

    public function getProdiAttribute(): ?string
    {
        return $this->study_program;
    }

    public function setProdiAttribute($value): void
    {
        $this->attributes['study_program'] = $value;
    }

    /**
     * Relasi ke status partisipasi pemilihan.
     */
    public function participations()
    {
        return $this->hasMany(VoterParticipation::class, 'student_nim', 'nim');
    }
}
