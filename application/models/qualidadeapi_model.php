<?php

class qualidadeapi_model extends Model {

    function Qualidadeapi_model($ambulatorio_pacientetemp_id = null) {
        parent::Model();
//        $this->load->library('utilitario');
    }

    function getLaudos($data) {
        $medico_id = $data->medico_id;
        $date = $data->date;
        $this->db->select('age.agenda_exames_id,
                            ag.texto as laudo,
                            ag.data,
                            age.paciente_id,
                            p.nome as paciente,
                            p.cpf,
                            p.nascimento,
                            p.cns as email_paciente,
                            o.email as email_medico,
                            o.nome as medico,
                            pt.nome as procedimento,
                            pt.codigo,
                            pc.procedimento_convenio_id,
                            ag.texto as descricao,
                            o.nome as medico');
        $this->db->from('tb_ambulatorio_laudo ag');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_exames ae', 'ae.exames_id = ag.exame_id', 'left');
        $this->db->join('tb_agenda_exames age', 'age.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ag.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_ambulatorio_grupo agr', 'agr.nome = pt.grupo', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        // $this->db->where('age.paciente_id', $paciente_id);
        // $this->db->where('age.agenda_exames_id !=', $agenda_exames_id);
        if($medico_id > 0){
            $this->db->where('ag.medico_parecer1', $medico_id);
        }
        if($date != ''){
            $this->db->where('ag.data', $date);
        }

        $this->db->where("ag.cancelada", 'false');
        $this->db->orderby('ag.data_cadastro desc');
        $this->db->orderby('ag.situacao');
        $this->db->orderby('ag.data');
        $this->db->limit(10);
        $return = $this->db->get()->result();
        return $return;
    }

}

?>
