<?php
session_start();
include 'connect.php';

if (isset($_GET['id'])) {
    $consultant_id = $_GET['id'];
    $result = $conn->query("SELECT * FROM `consultant` WHERE consultant_id = $consultant_id") or die($conn->error);
    $consultant = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.6.0/dist/full.min.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Edit Consultant</title>
</head>
<body>
  <?php include 'admin-header.php'; ?>
  <div class="container mx-auto p-4">
    <h1 class="text-center text-3xl font-bold mb-4">Edit Consultant Info</h1>
    <form action="consultant-account.php" method="post" class="max-w-lg mx-auto bg-white p-8 rounded shadow">
      <input type="hidden" name="update_user_id" value="<?php echo $consultant['consultant_id']; ?>">
      <div class="mb-4">
        <label for="update_user_name" class="block text-gray-700">Name:</label>
        <input type="text" name="update_user_name" value="<?php echo $consultant['consultant_name']; ?>" required class="w-full px-4 py-2 border rounded">
      </div>
      <div class="mb-4">
        <label for="update_user_email" class="block text-gray-700">Email:</label>
        <input type="email" name="update_user_email" value="<?php echo $consultant['consultant_email']; ?>" required class="w-full px-4 py-2 border rounded">
      </div>
      <div class="mb-4">
        <label for="update_user_pass" class="block text-gray-700">New Password (Leave blank to keep current password):</label>
        <input type="password" name="update_user_pass" placeholder="Enter new password" class="w-full px-4 py-2 border rounded">
      </div>
      <button type="submit" name="update_user" class="w-full bg-green-600 text-white px-4 py-2 rounded">Update</button>
    </form>
  </div>
</body>
</html>
