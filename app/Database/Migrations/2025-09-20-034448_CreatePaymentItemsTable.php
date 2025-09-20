<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePaymentItemsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "id" => [
                "type" => "BIGINT",
                "unsigned" => TRUE,
                "auto_increment" => TRUE
            ],
            "payment_id" => [
                "type" => "VARCHAR",
                "constraint" => "255",
                "null" => false,
            ],
            "course_id" => [
                "type" => "BIGINT",
                "unsigned" => TRUE,
                "null" => false,
            ],
            "price_at_purchase" => [
                "type" => "Decimal",
                "constraint" => "10,2",
            ]
        ]);

        $this->forge->addPrimaryKey("id");

        $this->forge->addForeignKey("payment_id", "payments", "id", "CASCADE", "CASCADE");
        $this->forge->addForeignKey("course_id", "courses", "id", "CASCADE", "CASCADE");

        $this->forge->createTable("payment_items");
    }

    public function down()
    {
        $this->forge->dropTable("payment_items");
    }
}
