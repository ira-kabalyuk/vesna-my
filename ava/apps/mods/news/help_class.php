<?php

class Mods_news_help
{
    static $links;
    static $modset;


    static function get_list($jar)
    {
        global $db, $htm, $Core;

        $set = parse_jar($jar);
        $w = array('is_hidden=0');

        if (isset($set['tpl']))
            $ret = $htm->load_tpl($set['tpl']);

        if (isset($set['where']))
            $w[] = $set['where'];

        $rid = intval($set['rubric']);

        $sort = (isset($set['nodate']) ? 'sort' : 'date_pub desc');

        if (isset($set['order'])) {
            $sort = ($set['order'] == 'sort' ? 'sort' : 'date_pub desc');
            if ($set['order'] == 'random')
                $sort = "RAND()";
        }
        if (isset($set['last']))
            $w[] = "date_pub<" . $db->value("select date_pub from news where id=" . $Core->link->news_id);


        $tag = intval($set['tag']);
        $cat = intval($set['cat']);
        $clear = intval($set['clear']);
        $row = (isset($set['row']) ? $set['row'] : "NEWS_LIST");
        $limit = intval($set['limit']);
        $offset = intval($set['offset']);
        $limit = ($limit == 0 ? 10 : $limit);
        $link = "news";


        if (isset($set['mod'])) {
            $conf = Com_mod::load_conf($set['mod']);

            $w[] = "parent_id=" . $conf['parent_id'];
            $link = $conf['prefix'];

        }

        if ($rid != 0) {
            $w[] = get_against('terms', 'cat_', $rid);
            $rlink = $db->value("select prefix from news_rubric where id=$rid");
            $link = ($rlink != "" ? $rlink : $link);

        }


        if ($tag != 0) $w[] = get_against('terms', 'tag_', $tag);
        if ($cat != 0) $w[] = get_against('terms', 'cat_', $cat);

        $res = $db->select("select id, title,date_pub,img, short,descr,guid,terms from news  where " . implode(" and ", $w) . " order by $sort limit $offset," . $limit);
        $c = 1;
        $i = 1;
        foreach ($res as $r) {

            if (!isset($set['nodate']))
                self::date($r);


            //$r['fotos']=$db->value("select count(*) from news_photo where is_hidden=0 and parent_id=".$r['id']);
            if (isset($set['meta']))
                $r = array_merge($r, self::metadata($r['id']));

            //if($r['meta-nolink']!='1')
            //  $r['link']="/".$link."-".$r['id'].".html";

            if ($Core->link->news_id == $r['id']) {
                $r['class'] = "active link-off";
                $r['link'] = "#";
            } else {
                $r['link'] = "/" . $link . "-" . $r['id'] . ".html";
            }

            $r['link'] = "/" . $conf['prefix'] . "/" . $r['guid'];
            if ($c == $clear) {
                $r['clear'] = 1;
                $c = 0;
            }
            $c++;
            $r['i'] = $i;
            $htm->addrow($row, $r);
            $i++;
        }

        if (isset($set['tpl'])) {
            $htm->assvar($set);
            $htm->_var($ret);
            $htm->_row($ret, true);
            return $ret;

        }
        return "";

    }


    static function index()
    {
        $news = new Mods_news_core();
        $news->_get();
    }

    static function date(&$r, $field = "date_pub", $prefix = "")
    {
        $month = array("", "января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря");
        // $month=array("","january", "february", "march", "april","may", "june", "july", "august", "september", "october", "november","december");
        $mnt = array("", "jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec");
        if (strpos($r[$field], ".") === false) {
            if (trim($r[$field]) == "") return "";
            $data = date("d.m.Y", $r[$field]);
        } else {
            $data = $r[$field];
        }


        $d = explode(".", $data);

        $r[$prefix . 'date'] = $data;

        $r[$prefix . 'day'] = $d[0];
        $r[$prefix . 'month'] = $month[intval($d[1])];
        $r[$prefix . 'mn'] = $d[1];
        $r[$prefix . 'mon'] = $mnt[intval($d[1])];
        $r[$prefix . 'year'] = $d[2];

        return $d[0] . " " . $month[intval($d[1])];

    }

    static function metadata($id = 0){
        global $Core;
        if ($id == 0) $id = $Core->link->news_id;
        return $Core->db->hash("select concat('meta_',metakey), metavalue from news_metadata where parent_id=$id");
    }
    static function langData(&$r, $lang){
        global $db;
        $id=intval($r['id']);
        $meta=$db->hash("select metakey, metavalue from news_metadata where parent_id=$id and lang='$lang'");
        $r=array_merge($r,$meta);
    }

    static function get_meta($id, $key = "")
    {
        global $db;
        if ($key == "")
            return $db->hash("select metakey, metavalue from news_metadata where parent_id=$id");
        return $db->value("select metavalue from news_metadata where parent_id=$id and metakey='$key'");
    }

    static function add_meta(&$r)
    {
        $r = array_merge($r, self::metadata($r['id']));
    }

    static function rubric_meta($id = 0)
    {
        global $Core;
        if ($id == 0) $id = $Core->link->news_id;
        return $Core->db->hash("select concat('meta_',metakey), metavalue from rubric_metadata where parent_id=$id");
    }


    static function gall($jar)
    {
        global $db, $Core;
        $set = parse_jar($jar);
        (isset($set['id'])) ? $id = $set['id'] : $id = intval($Core->news_id);
       // $id = intval($Core->news_id);
    
        $res = $db->select("select img ,descr from news_photo where is_hidden=0 and parent_id=$id order by sort");
        if (count($res) == 0) return $ret;
        foreach ($res as $r) {
            $r['descr'] = htmlspecialchars($r['descr']);
            $Core->htm->addrow($set['row'], $r);
        }
        if (!isset($set['tpl'])) return;
        $ret = file_get_contents(TEMPLATES . $set['tpl']);
        $Core->htm->_row($ret, true);
        return $ret;
    }

    static function rubric_menu($jar)
    {
        global $db, $htm;
        $set = parse_jar($jar);
        $pid = intval($set['parent']);

        if (isset($set['mod'])) {
            $conf = self::get_modset($set['mod']);
            $pid = $conf['parent_id'];
            $link = $conf['prefix'];

        }
        $res = $db->select("select id,title,prefix,link from news_rubric where parent_id=$pid and is_hidden=0 order by sort");
        foreach ($res as $r) {
            if(isset($set['meta'])){
                $meta=get_meta("rubric_metadata",$r['id'],'','meta_');
                $r=array_merge($r,$meta);
            }
            $htm->addrow($set['row'], $r);
            

        }
        if (isset($set['tpl'])) {
            $tpl = $htm->load_tpl($set['tpl']);
            $htm->_row($tpl, true);
            return $tpl;
        }
        return "";

    }


    static function popular_posts($jar)
    {
        global $db, $htm;
        $set = parse_jar($jar);

        if (isset($set['tpl']))
            $ret = $htm->load_tpl($set['tpl']);


        $w = array('is_hidden=0');
        $rid = intval($set['rubric']);
        $pid = intval($set['pid']);
        $clear = intval($set['clear']);

        if (isset($set['mod'])) {
            $conf = self::get_modset($set['mod']);
            $pid = $conf['parent_id'];
            $link = $conf['prefix'];

        }

        $sort = 'views desc';
        $tag = intval($set['tag']);
        $row = (isset($set['row']) ? $set['row'] : "POPULAR_LIST");
        $limit = intval($set['limit']);
        $limit = ($limit == 0 ? 10 : $limit);

        $w[] = "parent_id=$pid";

        $res = $db->select("select id, title,terms,date_pub,short from news  where " . implode(" and ", $w) . " order by $sort limit 0," . $limit);
        $i = 1;
        $links = self::_links($pid);

        foreach ($res as $r) {

            self::date($r);

            if (isset($set['meta']))
                $r = array_merge($r, self::metadata($r['id']));

            if ($r['meta-nolink'] != '1')
                $r['link'] = $link . "-" . $r['id'] . ".html";

            //рубрика не передана
            if ($rid == 0) {
                preg_match_all("/cat_([0-9]+)/", $r['terms'], $m);
                $r['link'] = $links[$m[1][0]] . "-" . $r['id'] . ".html";
            }


            if ($i == $clear) {
                $r['clear'] = 1;
                $i = 0;
            }
            $i++;
            $htm->addrow($row, $r);
        }

        if (isset($set['tpl'])) {
            $htm->assvar($set);
            $htm->_var($ret);
            $htm->_row($ret, true);
            return $ret;
        }


    }//


    /**
     * Получение ссылок на предыдущие новости в замкнутом цикле
     * */
    static function last_links($jar)
    {
        global $db, $Core;
        $len = 3;
        $ret = "";
        $set = parse_jar($jar);
        $news = $Core->link->news;
        $id = $news['id'];
        $date = $news['date_pub'];
        $filter = "parent_id=" . $news['parent_id'];

        $last = $db->select("select id,date_pub,title, short, guid from news where is_hidden=0 and id!=$id and $filter order by date_pub asc limit 0,$len");
        /*
        if(count($last)<$len){
          $add=$db->select("select id,date_pub,short, title from news where is_hidden=0 and id!=$id and $filter order by date_pub asc limit 0,$len");
          $c=$len-count($last);
          for($i=0; $i<$c; $i++)
            $last[]=$add[$i];
        }
        */

        foreach ($last as $r) {
            self::add_meta($r);
            $Core->htm->addrow($set['row'], $r);
        }

        if (isset($set['tpl'])) {
            $new = $Core->htm->load_tpl($set['tpl']);
            $Core->htm->_row($new, true);
            return $new;
        }


        return $ret;
    }


    static function other_posts($jar)
    {
        global $Core;
        return self::get_list($jar . ",where:id!=" . $Core->news_id . ",rubric:" . $Core->link->rubric_id);
    }

    static function get_rubr($terms)
    {
        preg_match_all("/cat_([0-9]+)/", $terms, $m);
        return $m[1];
    }

    static function _links($pid)
    {
        global $db;
        if (!isset(self::$links)) self::$links = $db->hash("select id, prefix from news_rubric where parent_id=$pid");
        return self::$links;
    }

    static function get_modset($mod)
    {
        if (isset(self::$modset[$mod])) return self::$modset[$mod];
        self::$modset[$mod] = Com_mod::get_config($mod);
        return self::$modset[$mod];
    }


}