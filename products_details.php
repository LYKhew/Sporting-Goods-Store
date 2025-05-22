<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once 'products_crud.php';

/// Check if user is logged in
if (!isset($_SESSION['userlevel'])) {
    echo "Please log in first.";
    exit();
}
  include_once 'database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sporting Goods Store Ordering System: Product Details</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Custom styles for the page */
    .product-image {
      max-width: 100%;
      max-height: 300px;
      margin-bottom: 20px;
    }
    .panel-heading {
      background-color: #343a40;
      color: white;
    }
    .panel-body {
      font-size: 16px;
    }
    .table > tbody > tr > td {
      vertical-align: middle;
    }
  </style>
</head>
<body>

<?php include_once 'nav_bar.php'; ?>

<?php
try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $stmt = $conn->prepare("SELECT * FROM tbl_products_a193067_pt2 WHERE fld_product_num = :pid");
  $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
  $pid = $_GET['pid'];
  $stmt->execute();
  $readrow = $stmt->fetch(PDO::FETCH_ASSOC);
}
catch(PDOException $e) {
  echo "Error: " . $e->getMessage();
}
$conn = null;
?>

<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12 col-sm-5 col-sm-offset-1 col-md-4 col-md-offset-2 well well-sm text-center">
  <?php if ($readrow['fld_product_image'] == "") {
    echo "No image available";
  }
  else { ?>
    <img src="products/<?php echo $readrow['fld_product_image'] ?>" class="product-image img-responsive center-block">
  <?php } ?>
</div>

    <div class="col-xs-12 col-sm-5 col-md-4">
      <div class="panel panel-default">
        <div class="panel-heading"><strong>Product Details</strong></div>
        <div class="panel-body">
          Below are specifications of the product.
        </div>
        <table class="table">
          <tr>
            <td class="col-xs-4 col-sm-4 col-md-4"><strong>Product ID</strong></td>
            <td><?php echo $readrow['fld_product_num'] ?></td>
          </tr>
          <tr>
            <td><strong>Name</strong></td>
            <td><?php echo $readrow['fld_product_name'] ?></td>
          </tr>
          <tr>
            <td><strong>Price</strong></td>
            <td>RM <?php echo $readrow['fld_product_price'] ?></td>
          </tr>
          <tr>
            <td><strong>Type</strong></td>
            <td><?php echo $readrow['fld_product_type'] ?></td>
          </tr>
          <tr>
            <td><strong>Size</strong></td>
            <td><?php echo $readrow['fld_product_size'] ?></td>
          </tr>
          <tr>
            <td><strong>Quantity</strong></td>
            <td><?php echo $readrow['fld_product_quantity'] ?></td>
          </tr>
          <tr>
            <td><strong>Gender</strong></td>
            <td><?php echo $readrow['fld_product_gender'] ?></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- jQuery and Bootstrap scripts -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>
