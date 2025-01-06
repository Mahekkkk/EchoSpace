<?php

namespace App\Models;

use CodeIgniter\Model;

class Blog extends Model
{
    protected $table = 'blogs'; // The name of your blogs table
    protected $primaryKey = 'id'; // The primary key of the table
    protected $allowedFields = [
        'user_id',
        'title',
        'content',
        'approved',
        'created_at',
        'updated_at',
    ]; // Fields that can be inserted/updated
    protected $useTimestamps = true; // Automatically manage created_at and updated_at
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
