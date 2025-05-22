<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once 'staffs_crud.php';

if ($_SESSION['userlevel'] != 'Admin') {
    // Redirect non-admin users away from this page
    header("Location: index.php");
    exit();
}

 // Check if the user is logged in
if (!isset($_SESSION['userlevel'])) {
    // User is not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

// Database connection setup
try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sporting Goods Store Ordering System: Staffs</title>
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
      background-color: #343a40;
      border-color: #343a40;
      color: white;
    }
    .btn-dark:hover {
      background-color: #23272b;
      border-color: #1d2124;
    }
  </style>
</head>
<body>

 <?php include_once 'nav_bar.php'; ?>

  <div class="container-fluid">
    <div class="row">
      <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
        <div class="page-header">
          <h2>Create New Staff</h2>
        </div>
        <form action="staffs.php" method="post" class="form-horizontal">
          <div class="form-group">
            <label for="staffid" class="col-sm-3 control-label">Staff ID</label>
            <div class="col-sm-9">
              <input name="sid" type="text" class="form-control" id="staffid" placeholder="Staff ID" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_staff_num']; ?>" required>
            </div>
          </div>
          <div class="form-group">
            <label for="staffname" class="col-sm-3 control-label">Full Name</label>
            <div class="col-sm-9">
              <input name="fullname" type="text" class="form-control" id="staffname" placeholder="Staff Full Name" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_staff_fullname']; ?>" required>
            </div>
          </div>
          <div class="form-group">
            <label for="staffgender" class="col-sm-3 control-label">Gender</label>
            <div class="col-sm-9">
              <div class="radio">
                <label>
                  <input name="gender" type="radio" value="Male" <?php if(isset($_GET['edit']) && $editrow['fld_staff_gender'] == "Male") echo "checked"; ?> required> Male
                </label>
              </div>
              <div class="radio">
                <label>
                  <input name="gender" type="radio" value="Female" <?php if(isset($_GET['edit']) && $editrow['fld_staff_gender'] == "Female") echo "checked"; ?> required> Female
                </label>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="staffdob" class="col-sm-3 control-label">Date Of Birth</label>
            <div class="col-sm-9">
              <input name="dob" type="date" class="form-control" id="staffdob" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_staff_dob']; ?>" required>
            </div>
          </div>
          <div class="form-group">
            <label for="staffphone" class="col-sm-3 control-label">Phone Number</label>
            <div class="col-sm-9">
              <input name="phone" type="text" class="form-control" id="staffphone" placeholder="Staff Phone" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_staff_phone']; ?>" required>
            </div>
          </div>
          <div class="form-group">
            <label for="staffemail" class="col-sm-3 control-label">Email</label>
            <div class="col-sm-9">
              <input name="email" type="email" class="form-control" id="staffemail" placeholder="Staff Email" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_staff_email']; ?>" required>
            </div>
          </div>

          <div class="form-group">
            <label for="userlevel" class="col-sm-3 control-label">User Level</label>
            <div class="col-sm-9">
              <select name="userlevel" class="form-control">
                <option value="Normal Staff" <?php if(isset($_GET['edit']) && $editrow['fld_staff_userlevel'] == "Normal Staff") echo "selected"; ?>>Normal Staff</option>
                <option value="Admin" <?php if(isset($_GET['edit']) && $editrow['fld_staff_userlevel'] == "Admin") echo "selected"; ?>>Admin</option>
              </select>
            </div>
          </div>

          <div class="form-group">
    <label for="staff_username" class="col-sm-3 control-label">Username</label>
    <div class="col-sm-9">
        <input name="staff_username" type="text" class="form-control" id="staff_username" placeholder="Username" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_staff_username']; ?>" required>
    </div>
</div>
<div class="form-group">
    <label for="staff_password" class="col-sm-3 control-label">Password</label>
    <div class="col-sm-9">
        <input name="staff_password" type="password" class="form-control" id="staff_password" placeholder="Password" required>
    </div>
</div>


          <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
              <?php if (isset($_GET['edit'])) { ?>
                <input type="hidden" name="oldsid" value="<?php echo $editrow['fld_staff_num']; ?>">
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
          <h2>Staffs List</h2>
        </div>
        <table class="table table-striped table-bordered">
          <tr>
            <th>Staff ID</th>
            <th>Full Name</th>
            <th>Gender</th>
            <th>Date Of Birth</th>
            <th>Phone Number</th>
            <th>Email Address</th>
            <th>User Level</th>
            <th>Username</th>
            <th>Password</th>
            <th>Actions</th>
          </tr>
          <?php
          // Pagination setup
          $per_page = 10;
          $page = isset($_GET["page"]) ? $_GET["page"] : 1;
          $start_from = ($page - 1) * $per_page;

          try {
            $stmt = $conn->prepare("SELECT * FROM tbl_staffs_a193067_pt2 LIMIT $start_from, $per_page");
            $stmt->execute();
            $result = $stmt->fetchAll();
          } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
          }

          foreach ($result as $staff) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($staff['fld_staff_num']) . "</td>";
            echo "<td>" . htmlspecialchars($staff['fld_staff_fullname']) . "</td>";
            echo "<td>" . htmlspecialchars($staff['fld_staff_gender']) . "</td>";
            echo "<td>" . htmlspecialchars($staff['fld_staff_dob']) . "</td>";
            echo "<td>" . htmlspecialchars($staff['fld_staff_phone']) . "</td>";
            echo "<td>" . htmlspecialchars($staff['fld_staff_email']) . "</td>";
            echo "<td>" . htmlspecialchars($staff['fld_staff_userlevel']) . "</td>";
            echo "<td>" . htmlspecialchars($staff['fld_staff_username']) . "</td>";
            echo "<td>" . htmlspecialchars($staff['fld_staff_password']) . "</td>";
            echo "<td>
                    <a href='staffs.php?edit=" . $staff['fld_staff_num'] . "' class='btn btn-success btn-xs' role='button'>Edit</a>
                    <a href='staffs.php?delete=" . $staff['fld_staff_num'] . "' onclick='return confirm(\"Are you sure to delete?\");' class='btn btn-danger btn-xs' role='button'>Delete</a>
                  </td>";
            echo "</tr>";
          }
          ?>
        </table>
      </div>
    </div>

    <div class="row">
      <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <nav>
          <ul class="pagination">
            <?php
            // Get the total number of records
            try {
              $stmt = $conn->prepare("SELECT * FROM tbl_staffs_a193067_pt2");
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
              <li><a href="staffs.php?page=<?php echo $page-1 ?>" aria-label="Previous"><span aria-hidden="true">«</span></a></li>
            <?php } ?>
            <?php
            for ($i=1; $i<=$total_pages; $i++) {
              if ($i == $page) {
                echo "<li class=\"active\"><a href=\"staffs.php?page=$i\">$i</a></li>";
              } else {
                echo "<li><a href=\"staffs.php?page=$i\">$i</a></li>";
              }
            }
            ?>
            <?php if ($page==$total_pages) { ?>
              <li class="disabled"><span aria-hidden="true">»</span></li>
            <?php } else { ?>
              <li><a href="staffs.php?page=<?php echo $page+1 ?>" aria-label="Next"><span aria-hidden="true">»</span></a></li>
            <?php } ?>
          </ul>
        </nav>
      </div>
    </div>
  </div>

  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://code.jquery.com/jquery.min.js"></script>
  <!-- Bootstrap's JavaScript -->
  <script src="js/bootstrap.min.js"></script>

</body>
</html>
