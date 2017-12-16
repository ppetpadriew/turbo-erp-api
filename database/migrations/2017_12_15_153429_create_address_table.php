<?php

use App\Database\Migrations\Migration;

class CreateAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->db->statement('
            CREATE TABLE address (
              id int(11) NOT NULL,
              house_number varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
              detail varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
              street varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
              district varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
              sub_district varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
              province varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
              zip_code varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
              country varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ');
        $this->db->statement('
            ALTER TABLE address
            ADD PRIMARY KEY (id)
        ');
        $this->db->statement('
            ALTER TABLE address
            MODIFY id int(11) NOT NULL AUTO_INCREMENT
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->db->statement('DROP TABLE IF EXISTS address');
    }
}
