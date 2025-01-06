<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePostsTable extends Migration
{
    public function up()
{
    $this->forge->addField([
        'id' => [
            'type'           => 'INT',
            'unsigned'       => true,
            'auto_increment' => true,
        ],
        'author_id' => [
            'type'       => 'INT',
            'constraint' => 11,
            'unsigned'   => true,
        ],
        'category_id' => [
            'type'       => 'INT',
            'constraint' => 11,
            'unsigned'   => true,
        ],
        'title' => [
            'type'       => 'VARCHAR',
            'constraint' => 255,
        ],
        'slug' => [
            'type'       => 'VARCHAR',
            'constraint' => 255,
        ],
        'content' => [
            'type' => 'TEXT',
        ],
        'featured_image' => [
            'type'       => 'VARCHAR',
            'constraint' => 255,
        ],
        'tags' => [
            'type' => 'TEXT',
            'null' => true,
        ],
        'meta_keywords' => [
            'type'       => 'VARCHAR',
            'constraint' => 255,
            'null'       => true,
        ],
        'meta_description' => [
            'type'       => 'VARCHAR',
            'constraint' => 255,
            'null'       => true,
        ],
        'visibility' => [
            'type'       => 'INT',
            'constraint' => 11,
            'default'    => 1, // 1 = Public, 0 = Private
        ],
        'created_at' => [
            'type'    => 'DATETIME',
            'null'    => true, // Allow null to avoid syntax errors
        ],
        'updated_at' => [
            'type'    => 'DATETIME',
            'null'    => true, // Allow null to avoid syntax errors
        ],
    ]);

    $this->forge->addKey('id', true); // Primary key
    $this->forge->createTable('posts');
}


    public function down()
    {
        $this->forge->dropTable('posts');
    }
}
