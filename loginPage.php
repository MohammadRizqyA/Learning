<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register</title>
    <link rel="stylesheet" href="css/loginPage.css">
</head>
<body>
    <section id="hero">
        <div class="banner">
        <h1>LUXORA</h1>
        <img src="login/banner.png" class="loginBanner"> 
        </div>
    <div class="container" id="signUp" style="display:none;">
        <h1 class="form-title">Register</h1>
        <h2>Create new account to start shopping!</h2>
       <form method="post" action="register.php">
            <div class="nameForm">
                <div class="input-group">
                    <input class="fName" type="text" name="fName" id="fName" placeholder="Enter your first name" autocomplete="off" required>
                </div>
                <div class="input-group">
                    <input class="lName" type="text" name="lName" id="lName" placeholder="Enter your last name" autocomplete="off" required>
                </div>
            </div>
            <div class="input-group">
                <input type="number" name="phoneNum" id="phoneNum" placeholder="Enter your phone number" autocomplete="off" required>
            </div>
            <div class="addressGender">
                <div class="input-group">
                    <textarea id="address" name="address" placeholder="Enter your address" autocomplete="off"></textarea>
                </div>
                <div class="input-group">
                   <select name="gender" class="genderCat "id="genderCat" required>
                    <option class="unselected" value="">Choose your gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                   </select>
                </div>
            </div>
           
            <div class="input-group">
                <input type="email" name="email" id="email" placeholder="Enter your email" autocomplete="off" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" id="password" placeholder="Enter your password" autocomplete="off" required>
            </div>
            
            
            <input type="submit" class="btn" value="Sign Up" name="signUp">
       </form>
        <div class="links">
            <div>
                <p>Already have an account?</p>
                <button id="signInButton">Sign In</button>
            </div>
            <div>
                <p>Are you a seller?</p>
                <button id="signUpSellerButton">Change to seller</button>
            </div>
            </div>
    </div>
    <div class="container" id="signIn">
        <h1>Sign In</h1>
        <h2>Sign in now to explore the new product you need!</h2>
        <form method="post" action="register.php">
            <div class="input-group"> 
                <input type="email" name="email" id="email" placeholder="Enter your email" autocomplete="off" required> 
            </div>
            <div class="input-group">
                <input type="password" name="password" id="password" placeholder="Enter your password" autocomplete="off" required>
            </div>
            <input type="submit" class="btn" value="Sign In" name="signIn">
        </form>
        <div class="links">
            <div class="have">
                <p>Dont have an account?</p>
                <button id="signUpButton">Sign Up</button>
            </div>
            <div>
                <p>Are you a seller?</p>
                <button id="signInSellerButton">Change to seller</button>
            </div>
           
         </div>
    </div>
    <div class="container" id="signUpSeller" style="display:none;">
            <h1 class="form-title">Register</h1>
            <h2>Create new store account to start selling items!</h2>
           <form method="post" action="register.php">

                <div class="input-group">
                    <input type="text" name="sName" id="sName" placeholder="Enter store name" autocomplete="off" required>
                </div>
                <div class="input-group">
                    <input type="number" name="sPhone" id="sPhone" placeholder="Enter store phone number" autocomplete="off" required>
                </div>
                <div class="input-group">
                    <textarea id="sAddress" name="sAddress" placeholder="Enter store address" autocomplete="off"></textarea>
                </div>
                <div class="input-group">
                    <input type="email" name="sEmail" id="sEmail" placeholder="Enter store email" autocomplete="off" required>
                </div>
                <div class="input-group">
                    <input type="password" name="sPassword" id="sPassword" placeholder="Enter store password" autocomplete="off" required>
                </div>
                
                <input type="submit" class="btn" value="Sign Up" name="signUpSeller">
            </form>
            <div class="links">
                <div>
                    <p>Already have an account?</p>
                    <button id="signInSellerButton2">Sign In</button>
                </div>
                <div>
                    <p>Are you looking for product?</p>
                    <button id="signUpBuyer">Change to buyer</button>
                </div>
            </div>
        </div>
    <div class="container" id="signInSeller" style="display:none;">
            <h1>Sign In</h1>
            <h2>Sign in to your store account!</h2>
            <form method="post" action="register.php">
                <div class="input-group"> 
                    <input type="email" name="sEmail" id="sEmail" placeholder="Enter your email" autocomplete="off" required> 
                </div>
                <div class="input-group">
                    <input type="password" name="sPassword" id="sPassword" placeholder="Enter your password" autocomplete="off" required>
                </div>
                <input type="submit" class="btn" value="Sign In" name="signInSeller">
            </form>
            <div class="links">
                <div class="have">
                    <p>Dont have an account?</p>
                    <button id="signUpSellerButton2">Sign Up</button>
                </div>
                <div>
                    <p>Are you looking for product?</p>
                    <button id="signInBuyer">Change to buyer</button>
                </div>
               
            </div>
    </div>
    </section>
    
    

    <script src="js/script.js"></script>
</body>
</html>