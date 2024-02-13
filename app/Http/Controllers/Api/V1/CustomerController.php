<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Conversation;
use App\Model\CustomerAddress;
use App\Model\Newsletter;
use App\Model\Order;
use App\Model\Warehouse;
use App\Model\OrderDetail;
use App\Model\Store;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Http\JsonResponse;


class CustomerController extends Controller
{
    public function __construct(
        private Conversation $conversation,
        private CustomerAddress $customer_address,
        private Newsletter $newsletter,
        private Order $order,
        private OrderDetail $order_detail,
        private User $user
    ){}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function address_list(Request $request): JsonResponse
    {
        if(isset($request->user()->id)){
            return response()->json($this->customer_address->where('user_id', $request->user()->id)->latest()->get(), 200);

        }else{
        $errors[] = ['code' => 'auth-001', 'message' => 'You are not authenticate.'];

        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function add_new_address(Request $request): JsonResponse
    {
       
        $validator = Validator::make($request->all(), [
            'address_type' => 'required',
            'address' => 'required',
            // 'city' => 'required',
            // 'house' => 'required',
            // 'area' => 'required',
            // 'landmark' => 'required',
            // 'pincode' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $address = [
            'user_id' => $request->user()->id,
            'address_type' => $request->address_type,
            'address' => $request->address,
            'road' => @$request->road,
            'house' => @$request->house,
            'floor' => @$request->floor,
            'city' => @$request->city,
            'area' => @$request->area,
            'landmark' => @$request->landmark,
            'pincode' => @$request->pincode,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'created_at' => now(),
            'updated_at' => now()
        ];
        DB::table('customer_addresses')->insert($address);
        return response()->json(['message' => 'successfully added!'], 200);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update_address(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'address_type' => 'required',
            'address' => 'required',
            // 'city' => 'required',
            // 'house' => 'required',
            // 'area' => 'required',
            // 'landmark' => 'required',
            // 'pincode' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $address = [
            'user_id' => $request->user()->id,
            'address_type' => $request->address_type,
            'address' => $request->address,
            'road' => @$request->road,
            'house' => @$request->house,
            'floor' => @$request->floor,
            'city' => @$request->city,
            'area' => @$request->area,
            'landmark' => @$request->landmark,
            'pincode' => @$request->pincode,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'created_at' => now(),
            'updated_at' => now()
        ];
        DB::table('customer_addresses')->where('id',$id)->update($address);
        return response()->json(['message' => 'successfully updated!'], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function delete_address(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'address_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        if (DB::table('customer_addresses')->where(['id' => $request['address_id'], 'user_id' => $request->user()->id])->first()) {
            DB::table('customer_addresses')->where(['id' => $request['address_id'], 'user_id' => $request->user()->id])->delete();
            return response()->json(['message' => 'successfully removed!'], 200);
        }
        return response()->json(['message' => 'No such data found!'], 404);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_order_list(Request $request): JsonResponse
    {
        $orders = $this->order->where(['user_id' => $request->user()->id])->get();
        return response()->json($orders, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_order_details(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $details = $this->order_detail->where(['order_id' => $request['order_id']])->get();
        foreach ($details as $det) {
            $det['product_details'] = Helpers::product_data_formatting(json_decode($det['product_details'], true));
        }

        return response()->json($details, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function info(Request $request): JsonResponse
    {
            $userData =   $request->user();
            if($userData->warehouse_id){
                $whData = Warehouse::find($userData->warehouse_id);
                $userData['delivery_time']= json_decode($whData->delivery_time,true);
                $stores = Store::where('warehouse_id',$userData->warehouse_id)->get();
                $userData['stores'] = $stores;
            }
           // $warehouse_data = $warehouse_id
            return response()->json($userData, 200);
    }

    public static function get_f_l_name($string)
    {
        $words = explode(' ', $string);
        if (count($words) == 1) {
            $firstName = array_pop($words);
            $lastName = null;
        } else {
            $lastName = array_pop($words);
            $firstName = implode(' ', $words);
        }
        $data['f_name'] = $firstName;
        $data['l_name'] = $lastName;
        return $data;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update_profile(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required',
            'phone' => ['required', 'unique:users,phone,'.auth()->user()->id]
        ], [
            'full_name.required' => 'The full name field is required.',
            'phone.required' => 'Phone is required!',
            'phone.unique' => translate('Phone must be unique!'),
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $image = $request->file('image');

        if ($image != null) {
            $data = getimagesize($image);
            $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . 'png';
            if (!Storage::disk('public')->exists('profile')) {
                Storage::disk('public')->makeDirectory('profile');
            }
            $note_img = Image::make($image)->fit($data[0], $data[1])->stream();
            Storage::disk('public')->put('profile/' . $imageName, $note_img);
        } else {
            $imageName = $request->user()->image;
        }

        if ($request['password'] != null && strlen($request['password']) > 5) {
            $pass = bcrypt($request['password']);
        } else {
            $pass = $request->user()->password;
        }
        $tmpName = $this->get_f_l_name($request->full_name);

        $userDetails = [
            'full_name' => $request->full_name,
            'f_name' => $tmpName['f_name'],
            'l_name' => $tmpName['l_name'],
            'phone' => $request->phone,
            'email' => $request->email,
            'image' => $imageName,
            'password' => $pass,
            'updated_at' => now()
        ];

        $this->user->where(['id' => $request->user()->id])->update($userDetails);

        return response()->json(['message' => 'successfully updated!'], 200);
    }
  
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update_cm_firebase_token(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'cm_firebase_token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        DB::table('users')->where('id',$request->user()->id)->update([
            'cm_firebase_token'=>$request['cm_firebase_token']
        ]);

        return response()->json(['message' => 'successfully updated!'], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function subscribe_newsletter(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $newsLetter = $this->newsletter->where('email', $request->email)->first();
        if (!isset($newsLetter)) {
            $newsLetter = $this->newsletter;
            $newsLetter->email = $request->email;
            $newsLetter->save();

            return response()->json(['message' => 'Successfully subscribed'], 200);

        } else {
            return response()->json(['message' => 'Email Already exists'], 400);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function remove_account(Request $request): JsonResponse
    {
        $customer = $this->user->find($request->user()->id);
        if(isset($customer)) {
            Helpers::file_remover('profile/', $customer->image);
            $customer->delete();

        } else {
            return response()->json(['status_code' => 404, 'message' => translate('Not found')], 200);
        }

        $conversations = $this->conversation->where('user_id', $customer->id)->get();
        foreach ($conversations as $conversation){
            if ($conversation->checked == 0){
                $conversation->checked = 1;
                $conversation->save();
            }
        }

        return response()->json(['status_code' => 200, 'message' => translate('Successfully deleted')], 200);
    }
}
