<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

/**
 * @method static where(string $string, mixed $email)
 * @method static create(array $array)
 * @method static whereNull(string $string)
 * @method static find($identifier)
 */
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
        'username',
        'email',
        'password',
        'photo_url',
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

    public static function verifyEmail($user): bool
    {
        return $user->email_verified_at !== null;
    }

    public static function getUsernameById($id)
    {
        $user = self::findUser($id);
        return $user->username;
    }

    public static function getPhotoUrlUserById($id)
    {
        $user = self::findUser($id);
        return $user->photo_url;
    }

    public static function createUser(array $array)
    {
        return self::create([
           'name' => $array['name'],
           'username' => $array['username'],
           'email' => $array['email'],
           'password' => Hash::make($array['password']),
           'photo_url' => $array['photo_url'],
        ]);
    }

    public static function updateUserPassword($email, $newPassword): void
    {
        $user = self::findUser($email);

        if ($user) {
            $user->password = Hash::make($newPassword);
            $user->save();
        }
    }

    public static function changeTimeVerifyEmail($email): void
    {
        $user = self::findUser($email);

        if ($user) {
            $user->email_verified_at = now();
            $user->save();
        }
    }

    public static function findUsedUsername($username): bool
    {
        return !self::where('username', $username)->exists();
    }

    public static function findUserByUsername($username): ?User
    {
        return self::where('username', $username)->first();
    }

    public static function modificationEmail($id, $email_novo): void
    {
        $user = self::findUser($id);

        if ($user){
            $user->email = $email_novo;
            $user->save();
        }
    }

    public static function findAllUsers(): Collection
    {
        return self::all();
    }

    public static function editUser($data, $id, $imagePath): bool
    {
        $user = self::findUser($id);
        $user->name = $data['nome_completo'.$id] ?? $user->name;
        $user->username = $data['nome_usuario'.$id] ?? $user->username;
        $user->email = $data['email_editavel'.$id] ?? $user->email;
        $user->photo_url = $imagePath ?? $user->photo_url;

        if (isset($data['senha_editavel'.$id])) {
            $user->password = Hash::make($data['senha_editavel'.$id]);
        }

        return $user->save();
    }

    public static function findUser($identifier)
    {
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            return self::where('email', $identifier)->first();
        }

        return self::find($identifier);
    }
}
