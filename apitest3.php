<?PHP
	header("Content-Type:application/json");
	$verb = $_SERVER['REQUEST_METHOD'];
	$my_file = 'test3.txt';
	//decode the json string
	if (json_decode(openFile($my_file)) != null) {
		$messages = json_decode(openFile($my_file));
		$i = count($messages);
	} else {
		$messages = [];
		$i = 0;
	}

	if ($verb == "GET") {
		//gets the correct id and mykey
		$getMessage = $messages[$_GET['lastid']];
		if (isset($_GET['lastid']) and isset($_GET['mykey'])) {
			response($getMessage[0], $getMessage[1], $getMessage[2]);
		} else {
			http_response_code(400);
		}
	}
		
		// opens and reads the file
		function openFile($file) {
			$handle = fopen($file, 'r');
			return fread($handle,filesize($file));
		}
		//write the message into the file
		function writeFile($file, $message) {
			$message = json_encode($message);
			$handle = fopen($file, 'w');
			fwrite($handle, $message);
		}

		// response function to see response from the network
		function response($lastid, $mykey, $value) {
		header("HTTP/1.1 ");
		
		$response['value'] = $value;
		$response['lastid'] = $lastid;
		$response['key'] = $mykey;
		
		$json_response = json_encode($response);
		echo $json_response;
	}
	
?>