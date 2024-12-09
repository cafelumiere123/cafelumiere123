<?php
     session_start();
     include 'process.php';


     if (!isset($_SESSION['orders'])) 
	 {
         $_SESSION['orders'] = [];
     }


     function calculateItemTotal($item) 
	 {
         $price = 0;
             switch ($item['name']) 
	 {
                 case 'Americano':
                 $price = ($item['size'] === 'tall') ? 90 : (($item['size'] === 'grande') ? 110 : 150);
                 break;
				 
                 case 'Cappuccino':
                 $price = ($item['size'] === 'tall') ? 90 : (($item['size'] === 'grande') ? 110 : 150);
                 break;
				 
                 case 'Espresso':
                 $price = ($item['shots'] === 'solo') ? 30 : (($item['shots'] === 'doppio') ? 50 : (($item['shots'] === 'triple') ? 70 : 90));
                  break;
              
        		 case 'Latte':
                 $price = ($item['size'] === 'tall') ? 130 : (($item['size'] === 'grande') ? 150 : 180);
                 break;
				 
                 case 'Mocha':
                 $price = ($item['size'] === 'tall') ? 140 : (($item['size'] === 'grande') ? 160 : 200);
                 break;
				 
                 case 'Matcha':
                 $price = ($item['serving'] === 'hot') ? 150 : 160;
                 break;
				 
                 case 'Croissant':
                 $price = ($item['flavor'] === 'ham') ? 100 : (($item['flavor'] === 'chocolate') ? 90 : 80);
                 break;
            
			     case 'Bagel':
                 $price = ($item['flavor'] === 'org') ? 100 : 120;
                 break;
				 
                 case 'Cookie':
                 $price = 60;
                 break;
                 
				 case 'Brownie':
                 $price = 80;
                 break;
     }
     return $price * $item['quantity'];
     }

     // Handle form submissions
         if ($_SERVER["REQUEST_METHOD"] == "POST") 
	 {
     // Handle drink orders
         
		 if (isset($_POST['order_americano'])) 
	  {
         $order = [
             'name' => 'Americano',
             'size' => $_POST['size_americano'],
             'shots' => $_POST['shots_americano'],
             'quantity' => $_POST['qty_americano']
         ];
         $_SESSION['orders'][] = $order;
      }
    
	     if (isset($_POST['order_cappuccino'])) 
	  {
         $order = [
             'name' => 'Cappuccino',
             'size' => $_POST['size_cappuccino'],
             'shots' => $_POST['shots_cappuccino'],
             'milk' => $_POST['milk_cappuccino'],
             'quantity' => $_POST['qty_cappuccino']
         ];
         $_SESSION['orders'][] = $order;
      }
        
		 if (isset($_POST['order_espresso']))
	  {
         $order = [
             'name' => 'Espresso',
             'shots' => $_POST['shots_espresso'],
             'quantity' => $_POST['qty_espresso']
         ];
         $_SESSION['orders'][] = $order;
      }
         
		 if (isset($_POST['order_latte'])) 
	  {
         $order = [
             'name' => 'Latte',
             'size' => $_POST['size_latte'],
             'shots' => $_POST['shots_latte'],
             'milk' => $_POST['milk_latte'],
             'quantity' => $_POST['qty_latte']
         ];
         $_SESSION['orders'][] = $order;
      }
    
	     if (isset($_POST['order_mocha'])) 
	  {
         $order = [
             'name' => 'Mocha',
             'size' => $_POST['size_mocha'],
             'shots' => $_POST['shots_mocha'],
             'milk' => $_POST['milk_mocha'],
             'quantity' => $_POST['qty_mocha']
         ];
         $_SESSION['orders'][] = $order;
      }
         
		 if (isset($_POST['order_matcha'])) 
	  {
         $order = [
             'name' => 'Matcha',
             'serving' => $_POST['serving_matcha'],
             'quantity' => $_POST['qty_matcha']
         ];
         $_SESSION['orders'][] = $order;
      }

     // Handle pastry orders
      
	     if (isset($_POST['order_croissant'])) 
	  {
         $order = [
             'name' => 'Croissant',
             'flavor' => $_POST['flavor_croissant'],
             'quantity' => $_POST['qty_croissant']
          ];
         $_SESSION['orders'][] = $order;
      }
    
	     if (isset($_POST['order_bagel'])) 
	  {
         $order = [
             'name' => 'Bagel',
             'flavor' => $_POST['flavor_bagel'],
             'quantity' => $_POST['qty_bagel']
          ];
         $_SESSION['orders'][] = $order;
      }
     
	     if (isset($_POST['order_cookie'])) 
	 {
         $order = [
             'name' => 'Cookie',
             'quantity' => $_POST['qty_cookies']
         ];
         $_SESSION['orders'][] = $order;
      }
     
	     if (isset($_POST['order_brownie'])) 
	 {
         $order = [
             'name' => 'Brownie',
             'quantity' => $_POST['qty_brownies']
         ];
         $_SESSION['orders'][] = $order;
      }

      // Handle order deletion
      if (isset($_POST['delete_order'])) 
	  {
         $index = (int)$_POST['delete_order'];
         unset($_SESSION['orders'][$index]);
         $_SESSION['orders'] = array_values($_SESSION['orders']); 
      }
	  
      file_put_contents('process.php', '<?php $orders = ' . var_export($_SESSION['orders'], true) . '; ?>');
     }

$showOrderReview = isset($_POST['order_review']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="design.css">
     <link rel="icon" type="image/x-icon" href="lego.png">
	 <title>Menu | Café de Lumière</title>
</head>
<header>
     <div class="navbar">
     <a href="homepage.html">Home</a>
     <a href="new 2.php">Menu</a>
     <a href="new3.php">Order</a>
     <a href="aboutus.html">About Us</a>
     <a id="acc" href="signup.php">Account</a>
     </div>
</header>
<body>

<?php
     if ($showOrderReview) {
     echo '<br><br><br><br><br><br>';
	 echo'<center>';
	 if (empty($_SESSION['orders'])) 
	 {
         echo '<p>Your order review is empty.</p>';
     } 
	 else 
	 {
         echo '<fieldset><legend>Order Details</legend>';
         $total = 0;
         foreach ($_SESSION['orders'] as $index => $orderedItem) {
             echo '<p>' . htmlspecialchars($orderedItem['name']) .
                 (isset($orderedItem['size']) ? ' (Size: ' . htmlspecialchars($orderedItem['size']) . ')' : '') .
                 (isset($orderedItem['flavor']) ? ' (Flavor: ' . htmlspecialchars($orderedItem['flavor']) . ')' : '') .
                 (isset($orderedItem['shots']) ? ' (Shots: ' . htmlspecialchars($orderedItem['shots']) . ')' : '') .
                 (isset($orderedItem['serving']) ? ' (Serving: ' . htmlspecialchars($orderedItem['serving']) . ')' : '') .
                 ' (Qty: ' . htmlspecialchars($orderedItem['quantity']) . ')</p>';
         $itemTotal = calculateItemTotal($orderedItem);
             echo '<p>- ₱' . number_format($itemTotal, 2) . '</p>';
             $total += $itemTotal;

            // Delete button for each order item
             echo '<form method="post" style="display:inline;">';
             echo '<button type="submit" id="del" name="delete_order" value="' . $index . '">Delete</button>';
             echo '</form>';
     }
             
             echo '<p><strong>Total:</strong> ₱' . number_format($total, 2) . '</p>';
             echo '</fieldset>';
     }

           // Button to return to the menu
          echo '<form method="post">';
          echo '<button type="submit" id="menu"name="back_to_menu">Back to Menu</button>';
          echo '</form>';
     } 
	     else 
	 {
    
         echo'</center>';
?>

     <form method="post">
     <br><br><br><br><br>
	 <div class="center">
         <h1>Coffee Beverages</h1>
         <h3>The enticing scent of freshly brewed coffee wafts through the air, welcoming patrons to unwind. 
         The inviting lighting, gentle music, and skillfully crafted beverages foster an ideal atmosphere for relaxation and conversation.</h3>
    </div>
   
   <div class="grid-container2">
          <!-- AMERICANO -->
         <div class="grid-item2">
             <img src="americano2.jpg" alt="Americano">
             <h2>Americano</h2>
             <p>A simple yet bold coffee made by diluting a shot of espresso with hot water.</p>
		     <label for="size-americano">Size:<select id="size-americano" name="size_americano">
                 <option value="">Select</option>
                 <option value="tall">Tall - ₱90.00</option>
                 <option value="grande">Grande - ₱110.00</option>
                 <option value="venti">Venti - ₱150.00</option>
             </select></label><br>
            
            
			<label for="shots-americano">Espresso Shots:<input type="number" id="shots-americano" name="shots_americano" max="10" min="1"></label><br>
           
            
			<label for="qty-americano">Quantity:<input type="number" id="qty-americano" name="qty_americano" max="50" min="1"></label>
            <br><br>
             <center><button class="order" type="submit" name="order_americano">Order</button></center>
         </div>

         <!-- CAPPUCCINO -->
         <div class="grid-item2">
             <img src="cappuccino2.jpg" alt="Cappuccino">
             <h2>Cappuccino</h2>
             <p>A balanced blend of espresso, steamed milk, and a layer of foamy milk on top.</p>
			 <label for="size-cappuccino">Size:<select id="size-cappuccino" name="size_cappuccino">
                 <option value="">Select</option>
                 <option value="tall">Tall - ₱90.00</option>
                 <option value="grande">Grande - ₱110.00</option>
                 <option value="venti">Venti - ₱150.00</option>
             </select></label>
             <br>
            
			 <label for="shots-cappuccino">Espresso Shots:<input type="number" id="shots-cappuccino" name="shots_cappuccino" max="10" min="1"></label>
             <br>
            
			 <label for="milk-cappuccino">Milk:<select id="milk-cappuccino" name="milk_cappuccino">
                 <option value="">Select</option>
                 <option value="whole">Whole Milk</option>
                 <option value="soy">Soy Milk</option>
                 <option value="almond">Almond Milk</option>
             </select></label>
             <br>
            
			 <label for="qty-cappuccino">Quantity:<input type="number" id="qty-cappuccino" name="qty_cappuccino" max="50" min="1"></label>
             
			 <br><br>
             <center><button class="order" type="submit" name="order_cappuccino">Order</button></center>
         </div>

        
	     <!-- ESPRESSO -->
         <div class="grid-item2">
             <img src="espresso2.jpg" alt="Espresso">
             <h2>Espresso</h2>
             <p>A concentrated coffee made by forcing hot water through finely ground coffee beans. </p>
			 <label for="size-espresso">Size:<select id="size-espresso" name="size_espresso">
                 <option value="">Select</option>
                 <option value="single">Single - ₱30.00</option>
                 <option value="double">Double - ₱50.00</option>
                 <option value="triple">Triple - ₱70.00</option>
                 <option value="quad">Quad - ₱90.00</option>
             </select></label>
             <br>
            
			 <label for="shots-espresso">Espresso Shots:<input type="number" id="shots-espresso" name="shots_espresso" max="10" min="1"></label>
             <br>
            
			 <label for="qty-espresso">Quantity:<input type="number" id="qty-espresso" name="qty_espresso" max="50" min="1"></label>
             <br><br>
            
			 <center><button class="order" type="submit" name="order_espresso">Order</button></center>
         </div>

         <!-- LATTE -->
         <div class="grid-item2">
             <img src="latte2.jpg" alt="Latte">
             <h2>Latte</h2>
             <p>A creamy blend of espresso and steamed milk, topped with a small amount of foam.</p>
			 <label for="size-latte">Size:<select id="size-latte" name="size_latte"></label>
                 <option value="">Select</option>
                 <option value="tall">Tall - ₱130.00</option>
                 <option value="grande">Grande - ₱150.00</option>
                 <option value="venti">Venti - ₱180.00</option>
             </select></label>
             <br>
            
			 <label for="shots-latte">Espresso Shots:<input type="number" id="shots-latte" name="shots_latte" max="10" min="1"></label>
             <br>
             
			 <label for="milk-latte">Milk:<select id="milk-latte" name="milk_latte">
                 <option value="">Select</option>
                 <option value="whole">Whole Milk</option>
                 <option value="soy">Soy Milk</option>
                 <option value="almond">Almond Milk</option>
             </select></label>
             <br>
            
			 <label for="qty-latte">Quantity:<input type="number" id="qty-latte" name="qty_latte" max="50" min="1"></label>
             <br><br>
             
			  <center><button class="order" type="submit" name="order_latte">Order</button> </center>
         </div>

         <!-- MOCHA -->
         <div class="grid-item2">
             <img src="mocha2.jpg" alt="Mocha">
             <h2>Mocha</h2>
             <p>A rich and indulgent drink made by combining espresso, steamed milk, and chocolate syrup, often topped with whipped cream.</p>
			 <label for="size-mocha">Size:<select id="size-mocha" name="size_mocha">
                 <option value="">Select</option>
                 <option value="tall">Tall - ₱140.00</option>
                 <option value="grande">Grande - ₱160.00</option>
                 <option value="venti">Venti - ₱200.00</option>
             </select></label>
             <br>
            
			 <label for="shots-mocha">EspressoShots:<input type="number" id="shots-mocha" name="shots_mocha" max="10" min="1">
             <br>
             
			 <label for="milk-mocha">Milk:<select id="milk-mocha" name="milk_mocha">
                <option value="">Select</option>
                <option value="whole">Whole Milk</option>
                <option value="soy">Soy Milk</option>
                <option value="almond">Almond Milk</option>
             </select></label>
             <br>
             
			 <label for="qty-mocha">Quantity:<input type="number" id="qty-mocha" name="qty_mocha" max="50" min="1"></label>
             <br><br>
             
			 <button class="order" type="submit" name="order_mocha">Order</button>
         </div>

         <!-- MATCHA -->
         <div class="grid-item2">
             <img src="matcha2.jpg" alt="Matcha">
             <h2>Matcha</h2>
             <p>A vibrant green powder made from finely ground tea leaves, known for its unique flavor and health benefits. </p>
			 <label for="serving-matcha">Serving:<select id="serving-matcha" name="serving_matcha">
                 <option value="">Select</option>
                 <option value="hot">Hot - ₱150.00</option>
                 <option value="iced">Iced - ₱160.00</option>
             </select></label>
             <br>
             
			 <label for="qty-matcha">Quantity:<input type="number" id="qty-matcha" name="qty_matcha" max="50" min="1"></label>
             <br><br>
            
			 <center><button class="order" type="submit" name="order_matcha">Order</button></center>
         </div>
         </div>
         <br>
		 
	         <center><button type="submit"id="view" name="order_review">Order Review</button></center>

			 
	 <div class="center">
         <h1>Patisserie </h1>
         <h3>Each pastry offers a delightful blend of flavors and textures, making it a perfect companion for a warm cup of coffee or tea. 
         These indulgent bites elevate any coffee break into a satisfying experience.</h3>
         </div>
         <div class="grid-container2">
        
		 <!-- CROISSANT -->
         <div class="grid-item2">
             <img src="cros2.png" alt="Croissant">
             <h2>Croissant</h2>
             <p>A flaky, buttery pastry that originated in France, characterized by its layers created through a process of folding and rolling dough with butter.</p>
			 <label for="flavor-croissant">Flavor:<select id="flavor-croissant" name="flavor_croissant">
                 <option value="">Select</option>
                 <option value="plain">Plain - ₱80.00</option>
                 <option value="chocolate">Chocolate - ₱90.00</option>
                 <option value="ham">Ham - ₱100.00</option>
             </select></label>
             <br>
            
			 <label for="qty-croissant">Quantity:<input type="number" id="qty-croissant" name="qty_croissant" max="50" min="1"></label>
             <br><br>
            
			  <center><button class="order" type="submit" name="order_croissant">Order</button></center>
         </div>

         <!-- BAGEL -->
         <div class="grid-item2">
             <img src="bagel.png" alt="Bagel">
             <h2>Bagel</h2>
             <p>A dense, chewy bread that is boiled before baking, giving it a distinctive texture and shiny crust.</p>
			 <label for="flavor-bagel">Flavor:<select id="flavor-bagel" name="flavor_bagel">
                 <option value="">Select</option>
                 <option value="org">Original - ₱100.00</option>
                 <option value="seeds">Seeds - ₱120.00</option>
             </select></label>
             <br>
            
			 <label for="qty-bagel">Quantity:<input type="number" id="qty-bagel" name="qty_bagel" max="50" min="1"></label>
             <br><br>
            
			 <center><button class="order" type="submit" name="order_bagel">Order</button></center>
         </div>

         <!-- COOKIE -->
         <div class="grid-item2">
             <img src="late.png" alt="Cookie">
             <h2>Cookie</h2>
			 <p>Soft, chewy treats made with rich cocoa powder or chocolate chunks, these beloved desserts feature a slight crispness on the edges and a gooey center.</p>
			 <p>Price:₱60.00</p>
             
			 <label for="qty-cookies">Quantity:<input type="number" id="qty-cookies" name="qty_cookies" max="50" min="1"></label>
             <br><br>
             
			 <center><button class="order" type="submit" name="order_cookie">Order</button></center>
         </div>

         <!-- BROWNIE -->
         <div class="grid-item2">
             <img src="brown.png" alt="Brownie">
             <h2>Brownie</h2>
			 <p>Decadent, fudgy squares made from rich chocolate, brownies are a favorite dessert for chocolate lovers. </p>
			 <p>Price:₱80.00</p>
            
			 <label for="qty-brownies">Quantity:<input type="number" id="qty-brownies" name="qty_brownies" max="50" min="1"></label>
             <br><br>
            
			 <center><button class="order" type="submit" name="order_brownie">Order</button></center>
         </div>
         </div>
	     <br>
	         <center><button type="submit"id="view" name="order_review">Order Review</button></center>
         </form>

     <?php
}
     ?>
</body>
</html>
<br><br><br><br><br><br>

<center>
<footer>
     <div class="contact">
      <p><strong>Contact Us</strong></p>
      <p>Phone: 0916 782 3419</p>
      <p>Email: cafe.lumiere@gmail.com</p>
      <a id="fb"href="https://m.facebook.com/p/Caf%C3%A9-de-Lumi%C3%A8re-61565829464639/?wtsid=rdr_0vVetnboXopuU5v8C" target="_blank">Facebook</a>
	  <p>&copy;Café de Lumière. All rights reserved.</p>	
     </div>
</footer> 
</center>
<br><br><br><br>
</html> 
