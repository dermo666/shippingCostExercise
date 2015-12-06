<?php

namespace Shipping;

class Order
{
    /**
     * @var Package[]
     */
    private $packages = [];
    
    /**
     * @var Item[]
     */
    private $items = [];
    
    /**
     * Constructor
     * 
     * @param array $items
     * @param array $itemList
     */
    public function __construct(array $items, array $itemList)
    {
        $this->initItems($items, $itemList);
    }
        
    /**
     * Init individual Items from config array
     * 
     * @param array $items
     * @param array $itemList
     * 
     * @return void
     * 
     * @throws \UnexpectedValueException
     */
    private function initItems(array $items, array $itemList)
    {
        foreach ($items as $itemId) {
            if (isset($itemList[$itemId])) {
                $this->items[] = new Item($itemList[$itemId]);
            } else {
                throw new \UnexpectedValueException('Incorrect Item ID: '.$itemId);
            }
        }
    }
    
    /**
     * Get Total Price 
     * 
     * @return float
     */
    public function getTotalPrice()
    {
        $totalPrice = 0;
        
        foreach ($this->items as $item) {
            $totalPrice += $item->getPrice();
        }
        
        return $totalPrice;
    }
    
    /**
     * Get Items ordered by Weight Desc
     *
     * @return Item[]
     */
    public function getItemsOrderedByWeightDesc()
    {
        $orderedItems = $this->items;
        
        usort($orderedItems, function(Item $a, Item $b)
        {
            return $a->getWeight() < $b->getWeight();
        });
        
        return $orderedItems;
    }
    
    /**
     * Get Items ordered by Price Desc
     *
     * @return Item[]
     */
    public function getItemsOrderedByPriceDesc()
    {
        $orderedItems = $this->items;
    
        usort($orderedItems, function(Item $a, Item $b)
        {
            return $a->getPrice() < $b->getPrice();
        });
    
        return $orderedItems;
    }
    
    /**
     * Get Items
     *
     * @return Item[]
     */
    public function getItems()
    {
        return $this->items;
    }
    
    /**
     * Set Packages
     * 
     * @param Package[] $packages
     */
    public function setPackages(array $packages)
    {
        $this->packages = $packages;
    }
    
    /**
     * Get Packages.
     * 
     * @return Package[]
     */
    public function getPackages()
    {
        return $this->packages;
    }
}