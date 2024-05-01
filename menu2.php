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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
</head>

<body>
    <?php
    include "layout/header.php";

    if (mysqli_num_rows($result) > 0) {
    ?>
    <div style="text-align: center;">
        <table border="1" class="table table-primary form-container" style="margin-top: 70px;">
            <tr>
                <th>Menu Name</th>
                <th>Price</th>
                <th>Description</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Action</th>
            </tr>

            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                $id = $row["dish_id"];
            ?>
                <tr>
                    <td><?= $row["dish_name"] ?></td>
                    <td><?= $row["price"] ?></td>
                    <td><?= $row["description"] ?></td>
                    <td><?= $row["category"] ?></td>
                    <td><?= $row["quantity"] ?></td>
                    <td><a href='menu.php?delete=$id' class='btn btn-success    '> Order </a></td>
                </tr>
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