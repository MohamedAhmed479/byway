<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePaymentMethodsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
           "id" => [
               "type" => "VARCHAR",
               "constraint" => "255",
               "null" => false,
           ],
            "user_id" => [
                "type" => "BIGINT",
                "unsigned" => true,
                "null" => false,
            ],
            "type" => [
                "type" => "VARCHAR",
                "constraint" => "50",
            ],
            "details" => [
                "type" => "JSON",
                "default" => json_encode([]),
            ],
            "is_default" => [
                "type" => "TINYINT",
                "constraint" => "0",
            ],
            "created_at" => [
                "type" => "DATETIME",
                "null" => false,
            ],
            "updated_at" => [
                "type" => "DATETIME",
                "null" => false,
            ]
        ]);

        $this->forge->addPrimaryKey("id");

        $this->forge->addForeignKey("user_id", "users", "id", "CASCADE", "CASCADE");

        $this->forge->createTable("payment_methods");
    }

    public function down()
    {
        $this->forge->dropTable("payment_methods");
    }
}
