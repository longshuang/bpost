<?php

namespace nlebpost\Bpost;

class BPost
{
    public $options = [

    ];

    public function __construct($options = [])
    {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * 包裹请求
     * @param $data
     * @return array
     * @throws \Exception
     */
    public function shipAPIRequest($data)
    {
        $result = HttpClient::sendXMLBPostShipAPIRequest($this->options, $data);
        $result = XmlBPost::xmlConvert($result);
        if ($result['Result']['Success'] === "false") {
            return [];
        }
        return $result['Result'];
    }
}