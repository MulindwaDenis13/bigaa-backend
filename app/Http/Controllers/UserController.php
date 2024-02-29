<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;


use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        // $users = User::all()->paginate(10);
        // return response()->json($users);
            // Paginate the users with 10 users per page (you can adjust this value as needed)
    $users = DB::table('users')->paginate(10);
    
    // Return the paginated users as JSON response
    return response()->json($users);
    }
}
