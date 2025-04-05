<?php

/**
 * ProfessionalList Listing
 * @author Luiz Henrique Lazarin
 */
class ProfessionalList extends TPage
{
    private $form;
    private $datagrid;
    private $pageNavigation;
    private $loaded;
    public function __construct()
    {
        parent::__construct();


        //creates the form
        $this->datagrid = new TDataGrid();
        $this->form = new BootstrapFormBuilder('form_search_professional');
        $this->form->setFormTitle('LISTAGEM DE PROFISSIONAIS');
        $this->form->setFieldSizes('100%');
        
        $name = new TEntry('name');
        $name->placeholder = "Nome do Profissional";

        $cpf = new TEntry('cpf');
        $cpf->setMask('999.999.999-99', true);
        $cpf->placeholder = "000.000.000-00";

        $crm = new TEntry('crm');
        $crm->setMask('00000000-0/BR', true);
        $crm->placeholder = "CRM";

        $phone = new TEntry('phone');
        $phone->placeholder = "Telefone";

        $email = new TEntry('email');
        $email->placeholder = "Email";

        $zip_code = new TEntry('zip_code');
        $zip_code->placeholder = "CEP";

        $address = new TEntry('address');
        $address->placeholder = "Endereço";

        $number = new TEntry('number');
        $number->placeholder = "Número";

        $complement = new TEntry('complement');
        $complement->placeholder = "Complemento";

        $district = new TEntry('district');
        $district->placeholder = "Bairro";

        $city = new TEntry('city');
        $city->placeholder = "Cidade";

        $uf = new TEntry('uf');
        $uf->placeholder = "Estado";
        
        $reference = new TEntry('reference');
        $reference->placeholder = "Ponto de referência";
        
        $observation = new TEntry('observation');
        $observation->placeholder = "Observação";

        $active = new TCombo('active');
        $active->addItems([ 'Y' => 'Sim', 'N' => 'Não' ]);
        $active->setDefaultOption("Selecione");

        $row = $this->form->addFields(
            [new TLabel('Pesquisa'), $name],
            [new TLabel('CPF'), $cpf],
            [new TLabel('CRM'), $crm],
            [new TLabel('Ativo'), $active],
           // [ new TLabel('Formato'), $output_type ], 
        );
        $row->layout = ['col-sm-6', 'col-sm-2', 'col-sm-2', 'col-sm-2',];


        $this->form->setData(TSession::getValue('ProfessionalList' . '_filter_data'));

        $btn = $this->form->addAction('Pesquisar', new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-default';

        $btn = $this->form->addAction('Limpar', new TAction([$this, 'onClear']), 'fa:eraser');
        $btn->class = 'btn btn-sm btn-default';

        $btn = $this->form->addActionLink(('Novo Profissional'), new TAction(['ProfessionalForm', 'onEdit']), 'fa:plus');
        $btn->class = 'btn btn-sm btn-primary right';

        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->disableDefaultClick();
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->datatable = 'true';

        $column_id = new TDataGridColumn('id', 'ID', 'center');
        $column_name = new TDataGridColumn('name', 'Nome', 'left');
        $column_cpf = new TDataGridColumn('cpf', 'CPF', 'left');
        $column_crm = new TDataGridColumn('crm', 'CRM', 'left');
        $column_phone = new TDataGridColumn('phone', 'Telefone', 'left');
        $column_email = new TDataGridColumn('email', 'Email', 'left');
        $column_zip_code = new TDataGridColumn('zip_code', 'CEP', 'left');
        $column_address = new TDataGridColumn('address', 'Endereço', 'left');
        $column_number = new TDataGridColumn('number', 'Número', 'left');
        $column_complement = new TDataGridColumn('complement', 'Complemento', 'left');
        $column_district = new TDataGridColumn('district', 'Bairro', 'left');
        $column_city = new TDataGridColumn('city', 'Cidade', 'left');
        $column_uf = new TDataGridColumn('uf', 'Estado', 'left');
        $column_reference = new TDataGridColumn('reference', 'Ponto de Referência', 'left');
        $column_observation = new TDataGridColumn('observation', 'Observação', 'left');
        $column_active = new TDataGridColumn('active', 'Ativo(a)', 'left');
        $column_created_at = new TDataGridColumn('created_at', 'Cadastro feito em: ', 'center');
        $column_updated_at = new TDataGridColumn('updated_at', 'Atualizado em: ', 'center');


        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_name);
        $this->datagrid->addColumn($column_cpf);
        $this->datagrid->addColumn($column_crm);
        $this->datagrid->addColumn($column_phone);
        $this->datagrid->addColumn($column_email);
        $this->datagrid->addColumn($column_zip_code);
        $this->datagrid->addColumn($column_address);
        $this->datagrid->addColumn($column_number);
        $this->datagrid->addColumn($column_complement);
        $this->datagrid->addColumn($column_district);
        $this->datagrid->addColumn($column_city);
        $this->datagrid->addColumn($column_uf);
        $this->datagrid->addColumn($column_reference);
        $this->datagrid->addColumn($column_observation);
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

        $action_edit = new TDataGridAction(['ProfessionalForm', 'onEdit']);
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
        TSession::setValue('ProfessionalList' . '_filter_data', null);
        TSession::setValue('ProfessionalList' . '_filters', null);

        AppHelper::toCleanForm($this->form);

        $this->onReload($param);
    }

    public function onSearch()
    {
        $data = $this->form->getData();
        TSession::setValue('ProfessionalList' . '_filter_data', $data);

        $filters = [];
        if ($data->name) {
            $filters[] = new TFilter('name', 'like', "%{$data->name}%");
        }
        if ($data->cpf) {
            $filters[] = new TFilter('cpf', '=', $data->cpf);
        }
        if ($data->crm) {
            $filters[] = new TFilter('crm', '=', $data->crm);
        }
        if ($data->active) {
            $filters[] = new TFilter('active', '=', $data->active);
        }
        TSession::setValue('ProfessionalList' . '_filters', $filters);
        $this->onReload(['offset' => 0, 'first_page' => 1]);
    }

    public function onReload($param = NULL)
    {
        try {
            TTransaction::open('app');

            $repository = new TRepository('Professional');
            $limit = 15;

            $criteria = new TCriteria;

            if (empty($param['order'])) {
                $param['order'] = 'name';
                $param['direction'] = 'asc';
            }
            $criteria->setProperties($param);
            $criteria->setProperty('limit', $limit);

            if ($filters = TSession::getValue('ProfessionalList' . '_filters')) {
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

        new TQuestion("<h5>Confirma a exclusão deste profissional?</h5><p>Essa ação é irreversível e todos os dados do profissional serão removidos permanentemente.</p>", $action);
    }

    /**
     * Delete a record
     */
    public static function Delete($param)
    {
        try {
            $key = $param['key'];
            TTransaction::open('app');
            $object = new Professional($key, FALSE);
            
            $object->clearParts();
            
            $object->delete();
            TTransaction::close();

            $pos_action = new TAction(['ProfessionalList', 'onReload']);
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
