<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\CustomerLogic;
use App\CentralLogics\Helpers;
use App\CentralLogics\OrderLogic;
use App\Http\Controllers\Controller;
use App\Model\Branch;
use App\Model\BusinessSetting;
use App\Model\DeliveryMan;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\Product;
use App\Model\Warehouse;
use App\Model\WarehouseProduct;
use App\User;
use Box\Spout\Common\Exception\InvalidArgumentException;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Writer\Exception\WriterNotOpenedException;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use function App\CentralLogics\translate;

class OrderController extends Controller
{
    public function __construct(
        private Branch $branch,
        private BusinessSetting $business_setting,
        private DeliveryMan $delivery_man,
        private Order $order,
        private OrderDetail $order_detail,
        private Product $product,
        private User $user,
        private Warehouse $warehouse,
        private WarehouseProduct $warehouseProduct,

    ) {
    }

    /**
     * @param Request $request
     * @param $status
     * @return Factory|View|Application
     */

    public function customQuery($whID = null)
    {
        if ($whID == 'all' || empty($whID)) {
            //dd(1);
            return $this->order->notPos();
        } else {
            // dd(10);
            return $this->order->where('warehouse_id', $whID)->notPos();
        }
    }
    public function list(Request $request, $status): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {

        $warehouse_id = null;
        $authUser = auth('admin')->user();
        if (in_array($authUser->admin_role_id, [6, 7])) {
            $warehouse_id = $authUser->Store->warehouse_id;
        } elseif (in_array($authUser->admin_role_id, [3, 4])) {
            $warehouse_id = $authUser->warehouse_id;
        } elseif ($authUser->admin_role_id == 1) {
            $warehouse_id = $request['warehouse_id'];
        }

        $query_param = [];
        $search = $request['search'];
        $warehouses = $this->warehouse->active()->where('deleted_at', null)->get();

        $branches = $this->branch->all();
        $branch_id = $request['branch_id'];
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];

        $this->order->where(['checked' => 0])->update(['checked' => 1]);

        $mainQuery = self::customQuery($warehouse_id)
            ->with(['customer', 'warehouse'])
            ->when((!is_null($start_date) && !is_null($end_date)), function ($query) use ($start_date, $end_date) {
                return $query->whereBetween('created_at', [$start_date, $end_date]);
            });


        if ($status != 'all') {
            $mainQuery = $mainQuery->where(['order_status' => $status]);
        }

        $query_param = ['warehouse_id' => $warehouse_id, 'start_date' => $start_date, 'end_date' => $end_date];

        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $mainQuery->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('order_status', 'like', "%{$value}%")
                        ->orWhere('payment_status', 'like', "{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }

        $orders = $mainQuery->orderBy('id', 'desc')->paginate(Helpers::getPagination())->appends($query_param);

        $count_data = [
            'pending' => $this->countOrdersByStatus($warehouse_id, 'pending', $start_date, $end_date),
            'confirmed' => $this->countOrdersByStatus($warehouse_id, 'confirmed', $start_date, $end_date),
            'processing' => $this->countOrdersByStatus($warehouse_id, 'processing', $start_date, $end_date),
            'out_for_delivery' => $this->countOrdersByStatus($warehouse_id, 'out_for_delivery', $start_date, $end_date),
            'delivered' => $this->countOrdersByStatus($warehouse_id, 'delivered', $start_date, $end_date),
            'canceled' => $this->countOrdersByStatus($warehouse_id, 'canceled', $start_date, $end_date),
            'returned' => $this->countOrdersByStatus($warehouse_id, 'returned', $start_date, $end_date),
            'failed' => $this->countOrdersByStatus($warehouse_id, 'failed', $start_date, $end_date),
        ];
        // dd($count_data);
        return view('admin-views.order.list', compact('orders', 'status', 'search', 'branches', 'branch_id', 'start_date', 'end_date', 'count_data', 'warehouses', 'warehouse_id'));
    }

    public function countOrdersByStatus($warehouse_id, $status, $start_date, $end_date)
    {
        return self::customQuery($warehouse_id)
            ->where(['order_status' => $status])
            ->when((!is_null($start_date) && !is_null($end_date)), function ($query) use ($start_date, $end_date) {
                return $query->whereDate('created_at', '>=', $start_date)
                    ->whereDate('created_at', '<=', $end_date);
            })
            ->count();
    }
    public function details($id): Factory|View|Application|RedirectResponse
    {
        //dd($id);
        $order = $this->order->with('details')->where(['id' => $id])->first();
        $delivery_man = $this->delivery_man->where(['is_active' => 1])
            ->where(function ($query) use ($order) {
                $query->where('warehouse_id', $order->warehouse_id)
                    ->orWhere('warehouse_id', 0);
            })
            ->get();

        if (isset($order)) {
            return view('admin-views.order.order-view', compact('order', 'delivery_man'));
        } else {
            Toastr::info(translate('No more orders!'));
            return back();
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): \Illuminate\Http\JsonResponse
    {

        $key = explode(' ', $request['search']);
        $orders = $this->order->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('id', 'like', "%{$value}%")
                    ->orWhere('order_status', 'like', "%{$value}%")
                    ->orWhere('transaction_reference', 'like', "%{$value}%");
            }
        })->latest()->paginate(2);

        return response()->json([
            'view' => view('admin-views.order.partials._table', compact('orders'))->render(),
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function date_search(Request $request): \Illuminate\Http\JsonResponse
    {
        $dateData = ($request['dateData']);

        $orders = $this->order->where(['delivery_date' => $dateData])->latest()->paginate(10);
        // $timeSlots = $orders->pluck('time_slot_id')->unique()->toArray();
        // if ($timeSlots) {

        //     $timeSlots = TimeSlot::whereIn('id', $timeSlots)->get();
        // } else {
        //     $timeSlots = TimeSlot::orderBy('id')->get();

        // }
        // dd($orders);

        return response()->json([
            'view' => view('admin-views.order.partials._table', compact('orders'))->render(),
            // 'timeSlot' => $timeSlots
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function time_search(Request $request): \Illuminate\Http\JsonResponse
    {

        $orders = $this->order->where(['time_slot_id' => $request['timeData']])->where(['delivery_date' => $request['dateData']])->get();
        // dd($orders)->toArray();

        return response()->json([
            'view' => view('admin-views.order.partials._table', compact('orders'))->render(),
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function status(Request $request): \Illuminate\Http\RedirectResponse
    {
        $order = $this->order->find($request->id);
        if (in_array($order->order_status, ['delivered', 'failed'])) {
            Toastr::warning(translate('you_can_not_change_the_status_of_a_completed_order'));
            return back();
        }

        if ($request->order_status == 'delivered' && $order['transaction_reference'] == null && !in_array($order['payment_method'], ['cash_on_delivery', 'wallet'])) {
            Toastr::warning(translate('add_your_payment_reference_first'));
            return back();
        }

        if ($request->order_status == 'out_for_delivery' && $order['delivery_man_id'] == null && $order['order_type'] != 'self_pickup') {
            Toastr::warning(translate('Please assign delivery man first!'));
            return back();
        }

        if ($request->order_status == 'returned' || $request->order_status == 'failed' || $request->order_status == 'canceled') {
            if($request->order_status == 'canceled'){
                
                    $this->order->find($request->id)->update([
                        'cancel_by' =>'admin', // Assuming $request->cancel_by is the value you want to update for 'cancel_by'
                        'cancel_by_id' => auth('admin')->user()->id, // Assuming $request->cancel_by is the value you want to update for 'cancel_by'
                ]);
            }
            foreach ($order->details as $detail) {
                if ($detail['is_stock_decreased'] == 1) {
                    $product = $this->product->find($detail['product_id']);
                    $warehouseProduct = $this->warehouseProduct->where('product_id',$detail['product_id'])->where('warehouse_id',$order->warehouse_id)->first();
                    $type =$warehouseProduct->total_stock;
                    if ($product != null) {
                        $var_store = [];
                       
                        $this->product->where(['id' => $product['id']])->update([
                            'variations' => json_encode($var_store),
                        ]);
                        $this->warehouseProduct->where('product_id',$detail['product_id'])->where('warehouse_id',$order->warehouse_id)->update([
                            'total_stock' => $warehouseProduct['total_stock'] + $detail['quantity'],
                        ]);
                        $this->order_detail->where(['id' => $detail['id']])->update([
                            'is_stock_decreased' => 0,
                        ]);
                    }
                } else {
                    Toastr::warning(translate('Product_deleted'));
                }
            }
        } else {
            foreach ($order->details as $detail) {
                if ($detail['is_stock_decreased'] == 0) {
                    $product = $this->product->find($detail['product_id']);
                    if ($product != null) {
                        //check stock
                        foreach ($order->details as $c) {
                            $product = $this->product->find($c['product_id']);
                            $type = json_decode($c['variation'])[0]->type;
                            foreach (json_decode($product['variations'], true) as $var) {
                                if ($type == $var['type'] && $var['stock'] < $c['quantity']) {
                                    Toastr::error(translate('Stock is insufficient!'));
                                    return back();
                                }
                            }
                        }

                        $type = json_decode($detail['variation'])[0]->type;
                        $var_store = [];
                        foreach (json_decode($product['variations'], true) as $var) {
                            if ($type == $var['type']) {
                                $var['stock'] -= $detail['quantity'];
                            }
                            $var_store[] = $var;
                        }
                        $this->product->where(['id' => $product['id']])->update([
                            'variations' => json_encode($var_store),
                            'total_stock' => $product['total_stock'] - $detail['quantity'],
                        ]);
                        $this->order_detail->where(['id' => $detail['id']])->update([
                            'is_stock_decreased' => 1,
                        ]);
                    } else {
                        Toastr::warning(translate('Product_deleted'));
                    }
                }
            }
        }

        if ($request->order_status == 'delivered') {
            if ($order->user_id) {
                CustomerLogic::create_loyalty_point_transaction($order->user_id, $order->id, $order->order_amount, 'order_place');
            }

            $user = $this->user->find($order->user_id);
            $is_first_order = $this->order->where('user_id', $user->id)->count('id');
            $referred_by_user = $this->user->find($user->referred_by);

            if ($is_first_order < 2 && isset($user->referred_by) && isset($referred_by_user)) {
                if ($this->business_setting->where('key', 'ref_earning_status')->first()->value == 1) {
                    CustomerLogic::referral_earning_wallet_transaction($order->user_id, 'referral_order_place', $referred_by_user->id);
                }
            }
        }

        $order->order_status = $request->order_status;
        if($request->order_status == 'pending'){
            $order->pending_time = now();

        }elseif($request->order_status == 'confirmed'){
            $order->confirmed_time = now();

        }elseif($request->order_status == 'processing'){
            $order->processing_time = now();

        }elseif($request->order_status == 'delivered'){
            $order->delivered_time = now();

        }elseif($request->order_status == 'failed'){
            $order->failed_time = now();
            
        }elseif($request->order_status == 'out_for_delivery'){
            $order->out_for_delivery_time = now();
            
        }elseif($request->order_status == 'returned'){
            $order->returned_time = now();
            
        }elseif($request->order_status == 'canceled'){
            $order->canceled_time = now();
            
        }
        $order->save();


        $fcm_token = isset($order->customer) ? $order->customer->cm_firebase_token : null;
        $value = Helpers::order_status_update_message($request->order_status);
        try {
            if ($value) {
                $data = [
                    'title' => translate('Order'),
                    'description' => $value,
                    'order_id' => $order['id'],
                    'image' => '',
                    'type' => 'order',
                    'user_id' => $order['user_id'],
                ];
               
                Helpers::send_push_notif_to_device($fcm_token, $data);
            }
        } catch (\Exception $e) {
            Toastr::warning(\App\CentralLogics\translate('Push notification failed for Customer!'));
        }

        //delivery man notification
        if ($request->order_status == 'processing' && $order->delivery_man != null) {
            $fcm_token = $order->delivery_man->fcm_token;
            $value = \App\CentralLogics\translate('One of your order is in processing');
            try {
                if ($value) {
                    $data = [
                        'title' => translate('Order'),
                        'description' => $value,
                        'order_id' => $order['id'],
                        'user_id' => @$order['user_id'],
                        'image' => '',
                        'type' => 'order'
                    ];
                Helpers::send_push_notif_to_device($fcm_token, $data);
                }
            } catch (\Exception $e) {
                Toastr::warning(\App\CentralLogics\translate('Push notification failed for DeliveryMan!'));
            }
        }

        Toastr::success(translate('Order status updated!'));
        return back();
    }

    /**
     * @param $order_id
     * @param $delivery_man_id
     * @return JsonResponse
     */
    public function add_delivery_man($order_id, $delivery_man_id): \Illuminate\Http\JsonResponse
    {
        
        if ($delivery_man_id == 0) {
            return response()->json([], 401);
        }

        $order = $this->order->find($order_id);

        if ($order->order_status == 'pending' || $order->order_status == 'confirmed' || $order->order_status == 'delivered' || $order->order_status == 'returned' || $order->order_status == 'failed' || $order->order_status == 'canceled') {
            //return response()->json(['status' => false], 200);
        }

        $order->delivery_man_id = $delivery_man_id;
        $order->save();

        $fcm_token = $order->delivery_man->fcm_token;
        $customer_fcm_token = $order->customer->cm_firebase_token;
        $value = Helpers::order_status_update_message('del_assign');
        try {
            if ($value) {
                $data = [
                    'title' => translate('Order'),
                    'description' => $value,
                    'order_id' => @$order['id'],
                    'user_id' => @$order->user_id,
                    'image' => '',
                    'type' => 'order'
                ];
                Helpers::send_push_notif_to_device($fcm_token, $data);
                $cs_notify_message = Helpers::order_status_update_message('customer_notify_message');
                if ($cs_notify_message) {
                    $data['description'] = $cs_notify_message;
                    Helpers::send_push_notif_to_device($customer_fcm_token, $data);
                }
            }
        } catch (\Exception $e) {
            Toastr::warning(\App\CentralLogics\translate('Push notification failed for DeliveryMan!'));
        }

        Toastr::success('Deliveryman successfully assigned/changed!');
        return response()->json(['status' => true], 200);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function payment_status(Request $request): \Illuminate\Http\RedirectResponse
    {
        $order = $this->order->find($request->id);
        if ($request->payment_status == 'paid' && $order['transaction_reference'] == null && $order['payment_method'] != 'cash_on_delivery') {
            Toastr::warning(translate('Add your payment reference code first!'));
            return back();
        }
        $order->payment_status = $request->payment_status;
        $order->save();
        Toastr::success(translate('Payment status updated!'));
        return back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update_shipping(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'contact_person_name' => 'required',
            'address_type' => 'required',
            'contact_person_number' => 'required',
            'address' => 'required',
        ]);

        $address = [
            'contact_person_name' => $request->contact_person_name,
            'contact_person_number' => $request->contact_person_number,
            'address_type' => $request->address_type,
            'address' => $request->address,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'road' => $request->road,
            'house' => $request->house,
            'floor' => $request->floor,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('customer_addresses')->where('id', $id)->update($address);
        Toastr::success(translate('Delivery Information updated!'));
        return back();
    }

    /**
     * @param Request $request
     * @return JsonResponse|void
     */
    public function update_time_slot(Request $request)
    {
        if ($request->ajax()) {
            $order = $this->order->find($request->id);
            $order->time_slot_id = $request->timeSlot;
            $order->save();
            $data = $request->timeSlot;

            return response()->json($data);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse|void
     */
    public function update_deliveryDate(Request $request)
    {
        if ($request->ajax()) {
            $order = $this->order->find($request->id);
            $order->delivery_date = $request->deliveryDate;
            // dd($order);
            $order->save();
            $data = $request->deliveryDate;
            return response()->json($data);
        }
    }

    /**
     * @param $id
     * @return Factory|View|Application
     */
    public function generate_invoice($id): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $order = $this->order->where('id', $id)->first();
        $footer_text = $this->business_setting->where(['key' => 'footer_text'])->first();
        return view('admin-views.order.invoice', compact('order', 'footer_text'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function add_payment_ref_code(Request $request, $id)
    {
        $this->order->where(['id' => $id])->update([
            'transaction_reference' => $request['transaction_reference'],
        ]);

        Toastr::success(translate('Payment reference code is added!'));
        return back();
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function branch_filter($id): \Illuminate\Http\RedirectResponse
    {
        session()->put('branch_filter', $id);
        return back();
    }

    /**
     * @param Request $request
     * @param $status
     * @return string|StreamedResponse
     * @throws IOException
     * @throws InvalidArgumentException
     * @throws UnsupportedTypeException
     * @throws WriterNotOpenedException
     */
    public function export_orders(Request $request, $status): StreamedResponse|string
    {
        $query_param = [];
        $search = $request['search'];
        $branch_id = $request['branch_id'];
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];

        if ($status != 'all') {
            $query = $this->order->with(['customer', 'branch'])
                ->when((!is_null($branch_id) && $branch_id != 'all'), function ($query) use ($branch_id) {
                    return $query->where('branch_id', $branch_id);
                })->when((!is_null($start_date) && !is_null($end_date)), function ($query) use ($start_date, $end_date) {
                    return $query->whereDate('created_at', '>=', $start_date)
                        ->whereDate('created_at', '<=', $end_date);
                })->where(['order_status' => $status]);
        } else {
            $query = $this->order->with(['customer', 'branch'])
                ->when((!is_null($branch_id) && $branch_id != 'all'), function ($query) use ($branch_id) {
                    return $query->where('branch_id', $branch_id);
                })->when((!is_null($start_date) && !is_null($end_date)), function ($query) use ($start_date, $end_date) {
                    return $query->whereDate('created_at', '>=', $start_date)
                        ->whereDate('created_at', '<=', $end_date);
                });
        }

        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('order_status', 'like', "%{$value}%")
                        ->orWhere('payment_status', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }

        //$orders = $query->notPos()->orderBy('id', 'desc')->paginate(Helpers::getPagination())->appends($query_param);
        $orders = $query->notPos()->orderBy('id', 'desc')->get();

        $storage = [];

        foreach ($orders as $order) {
            $branch = $order->branch ? $order->branch->name : '';
            $customer = $order->customer ? $order->customer->f_name . ' ' . $order->customer->l_name : 'Customer Deleted';
            //$delivery_address = $order->delivery_address ? $order->delivery_address['address'] : '';
            $delivery_man = $order->delivery_man ? $order->delivery_man->f_name . ' ' . $order->delivery_man->l_name : '';
            $timeslot = $order->time_slot ? $order->time_slot->start_time . ' - ' . $order->time_slot->end_time : '';

            $storage[] = [
                'order_id' => $order['id'],
                'customer' => $customer,
                'order_amount' => $order['order_amount'],
                'coupon_discount_amount' => $order['coupon_discount_amount'],
                'payment_status' => $order['payment_status'],
                'order_status' => $order['order_status'],
                'total_tax_amount' => $order['total_tax_amount'],
                'payment_method' => $order['payment_method'],
                'transaction_reference' => $order['transaction_reference'],
                // 'delivery_address' => $delivery_address,
                'delivery_man' => $delivery_man,
                'delivery_charge' => $order['delivery_charge'],
                'coupon_code' => $order['coupon_code'],
                'order_type' => $order['order_type'],
                'branch' =>  $branch,
                'time_slot_id' => $timeslot,
                'date' => $order['date'],
                'delivery_date' => $order['delivery_date'],
                'extra_discount' => $order['extra_discount'],
            ];
        }
        //return $storage;
        return (new FastExcel($storage))->download('orders.xlsx');
    }
}
