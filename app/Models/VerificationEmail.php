<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VerificationEmail extends Model
{
    use HasFactory;

    protected $table = 'emailverification';

    protected $fillable = [
        'email',
        'token',
        'created_at',
        'expired_at',
    ];

    public function findValidEmail($email, $token)
    {
        return $this->where('email', $email)
            ->where('token', $token)
            ->first();
    }

    public function findValidToken($email, $token)
    {
        return $this->where('email', $email)
            ->where('token', $token)
            ->where('expired_at', '>=', now())
            ->first();
    }

    public static function findEmailToken($token): ?string
    {
        return self::where('token', $token)->value('email');
    }

    public function saveToken($email, $token): void
    {
        $expireTime = now()->addHours(24);

        $this->updateOrInsert([
            'email' => $email
        ], [
            'token' => $token,
            'created_at' => now(),
            'expired_at' => $expireTime,
        ]);
    }

    public function deleteResetMethodToken($email): void
    {
        $this->where('email', $email)->delete();
    }
}
