<?php

class ContaBuilder {

    private $nomeConta; //nome da conta
    private $idsProprietarios; //ids de todos os donos
    private $valoresProprietarios; //valores de cada dono
    private $nomesItens; // nomes dos itens
    private $valoresItens; // valores dos itens
    private $valorIntegranteItens; //valores da distribuicao
    private $idsIntegrantes; //ids das distribuicoes
    private $contadorIntegrantes; //contador para saber onde cada integrante vai ficar
    private $descricaoAdicional;
    private $formato;

    function __construct() {
        $this->formato = new Formato();
    }

    public function setNomeConta($nomeConta) {
        $this->nomeConta = $nomeConta;
    }

    public function setIdsProprietarios($idsDonos) {
        $this->idsProprietarios = $idsDonos;
    }

    public function setValoresProprietarios($valoresDonos) {
        $this->valoresProprietarios = $valoresDonos;
        foreach ($this->valoresProprietarios as $key => $value) {
            $this->valoresProprietarios[$key] = $this->formato->numeroControle($value);
        }
    }

    public function setNomesItens($nomesItens) {
        $this->nomesItens = $nomesItens;
    }

    public function setValoresItens($valoresItens) {
        $this->valoresItens = $valoresItens;
        foreach ($this->valoresItens as $key => $value) {
            $this->valoresItens[$key] = $this->formato->numeroControle($value);
        }
    }

    public function setValorIntegranteItens($valorIntegranteItens) {
        $this->valorIntegranteItens = $valorIntegranteItens;
        foreach ($this->valorIntegranteItens as $key => $value) {
            $this->valorIntegranteItens[$key] = $this->formato->numeroControle($value);
        }
    }

    public function setIdsIntegrantes($idsIntegrantes) {
        $this->idsIntegrantes = $idsIntegrantes;
    }

    public function setContadorIntegrantes($contadorIntegrantes) {
        $this->contadorIntegrantes = $contadorIntegrantes;
    }

    public function setDescricaoAdicional($descricaoAdicional) {
        $this->descricaoAdicional = $descricaoAdicional;
    }

    public function gerarConta() {
        $indexIntegrantes = 0;
        $valorTotal = 0;
        $integrantesConta = array();
        $itens = array();

        //ContaItem:
        if (isset($this->nomesItens)) {
            for ($i = 0; $i < count($this->nomesItens); $i++) {
                $valorTotal += $this->valoresItens[$i];
                //Distribuicao:
                $distribuicoes = array();
                for ($j = 0; $j < $this->contadorIntegrantes[$i]; $j++) {
                    $id = $this->idsIntegrantes[$indexIntegrantes];
                    $valor = $this->valorIntegranteItens[$indexIntegrantes];

                    //Se não existir um integrante com aquele ID, ele é critado:
                    if (!isset($integrantesConta[$id])) {
                        $usuario = new Usuario($id, null, null);
                        $integrantesConta[$id] = new IntegrantesConta($usuario, 0, 0, 0, 0, 0);
                    }

                    $distribuicoes[$j] = new Distribuicao($integrantesConta[$id]->getUsuario(), $valor);
                    $integrantesConta[$id]->incrementarValorTotal($valor);
                    $indexIntegrantes++;
                }

                $itens[$i] = new ContaItem($this->nomesItens[$i], $this->valoresItens[$i], $distribuicoes);
            }
        }

        //Verifica quem pagou a conta na hora(dono):
        if (isset($this->idsProprietarios)) {
            for ($i = 0; $i < count($this->idsProprietarios); $i++) {
                $id = $this->idsProprietarios[$i];
                $valor = $this->valoresProprietarios[$i];
                //Seta integrante como dono
                if (isset($integrantesConta[$id])) {
                    $integrantesConta[$id]->setValores($valor);
                } else {
                    $usuario = new Usuario($id, null, null);
                    $integrantesConta[$id] = new IntegrantesConta($usuario, $valor, $valor, 0, 0, 0);
                }
            }
        }

        //Percorre vetor de integrantes para setar os valores de quem não é dono:
        foreach ($integrantesConta as $integrante) {
            if (!$integrante->isDono()) {
                $integrante->setValores(0);
            }
        }
        
        echo $this->descricaoAdicional;
        return new Conta($this->nomeConta, $this->descricaoAdicional, $valorTotal, null, $integrantesConta, $itens, null);
    }

}
