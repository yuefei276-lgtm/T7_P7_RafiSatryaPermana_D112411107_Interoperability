<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /products
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->has('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->has('brand')) {
            $query->where('brand', $request->input('brand'));
        }

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        $products = $query->orderBy('id', 'DESC')->paginate(10);

        return response()->json($products, 200);
    }

    /**
     * Store a newly created resource in storage.
     * POST /products
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:150',
            'sku' => 'required|string|max:50|unique:products,sku',
            'category' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'brand' => 'required|string|max:100',
            'description' => 'required|string',
            'status' => 'in:available,out_of_stock,discontinued',
        ]);
        $input = $request->all();
        if (!isset($input['status'])) {
            $input['status'] = 'available';
        }
        $product = Product::create($input);
        return response()->json($product, 201);
    }
    /**
     * Display the specified resource.
     * GET /products/{id} atau GET /product/{id}
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            abort(404, 'Product not found');
        }

        return response()->json($product, 200);
    }

    /**
     * Update the specified resource in storage.
     * PUT /products/{id} atau PUT /product/{id}
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            abort(404, 'Product not found');
        }
        $this->validate($request, [
            'name' => 'sometimes|required|string|max:150',
            'sku' => 'sometimes|required|string|max:50|unique:products,sku,' . $id,
            'category' => 'sometimes|required|string|max:50',
            'price' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
            'brand' => 'sometimes|required|string|max:100',
            'description' => 'sometimes|required|string',
            'status' => 'sometimes|in:available,out_of_stock,discontinued',
        ]);
        $product->fill($request->all());
        $product->save();
        return response()->json($product, 200);
    }
    /**
     * Remove the specified resource from storage.
     * DELETE /products/{id} atau DELETE /product/{id}
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            abort(404, 'Product not found');
        }

        $product->delete();

        return response()->json([
            'message' => 'deleted successfully',
            'product_id' => (int) $id,
        ], 200);
    }
}
