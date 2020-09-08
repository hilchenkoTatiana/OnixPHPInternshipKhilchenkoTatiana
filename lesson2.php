<?php

namespace lessons2;

class User
{
    //create two private parameters of the parent class
    private $name, $balance;

    // create standard constructor with parameter

    /**
     * @var mixed
     */

    function __construct($userName, $userBalance)
    {
        $this->name = $userName;
        $this->balance = $userBalance;
    }

// create method that print information about user
//    public function printStatus()
//    {
//        echo "У пользователя " . $this->name . " сейчас на счету " . $this->balance . "\n";
//    }
//
//// create method which implements the transfer of money between users
//    public function giveMoney($amount, $user)
//    {
//        if ($amount < $this->balance) {
//            $this->balance = $this->balance - $amount;
//            $user->balance = $user->balance + $amount;
//            echo "Пользователь " . $this->name . " перечислил " . $amount . " пользователю " . $user->name . "\n";
//        }
//    }

// implementation of standard getter and setter method
    public function __get($property)
    {
        return $property;
    }

    public function __set($property, $value)
    {
        $this->property = $value;
    }

    public function __toString()
    {
        return $this->name . " " . $this->balance;
    }

}


abstract class Product
{
    private $name, $price, $owner;
    private static $products = array();

    function __construct($user, $productName, $productPrice)
    {
        $this->name = $productName;
        $this->price = $productPrice;
        $this->owner = $user;
    }

    public static function registerProduct($ourProduct)
    {
        if (!in_array($ourProduct, self::$products)) {
            array_push(self::$products, $ourProduct);
        }
    }

    public function __toString()
    {
        return $this->owner . " " . $this->name . " " . $this->price . "\n";
    }

    public static function getProduct()
    {
        return new class(Product::$products) extends Product implements \Iterator {
            private $position = 0;
            private $products;

            public function __construct($products)
            {
                $this->position = 0;
                $this->products = $products;
            }

            public function current()
            {
                return $this->products[$this->position];
            }

            public function next()
            {
                ++$this->position;
            }

            public function key()
            {
                return $this->position;
            }

            public function valid()
            {
                return isset($this->products[$this->position]);
            }

            public function rewind()
            {
                $this->position = 0;
            }
        };
    }
}

class Processor extends Product
{
    private $frequency;

    function __construct($user, $productName, $productPrice, $userFrequency)
    {
        $this->frequency = $userFrequency;
        parent::__construct($user, $productName, $productPrice);
    }
}

class Ram extends Product
{
    private $type, $memory;

    function __construct($user, $productName, $productPrice, $productType, $productMemory)
    {
        parent::__construct($user, $productName, $productPrice);
        $this->type = $productType;
        $this->memory = $productMemory;

    }

}

$ram = new Ram(new User("Adam", 1000), "some", 10, "someType", 20);
$proc = new Processor(new User("Tatiana", 600), "Intel", 200, 3.3);

Product::registerProduct($ram);
Product::registerProduct($proc);

foreach (Product::getProduct() as $product) {
    echo $product;
}
