@inject('carbon', 'Carbon\Carbon')
<!DOCTYPE html>
<html lang="en">

<head>

    <title>{{ __('Complains Details') }}</title>

    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
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

<body>
    <div class="container">
        <div class="side-app">
            <!-- CONTAINER -->
            <div class="main-container container-fluid">
                {{-- Customer Details --}}
                <div class="page-header">
                    <h1 class='page-title'>{{ __('Complains Details') }}</h1>
                    <div>
                        <ol class='breadcrumb'>
                            <li class='breadcrumb-item active' aria-current='page'>{{ __('Ticket Details ') }}</a></li>
                            <li class='breadcrumb-item active' aria-current='page'><a href="{{ route('MytTickets') }}">{{ __('Complains History ') }}</a></li>
                            <li class='breadcrumb-item active' aria-current='page'><a href="{{ route('help') }}">{{ __('Help & Support ') }}</a></li>

                        </ol>
                    </div>
                </div>
            </div>
            <!-- PAGE-HEADER END -->
            <!-- ROW-1 -->
            <div class="row">

                <div class="col-4">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Ticket Details</h4>
                            <dl class="row">
                                <dt class="col-sm-6">{{ __('Ticket ID') }}</dt>

                                <dd class="col-sm-6" style="text-align: end">{{ $complain->complain->id }}</dd>






                                <dt class="col-sm-6">{{ __('Submitted at') }}</dt>
                                <dd class="col-sm-6" style="text-align: end; font-size: .875em;">
                                    {{ \Carbon\Carbon::parse($complain->complain->created_at)->format('Y-m-d H:i') }}
                                </dd>



                                <dt class="col-sm-6">{{ __('Updated at') }}</dt>
                                <dd class="col-sm-6" style="text-align:  end; font-size: .875em;">
                                    {{ \Carbon\Carbon::parse($complain->complain->updated_at)->format('Y-m-d H:i') }}</dd>
                                <dt class="col-sm-6">{{ __('Complain Category') }}</dt>
                                <dd class="col-sm-6" style="text-align: end">{{ $complain->category_name }}</dd>

                                <dt class="col-sm-6">{{ __('Number of Replies') }}</dt>
                                @php $count = 0; @endphp
                                @foreach ($complain->responses as $response)
                                    {{-- Loop through the replies --}}
                                    {{-- Increment the count variable for each reply --}}
                                    @php $count++; @endphp
                                @endforeach
                                <dd class="col-sm-6" style="text-align: end"> {{ $count }}</dd>


                                <dt class="col-sm-6">{{ __('Priority') }}</dt>
                                @if ($complain->complain->priority == 'High')
                                    <dd class="col-sm-6" style="text-align: end"><span style="color: red;">{{ $complain->complain->priority }}</span></dd>
                                @elseif($complain->complain->priority == 'Normal')
                                    <dd class="col-sm-6" style="text-align: end"><span style="color: green;">{{ $complain->complain->priority }}</span></dd>
                                @elseif($complain->complain->priority == 'Low')
                                    <dd class="col-sm-6" style="text-align: end"><span style="color: orange;">{{ $complain->complain->priority }}</span></dd>
                                @endif
                                <dt class="col-sm-6">{{ __('Status') }}</dt>
                                @if ($complain->complain->status == 'Open')
                                    <dd class="col-sm-6" style="text-align: end"><span style="color: green;">{{ $complain->complain->status }}</span></dd>
                                @else
                                    <dd class="col-sm-6" style="text-align: end"><span style="color: red;">{{ $complain->complain->status }}</span></dd>
                                    <dt class="col-sm-6">{{ __('Closed by') }}</dt>
                                    <dd class="col-sm-6" style="text-align: end"> <span style="color: red;">{{ $complain->complain->user_data->name }}</span></dd>
                                    <dt class="col-sm-6">{{ __('Closed at') }}</dt>
                                    <dd class="col-sm-6" style="text-align: end"><span style="color: red;">{{ $complain->complain->closed_at }}</span></dd>
                                    <dt class="col-sm-6"></dt>
                                    <dd>
                                        <form id="update" name="update" method="POST" action="{{ route('updateTicketStatus', $complain->complain->id) }}" enctype="multipart/form-data">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="btn btn-primary" onclick="scrollToReplyForm()">{{ __('Re-Open Ticket') }}</button>

                                            {{--                                             <button type="submit" id="re-open-button" class="btn btn-primary">{{ __('Re-Open Ticket') }}</button>
 --}}
                                        </form>

                                    </dd>
                                @endif

                            </dl>
                        </div>
                    </div>
                </div>





                <div class="col-8">
                    <div class="row"> {{-- row 33 --}}
                        {{-- main ticket data --}}
                        <div class="col-12">

                            <div class="card text-left">
                                <div class="card-body">
                                    <h4 class="card-title">Complains Detils</h4>
                                    <dd class="col-sm-12" style="text-align:  end; font-size: .875em;">
                                        {{ \Carbon\Carbon::parse($complain->complain->updated_at)->format('Y-m-d H:i') }}</dd>

                                    <dl class="row">

                                        <dt class="col-sm-6">{{ __('Complain subject:') }}</dt>
                                        <dd class="col-sm-6" style="text-align: end">{{ $complain->complain->complain_subject }}</dd>
                                        </dd>

                                        <dt class="col-sm-6">{{ __('Description:') }}</dt>
                                        <br>
                                        <br>
                                        <div class="card ">
                                            <div class="card-body">
                                                <dd class="col-sm-6" style="text-align: end">{{ $complain->complain->details }}</dd>

                                            </div>
                                        </div>

                                        <dt class="col-sm-6">{{ __('Attachments:') }}</dt>

                                        <dd class="col-sm-6" style="text-align: end">
                                            @if ($complain->complain->file1 != null)
                                                <a href="{{ config('helpsupport.base_url') . '/' . $complain->complain->file1 }}" target="_blank">
                                                    <small class="text-danger"><b font-weight: bold>View Attachment</b>
                                                    </small>

                                                </a>
                                            @else
                                                <small class="text-danger"><b font-weight: bold>None</b>
                                                </small>
                                            @endif
                                        </dd>
                                </div>

                            </div>

                            </dl>
                        </div>
                    </div>
                    {{-- responses --}}

                    @foreach ($complain->responses as $response)
                        <div class="col-12">
                            <div class="card">
                                <div class=card-header>
                                    @if ($response->respond_direction == 'm2c')
                                        <dt class="col-sm-6" style="text-align: end">{{ 'Reply by MASS ' }}
                                        </dt>
                                        <dt class="col-sm-6" style="text-align: end"> {{ $complain->project_name }}

                                        </dt>
                                    @else
                                        <dt class="col-sm-6" style="text-align: end">
                                            Reply by {{ $complain->client_name }}
                                        </dt>
                                        <dt class="col-sm-6" style="text-align: end"> {{ $complain->project_name }}

                                        </dt>
                                    @endif

                                </div>

                                <div class="card-body">
                                    <h6 class="col-sm-6" style="font-size"> {{ \Carbon\Carbon::parse($response->updated_at)->format('Y-m-d H:i') }}</h6>
                                    <dl class="row">
                                        <dt class="col-sm-6">{{ __('Message:') }}</dt>
                                        <dd class="col-sm-6" style="text-align: end">{{ $response->respond_txt }}</dd>

                                        <dt class="col-sm-6">{{ __('Attachments:') }}</dt>

                                        <dd class="col-sm-6" style="text-align: end">
                                            @if ($response->file1 != null)
                                                <a href="{{ config('helpsupport.base_url') . '/' . $response->file1 }}" target="_blank">
                                                    <small class="text-danger"><b font-weight: bold>View Attachment</b>
                                                    </small>

                                                </a>
                                            @else
                                                <small class="text-danger"><b font-weight: bold>None</b>
                                                </small>
                                            @endif
                                        </dd>

                                        {{-- </div> --}}
                                    </dl>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    {{--  Reply form --}}
                    @if ($complain->complain->status != 'Close')
                        <div id="reply-form" class="col-12">

                            <div class="card text-left">
                                <div class="card-body">
                                    <form action="{{ route('createNewResponse') }}" method="POST" enctype="multipart/form-data">
                                        @csrf

                                        <h4 class="card-title">{{ __('Add a reply') }}</h4>
                                        <div class="form-outline mb-4">
                                            <div class="form-group">
                                                <label class="col-sm-6">{{ __('The Message:') }}</label>
                                                <div id="loader" style="display:none;"><i class="fas fa-spinner fa-spin"></i> Loading...</div> {{--  TO Show a loader or a message to indicate the form submission is in progress --}}

                                                <textarea class="form-control" id="textAreaExample5" name="respond_txt" rows="3"></textarea>
                                            </div>


                                            <div class="form-group">
                                                <label for="file1" class="form-label">{{ __('Attchements:') }}
                                                </label>
                                                <input class="form-control form-control-sm" name="file1" id="file1" type="file" />
                                                <small class="text-muted">Max size 2 MB</small>
                                            </div>

                                            <div class="form-group">
                                                <input type="hidden" id="client_id" name="client_id" value="{{ $complain->complain->client_id }}" />
                                                <input type="hidden" id="complain_id" name="complain_id" value="{{ $complain->complain->id }}" />

                                                <button type="submit" id="replyButton" class="btn btn-primary btn-sm">{{ __('Reply') }}</button>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif

                </div> {{-- end of row 33 --}}
            </div>
        </div>

        <!-- ROW-1 END -->
        <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

        <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
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

        {{-- //blade (script) --}}
        <script type="text/javascript">
            $(function() {

                /*------------------------------------------
                 --------------------------------------------
                 Pass Header Token
                 --------------------------------------------
                 --------------------------------------------*/
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                /*------------------------------------------
                --------------------------------------------
                Render DataTable
                --------------------------------------------
                --------------------------------------------*/
                var table = $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ url('ticket') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'client_id',
                            name: 'client_id'
                        },
                        {
                            data: 'project_id',
                            name: 'project_id'
                        },
                        {
                            data: 'complain_category_id',
                            name: 'complain_category_id'
                        },
                        {
                            data: 'details',
                            name: 'details',
                            orderable: false,
                            searchable: false
                        },
                    ]
                });
            });


            function scrollToReplyFormTest() {
                const replyFormSection = document.getElementById('reply-form');
                if (replyFormSection) {
                    replyFormSection.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            }
        </script>
</body>


</html>
