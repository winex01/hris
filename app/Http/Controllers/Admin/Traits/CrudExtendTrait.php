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

        // dont include items that has relationship soft deleted
        foreach ($this->hasRelationshipTo() as $temp) {
            if (method_exists($this->crud->model, $temp) && $this->crud->model->getTable() != 'users') { // users crud allow nullable employee_id
                $this->crud->addClause('has', $temp); 
            }
        }
    }

    // dont include items that has relationship soft deleted
    public function hasRelationshipTo()
    {
        return [
            'employee'
        ];
    }

    private function employeeFilter($column = 'employee_id')
    {
        // show filter employee if model belongs to emp model
        if (method_exists($this->crud->model, 'employee') || $column == 'id') {
            $this->crud->addFilter([
                    'name'  => 'employee',
                    // 'type'  => 'custom_employee_filter',
                    'type'  => 'select2',
                    'label' => 'Select Employee',
                ],
                function () {
                  return employeeLists();
                },
                function ($value) use ($column) { // if the filter is active
                    if ($column == 'employee_id') {
                        $this->crud->addClause('where', $column, $value);
                    }elseif ($column == 'id') {
                        $this->crud->query->employeeWithId($value);
                    }else {
                        // do nothing
                    }
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
                  // 'type'  => 'simple',
                  'type'  => 'custom_simple_hide_bottom_buttons',
                  'name'  => 'trashed',
                  'label' => 'Trashed'
                ],
                false,
                function($values) { // if the filter is active
                    $this->crud->query = $this->crud->query->onlyTrashed();
                    disableLineButtons($this->crud);
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
        // debug($allRolePermissions);
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
        // debug($permissions);
        $this->crud->allowAccess($permissions);
    }

    /*
    |--------------------------------------------------------------------------
    | Fields
    |--------------------------------------------------------------------------
    */
    public function addTimestampField($col)
    {
        $this->crud->modifyField($col, [
            'type'    => 'datetime',
        ]);
    }

    public function transferFieldAfter($field, $afterField, $type = 'text')
    {
        $table = $this->crud->model->getTable();

        $this->crud->removeField($field);
        $this->crud->addField([
            'name'        => $field,
            'label'       => convertColumnToHumanReadable($field),
            'type'        => $type,
            'attributes'  => [
                'placeholder' => trans('lang.'.$table.'_'.$field)
            ]
        ])->afterField($afterField);
    }

    public function addRelationshipField($field, $entity = null, $model = null, $attribute = 'name')
    {
        if ($entity == null) {
            $entity = relationshipMethodName($field);
        }

        if ($model == null) {
            $model  = "App\Models\\".ucfirst(relationshipMethodName($field));
        }

        $this->crud->modifyField($field, [
            'type' => 'select2',
            'entity'    => $entity, 
            'model'     => $model, // related model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'allows_null' => true
        ]);
    }

    public function addBooleanField($col)
    {
        $this->crud->modifyField($col, [
            'type'    => 'radio',
            'label'   => convertColumnToHumanReadable($col),
            'default' => 0,
            'options' => booleanOptions(),
        ]);
    }

    public function addInlineCreatePivotField($field, $entity = null, $permission = null, $dataSource = null)
    {
        $permission = ($permission == null) ? \Str::plural($col).'_create' : $permission;
        $entity = ($entity == null) ? $field : $entity;

        $table = $this->crud->model->getTable();

        $this->crud->addField([
            'name'          => $field,
            'label'         => convertColumnToHumanReadable($field),
            'type'          => 'relationship',
            'ajax'          => false,
            'allows_null'   => true,
            'placeholder'   => trans('lang.select_placeholder'), 
            'inline_create' => hasAuthority($permission) ? ['entity' => $entity] : null,
            'data_source'   => url($dataSource), 
            'hint'          => trans('lang.'.$table.'_'.$field.'_hint'),
            'placeholder'   => trans('lang.select_placeholder'),
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
            'type'        => 'number',
            'prefix'      => trans('lang.currency'),
            'attributes'  => [
                'step'        => config('appsettings.inputbox_decimal_precision'),
                'placeholder' => 'Enter Amount'
            ],
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
                // $type = 'date';
                // $type = 'date_picker';
                $type = $this->dateFieldType();
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

    public function dateFieldType()
    {
        // return 'date_picker';
        return 'date';
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
            'smallint'  => 'number',
            'tinyint'   => 'boolean',
            'date'      => config('appsettings.date_column_format'), // if input field = date
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
    | Columns Related Stuff
    |--------------------------------------------------------------------------
    */
    public function convertColumnToDouble($col, $precision = 2)
    {
        $this->crud->modifyColumn($col, [
            'decimals' => $precision // modified this column bec. of leave_credit field type = number
        ]);
    }

    public function addColumnTitle($col, $title = 'description', $class = null)
    {
        if ($class == null) {
            $class = trans('lang.column_title_text_color');
        }

        $this->crud->modifyColumn($col, [
            'wrapper'   => [
                'span' => function ($crud, $column, $entry, $related_key) use ($col) {
                    return $entry->{$col};
                },
                'title' => function ($crud, $column, $entry, $related_key) use ($col, $title) {
                    return $entry->{relationshipMethodName($col)}->$title;
                },
                'class' => $class
            ],
        ]);
    }

    public function booleanColumn($col, $true = 'Open', $false = 'Close')
    {
        $this->crud->modifyColumn($col, [
            'wrapper' => [
                'element' => 'span',
                'class' => function ($crud, $column, $entry, $related_key) use ($true) {
                    if ($column['text'] == $true) {
                        return 'badge badge-success';
                    }
                    return 'badge badge-default';
                },
            ],
            'options' => [0 => $false, 1 => $true]
        ]);
    }

    public function renameLabelColumn($column, $newLabel)
    {
        $this->crud->modifyColumn($column, [
            'label' => $newLabel
        ]);
    }

    public function showRelationshipPivotColumn($column, $entity = null, $model = null, $attribute = 'name')
    {
        if ($entity == null) {
            $entity = relationshipMethodName($column);
        }

        if ($model == null) {
            $model  = "App\Models\\".ucfirst(relationshipMethodName($column));
        }

        $this->crud->addColumn([
            // n-n relationship (with pivot table)
           'label'     => convertColumnToHumanReadable($column), // Table column heading
           'type'      => 'select_multiple',
           'name'      => $column, // the method that defines the relationship in your Model
           'entity'    => $entity, // the method that defines the relationship in your Model
           'attribute' => $attribute, // foreign key attribute that is shown to user
           'model'     => $model, // foreign key model
        ]);
    }

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

    public function showTimestampColumn($col, $format = 'YYYY-MM-D HH:mm A')
    {
        $this->crud->modifyColumn($col, [
            'format' => $format,
            'type' => 'datetime',
        ]);
    }

    public function showEmployeeNameColumn($type = 'modify')
    {
        if ($type == 'add') {
            $this->crud->addColumn([
               'name'  => 'employee',
               'label' => 'Employee', // Table column heading
               'type'  => 'model_function',
               'function_name' => 'employeeNameAnchor', // the method in your Model
               'limit' => 200// if not specified it won't show the string in anchor
            ]);
            return; 
        }

        $currentTable = $this->crud->model->getTable();

        $this->crud->modifyColumn('employee_id', [
           'label'     => 'Employee',
           'type'     => 'closure',
            'function' => function($entry) {
                if ($entry->employee) {
                    return $entry->employee->full_name_with_badge;
                }

                return;
            },
            'wrapper'   => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    return employeeInListsLinkUrl($entry->employee_id);
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
            $decimals = config('appsettings.decimal_precision');
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

            if ($type == 'boolean') {
                $this->crud->modifyColumn($col, [
                    'wrapper' => [
                        'element' => 'span',
                        'class' => function ($crud, $column, $entry, $related_key) {
                            if ($column['text'] == 'Yes') {
                                return 'badge badge-success';
                            }
                            return 'badge badge-default';
                        },
                    ],
                ]);
            }// end if type == boolean
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

    public function uniqueRulesMultiple($table, $whereLists = [], $whereNotEqualLists = [])
    {
        return \Illuminate\Validation\Rule::unique($table)->where(function ($query) use ($whereLists, $whereNotEqualLists) {
            // where
            foreach ($whereLists as $col => $value) {
                $query->where($col, $value);
            }

            // where not equal
            foreach ($whereNotEqualLists as $col => $value) {
                $query->where($col, '!=', $value);
            }

            return $query->whereNull('deleted_at'); // ignore softDeleted
         });
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

    public function dumpAllRequest()
    {
        dd(request()->all());
    }
}