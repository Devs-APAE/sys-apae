<?php

/**
 * DisabilityList Listing
 * @author  Tony
 */
class DisabilityList extends TPage
{
    private $form;
    private $datagrid;
    private $pageNavigation;
    private $loaded;
    public function __construct()
    {
        parent::__construct();

        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_disability');
        $this->form->setFormTitle('LISTAGEM DE DEFICIÊNCIAS');
        $this->form->setFieldSizes('100%');

        $cid = new TEntry('cid');
        $cid->placeholder = "CID";

        $name = new TEntry('name');
        $name->placeholder = "Nome";

        $row = $this->form->addFields(
            [new TLabel('Pesquisa'), $cid, $name]
        );
        $row->layout = ['col-sm-4', 'col-sm-4', 'col-sm-4'];

        $this->form->setData(TSession::getValue('DisabilityList' . '_filter_data'));

        $btn = $this->form->addAction('Pesquisar', new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-default';

        $btn = $this->form->addAction('Limpar', new TAction([$this, 'onClear']), 'fa:eraser');
        $btn->class = 'btn btn-sm btn-default';

        $btn = $this->form->addActionLink(('Novo Deficiência'), new TAction(['DisabilityForm', 'onEdit']), 'fa:plus');
        $btn->class = 'btn btn-sm btn-primary right';


        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->disableDefaultClick();
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->datatable = 'true';

        $column_id = new TDataGridColumn('id', 'ID', 'center');
        $column_cid = new TDataGridColumn('cid', 'CID', 'left');
        $column_name = new TDataGridColumn('name', 'Nome', 'left');
        $column_created_at = new TDataGridColumn('created_at', 'Cadastro', 'center');

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_cid);
        $this->datagrid->addColumn($column_name);
        $this->datagrid->addColumn($column_created_at);

        $column_id->setTransformer(function ($value, $object, $row) {
            $row->style = "vertical-align: middle;";
            return $value;
        });

        $column_created_at->setTransformer(function ($value, $object, $row) {
            return $value; 
        });

        $action_edit = new TDataGridAction(['DisabilityForm', 'onEdit']);
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
        TSession::setValue('DisabilityList' . '_filter_data', null);
        TSession::setValue('DisabilityList' . '_filters', null);

        AppHelper::toCleanForm($this->form);

        $this->onReload($param);
    }

    public function onSearch()
    {
        $data = $this->form->getData();
        TSession::setValue('DisabilityList' . '_filter_data', $data);

        $filters = [];
        if ($data->cid) {
            $filters[] = new TFilter('cid', 'like', "%{$data->cid}%");
        }
        if ($data->name) {
            $filters[] = new TFilter('name', 'like', "%{$data->name}%");
        }

        TSession::setValue('DisabilityList' . '_filters', $filters);
        $this->onReload(['offset' => 0, 'first_page' => 1]);
    }

    public function onReload($param = NULL)
    {
        try {
            TTransaction::open('app');

            $repository = new TRepository('Disability');
            $limit = 15;

            $criteria = new TCriteria;

            if (empty($param['order'])) {
                $param['order'] = 'name';
                $param['direction'] = 'asc';
            }
            $criteria->setProperties($param);
            $criteria->setProperty('limit', $limit);

            if ($filters = TSession::getValue('DisabilityList' . '_filters')) {
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
        return true; 
    }


    public static function onDelete($param)
    {
        $action = new TAction(['DisabilityList', 'Delete']);
        $action->setParameters($param);

        new TQuestion("<h5>Deseja excluir essa deficiência?</h5>", $action);
    }

    /**
     * Delete a record
     */
    public static function Delete($param)
    {
        try {
            $key = $param['key'];
            TTransaction::open('app');
            $object = new Disability($key, FALSE);
            $object->delete();
            TTransaction::close();

            $pos_action = new TAction(['DisabilityList', 'onReload']);
            new TMessage('info', "<h5>Registro excluído com sucesso!</h5>", $pos_action);
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
