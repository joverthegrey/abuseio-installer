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

        function checkStatus() {
            $.ajax({
                type: "GET",
                url: "/install/status",
                async: true,
                success: function(data) {
                    if (data.migrated && !data.seeded) {
                        console.log('Migrate succeeded');
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
                    } else if (data.migrated && data.seeded) {
                        $('#icon').removeClass('fa-gear fa-spin');
                        $('#icon').addClass('fa-check');

                        $('#button').show();
                    }
                }
            })
        }

        let timer = setInterval(checkStatus, 5000);
    </script>
@endsection

