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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    // esto es importante(balance si se pondria..no lo necesito, pero en el caso de role no porque es
    //sensible a ser hackeado, por seguridad , en fillable solo lo que es tocable por el usuario )
    protected $fillable = [
        'name',
        'email',
        'password',
        'puntos',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relación: el usuario se relaciona con tareas a traves de los likes
    public function likes()
    {
        return $this->belongsToMany(Tarea::class, 'likes');
    }

    // Relación: Un usuario ha creado muchas tareas
    public function tareas()
    {
        return $this->hasMany(Tarea::class);
    }
}
