<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'password',
        'division_id'
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
    ];

    public function base_folders_accesses()
    {
        return $this->hasMany(BaseFolderAccess::class);
    }

    public function basefolder()
    {
        return $this->hasMany(BaseFolder::class, 'owner_id', 'id');
    }
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function access()
    {
        return $this->hasMany(User::class, 'user_id', 'id');
    }

    public function contents()
    {
        return $this->hasMany(Content::class, 'owner_id', 'id');
    }
}
