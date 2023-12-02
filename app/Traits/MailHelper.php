<?php

namespace App\Traits;



use Aws\Lambda\LambdaClient;

trait MailHelper
{

    /**
     * @param $subject
     * @param $email
     * @param $body
     * @return mixed
     */
    protected function sendEmail($subject, $email, $body)
    {


        $client = new LambdaClient([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION', 'us-east-1')
        ]);


        $result = $client->invoke([
            'FunctionName' => env('AWS_LAMBDA_NAME', 'laravelmail'),
            'Payload' => json_encode([
                'mailSubject'=> $subject,
                'email'=>$email,
                'mailBody'=>$body
            ]),
        ]);

        return json_decode($result['Payload']->getContents(),true);
    }


}
