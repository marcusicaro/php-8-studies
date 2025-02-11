<?php 

namespace PaymentGateway\Paddle;

use PaymentGateway\Stripe\Transaction as StripeTransaction;

class Transaction {
    public function __construct() {
        var_dump(new \Notification\Email());
        var_dump(new StripeTransaction());
        print "hello from " . __NAMESPACE__ . "\n";
    }
}