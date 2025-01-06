<?php

namespace App\Models;

use CodeIgniter\Model;

class EmailVerificationToken extends Model
{
    protected $table = 'email_verification_tokens';
    protected $primaryKey = 'id';
    protected $allowedFields = ['email', 'token', 'created_at'];
    public $timestamps = true;
}
