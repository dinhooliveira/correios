<?php namespace MeEmpresta;

/**
 * Class Cep
 * @package MeEmpresta
 */
class Cep extends CorreiosEndereco
{
    /**
     * @return $this
     */
    public function run()
    {

        try {
            $this->val = str_replace("-", "", $this->val);
            if (strlen($this->val) != 8 && is_numeric($this->val)) {
                throw new \Exception("Cep deve conter no minímo 8 caracteres numéricos Ex: 12345678");
            }

            $dados = http_build_query(array(
                'relaxation' => $this->val,
                'tipoCEP' => 'LOG',
                'semelhante' => 'N'
            ));
            $contexto = stream_context_create(array(
                'http' => array(
                    'method' => 'POST',
                    'content' => $dados,
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n"
                        . "Content-Length: " . strlen($dados) . "\r\n",
                )
            ));

            $result = file_get_contents($this->url, null, $contexto);
            $doc = new \DOMDocument;
            libxml_use_internal_errors(true);
            $doc->loadHTML($result);
            $xpath = new \DOMXpath($doc);
            $table = $xpath->query('//table[@class="tmptabela"]');
            if (count($table) < 1) {
                throw new \Exception("Não encontrado!");
            }
            $td = $table->item(0)->childNodes->item(1)->getElementsByTagName("td");
            if (count($td) < 1) {
                throw new \Exception("Não encontrado!");
            }
            $logradouroUF = explode("/",$td->item(2)->nodeValue);
            $data["data"] = array(
                "logradouro" => $td->item(0)->nodeValue,
                "bairro" => $td->item(1)->nodeValue,
                "localidade" => $logradouroUF[0],
                "uf"=>$logradouroUF[1],
                "cep" => $td->item(3)->nodeValue,
                "lat"=>null,
                "lon"=>null
            );

            $data["message"] = "Encontrado com com sucesso!";
            $data["success"] = true;

        } catch (\Exception $ex) {
            $data["success"] = false;
            $data["message"] = $ex->getMessage();
        }

        $this->resp = $data;
        return $this;

    }

    /**
     * @return $this|mixed
     */
    public function withGeo()
    {
        $newAddress = $this->findGeoLocationSiteGoogleMaps($this->resp["data"]);
        if ($newAddress) {
            $this->resp["data"] = $newAddress;
        }

        return $this;
    }
}