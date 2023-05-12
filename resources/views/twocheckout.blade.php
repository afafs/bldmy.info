<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form id="myCCForm" action="{{url('twocheckout/charge')}}" method="post">
        @csrf
        <input id="token" name="token" type="hidden" value="">
        <div>
            <label>
                <span>Card Number</span>
            </label>
            <input id="ccNo" type="text" size="20" value="4111111111111111" autocomplete="off" required />
        </div>
        <div>
            <label>
                <span>Expiration Date (MM/YYYY)</span>
            </label>
            <input type="text" size="2" id="expMonth" required value="12"/>
            <span> / </span>
            <input type="text" size="2" id="expYear" required value="2021" />
        </div>
        <div>
            <label>
                <span>CVC</span>
            </label>
            <input id="cvv" size="4" type="text" value="123" autocomplete="off" required />
        </div>
        <input type="submit" value="Submit Payment">
    </form>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="https://www.2checkout.com/checkout/api/2co.min.js"></script>
    
    <script>
        // Called when token created successfully.
        var successCallback = function(data) {
            var myForm = document.getElementById('myCCForm');
    
            // Set the token as the value for the token input
            myForm.token.value = data.response.token.token;
    
            // IMPORTANT: Here we call `submit()` on the form element directly instead of using jQuery to prevent and infinite token request loop.
            myForm.submit();
        };
    
        // Called when token creation fails.
        var errorCallback = function(data) {
            if (data.errorCode === 200) {
                tokenRequest();
            } else {
                alert(data.errorMsg);
            }
        };
    
        var tokenRequest = function() {
            // Setup token request arguments
            var args = {
                sellerId: "251369017333",
                publishableKey: "71538480-1367-4559-9D41-0D3C0C46B7CC",
                ccNo: $("#ccNo").val(),
                cvv: $("#cvv").val(),
                expMonth: $("#expMonth").val(),
                expYear: $("#expYear").val()
            };
    
            // Make the token request
            TCO.requestToken(successCallback, errorCallback, args);
        };
    
        $(function() {
            // Pull in the public encryption key for our environment
            TCO.loadPubKey('sandbox');
    
            $("#myCCForm").submit(function(e) {
                // Call our token request function
                tokenRequest();
    
                // Prevent form from submitting
                return false;
            });
        });
    </script>
    
</body>
</html>