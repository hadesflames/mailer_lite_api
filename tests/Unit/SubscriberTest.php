<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class SubscriberTest extends TestCase
{
    public function testGet()
    {
        $response = $this->get('api/subscriber');
        $response->assertStatus(200)->assertJson([]);

        $response = $this->get('api/subscriber/1');
        $response->assertStatus(200)->assertJson(['id' => 1]);
    }

    public function testPut()
    {
        $response = $this->json('PUT', 'api/subscriber/1', [
            'email' => 'new_mail@live.com',
            'name' => 'Aldo'
        ]);
        $response->assertStatus(200)->assertJson(['id' => 1, 'email' => 'new_mail@live.com', 'name' => 'Aldo']);
    }

    public function testActivate()
    {
        DB::table('subscribers')->where('id', 1)->update(['state' => 'unconfirmed']);
        $this->assertDatabaseHas('subscribers', [
            'id' => 1,
            'state' => 'unconfirmed'
        ]);
        $response = $this->put('api/subscriber/activate/1');
        $response->assertStatus(200);
        $this->assertDatabaseHas('subscribers', [
            'id' => 1,
            'state' => 'active'
        ]);
    }

    public function testCreate()
    {
        $last_val = DB::table('subscribers')->orderBy('id', 'desc')->first();
        $response = $this->json('POST', 'api/subscriber', [
            'owner_id' => 1,
            'email' => 'some_mail@live.com',
            'name' => 'Aldo',
            'surname' => 'Barreras',
            'state' => 'unconfirmed'
        ]);
        $response->assertStatus(200)->assertJson([
            'owner_id' => 1,
            'email' => 'some_mail@live.com',
            'name' => 'Aldo',
            'surname' => 'Barreras',
            'state' => 'unconfirmed'
        ]);

        $new_last_val = DB::table('subscribers')->orderBy('id', 'desc')->first();
        $this->assertTrue($new_last_val->id > $last_val->id);
        DB::table('subscribers')->where('id', $new_last_val->id)->delete();
    }

    public function testDelete()
    {
        DB::table('subscribers')->insert([
            'owner_id' => 1,
            'email' => 'some_mail@live.com',
            'name' => 'Aldo',
            'surname' => 'Barreras',
            'state' => 'unconfirmed'
        ]);
        $last_val = DB::table('subscribers')->orderBy('id', 'desc')->first();
        $id = $last_val->id;

        $response = $this->delete('api/subscriber/' . $id);
        $response->assertStatus(200);

        $new_last_val = DB::table('subscribers')->orderBy('id', 'desc')->first();
        $this->assertTrue($new_last_val->id !== $id);
    }
}
