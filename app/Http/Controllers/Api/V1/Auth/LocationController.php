<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\CentralLogics\Helpers;
use App\CentralLogics\SMS_module;
use App\Http\Controllers\Controller;
use App\Mail\EmailVerification;
use App\Model\BusinessSetting;
use App\Model\EmailVerifications;
use App\Model\PhoneVerification;
use App\User;
use App\Model\Warehouse;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use Illuminate\Support\Carbon;
use Carbon\CarbonInterval;

class LocationController extends Controller
{
    public function __construct(
        private BusinessSetting $business_setting,
        private EmailVerifications $email_verification,
        private PhoneVerification $phone_verification,
        private User $user,
        private Warehouse $warehouse,
    ) {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_location(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required',
            'longitude' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $latitude = $request->latitude;
        $longitude = $request->longitude;

        $warehouses = Warehouse::withinRadius($latitude, $longitude)->get();
        $guestWarehouse =  Warehouse::where('is_guest_warehouse', '1')->first();
        $assignWarehouseId = $guestWarehouse ? $guestWarehouse->id : 0;

        if (count($warehouses) > 0 && auth('api')->user()) {
            $assignWarehouseId = $warehouses[0]->id;
            if (count($warehouses) > 0) {
                $assignWarehouseId = $warehouses[0]->id;
            }
        }

        auth('api')->user()->update(['warehouse_id' => $assignWarehouseId]);

        return response()->json(['warehouses' => $warehouses]);
    }
}
