<?php 
session_start();

require_once 'connection.php';
$conn = $mysqli;
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the imgID from the URL query parameter
if (isset($_GET['imgID']) && is_numeric($_GET['imgID'])) {
    $imgID = $_GET['imgID']; 
} else {
    echo "Invalid product ID.";
    exit;
}

$sql = "SELECT imgName, imgUrl, Description, price, image1, image2, image3 FROM productstab WHERE imgID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $imgID);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "Product not found. imgID: " . htmlspecialchars($imgID);
    exit;
}

// Extract product details
$imgName = $product['imgName'];
$imgUrl = $product['imgUrl'];
$price = $product['price'];
$Description = $product['Description'];
$images = [$product['image1'], $product['image2'], $product['image3']];

$reviewQuery = "
    SELECT r.review, r.rate, u.user_name 
    FROM reviews r
    JOIN orders o ON r.orderID = o.orderID
    JOIN users u ON r.userID = u.user_id
    WHERE o.productID = ?
";
$reviewStmt = $conn->prepare($reviewQuery);
$reviewStmt->bind_param("i", $imgID);
$reviewStmt->execute();
$reviewsResult = $reviewStmt->get_result();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $cartItem = [
      'imgID' => $imgID,
      'imgName' => $imgName,
      'price' => $price,
      'quantity' => $_POST['quantity'],
      'imgUrl' => $imgUrl
  ];
  
  if (isset($_SESSION['cart'][$imgID])) {
      $_SESSION['cart'][$imgID]['quantity'] += $_POST['quantity'];
  } else {
      $_SESSION['cart'][$imgID] = $cartItem;
  }

  echo '<pre>';
  print_r($_SESSION['cart']);
  echo '</pre>';
  
  header('Location: cart.php');
  exit;
}
if (isset($_POST['buy_now'])) {
  $_SESSION['imgIDs'] = [$_POST['imgID']]; 
  $_SESSION['directPrice'] = $_POST['price']; 

  header("Location: checkout.php?total=" . $_POST['price']);
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./output.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title><?php echo htmlspecialchars($imgName); ?></title>
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
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
      <div class="pr-4"> 
        <img id="main-image" src="<?php echo htmlspecialchars($imgUrl); ?>" alt="Product Image" class="main-image rounded-lg shadow-md">
        <div class="flex space-x-4 mt-4">
          <?php foreach ($images as $image): ?>
            <img src="<?php echo htmlspecialchars($image); ?>" alt="Additional Image" class="small-image w-20 h-20 object-cover rounded-lg border-2 border-gray-200 hover:border-pink-500">
          <?php endforeach; ?>
        </div>
      </div>
      <div>
        <h1 class="text-3xl font-bold text-gray-800"><?php echo htmlspecialchars($imgName); ?></h1>
        <div class="flex items-center my-4">
          <div class="flex text-yellow-500">
            <span>★★★★★</span>
          </div>
        </div>
        <p class="text-3xl font-bold text-purple-900">LKR<?php echo htmlspecialchars($price); ?></p>
        <p class="text-l text-gray-800 font-bold">Free shipping</p>
        <div class="mt-6">
          <div class="flex items-center space-x-2 mt-2 quantity-box">
            <span class="text-gray-600 mr-2">Qnty</span>
            <button id="decrease-btn" class="quantity-button bg-gray-200 text-gray-800">-</button>
            <input id="quantity" class="quantity-input border border-gray-400 rounded-lg px-2 py-1 text-center" type="text" value="1" readonly>
            <button id="increase-btn" class="quantity-button bg-gray-200 text-gray-800">+</button>
          </div>
          <div class="flex items-center space-x-4 mt-6">

          <form action="" method="POST">
            <input type="hidden" name="imgID" value="<?php echo $imgID; ?>">
            <input type="hidden" name="quantity" id="quantity-input" value="1">
            <button id="add-to-cart-btn" type="submit" class="bg-purple-200 text-purple-700 font-semibold px-28 py-2 rounded-lg hover:bg-purple-100" data-item-id="<?php echo htmlspecialchars($imgID); ?>">Add To Cart</button>
            </form>

            <button class="border-2 border-purple-300 bg-purple-100 text-purple-500 p-2 rounded-full shadow-md hover:bg-purple-200">
              <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
              </svg>
            </button>
          </div>
          <div class="mt-4">
            <a href="checkout.php?imgID=<?php echo urlencode($imgID); ?>&price=<?php echo urlencode($price); ?>">
              <button type = "submit" name="buy_now" class="bg-indigo-900 text-white font-semibold px-40 py-2 rounded-lg hover:bg-indigo-700">
                Buy Now
              </button>
            </a>
          </div>
        </div>
      </div>
      <div class="mt-12">
        <h3 class="text-xl font-bold text-gray-800">Description</h3>
        <ul class="list-disc list-inside text-black mt-2">
          <?php
            $desc_items = explode("\n", $Description);
            foreach ($desc_items as $item) {
                echo "<li>" . htmlspecialchars(trim($item)) . "</li>";
            }
          ?>
        </ul><br>
        <h2 class="text-2xl font-bold text-black flex items-center">
          <span class="mr-10">Reviews</span>
          <span class="flex items-center text-black">
          <span class="mr-2">5.0</span>
          <span>★★★★★</span>
          </span>
        </h2>
        <div class="mt-4">
      <?php while ($review = $reviewsResult->fetch_assoc()): ?>
        <div class="bg-pink-200 p-4 rounded-lg shadow-md mb-4">
          <p class="font-bold"><?php echo htmlspecialchars($review['user_name']); ?></p>
          <p class="text-yellow-500"><?php echo str_repeat('★', $review['rate']); ?><?php echo str_repeat('☆', 5 - $review['rate']); ?></p>
          <p class="text-gray-600"><?php echo htmlspecialchars($review['review']); ?></p>
        </div>
      <?php endwhile; ?>
      </div>
      
      </div>
    </div>
  </div>
  <footer class="bg-black text-white py-10">
  <div class="container mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
    <div class="flex flex-col items-start md:items-start md:ml-12">
      <div class="flex items-center">
        <img src="images/logoo.png" alt="Craft Treasure Logo" class="w-14 h-14 mr-4"> 
          <div>
          <h2 class="text-lg font-bold mb-2">Craft Treasure</h2>
          <div class="flex justify-center md:justify-start gap-2 mt-4"> 
            <a href="https://facebook.com/yourpage" target="_blank" class="hover:opacity-75">
            <img src="images/fb.png" alt="Facebook" class="w-8 h-8">
            </a>
            <a href="https://instagram.com/yourpage" target="_blank" class="hover:opacity-100">
            <img src="images/inss.png" alt="Instagram" class="w-8 h-8">
            </a>
            <a href="https://twitter.com/yourpage" target="_blank" class="hover:opacity-75">
            <img src="images/twitter.png" alt="Twitter" class="w-8 h-8">
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="flex flex-col items-center md:items-start">
      <h3 class="text-lg font-semibold mb-4">Get in Touch</h3>
      <p class="text-gray-400 mb-2">
        <i class="fas fa-phone-alt mr-2"></i> +1 (234) 567-890
      </p>
      <p class="text-gray-400">
        <i class="fas fa-envelope mr-2"></i> info@crafttreasure.com
      </p>
    </div>
    <div class="flex flex-col items-center md:items-start">
      <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
      <ul class="text-center md:text-left space-y-2">
        <li><a href="privacy.php" class="text-gray-400 hover:text-white text-sm">Privacy Policy</a></li>
        <li><a href="terms.php" class="text-gray-400 hover:text-white text-sm">Terms of Service</a></li>
      </ul>
    </div>
  </div>

  <div class="pt-4 text-center text-gray-400 text-sm">
    <p>&copy; 2024 Craft Treasure. All rights reserved.</p>
  </div>
</footer>
<script src="function.js"></script>
</body>
</html>
