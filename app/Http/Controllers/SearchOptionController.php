<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminOptionRequestForm;
use App\Services\TravelOptionService;

class SearchOptionController extends Controller
{
    protected TravelOptionService $service;

    /**
    *
    * @return void
    */
   public function __construct(TravelOptionService $service) 
   {
        $this->middleware(['auth:api', 'acl:user'], ['except' => ['index', 'filter']]);
        $this->service = $service;
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json($this->service->getTravelOptions());
    }
    
    public function filter()
    {
        return response()->json($this->service->SearchTravelOption());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminOptionRequestForm $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
    public function update(AdminOptionRequestForm $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
