<?php

namespace AbuseIO\AbuseIOInstaller\Helpers;

use Exception;
use Illuminate\Http\Request;

class EnvironmentManager
{
    /**
     * @var string
     */
    private $envPath;

    /**
     * @var string
     */
    private $envExamplePath;

    /**
     * Set the .env and .env.example paths.
     */
    public function __construct()
    {
        $this->envPath = base_path('.env');
        $this->envExamplePath = base_path('.env.example');
    }

    /**
     * Get the content of the .env file.
     * If the .env doesn't exist, create it and return the empty file.
     *
     * @param bool $example
     *   use the example env file
     * @return string
     */
    public function getEnvContent($example = false)
    {
        $filePath = $this->envPath;
        if ($example) {
            $filePath = $this->envExamplePath;
        }

        if (!file_exists($filePath)) {
            touch($this->envPath);
        }

        return file_get_contents($filePath);
    }

    /**
     * Get the the .env file path.
     *
     * @return string
     */
    public function getEnvPath() {
        return $this->envPath;
    }

    /**
     * Get the the .env.example file path.
     *
     * @return string
     */
    public function getEnvExamplePath() {
        return $this->envExamplePath;
    }

    /**
     * Save the edited content to the .env file.
     *
     * @param Request $input
     * @return string
     */
    public function saveFileClassic(Request $input)
    {
        $message = trans('installer_messages.environment.success');

        try {
            file_put_contents($this->envPath, $input->get('envConfig'));
        }
        catch(Exception $e) {
            $message = trans('installer_messages.environment.errors');
        }

        return $message;
    }

    /**
     * Save the form content to the .env file.
     *
     * @param Request $request
     * @return string
     * @throws Exception
     */
    public function saveFileWizard(Request $request)
    {
        $results = trans('installer_messages.environment.success');

        $envFileData =
        'APP_NAME=\'' . $request->app_name . "'\n" .
        'APP_ENV=' . $request->environment . "\n" .
        'APP_KEY=' . bin2hex(random_bytes(16)) . "\n" .
        'APP_DEBUG=' . $request->app_debug . "\n" .
        'APP_LOG_LEVEL=' . $request->app_log_level . "\n" .
        'APP_URL=' . $request->app_url . "\n" .
        "APP_INSTALLED=true\n\n" .
        'DB_CONNECTION=' . $request->database_connection . "\n" .
        'DB_HOST=' . $request->database_hostname . "\n" .
        'DB_PORT=' . $request->database_port . "\n" .
        'DB_DATABASE=' . $request->database_name . "\n" .
        'DB_USERNAME=' . $request->database_username . "\n" .
        'DB_PASSWORD=' . $request->database_password . "\n\n" .
        "CACHE_DRIVER=file\n".
        "SESSION_DRIVER=file\n".
        "QUEUE_DRIVER=database\n\n".
        "MAIL_DRIVER=smtp\n" .
        'MAIL_HOST=' . $request->mail_host . "\n" .
        'MAIL_PORT=' . $request->mail_port . "\n" .
        'MAIL_USERNAME=' . $request->mail_username . "\n" .
        'MAIL_PASSWORD=' . $request->mail_password . "\n" .
        'MAIL_ENCRYPTION=' . $request->mail_encryption . "\n\n" .
        'GDPR_ANONYMIZE_DOMAIN=' . $request->gdpr_domain . "\n\n" .
        '';

        try {
            file_put_contents($this->envPath, $envFileData);

        }
        catch(Exception $e) {
            $results = trans('installer_messages.environment.errors');
        }

        return $results;
    }
}
