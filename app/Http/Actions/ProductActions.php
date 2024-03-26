<?php

namespace App\Http\Actions;

use App\Http\Helpers\SearchHelper;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductActions
{
    use SearchHelper;

    public function create(array $request): array
    {
        try {
            DB::beginTransaction();
            $request['user_id'] = Auth()->id();
            $request['slug'] = uniqid().'-'.Str::slug($request['name']);
            $product = Product::query()->create($request);
            if (!$product) throw new Exception('Unable to create product, an internal error occurred');
            DB::commit();
            return [
                'code' => 200,
                'message' => 'Product created',
                'data' => ProductResource::make($product)
            ];
        } catch (Exception $e) {
            Db::rollBack();
            return [
                'message' => $e->getMessage(),
                'code' => in_array($e->getCode(), [500, 422]) ? $e->getCode() : 400,
                'data' => ['errors' => ($e->getCode() == 500) ? $e->getTrace() : []]
            ];
        }
    }

    public function update(Product $product, array $request): array
    {
        try {
            DB::beginTransaction();
            if(!is_null($request['name'])) $request['slug'] = uniqid().'-'.Str::slug($request['name']);
            $update = $product->update($request);
            if (!$update) throw new Exception('Unable to update product, an internal error occurred');
            DB::commit();
            return [
                'code' => 200,
                'message' => 'Product info updated',
                'data' => ProductResource::make($product->refresh())
            ];
        } catch (Exception $e) {
            Db::rollBack();
            return [
                'message' => $e->getMessage(),
                'code' => in_array($e->getCode(), [500, 422]) ? $e->getCode() : 400,
                'data' => ['errors' => ($e->getCode() == 500) ? $e->getTrace() : []]
            ];
        }
    }


}
