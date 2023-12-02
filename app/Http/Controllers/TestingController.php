<?php

namespace App\Http\Controllers;


use App\Traits\MailHelper;
use Aws\Lambda\LambdaClient;
use Aws\Sns\SnsClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class TestingController extends Controller
{
    //
    use MailHelper;
    public function snsmail(){
        $message_pool = [
            [
                'subject' => 'Get a 5% discount in your next purchase',
                'message' => 'Get a huge discount. The next time you book a flight, just buy as usual and then send a mail to support@flights.com and send your book number to receive the discount'
            ],
            [
                'subject' => 'Upload your memories',
                'message' => 'Remember you can upload pictures in "My Flights" section, by selecting one of your past trips. Happy new trip'
            ],
            [
                'subject' => 'There is always time for a trip',
                'message' => 'Try to book a flight to any of +7000 destinies, and explore new places by booking in Flieghts. Explore more! '
            ]
        ];

        $key = array_rand($message_pool);
        $value = $message_pool[$key];
        $region = env('AWS_DEFAULT_REGION', 'forge');
        $topicArn = env('AWS_SNS_TOPIC_ARN', 'forge');

        info("Cron Job running at " . now());
        $client = new SnsClient([
            'version' => 'latest',
            'region' => $region,
            'http' => [
                'verify' => base_path('cacert.pem')//'/etc/ssl/certs/ca-certificates.crt'
            ]
        ]);
        $subject = $value['subject'];
        $message = $value['message'];


        $result = $client->publish([
            'TopicArn' => $topicArn,
            'Message' => $message,
            'Subject' => $subject
        ]);
        dd($result);
    }

    public function subscribesns(Request $request)
    {
        $snsClient = new SnsClient([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION', 'forge')
        ]);

        $topicArn = env('AWS_SNS_TOPIC_ARN', 'forge');
        $endpoint = 'carlosnoyola0411@gmail.com';


        $response = $snsClient->subscribe([
            'TopicArn' => $topicArn,
            'Protocol' => 'email',
            'Endpoint' => $endpoint,
        ]);


        return response()->json(['message' => 'Subscriber added successfully']);
    }

    public function lambda()
    {

        $region = env('AWS_DEFAULT_REGION', 'forge');

        $client = new LambdaClient([
            'version' => 'latest',
            'region'  => $region
        ]);


        $result = $client->invoke([
            'FunctionName' => env('AWS_LAMBDA_NAME', 'forge'),
            'Payload' => json_encode([
                'region_name'=> $region,
                'mailSubject'=>'Amazon notificacion',
                'email'=>'metahuargen@gmail.com',
                'mailBody'=>'Finally, some sleep'.now()
            ]),
        ]);

        dd(json_decode($result['Payload']->getContents(),true));

    }

    public function traitstesting(){
        echo Auth::user()->email;
        var_dump($this->sendEmail('Testing fake mail', Auth::user()->email , 'tesitng '));
    }

    public function testredis(){
        Redis::set('MEX', "value123");
        Redis::set('DUB', json_encode(["cadena","dos"]));
        $cachedBlog = Redis::get('a');
        dd($cachedBlog);
    }

}
