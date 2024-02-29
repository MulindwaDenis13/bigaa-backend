<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    public function index()
    {
        try {

            $users = DB::table('users')
                ->orderBy('id', 'desc')
                ->paginate(10)
                ->through(function ($user) {
                    return [
                        'id' => $user->id,
                        'email' => $user->email,
                        'username' => $user->username,
                        'picture_path' => $user->picture_path,
                        'birthday' => $user->birthday,
                        'status' => $user->status,
                        'phone' => $user->phone,
                        'registered_from' => $user->registered_from,
                        'gender' => $user->gender,
                        'race' => $user->race,
                        'tribe' => $user->tribe,
                        'faith' => $user->faith,
                        'nationality' => $user->nationality,
                        'country' => $user->country,
                        'state' => $user->state,
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'address' => $user->address,
                        'city' => $user->city
                    ];
                });

            return response()->json(['status' => true, 'data' => $users]);

        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }

    }
}
