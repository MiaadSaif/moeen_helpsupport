<!DOCTYPE html>
<html lang="en">
@inject('carbon', 'Carbon\Carbon')

<head>
    <title>{{ __('Complains History') }}</title>
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
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
                                        <th>{{ __('Complain Subject') }}</th>
                                        <th>{{ __('Category Type') }}</th>
                                        <th>{{ __('Attachment') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Priority') }}</th>
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
                                                    <small class="text"><b font-weight: bold>Available</i></b></small>
                                                @else
                                                    <small class="text-danger"><b font-weight: bold>None</b></small>
                                                @endif
                                            </td>
                                            <td class="{{ $complain->status === 'Open' ? 'text-success' : ($complain->status === 'Close' ? 'text-danger' : '') }}">{{ $complain->status }}</td>
                                            <td class="{{ $complain->priority === 'High' ? 'text-danger' : ($complain->priority === 'Normal' ? 'text-success' : 'text-warning') }}">{{ $complain->priority }}</td>
                                            <td><a class="btn btn-primary btn-sm" href="{{ route('showResponse', ['complain_id' => $complain->id, 'client_id' => config('helpsupport.client_id')]) }}"><i class="fa fa-eye"></i></a></td>

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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#listcomplains').DataTable();
        });
        $('#status-filter').on('change', function() {
            var status = $(this).val();
            if (status === 'all') {
                $('#listcomplains tbody tr').show();
            } else {
                $('#listcomplains tbody tr').hide();
                $('#listcomplains tbody tr').each(function() {
                    if ($(this).find('td:eq(5)').text() === status) {
                        $(this).show();
                    }
                });
            }
        });
    </script>
</body>

</html>
