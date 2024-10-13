<?php 
require 'db_connection.php'; 
session_start(); // Start the session to access user data 

// Check if the user is logged in 
if (!isset($_SESSION['username'])) {     
    header("Location: login.php"); // Redirect to the login page if not logged in     
    exit; 
}

// Database connection 
$db_server = "localhost"; 
$db_username = "admin"; 
$db_password = "admin"; 
$db_name = "web_app"; 

$conn = new mysqli($db_server, $db_username, $db_password, $db_name); 

// Check the connection 
if ($conn->connect_error) {     
    die("Connection failed: " . $conn->connect_error); 
}

// Check for removal request 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove'])) {     
    $product_name = $_POST['product_name'];     
    $username = $_SESSION['username']; 

    // Call a function to remove the product from orders 
    if (removeFromOrderByName($product_name, $username, $conn)) {         
        echo "<p style='color: lightcoral;'>Product removed from your order.</p>";     
    } else {         
        echo "<p style='color: red;'>Error removing product from your order.</p>";     
    } 
}

// Function to remove the product from orders 
function removeFromOrderByName($product_name, $username, $conn) {     
    $stmt = $conn->prepare("DELETE FROM orders WHERE username=? AND product_name=?");     
    $stmt->bind_param("ss", $username, $product_name); 

    if ($stmt->execute()) {         
        return $stmt->affected_rows > 0; // Check if any rows were deleted     
    } else {         
        echo "Error: " . $stmt->error; // Log error message for debugging         
        return false; // Indicate failure     
    } 
}

// Check for add to cart request 
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['remove'])) {     
    $product_name = $_POST['product_name'];     
    $quantity = $_POST['quantity'];     
    $username = $_SESSION['username']; 

    // Call a function to add the product to orders 
    if (addToOrder($product_name, $quantity, $username, $conn)) {         
        echo "<p style='color: lightgreen;'>Product added to your order.</p>";     
    } else {         
        echo "<p style='color: red;'>Error adding product to your order.</p>";     
    } 
}

// Function to add the product to orders 
function addToOrder($product_name, $quantity, $username, $conn) {     
    $stmt = $conn->prepare("INSERT INTO orders (username, product_name, quantity) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE quantity=quantity + ?");     
    $stmt->bind_param("ssii", $username, $product_name, $quantity, $quantity); 

    if ($stmt->execute()) {         
        return true; // Indicate success     
    } else {         
        echo "Error: " . $stmt->error; // Log error message for debugging         
        return false; // Indicate failure     
    } 
} 
?>

<!DOCTYPE html> 
<html lang="en"> 
<head>     
    <meta charset="UTF-8">     
    <meta name="viewport" content="width=device-width, initial-scale=1.0">     
    <title>Dashboard - Outfit Store</title>     
    <style>         
        body {             
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;             
            margin: 0;             
            padding: 0;             
            background-color: #f9f9f9; /* Light background */
            color: #333; /* Dark text */         
        }          
        header {             
            background-color: rgba(255, 255, 255, 0.9);             
            color: #6d4c41; /* Darker brown color */             
            padding: 20px;             
            text-align: center;             
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);         
        }          
        nav ul {             
            list-style: none;             
            margin: 0;             
            padding: 0;         
        }          
        nav li {             
            display: inline-block;             
            margin: 0 10px;         
        }          
        nav a {             
            color: #6d4c41; /* Darker brown color */             
            text-decoration: none;             
            transition: color 0.3s;         
        }          
        nav a:hover {             
            color: #b19870; /* Lighter brown on hover */         
        }          
        main {             
            padding: 20px;         
        }          
        section {             
            margin-bottom: 20px;             
            background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent white */             
            border: 2px solid #e8dba0; /* Light beige border */             
            border-radius: 10px;             
            padding: 15px;             
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */         
        }          
        input[type="text"],         
        input[type="number"],         
        input[type="email"],         
        textarea {             
            width: calc(100% - 20px);             
            padding: 10px;             
            border: 2px solid #e8dba0; /* Light beige border */             
            border-radius: 4px;             
            margin-bottom: 15px;             
            background-color: #fff; /* White input background */             
            color: #000; /* Dark text for inputs */         
        }          
        input[type="submit"],         
        button {             
            padding: 10px 20px;             
            border: none;             
            border-radius: 4px;             
            cursor: pointer;             
            transition: background-color 0.3s;             
            background-color: #d7ccc8; /* Beige background */             
            color: #000; /* Black text */         
        }          
        button {             
            background-color: #6d4c41; /* Dark brown color */             
            color: #FFFFFF; /* White text */         
        }          
        input[type="submit"][name='remove'] {             
            background-color: #FF4C4C; /* Red color */             
            color: #FFFFFF; /* White text */         
        }          
        input[type="submit"][value='Add to Cart'] {             
            background-color: #28A745; /* Green color */             
            color: #FFFFFF; /* White text */         
        }          
        input[type="submit"]:hover,         
        button:hover {             
            background-color: #b19870; /* Darker beige on hover */         
        }          
        h1, h2, h4 {             
            color: #6d4c41; /* Darker brown color */         
        }          
        footer {             
            background-color: rgba(255, 255, 255, 0.9);             
            color: #333;             
            padding: 20px;             
            text-align: center;             
            position: relative;             
            bottom: 0;             
            width: 100%;         
        }          
        a#logout {             
            color: #6d4c41; /* Darker brown color */             
            text-decoration: none;         
        }          
        a#logout:hover {             
            color: red; /* Red on hover */         
        }          
        /* Responsive adjustments */         
        @media (max-width: 600px) {             
            main {                 
                padding: 10px;             
            }         
        }     
    </style> 
</head> 
<body>     
    <header>         
        <img src="logo.png" alt="logo" width="90px">         
        <h1>Welcome to Beige Bliss</h1>         
        <nav>             
            <ul>                 
                <li><a href="#">Home</a></li>                 
                <li><a href="#Our Products">Products</a></li>                 
                <li><a href="#Cart">Add to Cart</a></li>                 
                <li><a href="#Your Orders">Your Orders</a></li>                 
                <li><a href="#Our Services">Services</a></li>                 
                <li><a href="#About Us">About Us</a></li>             
            </ul>         
        </nav>     
    </header>      

    <h4 style="color:lightgreen">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h4>          

    <main>         
        <section id="Our Products">             
            <u><h2>Our Products</h2></u>             
            <ul>                 
                <li><b><i>Bag</i></b><br>                     
                    <img src="bag.png" alt="Stylish Bag" width=180px>  
                    <i><p>Elegant Beige Bag- $100</p></i>                 
                </li>                 
                <li><b><i>DRESSES</i></b><br>                     
                    <img src="dress.png" alt="Elegant Dress" width=180px>                     
                    <i><p>Elegant Evening Dress - $200</p></i>                 
                </li>                 
                <li><b><i>Sneakers</i></b><br><i><p>Available Soon...</p></i></li>             
            </ul>         
        </section>          

        <section id="Cart">             
            <u><h2>Add to Cart</h2></u>             
            <form action="dashboard.php" method="post">                 
                <label for="product_name">Product Name:</label>                 
                <input type="text" id="product_name" name="product_name" required>                  

                <label for="quantity">Quantity:</label>                 
                <input type="number" id="quantity" name="quantity" min="1" value="1" required>                  

                <input type="submit" value="Add to Cart">             
            </form> 
        </section>           

        <section id="Your Orders">             
            <u><h2>Your Orders</h2></u>             
            <?php             
            $username = $_SESSION['username'];             
            $sql = "SELECT product_name, quantity FROM orders WHERE username='$username'";             
            $result = $conn->query($sql);              

            if ($result->num_rows > 0) {                 
                $itemIndex = 0;                 
                echo "<ul>";                 
                while ($row = $result->fetch_assoc()) {                     
                    $itemIndex++;                     
                    echo "<li>{$row['quantity']} x {$row['product_name']}                          
                            <form method='post' style='display: inline;'>                             
                                <input type='hidden' name='product_name' value='" . htmlspecialchars($row['product_name']) . "'>                             
                                <input type='submit' name='remove' value='Remove'>                         
                            </form>                         
                            <button id='edit-button-$itemIndex' onclick='toggleEditForm($itemIndex)'>Edit</button>                         
                            <form method='post' id='edit-form-$itemIndex' style='display: none;'>                             
                                <input type='hidden' name='product_name' value='" . htmlspecialchars($row['product_name']) . "'>                             
                                <label for='new_quantity'>New Quantity:</label>                             
                                <input type='number' id='new_quantity' name='new_quantity' required min='1'>                             
                                <input type='submit' name='edit' value='Edit'>                         
                            </form>                     
                        </li>";                 
                }                 
                echo "</ul>";             
            } else {                 
                echo "<p>No orders yet.</p>";             
            }             
            ?>         
        </section>          

        <section id="Our Services">             
            <u><h2>Our Services</h2></u>             
            <ul>                 
                <li>Home Delivery Service</li>                 
                <li>Free Returns within 30 days</li>                 
                <li>Styling Advice Available</li>             
            </ul>         
        </section>          

        <section id="About Us">             
            <u><h2>About Us</h2></u>             
            <i>                 
                <p>We are a premier outfit store dedicated to providing stylish and high-quality clothing options. Our store is known for its exceptional customer service and wide range of trendy apparel. We pride ourselves on being a trusted name in fashion, committed to meeting the diverse needs of our customers.                   
                </p>             
            </i>         
        </section>          

        <section id="Contact Us">             
            <u><h2>Contact Us</h2></u>             
            <form action="">                 
                <label for="name">Name</label>                 
                <input type="text" id="name" name="name" required>                 
                <label for="email">Email</label>                 
                <input type="email" id="email" name="email" required>                 
                <label for="message">Message</label>                 
                <textarea id="message" name="message" rows="4" required></textarea>                 
                <button id="contactUsButton" type="submit">Submit</button>             
            </form>         
        </section>     
    </main>      

    <a id="logout" href="logout.php">Logout</a> <!-- Logout link -->     
    <br>      

    <footer>         
        <p>Copyright © 2023 Outfit Store</p>     
    </footer>      

    <script>         
        function toggleEditForm(itemIndex) {             
            var editForm = document.getElementById('edit-form-' + itemIndex);             
            var editButton = document.getElementById('edit-button-' + itemIndex);             

            if (editForm.style.display === 'none' || editForm.style.display === '') {                 
                editForm.style.display = 'block';                 
                editButton.innerHTML = 'Cancel';             
            } else {                 
                editForm.style.display = 'none';                 
                editButton.innerHTML = 'Edit';             
            }         
        }     
    </script> 
</body> 
</html>
