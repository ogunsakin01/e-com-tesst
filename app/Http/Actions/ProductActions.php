<?php

namespace App\Http\Actions;

use App\Http\Helpers\SearchHelper;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductActions
{
    use SearchHelper;

    public function create(array $request): array
    {
        try {
            DB::beginTransaction();
            $request['user_id'] = Auth()->id();
            $product = Product::query()->create($request);
            if (!$product) throw new Exception('Unable to create product, an internal error occurred');
            DB::commit();
            return [
                'code' => 200,
                'message' => 'Free Periods',
                'data' => $product->toArray()
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
            $update = $product->update($request);
            if (!$update) throw new Exception('Unable to update product, an internal error occurred');
            DB::commit();
            return [
                'code' => 200,
                'message' => 'Free Periods',
                'data' => $product->refresh()->toArray()
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

    public function getAll(): array
    {
        $page = request()->route('page') ?? null;
        $searchTerm = request()->route('search_term') ?? null;
        try {
            $this->searchQuery = Product::query();
            $this->buildSearchParams($page,$searchTerm);
            $this->buildPaginationResponse();
            $this->buildAllPossibleResponse();
            return [
                'code' => 200,
                'message' => 'Products retrieved',
                'data' => $this->getResponse()
            ];
        }catch (\Exception $e) {
            return [
                'message' => $e->getMessage(),
                'code' => in_array($e->getCode(), [500, 422]) ? $e->getCode() : 400,
                'data' => ['errors' => ($e->getCode() == 500) ? $e->getTrace() : []]
            ];
        }
    }
}
