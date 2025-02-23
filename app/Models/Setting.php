<?php

namespace App\Models;

use CodeIgniter\Model;

class Setting extends Model
{

    protected $DBGroup = 'default';
    protected $table = 'settings'; // Table name
    protected $primaryKey = 'id';  // Primary key

    protected $allowedFields = [
        'blog_title',
        'blog_email',
        'blog_phone',
        'blog_meta_keywords',
        'blog_meta_description',
        'blog_logo',
        'blog_favicon',
    ]; // Fields that are mass-assignable


}
