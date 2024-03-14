<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\DiscountCoupon;

class DiscountCouponController extends Controller
{
    public function index(Request $request)
    {            $pagination_limit = (int) $request->paginationLimit ?? 10;
        $coupons = DiscountCoupon::orderBy('id', 'desc')
        ->paginate($pagination_limit);
       
        return response()->json(['status' => true, 'data' => $coupons]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'unique_id' => 'required|string|max:100',
            'coupon_name' => 'required|string|max:100',
            'percent_off' => 'required|integer',
            'max_redemptions' => 'required|integer',
            'coupon_id' => 'nullable|string|max:100',
            'times_used' => 'integer',
            'created_at' => 'nullable|date',
        ]);

        $coupon = DiscountCoupon::create($validatedData);

        return response()->json($coupon, 201);
    }
}
