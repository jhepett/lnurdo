<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
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
     * @var string[]
     */
    protected $fillable = [
        'name',
        'gender',
        'contact_number',
        'address',
        'unit_id',
        'college_id',
        'user_type_id',
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
    
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    public function user_type()
    {
        return $this->belongsTo(UserType::class);
    }
    public function completed_research()
    {
        return $this->hasMany(CompletedResearch::class, 'owner_id');
    }
    public function fpes_research()
    {
        return $this->hasMany(FpesResearch::class, 'owner_id');
    }
    public function ongoing_research()
    {
        return $this->hasMany(OngoingResearch::class, 'owner_id');
    }
    public function presented_research()
    {
        return $this->hasMany(PresentedResearch::class, 'owner_id');
    }
    public function published_research()
    {
        return $this->hasMany(PublishedResearch::class, 'owner_id');
    }

}
