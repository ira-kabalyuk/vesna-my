<?php
// добавление голоса 2 - товар
global $db;
$id=$args[1];
$v=$db->get_recs("select type, parent_id as pid from reviews where id=$id");

if($v['type']==2){
	$vt=$db->get_recs(
	"select AVG(vote) as r , count(id) as v from reviews where parent_id={$v['pid']} and is_hidden=0");
	$db->execute("update skat set votes={$vt['v']} ,rating=".round($vt['r'],0)." where id=".$v['pid']);
}
