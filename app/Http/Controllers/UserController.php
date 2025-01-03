<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    public function index(Request $request)
    {
        try {

            $pagination_limit = (int) $request->paginationLimit ?? 10;

            $users = DB::table('users')
                ->when($request->keyword, function ($query) use ($request) {
                    $pattern = '%' . $request->keyword . '%';
                    $query->where('username', 'like', $pattern)
                        ->orWhere('first_name', 'like', $pattern)
                        ->orWhere('last_name', 'like', $pattern)
                        ->orWhere('email', 'like', $pattern)
                        ->orWhere('country', 'like', $pattern)
                        ->orWhere('phone', 'like', $pattern)
                        ->orWhere('nationality', 'like', $pattern)
                        ->orWhere('state', 'like', $pattern)
                        ->orWhere('city', 'like', $pattern);
                })
                ->orderBy('id', 'desc')
                ->paginate($pagination_limit)
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
