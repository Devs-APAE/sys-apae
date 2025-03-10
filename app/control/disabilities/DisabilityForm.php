<?php

class DisabilityForm extends TWindow
{
    protected $form;

    public function __construct($param)
    {
        parent::__construct();
        parent::setSize(0.6, null);
        parent::setTitle('Disability Form');
        parent::disableEscape();

        $this->form = new BootstrapFormBuilder('form_Disability');
        $this->form->setFieldSizes('100%');

        TPage::register_css('css_form_Disability', '
            div.panel[form="form_Disability"] {
                margin-bottom: 0px !important;
            }
        ');

        $id = new THidden('id');
        $cid = new TEntry('cid');
        $cid->setMaxLength(100);
        $cid->addValidation('CID', new TRequiredValidator);

        $name = new TEntry('name');
        $name->setMaxLength(100);
        $name->forceUpperCase();
        $name->addValidation('Name', new TRequiredValidator);

        $this->form->addFields([$id]);

        $row = $this->form->addFields(
            [new TLabel('CID *'), $cid],
            [new TLabel('Name *'), $name]
        );
        $row->layout = ['col-sm-6', 'col-sm-6'];

        $this->form->addActionLink('Back', new TAction(['DisabilityList', 'onReload']), 'fa:arrow-alt-circle-left')->class = 'btn btn-sm btn-default';
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
            
            $object = new Disability;
            $object->fromArray((array) $data);
            $object->store();
            
            TTransaction::close(); 
            
            TApplication::loadPage("DisabilityList", 'onReload'); 
            new TMessage('info', "<h5>Disability saved successfully!</h5>");

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
                $object = new Disability($key);
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
