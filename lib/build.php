<?php 

header('Content-type: application/json');

$data = file_get_contents('original.json');
$data = json_decode($data);

foreach($data as $k => $entry){
    foreach($entry as $ke => $value){
        if (is_numeric($ke)){
            unset($data[$k]->$ke);
        } else if (!strlen($value)){
            $data[$k]->$ke = null;
        }
    }
    $data[$k]->cover = 'http://tpolm.org/~ps/enough/covers/'.$entry->cat.'.jpg';
    $data[$k]->tags = explode(",",$entry->tags);
}


$output = json_encode($data);
echo $output;

//$res = file_put_contents('clean.js',$output);





