<?php
namespace Narolalabs\ErrorLens\Services;

use Narolalabs\ErrorLens\Models\ErrorLogConfig;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

class ConfigurationService
{
   
    /**
     * Update or set the authentication detail to prevent 
     * the unauthorized access 
     *
     * @param string $username
     * @param string $password
     * @return array
     */
    public function updateAuthenticationDetail(string $username, string $password): array
    {
        try {
            // Specify the path to your htpasswd file
            $htpasswdFilePath = storage_path('.htpasswd');

            // Hash the password (you can use bcrypt or any other suitable hashing algorithm)
            $hashedPassword = bcrypt($password);

            // Create the htpasswd file content
            $htpasswdContent = "$username:$hashedPassword";

            // Write the content to the htpasswd file
            $update = File::put($htpasswdFilePath, $htpasswdContent);

            return ($update) ?
                [true, 'Configuration has been updated successfully.'] :
                [false, 'There seems to be an issue! Please try again later.'];
        } catch (\Throwable $e) {
            return [false, $e->getMessage()];
        }
    }

    /**
     * Fetch the authentication detail which was stored in storage/.htpasswd file
     *
     * @return void
     */
    public function fetchAuthenticationDetails(): Collection
    {
        try {
            // Specify the path to your htpasswd file
            $htpasswdFilePath = storage_path('.htpasswd');

            // Read the contents of the htpasswd file
            $htpasswdContent = File::get($htpasswdFilePath);

            // Extract username and password from the credentials
            list($username, $password) = explode(':', $htpasswdContent);

            return collect(compact('username', 'password'));
                
        } catch (\Throwable $e) {
            return collect([]);
        }
    }

    /**
     * Fetch the configuration using key name
     *
     * @param array $keys
     * @return void
     */
    public function fetchConfigurationsDetail(array $keys): Collection
    {
        return ErrorLogConfig::whereIn('key', $keys)->pluck('value', 'key');
    }    

    public function getConfigurations()
    {
        // Store configuration in cache
        if (Cache::get('error-lens')) {
            $errorLogConfigs = collect($this->flattenArray(Cache::get('error-lens')));
        } else {
            // If configuration data is not in the cache, then pick from database
            $errorLogConfigs = ErrorLogConfig::pluck('value', 'key');
            Cache::put('error-lens', $errorLogConfigs->toArray(), now()->addMinutes(10));
        }

        // Modify the key name of the config
        $errorLogConfigs = $errorLogConfigs->mapWithKeys(function ($value, $key) {
            return ['error-lens.' . $key => $value];
        })->toArray();

        // Update the configuration value
        config($errorLogConfigs);

        return $errorLogConfigs;
    }

    public function flattenArray(array $array, string $prefix = '')
    {
        $result = [];

        foreach ($array as $key => $value) {
            $newKey = $prefix . $key;

            if (is_array($value)) {
                $result = array_merge($result, $this->flattenArray($value, $newKey . '.'));
            } else {
                $result[$newKey] = $value;
            }
        }

        return $result;
    }
}
?>