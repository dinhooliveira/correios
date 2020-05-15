<?php namespace MeEmpresta;

class BairroLogradouro extends Correios
{

    public function run()
    {
        try {

            $data["data"] = $this->getEnderecoPorPagina();
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
     * @param array $data
     * @param null $qtdrow
     * @param null $pagini
     * @param null $pagfim
     * @throws \Exception
     */
    private function getEnderecoPorPagina($data = array(), $qtdrow = null, $pagini = null, $pagfim = null)
    {

        $params = http_build_query(array(
            'relaxation' => $this->val,
            'tipoCEP' => 'ALL',
            'qtdrow' => $qtdrow,
            'pagini' => $pagini,
            'pagfim' => $pagfim
        ));

        $contexto = stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'content' => $params,
                'header' => "Content-type: application/x-www-form-urlencoded\r\n"
                    . "Content-Length: " . strlen($params) . "\r\n",
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
        $trs = $table->item(0)->getElementsByTagName("tr");
        $totalTds = count($trs);
        if ($totalTds < 1) {
            throw new \Exception("Não encontrado!");
        }

        for ($i = 1; $i < $totalTds; $i++) {
            $td = $trs->item($i)->getElementsByTagName("td");
            $logradouroUF = explode("/", $td->item(2)->nodeValue);
            $data[] = array(
                "logradouro" => $td->item(0)->nodeValue,
                "bairro" => $td->item(1)->nodeValue,
                "localidade" => $logradouroUF[0],
                'uf' => $logradouroUF[1],
                "cep" => $td->item(3)->nodeValue
            );

        }

        $formProxima = $xpath->query('//form[@name="Proxima"]');

        if (count($formProxima) > 0) {
            $data = $this->getEnderecoPorPagina(
                $data,
                $formProxima->item(0)->childNodes->item(5)->getAttribute('value'),
                $formProxima->item(0)->childNodes->item(6)->getAttribute('value'),
                $formProxima->item(0)->childNodes->item(7)->getAttribute('value')
            );

        }
        return $data;


    }

}