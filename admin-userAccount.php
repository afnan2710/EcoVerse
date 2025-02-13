<?php
include 'connect.php';
session_start();

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    // Check if the ID exists in the database before attempting to delete
    $check_user = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
    $check_user->bind_param("i", $delete_id);
    $check_user->execute();
    $result = $check_user->get_result();

    if ($result->num_rows > 0) {
        $delete_user = $conn->prepare("DELETE FROM `users` WHERE id = ?");
        $delete_user->bind_param("i", $delete_id);
        if ($delete_user->execute()) {
            echo "User deleted successfully.";
        } else {
            echo "Error deleting user: " . $delete_user->error;
        }
        $delete_user->close();
    } else {
        echo "User not found.";
    }
    $check_user->close();
    header('Location: admin-userAccount.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="cupcake">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.6.0/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>User List</title>
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

<section class="accounts">
    <h3 class="font-pop text-center text-5xl font-bold text-green-950 mt-10">User Accounts</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 m-10">
    <?php
        $select_accounts = $conn->prepare("SELECT * FROM `users`");
        $select_accounts->execute();
        $result = $select_accounts->get_result();

        if ($result->num_rows > 0) {
            while ($fetch_accounts = $result->fetch_assoc()) {
    ?>
        <div class="card w-96 bg-base-100 shadow-xl p-5">
            <div class="text-center">
            <p class="font-bold text-3xl pb-3"> User ID : <span><?= $fetch_accounts['id']; ?></span> </p>
            <p class="text-xl"> Username : <span><?= $fetch_accounts['firstname']; ?></span> </p>
            <p class="text-xl"> Phone : <span><?= $fetch_accounts['phone']; ?></span> </p>
            <p class="text-xl"> Email : <span><?= $fetch_accounts['email']; ?></span> </p>
            </div>
            <a href="admin-userAccount.php?delete=<?= $fetch_accounts['id']; ?>" onclick="return confirm('Delete this account? The user related information will also be deleted!')" class="btn btn-error mt-5">Delete Account</a>
        </div>
    <?php
            }
        } else {
            echo '<i>No accounts available!</i>';
        }
        $select_accounts->close();
        $conn->close();
    ?>
    </div>
</section>

</body>
</html>
