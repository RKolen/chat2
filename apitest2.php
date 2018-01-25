<?PHP
	header("Content-Type:application/json");
	$verb = $_SERVER['REQUEST_METHOD'];
	$my_file = 'test2.txt';
	//decode the json string
	if (json_decode(openFile($my_file)) != null) {
		$messages = json_decode(openFile($my_file));
		$i = count($messages);
	} else {
		$messages = [];
		$i = 0;
	}
		// puts the message on the "textfile"
		if ($verb == "PUT") {
			//identify the mykey and value to add an ID
			if (isset($_GET['mykey']) and isset($_GET['value'])) {
				//create an array of id, mykey and value
				$newMessage = array($i, $_GET['mykey'], $_GET['value']);
				//add array to the file
				array_push($messages, $newMessage);
				writeFile($my_file, $messages);
				http_response_code(200);
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

?>