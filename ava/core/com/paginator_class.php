<?php
class Com_paginator{
	
	/**
	 * Com_paginator::_get()
	 *  пагинация выводит ссылки на все страницы
	 * @param mixed $max лимит
	 * @param mixed $count кол-во записей
	 * @param mixed $page текущая страница
	 * @param mixed $link ссылка
	 * @return array('limit','paginator');
	 */
	static function _get($max,$count,$page,$link){
		$pg=array('limit'=>" ",'paginator'=>array());
		if($count==0) return $pg;
		if($max==0) return $pg;
		$page=($page==0 ? 1:$page);
		
		//$link.=(stripos($link,"?") ? "&page=":"?page=");
		$link.="/page-";
		$p=1;
		$next=$page+1;
		while($count>0){
			$class="";
			if($next==$p) $class='class="next"';
			$pg['paginator'][]=($p==$page ? $p : '<a href="'.$link.$p.'" '.$class.' data-page="'.$p.'">'.$p.'</a>');
			$count-=$max;
			$p++;
		}
		$pg['limit']=" limit ".($page==1 ? "0,$max" : ($page-1)*$max.",".$max);
		if(count($pg['paginator'])==1) $pg['paginator']=array();
		return $pg;
	}
	
	
	/**
	 * Com_paginator::_next()
	 * выполняет запрос и устанавливает NEXTPAGE в шаблоне
	 * @param integer $max колво на страницу
	 * @param string $sql запрос
	 * @param integer $page текущая страница(если 0 то берется _postn)
	 * @return array результат запроса
	 */
	function _next($max,$sql,$page=0){
			global $db,$htm;
			if($page==0) $page=_postn('page');
			$page=($page==0 ? 1:$page);
			if($max==0) return $db->select($sql);
			$sql.=" limit ".($page-1)*$max.",".($max+1);
			$res=$db->select($sql);
			$count=count($res);
			if($count==0) return $res;
			if($count>$max){
				array_pop($res);
				$page++;
				$htm->assign("NEXTPAGE",$page);
				}
		return $res;
		
	}	
	
}