<?php

namespace Shipping;

class Package 
{
    
    /**
     * @var float
     */
    private $maxPricePerPackage = 0;
    
    /**
     * @var Item
     */
    private $items;
    
    /**
     * @var float
     */
    private $totalWeight = 0;
    
    /**
     * @var float
     */
    private $totalPrice = 0;
    
    /**
     * Constructor
     * 
     * @param float $maxPricePerPackage
     */
    public function __construct($maxPricePerPackage)
    {
        $this->maxPricePerPackage = $maxPricePerPackage;
    }
    
    /**
     * Check whether Item can be added
     * 
     * @param Item $item
     * 
     * @return boolean
     */
    public function canAddItem(Item $item)
    {
        if ($this->getTotalPrice() + $item->getPrice() > $this->maxPricePerPackage) {
            return false;
        } else {
            return true;
        }
    }
    
    /**
     * Add Item
     * 
     * @param Item $item
     * 
     * @return void
     * 
     * @throws \DomainException
     */
    public function addItem(Item $item)
    {
        if (!$this->canAddItem($item)) {
            throw \DomainException('Item value is greater than limit.');
        }
        
        $this->items[] = $item;
        
        $this->totalPrice  += $item->getPrice();
        $this->totalWeight += $item->getWeight();
    }
     
    /**
     * Get Total Price
     * 
     * @var float
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }
    
    /**
     * Get Total Weight
     * 
     * @return float
     */
    public function getTotalWeight()
    {
        return $this->totalWeight;
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
}