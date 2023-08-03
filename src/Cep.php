<?php

namespace MeEmpresta;

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

            $query = http_build_query(array(
                'endereco' => $this->val,
                'tipoCEP' => 'ALL'
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

            if (empty($result)) {
                throw new \Exception("Erro ao consultar");
            }

            if ($result->erro == true) {
                throw new \Exception();
            }
            $data["data"] = array(
                "logradouro" => $result->dados[0]->logradouroDNEC,
                "bairro" => $result->dados[0]->bairro,
                "localidade" =>  $result->dados[0]->localidade,
                "uf" => $result->dados[0]->uf,
                "cep" => $result->dados[0]->cep,
                "lat" => null,
                "lon" => null
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
