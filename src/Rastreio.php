<?php namespace MeEmpresta;

/**
 * Class Rastreio
 * @package MeEmpresta
 */
class Rastreio extends Correios
{
    /**
     * @var string
     */
    protected $url = "https://www2.correios.com.br/sistemas/rastreamento/resultado.cfm";

    /**
     * @return $this
     */
    public function run()
    {

        try {

            $dados = http_build_query(array(
                'objetos' => $this->val,
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
            if(!$result){
                throw new \Exception("Não encontrado!");
            }

            $doc = new \DOMDocument();
            libxml_use_internal_errors(true);
            $doc->loadHTML($result);
            $xpath = new \DOMXpath($doc);
            $tables = $xpath->query('//table[@class="listEvent sro"]');
            $tabelasEncontradas = count($tables);
            if ($tabelasEncontradas < 1) {
                throw new \Exception("Não encontrado!");
            }

            for ($indexTable = 0; $indexTable < $tabelasEncontradas; $indexTable++) {
                foreach ($tables->item($indexTable)->childNodes as $node) {
                    $registro = str_replace("  ", "", $node->getElementsByTagName("td")->item(0)->nodeValue);
                    $registro = explode(" ", $registro);
                    $partesRegistro = sizeof($registro);
                    $localidade = "";
                    for ($indexRegistro = 2; $indexRegistro < $partesRegistro; $indexRegistro++) {
                        $localidade .= $registro[$indexRegistro] . " ";
                    }

                    $data["data"][] = array(
                        "data" => $registro[0],
                        "hora" => $registro[1],
                        "localidade" => $localidade,
                        "status" => $node->getElementsByTagName("td")->item(1)->nodeValue
                    );
                }
            }

            $data["message"] = "Encontrado com com sucesso!";
            $data["success"] = true;

        } catch (\Exception $ex) {
            $data["message"] = $ex->getMessage();
            $data["success"] = false;
        }

        $this->resp = $data;
        return $this;

    }

}