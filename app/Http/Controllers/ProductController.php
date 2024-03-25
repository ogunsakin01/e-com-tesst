<?php

namespace App\Http\Controllers;

use App\Http\Actions\ProductActions;
use App\Http\Helpers\ResponseHelper;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    use ResponseHelper;

    public function __construct(private ProductActions $productActions){}

    public function index(): JsonResponse
    {
        $response = $this->productActions->getAll();
        return $this->formattedResponse($response);
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
            'data' => $product,
            'status' => 200
        ]);
    }

    public function delete(Product $product): JsonResponse
    {
        $product->delete();
        return $this->formattedResponse([
            'message' => 'Product safely deleted',
            'status' => 200
        ]);
    }

}
