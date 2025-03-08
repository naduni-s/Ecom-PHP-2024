<?php
require_once 'connection.php';

session_start();

if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
} else {
    $errorMessage = "You must be logged in to send a message.";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if POST variables are set
    if (isset($_POST['fullName'], $_POST['email'], $_POST['message'])) {
        // Sanitize input data
        $fullName = $mysqli->real_escape_string($_POST['fullName']);
        $email = $mysqli->real_escape_string($_POST['email']);
        $message = $mysqli->real_escape_string($_POST['message']);

        // Validate form fields
        if (empty($fullName) || empty($email) || empty($message)) {
            $errorMessage = "All fields are required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMessage = "Please enter a valid email address.";
        } else {
            $sql = "INSERT INTO message (userID, fullName, email, message) VALUES ('$userID', '$fullName', '$email', '$message')";

            if ($mysqli->query($sql) === TRUE) {
                $successMessage = "We got your message. We will respond soon!";
            } else {
                $errorMessage = "Error: " . $mysqli->error;
            }
        }
    } else {
        $errorMessage = "All fields are required.";
    }
}
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="./output.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <title>Contacts</title>
</head>
<body>
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

  <main>
  <section class="max-w-4xl mx-auto mt-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6">
            <div>
                <h1 class="text-4xl font-semibold mb-6">Contact Us</h1>

                <?php if (isset($successMessage)): ?>
                    <p class="text-green-800 mb-4"><?= $successMessage ?></p>
                <?php elseif (isset($errorMessage)): ?>
                    <p class="text-red-800 mb-4"><?= $errorMessage ?></p>
                <?php endif; ?>

                <form id="contactform" method="POST" action="contact.php" class="space-y-6">
                    <input type="text" id="fullName" name="fullName" placeholder="Full Name" class="w-full border-b border-gray-400 py-2 focus:outline-none focus:border-gray-600" required>
                    <textarea id="message" name="message" placeholder="Message" class="w-full border-b border-gray-400 py-2 focus:outline-none focus:border-gray-600" required></textarea>
                    <input type="email" id="email" name="email" placeholder="E-mail" class="w-full border-b border-gray-400 py-2 focus:outline-none focus:border-gray-600" required>
                    <button type="submit" class="w-full bg-blue-900 text-white py-3 rounded-md hover:bg-blue-600">Contact Us</button>
                </form>
                <p id="confirmationMessage" class="text-green-800 mt-4 hidden">We got your message. We will respond soon!</p>
            </div>
        </div>
    </section>
  </main>
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
        <li><a href="privacy.html" class="text-gray-400 hover:text-white text-sm">Privacy Policy</a></li>
        <li><a href="terms.html" class="text-gray-400 hover:text-white text-sm">Terms of Service</a></li>
      </ul>
    </div>
  </div>
  <div class="pt-4 text-center text-gray-400 text-sm">
    <p>&copy; 2024 Craft Treasure. All rights reserved.</p>
  </div>
</footer>
<script src = "contact.js" ></script>
</body>
</html>
