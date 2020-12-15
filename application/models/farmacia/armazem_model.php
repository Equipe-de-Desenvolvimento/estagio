<?php

class armazem_model extends Model {

    var $_farmacia_armazem_id = null;
    var $_descricao = null;

    function Armazem_model($farmacia_armazem_id = null) {
        parent::Model();
        if (isset($farmacia_armazem_id)) {
            $this->instanciar($farmacia_armazem_id);
        }
    }

    function listar($args = array()) {
        $this->db->select('farmacia_armazem_id,
                            descricao');
        $this->db->from('tb_farmacia_armazem');
        $this->db->where('ativo', 'true');
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('descricao ilike', "%" . $args['nome'] . "%");
        }
        return $this->db;
    }

    function excluir($farmacia_armazem_id) {

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('farmacia_armazem_id', $farmacia_armazem_id);
        $this->db->update('tb_farmacia_armazem');
        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return -1;
        else
            return 0;
    }

    function gravar() {
        try {
            /* inicia o mapeamento no banco */
            $farmacia_armazem_id = $_POST['txtfarmaciaarmazemid'];
            $this->db->set('descricao', $_POST['txtNome']);
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            if ($_POST['txtfarmaciaarmazemid'] == "") {// insert
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_farmacia_armazem');
                $erro = $this->db->_error_message();
                if (trim($erro) != "") // erro de banco
                    return -1;
                else
                    $farmacia_armazem_id = $this->db->insert_id();
            }
            else { // update
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $exame_armazem_id = $_POST['txtfarmaciaarmazemid'];
                $this->db->where('farmacia_armazem_id', $farmacia_armazem_id);
                $this->db->update('tb_farmacia_armazem');
            }
            return $farmacia_armazem_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    private function instanciar($farmacia_armazem_id) {

        if ($farmacia_armazem_id != 0) {
            $this->db->select('farmacia_armazem_id, descricao');
            $this->db->from('tb_farmacia_armazem');
            $this->db->where("farmacia_armazem_id", $farmacia_armazem_id);
            $query = $this->db->get();
            $return = $query->result();
            $this->_farmacia_armazem_id = $farmacia_armazem_id;
            $this->_descricao = $return[0]->descricao;
        } else {
            $this->_farmacia_armazem_id = null;
        }
    }

    function listararmazem() {


        $sql = $this->db->select('farmacia_armazem_id,
                            descricao');
        $this->db->from('tb_farmacia_armazem');
        $this->db->where('ativo', 'true');


        return $sql->get()->result();
    }

    function listarproduto() {
        $this->db->select('p.farmacia_produto_id,
                            p.descricao,
                            p.unidade_id,
                            u.descricao as unidade,
                            p.sub_classe_id,
                            sc.descricao as sub_classe,
                            p.valor_compra');
        $this->db->from('tb_farmacia_produto p');
        $this->db->join('tb_farmacia_sub_classe sc', 'sc.farmacia_sub_classe_id = p.sub_classe_id', 'left');
        $this->db->join('tb_farmacia_unidade u', 'u.farmacia_unidade_id = p.unidade_id', 'left');
        $this->db->where('p.ativo', 'true');

        $return = $this->db->get();
        return $return->result();
    }

    function gravartransferencia() {
        try {
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            $this->db->select('ea.descricao as armazem,
            ef.fantasia,
            sum(es.quantidade) as total,
            ep.descricao as produto');
            $this->db->from('tb_farmacia_saldo es');
            $this->db->join('tb_farmacia_armazem ea', 'ea.farmacia_armazem_id = es.armazem_id', 'left');
            $this->db->join('tb_farmacia_fornecedor ef', 'ef.farmacia_fornecedor_id = es.fornecedor_id', 'left');
            $this->db->join('tb_farmacia_produto ep', 'ep.farmacia_produto_id = es.produto_id', 'left');
            $this->db->where('es.ativo', 'true');
            $this->db->where('es.armazem_id', $_POST['armazem']);
            $this->db->where('es.produto_id', $_POST['produto']);
            $this->db->groupby('ea.descricao, ef.fantasia, ep.descricao');
            $this->db->orderby('ea.descricao, ef.fantasia, ep.descricao');
            $saldo = $this->db->get()->result();
           


            $this->db->select('e.farmacia_entrada_id,
                            e.produto_id,
                            e.fornecedor_id,
                            e.armazem_id,
                            e.valor_compra,
                            sum(s.quantidade) as quantidade,
                            e.nota_fiscal,
                            e.validade');
            $this->db->from('tb_farmacia_saldo s');
            $this->db->join('tb_farmacia_entrada e', 'e.farmacia_entrada_id = s.farmacia_entrada_id', 'left');
            $this->db->where('e.produto_id', $_POST['produto']);
            $this->db->where('e.armazem_id', $_POST['armazem']);
            $this->db->where('e.ativo', 't');
            $this->db->where('s.ativo', 't');
//        $this->db->where('quantidade >', '0');
            $this->db->groupby("e.farmacia_entrada_id,
                            e.produto_id,
                            e.fornecedor_id,
                            e.armazem_id,
                            e.valor_compra,
                            e.nota_fiscal,
                            e.validade");
            $this->db->orderby("sum(s.quantidade) desc");
//            $this->db->where("sum(s.quantidade) > 0");

            $return = $this->db->get()->result();

 
            $farmacia_armazem_id = $_POST['armazementrada'];
            $farmacia_transferencia = $_POST['armazem'];
            /* inicia o mapeamento no banco */

//            echo '<pre>';
//            var_dump($return);
//            die;
            $qtdeProduto = $_POST['quantidade'];
            $valor_compra = 0;
            $quantidade_compra = 0;
            foreach ($return as $item) {
                if ($quantidade_compra == $_POST['quantidade']) {
                    break;
                }
                $valor_compra = $valor_compra + $item->valor_compra;
                $quantidade_compra = $quantidade_compra + $item->quantidade;
            }

            $valor_entrada = round(($valor_compra / $quantidade_compra) * $_POST['quantidade'], 2);
            $farmacia_entrada_id = $_POST['entrada'];

            // Pega os ids das saidas pra inserir na tabela de entrada e poder excluir depois
            $saida_array = '';
            $qtdeProdutoSaldo = $saldo[0]->total;

            $i = 0;
            
            
            while ($qtdeProduto > 0) {

                if ($qtdeProduto > $return[$i]->quantidade) {
                    $qtdeProduto = $qtdeProduto - $return[$i]->quantidade;
                    $qtde = $return[$i]->quantidade;
                } else {
                    $qtde = $qtdeProduto;
                    $qtdeProduto = 0;
                }


                if ($return[$i]->farmacia_entrada_id != "") {

                    $this->db->set('farmacia_entrada_id', @$return[$i]->farmacia_entrada_id);
                }
                //        $this->db->set('solicitacao_cliente_id', $_POST['txtfarmacia_solicitacao_id']);
//                if ($_POST['txtexame'] != '') {
//                    $this->db->set('exames_id', $_POST['txtexame']);
//                }
                if ($return[$i]->produto_id != "") {
                    $this->db->set('produto_id', @$return[$i]->produto_id);
                }
                if ($return[$i]->fornecedor_id != "") {
                    $this->db->set('fornecedor_id', @$return[$i]->fornecedor_id);
                }
                if ($return[$i]->armazem_id != "") {
                    $this->db->set('armazem_id', @$return[$i]->armazem_id);
                }

                if (@$valor_entrada != '') {
                    $this->db->set('valor_venda', @$valor_entrada);
                }
//                $this->db->set('ambulatorio_gasto_sala_id', $ambulatorio_gasto_sala_id);
                if (@$qtde != '') {
                    $this->db->set('quantidade', str_replace(",", ".", str_replace(".", "", $qtde)));
                }
                if (@$return[$i]->nota_fiscal != '') {
                    $this->db->set('nota_fiscal', @$return[$i]->nota_fiscal);
                }
 
                if (@$return[$i]->validade != "") {
                    $this->db->set('validade', @$return[$i]->validade);
                }
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_farmacia_saida');
                $farmacia_saida_id = $this->db->insert_id();


                // SALDO 
                $this->db->set('farmacia_entrada_id', $return[$i]->farmacia_entrada_id);
                $this->db->set('farmacia_saida_id', $farmacia_saida_id);
                $this->db->set('produto_id', $return[$i]->produto_id);
                $this->db->set('fornecedor_id', $return[$i]->fornecedor_id);
                $this->db->set('armazem_id', $return[$i]->armazem_id);
                if ($valor_entrada != '') {
                    $this->db->set('valor_compra', $valor_entrada);
                }
//                $this->db->set('ambulatorio_gasto_sala_id', $ambulatorio_gasto_sala_id);
                $quantidade = -(str_replace(",", ".", str_replace(".", "", $qtde)));
                $this->db->set('quantidade', $quantidade);
                $this->db->set('nota_fiscal', $return[$i]->nota_fiscal);
                if ($return[$i]->validade != "") {
                    $this->db->set('validade', $return[$i]->validade);
                }
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_farmacia_saldo');
 
                $i++;
                if ($saida_array == '') {
                    $saida_array = $saida_array . $farmacia_saida_id;
                } else {
                    $saida_array = $saida_array . ',' . $farmacia_saida_id;
                }
            }

 
//            var_dump($saida_array); die;
            // ENTRADA AGORA
            // ENTRADA AGORA
            // ENTRADA AGORA
            // ENTRADA AGORA
            // ENTRADA AGORA
            // ENTRADA AGORA

            $this->db->set('saida_id_transferencia', $saida_array);
            $this->db->set('produto_id', $return[0]->produto_id);
            $this->db->set('fornecedor_id', $return[0]->fornecedor_id);
            $this->db->set('armazem_id', $farmacia_armazem_id);
            $this->db->set('lote', @$return[0]->lote);
            $this->db->set('transferencia', 't');
            $this->db->set('armazem_transferencia', $farmacia_transferencia);
            if ($valor_entrada != '') {
                $this->db->set('valor_compra', $valor_entrada);
            }
            $this->db->set('quantidade', str_replace(",", ".", str_replace(".", "", $_POST['quantidade'])));
            $this->db->set('nota_fiscal', $return[0]->nota_fiscal);
            if (@$returno[0]->validade != "//") {
                $this->db->set('validade', $return[0]->validade);
            }
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_farmacia_entrada');
            $farmacia_entrada_id = $this->db->insert_id();

            $this->db->set('farmacia_entrada_id', $farmacia_entrada_id);
//            $this->db->set('farmacia_saida_id', $return[$i]->farmacia_saida_id);
            $this->db->set('produto_id', $return[0]->produto_id);
            $this->db->set('fornecedor_id', $return[0]->fornecedor_id);
            $this->db->set('armazem_id', $farmacia_armazem_id);
            if ($valor_entrada != '') {
                $this->db->set('valor_compra', $valor_entrada);
            }
//                $this->db->set('ambulatorio_gasto_sala_id', $ambulatorio_gasto_sala_id);
            $quantidade = (str_replace(",", ".", str_replace(".", "", $_POST['quantidade'])));
            $this->db->set('quantidade', $quantidade);
            $this->db->set('nota_fiscal', $return[0]->nota_fiscal);
            if ($return[0]->validade != "") {
                $this->db->set('validade', $return[0]->validade);
            }
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_farmacia_saldo');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            else
                $farmacia_entrada_id = $this->db->insert_id();



            return $farmacia_saida_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

}

?>
