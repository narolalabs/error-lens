<?php

return [
	'security' => [
		'storeRequestedData' => env('SECURITY_STORE_REQUESTED_DATA', '0'),
		'confidentialFieldNames' => env('SECURITY_CONFIDENTIAL_FIELD_NAMES', 'email,password,pin,security,key,api,api_key,token,auth,authentication,authorization,pwd,pswd,pass,secret,access_code,private_key,secure_code,access_token,secure_key,passphrase,encrypted,authentication_key,secret_keyaccess_credentials,authentication_code'),
	]
];
