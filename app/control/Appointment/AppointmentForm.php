<?php

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
        $this->form->setFormTitle('FORMULÁRIO DE AGENDAMENTO');
        $this->form->setFieldSizes('100%');

        // Campos do formulário
        $id = new THidden('id');

        $appointment_type_id = new TDBCombo('appointment_type_id', 'app', 'AppointmentType', 'id', 'name');
        $appointment_type_id->addValidation('Tipo de Agendamento', new TRequiredValidator());

        $professional_id = new TDBCombo('professional_id', 'app', 'Professional', 'id', 'name');
        $professional_id->addValidation('Profissional', new TRequiredValidator());

        $patient_id = new TDBCombo('patient_id', 'app', 'Patient', 'id', 'name');
        $patient_id->addValidation('Paciente', new TRequiredValidator());

        $appointment_date = new TDate('appointment_date');
        $appointment_date->setMask('dd/mm/yyyy');
        $appointment_date->setDatabaseMask('yyyy-mm-dd');
        $appointment_date->addValidation('Data do Agendamento', new TRequiredValidator());

        $notes = new TText('notes');
        $notes->placeholder = 'Observações sobre o agendamento';

        // Adiciona os campos ao formulário
        $this->form->addFields([$id]);

        $row = $this->form->addFields(
            [new TLabel('Tipo de Agendamento *'), $appointment_type_id],
            [new TLabel('Profissional *'), $professional_id],
            [new TLabel('Paciente *'), $patient_id]
        );
        $row->layout = ['col-sm-4', 'col-sm-4', 'col-sm-4'];

        $row = $this->form->addFields(
            [new TLabel('Data do Agendamento *'), $appointment_date]
        );
        $row->layout = ['col-sm-4'];

        $row = $this->form->addFields(
            [new TLabel('Observações'), $notes]
        );
        $row->layout = ['col-sm-12'];

        // Botões de ação
        $btn = $this->form->addActionLink('Voltar', new TAction(['AppointmentList', 'onReload']), 'fa:arrow-alt-circle-left');
        $btn->class = 'btn btn-sm btn-default';

        $btn = $this->form->addActionLink(_t('Clear'), new TAction([$this, 'onEdit']), 'fa:eraser');
        $btn->class = 'btn btn-sm btn-default';

        $btn = $this->form->addAction('SALVAR', new TAction([$this, 'onSave']), 'fa:save');
        $btn->class = 'btn btn-sm btn-primary right';

        // Container vertical
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
    
            new TMessage('info', '<h5>Agendamento salvo com sucesso!</h5>', new TAction(['AppointmentList', 'onReload']));
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
