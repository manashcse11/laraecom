<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductVariation;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Http\Resources\Product as ProductResource;
use Validator;

class ProductController extends BaseController
{
    public $resource = "Product";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $prod = new Product();
        $products = $prod->getProducts($request->all());

        return $this->sendResponse(ProductResource::collection($products), $this->prepareMessage(__FUNCTION__, $this->resource."s"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|unique:products',
            'base_price' => 'required|numeric',
            'product_variations.*.in_stock' => 'required|numeric',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $products = new Product();
        if($this->insert_or_update($request, $products)){
            return $this->sendResponse(new ProductResource($products->find($products->id)->with('category', 'product_variations')->first()), $this->prepareMessage(__FUNCTION__, $this->resource));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id)->with('category', 'product_variations')->first();
        if (is_null($product)) {
            return $this->sendError($this->prepareMessage('not_found', $this->resource));
        }

        return $this->sendResponse(new ProductResource($product), $this->prepareMessage(__FUNCTION__, $this->resource));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'category_id' => 'required|exists:categories',
            'name' => 'required|unique:products,name,' . $product->id
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        if($this->insert_or_update($request, $product)){
            return $this->sendResponse(new ProductResource($product), $this->prepareMessage(__FUNCTION__, $this->resource));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return $this->sendResponse([], $this->prepareMessage(__FUNCTION__, $this->resource));
    }

    public function insert_or_update($request, $obj){
        $obj->category_id = $request->category_id;
        $obj->name = $request->name;
        $obj->description = $request->description;
        $obj->base_price = $request->base_price;
        if($obj->save()){
            if($request->product_variations){
                $variations = array();
                foreach($request->product_variations as $pv){
                    $variations[] = new ProductVariation(['size_id' => $pv['size_id'], 'color_id' => $pv['color_id'], 'in_stock' => $pv['in_stock']]);
                }
                $obj->product_variations()->saveMany($variations);
            }
            return true;
        }
        return false;
    }

}
