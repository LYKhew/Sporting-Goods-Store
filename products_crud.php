<?php

include_once 'database.php';

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


// Define the target directory for image upload
$target_dir = "products/"; // Ensure this directory exists

function validate_image($file) {
    // Allowed MIME types
    $valid_mime_types = ['image/jpeg', 'image/png'];
    // Allowed file extensions
    $valid_extensions = ['jpg', 'jpeg', 'png'];

    // Check if the file is a valid image
    $check = getimagesize($file['tmp_name']);
    if ($check === false) {
        $_SESSION['image_error'] = "The uploaded file is not a valid image.";
        return false;
    }

    // Check MIME type
    $mime_type = mime_content_type($file['tmp_name']);
    if (!in_array($mime_type, $valid_mime_types)) {
        $_SESSION['image_error'] = "Invalid file type. Only JPG and PNG images are allowed.";
        return false;
    }

    // Check file extension
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($file_extension, $valid_extensions)) {
        $_SESSION['image_error'] = "Invalid file extension. Only .jpg, .jpeg, and .png files are allowed.";
        return false;
    }

    // Check image dimensions
    if ($check[0] > 300 || $check[1] > 400) {
        $_SESSION['image_error'] = "Image dimensions exceed the allowed limit of 300x400 pixels.";
        return false;
    }

    return true;
}

if (isset($_POST['create'])) {
    // Get form data
    $pid = $_POST['pid'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $type = $_POST['type'];
    $size = $_POST['size'];
    $quantity = $_POST['quantity'];
    $gender = $_POST['gender'];

    // Validate form fields
    if (empty($pid) || empty($name) || empty($price) || empty($type) || empty($size) || empty($quantity) || empty($gender)) {
        $_SESSION['form_error'] = "Please fill in all required fields.";
    } else {
        // Check if product ID already exists in the database
        $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_products_a193067_pt2 WHERE fld_product_num = :pid");
        $stmt->bindParam(':pid', $pid);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        
        if ($count > 0) {
            // Product ID already exists, handle it as a duplicate entry
            $_SESSION['form_error'] = "Product with this ID already exists. Please choose a different ID.";
        } else {
            // Process image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                // Validate the image
                $image = $_FILES['image'];
                if (validate_image($image)) {
                    // Use the primary key (pid) as the image name
                    $image_name = $pid . ".png";  // Assuming $pid is the primary key
                    
                    // Set the target file path
                    $target_file = $target_dir . $image_name;

                    // Move the uploaded file to the target directory
                    if (move_uploaded_file($image['tmp_name'], $target_file)) {
                        // Save product details into database
                        $stmt = $conn->prepare("INSERT INTO tbl_products_a193067_pt2 (fld_product_num, fld_product_name, fld_product_price, fld_product_type, fld_product_size, fld_product_quantity, fld_product_gender, fld_product_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                        $stmt->execute([$pid, $name, $price, $type, $size, $quantity, $gender, $image_name]);
                        $_SESSION['image_upload_success'] = true; // Indicate success
                        header("Location: products.php");
                        exit; // Ensure no further code is executed
                    } else {
                        $_SESSION['image_error'] = "Sorry, there was an error uploading your file.";
                    }
                } else {
                    // If validation fails, redirect to the same page to show the error
                    header("Location: products.php");
                    exit;
                }
            } else {
                $_SESSION['image_error'] = "No image selected or there was an error with the upload.";
 }
        }
    }
}

// If there is a form error, show it
if (isset($_SESSION['form_error'])) {
    echo "<script>document.addEventListener('DOMContentLoaded', function() { $('#errorMessage').text('" . $_SESSION['form_error'] . "'); $('#errorModal').modal('show'); });</script>";
    unset($_SESSION['form_error']);
}

// If there is an image error, show it
if (isset($_SESSION['image_error'])) {
    echo "<script>document.addEventListener('DOMContentLoaded', function() { $('#errorMessage').text('" . $_SESSION['image_error'] . "'); $('#errorModal').modal('show'); });</script>";
    unset($_SESSION['image_error']);
}

// Insert product data into database
try {
    if (isset($_POST['create']) && !isset($_SESSION['form_error'])) {
        // Only insert data if the form was submitted and there were no validation errors
        $stmt = $conn->prepare("INSERT INTO tbl_products_a193067_pt2 (fld_product_num, fld_product_name, fld_product_price, fld_product_type, fld_product_size, fld_product_quantity, fld_product_gender, fld_product_image)
                                VALUES (:pid, :name, :price, :type, :size, :quantity, :gender, :image)");

        $stmt->bindParam(':pid', $pid);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':size', $size);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':image', $image_name); // Make sure to use the correct image name here.

        $stmt->execute();

        // Redirect after successful insert
        header("Location: products.php");
        exit;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Update
if (isset($_POST['update'])) {
    try {
        // Get form data
        $pid = $_POST['pid'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $type = $_POST['type'];
        $size = $_POST['size'];
        $quantity = $_POST['quantity'];
        $gender = $_POST['gender'];
        $oldpid = $_POST['oldpid']; // Make sure the oldpid is passed from the form

        // Check if a new image is uploaded
        $image_name = null;  // Default to null if no image is uploaded

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            // Validate the image
            $image = $_FILES['image'];
            if (validate_image($image)) {
                // Use the primary key (pid) as the image name
                $image_name = $pid . ".png";  // Assuming $pid is the primary key
                
                // Set the target file path
                $target_file = $target_dir . $image_name;

                // Move the uploaded file to the target directory
                if (move_uploaded_file($image['tmp_name'], $target_file)) {
                    $_SESSION['image_upload_success'] = true; // Indicate success
                } else {
                    $_SESSION['image_error'] = "Sorry, there was an error uploading your file.";
                }
            } else {
                $_SESSION['image_error'] = "Invalid image file.";
            }
        }

        // Prepare the SQL query to update the product
$sql = "UPDATE tbl_products_a193067_pt2 SET 
    fld_product_num = :pid,
    fld_product_name = :name, 
    fld_product_price = :price, 
    fld_product_type = :type,
    fld_product_size = :size, 
    fld_product_quantity = :quantity,
    fld_product_gender = :gender";

// Add the image field only if a new image is uploaded
if ($image_name) {
    $sql .= ", fld_product_image = :image";
}

$sql .= " WHERE fld_product_num = :oldpid";

$stmt = $conn->prepare($sql);

// Bind parameters
$stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
$stmt->bindParam(':name', $name, PDO::PARAM_STR);
$stmt->bindParam(':price', $price, PDO::PARAM_INT);
$stmt->bindParam(':type', $type, PDO::PARAM_STR);
$stmt->bindParam(':size', $size, PDO::PARAM_STR);
$stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
$stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
$stmt->bindParam(':oldpid', $oldpid, PDO::PARAM_STR);

// Bind the image parameter only if it's included in the query
if ($image_name) {
    $stmt->bindParam(':image', $image_name, PDO::PARAM_STR);
}

// Execute the query
$stmt->execute();


        // Redirect after successful update
        header("Location: products.php");
        exit;

    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Show success message if the image upload was successful
if (isset($_SESSION['image_upload_success'])) {
    echo "<script>document.addEventListener('DOMContentLoaded', function() { 
        $('#successMessage').text('Image uploaded successfully!'); 
        $('#successModal').modal('show'); 
    });</script>";
    unset($_SESSION['image_upload_success']);
}

// Delete
if (isset($_GET['delete'])) {
    try {
        $stmt = $conn->prepare("DELETE FROM tbl_products_a193067_pt2 WHERE fld_product_num = :pid");
        $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
        
        $pid = $_GET['delete'];
        $stmt->execute();

        header("Location: products.php");
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Edit
if (isset($_GET['edit'])) {
    try {
        $stmt = $conn->prepare("SELECT * FROM tbl_products_a193067_pt2 WHERE fld_product_num = :pid");
        $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
        
        $pid = $_GET['edit'];
        $stmt->execute();

        $editrow = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}