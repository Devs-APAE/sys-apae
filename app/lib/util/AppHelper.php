<?php

class AppHelper
{
    public static function toDouble($value, $casas = null)
    {
        if (is_int(strpos((string) $value, ','))) {
            $value = str_replace('R$', '', (string) $value);
            $value = str_replace('%', '', (string) $value);
            $value = str_replace('.', '', (string) $value);
            $value = (double) str_replace(',', '.', (string) $value);
        } else {
            // alteração feita para compatilizar com JQuery-mask
            if (!strpos((string) $value, '.')) {
                $value = (double) (($value)?$value:'0').'.00';
            }
        }
        if ($casas) {
            $value = number_format((string) $value, 1, '.', ',');
        }
        return $value;
    }

    public static function toNumeric($value, $casas = 2)
    {
        if($value){
            return number_format((string) $value, $casas, ',', '.');
        }
    }

    public static function toMonetario($value)
    {
        return 'R$ ' . number_format((string) $value, 2, ',', '.');
    }

    public static function toExtenso($value)
    {
        $value = str_replace('.', '', (string) $value);
        $value = str_replace(',', '', (string) $value);
        return GExtenso::moeda($value, 2);
    }

    public static function toCleanSimple($value)
    {
        $value = str_replace('.', '', (string) $value);
        $value = str_replace('-', '', (string) $value);
        $value = str_replace('/', '', (string) $value);
        return $value;
    }

    public static function toClean($value)
    {
        $value = str_replace('.', '', (string) $value);
        $value = str_replace(',', '', (string) $value);
        $value = str_replace('-', '', (string) $value);
        $value = str_replace('/', '', (string) $value);
        $value = str_replace('(', '', (string) $value);
        $value = str_replace(')', '', (string) $value);
        $value = str_replace('[', '', (string) $value);
        $value = str_replace(']', '', (string) $value);
        $value = str_replace('{', '', (string) $value);
        $value = str_replace('}', '', (string) $value);
        $value = str_replace('%', '', (string) $value);
        $value = str_replace('R$', '', (string) $value);
        return $value;
    }

    public static function toOnlyNumber($value)
    {
        return preg_replace("/[^0-9]/", "", $value);
    }

    public static function toCleanForm($form)
    {
        $array = array();
        foreach ($form->getFields() as $field) {
            if (!$field instanceof TButton) {
                if ($field instanceof TCombo) {
                    $array += [$field->getName() => null];
                } elseif ($field instanceof TDBUniqueSearch) {
                    $field->setValue('');
                } elseif ($field instanceof TDBMultiSearch) {
                    $array += [$field->getName() => null];
                } else {
                    $array += [$field->getName() => ''];
                }
            }
        }

        TForm::sendData($form->getName(), AppHelper::inStdClass($array));
    }


    public static function inStdClass($array)
    {
        $std = new StdClass;
        if (!is_array($array)) {
            $array = $array->toArray();
        }
        foreach ($array as $key => $value) {
            $std->$key = $value;
        }
        return $std;
    }

    public static function toSizeR($value, $size)
    {
        $value = substr((string) $value, 0, $size);
        $value = str_pad((string) $value, $size, '0', STR_PAD_RIGHT);
        return $value;
    }

    public static function toSizeL($value, $size)
    {
        // $value = substr($value, 0, $size);
        $value = str_pad((string) $value, $size, '0', STR_PAD_LEFT);
        return $value;
    }

    public static function toDateUS($value)
    {
        try{
            if ($value) {
                $date = DateTime::createFromFormat('d/m/Y', $value);
                return $date->format('Y-m-d');
            }
            return null;
        }catch(Exception $e){
            return null;
        }
    }

    public static function toDateBR($value)
    {
        try{
            if ($value) {
                $date = new DateTime($value);
                return $date->format('d/m/Y');
            }
            return null;
        }catch(Exception $e){
            return null;
        }
    }

    public static function toCheckDate($value){
        $d = DateTime::createFromFormat('d/m/Y', $value);
        if($d && $d->format('d/m/Y') == $value){
            return true;
        }else{
            return false;
        }
    }

    public static function toDateTimeUS($value)
    {
        if ($value) {
            $date = DateTime::createFromFormat('d/m/Y H:i:s', $value);
            return $date->format('Y-m-d H:i:s');
        }
        return null;
    }

    public static function toDateTimeBR($value)
    {
        if ($value) {
            $date = new DateTime($value);
            return $date->format('d/m/Y H:i:s');
        }
        return null;
    }

    public static function toSubStr($str, $size)
    {
        return substr($str, 0, $size);
    }

    public static function seoUrl($str)
    {
        $aaa = array('/(à|á|â|ã|ä|å|æ)/','/(è|é|ê|ë)/','/(ì|í|î|ï)/','/(ð|ò|ó|ô|õ|ö|ø)/','/(ù|ú|û|ü)/','/ç/','/þ/','/ñ/','/ß/','/(ý|ÿ)/','/(=|\+|\/|\\\|\.|\'|\_|\\n| |\(|\))/','/[^a-z0-9_ -]/s');
        $bbb = array('a','e','i','o','u','c','d','n','s','y','-','');
        return trim(trim(trim(preg_replace('/-{2,}/s', '-', preg_replace($aaa, $bbb, strtolower($str)))), '_'), '-');
    }

    public static function toUpper($str)
    {
        return strtr(strtoupper($str), "àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
    }

    public static function toLower($str)
    {
        return strtr(strtolower($str), "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß", "àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ");
    }

    public static function toMask($val, $mask)
    {
        $maskared = '';
        $k = 0;
        for ($i = 0; $i<=strlen($mask)-1; $i++) {
            if ($mask[$i] == '#') {
                if (isset($val[$k])) {
                    $maskared .= $val[$k++];
                }
            } else {
                if (isset($mask[$i])) {
                    $maskared .= $mask[$i];
                }
            }
        }
        return $maskared;
    }

    public static function toWindowData($param)
    {
        // show form values inside a window
        $win = TWindow::create('test', 0.6, 0.8);
        $win->add('<pre>'.str_replace("\n", '<br>', print_r($param, true)).'</pre>');
        $win->show();
    }

    public static function toSlug($title)
    {
        if(!empty($title)){
            $title = strip_tags($title);
            // Preserve escaped octets.
            $title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);
            // Remove percent signs that are not part of an octet.
            $title = str_replace('%', '', $title);
            // Restore octets.
            $title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);
        
            $title =  mb_convert_encoding($title, 'UTF-8', 'ISO-8859-1');

            $title = strtolower($title ?? "");
        
            // Kill entities.
            $title = preg_replace('/&.+?;/', '', $title);
            $title = str_replace('.', '-', $title);
        
            $title = preg_replace('/[^%a-z0-9 _-]/', '', $title);
            $title = preg_replace('/\s+/', '-', $title);
            $title = preg_replace('|-+|', '-', $title);
            $title = trim($title, '-');
        }
    
        return $title ?? "";
    }
    public static function formatPhone($value)
    {
        if (strlen(strval($value)) == 11) {
            $value = preg_replace("/(\d{2})(\d{1})(\d{4})(\d{4})/", "(\$1) \$2 \$3-\$4", $value);
        } else {
            if (strlen(strval($value)) == 10) {
                $value = preg_replace("/(\d{2})(\d{4})(\d{4})/", "(\$1) \$2-\$3", $value);
            }
        }
        return $value;
    }


    public static function formatCEP($value)
    {
        if (strlen(strval($value)) == 8) {
            $value = preg_replace('/([0-9]{5})([0-9]{3})/', '$1-$2', $value);
        }
        return $value;
    }

    public static function formatCpfCnpj($value)
    {
        if (strlen($value) == 14) {
            $value = preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $value);
        } else {
            if (strlen($value) == 11) {
                $value = preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $value);
            }
        }
        return $value;
    }

    public static function subStr($value, $amount = 20)
    {
        if (!empty($value)) {
            $sub = mb_substr($value, 0, $amount, 'UTF-8');
            if (strlen($value) > $amount) {
                return $sub . "...";
            }
        }
        return $value;
    }

    public static function toUcwords($value)
    {
        if ($value) {
            $str = mb_strtolower(trim($value));
            $words = preg_split('/\b/u', $str, -1, PREG_SPLIT_NO_EMPTY);
            foreach ($words as $word) {
                $ucword = mb_strtoupper(mb_substr($word, 0, 1)) . mb_substr($word, 1);
                $str = str_replace($word, $ucword, $str);
            }
            return $str;
        }
    }

    public static function isValidPhone(string $phone): bool {

        $phone = preg_replace('/\D/', '', $phone);

        if (strlen($phone) !== 11) {
            return false;
        }

        $ddd = substr($phone, 0, 2);
        if ((int)$ddd < 11 || (int)$ddd > 99) {
            return false;
        }

        if ($phone[2] != '9') {
            return false;
        }

        return true;
    }

    public static function isValidCPF($value): bool
    {
        // cpfs inválidos
        $nulos = array("12345678909","11111111111","22222222222","33333333333",
                       "44444444444","55555555555","66666666666","77777777777",
                       "88888888888","99999999999","00000000000");
        // Retira todos os caracteres que nao sejam 0-9
        $cpf = preg_replace("/[^0-9]/", "", $value);
        
        if (strlen($cpf) <> 11)
        {
            return false;
        }
        
        // Retorna falso se houver letras no cpf
        if (!(preg_match("/[0-9]/",$cpf)))
        {
            return false;
        }

        // Retorna falso se o cpf for nulo
        if( in_array($cpf, $nulos) )
        {
            return false;
        }

        // Calcula o penúltimo dígito verificador
        $acum=0;
        for($i=0; $i<9; $i++)
        {
          $acum+= $cpf[$i]*(10-$i);
        }

        $x=$acum % 11;
        $acum = ($x>1) ? (11 - $x) : 0;
        // Retorna falso se o digito calculado eh diferente do passado na string
        if ($acum != $cpf[9])
        {
          return false;
        }
        // Calcula o último dígito verificador
        $acum=0;
        for ($i=0; $i<10; $i++)
        {
          $acum+= $cpf[$i]*(11-$i);
        }  

        $x=$acum % 11;
        $acum = ($x > 1) ? (11-$x) : 0;
        // Retorna falso se o digito calculado eh diferente do passado na string
        if ( $acum != $cpf[10])
        {
          return false;
        } 
        
        return true;
    }

    public static function isValidEmail(string $email): bool {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}
