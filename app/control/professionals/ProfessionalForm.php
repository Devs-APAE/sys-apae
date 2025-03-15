<?php

class ProfessionalForm extends TWindow
{
    protected $form;

    public function __construct($param)
    {  
        parent::__construct();
        parent::setSize(0.6, null);
        parent::setTitle('Professional Form');
        parent::disableEscape();

        $this->form = new BootstrapFormBuilder('form_Professional');
        $this->form->setFieldSizes('100%');

        TPage::register_css('css_form_Professional', '
            div.panel[form="form_Professional"] {
                margin-bottom: 0px !important;
            }
        ');

        $id = new THidden('id');

        $name = new TEntry('name');
        $name->setMaxLength(100);
        $name->forceUpperCase();
        $name->addValidation('Name', new TRequiredValidator);

        $cpf = new TEntry('cpf');
        $cpf->setMaxLength(100);
        $cpf->forceUpperCase();
        $cpf->addValidation('CPF', new TRequiredValidator);

        $crm = new TEntry('crm');
        $crm->setMaxLength(100);
        $crm->forceUpperCase();
        $crm->addValidation('CRM', new TRequiredValidator);

        $phone = new TEntry('phone');
        $phone->setMaxLength(100);
        $phone->forceUpperCase();
        $phone->addValidation('Phone', new TRequiredValidator);

        $email = new TEntry('email');
        $email->setMaxLength(100);
        $email->forceUpperCase();
        $email->addValidation('Email', new TRequiredValidator);

        $zip_code = new TEntry('zip_code');
        $zip_code->setMaxLength(100);
        $zip_code->forceUpperCase();
        $zip_code->addValidation('Zip_code', new TRequiredValidator);

        $address = new TEntry('address');
        $address->setMaxLength(100);
        $address->forceUpperCase();
        $address->addValidation('Address', new TRequiredValidator);

        $number = new TEntry('number');
        $number->setMaxLength(100);
        $number->forceUpperCase();
        $number->addValidation('Number', new TRequiredValidator);

        $complement = new TEntry('complement');
        $complement->setMaxLength(100);
        $complement->forceUpperCase();
        $complement->addValidation('Complement', new TRequiredValidator);

        $district = new TEntry('district');
        $district->setMaxLength(100);
        $district->forceUpperCase();
        $district->addValidation('District', new TRequiredValidator);

        $city = new TEntry('city');
        $city->setMaxLength(100);
        $city->forceUpperCase();
        $city->addValidation('City', new TRequiredValidator);

        $uf = new TEntry('uf');
        $uf->setMaxLength(100);
        $uf->forceUpperCase();
        $uf->addValidation('UF', new TRequiredValidator);

        $reference = new TEntry('reference');
        $reference->setMaxLength(100);
        $reference->forceUpperCase();
        $reference->addValidation('Reference', new TRequiredValidator);

        $observation = new TEntry('observation');
        $observation->setMaxLength(100);
        $observation->forceUpperCase();
        $observation->addValidation('Observation', new TRequiredValidator);

        $active = new TEntry('active');
        $active->setMaxLength(100);
        $active->forceUpperCase();
        $active->addValidation('Active', new TRequiredValidator);

        $created_at = new TEntry('created_at');
        $created_at->setMaxLength(100);
        $created_at->forceUpperCase();
        $created_at->addValidation('created_at', new TRequiredValidator);

        $updated_at = new TEntry('updated_at');
        $updated_at->setMaxLength(100);
        $updated_at->forceUpperCase();
        $updated_at->addValidation('updated_at', new TRequiredValidator);

        // Adicionando campos ao formulário
        $this->form->addFields([$id]);

        $row = $this->form->addFields(
            [new TLabel('Nome *'), $name],
            [new TLabel('CPF *'), $cpf],
            [new TLabel('CRM *'), $crm]
        );
        $row->layout = ['col-sm-6', 'col-sm-3', 'col-sm-3'];
        
        $row = $this->form->addFields(
            [new TLabel('Telefone *'), $phone],
            [new TLabel('Email *'), $email],
            [new TLabel('CEP *'), $zip_code]
        );
        $row->layout = ['col-sm-6', 'col-sm-3', 'col-sm-3'];

        $row = $this->form->addFields(
            [new TLabel('Endereço *'), $address],
            [new TLabel('Número *'), $number],
            [new TLabel('Complemento *'), $complement]
        );
        $row->layout = ['col-sm-6', 'col-sm-3', 'col-sm-3'];

        $row = $this->form->addFields(
            [new TLabel('Bairro *'), $district],
            [new TLabel('Cidade *'), $city],
            [new TLabel('Estado *'), $uf]
        );
        $row->layout = ['col-sm-6', 'col-sm-3', 'col-sm-3'];

        $row = $this->form->addFields(
            [new TLabel('Ponto de Referência *'), $reference],
            [new TLabel('Observação *'), $observation],
            [new TLabel('Ativo(a) *'), $active]
        );
        $row->layout = ['col-sm-6', 'col-sm-3', 'col-sm-3'];

        $row = $this->form->addFields(
            [new TLabel('Cadastro feito em *'), $created_at],
            [new TLabel('Atualizado em *'), $updated_at],
        );
        $row->layout = ['col-sm-6', 'col-sm-6'];

        $this->form->addActionLink('Clear', new TAction([$this, 'onClear']), 'fa:eraser')->class = 'btn btn-sm btn-default';
        $this->form->addAction('SAVE', new TAction([$this, 'onSave']), 'fa:save')->class = 'btn btn-sm btn-primary right';

        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add($this->form);

        parent::add($container);
    }

    public function onSave($param)
    {
        try {
            TTransaction::open('app');
            $this->form->validate(); 
            $data = $this->form->getData();

            // Salvando o profissional
            $object = new Professional;  // Alterado para Professional
            $object->fromArray((array) $data);
            $object->store();

            // Aqui, se houver especialidades, associá-las ao profissional
            if (isset($param['specialities']) && is_array($param['specialities'])) {
                foreach ($param['specialities'] as $speciality_id) {
                    $speciality = new Speciality($speciality_id); // Criar o objeto Speciality
                    $object->addProfessionalSpeciality($speciality);  // Adicionar especialidade
                }
            }

            TTransaction::close();

            new TMessage('info', 'Professional saved successfully!');

        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            $this->form->setData($this->form->getData());
            TTransaction::rollback();
        }
    }

    public function onClear($param)
    {
        $this->form->clear(true);
    }

    public function onEdit($param)
    {
        try {
            if (isset($param['key'])) {
                $key = $param['key'];
                TTransaction::open('app'); 
                $object = new Professional($key);
                $this->form->setData($object);
                TTransaction::close();
            } else {
                $this->form->clear(true);
            }
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
}
