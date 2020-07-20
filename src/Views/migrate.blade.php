@extends('vendor.installer.layouts.master')

@section('template_title')
    {{ trans('installer_messages.migrate.templateTitle') }}
@endsection

@section('title')
    {{ trans('installer_messages.migrate.title') }}
@endsection

@section('container')

    <p style="font-size: 72px; text-align: center"><i id="icon" class="fa fa-gear fa-spin"></i></p>

    <div class="buttons">
        <a href="{{ route('LaravelInstaller::final') }}" id="button" class="button">{{ trans('installer_messages.final.next') }}</a>
    </div>

@endsection

@section('scripts')
    <script>
        $('#button').hide();
        var seed = {{ session('seed') ? 1 : 0 }};
        var status = 'migrating';
        var timer = setInterval(checkStatus, 5000);

        function checkStatus() {
            $.ajax({
                type: "GET",
                url: "/install/status",
                async: true,
                success: function(data) {
                    if (data.migrated && !data.seeded && status != 'seeding' && status != 'adding' && seed) {
                        console.log('Migrate succeeded');
                        status = 'seeding';
                        // migrated and not seeded, fire seed call
                        $('#header_title').text("{{ trans('installer_messages.migrate.seeding') }}");
                        $.ajax({
                            type: "GET",
                            url: "/install/seed",
                            async: true,
                            success: function() {
                                console.log('Seed succeeded');
                            }
                        });
                    } else if (data.migrated && (data.seeded || !seed) && status != 'adding') {
                        status = 'adding';
                        $('#header_title').text("{{ trans('installer_messages.migrate.adding') }}");
                        $.ajax({
                            type: "GET",
                            url: "/install/add_admin",
                            async: true,
                            success: function() {
                                console.log('Creating admin call succeeded');
                            }
                        });
                    } else if (data.migrated && (data.seeded || !seed) && data.admin_created) {
                        // implement adding call
                        $('#icon').removeClass('fa-gear fa-spin');
                        $('#icon').addClass('fa-check');

                        $('#button').show();
                        clearInterval(timer);
                    }
                }
            })
        }

    </script>
@endsection

