<?PHP
	header("Content-Type:application/json");
	$verb = $_SERVER['REQUEST_METHOD'];
	$my_file = 'storagefile.txt';
	//decode the json string
	if (json_decode(openFile($my_file)) != null) {
		$messages = json_decode(openFile($my_file));
		$i = count($messages);
	} else {
		$messages = [];
		$i = 0;
	}

	if ($verb == "GET") {
		//gets the correct id and key
		$getMessage = $messages[$_GET['id']];
		if (isset($_GET['id']) and isset($_GET['key'])) {
			response($getMessage[0], $getMessage[1], $getMessage[2]);
			// if id is not given one must be applied
		} elseif (!isset($_GET['id']) and isset($_GET['key'])) {
			$idlist = "";
			for ($j = 0 ; $j < count($messages) ; $j++) {
				if ($messages[$j][1] == $_GET['key']) {
					$idlist = $idlist . $messages[$j][0] . ",";
				}
			}
			$idlist = substr($idlist, 0, -1);
			echo($idlist);
		} else {
			http_response_code(400);
	}
	// puts the message on the "textfile"
	} elseif ($verb == "PUT") {
			//identify the key and value to add an id
			if (isset($_GET['key']) and isset($_GET['value'])) {
				//create an array of id, key and value
				$newMessage = array($i, $_GET['key'], $_GET['value']);
				//add array to the file
				array_push($messages, $newMessage);
				writeFile($my_file, $messages);
				http_response_code(200);
				echo("chat-message stored");
				$i++;
			} else {
				http_response_code(400);
			}
		} else {
			http_response_code(400);
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
		function response($id, $key, $message) {
		header("HTTP/1.1 ");
		
		$response['id'] = $id;
		$response['key'] = $key;
		$response['message'] = $message;
		
		$json_response = json_encode($response);
		echo $json_response;
	}
	
?>