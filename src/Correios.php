<?php

namespace MeEmpresta;

abstract class Correios implements ICorreios
{
    protected $val = null;
    protected $url = "http://www.buscacep.correios.com.br/sistemas/buscacep/resultadoBuscaCepEndereco.cfm";
    protected $resp = array();

    abstract public function run();

    public function setField($val)
    {
        $this->val = $val;
    }

    public function toObject()
    {
        return json_decode(json_encode($this->resp));
    }

    public function toArray()
    {
        return $this->resp;
    }

    public function toJson()
    {
        return json_encode($this->resp);
    }
}
