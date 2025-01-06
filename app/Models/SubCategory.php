<?php

namespace App\Models;

use CodeIgniter\Model;

class SubCategory extends Model
{
    protected $DBGroup          = "default";
    protected $table            = 'sub_categories';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'name',
        'slug',
        'parent_cat',
        'description',
        'ordering'

    ];
   } 




