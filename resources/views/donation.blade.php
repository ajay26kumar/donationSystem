@extends('layouts.app')
@section('title', 'Donation')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
 @if ($errors->any())
            <div class="bg-red-100 p-2 mb-4 text-red-700">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

@section('content')
<div class="donation-container">
    <h2>Missionary Donation</h2>
    
    <div class="donation-type-toggle">
        <button id="oneTimeBtn" class="active">One-Time</button>
        <button id="monthlyBtn">Monthly</button>
    </div>

    <form method="POST" action="{{ route('donation.submit') }}">
        @csrf

        <div class="input-row">
            <input type="text" name="donor_name" placeholder="Donor's Name">
            <div class="input-with-error">
                <input type="email" name="donor_email" placeholder="Donor's Email" required>
                <span class="error-message">Email is required</span>
            </div>
        </div>

        <select name="missionary" class="missionary-dropdown">
            <option value="brandon">Brandon Croley</option>
            <!-- Add more options if needed -->
        </select>

        <div class="amount-options">
            @foreach([10, 25, 50, 100, 250, 500, 1000] as $amount)
                <button type="button" class="amount-btn" data-amount="{{ $amount }}">{{ $amount }}$</button>
            @endforeach
            <input type="number" name="custom_amount" placeholder="Other" class="amount-other" min="1">
        </div>

        <textarea name="message" placeholder="Write a message..."></textarea>

        <label class="stay-anonymous">
            <input type="checkbox" name="anonymous" checked>
            Stay Anonymous
        </label>

        <input type="hidden" name="donation_type" value="one-time" id="donationTypeInput">
        <input type="hidden" name="amount" id="selectedAmount">

        <div id="card-element" class="StripeElement"></div>
<div id="card-errors" class="text-danger mt-2"></div>

<button id="submit-button" class="continue-btn mt-3">Continue</button>

    </form>
</div>
@endsection
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('{{ config("services.stripe.key") }}');
    const elements = stripe.elements();

    const style = {
        base: {
            color: "#32325d",
            fontFamily: "'Segoe UI', sans-serif",
            fontSmoothing: "antialiased",
            fontSize: "16px",
            "::placeholder": { color: "#aab7c4" }
        },
        invalid: {
            color: "#fa755a",
            iconColor: "#fa755a"
        }
    };

    const card = elements.create("card", { style: style });
    card.mount("#card-element");

    card.on("change", event => {
        const displayError = document.getElementById("card-errors");
        displayError.textContent = event.error ? event.error.message : "";
    });

    const form = document.querySelector("form");
    const submitBtn = document.getElementById("submit-button");

    form.addEventListener("submit", async (e) => {
        e.preventDefault();
        submitBtn.disabled = true;

        const { paymentMethod, error } = await stripe.createPaymentMethod({
            type: 'card',
            card: card,
            billing_details: {
                name: form.donor_name.value,
                email: form.donor_email.value,
            }
        });

        if (error) {
            document.getElementById("card-errors").textContent = error.message;
            submitBtn.disabled = false;
        } else {
            const hiddenInput = document.createElement("input");
            hiddenInput.setAttribute("type", "hidden");
            hiddenInput.setAttribute("name", "payment_method");
            hiddenInput.setAttribute("value", paymentMethod.id);
            form.appendChild(hiddenInput);

            form.submit();
        }
    });
</script>

