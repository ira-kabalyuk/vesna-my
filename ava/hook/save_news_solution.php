<?php
global $db;
$self=$args[1];
$id=$self->id;
$face=$db->value("select metavalue from news_metadata where metakey='logo' and parent_id=$id");
$db->execute("update news set img='$face' where id=$id");