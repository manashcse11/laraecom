<?php

namespace App\Http\Controllers;

use App\Color;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Http\Resources\Color as ColorResource;
use Validator;

class ColorController extends BaseController
{
    public $resource = "Color";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $colors = Color::all();

        return $this->sendResponse(ColorResource::collection($colors), $this->prepareMessage(__FUNCTION__, $this->resource."s"));
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
            'name' => 'required|unique:colors'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $color = new Color();
        if($this->insert_or_update($request, $color)){
            return $this->sendResponse(new ColorResource($color), $this->prepareMessage(__FUNCTION__, $this->resource));
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
        $color = Color::find($id);

        if (is_null($color)) {
            return $this->sendError($this->prepareMessage('not_found', $this->resource));
        }

        return $this->sendResponse(new ColorResource($color), $this->prepareMessage(__FUNCTION__, $this->resource));
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
    public function update(Request $request, Color $color)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required|unique:sizes,name,' . $color->id
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        if($this->insert_or_update($request, $color)){
            return $this->sendResponse(new ColorResource($color), $this->prepareMessage(__FUNCTION__, $this->resource));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Color $color)
    {
        $color->delete();

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
