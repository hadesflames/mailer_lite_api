<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class Fields extends Controller
{
    public function getAll(Request $request)
    {
        $fields = DB::table('fields')->get();
        return Response::json($fields, 200);
    }

    public function get(Request $request, $id)
    {
        $field = DB::table('fields')->where('id', $id)->first();
        if ($field == null) {
            return Response::make('', 404);
        }

        return Response::json($field, 200);
    }

    public function create(Request $request)
    {
        $values = $request->all();
        $validator = Validator::make($values, [
            'subscriber_id' => 'required|integer|exists:subscribers,id',
            'title' => 'required|max:200|regex:/^[\pL\s]+$/u',
            'type' => 'required|in:date,number,string,boolean'
        ]);

        if ($validator->fails()) {
            return Response::json(['errors' => $validator->errors()], 400);
        }

        $id = DB::table('fields')->insertGetId([
            'subscriber_id' => $values['subscriber_id'],
            'title' => $values['title'],
            'type' => $values['type'],
        ]);
        $return_val = DB::table('fields')->where('id', $id)->first();
        return Response::json($return_val, 200);
    }

    public function update(Request $request, $id)
    {
        if (DB::table('fields')->where('id', $id)->doesntExist()) {
            return Response::make('', 404);
        }

        $custom_errors = array();
        if ($request->has('id')) {
            $custom_errors['forbidden'][] = 'The id field may not be modified.';
        }
        if ($request->has('subscriber_id')) {
            $custom_errors['forbidden'][] = 'The subscriber field may not be modified.';
        }

        $values = $request->all();
        $validator = Validator::make($values, [
            'title' => 'max:200|regex:/^[\pL\s]+$/u',
            'type' => 'in:date,number,string,boolean'
        ]);

        if (!empty($custom_errors) || $validator->fails()) {
            return Response::json(['errors' => array_merge(
                $custom_errors,
                json_decode(json_encode($validator->errors()), true)
            )], 400);
        }

        DB::table('fields')->where('id', $id)->update($values);
        $return_val = DB::table('fields')->where('id', $id)->first();
        return Response::json($return_val, 200);
    }

    public function delete($id)
    {
        if (DB::table('fields')->where('id', $id)->doesntExist()) {
            return Response::make('', 404);
        }

        DB::table('fields')->where('id', $id)->delete();
        return Response::make('', 200);
    }
}
