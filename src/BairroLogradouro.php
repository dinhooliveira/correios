<?php namespace MeEmpresta;

/**
 * Class BairroLogradouro
 * @package MeEmpresta
 */
class BairroLogradouro extends CorreiosEndereco
{

    /**
     * @return $this
     */
    public function run()
    {
        try {

            $data["data"] = $this->getEnderecoPorPagina(array(),0,50);
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
    private function getEnderecoPorPagina($data = array(), $pagini = null, $pagfim = null)
    {

        $query = http_build_query(array(
            'endereco' => $this->val,
            'tipoCEP' => 'ALL',
            'inicio' => $pagini,
            'final' => $pagfim
        ));

        $result = file_get_contents($this->urlEndereco."?".$query);
        $result = json_decode($result);
      
        if(empty($result)){
            throw new \Exception("Erro ao consultar");
        }

        foreach($result->dados as $dado){
            $data[] = array(
                "logradouro" => $dado->logradouroDNEC,
                "bairro" =>  $dado->bairro,
                "localidade" =>  $dado->localidade,
                "uf"=> $dado->uf,
                "cep" => $dado->cep,
                "lat"=>null,
                "lon"=>null
            );
        }

        if($pagfim < $result->total){
            $pagini = $pagfim+1;
            $pagfim = $result->total;

            $data = $this->getEnderecoPorPagina(
                $data,
                $pagini,
                $pagfim
            );
        }
        
        return $data;


    }

    /**
     * @return mixed|void
     */
    public  function withGeo()
    {
        foreach ($this->resp["data"] as $key => $address) {
            $newAddress = $this->findGeoLocationSiteGoogleMaps($address);
            if ($newAddress) {
                $this->resp["data"][$key] = $newAddress;
            }
        }

        return $this;
    }


}