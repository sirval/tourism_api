<?php

namespace App\Services;

use App\Models\Booking;
use App\Traits\ApiResponsesTrait;
use Carbon\Carbon;

class BookingService
{
    use ApiResponsesTrait;

    //TODO: USER BOOKING METHODS
    public function createBooking($request)
    {
      try {
            if($this->create_booking($request) === true){
                return $this->successResponse([], 'Booking Successful', 201);
            }
            return $this->errorResponse('Oops! An error occured. Try again or contact support', 500, false);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500, false);
        }
    }

    public function getBookings()
    {
        try {
            return $this->listBookings();
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500, false);
        }
    }

    public function cancelBooking($id)
    {
        try {
            $booking = $this->findBookingById($id);
            if ($booking === null) {
                return $this->errorResponse('Booking detail with specified ID not found', 404, false);
            }
            //grant permission for admin only to cancel booking with payment already made
            if ($booking->payment_status === 1 && auth()->user()->role_id === 'user') {
                return $this->errorResponse('Sorry this booking cannot be cancelled, payment already made. If you wish to continue please contact support', 409, false);
            }
            
            $booking->booking_status = Booking::IS_CANCELLED;
            $booking->payment_status = 0;
            $booking->save();

            return $this->successResponse([], 'Booking successfully cancelled');
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500, false);
        }
    }

    public function updateBooking($request, $id)
    {
      try {
            if($this->update_booking($request, $id) === true){
                return $this->successResponse([], 'Booking Updated Successfully', 200);
            }
            return $this->errorResponse('Oops! An error occured. Try again or contact support', 500, false);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500, false);
        }
    }

    public function showBooking($id)
    {
        try {
            $booking = $this->findBookingById($id);
            if ($booking === null) {
                return $this->errorResponse('Booking detail with specified ID not found', 404, false);
            }
            return $this->successResponse($booking, 200);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500, false);
        }
    }

    public function deleteBooking($id)
    {
        try {
            $booking = $this->findBookingById($id);
            if ($booking === null) {
                return $this->errorResponse('Booking detail with specified ID not found', 404, false);
            }
            //restrict deletion of payment with payment already made by user
            if ($booking->payment_status === 1 
                && auth()->user()->role_id === 'user') {
                return $this->errorResponse('Sorry this booking cannot be deleted, payment already made. If you wish to continue please contact support', 409, false);
            }
            if ($booking->delete()) {
                return $this->successResponse([],'Booking deleted successfully', 204); 
            }
            return $this->errorResponse('Oops! Booking couldn\'t be deleted', 500, false);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500, false);
        }
    }
    //TODO: Service Methods - reusable methods
    protected function findBookingById($id): mixed
    {
        $booking = Booking::find($id);
        return $booking !== null ? $booking : null;
    }

    private function listBookings()
    {
        $query = Booking::query();
        if (auth()->user()->role_id === 'user') {
            $result = $query->where('user_id', auth()->user()->id)->whereNot('booking_status', Booking::IS_CANCELLED)->get();
        }else{
            $result = $query->where('booking_status', Booking::IS_ACTIVE)->get();
        }
        
        return $this->successResponse($result, 201);
    }

    private function create_booking($request): bool
    {
        $booking = new Booking;
        $booking->user_id           = $request['user_id'] ?? auth()->user()->id;
        $booking->travel_option_id  = $request['travel_option_id'];
        $booking->from              = $request['from'];
        $booking->to                = $request['to'];
        $booking->phone             = $request['phone'];
        $booking->booking_email     = $request['booking_email'] ?? auth()->user()->email;
        $booking->departure_date    = $request['departure_date'];
        $booking->arrival_date      = $request['arrival_date'];
        $booking->num_guest         = $request['num_guest'];
        $booking->amount            = $request['amount'];

        if($booking->save()){
            return true;
        }
        return false;
    }

    private function update_booking($request, $id)
    {
        $booking = $this->findBookingById($id);
        if ($booking === null) {
            return $this->errorResponse('Booking detail with specified ID not found', 404, false);
        }
        //grant permission for admin only to update booking with payment already made
        if ($booking->payment_status === 1 && auth()->user()->role_id === 'user') {
            return $this->errorResponse('Sorry this booking cannot be updated, payment already made. If you wish to continue please contact support', 409, false);
        }

        $booking->user_id           = $request['user_id'] ?? auth()->user()->id;
        $booking->travel_option_id  = $request['travel_option_id'];
        $booking->from              = $request['from'];
        $booking->to                = $request['to'];
        $booking->phone             = $request['phone'];
        $booking->booking_email     = $request['booking_email'] ?? auth()->user()->email;
        $booking->departure_date    = $request['departure_date'];
        $booking->arrival_date      = $request['arrival_date'];
        $booking->num_guest         = $request['num_guest'];
        $booking->booking_status    = Booking::IS_ACTIVE;

        if($booking->save()){
            return true;
        }
        return false;
    }

    
}