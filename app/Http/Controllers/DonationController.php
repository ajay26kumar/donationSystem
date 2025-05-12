<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class DonationController extends Controller
{
    public function showForm()
    {
        return view('donation');
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'donor_name'     => 'nullable|string|max:255',
            'donor_email'    => 'required|email',
            'missionary'     => 'required|string',
            'donation_type'  => 'required|in:one-time,monthly',
            'amount'         => 'required|numeric|min:1',
            'message'        => 'nullable|string|max:1000',
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $validated['amount'] * 100, // Convert to cents
                'currency' => 'usd',
                'payment_method' => $validated['payment_method'],
                'confirmation_method' => 'automatic',
                'confirm' => true,
                'receipt_email' => $validated['donor_email'],
            ]);

            // Optional: Store the donation
             Donation::create($validated);

            return redirect()->route('donation.form')->with('success', 'Payment successful! Thank you for your donation.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Payment failed: ' . $e->getMessage()]);
        }
    }

    public function checkout(Request $request)
    {
        $data =  $request->validate([
            'donor_name'     => 'nullable|string|max:255',
            'donor_email'    => 'required|email',
            'missionary'     => 'required|string',
            'donation_type'  => 'required|in:one-time,monthly',
            'amount'         => 'required|numeric|min:1',
            'message'        => 'nullable|string|max:1000',
        ]);

         $donationInserted = Donation::create($data);
         $lastId = $donationInserted->id;   
        return view('checkout', [
            'donation' => $data['amount'],
            'name' => $data['donor_name'],
            'email' => $data['donor_email'],
            'missionary' => $data['missionary'],
            'order_id' =>$lastId
        ]);
    }

    public function verifyPayment(Request $request)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return redirect()->route('donation.cancel')->with('error', 'Missing session ID.');
        }

        try {
            $session = \Stripe\Checkout\Session::retrieve([
                'id' => $sessionId,
                'expand' => ['payment_intent'],
            ]);

            $paymentIntent = $session->payment_intent;

            if ($paymentIntent->status === 'succeeded') {
                // Save data to DB
                // Donation::create([
                //     'name' => $session->metadata->name,
                //     'email' => $session->customer_email,
                //     'amount' => $session->amount_total / 100,
                //     'tip' => $session->metadata->tip,
                //     'missionary' => $session->metadata->missionary,
                //     'anonymous' => $session->metadata->anonymous,
                //     'stripe_id' => $session->id,
                // ]);

                Donation::where('id', $session->metadata->order_id)->update(['stripe_id' => $session->id,'tip' => $session->metadata->tip]);


                return view('success', ['session' => $session]);
            } else {
                return redirect()->route('donation.cancel')->with('error', 'Payment not successful.');
            }

        } catch (\Exception $e) {
            return redirect()->route('donation.cancel')->with('error', 'Error verifying payment.');
        }
    }


    public function createCheckoutSession(Request $request)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $donationAmount = $request->input('donation');
        $tipAmount = $request->input('tip', 0);
        $totalAmount = $donationAmount + $tipAmount;
        $totalAmountInCents = intval(round($totalAmount * 100));

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card', 'ideal', 'link'],
            'mode' => 'payment',
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Donation to Nightbright',
                        ],
                        'unit_amount' => $totalAmountInCents,
                    ],
                    'quantity' => 1,
                ],
            ],
            'customer_email' => $request->input('email'),
            'success_url' => route('donation.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('donation.cancel'),
            'metadata' => [
                'donation' => $donationAmount,
                'tip' => $tipAmount,
                'name' => $request->input('name'),
                'missionary' => $request->input('missionary'),
                'order_id' => $request->input('order_id')
            ],
        ]);

        return redirect($session->url);
    }

}
