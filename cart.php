<?php

include "database.php";
include "layout/header.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="cart.css">
  <title>Shopping Cart</title>
  
</head>
<body>
  <h4>Shopping Cart</h4>
  <div class="cart-container">
    <?php
    if (isset($cart_items) && !empty($cart_items)) {
      foreach ($cart_items as $item) {
        // Extract item details
        $menu_name = $item['menu_name'];
        $price = $item['price'];
        $quantity = $item['quantity'];
        $total_price = $price * $quantity;
        ?>
        <div class="cart-item">
          <img src="" alt="<?php echo $menu_name; ?>"> <div class="item-details">
            <p><?php echo $menu_name; ?></p>
            <span class="price">IDR <?php echo number_format($price, 2); ?></span> <div class="quantity-container">
              <span class="quantity"><?php echo $quantity; ?></span>
              </div>
            <span class="total-price">IDR <?php echo number_format($total_price, 2); ?></span>
            </div>
        </div>
        <?php
      }
    } else {
      echo '<p>Your shopping cart is empty.</p>';
    }
    ?>
  </div>
</body>
</html>