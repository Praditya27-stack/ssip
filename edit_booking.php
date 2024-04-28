<!DOCTYPE html>
<html>

<head>
    <title>Edit Booking</title>
</head>

<body>

    <?php
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'ssip_db';

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $booking_id = $_POST['booking_id'];
        $booking_date = $_POST['booking_date'];
        $booking_time = $_POST['booking_time'];
        $booking_name = $_POST['booking_name'];

        $sql = "UPDATE bookings SET booking_date='$booking_date', booking_time='$booking_time', booking_name='$booking_name' WHERE id='$booking_id'";

        if ($conn->query($sql) === TRUE) {
            header('location:calendar.php');
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }

    if (isset($_GET['id'])) {
        $booking_id = $_GET['id'];
        $sql = "SELECT id, booking_date, booking_time, booking_name FROM bookings WHERE id='$booking_id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $booking_date = $row["booking_date"];
            $booking_time = $row["booking_time"];
            $booking_name = $row["booking_name"];
        } else {
            echo "Booking not found.";
        }
    }

    $conn->close();
    ?>

    <h2>Edit Booking</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
        <label for="booking_date">Tanggal:</label>
        <input type="date" id="booking_date" name="booking_date" value="<?php echo $booking_date; ?>" required><br><br>
        <label for="booking_time">Waktu:</label>
        <input type="time" id="booking_time" name="booking_time" value="<?php echo $booking_time; ?>" required><br><br>
        <label for="booking_name">Nama:</label>
        <input type="text" id="booking_name" name="booking_name" value="<?php echo $booking_name; ?>" required><br><br>
        <input type="submit" name="simpan" value="Simpan">
    </form>

</body>

</html>