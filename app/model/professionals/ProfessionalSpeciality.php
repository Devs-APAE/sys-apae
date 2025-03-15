<?php
/**
 * PatientDisability Active Record
 * @author  Pedro Vitor Rocha Do Val
 */
class ProfessionalSpeciality extends TRecord
{
    const TABLENAME = 'professional_specialities';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max';
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('professional_id');
        parent::addAttribute('speciality_id');
    }

    


}