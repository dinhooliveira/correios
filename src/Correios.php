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
    protected $url = "http://www.buscacep.correios.com.br/sistemas/buscacep/resultadoBuscaCepEndereco.cfm";
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
}
