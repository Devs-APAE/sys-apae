<?php

use Adianti\Widget\Wrapper\TDBUniqueSearch;

/**
 * AppointmentForm Form
 * @author Tony Sousa Lopes
 */
class AppointmentForm extends TPage
{
    protected $form;

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct($param)
    {
        parent::__construct();

        // Cria o formulário
        $this->form = new BootstrapFormBuilder('form_Appointment');
        $this->form->setFormTitle('Atendimento');
        $this->form->setFieldSizes('100%');

        // Campos do formulário
        $id = new THidden('id');

        $appointment_type_id = new TDBCombo('appointment_type_id', 'app', 'AppointmentType', 'id', 'name');
        $appointment_type_id->addValidation('Tipo de Atendimento', new TRequiredValidator());

        $professional_id = new TDBUniqueSearch('professional_id', 'app', 'Professional', 'id', 'name');
        $professional_id->setMinLength(1); // Carrega sugestões após 1 caractere digitado   
        $professional_id->setMask('{name} ({cpf})');
        $professional_id->addValidation('Profissional', new TRequiredValidator());
        
        $patient_id = new TDBUniqueSearch('patient_id', 'app', 'Patient', 'id', 'name');
        $patient_id->setMinLength(1);
        $patient_id->setMask('{name} ({cpf})');
        $patient_id->addValidation('Paciente', new TRequiredValidator());
        

        $appointment_date = new TDate('appointment_date');
        $appointment_date->setMask('dd/mm/yyyy');
        $appointment_date->setDatabaseMask('yyyy-mm-dd');
        $appointment_date->setValue(date('Y-m-d'));
        $appointment_date->addValidation('Data do Atendimento', new TRequiredValidator());

        $notes = new TText('notes');
        $notes->placeholder = 'Observações sobre o atendimento';

        // Adiciona os campos ao formulário
        $this->form->addFields([$id]);
        
        $row = $this->form->addFields(
            [new TLabel('Paciente *'), $patient_id],
            [new TLabel('Tipo de Atendimento *'), $appointment_type_id]
        );
        $row->layout = ['col-sm-8', 'col-sm-4'];
        
        $row = $this->form->addFields(
            [new TLabel('Profissional *'), $professional_id],
            [new TLabel('Data do Atendimento *'), $appointment_date]
        );
        $row->layout = ['col-sm-6', 'col-sm-6'];
        
        $row = $this->form->addFields(
            [new TLabel('Observação'), $notes]
        );
        $row->layout = ['col-sm-12'];
        
        $this->form->addActionLink('VOLTAR', new TAction(['AppointmentList', 'onReload']), 'fa:arrow-alt-circle-left')->class = 'btn btn-sm btn-default';
        $this->form->addActionLink('LIMPAR', new TAction([$this, 'onEdit']), 'fa:eraser')->class = 'btn btn-sm btn-default';
        $this->form->addAction('SALVAR', new TAction([$this, 'onSave']), 'fa:save')->class = 'btn btn-sm btn-primary right';

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
            
            // Depuração: Verificar se o campo está vindo preenchido
            if (empty($data->professional_id)) {
                throw new Exception('O campo "Profissional" é obrigatório.');
            }
    
            $object = new Appointment;
            $object->fromArray((array) $data);
            $object->store();
    
            TTransaction::close();
    
            new TMessage('info', '<h5>Atendimento salvo com sucesso!</h5>', new TAction(['AppointmentList', 'onReload']));
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            $this->form->setData($this->form->getData());
            TTransaction::rollback();
        }
    }
    

    public function onEdit($param)
    {
        try {
            if (isset($param['key'])) {
                $key = $param['key'];
                TTransaction::open('app');

                $object = new Appointment($key);
                $this->form->setData($object);

                TTransaction::close();
            } else {
                $this->form->clear(TRUE);
            }
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
}
