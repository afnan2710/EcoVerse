<?php
include 'connect.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.html");
    exit();
}

if (isset($_POST['submit'])) {
    $admin_name = $_POST['admin_name'];
    $admin_name = filter_var($admin_name, FILTER_SANITIZE_STRING);
    $admin_email = $_POST['admin_email'];
    $admin_email = filter_var($admin_email, FILTER_SANITIZE_EMAIL);
    $admin_password = $_POST['admin_password'];
    $admin_password = filter_var($admin_password, FILTER_SANITIZE_STRING);
    $cpass = $_POST['cpass'];
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

    $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE admin_email = ?");
    $select_admin->bind_param('s', $admin_email);
    $select_admin->execute();
    $select_admin->store_result();

    if ($select_admin->num_rows > 0) {
        $message[] = 'Email already exists!';
    } else {
        if ($admin_password != $cpass) {
            $message[] = 'Re-write Password does not match!';
        } else {
            $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);
            $insert_admin = $conn->prepare("INSERT INTO `admin` (admin_name, admin_email, admin_password) VALUES (?, ?, ?)");
            $insert_admin->bind_param('sss', $admin_name, $admin_email, $hashed_password);
            $insert_admin->execute();
            $message[] = 'New admin registered successfully!';
        }
    }
    $select_admin->close();
    if (isset($insert_admin)) {
        $insert_admin->close();
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
    <title>New Admin Register</title>
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
        <p class="font-pop text-green-900 font-extrabold text-4xl text-center pt-10">Register New Admin</p>
    </div>

    <?php
    if (isset($message)) {
        foreach ($message as $msg) {
            echo '<p class="text-red-600 text-center mt-5">' . $msg . '</p>';
        }
    }
    ?>

    <form class="card-body w-[400px] mx-[650px]" action="" method="POST">
        <div class="form-control">
            <label class="label">
                <span class="label-text">Name</span>
            </label>
            <input type="text" name="admin_name" placeholder="Name" class="input input-bordered" required />
        </div>
        <div class="form-control">
            <label class="label">
                <span class="label-text">Email</span>
            </label>
            <input type="email" name="admin_email" placeholder="Email" class="input input-bordered" required />
        </div>
        <div class="form-control">
            <label class="label">
                <span class="label-text">Password</span>
            </label>
            <input type="password" name="admin_password" placeholder="Password" class="input input-bordered" required />
        </div>
        <div class="form-control">
            <label class="label">
                <span class="label-text">Re-write Password</span>
            </label>
            <input type="password" name="cpass" placeholder="Password" class="input input-bordered" required />
        </div>
        <div class="form-control mt-6">
            <button type="submit" name="submit" class="btn btn-primary">Register as Admin</button>
        </div>
    </form>
</section>
</body>
</html>