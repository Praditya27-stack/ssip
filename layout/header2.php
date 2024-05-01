
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 
    <link rel="stylesheet" href="landing.css">


    <link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

<title>Radatouli</title>
</head>
<body>

    <header class="l-header">
    <nav class="nav bd-grid">
    <div>
        <a href="#" class="nav__logo">Radatouli</a>
    </div>

    <div class="nav__home" id="nav-home">
        <ul class="nav__list">
            <li class="nav__item"><a href="landing.php" class="nav__link active">home</a></li>
            <?php
            if(isset($_SESSION['username'])) {
                
                echo"<li class='nav__item'><a href='calender2.php' class='nav__link'>Reserve</a></li>";
                echo'<li class="nav__item"><a href="menu2.php" class="nav__link">menu</a></li>';
                echo '<li class="nav__item"><a href="logout.php" class="nav__link">Logout</a></li>';
            } else {
                // Jika belum login, tampilkan tautan Login
                echo"<li class='nav__item'><a href='submit-login.php' class='nav__link'>Reserve</a></li>";
                echo'<li class="nav__item"><a href="submit-login.php" class="nav__link">menu</a></li>';
                echo '<li class="nav__item"><a href="submit-login.php" class="nav__link">Login</a></li>';
                // <li class="nav__item"><a href="menu2.php" class="nav__link">menu</a></li>
            }
            ?>
        </ul>
    </div>

    <div class="nav__toggle" id="nav-toggle">
        <i class='bx bx-home'></i>
    </div>
</nav>
    </header>

    
</body>
</html>