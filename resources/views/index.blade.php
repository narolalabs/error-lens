@extends('error-lens::layouts.app')

@section('content')
@php
    $isArchivedPage = request()->route()->getName() === 'error-lens.archived.index';
    $isRelevantPage = request()->relevant ?? false;
    $currentRouteName = $isArchivedPage ? 'error-lens.archived.index' : 'error-lens.index';
    $viewRouteName = $isArchivedPage ? 'error-lens.archived.view' : 'error-lens.view';
    
    $heading = 'Dashboard';
    if ($isArchivedPage) {
        $heading = 'Archived List';
    }
    else if ($isRelevantPage) {
        $heading = 'Relevant Errors';
    }
@endphp
<h2 class="my-4">{{ $heading }}</h2>
<div class="row">
    @if ( ! $isRelevantPage)
    <div class="col-sm-12">
        <div class="row mb-3">
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
                        <a href="{{ route($currentRouteName, ['view' => 'today']) }}" class="text-decoration-none">   
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
                        <a href="{{ route($currentRouteName, ['view' => 'yesterday']) }}" class="text-decoration-none">
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
                        <a href="{{ route($currentRouteName, ['view' => 'last-month']) }}" class="text-decoration-none">
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
                        <a href="{{ route($currentRouteName, ['view' => 'current-year']) }}" class="text-decoration-none">
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
    @endif

    <div class="col-sm-12">
        <x-error-lens::alert-message :customMessage="$errors->all()"/>
    </div>
    
    <div class="col-md-9">
        <div class="row mb-3">
            <div class="col-12">
                <div class="card card_custom">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4>{{ $activeError }}</h4>
                        <div>
                            <form action="{{ route('error-lens.archived') }}" method="POST" id="archivedErrorForm" class="d-none">
                                @csrf
                                @method('POST')
                                <input type="hidden" name="errorId" id="errorId">
                                <div class="align-middle">
                                    <b><span id="selectedCheckboxCount">0</span> Selected</b>
                                    <button class="btn btn-primary ms-2">Archive</button>
                                </div>
                            </form>
                            <form action="{{ route('error-lens.archived.delete-selected') }}" method="POST" id="archivedErrorDeleteForm" class="d-none">
                                @csrf
                                @method('POST')
                                <input type="hidden" name="archiveErrorId" id="archiveErrorId">
                                <div class="align-middle">
                                    <b><span id="selectedArchivedCheckboxCount">0</span> Selected</b>
                                    <button class="btn btn-primary ms-2">Delete</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="custom_table p-4">
                        <div class="row">
                            <div class="col-md-12 col-lg-8 col-xl-5 ms-auto">
                                <form action="" method="POST" id="searchErrorForm">
                                    @csrf
                                    @method('POST')
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Search error" aria-label="Search error" aria-describedby="searchErrorButton" name="searchErrorInput" id="searchErrorInput" value="{{ request()->searchErrorInput }}">
                                        <button class="btn btn-primary d-inline-flex align-items-center" type="submit" id="searchErrorButton">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512"><path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/></svg>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div id="errorListingTableWrapper">
                            @include('error-lens::error-list')
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
        var isArchivedPage = @if($isArchivedPage) true @else false @endif;
        var selectAll = document.getElementById('selectAll');
        var singleCheckboxes = document.querySelectorAll('.singleCheckbox');
        var errorListingTableWrapper = document.getElementById('errorListingTableWrapper');

        // Search error form parameters
        var searchErrorForm = document.getElementById('searchErrorForm');
        var searchErrorInput = document.getElementById('searchErrorInput');

        // Archive error form parameters
        var archivedErrorForm = document.getElementById('archivedErrorForm');
        var selectedCheckboxCount = document.getElementById('selectedCheckboxCount');
        var errorId = document.getElementById('errorId');

        // Archived error delete form parameteres
        var archivedErrorDeleteForm = document.getElementById('archivedErrorDeleteForm');
        var selectedArchivedCheckboxCount = document.getElementById('selectedArchivedCheckboxCount');
        var archiveErrorId = document.getElementById('archiveErrorId');

        managePagination();
        selectAllCheckbox();

        // Archive the errors
        archivedErrorForm.addEventListener('submit', function (event) {
            if ( ! errorId.value.trim()) {
                alert('Please select at least one checkbox.');
                event.preventDefault();
                return false;
            }

            if ( ! confirm('Are you sure you want to archive the selected error logs?')) {
                event.preventDefault();
            }
        });

        // Paginate the listing
        searchErrorForm.addEventListener('submit', function (event) {
            event.preventDefault();

            addQueryParams('searchErrorInput', searchErrorInput.value);

            // Get form data
            let formData = new FormData(searchErrorForm);
            loadTableData('POST', window.location.href, formData);
        });

       
        function managePagination() {
            var pageLinks = document.querySelectorAll('.page-link');
            pageLinks.forEach(pageLink => {
                pageLink.addEventListener('click', function (event) {
                    event.preventDefault();

                    addQueryParams('page', getQueryParams(event.target.href, 'page'));

                    // Get form data
                    let formData = new FormData(searchErrorForm);
                    loadTableData('POST', event.target.href, formData);
                });    
            });
        }

        // Hide show archive error form
        function hideShowArchiveErrorFrom() {
            let checkedCheckboxes = document.querySelectorAll('.singleCheckbox:checked');
            archivedErrorForm.classList.add('d-none');

            // Display selected checkbox counting
            if (checkedCheckboxes.length) {
                archivedErrorForm.classList.remove('d-none');
                selectedCheckboxCount.innerText = checkedCheckboxes.length;
            }

            // Set the checkbox value in archive form
            let checkboxIds = [];
            checkedCheckboxes.forEach(function (checkbox) {
                checkboxIds.push(checkbox.value);
            });
            errorId.value = checkboxIds.toString();
        }

        // Hide show archive error delete form
        function hideShowArchivedErrorDeleteFrom() {
            let checkedCheckboxes = document.querySelectorAll('.singleCheckbox:checked');
            archivedErrorDeleteForm.classList.add('d-none');

            // Display selected checkbox counting
            if (checkedCheckboxes.length) {
                archivedErrorDeleteForm.classList.remove('d-none');
                selectedArchivedCheckboxCount.innerText = checkedCheckboxes.length;
            }

            // Set the checkbox value in archive form
            let checkboxIds = [];
            checkedCheckboxes.forEach(function (checkbox) {
                checkboxIds.push(checkbox.value);
            });
            archiveErrorId.value = checkboxIds.toString();
        }

        // Initialize the checkbox variables and events for the same
        function selectAllCheckbox() {
            selectAll = document.getElementById('selectAll');
            singleCheckboxes = document.querySelectorAll('.singleCheckbox');

             // While user click on select all checkbox then checked-unchecked all checkboxes
            selectAll.addEventListener('change', function (event) {
                for (const key in singleCheckboxes) {
                    if (singleCheckboxes.hasOwnProperty.call(singleCheckboxes, key)) {
                        singleCheckboxes[key].checked = event.target.checked;
                    }
                }
                (isArchivedPage) ? hideShowArchivedErrorDeleteFrom() : hideShowArchiveErrorFrom();
            });

            // While single checkbox checked, based on that, check-uncheck selectall checkbox
            singleCheckboxes.forEach(function (singleCheckbox) {
                singleCheckbox.addEventListener('change', function (event) {
                    selectAll.checked = ! document.querySelectorAll('.singleCheckbox:not(:checked)').length;
                    
                    (isArchivedPage) ? hideShowArchivedErrorDeleteFrom() : hideShowArchiveErrorFrom();
                });
            });
        }

        // Load table data while user search or paginate
        function loadTableData(method, url, formData) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", url, true);

            // Set the X-Requested-With header to XMLHttpRequest
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            // Set up the callback
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Handle the response here
                    let response = JSON.parse(xhr.responseText);
                    errorListingTableWrapper.innerHTML = response['data']['view'];
                    
                    // Reinitialize the checkbox events
                    selectAllCheckbox();
                    
                    // On click on checkbox hide show archive/delete form
                    hideShowArchiveErrorFrom();
                    hideShowArchivedErrorDeleteFrom();

                    // Apply AJAX on pagination click
                    managePagination();

                    // Scroll up
                    document.getElementsByTagName("body")[0].scrollIntoView();
                }
            };

            // Send the request with the form data
            xhr.send(formData);
        }

        // Update the URL with query params
        function addQueryParams(key, value) {
            // Get the current URL
            var currentUrl = window.location.href;

            // Create or update query parameters
            var queryParams = new URLSearchParams(window.location.search);
            queryParams.set(key, value);

            // Construct the new URL with updated query parameters
            var newUrl = currentUrl.split('?')[0] + '?' + queryParams.toString();

            // Use pushState to update the URL without triggering a page reload
            history.pushState({ path: newUrl }, '', newUrl);
        }

        // Get value from the URL using query param key
        function getQueryParams(url, paramName) {
            // Get the current URL
            var currentUrl = url;

            // Create a URL object
            var url = new URL(currentUrl);

            // Get the search params from the URL
            var queryParams = url.searchParams;

            // Access individual query parameters
            var paramValue = queryParams.get(paramName);

            return paramValue;
        }

    });
</script>
@endsection
