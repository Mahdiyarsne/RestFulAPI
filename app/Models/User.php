<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Transformers\UserTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use  HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    const VERIFIED_USER = '1';
    const UNVERIFIED_USER = '0';

    const ADMIN_USER = 'true';
    const REGULAR_USER = 'false';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    public $transformer = UserTransformer::class;
    protected $dates = ['deleted_at'];
    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'verified',
        'verification_token',
        'admin'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token'
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
        ];
    }


    public function setNameAttribute($name)
    {
        $this->attributes['name'] = strtolower($name);
    }


    public function getNameAttribute($name)
    {

        return ucwords($name);
    }


    public function setEmailAttribute($email)
    {
        $this->attributes['email'] = strtolower($email);
    }


    public function isVerified()
    {
        return $this->verified == User::VERIFIED_USER;
    }



    public function isAdmin()
    {
        return $this->admin == User::VERIFIED_USER;
    }


    public static function generateVerifiactionCode()
    {
        return Str::random(40);
    }
}
