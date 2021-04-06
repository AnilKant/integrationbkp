<?php

use yii\db\Migration;
use console\components\TableExists;
use yii\db\Schema;


class m171102_064156_insert_product extends Migration
{
    public function safeUp()
    {

    }

    public function safeDown()
    {
        echo "m171102_064156_insert_product cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        if(!TableExists::tableExists('insert_product'))
        {
            $tableOptions = null;
            if ($this->db->driverName === 'mysql') {
                // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
                $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
            }

            $this->createTable('{{%insert_product}}', [
                'id' => $this->integer(11),
                'merchant_id' => $this->integer(),
                'product_count' => $this->string()->notNull(),
                'not_sku'=>$this->string()->notNull(),
                'status' =>$this->string()->notNull(),
                'total_product' =>$this->string()->notNull(),
                'created_at' =>$this->dateTime()->notNull(),
                'total_variant_product' => $this->integer()->notNull(),
            ],$tableOptions);
                $this->addForeignKey('fk-insert_product-merchant_id', 'insert_product', 'merchant_id', 'merchant_db', 'merchant_id', 'CASCADE', 'CASCADE');


        }
        else
        {
            echo "table already exists";
        }

    }

    public function down()
    {
        echo "m171102_064156_insert_product cannot be reverted.\n";

        return false;
    }

}
