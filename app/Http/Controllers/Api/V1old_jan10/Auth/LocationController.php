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
        private User $user
    ) {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_location(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'city' => 'required'
        ]);
        dd($validator);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        
    }

    
}
