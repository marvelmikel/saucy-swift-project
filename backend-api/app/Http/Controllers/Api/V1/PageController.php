<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\BusinessSetting;

class PageController extends Controller
{
    public function index(){
        $return_page =BusinessSetting::where(['key' => 'return_page'])->first();
        $refund_page =BusinessSetting::where(['key' => 'refund_page'])->first();
        $cancellation_page =BusinessSetting::where(['key' => 'cancellation_page'])->first();

        return response()->json([
            'return_page' => isset($return_page) ? json_decode($return_page->value, true) : null,
            'refund_page'=> isset($refund_page) ? json_decode($refund_page->value, true) : null,
            'cancellation_page'=> isset($cancellation_page) ? json_decode($cancellation_page->value, true) : null,
        ]);
    }

}
