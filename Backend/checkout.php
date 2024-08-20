<?php

require_once("./Backend/vendor/autoload.php");

$stripe_secret_key = "sk_test_51PWD2aJvz6bIRSSVQUAQLFz6RObET0eiHl7BiUhxuGVP6mzCLADN4A7tKNxdAT0ZVDsSuV8pPwBarkOD4XSEGerk00m2AqsAQX";

\Stripe\Stripe::setApiKey($stripe_secret_key);

$checkout_session = \Stripe\Checkout\Session::create(
    [
        "mode" => "payment",
        "success_url" => "http://localhost/stripe/success.html",
        "line_items" => [
            [
                "quantity" => 1,
                "price_data" => [
                    "currency" => "usd",
                    "unit_amount" => 2000,
                    "product_data" => [
                        "name" => "T-Shirt"
                    ]
                ]
            ],
            [
                "quantity" => 4,
                "price_data" => [
                    "currency" => "usd",
                    "unit_amount" => 5000,
                    "product_data" => [
                        "name" => "Kota kalisamaka"
                    ]
                ]
            ]
        ]
    ]
);

http_response_code(303);
header("Location: " . $checkout_session->url);
