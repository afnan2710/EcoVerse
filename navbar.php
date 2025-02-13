<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$cartCount = 0;
$cartTotal = 0;

if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += $item['qty'];
        $cartTotal += $item['qty'] * $item['price'];
    }
}

$firstname = isset($_SESSION['firstname']) ? $_SESSION['firstname'] : 'Guest';

if (isset($message)) {
    foreach ([$message] as [$msg]) {
        echo '
        <div class="message">
            <span>' . htmlspecialchars($msg) . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
    }
}
?>

<nav>
    <div class="navbar bg-base-100 justify-between w-full">
        <div class="flex">
            <a class="btn btn-ghost text-xl" href="index.php">EcoVerse</a>
        </div>
        <div class="flex w-40 h-20 ml-20">
            <img src="logo.png" alt="EcoVerse Logo">
        </div>
        <div class="flex items-center">
            <div class="dropdown dropdown-hover">
                <div tabindex="0" role="button" class="btn m-1">Other Products</div>
                <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                    <li><a href="index.php?search=&filter=Fertilizer">Fertilizer</a></li>
                    <li><a href="index.php?search=&filter=Tools">Tools</a></li>
                </ul>
            </div>

            <div class="flex items-center justify-between">
                <div class="dropdown dropdown-end px-2">
                    <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
                        <div class="indicator">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <?php if ($cartCount > 0) : ?>
                                <span class="badge badge-sm indicator-item"><?php echo $cartCount; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div tabindex="0" class="mt-3 z-[1] card card-compact dropdown-content w-52 bg-base-100 shadow">
                        <div class="card-body">
                            <span class="font-bold text-lg"><?php echo $cartCount; ?> items</span>
                            <span class="text-info">Subtotal: $<?php echo number_format($cartTotal, 2); ?></span>
                            <div class="card-actions">
                                <a href="cart.php"><button class="btn btn-primary btn-block">View cart</button></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dropdown dropdown-end">
                    <div tabindex="0" role="button" class="btn btn-outline btn-primary w-[70px]">
                        <div class="w-[200px] rounded-full pt-1 text-xs">
                            <?php echo htmlspecialchars($firstname); ?>
                        </div>
                    </div>
                    <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                        <li>
                            <?php if ($firstname != 'Guest'): ?>
                                <a class="justify-between" href="profile.php">Profile Management</a>
                            <?php else: ?>
                                <a class="justify-between" href="login.html">User</a>
                            <?php endif; ?>
                        </li>
                        <li><a href="consultant-login.html">Consultant</a></li>
                        <li><a href="admin-login.html">Admin</a></li>
                        <?php if ($firstname != 'Guest'): ?>
                            <li><a href="logout.php?action=logout">Logout</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
