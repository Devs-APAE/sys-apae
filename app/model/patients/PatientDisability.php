<?php
/**
 * PatientDisability Active Record
 * @author  Pedro Vitor Rocha Do Val
 */
class PatientDisability extends TRecord
{
    const TABLENAME = 'patient_disabilities';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max';
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('patient_id');
        parent::addAttribute('disability_id');
    }



}