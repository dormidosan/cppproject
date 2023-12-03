<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use App\Models\Flight;


use App\Models\Travel;
use App\Traits\MailHelper;
use GuzzleHttp\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use GetMarks\HardProcess;
use GetMarks\ApiCall;
use Aws\Lambda\LambdaClient;

class FlightController extends Controller
{
    use MailHelper;

    /** Get all the IATA codes from DATABASE */
    public function iata(Request $request)
    {
        $term = $request->input('q');
        $results = null;

        //Get values from AWS ElastiCache
        try {
            $results = Redis::get($term);
        }catch (\Exception $e) {
            $results = Airport::where('iata', 'LIKE', '%' . $term . '%')
                ->orWhere('name', 'LIKE', '%' . $term . '%')
                ->limit(10)->get();
            $results = ['airports'=>$results,'hit'=>'DATABASE '.$term.' Redis Failed' . $e->getMessage()];
            return response()->json($results);
        }


        if(isset($results)){
            $results = json_decode($results);
            $results = ['airports'=>$results,'hit'=>'REDIS '.$term];
        }else{
            $results = Airport::where('iata', 'LIKE', '%' . $term . '%')
                ->orWhere('name', 'LIKE', '%' . $term . '%')
                ->limit(10)->get();

            //Save values from elasticache
            //In try catch providing Redis connection fails
            try {
                Redis::set($term, json_encode($results));
            }catch(\Exception $e){
                info("Redis connection failed");
            }
            $results = ['airports'=>$results,'hit'=>'DATABASE '.$term];
        }

        return response()->json($results);
    }

    /** Search for Flights in API Timetable */
    public function search_timetable(Request $request)
    {
        /**
         * Get all variables from GET Method
         */
        $origin = $request->input('origin');
        $destination = $request->input('destination');
        $departureDate = date('Ymd', strtotime($request->input('departureDate')));


        /**
         * API Supported : Rapidapi
         * Create a new instance of ApiCall to call the API we want to use for flights
         * Sending the key and the host to call the API
         * Retrieve the key and host from the Environment values for production
         */
        $apicall = new ApiCall(env('FLIGHT_API_KEY', 'dbfcdd77famsh9459b8b688e207ap14bc90jsn352555900c60'),  env('FLIGHT_API_URL', 'timetable-lookup.p.rapidapi.com'), 'rapidapi' );

        /**
         * Set the parameters to call the API
         */
        $apicall->setOrigin($origin);
        $apicall->setDestination($destination);
        $apicall->setDepartureDate($departureDate);


        /**
         * Execute the API from external library.
         * Get the results as XML
         */
        $response = $apicall->executeApi();


        /**
         * If needed, create a new HardProcces, to convert XML from a specific API flight
         * to JSON format.
         * API supported: rapidapi
         */
        $myHardProcess = new HardProcess("rapidapi");
        $flights = $myHardProcess->getObject($response);


        /**
         * Return results to the view
         */
        return view('flights.search', compact('flights'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {

        $flights = array();//Flight::take(20)->get();

        return view('flights.index', compact('flights'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {

        return view('flights.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) // :requestResponse
    {

        $flightData = $request->input('flight_data');
        $decodedFlightData = json_decode($flightData, true);

        $travel = new Travel();
        $email = Auth::user()->email;


        $travel->user_id = Auth::id();
        $travel->departure_iata = $decodedFlightData['FLSDepartureCode'];
        $travel->arrival_iata = $decodedFlightData['FLSArrivalCode'];
        $travel->departure_date = date('Y-m-d', strtotime($decodedFlightData['FLSDepartureDateTime']));
        $travel->arrival_date = date('Y-m-d', strtotime($decodedFlightData['FLSArrivalDateTime']));
        $travel->price = $decodedFlightData['FLSPrice'];


        $previous_record = Travel::where('user_id', $travel->user_id)
            ->where('departure_iata', $travel->departure_iata)
            ->where('arrival_iata', $travel->arrival_iata)
            ->whereDate('departure_date', $travel->departure_date)
            ->first();

        if (!$previous_record) {
            $travel->save();
            $this->sendEmail('Flight '.$travel->departure_iata.' => '.$travel->arrival_iata. ' booked', $email , 'Thank for booking with Flieghts');
            return redirect()->route('flights.index')
                ->with('success', 'Flight has been saved successfully.');
        } else {
            return redirect()->route('flights.index')
                ->with('error', 'Failed to save the flight. You already have a ticket from ' .
                    $travel->departure_iata . " to" .
                    $travel->arrival_iata . " the " .
                    date('d/M h:m A', strtotime($travel->departure_date)));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Flight $flight): view
    {
        return view('flights.show', compact('flight'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Flight $flight): view
    {
        return view('flights.edit', compact('flight'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Flight $flight): RedirectResponse
    {
        return redirect()->route('flights.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Flight $flight): RedirectResponse
    {
        return redirect()->route('flights.index');
    }


}
