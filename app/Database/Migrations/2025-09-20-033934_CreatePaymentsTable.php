<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "id" => [
                "type" => "VARCHAR",
                "constraint" => "255",
                "null" => false,
            ],
            "learner_id" => [
                "type" => "BIGINT",
                "unsigned" => true,
                "null" => false,
            ],
            "amount" => [
                "type" => "DECIMAL",
                "constraint" => "10,2",
            ],
            "payment_method" => [
                "type" => "VARCHAR",
                "constraint" => "50",
            ],
            "status" => [
                "type" => "ENUM",
                "constraint" => ['pending', 'completed', 'failed', 'refunded'],
            ],
            "transaction_id" => [
                "type" => "VARCHAR",
                "constraint" => "255",
            ],
            "paid_at" => [
                "type" => "DATETIME",
                "null" => false,
            ],
            "created_at" => [
                "type" => "DATETIME",
                "null" => false,
            ]
        ]);

        $this->forge->addPrimaryKey("id");

        $this->forge->addForeignKey("learner_id", "users", "id");

        $this->forge->createTable("payments");

    }

    public function down()
    {
        $this->forge->dropTable("payments");
    }
}
