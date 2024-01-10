<?php

return [
	'security' => [
		'storeRequestedData' => env('SECURITY_STORE_REQUESTED_DATA', '0'),
		'confidentialFieldNames' => env('SECURITY_CONFIDENTIAL_FIELD_NAMES', 'email,password,pin,security,key,api,api_key,token,auth,authentication,authorization,pwd,pswd,pass,secret,access_code,private_key,secure_code,access_token,secure_key,passphrase,encrypted,authentication_key,secret_keyaccess_credentials,authentication_code'),
	],
	'error_preferences' => [
		'severityLevel' => env('ERROR_PREFERENCES_SEVERITY_LEVEL', '1xx,2xx,3xx,4xx,5xx'),
		'autoDeleteLog' => env('ERROR_PREFERENCES_AUTO_DELETE_LOG', '1'),
		'logDeleteAfterDays' => env('ERROR_PREFERENCES_LOG_DELETE_AFTER_DAYS', '90'),
		'showRelatedErrors' => env('ERROR_PREFERENCES_SHOW_RELATED_ERRORS', '1'),
		'showRelatedErrorsOfDays' => env('ERROR_PREFERENCES_SHOW_RELATED_ERRORS_OF_DAYS', '30'),
		'skipErrorCodes' => env('ERROR_PREFERENCES_SKIP_ERROR_CODES', '400,401,403,404,406,409')
	]
];
