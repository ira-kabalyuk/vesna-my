<?
class Sform{

	
	static function editor($arg){
	
        include $_SERVER['DOCUMENT_ROOT']."/rul/fck6/fckeditor.php";
        $sBasePath="/rul/fck6/";
        
 
	$editor = new FCKeditor(isset($arg['name']) ? $arg['name'] : 'descr');
	$editor->Width=(isset($arg['width']) ? $arg['width'] : "600px");
	$editor->Height=(isset($arg['height']) ? $arg['height'] : "300px");
	$editor ->BasePath	= $sBasePath ;
	$editor->ToolbarSet =(isset($arg['toolbar']) ? $arg['toolbar'] : "Basic");
	$editor->Value = $arg['data'];
	return $editor->Create() ;
	}
	
	static function selbox($ar){
		$ret="";
		$i=0;
		foreach($ar['cont'] as $key=>$title){
			$ret.='<div><input type="checkbox" name="'.$ar['name'].'['.$i.']" value="'.$key.'" '.(in_array($key,$ar['val']) ? 'checked':'').'/>'.$title.'</div>';
			$i++;
		}
		return $ret;
	}
	static function select($sqlhash,$id){
		global $db;
		$res=$db->hash($sqlhash);
		$ret="";
		foreach($res as $key=>$title){
			$ret.='<option value="'.$key.'" '.($key==$id ? 'selected':'').'/>'.$title.'</option>';
			
		}
		return $ret;
	}
	
}
?>