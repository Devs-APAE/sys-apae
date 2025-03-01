<?php
/**
 * Appointment Active Record
 * @author  Marcos David Souza Ramos
 */
class Appointment extends TRecord
{
    const TABLENAME = 'appointments';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max';
    
    private $appointment_type;
    private $patient;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('appointment_type_id');
        parent::addAttribute('patient_id');
        parent::addAttribute('name');
    }


    public function set_appointment_type(AppointmentType $object)
    {
        $this->appointment_type = $object;
        $this->appointment_type_id = $object->id;
    }

    public function get_appointment_type()
    {
        if (empty($this->appointment_type))
            $this->appointment_type = new AppointmentType($this->appointment_type_id);

        return $this->appointment_type;
    }


    public function set_patient(Patient $object)
    {
        $this->patient = $object;
        $this->patient_id = $object->id;
    }

    public function get_patient()
    {
        if (empty($this->patient))
            $this->patient = new Patient($this->patient_id);

        return $this->patient;
    }



}
