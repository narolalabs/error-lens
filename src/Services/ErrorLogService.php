<?php 
namespace Narolalabs\ErrorLens\Services;
use Narolalabs\ErrorLens\Models\ErrorLog;

class ErrorLogService {
    private $configurationService;

    public function __construct(ConfigurationService $configurationService) {
        $this->configurationService = $configurationService;
    }
    
    public function autoRemoveErrorLogs()
    {
        $autoRemoveLogsConfigs = $this->configurationService->fetchConfigurationsDetail(['error_preferences.logDeleteAfterDays', 'error_preferences.autoDeleteLog']);

        if (isset($autoRemoveLogsConfigs['error_preferences.autoDeleteLog']) && $autoRemoveLogsConfigs['error_preferences.autoDeleteLog'] == '1') {
            $days = (isset($autoRemoveLogsConfigs['error_preferences.logDeleteAfterDays'])) ? $autoRemoveLogsConfigs['error_preferences.logDeleteAfterDays'] : 365;

            $dateUpto = date('Y-m-d', strtotime("today - $days days"));

            $removeErrors = ErrorLog::whereDate('created_at', '<=', $dateUpto)->delete();
            if ($removeErrors) {
                \Log::info("Error logs removed successfully upto date $dateUpto");
            }
        }
    }
}

?>