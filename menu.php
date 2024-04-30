<?php

include "database.php";
include "layout/header.php";
// Fetch menu items sorted by category
$query = "SELECT * FROM menu ORDER BY category";
$result = mysqli_query($db, $query);

// Check if there are any menu items
if (mysqli_num_rows($result) > 0) {
    // Start the HTML table and wrap it in a div for center alignment and positioning
    echo "<div style='text-align: center; margin-top: 100px;'>"; // Adjust the margin-top as needed
    echo "<table border='1' style='margin-left: auto; margin-right: auto;'>";
    echo "<tr><th>Menu ID</th><th>Menu Name</th><th>Price</th><th>Description</th><th>Category</th></tr>";

    // Output each menu item as a table row
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row["dish_id"] . "</td>";
        echo "<td>" . $row["dish_name"] . "</td>";
        echo "<td>$" . $row["price"] . "</td>";
        echo "<td>" . $row["description"] . "</td>";
        echo "<td>" . $row["category"] . "</td>";
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
