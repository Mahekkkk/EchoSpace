<?php

namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{
    protected $table = 'users';          // Specify the table name
    protected $primaryKey = 'id';        // Define the primary key

    protected $allowedFields = [
        'username',
        'name',
        'email',
        'password',
        'picture',
        'bio',
        'created_at',
        'updated_at'
    ];

    // Enable automatic handling of created_at and updated_at timestamps
    //protected $useTimestamps = true;
   // protected $createdField  = 'created_at';
   // protected $updatedField  = 'updated_at';

    // Optionally, set validation rules for user fields
   // protected $validationRules = [
   //     'username' => 'required|min_length[3]|max_length[100]',
   //     'email'    => 'required|valid_email|is_unique[users.email]',
   //     'password' => 'required|min_length[8]',
   // ];
}
