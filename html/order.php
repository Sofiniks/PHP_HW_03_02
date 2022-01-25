<?php
include '../src/config.php';
include '../src/class.db.php';
include '../src/class.burger.php';

$addressFields = ['phone', 'street', 'home', 'appt', 'floor'];
$name = '';
$email = '';
$address = '';

foreach ($_POST as $field => $value)
{
    if($value && $field == 'name') {
        $name = $value;
    }
    if($value && $field == 'email') {
        $email = $value;
    }
    if ($value && in_array($field, $addressFields)) {
        $address .= $value . ',';
    }
}

$data = ['address' => $address];

$burger = new Burger();

$user = $burger->getUserByEmail($email);

if($user) {
    $userId = $user['id'];
    $burger->incOrders($userId);
    $orderNumber = $user['orders_count'] + 1;
} else {
    $orderNumber = 1;
    $userId = $burger->createUser($email, $name);
}

$orderId = $burger->addOrder($userId, $data);

echo "Спасибо, ваш заказ будет доставлен по адресу: $address<br>
      Номер вашего заказа #$orderId<br>
      Это ваш №$orderNumber заказ!";
