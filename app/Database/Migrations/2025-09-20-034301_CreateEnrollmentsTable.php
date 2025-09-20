<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEnrollmentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "id" => [
                "type" => "BIGINT",
                "unsigned" => TRUE,
                "auto_increment" => TRUE
            ],
            "learner_id" => [
                "type" => "BIGINT",
                "unsigned" => TRUE,
                "null" => false,
            ],
            "course_id" => [
                "type" => "BIGINT",
                "unsigned" => TRUE,
                "null" => false,
            ],
            "enrolled_at" => [
                "type" => "DATETIME",
            ],
            "progress" => [
                "type" => "Decimal",
                "constraint" => "5,2",
                "default" => "0.00",
            ],
            "completed_at" => [
                "type" => "DATETIME",
            ]
        ]);

        $this->forge->addPrimaryKey("id");

        $this->forge->addForeignKey("learner_id", "users", "id", "CASCADE", "CASCADE");
        $this->forge->addForeignKey("course_id", "courses", "id", "CASCADE", "CASCADE");

        $this->forge->createTable("enrollments");
    }

    public function down()
    {
        $this->forge->dropTable("enrollments");
    }
}
