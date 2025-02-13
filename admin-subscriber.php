<?php
session_start();
include 'connect.php';

$result = $conn->query("SELECT email FROM `subscribers`") or die($conn->error);
$emails = [];
while ($row = $result->fetch_assoc()) {
    $emails[] = $row['email'];
}

$emails_string = implode(',', $emails);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.6.0/dist/full.min.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Subscribers List</title>
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
    <h1 class="text-2xl font-bold mb-4">Newsletter Subscribers</h1>
    <table class="table-auto w-auto border-collapse border border-gray-200">
      <thead>
        <tr class="bg-gray-100">
          <th class="border border-gray-300 px-4 py-2">Id</th>
          <th class="border border-gray-300 px-4 py-2">Email</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $result->data_seek(0);
        $id = 1;
        while ($row = $result->fetch_assoc()) : ?>
          <tr>
            <td class="border border-gray-300 px-4 py-2"><?php echo $id++; ?></td>
            <td class="border border-gray-300 px-4 py-2"><?php echo $row['email']; ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <div class="mt-4">
      <a href="mailto:?bcc=<?php echo $emails_string; ?>
         target="_blank" 
         class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Send Newsletter Update to Subscribers
      </a>
    </div>
  </div>
</body>
</html>
