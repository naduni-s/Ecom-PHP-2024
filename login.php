<?php
session_start();
require 'connection.php'; 

if (isset($_SESSION['userID'])) {
    echo "<script>
        if (confirm('You are already logged in. Do you want to log out?')) {
            window.location.href = 'logout.php';
        } else {
            window.location.href = 'index.php';
        }
    </script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $conn = $mysqli;
    $queryUser = "SELECT * FROM users WHERE email = ?";
    $stmtUser = $conn->prepare($queryUser);
    $stmtUser->bind_param("s", $email);
    $stmtUser->execute();
    $resultUser = $stmtUser->get_result();

    if ($resultUser->num_rows == 1) {
        $user = $resultUser->fetch_assoc();
        if ($user['password'] == $password) {
            $_SESSION['userID'] = $user['user_id'];
            $_SESSION['userName'] = $user['user_name'];
            $_SESSION['userEmail'] = $user['email'];
            
            if ($user['is_admin'] == 1) {
                header("Location: admin.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            echo "<script>alert('Invalid password. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('You don\'t have an account. Please create an account from Sign-Up.');</script>";
    }

    $stmtUser->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="flex justify-center items-center min-h-screen">
    <div class="bg-white p-6 rounded-lg shadow-lg flex flex-col lg:flex-row">
        <div class="lg:w-1/2 px-6 py-8">
            <h2 class="text-3xl font-semibold mb-4">Welcome Back <span class="wave">👋</span></h2>
            <form action="login.php" method="POST">
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email</label>
                    <input type="email" id="email" name="email" class="mt-2 w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500" placeholder="Example@email.com" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700">Password</label>
                    <input type="password" id="password" name="password" class="mt-2 w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500" placeholder="at least 8 characters" required>
                </div>
                
                <button type="submit" class="w-full bg-blue-950 text-white p-3 rounded-lg hover:bg-indigo-700 transition">Sign in</button>
            </form>
            <div class="flex items-center my-4">
                <div class="flex-grow border-t border-gray-300"></div>
                <span class="mx-2 text-gray-400">Or</span>
                <div class="flex-grow border-t border-gray-300"></div>
            </div>
            
            <p class="mt-4 text-center text-sm text-gray-600">Don't you have an account? <a href="create.php" class="text-blue-500 hover:underline">Sign up</a></p>
        </div>
        <div class="lg:w-3/5">
            <img src="images/loginn.jpg" alt="Login Image" class="rounded-lg">
        </div>
    </div>
</div>
</body>
</html>
