<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once 'products_crud.php';

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
    <title>Sporting Goods Store Ordering System: Products</title>
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

    <script>
        function showDialog(message) {
            document.getElementById('dialogMessage').innerText = message;
            document.getElementById('dialogOverlay').style.display = 'block';
            document.getElementById('dialogBox').style.display = 'block';
        }

        function closeDialog() {
            document.getElementById('dialogOverlay').style.display = 'none';
            document.getElementById('dialogBox').style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isset($_SESSION['image_upload_success'])): ?>
                showDialog("<?php echo $_SESSION['image_upload_success']; ?>");
                <?php unset($_SESSION['image_upload_success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['image_error'])): ?>
                showDialog("<?php echo $_SESSION['image_error']; ?>");
                <?php unset($_SESSION['image_error']); ?>
            <?php endif; ?>

             });

           document.addEventListener('DOMContentLoaded', function() {
            // Get the user role from PHP
            const isAdmin = <?php echo json_encode($isAdmin); ?>; // true or false
            const buttons = document.querySelectorAll('.btn-dark, .btn-success, .btn-danger');

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
          <h2>Create New Product</h2>
        </div>
      <form action="products.php" method="post" class="form-horizontal" enctype="multipart/form-data">
          <div class="form-group">
            <label for="productid" class="col-sm-3 control-label">Product ID</label>
            <div class="col-sm-9">
              <input name="pid" type="text" class="form-control" id="productid" placeholder="Product ID" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_product_num']; ?>" required>
            </div>
          </div>
          <div class="form-group">
            <label for="productname" class="col-sm-3 control-label">Name</label>
            <div class="col-sm-9">
              <input name="name" type="text" class="form-control" id="productname" placeholder="Product Name" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_product_name']; ?>" required>
            </div>
          </div>
          <div class="form-group">
            <label for="productprice" class="col-sm-3 control-label">Price</label>
            <div class="col-sm-9">
              <input name="price" type="number" class="form-control" id="productprice" placeholder="Product Price" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_product_price']; ?>" min="0.0" step="0.01" required>
            </div>
          </div>
          <div class="form-group">
            <label for="producttype" class="col-sm-3 control-label">Type</label>
            <div class="col-sm-9">
              <select name="type" class="form-control" id="producttype" required>
                <option value="Running Shoes" <?php if(isset($_GET['edit']) && $editrow['fld_product_type'] == "Running Shoes") echo "selected"; ?>>Running Shoes</option>
                <option value="Slide Shoes" <?php if(isset($_GET['edit']) && $editrow['fld_product_type'] == "Slide Shoes") echo "selected"; ?>>Slide Shoes</option>
                <option value="Sneaker" <?php if(isset($_GET['edit']) && $editrow['fld_product_type'] == "Sneaker") echo "selected"; ?>>Sneaker</option>
                <option value="Socks" <?php if(isset($_GET['edit']) && $editrow['fld_product_type'] == "Socks") echo "selected"; ?>>Socks</option>
                <option value="Hoodies&Sweatshirts" <?php if(isset($_GET['edit']) && $editrow['fld_product_type'] == "Hoodies&Sweatshirts") echo "selected"; ?>>Hoodies & Sweatshirts</option>
                <option value="Jackets" <?php if(isset($_GET['edit']) && $editrow['fld_product_type'] == "Jackets") echo "selected"; ?>>Jackets</option>
                <option value="T-Shirt&Tops" <?php if(isset($_GET['edit']) && $editrow['fld_product_type'] == "T-Shirt&Tops") echo "selected"; ?>>T-Shirt & Tops</option>
                <option value="Pants" <?php if(isset($_GET['edit']) && $editrow['fld_product_type'] == "Pants") echo "selected"; ?>>Pants</option>
                <option value="Shorts" <?php if(isset($_GET['edit']) && $editrow['fld_product_type'] == "Shorts") echo "selected"; ?>>Shorts</option>
                <option value="Leggings" <?php if(isset($_GET['edit']) && $editrow['fld_product_type'] == "Leggings") echo "selected"; ?>>Leggings</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="productsize" class="col-sm-3 control-label">Size</label>
            <div class="col-sm-9">
              <select name="size" class="form-control" id="productsize" required>
                <option value="38" <?php if(isset($_GET['edit']) && $editrow['fld_product_size'] == "38") echo "selected"; ?>>38</option>
                <option value="39" <?php if(isset($_GET['edit']) && $editrow['fld_product_size'] == "39") echo "selected"; ?>>39</option>
                <option value="40" <?php if(isset($_GET['edit']) && $editrow['fld_product_size'] == "40") echo "selected"; ?>>40</option>
                <option value="41" <?php if(isset($_GET['edit']) && $editrow['fld_product_size'] == "41") echo "selected"; ?>>41</option>
                <option value="S" <?php if(isset($_GET['edit']) && $editrow['fld_product_size'] == "S") echo "selected"; ?>>S</option>
                <option value="M" <?php if(isset($_GET['edit']) && $editrow['fld_product_size'] == "M") echo "selected"; ?>>M</option>
                <option value="L" <?php if(isset($_GET['edit']) && $editrow['fld_product_size'] == "L") echo "selected"; ?>>L</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="productquantity" class="col-sm-3 control-label">Quantity</label>
            <div class="col-sm-9">
              <input name="quantity" type="number" class="form-control" id="productquantity" placeholder="Product Quantity" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_product_quantity']; ?>" min="0" required>
            </div>
          </div>
          <div class="form-group">
            <label for="productgender" class="col-sm-3 control-label">Gender</label>
            <div class="col-sm-9">
              <div class="radio">
                <label><input name="gender" type="radio" value="Male" <?php if(isset($_GET['edit']) && $editrow['fld_product_gender'] == "Male") echo "checked"; ?>> Male</label>
              </div>
              <div class="radio">
                <label><input name="gender" type="radio" value="Female" <?php if(isset($_GET['edit']) && $editrow['fld_product_gender'] == "Female") echo "checked"; ?>> Female</label>
              </div>
              <div class="radio">
                <label><input name="gender" type="radio" value="Unisex" <?php if(isset($_GET['edit']) && $editrow['fld_product_gender'] == "Unisex") echo "checked"; ?>> Unisex</label>
              </div>
            </div>
          </div>

        <div class="form-group">
                        <label for="productimage" class="col-sm-3 control-label">Product Image</label>
                        <div class="col-sm-9">
                            <input type="file" name="image" class="form-control" id="productimage" accept="image/*" required>
                        </div>
                    </div>


          <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <?php if (isset($_GET['edit'])) { ?>
                <input type="hidden" name="oldpid" value="<?php echo $editrow['fld_product_num']; ?>">
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
      <!-- Success Modal for displaying success messages -->
          <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="successModalLabel">Success</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                      <div class="modal-body" id="successMessage">
                          <!-- Success message will be injected here -->
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      </div>
                  </div>
              </div>
          </div>

          <!-- Error Modal for displaying error messages -->
          <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="errorModalLabel">Error</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                      <div class="modal-body" id="errorMessage">
                          <!-- Error message will be injected here -->
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      </div>
                  </div>
              </div>
          </div>

        </form>
      </div>
    </div>

    <div class="row">
      <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="page-header">
          <h2>Products List</h2>
        </div>
        <table class="table table-striped table-bordered">
          <tr>
            <th>Product ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Type</th>
            <th>Size</th>
            <th>Quantity</th>
            <th>Gender</th>
            <th>Actions</th>
          </tr>

         
        <?php
// Pagination setup
$per_page = 10; // Products per page
$page = isset($_GET["page"]) ? $_GET["page"] : 1; // Current page
$start_from = ($page - 1) * $per_page; // Offset for the query

// Database connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch products for current page
    $stmt = $conn->prepare("SELECT * FROM tbl_products_a193067_pt2 LIMIT $start_from, $per_page");
    $stmt->execute();
    $result = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Displaying the products
foreach ($result as $readrow) {
    ?>
    <tr>
        <td><?php echo $readrow['fld_product_num']; ?></td>
        <td><?php echo $readrow['fld_product_name']; ?></td>
        <td><?php echo $readrow['fld_product_price']; ?></td>
        <td><?php echo $readrow['fld_product_type']; ?></td>
        <td><?php echo $readrow['fld_product_size']; ?></td>
        <td><?php echo $readrow['fld_product_quantity']; ?></td>
        <td><?php echo $readrow['fld_product_gender']; ?></td>
        <td>
            <a href="products_details.php?pid=<?php echo $readrow['fld_product_num']; ?>" class="btn btn-warning btn-xs" role="button">Details</a>
            <a href="products.php?edit=<?php echo $readrow['fld_product_num']; ?>" class="btn btn-success btn-xs" role="button">Edit</a>
            <a href="products.php?delete=<?php echo $readrow['fld_product_num']; ?>" onclick="return confirm('Are you sure to delete?');" class="btn btn-danger btn-xs" role="button">Delete</a>
        </td>
    </tr>
    <?php
}
?>

</table>
</div>
</div>

<!-- Pagination -->
<div class="row">
    <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <nav>
            <ul class="pagination">
                <?php
                // Fetch total records for pagination
                try {
                    $stmt = $conn->prepare("SELECT * FROM tbl_products_a193067_pt2");
                    $stmt->execute();
                    $result = $stmt->fetchAll();
                    $total_records = count($result);
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }

                // Calculate total pages
                $total_pages = ceil($total_records / $per_page);

                // Previous Page Link
                if ($page == 1) {
                    echo '<li class="disabled"><span aria-hidden="true">«</span></li>';
                } else {
                    echo '<li><a href="products.php?page=' . ($page - 1) . '" aria-label="Previous"><span aria-hidden="true">«</span></a></li>';
                }

                // Page Numbers
                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($i == $page) {
                        echo '<li class="active"><a href="products.php?page=' . $i . '">' . $i . '</a></li>';
                    } else {
                        echo '<li><a href="products.php?page=' . $i . '">' . $i . '</a></li>';
                    }
                }

                // Next Page Link
                if ($page == $total_pages) {
                    echo '<li class="disabled"><span aria-hidden="true">»</span></li>';
                } else {
                    echo '<li><a href="products.php?page=' . ($page + 1) . '" aria-label="Next"><span aria-hidden="true">»</span></a></li>';
                }
                ?>
            </ul>
        </nav>
    </div>
</div>

<!-- jQuery and Bootstrap Scripts -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>
