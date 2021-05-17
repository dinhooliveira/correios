<?php

namespace MeEmpresta;

/**
 * Class Correios
 * @package MeEmpresta
 */
abstract class Correios implements ICorreios
{
    /**
     * @var null
     */
    protected $val = null;
    /**
     * @var string
     */
    protected $urlCep = "https://buscacepinter.correios.com.br/app/cep/carrega-cep.php";

    protected $urlEndereco = "https://buscacepinter.correios.com.br/app/endereco/carrega-cep-endereco.php";
    /**
     * @var array
     */
    protected $resp = array();

    /**
     * @return mixed
     */
    abstract public function run();

    /**
     * @param $val
     */
    public function setField($val)
    {
        $this->val = $val;
    }

    /**
     * @return mixed
     */
    public function toObject()
    {
        return json_decode(json_encode($this->resp));
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->resp;
    }

    /**
     * @return false|string
     */
    public function toJson()
    {
        return json_encode($this->resp);
    }

    public  function mask($val, $mask)
    {
        $maskared = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; ++$i) {
            if ($mask[$i] == '#') {
                if (isset($val[$k])) {
                    $maskared .= $val[$k++];
                }
            } else {
                if (isset($mask[$i])) {
                    $maskared .= $mask[$i];
                }
            }
        }

        return $maskared;
    }
}
