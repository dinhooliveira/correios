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
            $result = file_get_contents($this->urlCep."?cep={$this->val}");
            $result = json_decode($result);
      
            if(empty($result)){
                throw new \Exception("Erro ao consultar");
            }

            if($result->erro == true){
                throw new \Exception();
            }
            $data["data"] = array(
                "logradouro" => $result->dados[0]->logradouroDNEC,
                "bairro" => $result->dados[0]->bairro,
                "localidade" =>  $result->dados[0]->localidade,
                "uf"=> $result->dados[0]->uf,
                "cep" => $result->dados[0]->cep,
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