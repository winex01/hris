{{-- hide all buttons in softDeleted or when trashed filter is active --}}
@if ($entry->deleted_at == null) 
    @php
    // hide all line buttons first
    $crud->denyAccess(lineButtons());

    // show or allow access only if meet condition here
    if ($entry->status == 1) { // status == approves
        $crud->AllowAccess([
            'status',
            'show',
            'revise',
        ]);
    }else {
        $crud->AllowAccess(lineButtons());
    }

    @endphp

    {{-- NOTE:: dont forget to add status button business logic --}}
    @include('crud::buttons.custom_status')
@endif