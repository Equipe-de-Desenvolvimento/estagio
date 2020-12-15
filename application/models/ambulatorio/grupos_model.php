<?php

class grupos_model extends Model {
    var $_ambulatorio_grupo_id = null;
    var $_nome = null;
    var $_tipo = null;
    var $_qtde = null;

    function Grupos_model($ambulatorio_grupo_id = null) {
        parent::Model();
        if (isset($ambulatorio_grupo_id)) {
            $this->instanciar($ambulatorio_grupo_id);
        }
    }

    function listar($args = array()) {
        $this->db->select('ambulatorio_grupo_id,
                            nome,
                            tipo');
        $this->db->from('tb_ambulatorio_grupo');
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('nome ilike', "%" . $args['nome'] . "%");
        }
        return $this->db;
    }


    function gravar() {
        try {
            /* inicia o mapeamento no banco */
            $ambulatorio_grupo_id = $_POST['txtgrupoid'];
            $this->db->set('nome', $_POST['txtNome']);
            $this->db->set('tipo', $_POST['txttipo']);

            if ($_POST['txtgrupoid'] == "") {// insert
                $this->db->insert('tb_ambulatorio_grupo');
                $erro = $this->db->_error_message();
                if (trim($erro) != "") // erro de banco
                    return -1;
                else
                    $ambulatorio_grupo_id = $this->db->insert_id();
            }
            else { // update
                $grupo_id = $_POST['txtgrupoid'];
                $this->db->where('ambulatorio_grupo_id', $ambulatorio_grupo_id);
                $this->db->update('tb_ambulatorio_grupo');
            }
            return $ambulatorio_grupo_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function excluir($ambulatorio_grupo_id, $nome_grupo){
        $this->db->select('grupo');
        $this->db->from('tb_procedimento_tuss');
        $this->db->where('grupo', $nome_grupo);
        $this->db->where('ativo', 'TRUE');
        $qtd = $this->db->get()->result();

        // echo count($qtd);
        // die;
        
        if(count($qtd) == 0){
            try {
                    $this->db->where('ambulatorio_grupo_id', $ambulatorio_grupo_id);
                    $this->db->delete('tb_ambulatorio_grupo');
                    $erro = $this->db->_error_message();
                    if (trim($erro) != "") // erro de banco
                        return -1;

            } catch (Exception $exc) {
                return -1;
            }
        }else{
            return -2;
        }
    }

    private function instanciar($ambulatorio_grupo_id) {

        if ($ambulatorio_grupo_id != 0) {
            $this->db->select('ambulatorio_grupo_id, nome, tipo');
            $this->db->from('tb_ambulatorio_grupo');
            $this->db->where("ambulatorio_grupo_id", $ambulatorio_grupo_id);
            $query = $this->db->get();
            $return = $query->result();
            $this->_ambulatorio_grupo_id = $ambulatorio_grupo_id;
            $this->_nome = $return[0]->nome;
            $this->_tipo = $return[0]->tipo;
        } else {
            $this->_ambulatorio_grupo_id = null;
        }
    }

}