<?php
include 'connect.php';

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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signUp'])) {
    $firstName = $_POST['fName'];
    $lastName = $_POST['lName'];
    $phoneNumber = $_POST['phoneNum'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    if ($gender == "Male" || $gender == "Female") {
        $checkEmail = "SELECT * FROM customer WHERE email = '$email'";
        $customerID = generateCustomID($conn, "CUS", "customer", "customerID");
        $result = $conn->query($checkEmail);
        
        if ($result->num_rows > 0) {
            echo "Email Address Already Exists!";
        } else {
            $insertQuery = "INSERT INTO customer (customerID, name, phoneNumber, address,email, password, gender) 
                            VALUES ('$customerID', '$firstName $lastName', '$phoneNumber','$address', '$email', '$password', '$gender')";
            if ($conn->query($insertQuery) === TRUE) {
                header("location: loginPage.php");
            } else {
                echo "Error: " . $conn->error;
            }
        }
    } else {
        echo "Pilihan gender tidak valid!";
    }
}

if(isset($_POST['signIn'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM customer WHERE email='$email'";
    $result = $conn->query($sql);

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        if (isset($row['Password']) && password_verify($password, $row['Password'])) {
            session_start();
            $_SESSION['customerID'] = $row['customerID'];
            $_SESSION['email'] = $row['email'];
            header("Location: homeCustomer.php");
            exit();
        } else {
            echo "Password salah!";
        }
    } else {
        echo "Email tidak ditemukan!";
    }
}


if(isset($_POST['signUpSeller'])){    
    $storeID = generateCustomID($conn, "STR", "store", "storeID");
    $storeName = $_POST['sName'];
    $storePhone = $_POST['sPhone'];
    $storeAddress = $_POST['sAddress'];
    $storeEmail = $_POST['sEmail'];
    $storePassword = password_hash($_POST['sPassword'], PASSWORD_DEFAULT);

    $checkStoreEmail = "SELECT * FROM store where storeEmail = '$storeEmail'";
    $resultStore = $conn->query($checkStoreEmail);
    
    if($resultStore->num_rows > 0){
        echo "Email Address Already Exists!";
    } else {
        $insertQuery = "INSERT INTO store (storeID,storeName,storePhoneNum,storeAddress,storeEmail,password) 
                        VALUES ('$storeID','$storeName','$storePhone','$storeAddress','$storeEmail','$storePassword')";
            if($conn->query($insertQuery) == TRUE){
                header("location: loginPage.php");
                exit(); 
            } else {
                echo "Error:".$conn->error;
            }
    }

}

if(isset($_POST['signInSeller'])){
    $storeEmail = $_POST['sEmail'];
    $storePassword = $_POST['sPassword'];

    $sql = "SELECT * FROM store WHERE storeEmail='$storeEmail'";
    $resultStore = $conn->query($sql);

    if($resultStore->num_rows > 0){
        $row = $resultStore->fetch_assoc();


        if (isset($row['password']) && password_verify($storePassword, $row['password'])) {
            session_start();
            $_SESSION['storeID'] = $row['storeID'];
            $_SESSION['sEmail'] = $row['storeEmail'];
            header("Location: homeStore.php");
            exit();
        } else {
            echo "Password salah!";
        }
    } else {
        echo "Email tidak ditemukan!";
    }

}



?>