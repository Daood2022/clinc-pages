<?php
session_start();
// variable declaration
$username = "";
$email    = "";
$errors = array();
$_SESSION['success'] = "";

// connect to database
$db = mysqli_connect('localhost', 'root', '', 'clinc');

// Add a Reservation
if (isset($_POST['reservation'])) {
	// receive all input values from the form
	$name = mysqli_real_escape_string($db, $_POST['name']);
	$email = mysqli_real_escape_string($db, $_POST['email']);
	$phone = mysqli_real_escape_string($db, $_POST['phone']);
	$doctor = mysqli_real_escape_string($db, $_POST['doctor']);
	$date = mysqli_real_escape_string($db, $_POST['date']);

	// form validation: ensure that the form is correctly filled
	if (empty($name)) {
		array_push($errors, "name is required");
	}
	if (empty($email)) {
		array_push($errors, "Email is required");
	}
	if (empty($phone)) {
		array_push($errors, "phone is required");
	}
	if (empty($doctor)) {
		array_push($errors, "doctor is required");
	}
	if (empty($date)) {
		array_push($errors, "date is required");
	}


	// Reservation if there are no errors in the form
	if (count($errors) == 0) {
		$password = md5($password_1); //encrypt the password before saving in the database
		$query = "INSERT INTO reservation (name,email,phone,doctor,date) 
						  VALUES('$name', '$email', '$phone','$doctor'.'$date')";
		mysqli_query($db, $query);

		// $_SESSION['username'] = $username;
		$_SESSION['success'] = "تم الحجز بنجاح";
		header('location: index.php');
	}
}
