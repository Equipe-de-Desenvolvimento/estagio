<?php

class tuotempo_model extends Model {

    function Tuotempo_model($ambulatorio_pacientetemp_id = null) {
        parent::Model();
//        $this->load->library('utilitario');
    }

    function listarAgendamentosPaciente($search) {
        $data_inicio = '';
        $data_fim = '';
        if($search->START_DATE != ''){
            $data_inicio = date("Y-m-d", strtotime($search->START_DATE));
        }
        if($search->END_DATE != ''){
            $data_fim = date("Y-m-d", strtotime($search->END_DATE));
        }
        $paciente_id = $search->USER_LID;
        $agenda_exames_id = $search->APP_LID;
        $medico_id = $search->RESOURCE_LID;
        
        $this->db->select('ae.medico_agenda as medico_id,
                            ae.agenda_exames_id,
                            ae.confirmado,
                            ae.paciente_id,
                            p.nome as paciente,
                            p.nascimento,
                            p.sexo,
                            p.cep,
                            p.celular,
                            p.cpf,
                            p.cns as email,
                            pt.nome as procedimento,
                            pc.procedimento_convenio_id,
                            ag.nome as grupo,
                            ag.ambulatorio_grupo_id,
                            o.nome as medico,
                            ae.realizada,
                            ae.data_autorizacao,
                            ae.data,
                            ae.inicio,
                            ae.fim');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id= pc.convenio_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ae.medico_agenda', 'left');
        $this->db->join('tb_ambulatorio_grupo ag', 'ag.nome = pt.grupo', 'left');
        $this->db->where("ae.data is not null");
        $this->db->where('ae.bloqueado', 'f');
        if($agenda_exames_id > 0){
            $this->db->where("ae.agenda_exames_id", $agenda_exames_id);
        }else{
            if($data_inicio != ''){
                $this->db->where("ae.data >=", $data_inicio);
            }
            if($data_fim != ''){
                $this->db->where("ae.data <=", $data_fim);
            }
            if ($paciente_id > 0) {
                $this->db->where("ae.paciente_id", $paciente_id);
            }
            if ($medico_id > 0) {
                $this->db->where("ae.medico_agenda", $medico_id);
            }
        }
        $this->db->orderby("ae.data, ae.situacao");
        $return = $this->db->get();
        return $return->result();
    }

    function listarAgendamento($search) {
        $data_inicio = '';
        $data_fim = '';
        $inicio = '';
        $fim = '';
        $tempo_minimo = '';
        $tempo_maximo = '';
        if($search->AVA_START_DAY != ''){
            $data_inicio = date("Y-m-d", strtotime($search->AVA_START_DAY));
        }
        if($search->AVA_END_DAY != ''){
            $data_fim = date("Y-m-d", strtotime($search->AVA_END_DAY));
        }
        // var_dump($data_fim); die;
        $agenda_exames_id = 0;
        if(isset($search->AVAILABILITY_LID)){
          $agenda_exames_id = $search->AVAILABILITY_LID;
        }
        $empresa_id = $search->LOCATION_LID;
        $convenio_id = $search->INSURANCE_LID;
        $medico_id = $search->RESOURCE_LID;
        $procedimento_tuss_id = $search->ACTIVITY_LID;
        if($search->AVA_START_TIME != ''){
            $inicio = date("H:i:s",strtotime($search->AVA_START_TIME));
        }
        if($search->AVA_END_TIME != ''){
            $fim = date("H:i:s",strtotime($search->AVA_END_TIME));
        }
        if($search->AVA_MIN_TIME != ''){
            $tempo_minimo = date("H:i:s",strtotime($search->AVA_MIN_TIME));
        }
        if($search->AVA_MAX_TIME != ''){
            $tempo_maximo = date("H:i:s",strtotime($search->AVA_MAX_TIME));
        }
        // var_dump($tempo_maximo); die;
        $maximoResult = $search->AVA_RESULTS_NUMBER;
        if(!$maximoResult > 0){
            $maximoResult = 1000;
        } 
        
         if ($convenio_id > 0 && $procedimento_tuss_id > 0) {
            $sql = "(
                       SELECT c2.convenio_id FROM ponto.tb_convenio_operador_procedimento cop
                       INNER JOIN ponto.tb_procedimento_convenio pc2 ON pc2.procedimento_convenio_id = cop.procedimento_convenio_id
                       INNER JOIN ponto.tb_convenio c2 ON c2.convenio_id = pc2.convenio_id
                       INNER JOIN ponto.tb_procedimento_tuss pt2 ON pt2.procedimento_tuss_id = pc2.procedimento_tuss_id
                       WHERE c2.convenio_id = {$convenio_id}
                       AND  cop.operador = ae.medico_agenda
                       AND  pc2.procedimento_convenio_id= {$procedimento_tuss_id}
                       AND cop.ativo = 't'  limit 1
                   )  as convenio_id,
                   (
                       SELECT pc2.procedimento_convenio_id FROM ponto.tb_convenio_operador_procedimento cop
                       INNER JOIN ponto.tb_procedimento_convenio pc2 ON pc2.procedimento_convenio_id = cop.procedimento_convenio_id
                       INNER JOIN ponto.tb_convenio c2 ON c2.convenio_id = pc2.convenio_id
                       INNER JOIN ponto.tb_procedimento_tuss pt2 ON pt2.procedimento_tuss_id = pc2.procedimento_tuss_id
                       WHERE c2.convenio_id = {$convenio_id}
                       AND  cop.operador = ae.medico_agenda
                       AND  pc2.procedimento_convenio_id= {$procedimento_tuss_id}
                       AND cop.ativo = 't'  limit 1
                   )  as procedimento_convenio_id
                   ";   
        }else{
           
            $sql = "";
        }
        
        
        $this->db->select("ae.medico_agenda as medico_id,
                            ae.agenda_exames_id,
                            ae.procedimento_tuss_id,
                            ae.data,
                            ae.inicio,
                            ae.fim,
                            ae.empresa_id,
                            {$sql}");
        $this->db->from('tb_agenda_exames ae');
        $this->db->where("ae.data is not null");
        $this->db->where('ae.bloqueado', 'f');
        $this->db->where('ae.paciente_id is null');
        if($agenda_exames_id > 0){
            $this->db->where("ae.agenda_exames_id", $agenda_exames_id);
        }else{
            if($data_inicio != ''){
                $this->db->where("ae.data >=", $data_inicio);
            }
            if($data_fim != ''){
                $this->db->where("ae.data <=", $data_fim);
            }
            if($tempo_minimo != ''){
                $this->db->where("ae.inicio >=", $tempo_minimo);
            }
            if($tempo_maximo != ''){
                $this->db->where("ae.inicio <=", $tempo_maximo);
            }
            if($inicio != ''){
                $this->db->where("TO_TIMESTAMP(to_char(ae.data + ae.inicio, 'yyyy-mm-dd HH:MI:SS'), 'yyyy-mm-dd HH:MI:SS') >=", $data_inicio . ' ' .  $inicio);
            }
            if($fim != ''){
                $this->db->where("TO_TIMESTAMP(to_char(ae.data + ae.inicio, 'yyyy-mm-dd HH:MI:SS'), 'yyyy-mm-dd HH:MI:SS') <=", $data_fim . ' ' .  $fim);
            }
            if ($empresa_id > 0) {
                $this->db->where("ae.empresa_id", $empresa_id);
            }
           
            if ($medico_id > 0 && $convenio_id > 0 && $procedimento_tuss_id > 0) { 
                    $this->db->where("ae.medico_agenda IN (
                       SELECT cop.operador FROM ponto.tb_convenio_operador_procedimento cop
                       INNER JOIN ponto.tb_procedimento_convenio pc2 ON pc2.procedimento_convenio_id = cop.procedimento_convenio_id
                       INNER JOIN ponto.tb_convenio c2 ON c2.convenio_id = pc2.convenio_id
                       INNER JOIN ponto.tb_procedimento_tuss pt2 ON pt2.procedimento_tuss_id = pc2.procedimento_tuss_id
                       WHERE c2.convenio_id = {$convenio_id}
                       AND  cop.operador = {$medico_id}
                       AND  pc2.procedimento_convenio_id= {$procedimento_tuss_id}
                       AND cop.ativo = 't'
                   )");
                                    
            }elseif($medico_id <= 0 && $convenio_id > 0 && $procedimento_tuss_id > 0){ 
                $this->db->where("ae.medico_agenda IN (
                      SELECT cop.operador FROM ponto.tb_convenio_operador_procedimento cop
                      INNER JOIN ponto.tb_procedimento_convenio pc2 ON pc2.procedimento_convenio_id = cop.procedimento_convenio_id
                      INNER JOIN ponto.tb_convenio c2 ON c2.convenio_id = pc2.convenio_id
                      INNER JOIN ponto.tb_procedimento_tuss pt2 ON pt2.procedimento_tuss_id = pc2.procedimento_tuss_id
                      WHERE c2.convenio_id = {$convenio_id}
                      AND  pc2.procedimento_convenio_id = {$procedimento_tuss_id}
                      AND cop.ativo = 't'
                  )");             
            }elseif($medico_id > 0){ 
                $this->db->where("ae.medico_agenda", $medico_id); 
                
            }
           
           
    
        }
        
        $this->db->orderby("ae.data, ae.inicio");


        $this->db->limit($maximoResult);

        $return = $this->db->get();
        return $return->result();
    }

    function gravarPacienteApp($data){
        $horario = date("Y-m-d H:i:s");
        $this->db->select('paciente_id, nome');
        $this->db->from('tb_paciente');
        // $this->db->where('nome', $json_post->nome);
        $this->db->where('cpf', str_replace(".", "", str_replace("-", "", $data->USER_ID_NUMBER)));
        $this->db->where('ativo', 't');
        $this->db->orderby('paciente_id');
        $contadorPaciente = $this->db->get()->result();
        if(count($contadorPaciente)  > 0){
            $paciente_id = $contadorPaciente[0]->paciente_id;
            return $paciente_id;
        }
        $this->db->set('nome', $data->USER_FIRST_NAME . ' ' . $data->USER_SECOND_NAME . ' ' . $data->USER_THIRD_NAME);
        $this->db->set('cns', $data->USER_EMAIL);
        if($data->USER_GENDER != ''){
            $this->db->set('sexo', $data->USER_GENDER);
        }
        if($data->USER_DATE_OF_BIRTH != ''){
            $this->db->set('nascimento', date("Y-m-d", strtotime(str_replace('/', '-', $data->USER_DATE_OF_BIRTH))) );
        }
        $this->db->set('cpf', str_replace(".", "", str_replace("-", "", $data->USER_ID_NUMBER)));
        $this->db->set('telefone', str_replace("(", "", str_replace(")", "", str_replace("-", "", $data->USER_LANDLINE_PHONE))));
        $this->db->set('celular', str_replace("(", "", str_replace(")", "", str_replace("-", "", $data->USER_MOBILE_PHONE))));
        $this->db->set('whatsapp', str_replace("(", "", str_replace(")", "", str_replace("-", "", $data->USER_MOBILE_PHONE))));
        $this->db->set('data_cadastro', $horario);
        $this->db->set('operador_cadastro', 1);
        $this->db->insert('tb_paciente');
        $paciente_id =  $this->db->insert_id();
        return $paciente_id;
    }

    function agendarPaciente($data){
        $procedimento_convenio_id = $data->ACTIVITY_LID;
        $agenda_exames_id = $data->AVAILABILITY_LID;
        $paciente_id = $data->USER_LID;

        $this->db->select('ae.agenda_exames_id');
        $this->db->from('tb_agenda_exames ae');
        $this->db->where('agenda_exames_id', $agenda_exames_id);
        $this->db->where('paciente_id is null');
        $return = $this->db->get()->result();
         // var_dump($return); die;
        if(!count($return) > 0){
            return array();
        }
        $this->db->set('tuotempo', 'INTEGRADO');
        $this->db->set('paciente_id', $paciente_id);
        $this->db->set('procedimento_tuss_id', $procedimento_convenio_id);
        $this->db->set('ativo', 'f');
        $this->db->set('cancelada', 'f');
        $this->db->set('confirmado', 'f');
        $this->db->set('situacao', 'OK');
        $horario = date("Y-m-d H:i:s");
        // $operador_id = $this->session->userdata('operador_id');
        $this->db->set('data_atualizacao', $horario);
        // $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('agenda_exames_id', $agenda_exames_id);
        $this->db->update('tb_agenda_exames');

        $this->db->select('ae.agenda_exames_id as reservation_id, ae.paciente_id as patient_id');
        $this->db->from('tb_agenda_exames ae');
        $this->db->where('agenda_exames_id', $agenda_exames_id);
        // $this->db->where('paciente_id is null');
        $return = $this->db->get()->result();
       
        return $return;
    }

    function pagarAgendamento($data){
        $agenda_exames_id = $data->APP_LID;
        $horario = date("Y-m-d H:i:s");

        $this->db->select('ae.agenda_exames_id, 
                           ae.procedimento_tuss_id as procedimento_convenio_id,
                           ae.valor_total,
                           ae.guia_id,
                           ae.data,
                           ');
        $this->db->from('tb_agenda_exames ae');
        $this->db->where('agenda_exames_id', $agenda_exames_id);
        $this->db->where('paciente_id is not null');
        $return = $this->db->get()->result();
         // var_dump($return); die;
        if(!count($return) > 0){
            return array();
        }
        $this->db->select('fp.forma_pagamento_id');
        $this->db->from('tb_forma_pagamento fp');
        $this->db->where('tuo_tempo', 't');
        $this->db->where('ativo', 't');
        $return_FP = $this->db->get()->result();
         // var_dump($return); die;
        if(!count($return_FP) > 0){ 
            $this->db->set('nome', 'TuoTempo');
            $this->db->set('tuo_tempo', 't');
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', 1);
            $this->db->insert('tb_forma_pagamento');
            $forma_pagamento_id = $this->db->insert_id();
        }else{
            $forma_pagamento_id = $return_FP[0]->forma_pagamento_id;
        }
       
        if($data->ACTION == 'ADD'){
            
            if($forma_pagamento_id > 0){
                $this->db->set('forma_pagamento_id', $forma_pagamento_id);
            }
            $this->db->set('parcela', 1);
            $this->db->set('guia_id', $return[0]->guia_id);
            $this->db->set('agenda_exames_id', $agenda_exames_id);
            $this->db->set('procedimento_convenio_id', $return[0]->procedimento_convenio_id);
            $this->db->set('desconto', 0);
            $this->db->set('ajuste', 0);
            $this->db->set('valor_total', $data->AMOUNT);
            $this->db->set('valor_bruto', $data->AMOUNT);
            $this->db->set('data', $return[0]->data);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', 1);
            $this->db->set('faturado', 't');
            $this->db->set('observacao', "TuoTempo");
            $this->db->insert('tb_agenda_exames_faturar');

            $this->db->set('forma_pagamento', $forma_pagamento_id);
            $this->db->set('valor1', (float) $data->AMOUNT);
            $this->db->set('faturado', 't');

        } elseif($data->ACTION == 'CANCEL'){

            $this->gravarProcedimentosAgendaExamesBackupFaturar($agenda_exames_id);
            $this->db->where('agenda_exames_id', $agenda_exames_id);
            $this->db->delete('tb_agenda_exames_faturar');

            $this->db->set('forma_pagamento', null);
            $this->db->set('valor1', (float) 0);
            $this->db->set('faturado', 't');
        }
        // var_dump($forma_pagamento_id); die;
        
        // $operador_id = $this->session->userdata('operador_id');
        $this->db->set('data_atualizacao', $horario);
        // $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('agenda_exames_id', $agenda_exames_id);
        $this->db->update('tb_agenda_exames');

        $this->db->select('ae.agenda_exames_id as APP_LID, ae.paciente_id as patient_id');
        $this->db->from('tb_agenda_exames ae');
        $this->db->where('agenda_exames_id', $agenda_exames_id);
        // $this->db->where('paciente_id is null');
        $return = $this->db->get()->result();
       
        return $return;
    }

    function gravarProcedimentosAgendaExamesBackupFaturar($agenda_exames_id, $alteracao = 'EDITAR') {

        $this->db->select('*');
        $this->db->from('tb_agenda_exames_faturar');
        $this->db->where('agenda_exames_id', $agenda_exames_id);
        $return = $this->db->get();
        $result = $return->result();

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        foreach ($result as $item) {

            $this->db->set('agenda_exames_faturar_id', $item->agenda_exames_faturar_id);
            $this->db->set('json_salvar', json_encode($item));
            $this->db->set('alteracao', $alteracao);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_agenda_exames_faturar_bkp');
        }
    }

    function deletarAgendarPaciente($data){
        $agenda_exames_id = $data->APP_LID;

        $this->db->select('ae.agenda_exames_id, ae.paciente_id');
        $this->db->from('tb_agenda_exames ae');
        $this->db->where('agenda_exames_id', $agenda_exames_id);
        $this->db->where('realizada', 'f');
        $this->db->where('confirmado', 'f');
        $return = $this->db->get()->result();
        if(!count($return) > 0){
            return array();
        }
        $this->db->set('tuotempo', null);
        $this->db->set('paciente_id', null);
        $this->db->set('procedimento_tuss_id', null);
        $this->db->set('ativo', 't');
        $this->db->set('cancelada', 'f');
        $this->db->set('confirmado', 'f');
        $this->db->set('realizada', 'f');
        $this->db->set('situacao', 'LIVRE');
        $horario = date("Y-m-d H:i:s");
        // $operador_id = $this->session->userdata('operador_id');
        $this->db->set('data_atualizacao', $horario);
        // $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('agenda_exames_id', $agenda_exames_id);
        $this->db->update('tb_agenda_exames');

        return $return;
    }

    function listarPacientes($data) {
        
        $this->db->from('tb_paciente')
                ->join('tb_municipio', 'tb_municipio.municipio_id = tb_paciente.municipio_id', 'left')
                ->select('"tb_paciente".*, tb_municipio.nome as ciade, tb_municipio.estado')
                ->where('ativo', 'true');

        if ($data) {
            if (isset($data->USER_LID) && strlen($data->USER_LID) > 0) {
                $this->db->where('paciente_id', $data->USER_LID);
                $this->db->where('ativo', 'true');
            }
            if (isset($data->ID_NUMBER) && strlen($data->ID_NUMBER) > 0) {
                $this->db->where('tb_paciente.cpf',$data->ID_NUMBER);
                $this->db->where('ativo', 'true');
            }
            if (isset($data->GENDER) && strlen($data->GENDER) > 0) {
                $this->db->where('sexo', $data->GENDER);
                $this->db->where('ativo', 'true');
            }
            if (isset($data->FIRST_NAME) && strlen($data->FIRST_NAME) > 0) {
                $nome = $this->removerCaracterEsp($data->FIRST_NAME);
                // var_dump($nome); die;
                $this->db->where("translate(tb_paciente.nome,  
                'áàâãäåaaaÁÂÃÄÅAAAÀéèêëeeeeeEEEÉEEÈÊìíîïìiiiÌÍÎÏÌIIIóôõöoooòÒÓÔÕÖOOOùúûüuuuuÙÚÛÜUUUUçÇñÑýÝ',  
                'aaaaaaaaaAAAAAAAAAeeeeeeeeeEEEEEEEEiiiiiiiiIIIIIIIIooooooooOOOOOOOOuuuuuuuuUUUUUUUUcCnNyY'   
                 ) ilike", '%' . $nome . '%');
                $this->db->where('ativo', 'true');
            }
        
            if (isset($data->SECOND_NAME) && strlen($data->SECOND_NAME) > 0) {
                $nome = $this->removerCaracterEsp($data->SECOND_NAME);
                // var_dump($nome); die;
                $this->db->where("translate(tb_paciente.nome,  
                'áàâãäåaaaÁÂÃÄÅAAAÀéèêëeeeeeEEEÉEEÈÊìíîïìiiiÌÍÎÏÌIIIóôõöoooòÒÓÔÕÖOOOùúûüuuuuÙÚÛÜUUUUçÇñÑýÝ',  
                'aaaaaaaaaAAAAAAAAAeeeeeeeeeEEEEEEEEiiiiiiiiIIIIIIIIooooooooOOOOOOOOuuuuuuuuUUUUUUUUcCnNyY'   
                 ) ilike", '%' . $nome . '%');
                $this->db->where('ativo', 'true');
            }
        
            if (isset($data->BIRTHDAY_MIN) && strlen($data->BIRTHDAY_MIN) > 0) {
                $this->db->where('tb_paciente.nascimento >=', date("Y-m-d", strtotime(str_replace("/", "-", $data->BIRTHDAY_MIN))));
                $this->db->where('ativo', 'true');
            }
            if (isset($data->BIRTHDAY_MAX) && strlen($data->BIRTHDAY_MAX) > 0) {
                $this->db->where('tb_paciente.nascimento <=', date("Y-m-d", strtotime(str_replace("/", "-", $data->BIRTHDAY_MAX))));
                $this->db->where('ativo', 'true');
            }

            if (isset($data->EMAIL) && strlen($data->EMAIL) > 0) {
                $this->db->where('tb_paciente.cns', $data->EMAIL);
                $this->db->where('ativo', 'true');
            }
            if (isset($data->MOBILE_PHONE) && strlen($data->MOBILE_PHONE) > 0) {
                $this->db->where('tb_paciente.celular', $data->MOBILE_PHONE);
                $this->db->where('ativo', 'true');
            }
            // $this->db->limit(10);

        }
       

        $return = $this->db->get();
        return $return->result();
    }

    function removerCaracterEsp($string) {
        return preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/"), explode(" ", "a A e E i I o O u U n N"), $string);
    }

    function listarhorariosAPP($medico_id, $data, $empresa_id) {
        
        $this->db->select('ae.agenda_exames_id as id, 
                            ae.paciente_id, 
                            p.nome as paciente,
                            p.cpf as paciente_cpf,
                            p.nascimento as paciente_dt_nascimento,
			                p.telefone,
                            ae.data,
                            pt.nome as exame,
                            ae.inicio as hora_inicio,
                            ae.fim as hora_fim,
                            cha.nome as convenio,
                            ha.grupo,
                            ae.situacao');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_horarioagenda ha', 'ha.horarioagenda_id = ae.horario_id', 'left');
        $this->db->join('tb_convenio cha', 'cha.convenio_id = ha.convenio_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where("(ae.situacao = 'LIVRE' OR ae.situacao = 'OK')");
        $this->db->where("ae.data is not null");
        $this->db->where("ae.data", $data);
        $this->db->where('ae.bloqueado', 'f');
        if ($empresa_id > 0) {
            $this->db->where("ae.empresa_id", $empresa_id);
        }
        $this->db->where("ae.medico_agenda", $medico_id);
        // $this->db->where("cha.convenio_id is not null");
        $this->db->orderby("ae.inicio DESC");
        $return = $this->db->get();
        return $return->result();
    }

    function listarhistoricoAPP($agenda_exames_id, $paciente_id, $medico_id) {
        
        $this->db->select('ag.ambulatorio_laudo_id as laudo_id,
                            ag.data,
                            pt.nome as exame,
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
        $this->db->where('age.paciente_id', $paciente_id);
        $this->db->where('age.agenda_exames_id !=', $agenda_exames_id);
        if($medico_id > 0){
            $this->db->where('ag.medico_parecer1', $medico_id);
        }
        $this->db->where("ag.cancelada", 'false');
        $this->db->orderby('ag.data_cadastro desc');
        $this->db->orderby('ag.situacao');
        $this->db->orderby('ag.data');
        $return = $this->db->get()->result();
        return $return;
    }

    function listarAtendimentosPaciente($search) {
        $data_inicio = '';
        if($search->START_DATE != ''){
            $data_inicio = date("Y-m-d", strtotime($search->START_DATE));
        }
        
        $paciente_id = $search->USER_LID;
        // var_dump($paciente_id); die;
        $ambulatorio_laudo_id = $search->EPISODE_LID;
        
        $this->db->select('ag.ambulatorio_laudo_id as laudo_id,
                            ag.data,
                            pt.nome as exame,
                            ag.texto,
                            pt.nome as procedimento,
                            pt.descricao,
                            pt.grupo,
                            ae.paciente_id,
                            p.nome as paciente,
                            p.nascimento,
                            p.sexo,
                            p.cep,
                            p.celular,
                            p.cpf,
                            p.cns as email,
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
        if($paciente_id > 0){
            $this->db->where('p.paciente_id', $paciente_id);
        }
        if($ambulatorio_laudo_id > 0){
            $this->db->where('ag.ambulatorio_laudo_id', $ambulatorio_laudo_id);
        }
        if($data_inicio != ''){
            $this->db->where('ag.data >=', $data_inicio);
        }
        $this->db->where("ag.cancelada", 'false');
        $this->db->orderby('ag.data_cadastro desc');
        $this->db->orderby('ag.situacao');
        $this->db->orderby('ag.data');
        $return = $this->db->get()->result();
        return $return;
    }

    function listarLaudoPaciente($search) {
       
        // var_dump($paciente_id); die;
        $ambulatorio_laudo_id = 0;
        if($search->EPISODE_LID > 0){
            $ambulatorio_laudo_id = $search->EPISODE_LID;
        }
        if($search->DOCUMENT_LID > 0){
            $ambulatorio_laudo_id = $search->DOCUMENT_LID;
        }
        
        
        $this->db->select('ag.ambulatorio_laudo_id as laudo_id, pt.grupo, ag.data_cadastro');
        $this->db->from('tb_ambulatorio_laudo ag');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_exames ae', 'ae.exames_id = ag.exame_id', 'left');
        $this->db->join('tb_agenda_exames age', 'age.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ag.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_ambulatorio_grupo agr', 'agr.nome = pt.grupo', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        if($ambulatorio_laudo_id > 0){
            $this->db->where('ag.ambulatorio_laudo_id', $ambulatorio_laudo_id);
        }
        $this->db->where("ag.cancelada", 'false');
        $this->db->orderby('ag.data_cadastro desc');
        $this->db->orderby('ag.situacao');
        $this->db->orderby('ag.data');
        $return = $this->db->get()->result();
        return $return;
    }

    function listarhistoricoAPPEspec($paciente_id, $tipo) {
        
        $this->db->select('agr.nome as especialidade,
                           o.nome as medico,
                           agr.ambulatorio_laudo_id,
                           age.data
                            ');
        $this->db->from('tb_ambulatorio_laudo ag');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_exames ae', 'ae.exames_id = ag.exame_id', 'left');
        $this->db->join('tb_agenda_exames age', 'age.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ag.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_ambulatorio_grupo agr', 'agr.nome = pt.grupo', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->where('age.paciente_id', $paciente_id);
        $this->db->where('agr.tipo', $tipo);
        $this->db->orderby('ag.data_cadastro desc');
        $this->db->orderby('ag.situacao');
        $this->db->orderby('ag.data');
        $return = $this->db->get()->result();
        return $return;
    }

    function listarmedicos($empresa_id, $convenio_id) {
        $empresa_p_id = 1;
        $empresapermissoes = $this->listarempresapermissoes($empresa_p_id);
        $this->db->select('o.operador_id as id,
                               o.email,
                               o.telefone,
                               o.cpf,
                               o.nome,');
        $this->db->from('tb_operador o');
        $this->db->join('tb_operador_empresas oe', 'oe.operador_id = o.operador_id');
        $this->db->where("(consulta = 'true' OR (consulta = 'true' and medico_cirurgiao = 'true') )");
        $this->db->where('o.ativo', 'true');
        $this->db->where('oe.ativo', 'true');
        $this->db->where('oe.empresa_id', $empresa_id);
//        $this->db->where('o.medico_cirurgiao', 'false');
        
        $procedimento_excecao = $empresapermissoes[0]->procedimento_excecao;
        if($convenio_id > 0){
            if ($procedimento_excecao == "t") {
                $this->db->where("o.operador_id NOT IN (
                                    SELECT cop.operador FROM ponto.tb_convenio_operador_procedimento cop
                                    INNER JOIN ponto.tb_procedimento_convenio pc2 ON pc2.procedimento_convenio_id = cop.procedimento_convenio_id
                                    INNER JOIN ponto.tb_convenio c2 ON c2.convenio_id = pc2.convenio_id
                                    INNER JOIN ponto.tb_procedimento_tuss pt2 ON pt2.procedimento_tuss_id = pc2.procedimento_tuss_id
                                    WHERE c2.convenio_id = {$convenio_id}
                                    
                                    AND cop.ativo = 't'
                                )");
            } else {
                $this->db->where("o.operador_id IN (
                                    SELECT cop.operador FROM ponto.tb_convenio_operador_procedimento cop
                                    INNER JOIN ponto.tb_procedimento_convenio pc2 ON pc2.procedimento_convenio_id = cop.procedimento_convenio_id
                                    INNER JOIN ponto.tb_convenio c2 ON c2.convenio_id = pc2.convenio_id
                                    INNER JOIN ponto.tb_procedimento_tuss pt2 ON pt2.procedimento_tuss_id = pc2.procedimento_tuss_id
                                    WHERE c2.convenio_id = {$convenio_id}
                                
                                    AND cop.ativo = 't'
                                )");
            }
        }
        
        $this->db->groupby('o.operador_id,
                            o.email,
                            o.telefone,
                            o.cpf,
                            o.nome');
        $this->db->orderby('o.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function listarProcedimentoConvenioMedico($medico_id, $convenio_id){
        $empresa_id = 1;
        $empresapermissoes = $this->listarempresapermissoes($empresa_id);
        $this->db->select(' pc.procedimento_convenio_id,
                            pc.valortotal,
                            pt.codigo,
                            pt.grupo,
                            sub.nome as subgrupo,
                            pt.subgrupo_id,
                            ag.ambulatorio_grupo_id,
                            pt.nome as procedimento,
                            pc.empresa_id');
        $this->db->from('tb_procedimento_convenio pc');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_ambulatorio_subgrupo sub', 'sub.ambulatorio_subgrupo_id = pt.subgrupo_id', 'left');
        $this->db->join('tb_ambulatorio_grupo ag', 'ag.nome = pt.grupo');
//        $this->db->where("ag.tipo !=", 'CONSULTA');
//        $this->db->where("ag.tipo !=", 'ESPECIALIDADE');
        $this->db->where("ag.tipo !=", 'CIRURGICO');
        $this->db->where("pc.ativo", 't');
        $this->db->where("pt.ativo", 't');
        if ($convenio_id != null) {
            $this->db->where('pc.convenio_id', $convenio_id);
        }else{
            $this->db->where('c.nome', 'PARTICULAR');
        }
        if(!$medico_id > 0){
            $medico_id = 0;
        }
        if(!$convenio_id > 0){
            $convenio_id = 0;
            $convenio_string = "";
            $this->db->where('c.nome', 'PARTICULAR');
        }else{
            $convenio_string = "AND pc2.convenio_id = {$convenio_id}";
        }
        // WHERE cop.empresa_id = {$empresa_id} 
        // AND cop.operador = {$medico_id}
        $procedimento_excecao = $empresapermissoes[0]->procedimento_excecao;
        if($medico_id > 0){
            if ($procedimento_excecao == "t") {
                $this->db->where("pc.procedimento_convenio_id NOT IN (
                                SELECT cop.procedimento_convenio_id FROM ponto.tb_convenio_operador_procedimento cop
                                INNER JOIN ponto.tb_procedimento_convenio pc2 ON pc2.procedimento_convenio_id = cop.procedimento_convenio_id
                                INNER JOIN ponto.tb_convenio c2 ON c2.convenio_id = pc2.convenio_id
                                INNER JOIN ponto.tb_procedimento_tuss pt2 ON pt2.procedimento_tuss_id = pc2.procedimento_tuss_id
                                
                                WHERE cop.operador = {$medico_id}
                                $convenio_string
                                AND cop.ativo = 't'
                            )");
            } else {
                $this->db->where("pc.procedimento_convenio_id IN (
                                SELECT cop.procedimento_convenio_id FROM ponto.tb_convenio_operador_procedimento cop
                                INNER JOIN ponto.tb_procedimento_convenio pc2 ON pc2.procedimento_convenio_id = cop.procedimento_convenio_id
                                INNER JOIN ponto.tb_convenio c2 ON c2.convenio_id = pc2.convenio_id
                                INNER JOIN ponto.tb_procedimento_tuss pt2 ON pt2.procedimento_tuss_id = pc2.procedimento_tuss_id
                               
                                WHERE cop.operador = {$medico_id}
                                $convenio_string
                                AND cop.ativo = 't'
                            )");
            }
        } 
        $this->db->orderby("pt.nome");
        $return = $this->db->get();
        return $return->result();
    }

    function listarConvenioMedico($procedimento_id, $medico_id){
        $empresa_id = 1;
        $empresapermissoes = $this->listarempresapermissoes($empresa_id);
        $this->db->select('c.convenio_id,
                            c.nome as convenio');
        $this->db->from('tb_procedimento_convenio pc');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_ambulatorio_grupo ag', 'ag.nome = pt.grupo');
//        $this->db->where("ag.tipo !=", 'CONSULTA');
//        $this->db->where("ag.tipo !=", 'ESPECIALIDADE');
        $this->db->where("ag.tipo !=", 'CIRURGICO');
        $this->db->where("pc.ativo", 't');
        $this->db->where("c.ativo", 't');
        if($procedimento_id > 0){
            $this->db->where("pc.procedimento_convenio_id", $procedimento_id);
        }
        
        if(!$medico_id > 0){
            $medico_id = 0;
        }
        $procedimento_excecao = $empresapermissoes[0]->procedimento_excecao;
        if($medico_id > 0){
            if ($procedimento_excecao == "t") {
                $this->db->where("pc.procedimento_convenio_id NOT IN (
                                    SELECT cop.procedimento_convenio_id FROM ponto.tb_convenio_operador_procedimento cop
                                    INNER JOIN ponto.tb_procedimento_convenio pc2 ON pc2.procedimento_convenio_id = cop.procedimento_convenio_id
                                    INNER JOIN ponto.tb_convenio c2 ON c2.convenio_id = pc2.convenio_id
                                    INNER JOIN ponto.tb_procedimento_tuss pt2 ON pt2.procedimento_tuss_id = pc2.procedimento_tuss_id
                                    WHERE cop.operador = {$medico_id}
                                    AND cop.empresa_id = {$empresa_id}
                                    AND cop.ativo = 't'
                                )");
            } else {
                $this->db->where("pc.procedimento_convenio_id IN (
                                    SELECT cop.procedimento_convenio_id FROM ponto.tb_convenio_operador_procedimento cop
                                    INNER JOIN ponto.tb_procedimento_convenio pc2 ON pc2.procedimento_convenio_id = cop.procedimento_convenio_id
                                    INNER JOIN ponto.tb_convenio c2 ON c2.convenio_id = pc2.convenio_id
                                    INNER JOIN ponto.tb_procedimento_tuss pt2 ON pt2.procedimento_tuss_id = pc2.procedimento_tuss_id
                                    WHERE cop.operador = {$medico_id}
                                    AND cop.empresa_id = {$empresa_id}
                                    AND cop.ativo = 't'
                                )");
            }
        }
       

        
        // $empresa_id = $this->session->userdata('empresa_id');
        $procedimento_multiempresa = $this->session->userdata('procedimento_multiempresa');
        if ($procedimento_multiempresa == 't') {
            // $this->db->where('pc.empresa_id', $empresa_id);
        }

        $this->db->groupby("c.convenio_id, c.nome");
        $this->db->orderby("c.convenio_id, c.nome");
        $return = $this->db->get();
        return $return->result();
    }

    function listarempresapermissoes($empresa_id = null) {
        if ($empresa_id == null) {
            $empresa_id = $this->session->userdata('empresa_id');
        }
        $this->db->select('procedimento_excecao');
        $this->db->from('tb_empresa e');
        $this->db->where('e.empresa_id', $empresa_id);
        $this->db->join('tb_empresa_permissoes ep', 'ep.empresa_id = e.empresa_id', 'left');
        $this->db->orderby('e.empresa_id');
        $return = $this->db->get();
        return $return->result();
    }

    function listarEmpresas() {

        $this->db->select('empresa_id as id,
                            nome,
                            logradouro as endereco,
                            email,
                            telefone as telefone_01,
                            celular as telefone_02
                            ');
        $this->db->from('tb_empresa');
        $this->db->where("ativo", 't');
        $this->db->orderby('empresa_id');
        $return = $this->db->get();
        return $return->result();
    }

    function listarEmpresa($empresa_id) {

        $this->db->select('empresa_id as id,
                            e.nome,
                            e.logradouro as endereco,
                            e.cep,
                            e.email,
                            m.nome as municipio,
                            m.estado,
                            e.bairro,
                            e.telefone as telefone_01,
                            e.celular as telefone_02
                            ');
        $this->db->from('tb_empresa e');
        $this->db->join('tb_municipio m', 'm.municipio_id = e.municipio_id', 'left');
        $this->db->where("e.ativo", 't');
        if($empresa_id > 0){
            $this->db->where("e.empresa_id", $empresa_id);
        }
        $this->db->orderby('e.empresa_id');
        $return = $this->db->get();
        return $return->result();
    }
    
   function listarAgenda($agenda_exames_id){ 
       $this->db->select('*');
       $this->db->from('tb_agenda_exames');
       $this->db->where('agenda_exames_id',$agenda_exames_id);
       return $this->db->get()->result(); 
    }
    
   function listarprocedimentomedico($data){
       
       $agenda_exames_id = $data->AVAILABILITY_LID;
       $Agenda = $this->tuotempo->listarAgenda($agenda_exames_id);
       $medico_id = $Agenda[0]->medico_agenda;
       $empresa_id = $Agenda[0]->empresa_id;  
       $procedimento_convenio_id = $data->ACTIVITY_LID;
        
       $this->db->select('cop.procedimento_convenio_id');
       $this->db->from('tb_convenio_operador_procedimento cop');
       $this->db->join('tb_procedimento_convenio pc2','pc2.procedimento_convenio_id = cop.procedimento_convenio_id','left');
       $this->db->join('tb_convenio c2','c2.convenio_id = pc2.convenio_id','left');
       $this->db->join('tb_convenio_empresa ce2','ce2.convenio_id = c2.convenio_id','left');
       $this->db->join('tb_procedimento_tuss pt2','pt2.procedimento_tuss_id = pc2.procedimento_tuss_id','left');
       $this->db->where('pc2.procedimento_convenio_id',$procedimento_convenio_id);
       $this->db->where('cop.operador',$medico_id);
       $this->db->where('cop.empresa_id',$empresa_id);
       $this->db->where('cop.ativo','t');
       return $this->db->get()->result(); 
         
    }

}

?>
