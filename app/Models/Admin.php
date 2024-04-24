<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

/**
 * @method static create(array $array)
 */
class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $guard = 'admin';

    protected $table = 'admin';

    protected $fillable = [
        'username',
        'password',
        'user_id',
    ];

    protected $hidden = [
        'password',
    ];

    public static function createAdmin(array $array)
    {
        return self::create([
            'username' => $array['username'],
            'password' => Hash::make($array['password']),
            'user_id' => $array['user_id'],
        ]);
    }

    public function getAuthPassword()
    {
        return $this->password;
    }
}
