<?php

use yii\db\Schema;
use yii\db\Migration;

class m150516_141507_bugs extends Migration
{
    public function up()
    {
         $this->createTable('bugs', [
            'id' => Schema::TYPE_PK,
            'jumlahBugs' => Schema::TYPE_INTEGER . ' NOT NULL',
            'tanggal' => Schema::TYPE_DATE . ' NOT NULL ',
            'tipeBugs' => Schema::TYPE_STRING . ' NOT NULL',
         ]);
    }

    public function down()
    {
        echo "m150516_141507_bugs cannot be reverted.\n";

        return false;
    }
    
    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }
    
    public function safeDown()
    {
    }
    */
}
