<?php
$consumerKey = '0A88rwj2fQ9mFZheqIDDTc7J11fj3lKFwbG1Iv8GPyjDsuLd';
$consumerSecret = 'SfKG3pjAoGW48oHqHhTTTGVhJBSzhFpLg16AMrXAWnicS7MygxWG59lmhLkbClFO';
$shortCode = '174379'; // Test shortcode
$passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';

$phone = $_POST['phone'];
$amount = $_POST['amount'];

date_default_timezone_set('Africa/Nairobi');
$timestamp = date('YmdHis');
$password = base64_encode($shortCode . $passkey . $timestamp);

// STEP 1: GET TOKEN
$curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_URL => 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials',
  CURLOPT_HTTPHEADER => ['Authorization: Basic ' . base64_encode($consumerKey . ':' . $consumerSecret)],
  CURLOPT_RETURNTRANSFER => true
]);
$response = curl_exec($curl);
$access_token = json_decode($response)->access_token;
curl_close($curl);

// STEP 2: SEND STK PUSH
$stk_push_url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
$callback = 'https://mydomain.com/callback'; // use webhook.site or a dummy one if testing

$payload = [
  'BusinessShortCode' => $shortCode,
  'Password' => $password,
  'Timestamp' => $timestamp,
  'TransactionType' => 'CustomerPayBillOnline',
  'Amount' => $amount,
  'PartyA' => $phone,
  'PartyB' => $shortCode,
  'PhoneNumber' => $phone,
  'CallBackURL' => $callback,
  'AccountReference' => 'Test123',
  'TransactionDesc' => 'Test Payment'
];

$curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_URL => $stk_push_url,
  CURLOPT_HTTPHEADER => [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $access_token
  ],
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_POST => true,
  CURLOPT_POSTFIELDS => json_encode($payload)
]);

$result = curl_exec($curl);
curl_close($curl);
echo "<pre>";
print_r(json_decode($result));
echo "</pre>";
?>
