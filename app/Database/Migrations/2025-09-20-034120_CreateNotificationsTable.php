<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotificationsTable extends Migration
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
            "type" => [
                "type" => "ENUM",
                "constraint" => ["enrollment", "payment_success", "payment_failure", "course_update", "review_added", "withdrawal_request"],
            ],
            "message" => [
                "type" => "TEXT",
                "null" => false,
            ],
            "read_at" => [
                "type" => "DATETIME",
                "null" => true,
            ],
            "created_at" => [
                "type" => "DATETIME",
                "null" => true,
            ],
        ]);

        $this->forge->addPrimaryKey("id");

        $this->forge->addForeignKey("user_id", "users", "id", "CASCADE", "CASCADE");

        $this->forge->createTable("notifications");
    }

    public function down()
    {
        $this->forge->dropTable("notifications");
    }
}
