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
}
