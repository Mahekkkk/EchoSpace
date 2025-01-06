<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserPostsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'slug' => [
            'type' => 'VARCHAR',
            'constraint' => 255,
            'null' => true,
            'after' => 'title',
        ],
            'content' => [
                'type' => 'TEXT',
            ],
            'subcategory_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'meta_keywords' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'meta_description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'tags' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'featured_image' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'visibility' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1, // 1 = Public, 2 = Private
            ],
            'status' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0, // 0 = Pending, 1 = Approved, 2 = Rejected
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('subcategory_id', 'sub_categories', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_posts');
    }

    public function down()
    {
        $this->forge->dropTable('user_posts');
    }
}
