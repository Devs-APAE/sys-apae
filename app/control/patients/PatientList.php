<?php

/**
 * PatientList Listing
 * @author  Marcos David Souza Ramos
 */
class PatientList extends TPage
{
    private $form;
    private $datagrid;
    private $pageNavigation;
    private $loaded;
    public function __construct()
    {
        parent::__construct();

        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_patient');
        $this->form->setFormTitle('LISTAGEM DE PACIENTES');
        $this->form->setFieldSizes('100%');

        $name = new TEntry('name');
        $name->placeholder = "Nome do Paciente";
        
        $cpf = new TEntry('cpf');
        $cpf->setMask('999.999.999-99', true);
        $cpf->placeholder = "000.000.000-00";

        $sus = new TEntry('sus');

        $birth = new TDate('birth');
        $birth->setMask("dd/mm/yyyy");
        $birth->setDatabaseMask("yyyy-mm-dd");
        $birth->placeholder = "dd/mm/aaaa";

        $active = new TCombo('active');
        $active->addItems([ 'Y' => 'Sim', 'N' => 'Não' ]);
        $active->setDefaultOption("Selecione");

        /*$output_type = new TRadioGroup('output_type');
        $output_type->addItems(array('pdf'=>'PDF', 'xls' => 'XLS', 'html'=>'HTML'));
        $output_type->setLayout('horizontal');
        $output_type->setUseButton();
        $output_type->setValue('pdf');*/

        $row = $this->form->addFields(
            [new TLabel('Pesquisa'), $name],
            [new TLabel('CPF'), $cpf],
            [new TLabel('SUS'), $sus],
            [new TLabel('Nascimento'), $birth],
            [new TLabel('Ativo'), $active],
           // [ new TLabel('Formato'), $output_type ], 
        );
        $row->layout = ['col-sm-4', 'col-sm-2', 'col-sm-2', 'col-sm-2', 'col-sm-2'];

        $this->form->setData(TSession::getValue('PatientList' . '_filter_data'));

        $btn = $this->form->addAction('Pesquisar', new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-default';

        $btn = $this->form->addAction('Limpar', new TAction([$this, 'onClear']), 'fa:eraser');
        $btn->class = 'btn btn-sm btn-default';

        $btn = $this->form->addActionLink(('Novo Paciente'), new TAction(['PatientForm', 'onEdit']), 'fa:plus');
        $btn->class = 'btn btn-sm btn-primary right';

        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->disableDefaultClick();
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->datatable = 'true';

        $column_id = new TDataGridColumn('id', 'ID', 'center');
        $column_name = new TDataGridColumn('name', 'Nome', 'left');
        $column_cpf = new TDataGridColumn('cpf', 'CPF', 'center');
        $column_responsible = new TDataGridColumn('responsible', 'Responsável', 'left');
        $column_phone = new TDataGridColumn('phone', 'Celular', 'center');
        $column_created_at = new TDataGridColumn('created_at', 'Cadastro', 'center');
        $column_active = new TDataGridColumn('active', 'Ativo', 'center');

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_name);
        $this->datagrid->addColumn($column_cpf);
        $this->datagrid->addColumn($column_responsible);
        $this->datagrid->addColumn($column_phone);
        $this->datagrid->addColumn($column_created_at);
        $this->datagrid->addColumn($column_active);

        $column_id->setTransformer(function ($value, $object, $row) {
            $row->style = "vertical-align: middle;";
            return $value;
        });

        $column_cpf->setTransformer(function ($value, $object, $row) {
            return AppHelper::formatCpfCnpj($value); 
        });

        $column_phone->setTransformer(function ($value, $object, $row) {
            return AppHelper::formatPhone($value); 
        });

        $column_created_at->setTransformer(function ($value, $object, $row) {
            return AppHelper::toDateBR($value); 
        });

        $column_active->setTransformer(function ($value, $object, $row) {
            $div = new TElement('span');
            $div->class = $value == 'Y' ? 'label label-success' : 'label label-danger';
            $div->style = 'text-shadow:none; font-size:10px; white-space: nowrap;';
            $div->add($value == 'Y' ? 'Sim' : 'Não');
            return $div;
        });

        $action_edit = new TDataGridAction(['PatientForm', 'onEdit']);
        $action_edit->setButtonClass('btn btn-sm btn-primary');
        $action_edit->setLabel(_t('Edit'));
        $action_edit->setImage('far:edit #020c44');
        $action_edit->setField('id');
        $this->datagrid->addAction($action_edit);

        $action_delete = new TDataGridAction([$this, 'onDelete'], ['id' => '{id}']);
        $action_delete->setDisplayCondition(array($this, 'displayColumnDelete'));
        $this->datagrid->addAction($action_delete, _t('Delete'), 'far:trash-alt red');

        $this->datagrid->createModel();

        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $container = new TVBox;
        $container->style = 'width: 100%;';
        $container->add($this->form);
        $container->add(TPanelGroup::pack('', $this->datagrid, $this->pageNavigation));

        parent::add($container);
    }

    public function onClear($param)
    {
        TSession::setValue('PatientList' . '_filter_data', null);
        TSession::setValue('PatientList' . '_filters', null);

        AppHelper::toCleanForm($this->form);
        $this->onReload($param);
    }

    public function onSearch()
    {
        $data = $this->form->getData();
        TSession::setValue('PatientList' . '_filter_data', $data);

        $filters = [];
        if ($data->name) {
            $filters[] = new TFilter('name', 'like', "%{$data->name}%");
        }
        if ($data->cpf) {
            $filters[] = new TFilter('cpf', '=', $data->cpf);
        }
        if ($data->sus) {
            $filters[] = new TFilter('sus', '=', $data->sus);
        }
        if ($data->birth) {
            $filters[] = new TFilter('birth', '=', $data->birth);
        }
        if ($data->active) {
            $filters[] = new TFilter('active', '=', $data->active);
        }

        TSession::setValue('PatientList' . '_filters', $filters);
        $this->onReload(['offset' => 0, 'first_page' => 1]);
    }

    public function onReload($param = NULL)
    {
        try {
            TTransaction::open('app');

            $repository = new TRepository('Patient');
            $limit = 15;

            $criteria = new TCriteria;

            if (empty($param['order'])) {
                $param['order'] = 'name';
                $param['direction'] = 'asc';
            }
            $criteria->setProperties($param);
            $criteria->setProperty('limit', $limit);

            if ($filters = TSession::getValue('PatientList' . '_filters')) {
                foreach ($filters as $filter) {
                    $criteria->add($filter);
                }
            }

            $objects = $repository->load($criteria, FALSE);

            $this->datagrid->clear();
            if ($objects) {
                foreach ($objects as $object) {
                    $this->datagrid->addItem($object);
                }
            }

            $criteria->resetProperties();
            $count = $repository->count($criteria);

            $this->pageNavigation->setCount($count);
            $this->pageNavigation->setProperties($param);
            $this->pageNavigation->setLimit($limit);

            TTransaction::close();
            $this->loaded = true;
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    public function displayColumnDelete($object)
    {
        $result = false;
        try{
            TTransaction::open('app'); 
            
            $user = new SystemUser(SystemUser::id());
            $result = $user->checkInRole(1); //1 - Permissão de Excluir Registros

            TTransaction::close();
            return $result;

        }catch (Exception $e) {
            return false;
        }
    }


    public static function onDelete($param)
    {
        $action = new TAction(['PatientList', 'Delete']);
        $action->setParameters($param);

        new TQuestion("<h5>Confirma a exclusão deste paciente?</h5><p>Essa ação é irreversível e todos os dados do paciente serão removidos permanentemente.</p>", $action);
    }

    /**
     * Delete a record
     */
    public static function Delete($param)
    {
        try {
            $key = $param['key'];
            TTransaction::open('app');
            $object = new Patient($key, FALSE);

            $object->clearParts();

            $object->delete();
            TTransaction::close();

            $pos_action = new TAction(['PatientList', 'onReload']);
            new TMessage('info', "<h5>Cadastro do Paciente excluído com sucesso!</h5>", $pos_action);
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    /**
     * method show()
     * Shows the page
     */
    public function show()
    {
        if (!$this->loaded and (!isset($_GET['method']) or !(in_array($_GET['method'], array('onReload', 'onSearch'))))) {
            if (func_num_args() > 0) {
                $this->onReload(func_get_arg(0));
            } else {
                $this->onReload();
            }
        }
        parent::show();
    }
}
