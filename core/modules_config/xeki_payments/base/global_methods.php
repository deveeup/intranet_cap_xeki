<?php
/**
 * version 0.0001
 * Agent utis methods
 */

function d($info)
{
    echo '<pre>';
    print_r($info);
    echo '</pre>';
}

function cleanToPrint($str)
{
    return html_entity_decode($str, ENT_COMPAT, 'UTF-8');
}

function clear_str($cadena)
{
//$str=strtr($cadena, "???????????", "&aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;");
    $cadena = str_replace('?', "&aacute;", $cadena);
    $cadena = str_replace('?', "&eacute;", $cadena);
    $cadena = str_replace('?', "&iacute;", $cadena);
    $cadena = str_replace('?', "&oacute;", $cadena);
    $cadena = str_replace('?', "&uacute;", $cadena);
    $cadena = str_replace('?', "&Aacute;", $cadena);
    $cadena = str_replace('?', "&Eacute;", $cadena);
    $cadena = str_replace('?', "&Iacute;", $cadena);
    $cadena = str_replace('?', "&Oacute;", $cadena);
    $cadena = str_replace('?', "&Uacute;", $cadena);
    $cadena = str_replace('?', "&ntilde;", $cadena);
    $cadena = str_replace('?', "&Ntilde;", $cadena);


    $cadena = str_replace('?', "&Oacute;", $cadena);
    $cadena = str_replace('?', "&ntilde;", $cadena);
    $cadena = str_replace('?', "&ntilde;", $cadena);
    $cadena = str_replace('?', "&Ntilde;", $cadena);
    $cadena = str_replace('?', "&Ntilde;", $cadena);


    $cadena = str_replace('?', "&aacute;", $cadena);
    $cadena = str_replace('?', "&eacute;", $cadena);
    $cadena = str_replace('?', "&iacute;", $cadena);
    $cadena = str_replace('?', "&oacute;", $cadena);
    $cadena = str_replace('?', "&uacute;", $cadena);

    $cadena = str_replace('ó', "&oacute;", $cadena);
    $cadena = str_replace('á', "&aacute;", $cadena);
    $cadena = str_replace('é', "&aacute;", $cadena);
    $cadena = str_replace('?*', "&iacute;", $cadena);
    $cadena = str_replace('ú', "&uacute;", $cadena);
    $cadena = str_replace('?', "&iacute;", $cadena);


    $cadena = str_replace('??', "&Aacute;", $cadena);
    $cadena = str_replace('?', "&Eacute;", $cadena);
    $cadena = str_replace('??', "&Iacute;", $cadena);
    $cadena = str_replace('?', "&Oacute;", $cadena);
    $cadena = str_replace('?', "&Uacute;", $cadena);

    return $cadena;
}

function clear_str_no_acutes($cadena)
{
//$str=strtr($cadena, "???????????", "&aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;");
    $cadena = str_replace('?', "a", $cadena);
    $cadena = str_replace('?', "e", $cadena);
    $cadena = str_replace('?', "i", $cadena);
    $cadena = str_replace('?', "o", $cadena);
    $cadena = str_replace('?', "u", $cadena);
    $cadena = str_replace('?', "A", $cadena);
    $cadena = str_replace('?', "E", $cadena);
    $cadena = str_replace('?', "I", $cadena);
    $cadena = str_replace('?', "O", $cadena);
    $cadena = str_replace('?', "U", $cadena);
    $cadena = str_replace('?', "n;", $cadena);
    $cadena = str_replace('?', "N", $cadena);


    $cadena = str_replace('?', "O", $cadena);
    $cadena = str_replace('?',  "n", $cadena);
    $cadena = str_replace('?', "n", $cadena);
    $cadena = str_replace('?',  "N", $cadena);
    $cadena = str_replace('?', "N", $cadena);


    $cadena = str_replace('?', "a", $cadena);
    $cadena = str_replace('?', "e", $cadena);
    $cadena = str_replace('?', "i", $cadena);
    $cadena = str_replace('?', "o", $cadena);
    $cadena = str_replace('?', "u", $cadena);

    $cadena = str_replace('ó', "o", $cadena);
    $cadena = str_replace('á', "a", $cadena);
    $cadena = str_replace('é', "a", $cadena);
    $cadena = str_replace('?*', "i", $cadena);
    $cadena = str_replace('ú', "u", $cadena);
    $cadena = str_replace('?', "i", $cadena);


    $cadena = str_replace('??', "A", $cadena);
    $cadena = str_replace('?', "E", $cadena);
    $cadena = str_replace('??', "I", $cadena);
    $cadena = str_replace('?', "O", $cadena);
    $cadena = str_replace('?', "U", $cadena);

    return $cadena;
}

function limpiarCadena($cadena)
{
    return clear_str($cadena);
}

function limpiarCadenaUTF8($cadena)
{
//$str=strtr($cadena, "???????????", "&aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;");
    $cadena = str_replace('?', "?", $cadena);
    $cadena = str_replace('?', "?", $cadena);
    $cadena = str_replace('?', "?", $cadena);

    $cadena = str_replace('ó', "?", $cadena);
    $cadena = str_replace('á', "?", $cadena);
    $cadena = str_replace('é', "?", $cadena);
    $cadena = str_replace('?*', "?", $cadena);
    $cadena = str_replace('ú', "?", $cadena);
    $cadena = str_replace('?', "?", $cadena);
    return $cadena;
}

function cleanHtml($data)
{
    $data = limpiarBasicsRev($data);
    $data = preg_replace('@<(p|font|b)[^>]*>@is', '', $data);
    $data = preg_replace('@</(p|font|b)>@is', '', $data);
    return $data;
}

function limpiarBasicsRev($cadena)
{
// $cadena = str_replace('??', "?", $cadena);
    $cadena = str_replace('&aacute;', "?", $cadena);
    $cadena = str_replace('&eacute;', "?", $cadena);
    $cadena = str_replace('&iacute;', "?", $cadena);
    $cadena = str_replace('&oacute;', "?", $cadena);
    $cadena = str_replace('&uacute;', "?", $cadena);
    $cadena = str_replace('&Aacute;', "?", $cadena);
    $cadena = str_replace('&Eacute;', "?", $cadena);
    $cadena = str_replace('&Iacute;', "?", $cadena);
    $cadena = str_replace('&Oacute;', "?", $cadena);
    $cadena = str_replace('&Uacute;', "?", $cadena);
    $cadena = str_replace('&ntilde;', "?", $cadena);
    $cadena = str_replace('&Ntilde;', "?", $cadena);
    return $cadena;
}

function formatcurrency($floatcurr, $curr = "COP")
{
    $floatcurr = (float)$floatcurr;
    $currencies['ARS'] = array(2, ',', '.');          //  Argentine Peso
    $currencies['AMD'] = array(2, '.', ',');          //  Armenian Dram
    $currencies['AWG'] = array(2, '.', ',');          //  Aruban Guilder
    $currencies['AUD'] = array(2, '.', ' ');          //  Australian Dollar
    $currencies['BSD'] = array(2, '.', ',');          //  Bahamian Dollar
    $currencies['BHD'] = array(3, '.', ',');          //  Bahraini Dinar
    $currencies['BDT'] = array(2, '.', ',');          //  Bangladesh, Taka
    $currencies['BZD'] = array(2, '.', ',');          //  Belize Dollar
    $currencies['BMD'] = array(2, '.', ',');          //  Bermudian Dollar
    $currencies['BOB'] = array(2, '.', ',');          //  Bolivia, Boliviano
    $currencies['BAM'] = array(2, '.', ',');          //  Bosnia and Herzegovina, Convertible Marks
    $currencies['BWP'] = array(2, '.', ',');          //  Botswana, Pula
    $currencies['BRL'] = array(2, ',', '.');          //  Brazilian Real
    $currencies['BND'] = array(2, '.', ',');          //  Brunei Dollar
    $currencies['CAD'] = array(2, '.', ',');          //  Canadian Dollar
    $currencies['KYD'] = array(2, '.', ',');          //  Cayman Islands Dollar
    $currencies['CLP'] = array(0, '', '.');           //  Chilean Peso
    $currencies['CNY'] = array(2, '.', ',');          //  China Yuan Renminbi
    $currencies['COP'] = array(0, '', '.');          //  Colombian Peso
    $currencies['CRC'] = array(2, ',', '.');          //  Costa Rican Colon
    $currencies['HRK'] = array(2, ',', '.');          //  Croatian Kuna
    $currencies['CUC'] = array(2, '.', ',');          //  Cuban Convertible Peso
    $currencies['CUP'] = array(2, '.', ',');          //  Cuban Peso
    $currencies['CYP'] = array(2, '.', ',');          //  Cyprus Pound
    $currencies['CZK'] = array(2, '.', ',');          //  Czech Koruna
    $currencies['DKK'] = array(2, ',', '.');          //  Danish Krone
    $currencies['DOP'] = array(2, '.', ',');          //  Dominican Peso
    $currencies['XCD'] = array(2, '.', ',');          //  East Caribbean Dollar
    $currencies['EGP'] = array(2, '.', ',');          //  Egyptian Pound
    $currencies['SVC'] = array(2, '.', ',');          //  El Salvador Colon
    $currencies['ATS'] = array(2, ',', '.');          //  Euro
    $currencies['BEF'] = array(2, ',', '.');          //  Euro
    $currencies['DEM'] = array(2, ',', '.');          //  Euro
    $currencies['EEK'] = array(2, ',', '.');          //  Euro
    $currencies['ESP'] = array(2, ',', '.');          //  Euro
    $currencies['EUR'] = array(2, ',', '.');          //  Euro
    $currencies['FIM'] = array(2, ',', '.');          //  Euro
    $currencies['FRF'] = array(2, ',', '.');          //  Euro
    $currencies['GRD'] = array(2, ',', '.');          //  Euro
    $currencies['IEP'] = array(2, ',', '.');          //  Euro
    $currencies['ITL'] = array(2, ',', '.');          //  Euro
    $currencies['LUF'] = array(2, ',', '.');          //  Euro
    $currencies['NLG'] = array(2, ',', '.');          //  Euro
    $currencies['PTE'] = array(2, ',', '.');          //  Euro
    $currencies['GHC'] = array(2, '.', ',');          //  Ghana, Cedi
    $currencies['GIP'] = array(2, '.', ',');          //  Gibraltar Pound
    $currencies['GTQ'] = array(2, '.', ',');          //  Guatemala, Quetzal
    $currencies['HNL'] = array(2, '.', ',');          //  Honduras, Lempira
    $currencies['HKD'] = array(2, '.', ',');          //  Hong Kong Dollar
    $currencies['HUF'] = array(0, '', '.');           //  Hungary, Forint
    $currencies['ISK'] = array(0, '', '.');           //  Iceland Krona
    $currencies['INR'] = array(2, '.', ',');          //  Indian Rupee
    $currencies['IDR'] = array(2, ',', '.');          //  Indonesia, Rupiah
    $currencies['IRR'] = array(2, '.', ',');          //  Iranian Rial
    $currencies['JMD'] = array(2, '.', ',');          //  Jamaican Dollar
    $currencies['JPY'] = array(0, '', ',');           //  Japan, Yen
    $currencies['JOD'] = array(3, '.', ',');          //  Jordanian Dinar
    $currencies['KES'] = array(2, '.', ',');          //  Kenyan Shilling
    $currencies['KWD'] = array(3, '.', ',');          //  Kuwaiti Dinar
    $currencies['LVL'] = array(2, '.', ',');          //  Latvian Lats
    $currencies['LBP'] = array(0, '', ' ');           //  Lebanese Pound
    $currencies['LTL'] = array(2, ',', ' ');          //  Lithuanian Litas
    $currencies['MKD'] = array(2, '.', ',');          //  Macedonia, Denar
    $currencies['MYR'] = array(2, '.', ',');          //  Malaysian Ringgit
    $currencies['MTL'] = array(2, '.', ',');          //  Maltese Lira
    $currencies['MUR'] = array(0, '', ',');           //  Mauritius Rupee
    $currencies['MXN'] = array(2, '.', ',');          //  Mexican Peso
    $currencies['MZM'] = array(2, ',', '.');          //  Mozambique Metical
    $currencies['NPR'] = array(2, '.', ',');          //  Nepalese Rupee
    $currencies['ANG'] = array(2, '.', ',');          //  Netherlands Antillian Guilder
    $currencies['ILS'] = array(2, '.', ',');          //  New Israeli Shekel
    $currencies['TRY'] = array(2, '.', ',');          //  New Turkish Lira
    $currencies['NZD'] = array(2, '.', ',');          //  New Zealand Dollar
    $currencies['NOK'] = array(2, ',', '.');          //  Norwegian Krone
    $currencies['PKR'] = array(2, '.', ',');          //  Pakistan Rupee
    $currencies['PEN'] = array(2, '.', ',');          //  Peru, Nuevo Sol
    $currencies['UYU'] = array(2, ',', '.');          //  Peso Uruguayo
    $currencies['PHP'] = array(2, '.', ',');          //  Philippine Peso
    $currencies['PLN'] = array(2, '.', ' ');          //  Poland, Zloty
    $currencies['GBP'] = array(2, '.', ',');          //  Pound Sterling
    $currencies['OMR'] = array(3, '.', ',');          //  Rial Omani
    $currencies['RON'] = array(2, ',', '.');          //  Romania, New Leu
    $currencies['ROL'] = array(2, ',', '.');          //  Romania, Old Leu
    $currencies['RUB'] = array(2, ',', '.');          //  Russian Ruble
    $currencies['SAR'] = array(2, '.', ',');          //  Saudi Riyal
    $currencies['SGD'] = array(2, '.', ',');          //  Singapore Dollar
    $currencies['SKK'] = array(2, ',', ' ');          //  Slovak Koruna
    $currencies['SIT'] = array(2, ',', '.');          //  Slovenia, Tolar
    $currencies['ZAR'] = array(2, '.', ' ');          //  South Africa, Rand
    $currencies['KRW'] = array(0, '', ',');           //  South Korea, Won
    $currencies['SZL'] = array(2, '.', ', ');         //  Swaziland, Lilangeni
    $currencies['SEK'] = array(2, ',', '.');          //  Swedish Krona
    $currencies['CHF'] = array(2, '.', '\'');         //  Swiss Franc
    $currencies['TZS'] = array(2, '.', ',');          //  Tanzanian Shilling
    $currencies['THB'] = array(2, '.', ',');          //  Thailand, Baht
    $currencies['TOP'] = array(2, '.', ',');          //  Tonga, Paanga
    $currencies['AED'] = array(2, '.', ',');          //  UAE Dirham
    $currencies['UAH'] = array(2, ',', ' ');          //  Ukraine, Hryvnia
    $currencies['USD'] = array(2, '.', ',');          //  US Dollar
    $currencies['VUV'] = array(0, '', ',');           //  Vanuatu, Vatu
    $currencies['VEF'] = array(2, ',', '.');          //  Venezuela Bolivares Fuertes
    $currencies['VEB'] = array(2, ',', '.');          //  Venezuela, Bolivar
    $currencies['VND'] = array(0, '', '.');           //  Viet Nam, Dong
    $currencies['ZWD'] = array(2, '.', ' ');          //  Zimbabwe Dollar
    if ($curr == "INR") {
        return formatinr($floatcurr);
    } else {
        return number_format($floatcurr, $currencies[$curr][0], $currencies[$curr][1], $currencies[$curr][2]);
    }
}

function formatinr($input)
{
//CUSTOM FUNCTION TO GENERATE ##,##,###.##
    $dec = "";
    $pos = strpos($input, ".");
    if ($pos === false) {
//no decimals
    } else {
//decimals
        $dec = substr(round(substr($input, $pos), 2), 1);
        $input = substr($input, 0, $pos);
    }
    $num = substr($input, -3); //get the last 3 digits
    $input = substr($input, 0, -3); //omit the last 3 digits already stored in $num
    while (strlen($input) > 0) //loop the process - further get digits 2 by 2
    {
        $num = substr($input, -2) . "," . $num;
        $input = substr($input, 0, -2);
    }
    return $num . $dec;
}


## end class
function toFullTextSearch($cadena)
{
    $cadena = str_replace('?', "a;", $cadena);
    $cadena = str_replace('?', "e;", $cadena);
    $cadena = str_replace('?', "i;", $cadena);
    $cadena = str_replace('?', "o;", $cadena);
    $cadena = str_replace('?', "u;", $cadena);
    $cadena = str_replace('?', "A;", $cadena);
    $cadena = str_replace('?', "E;", $cadena);
    $cadena = str_replace('?', "I;", $cadena);
    $cadena = str_replace('?', "O;", $cadena);
    $cadena = str_replace('?', "U;", $cadena);
    $cadena = str_replace('?', "n;", $cadena);
    $cadena = str_replace('?', "N;", $cadena);


    $cadena = str_replace('?', "O;", $cadena);
    $cadena = str_replace('?', "n;", $cadena);
    $cadena = str_replace('?', "n;", $cadena);
    $cadena = str_replace('?', "N;", $cadena);
    $cadena = str_replace('?', "N;", $cadena);

    $cadena = str_replace('ó', "o;", $cadena);
    $cadena = str_replace('á', "a;", $cadena);
    $cadena = str_replace('é', "a;", $cadena);
    $cadena = str_replace('?*', "i;", $cadena);
    $cadena = str_replace('ú', "u;", $cadena);
    $cadena = str_replace('?', "i;", $cadena);
    $cadena = str_replace('??', "A;", $cadena);
    $cadena = str_replace('??', "I;", $cadena);

    $cadena = str_replace('&aacute;', "a", $cadena);
    $cadena = str_replace('&eacute;', "e", $cadena);
    $cadena = str_replace('&iacute;', "i", $cadena);
    $cadena = str_replace('&oacute;', "o", $cadena);
    $cadena = str_replace('&uacute;', "u", $cadena);
    $cadena = str_replace('&Aacute;', "A", $cadena);
    $cadena = str_replace('&Eacute;', "E", $cadena);
    $cadena = str_replace('&Iacute;', "I", $cadena);
    $cadena = str_replace('&Oacute;', "O", $cadena);
    $cadena = str_replace('&Uacute;', "U", $cadena);
    $cadena = str_replace('&ntilde;', "n", $cadena);
    $cadena = str_replace('&Ntilde;', "N", $cadena);

    $cadena = str_replace('&Oacute;', "O", $cadena);
    $cadena = str_replace('&ntilde;', "n", $cadena);
    $cadena = str_replace('&ntilde;', "n", $cadena);
    $cadena = str_replace('&Ntilde;', "N", $cadena);
    $cadena = str_replace('&Ntilde;', "N", $cadena);

    $cadena = str_replace('&oacute;', "o", $cadena);
    $cadena = str_replace('&aacute;', "a", $cadena);
    $cadena = str_replace('&aacute;', "a", $cadena);
    $cadena = str_replace('&iacute;', "i", $cadena);
    $cadena = str_replace('&uacute;', "u", $cadena);
    $cadena = str_replace('&iacute;', "i", $cadena);
    $cadena = str_replace('&Aacute;', "A", $cadena);
    $cadena = str_replace('&Iacute;', "I", $cadena);
    return strtolower(cleanHtml($cadena));
}

function incrementalHash($len = 5)
{
    $charset = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    $base = strlen($charset);
    $result = '';

    $now = explode(' ', microtime());
    $now = $now[1];

    while ($now >= $base) {
        $i = $now % $base;
        $result = $charset[$i] . $result;
        $now /= $base;
    }
    return md5(substr($result, -10));
}

class BaseIntEncoder
{
    //const $codeset = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    //readable character set excluded (0,O,1,l)
    const codeset = "23456789abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ";

    static function encode($n)
    {
        $base = strlen(self::codeset);
        $converted = '';
        while ($n > 0) {
            $converted = substr(self::codeset, bcmod($n, $base), 1) . $converted;
            $n = self::bcFloor(bcdiv($n, $base));
        }
        return $converted;
    }

    static function decode($code)
    {
        $base = strlen(self::codeset);
        $c = '0';
        for ($i = strlen($code); $i; $i--) {
            $c = bcadd($c, bcmul(strpos(self::codeset, substr($code, (-1 * ($i - strlen($code))), 1))
                , bcpow($base, $i - 1)));
        }
        return bcmul($c, 1, 0);
    }

    static private function bcFloor($x)
    {
        return bcmul($x, '1', 0);
    }

    static private function bcCeil($x)
    {
        $floor = bcFloor($x);
        return bcadd($floor, ceil(bcsub($x, $floor)));
    }

    static private function bcRound($x)
    {
        $floor = bcFloor($x);
        return bcadd($floor, round(bcsub($x, $floor)));
    }
}
