@inject('carbon', 'Carbon\Carbon')


<head>
    <title>{{ __('Complains History') }}</title>
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
        <div class='page-header'>
            <h1 class='page-title'>{{ __('Complains History') }}</h1>
            <div>
                <ol class='breadcrumb'>
                    <li class='breadcrumb-item active' aria-current='page'>{{ __('My Tickets ') }}</li>
                    <li class='breadcrumb-item active' aria-current='page'><a href="{{ route('help') }}">{{ __('Help & Support ') }}</a></li>

                </ol>
            </div>
        </div>
        <div class="mb-3">
            <label for="status-filter" class="form-label">{{ __('Filter by status:') }}</label>
            <select id="status-filter" class="form-select">
                <option value="all">{{ __('All') }}</option>
                <option value="Close">{{ __('Closed') }}</option>
                <option value="Open">{{ __('Open') }}</option>
            </select>
        </div>
        <div class="complain-row">
            <div class="col">
                <div class="card">
                    <div class="card-body" style="overflow-x: scroll">
                        @if (count($complains ?? []) > 0)
                            <table class="table table-sm data-table table-striped" id="listcomplains">
                                <thead>
                                    <tr>
                                        <th>{{ __('No') }}</th>
                                        <th>{{ __('Project Name') }}</th>
                                        <th>{{ __('complain subject') }}</th>
                                        <th>{{ __('Category Type') }}</th>
                                        <th>{{ __('Attachment') }}</th>
                                        <th>{{ __('status') }}</th>
                                        <th>{{ __('priority') }}</th>
                                        <th>{{ __('Details') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($complains as $complain)
                                        <tr class="complain-row" data-status="{{ $complain->status }}">
                                            <td style="">{{ $complain->id }}</td>
                                            <td style="">{{ $complain->project_name }}</td>
                                            <td style="">{{ $complain->complain_subject }}</td>
                                            <td style="">{{ $complain->category_name }}</td>
                                            <td style="">
                                                @if ($complain->file1 != null)
                                                    <small class="text"><b font-weight: bold>Available</i></b>
                                                    </small>
                                                @else
                                                    <small class="text-danger"><b font-weight: bold>None</b>
                                                    </small>
                                                @endif
                                            </td>
                                            <td style="" class="    @if ($complain->status == 'Open') @elseif($complain->status == 'Close')
                                            text-danger
                                            @else
                                            text-danger @endif ">{{ $complain->status }}</td>
                                            <td style="" class="
                                        @if ($complain->priority == 'High') text-danger
                                        @elseif($complain->priority == 'Normal')
                                        text-success
                                        @else
                                        text-warning @endif
                                    ">{{ $complain->priority }}</td>
                                            <td style=""><a class="btn btn-primary btn-sm" href="{{ route('showResponse', ['complain_id' => $complain->id, 'client_id' => config('helpsupport.client_id')]) }}"><i class="fa fa-eye"></i> </a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else

                            <div class="alert alert-info" role="alert">
                                {{ __('There are no tickets for you.') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#listcomplains').DataTable();

            $('#status-filter').on('change', function() {
                var status = $(this).val();
                if (status === 'all') {
                    $('tr').show();
                } else {
                    $('tr').hide();
                    $('tr').each(function() {
                        if ($(this).find('td:eq(5)').text() === status) {
                            $(this).show();
                        }
                    });
                }
            });
        });
    </script>
@endsection
