<?php

namespace App\Http\Controllers;

use App\Size;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Http\Resources\Size as SizeResource;
use Validator;

class SizeController extends BaseController
{
    public $resource = "Size";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sizes = Size::all();

        return $this->sendResponse(SizeResource::collection($sizes), $this->prepareMessage(__FUNCTION__, $this->resource."s"));
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
            'name' => 'required|unique:sizes'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $size = new Size();
        if($this->insert_or_update($request, $size)){
            return $this->sendResponse(new SizeResource($size), $this->prepareMessage(__FUNCTION__, $this->resource));
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
        $size = Size::find($id);

        if (is_null($size)) {
            return $this->sendError($this->prepareMessage('not_found', $this->resource));
        }

        return $this->sendResponse(new SizeResource($size), $this->prepareMessage(__FUNCTION__, $this->resource));
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
    public function update(Request $request, Size $size)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required|unique:sizes,name,' . $size->id
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        if($this->insert_or_update($request, $size)){
            return $this->sendResponse(new SizeResource($size), $this->prepareMessage(__FUNCTION__, $this->resource));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Size $size)
    {
        $size->delete();

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
