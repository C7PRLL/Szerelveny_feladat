<?php

header('Access-Control-Allow-Origin: *');

$products = [
    [
        'id'        => 1,
        'nev'       => 'Viessmann Vitodens 200-W',
        'kategoria' => 'Kondenzációs kazán',
        'leiras'    => '35 kW-os, moduláló gázégővel felszerelt prémium fali kondenzációs kazán. Energiaosztály: A+++.',
        'ar'        => 598000,
        'keszlet'   => 4,
        'icon'      => 'boiler',
    ],
    [
        'id'        => 2,
        'nev'       => 'Daikin Emura 3 – 3,5 kW',
        'kategoria' => 'Inverteres klíma',
        'leiras'    => 'Ultra-vékony dizájn, Wi-Fi vezérlés, A+++ energiaosztály. Fehér és ezüst színben elérhető.',
        'ar'        => 299900,
        'keszlet'   => 0,
        'icon'      => 'ac',
    ],
    [
        'id'        => 3,
        'nev'       => 'Vaillant ecoTEC plus 246',
        'kategoria' => 'Kondenzációs kazán',
        'leiras'    => '24 kW kompakt kazán, integrált időjárásfüggő szabályozással. Ideális 120 m² alapterületig.',
        'ar'        => 435000,
        'keszlet'   => 2,
        'icon'      => 'boiler',
    ],
];

if (isset($_GET['export']) && $_GET['export'] === 'xml') {
    $inStockProducts = array_filter($products, function ($product) {
        return $product['keszlet'] > 0;
    });

    $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><termekek/>');

    foreach ($inStockProducts as $product) {
        $termek = $xml->addChild('termek');
        $termek->addChild('nev', htmlspecialchars($product['nev'], ENT_XML1, 'UTF-8'));
        $termek->addChild('ar', $product['ar']);
        $termek->addChild('keszlet', $product['keszlet']);
    }

    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($xml->asXML());

    header('Content-Type: application/xml; charset=UTF-8');
    header('Content-Disposition: attachment; filename="termekek.xml"');

    echo $dom->saveXML();
    exit;
}

header('Content-Type: application/json; charset=UTF-8');

echo json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);