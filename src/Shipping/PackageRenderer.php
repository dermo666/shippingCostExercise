<?php

namespace Shipping;

class PackageRenderer
{
    
    /**
     * @var ShippingChargeCalculator
     */
    private $shippingChargeCalculator;
    
    public function __construct(ShippingChargeCalculator $shippingChargeCalculator)
    {
        $this->shippingChargeCalculator = $shippingChargeCalculator;
    }
    
    /**
     * Render Packages
     * 
     * @param Package[] $packages
     * 
     * @return string
     */
    public function renderPackages(array $packages)
    {
        $html = "<p>\n";
        
        foreach ($packages as $key => $package) {
            $html .= 'Package '.($key + 1)."<br>\n";
            $html .= 'Items - ';
            
            $itemNames = [];
            
            foreach ($package->getItems() as $item) { 
                $itemNames[] = $item->getName();
            }
            
            $html .= implode(', ', $itemNames)."<br>\n";
            $html .= 'Total Weight - '.$package->getTotalWeight()."g<br>\n";
            $html .= 'Total Price - $'.$package->getTotalPrice()."<br>\n";
            $html .= 'Courier Price - $'.$this->shippingChargeCalculator->calculatePriceForPackage($package)."<br>\n";
            $html .= "<p>\n";
        }
        
        return $html;
    }
}
