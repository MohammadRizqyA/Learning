<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['customerID'])) {
    die("Anda harus login terlebih dahulu.");
}

$customerID = $_SESSION['customerID'];
$query = "SELECT Name, Email, customerImage FROM customer WHERE customerID = '$customerID'";
$result = mysqli_query($conn, $query);
$customer = mysqli_fetch_assoc($result);

$id = isset($_GET['id']) ? $_GET['id'] : '';
$orderID = mysqli_real_escape_string($conn, $id);

$detail_query = "SELECT o.*, p.* 
                FROM `order` o
                JOIN payment p ON o.paymentTypeID = p.paymentTypeID
                WHERE orderID = '$orderID'"; 
$detail_result = mysqli_query($conn, $detail_query);
$detail_data = mysqli_fetch_assoc($detail_result);

$order_query = " SELECT 
                s.storeID,
                s.storeName,
                s.storeImage,
                p.productID,
                p.productName,
                p.productImage,
                od.quantity,
                od.price,
                od.size,
                od.orderDetailID
                FROM `order` o
                JOIN subOrder so ON o.orderID = so.orderID
                JOIN orderDetail od ON so.subOrderID = od.subOrderID
                JOIN product p ON od.productID = p.productID
                JOIN store s ON p.storeID = s.storeID
                WHERE o.orderID = '$orderID'
                ORDER BY s.storeName ASC";
$order_result = mysqli_query($conn, $order_query);

$order_items = [];
while ($row = mysqli_fetch_assoc($order_result)) {
    $storeName = $row['storeName'];

    // Masukin storeImage satu kali saja
    if (!isset($order_items[$storeName])) {
        $order_items[$storeName] = [
            'storeImage' => $row['storeImage'],
            'products' => []
        ];
    }

    // Masukin produk ke dalam products[]
    $order_items[$storeName]['products'][] = $row;
}

function generateCustomID($conn, $prefix, $table, $idColumn) {
    $query = "SELECT $idColumn FROM $table WHERE $idColumn LIKE '$prefix%' ORDER BY $idColumn DESC LIMIT 1";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $lastNumber = (int)substr($row[$idColumn], 3); 
        $newNumber = $lastNumber + 1;
    } else {
        $newNumber = 1;
    }   
    $customID = $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    return $customID;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_review'])) {
    $pReviewID = generateCustomID($conn, "REV", "productreview", "pReviewID");
    $orderDetailID = $_POST['orderDetailID'];
    $rating = $_POST['rating'];
    $review = $_POST['review'];

    $stmt = $conn->prepare("INSERT INTO productReview (pReviewID, orderDetailID, rating, review) 
                            VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $pReviewID, $orderDetailID, $rating, $review);

    if ($stmt->execute()) {
        header("Location: orderDetail.php?id=$orderID");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/customerNavbar.css">
    <link rel="stylesheet" href="css/orderDetail.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bellefair&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mate+SC&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Afacad+Flux:wght@100..1000&family=Bodoni+Moda+SC:ital,opsz,wght@0,6..96,400..900;1,6..96,400..900&family=Bodoni+Moda:ital,opsz,wght@0,6..96,400..900;1,6..96,400..900&family=DM+Serif+Text:ital@0;1&family=Oswald:wght@200..700&family=Staatliches&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Luxora</title>
    <link rel="icon" href="images/logos/logo.png" type="image/png">
</head>
<body>


<div class="headerDetail" id="headerDetail">
    <h2>ID <?= htmlspecialchars($orderID) ?></h2>

    <div class="detail-top">
        <p>PAYMENT METHOD</p>
        <h3><?= htmlspecialchars($detail_data['paymentType']) ?></h3>
    </div>
    <div class="detail-top">
        <p>TOTAL PRICE</p>
        <h3>$ <?= htmlspecialchars($detail_data['totalPrice']) ?></h3>
    </div>
    <div class="detail-top">
        <p>TAX</p>
        <h3>$ <?= htmlspecialchars($detail_data['tax']) ?></h3>
    </div>
    <div class="detail-top">
        <p>STATUS</p>
        <h3 class="status">DELIVERED</h3>
    </div>
    <div class="detail-top">
        <p>ORDERED ON</p>
        <h3><?= htmlspecialchars($detail_data['orderDate']) ?></h3>
    </div>
    
</div>

<div class="content">
    <div class="left-content">
        <div class="text">
            <i class="fa-regular fa-clock"></i>
            <h1>ORDER DETAILS</h1>
        </div>
      
        <a href="orderHistory.php"><i class="fa-solid fa-angle-left"></i></a>
    </div>
    <div class="right-content">
        <div class="store-list">
        <?php foreach ($order_items as $store_name => $store): ?>
          <div class="store-wrap">
            <div class="store-header">
                <div class="store-image">
                    <img src="uploads/stores/<?= htmlspecialchars($store['storeImage']) ?>" alt="<?= htmlspecialchars($store_name) ?>">
                </div>
            </div>

              <div class="product-list">
                <?php foreach ($store['products'] as $item): ?>
                <div class="product-wrap">
                    <div class="details">
                       <div class="product-container">
                        <a><img src="uploads/products/<?= htmlspecialchars($item['productImage']) ?>"></a>
                        <div class="productbutton">
                            <a class="review-button" onclick="openPopup('<?= $item['productID'] ?>')">Review Product</a>
                        </div>
                       </div>
                        <div class="specific-details">
                            <div class="name"> 
                                <h1><?= htmlspecialchars($item['productName']) ?></h1>
                            </div>
                        </div>
                    </div>
                    <div class="size-qty">
                        <h2>Size <?= htmlspecialchars($item['size']) ?></h2>
                        <p>Qty <?= htmlspecialchars($item['quantity'])?></p>
                    </div>
                    <div class="price">$ <?= number_format($item['price'] * $item['quantity'], 2) ?> </div>
                </div>
                <div class="pop">
                    <div class="popup" id="popup">
                        <div class="add-product-form">
                            <div class="back">
                                <i class="fa-solid fa-angle-left" onclick="closePopup()"></i>
                                <div class="back-text">
                                    <h1>Review Product</h1>
                                    <p>Cancel</p>
                                </div>
                            </div>
                            <form method="POST">
                                <input type="hidden" name="orderDetailID" id="detailID" value="<?= htmlspecialchars($item['orderDetailID'])?>">
                                <label for="rating">Rating (1-5):</label><br>
                                <select name="rating" id="rating" required>
                                    <option value="">Select Rating</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select><br><br>
                                
                                <label for="comment">Review Comment</label><br>
                                <textarea name="review" id="comment" rows="4" required></textarea><br><br>

                                <button type="submit" name="submit_review">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
              </div>
                <div class="line"></div>
         
            </div>
        <?php endforeach; ?>
        </div>
    </div>
    </div>



    
</div>
    <script src="js/orderDetail.js"></script>

</body>
</html>