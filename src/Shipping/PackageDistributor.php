<?php

namespace Shipping;

class PackageDistributor
{
    const ALGO_BEST_PRICE = 'best_price';
    const ALGO_EVENLY_DISTRIBUTED = 'evenly_distributed';
    
    /**
     * @var string
     */
    private $algoType;
    
    /**
     * Constructor
     * 
     * @param string $algoType
     */
    public function __construct($algoType)
    {
        $this->algoType = $algoType;
    }
    
    /**
     * Distribute Items.
     * 
     * @param Order $order
     * @param float $maxPricePerPackage
     * 
     * @return void
     * 
     * @throws \UnexpectedValueException
     */
    public function distributeItems(Order $order, $maxPricePerPackage)
    {
        // Calculate initial number of packages
        $numPackages = ceil($order->getTotalPrice() / $maxPricePerPackage);
        
        if ($numPackages > count($order->getItems())) {
            throw new \UnexpectedValueException('Some items will not fit into packages.');
        }
        
        $this->distributeItemsIntoPackages($order, $maxPricePerPackage, $numPackages);
    }
    
    /**
     * Distribute Items into Packages
     * 
     * @param Order   $order
     * @param float   $maxPricePerPackage
     * @param integer $numPackages
     * 
     * @return void
     * 
     * @throws \UnexpectedValueException
     */
    protected function distributeItemsIntoPackages(Order $order, $maxPricePerPackage, $numPackages)
    {
        $packages = [];
        
        for ($i = 0; $i < $numPackages; $i++) {
            $packages[] = new Package($maxPricePerPackage);
        }
        
        if ($this->algoType == self::ALGO_BEST_PRICE) {
            $orderedItems = $order->getItemsOrderedByPriceDesc();
        } else if ($this->algoType == self::ALGO_EVENLY_DISTRIBUTED) {
            $orderedItems = $order->getItemsOrderedByWeightDesc();
        } else {
            throw new \UnexpectedValueException('Undefined algo type: '.$this->algoType);
        }

        foreach ($orderedItems as $item) {
            $bestPackage = $this->findBestPackageForItem($item, $packages);
            
            if ($bestPackage >= 0) {
                $packages[$bestPackage]->addItem($item);
            } else {
                // Cannot place item due to restriction so add one more package
                return $this->distributeItemsIntoPackages($order, $maxPricePerPackage, ($numPackages + 1));
            }
        }
        
        // Add packages to order
        $order->setPackages($packages);
    }
    
    /**
     * Find Best Package for Item
     * 
     * Returns index of best package to place the item into or -1 when item cannot be placed.
     * 
     * @param Item      $item
     * @param Package[] $packages
     * 
     * @return integer
     */
    public function findBestPackageForItem(Item $item, array $packages)
    {
        $bestWeightDifference = PHP_INT_MAX;
        $bestPackage          = -1;
        $numPackages          = count($packages);

        // Try to fit item to all packages. 
        for ($i = 0; $i < $numPackages; $i++) {
            if ($packages[$i]->canAddItem($item)) {
                $newWeight        = $packages[$i]->getTotalWeight() + $item->getWeight();
                $weightDifference = 0;
                
                // Gather the weight balances to all other packages
                for ($j = 0; $j < $numPackages; $j++) {
                    if ($j !== $i && $packages[$j]->canAddItem($item)) {
                        $weightDifference += abs($newWeight - $packages[$j]->getTotalWeight());
                    }
                }
                
                // Find the lowest weight difference to place item
                if ($bestWeightDifference > $weightDifference) {
                    $bestWeightDifference = $weightDifference;
                    $bestPackage          = $i;
                }
            }
        }
        
        return $bestPackage;
    }
}