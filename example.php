<html>
<form method="post">
    CEP <input type="radio" name="op" value="cep" <?= !empty($_POST["op"]) && $_POST["op"] == "cep" ? "checked" : "" ?>>
    RASTREIO <input type="radio" name="op"
                    value="rastreio" <?= !empty($_POST["op"]) && $_POST["op"] == "rastreio" ? "checked" : "" ?>>
    BAIRRO/LOGRADOURO <input type="radio" name="op"
                             value="bairro-logradouro" <?= !empty($_POST["op"]) && $_POST["op"] == "bairro-logradouro" ? "checked" : "" ?>/>
    <input name="valor" value="<?= !empty($_POST["valor"]) ? $_POST["valor"] : ""; ?>"/>
    <button>Pesquisa</button>
</form>
</html>

<?php

require "./vendor/autoload.php";
if (!empty($_POST["valor"]) && !empty($_POST["op"])) {

    switch ($_POST["op"]) {
        case "cep":
            $correios = new \MeEmpresta\Cep();
            $correios->setField($_POST["valor"]);
            $data = $correios->run()->toObject();
            print("<pre>");
            print_r($data);
            break;
        case "rastreio":
            $correios = new MeEmpresta\Rastreio();
            $correios->setField($_POST["valor"]);
            $data = $correios->run()->toObject();
            print("<pre>");
            print_r($data);
            break;
        case "bairro-logradouro":
            $correios = new MeEmpresta\BairroLogradouro();
            $correios->setField($_POST["valor"]);
            $data = $correios->run()->toObject();
            print("<pre>");
            print_r($data);
            break;
        default:
            $resultado = "opção invalida";
            break;
    }

}




