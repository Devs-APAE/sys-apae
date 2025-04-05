<?php

/**
 * ProfessionalForm Form
 * @author  Marcos David Souza Ramos
 */
class ProfessionalForm extends TPage
{
    protected $form;
    private $category_list;
    
    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();      
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_Person');
        $this->form->setFormTitle('FORMULÁRIO DE PROFISSIONAL');
        $this->form->setFieldSizes('100%');
        
        // create the form fields
        $id = new THidden('id');


        /*$this->category_list = new TDBCheckGroup('category_list','app','Category','id','title');
        $this->category_list->setLayout('horizontal');
        if ($this->category_list->getLabels())
        {
            foreach ($this->category_list->getLabels() as $label)
            {
                $label->setSize(220);
            }
        }*/


        $name = new TEntry('name');
        $name->forceUpperCase();
        $name->addValidation('Name', new TRequiredValidator);

        $cpf = new TEntry('cpf');
        $cpf->setMask('999.999.999-99', true);
        $cpf->addValidation('CPF', new TCPFValidator());

        $crm = new TEntry('crm');
        $crm->setMask('00000000-0/BR', True);
        $crm->addValidation('CRM', new TRequiredValidator());

        $phone = new TEntry('phone');
        $phone->setMask('(99) 99999-9999', true);
        $phone->setSize('100%');
        $phone->addValidation('Phone', new TRequiredValidator);

        $email = new TEntry('email');
        $email->setSize('100%');

        $zip_code = new TEntry('zip_code');
        $zip_code->setMask("99.999-999", true);
        $zip_code->addValidation('CEP', new TRequiredValidator());

        $address = new TEntry('address');
        $address->addValidation('Address', new TRequiredValidator);

        $number = new TEntry('number');
        
        $complement = new TEntry('complement');

        $district = new TEntry('district');
        $district->addValidation('District', new TRequiredValidator);

        $city = new TEntry('city');
        $city->addValidation('City', new TRequiredValidator);

        $uf = new TCombo('uf');
        $uf->setDefaultOption('Selecione');
        $uf->addItems(Professional::list_uf());
        $uf->addValidation('UF', new TRequiredValidator());


        $referencia = new TEntry('reference');
    
        $observation = new TText('observation');
        $observation->placeholder = ' Digite aqui...';

        $active = new TRadioGroup('active');
        $active->addItems(['Y'=>_t('Yes'),'N'=> _t('No')]);
        $active->setLayout('horizontal');
        $active->setUseButton();
        $active->setValue('Y');

        // Adicionando campos ao formulário
        $this->form->addFields( [ $id ] );

        $row = $this->form->addFields(
            [ new TLabel('Nome *'), $name ],
            [ new TLabel('CRM *'), $crm],
            [ new TLabel('CPF *'), $cpf],
            [ new TLabel('Ativo *'), $active ],
        );
        $row->layout = ['col-sm-6', 'col-sm-2', 'col-sm-2', 'col-sm-2',];
        
        $this->form->addFields([new TFormSeparator('<br> Contato')]); 

        $row = $this->form->addFields(
            [ new TLabel('Telefone *'), $phone ],
            [ new TLabel('Email '), $email ],
        );
        $row->layout = ['col-sm-6', 'col-sm-6',];

        $this->form->addFields([new TFormSeparator('<br> Endereço')]); 

        $row = $this->form->addFields(
            [ new TLabel('Endereço *'), $address ],
            [ new TLabel('Número '), $number ],
            [ new TLabel('Complemento'), $complement ],
        );
        $row->layout = ['col-sm-6', 'col-sm-3', 'col-sm-3',];

        $row = $this->form->addFields(
            [ new TLabel('Bairro *'), $district ],
            [ new TLabel('Cidade *'), $city ],
            [ new TLabel('Estado *'), $uf ],
            [ new TLabel('CEP *'), $zip_code ],
        );
        $row->layout = ['col-sm-4', 'col-sm-4', 'col-sm-2','col-sm-2',];

        $this->form->addFields( [ new TLabel('Referência'),  $referencia ] );


        $this->form->addFields([new TFormSeparator('Observações')]); 

        $row = $this->form->addFields(
            [$observation ], 
        );
        $row->layout = ['col-sm-12'];


        
        // create the form actions
        $btn = $this->form->addActionLink('Voltar', new TAction(['ProfessionalList', 'onReload']), 'fa:arrow-alt-circle-left');
        $btn->class = 'btn btn-sm btn-default ';

        $btn = $this->form->addActionLink( _t('Clear'), new TAction(array($this, 'onEdit')), 'fa:eraser');
        $btn->class = 'btn btn-sm btn-default';

        $btn = $this->form->addAction('SALVAR', new TAction([$this, 'onSave']), 'fa:save');
        $btn->class = 'btn btn-sm btn-primary right';
        

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add($this->form);
        
        parent::add($container);
    }
    
    

    /**
     * Save form data
     * @param $param Request
     */
    public function onSave( $param )
    {
        try
        {
            TTransaction::open('app'); 
            
            $this->form->validate(); 
            $data = $this->form->getData();   
            
            $object = new Professional;
            $object->fromArray( (array) $data);

            //$validator = new TEmailValidator;
            //$validator->validate('E-mail', $data->email);

            $object->store(); 

            //var_dump($data->category_list);
            /*CategoryPerson::where('person_id', '=', $object->id)->delete();
            if( !empty($data->category_list) ){
                foreach( $data->category_list as $category_id )
                {
                    $object->addCategory( new Category($category_id) );
                }
            }else{
                throw new Exception("<h5>Informe a categoria desta pessoal!</h5><spam>Cliente, Fornecedor e/ou Funcionário.</spam>");
            }*/

            
            TTransaction::close(); 
            
            new TMessage('info', '<h5>Cadastro de Profissional salvo com sucesso!</h5>', 
            new TAction(['ProfessionalList', 'onReload']));
        
        }catch (Exception $e) {
            new TMessage('error', $e->getMessage()); 

            $data = $this->form->getData();
            $this->form->setData($data);
            TTransaction::rollback();
        }
    }
    
    /**
     * Load object to form data
     * @param $param Request
     */
    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];
                TTransaction::open('app');

                $object = new Professional($key);
                //$this->form->setData($object);

                /*$categories = [];                
                if( $categories_db = $object->getCategory() )
                {
                    foreach( $categories_db as $category )
                    {
                        $categories[] = $category->id;
                    }
                }
                
                $object->category_list = $categories;*/

                $this->form->setData($object);
                TTransaction::close();                

            }else{
                $this->form->clear(TRUE);
            }

        } catch (Exception $e){
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    
}