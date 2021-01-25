@php
    
$activeInlineCreate = !empty($field['inline_create']) ? true : false;

if ($activeInlineCreate) {
    //by default, when creating an entity we want it to be selected/added to selection.
    $field['inline_create']['force_select'] = $field['inline_create']['force_select'] ?? true;

    $field['inline_create']['modal_class'] = $field['inline_create']['modal_class'] ?? 'modal-dialog';

    //if user don't specify a different entity in inline_create we assume it's the same from $field['entity'] kebabed
    $field['inline_create']['entity'] = $field['inline_create']['entity'] ?? $routeEntity;

    //route to create a new entity
    $field['inline_create']['create_route'] = route($field['inline_create']['entity']."-inline-create-save");

    //route to modal
    $field['inline_create']['modal_route'] = route($field['inline_create']['entity']."-inline-create");

    //include main form fields in the request when asking for modal data,
    //allow the developer to modify the inline create modal
    //based on some field on the main form
    $field['inline_create']['include_main_form_fields'] = $field['inline_create']['include_main_form_fields'] ?? false;

    if(!is_bool($field['inline_create']['include_main_form_fields'])) {
        if(is_array($field['inline_create']['include_main_form_fields'])) {
            $field['inline_create']['include_main_form_fields'] = json_encode($field['inline_create']['include_main_form_fields']);
        }else{
            //it is a string or treat it like
            $arrayed_field = array($field['inline_create']['include_main_form_fields']);
            $field['inline_create']['include_main_form_fields'] = json_encode($arrayed_field);
        }
    }
}

// dd($field);
@endphp
<!-- select2 from array -->
@include('crud::fields.inc.wrapper_start')
    <label>{!! $field['label'] !!}</label>

    {{-- +Add button --}}
    <button type="button" class="btn btn-sm btn-link float-right inline-create-button" onclick="setupInlineCreateButtons(this)" 
        {{-- add inline-create data-attributes --}}
        @include('crud::fields.relationship.field_attributes')

        data-inline-modal-class="{{ $field['inline_create']['modal_class'] }}"
        data-include-main-form-fields="{{ is_bool($field['inline_create']['include_main_form_fields']) ? var_export($field['inline_create']['include_main_form_fields']) : $field['inline_create']['include_main_form_fields'] }}"
    >
        <span class="la la-plus"></span>
        {{trans('backpack::crud.add')}}
    </button>

    <select
        name="{{ $field['name'] }}@if (isset($field['allows_multiple']) && $field['allows_multiple']==true)[]@endif"
        style="width: 100%"
        data-init-function="bpFieldInitSelect2FromArrayElement"
        @include('crud::fields.inc.attributes', ['default_class' =>  'form-control select2_from_array'])
        @if (isset($field['allows_multiple']) && $field['allows_multiple']==true)multiple @endif
        >

        @if (isset($field['allows_null']) && $field['allows_null']==true)
            <option value="">-</option>
        @endif

        @if (count($field['options']))
            @foreach ($field['options'] as $key => $value)
                @if((old(square_brackets_to_dots($field['name'])) && (
                        $key == old(square_brackets_to_dots($field['name'])) ||
                        (is_array(old(square_brackets_to_dots($field['name']))) &&
                        in_array($key, old(square_brackets_to_dots($field['name'])))))) ||
                        (null === old(square_brackets_to_dots($field['name'])) &&
                            ((isset($field['value']) && (
                                        $key == $field['value'] || (
                                                is_array($field['value']) &&
                                                in_array($key, $field['value'])
                                                )
                                        )) ||
                                (!isset($field['value']) && isset($field['default']) &&
                                ($key == $field['default'] || (
                                                is_array($field['default']) &&
                                                in_array($key, $field['default'])
                                            )
                                        )
                                ))
                        ))
                    <option value="{{ $key }}" selected>{{ $value }}</option>
                @else
                    <option value="{{ $key }}">{{ $value }}</option>
                @endif
            @endforeach
        @endif
    </select>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include('crud::fields.inc.wrapper_end')

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->fieldTypeNotLoaded($field))
    @php
        $crud->markFieldTypeAsLoaded($field);
    @endphp

    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')
    <!-- include select2 css-->
    <link href="{{ asset('packages/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('packages/select2-bootstrap-theme/dist/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
    <!-- include select2 js-->
    <script src="{{ asset('packages/select2/dist/js/select2.full.min.js') }}"></script>
    @if (app()->getLocale() !== 'en')
    <script src="{{ asset('packages/select2/dist/js/i18n/' . app()->getLocale() . '.js') }}"></script>
    @endif
    <script>
        function bpFieldInitSelect2FromArrayElement(element) {
            if (!element.hasClass("select2-hidden-accessible"))
                {
                    element.select2({
                        theme: "bootstrap"
                    }).on('select2:unselect', function(e) {
                        if ($(this).attr('multiple') && $(this).val().length == 0) {
                            $(this).val(null).trigger('change');
                        }
                    });
                }
        }

    

        {{-- TODO:: open model when add click is click --}}
        function setupInlineCreateButtons(element) {
            var $fieldEntity = $(element).attr('data-field-related-name');
            var $inlineCreateButtonElement = $(element).parent().find('.inline-create-button');
            var $inlineModalRoute = $(element).attr('data-inline-modal-route');
            var $inlineModalClass = $(element).attr('data-inline-modal-class');
            var $parentLoadedFields = $(element).attr('data-parent-loaded-fields');
            var $includeMainFormFields = $(element).attr('data-include-main-form-fields') == 'false' ? false : ($(element).attr('data-include-main-form-fields') == 'true' ? true : $(element).attr('data-include-main-form-fields'));


            //we change button state so users know something is happening.
            var loadingText = '<span class="la la-spinner la-spin" style="font-size:18px;"></span>';
            if ($(element).html() !== loadingText) {
                $(element).data('original-text', $(element).html());
                $(element).html(loadingText);
            }

            //prepare main form fields to be submited in case there are some.
            if(typeof $includeMainFormFields === "boolean" && $includeMainFormFields === true) {
                var $toPass = $form.serializeArray();
            }else{
                if(typeof $includeMainFormFields !== "boolean") {
                var $fields = JSON.parse($includeMainFormFields);
                var $serializedForm = $form.serializeArray();
                var $toPass = [];
                    $fields.forEach(function(value, index) {
                        $valueFromForm = $serializedForm.filter(field => field.name === value);
                        $toPass.push($valueFromForm[0]);

                    });

                    $includeMainFormFields = true;
                }
            }

            $.ajax({
                url: $inlineModalRoute,
                data: (function() {
                    if($includeMainFormFields) {
                        return {
                            'entity': $fieldEntity,
                            'modal_class' : $inlineModalClass,
                            'parent_loaded_fields' : $parentLoadedFields,
                            'main_form_fields' : $toPass
                        };
                    }else{
                        return {
                            'entity': $fieldEntity,
                            'modal_class' : $inlineModalClass,
                            'parent_loaded_fields' : $parentLoadedFields
                        };
                    }
                })(),
                type: 'POST',
                success: function (result) {
                    $('body').append(result);
                    triggerModal(element);

                },
                error: function (result) {
                    // Show an alert with the result
                    swal({
                        title: "error",
                        text: "error",
                        icon: "error",
                        timer: 4000,
                        buttons: false,
                    });
                }
            });
        }// end setupInlineCreateButtons



        //this is the function called when button to add is pressed,
        //it triggers the modal on page and initialize the fields
        function triggerModal(element) {
            var $fieldName = $(element).attr('data-field-related-name');
            var $modal = $('#inline-create-dialog');
            var $modalSaveButton = $modal.find('#saveButton');
            var $modalCancelButton = $modal.find('#cancelButton');
            var $form = $(document.getElementById($fieldName+"-inline-create-form"));
            var $inlineCreateRoute = $(element).attr('data-inline-create-route');
            var $ajax = $(element).attr('data-field-ajax') == 'true' ? true : false;
            var $force_select = ($(element).attr('data-force-select') == 'true') ? true : false;


            $modal.modal();

            initializeFieldsWithJavascript($form);

            $modalCancelButton.on('click', function () {
                $($modal).modal('hide');
            });

            //when you hit save on modal save button.
            $modalSaveButton.on('click', function () {

                $form = document.getElementById($fieldName+"-inline-create-form");

                //this is needed otherwise fields like ckeditor don't post their value.
                $($form).trigger('form-pre-serialize');

                var $formData = new FormData($form);

                //we change button state so users know something is happening.
                //we also disable it to prevent double form submition
                var loadingText = '<i class="la la-spinner la-spin"></i> saving...';
                if ($modalSaveButton.html() !== loadingText) {
                    $modalSaveButton.data('original-text', $(this).html());
                    $modalSaveButton.html(loadingText);
                    $modalSaveButton.prop('disabled', true);
                }


                $.ajax({
                    url: $inlineCreateRoute,
                    data: $formData,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    success: function (result) {

                        $createdEntity = result.data;

                        if(!$force_select) {
                            //if developer did not force the created entity to be selected we first try to
                            //check if created is still available upon model re-search.
                            ajaxSearch(element, result.data);

                        }else{
                            selectOption(element, result.data);
                        }

                        $modal.modal('hide');



                        new Noty({
                            type: "info",
                            text: '{{ trans('backpack::crud.related_entry_created_success') }}',
                        }).show();
                    },
                    error: function (result) {

                        var $errors = result.responseJSON.errors;

                        let message = '';
                        for (var i in $errors) {
                            message += $errors[i] + ' \n';
                        }

                        new Noty({
                            type: "error",
                            text: '<strong>{{ trans('backpack::crud.related_entry_created_error') }}</strong><br> '+message,
                        }).show();

                        //revert save button back to normal
                        $modalSaveButton.prop('disabled', false);
                        $modalSaveButton.html($modalSaveButton.data('original-text'));
                    }
                });
            });

            $modal.on('hidden.bs.modal', function (e) {
                $modal.remove();

                //when modal is closed (canceled or success submited) we revert the "+ Add" loading state back to normal.
                var $inlineCreateButtonElement = $(element).parent().find('.inline-create-button');
                $inlineCreateButtonElement.html($inlineCreateButtonElement.data('original-text'));
            });


            $modal.on('shown.bs.modal', function (e) {
                $modal.on('keyup',  function (e) {
                if($modal.is(':visible')) {
                    var key = e.which;
                        if (key == 13) { //This is an ENTER
                        e.preventDefault();
                        $modalSaveButton.click();
                    }
                }
                return false;
            });
            });
        }

        // TODO:: here naku test this
        // when an entity is created we query the ajax endpoint to check if the created option is returned.
        function ajaxSearch(element, created) {
            var $relatedAttribute = element.attr('data-field-attribute');
            var $relatedKeyName = element.attr('data-connected-entity-key-name');
            var $searchString = created[$relatedAttribute];
            var $appLang = element.attr('data-app-current-lang');

            //we run the promise with ajax call to search endpoint to check if we got the created entity back
            //in case we do, we add it to the selected options.
            performAjaxSearch(element, $searchString).then(result => {
                var inCreated = $.map(result.data, function (item) {
                    var $itemText = processItemText(item, $relatedAttribute, $appLang);
                    var $createdText = processItemText(created, $relatedAttribute, $appLang);
                    if($itemText == $createdText) {
                            return {
                                text: $itemText,
                                id: item[$relatedKeyName]
                            }
                        }
                });

                if(inCreated.length) {
                    selectOption(element, created);
                }
            });
        }

    </script>


    @endpush

@endif
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
