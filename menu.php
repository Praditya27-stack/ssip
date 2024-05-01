<?php

include "layout/header.php";
include "database.php";
// MUNCULIN DAFTAR MENU PLUS QUANTITY(TERMURAH)
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

// Check if there are any menu items
if (mysqli_num_rows($result) > 0) {
    // Start the HTML table and wrap it in a div for center alignment and positioning
    echo "<div style='text-align: center; margin-top: 100px;'>"; // Adjust the margin-top as needed
    echo "<table border='1' class='table table-primary form-container' style='margin-left: auto; margin-right: auto;'>";
    echo "<tr>
            <th>Menu ID</th>
            <th>Menu Name</th>
            <th>Price</th>
            <th>Description</th>
            <th>Category</th>
            <th>Quantity</th>
            <th>Action</th>
        </tr>";

    // Output each menu item as a table row
    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row["dish_id"];
        echo "<tr>";
        echo "<td>" . $row["dish_id"] . "</td>";
        echo "<td>" . $row["dish_name"] . "</td>";
        echo "<td>$" . $row["price"] . "</td>";
        echo "<td>" . $row["description"] . "</td>";
        echo "<td>" . $row["category"] . "</td>";
        echo "<td>" . $row["quantity"] . "</td>";
        echo "<td>" . "<a href='menu.php?delete=$id' class='btn btn-danger'> delete </a>
        <a href='menu.php?edit=$id' name='edit' class='btn btn-success'> update </a>" . "</td>";
        echo "</tr>";
    }



    // End the HTML table and the div
    echo "</table>";
    echo "</div>";
} else {
    echo "No menu items found.";
}

if (isset($_POST['add_menu'])) {
    
    $name = $_POST["name"];
    $price = $_POST["price"];
    $desc = $_POST["description"];
    $category = $_POST["category"];
   
    $result = mysqli_query($db, "INSERT INTO menu (dish_name, price,description,category) VALUES ('$name', '$price','$desc','$category')");
    if($result){

        $new_dish_id = mysqli_insert_id($db);
        // Now you can use $new_dish_id for further operations or logging
        echo "New dish_id: " . $new_dish_id;
    
        // Proceed to insert into the stock table with the retrieved dish_id
        $quantity = $_POST["quantity"];
        $result2 = mysqli_query($db, "INSERT INTO stock (stock_id,dish_id, quantity) VALUES ('$new_dish_id','$new_dish_id', '$quantity')");
    }
    header("location: menu.php");
    exit(); // Stop further execution

}

if (isset($_POST['update_menu'])) {
    $dish_id = $_POST['id'];
    $dish_name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $quantity =$_POST['quantity'];

    $update_query = mysqli_query($db,"UPDATE menu 
    SET dish_name = '$dish_name', 
        price = '$price', 
        description = '$description',
        category = '$category' 
    WHERE dish_id='$dish_id'");
    $update_query2 = mysqli_query($db,"UPDATE stock 
    SET quantity = '$quantity' 
    WHERE dish_id='$dish_id'");


    if ($update_query) {
        $message[] = 'product updated succesfully';
        header('location:menu.php');
    } else {
        $message[] = 'product could not be updated';
        header('location:menu.php');
    }
}

if (isset($_GET['delete'])) {
    $dish_id = $_GET['delete'];
    if (!empty($dish_id)) {
        $dish_id = (int)$dish_id;
        $delete_query = mysqli_query($db, "DELETE FROM menu WHERE dish_id=$dish_id");
        $recover_query = mysqli_query($db,"ALTER TABLE menu AUTO_INCREMENT = $dish_id");
        $recover_query2 = mysqli_query($db,"ALTER TABLE stock AUTO_INCREMENT = $dish_id");
    }
    header("location: menu.php");
}

// mysqli_close($db);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <div class="container">
        <section>
            <form action="" method="post" class="add-product-form d-column">
            
                <h3>Add Menu</h3>
                <div class="input-group justify-content-between">
                    <input type="text" name="name" placeholder="Enter Menu Name" required class="form-control me-3">
                    
                        <span class="input-group-text">$</span>
                        <input type="number" placeholder="Enter Price" name="price" class="form-control">
                        <span class="input-group-text">.00</span>
                        <input type="text" name="description" placeholder="Enter Description" class="form-control" required>
                        <input type="text" name="category" placeholder="Enter the Category" class="form-control" required>
                        <input type="number" name="quantity" placeholder="Enter the Quantity" class="form-control" required>
                </div>
                 
                 <input type="submit" name="add_menu" class="btn btn-primary" value="Add this menu">
            </form> 
        </section>
    </div>
    
   
    <?php

    if (isset($message)) {
        foreach ($message as $message) {
            echo '<div class="message"><span>' . $message . '</span> <i class="fas fa-times" onclick="this.parentElement.style.display = `none`;"></i> </div>';
        };
    };

    ?>

    <section class="edit-form-container">

        <?php

        if (isset($_GET['edit'])) {
            $id = $_GET['edit'];
            $edit_query = mysqli_query($db,  "SELECT m.dish_id, m.dish_name, m.price, m.description,
            m.category, s.quantity
            FROM menu m
            INNER JOIN stock s ON m.dish_id = s.dish_id
            WHERE m.dish_id = $id");
            // "SELECT * FROM `menu` WHERE dish_id = $id"
            if (mysqli_num_rows($edit_query) > 0) {
                while ($fetch_edit = mysqli_fetch_assoc($edit_query)) {
        ?>

                    <form action="" method="post" class="form-control d-flex justify-content-around">
                        <div>
                            <input type="hidden" name="id" value="<?=$fetch_edit['dish_id'] ?>">
                            <input type="text" name="name" value="<?=$fetch_edit['dish_name'] ?>">
                            <input type="number" name="price" value="<?= $fetch_edit['price'] ?>">
                            <input type="text" name="description" placeholder="Enter Description" value="<?=$fetch_edit['description'] ?>">
                            <input type="text" name="category" placeholder="Enter the Category" value="<?= $fetch_edit['category'] ?>">
                            <input type="text" name="quantity" placeholder="Enter the Quantity" value="<?= $fetch_edit['quantity'] ?>">
                       
                        </div>
                        <div>
                            <input type="submit" value="update the product" name="update_menu" class="btn btn-outline-success">
                            <input type="reset" value="cancel" id="close-edit" class="option-btn btn btn-outline-danger">
                        </div>
                    </form>

        <?php
                };
            };
            echo "<script>document.querySelector('.edit-form-container').style.display = 'flex';</script>";
        };

        mysqli_close($db);
        ?>

        <script src="landing.js"></script>

        <script>
            document.querySelector('#close-edit').onclick = () => {
                document.querySelector('.edit-form-container').style.display = 'none';
                window.location.href = 'menu.php';
            };
        </script>

    </section>
</body>

</html>