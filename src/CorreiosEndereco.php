<?php


namespace MeEmpresta;


/**
 * Class CorreiosEndereco
 * @package MeEmpresta
 */
abstract class  CorreiosEndereco extends Correios
{
    /**
     * @var string
     */
    private $urlGoogleMapas = "https://www.google.com/maps/search/";
    /**
     * @return mixed
     */
    abstract function withGeo();

    /**
     * @param $address
     * @return bool
     */
    protected function findGeoLocationSiteGoogleMaps($address)
    {
        $searchCep = $address["cep"];
        if(strpos($searchCep,"-") == false){
            $searchCep = $this->mask($searchCep,"#####-###");
        }

        $result = file_get_contents("{$this->urlGoogleMapas}{$searchCep}", null);

        if (empty($result)) {
            return false;
        }
        $doc = new \DOMDocument;
        libxml_use_internal_errors(true);
        $doc->loadHTML($result);
        $xpath = new \DOMXpath($doc);
        $meta = $xpath->query('//meta[@itemprop="image"]');
        if (empty($meta)) {
            return false;
        }
        $atribute = $meta->item(0)->getAttribute('content');
        if (empty($atribute)) {
            return false;
        }
        $parts = explode("center=", urldecode($atribute));
        $parts2 = explode("&", $parts[1]);
        $partGeos = explode(",", $parts2[0]);
        $address["lat"]= substr($partGeos[0], 0, 11);
        $address["lon"] = substr($partGeos[1], 0, 11);
        return $address;

    }
}