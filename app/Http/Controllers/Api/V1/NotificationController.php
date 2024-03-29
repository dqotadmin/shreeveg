<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Model\NotificationHistory;
use Illuminate\Http\Request;
use DB;

class NotificationController extends Controller
{
    

    public function get_notifications(Request $request): \Illuminate\Http\JsonResponse
    {

        try {
            $user =auth('api')->user()->id;

            if (!$user) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
        
            $notification_history = NotificationHistory::where('user_id', $user)
            ->paginate($request->limit, ['*'], 'page', $request->offset);

            return response()->json($notification_history, 200);
        } catch (\Exception $e) {
            return response()->json([], 200);
        }
        // try {
        //     return response()->json($this->notification->active()->get(), 200);
        // } catch (\Exception $e) {
        //     return response()->json([], 200);
        // }
    }
}
