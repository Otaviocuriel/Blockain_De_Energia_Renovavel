<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'cpf',
        'cnpj',
        'telefone',
        'cep',
        'endereco',
        'cidade',
        'tipo_energia',
        'latitude',
        'longitude',
        'data_nascimento',
        'cargo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int,string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'data_nascimento' => 'date',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    // Accessor para mostrar CPF/CNPJ sem formatação adicional (já armazenado limpo ou formatado)
    public function getDocumentoAttribute(): ?string
    {
        return $this->role === 'company' ? $this->cnpj : $this->cpf;
    }
}