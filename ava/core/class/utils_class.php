<?
class Utils{
  
  static function translit($str){
	 $tbl= array(  
        'а' => 'a',   'б' => 'b',   'в' => 'v',  
        'г' => 'g',   'д' => 'd',   'е' => 'e',  
        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',  
        'и' => 'i',   'й' => 'y',   'к' => 'k',  
        'л' => 'l',   'м' => 'm',   'н' => 'n',  
        'о' => 'o',   'п' => 'p',   'р' => 'r',  
        'с' => 's',   'т' => 't',   'у' => 'u',  
        'ф' => 'f',   'х' => 'h',   'ц' => 'c',  
        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',  
        'ь' => "'",  'ы' => 'y',   'ъ' => "'",  
        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',  
  
        'А' => 'A',   'Б' => 'B',   'В' => 'V',  
        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',  
        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',  
        'И' => 'I',   'Й' => 'Y',   'К' => 'K',  
        'Л' => 'L',   'М' => 'M',   'Н' => 'N',  
        'О' => 'O',   'П' => 'P',   'Р' => 'R',  
        'С' => 'S',   'Т' => 'T',   'У' => 'U',  
        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',  
        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',  
        'Ь' => "'",  'Ы' => 'Y',   'Ъ' => "'",  
        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',  
    );  
 $str=strtr($str, $tbl);  
$str=preg_replace("/[^(a-z)|(A-Z)|(0-9)|_|-| ]/","",$str);
//$str=iconv('UTF-8','UTF-8//TRANSLIT',$str);
$str=preg_replace("/[ ]+/","-",$str);
    return strtr($str, $tbl);
}
    
    
}
?>