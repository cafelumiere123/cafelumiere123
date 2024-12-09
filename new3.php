     <?php
         session_start();

         // Function to load all orders from the data file
         function loadOrders($filename) 
     {
         $orders = [];
         if (file_exists($filename)) 
     {
         include($filename);  
         if (isset($users)) 
	 {
            $orders = $users;  
      }
     }
         return $orders;
     }

         // Handle form submission
         if ($_SERVER["REQUEST_METHOD"] == "POST") 
	 {
         $user_data_file = 'new3data.php';

         if (isset($_POST['delete'])) 
	 {
         // Load existing orders
         $orders = loadOrders($user_data_file);
        
		     array_pop($orders); 
        
         // Save the updated order list back to the file
         $new_data = "<?php\n\$users = " . var_export($orders, true) . ";\n?>";
             file_put_contents($user_data_file, $new_data, LOCK_EX); 

          
		  // Clear session data
             unset($_SESSION['order_data']);
        
     }  
	     elseif (isset($_POST['edit'])) 
	 {
         // Direct to editing mode
     }  
	     else 
	 {
        
        $_SESSION['order_data'] = $_POST;

         // Prepare user data for saving
         $user_data = $_SESSION['order_data'];
         $orders = loadOrders($user_data_file);
         $orders[] = $user_data; 

         
         $new_data = "<?php\n\$users = " . var_export($orders, true) . ";\n?>";
         file_put_contents($user_data_file, $new_data, LOCK_EX);
      }
      }

         // Check if order data is set for display or editing
         $order_data = isset($_SESSION['order_data']) ? $_SESSION['order_data'] : null;

     ?>

<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Order | Café de Lumière</title>
     <link rel="icon" type="image/x-icon" href="lego.png">
     <link rel="stylesheet" href="design.css">
    <script>
    const barangays = {
        
            "Caloocan": ["Amparo", "Bagbaguin", "Bagong Barrio East", "Bagong Barrio West", "Bagong Silang", "Bagumbong", "Balintawak", "Barrio San Jose", "B.F. Homes", "Camarin", "Congress","Dagat-Dagatan","Deparo","Grace Park East","Grace Park West","Kaybiga","Kaunlaran Village","Libis Baesa/Reparo","Ilano","Maypajo","Marulas","Morning Breeze","Pangarap Village","Poblacion","Santa Quiteria","Sangandaan","Tala","Talipapa","University Hills","Urduja"],
            "Las Piñas": ["Almanza Uno", "Almanza Dos", "B.F. International Village", "Daniel Fajardo", "Elias Aldana", "Ilaya", "Manuyo Uno", "Manuyo Dos", "Pamplona Uno", "Pamplona Dos","Pamplona Tres","Pilar","Pulanglupa Uno","Pulanglupa Dos","Talon Uno","Talon Dos","Talon Tres","Talon Kuatro","Talon Singko","Zapote"],
            "Makati": ["Balingasa", "Bangkal", "Bel-Air", "Cembo", "Comembo", "Dasmariñas","East Rembo","Fort Bonifacio","Gagalangin","Guadalupe Nuevo","Guadalupe Viejo","Kasilawan","La Paz","Magallanes","North Cembo","Pitogo","Pio del Pilar","Rizal","San Antonio","San Isidro","San Lorenzo","San Pascual","Santa Cruz","Santo Domingo","South Cembo","Tejeros","Urdaneta","West Rembo"],
            "Malabon": ["Acacia", "Baritan", "Bayan-Bayanan", "Catmon", "Concepcion", "Dampalit", "Flores", "Hulong Duhat", "Ibaba", "Longos","Maysilo","Muzon","Niugan","Panghulo","San Agustin","Santulan","Tañong(Poblacion)","Tinajeros","Tonsuya","Tugatog"],
            "Mandaluyong": ["Addition Hills", "Bagong Silang", "Barangka Drive", "Barangka Ibaba", "Barangka Ilaya", "Barangka Itaas", "Buayang Bato", "Burol", "Daang Bakal", "Hagdan Bato Itaas", "Hagdan Bato Libis", "Harapin Ang Bukas", "Highway Hills","Hulo","Mabini–J.Rizal","Malamig","Mauway","Namayan","New Zañiga","Old Zañiga","Pag-Asa","Plainview","Pleasant Hills","Poblacion","San Jose","Vergara","Wack-Wack Greenhills"],
            "Manila": ["Binondo", "Ermita", "Intramuros", "Malate", "Paco", "Pandacan", "Port Area", "Quiapo", "Sampaloc", "San Andres", "San Miguel", "San Nicolas", "Santa Ana", "Santa Cruz", "Santa Mesa", "Tondo",],
            "Marikina": ["Barangka", "Calumpang", "Conception Uno", "Conception Dos", "Fortune", "Industrial Valley", "Jesus de la Peña", "Malanday", "Marikina Heights", "Nangka", "Parang", "San Roque", "Santa Elena", "Santo Niño", "Tañong", "Tumana"],
            "Muntinlupa": ["Alabang", "Ayala Alabang", "Bayan", "Buli", "Cupang", "Poblacion", "Putatan", "Sucat", "Tunasan"],
            "Navotas": ["Bagumbayan North", "Bagumbayan South", "Bangkulasi(Banculasi)", "Daanghari", "Navotas East", "Navotas West","NBBS Dagat-Dagatan (North Bay Boulevard South)","NBBS Kaunlaran (North Bay Boulevard South)","NBBS Proper (North Bay Boulevard South)","North Bay Boulevard North","San Jose(Poblacion)","San Rafael Village","San Roque","Sipac Almacen","Tangos North","Tangos South (Tañgos)","Tanza 1", "Tanza 2"],
            "Parañaque": ["Baclaran", "B.F. Homes", "Don Bosco", "Don Galo", "La Huerta", "Marcelo Green", "Merville", "Moonwalk", "San Antonio", "San Dionisio", "San Isidro", "San Martin de Porres", "Santo Niño", "Sun Valley", "Tambo", "Vitalez"],
            "Pasay": ["Apelo Cruz", "Baclaran", "Baltao", "Bay City", "Cabrera", "Cartimar", "Cuyegkeng", "Don Carlos Village", "Edang", "F.B. Harrison", "Juan Sumulong", "Kalayaan", "Leveriza", "Libertad", "Malibay", "Manila Bay Reclamation", "Marcela Marcelo", "Rivera Village", "San Pablo", "San Isidro", "San Jose", "San Rafael", "San Roque", "Santa Clara", "Santo Niño","Tramo","Tripa de Gallina","Ventanilla","Villamor"],
            "Pasig": ["Bagong Ilog", "Bagong Katipunan", "Bambang", "Buting", "Caniogan", "Dela Paz", "Kalawaan", "Kapasigan", "Kapitolyo", "Malinao", "Manggahan", "Maybunga", "Oranbo", "Palatiw", "Pinagbuhatan", "Pineda","Rosario","Sagad","San Antonio","San Joaquin","San Jose","San Miguel","San Nicolas","Santa Cruz","Santa Lucia","Santa Rosa","Santo Tomas","Santolan","Sumilang","Ugong"],
            "Pateros": ["Aguho", "Mangtanggol", "Martinez del 96", "Poblacion", "San Pedro", "San Roque", "Santa Ana", "Santo Rosario Kanluran", "Santo Rosario Silangan", "Tabacalera"],
            "Quezon": ["Bagong Pag-asa", "Bahay Toro", "Balong Bato", "Batasan Hills", "Balingasa", "Bagong Lipunan ng Crame", "Culiat", "Commonwealth", "East Kamias","Galas","Payatas","New Era","San Antonio","Santa Monica","Santo Cristo","Sto. Domingo","Tandang Sora","Veterans Village","West Kamias","Lourdes","Pinyahan","Old Balara","Camp Aguinaldo","East Rembo","San Isidro","San Jose","Sangandaan","San Bartolome","San Juan","San Francisco","San Antonio","Sto. Niño","Holy Spirit","Malaya","Libis","San Vicente","San Carlos","Sta. Lucia","Tandang Sora","Bagong Silangan","Nagkaisang Nayon","Kaligayahan","Paligsahan","San Roque","New Manila","South Triangle","North Triangle","Talayan","Matandang Balara","Bagong Silangan","San Diego","San Rafael","San Mateo","Paltok"],
            "San Juan": ["Additional Hills", "Balong-Bato", "Batis", "Corazón de Jesús(Poblacion)","Ermitaño","Greenhills","Isabelita","Kabayanan","Little Baguio","Maytunas","Onse","Pasadeña","Pedro Cruz","Progreso","Rivera","Saint Joseph(Halo-Halo)","Salapan","San Perfecto","Santa Lucia","Tibagan","West Crame"],
            "Taguig": ["Bagumbayan", "Bambang", "Calzada", "Central Bicutan","Central Signal Village","Fort Bonifacio","Hagonoy","Ibayo-Tipas","Katuparan","Ligid-Tipas","Lower Bicutan","Maharlika Village","Napindan","New Lower Bicutan","North Daang Hari","North Signal Village","Palingon","Pinagsama","San Miguel","Santa Ana","South Daang Hari","South Signal Village","Tanyag","Upper Bicutan","Ususan","Wawa","Western Bicutan"],
            "Valenzuela": ["Arkong Bato", "Bagbaguin", "Balangkas", "Bignay", "Bisig", "Canumay West", "Canumay East", "Coloong", "Dalandanan", "Gen. T. de Leon", "Isla","Karuhatan","Lawang Bato","Lingunan","Mabolo","Malanday","Malinta","Mapulang Lupa","Marulas","Maysan","Palasan","Parada","Pariancillo Villa","Paso de Blas","Pasolo","Poblacion","Polo","Punturin","Rincon","Tagalag","Ugong","Viente Reales","Wawang Pulo"]
    };

    function updateBarangays() {
        const city = document.getElementById('city').value;
        const barangaySelect = document.getElementById('barangay');
        barangaySelect.innerHTML = '<option value=""></option>'; // Clear current options
        if (barangays[city]) {
            barangays[city].forEach(barangay => {
                const option = document.createElement('option');
                option.value = barangay;
                option.textContent = barangay;
                barangaySelect.appendChild(option);
            });
        }

        // Set previously selected barangay if editing
        const selectedBarangay = "<?php echo htmlspecialchars($order_data['barangay'] ?? ''); ?>";
        if (selectedBarangay) {
            barangaySelect.value = selectedBarangay;
        }
    }

    // Call the function on page load if there's an existing city value
    window.addEventListener('load', () => {
        const existingCity = "<?php echo htmlspecialchars($order_data['city'] ?? ''); ?>";
        if (existingCity) {
            updateBarangays(); // Populate barangays for the selected city
        }
    });
</script>

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
     <center><br><br><br><br><br><br><br><br>
     <?php if (!$order_data || isset($_POST['edit'])): ?>
         
		 <form action="new3.php" method="post" class="form3">
         <fieldset>
         <legend>Delivery Location</legend><br>
         <label class="bel" for="street">Street Address</label><br>
         <input type="text" id="street" name="street" placeholder="Streeet Address" required pattern="[A-Za-z0-9\s]+" title="Please enter letters and numbers only" value="<?php echo htmlspecialchars($order_data['street'] ?? ''); ?>"><br><br>

         <label class="bel" for="province">Province</label><br>
         <input list="province-list" id="province" name="province" placeholder="Province" required value="<?php echo htmlspecialchars($order_data['province'] ?? ''); ?>">
         <datalist id="province-list">
         <option value=" National Capital Region">
         </datalist><br><br>

         <label  class="bel" for="city">City</label><br>
         <input list="city-list" id="city" name="city" placeholder="City" required onchange="updateBarangays()" value="<?php echo htmlspecialchars($order_data['city'] ?? ''); ?>">
         <datalist id="city-list">
             <option value="Caloocan">
             <option value="Las Piñas">
             <option value="Makati">
             <option value="Malabon">
             <option value="Mandaluyong">
             <option value="Manila">
             <option value="Marikina">
             <option value="Muntinlupa">
             <option value="Navotas">
             <option value="Parañaque">
             <option value="Pasay">
             <option value="Pasig">
             <option value="Pateros">
             <option value="Quezon">
             <option value="San Juan">
             <option value="Taguig">
             <option value="Valenzuela">
             </datalist><br><br>

         <label  class="bel" for="barangay">Barangay</label><br>
         <select id="barangay" name="barangay"  required>
             <option value=""></option>
         </select><br><br>

         <label class="bel" for="postalcode">Postal Code</label><br>
         <input type="text" id="postalcode" name="postalcode" placeholder="Postal Code" required pattern="\d+" title="Please enter numbers only" value="<?php echo htmlspecialchars($order_data['postalcode'] ?? ''); ?>"><br><br>
         </fieldset>
         <br><br>
             
		 <fieldset>
         <legend>Order Details</legend><br>
         <label class="bel" for="order_for">Order for whom</label><br>
         <select id="order_for" name="order_for"  required>
             <option value="Select">Select</option>
             <option value="myself" <?php echo (isset($order_data['order_for']) && $order_data['order_for'] == 'myself') ? 'selected' : ''; ?>>Myself</option>
             <option value="gift" <?php echo (isset($order_data['order_for']) && $order_data['order_for'] == 'gift') ? 'selected' : ''; ?>>A gift for someone</option>
         </select><br><br>

         <label  class="bel" for="delivery_time">When you would like this order</label><br>
         <select id="delivery_time" name="delivery_time" required>
             <option value="Select">Select</option>
             <option value="deliver_now" <?php echo (isset($order_data['delivery_time']) && $order_data['delivery_time'] == 'deliver_now') ? 'selected' : ''; ?>>Deliver now</option>
             <option value="deliver_later" <?php echo (isset($order_data['delivery_time']) && $order_data['delivery_time'] == 'deliver_later') ? 'selected' : ''; ?>>Deliver later</option>
         </select><br><br>

         <label class="bel" for="delivery_date">Select delivery date and time</label><br>
         <input type="datetime-local" id="delivery_date" name="delivery_date" required min="2024-11-04 00:00" value="<?php echo htmlspecialchars($order_data['delivery_date'] ?? ''); ?>"><br><br>
         <input type="submit" value="Proceed">
         </fieldset>
         </form>
     <?php else: ?>
			 
		 <fieldset class="form3">
         <legend>Delivery Location</legend>
             <p>Street Address: <?php echo htmlspecialchars($order_data['street']); ?></p>
             <p>Province: <?php echo htmlspecialchars($order_data['province']); ?></p>
             <p>City: <?php echo htmlspecialchars($order_data['city']); ?></p>
             <p>Barangay: <?php echo htmlspecialchars($order_data['barangay']); ?></p>
             <p>Postal Code: <?php echo htmlspecialchars($order_data['postalcode']); ?></p>
         </fieldset>
         <br><br>
			
		 <fieldset class="form3">
         <legend>Order Details</legend>
             <p>Order For: <?php echo htmlspecialchars($order_data['order_for']); ?></p>
             <p>Delivery Time: <?php echo htmlspecialchars($order_data['delivery_time']); ?></p>
             <p>Delivery Date: <?php echo htmlspecialchars($order_data['delivery_date']); ?></p>
         </fieldset>
            <br>
             
			 <form method="post" action="new3.php">
                 <input type="submit" name="delete" value="Delete Order">
             </form>
           
 		     <form method="post" action="new3.php">
                 <input type="submit" name="edit" value="Edit Order">
             </form>
     
	 <?php endif; ?>
     <br><br><br><br>
     </center>
     <br><br><br><br><br><br>

<center>
<footer>
     <div class="contact">
      <p><strong>Contact Us</strong></p>
      <p>Phone: 0916 782 3419</p>
      <p>Email: cafe.lumiere@gmail.com</p>
      <a id="fb" href="https://m.facebook.com/p/Caf%C3%A9-de-Lumi%C3%A8re-61565829464639/?wtsid=rdr_0vVetnboXopuU5v8C" target="_blank">Facebook</a>
      <p>&copy;Café de Lumière. All rights reserved.</p>	
 </div>
</footer> 
</center>
<br><br><br><br>
</body>
</html>
