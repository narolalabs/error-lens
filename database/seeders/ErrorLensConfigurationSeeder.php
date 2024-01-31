<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Narolalabs\ErrorLens\Models\ErrorLogConfig;

class ErrorLensConfigurationSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        try {
            ErrorLogConfig::upsert([
                [
                    'key' => 'error_preferences.severityLevel',
                    'value' => '1xx,2xx,3xx,4xx,5xx'
                ],
                [
                    'key' => 'error_preferences.autoDeleteLog',
                    'value' => '1'
                ],
                [
                    'key' => 'error_preferences.logDeleteAfterDays',
                    'value' => '30'
                ],
                [
                    'key' => 'error_preferences.showRelatedErrors',
                    'value' => '1'
                ],
                [
                    'key' => 'error_preferences.showRelatedErrorsOfDays',
                    'value' => '30'
                ],
                [
                    'key' => 'error_preferences.skipErrorCodes',
                    'value' => '400,401,403,404,406,409,413'
                ],
                [
                    'key' => 'security.storeRequestedData',
                    'value' => '1'
                ],
                [
                    'key' => 'security.confidentialFieldNames',
                    'value' => 'email,password,pin,security,key,api,api_key,token,auth,authentication,authorization,pwd,pswd,pass,secret,access_code,private_key,secure_code,access_token,secure_key,passphrase,encrypted,authentication_key,secret_keyaccess_credentials,authentication_code'
                ]
            ], 'key');
        } catch (\Throwable $e) {
        }
    }
}
