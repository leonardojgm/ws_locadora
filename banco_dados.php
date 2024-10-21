<?php
    class BancoDados {
        private $Conexao;
        private $Servidor;
        private $Usuario;
        private $Senha;
        private $BaseDados;
        private $NumeroErro;
        private $RegistrosLidos;

        function __construct ($Servidor, $Usuario, $Senha, $BaseDados){
            $this->Conexao = NULL;
            $this->Servidor = $Servidor;
            $this->Usuario = $Usuario;
            $this->Senha = $Senha;
            $this->BaseDados = $BaseDados;
            $this->NumeroErro = -1;
        }

        public function AbrirConexao() {
            $this->Conexao = new mysqli($this->Servidor, $this->Usuario, $this->Senha, $this->BaseDados);

            if ($this->Conexao->connect_errno){
                $this->Conexao = NULL;
                $this->NumeroErro = mysqli_connect_errno();
            }

            return $this->Conexao;
        }

        public function FecharConexao() {
            if ($this->Conexao == NULL) {
                return FALSE;
            }
            else {
                $this->Conexao->close();
                return TRUE;
            }
        }

        public function CodigoErro() {
            return $this->NumeroErro;
        }

        public function LerTabela($ListaCampos = "*", $NomeTabela = "", $Condicao = "", $Ordenacao = "") {
            if ($NomeTabela != "") {
                $ComandoSQL = "SELECT " . $ListaCampos . " FROM " . $NomeTabela;
    
                if ($Condicao != "") {
                    $ComandoSQL .= " WHERE " . $Condicao;
                }
    
                if ($Ordenacao != "") {
                    $ComandoSQL .= " ORDER BY " . $Ordenacao;
                }
    
                $this->RegistrosLidos = $this->Conexao->query($ComandoSQL);
    
                return $this->RegistrosLidos;
            }
            else {
                return NULL;
            }
        }

        public function AdicionarVeiculo($Marca, $Modelo, $Placa, $Tipo, $Cor, $Ano, $Combustivel, $Diaria) {
            $DataInclusao = date("Y/m/d");
            $ComandoSQL = 'INSERT INTO veiculo(Codigo_Marca, Codigo_Modelo, Placa, Codigo_Tipo, Codigo_Cor, Ano_Modelo, Codigo_Combustivel, Data_Inclusao, Valor_Diaria)' . 
            'VALUES(' . $Marca . ', ' . $Modelo . ', "' . $Placa . '", ' . $Tipo . ', ' . $Cor . ', ' . $Ano . ', ' . $Combustivel . ', "' . $DataInclusao . '", ' . $Diaria . ');';
            $Resultado = $this->Conexao->query($ComandoSQL);

            if (($Resultado == FALSE) || ($this->Conexao->affected_rows != 1)) {
                return FALSE;
            }
            else {
                return TRUE;
            }
        }
    }    
?>