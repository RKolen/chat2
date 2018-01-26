
var xhr = new XMLHttpRequest();
var key = "raymond";
// for the retrieve messages function change 0 to avoid seeing really old messages
var highestId = 0;

$(function() {
	var messageScreen = document.getElementById("chat-messages");
	// attach username to submit and start the session
	$("#textbox").keypress(function(event) {
		if(event.which == 13) {	
		//looks for enter key
			if ( $("#enter").prop("checked") ) {
				// looks if submit on enter is checked
				//console.log("enter pressed, checkbox is checked");
				$("#send").click();
				event.preventDefault();
			}
		}
	});

	// function sends message to the server and displays in chat-messages
	$("#send").click(function() {
		//user name added to message
		var userID = $(".chatname").val();
		var allMessage = $("#chat-messages").html();
		var newMessage = $("#textbox").val();
		//console.log(userID);
		// username = "<span class = 'username' style='color:red'>" + userID + "</span> <span class = 'username'> says: </span>";
		username =  userID + " says:";
		//function to clear textbox after sending message
		$("#textbox").val("");
		//sending to server username+message
		xhr.open("PUT", "apitier2.php?&key=" + key + "&value=" + username + newMessage, false);
		xhr.send ();
		//show all messages
		$("#chat-messages").html(allMessage);
		//scrolls down on send automatically
		scrollToBottom();
	});

	//scrolls to the bottom of the page
	function scrollToBottom() {
		$("#chat-messages").scrollTop($("#chat-messages").prop("scrollHeight"));
	}

	//retrieves from the server
	function grabMessageById(id) {
		xhr.open("GET", "apitier2.php?key=" + key + "&id=" + id, false);
		xhr.send();
		return xhr.response;
	}

	// gets the old messages from server
	function getCorrectIds() {
		xhr.open("GET", "apitier2.php?key=" + key, false);
		xhr.send();
		correctids = xhr.response;
		//splits array of substrings
		correctids = correctids.split(",");
		// gets the id`s used by the specific key
		for (i = 0 ; i < correctids.length; i++) {
			correctids[i] = parseInt(correctids[i]);
		}
	}

	//function to refresh and show old messages
	function refreshChat() {
		var messageScreen = document.getElementById("chat-messages");
			for (i = 0; i < correctids.length; i++) {
				if (correctids[i] > highestId) {
					grabMessageById(correctids[i]);
					var messageId = correctids[i];
					var newMessage = JSON.parse(grabMessageById(messageId)).message;
					//var newMessage = xhr.response;
					//line break for messages
					messageScreen.innerHTML += newMessage + "<br>";
					// to see latest messages
					scrollToBottom("chat-messages");
					highestId = correctids[i];
				}
			}
	}

	//refreshes window
	window.setInterval(function() {
		getCorrectIds();
		refreshChat();
	}, 3000);
		
});
