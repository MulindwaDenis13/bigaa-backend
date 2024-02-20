<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $loginUserData = $request->validate([
                'email' => 'required|string|email',
                'password' => 'required'
            ]);

            $user = Admin::where('email', $loginUserData['email'])->first();

            $checkPassword = $this->CheckHashedString($request->password, $user?->password, '2313');

            if (!$user || !$checkPassword) {
                return response()->json([
                    'message' => 'Invalid Credentials'
                ], 401);
            }

            $token = $user->createToken($user->username . '-AuthToken')->plainTextToken;
            return response()->json([
                'status' => true,
                'access_token' => $token,
                'user' => $user->only(['id', 'email', 'username'])
            ]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage(), 'status' => false], 500);
        }
    }


    private function CheckHashedString($string, $HashedString, $Rounds)
    {

        $Algo = '6';

        $CryptAlgo = '$' . $Algo . '$rounds=' . $Rounds . '$';

        $HashedString = $CryptAlgo . $HashedString;

        return crypt($string, $HashedString) == $HashedString;

    }


    public function authenticated()
    {
        try {
            return response()->json('loggedout', 401);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        try {

            // $request->user()->currentAccessToken()->delete();
            DB::table('personal_access_tokens')
                ->where('tokenable_id', $request->user()->id)
                ->delete();
            return response()->json([
                'status' => true,
                'message' => 'Loggedout'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }


}
