<?php
namespace MeEmpresta;

interface ICorreiosEndereco extends ICorreios{
    public function withGeo($address);
}