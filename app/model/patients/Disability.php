<?php
/**
 * Disability Active Record
 * @author  Pedro Vitor Rocha Do Val
 */
class Disability extends TRecord
{
    const TABLENAME = 'disabilities';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max';
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cid');
        parent::addAttribute('name');
    }



}
