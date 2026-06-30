<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCoordinateToKuliner extends Migration
{
    public function up()
    {
        $this->forge->addColumn('kuliner', [

            'latitude' => [
                'type' => 'DECIMAL',
                'constraint' => '10,8',
                'null' => true
            ],

            'longitude' => [
                'type' => 'DECIMAL',
                'constraint' => '11,8',
                'null' => true
            ]

        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('kuliner', 'latitude');
        $this->forge->dropColumn('kuliner', 'longitude');
    }
}