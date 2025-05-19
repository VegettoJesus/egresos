<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'tblUsuario'; // <- tu tabla personalizada

    protected $primaryKey = 'CodUsuario'; // <- clave primaria de tu tabla

    public $timestamps = false; // <- si no usas created_at y updated_at

    protected $fillable = [
        'Nombre',
        'Login',
        'password',
        'Estado',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Si usas login por nombre de usuario en vez de email
    public function getAuthIdentifierName()
    {
        return 'Login';
    }

    public function getAuthPassword()
    {
        return $this->password;
    }
}