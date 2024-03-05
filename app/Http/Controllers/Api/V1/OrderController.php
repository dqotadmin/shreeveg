<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\CustomerLogic;
use App\CentralLogics\Helpers;
use App\CentralLogics\OrderLogic;
use App\CentralLogics\CouponLogic;
use App\Http\Controllers\Controller;
use App\Model\Coupon;
use App\Model\CustomerAddress;
use App\Model\DMReview;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\Product;
use App\Model\WarehouseProduct;
use App\Model\Review;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use function App\CentralLogics\translate;

class OrderController extends Controller
{
    public function __construct(
        private Coupon $coupon,
        private CustomerAddress $customer_address,
        private DMReview $dm_review,
        private Order $order,
        private OrderDetail $order_detail,
        private Product $product,
        private WarehouseProduct $warehouseProduct,
        private Review $review,
        private User $user
    ) {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function track_order(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        return response()->json(OrderLogic::track_order($request['order_id']), 200);
    }

    public function place_order(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'order_amount' => 'required',
            'payment_method' => 'required',
            'delivery_address_id' => 'required',
            'order_type' => 'required|in:self_pickup,delivery',
            // 'warehouse_id' => 'required',
            'cart' => 'required',
            //'distance' => 'required_if:order_type,delivery',
            'store_id' => 'required_if:order_type,self_pickup',
            'delivery_time_slot' => 'required_if:order_type,delivery',
            'payment_id' => 'required_if:payment_method,online_payment',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        if ($request->payment_method == 'wallet_payment' && Helpers::get_business_settings('wallet_status', false) != 1) {
            return response()->json([
                'errors' => [
                    ['code' => 'payment_method', 'message' => translate('customer_wallet_status_is_disable')]
                ]
            ], 203);
        }

        $customer = $this->user->find($request->user()->id);
        if ($request->payment_method == 'wallet_payment' && $customer->wallet_balance < $request['order_amount']) {
            return response()->json([
                'errors' => [
                    ['code' => 'payment_method', 'message' => translate('you_do_not_have_sufficient_balance_in_wallet')]
                ]
            ], 203);
        }


        //Checking the distance between warehouse and customer.
        // $distanceData = Helpers::checkDistance($request->warehouse_id, $request->delivery_address_id);
        
        // $request['distance'] = $distanceData['distance'] ?? 0;
        $request['distance'] = 1;
        // if ($request->order_type == 'delivery' && !$distanceData['status']) {
        //     return response()->json([
        //         'errors' => [
        //             ['code' => 'delivery_address_id', 'message' => 'Sorry! Delivery is not Available on this address']
        //         ]
        //     ], 203);
        // }


        $max_amount = Helpers::get_business_settings('maximum_amount_for_cod_order');

        if ($request->payment_method == 'cash_on_delivery' && Helpers::get_business_settings('maximum_amount_for_cod_order_status') == 1 && ($max_amount < $request['order_amount'])) {
            $errors = [];
            $errors[] = ['code' => 'auth-001', 'message' => 'For Cash on Delivery, order amount must be equal or less than ' . $max_amount];
            return response()->json([
                'errors' => $errors
            ], 401);
        }

        //dd($request['cart']);
        // foreach ($request['cart'] as $c) {
        //     $whProduct = $this->warehouseProduct->find($c['product_id']);
        //     if ($whProduct['total_stock'] < $c['quantity']) {
        //         $validator->getMessageBag()->add('stock', 'Stock is insufficient! available stock ' . $whProduct['total_stock']);
        //     }
        //     // $type = $c['variation'][0]['type'];
        //     // foreach (json_decode($product['variations'], true) as $var) {
        //     //     if ($type == $var['type'] && $var['stock'] < $c['quantity']) {
        //     //         $validator->getMessageBag()->add('stock', 'Stock is insufficient! available stock ' . $var['stock']);
        //     //     }
        //     // }
        // }

        $free_delivery_amount = 0;
        if ($request['order_type'] == 'self_pickup') {
            $delivery_charge = 0;
        } elseif (Helpers::get_business_settings('free_delivery_over_amount_status') == 1 && (Helpers::get_business_settings('free_delivery_over_amount') <= $request['order_amount'])) {
            $delivery_charge = 0;
            $free_delivery_amount = Helpers::get_delivery_charge($request['distance']);
        } else {
            $delivery_charge = Helpers::get_delivery_charge($request['distance']);
        }

        // $coupon = $this->coupon->active()->where(['code' => $request['coupon_code']])->first();

        // if (isset($coupon)) {
        //     if ($coupon['coupon_type'] == 'free_delivery') {
        //         $free_delivery_amount = Helpers::get_delivery_charge($request['distance']);
        //         $coupon_discount = 0;
        //         $delivery_charge = 0;
        //     } else {
        //         $coupon_discount = $request['coupon_discount_amount'];
        //     }
        // } else {
        //     $coupon_discount = $request['coupon_discount_amount'];
        // }


        //        $coupon_discount_amount = 0;
        //        if ($request->has('coupon_code')){
        //            $coupon_discount_amount = CouponLogic::coupon_apply($request['coupon_code'], $request['order_amount'], $request->user()->id, $delivery_charge);
        //        }


        try {
            //DB::beginTransaction();
            $order_id = 100000 + Order::all()->count() + 1;
           
            $or = [
                'id' => $order_id,
                'user_id' => $request->user()->id,
                'order_amount' => $request['order_amount'],
                'coupon_code' =>  $request['coupon_code'],
                //'coupon_discount_amount' => $coupon_discount_amount,
                'coupon_discount_title' => $request->coupon_discount_title == 0 ? null : 'coupon_discount_title',
                'payment_status' => ($request->payment_method == 'cash_on_delivery' || $request->payment_method == 'offline_payment') ? 'unpaid' : 'paid',
                'order_status' => ($request->payment_method == 'cash_on_delivery' || $request->payment_method == 'offline_payment') ? 'pending' : 'confirmed',
                'pending_time' => now(),
                'confirmed_time' => ($request->payment_method == 'cash_on_delivery' || $request->payment_method == 'offline_payment') ? null : now(),
                'payment_method' => $request->payment_method,
                'transaction_reference' => $request->transaction_reference ?? null,
                'order_note' => $request['order_note'],
                'order_type' => $request['order_type'],
                'warehouse_id' => $request['warehouse_id'],
                'delivery_address_id' => $request->delivery_address_id,
                'delivery_time_slot' => $request->delivery_time_slot,
                'delivery_date' => $request->delivery_date,
                'delivery_address' => json_encode(CustomerAddress::find($request->delivery_address_id) ?? null),
                'date' => date('Y-m-d'),
                'delivery_charge' => $delivery_charge,
                'payment_by' => $request['payment_method'] == 'offline_payment' ? $request['payment_by'] : null,
                'payment_note' => $request['payment_method'] == 'offline_payment' ? $request['payment_note'] : null,
                'free_delivery_amount' => $free_delivery_amount,
                'payment_id' => @$request['payment_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]; 
            $o_time = $or['delivery_time_slot'];
            $o_delivery = $or['delivery_date'];
            $total_tax_amount = 0;
            $data = json_decode($request['cart'], true);
            // $dataa = json_decode($data['cart'], true);
           
            foreach ($data as $c) {

                $product = $this->warehouseProduct->find($c['id']);
                $total_stock = $product->total_stock; 
                    $pack_size = ($c['variations']['quantity']); 
                    $product_total_quantity = $pack_size *$c['quantity'];
                    $product_price = ($c['variations']['offer_price']); 
                    $total_product_price =  $product_price *$c['quantity']; 
                
                    // if ($product['maximum_order_quantity'] < $product_total_quantity) {
                    //     return response()->json(['errors' => $product['name'] . ' ' . \App\CentralLogics\translate('quantity_must_be_equal_or_less_than ' . $product['maximum_order_quantity'])], 401);
                    // }
  
                 
                        $price = $total_product_price;

                    $tax_on_product = Helpers::tax_calculate($product, $price);

                    //                if (Helpers::get_business_settings('product_vat_tax_status') === 'included'){
                    //                    //$price = $price - $tax_on_product;
                    //                }

                    // $category_id = null;
                    // foreach (json_decode($product['category_ids'], true) as $cat) {
                    //     if ($cat['position'] == 1) {
                    //         $category_id = ($cat['id']);
                    //     }
                    // }

                    // $category_discount = Helpers::category_discount_calculate($category_id, $price);
                    // $product_discount = Helpers::discount_calculate($product, $price);
                    // if ($category_discount >= $price) {
                    //     $discount = $product_discount;
                    //     $discount_type = 'discount_on_product';
                    // } else {
                    //     $discount = max($category_discount, $product_discount);
                    //     $discount_type = $product_discount > $category_discount ? 'discount_on_product' : 'discount_on_category';
                    // }
                    $or_d = [
                        'user_warehouse_order_id' => $order_id,
                        'product_id' => $c['product']['product_id'],
                        'delivery_time_slot' => $o_time,
                        'delivery_date' => $o_delivery,
                        'product_details' => $product,
                        'quantity' => $c['quantity'],
                        'price' => $price,
                        'unit' => $product['unit'],
                       
                        // 'discount_on_product' => $discount,
                        // 'discount_type' => $discount_type,
                        'variation' => json_encode($c['variations']),
                        'is_stock_decreased' => 1,
                        'vat_status' => Helpers::get_business_settings('product_vat_tax_status') === 'included' ? 'included' : 'excluded',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    // $total_tax_amount += $or_d['tax_amount'] * $c['quantity'];
                    
                    // $type = $c['variations'][0]['type'];
                   $after_deduct_quantity_stock =  $total_stock - $product_total_quantity;
                    $this->warehouseProduct->where(['id' => $product['id']])->update([
                        'total_stock' => $after_deduct_quantity_stock,
                    ]);
                    $this->product->where(['id' => $product['product_id']])->update([
                        'popularity_count' => $product['popularity_count'] + 1
                    ]);
                    DB::table('user_warehouse_order_details')->insert($or_d);
            }
         

            $or['total_tax_amount'] = $total_tax_amount;
            DB::table('user_warehouse_orders')->insertGetId($or);

            if ($request->payment_method == 'wallet_payment') {
                $amount = $or['order_amount'];
                CustomerLogic::create_wallet_transaction($or['user_id'], $amount, 'order_place', $or['id']);
            }

            //push notification
            $fcm_token = $request->user()->cm_firebase_token;
            $order_status_message = $request->payment_method == 'cash_on_delivery' ? 'pending' : 'confirmed';
            $value = Helpers::order_status_update_message($order_status_message);
            try {
                if ($value) {
                    $data = [
                        'title' => 'Order',
                        'description' => $value,
                        'order_id' => @$order_id,
                        'user_id' => @$request->user()->id,
                        'image' => '',
                        'type' => 'order'
                    ];
                    Helpers::send_push_notif_to_device($fcm_token, $data);
                }
  
                //send email
                $emailServices = Helpers::get_business_settings('mail_config');

                if (isset($emailServices['status']) && $emailServices['status'] == 1) {
                    Mail::to($request->user()->email)->send(new \App\Mail\OrderPlaced($order_id));
                }
            } catch (\Exception $e) {
                return response()->json([$e], 403);

            }

            return response()->json([
                'message' => 'Order placed successfully!',
                'order_id' => $order_id,
            ], 200);

            // DB::commit();
        } catch (\Exception $e) {
            // DB::rollBack();
          
            return response()->json([$e], 403);
        }
    }
 

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_order_list(Request $request): \Illuminate\Http\JsonResponse
    {
       
        // $orders = $this->order->with(['customer', 'delivery_man.rating'])
        //     ->withCount('details')
        //     ->where(['user_id' => $request->user()->id])->get();
        // $orders->map(function ($data) {
        //     $data['deliveryman_review_count'] = $this->dm_review->where(['delivery_man_id' => $data['delivery_man_id'], 'order_id' => $data['id']])->count();
        //     return $data;
        // });


        $warehouse_id = auth()->user()->warehouse_id;
        $time_slot = \App\Model\Warehouse::find($warehouse_id);
        $delivery_time_slot = json_decode($time_slot->delivery_time, true);
        $stores = \App\Model\Store::where('warehouse_id',$warehouse_id)->get();

        $orders = $this->order->with(['customer', 'delivery_man.rating'])
            ->withCount('details')
            ->where(['user_id' => $request->user()->id])
            ->get();
        $orders->each(function ($data) use ($delivery_time_slot,$stores) {
            $data->customer['delivery_time'] = $delivery_time_slot;
            $data->customer['stores'] = $stores;
            $data['deliveryman_review_count'] = $this->dm_review->where(['delivery_man_id' => $data['delivery_man_id'], 'order_id' => $data['id']])->count();
        });

        return response()->json($orders->map(function ($data) {
            $data->details_count = (int) $data->details_count;
            return $data;
        }), 200);

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_order_details(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $details = $this->order_detail->where(['user_warehouse_order_id' => $request['order_id']])
            ->whereHas('order', function ($q) use ($request) {
                $q->where(['user_id' => $request->user()->id]);
            })
            ->orderBy('id', 'desc')
            ->get();
        if ($details->count() > 0) {
            // foreach ($details as $det) {
            //     $det['variation'] = json_decode($det['variation'], true);

            //     if ($this->order->find($request->order_id)->order_type == 'pos') {
            //         $det['variation'] = (string)implode('-', array_values($det['variation'])) ?? null;
            //     }
            //     else {
            //         $det['variation'] = (string)$det['variation'][0]['type']??null;
            //     }

            //     $det['review_count'] = $this->review->where(['user_warehouse_order_id' => $det['order_id'], 'product_id' => $det['product_id']])->count();
            //     $product = $this->product->where('id', $det['product_id'])->first();
            //     $det['product_details'] = isset($product) ? Helpers::product_data_formatting($product) : null;
            // }
            foreach ($details as $product_detail) {
                $product = $this->product->find($product_detail['product_id']);
            
            $product_detail['name'] = $product->name;
            $product_detail['image'] = json_decode($product->image,true);
            $product_detail['product_details'] = json_decode($product_detail['product_details'], true);
                
            }
            return response()->json($details, 200);
        } else {
            return response()->json([
                'errors' => [
                    ['code' => 'order', 'message' => 'Order not found!']
                ]
            ], 401);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function cancel_order(Request $request): \Illuminate\Http\JsonResponse
    {
        if ($this->order->where(['user_id' => $request->user()->id, 'id' => $request['order_id']])->first()) {
            $order = $this->order->with(['details'])->where(['user_id' => $request->user()->id, 'id' => $request['order_id']])->first();
            if($order->order_status == 'pending'){
                $total_stock = 0;

                foreach ($order->details as $detail) {
                    if ($detail['is_stock_decreased'] == 1) {
                        // Assuming $detail->product_details is an array
                        $product_details = json_decode($detail->product_details, true);
                
                        // Assuming $detail['quantity'] is the stock quantity to be decreased
                        // $total_stock += $detail['quantity'];
                        $this->warehouseProduct->where(['id' => $product_details['product_id']])->update([
                            'total_stock' => $detail['quantity'],
                        ]);
                    }
                }
                //     $var_store[] = $var;
                        // $this->warehouseProduct->where(['id' => $product_details['product_id']])->update([
                        //     'total_stock' => $total_stock,
                        // ]);
                        $this->order_detail->where(['id' => $product_details['product_id']])->update([
                            'is_stock_decreased' => 0,
                        ]);
            }
         
            $this->order->where(['user_id' => $request->user()->id, 'id' => $request['order_id']])->update([
                'order_status' => 'canceled',
                'cancel_by' => 'user',
                'cancel_by_id' => $request->user()->id, // Assuming $request->cancel_by is the value you want to update for 'cancel_by'

            ]);
            return response()->json(['message' => 'Order canceled'], 200);
        }

        return response()->json([
            'errors' => [
                ['code' => 'order', 'message' => 'not found!'],
            ],
        ], 401);
    }
   
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update_payment_method(Request $request): \Illuminate\Http\JsonResponse
    {
        if ($this->order->where(['user_id' => $request->user()->id, 'id' => $request['order_id']])->first()) {
            $this->order->where(['user_id' => $request->user()->id, 'id' => $request['order_id']])->update([
                'payment_method' => $request['payment_method'],
            ]);
            return response()->json(['message' => 'Payment method is updated.'], 200);
        }
        return response()->json([
            'errors' => [
                ['code' => 'order', 'message' => 'not found!'],
            ],
        ], 401);
    }
    public function order_history(Request $request): \Illuminate\Http\JsonResponse
    {
        $details = $this->order->where(['user_id' => $request->user()->id])
        ->with(['details'])
        ->get(); 
        return response()->json($details, 200);

    }
    
}
