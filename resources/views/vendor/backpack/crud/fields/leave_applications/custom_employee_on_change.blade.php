@include('crud::fields.relationship')

{{-- FIELD JS - will be loaded in the after_scripts section --}}
@push('crud_fields_scripts')
<script>
    $('[name="employee_id"]').on('select2:select', function (e) {
        var employee_id = $(this).val();

        $.ajax({
            type: "post",
            url: "{{ url(route('leaveapplication.employeeOnChange')) }}",
            data: {
                employee_id : employee_id
            },
            success: function (response) {
                // console.log(response); 
                if (response.id) {
                    $('[name="leave_approver_id"]').val(response.id);
                    $('[name="leave_approvers_textbox"]').val();
                    $('#leave_approvers_paragraph').html(response.approvers_name);
                }else {
                    $('[name="leave_approver_id"]').val();
                    $('#leave_approvers_paragraph').html('');
                }
            },
            error: function (response) {
                new Noty({
                    type: "danger",
                    title: "{!! trans('backpack::crud.employeeFieldOnChange_ajax_error_title') !!}",
                    text: "{!! trans('backpack::crud.employeeFieldOnChange_ajax_error_text') !!}",
                }).show();
            }
        });

    });
</script>
@endpush