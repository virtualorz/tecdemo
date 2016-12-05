<?php

$id = 0;
if(isset($_GET['id'])){
    $id = intval($_GET['id']);
}
header('Location: '. 'edm/id-' . $id);
