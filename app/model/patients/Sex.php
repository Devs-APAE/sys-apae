<?php
/**
 * Sex Active Record
 * @author  Marcos David Souza Ramos
 */
class Sex extends TRecord
{
    const TABLENAME = 'sex';
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
