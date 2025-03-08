<?php
/**
 * Patient Active Record
 * @author  Marcos David Souza Ramos
 */
class Patient extends TRecord
{
    const TABLENAME = 'patients';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max';


    private $sex;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('sex_id');
        parent::addAttribute('name');
        parent::addAttribute('birth');
        parent::addAttribute('cpf');
        parent::addAttribute('rg');
        parent::addAttribute('cns');
        parent::addAttribute('sus');
        parent::addAttribute('responsible');
        parent::addAttribute('phone');
        parent::addAttribute('email');
        parent::addAttribute('zip_code');
        parent::addAttribute('address');
        parent::addAttribute('number');
        parent::addAttribute('complement');
        parent::addAttribute('district');
        parent::addAttribute('city');
        parent::addAttribute('uf');
        parent::addAttribute('reference');
        parent::addAttribute('observation');
        parent::addAttribute('active');
        parent::addAttribute('created_at');
        parent::addAttribute('updated_at');
    }

    public function set_sex(Sex $object)
    {
        $this->sex = $object;
        $this->sex_id = $object->id;
    }

    public function get_sex()
    {
        if (empty($this->sex))
            $this->sex = new Sex($this->sex_id);

        return $this->sex;
    }




}