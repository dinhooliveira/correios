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
              [cep] => 21531-710
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


