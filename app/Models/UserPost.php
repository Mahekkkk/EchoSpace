<?php

namespace App\Models;

use CodeIgniter\Model;

class UserPost extends Model
{
    protected $DBGroup          = 'default';
    protected $table = 'user_posts';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id',
        'title',
        'slug',
        'content',
        'subcategory_id',
        'meta_keywords',
        'meta_description',
        'tags',
        'featured_image',
        'visibility',
        'status',
        'created_at',
        'updated_at',
    ];
}
