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


    /**
     * Reset aggregates
     */
    public function clearParts()
    {
        PatientDisability::where('patient_id', '=', $this->id)->delete();
    }


    /**
     * Add a Disability to the Patient
     * @param $object Instance of Disability
     */
    public function addPatientDisability(Disability $disability)
    {
        $object = new PatientDisability;
        $object->disability_id = $disability->id;
        $object->patient_id = $this->id;
        $object->store();
    }

    /**
     * Return the patient' Disabilities
     * @return Collection of Disability
     */
    public function getPatientDisabilities()
    {
        return parent::loadAggregate('Disability', 'PatientDisability', 'patient_id', 'disability_id', $this->id);
    }


    public static function list_uf(){

        return array(
            'AC'=>'Acre',
            'AL'=>'Alagoas',
            'AP'=>'Amapá',
            'AM'=>'Amazonas',
            'BA'=>'Bahia',
            'CE'=>'Ceará',
            'DF'=>'Distrito Federal',
            'ES'=>'Espírito Santo',
            'GO'=>'Goiás',
            'MA'=>'Maranhão',
            'MT'=>'Mato Grosso',
            'MS'=>'Mato Grosso do Sul',
            'MG'=>'Minas Gerais',
            'PA'=>'Pará',
            'PB'=>'Paraíba',
            'PR'=>'Paraná',
            'PE'=>'Pernambuco',
            'PI'=>'Piauí',
            'RJ'=>'Rio de Janeiro',
            'RN'=>'Rio Grande do Norte',
            'RS'=>'Rio Grande do Sul',
            'RO'=>'Rondônia',
            'RR'=>'Roraima',
            'SC'=>'Santa Catarina',
            'SP'=>'São Paulo',
            'SE'=>'Sergipe',
            'TO'=>'Tocantins'
        );
    }


}