<?php

namespace App\Http\Livewire\Datatable\Main;

use App\Enums\Role;
use Illuminate\Support\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button,
    Column,
    Exportable,
    Footer,
    Header,
    PowerGrid,
    PowerGridComponent,
    PowerGridColumns,
    Rules\Rule};
use WireUi\Traits\Actions;

final class User extends PowerGridComponent
{
    use ActionButton;
    use WithExport;
    use Actions;
    /*
    |--------------------------------------------------------------------------
    |  Features Setup
    |--------------------------------------------------------------------------
    | Setup Table's general features
    |
    */
    public function setUp(): array
    {
        $this->showCheckBox('id');

        return [
            // Exportable::make('export')
                // ->striped()
                // ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),

            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    |  Datasource
    |--------------------------------------------------------------------------
    | Provides data to your Table using a Eloquent, Query Builder or Collection
    |
    */

    /**
     * PowerGrid datasource.
     *
     * @return Builder
     */
    public function datasource(): Builder
    {
        return DB::table('users')->whereNotNull('email_verified_at');
    }

    /*
    |--------------------------------------------------------------------------
    |  Relationship Search
    |--------------------------------------------------------------------------
    | Configure here relationships to be used by the Search and Table Filters.
    |
    */

    /**
     * Relationship search.
     *
     * @return array<string, array<int, string>>
     */
    public function relationSearch(): array
    {
        return [];
    }

    /*
    |--------------------------------------------------------------------------
    |  Add Column
    |--------------------------------------------------------------------------
    | Make Datasource fields available to be used as columns.
    | You can pass a closure to transform/modify the data.
    |
    | ❗ IMPORTANT: When using closures, you must escape any value coming from
    |    the database using the `e()` Laravel Helper function.
    |
    */
    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('id')
            ->addColumn('name')
            ->addColumn('email')
            ->addColumn('phone')
            ->addColumn('role', function ($model) {
                return Role::from($model->role)->labels();
            });
    }

    /*
    |--------------------------------------------------------------------------
    |  Include Columns
    |--------------------------------------------------------------------------
    | Include the columns added columns, making them visible on the Table.
    | Each column can be configured with properties, filters, actions...
    |
    */

     /**
      * PowerGrid Columns.
      *
      * @return array<int, Column>
      */
    public function columns(): array
    {
        $text_dir = LaravelLocalization::getCurrentLocale() == 'en' ? 'text-left' : 'text-right';
        return [
            Column::make('Id', 'id')
                ->headerAttribute($text_dir)
                ->hidden(),

            Column::make(__('app.NAME'), 'name')
                ->headerAttribute($text_dir)
                ->sortable()
                ->searchable(),

            Column::make(__('app.EMAIL'), 'email')
                ->headerAttribute($text_dir)
                ->sortable()
                ->searchable(),

            Column::make(__('app.PHONE'), 'phone')
                ->headerAttribute($text_dir)
                ->sortable()
                ->searchable(),

            Column::make(__('app.ROLE'), 'role')
                ->headerAttribute($text_dir)
                ->sortable()
                ->searchable(),
        ];
    }

    /**
     * PowerGrid Filters.
     *
     * @return array<int, Filter>
     */
    public function filters(): array
    {
        return [
            Filter::inputText('name')->operators(['contains']),
            Filter::inputText('email')->operators(['contains']),
            Filter::inputText('phone')->operators(['contains']),
            Filter::enumSelect('role' ,'role')
                ->dataSource(Role::cases())
                ->optionLabel('role.role'),
        ];
    }
    public function header(): array
    {
        return [
            Button::add('bulk-delete')
                ->caption(__(__('app.TRASH').' (<span x-text="window.pgBulkActions.count(\'' . $this->tableName . '\')"></span>)'))
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-500 dark:hover:bg-pg-primary-700
                dark:ring-offset-pg-primary-800 dark:text-pg-primary-200 dark:bg-pg-primary-600')
                ->emit('bulkDelete', []),
        ];
    }

    protected function getListeners()
    {
        return array_merge(
            parent::getListeners(), [
                'bulkDelete',
            ]);
    }
    public function bulkDelete(): void
    {
        if (count($this->checkboxValues) == 0) {
            $this->notification()->warning(
                __('app.Warning'),
                __('app.You must select at least one item')
            );
        }else{
            $this->notification()->confirm([
                'title'       => __('app.Are you Sure'),
                'description' => __('app.Are you sure to move this to trash'),
                'icon'        => 'question',
                'accept'      => [
                    'label'  => __('app.Confirm'),
                    'method' => 'save',
                    'params' => 'Saved',
                ],
                'reject' => [
                    'method' => 'cancel',
                    'label'  => __('app.CANCEL'),
                ],
            ]);
            // $this->dispatchBrowserEvent('showAlert', ['items' => $this->checkboxValues]);
            return;
        }
    }
    public function cancel(){
        $this->notification()->success(
            __('app.CANCEL'),
            __('app.Canceld')
        );
    }

    public function save(){
        foreach($this->checkboxValues as $ids){
            DB::table('users')->where('id' , $ids)->whereNotIn('id' ,[1])->delete();
        }
    }

    public function actions(): array
    {
        return [
            Button::add('SetAsManager')
                ->class('bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-blue-400 border border-blue-400'),

            Button::add('SetAsCashier')
                ->class('bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-blue-400 border border-blue-400'),

            Button::add('SetAsUser')
                ->class('bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-blue-400 border border-blue-400')
        ];
    }

    public function actionRules(): array
    {
        return [
            // Rule::button('SetAsManager')
            //     ->caption(__('app.SETASMANAGER'))
            //     ->when(fn($model) => $model->role != Role::MANGER->value && $model->id != 1 && Auth::user()->role == 1)
            //     ->redirect(fn($model) => route('User.setManager',$model->id) ,'_self'),

            Rule::button('SetAsCashier')
                ->caption(__('app.SETASCASHIER'))
                ->when(fn($model) => $model->role != Role::CASHIER->value && $model->id != 1 && Auth::user()->role == 1)
                ->redirect(fn($model) => route('User.setCashier',$model->id) ,'_self'),

            Rule::button('SetAsUser')
                ->caption(__('app.SETASUSER'))
                ->when(fn($model) => $model->role != Role::USER->value && $model->id != 1 && Auth::user()->role == 1)
                ->redirect(fn($model) => route('User.setUser',$model->id) ,'_self'),
        ];
    }
}
