<?php

require_once APPPATH . 'models/base/BaseModel.php';

//require_once APPPATH . 'models/base/ConvertXml.php';



class empresa_model extends BaseModel {

    var $_client_id = null;
    var $_client_secret = null;
    var $_client_sandbox = null;
    var $_valor_consulta_app = null;

    function Empresa_model($empresa = null) {
        parent::Model();
        if (isset($empresa)) {
            $this->instanciar($empresa);
        }
    }

    function gravargerencianet($empresa){

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        $this->db->set('client_id', $_POST['client_id']);
        $this->db->set('client_secret', $_POST['client_secret']);
        $this->db->set('client_sandbox', $_POST['client_sandbox']);
        $this->db->set('valor_consulta_app', $_POST['valor_consulta_app']);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->set('data_atualizacao', $horario);
        $this->db->where('empresa_id', $empresa);
        $this->db->update('tb_empresa');

    }


    private function instanciar($empresa) {
        if ($empresa != 0) {
            $this->db->select('client_id, client_secret, client_sandbox, valor_consulta_app');
            $this->db->from('tb_empresa');
            $this->db->where('empresa_id', $empresa);
            $query = $this->db->get();
            $return = $query->result();

            $this->_client_id = $return[0]->client_id;
            $this->_client_secret = $return[0]->client_secret;
            $this->_client_sandbox = $return[0]->client_sandbox;
            $this->_valor_consulta_app = $return[0]->valor_consulta_app;
        }
    }


}