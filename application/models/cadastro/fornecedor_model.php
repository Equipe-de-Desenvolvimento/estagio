<?php

class fornecedor_model extends Model {

    var $_financeiro_credor_devedor_id = null;
    var $_razao_social = null;
    var $_cnpj = null;
    var $_celular = null;
    var $_telefone = null;
    var $_tipo_logradouro_id = null;
    var $_logradouro = null;
    var $_numero = null;
    var $_cadastros_fornecedor_id = null;
    var $_bairro = null;
    var $_complemento = null;
    var $_municipio_id = null;
    var $_cep = null;
    var $_cpf = null;
    var $_email = null;

    function Fornecedor_model($financeiro_credor_devedor_id = null) {
        parent::Model();
        if (isset($financeiro_credor_devedor_id)) {
            $this->instanciar($financeiro_credor_devedor_id);
        }
    }

    function listar($args = array()) {
        $this->db->select(' f.financeiro_credor_devedor_id,
                            f.razao_social,
                            f.ativo,
                            f.cnpj,
                            f.cpf,
                            f.telefone,
                            f.email');
        $this->db->from('tb_financeiro_credor_devedor f');
        if (@$args['ativo'] == 't') {
            $this->db->where('ativo', 'false');
        } else {
            $this->db->where('ativo', 'true');
        }
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where("(f.razao_social ilike '%{$args['nome']}%' OR f.cnpj ilike '%{$args['nome']}%' OR f.cpf ilike '%{$args['nome']}%')");
        }

        if (isset($args['cpf']) && strlen($args['cpf']) > 0) {
            $this->db->where('f.cpf ilike', '%' . $args ['cpf'] . '%');
        }

        return $this->db;
    }

    function listartipo() {
        $this->db->select('tipo_logradouro_id,
                            descricao');
        $this->db->from('tb_tipo_logradouro');
        $return = $this->db->get();
        return $return->result();
    }

    // function reativar($financeiro_credor_devedor_id) {
    //     $this->db->select('financeiro_credor_devedor_id');
    //     $this->db->from('tb_financeiro_credor_devedor');
    //     $this->db->where('ativo', 't');
    //     $this->db->where("(cpf = (
    //                              SELECT cpf FROM ponto.tb_financeiro_credor_devedor 
    //                              WHERE financeiro_credor_devedor_id = $financeiro_credor_devedor_id LIMIT 1 ) 
    //                     OR cnpj = (
    //                              SELECT cnpj FROM ponto.tb_financeiro_credor_devedor 
    //                              WHERE financeiro_credor_devedor_id = $financeiro_credor_devedor_id LIMIT 1 )
    //                     )");
    //     $return = $this->db->get()->result();

    //     if (count($return) == 0) {
    //         $horario = date("Y-m-d H:i:s");
    //         $operador_id = $this->session->userdata('operador_id');
    //         $this->db->set('ativo', 't');
    //         $this->db->set('data_atualizacao', $horario);
    //         $this->db->set('operador_atualizacao', $operador_id);
    //         $this->db->where('financeiro_credor_devedor_id', $financeiro_credor_devedor_id);
    //         $this->db->update('tb_financeiro_credor_devedor');
    //         $erro = $this->db->_error_message();
    //         if (trim($erro) != "") // erro de banco
    //             return -1;
    //         else
    //             return 0;
    //     }
    //     else {
    //         return -1;
    //     }
    // }

    function verificadependenciasexclusao($financeiro_credor_devedor_id) {

        $this->db->select('operador_id');
        $this->db->from('tb_operador');
        $this->db->where('credor_devedor_id', $financeiro_credor_devedor_id);
        $this->db->where('ativo', 't');
        $operadores = $this->db->get()->result();

        $this->db->select('convenio_id');
        $this->db->from('tb_convenio');
        $this->db->where('credor_devedor_id', $financeiro_credor_devedor_id);
        $this->db->where('ativo', 't');
        $convenios = $this->db->get()->result();

        $this->db->select('estoque_fornecedor_id');
        $this->db->from('tb_estoque_fornecedor');
        $this->db->where('credor_devedor_id', $financeiro_credor_devedor_id);
        $this->db->where('ativo', 't');
        $fornecedores = $this->db->get()->result();

        $result = array(
            "fornecedores" => count($fornecedores),
            "operadores" => count($operadores),
            "convenios" => count($convenios)
        );

        return $result;
    }

    function listarcredoexistentecpfcnpj() {
        $cpf = str_replace("-", "", str_replace(".", "", $_POST['txtCPF']));
        $cnpj = str_replace("-", "", str_replace("/", "", str_replace(".", "", $_POST['txtCNPJ'])));
        $this->db->select('razao_social, financeiro_credor_devedor_id');
        $this->db->from('tb_financeiro_credor_devedor');

        $this->db->where("((cnpj = '$cnpj' AND cnpj is not null AND cnpj != '') OR (cpf = '$cpf' AND cpf is not null AND cpf != ''))");
        if($_POST['txtcadastrosfornecedorid'] > 0){
            $this->db->where('financeiro_credor_devedor_id !=', $_POST['txtcadastrosfornecedorid']);
        }
        $this->db->where('ativo', 't');
        $result = $this->db->get()->result();

        // var_dump($result); die;

        if(count($result) > 0){
            $mensagem =  "Já existe um credor associado a esse CPF ou CNPJ";
            return $mensagem;
        }else{
            return null;
        }
        

    }

    function listarcredordevedor($financeiro_credor_devedor_id) {
        $this->db->select('f.financeiro_credor_devedor_id,
                            f.razao_social,
                            f.email,
                            f.cpf,
                            f.cnpj,
                            f.telefone');
        $this->db->from('tb_financeiro_credor_devedor f');
        $this->db->where('ativo', 'true');
        $this->db->where('financeiro_credor_devedor_id f !=', $financeiro_credor_devedor_id);
        
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $nome = $this->removerCaracterEsp($args['nome']);
            // var_dump($nome); die;
            $this->db->where("translate(f.nome,  
            'áàâãäåaaaÁÂÃÄÅAAAÀéèêëeeeeeEEEÉEEÈÊìíîïìiiiÌÍÎÏÌIIIóôõöoooòÒÓÔÕÖOOOùúûüuuuuÙÚÛÜUUUUçÇñÑýÝ',  
            'aaaaaaaaaAAAAAAAAAeeeeeeeeeEEEEEEEEiiiiiiiiIIIIIIIIooooooooOOOOOOOOuuuuuuuuUUUUUUUUcCnNyY'   
             ) ilike", '%' . $nome . '%');
        }
        if (isset($args['cpf']) && strlen($args['cpf']) > 0) {
            $this->db->where('f.cpf ilike', '%' . $args ['cpf'] . '%');
        }

        if (isset($args ['telefone']) && strlen($args ['telefone']) > 0) {
            $this->db->where("(f.celular ilike '%" . $args['telefone'] . "%' f.telefone ilike '%" . $args['telefone'] . "%')");
        }
       
       if (isset($args ['email']) && strlen($args ['email']) > 0) {
     $this->db->where("(f.email ilike '%" . $args['f.email'] . "%' OR f.email_alternativo ilike '%" . $args['email'] . "%')");
    
       }

       $this->db->where('f.ativo','t');
        $return = $this->db->get();
        return $return->result();
    }

    function excluir($financeiro_credor_devedor_id){
        $this->db->set('ativo','f');
        $this->db->where('financeiro_credor_devedor_id',$financeiro_credor_devedor_id); 
        $this->db->update('tb_financeiro_credor_devedor');

        return 1;
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

    private function instanciar($financeiro_credor_devedor_id) {

        if ($financeiro_credor_devedor_id != 0) {
            $this->db->select('financeiro_credor_devedor_id, 
                               razao_social,
                               cnpj,
                               cpf,
                               celular,
                               telefone,
                               f.tipo_logradouro_id,
                               logradouro,
                               tipo_pessoa,
                               email,
                               observacao,
                               numero,
                               bairro,
                               complemento,
                               f.municipio_id,
                               c.nome,
                               c.estado,
                               cep');
            $this->db->from('tb_financeiro_credor_devedor f');
            $this->db->join('tb_municipio c', 'c.municipio_id = f.municipio_id', 'left');
            $this->db->where("financeiro_credor_devedor_id", $financeiro_credor_devedor_id);
            $query = $this->db->get();
            $return = $query->result();
            $this->_financeiro_credor_devedor_id = $financeiro_credor_devedor_id;
            $this->_cnpj = $return[0]->cnpj;
            $this->_cpf = $return[0]->cpf;
            $this->_razao_social = $return[0]->razao_social;
            $this->_celular = $return[0]->celular;
            $this->_telefone = $return[0]->telefone;
            $this->_tipo_pessoa = $return[0]->tipo_pessoa;
            $this->_email = $return[0]->email;
            $this->_observacao = $return[0]->observacao;
            $this->_tipo_logradouro_id = $return[0]->tipo_logradouro_id;
            $this->_logradouro = $return[0]->logradouro;
            $this->_numero = $return[0]->numero;
            $this->_bairro = $return[0]->bairro;
            $this->_complemento = $return[0]->complemento;
            $this->_municipio_id = $return[0]->municipio_id;
            $this->_nome = $return[0]->nome;
            $this->_estado = $return[0]->estado;
            $this->_cep = $return[0]->cep;
        } else {
            $this->_financeiro_credor_devedor_id = null;
        }
    }

}

?>
