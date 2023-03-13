<?php

require_once __DIR__ . '/db.php';

/**
 * Create a new order
 * @param string $userId Buyer's user id
 * @param string $paymentId payment id returned by paypal
 * @param string $state state of this order
 * @param string $amount payment amount in DD.DD format
 * @param string $description a description about this payment
 * @throws Exception
 */
function addOrder($userId, $paymentId, $state, $amount, $description) {
	$conn = getConnection();
	$query = sprintf("INSERT INTO %s(user_id, payment_id, state, amount, description, created_time) 
			VALUES('%s', '%s', '%s', '%s', '%s', NOW())",
			ORDERS_TABLE,
			mysqli_real_escape_string($conn,$userId),
			mysqli_real_escape_string($conn,$paymentId),
			mysqli_real_escape_string($conn,$state),
			mysqli_real_escape_string($conn,$amount),
			mysqli_real_escape_string($conn,$description));
	$result = mysqli_query($conn, $query);
	if(!$result) {
		$errMsg = "Error creating new order: " . mysqli_error($conn);
		mysqli_close($conn);
		throw new Exception($errMsg);
	}
	$orderId = mysqli_insert_id($conn);
	mysqli_close($conn);
	
	return $orderId;
}

/**
 * Update a previously created order.
 * 
 * @param int $orderId
 * @param string $state
 * @param string $paymentId
 * @throws Exception
 * @return number
 */
function updateOrder($orderId, $state, $paymentId=NULL) {
	$conn = getConnection();
	$args = array(ORDERS_TABLE, mysqli_real_escape_string($conn,$state));
	 $updates = array("state='%s'");
	
	if($paymentId != NULL) {
		$args[] = mysqli_real_escape_string($conn,$paymentId);
		$updates[] = "payment_id='%s'";
	}
	$args[] = $orderId;
		
	$query = vsprintf("UPDATE %s SET " . implode(', ', $updates) . " WHERE order_id='%s'", $args);		
	$result = mysqli_query($conn, $query);
	if(!$result) {
		$errMsg = "Error updating order record: " . mysqli_error($conn);
		mysqli_close($conn);
		throw new Exception($errMsg);
	}
	$isUpdated = mysqli_affected_rows($conn);
	mysqli_close($conn);
	
	return $isUpdated;
}

/**
 * Retrieve orders created by this buyer
 * @param string $email
 * @throws Exception
 * @return array
 */
function getOrders($email) {
	$conn = getConnection();
	$query = sprintf("SELECT * FROM %s WHERE user_id='%s' ORDER BY created_time DESC",
			ORDERS_TABLE,
			mysqli_real_escape_string($conn,$email));
	$result = mysqli_query($conn, $query);
	if(!$result) {
		$errMsg = "Error retrieving orders: " . mysqli_error($conn);
		mysqli_close($conn);
		throw new Exception($errMsg);
	}
	
	$rows = array();	
	while(($row = mysqli_fetch_assoc($result))) {
		$rows[] = $row;
	}	
	mysqli_close($conn);
	return $rows;
}


function getOrder($orderId) {
	$conn = getConnection();
	$query = sprintf("SELECT * FROM %s WHERE order_id='%d'",
			ORDERS_TABLE,
			mysqli_real_escape_string($conn,$orderId));
	$result = mysqli_query($conn, $query);
	if(!$result) {
		$errMsg = "Error retrieving order: " . mysqli_error($conn);
		mysqli_close($conn);
		throw new Exception($errMsg);
	}

	$row = mysqli_fetch_assoc($result);
	mysqli_close($conn);
	return $row;
}