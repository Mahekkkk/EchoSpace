<?php

namespace App\Models;

use CodeIgniter\Model;

class SiteUser extends Model
{
    protected $DBGroup          = "default";
    protected $table            = 'site_users';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['name', 'email', 'password', 'picture', 'bio','picture',  'created_at', 'updated_at'];
    protected $useTimestamps    = true;
    // protected $createdField     = 'created_at';
    // protected $updatedField     = 'updated_at';
    protected $validationRules = [
        'name' => 'required|min_length[3]',
        'email' => 'required|valid_email|is_unique[site_users.email]',
        'password' => 'required|min_length[5]'
    ];
    
}
