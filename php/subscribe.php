<?php

$config = require 'config.php';

$apiKey         = $config['apiKey'];
$listId         = $config['listId'];
$double_optin   = false;
$send_welcome   = true;
$email_type     = 'html';
$email          = $_POST['email'];
$name           = $_POST['name'];

// Replace us8 with your datacentre, usually found at end of api key
$submit_url     = "https://us12.api.mailchimp.com/3.0";
$members_url    = $submit_url . "/lists/" . $listId . "/members";

$data = [
    'email_address' => $email,
    'status' => 'subscribed',
    'email_type' => $email_type,
    'merge_fields' => ['NAME'=>$name]
];

$payload = json_encode($data);
 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $members_url);
curl_setopt($ch, CURLOPT_USERPWD, "apikey:" . $apiKey);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch);
$data = json_decode($result);

if ($data->status == 400) {
    echo '<p class="sub-form-error"><i class="fa fa-exclamation-triangle"></i>'.$data->detail.'</p>';
} else {
    echo "<p class='sub-form-success'><i class='fa fa-envelope'></i>Дякуємо! Чекайте на новини!</p>";
}

curl_close($ch);
