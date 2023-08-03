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
        
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->urlEndereco,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $query,
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        die();
        }

        $result = json_decode($response);
        
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