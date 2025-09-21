<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCategoryTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
           "id" => [
               "type" => "BIGINT",
               "unsigned" => true,
               "auto_increment" => true,
           ],
            "name" => [
                "type" => "VARCHAR",
                "constraint" => "100",
                "unique" => true,
                "null" => false,
            ],
            "description" => [
                "type" => "TEXT",
            ],
            "parent_id" => [
                "type" => "BIGINT",
                "unsigned" => true,
                "null" => true,
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

        $this->forge->addForeignKey("parent_id", "categories", "id", "CASCADE", "CASCADE");

        $this->forge->createTable("categories");
    }

    public function down()
    {
        $this->forge->dropTable("categories");
    }
}
