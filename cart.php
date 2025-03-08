<?php 
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

if (isset($_POST['remove'])) {
  $index = $_POST['remove'];  
  unset($_SESSION['cart'][$index]);  
  $_SESSION['cart'] = array_values($_SESSION['cart']);  
  header('Location: cart.php');  
  exit;
}

if (isset($_POST['checkout'])) {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['redirect_after_login'] = 'checkout.php';
        header('Location: login.php');
        exit;
    } else {
      header('Location: checkout.php?total=' . urlencode($totalPrice));
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./output.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Cart</title>
</head>
<body class="bg-white text-gray-800">
  <header class="flex justify-between items-center bg-white p-1 shadow-md sticky top-0 z-50">
    <div class="flex items-center">
      <a href="home.php">
        <img src="images/logoo.png" alt="Craft Treasure Logo" class="logo-size rounded-full border-4 border-white">
      </a> 
    </div>
    <nav>
      <ul class="flex">
        <li class="custom-spacing"><a href="index.php" class="text-black font-bold nav-link">Home</a></li>
        <li class="custom-spacing"><a href="listing.php" class="text-black font-bold nav-link">Products</a></li>
        <li class="custom-spacing"><a href="contact.php" class="text-black font-bold nav-link">Contacts</a></li>
        <li class="custom-spacing"><a href="user.php" class="text-black font-bold nav-link">User Account</a></li>
      </ul>
    </nav>
    <div class="flex items-center">
      <div class="search-bar flex items-center bg-pink-100 rounded-full shadow p-1">
        <input id="searchInput" type="text" placeholder="Search" class="p-2 bg-pink-100 text-gray-500 border-none focus:outline-none rounded-full custom-search-width" aria-label="Search">
        <button onclick="searchProducts()" class="bg-pink-300 text-white p-2 rounded-full ml-2 hover:bg-pink-400">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
          </svg>
        </button>
      </div>
      <div class="cart-icon relative ml-4">
        <a href="cart.php">
          <img src="images/cart.png" alt="Cart Icon" class="w-10">
          </a>
      </div>
      <div class="login-icon relative ml-4">
        <a href="login.php">
          <img src="images/user.png" alt="Login Icon" class="w-10">
        </a>
      </div>
    </div>
  </header>

    <div class="container mx-auto px-4 py-6">
        <h1 class="text-3xl font-bold mb-4">Your Cart</h1>

        <?php if (!empty($cartItems)) : 
            $totalPrice = 0; 
            $imgIDs = [];
        ?>
            <table class="table-auto w-full mb-4">
                <thead>
                    <tr>
                        <th class="text-left p-4 border">Product</th>
                        <th class="text-left p-4 border">Price</th>
                        <th class="text-left p-4 border">Quantity</th>
                        <th class="text-left p-4 border">Price</th>
                    </tr>
                </thead>
                <tbody>
                <?php $index = 0; ?>
                    <?php foreach ($cartItems as $item) : 
                        $itemTotal = $item['price'] * $item['quantity']; 
                        $totalPrice += $itemTotal;
                        $imgIDs[] = $item['imgID'];
                        ?>
                        <tr>
                            <td class="p-4 border">
                                <img src="<?php echo htmlspecialchars($item['imgUrl']); ?>" alt="Product Image" class="w-20 h-20 object-cover rounded-lg">
                                <span class="ml-4"><?php echo htmlspecialchars($item['imgName']); ?></span>
                            </td>
                            <td class="p-4 border">LKR<?php echo htmlspecialchars($item['price']); ?></td>
                            
                            <td class="p-4 border">
                            <input type="number" value="<?php echo htmlspecialchars($item['quantity']); ?>" 
                                   min="1" class="quantity-input w-16 p-2 border"
                                   data-price="<?php echo htmlspecialchars($item['price']); ?>" 
                                   data-index="<?php echo $index; ?>">
                            </td>
                            <td class="p-4 border">LKR<?php echo htmlspecialchars($item['price'] * $item['quantity']); ?></td>
                            <td class="p-4 border">
                                <form method="post" class="inline">
                                    <button name="remove" value="<?php echo $index; ?>" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Remove</button>
                                </form>
                            </td>
                          </tr>
                          <?php $index++; // Increment the index after each loop ?>
                          <?php endforeach; ?>
                </tbody>
            </table>
            <div class="flex justify-end">
              <p class="text-xl font-bold">Total: LKR<span id="total-price"><?php echo htmlspecialchars($totalPrice); ?></span></p>
          </div>

          <div class="flex justify-end">
          <a href="checkout.php?imgIDs=<?php echo implode(',', $imgIDs); ?>&total=<?php echo htmlspecialchars($totalPrice); ?>" class="bg-purple-500 text-white px-6 py-3 rounded-lg hover:bg-purple-600">Proceed to Checkout</a>
          </div>

        <?php else : ?>
            <p class="text-gray-600">Your cart is empty.</p>
        <?php endif; ?>
    </div>
    <script src="cart.js"></script>
</body>
</html>
