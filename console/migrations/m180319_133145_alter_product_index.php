<?php

use yii\db\Schema;
use yii\db\Migration;

class m180319_133145_alter_product_index extends Migration
{
    public function up()
    {
        // $this->createIndex('index_option_id', 'newegg_can_product_variants', 'option_id', true);
        $this->createIndexonNeweggOrderDetail('index_mid', 'newegg_order_detail', 'merchant_id');
        $this->createIndexonNeweggShopDetails('index_mid', 'newegg_shop_detail', 'merchant_id');
        
    }

    public function down()
    {
        echo "m180319_133145_alter_product_index cannot be reverted.\n";

        return false;
    }

    public function createIndex($name, $table, $columns, $unique = true)
    {
        if (is_null($name)) {
        $name = self::formIndexName($table, $columns, $unique ? "unq" : "idx");
    }
    return parent::createIndex($name, $table, $columns, $unique);

    }
    public function createIndexonNeweggOrderDetail($name, $table, $columns, $unique = false)
    {
        if (is_null($name)) {
        $name = self::formIndexName($table, $columns, $unique ? "unq" : "idx");
    }
    return parent::createIndex($name, $table, $columns, $unique);

    }
      public function createIndexonNeweggShopDetails($name, $table, $columns, $unique = false)
        {
            if (is_null($name)) {
            $name = self::formIndexName($table, $columns, $unique ? "unq" : "idx");
        }
        return parent::createIndex($name, $table, $columns, $unique);

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
