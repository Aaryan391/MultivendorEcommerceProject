@extends('welcome')
@section('content')
<style>
    #payment-button {
        background-color: violet;
    }
</style>
<div class="containers d-flex justify-content-center my-5">
    <button id="payment-button">Pay with Khalti</button>
</div>
<script src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2020.12.22.0.0.0/khalti-checkout.iffe.js"></script>
<script>

    var config = {
        // replace the publicKey with yours
        "publicKey": "test_public_key_9ecd180c7edb4c72a4348a15ed175a75",
        "productIdentity": "1234567890",
        "productName": "Dragon",
        "productUrl": "http://gameofthrones.wikia.com/wiki/Dragons",
        "paymentPreference": [
            "KHALTI",
            "EBANKING",
            "MOBILE_BANKING",
            "CONNECT_IPS",
            "SCT",
        ],
        "eventHandler": {
            onSuccess(payload) {
                // hit merchant api for initiating verfication
                console.log(payload);
                var url = '/update-order/'+`{{$order_id}}`
                window.location.replace(url)
            },
            onError(error) {
                console.log(error);
                var url = '/cancel-order/' + `{{$order_id}}`
                window.location.replace(url);
            },
            onClose() {
                console.log('widget is closing');
                var url = '/cancel-order/' + `{{$order_id}}`
                window.location.replace(url);
            }
        }
    };

    var checkout = new KhaltiCheckout(config);
    var btn = document.getElementById("payment-button");
    btn.onclick = function () {
        // minimum transaction amount must be 10, i.e 1000 in paisa.
        var total_price = parseFloat(`{{$total_price}}`) * 100; // Convert to paisa
        checkout.show({
            amount: total_price.toFixed(0) // Convert back to integer paisa
        });
    }
    btn.onclick = showKhaltiWidget;

</script>
<script>
    // Cancel the order if Khalti payment option is selected but not completed
    var paymentOption = document.querySelector('input[name="order_payment_type"]:checked');
    var submitButton = document.querySelector('#checkout-form button[type="submit"]');
    if (paymentOption && paymentOption.value === 'khalti') {
        submitButton.addEventListener('click', function(event) {
            event.preventDefault();
            var confirmCancel = confirm('Are you sure you want to cancel the order?');
            if (confirmCancel) {
                var url = '/cancel-order/' + `{{$order_id}}`;
                window.location.replace(url);
            }
        });
    }
</script>
<script>
    // Function to simulate a click on the "Pay with Khalti" button
    function clickPayWithKhaltiButton() {
        document.getElementById('payment-button').click();
    }

    // Automatically click the button on page load (optional)
    // Add this script only if you want the button to be clicked automatically on page load
    document.addEventListener('DOMContentLoaded', function() {
        clickPayWithKhaltiButton();
    });
</script>
@endsection