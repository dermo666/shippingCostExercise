<?php

namespace Shipping;

class ShippingChargeCalculator
{

    /**
     * Calculate Price
     * 
     * @param Package $package
     * 
     * @return float
     * 
     * @throws \OutOfRangeException
     */
    public function calculatePriceForPackage(Package $package)
    {
        if ($package->getTotalWeight() <= 200) {
            return 5;
        } else if ($package->getTotalWeight() > 200 && $package->getTotalWeight() <= 500) {
            return 10;
        } else if ($package->getTotalWeight() > 500 && $package->getTotalWeight() <= 1000) {
            return 15;
        } else if ($package->getTotalWeight() > 1000 && $package->getTotalWeight() <= 5000) {
            return 20;
        } else {
            throw new \OutOfRangeException('Package price is out of range');
        }
    }
}