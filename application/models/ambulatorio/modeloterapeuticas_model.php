<?php

class modeloterapeuticas_model extends Model {

    var $_ambulatorio_modelo_terapeuticas_id = null;
    var $_nome = null;
    var $_medico_id = null;
    var $_texto = null;

    function Modeloterapeuticas_model($ambulatorio_modelo_terapeuticas_id = null) {
        parent::Model();
        if (isset($ambulatorio_modelo_terapeuticas_id)) {
            $this->instanciar($ambulatorio_modelo_terapeuticas_id);
        }
    }

    function listar($args = array()) {
        $this->db->select('ambulatorio_modelo_terapeuticas_id,
                            nome');
        $this->db->from('tb_ambulatorio_modelo_terapeuticas_id');
        $this->db->where('ativo', 't');
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('nome ilike', "%" . $args['nome'] . "%");
        }
        return $this->db;
    }

    function excluir($ambulatorio_modelo_terapeuticas_id) {

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('ambulatorio_modelo_terapeuticas_id', $ambulatorio_modelo_terapeuticas_id);
        $this->db->update('tb_ambulatorio_modelo_terapeuticas_id');
        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return false;
        else
            return true;
    }



    function gravar() {
        try {
            /* inicia o mapeamento no banco */
            $ambulatorio_modelo_terapeuticas_id = $_POST['ambulatorio_modelo_terapeuticas_id'];
            $this->db->set('nome', $_POST['txtNome']);
            // $this->db->set('medico_id', $_POST['medico']);
            $this->db->set('texto', $_POST['terapeuticas']);
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            if ($_POST['ambulatorio_modelo_terapeuticas_id'] == "") {// insert
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_ambulatorio_modelo_terapeuticas_id');
                $erro = $this->db->_error_message();
                if (trim($erro) != "") // erro de banco
                    return -1;
                else
                    $ambulatorio_modelo_terapeuticas_id = $this->db->insert_id();
            }
            else { // update
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $this->db->where('ambulatorio_modelo_terapeuticas_id', $ambulatorio_modelo_terapeuticas_id);
                $this->db->update('tb_ambulatorio_modelo_terapeuticas_id');
            }
            return $ambulatorio_modelo_terapeuticas_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    private function instanciar($ambulatorio_modelo_terapeuticas_id) {
        if ($ambulatorio_modelo_terapeuticas_id != 0) {
            $this->db->select('ambulatorio_modelo_terapeuticas_id,
                            nome,
                            texto');
            $this->db->from('tb_ambulatorio_modelo_terapeuticas_id');
            $this->db->where("ambulatorio_modelo_terapeuticas_id", $ambulatorio_modelo_terapeuticas_id);
            $query = $this->db->get();
            $return = $query->result();
            $this->_ambulatorio_modelo_terapeuticas_id = $ambulatorio_modelo_terapeuticas_id;
            $this->_nome = $return[0]->nome;
            $this->_texto = $return[0]->texto;
        } else {
            $this->_ambulatorio_modelo_terapeuticas_id = null;
        }
    }

}

?>
