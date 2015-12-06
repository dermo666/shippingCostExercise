<?php

namespace Test\Shipping;


use Shipping\ShippingChargeCalculator;

class ShippingChargeCalculatorTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * @dataProvider getShippingChargeCombinations
     */
    public function testCalculatePriceForVariousWeights($weight, $methodCalls, $shippingCharge) 
    {
        $package = $this->getMockBuilder('Shipping\Package')
                         ->disableOriginalConstructor()
                         ->setMethods(['getTotalWeight'])
                         ->getMock();
        $package->expects($this->exactly($methodCalls))->method('getTotalWeight')->willReturn($weight);
        
        $calc = new ShippingChargeCalculator();
        
        $this->assertEquals($shippingCharge, $calc->calculatePriceForPackage($package));
    }
    
    public function getShippingChargeCombinations()
    {
        return [
            [100, 1, 5],
            [200, 1, 5],
            [200.5, 3, 10],
            [500, 3, 10],
            [1000, 5, 15],
            [5000, 7, 20],
        ];
    }
    
    public function testCalculatePriceForOver5000g()
    {
        $package = $this->getMockBuilder('Shipping\Package')
                        ->disableOriginalConstructor()
                        ->setMethods(['getTotalWeight'])
                        ->getMock();
        $package->expects($this->exactly(7))->method('getTotalWeight')->willReturn(5001);
    
        $calc = new ShippingChargeCalculator();
    
        $this->setExpectedException('OutOfRangeException');
        $calc->calculatePriceForPackage($package);
    }
}
