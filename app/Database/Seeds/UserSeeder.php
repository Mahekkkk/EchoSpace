<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username' => 'Admin',
                'email'    => 'Amanadmin@gmail.com',
                'password' => password_hash('AmanAdmin0', PASSWORD_DEFAULT),
                
            ],
            [
                'username' => 'Mahek',
                'email'    => 'MahekUser@gmail.com',
                'password' => password_hash('MahekUser0', PASSWORD_DEFAULT),
                
            ],
        ];

        // Insert each user data into the users table
        foreach ($data as $user) {
            $this->db->table('users')->insert($user);
        }
    }
}

