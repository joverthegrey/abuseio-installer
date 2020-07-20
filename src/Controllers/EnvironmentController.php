<?php

namespace AbuseIO\AbuseIOInstaller\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use AbuseIO\AbuseIOInstaller\Helpers\EnvironmentManager;
use AbuseIO\AbuseIOInstaller\Events\EnvironmentSaved;
use Validator;

class EnvironmentController extends Controller
{
    /**
     * @var EnvironmentManager
     */
    protected $EnvironmentManager;

    /**
     * @param EnvironmentManager $environmentManager
     */
    public function __construct(EnvironmentManager $environmentManager)
    {
        $this->EnvironmentManager = $environmentManager;
    }

    /**
     * Display the Environment menu page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function environmentMenu()
    {
        // force the wizard
        return redirect()->route('LaravelInstaller::environmentWizard');
    }

    /**
     * Display the Environment page.
     *
     * @return \Illuminate\View\View
     */
    public function environmentWizard()
    {
        $envConfig = $this->EnvironmentManager->getEnvContent();

        return view('vendor.installer.environment-wizard',
            [
                'envConfig' => $envConfig,
                'app_url' => \Request::getSchemeAndHttpHost(),
            ]);
    }

    /**
     * Processes the newly saved environment configuration (Form Wizard).
     *
     * @param Request $request
     * @param Redirector $redirect
     * @return \Illuminate\Http\RedirectResponse
     * @throws Exception
     */
    public function saveWizard(Request $request)
    {

        $errors = new MessageBag();
        $rules = config('installer.environment.form.rules');
        $messages = [
            'environment_custom.required_if' => trans('installer_messages.environment.wizard.form.name_required'),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();
        }

        if (!$this->checkDatabaseConnection($request)) {
            $errors = $errors->merge(['database_connection' => trans('installer_messages.environment.wizard.form.db_connection_failed')]);
        }

        if ($request->input('admin_password') != $request->input('admin_password2')) {
            $errors = $errors->merge([
                    'admin_password' => trans('installer_messages.environment.wizard.form.admin_passwords_differ'),
                    'admin_password2' => trans('installer_messages.environment.wizard.form.admin_passwords_differ'),
                ]);
        }

        // check to see if we have errors, ->count() seems broken atm
        // so do it the old way
        if (count($errors->all()) > 0 ) {
            return redirect()->back()
                ->withInput()
                ->withErrors($errors);
        }

        $results = $this->EnvironmentManager->saveFileWizard($request);

        return redirect()->route('LaravelInstaller::migrate')
            ->with([
                'seed' => ($request->input('demo_data', 'off') == 'on'),
                'admin_email' => $request->input('admin_email'),
                'admin_password' => $request->input('admin_password')
            ]);
    }

    /**
     * TODO: We can remove this code if PR will be merged: https://github.com/RachidLaasri/LaravelInstaller/pull/162
     * Validate database connection with user credentials (Form Wizard).
     *
     * @param Request $request
     * @return boolean
     */
    private function checkDatabaseConnection(Request $request)
    {
        $connection = $request->input('database_connection');

        $settings = config("database.connections.$connection");

        config([
            'database' => [
                'default' => $connection,
                'connections' => [
                    $connection => array_merge($settings, [
                        'driver' => $connection,
                        'host' => $request->input('database_hostname'),
                        'port' => $request->input('database_port'),
                        'database' => $request->input('database_name'),
                        'username' => $request->input('database_username'),
                        'password' => $request->input('database_password'),
                    ]),
                ],
            ],
        ]);

        try {
            // do a select, to see if everything works
            DB::connection()->select('select now()');
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
