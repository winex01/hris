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
        if ($role == null) {
            $role = $this->crud->model->getTable();
        }

        // check access for current role
        $this->checkAccess($role);
        // check access for admin
        $this->checkAccess('admin');

        // filters
        $this->trashedFilter();
        $this->employeeFilter();
    }

    private function employeeFilter()
    {
        // show filter employee if model belongs to emp model
        if (method_exists($this->crud->model, 'employee')) {
            $this->crud->addFilter([
                    'name'  => 'employee',
                    'type'  => 'custom_employee_filter',
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
            if (session('employee_filter') == null) {
                session()->put('employee_filter', []);
            }

            $currentRoute = \Str::slug($this->crud->getRoute());
            if (!in_array($currentRoute, session('employee_filter'))) {
                session()->push('employee_filter', $currentRoute);
            }

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
        if ($role != null) {
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

            // dd($permissions);
            
            // allow access if user have permission
            $this->crud->allowAccess($permissions);

            // show always column visibility button
            $this->crud->enableExportButtons();

        }//end if $role != null
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

    public function addInlineCreateField($columnId, $permission = null)
    {
        $col = str_replace('_id', '', $columnId);
        $method = \Str::camel($col);
        $permission = ($permission == null) ? \Str::plural($col).'_create' : $permission;
        $entity = str_replace('_', '', $col);

        $this->crud->removeField($columnId);
        $this->crud->addField([
            'name'          => $method, 
            'label'         => trans('lang.'.$col),
            'type'          => 'relationship',
            'ajax'          => false,
            'allows_null'   => false, 
            'inline_create' => hasAuthority($permission) ? ['entity' => $entity] : null
        ])->afterField('employee_id');
    }

    public function addSelectEmployeeField()
    {
        $this->crud->modifyField('employee_id', [
            'label'     => "Employee",
            'type'      => 'select2',
            'attribute' => 'full_name_with_badge',
            'options'   => (function ($query) {
                return $query
                ->orderBy('last_name')
                ->orderBy('first_name')
                ->orderBy('middle_name')
                ->orderBy('badge_id')
                ->get();
            }),
        ]);
    }

    public function currencyField($fieldName)
    {
        $this->crud->modifyField($fieldName, [
            'attributes' => ["step" => "any", 'placeholder' => 'Enter Amount'], // allow decimals
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
                    'label'       => ucwords(str_replace('_', ' ', $col)),
                    'type'        => 'radio',
                    'default' => 0,
                    'options' => booleanOptions(),
                    'tab'         => $tab,
                ]);

                continue;
            }

            $type = $this->fieldTypes()[$dataType];

            $this->crud->addField([
                'name'        => $col,
                'label'       => ucwords(str_replace('_', ' ', $col)),
                'type'        => $type,
                'tab'         => $tab,
                'attributes'  => [
                    'placeholder' => trans('lang.'.$table.'_'.$col)
                ]
            ]);
        }

    }

    public function fieldTypes()
    {
        $fieldType = [
            'varchar' => 'text',
            'date'    => 'date',
            'text'    => 'textarea',
            'double'  => 'number',
            'decimal' => 'number',
            'bigint'  => 'number',
            'int'     => 'number',
            'tinyint' => 'boolean',
        ];

        return $fieldType;
    }

    /*
    |--------------------------------------------------------------------------
    | Preview / show
    |--------------------------------------------------------------------------
    */
    public function showRelationshipColumn($columnId, $relationshipColumn = 'name')
    {
        $col = str_replace('_id', '', $columnId);
        $method = \Str::camel($col);
        $this->crud->modifyColumn($columnId, [
           'label' => trans('lang.'.$col),
           'type'     => 'closure',
            'function' => function($entry) use ($method) {
                return $entry->{$method}->name;
            },
            'searchLogic' => function ($query, $column, $searchTerm) use ($method, $relationshipColumn) {
                $query->orWhereHas($method, function ($q) use ($column, $searchTerm, $relationshipColumn) {
                    $q->where($relationshipColumn, 'like', '%'.$searchTerm.'%');
                });
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
                'label' => ucwords(str_replace('_', ' ', $col)),
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
}