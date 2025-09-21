<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPublicImageIdToCourses extends Migration
{
    public function up()
    {
        $this->forge->addColumn('courses', [
            'public_image_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'image_url',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('courses', 'public_image_id');
    }
}
