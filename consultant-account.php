<?php
session_start();
include 'connect.php';

if (isset($_GET['delete'])) {
    $consultant_id = $_GET['delete'];
    $conn->query("DELETE FROM `consultant` WHERE consultant_id = $consultant_id") or die($conn->error);
    header('location:consultant-account.php');
    exit();
}

if (isset($_POST['update_user'])) {
    $consultant_id = $_POST['update_user_id'];
    $consultant_name = $_POST['update_user_name'];
    $consultant_email = $_POST['update_user_email'];
    $consultant_password = $_POST['update_user_pass'];

    $result = $conn->query("SELECT consultant_password FROM `consultant` WHERE consultant_id = $consultant_id") or die($conn->error);
    $consultant = $result->fetch_assoc();

    if (!empty($consultant_password)) {
        $hashed_password = password_hash($consultant_password, PASSWORD_BCRYPT);
    } else {
        $hashed_password = $consultant['consultant_password'];
    }

    $conn->query("UPDATE `consultant` SET consultant_name='$consultant_name', consultant_email='$consultant_email', consultant_password='$hashed_password' WHERE consultant_id=$consultant_id") or die($conn->error);

    header("Location: consultant-account.php");
    exit();
}

$result = $conn->query("SELECT * FROM `consultant`") or die($conn->error);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.6.0/dist/full.min.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Registered Consultant's List</title>
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
    <h1 class="text-2xl font-bold mb-4">Consultant Accounts</h1>
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
            <td class="border border-gray-300 px-4 py-2"><?php echo $row['consultant_id']; ?></td>
            <td class="border border-gray-300 px-4 py-2"><?php echo $row['consultant_name']; ?></td>
            <td class="border border-gray-300 px-4 py-2"><?php echo $row['consultant_email']; ?></td>
            <td class="border border-gray-300 px-4 py-2">
              <a href="consultant-account.php?delete=<?php echo $row['consultant_id']; ?>" onclick="return confirm('Are you sure?')" class="text-red-600">Delete</a>
              <a href="consultant-edit.php?id=<?php echo $row['consultant_id']; ?>" class="ml-2 text-blue-600">Edit</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
