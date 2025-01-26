<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Listing;

class ListingController extends Controller
{
    public function index(){
        $listings = Listing::withCount('transaction')->orderBy('transaction_count','desc')->paginate();

        return response()->json([
            'success'=>true,
            'message'=>'Get All Listing',
            'data'=>$listings
        ]);

    }
    public function show(Listing $listing): JsonResponse{
        return response()->json([
            'success' => true,
            'message' => 'Get Detail Listing',
            'data' => $listing
        ]);
    }
}
