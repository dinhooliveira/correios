<?php
namespace MeEmpresta;
interface ICorreios{
    public function setField($val);
    public function run();
    public function toArray();
    public function toObject();
    public function toJson();
}
