<?php
session_start(); 
$mysqli = require_once 'connection.php';

if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['userID'];

$imgID = isset($_GET['imgID']) ? htmlspecialchars($_GET['imgID']) : null; 
$price = isset($_GET['price']) ? htmlspecialchars($_GET['price']) : null;
$quantity = isset($_GET['quantity']) ? intval($_GET['quantity']) : 1;

$cartTotal = isset($_SESSION['cartTotal']) ? $_SESSION['cartTotal'] : 0;
$directItemTotal = isset($_GET['total']) ? htmlspecialchars($_GET['total']) : $price;

$totalPrice = $cartTotal > 0 ? $cartTotal : $directItemTotal;
$cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : []; 
$directItems = isset($_SESSION['imgIDs']) ? $_SESSION['imgIDs'] : []; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_date = date('Y-m-d H:i:s');
    $status = 'Order Confirmed';

    // Validate direct items before inserting
    if ($imgID) {
        $validate_sql = "SELECT imgID FROM productstab WHERE imgID = '$imgID'";
        $result = mysqli_query($mysqli, $validate_sql);

        if (!$result) {
            echo "Validation Query Error: " . mysqli_error($mysqli) . "<br>";
        } else {
            if (mysqli_num_rows($result) > 0) {
                $sql = "INSERT INTO orders (productID, userID, orderDate, Status) 
                        VALUES ('$imgID', '$user_id', '$order_date', '$status')";

                if (!mysqli_query($mysqli, $sql)) {
                    echo "Insert Error for direct item: " . mysqli_error($mysqli) . "<br>";
                } else {
                    echo "Order for productID '$imgID' inserted successfully.<br>";
                }
            } else {
                echo "Error: Product ID '$imgID' not found in productstab.<br>";
            }
        }
    }

if (empty($cartItems)) {
    echo "";
} else {
    foreach ($cartItems as $cartItem) {
        if (!isset($cartItem['imgID'])) {
            echo "Error: imgID not set in cart item.<br>";
            continue;
        }

        $imgID = $cartItem['imgID'];
        $validate_sql = "SELECT imgID FROM productstab WHERE imgID = ?";
        $stmt = $mysqli->prepare($validate_sql);
        if (!$stmt) {
            echo "Preparation Error: " . $mysqli->error . "<br>";
            continue;
        }

        $stmt->bind_param("s", $imgID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            if ($result->num_rows > 0) {
                $sql = "INSERT INTO orders (productID, userID, orderDate, Status) VALUES (?, ?, ?, ?)";
                $stmtInsert = $mysqli->prepare($sql);
                if (!$stmtInsert) {
                    echo "Preparation Error for Insert: " . $mysqli->error . "<br>";
                    continue;
                }

                $stmtInsert->bind_param("ssss", $imgID, $user_id, $order_date, $status);
                if ($stmtInsert->execute()) {
                    echo "Order for productID '$imgID' inserted successfully.<br>";
                } else {
                    echo "Insert Error for cart item: " . $stmtInsert->error . "<br>";
                }
            } else {
                echo "Error: Product ID '$imgID' not found in productstab.<br>";
            }
        } else {
            echo "Validation Query Error: " . $stmt->error . "<br>";
        }

        $stmt->close(); 
    }
}

    unset($_SESSION['cart']);
    unset($_SESSION['cartTotal']);
    unset($_SESSION['imgIDs']);

    echo "<script>
        alert('Payment successful. Order confirmed.');
        window.location.href = 'index.php'; 
    </script>";

    mysqli_close($mysqli); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./output.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Checkout</title>
</head>
<body>
<header class="flex justify-between items-center bg-white p-4 shadow-md sticky top-0 z-50">
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

    <div class="flex flex-col lg:flex-row justify-between max-w-7xl mx-auto py-10 px-5 lg:px-0">
        <div class="w-full lg:w-1/2 lg:pr-10">
            <form class="space-y-4" method="POST">
                <div class="relative">
                    <h2 class="text-2xl font-bold mb-6">Let's Make Payment</h2>
                    <div class="flex items-center mb-4">
                        <img src="images/visa.png" alt="Visa" class="w-10 mr-2">
                        <img src="images/mast.png" alt="MasterCard" class="w-10 mr-2">
                        <img src="images/ame.png" alt="American Express" class="w-10">
                    </div>
                </div>
                <input type="hidden" name="imgID" value="<?php echo htmlspecialchars($imgID); ?>">
                <input type="text" placeholder="Name on Card" class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                <input type="text" placeholder="9870 3456 7890 6473" class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                <div class="flex space-x-4">
                    <input type="text" placeholder="03 / 25" class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <input type="text" placeholder="654" class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <button type="submit" class="w-full px-4 py-3 bg-purple-500 text-white font-semibold rounded-md hover:bg-purple-600">Pay</button>
            </form>
        </div>
    </div>
</body>
</html>
