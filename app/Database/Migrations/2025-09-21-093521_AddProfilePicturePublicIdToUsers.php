<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProfilePicturePublicIdToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'profile_picture_public_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'profile_picture',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'profile_picture_public_id');
    }
}
