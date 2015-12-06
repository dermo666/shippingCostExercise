<?php

namespace Test\Shipping;

use Shipping\Order;
use Shipping\Item;

class OrderTest extends \PHPUnit_Framework_TestCase
{
    private $itemList = [
        ['Item 1',10, 200],
        ['Item 2',100, 20],
        ['Item 3',30, 300],
    ];
    
    public function testOrderItemsByWeight()
    {
        $order = new Order([0,1,2], $this->itemList);
        
        $items = [
            new Item(['Item 3',30, 300]),
            new Item(['Item 1',10, 200]),
            new Item(['Item 2',100, 20]),
        ];
        
        $this->assertEquals($items, $order->getItemsOrderedByWeightDesc());
    }
    
    public function testOrderItemsByPrice()
    {
        $order = new Order([0,1,2], $this->itemList);
    
        $items = [
            new Item(['Item 2',100, 20]),
            new Item(['Item 3',30, 300]),
            new Item(['Item 1',10, 200]),
        ];
    
        $this->assertEquals($items, $order->getItemsOrderedByPriceDesc());
    }
}
