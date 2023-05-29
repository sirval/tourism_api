<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingFormRequest;
use App\Services\BookingService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    protected BookingService $service;

    /**
    *
    * @return void
    */
   public function __construct(BookingService $service) 
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
        return response()->json($this->service->getBookings());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookingFormRequest $request)
    {
        return response()->json($this->service->createBooking($request));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json($this->service->showBooking($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BookingFormRequest $request, $id)
    {
        return response()->json($this->service->updateBooking($request, $id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response()->json($this->service->deleteBooking($id));
    }

    public function cancelBooking($id)
    {
        return response()->json($this->service->cancelBooking($id));
    }
}
