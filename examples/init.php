<?php

use Pada\Tinkoff\Payment\Configuration;
use Pada\Tinkoff\Payment\PaymentClient;
use Pada\Tinkoff\Payment\PaymentClientInterface;
use Pada\Tinkoff\Payment\Contract\NewPaymentResultInterface;
use function Pada\Tinkoff\Payment\Functions\newPayment;
use function Pada\Tinkoff\Payment\Functions\newReceipt;
use function Pada\Tinkoff\Payment\Functions\newReceiptItem;

require 'vendor/autoload.php';

// ------------------------------------------------------------------------------------------------
// 1 - Создание клиента

$config = new Configuration();
$config->setTerminalKey('<terminal_key>');
$config->setPassword('<password>');

/** @var PaymentClientInterface $paymentClient */
$paymentClient = new PaymentClient($config);


// ------------------------------------------------------------------------------------------------
// 2 - Создание обьекта платежа

$payment = newPayment()
    ->orderId('1234')
    ->oneStep()
    ->receipt(newReceipt()
        ->email('pavel.k.danilin@gmail.com')
        ->taxationOSN()
        ->addItem(newReceiptItem()
            ->name('Кружка')
            ->price(1000)
            ->quantity(1)
            ->taxNone()
            ->build())
        ->build())
    ->build();


// ------------------------------------------------------------------------------------------------
// 3 - Вызываем API

/** @var NewPaymentResultInterface $result */
$result = $paymentClient->init($payment);

if ($result->isSuccess()) {
    print 'PaymentId:  ' . $result->getPaymentId() . "\n";
    print 'PaymentURL: ' . $result->getPaymentURL() . "\n";
} else {
    print 'Error: ' . $result->getMessage() . "\n";
}
