<?php
/**
 * Appointments Report View
 *
 * @version    1.0
 * @package    reports
 * @subpackage appointments
 */
class AppointmentsReportView extends TPage
{
    private $form; // form
    
    function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_appointments_report');
        $this->form->setFormTitle('Appointments Report');
        
        // create the form fields
        $date_from = new TDate('date_from');
        $date_to = new TDate('date_to');
        $output_type = new TRadioGroup('output_type');
        
        $this->form->addFields([new TLabel('Date from')], [$date_from]);
        $this->form->addFields([new TLabel('Date to')], [$date_to]);
        $this->form->addFields([new TLabel('Output')], [$output_type]);
        
        $output_type->addItems(['html' => 'HTML', 'pdf' => 'PDF', 'xls' => 'XLS']);
        $output_type->setLayout('horizontal');
        
        $this->form->addAction('Generate', new TAction([$this, 'onGenerate']), 'fa:download blue');
        
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add($this->form);
        
        parent::add($vbox);
    }

    function onGenerate()
    {
        try
        {
            TTransaction::open('sample');
            
            $data = $this->form->getData();
            
            $repository = new TRepository('appointments');
            $criteria = new TCriteria;
            
            if ($data->date_from) {
                $criteria->add(new TFilter('appointment_date', '>=', $data->date_from));
            }
            if ($data->date_to) {
                $criteria->add(new TFilter('appointment_date', '<=', $data->date_to));
            }
            
            $appointments = $repository->load($criteria);
            $format = $data->output_type;
            
            if ($appointments)
            {
                $widths = [40, 120, 120, 100, 150];
                
                switch ($format)
                {
                    case 'html': $table = new TTableWriterHTML($widths); break;
                    case 'pdf': $table = new TTableWriterPDF($widths); break;
                    case 'xls': $table = new TTableWriterXLS($widths); break;
                }
                
                $table->addStyle('header', 'Helvetica', '16', 'B', '#ffffff', '#4B5D8E');
                $table->addStyle('title', 'Helvetica', '10', 'B', '#ffffff', '#617FC3');
                $table->addStyle('data', 'Helvetica', '10', '', '#000000', '#E3E3E3', 'LR');
                
                $table->setHeaderCallback(function($table) {
                    $table->addRow();
                    $table->addCell('Appointments Report', 'center', 'header', 5);
                    
                    $table->addRow();
                    $table->addCell('ID', 'center', 'title');
                    $table->addCell('Professional ID', 'center', 'title');
                    $table->addCell('Patient ID', 'center', 'title');
                    $table->addCell('Date', 'center', 'title');
                    $table->addCell('Notes', 'center', 'title');
                });
                
                foreach ($appointments as $appointment)
                {
                    $table->addRow();
                    $table->addCell($appointment->id, 'center', 'data');
                    $table->addCell($appointment->professional_id, 'center', 'data');
                    $table->addCell($appointment->patient_id, 'center', 'data');
                    $table->addCell($appointment->appointment_date, 'center', 'data');
                    $table->addCell($appointment->notes, 'center', 'data');
                }
                
                $output = "app/output/appointments_report.{$format}";
                
                if (!file_exists($output) OR is_writable($output))
                {
                    $table->save($output);
                    parent::openFile($output);
                }
                else
                {
                    throw new Exception('Permission denied: ' . $output);
                }
                
                new TMessage('info', 'Report generated. Enable popups to view.');
            }
            else
            {
                new TMessage('error', 'No records found');
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
