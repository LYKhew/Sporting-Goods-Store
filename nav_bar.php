<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
  ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NextGen Sports</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Navbar background color */
    .navbar-black {
      background-color: #343a40; /* Black background */
    }

    /* Navbar text color */
    .navbar-black .navbar-nav > li > a {
      color: white; /* White text color */
    }

    /* Navbar brand color */
    .navbar-black .navbar-brand {
      color: white; /* White brand text color */
    }

    /* Navbar hover text color */
    .navbar-black .navbar-nav > li > a:hover,
    .navbar-black .navbar-brand:hover {
      color: #f8f9fa; /* Lighter white when hovered */
    }

    /* Dropdown menu background and text color */
    .navbar-black .dropdown-menu {
      background-color: #343a40; /* Black background */
      border-color: #343a40; /* Same as navbar */
    }

    .navbar-black .dropdown-menu > li > a {
      color: white; /* White text color in dropdown */
    }

    /* Dropdown menu item hover effect */
    .navbar-black .dropdown-menu > li > a:hover {
      background-color: #23272b; /* Slightly darker background on hover */
      color: #f8f9fa; /* White text on hover */
    }

    /* Flexbox for right aligned items */
    .navbar-black .navbar-nav.navbar-right {
      display: flex;
      align-items: center; /* Vertically center the content */
    }

    /* Styling for the staff name */
    .navbar-black .staff-name {
      color: white;
      margin-right: 15px; /* Space between staff name and logout button */
      font-weight: bold;
    }

    /* Logout Button */
    nav .logout-button {
      color: red;
      font-weight: bold;
      text-decoration: none;
    }

    nav .logout-button:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <!-- Navigation Bar -->
  <nav class="navbar navbar-default navbar-black">
    <div class="container-fluid">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php">NextGen Sports</a>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
          <li><a href="index.php">Home</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">

<!-- Display staff name if logged in -->
          <?php
            if (isset($_SESSION['staff_name'])) {
                echo '<li><span class="staff-name">Welcome, ' . htmlspecialchars($_SESSION['staff_name']) . '</span></li>';
            }
          ?>
          
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Menu <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="products.php">Products</a></li>
              <li><a href="customers.php">Customers</a></li>
              <li><a href="staffs.php">Staffs</a></li>
              <li><a href="orders.php">Orders</a></li>
            </ul>
          </li>

          
          <!-- Logout Button -->
          <li>
            <a href="logout.php" class="logout-button">Logout</a>
          </li>
        </ul>
      </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
  </nav>

  <script src="js/bootstrap.min.js"></script>
</body>
</html>
