<?php

namespace App\Http\Controllers;

use App\Http\Actions\ProductActions;
use App\Http\Helpers\ResponseHelper;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    use ResponseHelper;

    public function __construct(private ProductActions $productActions){}

    public function index(): JsonResponse
    {
        $products = Product::paginate();
        return response()->json(ProductCollection::make($products)->response()->getData());
    }

    public function create(CreateProductRequest $request): JsonResponse
    {
        $response = $this->productActions->create($request->all());
        return $this->formattedResponse($response);
    }

    public function update(Product $product, UpdateProductRequest $request): JsonResponse
    {
        $response = $this->productActions->update($product, $request->all());
        return $this->formattedResponse($response);
    }

    public function get(Product $product): JsonResponse
    {
        return $this->formattedResponse([
            'message' => 'Product retrieved',
            'data' => ProductResource::make($product),
            'status' => 200
        ]);
    }

    public function delete(Product $product): JsonResponse
    {
        $product->delete();
        return $this->formattedResponse([
            'message' => 'Product safely deleted',
            'status' => 200,
            'data' => []
        ]);
    }

}
