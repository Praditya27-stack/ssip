<?php

include "database.php";
include "layout/header.php";
// MUNCULIN DAFTAR MENU PLUS QUANTITY(TERMURAH)
$query = "SELECT m.dish_id, m.dish_name, m.price, m.description,
m.category, s.quantity
FROM menu m
INNER JOIN stock s ON m.dish_id = s.dish_id
ORDER BY m.price
ASC
";

$query2="SELECT m.dish_id, m.dish_name, m.price, m.description,
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
    echo "<table border='1' style='margin-left: auto; margin-right: auto;'>";
    echo "<tr><th>Menu ID</th><th>Menu Name</th><th>Price</th><th>Description</th><th>Category</th><th>Quantity</th></tr>";

    // Output each menu item as a table row
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row["dish_id"] . "</td>";
        echo "<td>" . $row["dish_name"] . "</td>";
        echo "<td>$" . $row["price"] . "</td>";
        echo "<td>" . $row["description"] . "</td>";
        echo "<td>" . $row["category"] . "</td>";
        echo "<td>" . $row["quantity"] . "</td>";
        echo "</tr>";
    }

    // End the HTML table and the div
    echo "</table>";
    echo "</div>";
} else {
    echo "No menu items found.";
}

mysqli_close($db);
?>
