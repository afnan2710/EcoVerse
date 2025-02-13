<?php
session_start();
include 'connect.php';

if (isset($_GET['delete'])) {
    $admin_id = $_GET['delete'];
    $conn->query("DELETE FROM `admin` WHERE admin_id = $admin_id") or die($conn->error);
    header('location:admin-account.php');
    exit();
}

if (isset($_POST['update_user'])) {
    $admin_id = $_POST['update_user_id'];
    $admin_name = $_POST['update_user_name'];
    $admin_email = $_POST['update_user_email'];
    $admin_password = $_POST['update_user_pass'];  // Correct variable name

    $result = $conn->query("SELECT admin_password FROM `admin` WHERE admin_id = $admin_id") or die($conn->error);
    $admin = $result->fetch_assoc();

    if (!empty($admin_password)) {
        $hashed_password = password_hash($admin_password, PASSWORD_BCRYPT);
    } else {
        $hashed_password = $admin['admin_password'];
    }

    $conn->query("UPDATE `admin` SET admin_name='$admin_name', admin_email='$admin_email', admin_password='$hashed_password' WHERE admin_id=$admin_id") or die($conn->error);

    header("Location: admin-account.php");
    exit();
}

$result = $conn->query("SELECT * FROM `admin`") or die($conn->error);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.6.0/dist/full.min.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Registered Admin's List</title>
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
  <div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Admin Accounts</h1>
    <table class="table-auto w-full border-collapse border border-gray-200">
      <thead>
        <tr class="bg-gray-100">
          <th class="border border-gray-300 px-4 py-2">Id</th>
          <th class="border border-gray-300 px-4 py-2">Name</th>
          <th class="border border-gray-300 px-4 py-2">Email</th>
          <th class="border border-gray-300 px-4 py-2">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()) : ?>
          <tr>
            <td class="border border-gray-300 px-4 py-2"><?php echo $row['admin_id']; ?></td>
            <td class="border border-gray-300 px-4 py-2"><?php echo $row['admin_name']; ?></td>
            <td class="border border-gray-300 px-4 py-2"><?php echo $row['admin_email']; ?></td>
            <td class="border border-gray-300 px-4 py-2">
              <a href="admin-account.php?delete=<?php echo $row['admin_id']; ?>" onclick="return confirm('Are you sure?')" class="text-red-600">Delete</a>
              <a href="admin-edit.php?id=<?php echo $row['admin_id']; ?>" class="ml-2 text-blue-600">Edit</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
