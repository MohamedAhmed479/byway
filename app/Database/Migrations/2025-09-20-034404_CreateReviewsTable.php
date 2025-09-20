<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReviewsTable extends Migration
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
            "learner_id" => [
                "type" => "BIGINT",
                "unsigned" => TRUE,
                "null" => false,
            ],
            "rating" => [
                "type" => "INT",
                "unsigned" => TRUE,
                "null" => false,
            ],
            "comment" => [
                "type" => "TEXT",
            ],
            "created_at" => [
                "type" => "DATETIME",
            ],
            "updated_at" => [
                "type" => "DATETIME",
            ],
        ]);

        $this->forge->addPrimaryKey("id");

        $this->forge->addForeignKey("learner_id", "users", "id", "CASCADE", "CASCADE");
        $this->forge->addForeignKey("course_id", "courses", "id", "CASCADE", "CASCADE");

        $this->forge->createTable("reviews");
    }

    public function down()
    {
        $this->forge->dropTable("reviews");
    }
}
