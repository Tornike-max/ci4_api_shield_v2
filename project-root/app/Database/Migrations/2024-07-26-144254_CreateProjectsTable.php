<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProjectsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 5,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false
            ],
            'budget' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => false
            ]
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('projects');
    }

    public function down()
    {
        //
    }
}
