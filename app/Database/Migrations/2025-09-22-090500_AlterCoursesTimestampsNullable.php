<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterCoursesTimestampsNullable extends Migration
{
    public function up()
    {
        // Make created_at, updated_at, deleted_at nullable with default NULL
        $fields = [
            'created_at' => [
                'name'       => 'created_at',
                'type'       => 'DATETIME',
                'null'       => true,
                'default'    => null,
            ],
            'updated_at' => [
                'name'       => 'updated_at',
                'type'       => 'DATETIME',
                'null'       => true,
                'default'    => null,
            ],
            'deleted_at' => [
                'name'       => 'deleted_at',
                'type'       => 'DATETIME',
                'null'       => true,
                'default'    => null,
            ],
        ];

        $this->forge->modifyColumn('courses', $fields);

        // Clean up any zero-date values that may exist
        $this->db->query("UPDATE `courses` SET `deleted_at` = NULL WHERE `deleted_at` = '0000-00-00 00:00:00'");
        $this->db->query("UPDATE `courses` SET `created_at` = NULL WHERE `created_at` = '0000-00-00 00:00:00'");
        $this->db->query("UPDATE `courses` SET `updated_at` = NULL WHERE `updated_at` = '0000-00-00 00:00:00'");
    }

    public function down()
    {
        // Revert to NOT NULL (matches original migration definition)
        $fields = [
            'created_at' => [
                'name'       => 'created_at',
                'type'       => 'DATETIME',
                'null'       => false,
                'default'    => null,
            ],
            'updated_at' => [
                'name'       => 'updated_at',
                'type'       => 'DATETIME',
                'null'       => false,
                'default'    => null,
            ],
            'deleted_at' => [
                'name'       => 'deleted_at',
                'type'       => 'DATETIME',
                'null'       => false,
                'default'    => null,
            ],
        ];

        $this->forge->modifyColumn('courses', $fields);
    }
}


