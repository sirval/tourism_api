<?php

namespace App\Services;

use App\Traits\ApiResponsesTrait;
use Illuminate\Support\Facades\Auth;
use App\Models\AvailableTravelOption;
use App\Models\TravelOption;

class TravelOptionService
{
    use ApiResponsesTrait;

    //TODO: ADMIN RELATED METHODS
    public function listAvailableSearchOptions()
    {
        $search_options = AvailableTravelOption::with('travelOption')->paginate(50);
        return $this->successResponse($search_options);
    }

    public function createSearchOption($request)
    {
        try {
            if ($request['min_price_range'] >= $request['max_price_range']) {
                return $this->errorResponse('Oops! Seems you\'re trying to set min price above max price!', 409, false);
            }
           $search_options = new AvailableTravelOption;
           $search_options->travel_option_id  = $request['travel_option_id'];
           $search_options->date              = $request['date'];
           $search_options->location          = $request['location'];
           $search_options->min_price_range   = $request['min_price_range'];
           $search_options->max_price_range   = $request['max_price_range'];
           $search_options->type              = $request['type'];

    
            if ($search_options->save()) {
                return $this->successResponse([], 'Search Criteria Created Successful!', 201);
            }
            return $this->errorResponse('Oops! An error occured when creating your account. Try again later!', 500, false);
            
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500, false);
        }
    }

    public function updateSearchOption($request, $id)
    {
        try {
            $search_options = $this->getSearchOptionById($id);

            if ($search_options === null) {
                return $this->errorResponse('Invalid search option id!', 409, false);
            }
            if ($request['min_price_range'] >= $request['max_price_range']) {
                return $this->errorResponse('Oops! Seems you\'re trying to set min price above max price!', 409, false);
            }

            $search_options->travel_option_id   = $request['travel_option_id'];
            $search_options->date               = $request['date'];
            $search_options->location           = $request['location'];
            $search_options->min_price_range    = $request['min_price_range'];
            $search_options->max_price_range    = $request['max_price_range'];
            $search_options->type               = $request['type'];
    
            if ($search_options->save()) {
                return $this->successResponse([], 'Search Criteria Updated Successfully!', 200);
            }
            return $this->errorResponse('Oops! An error occured. Try again later!', 500, false);
            
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500, false);
        }

    }

    public function showSearchOption($id)
    {
        try {
            return $this->successResponse($this->getSearchOptionById($id));
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500, false);
        }
    }

    public function deleteSearchOption($id)
    {
        try {
            $search_options = $this->getSearchOptionById($id);
            if ($search_options !== null) {
                if ($search_options->delete()) {
                    return $this->successResponse([], 'Search Option Deleted Successfully!', 204);
                }
                return $this->errorResponse('Oops! An error occured. Try again later!', 500, false);
            }
            return $this->errorResponse('Oops! Content with specified ID not found', 404, false);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500, false);
        }
    }

    //TODO: USER RELATED METHODS
    public function getTravelOptions()
    {
        try {
            $travel_options = TravelOption::all();
            return $this->successResponse($travel_options);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500, false);
        }
    }

    public function SearchTravelOption()
    {
        try {
            //define search criteria
            $location   = request('location');
            $date       = request('date');
            $min_price  = request('min_price_range') != 0 ? request('min_price_range') / 100 : request('min_price_range');
            $max_price  = request('max_price_range') != 0 ? request('max_price_range') / 100: request('max_price_range');
            $type       = request('type');
            
            $query = AvailableTravelOption::query();
    
            if ($location) {
                $query->where('location', 'LIKE', '%' . $location . '%');
            }
    
            if ($date) {
                $query->where('date', 'LIKE', '%' . $date . '%');
            }
    
            
           if ($min_price && $max_price) {
            $query->whereBetween('min_price_range', [floatval($min_price), floatval($max_price)])
                    ->orWhereBetween('max_price_range', [floatval($min_price), floatval($max_price)]);
           } 
           
           elseif ($min_price) {
            $query->where('min_price_range', '>=', $min_price);
            } 
            
            elseif ($max_price) {
                $query->where('max_price_range', '<=', $max_price);
            }
    
            if ($type) {
                $query->where('type', 'LIKE', '%' . $type . '%');
            }
    
            //get result
            $results = $query->get();
    
            return $this->successResponse($results);
            
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500, false);
        }
        
    }

    //TODO: SERVICE METHOD
    private function getSearchOptionById($id)
    {
        $available_options = AvailableTravelOption::find($id);
        return $available_options !== null? $available_options : null;
    }
}