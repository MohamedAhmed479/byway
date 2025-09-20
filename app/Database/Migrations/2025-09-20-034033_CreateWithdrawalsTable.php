<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWithdrawalsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "id" => [
                "type" => "VARCHAR",
                "constraint" => "255",
                "null" => false,
            ],
            "instructor_id" => [
                "type" => "BIGINT",
                "unsigned" => true,
                "null" => false,
            ],
            "amount" => [
                "type" => "DECIMAL",
                "constraint" => "10,2",
            ],
            "status" => [
                "type" => "ENUM",
                "constraint" => ['pending', 'completed', 'failed', 'refunded'],
            ],
            "method" => [
                "type" => "VARCHAR",
                "constraint" => "50",
            ],
            "details" => [
                "type" => "JSON",
                "default" => json_encode([]),
            ],
            "requested_at" => [
                "type" => "DATETIME",
            ],
            "processed_at" => [
                "type" => "DATETIME",
            ],
        ]);

        $this->forge->addPrimaryKey("id");

        $this->forge->addForeignKey("instructor_id", "users", "id");

        $this->forge->createTable("withdrawals");

    }

    public function down()
    {
        $this->forge->dropTable("withdrawals");
    }
}
