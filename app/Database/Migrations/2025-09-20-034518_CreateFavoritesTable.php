<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFavoritesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "id" => [
                "type" => "BIGINT",
                "unsigned" => TRUE,
                "auto_increment" => TRUE
            ],
            "user_id" => [
                "type" => "BIGINT",
                "unsigned" => TRUE,
                "null" => false,
            ],
            "course_id" => [
                "type" => "BIGINT",
                "unsigned" => TRUE,
                "null" => false,
            ],
            "added_at" => [
                "type" => "DATETIME",
            ],
        ]);

        $this->forge->addPrimaryKey("id");

        $this->forge->addForeignKey("user_id", "users", "id");
        $this->forge->addForeignKey("course_id", "courses", "id", "CASCADE", "CASCADE");

        $this->forge->createTable("favorites");

    }

    public function down()
    {
        $this->forge->dropTable("favorites");
    }
}
