<?php

namespace nlebpost\Bpost;
/**
 * Class XmlBPost
 * @package nlebpost\Bpost
 * @property \DOMDocument $xml
 */
class XmlBPost
{
    public static $instance;


    public $xml;

    /**
     * 获取XmlBPost实例
     * @return $this
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        if (empty(self::$instance->xml)) {
            self::$instance->xml = new \DOMDocument('1.0', 'utf-8');
        }
        return self::$instance;
    }


    public static function createShipXml($data)
    {
        $xml = self::getInstance()->xml;

        //添加根节点
        $shipRequest = $xml->createElement('ShipRequest');

        //添加登陆信息节点
        $login = $xml->createElement('Login');
        $username = $xml->createElement('Username');
        $usernameV = $xml->createTextNode($data['login']['username']);
        $username->appendChild($usernameV);
        $password = $xml->createElement('Password');
        $passwordV = $xml->createTextNode($data['login']['password']);
        $password->appendChild($passwordV);
        $login->appendChild($username);
        $login->appendChild($password);

        //测试 节点
        $test = $xml->createElement('Test');
        $testV = $xml->createTextNode($data['test']);
        $test->appendChild($testV);

        //创建客户端ID 节点
        $clientId = $xml->createElement('ClientID');
        $clientIdV = $xml->createTextNode($data['client_id']);
        $clientId->appendChild($clientIdV);

        //创建备注 节点
        $reference = $xml->createElement('Reference');
        $referenceV = $xml->createTextNode($data['reference']);
        $reference->appendChild($referenceV);

        //创建收件人节点
        $shipTo = $xml->createElement('ShipTo');
        $name = $xml->createElement('Name');
        $nameV = $xml->createTextNode($data['shipTo']['name']);
        $name->appendChild($nameV);
        $shipTo->appendChild($name);
        $attention = $xml->createElement('Attention');
        $attentionV = $xml->createTextNode($data['shipTo']['attention']);
        $attention->appendChild($attentionV);
        $shipTo->appendChild($attention);
        $address1 = $xml->createElement('Address1');
        $address1V = $xml->createTextNode($data['shipTo']['address1']);
        $address1->appendChild($address1V);
        $shipTo->appendChild($address1);
        $address2 = $xml->createElement('Address2');
        $address2V = $xml->createTextNode($data['shipTo']['address2']);
        $address2->appendChild($address2V);
        $shipTo->appendChild($address2);
        $address3 = $xml->createElement('Address3');
        $address3V = $xml->createTextNode($data['shipTo']['address3']);
        $address3->appendChild($address3V);
        $shipTo->appendChild($address3);
        $city = $xml->createElement('City');
        $cityV = $xml->createTextNode($data['shipTo']['city']);
        $city->appendChild($cityV);
        $shipTo->appendChild($city);
        $state = $xml->createElement('State');
        $stateV = $xml->createTextNode($data['shipTo']['state']);
        $state->appendChild($stateV);
        $shipTo->appendChild($state);
        $postalCode = $xml->createElement('PostalCode');
        $postalCodeV = $xml->createTextNode($data['shipTo']['postalCode']);
        $postalCode->appendChild($postalCodeV);
        $shipTo->appendChild($postalCode);
        $country = $xml->createElement('Country');
        $countryV = $xml->createTextNode($data['shipTo']['country']);
        $country->appendChild($countryV);
        $shipTo->appendChild($country);
        $phone = $xml->createElement('Phone');
        $phoneV = $xml->createTextNode($data['shipTo']['phone']);
        $phone->appendChild($phoneV);
        $shipTo->appendChild($phone);
        $email = $xml->createElement('Email');
        $emailV = $xml->createTextNode($data['shipTo']['email']);
        $email->appendChild($emailV);
        $shipTo->appendChild($email);
        $residential = $xml->createElement('Residential');
        $residentialV = $xml->createTextNode('true');
        $residential->appendChild($residentialV);
        $shipTo->appendChild($residential);

        //创建 ShippingLane 节点
        $shippingLane = $xml->createElement('ShippingLane');
        $region = $xml->createElement('Region');
        $regionV = $xml->createTextNode('Client EMC');
        $region->appendChild($regionV);
        $shippingLane->appendChild($region);
        $originFacilityCode = $xml->createElement('OriginFacilityCode');
        $originFacilityCodeV = $xml->createTextNode('');
        $originFacilityCode->appendChild($originFacilityCodeV);
        $shippingLane->appendChild($originFacilityCode);

        //shipMethod 节点
        $shipMethod = $xml->createElement('ShipMethod');
        $shipMethodV = $xml->createTextNode($data['shipTo']['shipMethod']);
        $shipMethod->appendChild($shipMethodV);

        //ShipmentInsuranceFreight 节点
        $shipmentInsuranceFreight = $xml->createElement('ShipmentInsuranceFreight');
        $shipmentInsuranceFreightV = $xml->createTextNode('');
        $shipmentInsuranceFreight->appendChild($shipmentInsuranceFreightV);

        //ItemsCurrency 节点
        $itemsCurrency = $xml->createElement('ItemsCurrency');
        $itemsCurrencyV = $xml->createTextNode('EUR');
        $itemsCurrency->appendChild($itemsCurrencyV);

        //LabelFormat 节点
        $labelFormat = $xml->createElement('LabelFormat');
        $labelFormatV = $xml->createTextNode('PDF');
        $labelFormat->appendChild($labelFormatV);

        //LabelFormat 节点
        $labelEncoding = $xml->createElement('LabelEncoding');
        $labelEncodingV = $xml->createTextNode('LINKS');
        $labelEncoding->appendChild($labelEncodingV);

        $shipRequest->appendChild($login);
        $shipRequest->appendChild($test);
        $shipRequest->appendChild($clientId);
        $shipRequest->appendChild($reference);
        $shipRequest->appendChild($shipTo);
        $shipRequest->appendChild($shippingLane);
        $shipRequest->appendChild($shipMethod);
        $shipRequest->appendChild($shipmentInsuranceFreight);
        $shipRequest->appendChild($itemsCurrency);
        $shipRequest->appendChild($labelFormat);
        $shipRequest->appendChild($labelEncoding);

        //包裹节点
        $packageCount = count($data['packages']);
        $packages = $xml->createElement('Packages');
        for ($i = 1; $i <= $packageCount; $i++) {

            $package = $xml->createElement('Package');
            //重量单位
            $weightUnit = $xml->createElement('WeightUnit');
            $weightUnitV = $xml->createTextNode($data['packages'][$i - 1]['weightUnit']);
            $weightUnit->appendChild($weightUnitV);
            $package->appendChild($weightUnit);
            //重量
            $weight = $xml->createElement('Weight');
            $weightV = $xml->createTextNode($data['packages'][$i - 1]['weight']);
            $weight->appendChild($weightV);
            $package->appendChild($weight);
            //DimensionsUnit
            $dimensionsUnit = $xml->createElement('DimensionsUnit');
            $dimensionsUnitV = $xml->createTextNode($data['packages'][$i - 1]['dimensionsUnit']);
            $dimensionsUnit->appendChild($dimensionsUnitV);
            $package->appendChild($dimensionsUnit);
            //高度
            $height = $xml->createElement('Height');
            $heightV = $xml->createTextNode($data['packages'][$i - 1]['height']);
            $height->appendChild($heightV);
            $package->appendChild($height);
            //备注
            $packageReference = $xml->createElement('PackageReference');
            $packageReferenceV = $xml->createTextNode($data['packages'][$i - 1]['packageReference']);
            $packageReference->appendChild($packageReferenceV);
            $package->appendChild($packageReference);

            $packages->appendChild($package);
        }


        //创建Items节点
        $itemCount = count($data['items']);
        $items = $xml->createElement('Items');
        for ($i = 1; $i <= $itemCount; $i++) {

            $item = $xml->createElement('Item');
            //sku
            $sku = $xml->createElement('Sku');
            $skuV = $xml->createTextNode($data['items'][$i - 1]['sku']);
            $sku->appendChild($skuV);
            $item->appendChild($sku);
            //quantity 数量
            $quantity = $xml->createElement('Quantity');
            $quantityV = $xml->createTextNode($data['items'][$i - 1]['quantity']);
            $quantity->appendChild($quantityV);
            $item->appendChild($quantity);
            //UnitPrice
            $unitPrice = $xml->createElement('UnitPrice');
            $unitPriceV = $xml->createTextNode($data['items'][$i - 1]['unitPrice']);
            $unitPrice->appendChild($unitPriceV);
            $item->appendChild($unitPrice);
            //Description
            $description = $xml->createElement('Description');
            $descriptionV = $xml->createTextNode($data['items'][$i - 1]['description']);
            $description->appendChild($descriptionV);
            $item->appendChild($description);
            //HSCode
            $hSCode = $xml->createElement('HSCode');
            $hSCodeV = $xml->createTextNode($data['items'][$i - 1]['hSCode']);
            $hSCode->appendChild($hSCodeV);
            $item->appendChild($hSCode);
            //CountryOfOrigin
            $countryOfOrigin = $xml->createElement('CountryOfOrigin');
            $countryOfOriginV = $xml->createTextNode('CN');
            $countryOfOrigin->appendChild($countryOfOriginV);
            $item->appendChild($countryOfOrigin);

            $items->appendChild($item);
        }

        //FreightDetails 节点
        $freightDetails = $xml->createElement('FreightDetails');
        //ProNumber
        $proNumber = $xml->createElement('ProNumber');
        $proNumberV = $xml->createTextNode('LGBR020409E');
        $proNumber->appendChild($proNumberV);
        $freightDetails->appendChild($proNumber);
        //$pieceUnit
        $pieceUnit = $xml->createElement('PieceUnit');
        $pieceUnitV = $xml->createTextNode('Pallet');
        $pieceUnit->appendChild($pieceUnitV);
        $freightDetails->appendChild($pieceUnit);


        $shipRequest->appendChild($packages);
        $shipRequest->appendChild($items);
        $shipRequest->appendChild($freightDetails);
        $xml->appendChild($shipRequest);

        return $xml->saveXML();
    }


    /**
     * 保存XML
     * @return bool
     */
    public static function saveXML($data)
    {
        $result = false;
        try {
            //打开要写入 XML数据的文件
            $fp = fopen("tmp.xml", "w");
            //写入 XML数据
            fwrite($fp, self::createShipXml($data));
            //关闭文件
            fclose($fp);
            $result = true;
        } catch (\Exception $e) {
            print $e->getMessage();
            exit();
        }
        return $result;
    }

    public static function xmlConvert($data)
    {
        return json_decode(json_encode(simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA), JSON_UNESCAPED_UNICODE), true);
    }


}