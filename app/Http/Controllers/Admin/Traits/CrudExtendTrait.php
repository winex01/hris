<?php 

namespace App\Http\Controllers\Admin\Traits;

/**
 * import in backpack crud controller
 * use in backpack crud controller
 */
trait CrudExtendTrait
{
    /*
    |--------------------------------------------------------------------------
    | Check Auth User Permissions
    |--------------------------------------------------------------------------
    */ 
    public function userPermissions($role = null)
    {
        // check access for current role & admin
        $this->checkAccess($role);
        $this->checkAccess('admin');

         // filters
        $this->trashedFilter();
        $this->employeeFilter();

        // rename entry label and button
        $this->crud->setEntityNameStrings($this->buttonLabel(), $this->entryLabel());

        // show always column visibility button
        $this->crud->enableExportButtons();
    }

    private function employeeFilter()
    {
        // show filter employee if model belongs to emp model
        if (method_exists($this->crud->model, 'employee')) {
            $this->crud->addFilter([
                    'name'  => 'employee',
                    // 'type'  => 'custom_employee_filter',
                    'type'  => 'select2',
                    'label' => 'Select Employee',
                ],
                function () {
                  return \App\Models\Employee::
                            orderBy('last_name')
                            ->orderBy('first_name')
                            ->orderBy('middle_name')
                            ->orderBy('badge_id')
                            ->get(['id', 'last_name', 'first_name', 'middle_name', 'badge_id'])
                            ->pluck("full_name_with_badge", "id")
                            ->toArray();
                },
                function ($value) { // if the filter is active
                    $this->crud->addClause('where', 'employee_id', $value);
                }
            );

            // 
            // if (session('employee_filter') == null) {
            //     session()->put('employee_filter', []);
            // }

            // $currentRoute = \Str::slug($this->crud->getRoute());
            // if (!in_array($currentRoute, session('employee_filter'))) {
            //     session()->push('employee_filter', $currentRoute);
            // }

        }//end if
    }

    private function trashedFilter()
    {
        // filter deleted
        if (hasAuthority('admin_trashed_filter')) {
            // if soft delete is enabled
            if ($this->crud->model->soft_deleting) {
                $this->crud->addFilter([
                  'type'  => 'simple',
                  'name'  => 'trashed',
                  'label' => 'Trashed'
                ],
                false,
                function($values) { // if the filter is active
                    $this->crud->query = $this->crud->query->onlyTrashed();
                });
            }//end if soft delete enabled
        }//end hasAuth
    }

    private function checkAccess($role)
    {
        $role = ($role == null) ? $this->crud->model->getTable() : $role;

        $allRolePermissions = \App\Models\Permission::where('name', 'LIKE', "$role%")
                            ->pluck('name')->map(function ($item) use ($role) {
                                $value = str_replace($role.'_', '', $item);
                                $value = \Str::camel($value);
                                return $value;
                            })->toArray();

        // deny all access first
        $this->crud->denyAccess($allRolePermissions);

        $permissions = auth()->user()->getAllPermissions()
            ->pluck('name')
            ->filter(function ($item) use ($role) {
                return false !== stristr($item, $role);
            })->map(function ($item) use ($role) {
                $value = str_replace($role.'_', '', $item);
                $value = \Str::camel($value);
                return $value;
            })->toArray();

        // allow access if user have permission
        $this->crud->allowAccess($permissions);
    }

    /*
    |--------------------------------------------------------------------------
    | Fields
    |--------------------------------------------------------------------------
    */
    public function addBooleanField($col)
    {
        $this->crud->modifyField($col, [
            'type'    => 'radio',
            'default' => 0,
            'options' => booleanOptions(),
        ]);
    }

    public function addInlineCreateField($columnId, $entity = null, $permission = null)
    {
        $col = str_replace('_id', '', $columnId);
        $permission = ($permission == null) ? \Str::plural($col).'_create' : $permission;
        $entity = ($entity == null) ? str_replace('_', '', $col) : $entity;

        $this->crud->modifyField($columnId, [
            'label'         => convertColumnToHumanReadable($col),
            'type'          => 'relationship',
            'ajax'          => false,
            'allows_null'   => true,
            'placeholder'   => trans('lang.select_placeholder'), 
            'inline_create' => hasAuthority($permission) ? ['entity' => $entity] : null,

            // need for camel case relationship name, ex: civilStatus
            'model'         => 'App\Models\\'.convertToClassName($columnId),
            'entity'        => relationshipMethodName($columnId),
            'relation_type' => 'BelongsTo',
            'multiple'      => false,
        ]);
    }

    public function addSelectEmployeeField()
    {   
        $field = 'employee_id';
        $this->crud->removeField($field);
        $this->crud->addField([
            'name'          => $field, 
            'label'         => convertColumnToHumanReadable($field),
            'type'          => 'relationship',
            'attribute'     => 'full_name_with_badge',
            'ajax'          => false,
            'allows_null'   => true,
            'placeholder'   => trans('lang.select_placeholder'), 
            'inline_create' => null
        ])->makeFirstField();
    }

    public function currencyField($fieldName)
    {
        $this->crud->modifyField($fieldName, [
            'attributes' => [
                'step' => config('hris.inputbox_decimal_precision'), 
                'placeholder' => 'Enter Amount'
            ],
            'prefix'     => trans('lang.currency'),
        ]);
    }

    public function addAttachmentField($fieldName = 'attachment')
    {
        // attachment field
        $this->crud->modifyField($fieldName, [
            'type'      => 'upload',
            'upload'    => true,
            'disk'      => 'public', 
            'hint'      => 'File must be less than <b>'.
                            convertKbToMb(config('settings.hris_attachment_file_limit'))
                            .'MB</b>',   
        ]);
    }

    public function inputs($table = null, $tab = null, $removeOthers = null)
    {
        if ($table == null) {
            $table = $this->crud->model->getTable();
        }

        $columns = getTableColumnsWithDataType($table, $removeOthers);
        
        foreach ($columns as $col => $dataType) {

            if ($dataType == 'tinyint') {
                // boolean
                $this->crud->addField([
                    'name'        => $col,
                    'label'       => convertColumnToHumanReadable($col),
                    'type'        => 'radio',
                    'default' => 0,
                    'options' => booleanOptions(),
                    'tab'         => $tab,
                ]);

                continue;
            }

            $type = $this->fieldTypes()[$dataType];

            if ($dataType == 'date') {
                // if dataType is date then dont use in fieldTypes
                // bec. thats prefer for showColumns, field must be
                // date in field.
                $type = 'date';
                // $type = 'date_picker';
            }

            $this->crud->addField([
                'name'        => $col,
                'label'       => convertColumnToHumanReadable($col),
                'type'        => $type,
                'tab'         => $tab,
                'attributes'  => [
                    'placeholder' => trans('lang.'.$table.'_'.$col)
                ]
            ]);
        }

    }

    // NOTE:: this prioritize showColumns
    public function fieldTypes()
    {
        $fieldType = [
            'varchar'   => 'text',
            'timestamp' => 'text',
            'json'      => 'table',
            'text'      => 'textarea',
            'double'    => 'number',
            'float'     => 'number',
            'decimal'   => 'number',
            'bigint'    => 'number',
            'int'       => 'number',
            'tinyint'   => 'boolean',
            'date'      => config('hris.date_format'), // if input field = date
        ];

        return $fieldType;
    }

    public function reorderFields()
    {
        return [
            'parent_id',
            'lft',
            'rgt',
            'depth',
        ];
    }
    /*
    |--------------------------------------------------------------------------
    | Preview / show
    |--------------------------------------------------------------------------
    */
    public function showRelationshipColumn($columnId, $relationshipColumn = 'name')
    {
        $col = str_replace('_id', '', $columnId);
        $method = relationshipMethodName($col);
        $currentTable = $this->crud->model->getTable();

        $this->crud->modifyColumn($columnId, [
           'label' => convertColumnToHumanReadable($col),
           'type'     => 'closure',
            'function' => function($entry) use ($method, $relationshipColumn) {
                if ($entry->{$method} == null) {
                    return;
                }
                return $entry->{$method}->{$relationshipColumn};
            },
            'searchLogic' => function ($query, $column, $searchTerm) use ($method, $relationshipColumn) {
                $query->orWhereHas($method, function ($q) use ($column, $searchTerm, $relationshipColumn) {
                    $q->where($relationshipColumn, 'like', '%'.$searchTerm.'%');
                });
            },
            'orderLogic' => function ($query, $column, $columnDirection) use ($currentTable, $col, $relationshipColumn) {
                $table = classInstance(convertToClassName($col))->getTable();
                return $query->leftJoin($table, $table.'.id', '=', $currentTable.'.'.$col.'_id')
                        ->orderBy($table.'.'.$relationshipColumn, $columnDirection)
                        ->select($currentTable.'.*');
            }
        ]);
    }

    public function showEmployeeNameColumn()
    {
        $currentTable = $this->crud->model->getTable();

        $this->crud->modifyColumn('employee_id', [
           'label'     => 'Employee',
           'type'     => 'closure',
            'function' => function($entry) {
                return $entry->employee->full_name_with_badge;
            },
            'wrapper'   => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url('employee/'.$entry->employee_id.'/show');
                },
                'class' => trans('lang.link_color')
            ],
            'orderLogic' => function ($query, $column, $columnDirection) use ($currentTable) {
                return $query->leftJoin('employees', 'employees.id', '=', $currentTable.'.employee_id')
                        ->orderBy('employees.last_name', $columnDirection)
                        ->orderBy('employees.first_name', $columnDirection)
                        ->orderBy('employees.middle_name', $columnDirection)
                        ->orderBy('employees.badge_id', $columnDirection)
                        ->select($currentTable.'.*');
            },
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('employee', function ($q) use ($column, $searchTerm) {
                    $q->where('last_name', 'like', '%'.$searchTerm.'%')
                      ->orWhere('first_name', 'like', '%'.$searchTerm.'%')
                      ->orWhere('middle_name', 'like', '%'.$searchTerm.'%')
                      ->orWhere('badge_id', 'like', '%'.$searchTerm.'%');
                });
            }
        ]);
    }

    public function showEmployeeNameColumnUnsortable()
    {
        $currentTable = $this->crud->model->getTable();
        $this->crud->modifyColumn('employee_id', [
           'label'     => 'Employee'.trans('lang.unsortable_column'),
           'type'     => 'closure',
            'function' => function($entry) {
                return $entry->employee->full_name_with_badge;
            },
            'wrapper'   => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url('employee/'.$entry->employee_id.'/show');
                },
                'class' => trans('lang.link_color')
            ],
            'orderable' => false,
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('employee', function ($q) use ($column, $searchTerm) {
                    $q->where('last_name', 'like', '%'.$searchTerm.'%')
                      ->orWhere('first_name', 'like', '%'.$searchTerm.'%')
                      ->orWhere('middle_name', 'like', '%'.$searchTerm.'%')
                      ->orWhere('badge_id', 'like', '%'.$searchTerm.'%');
                });
            }
        ]);
    }

    public function currencyColumnFormatted($fieldName, $decimals = null)
    {
        if ($decimals == null) {
            $decimals = config('hris.decimal_precision');
        }

        $this->crud->modifyColumn($fieldName, [
            'type'        => 'number',
            'prefix'      => trans('lang.currency'),
            'decimals'    => $decimals,
            'dec_point'   => '.',
            'searchLogic' => function ($query, $column, $searchTerm) use ($fieldName) {
                $searchTerm = str_replace(',', '', $searchTerm);
                $searchTerm = str_replace(trans('lang.currency'), '', $searchTerm);
                $query->orWhere($fieldName, 'like', '%'.$searchTerm.'%');
            }
        ]);
    }

    public function showColumns($table = null, $removeOthers = null)
    {
        if ($table == null) {
            $table = $this->crud->model->getTable();
        }

        $columns = getTableColumnsWithDataType($table, $removeOthers);

        foreach ($columns as $col => $dataType) {
            $type = $this->fieldTypes()[$dataType];
            $type = (stringContains($col, 'email')) ? 'email' : $type;

            $this->crud->addColumn([
                'name'  => $col,
                'label' => convertColumnToHumanReadable($col),
                'type' => $type,
            ]);
        }

    }

    public function downloadableAttachment($attachment = null)
    {
        if ($attachment == null) {
            $attachment = 'attachment';
        }

        $this->crud->modifyColumn($attachment, [
            'type'     => 'closure',
            'function' => function($entry) {
                return $entry->downloadAttachment();
            }
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Forms
    |--------------------------------------------------------------------------
    */
    public function uniqueRules($table, $requestInput = 'id')
    {
        return \Illuminate\Validation\Rule::unique($table)->ignore(
            request($requestInput)
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Misc.
    |--------------------------------------------------------------------------
    */
    public function classInstance($class) 
    {
        return classInstance($class);
    }

    public function entryLabel()
    {
        return \Str::plural(convertColumnToHumanReadable($this->crud->model->model));
    }

    public function buttonLabel()
    {
        return convertColumnToHumanReadable($this->crud->model->model);
    }

    public function downloadableHint($hint, $file)
    {
        $this->crud->addField([
            'name' => 'temp',
            'label' => '',
            'attributes' => [
                'hidden' => true
            ],
            'hint' => '<a download href="'.backpack_url($file).'">'.$hint.'</a>',
        ]);
    }

    public function hint($hint, $afterField = null)
    {

        if ($afterField != null) {
            $this->crud->addField([
                'name' => \Str::snake($hint).'_temp',
                'label' => '',
                'attributes' => [
                    'hidden' => true
                ],
                'hint' => $hint,
            ])->afterField($afterField);
        }else {
            $this->crud->addField([
                'name' => \Str::snake($hint).'_temp',
                'label' => '',
                'attributes' => [
                    'hidden' => true
                ],
                'hint' => $hint,
            ]);
        }
    }// end hint
}