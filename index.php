<?php
      require_once 'connection.php';
      $sql = "SELECT * FROM productstab LIMIT 3";
      $conn = $mysqli;
      $all_product = $conn->query($sql);
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="./output.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

  <title>Craft Treasure</title>
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
    
    <section class="relative bg-cover bg-center h-screen" style="background-image: url('images/mainimg.jpg');height:65vh;">
      <div class="absolute inset-0 bg-black opacity-50"></div>
      <div class="absolute bottom-2/3 right-0 p-4 text-right">
        <h1 class="text-4xl md:text-6xl font-bold text-white" >Artful Treasures</h1>
      </div>
    </section>
    <section class="py-12">
  <div class="flex">
    <div class="flex-1 flex flex-col items-start" style="padding-left:70px;">
      <h2 class="text-3xl font-bold mb-2">Best Selling<br>Products</h2><br>
      <a href="products.php" class="custom-see-more-button mb-4">See more →</a> 
    </div>
    
    <div class="flex flex-wrap gap-4">
    <?php while($row = mysqli_fetch_assoc($all_product)) { ?>
      <div class="bg-white rounded-lg shadow-md p-4 hover-effect">
      <a href="product.php?imgID=<?php echo $row['imgID']; ?>"> 
              <img src="<?php echo $row['imgUrl']; ?>" alt="<?php echo $row['imgName']; ?>" class="product-img-cato rounded-lg mb-4">
              <h3 class="text-lg font-semibold"><?php echo $row['imgName']; ?></h3>
              <p class="text-gray-600 text-2xl"><?php echo $row['price']; ?></p>
            </a>
      </div>
      <?php } ?>
    </div>
  </div>
</section>
    
    <section class="text-center py-12 bg-white">
      <h2 class="text-3xl font-bold mb-8">About Us</h2>
      <p class="max-w-3xl mx-auto text-gray-700 leading-relaxed text-lg">
        Welcome to Craft Treasure!<br> Explore our curated selection and experience the artistry behind every item. Our collection ranges from elegant jewelry to charming home decor, each piece meticulously crafted to offer something unique. Our commitment to quality and customer satisfaction drives us to deliver exceptional products and service every day.
      </p>
      <div class="flex justify-center mt-8 space-x-4">
        <div class="flex flex-col items-center">
          <div class="circle">
            <img src="images/ship.png" alt="Fast Shipping">
          </div>
          <p class="text-sm font-bold mt-2">Fast Shipping</p>
        </div>
        <div class="flex flex-col items-center">
          <div class="circle">
            <img src="images/support.png" alt="24/7 Support">
          </div>
          <p class="text-sm font-bold mt-2">24/7 Support</p>
        </div>
      </div>
    </section>
    <?php
  require_once 'connection.php';
  $sql = "SELECT * FROM category";
  $all_product = $conn->query($sql);
  ?>
    <div class="text-center py-8">
      <h2 class="text-3xl font-bold">Categories</h2><br>
      <p class="text-gray-600">Find what you are looking for</p>
    </div>
    <section class="text-center bg-cato w-full py-12 h-[400px]">
      <div class="flex justify-center mt-8 space-x-4">
      <?php while($row1 = mysqli_fetch_assoc($all_product)) { ?>
        
        <div class="border p-4 rounded-lg hover-effect">
          <img src="<?php echo $row1["imgUrl"];?>" class="product-img-cato rounded-lg mb-4">
          <h3 class="text-lg font-semibold"><?php echo $row1["imgName"];?></h3>
        </div>
        
        <?php } ?>
      </div>
    </section>
    
    <section class="py-12 bg-white">
    <h2 class="text-3xl font-bold mb-8 text-left ml-4 mt-12">What customers say about<br>Craft Treasure?</h2>
    <div class="container mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    <div class="border p-4 rounded-lg bg-yellow-pink-gradient">
      <p class="italic">“I am absolutely thrilled with this purchase! I'm extremely satisfied and will be recommending this to my friends!”</p>
      <p class="text-right font-bold mt-4 text-pink-500">John D.</p>
      <p class="text-right font-bold text-black">4.5 ★</p>
    </div>
    <div class="border p-4 rounded-lg bg-yellow-pink-gradient">
      <p class="italic">“A wonderful product that lives up to the description. I appreciate the care taken in the packaging.”</p>
      <p class="text-right font-bold  mt-4 text-pink-500">Kumar S.</p>
      <p class="text-right font-bold text-black">5.0 ★</p>
    </div>
    <div class="border p-4 rounded-lg bg-yellow-pink-gradient">
      <p class="italic">“Fantastic experience from start to finish. The customer service was excellent and the shipping was prompt.”</p>
      <p class="text-right font-bold mt-4 text-pink-500">Jane P.</p>
      <p class="text-right font-bold text-black">4.5 ★</p>
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
    <script src="cart.js"></script>
    
</body>
</html>


