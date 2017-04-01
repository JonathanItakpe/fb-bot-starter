<?php

namespace App\Controllers;

use App\Services\FacebookService;

/**
* IndexController
*/
class IndexController
{
	public function verify()
	{
		$hub_mode = array_get($_GET, 'hub_mode');
		$hub_verify_token = array_get($_GET, 'hub_verify_token');
		$hub_challenge = array_get($_GET, 'hub_challenge');

		if ($hub_mode === 'subscribe' && $hub_verify_token === VALIDATION_TOKEN) {
		    echo $hub_challenge;
		    http_response_code(200); 
		} else {
		    echo("Failed validation. Make sure the validation tokens match.");
		    http_response_code(403);         
		}  
	}

	public function index()
	{
		// Indicate that we recieved the message
		echo("success");
		http_response_code(200); 

		// New FacebookService Instance
		$bot = new FacebookService(PAGE_ACCESS_TOKEN);

		// Continue
		$data = json_decode(file_get_contents("php://input"), true, 512, JSON_BIGINT_AS_STRING);

		if (!empty($data['entry'][0]['messaging'])) {
	        foreach ($data['entry'][0]['messaging'] as $message) {
	            // Skipping delivery messages
	            if (!empty($message['delivery'])) {
	                continue;
	            }
	            
	            // skip the echo of my own messages
	            if (($message['message']['is_echo'] == "true")) {
	                continue;
	            }
	            
	            $command = "";
	            // When bot receive message from user
	            if (!empty($message['message'])) {
	                $command = $message['message']['text'];

	            // When bot receive button click from user
	            } else if (!empty($message['postback'])) {
	                $command = $message['postback']['payload'];
	            }

	            // Handle command
	            switch ($command) {
	                // When bot receive "hello"
	                case 'hello':
	                	$data = [
				            'recipient' => [ 'id' => $message['sender']['id'] ],
				            'message' => [
				                'text' => 'Hi!!'
				        	]];
	                    $bot->send($data);
	                    break;
	                default:
		                $data = [
					            'recipient' => [ 'id' => $message['sender']['id'] ],
					            'message' => [
					                'text' => $message['message']['text']
					        ]];
	                	$bot->send($data);
	                    break;
	            }
	        }
	    }
	}
}


