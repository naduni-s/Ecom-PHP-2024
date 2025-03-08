<?php
session_start();
require_once 'connection.php';
$userID = $_SESSION['userID'] ?? null;

if (!$userID) {
    header("Location: login.php");
    exit();
}
$phone = $address = "";
$editMode = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['save'])) {
        $newPhone = $_POST['phone'];
        $newAddress = $_POST['address'];

        $stmt = $mysqli->prepare("UPDATE customer SET phone = ?, address = ? WHERE userID = ?");
        if ($stmt === false) {
            die("Error preparing statement: " . $mysqli->error);
        }

        $stmt->bind_param("ssi", $newPhone, $newAddress, $userID);
        if (!$stmt->execute()) {
            die("Error executing statement: " . $stmt->error);
        }

        $stmt->close();
        header("Location: user.php"); 
        exit();
    } elseif (isset($_POST['edit'])) {
        $editMode = true;
    }
}

// Fetch user data
$stmt = $mysqli->prepare("SELECT userName, email, phone, address FROM customer WHERE userID = ?");
if ($stmt === false) {
    die("Error preparing statement: " . $mysqli->error);
}

$stmt->bind_param("i", $userID);
if (!$stmt->execute()) {
    die("Error executing statement: " . $stmt->error);
}
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

$orderStmt = $mysqli->prepare("
    SELECT o.orderID, p.imgUrl AS itemImg, p.imgName AS itemName, o.orderDate, o.status 
    FROM orders o 
    JOIN productstab p ON o.productID = p.imgID 
    WHERE o.userID = ?
");


if ($orderStmt === false) {
    die("Error preparing order statement: " . $mysqli->error);
}

$orderStmt->bind_param("i", $userID);
if (!$orderStmt->execute()) {
    die("Error executing order statement: " . $orderStmt->error);
}

$orderResult = $orderStmt->get_result();
$orders = $orderResult->fetch_all(MYSQLI_ASSOC);
$orderStmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order'])) {
  $orderID = $_POST['orderID'];
  $stmt = $mysqli->prepare("UPDATE orders SET status = 'Delivered' WHERE orderID = ?");
  $stmt->bind_param("i", $orderID);
  $stmt->execute();
  $stmt->close();
  header("Location: user.php");  // Redirect after updating
  exit();


}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Account Page</title>
  <link href="./output.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-400">
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
  <div class="max-w-4xl mx-auto mt-10 bg-pink-200 p-6 rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold mb-6">Personal Info</h1>
    
    <form id="userInfoForm" class="p-6" method="POST">
      <div class="mb-6">
        <label class="block text-gray-700 text-sm font-bold mb-2">Username</label>
        <input type="text" id="usernameField" value="<?php echo htmlspecialchars($user['userName']); ?>" disabled class="w-full p-2 border rounded-lg" />
      </div>

      <div class="mb-6">
        <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
        <input type="email" id="emailField" value="<?php echo htmlspecialchars($user['email']); ?>" disabled class="w-full p-2 border rounded-lg" />
      </div>

      <div class="mb-6">
        <label class="block text-gray-700 text-sm font-bold mb-2">Phone</label>
        <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" class="w-full p-2 border rounded-lg <?php echo $editMode ? '' : 'bg-gray-100'; ?>" <?php echo $editMode ? '' : 'disabled'; ?> />
      </div>
      
      <div class="mb-6">
        <label class="block text-gray-700 text-sm font-bold mb-2">Address</label>
        <input type="text" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" class="w-full p-2 border rounded-lg <?php echo $editMode ? '' : 'bg-gray-100'; ?>" <?php echo $editMode ? '' : 'disabled'; ?> />
      </div>

      <?php if ($editMode): ?>
        <button type="submit" name="save" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Save</button>
      <?php else: ?>
        <button type="submit" name="edit" class="bg-yellow-500 text-white px-4 py-2 rounded-lg">Edit</button>
      <?php endif; ?>
      
       </form>
  </div>

  <div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold mb-6">Order History</h2>

    <?php if (count($orders) > 0): ?>
        <?php foreach ($orders as $order): ?>
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center">
                    <img src="<?php echo htmlspecialchars($order['itemImg']); ?>" alt="Order Item" class="w-24 h-24 rounded-md">
                    <div class="ml-4">
                        <p class="font-bold"><?php echo htmlspecialchars($order['itemName']); ?></p>
                        <p><?php echo htmlspecialchars($order['orderDate']); ?></p>
                        <p><?php echo htmlspecialchars($order['status']); ?></p>
                    </div>
                </div>
                <?php if ($order['status'] !== 'Delivered'): ?>
                    <button onclick="confirmDelivery('<?php echo htmlspecialchars($order['orderID']); ?>')" class="bg-green-500 text-white px-4 py-2 rounded-lg">Confirm Delivery</button>
                <?php else: ?>
                  <a href="review.php?orderID=<?php echo htmlspecialchars($order['orderID']); ?>" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Add Review</a>

                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-gray-700">You have no orders yet.</p>
    <?php endif; ?>

    <form id="confirmForm" method="POST" action="">
    <input type="hidden" name="orderID" id="orderIDInput">
    <input type="hidden" name="confirm_order" value="1">
    </form>
    
  </div>
<script src = "user.js" ></script>
</body>
</html>
