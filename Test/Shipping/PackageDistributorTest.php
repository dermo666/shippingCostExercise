<?php

namespace Test\Shipping;

use Shipping\PackageDistributor;
use Shipping\Package;
use Shipping\Item;
use Shipping\Order;

class PackageDistributorTest extends \PHPUnit_Framework_TestCase
{
    public function testEvenlyDistibutedAlgoCalculateBestPackageForOneItem()
    {
        $packages = [];
        
        for ($i = 0; $i < 4; $i++) {
            $packages[] = new Package(250);
        }
        
        $item = new Item(['Item 1',10, 200]);
        
        $dist = new PackageDistributor(PackageDistributor::ALGO_EVENLY_DISTRIBUTED);
        $result = $dist->findBestPackageForItem($item, $packages);
        
        $this->assertEquals(0, $result);
    }
    
    public function testEvenlyDistibutedAlgoCalculateBestPackageForTwoItems()
    {
        $packages = [];
    
        for ($i = 0; $i < 4; $i++) {
            $packages[] = new Package(250);
        }
    
        $item1 = new Item(['Item 1',10, 200]);
        $item2 = new Item(['Item 1',10, 200]);
    
        $dist = new PackageDistributor(PackageDistributor::ALGO_EVENLY_DISTRIBUTED);
        $packages[0]->addItem($item1);
        
        $result = $dist->findBestPackageForItem($item2, $packages);
    
        $this->assertEquals(1, $result);
    }
    
    public function testEvenlyDistibutedAlgoCalculateBestPackageForThreeItems()
    {
        $packages = [];
    
        for ($i = 0; $i < 2; $i++) {
            $packages[] = new Package(250);
        }
    
        $item1 = new Item(['Item 4', 20, 500]);
        $item2 = new Item(['Item 3', 30, 300]);
        $item3 = new Item(['Item 1', 10, 200]);
    
        $dist = new PackageDistributor(PackageDistributor::ALGO_EVENLY_DISTRIBUTED);
        $packages[0]->addItem($item1);
        $packages[1]->addItem($item2);
    
        $result = $dist->findBestPackageForItem($item3, $packages);
    
        $this->assertEquals(1, $result);
    }
    
    public function testEvenlyDistibutedAlgoCalculateBestPackageForFourItems()
    {
        $packages = [];
    
        for ($i = 0; $i < 2; $i++) {
            $packages[] = new Package(250);
        }
    
        $item1 = new Item(['Item 4', 20, 500]);
        $item2 = new Item(['Item 3', 30, 300]);
        $item3 = new Item(['Item 1', 10, 200]);
        $item4 = new Item(['Item 2', 100, 20]);
    
        $dist = new PackageDistributor(PackageDistributor::ALGO_EVENLY_DISTRIBUTED);
        $packages[0]->addItem($item1);
        $packages[1]->addItem($item2);
        $packages[1]->addItem($item3);
    
        $result = $dist->findBestPackageForItem($item4, $packages);
    
        $this->assertEquals(0, $result);
    }
    
    public function testEvenlyDistibutedAlgoCalculateDoesFindBestPackageForItem()
    {
        $packages = [];
    
        for ($i = 0; $i < 2; $i++) {
            $packages[] = new Package(250);
        }
    
        $item1 = new Item(['Item 1', 245, 200]);
        $item2 = new Item(['Item 2', 245, 200]);
        $item3 = new Item(['Item 3', 10, 200]);
    
        $dist = new PackageDistributor(PackageDistributor::ALGO_EVENLY_DISTRIBUTED);
        $packages[0]->addItem($item1);
        $packages[1]->addItem($item2);

        $result = $dist->findBestPackageForItem($item3, $packages);
    
        $this->assertEquals(-1, $result);
    }
    
    public function testEvenlyDistibutedAlgoDistributeItemsWithHittingThePriceRestriction()
    {
       $itemList = [
            ['Item 1', 245, 200],
            ['Item 2', 245, 200],
            ['Item 3', 10, 200],
        ];
       
         $order = new Order([0,1,2], $itemList);
         
         $dist = new PackageDistributor(PackageDistributor::ALGO_EVENLY_DISTRIBUTED);
         $dist->distributeItems($order, 250);
    
         $this->assertEquals(10, $order->getPackages()[0]->getTotalPrice());
         $this->assertEquals(245, $order->getPackages()[1]->getTotalPrice());
         $this->assertEquals(245, $order->getPackages()[2]->getTotalPrice());
    }

    
    public function testEvenlyDistibutedAlgoDistributeItemsBasedExample()
    {
        $itemList = [
            ['Item 1', 10, 200],
            ['Item 2', 100, 20],
            ['Item 3', 30, 300],
            ['Item 4', 20, 500],
            ['Item 6', 40, 10],
            ['Item 7', 200, 10],
        ];
         
        $order = new Order([0, 1, 2, 3, 4, 5], $itemList);
         
        $dist = new PackageDistributor(PackageDistributor::ALGO_EVENLY_DISTRIBUTED);
        $dist->distributeItems($order, 250);
    
        $this->assertEquals(160, $order->getPackages()[0]->getTotalPrice());
        $this->assertEquals(530, $order->getPackages()[0]->getTotalWeight());
        $this->assertEquals(240, $order->getPackages()[1]->getTotalPrice());
        $this->assertEquals(510, $order->getPackages()[1]->getTotalWeight());
    }
    
    public function testEvenlyDistibutedAlgoDistributeItemsBasedExampleWithExtraItem()
    {
        $itemList = [
                ['Item 1', 10, 200],
                ['Item 2', 100, 20],
                ['Item 3', 30, 300],
                ['Item 4', 20, 500],
                ['Item 6', 40, 10],
                ['Item 7', 200, 10],
                ['Item 55', 50, 100],
        ];
         
        $order = new Order([0, 1, 2, 3, 4, 5, 6], $itemList);
         
        $dist = new PackageDistributor(PackageDistributor::ALGO_EVENLY_DISTRIBUTED);
        $dist->distributeItems($order, 250);
    
        // Here algo fail to distribute items into 2 packages instead it distributes in 3
        $this->assertEquals(220, $order->getPackages()[0]->getTotalPrice());
        $this->assertEquals(510, $order->getPackages()[0]->getTotalWeight());
        $this->assertEquals(170, $order->getPackages()[1]->getTotalPrice());
        $this->assertEquals(330, $order->getPackages()[1]->getTotalWeight());
        $this->assertEquals(60, $order->getPackages()[2]->getTotalPrice());
        $this->assertEquals(300, $order->getPackages()[2]->getTotalWeight());
    }
    
    public function testBestPriceAlgoDistributeItemsWithHittingThePriceRestriction()
    {
        $itemList = [
                ['Item 1', 245, 200],
                ['Item 2', 245, 200],
                ['Item 3', 10, 200],
        ];
         
        $order = new Order([0,1,2], $itemList);
         
        $dist = new PackageDistributor(PackageDistributor::ALGO_BEST_PRICE);
        $dist->distributeItems($order, 250);
    
        $this->assertEquals(245, $order->getPackages()[0]->getTotalPrice());
        $this->assertEquals(245, $order->getPackages()[1]->getTotalPrice());
        $this->assertEquals(10, $order->getPackages()[2]->getTotalPrice());
    }
    
    public function testBestPriceAlgoDistributeItemsBasedExample()
    {
        $itemList = [
                ['Item 1', 10, 200],
                ['Item 2', 100, 20],
                ['Item 3', 30, 300],
                ['Item 4', 20, 500],
                ['Item 6', 40, 10],
                ['Item 7', 200, 10],
        ];
         
        $order = new Order([0, 1, 2, 3, 4, 5], $itemList);
         
        $dist = new PackageDistributor(PackageDistributor::ALGO_BEST_PRICE);
        $dist->distributeItems($order, 250);
    
        // Here it calculates better price for shipping than the example, but weight is not evenly distributed
        $this->assertEquals(250, $order->getPackages()[0]->getTotalPrice());
        $this->assertEquals(220, $order->getPackages()[0]->getTotalWeight());
        $this->assertEquals(150, $order->getPackages()[1]->getTotalPrice());
        $this->assertEquals(820, $order->getPackages()[1]->getTotalWeight());
    }
    
    public function testBestPriceAlgoDistributeItemsBasedExampleWithExtraItem()
    {
        $itemList = [
                ['Item 1', 10, 200],
                ['Item 2', 100, 20],
                ['Item 3', 30, 300],
                ['Item 4', 20, 500],
                ['Item 6', 40, 10],
                ['Item 7', 200, 10],
                ['Item 55', 50, 100],
        ];
         
        $order = new Order([0, 1, 2, 3, 4, 5, 6], $itemList);
         
        $dist = new PackageDistributor(PackageDistributor::ALGO_BEST_PRICE);
        $dist->distributeItems($order, 250);
    
        // Here it distributes items into 2 packages with lower price than before but not eveny distributed
        $this->assertEquals(250, $order->getPackages()[0]->getTotalPrice());
        $this->assertEquals(110, $order->getPackages()[0]->getTotalWeight());
        $this->assertEquals(200, $order->getPackages()[1]->getTotalPrice());
        $this->assertEquals(1030, $order->getPackages()[1]->getTotalWeight());
    }
}
