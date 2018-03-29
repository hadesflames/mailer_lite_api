<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscribersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $state = array('active', 'unsubscribed', 'junk', 'bounced', 'unconfirmed');
        $emails = array('@gmail.com', '@live.com', '@yahoo.com');
        $inserts = array();
        for ($i = 0; $i<100; $i++) {
            $inserts[] = array(
                'owner_id' => 1,
                'email' => str_random(10) . $emails[rand(0, 2)],
                'name' => str_random(10),
                'surname' => str_random(10),
                'state' => $state[rand(0, 4)]
            );
        }

        DB::table('subscribers')->insert($inserts);
    }
}
