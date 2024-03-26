<?php

namespace App\Http\Controllers;

use App\Http\Actions\CreateOrderActions;
use App\Http\Helpers\ResponseHelper;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Resources\OrderCollection;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    use ResponseHelper;

    public function __construct(private CreateOrderActions $createOrderActions){}

    public function index(): JsonResponse
    {
        $orders = Order::where('user_id', Auth::id())->paginate();
        return response()->json(OrderCollection::make($orders)->response()->getData());
    }

    public function create(CreateOrderRequest $request): JsonResponse
    {
        $response = $this->createOrderActions->placeOrder($request->all());
        return $this->formattedResponse($response);
    }
}
