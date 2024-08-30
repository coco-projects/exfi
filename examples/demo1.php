<?php

    require '../vendor/autoload.php';

    $img = './imgs/1.jpg';

    $tool = new \Coco\exfi\Adapter\Exiftool($img);
//    $tool = new \Coco\exfi\Adapter\Native($img);

//    print_r($tool->getRawData());exit;;
    print_r($tool->getUnitizedData());exit;;