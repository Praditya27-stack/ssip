<!DOCTYPE html>
<html>

<head>

    <title>Booking Calendar</title>
    <style>
        .calendar {
            width: 400px;
            border-collapse: collapse;
        }

        .calendar td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            border-radius: 10px;
        }

        .calendar th {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            border-radius: 10px;
        }

        .booking-form input[type="date"], 
.booking-form input[type="time"], 
.booking-form input[type="text"] {
    background-color: #25b3ff; /* Warna latar belakang */
    border: none; /* Tanpa border */
    color: white; /* Warna teks */
    padding: 10px 20px; /* Padding */
    text-align: center; /* Teks di tengah */
    text-decoration: none;
    display: inline-block;
    font-size: 10px; /* Ukuran teks */
    border-radius: 5px; /* Border radius */
}

        .booking-form {
            position: absolute;
            top: 10%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-family: 'Montserrat', sans-serif; /* Mengubah jenis font */
        }

        .booking-schedule {
            position: absolute;
            top: 45%;
            left: 50%;
            transform: translateX(-50%);
            font-family: 'Montserrat', sans-serif;
        }

        .booking-schedule h3 {
            position: absolute;
            top: -50px;
            left: 50px;
            transform: translateX(-50%);
            font-family: 'Montserrat', sans-serif; /* Mengubah jenis font */
        }

        body {
            background-color: rgb(0, 140, 255);
            color: white; /* Menjadikan semua teks putih */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 500px;
            width: 500px;
            border-radius: 10px;
            margin: auto;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .booking-form input[type="submit"] {
            background-color: #25b3ff; /* Warna latar belakang */
            border: none; /* Tanpa border */
            color: white; /* Warna teks */
            padding: 10px 20px; /* Padding */
            text-align: center; /* Teks di tengah */
            text-decoration: none;
            display: inline-block;
            font-size: 10px; /* Ukuran teks */
            border-radius: 5px; /* Border radius */
        }

    </style>
</head>

<body>
    <?php
    // include "database.php"
    // Database connection
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'ssip_db';

    $msg = '';

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['action'])) {
            if ($_POST['action'] == "add") {
                $booking_date = $_POST['booking_date'];
                $booking_time = $_POST['booking_time'];
                $booking_name = $_POST['booking_name'];

                // Check if there is already a booking with the same name on the same date
                $sql = "SELECT COUNT(*) AS total_bookings FROM bookings WHERE booking_date='$booking_date' AND booking_name='$booking_name'";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $total_bookings = $row['total_bookings'];

                if ($total_bookings > 0) {
                    $msg = "<p>This name is already in that date.</p>";
                } else {
                    // Check if there are already 3 bookings at the same time
                    $sql = "SELECT COUNT(*) AS total_bookings FROM bookings WHERE booking_date='$booking_date' AND booking_time='$booking_time'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    $total_bookings_same_time = $row['total_bookings'];

                    if ($total_bookings_same_time >= 3) {
                        $msg = "<p>Udah 3 booking brooo.</p>";
                    } else {
                        $sql = "INSERT INTO bookings (booking_date, booking_time, booking_name) VALUES ('$booking_date', '$booking_time', '$booking_name')";

                        if ($conn->query($sql) === TRUE) {
                            $msg = "<p>Booking successs.</p>";
                        } else {
                            $msg = "Error: " . $sql . "<br>" . $conn->error;
                        }
                    }
                }
            } elseif ($_POST['action'] == "edit") {
                header("Location: edit_booking.php?id=" . $_POST['booking_id']);
            } elseif ($_POST['action'] == "delete") {
                $booking_id = $_POST['booking_id'];

                $sql = "DELETE FROM bookings WHERE id='$booking_id'";

                if ($conn->query($sql) === TRUE) {
                    $msg = "<p>Booking berhasil dihapus.</p>";
                } else {
                    $msg = "Error deleting record: " . $conn->error;
                }
            }
        }
    }

    ?>
    <section class="booking-form">
        <h2>Booking Calendar</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="action" value="add">
            <label for="booking_date">Tanggal:</label>
            <input type="date" id="booking_date" name="booking_date" min="<?php echo date("Y-m-d"); ?>" required><br><br>
            <label for="booking_time">Waktu:</label>
            <input type="time" id="booking_time" name="booking_time" required><br><br>
            <label for="booking_name">Nama:</label>
            <input type="text" id="booking_name" name="booking_name" required><br><br>
            <input class="btn" type="submit" value="Tambah Booking">
        </form>
        <?= $msg ?>
    </section>

    <section class="booking-schedule">
        <h3>Jadwal Booking</h3>

        <table class="calendar">
            <tr>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Nama</th>
                <th>Action</th>
            </tr>
    </section>
    <?php
    $sql = "SELECT id, booking_date, booking_time, booking_name FROM bookings";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["booking_date"] . "</td><td>" . $row["booking_time"] . "</td><td>" . $row["booking_name"] . "</td>";
            echo "<td>";
            echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
            echo "<input type='hidden' name='action' value='edit'>";
            echo "<input type='hidden' name='booking_id' value='" . $row["id"] . "'>";
            echo "<input type='hidden' name='booking_date' value='" . $row["booking_date"] . "'>";
            echo "<input type='hidden' name='booking_time' value='" . $row["booking_time"] . "'>";
            echo "<input type='hidden' name='booking_name' value='" . $row["booking_name"] . "'>";
            echo "<input type='submit' value='Edit'>";
            echo "</form>";
            echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
            echo "<input type='hidden' name='action' value='delete'>";
            echo "<input type='hidden' name='booking_id' value='" . $row["id"] . "'>";
            echo "<input type='submit' value='Delete'>";
            echo "</form>";
            echo "</td></tr>";
        }
    } else {
        echo "<tr><td colspan='4'>Tidak ada booking tersedia.</td></tr>";
    }

    $conn->close();
    ?>
    </table>

</body>

</html>