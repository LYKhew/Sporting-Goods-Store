<?php

include_once 'database.php';

// Establish database connection (use the correct DB credentials for MySQL connection)
try {
    // Correct DB credentials for connection
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Create staff record (including user level, username, and password)
if (isset($_POST['create'])) {
    try {
        // Bind values and insert the staff record into the database
        $stmt = $conn->prepare("INSERT INTO tbl_staffs_a193067_pt2(fld_staff_num, fld_staff_fullname, fld_staff_gender, fld_staff_dob, 
                                fld_staff_phone, fld_staff_email, fld_staff_userlevel, fld_staff_username, fld_staff_password) 
                                VALUES(:sid, :fullname, :gender, :dob, :phone, :email, :userlevel, :staff_username, :staff_password)");

        // Bind parameters
        $stmt->bindParam(':sid', $sid, PDO::PARAM_STR);
        $stmt->bindParam(':fullname', $fullname, PDO::PARAM_STR);
        $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
        $stmt->bindParam(':dob', $dob, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':userlevel', $userlevel, PDO::PARAM_STR);
        $stmt->bindParam(':staff_username', $staff_username, PDO::PARAM_STR);
        $stmt->bindParam(':staff_password', $staff_password, PDO::PARAM_STR); // Store password as plain text

        // Get POST data
        $sid = $_POST['sid'];
        $fullname = $_POST['fullname'];
        $gender = $_POST['gender'];
        $dob = $_POST['dob'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $userlevel = $_POST['userlevel'];  // 'Admin' or 'Normal Staff'
        $staff_username = $_POST['staff_username'];
        $staff_password = $_POST['staff_password'];  // Store as plain text

        // Execute the insert statement
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

if (isset($_POST['update'])) {
    try {
        // Prepare the update query
        $stmt = $conn->prepare("UPDATE tbl_staffs_a193067_pt2 SET
            fld_staff_num = :sid, fld_staff_fullname = :fullname, fld_staff_gender = :gender, fld_staff_dob = :dob,
            fld_staff_phone = :phone, fld_staff_email = :email, fld_staff_userlevel = :userlevel,
            fld_staff_username = :staff_username, fld_staff_password = :staff_password
            WHERE fld_staff_num = :oldsid");

        // Bind parameters
        $stmt->bindParam(':sid', $sid, PDO::PARAM_STR);
        $stmt->bindParam(':fullname', $fullname, PDO::PARAM_STR);
        $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
        $stmt->bindParam(':dob', $dob, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':userlevel', $userlevel, PDO::PARAM_STR);
        $stmt->bindParam(':staff_username', $staff_username, PDO::PARAM_STR);
        $stmt->bindParam(':staff_password', $staff_password, PDO::PARAM_STR);  // Store password as plain text
        $stmt->bindParam(':oldsid', $oldsid, PDO::PARAM_STR);

        // Get POST data
        $sid = $_POST['sid'];
        $fullname = $_POST['fullname'];
        $gender = $_POST['gender'];
        $dob = $_POST['dob'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $userlevel = $_POST['userlevel'];
        $staff_username = $_POST['staff_username'];
        $staff_password = !empty($_POST['staff_password']) ? $_POST['staff_password'] : $_POST['old_password'];  // Only update if new password is provided
        $oldsid = $_POST['oldsid'];

        // Execute the update statement
        $stmt->execute();

        // Redirect to staff page after update
        header("Location: staffs.php");
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Delete staff record
if (isset($_GET['delete'])) {
    try {
        // Prepare the delete query
        $stmt = $conn->prepare("DELETE FROM tbl_staffs_a193067_pt2 WHERE fld_staff_num = :sid");

        // Bind parameter
        $stmt->bindParam(':sid', $sid, PDO::PARAM_STR);

        // Get the staff ID from the URL
        $sid = $_GET['delete'];

        // Execute the delete statement
        $stmt->execute();

        // Redirect to staff page after deletion
        header("Location: staffs.php");
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Edit staff record (fetching data to display in the form)
if (isset($_GET['edit'])) {
    try {
        // Prepare the select query to fetch staff record
        $stmt = $conn->prepare("SELECT * FROM tbl_staffs_a193067_pt2 WHERE fld_staff_num = :sid");

        // Bind parameter
        $stmt->bindParam(':sid', $sid, PDO::PARAM_STR);

        // Get the staff ID from the URL
        $sid = $_GET['edit'];

        // Execute the select statement
        $stmt->execute();

        // Fetch the staff record
        $editrow = $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Close database connection
$conn = null;

?>
