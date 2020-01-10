<?php

namespace MeEmpresta;

class Correios
{
    private $valor = null;
    private $urlCep = "http://www.buscacep.correios.com.br/sistemas/buscacep/resultadoBuscaCepEndereco.cfm";
    private $urlRastreio = "https://www2.correios.com.br/sistemas/rastreamento/resultado.cfm";

    public function setVAlor($valor)
    {
        $this->valor = $valor;
    }

    public function searchCep()
    {

        try {

            $dados = http_build_query(array(
                'relaxation' => $this->valor,
                'tipoCEP' => 'LOG'
            ));
            $contexto = stream_context_create(array(
                'http' => array(
                    'method' => 'POST',
                    'content' => $dados,
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n"
                        . "Content-Length: " . strlen($dados) . "\r\n",
                )
            ));

            $result = file_get_contents($this->urlCep, null, $contexto);
            $doc = new \DOMDocument;
            $doc->loadHTML($result);
            $table = $doc->getElementsByTagName("table");
            if (count($table) != 1) {
                throw new \Exception("Não encontrado!");
            }
            $data = [];
            foreach ($table->item(0)->childNodes as $node) {
                if (!empty($node->getElementsByTagName("td")->item(0))) {

                    $data["data"][] = array(
                        "logradouro" => $node->getElementsByTagName("td")->item(0)->nodeValue,
                        "bairro" => $node->getElementsByTagName("td")->item(1)->nodeValue,
                        "localidade" => $node->getElementsByTagName("td")->item(2)->nodeValue,
                        "cep" => $node->getElementsByTagName("td")->item(3)->nodeValue
                    );
                }
            }

            $data["message"] = "Encontrado com com sucesso!";
            $data["success"] = true;

        } catch (\Exception $ex) {
            $data["message"] = $ex->getMessage();
        }

        $this->resultado = $data;
        return $this;

    }

    public function rastreio()
    {

        try {

            $dados = http_build_query(array(
                'objetos' => $this->valor,
            ));
            $contexto = stream_context_create(array(
                'http' => array(
                    'method' => 'POST',
                    'content' => $dados,
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n"
                        . "Content-Length: " . strlen($dados) . "\r\n",
                )
            ));

            $result = file_get_contents($this->urlRastreio, null, $contexto);
            $doc = new \DOMDocument;
            $doc->loadHTML($result);
            $table = $doc->getElementsByTagName("table");
            if (count($table) != 1) {
                throw new \Exception("Não encontrado!");
            }
            $data = [];
            foreach ($table->item(0)->childNodes as $node) {
                $registro = explode(" ", $node->getElementsByTagName("td")->item(0)->nodeValue);
                $data["data"][] = array(
                    "data" => $registro[0],
                    "hora" => $registro[1],
                    "localidade" => $registro[10],
                    "status" => $node->getElementsByTagName("td")->item(1)->nodeValue
                );
            }

            $data["message"] = "Encontrado com com sucesso!";
            $data["success"] = true;

        } catch (\Exception $ex) {
            $data["message"] = $ex->getMessage();
        }

        $this->resultado = $data;
        return $this;

    }

    public function resultObject()
    {
        return json_decode(json_encode($this->resultado));
    }

    public function resultArray()
    {
        return $this->resultado;
    }

    public function resultJson()
    {
        return json_encode($this->resultado);
    }
}
