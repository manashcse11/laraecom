<?php

namespace App\Http\Controllers;

use App\Variation;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Http\Resources\Variation as VariationResource;
use Validator;

class VariationController extends BaseController
{
    public $resource = "Variation";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $variations = Variation::all();

        return $this->sendResponse(VariationResource::collection($variations), $this->prepareMessage(__FUNCTION__, $this->resource."s"));
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
            'name' => 'required|unique:variations'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $variation = new Variation();
        if($this->insert_or_update($request, $variation)){
            return $this->sendResponse(new VariationResource($variation), $this->prepareMessage(__FUNCTION__, $this->resource));
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
        $variation = Variation::find($id);

        if (is_null($variation)) {
            return $this->sendError($this->prepareMessage('not_found', $this->resource));
        }

        return $this->sendResponse(new VariationResource($variation), $this->prepareMessage(__FUNCTION__, $this->resource));
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
    public function update(Request $request, Variation $variation)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required|unique:variations,name,' . $variation->id
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        if($this->insert_or_update($request, $variation)){
            return $this->sendResponse(new VariationResource($variation), $this->prepareMessage(__FUNCTION__, $this->resource));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Variation $variation)
    {
        $variation->delete();

        return $this->sendResponse([], $this->prepareMessage(__FUNCTION__, $this->resource));
    }

    public function insert_or_update($request, $obj){
        $obj->name = $request->name;
        if($obj->save()){
            return true;
        }
        return false;
    }

}
