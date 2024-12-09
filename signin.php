	<?php
		 session_start();
		 include 'data.php';

		 // Form visibility 
		 $formToShow = "login"; 
		 $errorMessage = ""; 
		 $showEditForm = false; 

		  // Which form to show based on button clicked 
		  if ($_SERVER["REQUEST_METHOD"] == "POST") 
		  {
		  if (isset($_POST['show_form'])) 
		  {
			 $formToShow = strtolower(str_replace(' ', '_', $_POST['show_form']));
		  }

		  // User management action 
		  if (isset($_POST['action'])) 
		  {
		  if ($_POST['action'] === 'Add User') 
		  {
			  // Sign up 
			  $firstname = htmlspecialchars($_POST["firstname"]);
			  $lastname = htmlspecialchars($_POST["lastname"]);
			  $email = htmlspecialchars($_POST["email"]);
			  $password = htmlspecialchars($_POST["password"]);

			   
		 if (!preg_match("/^[a-zA-Z ]*$/", $firstname) || !preg_match("/^[a-zA-Z ]*$/", $lastname)) 
		 {
			  $errorMessage = "First name and Last name should only contain letters and spaces.";
				} 
			 else 
		  {
			 // Check if email already exists
		 if (!isset($users[$email])) 
		 {
			  $users[$email] = [
			   'firstname' => $firstname,
			   'lastname' => $lastname,
			   'email' => $email,
			   'password' => $password,
						];
						
			 file_put_contents('data.php', '<?php $users = ' . var_export($users, true) . '; ?>');

			 // Sign up successful 
			 $_SESSION['loggedin'] = true;
			 $_SESSION['email'] = $email;
			 $_SESSION['firstname'] = $firstname;
			 $_SESSION['lastname'] = $lastname;
			 $formToShow = "account"; 
		 } 
		 else 
		 {
			 $errorMessage = "This email is already registered.";
		 }
		 }
		 } 
			 elseif ($_POST['action'] === 'Log In') 
		 {
			 // Login
			 $email = htmlspecialchars($_POST["email"]);
			 $password = htmlspecialchars($_POST["password"]);

			  // Check if the user exists and the password matches
		 if (isset($users[$email]) && $users[$email]['password'] === $password) {
			 
			 // Login successful
			 $_SESSION['loggedin'] = true;
			 $_SESSION['email'] = $email;
			 $_SESSION['firstname'] = $users[$email]['firstname'];
			 $_SESSION['lastname'] = $users[$email]['lastname'];
			 $formToShow = "account"; 
		 } 
		 else 
		 {
			 $errorMessage = "Invalid email or password!";
		 }
		 } 
		 elseif ($_POST['action'] === 'Log Out') 
		 {
			 // Logout
			 session_unset();
			 session_destroy();
			 header("Location: signup.php");
			 exit();
		 } 
		 elseif ($_POST['action'] === 'Edit Account') 
		 {
			 // Edit account
			 $firstname = htmlspecialchars($_POST["firstname"]);
			 $lastname = htmlspecialchars($_POST["lastname"]);
			 $password = htmlspecialchars($_POST["password"]);
			 $email = $_SESSION['email']; 

			
		 if (!preg_match("/^[a-zA-Z ]*$/", $firstname) || !preg_match("/^[a-zA-Z ]*$/", $lastname)) 
		 {
			 $errorMessage = "First name and Last name should only contain letters and spaces.";
		 } 
		 else 
		 {
			 // Update user information
			 $users[$email]['firstname'] = $firstname;
			 $users[$email]['lastname'] = $lastname;

			 // Update password if provided
			 if (!empty($password)) {
			 $users[$email]['password'] = $password;
		 }
		 
			 file_put_contents('data.php', '<?php $users = ' . var_export($users, true) . '; ?>');

			 // Update session 
			 $_SESSION['firstname'] = $firstname;
			 $_SESSION['lastname'] = $lastname;
			 $formToShow = "account"; 
		 }
		 } 
		 elseif ($_POST['action'] === 'Delete Account') 
		 {
			 // Delete account
			 $email = $_SESSION['email'];

			 // Remove account from array
			 unset($users[$email]);
			 
			 file_put_contents('data.php', '<?php $users = ' . var_export($users, true) . '; ?>');

			 // Log out account
			 session_unset();
			 session_destroy();
			 header("Location: signup.php"); 
			 exit();
		 } 
		 elseif ($_POST['action'] === 'Show Edit Form') 
		 {
			 $showEditForm = true; 
		 }
		 }
		 }
	?>

	<!DOCTYPE HTML>
	<html>
	<head>
		 <link rel="icon" type="image/x-icon" href="l2.png">
		 <link rel="stylesheet" href="design.css">
		 <title>Account | Café de Lumière</title>
	</head>
	<body>

	<header>
			 <div class="navbar">
			 <a href="homepage.html">Home</a>
			 <a href="new 2.php">Menu</a>
			 <a href="new3.php">Order</a>
			 <a href="aboutus.html">About Us</a>
			 <a id="acc" href="signup.php">Account</a>
			 </div>
	</header>

	<br><br><br><br>
	<center>
		 <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
			
			 <fieldset class="form5">
			 <legend>Account Information</legend>
			<p>
    <img src="icon.png" id="icon" /><br>
    <input type="file" accept="image/*" id="file-input" style="display: none;" />
</p>
<button id="change-profile-button">Change Profile</button><br><br>
<button id="remove-profile-button">Remove Profile</button>

<script>
    const image = document.getElementById("icon");
    const input = document.getElementById("file-input");
    const changeButton = document.getElementById("change-profile-button");
    const removeButton = document.getElementById("remove-profile-button");

    // Load saved image on page load
    window.addEventListener("load", () => {
        const storedImageSrc = localStorage.getItem("imageSrc");
        if (storedImageSrc) {
            image.src = storedImageSrc;
            removeButton.style.display = "inline"; 
        } else {
            image.src = "icon.png"; 
            removeButton.style.display = "none"; 
        }
    });

    // Trigger file input when "Change Profile" is clicked
    changeButton.addEventListener("click", () => {
        input.click();
    });

    // Handle file selection and update image
    input.addEventListener("change", () => {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = () => {
                const base64Image = reader.result;
                image.src = base64Image; 
                removeButton.style.display = "inline";
                localStorage.setItem("imageSrc", base64Image);
                input.value = ""; // Clear input to avoid issues on re-upload
            };
            reader.readAsDataURL(file);
        }
    });

    // Handle "Remove Profile" button click
    removeButton.addEventListener("click", () => {
        localStorage.removeItem("imageSrc"); // Clear saved image
        image.src = "icon.png"; // Reset to default icon
        removeButton.style.display = "none"; // Hide remove button
    });
</script>



			 <p>First Name: <?php echo $_SESSION['firstname']; ?></p>
			 <p>Last Name: <?php echo $_SESSION['lastname']; ?></p>
			 <p>Email: <?php echo $_SESSION['email']; ?></p><br>
			 </fieldset>
			 
		
		 <!-- Edit account form -->
		 <?php if ($showEditForm): ?>
	   
			 <form method="POST" class="form1">
			 <fieldset>
			 <legend>Edit Account Information</legend><br>
			 <label class="bel">First Name</label><br>
			 <input type="text" name="firstname" placeholder="First Name"value="<?php echo $_SESSION['firstname']; ?>" required><br>
			 <label class="bel">Last Name</label><br>
			 <input type="text" name="lastname" placeholder="Last Name"value="<?php echo $_SESSION['lastname']; ?>" required><br>
			 <label class="bel">Password</label>
			 <input type="password" placeholder="Leave blank if you don't want to change"name="password">
			 <input type="hidden" name="action" value="Edit Account">
			 <input type="submit" value="Update Account"><br>
		 <br>
			 </fieldset>
			 </form>
		 <?php endif; ?>
		  
		  <!-- Button to show edit account form -->
			 <form method="POST" class="but">
			 <input type="hidden" name="action" value="Show Edit Form">
			 <input type="submit" value="Edit Account">
			 </form><br>
		
		<!-- Delete account form -->
			 <form method="POST"class="but">
			 <input type="hidden" name="action" value="Delete Account">
			 <input type="submit" value="Delete Account">
			 </form><br>
			 <form action="signup.php"class="but" method="POST">
			 <input type="hidden" name="action" value="Log Out">
			 <input type="submit" value="Log Out">
			 </form><br>

		 <?php else: ?>
		 <?php if ($formToShow === 'login'): ?>
			 <form method="POST" class="form1">
			 <fieldset>
			 <legend>Log In</legend><br>
			 <label class="bel">Email</label><br>
			 <input type="email" name="email" placeholder="Email" required><br>
			 <label class="bel">Password</label><br>
			 <input type="password" name="password" placeholder="Password" required><br>
			 <input type="hidden" name="action" value="Log In">
			 <input type="submit" value="Log In"><br>
		 <br>
			 </fieldset>
			 </form>
			 <p>Don't have an account?</p>
			
		<!-- Separate signup button -->
			 <form method="POST" style="display:inline;">
			 <input type="hidden" name="show_form" value="Add User">
			 <input type="submit" value="Sign Up">
			 </form>
		 <?php elseif ($formToShow === 'add_user'): ?>
			
			<!-- Sign up -->
			 <form method="POST" class="form1">
			 <fieldset>
			 <legend>Sign Up</legend><br>
			 <label class="bel">First Name</label><br>
			 <input type="text" name="firstname"placeholder="First Name"  required><br>
			 <label class="bel">Last Name</label><br>
			 <input type="text" name="lastname" placeholder="Last Name" required><br>
			 <label class="bel">Email</label><br>
			 <input type="email" name="email"placeholder="Email"  required><br>
			 <label class="bel">Password</label><br>
			 <input type="password" name="password" placeholder="Password" required><br>
			 <input type="hidden" name="action" value="Add User"> <!-- Changed from Sign Up -->
			 <input type="submit" value="Sign Up"><br>
		 <br>
			 </fieldset>
			 </form>
			 <p>Already have an account?</p>
		
		 <!-- Separate login button -->
			 <form method="POST" style="display:inline;">
			 <input type="hidden" name="show_form" value="login">
			 <input type="submit" value="Log In">
			 </form>
		 <?php endif; ?>
		 <?php endif; ?>

		 <!-- Error messages at the bottom -->
		 <?php if (!empty($errorMessage)): ?>
			 <p style="color:red;"><?php echo $errorMessage; ?></p>
		 <?php endif; ?>
	</center>
	</body>
	</html>

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
