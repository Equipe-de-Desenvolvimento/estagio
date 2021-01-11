<?php

class fornecedor_model extends Model {

    var $_estoque_fornecedor_id = null;
    var $_razao_social = null;
    var $_fantasia = null;
    var $_cnpj = null;
    var $_celular = null;
    var $_telefone = null;
    var $_tipo_logradouro_id = null;
    var $_logradouro = null;
    var $_numero = null;
    var $_bairro = null;
    var $_complemento = null;
    var $_municipio_id = null;
    var $_cep = null;
    var $_credor_devedor_id = null;

    function Fornecedor_model($estoque_fornecedor_id = null) {
        parent::Model();
        if (isset($estoque_fornecedor_id)) {
            $this->instanciar($estoque_fornecedor_id);
        }
    }

    function listar($args = array()) {
        $this->db->select('estoque_fornecedor_id,
                            fantasia,
                            razao_social,
                            cnpj,
                            cpf,
                            telefone,
                            email');
        $this->db->from('tb_estoque_fornecedor');
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('razao_social ilike', "%" . $args['nome'] . "%");
            $this->db->orwhere('cnpj ilike', "%" . $args['nome'] . "%");
        }
        $this->db->where('ativo', 'true');
        return $this->db;
    }

    function autocompletefornecedor($parametro = null) {
        $this->db->select('estoque_fornecedor_id,
                            fantasia,
                            razao_social,
                            cnpj,
                            email');
        $this->db->from('tb_estoque_fornecedor');
        $this->db->where('ativo', 'true');
        if ($parametro != null) {
            $this->db->where('fantasia ilike', "%" . $parametro . "%");
//            $this->db->where('cnpj', $parametro . "%");
        }
        $return = $this->db->get();
        return $return->result();
    }

    function autocompletefornecedorfarmacia($parametro = null) {
        $this->db->select('farmacia_fornecedor_id,
                            fantasia,
                            razao_social,
                            cnpj,
                            email');
        $this->db->from('tb_farmacia_fornecedor');
        $this->db->where('ativo', 'true');
        if ($parametro != null) {
            $this->db->where('fantasia ilike', "%" . $parametro . "%");
//            $this->db->where('cnpj', $parametro . "%");
        }
        $return = $this->db->get();
        return $return->result();
    }

    function listartipo() {
        $this->db->select('tipo_logradouro_id,
                            descricao');
        $this->db->from('tb_tipo_logradouro');
        $return = $this->db->get();
        return $return->result();
    }

function excluir($financeiro_credor_devedor_id) {

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('financeiro_credor_devedor_id', $financeiro_credor_devedor_id);
        $this->db->update('tb_financeiro_credor_devedor');
        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return -1;
        else
            return 0;
    }
    
    
    function gravarunificarcredor() {
        try {
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            // Antigo
            $financeiro_credor_devedor_id = $_POST['credor_devedor_id_antigo'];
            // Novo
            $credor_devedor_novo = $_POST['credor_devedor_id'];

            $this->db->set('credor_devedor_id', $credor_devedor_novo);
            $this->db->where('credor_devedor_id', $financeiro_credor_devedor_id);
            $this->db->update('tb_convenio');

            $this->db->set('nome', $credor_devedor_novo);
            $this->db->where('nome', $financeiro_credor_devedor_id);
            $this->db->update('tb_entradas');

            $this->db->set('credor_devedor_id', $credor_devedor_novo);
            $this->db->where('credor_devedor_id', $financeiro_credor_devedor_id);
            $this->db->update('tb_estoque_fornecedor');

            $this->db->set('credor_devedor_id', $credor_devedor_novo);
            $this->db->where('credor_devedor_id', $financeiro_credor_devedor_id);
            $this->db->update('tb_farmacia_fornecedor');

            $this->db->set('credor', $credor_devedor_novo);
            $this->db->where('credor', $financeiro_credor_devedor_id);
            $this->db->update('tb_financeiro_contaspagar');

            $this->db->set('devedor', $credor_devedor_novo);
            $this->db->where('devedor', $financeiro_credor_devedor_id);
            $this->db->update('tb_financeiro_contasreceber');

            $this->db->set('devedor', $credor_devedor_novo);
            $this->db->where('devedor', $financeiro_credor_devedor_id);
            $this->db->update('tb_financeiro_contasreceber');

            $this->db->set('credor_devedor_id', $credor_devedor_novo);
            $this->db->where('credor_devedor_id', $financeiro_credor_devedor_id);
            $this->db->update('tb_operador');

            $this->db->set('ativo', 'f');
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('financeiro_credor_devedor_id', $financeiro_credor_devedor_id);
            $this->db->update('tb_financeiro_credor_devedor');

            $this->db->set('credor_antigo', $credor_devedor_novo);
            $this->db->set('credor_novo', $credor_devedor_novo);
            $this->db->set('data', $horario);
            $this->db->set('operador_id', $operador_id);
            $this->db->insert('tb_unificar_credor');
           
            return true;
        } catch (Exception $exc) {
            return -1;
        }
    }

function gravar() {
        try {
            $cpf = str_replace("-", "", str_replace(".", "", $_POST['txtCPF']));
            $cnpj = str_replace("-", "", str_replace("/", "", str_replace(".", "", $_POST['txtCNPJ'])));

//            var_dump($cnpj, $cpf);

            $this->db->select('financeiro_credor_devedor_id');
            $this->db->from('tb_financeiro_credor_devedor fcd');
            if ($cpf != '') {
                $this->db->where("fcd.cpf", $cpf);
            }
            if ($cnpj != '') {
                $this->db->where("fcd.cnpj", $cnpj);
            }
            $this->db->where("fcd.ativo", 't');
            $return = $this->db->get()->result();


            /* inicia o mapeamento no banco */
            $financeiro_credor_devedor_id = $_POST['txtcadastrosfornecedorid'];
            $this->db->set('razao_social', $_POST['txtrazaosocial']);
            $this->db->set('cep', $_POST['txttipo_id']);
            $this->db->set('cpf', $cpf);
            if ($cnpj != '') {
                $this->db->set('cnpj', $cnpj);
            }
            $this->db->set('telefone', str_replace("(", "", str_replace(")", "", str_replace("-", "", $_POST['telefone']))));
            $this->db->set('celular', str_replace("(", "", str_replace(")", "", str_replace("-", "", $_POST['celular']))));
            if ($_POST['txttipo_id'] != '') {
                $this->db->set('tipo_logradouro_id', $_POST['txttipo_id']);
            }
            if ($_POST['municipio_id'] != '') {
                $this->db->set('municipio_id', $_POST['municipio_id']);
            }
            $this->db->set('tipo_pessoa', $_POST['tipo_pessoa']);
            $this->db->set('email', $_POST['email']);
            $this->db->set('observacao', $_POST['observacao']);
            $this->db->set('logradouro', $_POST['endereco']);
            $this->db->set('numero', $_POST['numero']);
            $this->db->set('bairro', $_POST['bairro']);
            $this->db->set('complemento', $_POST['complemento']);
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');


            if ($_POST['txtcadastrosfornecedorid'] == "") {// insert
//                echo "<pre>";
//                var_dump($return); die;
                if (count($return) > 0) {
                    return -1;
                }

                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_financeiro_credor_devedor');
                $erro = $this->db->_error_message();
                if (trim($erro) != "") // erro de banco
                    return -1;
                else
                    $financeiro_credor_devedor_id = $this->db->insert_id();
            }
            else { // update
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $this->db->where('financeiro_credor_devedor_id', $financeiro_credor_devedor_id);
                $this->db->update('tb_financeiro_credor_devedor');
            }
            return $financeiro_credor_devedor_id;
        } catch (Exception $exc) {
            return -1;
        }
    }


    private function instanciar($estoque_fornecedor_id) {

        if ($estoque_fornecedor_id != 0) {
            $this->db->select('f.estoque_fornecedor_id, 
                               f.razao_social,
                               f.fantasia,
                               f.cnpj,
                               f.cpf,
                               f.celular,
                               f.telefone,
                               f.email,
                               f.tipo_logradouro_id,
                               f.logradouro,
                               f.numero,
                               f.bairro,
                               f.complemento,
                               f.municipio_id,
                               c.nome,
                               f.credor_devedor_id,
                               fc.razao_social as credor,
                               c.estado,
                               f.cep,');
            $this->db->from('tb_estoque_fornecedor f');
            $this->db->join('tb_municipio c', 'c.municipio_id = f.municipio_id', 'left');
            $this->db->join('tb_financeiro_credor_devedor fc', 'fc.financeiro_credor_devedor_id = f.credor_devedor_id', 'left');
            $this->db->where("estoque_fornecedor_id", $estoque_fornecedor_id);
            $query = $this->db->get();
            $return = $query->result();
            $this->_estoque_fornecedor_id = $estoque_fornecedor_id;
            $this->_cnpj = $return[0]->cnpj;
            $this->_cpf = $return[0]->cpf;
            $this->_razao_social = $return[0]->razao_social;
            $this->_fantasia = $return[0]->fantasia;
            $this->_celular = $return[0]->celular;
            $this->_telefone = $return[0]->telefone;
            $this->_tipo_logradouro_id = $return[0]->tipo_logradouro_id;
            $this->_logradouro = $return[0]->logradouro;
            $this->_numero = $return[0]->numero;
            $this->_bairro = $return[0]->bairro;
            $this->_complemento = $return[0]->complemento;
            $this->_municipio_id = $return[0]->municipio_id;
            $this->_nome = $return[0]->nome;
            $this->_estado = $return[0]->estado;
            $this->_cep = $return[0]->cep;
            $this->_credor_devedor_id = $return[0]->credor_devedor_id;
            $this->_credor = $return[0]->credor;
        } else {
            $this->_estoque_fornecedor_id = null;
        }
    }

}

?>
