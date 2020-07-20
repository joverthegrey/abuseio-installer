@extends('vendor.installer.layouts.master')

@section('template_title')
    {{ trans('installer_messages.environment.wizard.templateTitle') }}
@endsection

@section('title')
    <i class="fa fa-magic fa-fw" aria-hidden="true"></i>
    {!! trans('installer_messages.environment.wizard.title') !!}
@endsection

@section('container')
    <div class="tabs tabs-full">

        <input id="tab1" type="radio" name="tabs" class="tab-input" checked/>
        <label for="tab1" class="tab-label">
            <i class="fa fa-cog fa-2x fa-fw" aria-hidden="true"></i>
            <br/>
            {{ trans('installer_messages.environment.wizard.tabs.environment') }}
        </label>

        <input id="tab2" type="radio" name="tabs" class="tab-input"/>
        <label for="tab2" class="tab-label">
            <i class="fa fa-database fa-2x fa-fw" aria-hidden="true"></i>
            <br/>
            {{ trans('installer_messages.environment.wizard.tabs.database') }}
        </label>

        <input id="tab3" type="radio" name="tabs" class="tab-input"/>
        <label for="tab3" class="tab-label">
            <i class="fa fa-envelope fa-2x fa-fw" aria-hidden="true"></i>
            <br/>
            {{ trans('installer_messages.environment.wizard.tabs.mail') }}
        </label>

        <input id="tab4" type="radio" name="tabs" class="tab-input"/>
        <label for="tab4" class="tab-label">
            <i class="fa fa-user-circle fa-2x fa-fw" aria-hidden="true"></i>
            <br/>
            {{ trans('installer_messages.environment.wizard.tabs.admin') }}
        </label>

        <form method="post" action="{{ route('LaravelInstaller::environmentSaveWizard') }}" class="tabs-wrap">
            <div class="tab" id="tab1content">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group {{ !empty($errors) && $errors->has('app_name') ? ' has-error ' : '' }}">
                    <label for="app_name">
                        {{ trans('installer_messages.environment.wizard.form.app_name_label') }}
                    </label>
                    <input type="text" name="app_name" id="app_name" value="{{ old('app_name') }}"
                           placeholder="{{ trans('installer_messages.environment.wizard.form.app_name_placeholder') }}"/>
                    @if (!empty($errors) && $errors->has('app_name'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('app_name') }}
                        </span>
                    @endif
                </div>

                <div class="form-group {{ !empty($errors) && $errors->has('demo_data') ? ' has-error ' : '' }}">
                    <label for="demo_data">
                        {{ trans('installer_messages.environment.wizard.form.demo_data_label') }}
                    </label>
                    <input type="checkbox" name="demo_data" id="demo_data" @if (old('demo_data')) checked @endif />
                    @if (!empty($errors) && $errors->has('demo_data'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('demo_data') }}
                        </span>
                    @endif
                </div>

                <div class="form-group {{ !empty($errors) && $errors->has('environment') ? ' has-error ' : '' }}">
                    <label for="environment">
                        {{ trans('installer_messages.environment.wizard.form.app_environment_label') }}
                    </label>
                    <select name="environment" id="environment" onchange='checkEnvironment(this.value);'>
                        <option value="local"
                                @if (old('environment') == 'local') selected @endif>{{ trans('installer_messages.environment.wizard.form.app_environment_label_local') }}</option>
                        <option value="development"
                                @if (old('environment') == 'development') selected @endif>{{ trans('installer_messages.environment.wizard.form.app_environment_label_development') }}</option>
                        <option value="production"
                                @if (old('environment') == 'production') selected @endif>{{ trans('installer_messages.environment.wizard.form.app_environment_label_production') }}</option>
                    </select>
                    <div id="environment_text_input" style="display: none;">
                        <input type="text" name="environment_custom" id="environment_custom"
                               value="{{ old('environment_custom') }} placeholder="{{ trans('installer_messages.environment.wizard.form.app_environment_placeholder_other') }}
                        "/>
                    </div>
                    @if (!empty($errors) && $errors->has('environment'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{$errors->first('environment') }}
                        </span>
                    @endif
                </div>

                <div class="form-group {{ !empty($errors) && $errors->has('app_debug') ? ' has-error ' : '' }}">
                    <label for="app_debug">
                        {{ trans('installer_messages.environment.wizard.form.app_debug_label') }}
                    </label>
                    <label for="app_debug_true">
                        <input type="radio" name="app_debug" id="app_debug_true" value=true
                               @if (old('app_debug') == 'true') checked @endif/>
                        {{ trans('installer_messages.environment.wizard.form.app_debug_label_true') }}
                    </label>
                    <label for="app_debug_false">
                        <input type="radio" name="app_debug" id="app_debug_false" value=false
                               @if (old('app_debug') == 'false') checked @endif/>
                        {{ trans('installer_messages.environment.wizard.form.app_debug_label_false') }}
                    </label>
                    @if (!empty($errors) && $errors->has('app_debug'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{$errors->first('app_debug') }}
                        </span>
                    @endif
                </div>

                <div class="form-group {{ !empty($errors) && $errors->has('app_log_level') ? ' has-error ' : '' }}">
                    <label for="app_log_level">
                        {{ trans('installer_messages.environment.wizard.form.app_log_level_label') }}
                    </label>
                    <select name="app_log_level" id="app_log_level">
                        <option value="debug"
                                @if (old('app_log_level') == 'debug') selected @endif>{{ trans('installer_messages.environment.wizard.form.app_log_level_label_debug') }}</option>
                        <option value="info"
                                @if (old('app_log_level') == 'info') selected @endif>{{ trans('installer_messages.environment.wizard.form.app_log_level_label_info') }}</option>
                        <option value="notice"
                                @if (old('app_log_level') == 'notice') selected @endif>{{ trans('installer_messages.environment.wizard.form.app_log_level_label_notice') }}</option>
                        <option value="warning"
                                @if (old('app_log_level') == 'warning') selected @endif>{{ trans('installer_messages.environment.wizard.form.app_log_level_label_warning') }}</option>
                        <option value="error"
                                @if (old('app_log_level') == 'error') selected @endif>{{ trans('installer_messages.environment.wizard.form.app_log_level_label_error') }}</option>
                        <option value="critical"
                                @if (old('app_log_level') == 'critical') selected @endif>{{ trans('installer_messages.environment.wizard.form.app_log_level_label_critical') }}</option>
                        <option value="alert"
                                @if (old('app_log_level') == 'alert') selected @endif>{{ trans('installer_messages.environment.wizard.form.app_log_level_label_alert') }}</option>
                        <option value="emergency"
                                @if (old('app_log_level') == 'emergency') selected @endif>{{ trans('installer_messages.environment.wizard.form.app_log_level_label_emergency') }}</option>
                    </select>
                    @if (!empty($errors) && $errors->has('app_log_level'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{$errors->first('app_log_level') }}
                        </span>
                    @endif
                </div>

                <div class="form-group {{ !empty($errors) && $errors->has('app_url') ? ' has-error ' : '' }}">
                    <label for="app_url">
                        {{ trans('installer_messages.environment.wizard.form.app_url_label') }}
                    </label>
                    <input type="url" name="app_url" id="app_url"
                           value="{{ empty(old('app_url')) ? $app_url : old('app_url') }}"
                           placeholder="{{ trans('installer_messages.environment.wizard.form.app_url_placeholder') }}"/>
                    @if (!empty($errors) && $errors->has('app_url'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{$errors->first('app_url') }}
                        </span>
                    @endif
                </div>

                <div class="buttons">
                    <button class="button" onclick="showDatabaseSettings();return false">
                        {{ trans('installer_messages.environment.wizard.form.buttons.setup_database') }}
                        <i class="fa fa-angle-right fa-fw" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
            <div class="tab" id="tab2content">

                <div class="form-group {{ !empty($errors) && $errors->has('database_connection') ? ' has-error ' : '' }}">
                    <label for="database_connection">
                        {{ trans('installer_messages.environment.wizard.form.db_connection_label') }}
                    </label>
                    <select name="database_connection" id="database_connection">
                        <option value="mysql"
                                @if (old('database_connection') == 'mysql') selected @endif>{{ trans('installer_messages.environment.wizard.form.db_connection_label_mysql') }}</option>
                        <option value="pgsql"
                                @if (old('database_connection') == 'pgsql') selected @endif>{{ trans('installer_messages.environment.wizard.form.db_connection_label_pgsql') }}</option>
                    </select>
                    @if (!empty($errors) && $errors->has('database_connection'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{$errors->first('database_connection') }}
                        </span>
                    @endif
                </div>

                <div class="form-group {{ !empty($errors) && $errors->has('database_hostname') ? ' has-error ' : '' }}">
                    <label for="database_hostname">
                        {{ trans('installer_messages.environment.wizard.form.db_host_label') }}
                    </label>
                    <input type="text" name="database_hostname" id="database_hostname"
                           value="{{ empty(old('database_hostname')) ? '127.0.0.1' : old('database_hostname') }}"
                           placeholder="{{ trans('installer_messages.environment.wizard.form.db_host_placeholder') }}"/>
                    @if (!empty($errors) && $errors->has('database_hostname'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{  $errors->first('database_hostname') }}
                        </span>
                    @endif
                </div>

                <div class="form-group {{ !empty($errors) && $errors->has('database_port') ? ' has-error ' : '' }}">
                    <label for="database_port">
                        {{ trans('installer_messages.environment.wizard.form.db_port_label') }}
                    </label>
                    <input type="number" name="database_port" id="database_port"
                           value="{{ empty(old('database_port')) ? 3306 : old('database_port') }}"
                           placeholder="{{ trans('installer_messages.environment.wizard.form.db_port_placeholder') }}"/>
                    @if (!empty($errors) && $errors->has('database_port'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('database_port') }}
                        </span>
                    @endif
                </div>

                <div class="form-group {{ !empty($errors) && $errors->has('database_name') ? ' has-error ' : '' }}">
                    <label for="database_name">
                        {{ trans('installer_messages.environment.wizard.form.db_name_label') }}
                    </label>
                    <input type="text" name="database_name" id="database_name" value="{{ old('database_name') }}"
                           placeholder="{{ trans('installer_messages.environment.wizard.form.db_name_placeholder') }}"/>
                    @if (!empty($errors) && $errors->has('database_name'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('database_name') }}
                        </span>
                    @endif
                </div>

                <div class="form-group {{ !empty($errors) && $errors->has('database_username') ? ' has-error ' : '' }}">
                    <label for="database_username">
                        {{ trans('installer_messages.environment.wizard.form.db_username_label') }}
                    </label>
                    <input type="text" name="database_username" id="database_username"
                           value="{{ old('database_username') }}"
                           placeholder="{{ trans('installer_messages.environment.wizard.form.db_username_placeholder') }}"/>
                    @if (!empty($errors) && $errors->has('database_username'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('database_username') }}
                        </span>
                    @endif
                </div>

                <div class="form-group {{ !empty($errors) && $errors->has('database_password') ? ' has-error ' : '' }}">
                    <label for="database_password">
                        {{ trans('installer_messages.environment.wizard.form.db_password_label') }}
                    </label>
                    <input type="password" name="database_password" id="database_password"
                           value="{{ old('database_password') }}"
                           placeholder="{{ trans('installer_messages.environment.wizard.form.db_password_placeholder') }}"/>
                    @if (!empty($errors) && $errors->has('database_password'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('database_password') }}
                        </span>
                    @endif
                </div>

                <div class="buttons">
                    <button class="button" onclick="showApplicationSettings();return false">
                        {{ trans('installer_messages.environment.wizard.form.buttons.setup_mail') }}
                        <i class="fa fa-angle-right fa-fw" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
            <div class="tab" id="tab3content">

                <div class="form-group {{ !empty($errors) && $errors->has('mail_host') ? ' has-error ' : '' }}">
                    <label for="mail_host">{{ trans('installer_messages.environment.wizard.form.app_tabs.mail_host_label') }}</label>
                    <input type="text" name="mail_host" id="mail_host" value="{{ empty(old('mail_host')) ? 'smtp.mailtrap.io' : old('mail_host') }}"
                           placeholder="{{ trans('installer_messages.environment.wizard.form.app_tabs.mail_host_placeholder') }}"/>
                    @if (!empty($errors) && $errors->has('mail_host'))
                        <span class="error-block">
                                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                                    {{  $errors->first('mail_host') }}
                                </span>
                    @endif
                </div>
                <div class="form-group {{ !empty($errors) && $errors->has('mail_port') ? ' has-error ' : '' }}">
                    <label for="mail_port">{{ trans('installer_messages.environment.wizard.form.app_tabs.mail_port_label') }}</label>
                    <input type="number" name="mail_port" id="mail_port" value="{{ empty(old('mail_port')) ? '2525' : old('mail_port') }}"
                           placeholder="{{ trans('installer_messages.environment.wizard.form.app_tabs.mail_port_placeholder') }}"/>
                    @if (!empty($errors) && $errors->has('mail_port'))
                        <span class="error-block">
                                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                                    {{  $errors->first('mail_port') }}
                                </span>
                    @endif
                </div>
                <div class="form-group {{ !empty($errors) && $errors->has('mail_username') ? ' has-error ' : '' }}">
                    <label for="mail_username">{{ trans('installer_messages.environment.wizard.form.app_tabs.mail_username_label') }}</label>
                    <input type="text" name="mail_username" id="mail_username" value="{{ old('mail_username') }}"
                           placeholder="{{ trans('installer_messages.environment.wizard.form.app_tabs.mail_username_placeholder') }}"/>
                    @if (!empty($errors) && $errors->has('mail_username'))
                        <span class="error-block">
                                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                                    {{  $errors->first('mail_username') }}
                                </span>
                    @endif
                </div>
                <div class="form-group {{ !empty($errors) && $errors->has('mail_password') ? ' has-error ' : '' }}">
                    <label for="mail_password">{{ trans('installer_messages.environment.wizard.form.app_tabs.mail_password_label') }}</label>
                    <input type="text" name="mail_password" id="mail_password" value="{{ old('mail_password') }}"
                           placeholder="{{ trans('installer_messages.environment.wizard.form.app_tabs.mail_password_placeholder') }}"/>
                    @if (!empty($errors) && $errors->has('mail_password'))
                        <span class="error-block">
                                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                                    {{  $errors->first('mail_password') }}
                                </span>
                    @endif
                </div>
                <div class="form-group {{ !empty($errors) && $errors->has('mail_encryption') ? ' has-error ' : '' }}">
                    <label for="mail_encryption">{{ trans('installer_messages.environment.wizard.form.app_tabs.mail_encryption_label') }}</label>
                    <input type="text" name="mail_encryption" id="mail_encryption" value="{{ old('mail_encryption') }}"
                           placeholder="{{ trans('installer_messages.environment.wizard.form.app_tabs.mail_encryption_placeholder') }}"/>
                    @if (!empty($errors) && $errors->has('mail_encryption'))
                        <span class="error-block">
                                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                                    {{  $errors->first('mail_encryption') }}
                                </span>
                    @endif
                </div>

                <div class="buttons">
                    <button class="button" onclick="showAdminSettings();return false">
                        {{ trans('installer_messages.environment.wizard.form.buttons.setup_admin') }}
                        <i class="fa fa-angle-right fa-fw" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <div class="tab" id="tab4content">

                <div class="form-group {{!empty($errors) && $errors->has('admin_email') ? ' has-error ' : '' }}">
                    <label for="admin_email">
                        {{ trans('installer_messages.environment.wizard.form.admin_email') }}
                    </label>
                    <input type="text" name="admin_email" id="admin_email"
                           value="{{ empty(old('admin_email')) ? 'admin@isp.local' : old('admin_email') }}"/>

                    @if (!empty($errors) && $errors->has('admin_email'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{  $errors->first('admin_email') }}
                        </span>
                    @endif
                </div>

                <div class="form-group {{ !empty($errors) && $errors->has('admin_password') ? ' has-error ' : '' }}">
                    <label for="admin_password">
                        {{ trans('installer_messages.environment.wizard.form.admin_password') }}
                    </label>
                    <input type="password" name="admin_password" id="admin_password"
                           placeholder="{{ trans('installer_messages.environment.wizard.form.admin_password_placeholder') }}"/>

                    @if (!empty($errors) && $errors->has('admin_password'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{  $errors->first('admin_password') }}
                        </span>
                    @endif
                </div>

                <div class="form-group {{ !empty($errors) && $errors->has('admin_password2') ? ' has-error ' : '' }}">
                    <label for="admin_password2">
                        {{ trans('installer_messages.environment.wizard.form.admin_password_repeat') }}
                    </label>
                    <input type="password" name="admin_password2" id="admin_password2"
                           placeholder="{{ trans('installer_messages.environment.wizard.form.admin_password_placeholder') }}"/>

                    @if (!empty($errors) && $errors->has('admin_password2'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{  $errors->first('admin_password2') }}
                        </span>
                    @endif
                </div>

                <div class="buttons">
                    <button class="button" type="submit">
                        {{ trans('installer_messages.environment.wizard.form.buttons.install') }}
                        <i class="fa fa-angle-right fa-fw" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </form>

    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        function checkEnvironment(val) {
            var element = document.getElementById('environment_text_input');
            if (val == 'other') {
                element.style.display = 'block';
            } else {
                element.style.display = 'none';
            }
        }

        function showDatabaseSettings() {
            document.getElementById('tab2').checked = true;
        }

        function showApplicationSettings() {
            document.getElementById('tab3').checked = true;
        }

        function showAdminSettings() {
            document.getElementById('tab4').checked = true;
        }
    </script>
@endsection
