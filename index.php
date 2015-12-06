<?php

use Shipping\PackageDistributor;
use Shipping\Order;
use Shipping\ShippingChargeCalculator;
use Shipping\PackageRenderer;

require_once 'bootstrap.php';

echo '
<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<body>
';

// Render Place Order page
echo '<form method="post">'."<br>\n";
echo "Select items:<br>\n";

foreach ($itemList as $key => $item) {
    echo '<input type="checkbox" name="items[]" id="item_'.$key.'" value="'.$key.'"> <label for="item_'.$key.'">'.$item[0].' - $'.$item[1].' - '.$item[2].'g</label>';
    echo "<br>\n";
}

echo "<br>\n";

echo "Select algorithm:<br>\n";
echo '<input type="radio" name="algo" checked value="'.PackageDistributor::ALGO_BEST_PRICE.'">Best Price<br>
<input type="radio" name="algo" value="'.PackageDistributor::ALGO_EVENLY_DISTRIBUTED.'">Evenly Distributed'."<br>\n";

echo "<br>\n";

echo '<input type="submit" value="Place Order"><br>';

echo "</form><br>\n";

if (isset($_POST['items'])) {
    $algoType = $_POST['algo'];;
    $items    = $_POST['items'];
    
    $order       = new Order($items, $itemList);
    $distributor = new PackageDistributor($algoType);
    $calc        = new ShippingChargeCalculator();
    $renderer    = new PackageRenderer($calc);
    
    $distributor->distributeItems($order, _MAX_PRICE_PER_PACKAGE_);
    
    echo "This order has following packages:<br>";
    echo $renderer->renderPackages($order->getPackages());
}

echo '
</body>
</html>
';
