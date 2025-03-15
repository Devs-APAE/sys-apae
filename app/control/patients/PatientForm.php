<?php

/**
 * PatientForm Form
 * @author  Marcos David Souza Ramos
 */
class PatientForm extends TPage
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
        $this->form->setFormTitle('FORMULÁRIO DE PACIENTE');
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


        $sex_id = new TDBCombo('sex_id','app','Sex','id','name');
        $sex_id->addValidation('Sexo', new TRequiredValidator());
        $sex_id->setDefaultOption(false);

        $name = new TEntry('name');
        $name->forceUpperCase();

        $birth = new TDate('birth');
        $birth->setMask("dd/mm/yyyy");
        $birth->setDatabaseMask("yyyy-mm-dd"); 
        $birth->placeholder = "dd/mm/aaaa";
        $birth->addValidation('Nascimento', new TRequiredValidator());


        $cpf = new TEntry('cpf');
        $cpf->setMask('999.999.999-99', true);
        $cpf->addValidation('CPF', new TCPFValidator());

        $rg = new TEntry('rg');
        //$rg->addValidation('RG', new TRequiredValidator());

        $cns = new TEntry('cns');
        $cns->addValidation('CNS', new TRequiredValidator());

        $sus = new TEntry('sus');
        $sus->addValidation('SUS', new TRequiredValidator());

        $responsible = new TEntry('responsible');
        $responsible->addValidation('Responsável', new TRequiredValidator());

        $phone = new TEntry('phone');
        $phone->setMask('(99)99999-9999', true);
        $phone->setSize('100%');
        $phone->addValidation('Celular', new TRequiredValidator());

        $email = new TEntry('email');
        $email->setSize('100%');
        

        $zip_code = new TEntry('zip_code');
        $zip_code->setMask("99.999-999", true);
        $zip_code->addValidation('CEP', new TRequiredValidator());

        $address = new TEntry('address');
        $address->addValidation('Endereço', new TRequiredValidator());

        $number = new TEntry('number');

        $complement = new TEntry('complement');       

        $district = new TEntry('district');
        $district->addValidation('Bairro', new TRequiredValidator());

        $city = new TEntry('city');
        $city->addValidation('Cidade', new TRequiredValidator());

        $uf = new TCombo('uf');
        $uf->setDefaultOption('Selecione');
        $uf->addItems(Patient::list_uf());
        $uf->addValidation('UF', new TRequiredValidator());

        $referencia = new TEntry('reference');
        
        $observation = new TText('observation');
        $observation->placeholder = ' Digite aqui...';

        $active = new TRadioGroup('active');
        $active->addItems(['Y'=>_t('Yes'),'N'=> _t('No')]);
        $active->setLayout('horizontal');
        $active->setUseButton();
        $active->setValue('Y');


        $this->form->addFields( [ $id ] );

        $row = $this->form->addFields(
            [ new TLabel('Nome *'), $name ], 
            [ new TLabel('Nascimento *'), $birth ],
            [ new TLabel('Sexo *'), $sex_id ],
            [new TLabel('Ativo *'), $active],
        );
        $row->layout = ['col-sm-6', 'col-sm-2', 'col-sm-2', 'col-sm-2'];

        $row = $this->form->addFields(
            [ new TLabel('CPF'), $cpf ],
            [ new TLabel('RG '), $rg ],
            [ new TLabel('CNS '), $cns ],
            [ new TLabel('SUS '), $sus ],
        );
        $row->layout = ['col-sm-3', 'col-sm-3', 'col-sm-3', 'col-sm-3'];

        $this->form->addFields([new TFormSeparator('<br> Contato')]); 

        $row = $this->form->addFields(
            [ new TLabel('Responsável '), $responsible ],
            [ new TLabel('Celular '), $phone ],
            [ new TLabel('E-mail '), $email ],
        );
        $row->layout = ['col-sm-6', 'col-sm-3', 'col-sm-3'];
       
        $this->form->addFields([new TFormSeparator('<br> Endereço')]);         

        $row = $this->form->addFields(
            [ new TLabel('Endereço '), $address ],
            [ new TLabel('Número '), $number ],
            [ new TLabel('Complemento'), $complement ],
        );
        $row->layout = ['col-sm-4', 'col-sm-4', 'col-sm-4'];

        $row = $this->form->addFields(
            [ new TLabel('Bairro '), $district ], 
            [ new TLabel('Cidade '), $city ],
            [ new TLabel('Estado '), $uf ],
            [ new TLabel('CEP '), $zip_code ],
        );
        $row->layout = ['col-sm-4', 'col-sm-4', 'col-sm-2','col-sm-2',];

        $this->form->addFields( [ new TLabel('Referência'),  $referencia ] );


        $this->form->addFields([new TFormSeparator('Observações')]); 

        $row = $this->form->addFields(
            [$observation ], 
        );
        $row->layout = ['col-sm-12'];

      

        // create the form actions
        $btn = $this->form->addActionLink('Voltar', new TAction(['PatientList', 'onReload']), 'fa:arrow-alt-circle-left');
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
            
            $object = new Patient;
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
            
            new TMessage('info', '<h5>Cadastro de Paciente salvo com sucesso!</h5>', 
            new TAction(['PatientList', 'onReload']));
        
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

                $object = new Patient($key);
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