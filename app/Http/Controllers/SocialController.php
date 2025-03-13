<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use App\Models\User;
use App\Models\CardDetail;
use Stripe\Checkout\Session;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Stripe\Checkout\Session as StripeSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;  // For logging errors
use Stripe\PaymentMethod;


class SocialController extends Controller
{
    // Function to show subscription plans
    public function index()
    {
        $plans = SubscriptionPlan::all()->map(function ($plan) {
            if ($plan->trial_days == 0) {
                $plan->trial_days = null;
            }
            return $plan;
        });

        return view('subscription', compact('plans'));
    }

    // // Create Stripe Checkout Session
    // public function createCheckoutSession(Request $request)
    // {
    //     try {
    //         // Validate the input to ensure required fields are present
    //         $request->validate([
    //             'planName' => 'required|string',
    //             'amount' => 'required|numeric|min:1',
    //         ]);

    //         Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

    //         $checkoutSession = Session::create([
    //             'payment_method_types' => ['card'],
    //             'line_items' => [[
    //                 'price_data' => [
    //                     'currency' => 'usd',
    //                     'product_data' => [
    //                         'name' => $request->input('planName'),
    //                     ],
    //                     'unit_amount' => $request->input('amount') * 100, // Convert to cents
    //                 ],
    //                 'quantity' => 1,
    //             ]],
    //             'mode' => 'payment',
    //             'success_url' => route('payment-success') . '?session_id={CHECKOUT_SESSION_ID}',
    //             'cancel_url' => route('payment.cancel'),
    //         ]);

    //         return response()->json(['id' => $checkoutSession->id]);
    //     } catch (\Exception $e) {
    //         Log::error('Checkout Session Error: ' . $e->getMessage());
    //         return response()->json(['error' => 'An error occurred while creating the checkout session.'], 500);
    //     }
    // }

    // // Payment success handling
    // public function paymentSuccess(Request $request)
    // {
    //     try {
    //         // Get the Stripe session ID from the request
    //         $sessionId = $request->query('session_id');

    //         if (!$sessionId) {
    //             return response()->json(['error' => 'Session ID is missing.'], 400);
    //         }

    //         // Set Stripe API key
    //         Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

    //         // Retrieve the session details
    //         $session = StripeSession::retrieve($sessionId);

    //         // Extract details from the session
    //         $customerId = $session->customer;
    //         $paymentMethod = $session->payment_method;

    //         // Retrieve payment method details
    //         $paymentMethodDetails = \Stripe\PaymentMethod::retrieve($paymentMethod);

    //         // Assuming card details are available from the payment method
    //         $cardDetails = $paymentMethodDetails->card;

    //         // Ensure the user is authenticated before storing the card details
    //         if (auth()->check()) {
    //             // Store card details in the CardDetail model
    //             CardDetail::create([
    //                 'user_id' => auth()->id(),  // Store the authenticated user ID
    //                 'customer_id' => $customerId,
    //                 'card_id' => $paymentMethod,
    //                 'name' => $cardDetails->name ?? 'N/A',
    //                 'card_no' => '**** **** **** ' . substr($cardDetails->last4, -4), // Only store last 4 digits
    //                 'brand' => $cardDetails->brand,
    //                 'month' => $cardDetails->exp_month,
    //                 'year' => $cardDetails->exp_year,
    //             ]);

    //             return response()->json(['message' => 'Payment successful, card details stored']);
    //         } else {
    //             // If the user is not authenticated, redirect to login
    //             return response()->json(['error' => 'User not authenticated. Please log in.'], 401);
    //         }
    //     } catch (\Exception $e) {
    //         Log::error('Payment Success Error: ' . $e->getMessage());
    //         return response()->json(['error' => 'An error occurred while processing the payment.'], 500);
    //     }
    // }

   // Create Stripe Checkout Session
 public function createCheckoutSession(Request $request)
 {
    try {
        // Validate the input to ensure required fields are present
        $request->validate([
            'planName' => 'required|string',
            'amount' => 'required|numeric|min:1',
        ]);

        // Set Stripe API key
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        // Step 1: Create a Stripe Checkout Session
        $checkoutSession = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $request->input('planName'),
                    ],
                    'unit_amount' => $request->input('amount') * 100, // Convert to cents
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment-success') . '?session_id={CHECKOUT_SESSION_ID}', // Redirect URL after payment
            'cancel_url' => route('payment.cancel'), // Redirect URL if payment is canceled
        ]);

        // Step 2: Handle payment success (this part is typically triggered via a webhook or redirect URL)
        $sessionId = $checkoutSession->id;

        // Retrieve the session details
        $session = StripeSession::retrieve($sessionId);

        // Extract details from the session
        $customerId = $session->customer;
        $paymentMethod = $session->payment_method;

        // Retrieve payment method details
        $paymentMethodDetails = PaymentMethod::retrieve($paymentMethod);

        // Assuming card details are available from the payment method
        $cardDetails = $paymentMethodDetails->card;

        // Ensure the user is authenticated before storing the card details
        if (auth()->check()) {
            // Store card details in the CardDetail model
            CardDetail::create([
                'user_id' => auth()->id(),  // Store the authenticated user ID
                'customer_id' => $customerId,
                'card_id' => $paymentMethod,
                'name' => $cardDetails->name ?? 'N/A',
                'card_no' => '**** **** **** ' . $cardDetails->last4, // Only store last 4 digits
                'brand' => $cardDetails->brand,
                'month' => $cardDetails->exp_month,
                'year' => $cardDetails->exp_year,
            ]);

            return response()->json([
                'message' => 'Payment successful, card details stored',
                'session_id' => $sessionId,
            ]);
        } else {
            // If the user is not authenticated, redirect to login
            return response()->json(['error' => 'User not authenticated. Please log in.'], 401);
        }
    } catch (\Stripe\Exception\ApiErrorException $e) {
        // Handle Stripe-specific errors
        Log::error('Stripe API Error: ' . $e->getMessage());
        return response()->json(['error' => 'Stripe API Error: ' . $e->getMessage()], 500);
    } catch (\Exception $e) {
        // Handle generic errors
        Log::error('Payment Error: ' . $e->getMessage());
        return response()->json(['error' => 'An error occurred while processing the payment: ' . $e->getMessage()], 500);
    }
}


    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {

        try{
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->id)->first();
            if ($user) {
                   Auth::login($user);
                   return redirect()->route('dashboard');
               } else {
                   $userData = User::create([
                       'name' => $googleUser->name,
                       'email' => $googleUser->email,
                       'password' => Hash::make('password'),
                       'google_id' => $googleUser->id,
                   ]);

                   if ($userData) {
                       Auth::login($userData);
                       return redirect()->route('dashboard');
                   }

            }

           dd($googleUser);
           // Handle user information after successful authentication

        }catch(\Exception $e){
            return redirect()->route('login')->with('error', 'Something went wrong');
        }

    }

    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleGithubCallback()
    {
        $user = Socialite::driver('github')->user();
        // Handle user information after successful authentication
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        $user = Socialite::driver('facebook')->user();
        // Handle user information after successful authentication
    }
}
