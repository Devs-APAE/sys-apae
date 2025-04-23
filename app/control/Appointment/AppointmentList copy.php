<?php

/**
 * AppointmentList Listing
 * @author Pedro Paulo Teixeira de Araújo
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
        
        $professional= new TDBUniqueSearch('professional_id', 'app', 'Professional', 'id', 'name');
   
        $appointment_date = new TDate('appointment_date');

        $row = $this->form->addFields(
            [new TLabel('Paciente'), $patient],
            [new TLabel('Tipo de Atendimento'), $appointment_type_id],
            [new TLabel('Profissional'), $professional],
            [new TLabel('Appointment_date'), $appointment_date],
        );
        $row->layout = ['col-sm-4', 'col-sm-2', 'col-sm-2', 'col-sm-2', 'col-sm-2'];

        //$this->form->setData(TSession::get('AppointmentList' . '_filter_data'));
        
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

        $column_patient = new TDataGridColumn('patient', 'Paciente', 'left');
        $column_appointment_type= new TDataGridColumn('appointment_type', 'Tipo de Atendimento', 'left');
        $column_professional = new TDataGridColumn('professional', 'Profissional', 'left');
        $column_apointment_date = new TDataGridColumn('appointment_date', 'Data do Atendimento', 'center');

        $this->datagrid->addColumn($column_patient);
        $this->datagrid->addColumn($column_appointment_type);
        $this->datagrid->addColumn($column_professional);
        $this->datagrid->addColumn($column_apointment_date);

        $column_appointment_type->setTransformer(function ($value, $object, $row) {
            return $value; 
        });

        $column_apointment_date->setTransformer(function ($value, $object, $row) {
            return $value; 
        });

        $action_edit = new TDataGridAction(['AppointmentForm', 'onEdit']);
        $action_edit->setButtonClass('btn btn-sm btn-primary');
        $action_edit->setLabel('Edit');
        $action_edit->setImage('far:edit #020c44');
        $action_edit->setField('id');
        $this->datagrid->addColumn($action_edit);

        $action_delete = new TDataGridAction([$this,'onDelete'], ['id' => '{id}']);
        $action_delete->setDisplayCondition(array($this, 'displayColumnDelete'));
        $this->datagrid->addAction($action_delete, _t('Delete'), 'far:trash-alt red');

        $this->datagrid->createModel();

        $this->pageNavigation = TPageNavigation;
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $container = $this->container;
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
        if ($data->active) {
            $filters[] = new TFilter('active', '=', $data->active);
        }

        TSession::setValue('AppointmentList' . '_filters', $filters);
        $this->onReload(['offset'=> 0, 'first_page' => 1]);
    }

    public function onReload($param = null)
    {
        try {
            TTransaction::open('app');

            $repository = new TRepository('Appointment');
            $limit = 15;

            $criteria = new TCriteria;

            $filter = TSession::getValue('AppointmentList' . '_filters');
            if ($filter) {
                foreach ($filter as $item) {
                    $criteria->add($item);
                }
            }

            $criteria->setProperty('limit', $limit);
            $criteria->setProperty('order', 'id');

            $objects = $repository->load($criteria, false);
            $this->datagrid->clear();

            if ($objects) {
                foreach ($objects as $object) {
                    $this->datagrid->addItem($object);
                }
            }

            TTransaction::close();
            $this->loaded = true;
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    public function displayColumnDelete($object)
    {
        return true;
    }

    public static function onDelete($param)
    {
        $action = new TAction([_CLASS_, 'delete']);
        $action->setParameters($param);
        new TQuestion('Deseja realmente excluir o registro?', $action);
    }

    public static function Delete($param)
    {
        try{
            $key = $param['key'];
            TTransaction::open('app');
            $object = new Appointment($key, False);
            $object-> delete();
            TTransaction::close();

            $pos_action = new TAction(['AppointmentList', 'onReload']);
            new TMessage('info', '<h5>Registro excluído com sucesso!</h5>', $pos_action);
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    public function show()
    {
        if (!$this->loaded and (!isset($_GET['method']) or !(in_array(needle: $_GET['method'], haystack: ['onSearch', 'onReload'])))) {
            if (func_num_args() > 0){
                $this->onReload(func_get_arg(position: 0));
            } else {
                $this->onReload();
            }
        }
        parent::show();
    }
}
