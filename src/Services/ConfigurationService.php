<?php 
namespace Narolalabs\ErrorLens\Services;
use Narolalabs\ErrorLens\Models\ErrorLogConfig;

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
        $update = ErrorLogConfig::upsert([
            ['key' => 'authenticate.username', 'value' => $username],
            ['key' => 'authenticate.password', 'value' => bcrypt($password)]
        ], ['key']);
        if ($update) {
            return [true, 'Configuration has been updated successfully.'];
        }
        return [false, 'There seems to be an issue! Please try again later.'];
    }    

    /**
     * Fetch the configuration using key name
     *
     * @param array $keys
     * @return void
     */
    public function fetchConfigurationsDetail(array $keys): void
    {
        return ErrorLogConfig::whereIn('key', $keys)->pluck('value', 'key');
    }    
}
?>