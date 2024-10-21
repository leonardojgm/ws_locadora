<?php
    header("Access-Control-Allow-Origin: *"); // Permitir acesso de qualquer origem
    
    require_once("configuracao.php");
    require_once("banco_dados.php");
    
    $Tabela = $_REQUEST["tabela"];
    $Retorno = "";
    $Ordenacao = "";
    $ConexaoBaseDados = new BancoDados($Servidor, $Usuario, $Senha, $BaseDados);

    if ($ConexaoBaseDados->AbrirConexao() == NULL) {
        $Retorno .= '{"Erro":' . '"Erro na conexão com a base de dados!<br> Nro. do Erro: [' . $ConexaoBaseDados->CodigoErro() . ']"}';
    }
    else
    {
        if ($Tabela == "veiculo") {
            $Campos = "Descricao_Marca, Descricao_Modelo, Descricao_Tipo, Descricao_Cor, Placa, Ano_Modelo, Descricao_Combustivel, Valor_Diaria";
            $Tabelas = "veiculo, marca, modelo, tipo_veiculo, cor_veiculo, combustivel";
            $Condicao = "(veiculo.Codigo_Marca = marca.Codigo_Marca) AND " .
            "(veiculo.Codigo_Modelo = modelo.Codigo_Modelo) AND " .
            "(veiculo.Codigo_Tipo = tipo_veiculo.Codigo_Tipo) AND " .
            "(veiculo.Codigo_Cor = cor_veiculo.Codigo_Cor) AND " .
            "(veiculo.Codigo_Combustivel = combustivel.Codigo_Combustivel)";
            $Registros = $ConexaoBaseDados->LerTabela($Campos, $Tabelas, $Condicao, "Descricao_Marca, Descricao_Modelo");
        }
        else {
            if ($Tabela == "marca")
            {
                $Ordenacao = "Descricao_Marca";
            }
            else if ($Tabela == "modelo")
            {
                $Ordenacao = "Descricao_Modelo";
            }
            else if ($Tabela == "tipo_veiculo")
            {
                $Ordenacao = "Descricao_Tipo";
            }
            else if ($Tabela == "cor_veiculo")
            {
                $Ordenacao = "Descricao_Cor";
            }
            else if ($Tabela == "combustivel")
            {
                $Ordenacao = "Descricao_Combustivel";
            }

            $Registros = $ConexaoBaseDados->LerTabela("*", $Tabela, "", $Ordenacao);
        }

        if ($Registros != NULL) {
            if ($Registros->num_rows > 0) {
                while ($DadosRegistro = $Registros->fetch_assoc()) {
                    if ($Retorno != "") {
                        $Retorno .= ",";
                    }

                    if ($Tabela == "marca") {
                        $Retorno .= '{"CodigoMarca":"' . $DadosRegistro["Codigo_Marca"] . '", "Marca":"' . $DadosRegistro["Descricao_Marca"] . '"}';
                    }
                    else if ($Tabela == "modelo") {
                        $Retorno .= '{"CodigoModelo":"' . $DadosRegistro["Codigo_Modelo"] . '", "Modelo":"' . $DadosRegistro["Descricao_Modelo"] . '"}';
                    }
                    else if ($Tabela == "tipo_veiculo") {
                        $Retorno .= '{"CodigoTipo":"' . $DadosRegistro["Codigo_Tipo"] . '", "Tipo":"' . $DadosRegistro["Descricao_Tipo"] . '"}';
                    }
                    else if ($Tabela == "cor_veiculo") {
                        $Retorno .= '{"CodigoCor":"' . $DadosRegistro["Codigo_Cor"] . '", "Cor":"' . $DadosRegistro["Descricao_Cor"] . '"}';
                    }
                    else if ($Tabela == "combustivel") {
                        $Retorno .= '{"CodigoCombustivel":"' . $DadosRegistro["Codigo_Combustivel"] . '", "Combustivel":"' . $DadosRegistro["Descricao_Combustivel"] . '"}';
                    }
                    else if ($Tabela == "veiculo") {
                        $Retorno .= '{"Marca":"' . $DadosRegistro["Descricao_Marca"] . 
                            '", "Modelo":"' . $DadosRegistro["Descricao_Modelo"] .
                            '", "Tipo":"' . $DadosRegistro["Descricao_Tipo"] .
                            '", "Cor":"' . $DadosRegistro["Descricao_Cor"] .
                            '", "Placa":"' . $DadosRegistro["Placa"] .
                            '", "AnoModelo":"' . $DadosRegistro["Ano_Modelo"] .
                            '", "Combustivel":"' . $DadosRegistro["Descricao_Combustivel"] .  
                            '", "Diaria":"' . $DadosRegistro["Valor_Diaria"] . '"}';
                    }
                }
            }
            else {
                $Retorno .= '{"Erro":' . '"Nenhum registro encontrado na tabela [' . $Tabela . ']"}';
            }
        }
        else {
            $Retorno .= '{"Erro":' . '"Problema na leitura da tabela [' . $Tabela . ']"}';
        }

        if ($Tabela == "marca") {
            $Retorno = '{"marcas":[' . $Retorno . ']}';
        }
        else if ($Tabela == "modelo") {
            $Retorno = '{"modelos":[' . $Retorno . ']}';
        }    
        else if ($Tabela == "tipo_veiculo") {
            $Retorno = '{"tipos":[' . $Retorno . ']}';
        }
        else if ($Tabela == "cor_veiculo") {
            $Retorno = '{"cores":[' . $Retorno . ']}';
        }
        else if ($Tabela == "combustivel") {
            $Retorno = '{"combustiveis":[' . $Retorno . ']}';
        }
        else if ($Tabela == "veiculo") {
            $Retorno = '{"veiculos":[' . $Retorno . ']}';
        }
    }

    $ConexaoBaseDados->FecharConexao();

    echo $Retorno;
?>