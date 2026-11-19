<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Kolom yang boleh diisi melalui User::create().
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
    ];

    // Password tidak pernah ikut dikirim ke response atau view.
    protected $hidden = ['password', 'remember_token'];

    // Laravel otomatis melakukan hashing saat password diisi.
    protected $casts = ['password' => 'hashed'];

    public function panti()
    {
        return $this->hasOne(Panti::class);
    }

    public function donasi()
    {
        return $this->hasMany(Donasi::class);
    }

    public function isRole(string $role): bool
    {
        return $this->role === $role && $this->status === 'aktif';
    }
}
