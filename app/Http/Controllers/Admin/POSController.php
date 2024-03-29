<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Branch;
use App\Model\Category;
use App\Model\Store;
use App\Model\DeliveryMan;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\Product;
use App\Model\WarehouseProduct;
use App\Model\WarehouseCategory;
use App\Model\StoreProduct;
use App\User;
use App\Model\Warehouse;

use Box\Spout\Common\Exception\InvalidArgumentException;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Writer\Exception\WriterNotOpenedException;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psy\VersionUpdater\SelfUpdate;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use function App\CentralLogics\translate;
use App\Model\FlashDeal;
use App\Model\FlashDealProduct;

class POSController extends Controller
{
    public function __construct(
        private Branch $branch,
        private Category $category,
        private DeliveryMan $delivery_man,
        private Order $order,
        private OrderDetail $order_detail,
        private Product $product,
        private User $user,
        private WarehouseCategory $warehouse_categories,
        private Store $store,
        private Warehouse $warehouse,
        private StoreProduct $store_products,
        private WarehouseProduct $warehouse_products,

    ) {
    }

    /**
     * @param Request $request
     * @return Factory|View|Application
     */
    public function index(Request $request): View|Factory|Application
    {
        //session()->forget('cart');
        //session()->forget('last_order');
        $authUser = auth('admin')->user();
        if (in_array($authUser->admin_role_id, [6, 7])) {
            $warehouse_id = $authUser->Store->warehouse_id;
        } elseif (in_array($authUser->admin_role_id, [3, 4])) {
            $warehouse_id = $authUser->warehouse_id;
        }

        $assign_categories = $this->warehouse_categories->where('warehouse_id', $warehouse_id)->pluck('category_id');
        $categories = $this->category->whereIn('id', $assign_categories)->get();

        $category = $request->query('category_id', 0);

        $keyword = $request->keyword;
        $key = explode(' ', $keyword);

        $products = $this->product->whereIn('category_id', $assign_categories)->when($request->has('category_id') && $request['category_id'] != 0, function ($query) use ($request) {
            $query->where('category_id', $request['category_id']);
        })
            ->when($keyword, function ($query) use ($key) {
                return $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%")
                            ->orWhere('product_code', 'like', "%{$value}%");
                    }
                });
            })
            ->active()->latest()->paginate(Helpers::getPagination());

        $branches = $this->branch->all();
        $users = $this->user->orderBy('f_name')->get();
        return view('admin-views.pos.index', compact('categories', 'products', 'category', 'keyword', 'branches', 'users', 'authUser', 'warehouse_id'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function quick_view(Request $request): \Illuminate\Http\JsonResponse
    {
        $product = $this->product->findOrFail($request->product_id);
        $discount = self::discount_calculation($product, $product['price']);
        $offers =  Helpers::getWhProductOffers($request->product_id);
        //dd($offers);
        return response()->json([
            'success' => 1,
            'view' => view('admin-views.pos._quick-view-data', compact('product', 'discount', 'offers'))->render(),
        ]);
    }

    /**
     * @param Request $request
     * @return array
     */
    public static function calculateDynamicPrice($whProductId, $reqQty)
    {

        $product =  \App\Model\WarehouseProduct::find($whProductId);
        $product_details =   json_decode($product->product_details, true);

        $count = count($product_details); //3
        $price = $product->customer_price;

        foreach ($product_details as $key => $detail) {
            $quantity[] = $detail['quantity'];
        }
        $allQuantity =  $quantity;
        $count = count($quantity);
        for ($i = 0; $i < $count - 1; $i++) {
            if (isset($allQuantity[$i + 1]) && $reqQty >= $allQuantity[$i] && $reqQty < $allQuantity[$i + 1]) {
                // Get the actual price when $reqQty falls within the range
                $actualPrice = $product_details[$i]['offer_price'] / $allQuantity[$i];
                // Calculate the price based on the range
                $price = $actualPrice;
                break; // Stop the loop once we find the matching range
            }
        }

        if ($reqQty >=  max($allQuantity)) {
            $lastPrice = ($product_details[count($product_details) - 1]['offer_price']);
            $lastQty = ($product_details[count($product_details) - 1]['quantity']);
            $avgPrice = $lastPrice / $lastQty;
            $price = $avgPrice;
        }
        return $price;
    }
    public function variant_price(Request $request): array
    {
        $price = $this->calculateDynamicPrice($request->id, $request->quantity);
        $newPrice = ($price  * $request->quantity);
        $offerDiscount = $this->offerDiscountCal($request->product_id, $request['quantity'], $newPrice);
        return array('price' => Helpers::set_symbol($offerDiscount['updatedPrice']), 'offer_discount' => $offerDiscount['offerDiscount']);
        // return array('price' => Helpers::set_symbol($newPrice), 'offer_discount' => $offerDiscount['offerDiscount']);

        //return array('price' => Helpers::set_symbol(number_format($price * $request->quantity,2,'.','')));

    }
    /**
     * @param $product
     * @param $price
     * @return float
     */
    public function discount_calculation($product, $price): float
    {
        $category_id = $product['category_id'];
        /* foreach (json_decode($product['category_ids'], true) as $cat) {
            if ($cat['position'] == 1) {
                $category_id = ($cat['id']);
            }
        } */

        $category_discount = Helpers::category_discount_calculate($category_id, $price);
        $product_discount = Helpers::discount_calculate($product, $price);
        if ($category_discount >= $price) {
            $discount = $product_discount;
        } else {
            $discount = max($category_discount, $product_discount);
        }
        return $discount;
    }


    public function get_customers(Request $request): \Illuminate\Http\JsonResponse
    {
        $key = explode(' ', $request['q']);
        $data = DB::table('users')
            ->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('f_name', 'like', "%{$value}%")
                        ->orWhere('l_name', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%");
                }
            })
            ->whereNotNull(['f_name', 'l_name', 'phone'])
            ->limit(8)
            ->orderBy('f_name')

            ->get([DB::raw('id, CONCAT(f_name, " ", l_name, " (", phone ,")") as text')]);

        $data[] = (object)['id' => false, 'text' => translate('walk_in_customer')];

        return response()->json($data);
    }


    public function update_tax(Request $request): \Illuminate\Http\RedirectResponse
    {
        if ($request->tax < 0) {
            Toastr::error(translate('Tax_can_not_be_less_than_0_percent'));
            return back();
        } elseif ($request->tax > 100) {
            Toastr::error(translate('Tax_can_not_be_more_than_100_percent'));
            return back();
        }

        $cart = $request->session()->get('cart', collect([]));
        $cart['tax'] = $request->tax;
        $request->session()->put('cart', $cart);
        return back();
    }


    public function update_discount(Request $request): \Illuminate\Http\RedirectResponse
    {
        $total = session()->get('total');

        if ($request->type == 'percent' && $request->discount < 0) {
            Toastr::error(translate('Extra_discount_can_not_be_less_than_0_percent'));
            return back();
        } elseif ($request->type == 'percent' && $request->discount > 100) {
            Toastr::error(translate('Extra_discount_can_not_be_more_than_100_percent'));
            return back();
        } elseif ($request->type == 'amount' && $request->discount > $total) {
            Toastr::error(translate('Extra_discount_can_not_be_more_than_total_price'));
            return back();
        }

        $cart = $request->session()->get('cart', collect([]));

        $cart['extra_discount'] = $request->discount;
        $cart['extra_discount_type'] = $request->type;
        $request->session()->put('cart', $cart);

        Toastr::success(translate('Discount_applied'));
        return back();
    }


    public function updateQuantity(Request $request): \Illuminate\Http\JsonResponse
    {
        $cart = $request->session()->get('cart', collect([]));
        $cart = $cart->map(function ($object, $key) use ($request) {
            if ($key == $request->key) {
                $object['quantity'] = $request->quantity;
            }
            return $object;
        });
        $request->session()->put('cart', $cart);
        return response()->json([], 200);
    }



    public function addToCart(Request $request): \Illuminate\Http\JsonResponse
    {
        // dd($request->all());
        // dd($request->id, $this->product);
        $authUser = auth('admin')->user();
        $product = \App\Model\WarehouseProduct::find($request->id);
        $store_stock = \App\Model\StoreProduct::where('product_id', $request->product_id)->where('store_id', $authUser->store_id)->first();

        $data = array();
        $data['id'] = $product->id;
        $str = '';
        $variations = [];
        $price = 0;

        if ($request->store_total_stock < $request['quantity']) {
            return response()->json([
                'data' => 0
            ]);
        }
        // if ($product->total_stock < $request['quantity']) {
        //     return response()->json([
        //         'data' => 0
        //     ]);
        // }

        //Gets all the choice values of customer choice option and generate a string like Black-S-Cotton
        /* foreach (json_decode($product->choice_options) as $key => $choice) {
            $data[$choice->name] = $request[$choice->name];
            $variations[$choice->title] = $request[$choice->name];
            if ($str != null) {
                $str .= '-' . str_replace(' ', '', $request[$choice->name]);
            } else {
                $str .= str_replace(' ', '', $request[$choice->name]);
            }
        } */
        if (isset($data['variations'])) {
            $data['variations'] = $variations;
        }
        $data['variant'] = $str;
        if ($request->session()->has('cart')) {
            if (count($request->session()->get('cart')) > 0) {
                foreach ($request->session()->get('cart') as $key => $cartItem) {
                    if (is_array($cartItem) && $cartItem['id'] == $request['id'] && $cartItem['variant'] == $str) {
                        return response()->json([
                            'data' => 1
                        ]);
                    }
                }
            }
        }

        //Check the string and decreases quantity for the stock
        // if ($str != null) {
        //     $count = count(json_decode($product->variations));
        //     for ($i = 0; $i < $count; $i++) {
        //         if (json_decode($product->variations)[$i]->type == $str) {
        //             // $price = json_decode($product->variations)[$i]->price;
        //             $price = $product->customer_price;
        //         }
        //     }
        // } else {
        //     $price = $product->customer_price;
        // }

        $price = $this->calculateDynamicPrice($request->id, $request['quantity']);
        $newPrice =  $price * $request['quantity'];
        $offerDiscount = $this->offerDiscountCal($request->product_id, $request['quantity'], $newPrice);
        //check1RupeeOffer($product);
        // dd($price, $offerDiscount);
        $tax_on_product = Helpers::tax_calculate($product, $price);

        $discount = self::discount_calculation($product, $price);

        $data['quantity'] = $request['quantity'];
        $data['with_offer_price'] =  $offerDiscount['updatedPrice'];
        $data['price'] = $price;
        $data['name'] = $product->productDetail->name;
        $data['discount'] = $discount;
        $data['offer_discount'] = $offerDiscount['offerDiscount'];
        $data['image'] = $product->productDetail->image;
        if ($request->session()->has('cart')) {
            $cart = $request->session()->get('cart', collect([]));
            $cart->push($data);
        } else {
            $cart = collect([$data]);
            $request->session()->put('cart', $cart);
        }

        return response()->json([
            'data' => $data,
            'quantity' => $store_stock->total_stock
        ]);
    }

    public function check1RupeeOffer($productRow)
    {
        $offers = Helpers::getWhProductOffers($productRow->id);
        if (count($offers) > 0) {
            foreach ($offers as $offer) {
                //ALTER TABLE `flash_deals` ADD `min_purchase_amount` DECIMAL(10,2) NULL AFTER `deal_type`;
            }
        }
    }


    public function offerDiscountCal($productId, $qty, $currentPrice)
    {
        $offers =  Helpers::getWhProductOffers($productId);
        $price_discount = 0;
        if (count($offers) > 0) {
            foreach ($offers as $key => $offer) {
                $offerQty =  Helpers::getWhProductOfferQty($productId, $offer->id);
                if ($qty >= $offerQty) {
                    if ($offer['offer_type'] == 'one_rupee') {
                        $getPrice = round(($currentPrice / $qty), 2);
                        //dd($getPrice, $currentPrice, $offer['min_purchase_amount']);
                        // if ($currentPrice >= $offer['min_purchase_amount']) {
                        //     $price_discount += $getPrice - 1;
                        //     $chkPrice = $price_discount;
                        //     if ($chkPrice >= $offer['min_purchase_amount']) {
                        //         $price_discount += $getPrice - 1;
                        //     }
                        // }
                    } else {
                        if ($offer['discount_type'] == 'percent') {
                            $price_discount += ($currentPrice / 100) * $offer['discount_amount'];
                        } else {
                            $price_discount += $offer['discount_amount'];
                        }
                    }
                }
            }
        }
        $data['offerDiscount'] = round($price_discount, 2);
        $data['updatedPrice'] = max(round($currentPrice - $price_discount, 2), 0);

        return $data;
    }

    public function adminDiscountCal($productId, $qty, $currentPrice)
    {

        $price_discount = 0;
        if ($qty <= 1) {
            $price_discount += $currentPrice - 1;
            $quantity = 1;
            //dd($price_discount);

        } else {
            $price_discount += $currentPrice - 1;
            $newQty = $qty - 1;
            $price = $currentPrice * $newQty;
            //dump($currentPrice);
            $currentPrice = $price + 1;
            //dd($currentPrice);
            $quantity  = $qty;
        }

        $data['offerDiscount'] = round($price_discount, 2);
        $data['updatedPrice'] = round($currentPrice, 2);
        $data['quantity'] = $quantity;

        return $data;
    }

    public function offer_discount(Request $request)
    {
        $authUser = auth('admin')->user();
        if (in_array($authUser->admin_role_id, [6, 7])) {
            $warehouse_id = $authUser->Store->warehouse_id;
        } elseif (in_array($authUser->admin_role_id, [3, 4])) {
            $warehouse_id = $authUser->warehouse_id;
        }
        $total = session()->get('total');

        //dump($total, $request->all());
        $adminOffer = FlashDeal::find($request->admin_offer_id);
        if ($total < $adminOffer->min_purchase_amount) {
            Toastr::error(translate('min order value ' . $adminOffer->min_purchase_amount . ' to aval this offer'));
            return back();
        }
        $adminOfferProducts = $adminOffer->products->pluck('product_id');
        //dump($adminOffer, $adminOffer->products->pluck('product_id'));
        $whProducts = WarehouseProduct::where('warehouse_id', $warehouse_id)->whereIn('product_id', $adminOfferProducts)->get();

        //dd($whProducts);
        if ($request->session()->has('cart')) {
            if (count($request->session()->get('cart')) > 0) {
                foreach ($whProducts as $whProduct) {
                    foreach ($request->session()->get('cart') as $key => $cartItem) {
                        if ($cartItem['id'] == $whProduct->id) {
                            $temp = $cartItem;
                            // dump($cartItem);
                            $result = $this->adminDiscountCal($cartItem['id'], $cartItem['quantity'], $cartItem['price']);
                            // dd($result, $temp);
                            $cartItem['quantity'] = $result['quantity'];
                            //$cartItem['with_offer_price'] =  $temp['with_offer_price'];
                            $cartItem['with_offer_price'] =  $result['updatedPrice'];
                            $cartItem['price'] = $temp['price'];
                            $cartItem['name'] = $temp['name'];
                            $cartItem['discount'] = $temp['discount'];
                            $cartItem['offer_discount'] = $result['offerDiscount'];
                            $cartItem['image'] = $temp['image'];

                            $cart = $request->session()->get('cart', collect([]));
                            $cart[$key] = $cartItem;
                            $request->session()->put('cart', $cart);

                            // Break out of the inner loop once the item is found and updated
                            break;
                            // $cart = $request->session()->get('cart', collect([]));
                            // $cart[$key] = $cartItem;
                            // //$mk = $cartItem;
                            // $cart->push($cartItem);
                            // $request->session()->put('cart', $cart);

                            //dd($result, $temp, $mk, $cart);
                        }
                    }
                }
            }
        }
        //dd(1111111);


        Toastr::success(translate('offer_applied'));
        return back();
    }

    public function cart_items(): View|Factory|Application
    {
        return view('admin-views.pos._cart');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function emptyCart(Request $request): \Illuminate\Http\JsonResponse
    {
        session()->forget('cart');
        return response()->json([], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function removeFromCart(Request $request): \Illuminate\Http\JsonResponse
    {
        if ($request->session()->has('cart')) {
            $cart = $request->session()->get('cart', collect([]));
            $cart->forget($request->key);
            $request->session()->put('cart', $cart);
        }

        return response()->json([], 200);
    }

    //order

    /**
     * @param Request $request
     * @return Factory|View|Application
     */
    public function order_list(Request $request)
    {
        $branches = $this->branch->all();
        $query_param = [];
        $search = $request['search'];
        $query = $this->order;
        $authUser = auth('admin')->user();

        $storeId = $warehouse_id = null;
        if (in_array($authUser->admin_role_id, [6])) {
            $warehouse_id = $authUser->Store->warehouse_id;
            $storeId = $authUser->store_id;
            $query = $query->whereType('Store')->where('store_id', $authUser->store_id);
        } elseif (in_array($authUser->admin_role_id, [3, 4])) {
            $warehouse_id = $authUser->warehouse_id;
            $query = $query->where('warehouse_id', $authUser->warehouse_id);
        } elseif (in_array($authUser->admin_role_id, [7])) {
            $store_sales_person_id = $authUser->id;
            $query = $query->where('store_sales_person_id', $authUser->id);
        }

        // if ($authUser->admin_role_id == 6 ) {
        //     $query = $query->whereType('Store')->where('store_id', $authUser->store_id);
        // } elseif ($authUser->admin_role_id == 3) {

        // }

        // $warehouse_id = null;
        // $authUser = auth('admin')->user();
        // if (in_array($authUser->admin_role_id, [6, 7])) {
        //     $warehouse_id = $authUser->Store->warehouse_id;
        // } elseif (in_array($authUser->admin_role_id, [3, 4])) {
        //     $warehouse_id = $authUser->warehouse_id;
        // } elseif ($authUser->admin_role_id == 1) {

        // } 

        $start_date = $request['start_date'];
        $end_date = $request['end_date'];

        if (request('warehouse_id') && request('warehouse_id') != 'all') {
            $warehouse_id = request('warehouse_id');
            $query = $query->where('warehouse_id', request('warehouse_id'));
        }
        $query = $query->when((!is_null($start_date) && !is_null($end_date)), function ($query) use ($start_date, $end_date) {
            return $query->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date);
        });
        //dd($query->get());

        // $query_param = ['branch_id' => 0, 'start_date' => $start_date, 'end_date' => $end_date];
        //$this->order->where(['checked' => 0])->update(['checked' => 1]);

        // $query =  $query->pos()->with(['customer', 'branch', 'details'])
        //     ->when((!is_null($branch_id) && $branch_id != 'all'), function ($query) use ($branch_id) {
        //         return $query->where('branch_id', $branch_id);
        //     })
        //     ->when((!is_null($start_date) && !is_null($end_date)), function ($query) use ($start_date, $end_date) {
        //         return $query->whereDate('created_at', '>=', $start_date)
        //             ->whereDate('created_at', '<=', $end_date)->get;
        //     });
        //     $query_param = ['branch_id' => $branch_id, 'start_date' => $start_date, 'end_date' => $end_date];

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


        $orders = $query->orderBy('id', 'desc')->paginate(Helpers::getPagination())->appends($query_param);
        //return $orders;
        //dd($orders);
        $warehouses = $this->warehouse->active()->where('deleted_at', null)->get();
        return view('admin-views.pos.order.list', compact('orders', 'search', 'branches', 'start_date', 'end_date', 'warehouse_id', 'warehouses'));
    }

    /**
     * @param $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function order_details($id): View|Factory|RedirectResponse|Application
    {
        $order = $this->order->with('details')->where(['id' => $id])->first();
        //dd($order->details);
        $delivery_man = $this->delivery_man->where(['is_active' => 1])
            ->where(function ($query) use ($order) {
                $query->where('warehouse_id', $order->warehouse_id);
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
     * @return RedirectResponse
     */
    public function place_order(Request $request): \Illuminate\Http\RedirectResponse
    {
        // dd($request->all());
        $authUser = auth('admin')->user();
        if ($request->session()->has('cart')) {
            if (count($request->session()->get('cart')) < 1) {
                Toastr::error(translate('cart_empty_warning'));
                return back();
            }
        } else {
            Toastr::error(translate('cart_empty_warning'));
            return back();
        }

        $cart = $request->session()->get('cart');
        $total_tax_amount = 0;
        $product_price = 0;
        $order_details = [];

        $order_id = 100000 + $this->order->all()->count() + 1;
        if ($this->order->find($order_id)) {
            $order_id = $this->order->orderBy('id', 'DESC')->first()->id + 1;
        }
        $order = $this->order;
        $order->id = $order_id;

        $order->user_id = session()->has('customer_id') ? session('customer_id') : null;
        $order->coupon_discount_title = $request->coupon_discount_title == 0 ? null : 'coupon_discount_title';
        $order->payment_status = 'paid';
        $order->order_status = 'delivered';
        $order->order_type = 'pos';
        if ($authUser->admin_role_id == 6) {
            $order->type = 'Store';
            $order->store_id = $authUser->store_id;
            $order->warehouse_id = $authUser->Store->warehouse_id;
        }
        if ($authUser->admin_role_id == 3) {
            $order->warehouse_id = $authUser->warehouse_id;
        }
        if ($authUser->admin_role_id == 7) {
            $order->type = 'Store';
            $order->store_id = $authUser->store_id;
            $order->store_sales_person_id = $authUser->id;
            $order->warehouse_id = @$authUser->store->warehouse_id;
        }

        $order->coupon_code = $request->coupon_code ?? null;
        $order->payment_method = $request->type;
        $order->transaction_reference = $request->transaction_reference ?? null;
        $order->delivery_charge = 0; //since pos, no distance, no d. charge
        $order->delivery_address_id = $request->delivery_address_id ?? null;
        $order->delivery_date = Carbon::now()->format('Y-m-d');
        $order->order_note = null;
        $order->checked = 1;
        $order->created_at = now();
        $order->updated_at = now();

        foreach ($cart as $c) {
            if (is_array($c)) {
                $product = \App\Model\WarehouseProduct::find($c['id']);

                $store_product_stock = \App\Model\StoreProduct::where('store_id', $authUser->store_id)->where('product_id', $product->product_id)->first();

                if (!empty($product['variations'])) {
                    $type = $c['variant'];
                    foreach (json_decode($product['variations'], true) as $var) {
                        if ($type == $var['type'] && $store_product_stock['total_stock'] < $c['quantity']) {
                            Toastr::error($var['type'] . ' ' . translate('is out of stock'));

                            return back();
                        }
                    }
                } else {

                    if (($store_product_stock->total_stock - $c['quantity']) < 0) {
                        Toastr::error($product->productDetail->name . ' ' . translate('is out of stock'));
                        return back();
                    }
                }
            }
        }

        foreach ($cart as $c) {
            if (is_array($c)) {

                $discount_on_product = 0;
                //$product_subtotal = ($c['price']) * $c['quantity'];
                $product_subtotal_mkk = ($c['price']) * $c['quantity'];
                $product_subtotal = $c['with_offer_price'] > 0 ? $c['with_offer_price'] : $product_subtotal_mkk;
                $discount_on_product += ($c['discount'] * $c['quantity']);

                $product = \App\Model\WarehouseProduct::find($c['id']);


                if ($product) {

                    $price = $c['price'];

                    $tax_on_product = Helpers::tax_calculate($product, $price);

                    $category_id = $product->productDetail['category_id'];
                    /* foreach (json_decode($product['category_ids'], true) as $cat) {
                        if ($cat['position'] == 1){
                            $category_id = ($cat['id']);
                        }
                    } */

                    $category_discount = Helpers::category_discount_calculate($category_id, $price);
                    $product_discount = Helpers::discount_calculate($product, $price);
                    if ($c['offer_discount'] > 0) {
                        $discount = $c['offer_discount'];
                        $discount_type = 'offer_discount_on_product';
                    } else {
                        if ($category_discount >= $price) {
                            $discount = $product_discount;
                            $discount_type = 'discount_on_product';
                        } else {
                            $discount = max($category_discount, $product_discount);
                            $discount_type = $product_discount > $category_discount ? 'discount_on_product' : 'discount_on_category';
                        }
                    }
                    // dump($product);
                    $product = Helpers::product_data_formatting($product);
                    $or_d = [
                        'product_id' => $c['id'],
                        'product_details' => $product,
                        'quantity' => $c['quantity'],
                        'price' => $price,
                        'tax_amount' => $tax_on_product,
                        'discount_on_product' => $discount,
                        'discount_type' => $discount_type,
                        'vat_status' => Helpers::get_business_settings('product_vat_tax_status') === 'included' ? 'included' : 'excluded',
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    $total_tax_amount += $or_d['tax_amount'] * $c['quantity'];
                    $product_price += $product_subtotal - $discount_on_product;
                    $order_details[] = $or_d;
                }

                if (in_array($authUser->admin_role_id, [6, 7])) {
                    $storeupdate =   \App\Model\StoreProduct::where(['product_id' => $product['product_id']])->where(['store_id' => $authUser->store_id])->first();
                    $storeupdate->update([
                        'total_stock' => $storeupdate->total_stock - $c['quantity'],
                    ]);
                } elseif ($authUser->admin_role_id == 3) {
                    \App\Model\WarehouseProduct::where(['id' => $product['id']])->update([
                        'total_stock' => $product['total_stock'] - $c['quantity'],
                    ]);
                }
            }
        }
        $total_price = $product_price;
        if (isset($cart['extra_discount'])) {
            $extra_discount = $cart['extra_discount_type'] == 'percent' && $cart['extra_discount'] > 0 ? (($total_price * $cart['extra_discount']) / 100) : $cart['extra_discount'];
            $total_price -= $extra_discount;
        }
        $tax = isset($cart['tax']) ? $cart['tax'] : 0;
        $total_tax_amount = ($tax > 0) ? (($total_price * $tax) / 100) : $total_tax_amount;
        try {
            $order->extra_discount = $extra_discount ?? 0;
            $order->total_tax_amount = $total_tax_amount;
            $order->order_amount = $total_price + $total_tax_amount + $order->delivery_charge;

            $order->coupon_discount_amount = 0.00;

            $order->save();
            // dd($order_details);
            foreach ($order_details as $key => $item) {
                $order_details[$key]['user_warehouse_order_id'] = $order->id;
                $order_details[$key]['tax_amount'] = 0.00;
            }
            $this->order_detail->insert($order_details);
            session()->forget('cart');
            session()->forget('customer_id');
            session()->forget('warehouse_id');
            session(['last_order' => $order->id]);
            Toastr::success(translate('order_placed_successfully'));
            return back();
        } catch (\Exception $e) {
            info($e);
            dd($e);
        }

        Toastr::warning(translate('failed_to_place_order'));
        return back();
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function generate_invoice($id): \Illuminate\Http\JsonResponse
    {

        $order = $this->order->where('id', $id)->first();
        //dd($order);
        return response()->json([
            'success' => 1,
            'view' => view('admin-views.pos.order.invoice', compact('order'))->render(),
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store_keys(Request $request): \Illuminate\Http\JsonResponse
    {
        session()->put($request['key'], $request['value']);
        return response()->json('', 200);
    }

    /** 
     * @param Request $request
     * @return RedirectResponse
     */
    public function new_customer_store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'f_name' => 'required',
            'l_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'nullable|same:confirm_password|min:8',

            'phone' => 'required|unique:users'
        ], [
            'f_name.required' => translate('first name is required'),
            'l_name.required' => translate('last name is required'),
            'email.required' => translate('email name is required'),
            'phone.required' => translate('phone name is required'),
            'email.unique' => translate('email must be unique'),
            'phone.unique' => translate('phone must be unique'),
        ]);
        if ($request->has('image')) {
            $image_name = Helpers::upload('customer/', 'png', $request->file('image'));
        } else {
            $image_name = 'def.png';
        }

        $customer = $this->user;
        $customer->f_name = $request->f_name;
        $customer->l_name = $request->l_name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->image = $image_name;
        $customer->password = $request->password ? Hash::make($request->password) : Hash::make('12345678');
        $customer->save();
        Toastr::success(translate('Customer added successfully!'));
        return redirect()->route('admin.customer.list');
    }

    /**
     * @param Request $request
     * @return string|StreamedResponse
     * @throws IOException
     * @throws InvalidArgumentException
     * @throws UnsupportedTypeException
     * @throws WriterNotOpenedException
     */
    public function export_orders(Request $request)
    {
        $query_param = [];
        $storeId = $warehouse_id = null;
        $authUser = auth('admin')->user();
        if (in_array($authUser->admin_role_id, [6, 7])) {
            $warehouse_id = $authUser->Store->warehouse_id;
            $storeId = $authUser->store_id;
        } elseif (in_array($authUser->admin_role_id, [3, 4])) {
            $warehouse_id = $authUser->warehouse_id;
        }

        $search = $request['search'];

        $branch_id = $request['branch_id'];
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];

        // $query = $this->order->pos()->with(['customer', 'branch'])
        //     ->when((!is_null($branch_id) && $branch_id != 'all'), function ($query) use ($branch_id) {
        //         return $query->where('branch_id', $branch_id);
        //     })
        //     ->when((!is_null($start_date) && !is_null($end_date)), function ($query) use ($start_date, $end_date) {
        //         return $query->whereDate('created_at', '>=', $start_date)
        //             ->whereDate('created_at', '<=', $end_date);
        //     });
        $query = $this->order->pos();
        if (!empty($warehouse_id)) {
            $query = $query->where('warehouse_id', $warehouse_id);
        }

        if (!empty($storeId)) {
            $query = $query->where('store_id', $storeId);
        }
        $query = $query->when((!is_null($start_date) && !is_null($end_date)), function ($query) use ($start_date, $end_date) {
            return $query->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date);
        });
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

        $orders = $query->with('details')->orderBy('id', 'DESC')->get();
        // dd($orders);
        $storage = [];
        foreach ($orders as $order) {
            $vat_status = $order->details[0] ? $order->details[0]->vat_status : '';
            if ($vat_status == 'included') {
                $order_amount = $order['order_amount'] - $order['total_tax_amount'];
            } else {
                $order_amount = $order['order_amount'];
            }

            $store = $order->store_id ? $order->store->name : '';
            $warehouse = $order->warehouse_id ? $order->warehouse->name : '';
            $customer = $order->customer ? $order->customer->f_name . ' ' . $order->customer->l_name : 'Walking Customer';
            $storage[] = [
                'Order Id' => $order['id'],
                'Order Date' => date('d M Y', strtotime($order['created_at'])),
                'Customer' => $customer,
                'Store' => $store,
                'Warehouse' => $warehouse,
                'Order Amount' => $order_amount,
                'Order Status' => $order['order_status'],
                'Order Type' => $order['order_type'],
                'Payment Status' => $order['payment_status'],
                'Payment Method' => $order['payment_method'],
                'Delivery Date' => $order['delivery_date'],
            ];
        }

        return (new FastExcel($storage))->download('xlsx');
    }
    public function available_stock(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $query = $this->product;
        $authUser = auth('admin')->user();
        if ($authUser->admin_role_id == 3) {
            $assign_categories =  $this->warehouse_categories->where('warehouse_id', $authUser->warehouse_id)->pluck('category_id')->toArray();
            $query = $query->whereIn('category_id', $assign_categories);
        }

        $query_param = [];
        $search = $request['search'];
        if ($request->has('search') && $search) {
            $key = explode(' ', $request['search']);
            $query = $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('name', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }
        $warehouse_id =  $authUser->warehouse_id;
        $products = $query->latest()->with('category')->paginate(Helpers::getPagination())->appends($query_param);

        return view('admin-views.pos.stocks.stock', compact('products', 'search', 'warehouse_id', 'authUser'));
    }


    public function fetch_store_stock($store_id)
    {
        $data =  $this->store_products->where('store_id', $store_id)->with(['product', 'product.unit'])->get();
        // Decode JSON data to an associative array
        // return response()->json([
        //     'data' => $data
        // ]);
        return response()->json([
            'view' => view('admin-views.pos.stocks.render_store_stock', compact('data'))->render()
        ]);
    }

    public function prices_wareohuse_stock($warehouse_id)
    {
        $data =  $this->warehouse_products->where('warehouse_id', $warehouse_id)->with('productDetail')->get();
        // Decode JSON data to an associative array
        // return response()->json([
        //     'data' => $data
        // ]);
        return response()->json([
            'view' => view('admin-views.pos.stocks.render_stock', compact('data'))->render()
        ]);
    }
}
