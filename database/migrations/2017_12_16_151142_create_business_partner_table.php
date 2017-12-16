<?php

use App\Database\Migrations\Migration;

class CreateBusinessPartnerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->db->statement('
            CREATE TABLE business_partner (
              id int(11) NOT NULL,
              title varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
              name varchar(35) COLLATE utf8mb4_unicode_ci NOT NULL,
              language varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
              address_id int(11) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ');
        $this->db->statement('
            ALTER TABLE business_partner
              ADD PRIMARY KEY (id),
              ADD KEY address_id (address_id)
        ');
        $this->db->statement('
            ALTER TABLE business_partner
             MODIFY id int(11) NOT NULL AUTO_INCREMENT
        ');
        $this->db->statement('
            ALTER TABLE business_partner
              ADD CONSTRAINT business_partner_ibfk_1 FOREIGN KEY (address_id) REFERENCES address (id)
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->db->statement('DROP TABLE IF EXISTS business_partner');
    }
}
