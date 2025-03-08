<?php
$mysqli = require_once 'connection.php';
$conn = $mysqli;
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add-item'])) { 
        $item_name = isset($_POST['item-name']) ? $_POST['item-name'] : '';
        $item_price = isset($_POST['item-price']) ? $_POST['item-price'] : 0;
        $item_description = isset($_POST['item-description']) ? $_POST['item-description'] : '';

        function uploadImage($imageKey, $target_dir = "uploads/") {
            if (isset($_FILES[$imageKey])) {
                $fileError = $_FILES[$imageKey]['error'];
                if ($fileError == UPLOAD_ERR_OK) {
                    $target_file = $target_dir . basename($_FILES[$imageKey]["name"]);
                    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                    // Validate file type
                    if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
                        return "Invalid file type: " . $imageFileType;
                    }

                    // Move uploaded file to target directory
                    if (move_uploaded_file($_FILES[$imageKey]["tmp_name"], $target_file)) {
                        return $target_file;
                    } else {
                        return "Error moving file.";
                    }
                } else {
                    return "File upload error: " . $fileError;
                }
            }
            return "No file uploaded.";
        }

        // Upload images
        $main_image = uploadImage('item-image');
        $image1 = uploadImage('image1');
        $image2 = uploadImage('image2');
        $image3 = uploadImage('image3');

        if (strpos($main_image, 'Error') === false && strpos($image1, 'Error') === false && strpos($image2, 'Error') === false && strpos($image3, 'Error') === false) {
            // s for string , double for string
            $stmt = $conn->prepare("INSERT INTO productstab (imgName, price, Description, imgUrl, image1, image2, image3) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sdsssss", $item_name, $item_price, $item_description, $main_image, $image1, $image2, $image3);

            if ($stmt->execute()) {
                echo "New item added successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error uploading images: $main_image, $image1, $image2, $image3";
        }
    }

    if (isset($_POST['remove-item'])) { 
        $item_id = $_POST['remove-item-id'];

        $stmt = $conn->prepare("DELETE FROM productstab WHERE imgID = ?");
        $stmt->bind_param("i", $item_id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo "Item removed successfully.";
            } else {
                echo "No item found with the given ID.";
            }
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['imgID']) && isset($_POST['imgName']) && isset($_POST['price']) && isset($_POST['description'])) {
        $imgID = $_POST['imgID'];
        $imgName = $_POST['imgName'];
        $price = $_POST['price'];
        $description = $_POST['description'];
    }

    $sql = "UPDATE productstab SET imgName = ?, price = ?, description = ? WHERE imgID = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sdsi", $imgName, $price, $description, $imgID);

        if ($stmt->execute()) {
        } else {
            echo "Error updating product: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

}
$sql = "SELECT imgID, imgName, price, Description FROM productstab";
$conn = $mysqli;
$result = $conn->query($sql);
$sql_orders = "SELECT orderID, productID, userID, orderDate, status FROM orders";
$result_orders = $conn->query($sql_orders);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $imgID = isset($_POST['imgID']) ? $_POST['imgID'] : null;
    $imgName = isset($_POST['imgName']) ? $_POST['imgName'] : null;
    $price = isset($_POST['price']) ? $_POST['price'] : null;
    $description = isset($_POST['description']) ? $_POST['description'] : null;

    if ($imgID && $imgName && $price && $description) {
        $sql = "UPDATE productstab SET imgName = ?, price = ?, description = ? WHERE imgID = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sdsi", $imgName, $price, $description, $imgID);

            if ($stmt->execute()) {
                echo "Product updated successfully!";
            } else {
                echo "Error updating product: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    } else {
        echo "";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update-order-status'])) {
    $orderID = isset($_POST['order-id']) ? $_POST['order-id'] : null;
    $newStatus = isset($_POST['order-status']) ? $_POST['order-status'] : null;

    if ($orderID && $newStatus) {
        // First, check if the order ID exists
        $checkSql = "SELECT COUNT(*) FROM orders WHERE orderID = ?";
        if ($checkStmt = $conn->prepare($checkSql)) {
            $checkStmt->bind_param("i", $orderID);
            $checkStmt->execute();
            $checkStmt->bind_result($orderCount);
            $checkStmt->fetch();
            $checkStmt->close();

            if ($orderCount > 0) {
                // Proceed with the update if the order exists
                $sql = "UPDATE orders SET status = ? WHERE orderID = ?";
                
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("si", $newStatus, $orderID);
                    
                    if ($stmt->execute()) {
                        echo "Order status updated successfully!";
                    } else {
                        echo "Error updating order status: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    echo "Error preparing statement: " . $conn->error;
                }
            } else {
                echo "Error: Order ID does not exist.";
            }
        } else {
            echo "Error preparing check statement: " . $conn->error;
        }
    } else {
        echo "Please provide a valid order ID and status.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="./output.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex">
        <div class="w-1/5 bg-gray-800 text-white p-6 min-h-screen">
            <h2 class="text-2xl font-bold mb-7">Admin Dashboard</h2>
            <ul class="space-y-5">
                <li><a href="#" data-target="add-item" class="sidebar-link">Add Item</a></li>
                <li><a href="#" data-target="remove-item" class="sidebar-link">Remove Item</a></li>
                <li><a href="#" data-target="update-item" class="sidebar-link">Update Item</a></li>
                <li><a href="#" data-target="view-orders" class="sidebar-link">View Orders</a></li>
                <li><a href="#" data-target="manage-orders" class="sidebar-link">Manage Orders</a></li>
                <li><a href="#" data-target="logout" class="sidebar-link">Logout</a></li>
            </ul>
        </div>
        <div class="flex-1 p-6">
            <header class="mb-6 flex items-center space-x-4">
                <img src="images/security.png" alt="Profile Picture" class="w-12 h-12 rounded-full">
                <span class="text-xl font-semibold">Admin</span>
            </header>
            <section id="add-item" class="hidden mb-6">
            
                <h2 class="text-2xl font-semibold mb-4">Add Item</h2>
                <form id="add-item-form" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md space-y-4">
                <div class="mb-4">
                <label for="item-name" class="block text-gray-700">Item Name:</label>
                <input type="text" id="item-name" name="item-name" class="w-full p-2 border border-gray-300 rounded" required>
            </div>
            <div class="mb-4">
                <label for="item-price" class="block text-gray-700">Item Price (LKR):</label>
                <input type="number" id="item-price" name="item-price" class="w-full p-2 border border-gray-300 rounded" step="0.01" required>
            </div>
            <div class="mb-4">
                <label for="item-description" class="block text-gray-700">Item Description:</label>
                <textarea id="item-description" name="item-description" class="w-full p-2 border border-gray-300 rounded" rows="4" required></textarea>
            </div>
            <div class="mb-4">
                <label for="item-image" class="block text-gray-700">Main Image:</label>
                <input type="file" id="item-image" name="item-image" class="w-full border border-gray-300 rounded">
            </div>
            <div class="mb-4">
                <label for="image1" class="block text-gray-700">Additional Image 1:</label>
                <input type="file" id="image1" name="image1" class="w-full border border-gray-300 rounded">
            </div>
            <div class="mb-4">
                <label for="image2" class="block text-gray-700">Additional Image 2:</label>
                <input type="file" id="image2" name="image2" class="w-full border border-gray-300 rounded">
            </div>
            <div class="mb-4">
                <label for="image3" class="block text-gray-700">Additional Image 3:</label>
                <input type="file" id="image3" name="image3" class="w-full border border-gray-300 rounded">
            </div>
                    <button type="submit" name="add-item" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Add Item</button>
                </form>
            </section>

            <!-- Remove Item Section -->
            <section id="remove-item" class="hidden mb-6">
                <h2 class="text-2xl font-semibold mb-4">Remove Item</h2>
                <form id="remove-item-form" method="POST" class="bg-white p-6 rounded-lg shadow-md space-y-4">
                    <div class="flex flex-col">
                        <label for="remove-item-id" class="text-sm font-medium mb-1">Item ID:</label>
                        <input type="text" id="remove-item-id" name="remove-item-id" required class="border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    </div>
                    <button type="submit" name="remove-item" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">Remove Item</button>
                </form>
            </section>


            <!-- Update Item Section -->
            <section id="update-item" class="hidden mb-6">
            <table class="table-auto w-full bg-white rounded-lg shadow-lg">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2">Item ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Price</th>
                    <th class="px-4 py-2">Description</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<form id='edit-form-" . $row['imgID'] . "' method='POST'>";
                    echo "<tr>";
                    echo "<td class='border px-4 py-2'>" . $row['imgID'] . "</td>";
                
                    echo "<td class='border px-4 py-2'>
                            <input type='hidden' name='imgID' value='" . $row['imgID'] . "'>
                            <input type='text' id='item-name-" . $row['imgID'] . "' name='imgName' value='" . $row['imgName'] . "' class='w-full' readonly>
                          </td>";
                
                    echo "<td class='border px-4 py-2'>
                            <input type='number' id='item-price-" . $row['imgID'] . "' name='price' value='" . $row['price'] . "' class='w-full' readonly>
                          </td>";
                
                    echo "<td class='border px-4 py-2'>
                            <input type='text' id='item-description-" . $row['imgID'] . "' name='description' value='" . $row['Description'] . "' class='w-full' readonly>
                          </td>";
                
                    echo "<td class='border px-4 py-2'>
                            <button type='button' id='edit-btn-".$row['imgID']."' onclick='enableEdit(".$row['imgID'].")' class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded'>Edit</button>
                            <button type='button' id='save-btn-".$row['imgID']."' onclick='saveEdit(".$row['imgID'].")' class='bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded' style='display: none;'>Save</button>
                          </td>";
                    echo "</tr>";
                    echo "</form>";
                }
            } else {
                echo "<tr><td colspan='5' class='border px-4 py-2 text-center'>No items found</td></tr>";
            }
            ?>
            </tbody>
            </table>
            </section>

            <!-- Manage Orders Section -->
            <section id="manage-orders" class="hidden mb-6">
            <h2 class="text-2xl font-semibold mb-4">Manage Orders</h2>
            <form method="POST" action="admin.php" class="bg-white p-6 rounded-lg shadow-md space-y-4">
                <div class="flex flex-col">
                    <label for="order-id" class="text-sm font-medium mb-1">Order ID:</label>
                    <input type="text" id="order-id" name="order-id" required class="border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                </div>
                <div class="flex flex-col">
                    <label for="order-status" class="text-sm font-medium mb-1">Order Status:</label>
                    <select id="order-status" name="order-status" required class="border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <option value="pending">Select</option>
                        <option value="Processing">Processing</option>
                        <option value="Shipped">Shipped</option>
                        <option value="Delivered">Delivered</option>
                    </select>
                </div>
                <button type="submit" name="update-order-status" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Update Status
                </button>
            </form>
            </section>

            <!-- View Orders Section -->
            <section id="view-orders" class="hidden mb-6">
            <h2 class="text-2xl font-semibold mb-4">View Orders</h2>
            <table class="table-auto w-full bg-white rounded-lg shadow-lg">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="px-4 py-2">Order ID</th>
                        <th class="px-4 py-2">Product ID</th>
                        <th class="px-4 py-2">User ID</th>
                        <th class="px-4 py-2">Order Date</th>
                        <th class="px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result_orders->num_rows > 0) {
                        while($row = $result_orders->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='border px-4 py-2'>" . $row['orderID'] . "</td>";
                            echo "<td class='border px-4 py-2'>" . $row['productID'] . "</td>";
                            echo "<td class='border px-4 py-2'>" . $row['userID'] . "</td>";
                            echo "<td class='border px-4 py-2'>" . $row['orderDate'] . "</td>";
                            echo "<td class='border px-4 py-2'>" . $row['status'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='border px-4 py-2 text-center'>No orders found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            </section>
            <section id="logout" class="hidden mb-6">
                <h2 class="text-2xl font-semibold mb-4">Logout</h2>
                <button id="logout-btn" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">Logout</button>

                <div id="logout-modal" class="fixed inset-0 justify-center bg-gray-900 bg-opacity-50 z-50 hidden">
                    <div class="bg-white p-6 rounded-lg shadow-md w-1/3">
                        <h3 class="text-lg font-semibold mb-4">Are you sure you want to logout?</h3>
                        <div class="flex justify-end space-x-4">
                            <button id="confirm-logout" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">Yes</button>
                            <button id="cancel-logout" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">No</button>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
    <script src="admin.js"></script>
</body>
</html>
