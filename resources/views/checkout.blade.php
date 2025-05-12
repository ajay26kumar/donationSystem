<!DOCTYPE html>
<html>
<head>
    <title>Final Details</title>
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        .box { margin: 20px; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .flex { display: flex; justify-content: space-between; align-items: center; }
    </style>
</head>
<body>
    <form id="payment-form" method="POST" action="{{ route('donation.checkout') }}">
        @csrf

        <div class="box">
            <h3>Final Details</h3>
            <div class="flex">
                <span>Donation</span>
                <strong>${{ $donation }}</strong>
            </div>

            <div class="flex">
                <span>Processing Fee</span>
                <strong>$0.00</strong>
            </div>

            <label for="payment-method-type">Select Payment Method</label>
            <select id="payment-method-type" name="payment_type">
                <option value="card">Card</option>
            </select>

            <div id="card-element-container">
                <div id="card-element"></div>
            </div>

            <div id="ideal-element-container" style="display: none;">
                <div id="ideal-bank-element"></div>
            </div>
        </div>

        <div class="box" style="background-color: #fffcd6;">
            <div class="flex">
                <strong>Add a tip to support Night Bright</strong>
                <select id="tip-select" name="tip">
                    <option value="0">No Tip</option>
                    <option value="{{ $donation * 0.05 }}">5%</option>
                    <option value="{{ $donation * 0.1 }}">10%</option>
                    <option value="{{ $donation * 0.12 }}" selected>12%</option>
                </select>
            </div>
            <small>Night Bright does not charge platform fees. Your tip supports the platform.</small>
        </div>

        <label>
            <input type="checkbox" name="allow_contact" checked>
            Allow Night Bright to contact me
        </label>

        <input type="hidden" name="payment_method" id="payment-method">
        <input type="hidden" name="order_id" value="{{ $order_id }}">
        <input type="hidden" name="donation" value="{{ $donation }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <button type="submit" style="background: #a87c2b; color: white; padding: 12px 24px; border: none; border-radius: 8px;">
            Finish ($<span id="total-amount">{{ $donation + ($donation * 0.12) }}</span>)
        </button>
    </form>

    <script>
        const form = document.getElementById('payment-form');
        const paymentType = document.getElementById('payment-method-type');
        const cardContainer = document.getElementById('card-element-container');
        const idealContainer = document.getElementById('ideal-element-container');
        const tipSelect = document.getElementById('tip-select');
        const totalAmountDisplay = document.getElementById('total-amount');

        // Tip calculation
        tipSelect.addEventListener('change', function () {
            const donation = parseFloat({{ $donation }});
            const tip = parseFloat(this.value);
            totalAmountDisplay.textContent = (donation + tip).toFixed(2);
        });

        // Toggle payment type view
        paymentType.addEventListener('change', function () {
            cardContainer.style.display = this.value === 'card' ? 'block' : 'none';
            idealContainer.style.display = this.value === 'ideal' ? 'block' : 'none';
        });

    </script>
</body>
</html>
