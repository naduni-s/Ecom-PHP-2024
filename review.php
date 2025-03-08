<?php
session_start();
$mysqli = require_once 'connection.php';

$userID = $_SESSION['userID'] ?? null;
$orderID = $_GET['orderID'] ?? null;

if (!$userID) {
    header("Location: login.php");
    exit();
}

// Validate the userID by checking if it exists in the users table
$checkUser = "SELECT userID FROM customer WHERE userID = ?";
if ($stmt = $mysqli->prepare($checkUser)) {
    $stmt->bind_param('i', $userID);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        echo "Error: Invalid userID. Please log in again.";
        exit();
    }
    $stmt->close();
} else {
    echo "Error: Could not prepare statement to check userID.";
    exit();
}

$checkOrder = "SELECT orderID FROM orders WHERE userID = ?";
if ($stmt = $mysqli->prepare($checkOrder)) {
    $stmt->bind_param('i', $userID);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        echo "Error: Invalid orderID. Please log in again.";
        exit();
    }
    $stmt->close();
} else {
    echo "Error: Could not prepare statement to check userID.";
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $review = $_POST['review'] ?? '';
    $rating = $_POST['rating'] ?? 0;

    $sql = "INSERT INTO reviews (userID, orderID, review, rate) VALUES (?, ?, ?, ?)";

    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param('iisi', $userID, $orderID, $review, $rating);

        if ($stmt->execute()) {
            echo "Review submitted successfully!";
        } else {
            echo "Error: Could not submit review.";
        }
        $stmt->close();
    } else {
        echo "Error: Could not prepare the statement.";
    }
}
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Review</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-lg mx-auto mt-10 bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-6 text-center">Submit a Review</h2>

        <form action="" method="POST" class="space-y-6">
            <input type="hidden" name="orderID" value="<?php echo htmlspecialchars($orderID); ?>">

            <div>
                <label for="review" class="block text-gray-700 font-semibold mb-2">Review:</label>
                <textarea name="review" id="review" rows="4" class="w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-indigo-200 focus:border-indigo-500" required></textarea>
            </div>
            <div>
                <label for="rating" class="block text-gray-700 font-semibold mb-2">Rating:</label>
                <select name="rating" id="rate" class="w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-indigo-200 focus:border-indigo-500" required>
                    <option value="1">1 - Poor</option>
                    <option value="2">2 - Fair</option>
                    <option value="3">3 - Good</option>
                    <option value="4">4 - Very Good</option>
                    <option value="5">5 - Excellent</option>
                </select>
            </div>

            <div class="text-center">
                <button type="submit" name="submit_review" class="w-full bg-indigo-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring focus:ring-indigo-300">
                    Submit Review
                </button>
            </div>
        </form>
    </div>
</body>
</html>
