<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu List</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <?php
        include "database.php";
        $result = mysqli_query($db, "
            SELECT dish_id, dish_name, price, AVG(price) as avg_price
            FROM menu
            GROUP BY dish_id
            HAVING avg_price > (
                SELECT AVG(price)
                FROM menu
            )
        ");
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>". $row["dish_id"]. "</td>";
            echo "<td>". $row["dish_name"]. "</td>";
            echo "<td>". $row["price"]. "</td>";
            echo "<td>". $row["avg_price"]. "</td>";
            echo "</tr>";
        }
    ?>
</body>
</html>
