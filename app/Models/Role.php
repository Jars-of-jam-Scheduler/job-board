<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

	protected $primaryKey = 'title';
	public $incrementing = false;
    protected $keyType = 'string';

	protected $fillable = [
		'title',
	];
	
	public $timestamps = false;

	public function users()
	{
		return $this->belongsToMany(User::class, 'role_user', 'role_title', 'user_id');
	}

}
