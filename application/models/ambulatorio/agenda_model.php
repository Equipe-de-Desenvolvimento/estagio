<?php

class agenda_model extends Model {

    var $_agenda_id = null;
    var $_nome = null;
    var $_tipo = null;

    function Agenda_model($agenda_id = null) {
        parent::Model();
        if (isset($agenda_id)) {
            $this->instanciar($agenda_id);
        }
    }

    function listar($args = array()) {
        $this->db->select('agenda_id,
                            nome, tipo');
        $this->db->from('tb_agenda');
        $this->db->where('ativo', 'true');
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('nome ilike', $args['nome'] . "%");
        }
        return $this->db;
    }

    function listarferiados($args = array()) {
        $this->db->select('feriado_id,
                            nome, data');
        $this->db->from('tb_feriado');
        $this->db->where('ativo', 'true');
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('nome ilike', "%" . $args['nome'] . "%");
        }
        if (isset($args['data']) && strlen($args['data']) > 0) {
            $this->db->where('data', $args['data']);
        }
        return $this->db;
    }

    function listarferiadosagenda() {
        $this->db->select('data');
        $this->db->from('tb_feriado');
        $this->db->where('ativo', 'true');

        $return = $this->db->get();
        return $return->result();
    }

    function listarempresa($empresa_id = null) {
        $this->db->select('e.empresa_id,
                            ep.agenda_albert,
                            e.nome');
        $this->db->from('tb_empresa e');
        $this->db->join('tb_empresa_permissoes ep', 'ep.empresa_id = e.empresa_id', 'left');
        if($empresa_id > 0){
            $this->db->where('e.empresa_id', $empresa_id);
        }
        $this->db->where('e.ativo', 't');
        $query = $this->db->get();

        return $query->result();
    }

    function listartiposala() {
        $this->db->select('tipo');
        $this->db->from('tb_exame_sala_grupo esg');
        $this->db->join('tb_ambulatorio_grupo ag', 'ag.nome = esg.grupo', 'left');
        $this->db->where('esg.ativo', 't');
        $query = $this->db->get();

        return $query->result();
    }

    function listaragenda() {
        $this->db->select('agenda_id,
                            nome, 
                            tipo');
        $this->db->from('tb_agenda');
        $this->db->where('ativo', 'true');
        $this->db->orderby('nome');
        $return = $this->db->get();
        return $return->result();
    }

    function listarespecialidades() {
        $this->db->select('ambulatorio_grupo_id,
                            nome');
        $this->db->from('tb_ambulatorio_grupo');
        $return = $this->db->get();
        return $return->result();
    }

    function listarautocomplete($parametro = null) {
        $this->db->select('agenda_id,
                            nome');
        $this->db->from('tb_agenda');
        $this->db->where('ativo', 'true');
        if ($parametro != null) {
            $this->db->where('nome ilike', $parametro . "%");
        }
        $return = $this->db->get();
        return $return->result();
    }

    function excluirferiado($feriado_id) {

        $this->db->set('ativo', 'f');
        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('feriado_id', $feriado_id);
        $this->db->update('tb_feriado');
        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return false;
        else
            return true;
    }

    function excluir($agenda_id) {
//        var_dump($_POST); die;
        if ($_POST['excluir'] == 'on') {
            if ($_POST['txttipo'] != 'TODOS') {
                if ($_POST['txttipo'] == 'ESPECIALIDADE') {
                    $this->db->where("(tipo = 'ESPECIALIDADE' OR tipo = 'FISIOTERAPIA')");
                } else {
                    $this->db->where('tipo', $_POST['txttipo']);
                }
            }
            if ($_POST['txtmedico'] != 'TODOS') {
                $this->db->where('medico_agenda', $_POST['txtmedico']);
            }
            $this->db->where('data >=', date("Y-m-d", strtotime(str_replace("/", "-", $_POST['txtdatainicial']))));
            $this->db->where('data <=', date("Y-m-d", strtotime(str_replace("/", "-", $_POST['txtdatafinal']))));
            $this->db->where('inicio >=', $_POST['horainicio']);
            $this->db->where('inicio <=', $_POST['horafim']);
            $this->db->where('horarioagenda_id', $agenda_id);
            $this->db->where('paciente_id is null');
            $this->db->delete('tb_agenda_exames');
        }

        $this->db->set('ativo', 'f');
        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('agenda_id', $agenda_id);
        $this->db->update('tb_agenda');
        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return false;
        else
            return true;
    }

    function excluiragendascriadas() {
        $this->db->where('nome', $_GET['nome']);
        $this->db->where('medico_agenda', $_GET['medico_id']);
        $this->db->where('horarioagenda_id', $_GET['horario_id']);
        $this->db->where('paciente_id IS NULL');
        $this->db->delete('tb_agenda_exames');
    }

    function listarhorarioagendaeditadas($agenda_id) {
        $this->db->select('e.nome as empresa,
                           h.dia,
                           h.horaentrada1,
                           h.horasaida1,
                           h.intervaloinicio,
                           h.intervalofim,
                           h.tempoconsulta,
                           h.agenda_id,
                           h.qtdeconsulta,
                           h.empresa_id,
                           h.observacoes,
                           h.horarioagenda_editada_id,
                           h.sala_id,
                           h.empresa_id,
                           s.nome as sala');
        $this->db->from('tb_horarioagenda_editada h');
        $this->db->join('tb_empresa e', 'e.empresa_id = h.empresa_id', 'left');
        $this->db->join('tb_exame_sala s', 's.exame_sala_id = h.sala_id', 'left');
        $this->db->where("h.agenda_id", $agenda_id);
        $this->db->where("h.ativo", 't');
        $this->db->where("h.consolidado", 'f');
        $this->db->where("h.medico_id", $_GET['medico_id']);
        $this->db->where("h.nome", $_GET['nome']);
        $this->db->orderby('dia');
        $return = $this->db->get();
        return $return->result();
    }

    function listarhorariosagendacriadaconsolidados($agenda_id) {
        $this->db->select('e.nome as empresa,
                           h.dia,
                           h.horaentrada1,
                           h.horasaida1,
                           h.intervaloinicio,
                           h.intervalofim,
                           h.tempoconsulta,
                           h.agenda_id,
                           h.qtdeconsulta,
                           h.empresa_id,
                           h.observacoes,
                           h.horarioagenda_editada_id');
        $this->db->from('tb_horarioagenda_editada h');
        $this->db->join('tb_empresa e', 'e.empresa_id = h.empresa_id', 'left');
        $this->db->where("h.agenda_id", $agenda_id);
        $this->db->where("h.horarioagenda_editada_id IN (   
                                SELECT horarioagenda_editada_id 
                                FROM ponto.tb_agenda_exames
                                WHERE horarioagenda_id = '{$agenda_id}'
                                AND paciente_id IS NULL
                                AND agenda_editada = 't'
                                AND nome = '{$_GET['nome']}')");
        $this->db->orderby('dia');
        $return = $this->db->get();
        return $return->result();
    }

    function listarhorariosagendacriada($agenda_id) {
        $this->db->select('e.nome as empresa,
                           h.dia,
                           h.horaentrada1,
                           h.horasaida1,
                           h.intervaloinicio,
                           h.intervalofim,
                           h.tempoconsulta,
                           h.agenda_id,
                           h.qtdeconsulta,
                           h.empresa_id,
                           h.observacoes,
                           h.horarioagenda_id,
                           h.sala_id,
                           s.nome as sala');
        $this->db->from('tb_horarioagenda h');
        $this->db->join('tb_empresa e', 'e.empresa_id = h.empresa_id', 'left');
        $this->db->join('tb_exame_sala s', 's.exame_sala_id = h.sala_id', 'left');
        $this->db->where("h.agenda_id", $agenda_id);
        $this->db->where("h.horarioagenda_id IN (   
                                SELECT horario_id 
                                FROM ponto.tb_agenda_exames
                                WHERE horarioagenda_id = '{$agenda_id}'
                                AND paciente_id IS NULL
                                AND medico_agenda = '{$_GET['medico_id']}'
                                AND nome = '{$_GET['nome']}')");
        $this->db->orderby('dia');
        $return = $this->db->get();
        return $return->result();
    }

    function gravarsalahorarioagenda() {
        try {
            $sala_id = $_POST['sala_id'];
            $horario_id = $_POST['horario_id'];
            $this->db->set('sala_id', $sala_id);
            $this->db->where('horarioagenda_id', $horario_id);
            $this->db->update('tb_horarioagenda');
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarferiado() {
        try {

            /* inicia o mapeamento no banco */
            $feriado_id = $_POST['feriado_id'];
            $this->db->set('nome', $_POST['txtNome']);
            $this->db->set('data', $_POST['data']);

            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            if ($feriado_id == "") {// insert
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_feriado');
                $erro = $this->db->_error_message();
                if (trim($erro) != "") // erro de banco
                    return -1;
                else
                    $agenda_id = $this->db->insert_id();
            }
            else { // update
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $this->db->where('feriado_id', $feriado_id);
                $this->db->update('tb_feriado');
            }
            $data = date("Y-m-d");
            $data_post = $_POST['data'];

            $sql = "UPDATE ponto.tb_agenda_exames ae
            SET bloqueado = true, observacoes = 'Feriado'
            WHERE to_char(data, 'DD/MM') = '{$data_post}'
            AND data >= '$data'
            AND paciente_id is null;";
            $this->db->query($sql);


            $sql2 = "SELECT ae.agenda_exames_id 
            FROM ponto.tb_agenda_exames ae
            WHERE to_char(data, 'DD/MM') = '{$data_post}'
            AND data >= '$data'
            AND paciente_id is not null;";
            $return = $this->db->query($sql2)->result();


            if(count($return) > 0){
                return -1;
            }



            return $feriado_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravar() {
        try {

            /* inicia o mapeamento no banco */
            $agenda_id = $_POST['txtagendaID'];
            $this->db->set('nome', $_POST['txtNome']);
            $this->db->set('tipo', 'Fixo');

            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            if ($_POST['txthorariostipoID'] == "") {// insert
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_agenda');
                $erro = $this->db->_error_message();
                if (trim($erro) != "") // erro de banco
                    return -1;
                else
                    $agenda_id = $this->db->insert_id();
            }
            else { // update
                $agenda_id = $_POST['txthorariostipoID'];
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $this->db->where('agenda_id', $agenda_id);
                $this->db->update('tb_agenda');
            }

            return $agenda_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarAgendaData($agenda_id) {
        try {

            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            $this->db->set('datacon_inicio', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdatainicial']))));
            $this->db->set('datacon_fim', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdatafinal']))));
            $this->db->set('consolidada', 't');
            if ($_POST['txtintervalo'] > 0) {
                $this->db->set('intervalo', $_POST['txtintervalo']);
            }
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('agenda_id', $agenda_id);
            $this->db->update('tb_agenda');


            return $agenda_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarmodelo2() {
        try {

            /* inicia o mapeamento no banco */
            $agenda_id = $_POST['txtagendaID'];
            $this->db->set('nome', $_POST['txtNome']);
            $this->db->set('medico_id', $_POST['medico_id']);
            $this->db->set('tipo_agenda', $_POST['tipo_agenda']);
            $this->db->set('tipo', 'Fixo');

            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            if ($_POST['txthorariostipoID'] == "") {// insert
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_agenda');
                $erro = $this->db->_error_message();
                if (trim($erro) != "") // erro de banco
                    return -1;
                else
                    $agenda_id = $this->db->insert_id();
            }
            else { // update
                $agenda_id = $_POST['txthorariostipoID'];
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $this->db->where('agenda_id', $agenda_id);
                $this->db->update('tb_agenda');
            }

            return $agenda_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarmedico() {
        try {

            $datainicial = date("Y-m-d", strtotime(str_replace("/", "-", $_POST['datainicio'])));
            $datafinal = date("Y-m-d", strtotime(str_replace("/", "-", $_POST['datafim'])));

            if (isset($_POST['txtdomingo'])) {
                $Domingo = 'Domingo';
            } else {
                $Domingo = '';
            }
            if (isset($_POST['txtsegunda'])) {
                $Segunda = 'Segunda';
            } else {
                $Segunda = '';
            }
            if (isset($_POST['txtterca'])) {
                $Terça = 'Terça';
            } else {
                $Terça = '';
            }
            if (isset($_POST['txtquarta'])) {
                $Quarta = 'Quarta';
            } else {
                $Quarta = '';
            }
            if (isset($_POST['txtquinta'])) {
                $Quinta = 'Quinta';
            } else {
                $Quinta = '';
            }
            if (isset($_POST['txtsexta'])) {
                $Sexta = 'Sexta';
            } else {
                $Sexta = '';
            }
            if (isset($_POST['txtsabado'])) {
                $Sabado = 'Sabado';
            } else {
                $Sabado = '';
            }

            if ($_POST['txtacao'] == 'Alterarmedico') {

                for ($index = $datainicial; strtotime($index) <= strtotime($datafinal); $index = date('Y-m-d', strtotime("+1 days", strtotime($index)))) {

                    $data = strftime("%A", strtotime($index));

                    switch ($data) {
                        case"Sunday": $data = "Domingo";
                            break;
                        case"Monday": $data = "Segunda";
                            break;
                        case"Tuesday": $data = "Terça";
                            break;
                        case"Wednesday": $data = "Quarta";
                            break;
                        case"Thursday": $data = "Quinta";
                            break;
                        case"Friday": $data = "Sexta";
                            break;
                        case"Saturday": $data = "Sabado";
                            break;
                    }

                    if ($data == $Domingo) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('medico_agenda', $_POST['medico']);
                        $this->db->set('medico_consulta_id', $_POST['medico']);
                        $this->db->set('data_medico_agenda', $horario);
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'EXAME');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Segunda) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('medico_agenda', $_POST['medico']);
                        $this->db->set('data_medico_agenda', $horario);
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'EXAME');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Terça) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('medico_agenda', $_POST['medico']);
                        $this->db->set('data_medico_agenda', $horario);
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'EXAME');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Quarta) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('medico_agenda', $_POST['medico']);
                        $this->db->set('data_medico_agenda', $horario);
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'EXAME');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Quinta) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('medico_agenda', $_POST['medico']);
                        $this->db->set('data_medico_agenda', $horario);
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'EXAME');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Sexta) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('medico_agenda', $_POST['medico']);
                        $this->db->set('data_medico_agenda', $horario);
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'EXAME');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Sabado) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('medico_agenda', $_POST['medico']);
                        $this->db->set('data_medico_agenda', $horario);
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'EXAME');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                }
            }if ($_POST['txtacao'] == 'Bloquear') {


                for ($index = $datainicial; strtotime($index) <= strtotime($datafinal); $index = date('d-m-Y', strtotime("+1 days", strtotime($index)))) {

                    $data = strftime("%A", strtotime($index));

                    switch ($data) {
                        case"Sunday": $data = "Domingo";
                            break;
                        case"Monday": $data = "Segunda";
                            break;
                        case"Tuesday": $data = "Terça";
                            break;
                        case"Wednesday": $data = "Quarta";
                            break;
                        case"Thursday": $data = "Quinta";
                            break;
                        case"Friday": $data = "Sexta";
                            break;
                        case"Saturday": $data = "Sabado";
                            break;
                    }

                    if ($data == $Domingo) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('bloqueado', 't');
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'EXAME');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('paciente_id is null');
                        $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        $this->db->where('medico_agenda', $_POST['medico']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Segunda) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('bloqueado', 't');
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'EXAME');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('paciente_id is null');
                        $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        $this->db->where('medico_agenda', $_POST['medico']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Terça) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('bloqueado', 't');
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'EXAME');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('paciente_id is null');
                        $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        $this->db->where('medico_agenda', $_POST['medico']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Quarta) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('bloqueado', 't');
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'EXAME');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('paciente_id is null');
                        $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        $this->db->where('medico_agenda', $_POST['medico']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Quinta) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('bloqueado', 't');
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'EXAME');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('paciente_id is null');
                        $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        $this->db->where('medico_agenda', $_POST['medico']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Sexta) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('bloqueado', 't');
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'EXAME');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('paciente_id is null');
                        $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        $this->db->where('medico_agenda', $_POST['medico']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Sabado) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('bloqueado', 't');
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'EXAME');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('paciente_id is null');
                        $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        $this->db->where('medico_agenda', $_POST['medico']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                }
            }
            if ($_POST['txtacao'] == 'Excluir') {


                for ($index = $datainicial; strtotime($index) <= strtotime($datafinal); $index = date('d-m-Y', strtotime("+1 days", strtotime($index)))) {

                    $data = strftime("%A", strtotime($index));

                    switch ($data) {
                        case"Sunday": $data = "Domingo";
                            break;
                        case"Monday": $data = "Segunda";
                            break;
                        case"Tuesday": $data = "Terça";
                            break;
                        case"Wednesday": $data = "Quarta";
                            break;
                        case"Thursday": $data = "Quinta";
                            break;
                        case"Friday": $data = "Sexta";
                            break;
                        case"Saturday": $data = "Sabado";
                            break;
                    }

                    if ($data == $Domingo) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->where('paciente_id is null');
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'EXAME');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        $this->db->where('medico_agenda', $_POST['medico']);
                        $this->db->delete('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Segunda) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->where('paciente_id is null');
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'EXAME');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        $this->db->where('medico_agenda', $_POST['medico']);
                        $this->db->delete('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Terça) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->where('paciente_id is null');
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'EXAME');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        $this->db->where('medico_agenda', $_POST['medico']);
                        $this->db->delete('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Quarta) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->where('paciente_id is null');
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'EXAME');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        $this->db->where('medico_agenda', $_POST['medico']);
                        $this->db->delete('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Quinta) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->where('paciente_id is null');
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'EXAME');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        $this->db->where('medico_agenda', $_POST['medico']);
                        $this->db->delete('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Sexta) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->where('paciente_id is null');
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'EXAME');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        $this->db->where('medico_agenda', $_POST['medico']);
                        $this->db->delete('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Sabado) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->where('paciente_id is null');
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'EXAME');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        $this->db->where('medico_agenda', $_POST['medico']);
                        $this->db->delete('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                }
            }
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarmedicogeral() {
        try {

            $datainicial = date("Y-m-d", strtotime(str_replace("/", "-", $_POST['datainicio'])));
            $datafinal = date("Y-m-d", strtotime(str_replace("/", "-", $_POST['datafim'])));

            if (isset($_POST['txtdomingo'])) {
                $Domingo = 'Domingo';
            } else {
                $Domingo = '';
            }
            if (isset($_POST['txtsegunda'])) {
                $Segunda = 'Segunda';
            } else {
                $Segunda = '';
            }
            if (isset($_POST['txtterca'])) {
                $Terça = 'Terça';
            } else {
                $Terça = '';
            }
            if (isset($_POST['txtquarta'])) {
                $Quarta = 'Quarta';
            } else {
                $Quarta = '';
            }
            if (isset($_POST['txtquinta'])) {
                $Quinta = 'Quinta';
            } else {
                $Quinta = '';
            }
            if (isset($_POST['txtsexta'])) {
                $Sexta = 'Sexta';
            } else {
                $Sexta = '';
            }
            if (isset($_POST['txtsabado'])) {
                $Sabado = 'Sabado';
            } else {
                $Sabado = '';
            }

            if ($_POST['txtacao'] == 'Alterarmedico') {

                for ($index = $datainicial; strtotime($index) <= strtotime($datafinal); $index = date('d-m-Y', strtotime("+1 days", strtotime($index)))) {

                    $data = strftime("%A", strtotime($index));

                    switch ($data) {
                        case"Sunday": $data = "Domingo";
                            break;
                        case"Monday": $data = "Segunda";
                            break;
                        case"Tuesday": $data = "Terça";
                            break;
                        case"Wednesday": $data = "Quarta";
                            break;
                        case"Thursday": $data = "Quinta";
                            break;
                        case"Friday": $data = "Sexta";
                            break;
                        case"Saturday": $data = "Sabado";
                            break;
                    }

                    if ($data == $Domingo) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('medico_agenda', $_POST['medico']);
                        $this->db->set('data_medico_agenda', $horario);
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Segunda) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('medico_agenda', $_POST['medico']);
                        $this->db->set('data_medico_agenda', $horario);
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Terça) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('medico_agenda', $_POST['medico']);
                        $this->db->set('data_medico_agenda', $horario);
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Quarta) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('medico_agenda', $_POST['medico']);
                        $this->db->set('data_medico_agenda', $horario);
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Quinta) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('medico_agenda', $_POST['medico']);
                        $this->db->set('data_medico_agenda', $horario);
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Sexta) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('medico_agenda', $_POST['medico']);
                        $this->db->set('data_medico_agenda', $horario);
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Sabado) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('medico_agenda', $_POST['medico']);
                        $this->db->set('data_medico_agenda', $horario);
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                }
            }

            if ($_POST['txtacao'] == 'Bloquear') {

                for ($index = $datainicial; strtotime($index) <= strtotime($datafinal); $index = date('d-m-Y', strtotime("+1 days", strtotime($index)))) {

                    $data = strftime("%A", strtotime($index));

                    switch ($data) {
                        case"Sunday": $data = "Domingo";
                            break;
                        case"Monday": $data = "Segunda";
                            break;
                        case"Tuesday": $data = "Terça";
                            break;
                        case"Wednesday": $data = "Quarta";
                            break;
                        case"Thursday": $data = "Quinta";
                            break;
                        case"Friday": $data = "Sexta";
                            break;
                        case"Saturday": $data = "Sabado";
                            break;
                    }

                    if ($data == $Domingo) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('bloqueado', 't');
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('paciente_id is null');
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Segunda) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('bloqueado', 't');
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('paciente_id is null');
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Terça) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('bloqueado', 't');
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('paciente_id is null');
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Quarta) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('bloqueado', 't');
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('paciente_id is null');
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Quinta) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('bloqueado', 't');
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('paciente_id is null');
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Sexta) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('bloqueado', 't');
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('paciente_id is null');
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Sabado) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('bloqueado', 't');
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('paciente_id is null');
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                }
            }
            if ($_POST['txtacao'] == 'Excluir') {


                for ($index = $datainicial; strtotime($index) <= strtotime($datafinal); $index = date('d-m-Y', strtotime("+1 days", strtotime($index)))) {

                    $data = strftime("%A", strtotime($index));

                    switch ($data) {
                        case"Sunday": $data = "Domingo";
                            break;
                        case"Monday": $data = "Segunda";
                            break;
                        case"Tuesday": $data = "Terça";
                            break;
                        case"Wednesday": $data = "Quarta";
                            break;
                        case"Thursday": $data = "Quinta";
                            break;
                        case"Friday": $data = "Sexta";
                            break;
                        case"Saturday": $data = "Sabado";
                            break;
                    }

                    if ($data == $Domingo) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->where('paciente_id is null');
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->delete('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Segunda) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->where('paciente_id is null');
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->delete('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Terça) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->where('paciente_id is null');
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->delete('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Quarta) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->where('paciente_id is null');
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->delete('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Quinta) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->where('paciente_id is null');
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->delete('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Sexta) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->where('paciente_id is null');
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->delete('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Sabado) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->where('paciente_id is null');
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->delete('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                }
            }
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarmedicoconsulta() {
        try {

            $datainicial = date("Y-m-d", strtotime(str_replace("/", "-", $_POST['datainicio'])));
            $datafinal = date("Y-m-d", strtotime(str_replace("/", "-", $_POST['datafim'])));

            if (isset($_POST['txtdomingo'])) {
                $Domingo = 'Domingo';
            } else {
                $Domingo = '';
            }
            if (isset($_POST['txtsegunda'])) {
                $Segunda = 'Segunda';
            } else {
                $Segunda = '';
            }
            if (isset($_POST['txtterca'])) {
                $Terça = 'Terça';
            } else {
                $Terça = '';
            }
            if (isset($_POST['txtquarta'])) {
                $Quarta = 'Quarta';
            } else {
                $Quarta = '';
            }
            if (isset($_POST['txtquinta'])) {
                $Quinta = 'Quinta';
            } else {
                $Quinta = '';
            }
            if (isset($_POST['txtsexta'])) {
                $Sexta = 'Sexta';
            } else {
                $Sexta = '';
            }
            if (isset($_POST['txtsabado'])) {
                $Sabado = 'Sabado';
            } else {
                $Sabado = '';
            }

            if ($_POST['txtacao'] == 'Alterarmedico') {

                for ($index = $datainicial; strtotime($index) <= strtotime($datafinal); $index = date('d-m-Y', strtotime("+1 days", strtotime($index)))) {

                    $data = strftime("%A", strtotime($index));

                    switch ($data) {
                        case"Sunday": $data = "Domingo";
                            break;
                        case"Monday": $data = "Segunda";
                            break;
                        case"Tuesday": $data = "Terça";
                            break;
                        case"Wednesday": $data = "Quarta";
                            break;
                        case"Thursday": $data = "Quinta";
                            break;
                        case"Friday": $data = "Sexta";
                            break;
                        case"Saturday": $data = "Sabado";
                            break;
                    }

                    if ($data == $Domingo) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('medico_agenda', $_POST['medico']);
                        $this->db->set('data_medico_agenda', $horario);
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'CONSULTA');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        if ($_POST['sala'] != "") {
                            $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        }
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Segunda) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('medico_agenda', $_POST['medico']);
                        $this->db->set('data_medico_agenda', $horario);
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'CONSULTA');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        if ($_POST['sala'] != "") {
                            $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        }
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Terça) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('medico_agenda', $_POST['medico']);
                        $this->db->set('data_medico_agenda', $horario);
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'CONSULTA');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        if ($_POST['sala'] != "") {
                            $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        }
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Quarta) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('medico_agenda', $_POST['medico']);
                        $this->db->set('data_medico_agenda', $horario);
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'CONSULTA');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        if ($_POST['sala'] != "") {
                            $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        }
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Quinta) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('medico_agenda', $_POST['medico']);
                        $this->db->set('data_medico_agenda', $horario);
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'CONSULTA');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        if ($_POST['sala'] != "") {
                            $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        }
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Sexta) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('medico_agenda', $_POST['medico']);
                        $this->db->set('data_medico_agenda', $horario);
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'CONSULTA');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        if ($_POST['sala'] != "") {
                            $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        }
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Sabado) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('medico_agenda', $_POST['medico']);
                        $this->db->set('data_medico_agenda', $horario);
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'CONSULTA');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        if ($_POST['sala'] != "") {
                            $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        }
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                }
            }

            if ($_POST['txtacao'] == 'Bloquear') {

                for ($index = $datainicial; strtotime($index) <= strtotime($datafinal); $index = date('d-m-Y', strtotime("+1 days", strtotime($index)))) {

                    $data = strftime("%A", strtotime($index));

                    switch ($data) {
                        case"Sunday": $data = "Domingo";
                            break;
                        case"Monday": $data = "Segunda";
                            break;
                        case"Tuesday": $data = "Terça";
                            break;
                        case"Wednesday": $data = "Quarta";
                            break;
                        case"Thursday": $data = "Quinta";
                            break;
                        case"Friday": $data = "Sexta";
                            break;
                        case"Saturday": $data = "Sabado";
                            break;
                    }

                    if ($data == $Domingo) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('bloqueado', 't');
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'CONSULTA');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('paciente_id is null');
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Segunda) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('bloqueado', 't');
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'CONSULTA');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('paciente_id is null');
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Terça) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('bloqueado', 't');
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'CONSULTA');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('paciente_id is null');
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Quarta) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('bloqueado', 't');
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'CONSULTA');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('paciente_id is null');
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Quinta) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('bloqueado', 't');
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'CONSULTA');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('paciente_id is null');
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Sexta) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('bloqueado', 't');
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'CONSULTA');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('paciente_id is null');
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Sabado) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('bloqueado', 't');
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'CONSULTA');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('paciente_id is null');
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                }
            }
            if ($_POST['txtacao'] == 'Excluir') {


                for ($index = $datainicial; strtotime($index) <= strtotime($datafinal); $index = date('d-m-Y', strtotime("+1 days", strtotime($index)))) {

                    $data = strftime("%A", strtotime($index));

                    switch ($data) {
                        case"Sunday": $data = "Domingo";
                            break;
                        case"Monday": $data = "Segunda";
                            break;
                        case"Tuesday": $data = "Terça";
                            break;
                        case"Wednesday": $data = "Quarta";
                            break;
                        case"Thursday": $data = "Quinta";
                            break;
                        case"Friday": $data = "Sexta";
                            break;
                        case"Saturday": $data = "Sabado";
                            break;
                    }

                    if ($data == $Domingo) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->where('paciente_id is null');
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'CONSULTA');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->delete('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Segunda) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->where('paciente_id is null');
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'CONSULTA');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->delete('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Terça) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->where('paciente_id is null');
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'CONSULTA');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->delete('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Quarta) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->where('paciente_id is null');
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'CONSULTA');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->delete('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Quinta) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->where('paciente_id is null');
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'CONSULTA');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->delete('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Sexta) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->where('paciente_id is null');
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'CONSULTA');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->delete('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Sabado) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->where('paciente_id is null');
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where('tipo', 'CONSULTA');
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->delete('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                }
            }
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarmedicoespecialidade() {
        try {

            $datainicial = date("Y-m-d", strtotime(str_replace("/", "-", $_POST['datainicio'])));
            $datafinal = date("Y-m-d", strtotime(str_replace("/", "-", $_POST['datafim'])));

            if (isset($_POST['txtdomingo'])) {
                $Domingo = 'Domingo';
            } else {
                $Domingo = '';
            }
            if (isset($_POST['txtsegunda'])) {
                $Segunda = 'Segunda';
            } else {
                $Segunda = '';
            }
            if (isset($_POST['txtterca'])) {
                $Terça = 'Terça';
            } else {
                $Terça = '';
            }
            if (isset($_POST['txtquarta'])) {
                $Quarta = 'Quarta';
            } else {
                $Quarta = '';
            }
            if (isset($_POST['txtquinta'])) {
                $Quinta = 'Quinta';
            } else {
                $Quinta = '';
            }
            if (isset($_POST['txtsexta'])) {
                $Sexta = 'Sexta';
            } else {
                $Sexta = '';
            }
            if (isset($_POST['txtsabado'])) {
                $Sabado = 'Sabado';
            } else {
                $Sabado = '';
            }

            if ($_POST['txtacao'] == 'Alterarmedico') {

                for ($index = $datainicial; strtotime($index) <= strtotime($datafinal); $index = date('d-m-Y', strtotime("+1 days", strtotime($index)))) {

                    $data = strftime("%A", strtotime($index));

                    switch ($data) {
                        case"Sunday": $data = "Domingo";
                            break;
                        case"Monday": $data = "Segunda";
                            break;
                        case"Tuesday": $data = "Terça";
                            break;
                        case"Wednesday": $data = "Quarta";
                            break;
                        case"Thursday": $data = "Quinta";
                            break;
                        case"Friday": $data = "Sexta";
                            break;
                        case"Saturday": $data = "Sabado";
                            break;
                    }

                    if ($data == $Domingo) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('medico_agenda', $_POST['medico']);
                        $this->db->set('data_medico_agenda', $horario);
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where("(tipo = 'FISIOTERAPIA' OR tipo = 'ESPECIALIDADE')");
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Segunda) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('medico_agenda', $_POST['medico']);
                        $this->db->set('data_medico_agenda', $horario);
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where("(tipo = 'FISIOTERAPIA' OR tipo = 'ESPECIALIDADE')");
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Terça) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('medico_agenda', $_POST['medico']);
                        $this->db->set('data_medico_agenda', $horario);
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where("(tipo = 'FISIOTERAPIA' OR tipo = 'ESPECIALIDADE')");
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Quarta) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('medico_agenda', $_POST['medico']);
                        $this->db->set('data_medico_agenda', $horario);
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where("(tipo = 'FISIOTERAPIA' OR tipo = 'ESPECIALIDADE')");
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Quinta) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('medico_agenda', $_POST['medico']);
                        $this->db->set('data_medico_agenda', $horario);
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where("(tipo = 'FISIOTERAPIA' OR tipo = 'ESPECIALIDADE')");
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Sexta) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('medico_agenda', $_POST['medico']);
                        $this->db->set('data_medico_agenda', $horario);
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where("(tipo = 'FISIOTERAPIA' OR tipo = 'ESPECIALIDADE')");
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Sabado) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('medico_agenda', $_POST['medico']);
                        $this->db->set('data_medico_agenda', $horario);
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where("(tipo = 'FISIOTERAPIA' OR tipo = 'ESPECIALIDADE')");
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('agenda_exames_nome_id', $_POST['sala']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                }
            }

            if ($_POST['txtacao'] == 'Bloquear') {

                for ($index = $datainicial; strtotime($index) <= strtotime($datafinal); $index = date('d-m-Y', strtotime("+1 days", strtotime($index)))) {

                    $data = strftime("%A", strtotime($index));

                    switch ($data) {
                        case"Sunday": $data = "Domingo";
                            break;
                        case"Monday": $data = "Segunda";
                            break;
                        case"Tuesday": $data = "Terça";
                            break;
                        case"Wednesday": $data = "Quarta";
                            break;
                        case"Thursday": $data = "Quinta";
                            break;
                        case"Friday": $data = "Sexta";
                            break;
                        case"Saturday": $data = "Sabado";
                            break;
                    }

                    if ($data == $Domingo) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('bloqueado', 't');
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where("(tipo = 'FISIOTERAPIA' OR tipo = 'ESPECIALIDADE')");
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('paciente_id is null');
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Segunda) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('bloqueado', 't');
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where("(tipo = 'FISIOTERAPIA' OR tipo = 'ESPECIALIDADE')");
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('paciente_id is null');
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Terça) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('bloqueado', 't');
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where("(tipo = 'FISIOTERAPIA' OR tipo = 'ESPECIALIDADE')");
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('paciente_id is null');
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Quarta) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('bloqueado', 't');
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where("(tipo = 'FISIOTERAPIA' OR tipo = 'ESPECIALIDADE')");
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('paciente_id is null');
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Quinta) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('bloqueado', 't');
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where("(tipo = 'FISIOTERAPIA' OR tipo = 'ESPECIALIDADE')");
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('paciente_id is null');
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Sexta) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->set('bloqueado', 't');
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where("(tipo = 'FISIOTERAPIA' OR tipo = 'ESPECIALIDADE')");
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('paciente_id is null');
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Sabado) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');
                        if ($_POST['txtobservacao'] != '') {
                            $this->db->set('observacoes', $_POST['txtobservacao']);
                        }
                        $this->db->set('bloqueado', 't');
                        $this->db->set('operador_medico_agenda', $operador_id);
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where("(tipo = 'FISIOTERAPIA' OR tipo = 'ESPECIALIDADE')");
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('paciente_id is null');
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->update('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                }
            }
            if ($_POST['txtacao'] == 'Excluir') {


                for ($index = $datainicial; strtotime($index) <= strtotime($datafinal); $index = date('d-m-Y', strtotime("+1 days", strtotime($index)))) {

                    $data = strftime("%A", strtotime($index));

                    switch ($data) {
                        case"Sunday": $data = "Domingo";
                            break;
                        case"Monday": $data = "Segunda";
                            break;
                        case"Tuesday": $data = "Terça";
                            break;
                        case"Wednesday": $data = "Quarta";
                            break;
                        case"Thursday": $data = "Quinta";
                            break;
                        case"Friday": $data = "Sexta";
                            break;
                        case"Saturday": $data = "Sabado";
                            break;
                    }

                    if ($data == $Domingo) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->where('paciente_id is null');
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where("(tipo = 'FISIOTERAPIA' OR tipo = 'ESPECIALIDADE')");
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->delete('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Segunda) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->where('paciente_id is null');
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where("(tipo = 'FISIOTERAPIA' OR tipo = 'ESPECIALIDADE')");
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->delete('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Terça) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->where('paciente_id is null');
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where("(tipo = 'FISIOTERAPIA' OR tipo = 'ESPECIALIDADE')");
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->delete('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Quarta) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->where('paciente_id is null');
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where("(tipo = 'FISIOTERAPIA' OR tipo = 'ESPECIALIDADE')");
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->delete('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Quinta) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->where('paciente_id is null');
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where("(tipo = 'FISIOTERAPIA' OR tipo = 'ESPECIALIDADE')");
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->delete('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Sexta) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->where('paciente_id is null');
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where("(tipo = 'FISIOTERAPIA' OR tipo = 'ESPECIALIDADE')");
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->delete('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                    if ($data == $Sabado) {
                        /* inicia o mapeamento no banco */
                        $horario = date("Y-m-d H:i:s");
                        $operador_id = $this->session->userdata('operador_id');

                        $this->db->where('paciente_id is null');
                        $this->db->where('data', date("Y-m-d", strtotime(str_replace("/", "-", $index))));
                        $this->db->where("(tipo = 'FISIOTERAPIA' OR tipo = 'ESPECIALIDADE')");
                        $this->db->where('inicio >=', $_POST['horainicio']);
                        $this->db->where('inicio <=', $_POST['horafim']);
                        $this->db->where('medico_consulta_id', $_POST['medico']);
                        $this->db->delete('tb_agenda_exames');
                        $erro = $this->db->_error_message();
                        if (trim($erro) != "") // erro de banco
                            return -1;
                    }
                }
            }
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function listarhorarioagendacriado($agenda_id = null) {
        $this->db->select('e.nome as empresa,
                           h.dia,
                           h.horaentrada1,
                           h.horasaida1,
                           h.intervaloinicio,
                           h.intervalofim,
                           h.tempoconsulta,
                           h.agenda_id,
                           h.qtdeconsulta,
                           h.empresa_id,
                           h.observacoes,
                           h.grupo,
                           c.nome as convenio,
                           h.horarioagenda_id,
                           s.nome as sala,
                           h.empresa_id,
                           h.sala_id');
        $this->db->from('tb_horarioagenda h');
        $this->db->join('tb_empresa e', 'e.empresa_id = h.empresa_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = h.convenio_id', 'left');
        $this->db->join('tb_exame_sala s', 's.exame_sala_id = h.sala_id', 'left');
        $this->db->where('agenda_id', $agenda_id);
        $this->db->orderby('dia');
        $return = $this->db->get();
        return $return->result();
    }

    function listarhorarioagenda($agenda_id = null) {
        $this->db->select('e.nome as empresa,
                           h.dia,
                           h.horaentrada1,
                           h.horasaida1,
                           h.intervaloinicio,
                           h.intervalofim,
                           h.tempoconsulta,
                           h.agenda_id,
                           h.qtdeconsulta,
                           h.empresa_id,
                           h.observacoes,
                           h.horarioagenda_id,
                           h.empresa_id,
                           s.nome as sala,
                           h.sala_id');
        $this->db->from('tb_horarioagenda h');
        $this->db->join('tb_empresa e', 'e.empresa_id = h.empresa_id', 'left');
        $this->db->join('tb_exame_sala s', 's.exame_sala_id = h.sala_id', 'left');
        $this->db->where('agenda_id', $agenda_id);
        $this->db->orderby('dia');
        $return = $this->db->get();
        return $return->result();
    }

    function listartotalhoariofixo() {
        $this->db->select();
        $this->db->from('tb_agenda');
        $this->db->where('tipo', 'Fixo');
        $this->db->where('ativo', 't');
        $return = $this->db->get();
        return $return->result();
    }

    function listaratribuiragenda($agenda_id) {
        $this->db->select('agenda_id,
                            nome, 
                            tipo');
        $this->db->from('tb_agenda');
        $this->db->where('ativo', 'true');
        $this->db->where('agenda_id', $agenda_id);
        $this->db->orderby('nome');
        $return = $this->db->get();
        return $return->result();
    }

    function listaragendainformacoes($agenda_id) {
        $this->db->select('a.agenda_id,
                            a.nome, 
                            a.datacon_inicio, 
                            a.datacon_fim, 
                            a.intervalo, 
                            a.consolidada, 
                            a.tipo_agenda as tipo_agenda_id, 
                            o.nome as medico,
                            o.operador_id as medico_id,
                            ts.descricao as tipo_agenda,
                            a.tipo,
                            o.hora_manha,
                            o.hora_tarde');
        $this->db->from('tb_agenda a');
        $this->db->join('tb_operador o', 'o.operador_id = a.medico_id', 'left');
        $this->db->join('tb_ambulatorio_tipo_consulta ts', 'ts.ambulatorio_tipo_consulta_id = a.tipo_agenda', 'left');
        // $this->db->where('a.ativo', 'true');
        $this->db->where('a.agenda_id', $agenda_id);
        $this->db->orderby('a.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function listarhorarioagendacriacao($agenda_id = null, $medico_id = null, $datainicial, $datafinal, $tipo) {

        $this->db->select('distinct(horario_id)');
        $this->db->from('tb_agenda_exames ae');
//        $this->db->join('tb_empresa e', 'e.empresa_id = h.empresa_id', 'left');
        $this->db->where('horarioagenda_id', $agenda_id);
        $this->db->where('medico_agenda', $medico_id);
        $this->db->where('data >=', $datainicial);
        $this->db->where('data <=', $datafinal);
        $this->db->where('tipo', $tipo);
        $this->db->where('horario_id is not null');
        // $this->db->where('paciente_id is null');
        $this->db->groupby('horario_id');
        $return2 = $this->db->get()->result();
        if (count($return2) > 0) {
            $horario_id = '';
//            $horario_id = $return2
            foreach ($return2 as $item) {
                if ($horario_id == '') {
                    $horario_id = $horario_id . "$item->horario_id";
                } else {
                    $horario_id = $horario_id . ",$item->horario_id";
                }
            }
        }


        $this->db->select('e.nome as empresa,
                           h.dia,
                           h.horaentrada1,
                           h.horasaida1,
                           h.intervaloinicio,
                           h.intervalofim,
                           h.tempoconsulta,
                           h.agenda_id,
                           h.qtdeconsulta,
                           h.empresa_id,
                           h.observacoes,
                           h.horarioagenda_id,
                           h.sala_id');
        $this->db->from('tb_horarioagenda h');
        $this->db->join('tb_empresa e', 'e.empresa_id = h.empresa_id', 'left');
        $this->db->join('tb_exame_sala s', 's.exame_sala_id = h.sala_id', 'left');
        $this->db->where('agenda_id', $agenda_id);
        $this->db->where('h.sala_id IS NOT NULL');

        if (count($return2) > 0) {

            $this->db->where("horarioagenda_id NOT IN ($horario_id)");
        }

        $this->db->orderby('dia');
        $return = $this->db->get();
//        var_dump($return->result()); die;
        return $return->result();
    }

    function listarhorarioagendacriacaogeral($agenda_id = null, $medico_id = null, $datainicial, $datafinal, $tipo) {

        $this->db->select('distinct(horario_id)');
        $this->db->from('tb_agenda_exames ae');
//        $this->db->join('tb_empresa e', 'e.empresa_id = h.empresa_id', 'left');
        $this->db->where('horarioagenda_id', $agenda_id);
        $this->db->where('medico_agenda', $medico_id);
        $this->db->where('data >=', $datainicial);
        $this->db->where('data <=', $datafinal);
        $this->db->where('tipo', $tipo);
        $this->db->where('horario_id is not null');
        $this->db->groupby('horario_id');
        $return2 = $this->db->get()->result();
        if (count($return2) > 0) {
            $horario_id = '';
            foreach ($return2 as $item) {
                if ($horario_id == '') {
                    $horario_id = $horario_id . "$item->horario_id";
                } else {
                    $horario_id = $horario_id . ",$item->horario_id";
                }
            }
        }


        $this->db->select('e.nome as empresa,
                           h.dia,
                           h.horaentrada1,
                           h.horasaida1,
                           h.intervaloinicio,
                           h.intervalofim,
                           h.tempoconsulta,
                           h.agenda_id,
                           h.qtdeconsulta,
                           h.empresa_id,
                           h.observacoes,
                           h.horarioagenda_id,
                           h.sala_id');
        $this->db->from('tb_horarioagenda h');
        $this->db->join('tb_empresa e', 'e.empresa_id = h.empresa_id', 'left');
        $this->db->join('tb_exame_sala s', 's.exame_sala_id = h.sala_id', 'left');
        $this->db->where('agenda_id', $agenda_id);
        $this->db->where('h.sala_id IS NOT NULL');

        if (count($return2) > 0) {
            $this->db->where("horarioagenda_id NOT IN ($horario_id)");
        }

        $this->db->orderby('dia');
        $return = $this->db->get();
//        var_dump($return->result()); die;
        return $return->result();
    }

    function deletaragendacriacaogeralmodelo2($agenda_id = null) {

        // Deletando todos os horários daquela agenda cujo o Agenda_id é igual
        // e não há agendamento
        if ($agenda_id > 0) {
            $this->db->where('horarioagenda_id', $agenda_id);
            $this->db->where('paciente_id is null');
            $this->db->delete('tb_agenda_exames');
        }

        return $agenda_id;
    }

    function listarhorarioagendacriacaogeralmodelo2($agenda_id = null, $medico_id = null, $datainicial, $datafinal, $tipo) {

        $this->db->select('distinct(horario_id)');
        $this->db->from('tb_agenda_exames ae');
//        $this->db->join('tb_empresa e', 'e.empresa_id = h.empresa_id', 'left');
        $this->db->where('horarioagenda_id', $agenda_id);
        $this->db->where('medico_agenda', $medico_id);
        $this->db->where('data >=', $datainicial);
        $this->db->where('data <=', $datafinal);
        $this->db->where('tipo', $tipo);
        $this->db->where('horario_id is not null');
        $this->db->groupby('horario_id');
        $return2 = $this->db->get()->result();
        if (count($return2) > 0) {
            $horario_id = '';
            foreach ($return2 as $item) {
                if ($horario_id == '') {
                    $horario_id = $horario_id . "$item->horario_id";
                } else {
                    $horario_id = $horario_id . ",$item->horario_id";
                }
            }
        }


        $this->db->select('e.nome as empresa,
                           h.dia,
                           h.horaentrada1,
                           h.horasaida1,
                           h.intervaloinicio,
                           h.intervalofim,
                           h.tempoconsulta,
                           h.agenda_id,
                           h.qtdeconsulta,
                           h.empresa_id,
                           h.observacoes,
                           h.horarioagenda_id,
                           h.sala_id');
        $this->db->from('tb_horarioagenda h');
        $this->db->join('tb_empresa e', 'e.empresa_id = h.empresa_id', 'left');
        $this->db->join('tb_exame_sala s', 's.exame_sala_id = h.sala_id', 'left');
        $this->db->where('agenda_id', $agenda_id);
        $this->db->where('h.sala_id IS NOT NULL');

        if (count($return2) > 0) {
            // A verificação de horários já existentes será feita posteriormente
            // $this->db->where("horarioagenda_id NOT IN ($horario_id)");
        }

        $this->db->orderby('dia');
        $return = $this->db->get();
//        var_dump($return->result()); die;
        return $return->result();
    }

    function listarnovoshorarioseditaragendacriada() {

        $this->db->select('e.nome as empresa,
                           h.dia,
                           h.horaentrada1,
                           h.horasaida1,
                           h.intervaloinicio,
                           h.intervalofim,
                           h.tempoconsulta,
                           h.agenda_id,
                           h.qtdeconsulta,
                           h.empresa_id,
                           h.observacoes,
                           h.horarioagenda_editada_id,
                           h.sala_id');
        $this->db->from('tb_horarioagenda_editada h');
        $this->db->join('tb_empresa e', 'e.empresa_id = h.empresa_id', 'left');
        $this->db->join('tb_exame_sala s', 's.exame_sala_id = h.sala_id', 'left');
        $this->db->where("h.agenda_id", $_GET['agenda_id']);
        $this->db->where("h.ativo", 't');
        $this->db->where("h.consolidado", 'f');
        $this->db->where("h.medico_id", $_GET['medico_id']);
        $this->db->where("h.nome", $_GET['nome_agenda']);
        $this->db->orderby('dia');
        $return = $this->db->get();
        return $return->result();
    }

    function listarhorarioagendacriacaoespecialidade($agenda_id = null, $medico_id = null, $datainicial, $datafinal, $tipo) {

        $this->db->select('distinct(horario_id)');
        $this->db->from('tb_agenda_exames ae');
//        $this->db->join('tb_empresa e', 'e.empresa_id = h.empresa_id', 'left');
        $this->db->where('horarioagenda_id', $agenda_id);
        $this->db->where('medico_agenda', $medico_id);
        $this->db->where('data >=', $datainicial);
        $this->db->where('data <=', $datafinal);
        $this->db->where("(tipo ='ESPECIALIDADE' OR tipo = 'FISIOTERAPIA')");
        $this->db->where('horario_id is not null');
        $this->db->where('paciente_id is null');
        $this->db->groupby('horario_id');
        $return2 = $this->db->get()->result();
        if (count($return2) > 0) {
            $horario_id = '';
//            $horario_id = $return2
            foreach ($return2 as $item) {
                if ($horario_id == '') {
                    $horario_id = $horario_id . "$item->horario_id";
                } else {
                    $horario_id = $horario_id . ",$item->horario_id";
                }
            }
        }
//        var_dump($medico_id); die;


        $this->db->select('e.nome as empresa,
                           h.dia,
                           h.horaentrada1,
                           h.horasaida1,
                           h.intervaloinicio,
                           h.intervalofim,
                           h.tempoconsulta,
                           h.agenda_id,
                           h.qtdeconsulta,
                           h.empresa_id,
                           h.observacoes,
                           h.horarioagenda_id');
        $this->db->from('tb_horarioagenda h');
        $this->db->join('tb_empresa e', 'e.empresa_id = h.empresa_id', 'left');
        $this->db->where('agenda_id', $agenda_id);

        if (count($return2) > 0) {

            $this->db->where("horarioagenda_id NOT IN ($horario_id)");
        }

        $this->db->orderby('dia');
        $return = $this->db->get();
//        var_dump($agenda_id, $return->result()); die;
        return $return->result();
    }

    function instanciarferiado($feriado_id = null) {
        $this->db->select('feriado_id,
                           nome,
                           data');
        $this->db->from('tb_feriado');
        $this->db->where('feriado_id', $feriado_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listarhorarioagendaexclusao($agenda_id = null) {
        $this->db->select('e.nome as empresa,
                           h.dia,
                           h.horaentrada1,
                           h.horasaida1,
                           h.intervaloinicio,
                           h.intervalofim,
                           h.tempoconsulta,
                           h.agenda_id,
                           h.qtdeconsulta,
                           h.empresa_id,
                           h.observacoes,
                           h.horarioagenda_id,
                           a.medico_id');
        $this->db->from('tb_horarioagenda h');
        $this->db->join('tb_agenda a', 'a.agenda_id = h.agenda_id', 'left');
        $this->db->join('tb_empresa e', 'e.empresa_id = h.empresa_id', 'left');
        $this->db->where('horarioagenda_id', $agenda_id);
        $this->db->orderby('dia');
        $return = $this->db->get();
        return $return->result();
    }

    function listaragendaexclusao($agenda_id = null) {
        $this->db->select('e.nome as empresa,
                           h.dia,
                           h.horaentrada1,
                           h.horasaida1,
                           h.intervaloinicio,
                           h.intervalofim,
                           h.tempoconsulta,
                           h.agenda_id,
                           h.qtdeconsulta,
                           h.empresa_id,
                           h.observacoes,
                           h.horarioagenda_id');
        $this->db->from('tb_horarioagenda h');
        $this->db->join('tb_empresa e', 'e.empresa_id = h.empresa_id', 'left');
        $this->db->where('agenda_id', $agenda_id);
        $this->db->orderby('dia');
        $return = $this->db->get();
        return $return->result();
    }

    function gravarhorarioagendacriada($agenda_id) {
        try {
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

//            $agenda_id = $_POST['txtagendaID'];
            $x = 0;
            foreach ($_POST['txtDia'] as $dia) { //verifica se esse dia ja tem algum cadastro nessa agenda, se tiver, deve primeiro exclui-lo para depois criar.
                $x++;
                $horaentrada1 = $_POST['txthoraEntrada'][$x];
                if ($horaentrada1 != '') {
                    $this->db->select('e.nome as empresa,
                                       h.dia,
                                       h.horaentrada1,
                                       h.horasaida1,
                                       h.intervaloinicio,
                                       h.intervalofim,
                                       h.tempoconsulta,
                                       h.agenda_id,
                                       h.qtdeconsulta,
                                       h.empresa_id,
                                       h.observacoes,
                                       h.horarioagenda_id');
                    $this->db->from('tb_horarioagenda h');
                    $this->db->join('tb_empresa e', 'e.empresa_id = h.empresa_id', 'left');
                    $this->db->where("h.agenda_id", $agenda_id);
                    $this->db->where("h.dia", $dia);
                    $this->db->where("h.horarioagenda_id IN (   
                                            SELECT horario_id 
                                            FROM ponto.tb_agenda_exames
                                            WHERE horarioagenda_id = '{$agenda_id}'
                                            AND paciente_id IS NULL
                                            AND medico_agenda = '{$_POST['medico_id']}'
                                            AND nome = '{$_POST['nome_agenda']}')");
                    $this->db->orderby('dia');
                    $return = $this->db->get();
                    $return = $return->result();

                    if (count($return) > 0) {
                        return true;
                    }
//                    
//                    $this->db->select('e.nome as empresa,
//                               h.dia,
//                               h.horaentrada1,
//                               h.horasaida1,
//                               h.intervaloinicio,
//                               h.intervalofim,
//                               h.tempoconsulta,
//                               h.agenda_id,
//                               h.qtdeconsulta,
//                               h.empresa_id,
//                               h.observacoes,
//                               h.horarioagenda_id');
//                    $this->db->from('tb_horarioagenda h');
//                    $this->db->join('tb_empresa e', 'e.empresa_id = h.empresa_id', 'left');
//                    $this->db->where("h.agenda_id", $agenda_id);
//                    $this->db->where("h.dia", $dia);
//                    $this->db->where("h.horarioagenda_id IN (   
//                                            SELECT horario_id 
//                                            FROM ponto.tb_agenda_exames
//                                            WHERE horarioagenda_id = '{$agenda_id}'
//                                            AND paciente_id IS NULL
//                                            AND medico_agenda = '{$_POST['medico_id']}'
//                                            AND nome = '{$_POST['nome_agenda']}')");
//                    $this->db->orderby('dia');
//                    $return = $this->db->get();
//                    $return = $return->result();
//
//                    if( count($return) > 0 ){
//                        return true;
//                    }
                }
            }

            $i = 0;
            foreach ($_POST['txtDia'] as $dia) {
                $this->db->select('agenda_id, nome, tipo');
                $this->db->from('tb_agenda');
                $this->db->where("agenda_id", $agenda_id);
                $query = $this->db->get();
                $return = $query->result();

                $i++;
                $horaentrada1 = $_POST['txthoraEntrada'][$i];
                $horasaida1 = $_POST['txthoraSaida'][$i];
                $intervaloinicio = $_POST['txtIniciointervalo'][$i];
                $intervalofim = $_POST['txtFimintervalo'][$i];
                $tempoconsulta = $_POST['txtTempoconsulta'][$i];
                $qtdeconsulta = $_POST['txtQtdeconsulta'][$i];
                $empresa_id = $_POST['empresa'][$i];
                $sala_id = $_POST['sala'][$i];

                if ($horaentrada1 != '') {


                    $this->db->set('agenda_id', $agenda_id);
                    $this->db->set('dia', $dia);
                    $this->db->set('horaentrada1', $horaentrada1);
                    $this->db->set('horasaida1', $horasaida1);
                    $this->db->set('intervaloinicio', $intervaloinicio);
                    $this->db->set('intervalofim', $intervalofim);
                    if ($tempoconsulta != '') {
                        $this->db->set('tempoconsulta', $tempoconsulta);
                    }
                    if ($qtdeconsulta != '') {
                        $this->db->set('qtdeconsulta', $qtdeconsulta);
                    }
                    $this->db->set('empresa_id', $empresa_id);
                    $this->db->set('sala_id', $sala_id);
                    $this->db->set('medico_id', $_POST['medico_id']);
                    $this->db->set('nome', $_POST['nome_agenda']);
                    $this->db->set('data_cadastro', $horario);
                    $this->db->set('operador_cadastro', $operador_id);

                    $this->db->insert('tb_horarioagenda_editada');
                }
            }
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return true;
            else
                return false;
        } catch (Exception $exc) {
            return false;
        }
    }

    function gravarhorariofixo() {
        try {
            $agenda_id = $_POST['txtagendaID'];
            $i = 0;
            $retorno_mensagem = 1;
            $empresa_id = $this->session->userdata('empresa_id');
            $this->db->select('es.exame_sala_id, es.nome');
            $this->db->from('tb_exame_sala es');
            $this->db->where('es.excluido', 'f');
            $this->db->where('es.empresa_id', $empresa_id);
            $this->db->orderby('es.nome');
            $return_sala = $this->db->get()->result();
            
            /* inicia o mapeamento no banco */

            foreach ($_POST['txtDia'] as $dia) {
                $i++;
                $horaentrada1 = $_POST['txthoraEntrada'][$i];
                $horasaida1 = $_POST['txthoraSaida'][$i];
                $intervaloinicio = $_POST['txtIniciointervalo'][$i];
                $intervalofim = $_POST['txtFimintervalo'][$i];
                $tempoconsulta = $_POST['txtTempoconsulta'][$i];
                $qtdeconsulta = $_POST['txtQtdeconsulta'][$i];
                if(isset($_POST['empresa'][$i])){
                    $empresa_id = $_POST['empresa'][$i];
                    $sala_id = $_POST['sala'][$i];
                }else{
                    
                    $sala_id = $return_sala[0]->exame_sala_id;
                }
                if(isset($_POST['convenio'][$i])){
                    $convenio_id = $_POST['convenio'][$i];
                    $grupos = $_POST['grupos'][$i];
                }else{
                    $convenio_id = null;
                    $grupos = null;
                }
                // var_dump($convenio_id, $grupos); die;
                if ($horaentrada1 != '') {
                    // A lógica abaixo serve para limitar os horários ao horário existente no cadastro de empresa

                    $this->db->select('horario_seg_sex_inicio,
                               horario_seg_sex_fim,
                               horario_sab_inicio,
                               horario_sab_fim,
                                ');
                    $this->db->from('tb_empresa');
                    $this->db->where('empresa_id', $empresa_id);
                    // $this->db->where('dia', $dia);
                    $empresa_inf = $this->db->get()->result();
                    // echo '<pre>';

                    $horario_seg_ini = $empresa_inf[0]->horario_seg_sex_inicio;
                    $horario_seg_sex_fim = $empresa_inf[0]->horario_seg_sex_fim;
                    $horario_sab_inicio = $empresa_inf[0]->horario_sab_inicio;
                    $horario_sab_fim = $empresa_inf[0]->horario_sab_fim;

                    if ($i < 6) { // Caso seja dia de semana.
                        if (strtotime($horario_seg_ini) > strtotime($horaentrada1) && $horario_seg_ini != '') {
                            $horaentrada1 = $horario_seg_ini;
                            $retorno_mensagem = -5;
                        }

                        if (strtotime($horario_seg_sex_fim) < strtotime($horasaida1) && $horario_seg_sex_fim != '') {
                            $horasaida1 = $horario_seg_sex_fim;
                            $retorno_mensagem = -5;
                        }
                    } else {

                        if (strtotime($horario_sab_inicio) > strtotime($horaentrada1) && $horario_sab_inicio != '') {
                            $horaentrada1 = $horario_sab_inicio;
                            $retorno_mensagem = -5;
                        }

                        if (strtotime($horario_sab_fim) < strtotime($horasaida1) && $horario_sab_fim != '') {
                            $horasaida1 = $horario_sab_fim;
                            $retorno_mensagem = -5;
                        }
                    }

                    if ($horaentrada1 >= $horasaida1) {
                        return -4;
                    }

                    $this->db->select('horaentrada1, horasaida1');
                    $this->db->from('tb_horarioagenda');
                    $this->db->where('agenda_id', $agenda_id);
                    $this->db->where('dia', $dia);
                    $query = $this->db->get()->result();

                    if (count($query) > 0) { // Caso ja tenha algum horario nesse dia
                        if (count($query) == 1) { // Caso tenha apenas um horario
                            // A condição abaixo verifica se os horarios colidem
                            if (($horaentrada1 > $query[0]->horaentrada1 && $horaentrada1 >= $query[0]->horasaida1) || ($horasaida1 < $query[0]->horaentrada1 )) {

                                $this->db->set('agenda_id', $agenda_id);
                                $this->db->set('dia', $dia);
                                $this->db->set('convenio_id', $convenio_id);
                                $this->db->set('grupos', $grupos);
                                $this->db->set('horaentrada1', $horaentrada1);
                                $this->db->set('horasaida1', $horasaida1);
                                $this->db->set('intervaloinicio', $intervaloinicio);
                                $this->db->set('intervalofim', $intervalofim);

                                if ($tempoconsulta != "") {
                                    $this->db->set('tempoconsulta', $tempoconsulta);
                                }
                                if ($qtdeconsulta != "") {
                                    $this->db->set('qtdeconsulta', $qtdeconsulta);
                                }

                                $this->db->set('empresa_id', $empresa_id);
                                $this->db->set('sala_id', $sala_id);
                                $this->db->set('observacoes', $_POST['obs']);

                                $this->db->insert('tb_horarioagenda');
                            } else {
                                return -2;
                            }
                        } else {
                            return -3;
                        }
                    } else {

                        $this->db->set('agenda_id', $agenda_id);
                        $this->db->set('dia', $dia);
                        $this->db->set('convenio_id', $convenio_id);
                        $this->db->set('grupo', $grupos);
                        $this->db->set('horaentrada1', $horaentrada1);
                        $this->db->set('horasaida1', $horasaida1);
                        $this->db->set('intervaloinicio', $intervaloinicio);
                        $this->db->set('intervalofim', $intervalofim);

                        if ($tempoconsulta != "") {
                            $this->db->set('tempoconsulta', $tempoconsulta);
                        }
                        if ($qtdeconsulta != "") {
                            $this->db->set('qtdeconsulta', $qtdeconsulta);
                        }

                        $this->db->set('empresa_id', $empresa_id);
                        $this->db->set('sala_id', $sala_id);
                        $this->db->set('observacoes', $_POST['obs']);

                        $this->db->insert('tb_horarioagenda');
                    }
                }
            }
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            else
                return $retorno_mensagem;
        } catch (Exception $exc) {
            return false;
        }
    }

    function adicionarDia($dia){
        
        if($dia == '1 - Segunda'){
            return '2 - Terça';
        }
        else if($dia == '2 - Terça'){
            return '3 - Quarta';
        }
        else if($dia == '3 - Quarta'){
            return '4 - Quinta';
        }
        else if($dia == '4 - Quinta'){
            return '5 - Sexta';
        }
        else if($dia == '5 - Sexta'){
            return '6 - Sabado';
        }
        else if($dia == '6 - Sabado'){
            return '7 - Domingo';
        }
        else if($dia == '7 - Domingo'){
            return '1 - Segunda';
        }
    }

    function gravarhorariofixo2() {
        try {
            $agenda_id = $_POST['txtagendaID'];
            $i = 1;
            $retorno_mensagem = 1;
            $empresa_id = $this->session->userdata('empresa_id');
            $this->db->select('es.exame_sala_id, es.nome');
            $this->db->from('tb_exame_sala es');
            $this->db->where('es.excluido', 'f');
            $this->db->where('es.empresa_id', $empresa_id);
            $this->db->orderby('es.nome');
            $return_sala = $this->db->get()->result();

            $horaentrada1 = $_POST['txthoraEntrada'][$i];
            $horasaida1 = $_POST['txthoraSaida'][$i];
            $tempoconsulta = $_POST['txtTempoconsulta'][$i];

            if ($horaentrada1 >= $horasaida1) {
                $_POST['txtDia'][$i + 1] = $this->adicionarDia($_POST['txtDia'][$i]);
                $_POST['txthoraEntrada'][$i + 1] = '00:00';
                $_POST['txthoraSaida'][$i + 1] = $_POST['txthoraSaida'][$i];
                $_POST['txthoraSaida'][$i] = '23:59';
                $_POST['txtIniciointervalo'][$i + 1] = '00:00';
                $_POST['txtFimintervalo'][$i + 1] = '00:00';
                $_POST['txtTempoconsulta'][$i + 1] = $tempoconsulta;
                $_POST['txtQtdeconsulta'][$i] = round($_POST['txtQtdeconsulta'][$i] / 2);  
                $_POST['txtQtdeconsulta'][$i + 1] = $_POST['txtQtdeconsulta'][$i];
                $_POST['empresa'][$i + 1] = $_POST['empresa'][$i];
                $_POST['sala'][$i + 1] = $_POST['sala'][$i];
                $_POST['convenio'][$i + 1] = $_POST['convenio'][$i];
                $_POST['grupos'][$i + 1] = $_POST['grupos'][$i];
            }
            
            /* inicia o mapeamento no banco */

            foreach ($_POST['txtDia'] as $dia) {
               
                $horaentrada1 = $_POST['txthoraEntrada'][$i];
                $horasaida1 = $_POST['txthoraSaida'][$i];
                $intervaloinicio = $_POST['txtIniciointervalo'][$i];
                $intervalofim = $_POST['txtFimintervalo'][$i];
                $tempoconsulta = $_POST['txtTempoconsulta'][$i];
                $qtdeconsulta = $_POST['txtQtdeconsulta'][$i];
                if(isset($_POST['empresa'][$i])){
                    $empresa_id = $_POST['empresa'][$i];
                    $sala_id = $_POST['sala'][$i];
                }else{
                    
                    $sala_id = $return_sala[0]->exame_sala_id;
                }
                if(isset($_POST['convenio'][$i])){
                    $convenio_id = $_POST['convenio'][$i];
                    $grupos = $_POST['grupos'][$i];
                }else{
                    $convenio_id = null;
                    $grupos = null;
                }

                

                // var_dump($convenio_id, $grupos); die;
                if ($horaentrada1 != '') {
                    // A lógica abaixo serve para limitar os horários ao horário existente no cadastro de empresa

                    $this->db->select('horario_seg_sex_inicio,
                               horario_seg_sex_fim,
                               horario_sab_inicio,
                               horario_sab_fim,
                                ');
                    $this->db->from('tb_empresa');
                    $this->db->where('empresa_id', $empresa_id);
                    // $this->db->where('dia', $dia);
                    $empresa_inf = $this->db->get()->result();
                    // echo '<pre>';

                    $horario_seg_ini = $empresa_inf[0]->horario_seg_sex_inicio;
                    $horario_seg_sex_fim = $empresa_inf[0]->horario_seg_sex_fim;
                    $horario_sab_inicio = $empresa_inf[0]->horario_sab_inicio;
                    $horario_sab_fim = $empresa_inf[0]->horario_sab_fim;

                    if ($i < 6) { // Caso seja dia de semana.
                        if (strtotime($horario_seg_ini) > strtotime($horaentrada1) && $horario_seg_ini != '') {
                            $horaentrada1 = $horario_seg_ini;
                            $retorno_mensagem = -5;
                        }

                        if (strtotime($horario_seg_sex_fim) < strtotime($horasaida1) && $horario_seg_sex_fim != '') {
                            $horasaida1 = $horario_seg_sex_fim;
                            $retorno_mensagem = -5;
                        }
                    } else {

                        if (strtotime($horario_sab_inicio) > strtotime($horaentrada1) && $horario_sab_inicio != '') {
                            $horaentrada1 = $horario_sab_inicio;
                            $retorno_mensagem = -5;
                        }

                        if (strtotime($horario_sab_fim) < strtotime($horasaida1) && $horario_sab_fim != '') {
                            $horasaida1 = $horario_sab_fim;
                            $retorno_mensagem = -5;
                        }
                    }

                    

                    $this->db->select('horaentrada1, horasaida1');
                    $this->db->from('tb_horarioagenda');
                    $this->db->where('agenda_id', $agenda_id);
                    $this->db->where('dia', $dia);
                    $query = $this->db->get()->result();

                    if (count($query) > 0) { // Caso ja tenha algum horario nesse dia
                        if (count($query) == 1) { // Caso tenha apenas um horario
                            // A condição abaixo verifica se os horarios colidem
                            if (($horaentrada1 > $query[0]->horaentrada1 && $horaentrada1 >= $query[0]->horasaida1) || ($horasaida1 < $query[0]->horaentrada1 )) {

                                $this->db->set('agenda_id', $agenda_id);
                                $this->db->set('dia', $dia);
                                $this->db->set('convenio_id', $convenio_id);
                                $this->db->set('grupos', $grupos);
                                $this->db->set('horaentrada1', $horaentrada1);
                                $this->db->set('horasaida1', $horasaida1);
                                $this->db->set('intervaloinicio', $intervaloinicio);
                                $this->db->set('intervalofim', $intervalofim);

                                if ($tempoconsulta != "") {
                                    $this->db->set('tempoconsulta', $tempoconsulta);
                                }
                                if ($qtdeconsulta != "") {
                                    $this->db->set('qtdeconsulta', $qtdeconsulta);
                                }

                                $this->db->set('empresa_id', $empresa_id);
                                $this->db->set('sala_id', $sala_id);
                                $this->db->set('observacoes', $_POST['obs']);

                                $this->db->insert('tb_horarioagenda');
                            } else {
                                return -2;
                            }
                        } else {
                            return -3;
                        }
                    } else {

                        $this->db->set('agenda_id', $agenda_id);
                        $this->db->set('dia', $dia);
                        $this->db->set('convenio_id', $convenio_id);
                        $this->db->set('grupo', $grupos);
                        $this->db->set('horaentrada1', $horaentrada1);
                        $this->db->set('horasaida1', $horasaida1);
                        $this->db->set('intervaloinicio', $intervaloinicio);
                        $this->db->set('intervalofim', $intervalofim);

                        if ($tempoconsulta != "") {
                            $this->db->set('tempoconsulta', $tempoconsulta);
                        }
                        if ($qtdeconsulta != "") {
                            $this->db->set('qtdeconsulta', $qtdeconsulta);
                        }

                        $this->db->set('empresa_id', $empresa_id);
                        $this->db->set('sala_id', $sala_id);
                        $this->db->set('observacoes', $_POST['obs']);

                        $this->db->insert('tb_horarioagenda');
                    }
                }
                $i++;
            }
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            else
                return $retorno_mensagem;
        } catch (Exception $exc) {
            return false;
        }
    }

    function excluirhorarioagendaconsolidada($agenda_id) {
        $this->db->where('paciente_id is null');
        $this->db->where('nome', $_GET['nome']);
        $this->db->where('horarioagenda_id', $agenda_id);
        $this->db->where('medico_agenda', $_GET['medico_id']);
        $this->db->where('horarioagenda_editada_id', $_GET['horario_id']);
        $this->db->delete('tb_agenda_exames');

        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return false;
        else
            return true;
    }

    function excluirhorarioagendacriada($agenda_id) {
//        echo "<pre>"; var_dump($_GET);die;
        $this->db->where('nome', $_GET['nome']);
        $this->db->where('horario_id', $_GET['horario_id']);
        $this->db->where('medico_agenda', $_GET['medico_id']);
        $this->db->where('horarioagenda_id', $agenda_id);
        $this->db->where('paciente_id is null');
        $this->db->delete('tb_agenda_exames');

        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return false;
        else
            return true;
    }

    function consolidandonovoshorarios($horario_id) {
        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');

        $this->db->set('consolidado', 't');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('horarioagenda_editada_id', $horario_id);
        $this->db->update('tb_horarioagenda_editada');
    }

    function excluirhorarioagendaeditada($horario_id) {
        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');

        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('horarioagenda_editada_id', $horario_id);
        $this->db->update('tb_horarioagenda_editada');
        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return false;
        else
            return true;
    }

    function excluirhorariofixo($agenda_id) {
//        var_dump($agenda_id); die;
        if ($_POST['excluir'] == 'on') {
            if ($_POST['txttipo'] != 'TODOS') {
                if ($_POST['txttipo'] == 'ESPECIALIDADE') {
                    $this->db->where("(tipo = 'ESPECIALIDADE' OR tipo = 'FISIOTERAPIA')");
                } else {
                    $this->db->where('tipo', $_POST['txttipo']);
                }
            }
            if ($_POST['txtmedico'] != 'TODOS') {
                $this->db->where('medico_agenda', $_POST['txtmedico']);
            }
            $this->db->where('horario_id', $agenda_id);
            $this->db->where('paciente_id is null');
            $this->db->delete('tb_agenda_exames');
        }

        $this->db->where('horarioagenda_id', $agenda_id);
        $this->db->delete('tb_horarioagenda');
        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return false;
        else
            return true;
    }

    /**
     * Função para instanciar os valores da tabela TB_SERVIDOR.
     * @author Equipe de desenvolvimento APH
     * @access private
     * @return Resposta true/false da conexão com o banco
     * @param integer $servidor_id com a informação do KEY do servidor.
     */
    private function instanciar($agenda_id) {
        if ($agenda_id != 0) {
            $this->db->select('agenda_id, nome, tipo');
            $this->db->from('tb_agenda');
            $this->db->where("agenda_id", $agenda_id);
            $query = $this->db->get();
            $return = $query->result();
            $this->_agenda_id = $agenda_id;

            $this->_nome = $return[0]->nome;
            $this->_tipo = $return[0]->tipo;
        } else {
            $this->_agenda_id = null;
        }
    }

    function getAgendaexames($maximo = NULL) {
        $dia = date('d') + 1;
        $amanha = date('Y-m-' . $dia . '');
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ae.data,ew.endereco_clinica,ew.endereco_externo,ew.mensagem,e.nome as clinica,o.nome as medico,ae.medico_agenda,pt.nome as exame,ae.agenda_exames_id,ae.paciente_id,p.nome as paciente,p.whatsapp,ae.empresa_id,ae.inicio,ae.fim,pt.descricao_procedimento');
        $this->db->from('tb_agenda_exames as ae');
        $this->db->join('tb_paciente as p', 'p.paciente_id = ae.paciente_id');
        $this->db->join('tb_procedimento_convenio as pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id');
        $this->db->join('tb_procedimento_tuss as pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id');
        $this->db->join('tb_operador as o', 'o.operador_id = ae.medico_agenda');
        $this->db->join('tb_empresa as e', 'e.empresa_id = ae.empresa_id');
        $this->db->join('tb_empresa_whatsapp as ew', 'ew.empresa_id = ae.empresa_id');
// $this->db->where('ae.ativo','t');
        $this->db->where('ae.paciente_id !=', 0);
        $this->db->where('p.whatsapp !=', "");
        $this->db->where("(ae.telefonema is null OR ae.telefonema='f')");
        $this->db->where('ae.empresa_id', $empresa_id);
        $this->db->where("(ae.confirmacao_por_sms is null OR ae.confirmacao_por_sms ='f') ");
        $this->db->where("(ae.whatsapp_enviado = 'f' OR ae.whatsapp_enviado is null)");
//        pegar todos exames de amanha
        $this->db->where('ae.data', date("Y-m-d", strtotime(str_replace("/", "-", $amanha))));
        $this->db->limit($maximo);
        $arrayexames = $this->db->get()->result();

        foreach ($arrayexames as $value) {
            $this->db->set('whatsapp_enviado', 't');
            $this->db->where('agenda_exames_id', $value->agenda_exames_id);
            $this->db->update('tb_agenda_exames');
        }

        return $arrayexames;
    }

    function listarconvenios(){
        $this->db->select(' c.convenio_id,
                            c.nome,
                            c.dinheiro,
                            c.padrao_particular,
                            c.conta_id');
        $this->db->from('tb_convenio c');
        $this->db->join('tb_convenio_empresa ce', 'ce.convenio_id = c.convenio_id', 'left');
        $this->db->where("c.ativo", 'true');
        $this->db->orderby("c.nome");
        $this->db->groupby("c.convenio_id,
                            c.nome,
                            c.dinheiro,
                            c.conta_id");
        $query = $this->db->get();
        $return = $query->result();

        return $return;
    }

    function listargrupos() {
        $this->db->select('ambulatorio_grupo_id,
                            nome,');
        $this->db->from('tb_ambulatorio_grupo');
        $this->db->orderby('nome');
        $return = $this->db->get();
        return $return->result();
    }

    function getAgendaexamesUnity($agenda_exames_id, $maximo = NULL) {
        $dia = date('d') + 1;
        $amanha = date('Y-m-' . $dia . '');
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ae.data,ew.endereco_clinica,ew.endereco_externo,ew.mensagem,e.nome as clinica,o.nome as medico,ae.medico_agenda,pt.nome as exame,ae.agenda_exames_id,ae.paciente_id,p.nome as paciente,p.whatsapp,ae.empresa_id');
        $this->db->from('tb_agenda_exames as ae');
        $this->db->join('tb_paciente as p', 'p.paciente_id = ae.paciente_id');
        $this->db->join('tb_procedimento_convenio as pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id');
        $this->db->join('tb_procedimento_tuss as pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id');
        $this->db->join('tb_operador as o', 'o.operador_id = ae.medico_agenda');
        $this->db->join('tb_empresa as e', 'e.empresa_id = ae.empresa_id');
        $this->db->join('tb_empresa_whatsapp as ew', 'ew.empresa_id = ae.empresa_id');
// $this->db->where('ae.ativo','t');
        $this->db->where('ae.paciente_id !=', 0);
        $this->db->where('p.whatsapp !=', "");
        $this->db->where("(ae.telefonema is null OR ae.telefonema='f')");
        $this->db->where('ae.empresa_id', $empresa_id);
        $this->db->where("(ae.confirmacao_por_sms is null OR ae.confirmacao_por_sms ='f') ");
        // $this->db->where("(ae.whatsapp_enviado = 'f' OR ae.whatsapp_enviado is null)");
//        pegar todos exames de amanha
        $this->db->where('ae.agenda_exames_id', $agenda_exames_id);
        // $this->db->limit($maximo);
        $arrayexames = $this->db->get()->result();

        foreach ($arrayexames as $value) {
            $this->db->set('whatsapp_enviado', 't');
            $this->db->where('agenda_exames_id', $value->agenda_exames_id);
            $this->db->update('tb_agenda_exames');
        }

        return $arrayexames;
    }

    function listarendereco_externo() {
        $this->db->select('endereco_externo');
        $this->db->from('tb_empresa_whatsapp');
        $this->db->where('empresa_id', $this->session->userdata('empresa_id'));
        return $this->db->get()->result();
    }

    function listarquantidadeatual() {

        $this->db->select('contador');
        $this->db->from('tb_empresa_whatsapp');
        $this->db->where('empresa_id', $this->session->userdata('empresa_id'));
        return $this->db->get()->result();
    }

    function listarpacoteempresa() {

        $this->db->select('pacote');
        $this->db->from('tb_empresa_whatsapp');
        $this->db->where('empresa_id', $this->session->userdata('empresa_id'));
        return $this->db->get()->result();
    }

    function atualizarcontador($quantoveio = NULL, $contador_atual = NULL) {
        $resultado = $quantoveio + $contador_atual;
        $this->db->set('contador', $resultado);
        $this->db->where('empresa_id', $this->session->userdata('empresa_id'));
        $this->db->update('tb_empresa_whatsapp');
    }

    
    function listarpermissoes(){
        $empresa_id  = $this->session->userdata('empresa_id');
        $this->db->select('');
        $this->db->from('tb_empresa as e');
        $this->db->where('e.empresa_id',$empresa_id);
       return $this->db->get()->result();
                        
        
    }
                        
    
    function auditoriaagenda($agenda_id){ 
         $this->db->select('a.agenda_id, 
                            a.nome, a.tipo,a.data_cadastro,op.nome as op_cadastro,opa.nome as op_atualizacao,a.data_atualizacao');
        $this->db->from('tb_agenda a');
        $this->db->join('tb_operador op','op.operador_id = a.operador_cadastro','left');
        $this->db->join('tb_operador opa','opa.operador_id = a.operador_atualizacao','left');
        $this->db->where('a.ativo', 'true');
        $this->db->where('a.agenda_id',$agenda_id); 
        return $this->db->get()->result();
    }               
    
    
    
}

?>
