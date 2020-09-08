<?php

class User
{
    //create two private parameters of the parent class
    private $name, $balance;

    // create standard constructor with parameter
    function __construct($userName, $userBalance)
    {
        $this->name = $userName;
        $this->balance = $userBalance;
    }

// create method that print information about user
    public function printStatus()
    {
        echo "У пользователя " . $this->name . " сейчас на счету " . $this->balance . "\n";
    }

// create method which implements the transfer of money between users
    public function giveMoney($amount, $user)
    {
        if ($amount < $this->balance) {
            $this->balance = $this->balance - $amount;
            $user->balance = $user->balance + $amount;
            echo "Пользователь " . $this->name . " перечислил " . $amount . " пользователю " . $user->name . "\n";
        }
    }

// implementation of standard getter and setter method
    public function __get($property)
    {
        switch ($property) {
            case 'name':
                return $this->name;
            case 'balance':
                return $this->balance;
        }
    }

    public function __set($property, $value)
    {
        switch ($property) {
            case 'name':
                $this->name = $value;
                break;
            case 'balance':
                $this->balance = $value;
                break;
        }
    }

}

$userVasya = new User("Vasya", 600);
$userKolya = new User("Kolya", 1000);

$userVasya->printStatus();
$userKolya->printStatus();

$userKolya->giveMoney(800, $userVasya);

$userVasya->printStatus();
$userKolya->printStatus();
?>
