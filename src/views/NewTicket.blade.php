<head>
    <title>{{ __('Help Support') }}</title>
    {{--     {{ Config::get('helpsupport.base_url') }} --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('Helpsupport/src/assets/css/styles.css') }}">



    <link id="style" href="{{ asset('plugins/css/bootstrap.min.css') }}" rel="stylesheet" />

    <!-- STYLE CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/helpsupport/css/style.css') }}">
    <link href="{{ asset('vendor/helpsupport/css/dark-style.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/helpsupport/css/transparent-style.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/helpsupport/css/skin-modes.css') }}" rel="stylesheet" />

    <!-- FONT-ICONS CSS -->
    <link href="{{ asset('vendor/helpsupport/css/icons.css') }}" rel="stylesheet" />

    <!-- COLOR SKIN CSS -->
    <link id="theme" rel="stylesheet" type="text/css" media="all" href="{{ asset('vendor/helpsupport/colors/color1.css') }}" />
</head>

<!-- Page-Header -->

<body>
    <div class="container">
        <div class='page-header'>
            <div>
                <ol class='breadcrumb'>
                    <li class='breadcrumb-item active' aria-current='page'>{{ __('Submit New Ticket ') }}</li>
                    <li class='breadcrumb-item active' aria-current='page'><a href="{{ route('MytTickets') }}">{{ __('Help & Support ') }}</a></li>


                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('New Ticket') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="card">
                                    <div class="card-header">
                                    </div>
                                    <div class="card-body">
                                        <form id="TicketForm" name="TicketForm" method="post" class="form-horizontal" action="{{ route('storeNewTicket') }}" enctype="multipart/form-data">
                                            @csrf

                                            <input type="hidden" name="client_id" id="client_id" value="{{ Config::get('helpsupport.client_id') }}">
                                            <input type="hidden" name="project_id" id="project_id" value="{{ Config::get('helpsupport.project_id') }}">
                                            <input type="hidden" name="complain_category_id" id="complain_category_id" value="{{ $type }}">
                                            {{-- Email  --}}
                                            <div class="form-group">
                                                <label for="Email" class="control-label col-sm">{{ __('Email') }}</label>
                                                <div class="col-sm-12">
                                                    @if (Auth::check() && Auth::user()->email)
                                                        <input type="email" class="form-control" name="email" id="{{ Auth::user()->email }}" placeholder="{{ __('email') }}" value="{{ Auth::user()->email }}" required readonly>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="complain_subject" class="control-label col-sm">{{ __('Complain Subject') }}</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="complain_subject" name="complain_subject" placeholder="{{ __('complain subject') }}" value="{{ old('complain_subject') }}" maxlength="225" required>
                                                </div>
                                            </div>
                                            {{-- Details  --}}
                                            <div class="form-group">
                                                <label for="details" class="col-sm control-label">{{ __('Details') }}
                                                </label>
                                                <div class="col-sm-12">
                                                    <textarea name="details" id="details" cols="30" rows="10" class="form-control" value="{{ old('details') }}" placeholder="{{ __('Details ') }}" required></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="priority" class="col-sm control-label">{{ __('priority') }}
                                                </label>
                                                <div class="col-sm-12">
                                                    <input type="radio" id="1" name="priority" value="High" checked>High
                                                    <input type="radio" id="2" name="priority" value="Normal" checked>Normal
                                                    <input type="radio" id="3" name="priority" value="Low">Low
                                                </div>
                                            </div>

                                            {{-- upload Screenshoot --}}
                                            <div class="form-group">
                                                <label for="file1" class="col-sm control-label">{{ __('Upload Screenshoot') }}</label>
                                                <div class="col-sm-12">
                                                    <input class="form-control form-control-sm" type="file" name="file1" id="file1">
                                                </div>
                                            </div>

                                            {{-- Submit Button --}}
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="submit" class="btn btn-primary" value="edit-type">{{ __('Submit') }}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
@section('scripts')
    <script src="{{ asset('vendor/helpsupport/js/jquery.min.js') }}"></script>

    <!-- BOOTSTRAP JS -->
    <script src="{{ asset('vendor/helpsupport/plugins/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('vendor/helpsupport/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

    <!-- SHOW PASSWORD JS -->
    <script src="{{ asset('vendor/helpsupport/js/show-password.min.js') }}"></script>

    <!-- GENERATE OTP JS -->
    <script src="{{ asset('vendor/helpsupport/js/generate-otp.js') }}"></script>

    <!-- Perfect SCROLLBAR JS-->
    <script src="{{ asset('vendor/helpsupport/plugins/p-scroll/perfect-scrollbar.js') }}"></script>

    <!-- Color Theme js -->
    <script src="{{ asset('vendor/helpsupport/js/themeColors.js') }}"></script>

    <!-- CUSTOM JS -->
    <script src="{{ asset('vendor/helpsupport/js/custom.js') }}"></script>
@endsection
