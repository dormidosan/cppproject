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
    public function searchinfilexml_remove(Request $request)
    {

        $sqlFilePath = base_path('flightdetails10.xml');
        $xml = simplexml_load_file($sqlFilePath);
        //$flightDetails = $xml->xpath('//FlightDetails');

        $flsResponseFields = [
            'FLSOriginCode' => (string)$xml->FLSResponseFields['FLSOriginCode'],
            'FLSOriginName' => (string)$xml->FLSResponseFields['FLSOriginName'],
            'FLSDestinationCode' => (string)$xml->FLSResponseFields['FLSDestinationCode'],
            'FLSDestinationName' => (string)$xml->FLSResponseFields['FLSDestinationName'],
            'FLSStartDate' => (string)$xml->FLSResponseFields['FLSStartDate'],
            'FLSEndDate' => (string)$xml->FLSResponseFields['FLSEndDate'],
            'FLSResultCount' => (string)$xml->FLSResponseFields['FLSResultCount'],
            'FLSRoutesFound' => (string)$xml->FLSResponseFields['FLSRoutesFound'],
            'FLSBranchCount' => (string)$xml->FLSResponseFields['FLSBranchCount'],
            'FLSTargetCount' => (string)$xml->FLSResponseFields['FLSTargetCount'],
            'FLSRecordCount' => (string)$xml->FLSResponseFields['FLSRecordCount'],
        ];

        $flightDetails = [];
        foreach ($xml->FlightDetails as $details) {
            $flight = [
                'TotalFlightTime' => (string)$details['TotalFlightTime'],
                'TotalMiles' => (string)$details['TotalMiles'],
                'TotalTripTime' => (string)$details['TotalTripTime'],
                'FLSDepartureDateTime' => (string)$details['FLSDepartureDateTime'],
                'FLSDepartureTimeOffset' => (string)$details['FLSDepartureTimeOffset'],
                'FLSDepartureCode' => (string)$details['FLSDepartureCode'],
                'FLSDepartureName' => (string)$details['FLSDepartureName'],
                'FLSArrivalDateTime' => (string)$details['FLSArrivalDateTime'],
                'FLSArrivalTimeOffset' => (string)$details['FLSArrivalTimeOffset'],
                'FLSArrivalCode' => (string)$details['FLSArrivalCode'],
                'FLSArrivalName' => (string)$details['FLSArrivalName'],
                'FLSFlightType' => (string)$details['FLSFlightType'],
                'FLSFlightLegs' => (string)$details['FLSFlightLegs'],
                'FLSFlightDays' => (string)$details['FLSFlightDays'],
                'FLSDayIndicator' => (string)$details['FLSDayIndicator'],
                'FLSPrice' => rand(100, 1000),
                'FLSCurrency' => 'EUR',
                'FlightLegDetails' => []
            ];

            foreach ($details->FlightLegDetails as $leg) {
                $flight['FlightLegDetails'][] = [
                    'DepartureDateTime' => (string)$leg['DepartureDateTime'],
                    'FLSDepartureTimeOffset' => (string)$leg['FLSDepartureTimeOffset'],
                    'ArrivalDateTime' => (string)$leg['ArrivalDateTime'],
                    'FLSArrivalTimeOffset' => (string)$leg['FLSArrivalTimeOffset'],
                    'FlightNumber' => (string)$leg['FlightNumber'],
                    'JourneyDuration' => (string)$leg['JourneyDuration'],
                    'SequenceNumber' => (string)$leg['SequenceNumber'],
                    'LegDistance' => (string)$leg['LegDistance'],
                    'FLSMeals' => (string)$leg['FLSMeals'],
                    'FLSInflightServices' => (string)$leg['FLSInflightServices'],
                    'FLSUUID' => (string)$leg['FLSUUID'],
                    'DepartureAirport' => [
                        'CodeContext' => (string)$leg->DepartureAirport['CodeContext'],
                        'LocationCode' => (string)$leg->DepartureAirport['LocationCode'],
                        'FLSLocationName' => (string)$leg->DepartureAirport['FLSLocationName'],
                        'Terminal' => (string)$leg->DepartureAirport['Terminal'],
                        'FLSDayIndicator' => (string)$leg->DepartureAirport['FLSDayIndicator'],
                    ],
                    'ArrivalAirport' => [
                        'CodeContext' => (string)$leg->ArrivalAirport['CodeContext'],
                        'LocationCode' => (string)$leg->ArrivalAirport['LocationCode'],
                        'FLSLocationName' => (string)$leg->ArrivalAirport['FLSLocationName'],
                        'Terminal' => (string)$leg->ArrivalAirport['Terminal'],
                        'FLSDayIndicator' => (string)$leg->ArrivalAirport['FLSDayIndicator'],
                    ],
                    'MarketingAirline' => [
                        'Code' => (string)$leg->MarketingAirline['Code'],
                        'CodeContext' => (string)$leg->MarketingAirline['CodeContext'],
                        'CompanyShortName' => (string)$leg->MarketingAirline['CompanyShortName'],
                    ],
                    'Equipment' => [
                        'AirEquipType' => (string)$leg->Equipment['AirEquipType'],
                    ],
                ];
            }

            $flightDetails[] = $flight;
        }

        // Create the final JSON structure
        //
        /* */
        $flights = [
            'FLSResponseFields' => $flsResponseFields,
            'FlightDetails' => $flightDetails,
        ];

        //dd($jsonData);

        $json = json_encode($flights);
        //dd($flights);
        return view('flights.search', compact('flights'));
        //return response()->json($jsonData);


// Close cURL session
        curl_close($curl);

        return view('flights.search', compact('flights'));
    }

    public function s3function_remove(Request $request)
    {

        //
        //$file = $request->file('file')->store('public/
        //$car = Storage::disk('s3')->put('AAAAAAAAAAexample.txt', 'Contents');


        $files = Storage::disk('s3')->files('s3storage');


        return response()->json([
            'path' => 'yes',
            'msg' => 'success'
        ]);
        //echo "Lambda Function Response: " . $file.  " a\n";

    }

    public function s3filesapilambdas3_remove()
    {


        $fileName = 'mycustom.txt';
        $fileContent = 'testing file content';

        // Path where the file will be created
        $filePath = public_path($fileName);

        // Create the file and write content to it
        file_put_contents($filePath, $fileContent);

        $url = 'https://x3ojbpahgh.execute-api.us-east-1.amazonaws.com/CPPAPIv2/storage';

        $file = $filePath;
        if ($file) {
            // Instantiate GuzzleHTTP client
            $client = new Client();

            // Replace 'YOUR_AWS_API_ENDPOINT' with your actual AWS API Gateway endpoint
            $awsApiGatewayEndpoint = $url;

            try {
                // Send a POST request to AWS API Gateway with the file
                $response = $client->request('POST', $awsApiGatewayEndpoint, [
                    'multipart' => [
                        [
                            'name' => 'file', // Name of the file input in your AWS API
                            'contents' => fopen($filePath, 'r'), // Open file in read mode
                            'filename' => $fileName, // Original file name
                        ],
                    ],
                    // Add any additional headers required by your AWS API Gateway
                    'headers' => [

                        'Content-Type' => 'application/json', // Adjust content type if needed
                        'Accept' => 'application/json'
                        // Add any other required headers
                    ],
                ]);

                // Get the response body
                $result = $response->getBody()->getContents();

                // Handle the API response here
                // ...

                print_r(explode("\n", $result));
                return response()->json(['message' => 'File uploaded successfully']);
            } catch (\Exception $e) {
                // Handle exceptions here
                return response()->json(['error' => $e->getMessage()], 500);
            }
        } else {
            return response()->json(['error' => 'No file uploaded'], 400);
        }


    }


    public function sns_remove()
    {

        $region = env('AWS_DEFAULT_REGION', 'us-east-1');
        $client = new LambdaClient([
            'version' => 'latest',
            'region' => $region
        ]);

        $result = $client->invoke([
            'FunctionName' => 'mylambda',
            'Payload' => json_encode([
                'region_name' => $region,
                'name' => 'aja',
                'subject' => 'Amazon notificacion',
                'email' => 'metahuagen@gmail.com',
                'msg' => 'Finally, some sleep'
            ]),
        ]);

        dd(json_decode($result['Payload']->getContents(), true)['body']);

    }

    public function upload_remove()
    {
        return view('flights.upload');
    }


    public function mylibrary_remove(Request $request)
    {
        $var = new HardProcess();
        $var->getDetails("I want", 5, "Tacos", 3, " people");

    }


    /** Get all the IATA codes from DATABASE */
    public function iata(Request $request)
    {
        $term = $request->input('q');
        $results = null;

        //Get values from elasticache
        try {
            $results = Redis::get($term);
        }catch (\Exception $e) {
            $results = Airport::where('iata', 'LIKE', '%' . $term . '%')
                ->orWhere('name', 'LIKE', '%' . $term . '%')
                ->limit(10)->get();
            $results = ['airports'=>$results,'hit'=>'DATABASE '.$term];
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
            Redis::set($term, json_encode($results));
            $results = ['airports'=>$results,'hit'=>'DATABASE '.$term];
        }

        return response()->json($results);
    }

    /** Search for Flights in API Timetable */
    public function search_timetable(Request $request)
    {
        $origin = $request->input('origin');
        $destination = $request->input('destination');
        $departureDate = date('Ymd', strtotime($request->input('departureDate')));

        // API endpoint and required headers with variables


        // Execute the cURL request
        $apicall = new ApiCall(env('FLIGHT_API_KEY', 'dbfcdd77famsh9459b8b688e207ap14bc90jsn352555900c60'),  env('FLIGHT_API_URL', 'timetable-lookup.p.rapidapi.com'), 'rapidapi' );
        $apicall->setOrigin($origin);
        $apicall->setDestination($destination);
        $apicall->setDepartureDate($departureDate);


        $response = $apicall->executeApi();


        $myHardProcess = new HardProcess("rapidapi");
        $flights = $myHardProcess->getObject($response);

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
