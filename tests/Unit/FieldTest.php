<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class FieldTest extends TestCase
{
    public function testGet()
    {
        $response = $this->get('api/field');
        $response->assertStatus(200)->assertJson([]);

        $response = $this->get('api/field/1');
        $response->assertStatus(200)->assertJson(['id' => 1]);
    }

    public function testPut()
    {
        $response = $this->json('PUT', 'api/field/1', [
            'title' => 'new title',
        ]);
        $response->assertStatus(200)->assertJson(['id' => 1, 'title' => 'new title']);
    }

    public function testCreate()
    {
        $last_val = DB::table('fields')->orderBy('id', 'desc')->first();
        $response = $this->json('POST', 'api/field', [
            'subscriber_id' => 1,
            'title' => 'new field test',
            'type' => 'string',
        ]);
        $response->assertStatus(200)->assertJson([
            'subscriber_id' => 1,
            'title' => 'new field test',
            'type' => 'string',
        ]);

        $new_last_val = DB::table('fields')->orderBy('id', 'desc')->first();
        $this->assertTrue($new_last_val->id > $last_val->id);
        DB::table('subscribers')->where('id', $new_last_val->id)->delete();
    }

    public function testDelete()
    {
        DB::table('fields')->insert([
            'subscriber_id' => 1,
            'title' => 'new field test',
            'type' => 'string',
        ]);
        $last_val = DB::table('fields')->orderBy('id', 'desc')->first();
        $id = $last_val->id;

        $response = $this->delete('api/field/' . $id);
        $response->assertStatus(200);

        $new_last_val = DB::table('fields')->orderBy('id', 'desc')->first();
        $this->assertTrue($new_last_val->id !== $id);
    }
}
