@extends('error-lens::layouts.app')

@section('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <h2 class="my-4">Configurations</h2>
    <x-error-lens::alert-message />
    <div class="row">
        <div class="col-6">
            <div class="row sortingGroup" data-index='gp1'>
                @isset($repositions['gp1'])
                    @foreach ($repositions['gp1'] as $cardName)
                        <div class="col-12 mb-4">
                            @include('error-lens::config.' . $cardName)
                        </div>
                    @endforeach
                @endisset
            </div>
        </div>
        <div class="col-6">
            <div class="row sortingGroup" data-index='gp2'>
                @isset($repositions['gp2'])
                    @foreach ($repositions['gp2'] as $cardName)
                        <div class="col-12 mb-4">
                            @include('error-lens::config.' . $cardName)
                        </div>
                    @endforeach
                @endisset
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Do you wish to save the repositioned config cards?
                </div>
                <div class="modal-footer flex justify-content-center">
                    <button type="button" class="btn btn-primary save-configs">Save</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('error-lens.config.store-config-reposition') }}" method="post" id="configRepositionForm">
        @csrf
        @method('POST')
        <input type="hidden" name="configReposition">
    </form>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.2/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hide show for requested data elements
            hideShow('storeRequestedData', 'confidentialFieldNames');
            // Hide show for auto delete log elements
            hideShow('autoDeleteLog', 'logDeleteAfterDays');
            // Hide show for related errors elements
            hideShow('showRelatedErrors', 'showRelatedErrorsOfDays');
            // Hide show for custom environment setup
            hideShow('haventProductionEnv', 'customEnvName');

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

    <script>
        try {
            var sortingGroups = document.getElementsByClassName('sortingGroup');
            for (const key in sortingGroups) {
                if (sortingGroups.hasOwnProperty.call(sortingGroups, key)) {
                    const sortingGroup = sortingGroups[key];
                    new Sortable(sortingGroup, {
                        group: 'shared',
                        handle: ".cursor-grab",
                        animation: 150,
                        onStart: function(evt,originalEvent) {
                            $('.sortingGroup').addClass('draggableGroup');
                        },
                        onEnd: function(evt) {
                            $('.sortingGroup').removeClass('draggableGroup');
                            getIndices((sortingData) => {
                                // Show confirmation box
                                showConfirmation((response) => {
                                    // while click on confirm button
                                    if (response) {
                                        saveRepositions(sortingData);
                                    }
                                });
                            });
                        }
                    });
                }
            }
        } catch (error) {
            console.error(error);
        }


        // Initially call the function
        getIndices((sortingData) => {});

        /********************* Define functions *********************/
        function showConfirmation(callback) {
            var confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'), {
                backdrop: 'static',
                keyboard: false
            });
            confirmationModal.show();

            $('.save-configs').off().on('click', function() {
                confirmationModal.hide();
                callback(1);
            });
        }

        function getIndices(callback) {
            var sortingData = {};
            for (const sortingGroup of sortingGroups) {
                const cardCustoms = sortingGroup.querySelectorAll('.card_custom');
                const sortingGroupIndex = sortingGroup.getAttribute('data-index');
                for (const key in cardCustoms) {
                    if (cardCustoms.hasOwnProperty.call(cardCustoms, key)) {
                        const cardCustom = cardCustoms[key];
                        const cardCustomIndex = cardCustom.getAttribute('data-index');
                        // console.log(sortingGroupIndex, key, cardCustomIndex); 
                        sortingData[sortingGroupIndex + '-' + key] = cardCustomIndex;
                    }
                }
            }
            callback(sortingData);
        }

        function saveRepositions(sortingData) {
            const configRepositionForm = document.getElementById('configRepositionForm');
            const configReposition = configRepositionForm.querySelector('[name=configReposition]');
            configReposition.value = btoa(JSON.stringify(sortingData));
            configRepositionForm.submit();
        }
    </script>
@endsection
