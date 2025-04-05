<?php
/**
 * AppointmentType Active Record
 * @author  Marcos David Souza Ramos
 */
class AppointmentType extends TRecord
{
    const TABLENAME = 'appointment_types';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max';
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('name');
    }



}
