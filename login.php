<?php
session_start();  // Ensure session starts

include_once 'database.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if (isset($_POST['login'])) {
    $staffUsername = $_POST['username'];  // Staff username from the login form
    $staffPassword = $_POST['password'];  // Staff password from the login form
    
    try {
        // Query the database for the username
        $stmt = $conn->prepare("SELECT * FROM tbl_staffs_a193067_pt2 WHERE fld_staff_username = :username");
        $stmt->bindParam(':username', $staffUsername, PDO::PARAM_STR);
        $stmt->execute();
        
        $staff = $stmt->fetch(PDO::FETCH_ASSOC);

        // Compare the entered password with the stored password
        if ($staff && $staff['fld_staff_password'] == $staffPassword) {
            // Set session variables upon successful login
            $_SESSION['staff_id'] = $staff['fld_staff_num'];
            $_SESSION['staff_name'] = $staff['fld_staff_fullname']; // Store staff's full name
            $_SESSION['username'] = $staff['fld_staff_username'];
            $_SESSION['userlevel'] = $staff['fld_staff_userlevel'];

            // Redirect to the dashboard or main page
            header("Location: index.php");
            exit();
        } else {
            // Set error message on invalid login
            $error = "Invalid username or password.";
        }
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-image: url(background.png);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-color: #333;
            color: #ffffff;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.5);
            padding: 40px;
            border-radius: 10px;
            width: 350px;
            text-align: left;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }

        h2 {
            color: #000000;
            text-align: center;
            font-size: 28px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: #000000;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #000000;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            box-sizing: border-box;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #363232;
        }

        p {
            color: red;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Welcome to NextGen Sports</h2>
        <form method="POST">
            <label>Username:</label>
            <input type="text" name="username" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <button type="submit" name="login">Login</button>

            <?php if (isset($error)) echo "<p>$error</p>"; ?>
        </form>
    </div>
</body>
</html>
