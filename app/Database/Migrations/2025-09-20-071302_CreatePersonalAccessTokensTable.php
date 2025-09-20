<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePersonalAccessTokensTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tokenable_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                "default" => "App\\Models\\User",
                'comment'    => 'Model type (usually App\\Models\\User)',
            ],
            'tokenable_id' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
                'comment'    => 'User ID',
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'comment'    => 'Token name/description',
            ],
            'token' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
                'unique'     => true,
                'comment'    => 'Hashed token',
            ],
            'abilities' => [
                'type'    => 'TEXT',
                'null'    => true,
                'comment' => 'JSON array of abilities/permissions',
            ],
            'last_used_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Last time token was used',
            ],
            'expires_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Token expiration time',
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

        $this->forge->addKey(['tokenable_type', 'tokenable_id']);
        $this->forge->addKey('last_used_at');
        $this->forge->addKey('expires_at');

        $this->forge->createTable('personal_access_tokens');

        $this->forge->addForeignKey('tokenable_id', 'users', 'id', 'CASCADE', 'CASCADE');


    }

    public function down()
    {
        $this->forge->dropTable('personal_access_tokens');
    }
}
