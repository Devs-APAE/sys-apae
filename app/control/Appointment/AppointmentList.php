<?php

/**
 * AppointmentList Listing
 * @author  Marcos David Souza Ramos
 */
class AppointmentList extends TPage
{
    private $form;
    private $datagrid;
    private $pageNavigation;
    private $loaded;
    public function __construct()
    {
        parent::__construct();

        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_appointment');
        $this->form->setFormTitle('LISTAGEM DE ATENDIMENTOS');
        $this->form->setFieldSizes('100%');

        $patient = new TDBUniqueSearch('patient_id', 'app', 'Patient', 'id', 'name');
        
        $appointment_type_id = new TDBCombo('appointment_type_id', 'app', 'AppointmentType', 'id', 'name');
        $appointment_type_id->setDefaultOption('Selecione');

        
        $professional= new TDBUniqueSearch('professional_id', 'app', 'Professional', 'id', 'name');
   
        $appointment_date = new TDate('appointment_date');
        $appointment_date->setMask('dd/mm/yyyy');
        $appointment_date->setDatabaseMask('yyyy-mm-dd');

        /*$output_type = new TRadioGroup('output_type');
        $output_type->addItems(array('pdf'=>'PDF', 'xls' => 'XLS', 'html'=>'HTML'));
        $output_type->setLayout('horizontal');
        $output_type->setUseButton();
        $output_type->setValue('pdf');*/

        $row = $this->form->addFields(
            [new TLabel('Paciente'), $patient],
            [new TLabel('Tipo de Atendimento'), $appointment_type_id],
            [new TLabel('Profissional'), $professional],
            [new TLabel('Data de Atendimento'), $appointment_date],
        );
        $row->layout = ['col-sm-4', 'col-sm-2', 'col-sm-3', 'col-sm-3'];

        $this->form->setData(TSession::getValue('AppointmentList' . '_filter_data'));

        $btn = $this->form->addAction('Pesquisar', new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-default';

        $btn = $this->form->addAction('Limpar', new TAction([$this, 'onClear']), 'fa:eraser');
        $btn->class = 'btn btn-sm btn-default';

        $btn = $this->form->addActionLink(('Novo Atendimento'), new TAction(['AppointmentForm', 'onEdit']), 'fa:plus');
        $btn->class = 'btn btn-sm btn-primary right';

        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->disableDefaultClick();
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->datatable = 'true';

        $column_id = new TDataGridColumn('id', 'ID', 'center');
        $column_patient = new TDataGridColumn('patient->name', 'Paciente', 'left');
        $column_appointment_type= new TDataGridColumn('appointment_type->name', 'Tipo de Atendimento', 'left');
        $column_professional = new TDataGridColumn('professional->name', 'Profissional', 'left');
        $column_apointment_date = new TDataGridColumn('appointment_date', 'Data do Atendimento', 'center');

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_patient);
        $this->datagrid->addColumn($column_appointment_type);
        $this->datagrid->addColumn($column_professional);
        $this->datagrid->addColumn($column_apointment_date);

        $column_id->setTransformer(function ($value, $object, $row) {
            $row->style = "vertical-align: middle;";
            return $value;
        });

        $column_apointment_date->setTransformer(function ($value, $object, $row) {
            return AppHelper::toDateBR($value); 
        });

        $action_edit = new TDataGridAction(['AppointmentForm', 'onEdit']);
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
        TSession::setValue('AppointmentList' . '_filter_data', null);
        TSession::setValue('AppointmentList' . '_filters', null);

        AppHelper::toCleanForm($this->form);

        $this->onReload($param);
    }

    public function onSearch()
    {
        $data = $this->form->getData();
        TSession::setValue('AppointmentList' . '_filter_data', $data);

        $filters = [];
        if ($data->patient_id) {
            $filters[] = new TFilter('patient_id', '=', $data->patient_id);
        }
        if ($data->appointment_type_id) {
            $filters[] = new TFilter('appointment_type_id', '=', $data->appointment_type_id);
        }
        if ($data->professional_id) {
            $filters[] = new TFilter('professional_id', '=', $data->professional_id);
        }
        if ($data->appointment_date) {
            $filters[] = new TFilter('appointment_date', '=', $data->appointment_date);
        }

        TSession::setValue('AppointmentList' . '_filters', $filters);
        $this->onReload(['offset' => 0, 'first_page' => 1]);
    }

    public function onReload($param = NULL)
    {
        try {
            TTransaction::open('app');

            $repository = new TRepository('Appointment');
            $limit = 15;

            $criteria = new TCriteria;

            if (empty($param['order'])) {
                $param['order'] = 'id';
                $param['direction'] = 'desc';
            }
            $criteria->setProperties($param);
            $criteria->setProperty('limit', $limit);

            if ($filters = TSession::getValue('AppointmentList' . '_filters')) {
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
        $action = new TAction(['AppointmentList', 'Delete']);
        $action->setParameters($param);

        new TQuestion("<h5>Confirma a exclusão deste atendimento?</h5><p>Essa ação é irreversível e todos os dados do atendimento serão removidos permanentemente.</p>", $action);
    }

    /**
     * Delete a record
     */
    public static function Delete($param)
    {
        try {
            $key = $param['key'];
            TTransaction::open('app');
            $object = new Appointment($key, FALSE);

            //$object->clearParts();

            $object->delete();
            TTransaction::close();

            $pos_action = new TAction(['AppointmentList', 'onReload']);
            new TMessage('info', "<h5>Atendimento excluído com sucesso!</h5>", $pos_action);
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
