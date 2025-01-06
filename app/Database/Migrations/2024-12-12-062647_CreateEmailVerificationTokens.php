<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmailVerificationTokens extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'email'       => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'token'       => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'created_at'  => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('email_verification_tokens');
    }

    public function down()
    {
        $this->forge->dropTable('email_verification_tokens');
    }
}
