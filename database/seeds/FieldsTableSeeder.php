<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FieldsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subscribers = DB::table('subscribers')->pluck('id');
        $types = array('date', 'number', 'string', 'boolean');
        $inserts = array();
        for ($i = 0; $i<250; $i++) {
            $inserts[] = array(
                'subscriber_id' => $subscribers[rand(0, count($subscribers) - 1)],
                'title' => str_random(15),
                'type' => $types[rand(0, 3)]
            );
        }

        DB::table('fields')->insert($inserts);
    }
}
