<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPublicVideoIdToLessons extends Migration
{
    public function up()
    {
        $fields = [
            'public_video_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'video_url'
            ],
        ];

        $this->forge->addColumn('lessons', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('lessons', 'public_video_id');
    }
}


