<?php

namespace Shipping;

class Item 
{
    
    /**
     * @var string
     */
    private $name;
    
    /**
     * @var float
     */
    private $price;
    
    /**
     * @var float
     */
    private $weight;
    
    /**
     * Constructor
     * 
     * @param array $details
     */
    public function __construct(array $details)
    {
        $this->name = $details[0];
        $this->price = $details[1];
        $this->weight = $details[2];
    }
    
    /**
     * Get Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Get Price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }
    
    /**
     * Get Weight
     *
     * @return float
     */
    public function getWeight()
    {
        return $this->weight;
    }
}