<?php

namespace App\Http\Controllers;

use App\Http\Actions\AddToCartItemActions;
use App\Http\Actions\RemoveFromCartActions;
use App\Http\Helpers\ResponseHelper;
use App\Http\Requests\CartItemRequest;
use App\Http\Resources\CartItemCollection;
use App\Models\CartItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    use ResponseHelper;

    public function __construct(private AddToCartItemActions $addToCartItemActions, private RemoveFromCartActions $removeFromCartActions){}

    public function index(): JsonResponse
    {
        $cartItems = CartItem::where('user_id', Auth::id())
            ->whereNull('order_id')->paginate();
        return response()->json(CartItemCollection::make($cartItems)->response()->getData());
    }

    public function addToCart(CartItemRequest $request): JsonResponse
    {
        $response = $this->addToCartItemActions->addToCart($request->all());
        return $this->formattedResponse($response);
    }

    public function removeFromCart(CartItemRequest $request): JsonResponse
    {
        $response = $this->removeFromCartActions->removeFromCart($request->all());
        return $this->formattedResponse($response);
    }

}
