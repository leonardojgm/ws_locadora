<!DOCTYPE HTML>
<html>
    <head>
        <meta content="text/html; charset=UTF-8" http-equiv="content-type"/>
        <title>Teste Web Service em PHP</title>
    </head>
    <body>
        <h1>Lista de Marcas de Veículos</h1>
        <?php
            require_once("configuracao.php");
            require_once("banco_dados.php");

            $ConexaoBaseDados = new BancoDados($Servidor, $Usuario, $Senha, $BaseDados);

            if ($ConexaoBaseDados->AbrirConexao() == NULL) {
                echo "ERRO: Erro na conexão com a base de dados!<br> Nro. do Erro: [" . $ConexaoBaseDados->CodigoErro() . "]";
            }
            else
            {
                $Registros = $ConexaoBaseDados->LerTabela("*", "marca", "", "Codigo_Marca");

                if ($Registros != NULL) {
                    if ($Registros->num_rows > 0) {
                        echo "<table><thead><tr><th>Código</th><th>Descrição</th></tr></thead><tbody>";

                        while ($DadosRegistro = $Registros->fetch_assoc()) {
                            echo "<tr><td>" . $DadosRegistro["Codigo_Marca"] . "</td><td>" . $DadosRegistro["Descricao_Marca"] . "</td></tr>";
                        }

                        echo "</tbody></table>";
                    }
                    else {
                        echo "ERRO: Nenhum registro encontrado na tabela [marca]";
                    }
                }
                else {
                    echo "ERRO: Problema na leitura da tabela [marca]";
                }
            }

            $ConexaoBaseDados->FecharConexao();
        ?>
    </body>
</html>