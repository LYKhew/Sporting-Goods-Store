<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

  include_once 'orders_crud.php';

   // Check if the user is logged in
if (!isset($_SESSION['userlevel'])) {
    // User is not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

// Determine user level
$isAdmin = $_SESSION['userlevel'] == 'Admin';  // Check if user is admin
$isStaff = $_SESSION['userlevel'] == 'Normal Staff';  // Check if user is staff

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sporting Goods Store Ordering System: Orders</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-image: url('https://marketplace.canva.com/EAE6ct-7FQY/1/0/1600w/canva-blue-minimalist-desktop-wallpaper-7vuV8a097eU.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
    }
    
    .btn-dark {
      background-color: #343a40; /* Dark background */
      border-color: #343a40; /* Dark border */
      color: white; /* White text */
    }

    .btn-dark:hover {
      background-color: #23272b; /* Slightly darker on hover */
      border-color: #1d2124;
    }


  </style>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
            // Get the user role from PHP
            const isAdmin = <?php echo json_encode($isAdmin); ?>; // true or false
            const buttons = document.querySelectorAll('.btn-success, .btn-danger');

            if (!isAdmin) {
                // Disable buttons for non-admin users
                buttons.forEach(button => {
                    button.setAttribute('disabled', 'true'); // Disable the button
                    button.addEventListener('click', (event) => {
                        event.preventDefault(); // Prevent the default button action
                        alert('You do not have permission to perform this action.');
                    });
                });
            }
        });
  </script>
</head>
<body>

  <?php include_once 'nav_bar.php'; ?>

  <div class="container-fluid">
    <div class="row">
      <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
        <div class="page-header">
          <h2>Create New Order</h2>
        </div>
        <form action="orders.php" method="post" class="form-horizontal">
          <div class="form-group">
            <label for="orderid" class="col-sm-3 control-label">Order ID</label>
            <div class="col-sm-9">
              <input name="oid" type="text" class="form-control" readonly value="<?php if(isset($_GET['edit'])) echo $editrow['fld_order_num']; ?>">
            </div>
          </div>
          <div class="form-group">
            <label for="orderdate" class="col-sm-3 control-label">Order Date</label>
            <div class="col-sm-9">
              <input name="orderdate" type="text" class="form-control" readonly value="<?php if(isset($_GET['edit'])) echo $editrow['fld_order_date']; ?>">
            </div>
          </div>
          <div class="form-group">
            <label for="staff" class="col-sm-3 control-label">Staff</label>
            <div class="col-sm-9">
              <select name="sid" class="form-control">
                <?php
                try {
                  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                  $stmt = $conn->prepare("SELECT * FROM tbl_staffs_a193067_pt2");
                  $stmt->execute();
                  $result = $stmt->fetchAll();
                }
                catch(PDOException $e) {
                  echo "Error: " . $e->getMessage();
                }
                foreach($result as $staffrow) {
                  ?>
                  <option value="<?php echo $staffrow['fld_staff_num']; ?>" <?php if((isset($_GET['edit'])) && ($editrow['fld_staff_num'] == $staffrow['fld_staff_num'])) echo 'selected'; ?>>
                    <?php echo $staffrow['fld_staff_fullname']; ?>
                  </option>
                <?php
                }
                ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="customer" class="col-sm-3 control-label">Customer</label>
            <div class="col-sm-9">
              <select name="cid" class="form-control">
                <?php
                try {
                  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                  $stmt = $conn->prepare("SELECT * FROM tbl_customers_a193067_pt2");
                  $stmt->execute();
                  $result = $stmt->fetchAll();
                }
                catch(PDOException $e) {
                  echo "Error: " . $e->getMessage();
                }
                foreach($result as $custrow) {
                  ?>
                  <option value="<?php echo $custrow['fld_customer_num']; ?>" <?php if((isset($_GET['edit'])) && ($editrow['fld_customer_num'] == $custrow['fld_customer_num'])) echo 'selected'; ?>>
                    <?php echo $custrow['fld_customer_fullname']; ?>
                  </option>
                <?php
                }
                ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
              <?php if (isset($_GET['edit'])) { ?>
                <button class="btn btn-dark text-white" type="submit" name="update">
                  <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Update
                </button>
              <?php } else { ?>
                <button class="btn btn-dark text-white" type="submit" name="create">
                  <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Create
                </button>
              <?php } ?>
              <button class="btn btn-dark text-white" type="reset">
                <span class="glyphicon glyphicon-erase" aria-hidden="true"></span> Clear
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="row">
      <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="page-header">
          <h2>Orders List</h2>
        </div>
        <table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Order Date</th>
              <th>Staff</th>
              <th>Customer</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Pagination setup
            $per_page = 10;
            $page = isset($_GET["page"]) ? $_GET["page"] : 1;
            $start_from = ($page - 1) * $per_page;

            try {
              $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
              $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              $stmt = $conn->prepare("SELECT * FROM tbl_orders_a193067_pt2, tbl_staffs_a193067_pt2, tbl_customers_a193067_pt2 WHERE tbl_orders_a193067_pt2.fld_staff_num = tbl_staffs_a193067_pt2.fld_staff_num AND tbl_orders_a193067_pt2.fld_customer_num = tbl_customers_a193067_pt2.fld_customer_num LIMIT $start_from, $per_page");
              $stmt->execute();
              $result = $stmt->fetchAll();
            } catch (PDOException $e) {
              echo "Error: " . $e->getMessage();
            }

            foreach ($result as $orderrow) {
              ?>
              <tr>
                <td><?php echo $orderrow['fld_order_num']; ?></td>
                <td><?php echo $orderrow['fld_order_date']; ?></td>
                <td><?php echo $orderrow['fld_staff_fullname']; ?></td>
                <td><?php echo $orderrow['fld_customer_fullname']; ?></td>
                <td>
                  <a href="orders_details.php?oid=<?php echo $orderrow['fld_order_num']; ?>" class="btn btn-warning btn-xs" role="button">Details</a>
                  <a href="orders.php?edit=<?php echo $orderrow['fld_order_num']; ?>" class="btn btn-success btn-xs" role="button">Edit</a>
                  <a href="orders.php?delete=<?php echo $orderrow['fld_order_num']; ?>" onclick="return confirm('Are you sure to delete?');" class="btn btn-danger btn-xs" role="button">Delete</a>
                </td>
              </tr>
            <?php
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>

    <div class="row">
      <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <nav>
          <ul class="pagination">
  <?php
  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT * FROM tbl_orders_a193067_pt2");
    $stmt->execute();
    $result = $stmt->fetchAll();
    $total_records = count($result);
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }

  $total_pages = ceil($total_records / $per_page);
  ?>

  <?php if ($page==1) { ?>
            <li class="disabled"><span aria-hidden="true">«</span></li>
          <?php } else { ?>
            <li><a href="orders.php?page=<?php echo $page-1 ?>" aria-label="Previous"><span aria-hidden="true">«</span></a></li>
          <?php
          }
          for ($i=1; $i<=$total_pages; $i++)
            if ($i == $page)
              echo "<li class=\"active\"><a href=\"orders.php?page=$i\">$i</a></li>";
            else
              echo "<li><a href=\"orders.php?page=$i\">$i</a></li>";
          ?>
          <?php if ($page==$total_pages) { ?>
            <li class="disabled"><span aria-hidden="true">»</span></li>
          <?php } else { ?>
            <li><a href="orders.php?page=<?php echo $page+1 ?>" aria-label="Previous"><span aria-hidden="true">»</span></a></li>
          <?php } ?>
        </ul>

        </nav>
      </div>
    </div>
  </div>
  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
