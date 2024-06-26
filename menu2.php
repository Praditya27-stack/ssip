<?php
include "database.php"; // Include the database connection file

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

// Process form submission
if (isset($_POST['add_to_cart'])) {
    $dish_name = $_POST['dish_name'];
    $price = $_POST['price'];
    $total_amount = 1;
   
    $customer_check_query = "SELECT customer_id FROM customers WHERE customer_id = 7";
    $query_max = mysqli_query($db, "SELECT name FROM orders WHERE name = '$dish_name'");
    $total_amount = isset($_POST['total_amount']) ? intval($_POST['total_amount']) : 1;
    
    $getid=mysqli_query($db,"SELECT customer_id FROM login WHERE login_id = 0");
    $custid=mysqli_fetch_array($getid);
    $customer_id=$custid['customer_id'];

    if ($customer_id) {
        $customer_check_query = "SELECT customer_id FROM customers WHERE customer_id = '$customer_id'";
        $customer_check_result = mysqli_query($db, $customer_check_query);

        if (mysqli_num_rows($query_max) > 0) {
            echo "Gabisa lagi brooo!";
        } else {
        if (mysqli_num_rows($customer_check_result) > 0) {
                $insert_order_query = mysqli_query($db, "INSERT INTO orders (customer_id, name, price, total_amount) 
                VALUES (7, '$dish_name', '$price', '$total_amount')");
            }
        }
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
    <?php include "layout/header2.php"; ?>

    <div class="menu-container">
        <?php
        $result = mysqli_query($db, $query); 

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
        ?>
                <div class="menu-item">
                    <h2><?= $row["dish_name"] ?></h2>
                    <p>Price: <?= $row["price"] ?></p>
                    <p>Description: <?= $row["description"] ?></p>
                    <p>Category: <?= $row["category"] ?></p>
                    <p>Quantity: <?= $row["quantity"] ?></p>
                    <form action="" method="post">
                        <input type="hidden" name="dish_name" value="<?= htmlspecialchars($row["dish_name"]) ?>">
                        <input type="hidden" name="price" value="<?= $row["price"] ?>">
                        
                        <input type="hidden" name="total_amount" value="<?= isset($_POST['total_amount']) ? htmlspecialchars($_POST['total_amount']) : '1' ?>">
                        <input type="submit" name="add_to_cart" value="Order">
                    </form>
                </div>
        <?php
            }
        } else {
            echo "Menu is empty.";
        }
        ?>
    </div>

    <!-- Customer selection form -->
    <form method="post" action="">
        <label for="customer_id">Select Customer:</label>
        <select name="customer_id" id="customer_id">
            <?php
            $customer_query = "SELECT customer_id, customer_name FROM customers";
            $customer_result = mysqli_query($db, $customer_query);

            if ($customer_result && mysqli_num_rows($customer_result) > 0) {
               

                while ($row = mysqli_fetch_assoc($customer_result)) {
                    echo "<option value='" . $row["customer_id"] . "'>" . $row["customer_name"] . "</option>";
                }
            }
            ?>
        </select>
    </form>

</body>
</html>
