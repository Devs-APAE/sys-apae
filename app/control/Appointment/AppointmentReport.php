<?php


/**
 * 
 * @author
 */
class AppointmentReport extends TPage
{
    private $form;

    public function __construct()
    {
        parent::__construct();

        $this->form = new BootstrapFormBuilder('form_report_appointment');
        $this->form->setFormTitle('RELATÓRIO DE ATENDIMENTOS');
        $this->form->setFieldSizes('100%');

        $patient = new TDBUniqueSearch('patient_id', 'app', 'Patient', 'id', 'name');
        $appointment_type_id = new TDBCombo('appointment_type_id', 'app', 'AppointmentType', 'id', 'name');
        $appointment_type_id->setDefaultOption('Selecione');

        $professional = new TDBUniqueSearch('professional_id', 'app', 'Professional', 'id', 'name');
        $appointment_date = new TDate('appointment_date');
        $appointment_date->setMask('dd/mm/yyyy');
        $appointment_date->setDatabaseMask('yyyy-mm-dd');

        $output_type = new TRadioGroup('output_type');
        $output_type->addItems(['pdf' => 'PDF', 'xls' => 'XLS', 'html' => 'HTML']);
        $output_type->setLayout('horizontal');
        $output_type->setUseButton();
        $output_type->setValue('pdf');

        $row = $this->form->addFields(
            [new TLabel('Paciente'), $patient],
            [new TLabel('Tipo de Atendimento'), $appointment_type_id],
            [new TLabel('Profissional'), $professional],
            [new TLabel('Data do Atendimento'), $appointment_date],
        );
        $row->layout = ['col-sm-4', 'col-sm-3', 'col-sm-3', 'col-sm-2'];

        $this->form->addFields([new TLabel('Formato')], [$output_type]);

        $this->form->setData(TSession::getValue(__CLASS__ . '_filter_data'));

        $btn = $this->form->addAction('Gerar Relatório', new TAction([$this, 'onGenerate']), 'fa:print');
        $btn->class = 'btn btn-sm btn-primary';

        $btn = $this->form->addAction('Limpar', new TAction([$this, 'onClear']), 'fa:eraser');
        $btn->class = 'btn btn-sm btn-default';

        parent::add($this->form);
    }

    public function onClear($param)
    {
        TSession::setValue(__CLASS__ . '_filter_data', null);
        TSession::setValue(__CLASS__ . '_filters', null);

        AppHelper::toCleanForm($this->form);
    }

    public function onGenerate()
    {
        try
        {
            TTransaction::open('app');
    
            $data = $this->form->getData();
            $this->form->validate();
    
            $repository = new TRepository('Appointment');
            $criteria   = new TCriteria;
    
            $param['order'] = 'appointment_date';
            $param['direction'] = 'asc';
            $criteria->setProperties($param);
    
            if ($filters = TSession::getValue(__CLASS__ . '_filters')) {
                foreach ($filters as $filter) {
                    $criteria->add($filter);
                }
            }
    
            $objects = $repository->load($criteria, FALSE);
            $format  = $data->output_type ?? 'pdf';
    
            if ($objects)
            {
                $widths = [100, 100, 100, 100, 100, 200, 150, 150];
                $size   = count($widths);
    
                switch ($format)
                {
                    case 'html':
                        $tr = new TTableWriterHTML($widths);
                        break;
                    case 'pdf':
                        $tr = new TTableWriterPDF($widths, 'L');
                        break;
                    case 'xls':
                        $tr = new TTableWriterXLS($widths);
                        break;
                    case 'rtf':
                        $tr = new TTableWriterRTF($widths);
                        break;
                    default:
                        throw new Exception("Formato inválido: {$format}");
                }
    
                // Estilos
                $tr->addStyle('title', 'Arial', '10', 'B',   '#ffffff', '#9898EA');
                $tr->addStyle('datap', 'Arial', '10', '',    '#000000', '#EEEEEE');
                $tr->addStyle('datai', 'Arial', '10', '',    '#000000', '#ffffff');
                $tr->addStyle('header', 'Arial', '16', '',   '#ffffff', '#494D90');
                $tr->addStyle('footer', 'Times', '10', 'I',  '#000000', '#B1B1EA');
    
                $tr->addRow();
                $tr->addCell("Sistema de Agendamento", 'center', 'header', $size);
    
                $tr->addRow();
                $tr->addCell('RELATÓRIO DE AGENDAMENTOS', 'center', 'title', $size);
    
                // Cabeçalhos
                $tr->addRow();
                $tr->addCell('ID', 'center', 'title');
                $tr->addCell('Tipo', 'center', 'title');
                $tr->addCell('Profissional', 'center', 'title');
                $tr->addCell('Paciente', 'center', 'title');
                $tr->addCell('Data', 'center', 'title');
                $tr->addCell('Observações', 'center', 'title');
                $tr->addCell('Criado em', 'center', 'title');
                $tr->addCell('Atualizado em', 'center', 'title');
    
                $alternado = FALSE;
                foreach ($objects as $object)
                {
                    $style = $alternado ? 'datap' : 'datai';
                    $tr->addRow();
                    $tr->addCell($object->id, 'center', $style);
                    $tr->addCell($object->appointment_type->name ?? '-', 'center', $style);
                    $tr->addCell($object->professional->name ?? '-', 'center', $style);
                    $tr->addCell($object->patient->name ?? '-', 'center', $style);                    
                    $tr->addCell(date('d/m/Y', strtotime($object->appointment_date)), 'center', $style);
                    $tr->addCell($object->notes ?? '-', 'left', $style);
                    $tr->addCell($object->created_at ? date('d/m/Y H:i', strtotime($object->created_at)) : '-', 'center', $style);
                    $tr->addCell($object->updated_at ? date('d/m/Y H:i', strtotime($object->updated_at)) : '-', 'center', $style);
    
                    $alternado = !$alternado;
                }
    
                $tr->addRow();
                $tr->addCell("Gerado em: " . date('d/m/Y H:i'), 'center', 'footer', $size);
    
                $file_path = "app/output/AppointmentReport.{$format}";
                if (!file_exists($file_path) || is_writable($file_path)) {
                    $tr->save($file_path);
                } else {
                    throw new Exception(_t('Permission denied') . ': ' . $file_path);
                }
    
                parent::openFile($file_path);
                new TMessage('info', 'Relatório gerado com sucesso. Por favor, ative os pop-ups do navegador.');
            }
            else
            {
                new TMessage('error', '<h5>Nenhum agendamento encontrado com os filtros aplicados.</h5>');
            }
    
            $this->form->setData($data);
    
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
}    

