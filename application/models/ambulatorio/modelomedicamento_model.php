<?php

class modelomedicamento_model extends Model {

    var $_ambulatorio_modelo_medicamento_id = null;
    var $_nome = null;
    var $_medico_id = null;
    var $_texto = null;

    function Modelomedicamento_model($ambulatorio_modelo_medicamento_id = null) {
        parent::Model();
        if (isset($ambulatorio_modelo_medicamento_id)) {
            $this->instanciar($ambulatorio_modelo_medicamento_id);
        }
    }

    function listar($args = array()) {
//        DIE;
        $this->db->select('arm.ambulatorio_receituario_medicamento_id as medicamento_id,
                           arm.nome,
                           o.nome as medico,
                           arm.texto');
        $this->db->from('tb_ambulatorio_receituario_medicamento arm');
        $this->db->join('tb_operador o', 'o.operador_id = arm.medico_id', 'left');
        $this->db->where('arm.ativo', "t");
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('arm.nome ilike', "%" . $args['nome'] . "%");
        }
        return $this->db;
    }
    
    function listarMedicamentos() {
//        DIE;
        $this->db->select('arm.ambulatorio_receituario_medicamento_id as medicamento_id,
                           arm.nome,
                           arm.posologia,
                           o.nome as medico,
                           arm.texto');
        $this->db->from('tb_ambulatorio_receituario_medicamento arm');
        $this->db->join('tb_operador o', 'o.operador_id = arm.medico_id', 'left');
        $this->db->where('arm.ativo', "t");
        $this->db->orderby('arm.nome');
        $query = $this->db->get();
        $return = $query->result();
        return $return;
    }

    function listarunidade($args = array()) {
        $this->db->select('amu.ambulatorio_receituario_medicamento_unidade_id as unidade_id,
                           amu.descricao');
        $this->db->from('tb_ambulatorio_receituario_medicamento_unidade amu');
        $this->db->where('amu.ativo', "t");
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('amu.descricao ilike', "%" . $args['nome'] . "%");
        }
        return $this->db;
    }

    function excluir($ambulatorio_modelo_medicamento_id) {

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('ambulatorio_modelo_medicamento_id', $ambulatorio_modelo_medicamento_id);
        $this->db->update('tb_ambulatorio_modelo_medicamento');
        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return false;
        else
            return true;
    }

    function excluirunidade($unidade_id) {

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('ambulatorio_receituario_medicamento_unidade_id', $unidade_id);
        $this->db->update('tb_ambulatorio_receituario_medicamento_unidade');
        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return false;
        else
            return true;
    }

    function gravar() {
        
        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        
        $this->db->set('nome', $_POST['txtmedicamento']);
        $this->db->set('quantidade', $_POST['qtde']);
        if ($_POST['unidade'] == "") {
           $this->db->set('unidade_id', null);
        }else{
          $this->db->set('unidade_id', $_POST['unidadeid']);
        }
        $this->db->set('posologia', $_POST['posologia']); 
        $texto  = $_POST['txtmedicamento']; 
        if ($_POST['qtde'] != "") {
            $texto .= " ----- ".$_POST['qtde'];
        }
        
        if($_POST['unidade'] != ""){
            $texto .=  " ----- ".$_POST['unidade'];
        }
        
        if($_POST['r_especial'] == 'on'){
            $this->db->set('r_especial', 't' );
        }else{
            $this->db->set('r_especial', 'f' );
        }

        $this->db->set('texto', $texto );
          
        $this->db->set('medico_id', $operador_id);
        if($_POST['txtmedicamentoID'] > 0){
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->where('ambulatorio_receituario_medicamento_id', $_POST['txtmedicamentoID']);
            $this->db->update('tb_ambulatorio_receituario_medicamento');
        }else{
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_ambulatorio_receituario_medicamento');
        }
        
        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return false;
        else
            return true;
    }

    function gravarunidade() {
        try {
            /* inicia o mapeamento no banco */
            $unidade_id = $_POST['txtunidadeid'];
            $this->db->set('descricao', $_POST['txtDescricao']);

            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            if ($_POST['txtunidadeid'] == "") {// insert
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_ambulatorio_receituario_medicamento_unidade');
                $erro = $this->db->_error_message();
                if (trim($erro) != "") // erro de banco
                    return -1;
                else
                    $unidade_id = $this->db->insert_id();
            }
            else { // update
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $this->db->where('ambulatorio_receituario_medicamento_unidade_id', $unidade_id);
                $this->db->update('tb_ambulatorio_receituario_medicamento_unidade');
            }
            return $unidade_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function carregarunidade($unidade_id) {
        if ($unidade_id != 0) {
            $this->db->select('amu.ambulatorio_receituario_medicamento_unidade_id as unidade_id,
                               amu.descricao');
            $this->db->from('tb_ambulatorio_receituario_medicamento_unidade amu');
            $this->db->where("ambulatorio_receituario_medicamento_unidade_id", $unidade_id);
            $query = $this->db->get();
            $return = $query->result();
        } else {
            $return = null;
        }
        return $return;
    }

    private function instanciar($ambulatorio_modelo_medicamento_id) {
        if ($ambulatorio_modelo_medicamento_id != 0) {
            $this->db->select('ambulatorio_receituario_medicamento_id,
                            amr.nome,
                            amr.posologia,
                            amr.quantidade,
                            medico_id,
                            o.nome as medico,
                            mu.descricao as unidade,
                            amr.unidade_id,
                            amr.texto,
                            amr.r_especial');
            $this->db->from('tb_ambulatorio_receituario_medicamento amr');
            $this->db->join('tb_operador o', 'o.operador_id = amr.medico_id', 'left');
            $this->db->join('tb_ambulatorio_receituario_medicamento_unidade mu', 'amr.unidade_id = mu.ambulatorio_receituario_medicamento_unidade_id', 'left');
            $this->db->where("ambulatorio_receituario_medicamento_id", $ambulatorio_modelo_medicamento_id);
            $query = $this->db->get();
            $return = $query->result();

            $this->_ambulatorio_modelo_medicamento_id = $ambulatorio_modelo_medicamento_id;
            $this->_nome = $return[0]->nome;
            $this->_medico_id = $return[0]->medico_id;
            $this->_texto = $return[0]->texto;
            $this->_quantidade = $return[0]->quantidade;
            $this->_posologia = $return[0]->posologia;
            $this->_unidade_id = $return[0]->unidade_id;
            $this->_unidade = $return[0]->unidade;
            $this->_r_especial = $return[0]->r_especial;
        } else {
            $this->_ambulatorio_modelo_medicamento_id = null;
        }
    }

}

?>
