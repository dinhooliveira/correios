<html>
<form method="post">
    CEP <input type="radio" name="op" value="cep" <?=!empty($_POST["op"]) && $_POST["op"] == "cep"? "checked": "" ?>>
    RASTREIO <input type="radio" name="op" value="rastreio"  <?=!empty($_POST["op"]) && $_POST["op"] == "rastreio"? "checked": "" ?>>
    <input name="valor" value="<?= !empty($_POST["valor"]) ? $_POST["valor"] : ""; ?>"/>
    <button>Pesquisa</button>
</form>
</html>

<?php
require "./vendor/autoload.php";
if (!empty($_POST["valor"]) && !empty($_POST["op"])) {
    $correios = new MeEmpresta\Correios();
    $correios->setValor($_POST["valor"]);
    switch ($_POST["op"]) {
        case "cep":
            $resultado = $correios->searchCep();
            $data = $resultado->resultObject();
            echo $data->message;
            if($data->success){
                foreach ($data->data as $reg){
                    echo "<br>".$reg->logradouro."<br>";
                    echo $reg->bairro."<br>";
                    echo $reg->localidade."<br>";
                    echo $reg->cep."<br>";
                }
            }
            break;
        case "rastreio":
            $resultado = $correios->rastreio();
            $data = $resultado->resultObject();
            if($data->success){
                foreach ($data->data as $reg){
                    echo "<br>".$reg->data."<br>";
                    echo $reg->hora."<br>";
                    echo $reg->localidade."<br>";
                    echo $reg->status."<br>";
                }
            }
            break;
        default:
            $resultado = "opção invalida";
            break;

    }

}




