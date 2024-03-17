<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * Checks if a given user is the owner of any books.
     */
    public function bookOwner(): HasMany
    {
        return $this->hasMany(Book::class, 'id', 'user_id_owner');
    }

    /**
     * A user can checkout many books.
     */
    public function bookCheckout(): HasMany
    {
        return $this->hasMany(BookCheckout::class);
    }

    /**
     * A user can add many book comments.
     */
    public function bookComments(): HasMany
    {
        return $this->hasMany(BookComments::class);
    }
}
