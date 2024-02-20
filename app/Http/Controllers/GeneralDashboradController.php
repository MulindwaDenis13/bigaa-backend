<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GeneralDashboradController extends Controller
{

    public function card_counters(Request $request)
    {
        try {

            $card_counters = [

                'authors' => $this->generate_query_counts(DB::table('authors'), $request, 'created_at'),

                'subscribers' => $this->generate_query_counts(DB::table('users'), $request, 'created_at'),

                'likes' => $this->generate_query_counts(DB::table('post_likes'), $request, 'date_time'),

                'comments' => $this->generate_query_counts(DB::table('post_comments'), $request, 'date_time'),

                'tags' => $this->generate_query_counts(DB::table('post_saved'), $request, 'date_time')

            ];
            return response()->json(['status' => true, 'data' => $card_counters,]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }


    public function user_gender(Request $request)
    {
        try {
            $gender = [

                'male' => $this->generate_query_counts(DB::table('users')->where('gender', 'Male'), $request, 'created_at'),

                'female' => $this->generate_query_counts(DB::table('users')->where('gender', 'Female'), $request, 'created_at')

            ];

            return response()->json(['status' => true, 'data' => $gender]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    private function generate_query_counts($query, Request $request, string $date_column)
    {
        return $query
            ->when($request->period == YESTERDAY, function ($q) use ($date_column) {
                $q->whereDate($date_column, Carbon::yesterday());
            })
            ->when($request->period == LAST_WEEK, function ($q) use ($date_column) {
                $q->whereDate($date_column, '>', Carbon::now()->subDays(LAST_7_DAYS_NUM));
            })
            ->when($request->period == LAST_MONTH, function ($q) use ($date_column) {
                $q->whereDate($date_column, '>', Carbon::now()->subDays(LAST_30_DAYS_NUM));
            })
            ->when($request->period == LAST_90_DAYS, function ($q) use ($date_column) {
                $q->whereDate($date_column, '>', Carbon::now()->subDays(LAST_90_DAYS_NUM));
            })
            ->when($request->period == LAST_180_DAYS, function ($q) use ($date_column) {
                $q->whereDate($date_column, '>', Carbon::now()->subDays(LAST_180_DAYS_NUM));
            })
            ->count();
    }


    public function user_age_groups(Request $request)
    {
        try {

            $all_users = DB::table('users')
                ->select('users.*')
                ->addSelect(DB::raw('TIMESTAMPDIFF(YEAR, birthday, CURDATE()) as age'))
                ->when($request->period == YESTERDAY, function ($q) {
                    $q->whereDate('created_at', Carbon::yesterday());
                })
                ->when($request->period == LAST_WEEK, function ($q) {
                    $q->whereDate('created_at', '>', Carbon::now()->subDays(LAST_7_DAYS_NUM));
                })
                ->when($request->period == LAST_MONTH, function ($q) {
                    $q->whereDate('created_at', '>', Carbon::now()->subDays(LAST_30_DAYS_NUM));
                })
                ->when($request->period == LAST_90_DAYS, function ($q) {
                    $q->whereDate('created_at', '>', Carbon::now()->subDays(LAST_90_DAYS_NUM));
                })
                ->when($request->period == LAST_180_DAYS, function ($q) {
                    $q->whereDate('created_at', '>', Carbon::now()->subDays(LAST_180_DAYS_NUM));
                })
                ->get()
                ->toArray();

            $age_groups = [
                '13-17' => count(
                    array_filter(
                        $all_users,
                        fn($user) =>
                        (13 <= $user->age) && ($user->age <= 17)
                    )
                ),
                '18-29' => count(
                    array_filter(
                        $all_users,
                        fn($user) =>
                        (18 <= $user->age) && ($user->age <= 29)
                    )
                ),
                '30-42' => count(
                    array_filter(
                        $all_users,
                        fn($user) =>
                        (30 <= $user->age) && ($user->age <= 42)
                    )
                ),
                '43-52' => count(
                    array_filter(
                        $all_users,
                        fn($user) =>
                        (43 <= $user->age) && ($user->age <= 52)
                    )
                ),
                '>52' => count(
                    array_filter(
                        $all_users,
                        fn($user) =>
                        $user->age > 52
                    )
                ),
            ];

            return response()->json(['status' => true, 'data' => $age_groups]);

        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }




}
