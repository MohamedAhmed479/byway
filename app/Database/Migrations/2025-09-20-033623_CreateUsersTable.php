<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
           "id" => [
               "type" => "BIGINT",
               "unsigned" => true,
               "auto_increment" => true,
           ] ,
            "name" => [
                "type" => "VARCHAR",
                "constraint" => "100",
            ],
            "email" => [
                "type" => "VARCHAR",
                "constraint" => "100",
                "unique" => true,
            ],
            "password" => [
                "type" => "VARCHAR",
                "constraint" => "255",
            ],
            "role" => [
                "type" => "ENUM",
                "constraint" => ["admin", "learner", "instructor"],
            ],
            "email_verified_at" => [
                "type" => "DATETIME",
                "null" => true
            ],
            "profile_picture" => [
                "type" => "VARCHAR",
                "constraint" => "255",
                "null" => true
            ],
            "bio" => [
                "type" => "TEXT",
                "null" => true
            ],
            "social_links" => [
                "type" => "JSON",
                "default" => json_encode([]),
            ],
            "created_at" => [
                "type" => "DATETIME",
                "null" => true
            ],
            "updated_at" => [
                "type" => "DATETIME",
                "null" => true
            ],
            "deleted_at" => [
                "type" => "DATETIME",
                "null" => true
            ]
        ]);

        $this->forge->addPrimaryKey("id");

        $this->forge->createTable("users");
    }

    public function down()
    {
        $this->forge->dropTable("users");
    }
}
