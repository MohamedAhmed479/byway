<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLessonCompletionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "id" => [
                "type" => "BIGINT",
                "unsigned" => TRUE,
                "auto_increment" => TRUE
            ],
            "enrollment_id" => [
                "type" => "BIGINT",
                "unsigned" => TRUE,
                "null" => false,
            ],
            "lesson_id" => [
                "type" => "BIGINT",
                "unsigned" => TRUE,
                "null" => false,
            ],
            "completed_at" => [
                "type" => "DATETIME",
            ]
        ]);

        $this->forge->addPrimaryKey("id");

        $this->forge->addForeignKey("enrollment_id", "enrollments", "id", "CASCADE", "CASCADE");
        $this->forge->addForeignKey("lesson_id", "lessons", "id", "CASCADE", "CASCADE");

        $this->forge->createTable("lesson_completions");
    }

    public function down()
    {
        $this->forge->dropTable("lesson_completions");
    }
}
