<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Traits\MailHelper;
use Aws\Sns\SnsClient;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    use MailHelper;
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        //CPP Subscribe to the topic for new Users
        self::subscribeTopic($request->email);

        $this->sendEmail('Registration completed in Flieghts', $request->email , 'Thank you for register in Flieghts');


        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    /**
     * Subscribe to the global SNS topic, to receive notifications
     * @param $email
     * @return void
     */
    private function subscribeTopic($email){
        $snsClient = new SnsClient([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1')
        ]);

        $topicArn = env('AWS_SNS_TOPIC_ARN', 'arn:aws:sns:us-east-1:330693592718:x22232991-sns-topic');
        $endpoint = $email;

        $response = $snsClient->subscribe([
            'TopicArn' => $topicArn,
            'Protocol' => 'email',
            'Endpoint' => $endpoint,
        ]);
        Log::channel('awslogs')->info("Getting => ".$response );
    }
}
