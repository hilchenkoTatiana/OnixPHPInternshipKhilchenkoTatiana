<?php
namespace lessons4;
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
    // Get own products
    public function listProducts()
    {
        $owningProducts = array();
        foreach (Product::getProduct() as $product) {
            if($product->getOwner() == $this)
            {
                array_push($owningProducts, $product);
            }
        }
        return $owningProducts;
    }
    // Sell product to customer if you are owner of the product and the customer has enough money, to buy
    public function sellProduct($product, $productCustomer)
    {
        if(!$this->isProductOwner($product))
        {
            printf("Пользователь %s не может продать продукт %s ($%d)  так как он принадлежит %s. \n", $this->name,
                $product->getName(), $product->getPrice(), $product->getOwner()->getName());
        }
        else if(!$productCustomer->canBuyProduct($product->getPrice()))
        {
            printf("Пользователь %s не может перечислить $%d пользователю %s так как имеет только $%d. \n", $productCustomer->getName(),
                $product->getPrice(), $this->name, $productCustomer->getBalance());
        }
        else
        {
            $this->addBalance($product->getPrice());
            $product->setOwner($productCustomer);
            $productCustomer->decreaseBalance($product->getPrice());
            printf("Пользователь %s продал продукт %s ($%d) пользователю %s. \n", $this->name, $product->getName(), $product->getPrice(), $productCustomer->getName());
        }
    }
    // Check, is current user owner of the product
    private function isProductOwner($product)
    {
        $owningProducts = $this->listProducts();
        return in_array($product, $owningProducts);
    }

    // Check, can current user buy the product
    private function canBuyProduct($productPrice)
    {
        return $productPrice <= $this->balance;
    }

    // Return user name
    public function getName()
    {
        return $this->name;
    }

    // Return user balance
    public function getBalance()
    {
        return $this->balance;
    }

    // Add balance
    public function addBalance($amount)
    {
        $this->balance += $amount;
    }

    // Decrease balance
    public function decreaseBalance($amount)
    {
        $this->balance -= $amount;
    }

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
        self::registerProduct($this);
    }

    private static function registerProduct($ourProduct)
    {
        if (!in_array($ourProduct, self::$products, false)) {
            array_push(self::$products, $ourProduct);
        }
    }

    // Create random product for the specified user
    public static function createRandomProduct($user)
    {
        new class($user) extends Product {
            public function __construct($user)
            {
                parent::__construct($user, uniqid(), rand());
            }
        };
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

    public function getOwner()
    {
        return $this->owner;
    }

    public function setOwner($user)
    {
        $this->owner = $user;
    }

    public function getName()
    {
        return $this->name;
    }
    public function getPrice()
    {
        return $this->price;
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
$user1 = new User("Adam", 1000);
$user2 = new User("Tatiana", 200);
$ram = new Ram($user1, "some", 10, "someType", 20);
$ram1 = new Ram($user1, "some", 100, "someType", 30);
$proc = new Processor($user2, "Intel", 200, 3.3);

// Create random product
Product::createRandomProduct($user2);
Product::createRandomProduct($user2);


// Get a list of products, belong to Tatiana
foreach ($user2->listProducts() as $product){
    echo $product;
}

// Try to sell all Tatiana's products to Adam
foreach ($user1->listProducts() as $product)
{
    $user2->sellProduct($product, $user1);
}
//
//// Tatiana tries to sell Adam's product to Adam
$user2->sellProduct($ram, $user1);