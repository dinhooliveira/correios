# correios
Busca em site do correios dados de rastreio e de endereço

#### install
 composer require adereldo/correios
## Exemplo
##### BUSCANDO  UM ENDEREÇ0 COM BASE NO CEP
```PHP
<?php

 
  $correios = new MeEmpresta\Cep();
  $correios->setField('11111111');
  $dadosOBJ = $correios->run()->toObject();
  
  //retorna seguinte estrutura
  stdClass Object
  (
      [data] => stdClass Object
          (
              [logradouro] => Rua Wilson 
              [bairro] => Coelho Neto 
              [localidade] => Rio de Janeiro
              [uf] => RJ 
              [cep] => 21531-710,
              [lat] => 
              [lon] =>
          )
  
      [message] => Encontrado com com sucesso!
      [success] => 1
  )
?>
```

 ##### RASTREANDO UMA ENCOMENDA
 ```PHP
  $correios = new MeEmpresta\Rastreio();
  $correios->setField('seu-codigo-rastreio');
  $dadosOBJ = $correios->run()->toObject();
  //retorna seguinte estrutura
stdClass Object
(
    [data] => Array
        (
            [0] => stdClass Object
                (
                    [data] => 09/01/2020
                    [hora] => 13:49
                    [localidade] => PORTO ALEGRE / RS
                    [status] => Objeto entregue ao remetente
                )
       )

    [message] => Encontrado com com sucesso!
    [success] => 1
)
?>
```

 ##### ENCONTRADO POR DESCRIÇÃO
 ```PHP
  $correios= new MeEmpresta\BairroLogradouro();
  $correios->setField('RUA A');
  $dadosOBJ = $correios->run()->toObject();
  //retorna seguinte estrutura
  stdClass Object
  (
      [data] => stdClass Object
          (
              [logradouro] => Rua Wilson 
              [bairro] => Coelho Neto 
              [localidade] => Rio de Janeiro
              [uf] => RJ 
              [cep] => 21531-710
          )
  
      [message] => Encontrado com com sucesso!
      [success] => 1
  )
?>
```

 ##### BUSCANDO ENDEREÇO COM LATITUDE E LONGITUDE GOOGLE MAPS
 ```PHP
  $correios= new MeEmpresta\Cep();
  $correios->setField('21531710');
  $dadosOBJ = $correios->run()->withGeo()->toObject();
  //retorna seguinte estrutura
  stdClass Object
  (
      [data] => stdClass Object
          (
              [logradouro] => Rua Wilson 
              [bairro] => Coelho Neto 
              [localidade] => Rio de Janeiro
              [uf] => RJ 
              [cep] => 21531-710,
              [lat] => -22.7684959
              [lon] =>-43.423122,14
          )
  
      [message] => Encontrado com com sucesso!
      [success] => 1
  )
?>
```
<p>caso faça o uso excessivo ou busca em uma quantidade muito grande de endereços usando a Classe BairroLogradouro  poderar  receber o seguinte erro </p>
<code>
failed to open stream: HTTP request failed! HTTP/1.0 429 Too Many Requests
</code>
<p>
mas isso não impede o retorno dos dados de endereço apenas da latitude e longitude</p>


