<?php

require_once __DIR__ . '/db.php';


/**
 * Add a new user
 * @param string $email user's email address
 * @param string $password plain text password
 * @param string $creditCardId credit card identifier as returned by the vault api
 * @throws Exception
 * @return number
 */
function addUser($email, $password, $creditCardId=NULL) {
	
	$conn = getConnection();
	$query = sprintf("INSERT INTO %s(email, password, creditcard_id) VALUES('%s', PASSWORD('%s'), '%s')", 
			USERS_TABLE, 
			mysqli_real_escape_string($conn,$email),
			mysqli_real_escape_string($conn,$password),
			mysqli_real_escape_string($conn,$creditCardId));
	$result = mysqli_query($conn, $query);
	if(!$result) {
		$errMsg = "Error creating user: " . mysqli_error($conn);
		mysqli_close($conn);
		throw new Exception($errMsg);
	}
	$userId = mysqli_insert_id($conn);
	mysqli_close($conn);
	
	return $userId;
}

/**
 * Validate a login attempt
 * @param string $email user's email address
 * @param string $password plain text password
 * @throws Exception
 * @return boolean
 */
function validateLogin($email, $password) {
	
	$conn = getConnection();
	$query = sprintf("SELECT COUNT(1) FROM %s WHERE email='%s' AND password=PASSWORD('%s')",
			USERS_TABLE,
			mysqli_real_escape_string($conn,$email),
			mysqli_real_escape_string($conn,$password));
	$result = mysqli_query($conn, $query);

	if(!$result) {
		$errMsg = "Error validating login: " . mysqli_error($conn);
		mysqli_close($conn);
		throw new Exception($errMsg);
	}
	$row = mysqli_fetch_row($result);
	mysqli_close($conn);
	return ($row[0] > 0);
}

/**
 * Update user record
 * @param string $email user's email address
 * @param string $newPassword
 * @param string $newCreditCardId A new credit card identifier as returned by the vault api
 * @throws Exception
 * @return boolean
 */
function updateUser($email, $newPassword, $newCreditCardId) {

	if($newPassword == NULL && $newCreditCardId == NULL) {
		return;
	}
	$conn = getConnection();
	$args = array(USERS_TABLE); $updates = array();
	if($newPassword != NULL) {
		$args[] = mysqli_real_escape_string($conn,$newPassword);
		$updates[] = "password=PASSWORD('%s')";
	}
	if($newCreditCardId != NULL) {
		$args[] = mysqli_real_escape_string($conn,$newCreditCardId);
		$updates[] = "creditcard_id='%s'";
	}
	$args[] = mysqli_real_escape_string($conn,$email);

	$query = vsprintf("UPDATE %s SET " . implode(', ', $updates) . " WHERE email='%s'", $args);	
	$result = mysqli_query($conn, $query);
	if(!$result) {
		$errMsg = "Error updating user record: " . mysqli_error($conn);
		mysqli_close($conn);
		throw new Exception($errMsg);
	}
	$isUpdated = mysqli_affected_rows($conn);
	mysqli_close($conn);
	
	return $isUpdated;
}

/**
 * Retrieve a user recod
 * @param string $email user's email address
 * @throws Exception
 * @return array
 */
function getUser($email) {
	$conn = getConnection();
	$query = sprintf("SELECT email, creditcard_id FROM %s WHERE email='%s' ",
			USERS_TABLE,
			mysqli_real_escape_string($conn,$email));
	$result = mysqli_query($conn, $query);
	if(!$result) {
		$errMsg = "Error retrieving user record: " . mysqli_error($conn);
		mysqli_close($conn);
		throw new Exception($errMsg);
	}
	$row = mysqli_fetch_assoc($result);
	mysqli_close($conn);
	
	return $row;	
}
