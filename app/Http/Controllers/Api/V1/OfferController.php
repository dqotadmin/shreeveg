<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\FlashDeal;
use App\Model\FlashDealProduct;
use App\Model\Product;
use App\Model\WarehouseProduct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function __construct(
        private FlashDeal $flash_deal,
        private FlashDealProduct $flash_deal_product,
        private Product $product
    ) {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_flash_deal(Request $request): JsonResponse
    {
        try {
            $warehouse_id = @auth('api')->user()->warehouse_id;
            if ($warehouse_id == null ) {
                $warehouse_id = 8;  // Use the assignment operator (=) to set the value
            }
            if($warehouse_id){
                $flash_deals = $this->flash_deal->active()
                ->where('deal_type', 'flash_deal')
                ->get();

            $filtered_flash_deals = $flash_deals->filter(function ($flash_deal) use ($warehouse_id) {
                $warehouse_ids = $flash_deal->warehouse_id; // Assuming warehouse_ids is an array
                return in_array($warehouse_id, $warehouse_ids);
            });
            return response()->json($filtered_flash_deals, 200);
            }
           
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 403);
        }
    }

    /**
     * @param Request $request
     * @param $flash_deal_id
     * @return JsonResponse
     */
    public function get_flash_deal_products(Request $request, $flash_deal_id): JsonResponse
    {
        $qty_amnt = $this->flash_deal_product->get();
        $p_ids = $this->flash_deal_product->with(['product'])
            ->whereHas('product', function ($q) {
                $q->active();
            })
            ->where(['flash_deal_id' => $flash_deal_id])
            ->pluck('product_id')
            ->toArray();
        if (count($p_ids) > 0) {
            $paginator = $this->product->with(['rating'])
                ->whereIn('id', $p_ids)
                ->paginate($request['limit'], ['*'], 'page', $request['offset']);

            $products = [
                'total_size' => $paginator->total(),
                'limit' => $request['limit'],
                'offset' => $request['offset'],
                'products' => $paginator->items()
            ];

            $data = Helpers::getWarehouseProductsdetail();
            $whProducts = $data->whereIn('product_id', $p_ids)->get();


            // Assuming $qty_amnt is an associative array with product_id as keys


            // Update all items with the same product_id
            foreach ($whProducts as &$whProduct) {
                $product_id = $whProduct['product_id'];
                // Find the corresponding item in $qty_amnt based on 'product_id'
                $qty_amnt_item = collect($qty_amnt)->where('product_id', $product_id)->first();
                if ($qty_amnt_item) {
                    // Add 'quantity' and 'amount' keys to each item in $whProducts
                    $whProduct['quantity'] = $qty_amnt_item['quantity'];
                    $whProduct['amount`'] = $qty_amnt_item['amount'];
                }
            }

            $products['products'] = Helpers::apk_product_data_formatting($whProducts, true);
            return response()->json($products, 200);
        }

        return response()->json([], 200);
    }
}
