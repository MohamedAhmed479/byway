<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyCategoryTableParentId extends Migration
{
    public function up()
    {
        // Modify parent_id column to allow NULL values
        $this->forge->modifyColumn('categories', [
            'parent_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ]
        ]);
    }

    public function down()
    {
        // Revert parent_id column to not allow NULL values
        $this->forge->modifyColumn('categories', [
            'parent_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ]
        ]);
    }
}
