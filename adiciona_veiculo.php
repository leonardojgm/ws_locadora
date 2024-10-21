<?php  
    header("Access-Control-Allow-Origin: *"); // Permitir acesso de qualquer origem
    header("Access-Control-Allow-Methods: POST, OPTIONS"); // Permitir métodos POST e OPTIONS
    header("Access-Control-Allow-Headers: Content-Type"); // Permitir cabeçalhos personalizados

    // Se a solicitação for OPTIONS, apenas retorne os cabeçalhos CORS
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        header('HTTP/1.1 204 No Content');
        exit();
    }

    require_once("configuracao.php");
    require_once("banco_dados.php");

    $Dados = file_get_contents("php://input");
    $DadosJSON = json_decode($Dados, true);

    if (count($DadosJSON) > 0) {
        $Marca = $DadosJSON["marca"];
        $Modelo = $DadosJSON["modelo"];
        $Placa = $DadosJSON["placa"];
        $Tipo = $DadosJSON["tipo"];
        $Cor = $DadosJSON["cor"];
        $Ano = $DadosJSON["ano_modelo"];
        $Combustivel = $DadosJSON["combustivel"];
        $Diaria = $DadosJSON["diaria"];
    }
    else {
        $Retorno = "Sem registro...";
    }

    if ($Retorno != "Sem registro...") {
        $ConexaoBaseDados = new BancoDados($Servidor, $Usuario, $Senha, $BaseDados);

        if ($ConexaoBaseDados->AbrirConexao() == NULL) {
            $Retorno .= '{"Erro":' . '"Erro na conexão com a base de dados!<br> Nro. do Erro: [' . $ConexaoBaseDados->CodigoErro() . ']"}';
        }
        else {
            $Resposta = $ConexaoBaseDados->AdicionarVeiculo($Marca, $Modelo, $Placa, $Tipo, $Cor, $Ano, $Combustivel, $Diaria);

            if ($Resposta == FALSE) {
                $Retorno = "Não foi possível inserir o veículo no cadastro!";
            }
            else {
                $Retorno = "Veículo inserido com sucesso no cadastro!";
            }
        }

        $ConexaoBaseDados->FecharConexao();
    }    

    echo '{"Erro":"' . $Retorno . '"}';
?>