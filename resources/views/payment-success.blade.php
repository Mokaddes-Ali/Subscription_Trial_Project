<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
</head>
<body>
    <h1>Payment was successful!</h1>
    <p>Your payment has been processed successfully. Customer ID: {{ session('customer_id') }}</p>
    <p>Card ID: {{ session('card_id') }}</p>
</body>
</html>
