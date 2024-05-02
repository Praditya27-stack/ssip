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
    $total_amount = isset($_POST['total_amount']) ? intval($_POST['total_amount']) : 1;
    
    // $customer_id = isset($_POST['customer_id']) ? intval($_POST['customer_id']) : null;
    $getid=mysqli_query($db,"SELECT customer_id FROM login WHERE login_id = 0");
    $custid=mysqli_fetch_array($getid);
    $customer_id=$custid['customer_id'];

    if ($customer_id) {
        // Periksa apakah customer_id valid
        $customer_check_query = "SELECT customer_id FROM customers WHERE customer_id = '$customer_id'";
        $customer_check_result = mysqli_query($db, $customer_check_query);

        if (mysqli_num_rows($customer_check_result) > 0) {
            // Customer_id valid, lakukan operasi INSERT ke tabel orders
            $insert_order_query = "INSERT INTO orders (customer_id, name, price, total_amount) 
                                   VALUES ('$customer_id', '$dish_name', '$price', '$total_amount')";

            if (mysqli_query($db, $insert_order_query)) {
                echo "Produk berhasil ditambahkan ke keranjang.";
            } else {
                echo "Error: " . mysqli_error($db);
            }
        } else {
            echo "Invalid customer selection.";
        }
    } else {
        echo "Invalid customer ID.";
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
    <?php include "layout/header.php"; ?>

    <div class="menu-container">
        <?php
        $result = mysqli_query($db, $query); // Execute the main menu query

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
                        <!-- Pass customer_id and total_amount dynamically from the form -->
                        <input type="hidden" name="customer_id" value="<?= isset($_POST['customer_id']) ? htmlspecialchars($_POST['customer_id']) : '' ?>">
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
