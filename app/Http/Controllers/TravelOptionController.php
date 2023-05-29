<?php

namespace App\Http\Controllers;

use App\Services\TravelOptionService;
use App\Http\Requests\AdminOptionRequestForm;

class TravelOptionController extends Controller
{
    protected TravelOptionService $service;
    
     /**
    *
    * @return void
    */
   public function __construct(TravelOptionService $service) 
   {
        $this->service = $service;
   }

   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json($this->service->listAvailableSearchOptions());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminOptionRequestForm $request)
    {
        return response()->json($this->service->createSearchOption($request));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json($this->service->showSearchOption($id));
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
        return response()->json($this->service->updateSearchOption($request, $id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response()->json($this->service->deleteSearchOption($id));
    }
    
}
