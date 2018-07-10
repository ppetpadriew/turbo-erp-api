<?php

use App\Database\Migrations\Migration;

class CleanUpFirstFreeNumber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->db->statement('DROP TABLE number_group;');

        $this->db->statement('
            ALTER TABLE first_free_number
            DROP COLUMN number_group_id,
            DROP COLUMN first_free_number,
            ADD COLUMN `length` INT(11) NOT NULL AFTER `description`,
            ADD COLUMN last_used_number INT(11) NOT NULL AFTER `length`,
            DROP INDEX combined_1,
            ADD INDEX series (series ASC)
        ');
    }
}
