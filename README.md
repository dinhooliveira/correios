# correios
Busca em site do correios dados de rastreio e de endereço

## Exemplo
```PHP

<?php

  #BUSCANDO UM CEP#
  $correiosCep = new MeEmpresta\Correios();
  $correiosCep->setVAlor('11111111');
  $dadosOBJ = $correiosCep->searchCep()->resultObject();
  $dadosArray = $correiosCep->searchCep()->resultArray();
  $dadosJSON = $correiosCep->searchCep()->resultJson();
  
  //retorna seguinte estrutura
  [
    data => array indices["logradouro","bairro", "localidade","cep"]
    success => boolean
    message => string
  ]
  
  #RASTREANDO UMA ENCOMENDA#
  $correiosRastreio = new MeEmpresta\Correios();
  $correiosRastreio->setVAlor('seu-codigo-rastreio');
  $dadosOBJ = $correiosRastreio->rastreio()->resultObject();
  $dadosArray = $correiosRastreio->rastreio()->resultArray();
  $dadosJSON = $correiosRastreio->rastreio()->resultJson();
  //retorna seguinte estrutura
  [
    data => array indices["data","hora","localidade","status"]
    success => boolean
    message => string
  ]
