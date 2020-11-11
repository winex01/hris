{{-- regular object attribute --}}

{{-- NOTE:: use in preview for custom row attributes that dosn't exist in DB --}}
<span>
    @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_start')
        {!! $column['value'] !!}
    @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_end')
</span>
