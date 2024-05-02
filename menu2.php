<?php

include "database.php";

$query = "SELECT m.dish_id, m.dish_name, m.price, m.description,
m.category, s.quantity
FROM menu m
INNER JOIN stock s ON m.dish_id = s.dish_id
ORDER BY m.price
ASC
";

$query2 = "SELECT m.dish_id, m.dish_name, m.price, m.description,
m.category, s.quantity
FROM menu m
LEFT JOIN stock s ON m.dish_id = s.dish_id
GROUP BY m.dish_id
HAVING quantity < 5
ORDER BY m.category, m.price DESC";

$result = mysqli_query($db, $query);

if (isset($_POST['add_to_cart'])) {
    $dish_name = $_POST['dish_name'];
    $price = $_POST['price'];
    $total_amount = 1;
    // $desc = $_POST['description'];
    // $category = $_POST['category'];

    $cart = mysqli_query($db, "SELECT * FROM `orders` WHERE name='$dish_name'");

    if(mysqli_num_rows($cart) > 0){
        $message[] = 'product already added to cart';
     }else{
        $insert_product = mysqli_query($db, "INSERT INTO `orders`(name, price, total_amount) VALUES('$dish_name', '$price',  '$total_amount')");
        $message[] = 'product added to cart succesfully';
     }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="menu2.css">
    <title>Menu</title>
</head>

<body>
    <?php
    include "layout/header.php";

    if (mysqli_num_rows($result) > 0) {
        ?>
        <div class="menu-container">
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                $id = $row["dish_id"];
                ?>
                <div class="menu-item">
                    <h2><?= $row["dish_name"] ?></h2>
                    <p>Price: <?= $row["price"] ?></p>
                    <p>Description: <?= $row["description"] ?></p>
                    <p>Category: <?= $row["category"] ?></p>
                    <p>Quantity: <?= $row["quantity"] ?></p>
                    <form action="" method="post">
                        <input type="hidden" name="dish_name" value="<?= $row["dish_name"] ?>">
                        <input type="hidden" name="price" value="<?= $row["price"] ?>">
                        <input type="hidden" name="description" value="<?= $row["description"] ?>">
                        <input type="hidden" name="category" value="<?= $row["category"] ?>">
                        <input type="hidden" name="quantity" value="<?= $row["quantity"] ?>">
                        <input type="submit" name="add_to_cart" value="Order">
                    </form>
                </div>

                    
            <?php
                }
            } else {
                echo "Menunya Kosong.";
            }

            ?>
            </table>
        </div>
</body>

</html>