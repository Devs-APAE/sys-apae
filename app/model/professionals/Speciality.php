<?php
/**
 * Speciality Active Record
 * @author  Luiz Henrique Lazarin
 */
class Speciality extends TRecord
{
    const TABLENAME = 'specialities';
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
