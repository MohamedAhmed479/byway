<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPublicVideoIdToCourses extends Migration
{
    public function up()
    {
        $this->forge->addColumn('courses', [
            'public_video_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'video_url',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('courses', 'public_video_id');
    }
}
