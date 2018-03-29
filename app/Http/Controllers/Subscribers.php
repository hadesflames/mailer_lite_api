<?php

namespace App\Http\Controllers;

use App\Rules\ValidDomain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class Subscribers extends Controller
{
    public function getAll(Request $request)
    {
        $subscribers = DB::table('subscribers')->get();
        return Response::json($subscribers, 200);
    }

    public function get(Request $request, $id)
    {
        $subscriber = DB::table('subscribers')->where('id', $id)->first();
        if ($subscriber == null) {
            return Response::make('', 404);
        }

        return Response::json($subscriber, 200);
    }

    public function create(Request $request)
    {
        $values = $request->all();
        $validator = Validator::make($values, [
            // Normally, this would also validate that the owner id exists in owner table with exists:owners,id
            'owner_id' => 'required|integer',
            'email' => ['required', 'max:254', 'email', new ValidDomain],
            'name' => 'required|max:50|alpha',
            'surname' => 'required|max:50|alpha',
            'state' => 'required|in:unsubscribed,junk,bounced,unconfirmed'
        ]);

        if ($validator->fails()) {
            return Response::json(['errors' => $validator->errors()], 400);
        }

        if (DB::table('subscribers')->where(
            [
                ['owner_id', $values['owner_id']],
                ['email', $values['email']]
            ]
        )->exists()) {
            return Response::json(['errors' => ['forbidden' =>
                ['An email with the same owner id already exists']]], 400);
        }

        $id = DB::table('subscribers')->insertGetId([
            'owner_id' => $values['owner_id'],
            'email' => $values['email'],
            'name' => $values['name'],
            'surname' => $values['surname'],
            'state' => $values['state']
        ]);
        $return_val = DB::table('subscribers')->where('id', $id)->first();
        return Response::json($return_val, 200);
    }

    public function update(Request $request, $id)
    {
        if (DB::table('subscribers')->where('id', $id)->doesntExist()) {
            return Response::make('', 404);
        }

        $current_val = DB::table('subscribers')->where('id', $id)->first();
        $custom_errors = array();
        if ($request->has('id')) {
            $custom_errors['forbidden'][] = 'The id field may not be modified.';
        }
        if ($request->has('owner_id')) {
            $custom_errors['forbidden'][] = 'The owner field may not be modified.';
        }

        $values = $request->all();
        $validator = Validator::make($values, [
            'email' => ['max:254', 'email', new ValidDomain],
            'name' => 'max:50|alpha',
            'surname' => 'max:50|alpha',
            'state' => 'in:unsubscribed,junk,bounced,unconfirmed'
        ]);

        if (!empty($custom_errors) || $validator->fails()) {
            return Response::json(['errors' => array_merge(
                $custom_errors,
                json_decode(json_encode($validator->errors()), true)
            )], 400);
        }

        if ($request->has('email')) {
            if (DB::table('subscribers')->where(
                [
                    ['owner_id', $current_val->owner_id],
                    ['email', $values['email']]
                ]
            )->exists() && $current_val->email != $values['email']) {
                return Response::json(['errors' => ['forbidden' =>
                    ['An email with the same owner id already exists']]], 400);
            }
        }

        DB::table('subscribers')->where('id', $id)->update($values);
        $return_val = DB::table('subscribers')->where('id', $id)->first();
        return Response::json($return_val, 200);
    }

    public function activate($id)
    {
        if (DB::table('subscribers')->where('id', $id)->doesntExist()) {
            return Response::make('', 404);
        }

        DB::table('subscribers')->where('id', $id)->update(['state' => 'active']);
    }

    public function delete($id)
    {
        if (DB::table('subscribers')->where('id', $id)->doesntExist()) {
            return Response::make('', 404);
        }

        DB::table('subscribers')->where('id', $id)->delete();
        return Response::make('', 200);
    }
}
