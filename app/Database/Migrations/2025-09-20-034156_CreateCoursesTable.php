<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCoursesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "id" => [
                "type" => "BIGINT",
                "unsigned" => TRUE,
                "auto_increment" => TRUE
            ],
            "title" => [
                "type" => "VARCHAR",
                "constraint" => "255",
                "null" => false,
            ],
            "description" => [
                "type" => "TEXT",
                "null" => false,
            ],
            "image_url" => [
                "type" => "VARCHAR",
                "constraint" => "255",
            ],
            "video_url" => [
                "type" => "VARCHAR",
                "constraint" => "255",
            ],
            "price" => [
                "type" => "Decimal",
                "constraint" => "10,2",
                "default" => "0.00",
            ],
            "instructor_id" => [
                "type" => "BIGINT",
                "unsigned" => TRUE,
                "null" => false,
            ],
            "category_id" => [
                "type" => "BIGINT",
                "unsigned" => TRUE,
                "null" => false,
            ],
            "status" => [
                "type" => "ENUM",
                "constraint" => ["draft", "pending_approval", "published", "archived"],
            ],
            "thumbnail" => [
                "type" => "VARCHAR",
                "constraint" => "255",
            ],
            "duration" => [
                "type" => "DECIMAL",
                "constraint" => "10,2",
                "default" => "0.00",
            ],
            "created_at" => [
                "type" => "DATETIME",
            ],
            "updated_at" => [
                "type" => "DATETIME",
            ],
            "deleted_at" => [
                "type" => "DATETIME",
            ]
        ]);

        $this->forge->addPrimaryKey("id");

        $this->forge->addForeignKey("instructor_id", "users", "id");
        $this->forge->addForeignKey("category_id", "categories", "id");

        $this->forge->createTable("courses");
    }

    public function down()
    {
        $this->forge->dropTable("courses");
    }
}
