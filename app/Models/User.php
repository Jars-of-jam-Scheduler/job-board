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
    protected $fillable = [
        'name',
        'email',
        'password',
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

	public function jobs()
	{
		return $this->belongsToMany(Job::class)->using(JobUser::class);
	}

	public function roles()
	{
		return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_title');
	}

	public function hasAppliedFor(int $job_id) : bool
	{
		return $this->jobs()->where('job_id', $job_id)->exists();
	}

	public function hasRole(string $role_title) : bool
	{
		return $this->roles()->where('title', $role_title)->exists();
	}

	protected function name(): Attribute
	{
		return Attribute::make(
			get: fn ($value) => ucfirst($value)
		);
	}
}
