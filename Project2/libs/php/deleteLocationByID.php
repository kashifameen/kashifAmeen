<?php

	// example use from browser
	// use insertDepartment.php first to create new dummy record and then specify it's id in the command below
	// http://localhost/companydirectory/libs/php/deleteDepartmentByID.php?id=<id>

	// remove next two lines for production
	
	

	$executionStartTime = microtime(true);

	include("config.php");

	header('Content-Type: application/json; charset=UTF-8');

	$conn = new mysqli($cd_host, $cd_user, $cd_password, $cd_dbname, $cd_port, $cd_socket);

	if (mysqli_connect_errno()) {
		
		$output['status']['code'] = "300";
		$output['status']['name'] = "failure";
		$output['status']['description'] = "database unavailable";
		$output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000 . " ms";
		$output['data'] = [];

		mysqli_close($conn);

		echo json_encode($output);

		exit;

	}	

	// SQL statement accepts parameters and so is prepared to avoid SQL injection.
	// $_REQUEST used for development / debugging. Remember to change to $_POST for production

	$query = $conn->prepare('SELECT * FROM department WHERE locationID = ?');
	
	$query->bind_param("i", $_REQUEST['locationID']);

	$query->execute();
    $result = $query->get_result();
	//Execute the query and store the result set
    $numRows = $result->num_rows;
    if(!$numRows){
        $query = $conn->prepare('DELETE FROM location WHERE id = ?');
        $query->bind_param("i", $_REQUEST['id']);
        $query->execute();
        $output['status']['code'] = "200";
        $output['status']['name'] = "ok";
        $output['status']['description'] = "success";
        $output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000 . " ms";
        $output['data'] = ['Deleted'];
        
	mysqli_close($conn);

	echo json_encode($output); 
    } 
	
    else {
        $output['status']['code'] = "400";
		$output['status']['name'] = "executed";
		$output['status']['description'] = "query failed";	
		$output['data'] = ['Failed'];

		mysqli_close($conn);

		echo json_encode($output); 

		exit;

	}

	

?>