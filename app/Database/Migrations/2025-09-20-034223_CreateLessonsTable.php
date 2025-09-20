<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLessonsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "id" => [
                "type" => "BIGINT",
                "unsigned" => TRUE,
                "auto_increment" => TRUE
            ],
            "course_id" => [
                "type" => "BIGINT",
                "unsigned" => TRUE,
                "null" => false,
            ],
            "title" => [
                "type" => "VARCHAR",
                "constraint" => "255",
                "null" => false,
            ],
            "video_url" => [
                "type" => "VARCHAR",
                "constraint" => "255",
            ],
            "duration" => [
                "type" => "DECIMAL",
                "constraint" => "10,2",
            ],
            "order" => [
                "type" => "INT",
                "unsigned" => TRUE,
                "null" => false,
            ],
            "created_at" => [
                "type" => "DATETIME",
            ],
            "updated_at" => [
                "type" => "DATETIME",
            ],
        ]);

        $this->forge->addPrimaryKey("id");

        $this->forge->addForeignKey("course_id", "courses", "id", "CASCADE", "CASCADE");

        $this->forge->createTable("lessons");
    }

    public function down()
    {
        $this->forge->dropTable("lessons");
    }
}
