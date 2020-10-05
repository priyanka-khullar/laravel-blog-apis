<?php

namespace App\Http;

use GuzzleHttp\Client;

class Curl
{
    static public function sendSmsViaGetRequest()
    {
     	$url = env('BASE_URL_FOR_SMS');
     	$client = new Client();

     	$queryParams = [
	    	'sender' 	=> env('SENDER_ID'),
	    	'route'		=> env('ROUTE'),
	    	'mobiles'	=> env('MOBILE_NO'),
	    	'country'	=> '91',
	    	'message'	=> 'Post Created Successfully',
	    ];

     	$params = [
		    'query' => $queryParams,
		    'headers' => [
				'authkey' => env('AUTH_KEY'),
			],
		];

		return $client->get($url, $params);
    }

    static public function sendSmsViaPostRequest()
    {
     	$url = "http://api.msg91.com/api/v2/sendsms/";
     	$client = new Client();

     	$rawData = [
	    	'sender' 	=> 'SOCKET',
	    	'route'		=> env('ROUTE'),
	    	'country'	=> '91',
	    	'sms'		=> [
		    	[
		    		'message'	=> 'SockerSender',
			    	'to'		=> [
			    		env('MOBILE_NO'),
			    	]
		    	]
	    	]
	    ];

	    $jsonData = json_encode($rawData);
     	$params = [
		    'json' => $jsonData,
		    'headers' => [
		    	'Content-Type'	=> 'application/json',
				'authkey' => env('AUTH_KEY'),
			],
		];

		return $client->post($url, $params);
    }
}
