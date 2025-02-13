<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_name = $_POST['admin_name'];
    $admin_email = $_POST['admin_email'];
    $captcha_result = $_POST['captcha_result'];
    $captcha_expected = $_SESSION['captcha_expected'];

    if ($captcha_result != $captcha_expected) {
        $message = "Incorrect captcha. Please try again.";
    } else {
        include 'connect.php';

        $stmt = $conn->prepare("SELECT admin_password FROM admin WHERE admin_name = ? AND admin_email = ?");
        $stmt->bind_param("ss", $admin_name, $admin_email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($admin_password);
            $stmt->fetch();
            $message = "Your password is: " . htmlspecialchars($admin_password);
        } else {
            $message = "No admin found with the provided information.";
        }

        $stmt->close();
        $conn->close();
    }
}

$_SESSION['captcha_a'] = rand(1, 10);
$_SESSION['captcha_b'] = rand(1, 10);
$_SESSION['captcha_expected'] = $_SESSION['captcha_a'] + $_SESSION['captcha_b'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Admin</title>
    <style>
        body {
            font-family: 'Comic Sans MS', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: white;
            color: white;
        }
        .container {
            background-color: #3B7D1F;
            border-radius: 8px;
            box-shadow: 14px 14px 8px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 400px;
            width: 100%;
        }
        .container2 {
            width: 50%;
            margin-left: 90px;
        }
        h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        label {
            font-size: 16px;
        }
        input {
            padding: 10px;
            border: none;
            border-radius: 20px;
            font-size: 13px;
        }
        button[type="submit"] {
            background-color: white;
            color: #007d3c;
            padding: 10px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            margin-top: 20px;
        }
        button[type="submit"]:hover {
            background-color: skyblue;
        }
        .message {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Account Recovery</h2>
        <form action="admin-forgetPassword.php" method="post">
            <label for="admin_name">Name</label>
            <input type="text" id="admin_name" name="admin_name" placeholder="Your Name" required>
            <label for="admin_email">Email</label>
            <input type="email" id="admin_email" name="admin_email" placeholder="Your Email" required>
            <label for="captcha">Captcha: <?php echo $_SESSION['captcha_a'] . " + " . $_SESSION['captcha_b']; ?></label>
            <input type="number" id="captcha" name="captcha_result" placeholder="Answer" required>
            <button type="submit">Submit</button>
        </form>
        <div class="message">
            <?php
                if (isset($message)) {
                    echo htmlspecialchars($message);
                }
            ?>
        </div>
        <a href="admin-login.html"><button class="container2" type="submit">Login as Admin</button></a>
    </div>
</body>
</html>
