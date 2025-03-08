<?php
$mysqli = require_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userName = $_POST['userName'];
    $Email = $_POST['Email'];
    $Password = $_POST['Password'];
    $confirmPassword = $_POST['confirm-password'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    if ($Password !== $confirmPassword) {
        $message = "Passwords do not match.";
    } else {
        // Start transaction to ensure data consistency across both tables
        $mysqli->begin_transaction();

        try {
            $stmtUsers = $mysqli->prepare('INSERT INTO users (user_name, email, password, is_admin) VALUES (?, ?, ?, ?)');
            if ($stmtUsers === false) {
                throw new Exception($mysqli->error);
            }
            $is_admin = 0; 
            $stmtUsers->bind_param('sssi', $userName, $Email, $Password, $is_admin);

            if (!$stmtUsers->execute()) {
                throw new Exception($stmtUsers->error);
            }

            // Get the last inserted userID from `users` table
            $userID = $mysqli->insert_id;
            $stmtCustomer = $mysqli->prepare('INSERT INTO customer (userID, userName, phone, address, email, password) VALUES (?, ?, ?, ?, ?, ?)');
            if ($stmtCustomer === false) {
                throw new Exception($mysqli->error);
            }

            $stmtCustomer->bind_param('isssss', $userID, $userName, $phone, $address, $Email, $Password);

            if (!$stmtCustomer->execute()) {
                throw new Exception($stmtCustomer->error);
            }
            $mysqli->commit();
            header("Location: login.php");
            exit();

        } catch (Exception $e) {
            // Rollback transaction in case of error
            $mysqli->rollback();
            $message = "Error inserting: " . $e->getMessage();
        } finally {
            // Close the prepared statements if they exist
            if (isset($stmtUsers) && $stmtUsers !== false) {
                $stmtUsers->close();
            }
            if (isset($stmtCustomer) && $stmtCustomer !== false) {
                $stmtCustomer->close();
            }
        }
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
    <title>Create Account</title>
</head>
<body class="bg-indigo-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-purple-200 shadow-lg rounded-lg flex max-w-4xl w-full overflow-hidden">
            
            <div class="w-1/2 hidden md:block">
                <img src="images/create.jpg" alt="Craft Items" class="h-full w-full object-cover">
            </div>
            <div class="w-full md:w-1/2 p-2 flex flex-col justify-center">
                <h2 class="text-2xl font-semibold mb-1 text-gray-800 text-center">Create Account</h2>
                 
                <?php
                if (isset($message)) {
                    echo "<p>$message</p>";
                }
                ?>
                <form id="createAccountForm" method="post">
                    <div class="mb-3">
                        <label class="block text-sm mb-1 text-gray-600" for="userName">User Name</label>
                        <div class="relative">
                            <input type="text" id="userName" name="userName" placeholder="User Name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-400"> 
                        </div>
                        <p id="usernameError" class="text-red-500 text-sm hidden mt-1">Can only contain letters and numbers.</p>
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm mb-1 text-gray-600" for="Email">Your Email</label>
                        <div class="relative">
                            <input type="email" id="Email" name="Email" placeholder="Email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-400">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm mb-1 text-gray-600" for="phone">Phone Number</label>
                        <div class="relative">
                            <input type="text" id="phone" name="phone" placeholder="Phone Number" class="w-full px-2 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-green-400">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm mb-1 text-gray-600" for="address">Address</label>
                        <div class="relative">
                            <textarea id="address" name="address" placeholder="Address" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-400"></textarea>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm mb-1 text-gray-600" for="Password">Password</label>
                        <div class="relative">
                            <input type="password" id="Password" name="Password" placeholder="Password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-400">
                        </div>
                        <p id="passwordError" class="text-red-500 text-sm hidden mt-1">Password should be a combination of uppercase letters, lowercase letters, numbers, and symbols, and at least 8 characters long.</p>
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm mb-1 text-gray-600" for="confirm-password">Confirm Password</label>
                        <div class="relative">
                            <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm Password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-400">   
                        </div>
                        <p id="confirmPasswordError" class="text-red-500 text-sm hidden mt-1">Passwords do not match.</p>
                    </div>

                    <button type="submit" class="w-full py-2 text-black bg-purple-400 hover:bg-purple-500 rounded-lg font-semibold text-lg">
                        SUBMIT
                    </button>
                </form>

                <p class="mt-2 text-center text-gray-600">
                    Already Have An Account? 
                    <a href="login.php" class="text-green-500 font-semibold">Log In</a>
                </p>
            </div>
        </div>
    </div>
    <script src="create.js"></script>
</body>
</html>
