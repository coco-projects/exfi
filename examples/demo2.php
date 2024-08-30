<?php

    use Coco\exfi\Adapter\Reader;

    require '../vendor/autoload.php';

    $img  = './imgs/2.jpg';

    $data = Reader::readExfi($img);

    print_r($data);

