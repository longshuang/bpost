<?php
require_once dirname(__FILE__) . '/../Bpost/BPost.php';
require_once dirname(__FILE__) . '/../Bpost/HttpClient.php';
require_once dirname(__FILE__) . '/../Bpost/XmlBPost.php';

$bpost = new \nlebpost\Bpost\BPost();
$data = [
    'reference' => '904555',
    'shipTo' => [
        'name' => '李三',
        'attention' => '李三',
        'address1' => 'aaaa',
        'address2' => '',
        'address3' => '',
        'city' => '蚌埠市',
        'state' => '安徽省',
        'postalCode' => '233002',
        'country' => 'CN',
        'phone' => '008613025935182',
        'email' => 'orders@test.com',
        'residential' => 'true',
    ],
    'additionalFields' => [],
    'packages' => [
        [
            'weightUnit' => 'LB',
            'weight' => '4.5',
            'dimensionsUnit' => 'IN',
            'height' => '12',
            'packageReference' => '98233312',
        ]
    ],
    'items' => [
        [
            'sku' => '7224059',
            'quantity' => '2',
            'unitPrice' => '93.99',
            'description' => "Women's Shoes",
            'hSCode' => '640399.30.00',
            'countryOfOrigin' => 'CN',
        ]
    ],
];
$result = $bpost->shipAPIRequest($data);