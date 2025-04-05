<?php
/**
 * Professional Active Record
 * @author Luiz Henrique Lazarin
 */
class Professional extends TRecord
{
    const TABLENAME  = 'professionals';
    const PRIMARYKEY = 'id';
    const ID_POLICY  = 'max';

    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('name');
        parent::addAttribute('cpf');
        parent::addAttribute('crm');
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

    // Clear any associated specialties when the professional is deleted
    public function clearParts()
    {
        ProfessionalSpeciality::where('professional_id', '=', $this->id)->delete();
    }

    // Add a professional speciality
    public function addProfessionalSpeciality(Speciality $speciality) 
    {
        $object = new ProfessionalSpeciality;
        $object->speciality_id = $speciality->id;
        $object->professional_id = $this->id;
        $object->store();
    }

    // Get the specialities of a professional
    public function getProfessionalSpeciality()
    {
        return parent::loadAggregate('Speciality', 'ProfessionalSpeciality', 'professional_id', 'speciality_id', $this->id);
    }

    /**
     * Retorna uma lista de estados brasileiros (UFs)
     */
    public static function list_uf()
    {
        return [
            'AC' => 'Acre',
            'AL' => 'Alagoas',
            'AP' => 'Amapá',
            'AM' => 'Amazonas',
            'BA' => 'Bahia',
            'CE' => 'Ceará',
            'DF' => 'Distrito Federal',
            'ES' => 'Espírito Santo',
            'GO' => 'Goiás',
            'MA' => 'Maranhão',
            'MT' => 'Mato Grosso',
            'MS' => 'Mato Grosso do Sul',
            'MG' => 'Minas Gerais',
            'PA' => 'Pará',
            'PB' => 'Paraíba',
            'PR' => 'Paraná',
            'PE' => 'Pernambuco',
            'PI' => 'Piauí',
            'RJ' => 'Rio de Janeiro',
            'RN' => 'Rio Grande do Norte',
            'RS' => 'Rio Grande do Sul',
            'RO' => 'Rondônia',
            'RR' => 'Roraima',
            'SC' => 'Santa Catarina',
            'SP' => 'São Paulo',
            'SE' => 'Sergipe',
            'TO' => 'Tocantins'
        ];
    }
}

