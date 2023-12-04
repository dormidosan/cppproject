<?php

namespace App\Console\Commands;

use Aws\Sns\SnsClient;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send SNS to all subscribers in CLOUD';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
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
        $region = env('AWS_DEFAULT_REGION', 'us-east-1');
        $topicArn = env('AWS_SNS_TOPIC_ARN', 'arn:aws:sns:us-east-1:330693592718:x22232991-sns-topic');

        info("Cron Job running at " . now());
        $client = new SnsClient([
            'version' => 'latest',
            'region' => $region,
            'http' => [
                'verify' => str_starts_with(php_uname(), 'Win') ? 'cacert.pem' : '/etc/ssl/certs/ca-certificates.crt'

            ]
        ]);

        //Prefix to know where the mail is produced.
        $prefix = str_starts_with(php_uname(), 'Win') ? 'L: ' : 'C: ';
        $subject = $prefix.$value['subject'];
        $message = $value['message']. " TIME: " .now();

        $result = null;

        Log::info(json_encode([$region,$topicArn]) );

        try {
            $result = $client->publish([
                'TopicArn' => $topicArn,
                'Message' => $message,
                'Subject' => $subject
            ]);
        } catch (\Exception $e) {
            Log::info("Gettingaa => ".$e->getMessage());
        }


        info($result);
    }
}
