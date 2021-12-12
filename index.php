<?php
session_start();
require_once "dbconnect.php";
$username = $email = $pass = $rpass = $desc = $priority = $category = "";
if(isset($_POST['formname'])){
	if($_POST['formname']=="report"){

		if($_SERVER['REQUEST_METHOD']=="POST"&&isset($_POST['formname'])=="report"){
			$username = $_POST['username'];
			$email = $_POST['email'];
			$pass = $_POST['pass'];
			$rpass = $_POST['rpass'];
			$desc = $_POST['description'];
			$category = $_POST['category'];
			$priority = $_POST['range'];

			if(!empty($username)&&!empty($email)&&!empty($pass)&&!empty($rpass)&&!empty($desc)&&!empty($category)&&!empty($priority)){

				$sqlcheckemail = "SELECT * FROM authorization WHERE username = '$username'";
				if($rescheckemail = mysqli_query($link, $sqlcheckemail)){
					if(mysqli_num_rows($rescheckemail)>0){
						//if email is detected
						$dt = date('y-m-d h:i:s', time());
						$sqladdincident = "INSERT INTO incident (number, username, priority, category, incident_description, date_time)
						VALUES (NULL, '$username', '$priority', '$category', '$desc', '$dt')";
						if($resaddincident = mysqli_query($link, $sqladdincident)){
							header('Location: index2.php');
						}
					} else {
						//if email not exists


						$sqladdmail = "INSERT INTO authorization (username, password, email_address) VALUES ('$username', '$pass', '$email')";

						if($resadd = mysqli_query($link, $sqladdmail)){

							$dt = date('y-m-d h:i:s', time());
							$sqladdincident = "INSERT INTO incident (number, username, priority, category, incident_description, date_time)
							VALUES (NULL, '$username', '$priority', '$category', '$desc', '$dt')";
							if($resaddincident = mysqli_query($link, $sqladdincident)){
								header('Location: index2.php');
							}
						}
					}
				}
			}
		}
	}
}
if(isset($_POST['formname'])){
	if($_POST['formname']=="load"){
		if($_SERVER['REQUEST_METHOD']=="POST"&&isset($_POST['formname'])=="load"){
			$username = $_POST['username'];
			$email = $_POST['email'];
			$pass = $_POST['pass'];
			$rpass = $_POST['rpass'];
			$checkuser = "SELECT * FROM authorization WHERE username = '$username' AND password = '$pass' AND email_address = '$email'";

			if($resuser = mysqli_query($link, $checkuser)){

				if(mysqli_num_rows($resuser)>0){
						header ("location: loadreports.php?user=$username");
				}
			}
		}
	}
}

?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>PHP Simple Project</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="stylesheet" href="assets/css/drop.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
		<style>

.deletestyle {
	height: 100%;
	width: 0;
	position: fixed;
	z-index: 1;
	top: 0;
	left: 0;
	background-color: rgb(0,0,0);
	background-color: rgba(0,0,0, 0.9);
	overflow-x: hidden;
	transition: 0.5s;
}

.deletestyle-content {
position: relative;
top: 25%;
display: flex;
width: 100%;
text-align: center;
margin-top: 30px;
justify-content: center;
}


.overlay {
height: 100%;
width: 0;
position: fixed;
z-index: 1;
top: 0;
left: 0;
background-color: rgb(0,0,0);
background-color: rgba(0,0,0, 0.9);
overflow-x: hidden;
transition: 0.5s;
}

.overlay-content {
position: relative;
top: 25%;
display: flex;
width: 100%;
text-align: center;
margin-top: 30px;
justify-content: center;
}

.overlay a {
padding: 8px;
text-decoration: none;
font-size: 36px;
color: #818181;
display: block;
transition: 0.3s;
}

.overlay a:hover, .overlay a:focus {
color: #f1f1f1;
}

.overlay .closebtn {
position: absolute;
top: 20px;
right: 45px;
font-size: 60px;
}

		</style>


	</head>
	<body style="padding: 10px;">




		<!-- Header -->
		<!-- Main -->
<script>
var sps = 0;
var lps = 0;
	function reportform() {
		if(sps == 0){
			$(".incident_send").show();
			$(".incident_load").hide();
			$(".intro").hide();
			sps = 1;
			lps = 0;
		} else {
			$(".incident_send").hide();
			$(".incident_load").hide();
			$(".intro").show();
			sps = 0;
			lps = 0;
		}
	}
	function loadform() {
		if(lps == 0){
			$(".incident_send").hide();
			$(".incident_load").show();
			$(".intro").hide();
			sps = 0;
			lps = 1;
		} else {
			$(".incident_send").hide();
			$(".incident_load").hide();
			$(".intro").show();
			sps = 0;
			lps = 0;
		}
	}
	function formvalidation() {
		var formEl = document.forms.report;
		var formData = new FormData(formEl);
		var username = formData.get('username');
		var pass = formData.get('pass');
		var rpass = formData.get('rpass');
		var email = formData.get('email');
		var desc = formData.get('description');
		var cat = formData.get('category');
		var prior = formData.get('range');
		if(username==""){
			alert("Please fill in the username");
			return false;
		} else {
			if(!/^[a-z0-9_\.]+$/.exec(username)){
				alert('username not acceptable. Only 0-9 and a-z lowercase are acceptable.');
				return false;
			}
		}
		if(pass==""){
			alert("Please enter the password");
			return false;
		}
		if(rpass==""){
			alert("Please enter the confirmation password");
			return false;
		}
		if(pass!=""&&rpass!=""){
			if(pass.localeCompare(rpass)){
				alert("Passwords do not match. Please re-enter!");
				return false;
			}
		}
		if(email==""){
			alert("Please enter the email address.");
			return false;
		} else {
			if(!/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.exec(email)){
				alert('Invalid email address');
				return false;
			}
		}
		if(desc==""){
			alert("Please enter the description of incident");
			return false;
		}
		if(cat=="0"){
			alert("Please select the category of incident");
			return false;
		}
	}

	function formvalidation2() {
		var formEl = document.forms.load;
		var formData = new FormData(formEl);
		var username = formData.get('username');
		var pass = formData.get('pass');
		var rpass = formData.get('rpass');
		var email = formData.get('email');

		if(username==""){
			alert("Please fill in the username");
			return false;
		} else {
			if(!/^[a-z0-9_\.]+$/.exec(username)){
				alert('username not acceptable. Only 0-9 and a-z lowercase are acceptable.');
				return false;
			}
		}
		if(pass==""){
			alert("Please enter the password");
			return false;
		}
		if(rpass==""){
			alert("Please enter the confirmation password");
			return false;
		}
		if(pass!=""&&rpass!=""){
			if(pass.localeCompare(rpass)){
				alert("Passwords do not match. Please re-enter!");
				return false;
			}
		}
		if(email==""){
			alert("Please enter the email address.");
			return false;
		} else {
			if(!/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.exec(email)){
				alert('Invalid email address');
				return false;
			}
		}

	}
</script>

		<section id="main" class="wrapper"  style="padding-top:10px; padding-bottom:0px;">
			<div class="container" style="width:98% !important; padding:0px; padding-top:0px;">
				<h2>INCIDENT REPORTING PORTAL DEMO</h2>
				<button style="color: white !important;" onclick="reportform();">Report Incident</button>
				<button style="color: white !important;" onclick="loadform();">Retrieve Incident</button>
				<div class="incident_send" id="reportform" style="border-radius: 15px; border: 2px solid black; padding: 3px; margin: 3px; width: 60%; display: none;">
					<h3>Report Incident</h3>
					<form id="report" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" onsubmit="return formvalidation();">
						<label for="username">username</label>
						<input type="text" name="username" id="username" placeholder="Username" value="<?php if(!empty($username)) { echo $username; }?>">
						<label for="pass">Password</label>
						<input type="Password" name="pass" id="pass"  placeholder="Password" value="<?php if(!empty($pass)) { echo $pass; }?>">
						<label for="rpass">Confirm Password</label>
						<input type="Password" name="rpass" id="rpass"  placeholder="Confirm Password" value="<?php if(!empty($rpass)) { echo $rpass; }?>">
						<label for="email">Email Address</label>
						<input type="email" name="email" id="email"  placeholder="Email" value="<?php if(!empty($email)) { echo $email; }?>">
						<label for="description">Incident Description</label>
						<input type="text" name="description" id="description"  placeholder="Description of Incident" value="<?php if(!empty($desc)) { echo $desc; }?>">
						<label for="category">Category</label>
						<select name="category" id="category">
							<option value="0" <?php if($category=="0") { echo 'selected'; } ?>>---Select---</option>
							<option value="Computer Related" <?php if($category=="Computer Related") { echo 'selected'; } ?>>Computer Related</option>
							<option value="Password Related" <?php if($category=="Password Related") { echo 'selected'; } ?>>Password Related</option>
							<option value="DB Related" <?php if($category=="DB Related") { echo 'selected'; } ?>>DB Related</option>
							<option value="Hardware Related" <?php if($category=="Hardware Related") { echo 'selected'; } ?>>Hardware Related</option>
							<option value="AFS Related" <?php if($category=="AFS Related") { echo 'selected'; } ?>>AFS Related</option>
							<option value="Other" <?php if($category=="Other") { echo 'selected'; } ?>>Any Other type</option>
						</select>
						<label for="range">Priority</label>
						<input type="range" min="1" max="3" value="<?php if(!empty($priority)){ echo $priority; } else {echo '2'; }?>"  id="range" name="range">
						<hr>
						<input type="hidden" name="formname" value="report">
						<input style="color: white !important; background: #34e0a1;" type="submit" name="submitreport" value="Submit Report">
						<input style="color: white !important; background: #34e0a1;" type="button" value="Reset">

					</form>
				</div>
				<div class="incident_load"  style="border-radius: 15px; border: 2px solid black; padding: 3px; margin: 3px; width: 60%; display: none;">
					<h3>Retrieve Incident</h3>
					<form id="load" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" onsubmit="return formvalidation2();">
						<label for="username">username</label>
						<input type="text" name="username" id="username" placeholder="Username" value="<?php if(!empty($username)) { echo $username; }?>">
						<label for="pass">Password</label>
						<input type="Password" name="pass" id="pass"  placeholder="Password" value="<?php if(!empty($pass)) { echo $pass; }?>">
						<label for="rpass">Confirm Password</label>
						<input type="Password" name="rpass" id="rpass"  placeholder="Confirm Password" value="<?php if(!empty($rpass)) { echo $rpass; }?>">
						<label for="email">Email Address</label>
						<input type="email" name="email" id="email"  placeholder="Email" value="<?php if(!empty($email)) { echo $email; }?>">
						<hr>
						<input type="hidden" name="formname" value="load">
						<input style="color: white !important; background: #34e0a1;" type="submit" name="loadreport" value="Retrieve Incident">
						<input style="color: white !important; background: #34e0a1;" type="button" value="Reset">
					</form>
				</div>
				<div class="intro">
					<hr>
					<h3>Intro</h3>
					<p>This Application demonstrates the basics of PHP implementation.</p>
				</div>
				<span>
					<i><u>This application is made by SHAFAT.<i><u>
				</span>

</div>

			</section>

		<!-- Footer -->


		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>
