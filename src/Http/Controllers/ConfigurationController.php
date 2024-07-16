<?php

namespace Narolalabs\ErrorLens\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Narolalabs\ErrorLens\Http\Requests\SecurityConfigRequest;
use Narolalabs\ErrorLens\Models\ErrorLogConfig;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class ConfigurationController extends Controller
{
    /**
     * Fetch and show the configrations
     *
     * @param Request $request
     * @return void
     */
    public function config(Request $request)
    {
        $configurations = ErrorLogConfig::where('key', 'NOT LIKE', 'authenticate.%')->pluck('value', 'key');

        $multiSelectedValues = ['security.confidentialFieldNames', 'error_preferences.severityLevel', 'error_preferences.skipErrorCodes'];
        foreach ($multiSelectedValues as $multiSelectedValue) {
            if (isset($configurations[$multiSelectedValue]) && !empty($configurations[$multiSelectedValue])) {
                // Convert comma separated string to array 
                $configurations[$multiSelectedValue] = explode(',', @$configurations[$multiSelectedValue]);
            }
        }

        return view('error-lens::config.config', compact('configurations'));
    }

    /**
     * Store the updated configurations
     *
     * @param SecurityConfigRequest $request
     * @return void
     */
    public function config_store(SecurityConfigRequest $request)
    {
        if ($request->type == 'error_preferences') {
            $data = $request->all();
            $data['logDeleteAfterDays'] = $data['logDeleteAfterDays'] ?? 1;
            $data['showRelatedErrorsOfDays'] = $data['showRelatedErrorsOfDays'] ?? 1;
            $data = collect($data)->only(['haventProductionEnv', 'customEnvName', 'autoDeleteLog', 'logDeleteAfterDays', 'showRelatedErrors', 'showRelatedErrorsOfDays', 'severityLevel', 'skipErrorCodes']);
    
            if ( ! isset($data['skipErrorCodes'])) {
                $data->put('skipErrorCodes', []);
            }
            if ( ! isset($data['severityLevel'])) {
                $data->put('severityLevel', []);
            }

            $data = $data->map(function ($value, $key) use ($request) {
                return [
                    'key' => $request->type . '.' . $key,
                    'value' => in_array($key, ['severityLevel', 'skipErrorCodes']) ? implode(',', array_filter(array_map('trim', $value))) : $value,
                ];
            })->toArray();

            $update = ErrorLogConfig::upsert($data, ['key']);
            if ($update) {
                Session::flash('error-lens-success', 'Preferences have been updated successfully.');
                $redirect = redirect()->back();
                Cache::forget('error-lens');
                \Artisan::call('cache:clear');
                \Artisan::call('config:cache');
                return $redirect;
            }

        } else if ($request->type == 'security') {
            $data = collect($request->all())->only(['storeRequestedData', 'confidentialFieldNames']);

            $data = $data->map(function ($value, $key) use ($request) {
                return [
                    'key' => $request->type . '.' . $key,
                    'value' => ($key == 'confidentialFieldNames') ? implode(',', array_filter(array_map('trim', $value))) : $value,
                ];
            })->toArray();

            $update = ErrorLogConfig::upsert($data, ['key']);
            if ($update) {
                Session::flash('error-lens-success', 'Security configurations have been updated successfully.');
                $redirect = redirect()->back();
                Cache::forget('error-lens');
                \Artisan::call('cache:clear');
                \Artisan::call('config:cache');
                return $redirect;
            }
        }
        Session::flash('error-lens-error', 'There seems to be an issue! Please try again later.');
        return redirect()->back();
    }

    /**
     * Clear the system cache
     *
     * @param Request $request
     * @return void
     */
    public function cache_clear(Request $request)
    {
        try {
             // [DeveloperNote: while we set this line after cache clear. We getting null value in session.]
            Session::flash('error-lens-success', 'The cache has been cleared successfully.');
            $redirect = redirect()->back();
            Cache::forget('error-lens');
            \Artisan::call('cache:clear');
            \Artisan::call('config:cache');
            return $redirect;
        } catch (\Throwable $e) {
            Session::flash('error-lens-error', 'There seems to be an issue! Please try again later.');
            return redirect()->back();
        }
    }
}
