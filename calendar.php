<!DOCTYPE html>
<html>

<head>
    <title>Booking Calendar</title>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&display=swap");


        :root {
            --header-height: 3rem;
            --font-medium: 500;
        }


        :root {
            --first-color: rgb(0, 140, 255);
            --white-color: rgb(255, 255, 255);
            --dark-color: #13232e;
            --text-color: rgb(150, 150, 150);
        }


        :root {
            --body-font: 'Montserrat', sans-serif;
            --big-font-size: 6.25rem;
            --h2-font-size: 1.25rem;
            --normal-font-size: .938rem;
            --small-font-size: .813rem;
        }

        @media screen and (min-width: 768px) {
            :root {
                --big-font-size: 9rem;
                --h2-font-size: 2rem;
                --normal-font-size: 1rem;
                --small-font-size: .875rem;
            }
        }


        :root {
            --mb-1: .5rem;
            --mb-2: 1rem;
            --mb-3: 1.5rem;
            --mb-4: 2rem;
        }


        :root {
            --z-fixed: 100;
        }

        .bd-grid {
            display: grid;
            grid-template-columns: 100%;
            grid-column-gap: 2rem;
            width: calc(100% - 2rem);
            margin-left: var(--mb-2);
            margin-right: var(--mb-2);
        }

        .l-header {
            width: 100%;
            height: 85px;
            position: relative;
            top: -80%;
            left: 50%;
            z-index: var(--z-fixed);
            background-color: var(--first-color);

        }
        


        .nav {
            height: var(--header-height);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-direction: row;
        }

        @media screen and (max-width: 768px) {
            .nav__home {
                position: relative;
                top: var(--header-height);
                right: -100%;
                width: 100%;
                height: 100%;
                padding: 2rem;
                background-color: rgba(255, 255, 255, .3);
                transition: .5s;
                backdrop-filter: blur(10px);
            }
        }

        .nav__item {
            margin-bottom: var(--mb-4);
        }

        .nav__link {
            position: relative;
            color: var(--dark-color);
        }

        .nav__link:hover {
            color: var(--first-color);
        }

        .nav__logo {
            color: var(--white-color);
        }

        .nav__toggle {
            color: var(--white-color);
            font-size: 1.5rem;
            cursor: pointer;
        }
        

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
            background-color: #ffffff;
            border: none;
            color: black;
            padding: 7px 10px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 10px;
            border-radius: 5px;
        }

        .booking-form {
            position: absolute;
            top: 10%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-family: 'Montserrat', sans-serif;
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
            font-family: 'Montserrat', sans-serif;
        }

        body {
            background-color: grey;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
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
            background-color: #25b3ff;
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 10px;
            border-radius: 5px;

        }
    </style>
</head>

<body>
    <header class="l-header">
        <nav class="nav bd-grid">
            <div>
                <a href="#" class="nav__logo">Radatouli</a>
            </div>

            <div class="nav__home" id="nav-home">
                <ul class="nav__list">
                    <li class="nav__item"><a href="#home" class="nav__link active">home</a></li>
                    <li class="nav__item"><a href="#reserve" class="nav__link">Reserve</a></li>
                    <li class="nav__item"><a href="menu.php" class="nav__link">menu</a></li>
                    <?php
                    // session_start();
                    if (isset($_SESSION['username'])) {
                        // Jika sudah login, tampilkan tautan Logout
                        echo '<li class="nav__item"><a href="logout.php" class="nav__link">Logout</a></li>';
                    } else {
                        // Jika belum login, tampilkan tautan Login
                        echo '<li class="nav__item"><a href="submit-login.php" class="nav__link">Login</a></li>';
                    }
                    ?>
                </ul>
            </div>

            <div class="nav__toggle" id="nav-toggle">
                <i class='bx bx-home'></i>
            </div>
        </nav>
    </header>

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

                $sql = "SELECT COUNT(*) AS total_bookings FROM bookings WHERE booking_date='$booking_date' AND booking_name='$booking_name'";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $total_bookings = $row['total_bookings'];

                if ($total_bookings > 0) {
                    $msg = "<p>This name is already in that date.</p>";
                } else {
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

</html>