<?php
include 'connect.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    // Redirect to login page if not logged in
    header("Location: admin-login.html");
    exit();
}


if (isset($_POST['submit'])) {
    $consultant_name = $_POST['consultant_name'];
    $consultant_name = filter_var($consultant_name, FILTER_SANITIZE_STRING);
    $consultant_email = $_POST['consultant_email'];
    $consultant_email = filter_var($consultant_email, FILTER_SANITIZE_EMAIL);
    $consultant_password = $_POST['consultant_password'];
    $consultant_password = filter_var($consultant_password, FILTER_SANITIZE_STRING);
    $cpass = $_POST['cpass'];
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

    $select_consultant = $conn->prepare("SELECT * FROM `consultant` WHERE consultant_email = ?");
    $select_consultant->bind_param('s', $consultant_email);
    $select_consultant->execute();
    $select_consultant->store_result();

    if ($select_consultant->num_rows > 0) {
        $message[] = 'Email already exists!';
    } else {
        if ($consultant_password != $cpass) {
            $message[] = 'Re-write Password does not match!';
        } else {
            $hashed_password = password_hash($consultant_password, PASSWORD_DEFAULT);
            $insert_consultant = $conn->prepare("INSERT INTO `consultant` (consultant_name, consultant_email, consultant_password) VALUES (?, ?, ?)");
            $insert_consultant->bind_param('sss', $consultant_name, $consultant_email, $hashed_password);
            $insert_consultant->execute();
            $message[] = 'New consultant registered successfully!';
        }
    }
    $select_consultant->close();
    if (isset($insert_consultant)) {
        $insert_consultant->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.6.0/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>New Consultant Register</title>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    clifford: '#da373d',
                    'plant-primary': '#E76F51',
                    'plant-primary-bg': 'rgba(231, 111, 81, 0.10)',
                }
            }
        }
    }
    </script>
</head>
<body>

<?php include 'admin-header.php'; ?>

<section class="form-container">
    <div>
        <p class="font-pop text-green-900 font-extrabold text-4xl text-center pt-10">Register New Consultant</p>
    </div>

    <?php
    if (isset($message)) {
        foreach ($message as $msg) {
            echo '<p class="text-green-600 text-center mt-5">' . $msg . '</p>';
        }
    }
    ?>

    <form class="card-body w-[400px] mx-[650px]" action="" method="POST">
        <div class="form-control">
            <label class="label">
                <span class="label-text">Name</span>
            </label>
            <input type="text" name="consultant_name" placeholder="Name" class="input input-bordered" required />
        </div>
        <div class="form-control">
            <label class="label">
                <span class="label-text">Email</span>
            </label>
            <input type="email" name="consultant_email" placeholder="Email" class="input input-bordered" required />
        </div>
        <div class="form-control">
            <label class="label">
                <span class="label-text">Password</span>
            </label>
            <input type="password" name="consultant_password" placeholder="Password" class="input input-bordered" required />
        </div>
        <div class="form-control">
            <label class="label">
                <span class="label-text">Re-write Password</span>
            </label>
            <input type="password" name="cpass" placeholder="Password" class="input input-bordered" required />
        </div>
        <div class="form-control mt-6">
            <button type="submit" name="submit" class="btn btn-primary">Register as Consultant</button>
        </div>
    </form>
</section>
</body>
</html>
