@extends('error-lens::layouts.app')

@section('content')
<h2 class="my-4">Dashboard</h2>
<div class="row">
    <div class="col-sm-12">
        <div class="row mb-4">
            <div class="col-lg-3">
                <div class="card card_block">
                    <div class="card-header text-uppercase border-0">
                        <div class="card_left">
                            <h2>{{ $today_errors_count }}</h2>
                            <h6>Today's ERRORS</h6>
                            <small>{{ date('d M, Y') }}</small>
                        </div>
                        <div class="card_right">
                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><path d="M256 0c53 0 96 43 96 96v3.6c0 15.7-12.7 28.4-28.4 28.4H188.4c-15.7 0-28.4-12.7-28.4-28.4V96c0-53 43-96 96-96zM41.4 105.4c12.5-12.5 32.8-12.5 45.3 0l64 64c.7 .7 1.3 1.4 1.9 2.1c14.2-7.3 30.4-11.4 47.5-11.4H312c17.1 0 33.2 4.1 47.5 11.4c.6-.7 1.2-1.4 1.9-2.1l64-64c12.5-12.5 32.8-12.5 45.3 0s12.5 32.8 0 45.3l-64 64c-.7 .7-1.4 1.3-2.1 1.9c6.2 12 10.1 25.3 11.1 39.5H480c17.7 0 32 14.3 32 32s-14.3 32-32 32H416c0 24.6-5.5 47.8-15.4 68.6c2.2 1.3 4.2 2.9 6 4.8l64 64c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0l-63.1-63.1c-24.5 21.8-55.8 36.2-90.3 39.6V240c0-8.8-7.2-16-16-16s-16 7.2-16 16V479.2c-34.5-3.4-65.8-17.8-90.3-39.6L86.6 502.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l64-64c1.9-1.9 3.9-3.4 6-4.8C101.5 367.8 96 344.6 96 320H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H96.3c1.1-14.1 5-27.5 11.1-39.5c-.7-.6-1.4-1.2-2.1-1.9l-64-64c-12.5-12.5-12.5-32.8 0-45.3z"></path></svg>   
                        </div>
                    </div>
                    <div class="card-body d-flex justify-content-between align-items-end py-2">
                        <a href="{{ route('error-lens.index', ['view' => 'today']) }}" class="text-decoration-none">   
                            View
                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><path d="M502.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L402.7 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l370.7 0-73.4 73.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l128-128z"></path></svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card card_block">
                    <div class="card-header text-uppercase border-0">
                        <div class="card_left">
                            <h2>{{ $yesterday_errors_count }}</h2>
                            <h6>Yesterday's ERRORS</h6>
                            <small>{{ date('d M, Y', strtotime('yesterday')) }}</small>
                        </div>
                        <div class="card_right">
                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><path d="M256 0c53 0 96 43 96 96v3.6c0 15.7-12.7 28.4-28.4 28.4H188.4c-15.7 0-28.4-12.7-28.4-28.4V96c0-53 43-96 96-96zM41.4 105.4c12.5-12.5 32.8-12.5 45.3 0l64 64c.7 .7 1.3 1.4 1.9 2.1c14.2-7.3 30.4-11.4 47.5-11.4H312c17.1 0 33.2 4.1 47.5 11.4c.6-.7 1.2-1.4 1.9-2.1l64-64c12.5-12.5 32.8-12.5 45.3 0s12.5 32.8 0 45.3l-64 64c-.7 .7-1.4 1.3-2.1 1.9c6.2 12 10.1 25.3 11.1 39.5H480c17.7 0 32 14.3 32 32s-14.3 32-32 32H416c0 24.6-5.5 47.8-15.4 68.6c2.2 1.3 4.2 2.9 6 4.8l64 64c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0l-63.1-63.1c-24.5 21.8-55.8 36.2-90.3 39.6V240c0-8.8-7.2-16-16-16s-16 7.2-16 16V479.2c-34.5-3.4-65.8-17.8-90.3-39.6L86.6 502.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l64-64c1.9-1.9 3.9-3.4 6-4.8C101.5 367.8 96 344.6 96 320H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H96.3c1.1-14.1 5-27.5 11.1-39.5c-.7-.6-1.4-1.2-2.1-1.9l-64-64c-12.5-12.5-12.5-32.8 0-45.3z"></path></svg>   
                        </div>
                    </div>
                    <div class="card-body d-flex justify-content-between align-items-end py-2">
                        <a href="{{ route('error-lens.index', ['view' => 'yesterday']) }}" class="text-decoration-none">
                            View
                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                                <path d="M502.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L402.7 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l370.7 0-73.4 73.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l128-128z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3">
                <div class="card card_block">
                    <div class="card-header text-uppercase border-0">
                        <div class="card_left">
                            <h2>{{ $last_month_errors_count }}</h2>
                            <h6>Last Month ERRORS</h6>
                            <small>{{ date('F-Y', strtotime('last month')) }}</small>
                        </div>
                        <div class="card_right">
                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><path d="M256 0c53 0 96 43 96 96v3.6c0 15.7-12.7 28.4-28.4 28.4H188.4c-15.7 0-28.4-12.7-28.4-28.4V96c0-53 43-96 96-96zM41.4 105.4c12.5-12.5 32.8-12.5 45.3 0l64 64c.7 .7 1.3 1.4 1.9 2.1c14.2-7.3 30.4-11.4 47.5-11.4H312c17.1 0 33.2 4.1 47.5 11.4c.6-.7 1.2-1.4 1.9-2.1l64-64c12.5-12.5 32.8-12.5 45.3 0s12.5 32.8 0 45.3l-64 64c-.7 .7-1.4 1.3-2.1 1.9c6.2 12 10.1 25.3 11.1 39.5H480c17.7 0 32 14.3 32 32s-14.3 32-32 32H416c0 24.6-5.5 47.8-15.4 68.6c2.2 1.3 4.2 2.9 6 4.8l64 64c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0l-63.1-63.1c-24.5 21.8-55.8 36.2-90.3 39.6V240c0-8.8-7.2-16-16-16s-16 7.2-16 16V479.2c-34.5-3.4-65.8-17.8-90.3-39.6L86.6 502.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l64-64c1.9-1.9 3.9-3.4 6-4.8C101.5 367.8 96 344.6 96 320H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H96.3c1.1-14.1 5-27.5 11.1-39.5c-.7-.6-1.4-1.2-2.1-1.9l-64-64c-12.5-12.5-12.5-32.8 0-45.3z"></path></svg>   
                        </div>
                    </div>
                    <div class="card-body d-flex justify-content-between align-items-end py-2">
                        <a href="{{ route('error-lens.index', ['view' => 'last-month']) }}" class="text-decoration-none">
                            View
                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                                <path d="M502.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L402.7 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l370.7 0-73.4 73.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l128-128z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3">
                <div class="card card_block">
                    <div class="card-header text-uppercase border-0">
                        <div class="card_left">
                            <h2>{{ $current_year_errors_count }}</h2>
                            <h6>ALL ERRORS</h6>
                            <small>{{ date('Y') }}</small>
                        </div>
                        <div class="card_right">
                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><path d="M256 0c53 0 96 43 96 96v3.6c0 15.7-12.7 28.4-28.4 28.4H188.4c-15.7 0-28.4-12.7-28.4-28.4V96c0-53 43-96 96-96zM41.4 105.4c12.5-12.5 32.8-12.5 45.3 0l64 64c.7 .7 1.3 1.4 1.9 2.1c14.2-7.3 30.4-11.4 47.5-11.4H312c17.1 0 33.2 4.1 47.5 11.4c.6-.7 1.2-1.4 1.9-2.1l64-64c12.5-12.5 32.8-12.5 45.3 0s12.5 32.8 0 45.3l-64 64c-.7 .7-1.4 1.3-2.1 1.9c6.2 12 10.1 25.3 11.1 39.5H480c17.7 0 32 14.3 32 32s-14.3 32-32 32H416c0 24.6-5.5 47.8-15.4 68.6c2.2 1.3 4.2 2.9 6 4.8l64 64c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0l-63.1-63.1c-24.5 21.8-55.8 36.2-90.3 39.6V240c0-8.8-7.2-16-16-16s-16 7.2-16 16V479.2c-34.5-3.4-65.8-17.8-90.3-39.6L86.6 502.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l64-64c1.9-1.9 3.9-3.4 6-4.8C101.5 367.8 96 344.6 96 320H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H96.3c1.1-14.1 5-27.5 11.1-39.5c-.7-.6-1.4-1.2-2.1-1.9l-64-64c-12.5-12.5-12.5-32.8 0-45.3z"></path></svg>   
                        </div>
                    </div>
                    <div class="card-body d-flex justify-content-between align-items-end py-2">
                        <a href="{{ route('error-lens.index', ['view' => 'current-year']) }}" class="text-decoration-none">
                            View
                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                                <path d="M502.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L402.7 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l370.7 0-73.4 73.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l128-128z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <x-error-lens::alert-message :customMessage="$errors->all()"/>
    </div>
    
    <div class="col-md-9">
        <div class="row mb-3">
            <div class="col-12">
                <div class="card card_custom">
                    <h4 class="card-header">{{ $activeError }}</h4>
                    <div class="custom_table p-4">
                        <div>
                            <form action="{{ route('error-lens.archive.selected') }}" method="POST" id="archiveErrorForm" class="d-none">
                                @csrf
                                @method('POST')
                                <input type="hidden" name="archiveErrorId" id="archiveErrorId">
                                <div class="align-middle">
                                    <b><span id="selectedCheckboxCount">0</span> Selected</b>
                                    <button class="btn btn-primary ms-2">Archive</button>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    id="selectAll">
                                            </div>
                                        </th>
                                        <th>URL</th>
                                        <th>Message</th>
                                        <th>Occured At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse( $errorLogs as $errorLog )
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input singleCheckbox" type="checkbox" value="{{ $errorLog->id }}">
                                                </div>
                                            </td>
                                            <td width="30%">{{ $errorLog->url }}</td>
                                            <td>{{ $errorLog->message }}</td>
                                            <td width="20%">{{ date('dS F, Y H:i', strtotime($errorLog->created_at)) }}</td>
                                            <td>
                                                <a href="{{ route('error-lens.view', [ 'id' => $errorLog->id ]) }}" class="btn btn-link text-decoration-none view-error">
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"></path></svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                <h6>No errors reported.</h6>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{ $errorLogs->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
             </div>
        </div>
    </div>

    <div class="col-md-3">
        <x-error-lens::site-info />
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var selectAll = document.getElementById('selectAll');
        var singleCheckboxes = document.querySelectorAll('.singleCheckbox');
        var selectedCheckboxCount = document.getElementById('selectedCheckboxCount');
        var archiveErrorForm = document.getElementById('archiveErrorForm');
        var archiveErrorId = document.getElementById('archiveErrorId');
        
        // While user click on select all checkbox then checked-unchecked all checkboxes
        selectAll.addEventListener('change', function (event) {
            for (const key in singleCheckboxes) {
                if (singleCheckboxes.hasOwnProperty.call(singleCheckboxes, key)) {
                    singleCheckboxes[key].checked = event.target.checked;
                }
            }

            hideShowArchiveErrorFrom();
        });

        // While single checkbox checked, based on that, check-uncheck selectall checkbox
        singleCheckboxes.forEach(function (singleCheckbox) {
            singleCheckbox.addEventListener('change', function (event) {
                selectAll.checked = ! document.querySelectorAll('.singleCheckbox:not(:checked)').length
                
                hideShowArchiveErrorFrom();
            });
        });

        archiveErrorForm.addEventListener('submit', function (event) {
            if ( ! archiveErrorId.value.trim()) {
                alert('Please select at least one checkbox.');
                event.preventDefault();
                return false;
            }

            if ( ! confirm('Are you sure you want to archive the selected error logs?')) {
                event.preventDefault();
            }
        });

        // Hide show archive error form
        function hideShowArchiveErrorFrom() {
            let checkedCheckboxes = document.querySelectorAll('.singleCheckbox:checked');
            archiveErrorForm.classList.add('d-none');

            // Display selected checkbox counting
            if (checkedCheckboxes.length) {
                archiveErrorForm.classList.remove('d-none');
                selectedCheckboxCount.innerText = checkedCheckboxes.length;
            }

            // Set the checkbox value in archive form
            let checkboxIds = [];
            checkedCheckboxes.forEach(function (checkbox) {
                checkboxIds.push(checkbox.value);
            });
            archiveErrorId.value = checkboxIds.toString();
        }
    });
</script>
@endsection
