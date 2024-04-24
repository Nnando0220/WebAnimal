<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, $email)
 * @method static updateOrInsert(array $array, array $array1)
 */
class PasswordResetToken extends Model
{
    use HasFactory;

    protected $table = 'password_reset_tokens';
    protected $primaryKey = 'email';

    protected $fillable = [
        'email',
        'token',
        'created_at',
        'expired_at',
    ];

    public function saveToken($email, $token): void
    {

        $expireTime = now()->addHours(24);

        self::updateOrInsert([
                'email' => $email
            ], [
                'token' => $token,
                'created_at' => now(),
                'expired_at' => $expireTime,
            ]
        );
    }

    public function findValidEmail($email, $token)
    {
        return self::where('email', $email)
            ->where('token', $token)
            ->first();
    }

    public function findValidToken($email, $token): ?PasswordResetToken
    {
        return self::where('email', $email)
            ->where('token', $token)
            ->where('expired_at', '>=', now())
            ->first();
    }

    public function deleteResetPasswordToken($email): void
    {
        self::where('email', $email)->delete();
    }
}
