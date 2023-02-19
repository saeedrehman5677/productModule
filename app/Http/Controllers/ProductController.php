<?php

// app/Http/Controllers/ProductController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Http\Resources\ProductResource;
use App\Notifications\ProductAddedNotification;
use Illuminate\Support\Facades\Notification;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->when($request->has('name'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('name') . '%');
            })
            ->when($request->has('user_id'), function ($query) use ($request) {
                $query->where('user_id', $request->input('user_id'));
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return ProductResource::collection($products);
    }

    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'status' => 'required|string',
            'product_type' => 'required|string'
        ]);
        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'status' => $request->status,
            'product_type' => $request->product_type,
            'user_id' => auth()->id() // Assuming authenticated user creates the product
        ]);

        // Send notification to the user who added the product
//        Notification::send(User::find($product->user_id), new ProductAddedNotification($product));

        return new ProductResource($product);
    }

    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required',
            'price' => 'sometimes|required|numeric',
            'status' => 'sometimes|required',
            'product_type' => 'sometimes|required|string'
        ]);

        $product->update($validatedData);

        return new ProductResource($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->noContent();
    }
}
