@extends('error-lens::layouts.app')

@section('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <h2 class="my-4">Configurations</h2>
    <x-error-lens::alert-message />
    <div class="row">
        <div class="col-md-6">
            <div class="row mb-3">
                <div class="col-12">
                    @include('error-lens::config.preferences')
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row mb-3">
                <div class="col-12">
                    @include('error-lens::config.securityConfig')
                </div>
                <div class="col-12">
                    @include('error-lens::config.cacheClearConfig')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hide show for requested data elements
            hideShow('storeRequestedData', 'confidentialFieldNames'); 
            // Hide show for auto delete log elements
            hideShow('autoDeleteLog', 'logDeleteAfterDays');
            // Hide show for related errors elements
            hideShow('showRelatedErrors', 'showRelatedErrorsOfDays');
            // Hide show for email notification elements
            // hideShow('EmailNotificationforRepeatedErrors', 'EmailNotificationforRepeatedErrors');

            /** Start:: Select2 for multiple selections **/
            $(".confidentialFieldNamesInput, .notificationReceiverEmailInputs, .skipErrorCodesInput")
                .select2({
                    width: '100%',
                    tags: true,
                    tokenSeparators: [',', ' ']
                });

            $(".severityLevel").select2({
                width: '100%',
            });
            /** End:: Select2 for multiple selections **/

            // Apply bootstrap tooltip for the elements
            var toolTipElements = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            toolTipElements.forEach(function(item) {
                new bootstrap.Tooltip(item, {
                    boundary: document.body
                })
            })
        });

        /** 
         * Description:: Function to hide and show elements
         * @parameter string "ID of the toggle button on which apply change event"
         * @parameter string "Class name of the element which need to hide and show"
         */
        function hideShow(toggleButtonElementId, hideShowElementClass) {
            var toggleButtonElement = document.getElementById(toggleButtonElementId);
            var hideShowElement = document.getElementsByClassName(hideShowElementClass);

            toggleButtonElement.addEventListener('change', function(event) {
                if (toggleButtonElement.checked) {
                    // Show element
                    for (const key in hideShowElement) {
                        if (hideShowElement.hasOwnProperty.call(hideShowElement, key)) {
                            hideShowElement[key].classList.remove('d-none');
                        }
                    }
                } else {
                    // Hide element
                    for (const key in hideShowElement) {
                        if (hideShowElement.hasOwnProperty.call(hideShowElement, key)) {
                            hideShowElement[key].classList.add('d-none');
                        }
                    }
                }
            });

            toggleButtonElement.dispatchEvent(new Event("change"));
        }
    </script>
@endsection
