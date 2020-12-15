<?php

class laudo_model extends Model {

    var $_ambulatorio_laudo_id = null;
    var $_texto = null;
    var $_situacaolaudo = null;
    var $_agenda_exames_id = null;
    var $_medico_parecer1 = null;
    var $_medico_parecer2 = null;
    var $_revisor = null;
    var $_status = null;
    var $_agrupador_fisioterapia = null;
    var $_assinatura = null;
    var $_rodape = null;
    var $_cabecalho = null;
    var $_grupo = null;
    var $_sala = null;
    var $_sala_id = null;
    var $_guia_id = null;
    var $_nome = null;
    var $_Idade = null;
    var $_indicado = null;
    var $_indicacao = null;
    var $_procedimento = null;
    var $_nascimento = null;
    var $_telefone = null;
    var $_uf = null;
    var $_bairro = null;
    var $_logradouro = null;
    var $_numero = null;
    var $_solicitante = null;
    var $_quantidade = null;
    var $_convenio = null;
    var $_sexo = null;
    var $_imagens = null;
    var $_cid = null;
    var $_ciddescricao = null;
    var $_cid2 = null;
    var $_cid2descricao = null;
    var $_peso = null;
    var $_altura = null;
    var $_pasistolica = null;
    var $_padiastolica = null;
    var $_superficie_corporea = null;
    var $_ve_volume_telediastolico = null;
    var $_ve_volume_telessistolico = null;
    var $_ve_diametro_telediastolico = null;
    var $_ve_diametro_telessistolico = null;
    var $_ve_indice_do_diametro_diastolico = null;
    var $_ve_septo_interventricular = null;
    var $_ve_parede_posterior = null;
    var $_ve_relacao_septo_parede_posterior = null;
    var $_ve_espessura_relativa_paredes = null;
    var $_ve_massa_ventricular = null;
    var $_ve_indice_massa = null;
    var $_ve_relacao_volume_massa = null;
    var $_ve_fracao_ejecao = null;
    var $_ve_fracao_encurtamento = null;
    var $_vd_diametro_telediastolico = null;
    var $_vd_area_telediastolica = null;
    var $_ae_diametro = null;
    var $_ae_volume = null;
    var $_ad_volume = null;
    var $_ad_volume_indexado = null;
    var $_vd_diametro_pel = null;
    var $_vd_diametro_basal = null;
    var $_ao_diametro_raiz = null;
    var $_paciente_id = null;
    var $_ao_relacao_atrio_esquerdo_aorta = null;
    var $_diagnostico = null;

    function laudo_model($ambulatorio_laudo_id = null) {
        parent::Model();
        if (isset($ambulatorio_laudo_id)) {
            $this->instanciar($ambulatorio_laudo_id);
        }
    }

    function validar() {
        $senha = $_POST['senha'];
        $medico = $_POST['medico'];


        $this->db->select(' o.operador_id,
                                o.perfil_id,
                                p.nome as perfil,
                                a.modulo_id'
        );
        $this->db->from('tb_operador o');
        $this->db->join('tb_perfil p', 'p.perfil_id = o.perfil_id');
        $this->db->join('tb_acesso a', 'a.perfil_id = o.perfil_id', 'left');
        $this->db->where('o.operador_id', $medico);
        $perfil_id = $this->session->userdata('perfil_id');
        if ($perfil_id != 1) {
            $this->db->where('o.senha', md5($senha));
        }
        $this->db->where('o.ativo = true');
        $this->db->where('p.ativo = true');
        $return = $this->db->get()->result();

        if (isset($return) && count($return) > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    function validarrevisor() {
        $senha = $_POST['senha'];
        $medico = $_POST['medicorevisor'];


        $this->db->select(' o.operador_id,
                                o.perfil_id,
                                p.nome as perfil,
                                a.modulo_id'
        );
        $this->db->from('tb_operador o');
        $this->db->join('tb_perfil p', 'p.perfil_id = o.perfil_id');
        $this->db->join('tb_acesso a', 'a.perfil_id = o.perfil_id', 'left');
        $perfil_id = $this->session->userdata('perfil_id');
        if ($perfil_id != 1) {
//        $this->db->where('o.senha', md5($senha));
//        $this->db->where('o.operador_id', $medico);
        }
        $this->db->where('o.ativo = true');
        $this->db->where('p.ativo = true');
        $return = $this->db->get()->result();
//        var_dump($return); die;
        if (isset($return) && count($return) > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    function listarlaudosintegracaotodos() {
        $this->db->select('il.exame_id');
        $this->db->from('tb_integracao_laudo il');
        $this->db->join('tb_operador o', 'o.conselho = il.laudo_conselho_medico', 'left');
        $this->db->join('tb_operador op', 'op.conselho = il.laudo_conselho_medico_revisor', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = il.exame_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
//        $this->db->where('il.exame_id', $agenda_exames_id);
        $this->db->where("((il.laudo_status = 'PUBLICADO') OR (il.laudo_status = 'REPUBLICADO') ) ");
        $return = $this->db->get();
        return $return->result();
    }

    function listardadoslaudogravarxml($ambulatorio_laudo_id) {
        $this->db->select('al.ambulatorio_laudo_id, al.exame_id, e.sala_id');
        $this->db->from('tb_ambulatorio_laudo al');
        $this->db->join('tb_exames e', 'e.exames_id = al.exame_id');
        $this->db->where('al.ambulatorio_laudo_id', $ambulatorio_laudo_id);
        $return = $this->db->get();
        return $return->result();
    }

    function atualizacaolaudosintegracaotodos() {

        $this->db->select('il.integracao_laudo_id,
                            il.exame_id,
                            il.laudo_texto,
                            il.laudo_data_hora,
                            al.ambulatorio_laudo_id,
                            il.laudo_status,
                            al.ambulatorio_laudo_id,
                            o.operador_id as medico,
                            op.operador_id as revisor');
        $this->db->from('tb_integracao_laudo il');
        $this->db->join('tb_operador o', 'o.conselho = il.laudo_conselho_medico', 'left');
        $this->db->join('tb_operador op', 'op.conselho = il.laudo_conselho_medico_revisor', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = il.exame_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
//        $this->db->where('il.exame_id', $agenda_exames_id);
        $this->db->where("((il.laudo_status = 'PUBLICADO') OR (il.laudo_status = 'REPUBLICADO') ) ");
        $this->db->orderby('il.laudo_status');
        $this->db->orderby('il.integracao_laudo_id');
        $query = $this->db->get();
        $return = $query->result();

        $laudosInseridos = array();

        foreach ($return as $value) {
            $laudosInseridos[] = $value->ambulatorio_laudo_id;

            $laudo_texto = $value->laudo_texto;
            $laudo_data_hora = $value->laudo_data_hora;
            $ambulatorio_laudo_id = $value->ambulatorio_laudo_id;
            $medico = $value->medico;
            $revisor = $value->revisor;
            $agenda_exames_id = $value->exame_id;
            $this->db->set('texto', $laudo_texto);
            $this->db->set('situacao', 'FINALIZADO');
            $this->db->set('medico_parecer1', $medico);
            $this->db->set('medico_parecer2', $revisor);
            $this->db->set('data_atualizacao', $laudo_data_hora);
            $this->db->where('ambulatorio_laudo_id', $ambulatorio_laudo_id);
            $this->db->update('tb_ambulatorio_laudo');

            $this->db->set('medico_consulta_id', $medico);
            $this->db->where('agenda_exames_id', $agenda_exames_id);
            $this->db->update('tb_agenda_exames');

            $this->db->set('laudo_status', 'LIDO');
            $this->db->where('exame_id', $agenda_exames_id);
            $this->db->update('tb_integracao_laudo');
        }

        return $laudosInseridos;
//
//
//        $this->db->select('il.integracao_laudo_id,
//                            il.exame_id,
//                            il.laudo_texto,
//                            il.laudo_data_hora,
//                            al.ambulatorio_laudo_id,
//                            il.laudo_status,
//                            al.ambulatorio_laudo_id,
//                            o.operador_id as medico,
//                            op.operador_id as revisor');
//        $this->db->from('tb_integracao_laudo il');
//        $this->db->join('tb_operador o', 'o.conselho = il.laudo_conselho_medico', 'left');
//        $this->db->join('tb_operador op', 'op.conselho = il.laudo_conselho_medico_revisor', 'left');
//        $this->db->join('tb_exames e', 'e.agenda_exames_id = il.exame_id', 'left');
//        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
////        $this->db->where('il.exame_id', $agenda_exames_id);
//        $this->db->where('il.laudo_status', 'REPUBLICADO');
//        $this->db->orderby('il.integracao_laudo_id');
//        $query = $this->db->get();
//        $return = $query->result();
//
//        foreach ($return as $value) {
//            $laudo_texto = $value->laudo_texto;
//            $laudo_data_hora = $value->laudo_data_hora;
//            $ambulatorio_laudo_id = $value->ambulatorio_laudo_id;
//            $medico = $value->medico;
//            $revisor = $value->revisor;
//            $agenda_exames_id = $value->exame_id;
//            $this->db->set('texto', $laudo_texto);
//            $this->db->set('situacao', 'FINALIZADO');
//            $this->db->set('medico_parecer1', $medico);
//            $this->db->set('medico_parecer2', $revisor);
//            $this->db->set('data_atualizacao', $laudo_data_hora);
//            $this->db->where('ambulatorio_laudo_id', $ambulatorio_laudo_id);
//            $this->db->update('tb_ambulatorio_laudo');
//
//            $this->db->set('medico_consulta_id', $medico);
//            $this->db->where('agenda_exames_id', $agenda_exames_id);
//            $this->db->update('tb_agenda_exames');
//
//            $this->db->set('laudo_status', 'LIDO');
//            $this->db->where('exame_id', $agenda_exames_id);
//            $this->db->update('tb_integracao_laudo');
//        }
    }

    function email($paciente_id) {

        $this->db->select('cns');
        $this->db->from('tb_paciente');
        $this->db->where('paciente_id', $paciente_id);
        $return = $this->db->get();
        $result = $return->result();

        return $result[0]->cns;
    }

    function listarempresatipoxml() {
        $empresa_id = $this->session->userdata('empresa_id');

        $this->db->select('t.nome, e.impressao_tipo');
        $this->db->from('tb_empresa e');
        $this->db->join('tb_empresa_tipo_xml t', 't.tipo_xml_id = e.tipo_xml_id', 'left');
        $this->db->where("t.ativo", 't');
        $this->db->where("e.ativo", 't');
        $this->db->where("e.empresa_id", $empresa_id);

        $return = $this->db->get();
        return $return->result();
    }

    function listaradcl($args = array()) {
        $this->db->select('oad.ad_cilindrico_id,oad.numero, oad.nome ');
        $this->db->from('tb_oftamologia_ad_cilindrico oad');
        $this->db->where('oad.ativo ', 't');
        $this->db->orderby('oad.nome ');
        $return = $this->db->get();
        return $return->result();
    }

    function listarades($args = array()) {
        $this->db->select('oad.ad_esferico_id,oad.numero, oad.nome ');
        $this->db->from('tb_oftamologia_ad_esferico oad');
        $this->db->where('oad.ativo ', 't');
        $this->db->orderby('oad.nome ');
        $return = $this->db->get();
        return $return->result();
    }

    function listarodcl($args = array()) {
        $this->db->select('ood.od_cilindrico_id,ood.numero, ood.nome ');
        $this->db->from('tb_oftamologia_od_cilindrico ood');
        $this->db->where('ood.ativo ', 't');
        $this->db->orderby('ood.nome ');
        $return = $this->db->get();
        return $return->result();
    }

    function listarodes($args = array()) {
        $this->db->select('ood.od_esferico_id,ood.numero, ood.nome ');
        $this->db->from('tb_oftamologia_od_esferico ood');
        $this->db->where('ood.ativo ', 't');
        $this->db->orderby('ood.nome ');
        $return = $this->db->get();
        return $return->result();
    }

    function listarodeixo($args = array()) {
        $this->db->select('ood.od_eixo_id,ood.numero, ood.nome ');
        $this->db->from('tb_oftamologia_od_eixo ood');
        $this->db->where('ood.ativo ', 't');
        $this->db->orderby('ood.nome ');
        $return = $this->db->get();
        return $return->result();
    }

    function listarodav($args = array()) {
        $this->db->select('ood.od_av_id,ood.numero, ood.nome ');
        $this->db->from('tb_oftamologia_od_av ood');
        $this->db->where('ood.ativo ', 't');
        $this->db->orderby('ood.nome ');
        $return = $this->db->get();
        return $return->result();
    }

    function listaroecl($args = array()) {
        $this->db->select('ooe.oe_cilindrico_id,ooe.numero, ooe.nome ');
        $this->db->from('tb_oftamologia_oe_cilindrico ooe');
        $this->db->where('ooe.ativo ', 't');
        $this->db->orderby('ooe.nome ');
        $return = $this->db->get();
        return $return->result();
    }

    function listaroees($args = array()) {
        $this->db->select('ooe.oe_esferico_id,ooe.numero, ooe.nome ');
        $this->db->from('tb_oftamologia_oe_esferico ooe');
        $this->db->where('ooe.ativo ', 't');
        $this->db->orderby('ooe.nome ');
        $return = $this->db->get();
        return $return->result();
    }

    function listaroeeixo($args = array()) {
        $this->db->select('ooe.oe_eixo_id,ooe.numero, ooe.nome ');
        $this->db->from('tb_oftamologia_oe_eixo ooe');
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('ooe.numero ilike', "%" . $args['nome'] . "%");
            $this->db->orwhere('ooe.nome ilike', "%" . $args['nome'] . "%");
        }
        $this->db->where('ooe.ativo ', 't');
        $this->db->orderby('ooe.nome ');
        $return = $this->db->get();
        return $return->result();
    }

    function listaroeav($args = array()) {
        $this->db->select('ooe.oe_av_id,ooe.numero, ooe.nome ');
        $this->db->from('tb_oftamologia_oe_av ooe');
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('ooe.numero ilike', "%" . $args['nome'] . "%");
            $this->db->orwhere('ooe.nome ilike', "%" . $args['nome'] . "%");
        }
        $this->db->where('ooe.ativo ', 't');
        $this->db->orderby('ooe.nome ');
        $return = $this->db->get();
        return $return->result();
    }

    function listaracuidadeod($args = array()) {
        $this->db->select('oad.od_acuidade_id,oad.numero, oad.nome ');
        $this->db->from('tb_oftamologia_od_acuidade oad');
        $this->db->where('oad.ativo ', 't');
        $this->db->orderby('oad.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function listaracuidadeoe($args = array()) {
        $this->db->select('oad.oe_acuidade_id,oad.numero, oad.nome ');
        $this->db->from('tb_oftamologia_oe_acuidade oad');
        $this->db->where('oad.ativo ', 't');
        $this->db->orderby('oad.nome ');
        $return = $this->db->get();
        return $return->result();
    }

    function listar($args = array()) {

        $empresa_id = $this->session->userdata('empresa_id');
        $pesquisar_responsavel = $this->listarempresapermissoespesquisa();
        $this->db->select('ag.ambulatorio_laudo_id,
                            ag.paciente_id,
                            ag.data_cadastro,
                            ag.exame_id,
                            ag.situacao,
                            ag.situacao_revisor,
                            o.nome as medico,
                            age.procedimento_tuss_id,
                            op.nome as medicorevisor,
                            age.agenda_exames_nome_id,
                            ag.data_cadastro,
                            p.idade,
                            ag.medico_parecer2,
                            p.nome as paciente');
        $this->db->from('tb_ambulatorio_laudo ag');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_exames ae', 'ae.exames_id = ag.exame_id', 'left');
        $this->db->join('tb_agenda_exames age', 'age.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = age.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c','c.convenio_id = pc.convenio_id','left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ag.medico_parecer2', 'left');
        $this->db->where('pt.grupo !=', 'CONSULTA');
        // $this->db->where('pt.grupo !=', 'ESPECIALIDADE');
        $this->db->where('pt.grupo !=', 'MEDICAMENTO');
        $this->db->where('pt.grupo !=', 'MATERIAL');
        $this->db->where("ag.cancelada", 'false');
        $this->db->where('age.sala_preparo', 'f');




        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $nome = $this->removerCaracterEsp($args['nome']);
            // var_dump($nome); die;
            $where_p = "translate(p.nome,  
            'áàâãäåaaaÁÂÃÄÅAAAÀéèêëeeeeeEEEÉEEÈÊìíîïìiiiÌÍÎÏÌIIIóôõöoooòÒÓÔÕÖOOOùúûüuuuuÙÚÛÜUUUUçÇñÑýÝ',  
            'aaaaaaaaaAAAAAAAAAeeeeeeeeeEEEEEEEEiiiiiiiiIIIIIIIIooooooooOOOOOOOOuuuuuuuuUUUUUUUUcCnNyY'   
            ) ilike '%$nome%'";
            $where_pm = "translate(p.nome_mae,  
            'áàâãäåaaaÁÂÃÄÅAAAÀéèêëeeeeeEEEÉEEÈÊìíîïìiiiÌÍÎÏÌIIIóôõöoooòÒÓÔÕÖOOOùúûüuuuuÙÚÛÜUUUUçÇñÑýÝ',  
            'aaaaaaaaaAAAAAAAAAeeeeeeeeeEEEEEEEEiiiiiiiiIIIIIIIIooooooooOOOOOOOOuuuuuuuuUUUUUUUUcCnNyY'   
            ) ilike '%$nome%'";
            $where_pp = "translate(p.nome_pai,  
            'áàâãäåaaaÁÂÃÄÅAAAÀéèêëeeeeeEEEÉEEÈÊìíîïìiiiÌÍÎÏÌIIIóôõöoooòÒÓÔÕÖOOOùúûüuuuuÙÚÛÜUUUUçÇñÑýÝ',  
            'aaaaaaaaaAAAAAAAAAeeeeeeeeeEEEEEEEEiiiiiiiiIIIIIIIIooooooooOOOOOOOOuuuuuuuuUUUUUUUUcCnNyY'   
            ) ilike '%$nome%'";
            if ($pesquisar_responsavel == 't') {
                $this->db->where("($where_p OR $where_pm OR $where_pp)");
            } else {
                $this->db->where("($where_p)");
            }
            $this->db->where('p.ativo', 'true');
        }


        if (isset($args['data']) && strlen($args['data']) > 0) {
            $this->db->where('ag.data', date("Y-m-d", strtotime(str_replace('/', '-', $args['data']))));
        }

        if (isset($args['sala']) && strlen($args['sala']) > 0) {
            $this->db->where('age.agenda_exames_nome_id', $args['sala']);
        }
        if (isset($args['exame_id']) && strlen($args['exame_id']) > 0) {
            $this->db->where('age.agenda_exames_id', $args['exame_id']);
        }
        if (isset($args['medico']) && strlen($args['medico']) > 0) {
            $this->db->where('ag.medico_parecer1', $args['medico']);
        }
        if (isset($args['prontuario_antigo']) && strlen($args['prontuario_antigo']) > 0) {
            $this->db->where('p.prontuario_antigo', $args['prontuario_antigo']);
        }
        if (isset($args['situacao']) && strlen($args['situacao']) > 0) {
            $this->db->where('ag.situacao', $args['situacao']);
        }
        if (isset($args['medicorevisor']) && strlen($args['medicorevisor']) > 0) {
            $this->db->where('ag.medico_parecer2', $args['medicorevisor']);
        }
        if (isset($args['situacaorevisor']) && strlen($args['situacaorevisor']) > 0) {
            $this->db->where('ag.situacao_revisor', $args['situacaorevisor']);
        }
        if (isset($args['convenios']) && count($args['convenios']) > 0) {
                if (!(in_array("0", $args['convenios']))) {  
                    $this->db->where_in('c.convenio_id', $args['convenios']);
                } 
        }  
        return $this->db;
    }

    function listar2($args = array()) {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $pesquisar_responsavel = $this->listarempresapermissoespesquisa();
        $contador = count($args);
        $empresapesquisa = $this->empresa->listarverificacaopermisao2($this->session->userdata('empresa_id'));
        $entrega_laudos = $empresapesquisa[0]->entrega_laudos;
        $this->db->select('ag.ambulatorio_laudo_id,
                            ag.paciente_id,
                            ag.data_cadastro,
                            ag.data,
                            ag.exame_id,
                            ag.situacao,
                            ag.situacao_revisor,
                            o.nome as medico,
                            age.procedimento_tuss_id,
                            op.nome as medicorevisor,
                            pt.nome as procedimento,
                            p.idade,
                            p.nascimento,
                            ag.medico_parecer1,
                            ae.guia_id,
                            ae.agenda_exames_id,
                            ag.data_cadastro,
                            pt.grupo,
                            age.agenda_exames_nome_id,
                            ag.medico_parecer2,
                            p.nome as paciente,
                            c.nome as convenio,
                            ag.data_antiga,
                            age.data_cadastro as data_cadastro_age,
                            set.nome as setore');
        $this->db->from('tb_ambulatorio_laudo ag');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_exames ae', 'ae.exames_id = ag.exame_id', 'left');
        $this->db->join('tb_agenda_exames age', 'age.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_setores set', 'set.setor_id = age.setores_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ag.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'pc.convenio_id = c.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ag.medico_parecer2', 'left');
        $this->db->where('pt.grupo !=', 'CONSULTA');
        $this->db->where('pt.grupo !=', 'LABORATORIAL');
        $this->db->where('pt.grupo !=', 'MEDICAMENTO');
        $this->db->where('pt.grupo !=', 'MATERIAL');
        $this->db->where("ag.cancelada", 'false');
        $this->db->where('age.sala_preparo', 'f');
        $this->db->orderby('ag.data_cadastro desc');
        $this->db->orderby('ag.situacao');
        $this->db->orderby('ag.data_cadastro');
        if ($contador == 0) {
            $this->db->where('ag.data >=', $data);
        }



        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $nome = $this->removerCaracterEsp($args['nome']);
            // var_dump($nome); die;
            $where_p = "translate(p.nome,  
            'áàâãäåaaaÁÂÃÄÅAAAÀéèêëeeeeeEEEÉEEÈÊìíîïìiiiÌÍÎÏÌIIIóôõöoooòÒÓÔÕÖOOOùúûüuuuuÙÚÛÜUUUUçÇñÑýÝ',  
            'aaaaaaaaaAAAAAAAAAeeeeeeeeeEEEEEEEEiiiiiiiiIIIIIIIIooooooooOOOOOOOOuuuuuuuuUUUUUUUUcCnNyY'   
            ) ilike '%$nome%'";
            $where_pm = "translate(p.nome_mae,  
            'áàâãäåaaaÁÂÃÄÅAAAÀéèêëeeeeeEEEÉEEÈÊìíîïìiiiÌÍÎÏÌIIIóôõöoooòÒÓÔÕÖOOOùúûüuuuuÙÚÛÜUUUUçÇñÑýÝ',  
            'aaaaaaaaaAAAAAAAAAeeeeeeeeeEEEEEEEEiiiiiiiiIIIIIIIIooooooooOOOOOOOOuuuuuuuuUUUUUUUUcCnNyY'   
            ) ilike '%$nome%'";
            $where_pp = "translate(p.nome_pai,  
            'áàâãäåaaaÁÂÃÄÅAAAÀéèêëeeeeeEEEÉEEÈÊìíîïìiiiÌÍÎÏÌIIIóôõöoooòÒÓÔÕÖOOOùúûüuuuuÙÚÛÜUUUUçÇñÑýÝ',  
            'aaaaaaaaaAAAAAAAAAeeeeeeeeeEEEEEEEEiiiiiiiiIIIIIIIIooooooooOOOOOOOOuuuuuuuuUUUUUUUUcCnNyY'   
            ) ilike '%$nome%'";
            if ($pesquisar_responsavel == 't') {
                $this->db->where("($where_p OR $where_pm OR $where_pp)");
            } else {
                $this->db->where("($where_p)");
            }
            $this->db->where('p.ativo', 'true');
        }


        if (isset($args['prontuario_antigo']) && strlen($args['prontuario_antigo']) > 0) {
            $this->db->where('p.prontuario_antigo', $args['prontuario_antigo']);
        }

        if (isset($args['data']) && strlen($args['data']) > 0) {
            $this->db->where('ag.data', date("Y-m-d", strtotime(str_replace('/', '-', $args['data']))));
        }
        if (isset($args['sala']) && strlen($args['sala']) > 0) {
            $this->db->where('age.agenda_exames_nome_id', $args['sala']);
        }
        if (isset($args['exame_id']) && strlen($args['exame_id']) > 0) {
            $this->db->where('age.agenda_exames_id', $args['exame_id']);
        }
        if (isset($args['medico']) && strlen($args['medico']) > 0) {
            $this->db->where('ag.medico_parecer1', $args['medico']);
        }
        if (isset($args['situacao']) && strlen($args['situacao']) > 0) {
            $this->db->where('ag.situacao', $args['situacao']);
        }
        if (isset($args['medicorevisor']) && strlen($args['medicorevisor']) > 0) {
            $this->db->where('ag.medico_parecer2', $args['medicorevisor']);
        }
        if (isset($args['situacaorevisor']) && strlen($args['situacaorevisor']) > 0) {
            $this->db->where('ag.situacao_revisor', $args['situacaorevisor']);
        }
        if (isset($args['convenios']) && count($args['convenios']) > 0) {
                if (!(in_array("0", $args['convenios']))) {  
                    $this->db->where_in('c.convenio_id', $args['convenios']);
                } 
        }
            if (isset($args['setores']) && strlen($args['setores']) > 0) {
                $this->db->where('age.setores_id', $args['setores']);
            }  


        return $this->db;
    }

    function listarconsulta($args = array()) {
        $pesquisar_responsavel = $this->listarempresapermissoespesquisa();
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ag.ambulatorio_laudo_id,
                            ag.paciente_id,
                            ag.data as data_cadastro,
                            ag.exame_id,
                            ag.situacao,
                            ag.situacao_revisor,
                            o.nome as medico,
                            age.procedimento_tuss_id,
                            op.nome as medicorevisor,
                            age.agenda_exames_nome_id,
                            ag.data as data_cadastro,
                            p.idade,
                            ag.medico_parecer2,
                            p.nome as paciente');
        $this->db->from('tb_ambulatorio_laudo ag');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_exames ae', 'ae.exames_id = ag.exame_id', 'left');
        $this->db->join('tb_agenda_exames age', 'age.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = age.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ag.medico_parecer2', 'left');
        $this->db->where('ag.empresa_id', $empresa_id);
        $this->db->where('pt.grupo', 'CONSULTA');
        $this->db->where("ag.cancelada", 'false');
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $nome = $this->removerCaracterEsp($args['nome']);
            // var_dump($nome); die;
            $where_p = "translate(p.nome,  
            'áàâãäåaaaÁÂÃÄÅAAAÀéèêëeeeeeEEEÉEEÈÊìíîïìiiiÌÍÎÏÌIIIóôõöoooòÒÓÔÕÖOOOùúûüuuuuÙÚÛÜUUUUçÇñÑýÝ',  
            'aaaaaaaaaAAAAAAAAAeeeeeeeeeEEEEEEEEiiiiiiiiIIIIIIIIooooooooOOOOOOOOuuuuuuuuUUUUUUUUcCnNyY'   
                ) ilike '%$nome%'";
            $where_pm = "translate(p.nome_mae,  
            'áàâãäåaaaÁÂÃÄÅAAAÀéèêëeeeeeEEEÉEEÈÊìíîïìiiiÌÍÎÏÌIIIóôõöoooòÒÓÔÕÖOOOùúûüuuuuÙÚÛÜUUUUçÇñÑýÝ',  
            'aaaaaaaaaAAAAAAAAAeeeeeeeeeEEEEEEEEiiiiiiiiIIIIIIIIooooooooOOOOOOOOuuuuuuuuUUUUUUUUcCnNyY'   
                ) ilike '%$nome%'";
            $where_pp = "translate(p.nome_pai,  
            'áàâãäåaaaÁÂÃÄÅAAAÀéèêëeeeeeEEEÉEEÈÊìíîïìiiiÌÍÎÏÌIIIóôõöoooòÒÓÔÕÖOOOùúûüuuuuÙÚÛÜUUUUçÇñÑýÝ',  
            'aaaaaaaaaAAAAAAAAAeeeeeeeeeEEEEEEEEiiiiiiiiIIIIIIIIooooooooOOOOOOOOuuuuuuuuUUUUUUUUcCnNyY'   
                ) ilike '%$nome%'";
            if ($pesquisar_responsavel == 't') {
                $this->db->where("($where_p OR $where_pm OR $where_pp)");
            } else {
                $this->db->where("($where_p)");
            }
        }
        if (isset($args['data']) && strlen($args['data']) > 0) {
            $this->db->where('ag.data', date("Y-m-d", strtotime(str_replace('/', '-', $args['data']))));
        }
        if (isset($args['sala']) && strlen($args['sala']) > 0) {
            $this->db->where('age.agenda_exames_nome_id', $args['sala']);
        }
        if (isset($args['exame_id']) && strlen($args['exame_id']) > 0) {
            $this->db->where('age.agenda_exames_id', $args['exame_id']);
        }
        if (isset($args['medico']) && strlen($args['medico']) > 0) {
            $this->db->where('ag.medico_parecer1', $args['medico']);
        }
        if (isset($args['situacao']) && strlen($args['situacao']) > 0) {
            $this->db->where('ag.situacao', $args['situacao']);
        }
        if (isset($args['medicorevisor']) && strlen($args['medicorevisor']) > 0) {
            $this->db->where('ag.medico_parecer2', $args['medicorevisor']);
        }
        if (isset($args['situacaorevisor']) && strlen($args['situacaorevisor']) > 0) {
            $this->db->where('ag.situacao_revisor', $args['situacaorevisor']);
        }
        if (isset($args['convenios']) && count($args['convenios']) > 0) {
                if (!(in_array("0", $args['convenios']))) {  
                    $this->db->where_in('c.convenio_id', $args['convenios']);
                } 
        } 
        return $this->db;
    }

    function listar2consulta($args = array()) {
        $pesquisar_responsavel = $this->listarempresapermissoespesquisa();
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ag.ambulatorio_laudo_id,
                            ag.paciente_id,
                            ag.data_cadastro,
                            ag.data,
                            ag.exame_id,
                            ag.situacao,
                            ag.situacao_revisor,
                            o.nome as medico,
                            age.procedimento_tuss_id,
                            op.nome as medicorevisor,
                            pt.nome as procedimento,
                            p.idade,
                            p.nascimento,
                            ag.medico_parecer1,
                            ae.guia_id,
                            ae.exames_id,
                            ae.sala_id,
                            ae.agenda_exames_id,
                            c.nome as convenio,
                            ag.data_cadastro,
                            age.agenda_exames_nome_id,
                            ag.medico_parecer2,
                            p.nome as paciente');
        $this->db->from('tb_ambulatorio_laudo ag');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_exames ae', 'ae.exames_id = ag.exame_id', 'left');
        $this->db->join('tb_agenda_exames age', 'age.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ag.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ag.medico_parecer2', 'left');
        $this->db->where('ag.empresa_id', $empresa_id);
        $this->db->where('pt.grupo', 'CONSULTA');
        $this->db->where("ag.cancelada", 'false');
        $this->db->orderby('ag.data_cadastro desc');
        $this->db->orderby('ag.situacao');
        $this->db->orderby('ag.data_cadastro');
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $nome = $this->removerCaracterEsp($args['nome']);
            // var_dump($nome); die;
            $where_p = "translate(p.nome,  
            'áàâãäåaaaÁÂÃÄÅAAAÀéèêëeeeeeEEEÉEEÈÊìíîïìiiiÌÍÎÏÌIIIóôõöoooòÒÓÔÕÖOOOùúûüuuuuÙÚÛÜUUUUçÇñÑýÝ',  
            'aaaaaaaaaAAAAAAAAAeeeeeeeeeEEEEEEEEiiiiiiiiIIIIIIIIooooooooOOOOOOOOuuuuuuuuUUUUUUUUcCnNyY'   
                ) ilike '%$nome%'";
            $where_pm = "translate(p.nome_mae,  
            'áàâãäåaaaÁÂÃÄÅAAAÀéèêëeeeeeEEEÉEEÈÊìíîïìiiiÌÍÎÏÌIIIóôõöoooòÒÓÔÕÖOOOùúûüuuuuÙÚÛÜUUUUçÇñÑýÝ',  
            'aaaaaaaaaAAAAAAAAAeeeeeeeeeEEEEEEEEiiiiiiiiIIIIIIIIooooooooOOOOOOOOuuuuuuuuUUUUUUUUcCnNyY'   
                ) ilike '%$nome%'";
            $where_pp = "translate(p.nome_pai,  
            'áàâãäåaaaÁÂÃÄÅAAAÀéèêëeeeeeEEEÉEEÈÊìíîïìiiiÌÍÎÏÌIIIóôõöoooòÒÓÔÕÖOOOùúûüuuuuÙÚÛÜUUUUçÇñÑýÝ',  
            'aaaaaaaaaAAAAAAAAAeeeeeeeeeEEEEEEEEiiiiiiiiIIIIIIIIooooooooOOOOOOOOuuuuuuuuUUUUUUUUcCnNyY'   
                ) ilike '%$nome%'";
            if ($pesquisar_responsavel == 't') {
                $this->db->where("($where_p OR $where_pm OR $where_pp)");
            } else {
                $this->db->where("($where_p)");
            }
        }
        if (isset($args['data']) && strlen($args['data']) > 0) {
            $this->db->where('ag.data', date("Y-m-d", strtotime(str_replace('/', '-', $args['data']))));
        }
        if (isset($args['sala']) && strlen($args['sala']) > 0) {
            $this->db->where('age.agenda_exames_nome_id', $args['sala']);
        }
        if (isset($args['exame_id']) && strlen($args['exame_id']) > 0) {
            $this->db->where('age.agenda_exames_id', $args['exame_id']);
        }
        if (isset($args['medico']) && strlen($args['medico']) > 0) {
            $this->db->where('ag.medico_parecer1', $args['medico']);
        }
        if (isset($args['situacao']) && strlen($args['situacao']) > 0) {
            $this->db->where('ag.situacao', $args['situacao']);
        }
        if (isset($args['medicorevisor']) && strlen($args['medicorevisor']) > 0) {
            $this->db->where('ag.medico_parecer2', $args['medicorevisor']);
        }
        if (isset($args['situacaorevisor']) && strlen($args['situacaorevisor']) > 0) {
            $this->db->where('ag.situacao_revisor', $args['situacaorevisor']);
        }
        if (isset($args['convenios']) && count($args['convenios']) > 0) {
                if (!(in_array("0", $args['convenios']))) {  
                    $this->db->where_in('c.convenio_id', $args['convenios']);
                } 
            }
        return $this->db;
    }

    function listarconsultahistorico($paciente_id) {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ag.ambulatorio_laudo_id,
                            ag.data as data_cadastro,
                            ag.situacao,
                            o.nome as medico,
                            ag.texto,
                            ag.enfermagem,
                            age.procedimento_tuss_id,
                            pt.nome as procedimento,
                            ag.medico_parecer1,
                            ae.agenda_exames_id,
                            c.nome as convenio,
                            age.agenda_exames_nome_id,
                            p.nome as paciente');
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
//        $this->db->where('ag.empresa_id', $empresa_id);
        $this->db->where('agr.tipo', 'CONSULTA');
        $this->db->where("ag.cancelada", 'false');
        $this->db->orderby('ag.data_cadastro desc');
        $this->db->orderby('ag.situacao');
        $this->db->orderby('ag.data');
        $return = $this->db->get()->result();
//        echo '<pre>';
//        var_dump($return); die;
        return $return;
    }
    function listarconsultahistoricoreceita($laudo_id) {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ar.texto,
                          ar.data_cadastro,
                          o.nome as medicoreceita');
        $this->db->from('tb_ambulatorio_receituario ar');
        $this->db->join('tb_ambulatorio_laudo ag','ar.laudo_id = ag.ambulatorio_laudo_id','left');
        $this->db->join('tb_operador o', 'o.operador_id = ar.medico_parecer1', 'left');
        $this->db->where('ar.laudo_id', $laudo_id);
        $return = $this->db->get()->result();
//        echo '<pre>';
//        var_dump($return); die;
        return $return;
    }

    function listarconsultahistoricoreceitaespecial($laudo_id) {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('are.texto,
                          are.data_cadastro,
                          o.nome as medicoreceita');
        $this->db->from('tb_ambulatorio_receituario_especial are');
        $this->db->join('tb_ambulatorio_laudo ag','are.laudo_id = ag.ambulatorio_laudo_id','left');
        $this->db->join('tb_operador o', 'o.operador_id = are.medico_parecer1', 'left');
        $this->db->where('are.laudo_id', $laudo_id);
        $return = $this->db->get()->result();
//        echo '<pre>';
//        var_dump($return); die;
        return $return;
    }

    function listarconsultahistoricosolicitarexame($laudo_id) {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ae.texto,
                          ae.data_cadastro,
                          o.nome as medicoreceita');
        $this->db->from('tb_ambulatorio_exame ae');
        $this->db->join('tb_ambulatorio_laudo ag','ae.laudo_id = ag.ambulatorio_laudo_id','left');
        $this->db->join('tb_operador o', 'o.operador_id = ae.medico_parecer1', 'left');
        $this->db->where('ae.laudo_id', $laudo_id);
        $return = $this->db->get()->result();
//        echo '<pre>';
//        var_dump($return); die;
        return $return;
    }

    function listarconsultahistoricoatestado($laudo_id) {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('aa.texto,
                          aa.data_cadastro,
                          o.nome as medicoreceita');
        $this->db->from('tb_ambulatorio_atestado aa');
        $this->db->join('tb_ambulatorio_laudo ag','aa.laudo_id = ag.ambulatorio_laudo_id','left');
        $this->db->join('tb_operador o', 'o.operador_id = aa.medico_parecer1', 'left');
        $this->db->where('aa.laudo_id', $laudo_id);
        $return = $this->db->get()->result();
//        echo '<pre>';
//        var_dump($return); die;
        return $return;
    }


    function listarconsultahistoricodiferenciado($paciente_id, $ambulatorio_laudo_id=NULL, $adendo = false) {
        // print_r($adendo);
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ag.ambulatorio_laudo_id,
                            ag.data as data_cadastro,
                            ag.situacao,
                            o.nome as medico,
                            ag.texto,
                            ag.enfermagem,
                            age.procedimento_tuss_id,
                            pt.nome as procedimento,
                            ag.medico_parecer1,
                            ae.agenda_exames_id,
                            c.nome as convenio,
                            age.agenda_exames_nome_id,
                            p.nome as paciente');
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
        if($adendo){
            // $this->db->where('ag.ambulatorio_laudo_id !=', $ambulatorio_laudo_id);
        }else if ($ambulatorio_laudo_id != "") { 
         $this->db->where('ag.ambulatorio_laudo_id !=', $ambulatorio_laudo_id);
        }
                    
        $this->db->where('agr.tipo', 'CONSULTA');
        $this->db->where("ag.cancelada", 'false');
        $this->db->orderby('ag.data_cadastro desc');
        $this->db->orderby('ag.situacao');
        $this->db->orderby('ag.data');
        $return = $this->db->get()->result();
//        echo '<pre>';
//        var_dump($return); die;
        return $return;
    }

    function listarespecialidadeparecer() {

        $this->db->select('ep.especialidade_parecer_id, 
                            ep.nome
                               ');
        $this->db->from('tb_especialidade_parecer ep');
        $this->db->where('ep.ativo', 't');
        $return = $this->db->get();
        return $return->result();
    }

    function listardatashistorico($paciente_id,$tipo=NULL) {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select(' distinct(ag.data_cadastro),
                            ag.ambulatorio_laudo_id,                            
                            ag.situacao,
                            o.nome as medico,
                            ag.texto,
                            age.procedimento_tuss_id,
                            pt.nome as procedimento,
                            ag.medico_parecer1,
                            ae.agenda_exames_id,
                            c.nome as convenio,
                            age.agenda_exames_nome_id,
                            p.nome as paciente');
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
        if($tipo != ""){
            if($tipo == "exame"){
                $this->db->where('agr.tipo','EXAME');
            }else{
                $this->db->where('agr.tipo !=','EXAME');
            }
        }
        $this->db->where("ag.cancelada", 'false');
        $this->db->orderby('ag.data_cadastro desc');
        $this->db->orderby('ag.situacao');
        $this->db->orderby('ag.data_cadastro');
        $return = $this->db->get()->result();
//        echo '<pre>';
//        var_dump($return); die;
        return $return;
    }

    function listardatashistoricopordia($paciente_id, $dataescolhida,$tipo=null) {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select(' distinct(ag.data_cadastro),
                            ag.ambulatorio_laudo_id,                            
                            ag.situacao,
                            o.nome as medico,
                            ag.texto,
                            age.procedimento_tuss_id,
                            pt.nome as procedimento,
                            ag.medico_parecer1,
                            ae.agenda_exames_id,
                            c.nome as convenio,
                            age.agenda_exames_nome_id,
                            p.nome as paciente,
                            ae.exames_id,
                            ag.obj_evolucao');
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
        $this->db->where('ag.data_cadastro >=', $dataescolhida . " " . "00:00:00");
        $this->db->where('ag.data_cadastro <=', $dataescolhida . " " . "23:59:59");
        $this->db->where("ag.cancelada", 'false');
        $this->db->orderby('ag.data_cadastro desc');
        $this->db->orderby('ag.situacao');
        $this->db->orderby('ag.data_cadastro');
        if($tipo != ""){
            if($tipo == "exame"){
                $this->db->where('agr.tipo','EXAME');
            }else{
                $this->db->where('agr.tipo !=','EXAME');
            }
        }
        $return = $this->db->get()->result();
//        echo '<pre>';
//        var_dump($return); die;
        return $return;
    }

    function listarxmllaudo($args = array()) {
//        var_dump($_POST['convenio'] , $_POST['medico'],$_POST['paciente']);
//        die;

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('pt.codigo , g.ambulatorio_guia_id,
                            ae.valor_total,
                            ae.valor,
                            ae.autorizacao,
                            p.convenionumero,
                            p.nome as paciente,
                            p.nascimento,
                            op.nome as medicosolicitante,
                            op.conselho as conselhosolicitante,
                            o.nome as medico,
                            o.conselho,
                            o.cbo_ocupacao_id,
                            o.cpf,
                            ae.data_autorizacao,
                            ae.data_realizacao,                            
                            tu.descricao as procedimento,
                            ae.data,
                            pt.grupo,
                            c.nome as convenio,
                            tuc.nome as classificacao,
                            ae.quantidade,
                            c.registroans,
                            c.codigoidentificador,
                            e.data_cadastro,
                            e.data_atualizacao,
                            g.data_criacao,
                            g.guiaconvenio,
                            ae.paciente_id,
                            al.texto_laudo,
                            al.ambulatorio_laudo_id,
                            i.wkl_accnumber,
                            i.wkl_procstep_descr');
        $this->db->from('tb_ambulatorio_guia g');
        $this->db->join('tb_agenda_exames ae', 'ae.guia_id = g.ambulatorio_guia_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_tuss tu', 'tu.tuss_id = pt.tuss_id', 'left');
        $this->db->join('tb_tuss_classificacao tuc', 'tuc.tuss_classificacao_id = tu.classificacao', 'left');
        $this->db->join('tb_exame_sala an', 'an.exame_sala_id = ae.agenda_exames_nome_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id= ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = al.medico_parecer1', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ae.medico_solicitante', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_integracao_naja i', 'i.wkl_exame_id = ae.agenda_exames_id', 'left');
        $this->db->where("c.dinheiro", 'f');
        $this->db->where('ae.ativo', 'false');
        $this->db->where('ae.cancelada', 'false');
        if (isset($_POST['datainicio']) && strlen($_POST['datainicio']) > 0) {
            $this->db->where('ae.data >=', $_POST['datainicio']);
        }
        if ($_POST['empresa'] != "0") {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['medico'] != "0") {
            $this->db->where('al.medico_parecer1', $_POST['medico']);
        }
        if (isset($_POST['datafim']) && strlen($_POST['datafim']) > 0) {
            $this->db->where('ae.data <=', $_POST['datafim']);
        }
        if (isset($_POST['convenio']) && $_POST['convenio'] != "") {
            $this->db->where('pc.convenio_id', $_POST['convenio']);
        }
        if (isset($_POST['paciente_id']) && $_POST['paciente_id'] != "") {
            $this->db->where('p.paciente_id', $_POST['paciente_id']);
        }
        $return = $this->db->get();

//        var_dump($return->result());
//        die;
        return $return->result();
    }

    function listarxmlsalvar($ambulatorio_laudo_id, $exame_id, $sala_id) {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('pt.codigo , g.ambulatorio_guia_id,
                            ae.valor_total,
                            ae.valor,
                            ae.autorizacao,
                            an.nome as sala, 
                            p.convenionumero,
                            p.nome as paciente,
                            p.nascimento,
                            p.sexo,
                            p.paciente_id,
                            op.nome as medicosolicitante,
                            op.conselho as conselhosolicitante,
                            o.nome as medico,
                            o.conselho,
                            o.cbo_ocupacao_id,
                            o.cpf,
                            ae.data_autorizacao,
                            ae.data_realizacao,                            
                            tu.descricao as procedimento,
                            al.data,
                            al.medico_parecer1,
                            al.medico_parecer2,
                            pt.grupo,
                            c.nome as convenio,
                            tuc.nome as classificacao,
                            ae.quantidade,
                            c.registroans,
                            c.convenio_pasta,
                            c.codigoidentificador,
                            e.data_cadastro,
                            e.data_atualizacao,
                            g.data_criacao,
                            ae.guiaconvenio,
                            ae.paciente_id,
                            al.situacao,
                            al.texto_laudo,
                            al.texto,
                            al.ambulatorio_laudo_id,
                            i.wkl_accnumber,
                            i.wkl_procstep_descr');
        $this->db->from('tb_ambulatorio_laudo al');
        $this->db->join('tb_exames e', 'e.exames_id= al.exame_id', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_guia g', 'ae.guia_id = g.ambulatorio_guia_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_tuss tu', 'tu.tuss_id = pt.tuss_id', 'left');
        $this->db->join('tb_tuss_classificacao tuc', 'tuc.tuss_classificacao_id = tu.classificacao', 'left');
        $this->db->join('tb_exame_sala an', 'an.exame_sala_id = ae.agenda_exames_nome_id', 'left');
//        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = al.medico_parecer1', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ae.medico_solicitante', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_integracao_naja i', 'i.wkl_exame_id = ae.agenda_exames_id', 'left');
        $this->db->where("c.dinheiro", 'f');
        $this->db->where('ae.ativo', 'false');
        $this->db->where('ae.cancelada', 'false');
//        $this->db->where('an.exame_sala_id', $sala_id);
//        $this->db->where('e.exames_id', $exame_id);
        $this->db->where('al.ambulatorio_laudo_id', $ambulatorio_laudo_id);
        $return = $this->db->get();

        return $return->result();
    }

    function chamarpacientesalaespera($agenda_exames_id) {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select(' o.nome as medico,
                            an.exame_sala_id,
                            cbo.descricao,
                            o.ocupacao_painel,
                            e.numero_empresa_painel,
                            p.nome as paciente');
        $this->db->from('tb_agenda_exames ag');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ag.procedimento_tuss_id', 'left');
        $this->db->join('tb_exame_sala an', 'an.exame_sala_id = ag.agenda_exames_nome_id', 'left');
        $this->db->join('tb_empresa e', 'e.empresa_id = an.empresa_id');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_consulta_id', 'left');
        $this->db->join('tb_cbo_ocupacao cbo', 'cbo.cbo_ocupacao_id = o.cbo_ocupacao_id', 'left');
        $this->db->where('ag.agenda_exames_id', $agenda_exames_id);
        $return = $this->db->get()->result();


        $this->db->select(' nome_chamada,
                            painel_id');
        $this->db->from('tb_exame_sala_painel');
        $this->db->where('exame_sala_id', $return[0]->exame_sala_id);
        $this->db->where('ativo', 't');
        $paineis = $this->db->get()->result();

        $config['hostname'] = "localhost";
        $config['username'] = "postgres";
        $config['password'] = "123456";
        $config['database'] = "painelWeb";
        $config['dbdriver'] = "postgre";
        $config['dbprefix'] = "public.";
        $config['pconnect'] = FALSE;
        $config['db_debug'] = TRUE;
        $config['active_r'] = TRUE;
        $config['cachedir'] = "";
        $config['char_set'] = "utf8";
        $config['dbcollat'] = "utf8_general_ci";
        $DB1 = $this->load->database($config, TRUE);

        foreach ($paineis as $value) {
            $salas = $value->nome_chamada;
            $data = date("Y-m-d H:i:s");
            if ($return[0]->ocupacao_painel == 't') {
                $medico = $return[0]->descricao;
            } else {
                $medico = '';
            }
//            var_dump($medico); die;

            if ($value->painel_id != '') {
                $painel_id = $return[0]->numero_empresa_painel . $value->painel_id;
            } else {
                $painel_id = $return[0]->numero_empresa_painel . 1;
            }

            $paciente = $return[0]->paciente;
            $superior = 'Paciente: ' . $paciente;
            $inferior = $salas . ' ' . $medico;
            $sql = "INSERT INTO chamado(
                data, linha_inferior, linha_superior, setor_id)
        VALUES ('$data', '$inferior', '$superior', $painel_id);";
            $DB1->query($sql);
        }
    }

    function chamada($ambulatorio_laudo_id) {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ag.ambulatorio_laudo_id,
                            o.nome as medico,
                            ag.medico_parecer1,
                            o.ocupacao_painel,
                            an.exame_sala_id,
                            cbo.descricao,
                            p.nome as paciente,
                            p.cpf,
                            e.numero_empresa_painel,
                            ag.idfila_painel');
        $this->db->from('tb_ambulatorio_laudo ag');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_exames ae', 'ae.exames_id = ag.exame_id', 'left');
        $this->db->join('tb_agenda_exames age', 'age.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ag.procedimento_tuss_id', 'left');
        $this->db->join('tb_exame_sala an', 'an.exame_sala_id = age.agenda_exames_nome_id', 'left');
        $this->db->join('tb_empresa e', 'e.empresa_id = an.empresa_id');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_cbo_ocupacao cbo', 'cbo.cbo_ocupacao_id = o.cbo_ocupacao_id', 'left');
        $this->db->where('ag.ambulatorio_laudo_id', $ambulatorio_laudo_id);
        $return = $this->db->get()->result();


        $this->db->select(' nome_chamada,
                            painel_id');
        $this->db->from('tb_exame_sala_painel');
        $this->db->where('exame_sala_id', $return[0]->exame_sala_id);
        $this->db->where('ativo', 't');
        $paineis = $this->db->get()->result();

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('endereco_toten');
        $this->db->from('tb_empresa');
        $this->db->where('empresa_id', $empresa_id);
        $dados = $this->db->get()->result();

        if ($dados[0]->endereco_toten != '') {
            if ($return[0]->idfila_painel == '') {
                $this->db->select('id');
                $this->db->from('tb_toten_senha');
                $this->db->where('chamada', 'f');
                $paineis = $this->db->get()->result();
                $idfila = $paineis[0]->id;
            } else {
                $idfila = $return[0]->idfila_painel;
            }

            $endereco = $dados[0]->endereco_toten . "/webService/telaAtendimento/enviarFicha/";
            $endereco .= "{$idfila}/{$return[0]->paciente}/null/{$return[0]->medico_parecer1}/{$return[0]->medico}/{$return[0]->exame_sala_id}/true";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endereco);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, array());
            $result = curl_exec($ch);
            curl_close($ch);

            $endereco = $dados[0]->endereco_toten . "/webService/telaChamado/proximo/{$return[0]->medico_parecer1}";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endereco);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, array());
            $result = curl_exec($ch);
            curl_close($ch);
        } else {
            $config['hostname'] = "localhost";
            $config['username'] = "postgres";
            $config['password'] = "123456";
            $config['database'] = "painelWeb";
            $config['dbdriver'] = "postgre";
            $config['dbprefix'] = "public.";
            $config['pconnect'] = FALSE;
            $config['db_debug'] = TRUE;
            $config['active_r'] = TRUE;
            $config['cachedir'] = "";
            $config['char_set'] = "utf8";
            $config['dbcollat'] = "utf8_general_ci";
            $DB1 = $this->load->database($config, TRUE);

            foreach ($paineis as $value) {
                $salas = $value->nome_chamada;
                $data = date("Y-m-d H:i:s");
                if ($return[0]->ocupacao_painel == 't') {
                    $medico = $return[0]->descricao;
                } else {
                    $medico = '';
                }
                //            var_dump($medico); die;
                if ($value->painel_id != '') {
                    $painel_id = $return[0]->numero_empresa_painel . $value->painel_id;
                } else {
                    $painel_id = $return[0]->numero_empresa_painel . 1;
                }

                $paciente = $return[0]->paciente;
                $superior = 'Paciente: ' . $paciente;
                $inferior = $salas . ' ' . $medico;
                $sql = "INSERT INTO chamado(
                    data, linha_inferior, linha_superior, setor_id)
            VALUES ('$data', '$inferior', '$superior', $painel_id);";
                $DB1->query($sql);
            }
        }
    }

    function chamadaconsulta($ambulatorio_laudo_id) {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ag.ambulatorio_laudo_id,
                            o.nome as medico,
                            o.ocupacao_painel,
                            an.exame_sala_id,
                            cbo.descricao,
                            p.nome as paciente,
                            e.numero_empresa_painel');
        $this->db->from('tb_ambulatorio_laudo ag');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_exames ae', 'ae.exames_id = ag.exame_id', 'left');
        $this->db->join('tb_agenda_exames age', 'age.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ag.procedimento_tuss_id', 'left');
        $this->db->join('tb_exame_sala an', 'an.exame_sala_id = age.agenda_exames_nome_id', 'left');
        $this->db->join('tb_empresa e', 'e.empresa_id = an.empresa_id');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_cbo_ocupacao cbo', 'cbo.cbo_ocupacao_id = o.cbo_ocupacao_id', 'left');
        $this->db->where('ag.ambulatorio_laudo_id', $ambulatorio_laudo_id);
        $return = $this->db->get()->result();

        $this->db->select(' nome_chamada,
                            painel_id');
        $this->db->from('tb_exame_sala_painel');
        $this->db->where('exame_sala_id', $return[0]->exame_sala_id);
        $this->db->where('ativo', 't');
        $paineis = $this->db->get()->result();


        $config['hostname'] = "localhost";
        $config['username'] = "postgres";
        $config['password'] = "123456";
        $config['database'] = "painelWeb";
        $config['dbdriver'] = "postgre";
        $config['dbprefix'] = "public.";
        $config['pconnect'] = FALSE;
        $config['db_debug'] = TRUE;
        $config['active_r'] = TRUE;
        $config['cachedir'] = "";
        $config['char_set'] = "utf8";
        $config['dbcollat'] = "utf8_general_ci";
        $DB1 = $this->load->database($config, TRUE);
//            $DB1 = $this->load->database('group_one', TRUE);
        foreach ($paineis as $value) {
            $salas = $value->nome_chamada;
            $data = date("Y-m-d H:i:s");
            if ($return[0]->ocupacao_painel == 't') {
                $medico = $return[0]->descricao;
            } else {
                $medico = '';
            }
            if ($value->painel_id != '') {
                $painel_id = $return[0]->numero_empresa_painel . $value->painel_id;
            } else {
                $painel_id = $return[0]->numero_empresa_painel . 1;
            }

            $paciente = $return[0]->paciente;
            $superior = 'Paciente: ' . $paciente;
            $inferior = $salas . ' ' . $medico;
            $sql = "INSERT INTO chamado(
                data, linha_inferior, linha_superior, setor_id)
        VALUES ('$data', '$inferior', '$superior', $painel_id);";
            $DB1->query($sql);
        }
    }

    function editaranaminesehistorico() {
        // var_dump($_POST['laudoantigo_id']); die;
        $this->db->set('laudo', $_POST['laudo']);
        $this->db->where('laudoantigo_id', $_POST['laudoantigo_id']);
        $this->db->update('tb_laudoantigo');
    }

    function listarconsultahistoricoantigoeditar($laudoantigo_id) {

        $this->db->select('laudo, data_cadastro, laudoantigo_id, paciente_id');
        $this->db->from('tb_laudoantigo');
        $this->db->where('laudoantigo_id', $laudoantigo_id);
//        $this->db->orderby('data_cadastro desc');
        $return = $this->db->get();
        return $return->result();
    }

    function listarconsultahistoricoantigo($paciente_id) {

        $this->db->select('laudo, data_cadastro, laudoantigo_id, paciente_id, nrcategoria, nomemedicolaudo');
        $this->db->from('tb_laudoantigo');
        $this->db->where('paciente_id', $paciente_id);
        $this->db->orderby('data_cadastro desc, nrcategoria desc');
        // $this->db->orderby('');
        $return = $this->db->get();
        return $return->result();
    }

    function listarconsultahistoricoweb($paciente_id) {

        $this->db->select('ali.*, o.nome as medico_integracao');
        $this->db->from('tb_ambulatorio_laudo_integracao ali');
        $this->db->join('tb_operador o', 'ali.medico_id = o.operador_id', 'left');
        $this->db->where('paciente_id', $paciente_id);
        $this->db->where('tipo', 'CONSULTA');
        $this->db->orderby('data_cadastro desc');
        $return = $this->db->get();
        return $return->result();
    }

    function listarexamehistoricoweb($paciente_id) {

        $this->db->select('ali.*, o.nome as medico_integracao');
        $this->db->from('tb_ambulatorio_laudo_integracao ali');
        $this->db->join('tb_operador o', 'ali.medico_id = o.operador_id', 'left');
        $this->db->where('paciente_id', $paciente_id);
        $this->db->where('tipo', 'EXAME');
        $this->db->orderby('data_cadastro desc');
        $return = $this->db->get();
        return $return->result();
    }

    function listarespecialidadehistoricoweb($paciente_id) {

        $this->db->select('ali.*,o.nome as medico_integracao');
        $this->db->from('tb_ambulatorio_laudo_integracao ali');
        $this->db->join('tb_operador o', 'ali.medico_id = o.operador_id', 'left');
        $this->db->where('paciente_id', $paciente_id);
        $this->db->where('tipo', 'ESPECIALIDADE');
        $this->db->orderby('data_cadastro desc');
        $return = $this->db->get();
        return $return->result();
    }

    function listarhistoricoantigo2($args = array()) {

        $this->db->select('distinct(la.paciente_id), p.nome as paciente');
        $this->db->from('tb_laudoantigo la');
        $this->db->join('tb_paciente p', 'p.paciente_id = la.paciente_id', 'left');
//        $this->db->where('p.nome ', null);
        $this->db->orderby('p.nome');
        if (isset($args['prontuario']) && strlen($args['prontuario']) > 0) {
            $this->db->where('la.paciente_id ', $args['prontuario']);
        }
        if (isset($args['paciente'])) {

            $this->db->where('p.nome ilike', '%' . $args['paciente'] . '%');
        }
        return $this->db;
    }

    function listarhistoricoantigo($args = array()) {

        $this->db->select('distinct(la.paciente_id)');
        $this->db->from('tb_laudoantigo la');
        $this->db->join('tb_paciente p', 'p.paciente_id = la.paciente_id', 'left');
//        $this->db->where('p.nome ', null);
        if (isset($args['prontuario']) && strlen($args['prontuario']) > 0) {
            $this->db->where('la.paciente_id ', $args['prontuario']);
        }
        if (isset($args['paciente'])) {
            $this->db->where('p.nome ilike', '%' . $args['paciente'] . '%');
        }
        return $this->db;
    }

    function listarnomeendoscopia() {

        $this->db->select('nome');
        $this->db->from('tb_ambulatorio_nome_endoscopia');
        $this->db->where('ativo', 'true');
        $this->db->orderby('nome');
        $return = $this->db->get();
        return $return->result();
    }

    function contadorimagem($exame_id, $sequencia) {

        $this->db->select('nome');
        $this->db->from('tb_ambulatorio_nome_endoscopia_imagem');
        $this->db->where('exame_id', $exame_id);
        $this->db->where('ambulatorio_nome_endoscopia', $sequencia);
        $return = $this->db->get();
        return $return->result();
    }

    function deletarregistroimagem($exame_id, $imagem_id) {

        $this->db->where('exame_id', $exame_id);
        $this->db->where('ambulatorio_nome_endoscopia', $imagem_id);
        $this->db->delete('tb_ambulatorio_nome_endoscopia_imagem');
        $erro = $this->db->_error_message();
    }

    function deletarnomesimagens($exame_id) {

        $this->db->where('exame_id', $exame_id);
        $this->db->delete('tb_ambulatorio_nome_endoscopia_imagem');
    }

    function contadorimagem2($exame_id, $imagem_id) {

        $this->db->select('nome');
        $this->db->from('tb_ambulatorio_nome_endoscopia_imagem');
        $this->db->where('exame_id', $exame_id);
        $this->db->where('ambulatorio_nome_endoscopia', $imagem_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listarnomeimagem($exame_id) {

        $this->db->select('nome');
        $this->db->from('tb_ambulatorio_nome_endoscopia_imagem');
        $this->db->where('exame_id', $exame_id);
        $this->db->orderby('nome');
        $return = $this->db->get();
        return $return->result();
    }

    function gravarnome($exame_id, $imagem_id, $novonome, $sequencia) {
        try {
            /* inicia o mapeamento no banco */
            $this->db->set('nome', trim($novonome));
            $this->db->set('exame_id', $exame_id);
            $this->db->set('ambulatorio_nome_endoscopia', $sequencia);
            $this->db->insert('tb_ambulatorio_nome_endoscopia_imagem');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") { // erro de banco
                return false;
            }
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function alterarnome($exame_id, $imagem_id, $novonome, $sequencia) {
        try {
            /* inicia o mapeamento no banco */
            $this->db->set('nome', trim($novonome));
            $this->db->set('ambulatorio_nome_endoscopia', $sequencia);
            $this->db->where('exame_id', $exame_id);
            $this->db->where('ambulatorio_nome_endoscopia', trim($_POST['imagem_id']));
            $this->db->update('tb_ambulatorio_nome_endoscopia_imagem');

            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function listarreceitahistorico($paciente_id) {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('al.ambulatorio_laudo_id,
                            ag.data_cadastro,
                            o.nome as medico,
                            ag.texto,
                            age.procedimento_tuss_id,
                            pt.nome as procedimento,
                            ag.medico_parecer1,
                            ae.agenda_exames_id,
                            age.agenda_exames_nome_id,
                            p.nome as paciente,
                            ag.ambulatorio_receituario_id,
                            ag.texto,
                            ag.data_cadastro,
                            ag.medico_parecer1');
        $this->db->from('tb_ambulatorio_receituario ag');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.ambulatorio_laudo_id = ag.laudo_id', 'left');
        $this->db->join('tb_exames ae', 'ae.exames_id = al.exame_id', 'left');
        $this->db->join('tb_agenda_exames age', 'age.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ag.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->where('ag.paciente_id', $paciente_id);
        $this->db->where('ag.empresa_id', $empresa_id);
        $this->db->orderby('ag.data_cadastro desc');
        $this->db->orderby('ag.data_cadastro');
        $return = $this->db->get();
        return $return->result();
    }

    function listarreceita($paciente_id,$tipo=NULL) {

        $this->db->select(' ag.ambulatorio_receituario_id,
                            ag.texto,
                            ag.data_cadastro,
                            ag.medico_parecer1,
                            ag.especial,
                            al.cabecalho,
                            o.nome as medico,
                            o.operador_id,
                            pt.nome as procedimento
                            ');
        $this->db->from('tb_ambulatorio_receituario ag');
        $this->db->join('tb_ambulatorio_laudo al', 'al.ambulatorio_laudo_id = ag.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = al.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ag.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_ambulatorio_grupo agr', 'agr.nome = pt.grupo', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = al.medico_parecer1', 'left');
        $this->db->where('al.paciente_id', $paciente_id);
        $this->db->where('ag.tipo', 'NORMAL');
        
//         if($tipo != ""){
//            if($tipo == "exame"){
//                $this->db->where('agr.tipo','EXAME');
//            }else{
//                $this->db->where('agr.tipo !=','EXAME');
//            }
//        }
        $this->db->orderby('ag.data_cadastro DESC');

        $return = $this->db->get();
        return $return->result();
    }

    function listarreceitaconsulta($ambulatorio_laudo_id, $adendo = FALSE) {

        $this->db->select(' ag.ambulatorio_receituario_id,
                            ag.texto,
                            ag.data_cadastro,
                            ag.medico_parecer1,
                            ag.especial,
                            ag.receita_id,
                            al.cabecalho,
                            o.nome as medico,
                            o.operador_id,
                            pt.nome as procedimento,
                            mr.nome
                            ');
        $this->db->from('tb_ambulatorio_receituario ag');
        $this->db->join('tb_ambulatorio_modelo_receita mr', 'mr.ambulatorio_modelo_receita_id = ag.receita_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.ambulatorio_laudo_id = ag.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = al.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ag.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = al.medico_parecer1', 'left');
        $this->db->where('ag.laudo_id', $ambulatorio_laudo_id);
        if($adendo){
        $this->db->where('ag.adendo', 't');
        }
        $this->db->where('ag.tipo', 'NORMAL');
        $this->db->where('ag.especial', 'f');
        $this->db->where('ag.ativo', 't');
        $this->db->orderby('ag.data_cadastro DESC');

        $return = $this->db->get();
        return $return->result();
    }

    function datalistarreceitaconsultaantigo($ambulatorio_laudo_id, $paciente_id) {

        $this->db->select('al.data');
        $this->db->from('tb_ambulatorio_receituario ag');
        $this->db->join('tb_ambulatorio_modelo_receita mr', 'mr.ambulatorio_modelo_receita_id = ag.receita_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.ambulatorio_laudo_id = ag.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = al.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ag.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = al.medico_parecer1', 'left');
        $this->db->where('ag.laudo_id !=', $ambulatorio_laudo_id);
        $this->db->where('al.paciente_id', $paciente_id);
        $this->db->where('ag.tipo', 'NORMAL');
        $this->db->where('ag.especial', 'f');
        $this->db->where('ag.ativo', 't');
        $this->db->orderby('al.data DESC');
        $this->db->limit('1');

        $return = $this->db->get()->result();
        if(count($return) > 0){
            return $return[0]->data;  
        }else{
            return '1939-12-31';
        }
    }

    function listarreceitaconsultaantigo($ambulatorio_laudo_id, $paciente_id, $data, $adendo) {

        $this->db->select(' ag.ambulatorio_receituario_id,
                            ag.texto,
                            ag.data_cadastro,
                            ag.medico_parecer1,
                            ag.especial,
                            al.cabecalho,
                            o.nome as medico,
                            o.operador_id,
                            pt.nome as procedimento,
                            mr.nome,
                            al.data
                            ');
        $this->db->from('tb_ambulatorio_receituario ag');
        $this->db->join('tb_ambulatorio_modelo_receita mr', 'mr.ambulatorio_modelo_receita_id = ag.receita_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.ambulatorio_laudo_id = ag.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = al.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ag.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = al.medico_parecer1', 'left');
        if($adendo){
            $this->db->where('ag.laudo_id', $ambulatorio_laudo_id);
            $this->db->where('ag.adendo', 'f');
        }else{
            $this->db->where('ag.laudo_id !=', $ambulatorio_laudo_id);
            $this->db->where('al.data', $data);
        }
        $this->db->where('al.paciente_id', $paciente_id);
        $this->db->where('ag.tipo', 'NORMAL');
        $this->db->where('ag.ativo', 't');
        $this->db->where('ag.especial', 'f');
        $this->db->orderby('al.data DESC');

        $return = $this->db->get();
        return $return->result();
    }

    function listarreceitaespecialconsulta($ambulatorio_laudo_id, $adendo = false) {

        $this->db->select(' ag.ambulatorio_receituario_id,
                            ag.texto,
                            ag.data_cadastro,
                            ag.medico_parecer1,
                            ag.especial,
                            ag.receita_id,
                            al.cabecalho,
                            o.nome as medico,
                            o.operador_id,
                            pt.nome as procedimento,
                            mr.nome
                            ');
        $this->db->from('tb_ambulatorio_receituario ag');
        $this->db->join('tb_ambulatorio_modelo_receita_especial mr', 'mr.ambulatorio_modelo_receita_especial_id = ag.receita_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.ambulatorio_laudo_id = ag.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = al.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ag.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = al.medico_parecer1', 'left');
        $this->db->where('ag.laudo_id', $ambulatorio_laudo_id);
        if($adendo){
            $this->db->where('ag.adendo', 't');
        }
        $this->db->where('ag.tipo', 'NORMAL');
        $this->db->where('ag.ativo', 't');
        $this->db->where('ag.especial', 't');
        $this->db->orderby('ag.data_cadastro DESC');

        $return = $this->db->get();
        return $return->result();
    }

    function datalistarreceitaespecialconsultaantigo($ambulatorio_laudo_id, $paciente_id) {

        $this->db->select('al.data');
        $this->db->from('tb_ambulatorio_receituario ag');
        $this->db->join('tb_ambulatorio_modelo_receita_especial mr', 'mr.ambulatorio_modelo_receita_especial_id = ag.receita_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.ambulatorio_laudo_id = ag.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = al.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ag.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = al.medico_parecer1', 'left');
        $this->db->where('ag.laudo_id !=', $ambulatorio_laudo_id);
        $this->db->where('al.paciente_id', $paciente_id);
        $this->db->where('ag.tipo', 'NORMAL');
        $this->db->where('ag.especial', 't');
        $this->db->where('ag.ativo', 't');
        $this->db->orderby('al.data DESC');
        $this->db->limit('1');

        $return = $this->db->get()->result();
        if(count($return) > 0){
            return $return[0]->data;  
        }else{
            return '1939-12-31';
        }
    }

    function listarreceitaespecialconsultaantigo($ambulatorio_laudo_id, $paciente_id, $data, $adendo) {

        $this->db->select(' ag.ambulatorio_receituario_id,
                            ag.texto,
                            ag.data_cadastro,
                            ag.medico_parecer1,
                            ag.especial,
                            al.cabecalho,
                            o.nome as medico,
                            o.operador_id,
                            pt.nome as procedimento,
                            mr.nome,
                            al.data
                            ');
        $this->db->from('tb_ambulatorio_receituario ag');
        $this->db->join('tb_ambulatorio_modelo_receita_especial mr', 'mr.ambulatorio_modelo_receita_especial_id = ag.receita_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.ambulatorio_laudo_id = ag.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = al.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ag.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = al.medico_parecer1', 'left');
        if($adendo){
            $this->db->where('ag.laudo_id', $ambulatorio_laudo_id);
            $this->db->where('ag.adendo', 'f');
        }else{
            $this->db->where('ag.laudo_id !=', $ambulatorio_laudo_id);
            $this->db->where('al.data', $data);
        }
        $this->db->where('al.paciente_id', $paciente_id);
        $this->db->where('ag.tipo', 'NORMAL');
        $this->db->where('ag.ativo', 't');
        $this->db->where('ag.especial', 't');
        $this->db->orderby('al.data DESC');

        $return = $this->db->get();
        return $return->result();
    }

    function listarexamesconsulta($ambulatorio_laudo_id, $adendo = false) {

        $this->db->select(' ag.ambulatorio_exame_id,
                            ag.texto,
                            ag.data_cadastro,
                            ag.medico_parecer1,
                            mr.nome
                            ');
        $this->db->from('tb_ambulatorio_exame ag');
        $this->db->join('tb_ambulatorio_modelo_solicitar_exames mr', 'mr.ambulatorio_modelo_solicitar_exames_id = ag.exame_id', 'left');
        $this->db->where('ag.laudo_id', $ambulatorio_laudo_id);
        if($adendo){
            $this->db->where('ag.adendo', 't');
        }
        $this->db->where('ag.tipo', 'NORMAL');
        $this->db->where('ag.ativo', 't');
        $this->db->orderby('ag.data_cadastro DESC');

        $return = $this->db->get();
        return $return->result();
    }

    function datalistarexamesconsultaantigo($ambulatorio_laudo_id, $paciente_id) {

        $this->db->select('al.data');
        $this->db->from('tb_ambulatorio_exame ag');
        $this->db->join('tb_ambulatorio_modelo_solicitar_exames mr', 'mr.ambulatorio_modelo_solicitar_exames_id = ag.exame_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.ambulatorio_laudo_id = ag.laudo_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = al.medico_parecer1', 'left');
        $this->db->where('ag.laudo_id !=', $ambulatorio_laudo_id);
        $this->db->where('al.paciente_id', $paciente_id);
        $this->db->where('ag.tipo', 'NORMAL');
        $this->db->where('ag.ativo', 't');
        $this->db->orderby('al.data DESC');
        $this->db->limit('1');

        $return = $this->db->get()->result();
        if(count($return) > 0){
            return $return[0]->data;  
        }else{
            return '1939-12-31';
        }
    }

    function listarexamesconsultaantigo($ambulatorio_laudo_id, $paciente_id, $data, $adendo) {

        $this->db->select(' ag.ambulatorio_exame_id,
                            ag.texto,
                            ag.data_cadastro,
                            ag.medico_parecer1,
                            mr.nome,
                            al.data,
                            o.nome as medico
                            ');
        $this->db->from('tb_ambulatorio_exame ag');
        $this->db->join('tb_ambulatorio_modelo_solicitar_exames mr', 'mr.ambulatorio_modelo_solicitar_exames_id = ag.exame_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.ambulatorio_laudo_id = ag.laudo_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = al.medico_parecer1', 'left');
        if($adendo){
            $this->db->where('ag.laudo_id', $ambulatorio_laudo_id);
            $this->db->where('ag.adendo', 'f');
        }else{
            $this->db->where('ag.laudo_id !=', $ambulatorio_laudo_id);
            $this->db->where('al.data', $data);
        }
        $this->db->where('al.paciente_id', $paciente_id);
        $this->db->where('ag.ativo', 't');
        $this->db->where('ag.tipo', 'NORMAL');
        $this->db->orderby('al.data DESC');

        $return = $this->db->get();
        return $return->result();
    }

    function listarterapeuticasconsulta($ambulatorio_laudo_id, $adendo = false) {

        $this->db->select(' ag.ambulatorio_terapeutica_id,
                            ag.texto,
                            ag.data_cadastro,
                            ag.medico_parecer1,
                            mr.nome
                            ');
        $this->db->from('tb_ambulatorio_terapeuticas ag');
        $this->db->join('tb_ambulatorio_modelo_terapeuticas_id mr', 'mr.ambulatorio_modelo_terapeuticas_id = ag.terapeuticas_id', 'left');
        $this->db->where('ag.laudo_id', $ambulatorio_laudo_id);
        if($adendo){
            $this->db->where('ag.adendo', 't');
        }
        $this->db->orderby('ag.data_cadastro DESC');
        $this->db->where('ag.ativo', 't');

        $return = $this->db->get();
        return $return->result();
    }

    function datalistarterapeuticasconsultaantigo($ambulatorio_laudo_id, $paciente_id) {

        $this->db->select('al.data');
        $this->db->from('tb_ambulatorio_terapeuticas ag');
        $this->db->join('tb_ambulatorio_modelo_terapeuticas_id mr', 'mr.ambulatorio_modelo_terapeuticas_id = ag.terapeuticas_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.ambulatorio_laudo_id = ag.laudo_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = al.medico_parecer1', 'left');
        $this->db->where('ag.laudo_id !=', $ambulatorio_laudo_id);
        $this->db->where('al.paciente_id', $paciente_id);
        $this->db->where('ag.ativo', 't');
        $this->db->orderby('al.data DESC');
        $this->db->limit('1');

        $return = $this->db->get()->result();
        if(count($return) > 0){
            return $return[0]->data;  
        }else{
            return '1939-12-31';
        }
    }

    function listarterapeuticasconsultaantigo($ambulatorio_laudo_id, $paciente_id, $data, $adendo) {

        $this->db->select(' ag.ambulatorio_terapeutica_id,
                            ag.texto,
                            ag.data_cadastro,
                            ag.medico_parecer1,
                            mr.nome,
                            al.data,
                            o.nome as medico
                            ');
        $this->db->from('tb_ambulatorio_terapeuticas ag');
        $this->db->join('tb_ambulatorio_modelo_terapeuticas_id mr', 'mr.ambulatorio_modelo_terapeuticas_id = ag.terapeuticas_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.ambulatorio_laudo_id = ag.laudo_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = al.medico_parecer1', 'left');
        if($adendo){
            $this->db->where('ag.laudo_id', $ambulatorio_laudo_id);
            $this->db->where('ag.adendo', 'f');
        }else{
            $this->db->where('ag.laudo_id !=', $ambulatorio_laudo_id);
            $this->db->where('al.data', $data);
        }
        $this->db->where('ag.ativo', 't');
        $this->db->where('al.paciente_id', $paciente_id);
        $this->db->orderby('al.data DESC');

        $return = $this->db->get();
        return $return->result();
    }


    function listarrelatorioconsulta($ambulatorio_laudo_id, $adendo) {

        $this->db->select(' ag.ambulatorio_relatorio_id,
                            ag.texto,
                            ag.data_cadastro,
                            ag.medico_parecer1,
                            mr.nome
                            ');
        $this->db->from('tb_ambulatorio_relatorio ag');
        $this->db->join('tb_ambulatorio_modelo_relatorio mr', 'mr.ambulatorio_modelo_relatorio_id = ag.relatorio_id', 'left');
        $this->db->where('ag.laudo_id', $ambulatorio_laudo_id);
        if($adendo){
            $this->db->where('ag.adendo', 't');
        }
        $this->db->orderby('ag.data_cadastro DESC');
        $this->db->where('ag.ativo', 't');

        $return = $this->db->get();
        return $return->result();
    }

    function datalistarrelatorioconsultaantigo($ambulatorio_laudo_id, $paciente_id) {

        $this->db->select('al.data');
        $this->db->from('tb_ambulatorio_relatorio ag');
        $this->db->join('tb_ambulatorio_modelo_relatorio mr', 'mr.ambulatorio_modelo_relatorio_id = ag.relatorio_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.ambulatorio_laudo_id = ag.laudo_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = al.medico_parecer1', 'left');
        $this->db->where('ag.laudo_id !=', $ambulatorio_laudo_id);
        $this->db->where('al.paciente_id', $paciente_id);
        $this->db->where('ag.ativo', 't');
        $this->db->orderby('al.data DESC');
        $this->db->limit('1');

        $return = $this->db->get()->result();
        if(count($return) > 0){
            return $return[0]->data;  
        }else{
            return '1939-12-31';
        }
    }

    function listarrelatorioconsultaantigo($ambulatorio_laudo_id, $paciente_id, $data, $adendo) {

        $this->db->select(' ag.ambulatorio_relatorio_id,
                            ag.texto,
                            ag.data_cadastro,
                            ag.medico_parecer1,
                            mr.nome,
                            al.data,
                            o.nome as medico
                            ');
        $this->db->from('tb_ambulatorio_relatorio ag');
        $this->db->join('tb_ambulatorio_modelo_relatorio mr', 'mr.ambulatorio_modelo_relatorio_id = ag.relatorio_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.ambulatorio_laudo_id = ag.laudo_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = al.medico_parecer1', 'left');
        if($adendo){
            $this->db->where('ag.laudo_id', $ambulatorio_laudo_id);
            $this->db->where('ag.adendo', 'f');
        }else{
            $this->db->where('ag.laudo_id !=', $ambulatorio_laudo_id);
            $this->db->where('al.data', $data);
        }
        $this->db->where('ag.ativo', 't');
        $this->db->where('al.paciente_id', $paciente_id);
        $this->db->orderby('al.data DESC');

        $return = $this->db->get();
        return $return->result();
    }
    
    function salvarreceita_rapido($ambulatorio_receituario_id, $texto){

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');

        $this->db->set('texto', $texto);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->set('data_atualizacao', $horario);
        $this->db->where('ambulatorio_receituario_id', $ambulatorio_receituario_id);
        $this->db->update('tb_ambulatorio_receituario');

        return 'Sucesso';
    }

    function salvarexames_rapido($ambulatorio_exame_id, $texto){

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');

        $this->db->set('texto', $texto);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->set('data_atualizacao', $horario);
        $this->db->where('ambulatorio_exame_id', $ambulatorio_exame_id);
        $this->db->update('tb_ambulatorio_exame');

        return 'Sucesso';
    }

    function salvarterapeuticas_rapido($ambulatorio_terapeutica_id, $texto){

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');

        $this->db->set('texto', $texto);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->set('data_atualizacao', $horario);
        $this->db->where('ambulatorio_terapeutica_id', $ambulatorio_terapeutica_id);
        $this->db->update('tb_ambulatorio_terapeuticas');

        return 'Sucesso';
    }

    function salvarrelatorios_rapido($ambulatorio_relatorio_id, $texto){

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');

        $this->db->set('texto', $texto);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->set('data_atualizacao', $horario);
        $this->db->where('ambulatorio_relatorio_id', $ambulatorio_relatorio_id);
        $this->db->update('tb_ambulatorio_relatorio');

        return 'Sucesso';
    }

    function excluirmodeloreceita($id){
        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');

        $this->db->set('ativo', 'f');
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->set('data_atualizacao', $horario);
        $this->db->where('ambulatorio_receituario_id', $id);
        $this->db->update('tb_ambulatorio_receituario');
    }

    function excluirmodelorelatorio($id){
        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');

        $this->db->set('ativo', 'f');
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->set('data_atualizacao', $horario);
        $this->db->where('ambulatorio_relatorio_id', $id);
        $this->db->update('tb_ambulatorio_relatorio');
    }

    function excluirmodeloterapeutica($id){
        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');

        $this->db->set('ativo', 'f');
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->set('data_atualizacao', $horario);
        $this->db->where('ambulatorio_terapeutica_id', $id);
        $this->db->update('tb_ambulatorio_terapeuticas');
    }

    function excluirmodeloexames($id){
        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');

        $this->db->set('ativo', 'f');
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->set('data_atualizacao', $horario);
        $this->db->where('ambulatorio_exame_id', $id);
        $this->db->update('tb_ambulatorio_exame');
    }

    function marcarnovareceita($ambulatorio_laudo_id, $tipo, $receita_id){
        $this->db->select('ambulatorio_id, impressoes_laudo_id');
        $this->db->from('tb_ambulatorio_impressoes_laudo');
        $this->db->where('ativo', 't');
        $this->db->where('tipo', $tipo);
        $this->db->where('laudo_id', $ambulatorio_laudo_id);
        $return = $this->db->get()->result();


        $lista = json_decode($return[0]->ambulatorio_id);
        array_push($lista, $receita_id);
        $nova_lista = json_encode($lista);



        $this->db->set('ambulatorio_id', $nova_lista);
        $this->db->where('ambulatorio_id', $return[0]->ambulatorio_id);
        $this->db->update('tb_ambulatorio_impressoes_laudo');
    }

    function listarreceitasimpressoes($ambulatorio_relatorio_id, $adendo){
        $this->db->select('laudo_id, tipo, ambulatorio_id');
        $this->db->from('tb_ambulatorio_impressoes_laudo');
        $this->db->where('tipo', 'RECEITAS');
        $this->db->where('ativo', 't');
        if($adendo){
            $this->db->where('adendo', 't');
        }
        $this->db->where('laudo_id', $ambulatorio_relatorio_id);

        $return = $this->db->get();
        return $return->result();
    }

    function listarexamesimpressoes($ambulatorio_relatorio_id, $adendo){
        $this->db->select('laudo_id, tipo, ambulatorio_id');
        $this->db->from('tb_ambulatorio_impressoes_laudo');
        $this->db->where('tipo', 'EXAMES');
        $this->db->where('ativo', 't');
        if($adendo){
            $this->db->where('adendo', 't');
        }
        $this->db->where('laudo_id', $ambulatorio_relatorio_id);

        $return = $this->db->get();
        return $return->result();
    }

    function listarterapeuticasimpressoes($ambulatorio_relatorio_id, $adendo){
        $this->db->select('laudo_id, tipo, ambulatorio_id');
        $this->db->from('tb_ambulatorio_impressoes_laudo');
        $this->db->where('tipo', 'TERAPEUTICAS');
        $this->db->where('ativo', 't');
        if($adendo){
            $this->db->where('adendo', 't');
        }
        $this->db->where('laudo_id', $ambulatorio_relatorio_id);

        $return = $this->db->get();
        return $return->result();
    }

    function listarrelatorioimpressoes($ambulatorio_relatorio_id, $adendo){
        $this->db->select('laudo_id, tipo, ambulatorio_id');
        $this->db->from('tb_ambulatorio_impressoes_laudo');
        $this->db->where('tipo', 'RELATORIOS');
        $this->db->where('ativo', 't');
        if($adendo){
            $this->db->where('adendo', 't');
        }
        $this->db->where('laudo_id', $ambulatorio_relatorio_id);

        $return = $this->db->get();
        return $return->result();
    }

    function apagarimpressoeslaudo($ambulatorio_laudo_id){
        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');

        $this->db->set('ativo', 'f');
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->set('data_atualizacao', $horario);
        $this->db->where('laudo_id', $ambulatorio_laudo_id);
        $this->db->update('tb_ambulatorio_impressoes_laudo');


        $this->db->set('ativo', 'f');
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->set('data_atualizacao', $horario);
        $this->db->where('laudo_id', $ambulatorio_laudo_id);
        $this->db->update('tb_ambulatorio_visualizar_impressao');
    }

    function gravarimpressoes($ambulatorio_id, $tipo, $ambulatorio_laudo_id, $adendo){
        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');

        if (count($ambulatorio_id) > 0) {
            $this->db->set('ambulatorio_id', json_encode($ambulatorio_id));
        } else {
            $this->db->set('ambulatorio_id', '');
        }

            $this->db->set('tipo', $tipo);
            $this->db->set('laudo_id', $ambulatorio_laudo_id);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->set('data_cadastro', $horario);
            if($adendo){
                $this->db->set('adendo', 't');
            }
            $this->db->insert('tb_ambulatorio_impressoes_laudo');

    }


    function gravarimpressoes_antigo($ambulatorio_id, $tipo, $ambulatorio_laudo_id, $adendo){
        $this->db->select('tipo, impressoes_laudo_id, ambulatorio_id');
        $this->db->from('tb_ambulatorio_impressoes_laudo');
        $this->db->where('tipo', $tipo);
        $this->db->where('laudo_id', $ambulatorio_laudo_id);
        $this->db->where('ativo', 't');
        $return = $this->db->get()->result();

        if(count($return) > 0){
            $array_ids = json_decode($return[0]->ambulatorio_id);
            $uniao_ids = array_merge($array_ids, $ambulatorio_id);

            $this->db->set('ativo', 'f');
            $this->db->where('impressoes_laudo_id', $return[0]->impressoes_laudo_id);
            $this->db->update('tb_ambulatorio_impressoes_laudo');
        }else{
            $uniao_ids = $ambulatorio_id;
        }

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');

            $this->db->set('ambulatorio_id', json_encode($uniao_ids));
            $this->db->set('tipo', $tipo);
            $this->db->set('laudo_id', $ambulatorio_laudo_id);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->set('data_cadastro', $horario);
            if($adendo){
                $this->db->set('adendo', 't');
            }
            $this->db->insert('tb_ambulatorio_impressoes_laudo');
    }

    function listareceituarioslaudo($ambulatorio_laudo_id) {

        $this->db->select(' ag.ambulatorio_receituario_id,
                            ag.texto,
                            ag.data_cadastro,
                            ag.medico_parecer1,
                            ag.especial,
                            al.cabecalho,
                            o.nome as medico,
                            o.operador_id,
                            pt.nome as procedimento
                            ');
        $this->db->from('tb_ambulatorio_receituario ag');
        $this->db->join('tb_ambulatorio_laudo al', 'al.ambulatorio_laudo_id = ag.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = al.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ag.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = al.medico_parecer1', 'left');
        $this->db->where('al.ambulatorio_laudo_id', $ambulatorio_laudo_id);
        $this->db->where('ag.tipo', 'NORMAL');
        $this->db->orderby('ag.data_cadastro DESC');

        $return = $this->db->get();
        return $return->result();
    }

    function listaranotacaoprivada($ambulatorio_laudo_id, $medico_id) {

        $this->db->select(' ag.ambulatorio_privado_id,
                            ag.texto,
                            ag.data_cadastro,
                            ag.medico_parecer1,
                            o.nome as medico,
                            o.operador_id
                            ');
        $this->db->from('tb_ambulatorio_privado ag');
        $this->db->join('tb_ambulatorio_laudo al', 'al.ambulatorio_laudo_id = ag.ambulatorio_laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = al.paciente_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = al.medico_parecer1', 'left');
        $this->db->where('ag.ambulatorio_laudo_id', $ambulatorio_laudo_id);
        $this->db->where('ag.medico_parecer1', $medico_id);
        $this->db->orderby('ag.data_cadastro DESC');

        $return = $this->db->get();
        return $return->result();
    }

    function listarreceitalaudo($ambulatorio_laudo_id) {

        $this->db->select(' ag.ambulatorio_receituario_id,
                            ag.texto,
                            ag.data_cadastro,
                            ag.medico_parecer1,
                            ag.especial,
                            al.cabecalho,
                            o.nome as medico,
                            o.operador_id,
                            pt.nome as procedimento
                            ');
        $this->db->from('tb_ambulatorio_receituario ag');
        $this->db->join('tb_ambulatorio_laudo al', 'al.ambulatorio_laudo_id = ag.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = al.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ag.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = al.medico_parecer1', 'left');
        $this->db->where('al.ambulatorio_laudo_id', $ambulatorio_laudo_id);
        // $this->db->where('ag.tipo', 'NORMAL');
        $this->db->orderby('ag.data_cadastro DESC');

        $return = $this->db->get();
        return $return->result();
    }

    function listarrotina($paciente_id) {

        $this->db->select(' ar.ambulatorio_rotinas_id,
                            ar.texto,
                            ar.data_cadastro,
                            ar.medico_parecer1,
                            al.cabecalho,
                            o.nome as medico,
                            o.operador_id,
                            pt.nome as procedimento
                            ');
        $this->db->from('tb_ambulatorio_rotinas ar');
        $this->db->join('tb_ambulatorio_laudo al', 'al.ambulatorio_laudo_id = ar.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = al.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ar.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = al.medico_parecer1', 'left');
        $this->db->where('al.paciente_id', $paciente_id);
        $this->db->where('ar.tipo', 'NORMAL');
        $this->db->orderby('ar.data_cadastro DESC');

        $return = $this->db->get();
        return $return->result();
    }

    function listarprescricao($paciente_id, $ambulatorio_laudo_id) {

        $this->db->select(' rs.receituario_sollis_id,
                            rs.cid_id,
                            rs.paciente_id,
                            rs.laudo_id,
                            rs.frequencia,
                            rs.frequnit,
                            rs.qtdmed,
                            rs.medid,
                            rs.periodo,
                            rs.perunit,
                            rs.observacao,
                            rs.medico_parecer1,
                            rs.prescricao_id
                            
                            ');
        $this->db->from('tb_receituario_sollis rs');
        $this->db->where('rs.paciente_id', $paciente_id);
        $this->db->where('rs.laudo_id', $ambulatorio_laudo_id);
        $this->db->where('rs.ativo', 'TRUE');
        $this->db->orderby('rs.data_cadastro DESC');

        $return = $this->db->get();
        return $return->result();
    }

    function listarprescricoes($paciente_id) {

        $this->db->select(' p.paciente_id,
                            p.laudo_id,
                            p.prescricao_id,
                            p.prescricao,
                            p.data_cadastro,
                            p.ativo
                            
                            ');
        $this->db->from('tb_prescricao p');
        $this->db->where('p.ativo', 'TRUE');
        $this->db->where('p.paciente_id', $paciente_id);
        $this->db->orderby('p.data_cadastro DESC');

        $return = $this->db->get();
        return $return->result();
    }

    function listarprescricoespaciente($paciente_id) {

        $this->db->select(' p.paciente_id,                            
                            p.ativo,
                            p.medico_parecer1,
                            pa.nome,
                            pa.nascimento,
                            pa.idade,
                            o.nome as medico
                            
                            
                            ');
        $this->db->from('tb_prescricao p');
        $this->db->join('tb_paciente pa', 'p.paciente_id = pa.paciente_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = p.medico_parecer1', 'left');
//        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where('p.ativo', 'TRUE');
        $this->db->where('p.paciente_id', $paciente_id);
        $this->db->orderby('p.data_cadastro DESC');

        $return = $this->db->get();
        return $return->result();
    }

    function gravarprescricao($ambulatorio_laudo_id) {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            $this->db->select('al.paciente_id, al.medico_parecer1 ');
            $this->db->from('tb_ambulatorio_laudo al');
            $this->db->where('ambulatorio_laudo_id', $ambulatorio_laudo_id);
            $return = $this->db->get()->result();

//            var_dump($return);die;          
//            $this->db->select('setor_cadastro_id');
//            $this->db->from('tb_setor_cadastro');
//            $this->db->where("ativo", 't');
//            $this->db->where("setor_id", $setor_id);            
//            $return = $this->db->get()->result();
//            if (count($return) == 0) {
            $prescricao = $_POST['prescricao'];
//                var_dump($prescricao);die;
            $this->db->set('laudo_id', $ambulatorio_laudo_id);
            $this->db->set('medico_parecer1', $return[0]->medico_parecer1);
            $this->db->set('paciente_id', $return[0]->paciente_id);
            $this->db->set('prescricao', $prescricao);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_prescricao');

//            }
//            else {
//                $funcao_id = $_POST['txtfuncao_id'];
//                foreach($funcao_id as $item){
//                $this->db->select('setor_cadastro_id');
//                $this->db->from('tb_setor_cadastro');
//                $this->db->where("ativo", 't');
//                $this->db->where("setor_id", $setor_id);            
//                $this->db->where("funcao_id", $item);            
//                $return2 = $this->db->get()->result();
//                
//                    if (count($return2) == 0) {
//                    $this->db->set('empresa_id', $convenio_id);
//                    $this->db->set('setor_id', $setor_id);
//                    $this->db->set('funcao_id', $item);
//                    $this->db->set('risco_id', $array_risco);
//                    $this->db->set('data_atualizacao', $horario);
//                    $this->db->set('operador_atualizacao', $operador_id);
//                    $this->db->where('setor_id', $setor_id);
//                    $this->db->insert('tb_setor_cadastro');
//                    }
//                }
//            }
            return $setor_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function excluirprescricao($prescricao_id) {

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('prescricao_id', $prescricao_id);
        $this->db->update('tb_prescricao');
        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return -1;
        else
            return 0;
    }

    function excluirmedicamento($receituario_sollis_id) {

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('receituario_sollis_id', $receituario_sollis_id);
        $this->db->update('tb_receituario_sollis');
        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return -1;
        else
            return 0;
    }

    function listareditarreceita($ambulatorio_laudo_id) {

        $this->db->select(' ag.ambulatorio_receituario_id ,
                            ag.texto,
                            ag.medico_parecer1');
        $this->db->from('tb_ambulatorio_receituario ag');
        $this->db->where('ag.ambulatorio_receituario_id', $ambulatorio_laudo_id);
        $this->db->where('ag.tipo', 'NORMAL');
        $return = $this->db->get();
        return $return->result();
    }

    function listareditarrotina($ambulatorio_laudo_id) {

        $this->db->select(' ar.ambulatorio_rotinas_id ,
                            ar.texto,
                            ar.medico_parecer1');
        $this->db->from('tb_ambulatorio_rotinas ar');
        $this->db->where('ar.ambulatorio_rotinas_id', $ambulatorio_laudo_id);
        $this->db->where('ar.tipo', 'NORMAL');
        $return = $this->db->get();
        return $return->result();
    }

    function listarrepetirreceita($ambulatorio_laudo_id) {

        $this->db->select(' ag.ambulatorio_receituario_id ,
                            ag.data_cadastro,
                            ag.texto,
                            ag.medico_parecer1');
        $this->db->from('tb_ambulatorio_receituario ag');
        $this->db->where('ag.ambulatorio_receituario_id', $ambulatorio_laudo_id);
        $this->db->where('ag.tipo', 'NORMAL');
        $return = $this->db->get();
        return $return->result();
    }

    function listaratestado($ambulatorio_laudo_id) {

        $this->db->select(' ag.ambulatorio_atestado_id,
                            ag.texto,
                            ag.data_cadastro,
                            ag.medico_parecer1');
        $this->db->from('tb_ambulatorio_atestado ag');
        $this->db->where('ag.laudo_id', $ambulatorio_laudo_id);
        $this->db->where('ag.tipo', 'NORMAL');
        $this->db->orderby('ag.data_cadastro DESC');
        $return = $this->db->get();
        return $return->result();
    }

    function listareditaratestado($ambulatorio_laudo_id) {

        $this->db->select(' ag.ambulatorio_atestado_id ,
                            ag.texto,
                            ag.medico_parecer1');
        $this->db->from('tb_ambulatorio_atestado ag');
        $this->db->where('ag.ambulatorio_atestado_id', $ambulatorio_laudo_id);
        $this->db->where('ag.tipo', 'NORMAL');
        $return = $this->db->get();
        return $return->result();
    }

    function listarexame($ambulatorio_laudo_id) {

        $this->db->select(' ag.ambulatorio_exame_id,
                            ag.texto,
                            ag.data_cadastro,
                            o.nome as medico,
                            ag.medico_parecer1');
        $this->db->from('tb_ambulatorio_exame ag');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->where('ag.laudo_id', $ambulatorio_laudo_id);
        $this->db->where('ag.tipo', 'NORMAL');
        $this->db->orderby('ambulatorio_exame_id');
        $return = $this->db->get();
        return $return->result();
    }

    function listarterapeuticas($ambulatorio_laudo_id) {

        $this->db->select(' ag.ambulatorio_terapeutica_id,
                            ag.texto,
                            ag.data_cadastro,
                            o.nome as medico,
                            ag.medico_parecer1');
        $this->db->from('tb_ambulatorio_terapeuticas ag');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->where('ag.laudo_id', $ambulatorio_laudo_id);
        $this->db->orderby('ag.ambulatorio_terapeutica_id');
        $return = $this->db->get();
        return $return->result();
    }

    function listarrelatorios($ambulatorio_laudo_id) {

        $this->db->select(' ag.ambulatorio_relatorio_id,
                            ag.texto,
                            ag.data_cadastro,
                            o.nome as medico,
                            ag.medico_parecer1,
                            ag.laudo_id');
        $this->db->from('tb_ambulatorio_relatorio ag');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->where('ag.laudo_id', $ambulatorio_laudo_id);
        $this->db->orderby('ag.ambulatorio_relatorio_id');
        $return = $this->db->get();
        return $return->result();
    }

    function listareditarexame($ambulatorio_laudo_id) {

        $this->db->select(' ag.ambulatorio_exame_id ,
                            ag.texto,
                            ag.medico_parecer1');
        $this->db->from('tb_ambulatorio_exame ag');
        $this->db->where('ag.ambulatorio_exame_id', $ambulatorio_laudo_id);
        $this->db->where('ag.tipo', 'NORMAL');
        $return = $this->db->get();
        return $return->result();
    }

    function listarlaudoemailencaminhamento($ambulatorio_laudo_id) {


        $this->db->select('o.nome as medico,
                            o.email');
        $this->db->from('tb_ambulatorio_laudo ag');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->where("ag.ambulatorio_laudo_id", $ambulatorio_laudo_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listarmedicoenviarencaminhamento($medico_id) {


        $this->db->select('o.nome as medico,
                            o.email');
        $this->db->from('tb_operador o');
        $this->db->where("o.operador_id", $medico_id);
        $return = $this->db->get();
        return $return->result();
    }

    function contadorlistarreceita($ambulatorio_laudo_id) {

        $this->db->select(' ag.texto,
                            ag.medico_parecer1');
        $this->db->from('tb_ambulatorio_receituario ag');
        $this->db->where('ag.laudo_id', $ambulatorio_laudo_id);
        $return = $this->db->count_all_results();
        return $return;
    }

    function listarreceitasespeciais($ambulatorio_laudo_id) {

        $this->db->select(' ag.ambulatorio_receituario_especial_id ,
                            ag.texto,
                            ag.data_cadastro,
                            ag.medico_parecer1');
        $this->db->from('tb_ambulatorio_receituario_especial ag');
        $this->db->where('ag.laudo_id', $ambulatorio_laudo_id);
        $this->db->where('ag.tipo', 'ESPECIAL');
        $this->db->orderby('ag.data_cadastro DESC');
        $return = $this->db->get();
        return $return->result();
    }

    function listarreceitasespeciaispaciente($ambulatorio_laudo_id, $paciente_id) {

        $this->db->select(' ag.ambulatorio_receituario_especial_id ,
                            ag.texto,
                            ag.data_cadastro,
                            ag.medico_parecer1');
        $this->db->from('tb_ambulatorio_receituario_especial ag');
        $this->db->join('tb_ambulatorio_laudo al', 'al.ambulatorio_laudo_id = ag.laudo_id', 'left');
        $this->db->where('al.paciente_id', $paciente_id);
        $this->db->where('ag.tipo', 'ESPECIAL');
        $this->db->orderby('ag.data_cadastro DESC');
        $return = $this->db->get();
        return $return->result();
    }

    function listarreceitaespecial($ambulatorio_laudo_id) {

        $this->db->select(' ag.ambulatorio_receituario_especial_id ,
                            ag.texto,
                            ag.medico_parecer1');
        $this->db->from('tb_ambulatorio_receituario_especial ag');
        $this->db->where('ag.ambulatorio_receituario_especial_id', $ambulatorio_laudo_id);
        $this->db->where('ag.tipo', 'ESPECIAL');
        $return = $this->db->get();
        return $return->result();
    }

    function contadorlistarreceitaespecial($ambulatorio_laudo_id) {

        $this->db->select(' ag.texto,
                            ag.medico_parecer1');
        $this->db->from('tb_ambulatorio_receituario_especial ag');
        $this->db->where('ag.laudo_id', $ambulatorio_laudo_id);
        $return = $this->db->count_all_results();
        return $return;
    }

    function listarexamehistorico($paciente_id) {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ag.ambulatorio_laudo_id,
                            ag.data as data_cadastro,
                            ag.situacao,
                            o.nome as medico,
                            ag.texto,
                            ae.exames_id,
                            age.procedimento_tuss_id,
                            pt.nome as procedimento,
                            ag.medico_parecer1,
                            ae.agenda_exames_id,
                            age.agenda_exames_nome_id,
                            p.nome as paciente');
        $this->db->from('tb_ambulatorio_laudo ag');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_exames ae', 'ae.exames_id = ag.exame_id', 'left');
        $this->db->join('tb_agenda_exames age', 'age.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ag.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_ambulatorio_grupo agr', 'agr.nome = pt.grupo', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->where('ae.paciente_id', $paciente_id);
//        $this->db->where('ag.empresa_id', $empresa_id);
        $this->db->where('agr.tipo !=', 'CONSULTA');
        $this->db->where('agr.tipo !=', 'ESPECIALIDADE');
        $this->db->where("ag.cancelada", 'false');
        $this->db->orderby('ag.data_cadastro desc');
        $this->db->orderby('ag.situacao');
        $this->db->orderby('ag.data_cadastro');
        $return = $this->db->get();
        return $return->result();
    }

    function listarespecialidadehistorico($paciente_id) {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ag.ambulatorio_laudo_id,
                            ag.data_cadastro,
                            ag.situacao,
                            o.nome as medico,
                            ag.texto,
                            ae.exames_id,
                            age.procedimento_tuss_id,
                            pt.nome as procedimento,
                            ag.medico_parecer1,
                            ae.agenda_exames_id,
                            age.agenda_exames_nome_id,
                            p.nome as paciente');
        $this->db->from('tb_ambulatorio_laudo ag');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_exames ae', 'ae.exames_id = ag.exame_id', 'left');
        $this->db->join('tb_agenda_exames age', 'age.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ag.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_ambulatorio_grupo agr', 'agr.nome = pt.grupo', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->where('ae.paciente_id', $paciente_id);
//        $this->db->where('ag.empresa_id', $empresa_id);
        $this->db->where('agr.tipo', 'ESPECIALIDADE');
        $this->db->where("ag.cancelada", 'false');
        $this->db->orderby('ag.data_cadastro desc');
        $this->db->orderby('ag.situacao');
        $this->db->orderby('ag.data_cadastro');
        $return = $this->db->get();
        return $return->result();
    }

    function listarexamehistoricodiferenciado($paciente_id, $ambulatorio_laudo_id=NULL) {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ag.ambulatorio_laudo_id,
                            ag.data as data_cadastro,
                            ag.situacao,
                            o.nome as medico,
                            ag.texto,
                            ae.exames_id,
                            age.procedimento_tuss_id,
                            pt.nome as procedimento,
                            ag.medico_parecer1,
                            ae.agenda_exames_id,
                            age.agenda_exames_nome_id,
                            p.nome as paciente');
        $this->db->from('tb_ambulatorio_laudo ag');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_exames ae', 'ae.exames_id = ag.exame_id', 'left');
        $this->db->join('tb_agenda_exames age', 'age.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ag.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_ambulatorio_grupo agr', 'agr.nome = pt.grupo', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->where('ae.paciente_id', $paciente_id);
        if ($ambulatorio_laudo_id != "") { 
        $this->db->where('ag.ambulatorio_laudo_id !=', $ambulatorio_laudo_id);
         }
//        $this->db->where('ag.empresa_id', $empresa_id);
        $this->db->where('agr.tipo !=', 'CONSULTA');
        $this->db->where('agr.tipo !=', 'ESPECIALIDADE');
        $this->db->where("ag.cancelada", 'false');
        $this->db->orderby('ag.data_cadastro desc');
        $this->db->orderby('ag.situacao');
        $this->db->orderby('ag.data_cadastro');
        $return = $this->db->get();
        return $return->result();
    }

    function listarespecialidadehistoricodiferenciado($paciente_id, $ambulatorio_laudo_id=NULL) {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ag.ambulatorio_laudo_id,
                            ag.data_cadastro,
                            ag.situacao,
                            o.nome as medico,
                            ag.texto,
                            ae.exames_id,
                            age.procedimento_tuss_id,
                            pt.nome as procedimento,
                            ag.medico_parecer1,
                            ae.agenda_exames_id,
                            age.agenda_exames_nome_id,
                            p.nome as paciente');
        $this->db->from('tb_ambulatorio_laudo ag');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_exames ae', 'ae.exames_id = ag.exame_id', 'left');
        $this->db->join('tb_agenda_exames age', 'age.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ag.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_ambulatorio_grupo agr', 'agr.nome = pt.grupo', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->where('ae.paciente_id', $paciente_id);
        if ($ambulatorio_laudo_id != "") { 
        $this->db->where('ag.ambulatorio_laudo_id !=', $ambulatorio_laudo_id);
        }
//        $this->db->where('ag.empresa_id', $empresa_id);
        $this->db->where('agr.tipo', 'ESPECIALIDADE');
        $this->db->where("ag.cancelada", 'false');
        $this->db->orderby('ag.data_cadastro desc');
        $this->db->orderby('ag.situacao');
        $this->db->orderby('ag.data_cadastro');
        $return = $this->db->get();
        return $return->result();
    }

    function listardigitador($args = array(), $medico_laudodigitador) {

        $empresa_id = $this->session->userdata('empresa_id');
        $operador_id = $this->session->userdata('operador_id');
        $perfil_id = $this->session->userdata('perfil_id');
        $pesquisar_responsavel = $this->listarempresapermissoespesquisa();
        $this->db->select('ag.ambulatorio_laudo_id,
                            ag.paciente_id,
                            ag.data_cadastro,
                            ag.exame_id,
                            ag.situacao,
                            ag.situacao_revisor,
                            o.nome as medico,
                            age.procedimento_tuss_id,
                            op.nome as medicorevisor,
                            age.agenda_exames_nome_id,
                            ag.data_cadastro,
                            p.idade,
                            ag.medico_parecer2,
                            p.nome as paciente');
        $this->db->from('tb_ambulatorio_laudo ag');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_exames ae', 'ae.exames_id = ag.exame_id', 'left');
        $this->db->join('tb_agenda_exames age', 'age.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = age.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_operador ope', 'ope.operador_id = age.operador_recebido', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ag.medico_parecer2', 'left');
        $this->db->where('ag.empresa_id', $empresa_id);
        $this->db->where('pt.grupo !=', 'CONSULTA');
        $this->db->where('pt.grupo !=', 'MEDICAMENTO');
        $this->db->where('pt.grupo !=', 'MATERIAL');
        $this->db->where("ag.cancelada", 'false');
        $this->db->where('age.sala_preparo', 'f');


        if ($perfil_id == 4 && $medico_laudodigitador == 'f') {
            $this->db->where('age.medico_consulta_id', $operador_id);
        }



        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $nome = $this->removerCaracterEsp($args['nome']);
            // var_dump($nome); die;
            $where_p = "translate(p.nome,  
                'áàâãäåaaaÁÂÃÄÅAAAÀéèêëeeeeeEEEÉEEÈÊìíîïìiiiÌÍÎÏÌIIIóôõöoooòÒÓÔÕÖOOOùúûüuuuuÙÚÛÜUUUUçÇñÑýÝ',  
                'aaaaaaaaaAAAAAAAAAeeeeeeeeeEEEEEEEEiiiiiiiiIIIIIIIIooooooooOOOOOOOOuuuuuuuuUUUUUUUUcCnNyY'   
                ) ilike '%$nome%'";
            $where_pm = "translate(p.nome_mae,  
                'áàâãäåaaaÁÂÃÄÅAAAÀéèêëeeeeeEEEÉEEÈÊìíîïìiiiÌÍÎÏÌIIIóôõöoooòÒÓÔÕÖOOOùúûüuuuuÙÚÛÜUUUUçÇñÑýÝ',  
                'aaaaaaaaaAAAAAAAAAeeeeeeeeeEEEEEEEEiiiiiiiiIIIIIIIIooooooooOOOOOOOOuuuuuuuuUUUUUUUUcCnNyY'   
                ) ilike '%$nome%'";
            $where_pp = "translate(p.nome_pai,  
                'áàâãäåaaaÁÂÃÄÅAAAÀéèêëeeeeeEEEÉEEÈÊìíîïìiiiÌÍÎÏÌIIIóôõöoooòÒÓÔÕÖOOOùúûüuuuuÙÚÛÜUUUUçÇñÑýÝ',  
                'aaaaaaaaaAAAAAAAAAeeeeeeeeeEEEEEEEEiiiiiiiiIIIIIIIIooooooooOOOOOOOOuuuuuuuuUUUUUUUUcCnNyY'   
                ) ilike '%$nome%'";
            if ($pesquisar_responsavel == 't') {
                $this->db->where("($where_p OR $where_pm OR $where_pp)");
            } else {
                $this->db->where("($where_p)");
            }
            $this->db->where('p.ativo', 'true');
        }

        if (isset($args['prontuario_antigo']) && strlen($args['prontuario_antigo']) > 0) {
            $this->db->where('p.prontuario_antigo', $args['prontuario_antigo']);
        }
        if (isset($args['data']) && strlen($args['data']) > 0) {
            $this->db->where('ag.data', date("Y-m-d", strtotime(str_replace('/', '-', $args['data']))));
        }
        if (isset($args['sala']) && strlen($args['sala']) > 0) {
            $this->db->where('age.agenda_exames_nome_id', $args['sala']);
        }
        if (isset($args['paciente_id']) && strlen($args['paciente_id']) > 0) {
            $this->db->where('ag.paciente_id', $args['paciente_id']);
        }
        if (isset($args['exame_id']) && strlen($args['exame_id']) > 0) {
            $this->db->where('ag.guia_id', $args['exame_id']);
        }
        if (isset($args['medico']) && strlen($args['medico']) > 0) {
            $this->db->where('ag.medico_parecer1', $args['medico']);
        }
        if (isset($args['situacao']) && strlen($args['situacao']) > 0) {
            $this->db->where('ag.situacao', $args['situacao']);
        }
        if (isset($args['medicorevisor']) && strlen($args['medicorevisor']) > 0) {
            $this->db->where('ag.medico_parecer2', $args['medicorevisor']);
        }
        if (isset($args['situacaorevisor']) && strlen($args['situacaorevisor']) > 0) {
            $this->db->where('ag.situacao_revisor', $args['situacaorevisor']);
        }
        return $this->db;
    }

    function listarempresapermissoespesquisa($empresa_id = null) {
        if ($empresa_id == null) {
            $empresa_id = $this->session->userdata('empresa_id');
        }

        $this->db->select('ep.pesquisar_responsavel
                            ');
        $this->db->from('tb_empresa e');
        $this->db->where('e.empresa_id', $empresa_id);
        $this->db->join('tb_empresa_permissoes ep', 'ep.empresa_id = e.empresa_id', 'left');
        $this->db->orderby('e.empresa_id');
        $return = $this->db->get()->result();
        return $return[0]->pesquisar_responsavel;
    }

    function listar2digitador($args = array(), $medico_laudodigitador) {
        $data = date("Y-m-d");
        $contador = count($args);
        $empresa_id = $this->session->userdata('empresa_id');
        $operador_id = $this->session->userdata('operador_id');
        $perfil_id = $this->session->userdata('perfil_id');
        $pesquisar_responsavel = $this->listarempresapermissoespesquisa();
        $empresapesquisa = $this->empresa->listarverificacaopermisao2($this->session->userdata('empresa_id'));
        $entrega_laudos = $empresapesquisa[0]->entrega_laudos;
        $this->db->select('ag.ambulatorio_laudo_id,
                            ag.paciente_id,
                            ag.data_cadastro,
                            ag.data,
                            ag.exame_id,
                            ag.situacao,
                            ag.situacao_revisor,
                            o.nome as medico,
                            age.procedimento_tuss_id,
                            op.nome as medicorevisor,
                            pt.nome as procedimento,
                            p.idade,
                            ae.sala_id,
                            age.entregue,
                            age.recebido,
                            age.data_recebido,
                            age.data_entregue,
                            age.entregue_telefone,
                            ope.nome as operadorrecebido,
                            ae.exames_id,
                            ag.medico_parecer1,
                            ae.guia_id,
                            ae.agenda_exames_id,
                            ag.data_atualizacao,
                            age.agenda_exames_nome_id,
                            ag.medico_parecer2,
                            p.nome as paciente,
                            age.data_cadastro as data_cadastro_age,
                            set.nome as setore');
        $this->db->from('tb_ambulatorio_laudo ag');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_exames ae', 'ae.exames_id = ag.exame_id', 'left');
        $this->db->join('tb_agenda_exames age', 'age.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_setores set', 'set.setor_id = age.setores_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = age.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_operador ope', 'ope.operador_id = age.operador_recebido', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ag.medico_parecer2', 'left');
        $this->db->where('ag.empresa_id', $empresa_id);
        $this->db->where('pt.grupo !=', 'CONSULTA');
        $this->db->where('pt.grupo !=', 'MEDICAMENTO');
        $this->db->where('pt.grupo !=', 'MATERIAL');
        $this->db->where("ag.cancelada", 'false');
        $this->db->where('age.sala_preparo', 'f');
        $this->db->orderby('ag.situacao');
        $this->db->orderby('ag.data desc');
        if ($contador == 0) {
            $this->db->where('ag.data >=', $data);
        }

        if ($perfil_id == 4 && $medico_laudodigitador == 'f') {
            $this->db->where('age.medico_consulta_id', $operador_id);
        }

        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $nome = $this->removerCaracterEsp($args['nome']);
            // var_dump($nome); die;
            $where_p = "translate(p.nome,  
            'áàâãäåaaaÁÂÃÄÅAAAÀéèêëeeeeeEEEÉEEÈÊìíîïìiiiÌÍÎÏÌIIIóôõöoooòÒÓÔÕÖOOOùúûüuuuuÙÚÛÜUUUUçÇñÑýÝ',  
            'aaaaaaaaaAAAAAAAAAeeeeeeeeeEEEEEEEEiiiiiiiiIIIIIIIIooooooooOOOOOOOOuuuuuuuuUUUUUUUUcCnNyY'   
            ) ilike '%$nome%'";
            $where_pm = "translate(p.nome_mae,  
            'áàâãäåaaaÁÂÃÄÅAAAÀéèêëeeeeeEEEÉEEÈÊìíîïìiiiÌÍÎÏÌIIIóôõöoooòÒÓÔÕÖOOOùúûüuuuuÙÚÛÜUUUUçÇñÑýÝ',  
            'aaaaaaaaaAAAAAAAAAeeeeeeeeeEEEEEEEEiiiiiiiiIIIIIIIIooooooooOOOOOOOOuuuuuuuuUUUUUUUUcCnNyY'   
            ) ilike '%$nome%'";
            $where_pp = "translate(p.nome_pai,  
            'áàâãäåaaaÁÂÃÄÅAAAÀéèêëeeeeeEEEÉEEÈÊìíîïìiiiÌÍÎÏÌIIIóôõöoooòÒÓÔÕÖOOOùúûüuuuuÙÚÛÜUUUUçÇñÑýÝ',  
            'aaaaaaaaaAAAAAAAAAeeeeeeeeeEEEEEEEEiiiiiiiiIIIIIIIIooooooooOOOOOOOOuuuuuuuuUUUUUUUUcCnNyY'   
            ) ilike '%$nome%'";
            if ($pesquisar_responsavel == 't') {
                $this->db->where("($where_p OR $where_pm OR $where_pp)");
            } else {
                $this->db->where("($where_p)");
            }
            $this->db->where('p.ativo', 'true');
        }

        if (isset($args['prontuario_antigo']) && strlen($args['prontuario_antigo']) > 0) {
            $this->db->where('p.prontuario_antigo', $args['prontuario_antigo']);
        }
        if (isset($args['data']) && strlen($args['data']) > 0) {
            $this->db->where('ag.data', date("Y-m-d", strtotime(str_replace('/', '-', $args['data']))));
        }
        if (isset($args['sala']) && strlen($args['sala']) > 0) {
            $this->db->where('age.agenda_exames_nome_id', $args['sala']);
        }
        if (isset($args['exame_id']) && strlen($args['exame_id']) > 0) {
            $this->db->where('ag.guia_id', $args['exame_id']);
        }
        if (isset($args['paciente_id']) && strlen($args['paciente_id']) > 0) {
            $this->db->where('ag.paciente_id', $args['paciente_id']);
        }
        if (isset($args['medico']) && strlen($args['medico']) > 0) {
            $this->db->where('ag.medico_parecer1', $args['medico']);
        }
        if (isset($args['situacao']) && strlen($args['situacao']) > 0) {
            $this->db->where('ag.situacao', $args['situacao']);
        }
        if (isset($args['medicorevisor']) && strlen($args['medicorevisor']) > 0) {
            $this->db->where('ag.medico_parecer2', $args['medicorevisor']);
        }
        if (isset($args['situacaorevisor']) && strlen($args['situacaorevisor']) > 0) {
            $this->db->where('ag.situacao_revisor', $args['situacaorevisor']);
        }
        if (isset($args['convenios']) && count($args['convenios']) > 0) {
                if (!(in_array("0", $args['convenios']))) {  
                    $this->db->where_in('c.convenio_id', $args['convenios']);
                } 
        }

            if (isset($args['setores']) && strlen($args['setores']) > 0) {
                $this->db->where('age.setores_id', $args['setores']);
            }  


        return $this->db;
    }

    function listarlaudopadrao($procedimento_tuss_id) {
        $this->db->select('aml.ambulatorio_modelo_laudo_id,
                            aml.nome,
                            pt.nome as procedimento,
                            aml.texto');
        $this->db->from('tb_ambulatorio_modelo_laudo aml');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_tuss_id = aml.procedimento_tuss_id');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id');
        $this->db->where('aml.ativo', 'true');
        $this->db->where('aml.nome', '01PADRAO');
        $this->db->where('pc.procedimento_convenio_id', $procedimento_tuss_id);
        $this->db->orderby('aml.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function contadorlistarlaudopadrao($procedimento_tuss_id) {
        $this->db->select('aml.ambulatorio_modelo_laudo_id,
                            aml.nome,
                            pt.nome as procedimento,
                            aml.texto');
        $this->db->from('tb_ambulatorio_modelo_laudo aml');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_tuss_id = aml.procedimento_tuss_id');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id');
        $this->db->where('aml.ativo', 'true');
        $this->db->where('aml.nome', '01PADRAO');
        $this->db->where('pc.procedimento_convenio_id', $procedimento_tuss_id);
        $return = $this->db->count_all_results();
        return $return;
    }

    function listarrevisor($args = array()) {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ag.ambulatorio_laudo_id,
                            ag.paciente_id,
                            ag.data_cadastro,
                            ag.exame_id,
                            ag.situacao,
                            ag.situacao_revisor,
                            o.nome as medico,
                            age.procedimento_tuss_id,
                            op.nome as medicorevisor,
                            age.agenda_exames_nome_id,
                            ag.data_cadastro,
                            p.idade,
                            ag.medico_parecer2,
                            p.nome as paciente');
        $this->db->from('tb_ambulatorio_laudo ag');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_exames ae', 'ae.exames_id = ag.exame_id', 'left');
        $this->db->join('tb_agenda_exames age', 'age.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = age.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ag.medico_parecer2', 'left');
        $this->db->where('ag.empresa_id', $empresa_id);
        $this->db->where("ag.cancelada", 'false');
        $this->db->where("ag.revisor", 'true');
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('p.nome ilike', "%" . $args['nome'] . "%");
        }
        if (isset($args['data']) && strlen($args['data']) > 0) {
            $this->db->where('ag.data', date("Y-m-d", strtotime(str_replace('/', '-', $args['data']))));
        }
        if (isset($args['prontuario']) && strlen($args['prontuario']) > 0) {
            $this->db->where('p.paciente_id', $args['prontuario']);
        }
        if (isset($args['sala']) && strlen($args['sala']) > 0) {
            $this->db->where('age.agenda_exames_nome_id', $args['sala']);
        }
        if (isset($args['medico']) && strlen($args['medico']) > 0) {
            $this->db->where('ag.medico_parecer1', $args['medico']);
        }
        if (isset($args['situacao']) && strlen($args['situacao']) > 0) {
            $this->db->where('ag.situacao', $args['situacao']);
        }
        if (isset($args['medicorevisor']) && strlen($args['medicorevisor']) > 0) {
            $this->db->where('ag.medico_parecer2', $args['medicorevisor']);
        }
        if (isset($args['situacaorevisor']) && strlen($args['situacaorevisor']) > 0) {
            $this->db->where('ag.situacao_revisor', $args['situacaorevisor']);
        }
        return $this->db;
    }

    function listar2revisor($args = array()) {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ag.ambulatorio_laudo_id,
                            ag.paciente_id,
                            ag.data_cadastro,
                            ag.exame_id,
                            ag.situacao,
                            ag.situacao_revisor,
                            o.nome as medico,
                            age.procedimento_tuss_id,
                            op.nome as medicorevisor,
                            pt.nome as procedimento,
                            p.idade,
                            ag.medico_parecer1,
                            ae.guia_id,
                            ae.agenda_exames_id,
                            ag.data_cadastro,
                            age.agenda_exames_nome_id,
                            ag.medico_parecer2,
                            p.nome as paciente');
        $this->db->from('tb_ambulatorio_laudo ag');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_exames ae', 'ae.exames_id = ag.exame_id', 'left');
        $this->db->join('tb_agenda_exames age', 'age.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = age.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ag.medico_parecer2', 'left');
        $this->db->where('ag.empresa_id', $empresa_id);
        $this->db->where("ag.cancelada", 'false');
        $this->db->where("ag.revisor", 'true');
        $this->db->orderby('ag.situacao');
        $this->db->orderby('ag.data_cadastro');
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('p.nome ilike', "%" . $args['nome'] . "%");
        }
        if (isset($args['data']) && strlen($args['data']) > 0) {
            $this->db->where('ag.data', date("Y-m-d", strtotime(str_replace('/', '-', $args['data']))));
        }
        if (isset($args['prontuario']) && strlen($args['prontuario']) > 0) {
            $this->db->where('p.paciente_id', $args['prontuario']);
        }
        if (isset($args['sala']) && strlen($args['sala']) > 0) {
            $this->db->where('age.agenda_exames_nome_id', $args['sala']);
        }
        if (isset($args['situacao']) && strlen($args['situacao']) > 0) {
            $this->db->where('ag.situacao', $args['situacao']);
        }
        if (isset($args['medicorevisor']) && strlen($args['medicorevisor']) > 0) {
            $this->db->where('ag.medico_parecer2', $args['medicorevisor']);
            $this->db->where("ag.situacao_revisor !=", 'FINALIZADO');
        }
        if (isset($args['situacaorevisor']) && strlen($args['situacaorevisor']) > 0) {
            $this->db->where('ag.situacao_revisor', $args['situacaorevisor']);
        }
        return $this->db;
    }

    function listarprocedimentos($guia_id, $grupo) {
        $this->db->select('ag.procedimento_tuss_id,
                            pt.nome');
        $this->db->from('tb_ambulatorio_laudo ag');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ag.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where("ag.guia_id", $guia_id);
        $this->db->where("pt.grupo", $grupo);
        $return = $this->db->get();
        return $return->result();
    }

    function listartudopaciente($paciente_id) {
        $this->db->select('*');
        $this->db->from('tb_paciente p');
        $this->db->where("p.paciente_id", $paciente_id);
        $return = $this->db->get();
        return $return->result_array();
    }

    function listartudoexames($exames_id) {
        $this->db->select('*');
        $this->db->from('tb_exames');
        $this->db->where("exames_id", $exames_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listartudoagendaexames($agenda_exames_id) {
        $this->db->select('*');
        $this->db->from('tb_agenda_exames');
        $this->db->where("agenda_exames_id", $agenda_exames_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listarlaudointegracaoweb($ambulatorio_laudo_id) {
        $this->db->select('p.nome as procedimento, 
                            ag.texto,
                            ag.ambulatorio_laudo_id,
                            o.nome as medico,
                            o.cpf,
                            e.nome as empresa,
                            pt.nome as procedimento,
                            pt.grupo,
                            agr.tipo,
                            ag.data,
                            c.nome as convenio
                            ');
        $this->db->from('tb_ambulatorio_laudo ag');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_exames ae', 'ae.exames_id = ag.exame_id', 'left');
        $this->db->join('tb_agenda_exames age', 'age.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_empresa e', 'e.empresa_id = age.empresa_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = age.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_ambulatorio_grupo agr', 'pt.grupo = agr.nome', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->where("ambulatorio_laudo_id", $ambulatorio_laudo_id);
        $query = $this->db->get();
        $return = $query->result_array();
        return $return;
    }

    function listarlaudoantigo($args = array()) {
        $this->db->select('emissao,
                           nomeexame,
                           nomemedicolaudo,
                           nrmedicolaudo,
                           nomedopaciente');
        $this->db->from('tb_laudoantigo');
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('nomedopaciente ilike', "%" . $args['nome'] . "%");
        }
        if (isset($args['data']) && strlen($args['data']) > 0) {
            $this->db->where('emissao', $args['data']);
        }
        if (isset($args['medico']) && strlen($args['medico']) > 0) {
            $this->db->where('nrmedicolaudo', $args['medico']);
        }
        return $this->db;
    }

    function listarlaudoantigo2($args = array()) {
        $this->db->select('id,
                           emissao,
                           nomeexame,
                           nomemedicolaudo,
                           nrmedicolaudo,
                           nomedopaciente');
        $this->db->from('tb_laudoantigo');
        $this->db->orderby('emissao');
        $this->db->orderby('nomedopaciente');
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('nomedopaciente ilike', "%" . $args['nome'] . "%");
        }
        if (isset($args['data']) && strlen($args['data']) > 0) {
            $this->db->where('emissao', $args['data']);
        }
        if (isset($args['medico']) && strlen($args['medico']) > 0) {
            $this->db->where('nrmedicolaudo', $args['medico']);
        }
        return $this->db;
    }

    function listarlaudoantigoimpressao($id) {
        $this->db->select('id,
                           emissao,
                           nomeexame,
                           nomemedicolaudo,
                           nrmedicolaudo,
                           nomedopaciente,
                           nomemedicosolic,
                           laudo');
        $this->db->from('tb_laudoantigo');
        $this->db->orderby('emissao');
        $this->db->orderby('nomedopaciente');
        $this->db->where('id', $id);
        $return = $this->db->get();
        return $return->result();
    }

    function listarlaudo($ambulatorio_laudo_id) {

        $this->db->select("ag.ambulatorio_laudo_id,
                            ag.paciente_id,
                            ag.data_cadastro,
                            ag.data,
                            ag.exame_id,
                            ag.situacao,
                            ae.agenda_exames_nome_id,
                            ag.texto,
                            ag.adendo,
                            p.nascimento,
                            p.cpf,
                            ag.situacao_revisor,
                            o.nome as medico,
                            o.conselho,
                            o.carimbo,
                            op.conselho as conselho2,
                            ag.assinatura,
                            ag.rodape,
                            p.rg,
                            p.empresa as empresa_paciente,
                            ag.guia_id,
                            ag.cabecalho,
                            ag.medico_parecer1,
                            ag.medico_parecer2,
                            ag.cid2,
                            ag.cid as cid1,
                            ag.peso,
                            ag.altura,
                            ag.superficie_corporea,
                            ag.ve_volume_telediastolico,
                            ag.ve_volume_telessistolico,
                            ag.ve_diametro_telediastolico,
                            ag.ve_diametro_telessistolico,
                            ag.ve_indice_do_diametro_diastolico,
                            ag.ve_septo_interventricular,
                            ag.ve_parede_posterior,
                            ag.ve_relacao_septo_parede_posterior,
                            ag.ve_espessura_relativa_paredes,
                            ag.ve_massa_ventricular,
                            ag.ve_indice_massa,
                            ag.ve_relacao_volume_massa,
                            ag.ve_fracao_ejecao,
                            ag.ve_fracao_encurtamento,
                            ag.vd_diametro_telediastolico,
                            ag.vd_area_telediastolica,
                            ag.ve_volume_telessistolico,
                            ag.ae_diametro,                            
                            ag.ao_diametro_raiz,
                            ag.template_obj,
                            ag.ao_relacao_atrio_esquerdo_aorta,
                            me.nome as solicitante,
                            op.nome as medicorevisor,
                            op2.nome as usuario_salvar,
                            pt.nome as procedimento,
                            pt.grupo,
                            ae.agenda_exames_id,
                            ag.imagens,
                            es.nome as sala,
                            c.nome as convenio,
                            pc.convenio_id,
                            p.sexo,
                            p.celular,
                            p.telefone,
                            p.whatsapp,
                            p.prontuario_antigo,
                            p.nome_mae,
                            p.nome as paciente,
                            ae.data as data_emissao,
                            ae.data as data_agenda_exames,
                            ag.opcoes_diagnostico,
                            ag.nivel1_diagnostico,
                            ag.nivel2_diagnostico,
                            ag.nivel3_diagnostico,
                            set.nome as setor,
                            ae.observacoes");
        $this->db->from('tb_ambulatorio_laudo ag');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ag.medico_parecer2', 'left');
        $this->db->join('tb_operador op2', 'op2.operador_id = ag.operador_atualizacao', 'left');
        $this->db->join('tb_exames e', 'e.exames_id = ag.exame_id ', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->join('tb_setores set', 'ae.setores_id = set.setor_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_exame_sala es', 'es.exame_sala_id = ae.agenda_exames_nome_id', 'left');
        $this->db->join('tb_operador me', 'me.operador_id = ae.medico_solicitante', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'pc.convenio_id = c.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where("ag.ambulatorio_laudo_id", $ambulatorio_laudo_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listarlaudohistorico($ambulatorio_laudo_id, $paciente_id) {

        $this->db->select('ag.ambulatorio_laudo_id,
                            ag.paciente_id,
                            ag.data_cadastro,
                            ae.data,
                            ag.exame_id,
                            ag.situacao,
                            ae.agenda_exames_nome_id,
                            ag.texto,
                            p.nascimento,
                            p.cpf,
                            ag.situacao_revisor,
                            o.nome as medico,
                            o.conselho,
                            o.carimbo,
                            op.conselho as conselho2,
                            ag.assinatura,
                            ag.rodape,
                            p.rg,
                            ag.guia_id,
                            ag.cabecalho,
                            ag.medico_parecer1,
                            ag.medico_parecer2,
                            ag.cid2,
                            ag.cid as cid1,
                            ag.peso,
                            ag.altura,
                            ag.superficie_corporea,
                            ag.ve_volume_telediastolico,
                            ag.ve_volume_telessistolico,
                            ag.ve_diametro_telediastolico,
                            ag.ve_diametro_telessistolico,
                            ag.ve_indice_do_diametro_diastolico,
                            ag.ve_septo_interventricular,
                            ag.ve_parede_posterior,
                            ag.ve_relacao_septo_parede_posterior,
                            ag.ve_espessura_relativa_paredes,
                            ag.ve_massa_ventricular,
                            ag.ve_indice_massa,
                            ag.ve_relacao_volume_massa,
                            ag.ve_fracao_ejecao,
                            ag.ve_fracao_encurtamento,
                            ag.vd_diametro_telediastolico,
                            ag.vd_area_telediastolica,
                            ag.ve_volume_telessistolico,
                            ag.ae_diametro,                            
                            ag.ao_diametro_raiz,
                            ag.ao_relacao_atrio_esquerdo_aorta,
                            me.nome as solicitante,
                            op.nome as medicorevisor,
                            op2.nome as usuario_salvar,
                            pt.nome as procedimento,
                            pt.grupo,
                            ae.agenda_exames_id,
                            ag.imagens,
                            es.nome as sala,
                            c.nome as convenio,
                            pc.convenio_id,
                            p.sexo,
                            p.nome as paciente');
        $this->db->from('tb_ambulatorio_laudo ag');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ag.medico_parecer2', 'left');
        $this->db->join('tb_operador op2', 'op2.operador_id = ag.operador_atualizacao', 'left');
        $this->db->join('tb_exames e', 'e.exames_id = ag.exame_id ', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->join('tb_exame_sala es', 'es.exame_sala_id = ae.agenda_exames_nome_id', 'left');
        $this->db->join('tb_operador me', 'me.operador_id = ae.medico_solicitante', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'pc.convenio_id = c.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where("ag.paciente_id", $paciente_id);
        if ($_GET['medico_id'] != '' && $_GET['medico_id'] != 'TODOS') {
            $this->db->where("ag.medico_parecer1", $_GET['medico_id']);
        }
        $this->db->orderby("ag.ambulatorio_laudo_id desc");
        $return = $this->db->get();
        return $return->result();
    }

    function listarlaudohistoricointernacao($paciente_id) {

        $this->db->select('ag.ambulatorio_laudo_id,
                            ag.paciente_id,
                            ag.data_cadastro,
                            ae.data,
                            ag.exame_id,
                            ag.situacao,
                            ae.agenda_exames_nome_id,
                            ag.texto,
                            p.nascimento,
                            p.cpf,
                            ag.situacao_revisor,
                            o.nome as medico,
                            o.conselho,
                            o.carimbo,
                            op.conselho as conselho2,
                            ag.assinatura,
                            ag.rodape,
                            p.rg,
                            ag.guia_id,
                            ag.cabecalho,
                            ag.medico_parecer1,
                            ag.medico_parecer2,
                            ag.cid2,
                            ag.cid as cid1,
                            ag.peso,
                            ag.altura,
                            ag.superficie_corporea,
                            ag.ve_volume_telediastolico,
                            ag.ve_volume_telessistolico,
                            ag.ve_diametro_telediastolico,
                            ag.ve_diametro_telessistolico,
                            ag.ve_indice_do_diametro_diastolico,
                            ag.ve_septo_interventricular,
                            ag.ve_parede_posterior,
                            ag.ve_relacao_septo_parede_posterior,
                            ag.ve_espessura_relativa_paredes,
                            ag.ve_massa_ventricular,
                            ag.ve_indice_massa,
                            ag.ve_relacao_volume_massa,
                            ag.ve_fracao_ejecao,
                            ag.ve_fracao_encurtamento,
                            ag.vd_diametro_telediastolico,
                            ag.vd_area_telediastolica,
                            ag.ve_volume_telessistolico,
                            ag.ae_diametro,                            
                            ag.ao_diametro_raiz,
                            ag.ao_relacao_atrio_esquerdo_aorta,
                            me.nome as solicitante,
                            op.nome as medicorevisor,
                            pt.nome as procedimento,
                            pt.grupo,
                            ae.agenda_exames_id,
                            ag.imagens,
                            es.nome as sala,
                            c.nome as convenio,
                            pc.convenio_id,
                            p.sexo,
                            p.nome as paciente');
        $this->db->from('tb_ambulatorio_laudo ag');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ag.medico_parecer2', 'left');
        $this->db->join('tb_exames e', 'e.exames_id = ag.exame_id ', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->join('tb_exame_sala es', 'es.exame_sala_id = ae.agenda_exames_nome_id', 'left');
        $this->db->join('tb_operador me', 'me.operador_id = ae.medico_solicitante', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'pc.convenio_id = c.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where("ag.paciente_id", $paciente_id);

        $this->db->orderby("ag.ambulatorio_laudo_id desc");
        $return = $this->db->get();
        return $return->result();
    }

    function listarreceitaimpressao($ambulatorio_laudo_id, $modo = 'simples') {

        $this->db->select('ag.ambulatorio_laudo_id,
                            ag.paciente_id,
                            ag.data_cadastro,
                            ag.exame_id,
                            ag.peso,
                            ag.altura,
                            ag.data,
                            ag.situacao,
                            ae.agenda_exames_nome_id,
                            ar.texto,
                            ar.data_cadastro,
                            p.nascimento,
                            ag.situacao_revisor,
                            o.nome as medico,
                            o.conselho,
                            o.carimbo as carimbo_medico,
                            ag.assinatura,
                            ag.rodape,
                            ag.guia_id,
                            ag.cabecalho,
                            ag.medico_parecer1,
                            ag.medico_parecer2,
                            me.nome as solicitante,
                            op.nome as medicorevisor,
                            pt.nome as procedimento,
                            pt.grupo,
                            ae.agenda_exames_id,
                            ag.imagens,
                            c.nome as convenio,
                            pc.convenio_id,
                            p.nome as paciente,
                            p.cpf,
                            p.nascimento,
                            p.sexo,
                            ar.assinatura,
                            ar.carimbo,
                            o.carimbo as medico_carimbo,
                            m.nome as cidaempresa,
                            m.estado as estadoempresa,
                            ep.bairro as bairroemp,
                            ep.logradouro as endempresa,
                            ep.numero as numeroempresa,
                            ep.telefone as telempresa,
                            ep.celular as celularempresa,
                            p.logradouro,
                            p.numero,
                            p.bairro,
                            mp.nome as cidade,
                            mp.estado as estado,
                            es.nome as sala,
                            p.rg,
                            ag.cid as cid1,
                            p.prontuario_antigo,
                            p.telefone,
                            p.celular,
                            p.whatsapp,
                            p.nome_mae,
                            ag.cid2,
                            cbo.descricao,
                            cbo.cbo_ocupacao_id,
                            ar.receita_id,
                            amr.nome as modelo');
        $this->db->from('tb_ambulatorio_receituario ar');
        if($modo == 'simples'){
            $this->db->join('tb_ambulatorio_modelo_receita amr', 'amr.ambulatorio_modelo_receita_id = ar.receita_id', 'left');
        }else if($modo == 'especial'){
            $this->db->join('tb_ambulatorio_modelo_receita_especial amr', 'amr.ambulatorio_modelo_receita_especial_id = ar.receita_id', 'left');
        }
        $this->db->join('tb_ambulatorio_laudo ag', 'ag.ambulatorio_laudo_id = ar.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ag.medico_parecer2', 'left');
        $this->db->join('tb_exames e', 'e.exames_id = ag.exame_id ', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->join('tb_operador me', 'me.operador_id = ae.medico_solicitante', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'pc.convenio_id = c.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_empresa ep', 'ep.empresa_id = ae.empresa_id', 'left');
        $this->db->join('tb_municipio m', 'm.municipio_id = ep.municipio_id', 'left');
        $this->db->join('tb_municipio mp', 'mp.municipio_id = p.municipio_id', 'left');
        $this->db->join('tb_exame_sala es', 'es.exame_sala_id = ae.agenda_exames_nome_id', 'left');
        $this->db->join('tb_cbo_ocupacao cbo', 'cbo.cbo_ocupacao_id = o.cbo_ocupacao_id', 'left');
        $this->db->where("ar.ambulatorio_receituario_id", $ambulatorio_laudo_id);
        $return = $this->db->get();
        return $return->result();
    }

    function verificarreceitaespecial($ambulatorio_receita_id){
        $this->db->select('especial');
        $this->db->from('tb_ambulatorio_receituario');
        $this->db->where('ambulatorio_receituario_id', $ambulatorio_receita_id);
        $return = $this->db->get()->result();
        return $return[0]->especial;
    }

    function replicarreceitaatual($ambulatorio_receita_id){
        $this->db->select('texto, assinatura, laudo_id, tipo, carimbo, especial, receita_id');
        $this->db->from('tb_ambulatorio_receituario');
        $this->db->where('ambulatorio_receituario_id', $ambulatorio_receita_id);
        $return = $this->db->get()->result();
        return $return;
    }
    function replicarexameatual($ambulatorio_exame_id){
        $this->db->select('texto, assinatura, laudo_id, tipo, carimbo, exame_id');
        $this->db->from('tb_ambulatorio_exame');
        $this->db->where('ambulatorio_exame_id', $ambulatorio_exame_id);
        $return = $this->db->get()->result();
        return $return;
    }
    function replicarterapeuticaatual($ambulatorio_terapeutica_id){
        $this->db->select('texto, assinatura, laudo_id, carimbo, terapeuticas_id');
        $this->db->from('tb_ambulatorio_terapeuticas');
        $this->db->where('ambulatorio_terapeutica_id', $ambulatorio_terapeutica_id);
        $return = $this->db->get()->result();
        return $return;
    }
    function replicarrelatorioatual($ambulatorio_relatorio_id){
        $this->db->select('texto, assinatura, laudo_id, carimbo, relatorio_id');
        $this->db->from('tb_ambulatorio_relatorio');
        $this->db->where('ambulatorio_relatorio_id', $ambulatorio_relatorio_id);
        $return = $this->db->get()->result();
        return $return;
    }

    function listarreceitaimpressaoatendimento($ambulatorio_laudo_id) {

        $this->db->select('ag.ambulatorio_laudo_id,
                        ag.paciente_id,
                        ag.data_cadastro,
                        ag.exame_id,
                        ag.situacao,
                        ae.agenda_exames_nome_id,
                        ar.texto,
                        ag.peso,
                        ag.altura,
                        p.bairro,
                        ag.data,
                        p.nascimento,
                        p.nome as paciente,
                        p.logradouro,
                        p.numero,
                        mp.nome as cidade,
                        mp.estado as estado,
                        p.rg,
                        p.uf_rg,
                        ag.situacao_revisor,
                        o.nome as medico,
                        o.conselho,
                        me.nome as solicitante,
                        op.nome as medicorevisor,
                        pt.nome as procedimento,
                        ag.assinatura,
                        ag.rodape,
                        ag.guia_id,
                        ag.cabecalho,
                        ag.medico_parecer1,
                        pt.nome as procedimento,
                        pt.grupo,
                        ae.agenda_exames_id,
                        ag.imagens,
                        ar.data_cadastro,
                        ar.especial,
                        m.nome as cidaempresa,
                        m.estado as estadoempresa,
                        ep.bairro as bairroemp,
                        ep.logradouro as endempresa,
                        ep.numero as numeroempresa,
                        ep.telefone as telempresa,
                        ep.celular as celularempresa,
                        tl.descricao as tipologradouro,
                        c.nome as convenio,
                        pc.convenio_id,
                        ar.assinatura,
                        ar.carimbo,
                        p.nome as paciente,
                        p.cpf,
                        p.nascimento,
                        p.sexo,
                        ar.assinatura,
                        o.carimbo as carimbo_medico,
                        o.carimbo as medico_carimbo');
        $this->db->from('tb_ambulatorio_receituario ar');
        $this->db->join('tb_ambulatorio_laudo ag', 'ag.ambulatorio_laudo_id = ar.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ag.medico_parecer2', 'left');
        $this->db->join('tb_tipo_logradouro tl', 'tl.tipo_logradouro_id = o.tipo_logradouro', 'left');
        $this->db->join('tb_exames e', 'e.exames_id = ag.exame_id ', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->join('tb_operador me', 'me.operador_id = ae.medico_solicitante', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'pc.convenio_id = c.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_empresa ep', 'ep.empresa_id = ae.empresa_id', 'left');
        $this->db->join('tb_municipio m', 'm.municipio_id = ep.municipio_id', 'left');
        $this->db->join('tb_municipio mp', 'mp.municipio_id = p.municipio_id', 'left');
        $this->db->where("ar.ambulatorio_receituario_id", $ambulatorio_laudo_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listarreceitaimpressaotodos($ambulatorio_laudo_id) {

        $this->db->select('ag.ambulatorio_laudo_id,
                        ar.ambulatorio_receituario_id,
                        ag.paciente_id,
                        ag.data_cadastro,
                        ag.exame_id,
                        ag.situacao,
                        ae.agenda_exames_nome_id,
                        ar.texto,
                        ag.peso,
                        ag.altura,
                        p.bairro,
                        ag.data,
                        p.nascimento,
                        p.nome as paciente,
                        p.logradouro,
                        p.numero,
                        mp.nome as cidade,
                        mp.estado as estado,
                        p.rg,
                        p.uf_rg,
                        ag.situacao_revisor,
                        o.nome as medico,
                        o.conselho,
                        me.nome as solicitante,
                        op.nome as medicorevisor,
                        pt.nome as procedimento,
                        ag.assinatura,
                        ag.rodape,
                        ag.guia_id,
                        ag.cabecalho,
                        ag.medico_parecer1,
                        ag.medico_parecer2,
                        pt.nome as procedimento,
                        pt.grupo,
                        ae.agenda_exames_id,
                        ag.imagens,
                        ar.data_cadastro,
                        ar.especial,
                        m.nome as cidaempresa,
                        m.estado as estadoempresa,
                        ep.bairro as bairroemp,
                        ep.logradouro as endempresa,
                        ep.numero as numeroempresa,
                        ep.telefone as telempresa,
                        ep.celular as celularempresa,
                        tl.descricao as tipologradouro,
                        c.nome as convenio,
                        pc.convenio_id,
                        ar.assinatura,
                        ar.carimbo,
                        p.nome as paciente,
                        p.cpf,
                        p.nascimento,
                        p.sexo,
                        ar.assinatura,
                        o.carimbo as carimbo_medico,
                        o.carimbo as medico_carimbo,
                        es.nome as sala,
                        p.rg,
                        ag.cid as cid1,
                        p.prontuario_antigo,
                        p.telefone,
                        p.celular,
                        p.whatsapp,
                        p.nome_mae,
                        ag.cid2');
        $this->db->from('tb_ambulatorio_receituario ar');
        $this->db->join('tb_ambulatorio_laudo ag', 'ag.ambulatorio_laudo_id = ar.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ag.medico_parecer2', 'left');
        $this->db->join('tb_tipo_logradouro tl', 'tl.tipo_logradouro_id = o.tipo_logradouro', 'left');
        $this->db->join('tb_exames e', 'e.exames_id = ag.exame_id ', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->join('tb_operador me', 'me.operador_id = ae.medico_solicitante', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'pc.convenio_id = c.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_empresa ep', 'ep.empresa_id = ae.empresa_id', 'left');
        $this->db->join('tb_municipio m', 'm.municipio_id = ep.municipio_id', 'left');
        $this->db->join('tb_municipio mp', 'mp.municipio_id = p.municipio_id', 'left');
        $this->db->join('tb_exame_sala es', 'es.exame_sala_id = ae.agenda_exames_nome_id', 'left');
        $this->db->where("ar.laudo_id", $ambulatorio_laudo_id);
        $this->db->where("ar.especial", 'f');
        $return = $this->db->get();
        return $return->result();
    }

    function listarreceitaimpressaotodosnovo($receitas) {

        $this->db->select('
                        ar.ambulatorio_receituario_id,
                        ag.ambulatorio_laudo_id,
                        ag.paciente_id,
                        ag.data_cadastro,
                        ag.exame_id,
                        ag.situacao,
                        ae.agenda_exames_nome_id,
                        ar.texto,
                        ag.peso,
                        ag.altura,
                        p.bairro,
                        ag.data,
                        p.nascimento,
                        p.nome as paciente,
                        p.logradouro,
                        p.numero,
                        mp.nome as cidade,
                        mp.estado as estado,
                        p.rg,
                        p.uf_rg,
                        ag.situacao_revisor,
                        o.nome as medico,
                        o.conselho,
                        me.nome as solicitante,
                        op.nome as medicorevisor,
                        pt.nome as procedimento,
                        ag.assinatura,
                        ag.rodape,
                        ag.guia_id,
                        ag.cabecalho,
                        ag.medico_parecer1,
                        ag.medico_parecer2,
                        pt.nome as procedimento,
                        pt.grupo,
                        ae.agenda_exames_id,
                        ag.imagens,
                        ar.data_cadastro,
                        ar.especial,
                        m.nome as cidaempresa,
                        m.estado as estadoempresa,
                        ep.bairro as bairroemp,
                        ep.logradouro as endempresa,
                        ep.numero as numeroempresa,
                        ep.telefone as telempresa,
                        ep.celular as celularempresa,
                        tl.descricao as tipologradouro,
                        c.nome as convenio,
                        pc.convenio_id,
                        ar.assinatura,
                        ar.carimbo,
                        p.nome as paciente,
                        p.cpf,
                        p.nascimento,
                        p.sexo,
                        ar.assinatura,
                        o.carimbo as carimbo_medico,
                        o.carimbo as medico_carimbo,
                        es.nome as sala,
                        p.rg,
                        ag.cid as cid1,
                        p.prontuario_antigo,
                        p.telefone,
                        p.celular,
                        p.whatsapp,
                        p.nome_mae,
                        ag.cid2,
                        cbo.cbo_ocupacao_id,
                        cbo.descricao');
        $this->db->from('tb_ambulatorio_receituario ar');
        $this->db->join('tb_ambulatorio_laudo ag', 'ag.ambulatorio_laudo_id = ar.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ag.medico_parecer2', 'left');
        $this->db->join('tb_tipo_logradouro tl', 'tl.tipo_logradouro_id = o.tipo_logradouro', 'left');
        $this->db->join('tb_exames e', 'e.exames_id = ag.exame_id ', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->join('tb_operador me', 'me.operador_id = ae.medico_solicitante', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'pc.convenio_id = c.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_empresa ep', 'ep.empresa_id = ae.empresa_id', 'left');
        $this->db->join('tb_municipio m', 'm.municipio_id = ep.municipio_id', 'left');
        $this->db->join('tb_municipio mp', 'mp.municipio_id = p.municipio_id', 'left');
        $this->db->join('tb_exame_sala es', 'es.exame_sala_id = ae.agenda_exames_nome_id', 'left');
        $this->db->join('tb_cbo_ocupacao cbo', 'cbo.cbo_ocupacao_id = o.cbo_ocupacao_id', 'left');
        $this->db->where_in("ar.ambulatorio_receituario_id", $receitas);
        $this->db->where("ar.especial", 'f');
        $return = $this->db->get();
        return $return->result();
    }

    function listarreceitaimpressaotodosnovo_imprimir() {

        $this->db->select('
                        ar.ambulatorio_receituario_id,
                        ag.ambulatorio_laudo_id,
                        ag.paciente_id,
                        ag.data_cadastro,
                        ag.exame_id,
                        ag.situacao,
                        ae.agenda_exames_nome_id,
                        ar.texto,
                        ag.peso,
                        ag.altura,
                        p.bairro,
                        ag.data,
                        p.nascimento,
                        p.nome as paciente,
                        p.logradouro,
                        p.numero,
                        mp.nome as cidade,
                        mp.estado as estado,
                        p.rg,
                        p.uf_rg,
                        ag.situacao_revisor,
                        o.nome as medico,
                        o.conselho,
                        me.nome as solicitante,
                        op.nome as medicorevisor,
                        pt.nome as procedimento,
                        ag.assinatura,
                        ag.rodape,
                        ag.guia_id,
                        ag.cabecalho,
                        ag.medico_parecer1,
                        ag.medico_parecer2,
                        pt.nome as procedimento,
                        pt.grupo,
                        ae.agenda_exames_id,
                        ag.imagens,
                        ar.data_cadastro,
                        ar.especial,
                        m.nome as cidaempresa,
                        m.estado as estadoempresa,
                        ep.bairro as bairroemp,
                        ep.logradouro as endempresa,
                        ep.numero as numeroempresa,
                        ep.telefone as telempresa,
                        ep.celular as celularempresa,
                        tl.descricao as tipologradouro,
                        c.nome as convenio,
                        pc.convenio_id,
                        ar.assinatura,
                        ar.carimbo,
                        p.nome as paciente,
                        p.cpf,
                        p.nascimento,
                        p.sexo,
                        ar.assinatura,
                        o.carimbo as carimbo_medico,
                        o.carimbo as medico_carimbo,
                        es.nome as sala,
                        p.rg,
                        ag.cid as cid1,
                        p.prontuario_antigo,
                        p.telefone,
                        p.celular,
                        p.whatsapp,
                        p.nome_mae,
                        ag.cid2');
        $this->db->from('tb_ambulatorio_receituario ar');
        $this->db->join('tb_ambulatorio_laudo ag', 'ag.ambulatorio_laudo_id = ar.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ag.medico_parecer2', 'left');
        $this->db->join('tb_tipo_logradouro tl', 'tl.tipo_logradouro_id = o.tipo_logradouro', 'left');
        $this->db->join('tb_exames e', 'e.exames_id = ag.exame_id ', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->join('tb_operador me', 'me.operador_id = ae.medico_solicitante', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'pc.convenio_id = c.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_empresa ep', 'ep.empresa_id = ae.empresa_id', 'left');
        $this->db->join('tb_municipio m', 'm.municipio_id = ep.municipio_id', 'left');
        $this->db->join('tb_municipio mp', 'mp.municipio_id = p.municipio_id', 'left');
        $this->db->join('tb_exame_sala es', 'es.exame_sala_id = ae.agenda_exames_nome_id', 'left');
        $this->db->where_in("ar.ambulatorio_receituario_id", $_POST['impressao_receita']);
        $this->db->where("ar.especial", 'f');
        $return = $this->db->get();
        return $return->result();
    }

    function listarreceitaespecialimpressaotodos($ambulatorio_laudo_id) {

        $this->db->select('ag.ambulatorio_laudo_id,
                        ag.paciente_id,
                        ag.data_cadastro,
                        ag.exame_id,
                        ag.situacao,
                        ae.agenda_exames_nome_id,
                        ar.texto,
                        ag.peso,
                        ag.altura,
                        p.bairro,
                        ag.data,
                        p.nascimento,
                        p.nome as paciente,
                        p.logradouro,
                        p.numero,
                        mp.nome as cidade,
                        mp.estado as estado,
                        p.rg,
                        p.uf_rg,
                        ag.situacao_revisor,
                        o.nome as medico,
                        o.conselho,
                        me.nome as solicitante,
                        op.nome as medicorevisor,
                        pt.nome as procedimento,
                        ag.assinatura,
                        ag.rodape,
                        ag.guia_id,
                        ag.cabecalho,
                        ag.medico_parecer1,
                        pt.nome as procedimento,
                        pt.grupo,
                        ae.agenda_exames_id,
                        ag.imagens,
                        ar.data_cadastro,
                        ar.especial,
                        m.nome as cidaempresa,
                        m.estado as estadoempresa,
                        ep.bairro as bairroemp,
                        ep.logradouro as endempresa,
                        ep.numero as numeroempresa,
                        ep.telefone as telempresa,
                        ep.celular as celularempresa,
                        tl.descricao as tipologradouro,
                        c.nome as convenio,
                        pc.convenio_id,
                        ar.assinatura,
                        ar.carimbo,
                        p.nome as paciente,
                        p.cpf,
                        p.nascimento,
                        p.sexo,
                        ar.assinatura,
                        o.carimbo as carimbo_medico,
                        o.carimbo as medico_carimbo');
        $this->db->from('tb_ambulatorio_receituario ar');
        $this->db->join('tb_ambulatorio_laudo ag', 'ag.ambulatorio_laudo_id = ar.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ag.medico_parecer2', 'left');
        $this->db->join('tb_tipo_logradouro tl', 'tl.tipo_logradouro_id = o.tipo_logradouro', 'left');
        $this->db->join('tb_exames e', 'e.exames_id = ag.exame_id ', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->join('tb_operador me', 'me.operador_id = ae.medico_solicitante', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'pc.convenio_id = c.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_empresa ep', 'ep.empresa_id = ae.empresa_id', 'left');
        $this->db->join('tb_municipio m', 'm.municipio_id = ep.municipio_id', 'left');
        $this->db->join('tb_municipio mp', 'mp.municipio_id = p.municipio_id', 'left');
        $this->db->where("ar.laudo_id", $ambulatorio_laudo_id);
        $this->db->where("ar.especial", 't');
        $return = $this->db->get();
        return $return->result();
    }

    function listarreceitaespecialimpressaotodosnovo($receitas_esp) {

        $this->db->select('
                        ar.ambulatorio_receituario_id,
                        ag.ambulatorio_laudo_id,
                        ag.paciente_id,
                        ag.data_cadastro,
                        ag.exame_id,
                        ag.situacao,
                        ae.agenda_exames_nome_id,
                        ar.texto,
                        ag.peso,
                        ag.altura,
                        p.bairro,
                        ag.data,
                        p.nascimento,
                        p.nome as paciente,
                        p.logradouro,
                        p.numero,
                        mp.nome as cidade,
                        mp.estado as estado,
                        p.rg,
                        p.uf_rg,
                        ag.situacao_revisor,
                        o.nome as medico,
                        o.conselho,
                        me.nome as solicitante,
                        op.nome as medicorevisor,
                        pt.nome as procedimento,
                        ag.assinatura,
                        ag.rodape,
                        ag.guia_id,
                        ag.cabecalho,
                        ag.medico_parecer1,
                        pt.nome as procedimento,
                        pt.grupo,
                        ae.agenda_exames_id,
                        ag.imagens,
                        ar.data_cadastro,
                        ar.especial,
                        m.nome as cidaempresa,
                        m.estado as estadoempresa,
                        ep.bairro as bairroemp,
                        ep.logradouro as endempresa,
                        ep.numero as numeroempresa,
                        ep.telefone as telempresa,
                        ep.celular as celularempresa,
                        tl.descricao as tipologradouro,
                        c.nome as convenio,
                        pc.convenio_id,
                        ar.assinatura,
                        ar.carimbo,
                        p.nome as paciente,
                        p.cpf,
                        p.nascimento,
                        p.sexo,
                        ar.assinatura,
                        o.carimbo as carimbo_medico,
                        o.carimbo as medico_carimbo');
        $this->db->from('tb_ambulatorio_receituario ar');
        $this->db->join('tb_ambulatorio_laudo ag', 'ag.ambulatorio_laudo_id = ar.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ag.medico_parecer2', 'left');
        $this->db->join('tb_tipo_logradouro tl', 'tl.tipo_logradouro_id = o.tipo_logradouro', 'left');
        $this->db->join('tb_exames e', 'e.exames_id = ag.exame_id ', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->join('tb_operador me', 'me.operador_id = ae.medico_solicitante', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'pc.convenio_id = c.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_empresa ep', 'ep.empresa_id = ae.empresa_id', 'left');
        $this->db->join('tb_municipio m', 'm.municipio_id = ep.municipio_id', 'left');
        $this->db->join('tb_municipio mp', 'mp.municipio_id = p.municipio_id', 'left');
        $this->db->where_in("ar.ambulatorio_receituario_id", $receitas_esp);
        $this->db->where("ar.especial", 't');
        $return = $this->db->get();
        return $return->result();
    }


    function listarreceitaespecialimpressaotodosnovo_imprimir() {

        $this->db->select('
                        ar.ambulatorio_receituario_id,
                        ag.ambulatorio_laudo_id,
                        ag.paciente_id,
                        ag.data_cadastro,
                        ag.exame_id,
                        ag.situacao,
                        ae.agenda_exames_nome_id,
                        ar.texto,
                        ag.peso,
                        ag.altura,
                        p.bairro,
                        ag.data,
                        p.nascimento,
                        p.nome as paciente,
                        p.logradouro,
                        p.numero,
                        mp.nome as cidade,
                        mp.estado as estado,
                        p.rg,
                        p.uf_rg,
                        ag.situacao_revisor,
                        o.nome as medico,
                        o.conselho,
                        me.nome as solicitante,
                        op.nome as medicorevisor,
                        pt.nome as procedimento,
                        ag.assinatura,
                        ag.rodape,
                        ag.guia_id,
                        ag.cabecalho,
                        ag.medico_parecer1,
                        pt.nome as procedimento,
                        pt.grupo,
                        ae.agenda_exames_id,
                        ag.imagens,
                        ar.data_cadastro,
                        ar.especial,
                        m.nome as cidaempresa,
                        m.estado as estadoempresa,
                        ep.bairro as bairroemp,
                        ep.logradouro as endempresa,
                        ep.numero as numeroempresa,
                        ep.telefone as telempresa,
                        ep.celular as celularempresa,
                        tl.descricao as tipologradouro,
                        c.nome as convenio,
                        pc.convenio_id,
                        ar.assinatura,
                        ar.carimbo,
                        p.nome as paciente,
                        p.cpf,
                        p.nascimento,
                        p.sexo,
                        ar.assinatura,
                        o.carimbo as carimbo_medico,
                        o.carimbo as medico_carimbo');
        $this->db->from('tb_ambulatorio_receituario ar');
        $this->db->join('tb_ambulatorio_laudo ag', 'ag.ambulatorio_laudo_id = ar.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ag.medico_parecer2', 'left');
        $this->db->join('tb_tipo_logradouro tl', 'tl.tipo_logradouro_id = o.tipo_logradouro', 'left');
        $this->db->join('tb_exames e', 'e.exames_id = ag.exame_id ', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->join('tb_operador me', 'me.operador_id = ae.medico_solicitante', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'pc.convenio_id = c.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_empresa ep', 'ep.empresa_id = ae.empresa_id', 'left');
        $this->db->join('tb_municipio m', 'm.municipio_id = ep.municipio_id', 'left');
        $this->db->join('tb_municipio mp', 'mp.municipio_id = p.municipio_id', 'left');
        $this->db->where_in("ar.ambulatorio_receituario_id", $_POST['impressao_receita_especial']);
        $this->db->where("ar.especial", 't');
        $return = $this->db->get();
        return $return->result();
    }


    function listarexameimpressao($ambulatorio_laudo_id) {

        $this->db->select('ag.ambulatorio_laudo_id,
                            ag.paciente_id,
                            ag.data_cadastro,
                            ag.exame_id,
                            ag.peso,
                            ag.altura,
                            ag.data,
                            ag.situacao,
                            ae.agenda_exames_nome_id,
                            ar.texto,
                            ar.data_cadastro,
                            p.nascimento,
                            ag.situacao_revisor,
                            o.nome as medico,
                            o.conselho,
                            ag.assinatura,
                            ag.rodape,
                            ag.guia_id,
                            ag.cabecalho,
                            ag.medico_parecer1,
                            ag.medico_parecer2,
                            me.nome as solicitante,
                            op.nome as medicorevisor,
                            pt.nome as procedimento,
                            pt.grupo,
                            ae.agenda_exames_id,
                            ag.imagens,
                            c.nome as convenio,
                            pc.convenio_id,
                            p.nome as paciente,
                            p.cpf,
                            p.nascimento,
                            p.sexo,
                            ar.assinatura,
                            ar.carimbo,
                            o.carimbo as medico_carimbo,
                            ar.exame_id,
                            amr.nome as modelo');
        $this->db->from('tb_ambulatorio_exame ar');
        $this->db->join('tb_ambulatorio_modelo_solicitar_exames amr', 'amr.ambulatorio_modelo_solicitar_exames_id = ar.exame_id', 'left');
        $this->db->join('tb_ambulatorio_laudo ag', 'ag.ambulatorio_laudo_id = ar.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ag.medico_parecer2', 'left');
        $this->db->join('tb_exames e', 'e.exames_id = ag.exame_id ', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->join('tb_operador me', 'me.operador_id = ae.medico_solicitante', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'pc.convenio_id = c.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where("ar.ambulatorio_exame_id", $ambulatorio_laudo_id);
        $return = $this->db->get();
        return $return->result();
    }


    function listarteraupeticaimpressao($ambulatorio_laudo_id) {

        $this->db->select('ag.ambulatorio_laudo_id,
                            ag.paciente_id,
                            ag.data_cadastro,
                            ag.exame_id,
                            ag.peso,
                            ag.altura,
                            ag.data,
                            ag.situacao,
                            ae.agenda_exames_nome_id,
                            ar.texto,
                            ar.data_cadastro,
                            p.nascimento,
                            ag.situacao_revisor,
                            o.nome as medico,
                            o.conselho,
                            ag.assinatura,
                            ag.rodape,
                            ag.guia_id,
                            ag.cabecalho,
                            ag.medico_parecer1,
                            ag.medico_parecer2,
                            me.nome as solicitante,
                            op.nome as medicorevisor,
                            pt.nome as procedimento,
                            pt.grupo,
                            ae.agenda_exames_id,
                            ag.imagens,
                            c.nome as convenio,
                            pc.convenio_id,
                            p.nome as paciente,
                            p.cpf,
                            p.nascimento,
                            p.sexo,
                            ar.assinatura,
                            ar.carimbo,
                            o.carimbo as medico_carimbo,
                            ar.terapeuticas_id,
                            amr.nome as modelo');
        $this->db->from('tb_ambulatorio_terapeuticas ar');
        $this->db->join('tb_ambulatorio_modelo_terapeuticas_id amr', 'amr.ambulatorio_modelo_terapeuticas_id = ar.terapeuticas_id', 'left');
        $this->db->join('tb_ambulatorio_laudo ag', 'ag.ambulatorio_laudo_id = ar.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ag.medico_parecer2', 'left');
        $this->db->join('tb_exames e', 'e.exames_id = ag.exame_id ', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->join('tb_operador me', 'me.operador_id = ae.medico_solicitante', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'pc.convenio_id = c.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where("ar.ambulatorio_terapeutica_id", $ambulatorio_laudo_id);
        $return = $this->db->get();
        return $return->result();
    }


    function listarrelatorioimpressao($ambulatorio_laudo_id) {

        $this->db->select('ag.ambulatorio_laudo_id,
                            ag.paciente_id,
                            ag.data_cadastro,
                            ag.exame_id,
                            ag.peso,
                            ag.altura,
                            ag.data,
                            ag.situacao,
                            ae.agenda_exames_nome_id,
                            ar.texto,
                            ar.data_cadastro,
                            p.nascimento,
                            ag.situacao_revisor,
                            o.nome as medico,
                            o.conselho,
                            ag.assinatura,
                            ag.rodape,
                            ag.guia_id,
                            ag.cabecalho,
                            ag.medico_parecer1,
                            ag.medico_parecer2,
                            me.nome as solicitante,
                            op.nome as medicorevisor,
                            pt.nome as procedimento,
                            pt.grupo,
                            ae.agenda_exames_id,
                            ag.imagens,
                            c.nome as convenio,
                            pc.convenio_id,
                            p.nome as paciente,
                            p.cpf,
                            p.nascimento,
                            p.sexo,
                            ar.assinatura,
                            ar.carimbo,
                            o.carimbo as medico_carimbo,
                            ar.relatorio_id,
                            amr.nome as modelo');
        $this->db->from('tb_ambulatorio_relatorio ar');
        $this->db->join('tb_ambulatorio_modelo_relatorio amr', 'amr.ambulatorio_modelo_relatorio_id = ar.relatorio_id', 'left');
        $this->db->join('tb_ambulatorio_laudo ag', 'ag.ambulatorio_laudo_id = ar.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ag.medico_parecer2', 'left');
        $this->db->join('tb_exames e', 'e.exames_id = ag.exame_id ', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->join('tb_operador me', 'me.operador_id = ae.medico_solicitante', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'pc.convenio_id = c.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where("ar.ambulatorio_relatorio_id", $ambulatorio_laudo_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listarexameimpressaotodos($ambulatorio_laudo_id) {

        $this->db->select('ag.ambulatorio_laudo_id,
                            ag.paciente_id,
                            ag.data_cadastro,
                            ag.exame_id,
                            ag.peso,
                            ag.altura,
                            ag.data,
                            ag.situacao,
                            ae.agenda_exames_nome_id,
                            ar.texto,
                            ar.ambulatorio_exame_id,
                            ar.data_cadastro,
                            p.nascimento,
                            ag.situacao_revisor,
                            o.nome as medico,
                            o.conselho,
                            ag.assinatura,
                            ag.rodape,
                            ag.guia_id,
                            ag.cabecalho,
                            ag.medico_parecer1,
                            ag.medico_parecer2,
                            me.nome as solicitante,
                            op.nome as medicorevisor,
                            pt.nome as procedimento,
                            pt.grupo,
                            ae.agenda_exames_id,
                            ag.imagens,
                            c.nome as convenio,
                            pc.convenio_id,
                            p.nome as paciente,
                            p.cpf,
                            p.nascimento,
                            p.sexo,
                            ar.assinatura,
                            ar.carimbo,
                            o.carimbo as medico_carimbo');
        $this->db->from('tb_ambulatorio_exame ar');
        $this->db->join('tb_ambulatorio_laudo ag', 'ag.ambulatorio_laudo_id = ar.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ag.medico_parecer2', 'left');
        $this->db->join('tb_exames e', 'e.exames_id = ag.exame_id ', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->join('tb_operador me', 'me.operador_id = ae.medico_solicitante', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'pc.convenio_id = c.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where("ar.laudo_id", $ambulatorio_laudo_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listarexameimpressaotodosnovo($sol_exames) {

        $this->db->select('ar.ambulatorio_exame_id,
                            ag.ambulatorio_laudo_id,
                            ag.paciente_id,
                            ag.data_cadastro,
                            ag.exame_id,
                            ag.peso,
                            ag.altura,
                            ag.data,
                            ag.situacao,
                            ae.agenda_exames_nome_id,
                            ar.texto,
                            ar.data_cadastro,
                            p.nascimento,
                            ag.situacao_revisor,
                            o.nome as medico,
                            o.conselho,
                            ag.assinatura,
                            ag.rodape,
                            ag.guia_id,
                            ag.cabecalho,
                            ag.medico_parecer1,
                            ag.medico_parecer2,
                            me.nome as solicitante,
                            op.nome as medicorevisor,
                            pt.nome as procedimento,
                            pt.grupo,
                            ae.agenda_exames_id,
                            ag.imagens,
                            c.nome as convenio,
                            pc.convenio_id,
                            p.nome as paciente,
                            p.cpf,
                            p.nascimento,
                            p.sexo,
                            ar.assinatura,
                            ar.carimbo,
                            o.carimbo as medico_carimbo');
        $this->db->from('tb_ambulatorio_exame ar');
        $this->db->join('tb_ambulatorio_laudo ag', 'ag.ambulatorio_laudo_id = ar.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ag.medico_parecer2', 'left');
        $this->db->join('tb_exames e', 'e.exames_id = ag.exame_id ', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->join('tb_operador me', 'me.operador_id = ae.medico_solicitante', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'pc.convenio_id = c.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where_in("ar.ambulatorio_exame_id", $sol_exames);
        $return = $this->db->get();
        return $return->result();
    }

    function listarexameimpressaotodosnovo_imprimir() {

        $this->db->select('ar.ambulatorio_exame_id,
                            ag.ambulatorio_laudo_id,
                            ag.paciente_id,
                            ag.data_cadastro,
                            ag.exame_id,
                            ag.peso,
                            ag.altura,
                            ag.data,
                            ag.situacao,
                            ae.agenda_exames_nome_id,
                            ar.texto,
                            ar.data_cadastro,
                            p.nascimento,
                            ag.situacao_revisor,
                            o.nome as medico,
                            o.conselho,
                            ag.assinatura,
                            ag.rodape,
                            ag.guia_id,
                            ag.cabecalho,
                            ag.medico_parecer1,
                            ag.medico_parecer2,
                            me.nome as solicitante,
                            op.nome as medicorevisor,
                            pt.nome as procedimento,
                            pt.grupo,
                            ae.agenda_exames_id,
                            ag.imagens,
                            c.nome as convenio,
                            pc.convenio_id,
                            p.nome as paciente,
                            p.cpf,
                            p.nascimento,
                            p.sexo,
                            ar.assinatura,
                            ar.carimbo,
                            o.carimbo as medico_carimbo');
        $this->db->from('tb_ambulatorio_exame ar');
        $this->db->join('tb_ambulatorio_laudo ag', 'ag.ambulatorio_laudo_id = ar.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ag.medico_parecer2', 'left');
        $this->db->join('tb_exames e', 'e.exames_id = ag.exame_id ', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->join('tb_operador me', 'me.operador_id = ae.medico_solicitante', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'pc.convenio_id = c.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where_in("ar.ambulatorio_exame_id", $_POST['impressao_exame']);
        $return = $this->db->get();
        return $return->result();
    }

    function listarrelatorioimpressaotodos($ambulatorio_laudo_id) {

        $this->db->select('ag.ambulatorio_laudo_id,
                            ag.paciente_id,
                            ag.data_cadastro,
                            ag.exame_id,
                            ag.peso,
                            ag.altura,
                            ag.data,
                            ag.situacao,
                            ae.agenda_exames_nome_id,
                            ar.texto,
                            ar.data_cadastro,
                            p.nascimento,
                            ag.situacao_revisor,
                            o.nome as medico,
                            o.conselho,
                            ag.assinatura,
                            ag.rodape,
                            ag.guia_id,
                            ag.cabecalho,
                            ag.medico_parecer1,
                            ag.medico_parecer2,
                            me.nome as solicitante,
                            op.nome as medicorevisor,
                            pt.nome as procedimento,
                            pt.grupo,
                            ae.agenda_exames_id,
                            ag.imagens,
                            c.nome as convenio,
                            pc.convenio_id,
                            p.nome as paciente,
                            p.cpf,
                            p.nascimento,
                            p.sexo,
                            ar.assinatura,
                            ar.carimbo,
                            o.carimbo as medico_carimbo');
        $this->db->from('tb_ambulatorio_relatorio ar');
        $this->db->join('tb_ambulatorio_laudo ag', 'ag.ambulatorio_laudo_id = ar.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ag.medico_parecer2', 'left');
        $this->db->join('tb_exames e', 'e.exames_id = ag.exame_id ', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->join('tb_operador me', 'me.operador_id = ae.medico_solicitante', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'pc.convenio_id = c.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where("ar.laudo_id", $ambulatorio_laudo_id);
        $return = $this->db->get();
        return $return->result();
    }

    function gravaranexoarquivo($ambulatorio_laudo_id, $paciente_id, $caminho, $nome){
        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');

         $this->db->set('laudo_id', $ambulatorio_laudo_id);
         $this->db->set('nome', $nome);
         $this->db->set('medico_id', $operador_id);
         $this->db->set('paciente_id', $paciente_id);
         $this->db->set('caminho', $caminho);
         $this->db->set('data_cadastro', $horario);
         $this->db->set('operador_cadastro', $operador_id);
         $this->db->insert('tb_ambulatorio_arquivos_anexados');
    }

    function excluiranexoarquivo($caminho){
        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');

         $this->db->set('ativo', 'f');
         $this->db->set('data_atualizacao', $horario);
         $this->db->set('operador_atualizacao', $operador_id);
         $this->db->where('caminho', $caminho);
         $this->db->update('tb_ambulatorio_arquivos_anexados');
    }

    function listarresultadosexames($paciente_id){
        $this->db->select('aa.nome, aa.caminho, aa.paciente, aa.data_cadastro, aa.laudo_id,
                           o.nome as operador');
        $this->db->from('tb_ambulatorio_arquivos_anexados aa');
        $this->db->join('tb_operador o', 'o.operador_id = aa.medico_id', 'left');
        $this->db->where('aa.ativo', 't');
        $this->db->where('aa.paciente_id', $paciente_id);
        $this->db->orderby('laudo_id', 'desc');

        $return = $this->db->get();
        return $return->result();
    }

    function informacaoimpressaosalvar($laudo_id, $modelo, $ambulatorio_id){
        if($modelo == 'receita_simples'){
            $this->db->select('mr.nome, ar.data_cadastro');
            $this->db->from('tb_ambulatorio_receituario ar');
            $this->db->join('tb_ambulatorio_modelo_receita mr', 'mr.ambulatorio_modelo_receita_id = ar.receita_id', 'left');
            $this->db->where('ar.ambulatorio_receituario_id', $ambulatorio_id);
            $return = $this->db->get()->result();
            $nome = 'receita_'.$ambulatorio_id.'.pdf';

        }elseif($modelo == 'receita_especial'){
            $this->db->select('mr.nome, ar.data_cadastro');
            $this->db->from('tb_ambulatorio_receituario ar');
            $this->db->join('tb_ambulatorio_modelo_receita_especial mr', 'mr.ambulatorio_modelo_receita_especial_id = ar.receita_id', 'left');
            $this->db->where('ar.ambulatorio_receituario_id', $ambulatorio_id);
            $return = $this->db->get()->result();
            $nome = 'receituarioEspecial_'.$ambulatorio_id.'.pdf';

        }elseif($modelo == 'solicitacao_exames'){
            $this->db->select('mr.nome, ar.data_cadastro');
            $this->db->from('tb_ambulatorio_exame ar');
            $this->db->join('tb_ambulatorio_modelo_solicitar_exames mr', 'mr.ambulatorio_modelo_solicitar_exames_id = ar.exame_id', 'left');
            $this->db->where('ar.ambulatorio_exame_id', $ambulatorio_id);
            $return = $this->db->get()->result();
            $nome = 'solicitacaoexames_'.$ambulatorio_id.'.pdf';

        }elseif($modelo == 'terapeuticas'){
            $this->db->select('mr.nome, ar.data_cadastro');
            $this->db->from('tb_ambulatorio_terapeuticas ar');
            $this->db->join('tb_ambulatorio_modelo_terapeuticas_id mr', 'mr.ambulatorio_modelo_terapeuticas_id = ar.terapeuticas_id', 'left');
            $this->db->where('ar.ambulatorio_terapeutica_id', $ambulatorio_id);
            $return = $this->db->get()->result();
            $nome = 'terapeuticas_'.$ambulatorio_id.'.pdf';

        }elseif($modelo == 'relatorios'){
            $this->db->select('mr.nome, ar.data_cadastro');
            $this->db->from('tb_ambulatorio_relatorio ar');
            $this->db->join('tb_ambulatorio_modelo_relatorio mr', 'mr.ambulatorio_modelo_relatorio_id = ar.relatorio_id', 'left');
            $this->db->where('ar.ambulatorio_relatorio_id', $ambulatorio_id);
            $return = $this->db->get()->result();
            $nome = 'relatorios_'.$ambulatorio_id.'.pdf';
        }

        $data = date('d_m_Y', strtotime($return[0]->data_cadastro));

        if($return[0]->nome == ''){
            $novonome = $modelo.'_'.$data.'.pdf';
        }else{
            $novonome = $return[0]->nome.'_'.$data.'.pdf'; 
        }


        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        $this->db->set('laudo_id', $laudo_id);
        $this->db->set('nome_impressao', $novonome);
        $this->db->set('nome', $nome);
        $this->db->set('data_cadastro', $horario);
        $this->db->set('operador_cadastro', $operador_id);
        $this->db->insert('tb_ambulatorio_visualizar_impressao');
    }

    function listarnomesimpressoes($laudo_id){
        $this->db->select('nome, nome_impressao');
        $this->db->from('tb_ambulatorio_visualizar_impressao');
        $this->db->where('ativo', 't');
        $this->db->where('laudo_id', $laudo_id);
        $this->db->orderby('nome, nome_impressao');
        $return = $this->db->get()->result();

        return $return;
    }

    function listarrelatorioimpressaotodosnovo($relatorio) {

        $this->db->select(' ar.ambulatorio_relatorio_id,
                            ag.ambulatorio_laudo_id,
                            ag.paciente_id,
                            ag.data_cadastro,
                            ag.exame_id,
                            ag.peso,
                            ag.altura,
                            ag.data,
                            ag.situacao,
                            ae.agenda_exames_nome_id,
                            ar.texto,
                            ar.data_cadastro,
                            p.nascimento,
                            ag.situacao_revisor,
                            o.nome as medico,
                            o.conselho,
                            ag.assinatura,
                            ag.rodape,
                            ag.guia_id,
                            ag.cabecalho,
                            ag.medico_parecer1,
                            ag.medico_parecer2,
                            me.nome as solicitante,
                            op.nome as medicorevisor,
                            pt.nome as procedimento,
                            pt.grupo,
                            ae.agenda_exames_id,
                            ag.imagens,
                            c.nome as convenio,
                            pc.convenio_id,
                            p.nome as paciente,
                            p.cpf,
                            p.nascimento,
                            p.sexo,
                            ar.assinatura,
                            ar.carimbo,
                            o.carimbo as medico_carimbo');
        $this->db->from('tb_ambulatorio_relatorio ar');
        $this->db->join('tb_ambulatorio_laudo ag', 'ag.ambulatorio_laudo_id = ar.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ag.medico_parecer2', 'left');
        $this->db->join('tb_exames e', 'e.exames_id = ag.exame_id ', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->join('tb_operador me', 'me.operador_id = ae.medico_solicitante', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'pc.convenio_id = c.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where_in("ar.ambulatorio_relatorio_id", $relatorio);
        $return = $this->db->get();
        return $return->result();
    }

    function listarrelatorioimpressaotodosnovo_imprimir() {

        $this->db->select(' ar.ambulatorio_relatorio_id,
                            ag.ambulatorio_laudo_id,
                            ag.paciente_id,
                            ag.data_cadastro,
                            ag.exame_id,
                            ag.peso,
                            ag.altura,
                            ag.data,
                            ag.situacao,
                            ae.agenda_exames_nome_id,
                            ar.texto,
                            ar.data_cadastro,
                            p.nascimento,
                            ag.situacao_revisor,
                            o.nome as medico,
                            o.conselho,
                            ag.assinatura,
                            ag.rodape,
                            ag.guia_id,
                            ag.cabecalho,
                            ag.medico_parecer1,
                            ag.medico_parecer2,
                            me.nome as solicitante,
                            op.nome as medicorevisor,
                            pt.nome as procedimento,
                            pt.grupo,
                            ae.agenda_exames_id,
                            ag.imagens,
                            c.nome as convenio,
                            pc.convenio_id,
                            p.nome as paciente,
                            p.cpf,
                            p.nascimento,
                            p.sexo,
                            ar.assinatura,
                            ar.carimbo,
                            o.carimbo as medico_carimbo');
        $this->db->from('tb_ambulatorio_relatorio ar');
        $this->db->join('tb_ambulatorio_laudo ag', 'ag.ambulatorio_laudo_id = ar.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ag.medico_parecer2', 'left');
        $this->db->join('tb_exames e', 'e.exames_id = ag.exame_id ', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->join('tb_operador me', 'me.operador_id = ae.medico_solicitante', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'pc.convenio_id = c.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where_in("ar.ambulatorio_relatorio_id", $_POST['impressao_relatorio']);
        $return = $this->db->get();
        return $return->result();
    }

    function impressaonovoatendimento_botaoimprimir($ambulatorio_laudo_id, $modelo){
        $this->db->select('ambulatorio_id');
        $this->db->from('tb_ambulatorio_impressoes_laudo');
        $this->db->where('ativo', 't');
        $this->db->where('tipo', $modelo);
        $this->db->where('laudo_id', $ambulatorio_laudo_id);

        $return = $this->db->get()->result(); 
        if(count($return) > 0){
         return json_decode($return[0]->ambulatorio_id);
        }else{
            return 0;
        }
    }

    function listarterapeuticaimpressaotodos($ambulatorio_laudo_id) {

        $this->db->select('ag.ambulatorio_laudo_id,
                            ag.paciente_id,
                            ag.data_cadastro,
                            ag.exame_id,
                            ag.peso,
                            ag.altura,
                            ag.data,
                            ag.situacao,
                            ae.agenda_exames_nome_id,
                            ar.texto,
                            ar.data_cadastro,
                            p.nascimento,
                            ag.situacao_revisor,
                            o.nome as medico,
                            o.conselho,
                            ag.assinatura,
                            ag.rodape,
                            ag.guia_id,
                            ag.cabecalho,
                            ag.medico_parecer1,
                            ag.medico_parecer2,
                            me.nome as solicitante,
                            op.nome as medicorevisor,
                            pt.nome as procedimento,
                            pt.grupo,
                            ae.agenda_exames_id,
                            ag.imagens,
                            c.nome as convenio,
                            pc.convenio_id,
                            p.nome as paciente,
                            p.cpf,
                            p.nascimento,
                            p.sexo,
                            ar.assinatura,
                            ar.carimbo,
                            o.carimbo as medico_carimbo');
        $this->db->from('tb_ambulatorio_terapeuticas ar');
        $this->db->join('tb_ambulatorio_laudo ag', 'ag.ambulatorio_laudo_id = ar.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ag.medico_parecer2', 'left');
        $this->db->join('tb_exames e', 'e.exames_id = ag.exame_id ', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->join('tb_operador me', 'me.operador_id = ae.medico_solicitante', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'pc.convenio_id = c.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where("ar.laudo_id", $ambulatorio_laudo_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listarterapeuticaimpressaotodosnovo($terapeuticas) {

        $this->db->select(' ar.ambulatorio_terapeutica_id,
                            ag.ambulatorio_laudo_id,
                            ag.paciente_id,
                            ag.data_cadastro,
                            ag.exame_id,
                            ag.peso,
                            ag.altura,
                            ag.data,
                            ag.situacao,
                            ae.agenda_exames_nome_id,
                            ar.texto,
                            ar.data_cadastro,
                            p.nascimento,
                            ag.situacao_revisor,
                            o.nome as medico,
                            o.conselho,
                            ag.assinatura,
                            ag.rodape,
                            ag.guia_id,
                            ag.cabecalho,
                            ag.medico_parecer1,
                            ag.medico_parecer2,
                            me.nome as solicitante,
                            op.nome as medicorevisor,
                            pt.nome as procedimento,
                            pt.grupo,
                            ae.agenda_exames_id,
                            ag.imagens,
                            c.nome as convenio,
                            pc.convenio_id,
                            p.nome as paciente,
                            p.cpf,
                            p.nascimento,
                            p.sexo,
                            ar.assinatura,
                            ar.carimbo,
                            o.carimbo as medico_carimbo');
        $this->db->from('tb_ambulatorio_terapeuticas ar');
        $this->db->join('tb_ambulatorio_laudo ag', 'ag.ambulatorio_laudo_id = ar.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ag.medico_parecer2', 'left');
        $this->db->join('tb_exames e', 'e.exames_id = ag.exame_id ', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->join('tb_operador me', 'me.operador_id = ae.medico_solicitante', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'pc.convenio_id = c.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where_in("ar.ambulatorio_terapeutica_id", $terapeuticas);
        $return = $this->db->get();
        return $return->result();
    }

    function listarterapeuticaimpressaotodosnovo_imprimir() {

        $this->db->select(' ar.ambulatorio_terapeutica_id,
                            ag.ambulatorio_laudo_id,
                            ag.paciente_id,
                            ag.data_cadastro,
                            ag.exame_id,
                            ag.peso,
                            ag.altura,
                            ag.data,
                            ag.situacao,
                            ae.agenda_exames_nome_id,
                            ar.texto,
                            ar.data_cadastro,
                            p.nascimento,
                            ag.situacao_revisor,
                            o.nome as medico,
                            o.conselho,
                            ag.assinatura,
                            ag.rodape,
                            ag.guia_id,
                            ag.cabecalho,
                            ag.medico_parecer1,
                            ag.medico_parecer2,
                            me.nome as solicitante,
                            op.nome as medicorevisor,
                            pt.nome as procedimento,
                            pt.grupo,
                            ae.agenda_exames_id,
                            ag.imagens,
                            c.nome as convenio,
                            pc.convenio_id,
                            p.nome as paciente,
                            p.cpf,
                            p.nascimento,
                            p.sexo,
                            ar.assinatura,
                            ar.carimbo,
                            o.carimbo as medico_carimbo');
        $this->db->from('tb_ambulatorio_terapeuticas ar');
        $this->db->join('tb_ambulatorio_laudo ag', 'ag.ambulatorio_laudo_id = ar.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ag.medico_parecer2', 'left');
        $this->db->join('tb_exames e', 'e.exames_id = ag.exame_id ', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->join('tb_operador me', 'me.operador_id = ae.medico_solicitante', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'pc.convenio_id = c.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where_in("ar.ambulatorio_terapeutica_id", $_POST['impressao_terapeutica']);
        $return = $this->db->get();
        return $return->result();
    }

    function listarrotinaimpressao($ambulatorio_laudo_id) {

        $this->db->select('ag.ambulatorio_laudo_id,
                            ag.paciente_id,
                            ag.data_cadastro,
                            ag.exame_id,
                            ag.peso,
                            ag.altura,
                            ag.data,
                            ag.situacao,
                            ae.agenda_exames_nome_id,
                            ar.texto,
                            ar.data_cadastro,
                            p.nascimento,
                            ag.situacao_revisor,
                            o.nome as medico,
                            o.conselho,
                            ag.assinatura,
                            ag.rodape,
                            ag.guia_id,
                            ag.cabecalho,
                            ag.medico_parecer1,
                            ag.medico_parecer2,
                            me.nome as solicitante,
                            op.nome as medicorevisor,
                            pt.nome as procedimento,
                            pt.grupo,
                            ae.agenda_exames_id,
                            ag.imagens,
                            c.nome as convenio,
                            pc.convenio_id,
                            p.nome as paciente,
                            p.cpf,
                            p.nascimento,
                            p.sexo,
                            ar.assinatura,
                            ar.carimbo,
                            o.carimbo as medico_carimbo');
        $this->db->from('tb_ambulatorio_rotinas ar');
        $this->db->join('tb_ambulatorio_laudo ag', 'ag.ambulatorio_laudo_id = ar.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ag.medico_parecer2', 'left');
        $this->db->join('tb_exames e', 'e.exames_id = ag.exame_id ', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->join('tb_operador me', 'me.operador_id = ae.medico_solicitante', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'pc.convenio_id = c.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where("ar.ambulatorio_rotinas_id", $ambulatorio_laudo_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listarTomadaimpressao($ambulatorio_laudo_id) {

        $this->db->select('ag.ambulatorio_laudo_id,
                            arm.nome as medicamento,
                            ar.quantidade quantidade_med,
                            arm.posologia,
                            ag.paciente_id,
                            ag.data_cadastro,
                            ag.exame_id,
                            ag.peso,
                            ag.altura,
                            ag.data,
                            ag.situacao,
                            ae.agenda_exames_nome_id,
                            p.nascimento,
                            ag.situacao_revisor,
                            o.nome as medico,
                            o.conselho,
                            ag.assinatura,
                            ag.rodape,
                            ag.guia_id,
                            ag.cabecalho,
                            ag.medico_parecer1,
                            ag.medico_parecer2,
                            me.nome as solicitante,
                            op.nome as medicorevisor,
                            pt.nome as procedimento,
                            pt.grupo,
                            ae.agenda_exames_id,
                            ag.imagens,
                            c.nome as convenio,
                            pc.convenio_id,
                            p.nome as paciente,
                            p.cpf,
                            p.nascimento,
                            p.sexo,
                            o.carimbo as medico_carimbo');
        $this->db->from('tb_ambulatorio_laudo_tomada ar');
        $this->db->join('tb_ambulatorio_receituario_medicamento arm', 'arm.ambulatorio_receituario_medicamento_id = ar.medicamento_id', 'left');
        $this->db->join('tb_ambulatorio_laudo ag', 'ag.ambulatorio_laudo_id = ar.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ag.medico_parecer2', 'left');
        $this->db->join('tb_exames e', 'e.exames_id = ag.exame_id ', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->join('tb_operador me', 'me.operador_id = ae.medico_solicitante', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'pc.convenio_id = c.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where("ar.laudo_id", $ambulatorio_laudo_id);
        $this->db->where("ar.ativo", 't');
        $return = $this->db->get();
        return $return->result();
    }

    function auditoriaenviaronline($mensagem, $paciente, $medico){
        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');

        $this->db->set('texto', $mensagem);
        $this->db->set('operador_cadastro', $operador_id);
        $this->db->set('data_cadastro', $horario);
        $this->db->set('paciente_id', $paciente);
        $this->db->set('medico_id', $medico);
        $this->db->insert('tb_auditoria_enviar_online');

    }

    function listarTomadaimpressaohorario($ambulatorio_laudo_id) {

        $this->db->select('ar.horario,
                            ar.horario_texto,
                            ar.texto
                        ');
        $this->db->from('tb_ambulatorio_laudo_tomada ar');
        $this->db->where("ar.laudo_id", $ambulatorio_laudo_id);
        $this->db->where("ar.ativo", 't');
        $this->db->groupby('ar.horario, ar.horario_texto, ar.texto');
        $this->db->orderby('ar.horario');
        $return = $this->db->get();
        return $return->result();
    }


    function listarTomadaimpressaomedicamento($ambulatorio_laudo_id, $horario, $horario_texto, $texto) {

        $this->db->select('arm.nome as medicamento,
                           SUM(ar.quantidade) as qtd');
        $this->db->from('tb_ambulatorio_laudo_tomada ar');
        $this->db->join('tb_ambulatorio_receituario_medicamento arm', 'arm.ambulatorio_receituario_medicamento_id = ar.medicamento_id', 'left');
        $this->db->where("ar.laudo_id", $ambulatorio_laudo_id);
        $this->db->where("ar.horario", $horario);
        $this->db->where("ar.texto", $texto);
        // $this->db->where("ar.texto", null);
        // $this->db->where("ar.horario_texto", $horario_texto);

        // $this->db->where("ar.horario_texto", $horario_texto);
        // $this->db->where("ar.texto", $texto);
        $this->db->where("ar.ativo", 't');
        $this->db->groupby('arm.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function buscartomadaslancadas($ambulatorio_laudo_id) {

        $this->db->select('arm.nome as medicamento,
                           SUM(ar.quantidade) as qtd');
        $this->db->from('tb_ambulatorio_laudo_tomada ar');
        $this->db->join('tb_ambulatorio_receituario_medicamento arm', 'arm.ambulatorio_receituario_medicamento_id = ar.medicamento_id', 'left');
        $this->db->where("ar.laudo_id", $ambulatorio_laudo_id);
        $this->db->where("ar.ativo", 't');
        $this->db->where("arm.r_especial !=", 't');
        $this->db->where("ar.nao_repeti", 'f');
        $this->db->groupby('arm.nome');
        $this->db->orderby('arm.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function buscartomadaslancadasespecial($ambulatorio_laudo_id) {

        $this->db->select('arm.nome as medicamento,
                           SUM(ar.quantidade) as qtd');
        $this->db->from('tb_ambulatorio_laudo_tomada ar');
        $this->db->join('tb_ambulatorio_receituario_medicamento arm', 'arm.ambulatorio_receituario_medicamento_id = ar.medicamento_id', 'left');
        $this->db->where("ar.laudo_id", $ambulatorio_laudo_id);
        $this->db->where("ar.ativo", 't');
        $this->db->where("arm.r_especial", 't');
        $this->db->where("ar.nao_repeti", 'f');
        $this->db->groupby('arm.nome');
        $this->db->orderby('arm.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function tomadasnaorepetir($ambulatorio_laudo_id){
        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');

        $this->db->set('nao_repeti', 't');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('laudo_id', $ambulatorio_laudo_id);
        $this->db->update('tb_ambulatorio_laudo_tomada');
    }

    function listarsolicitarexameimpressao($ambulatorio_laudo_id) {
        $this->db->select('ag.ambulatorio_laudo_id,
                            ag.paciente_id,
                            ag.data_cadastro,
                            ag.exame_id,
                            ag.situacao,
                            ae.agenda_exames_nome_id,
                            ar.texto,
                            p.nascimento,
                            ag.situacao_revisor,
                            o.nome as medico,
                            o.conselho,
                            ag.assinatura,
                            ag.rodape,
                            ag.guia_id,
                            ag.cabecalho,
                            ag.medico_parecer1,
                            ag.medico_parecer2,
                            me.nome as solicitante,
                            op.nome as medicorevisor,
                            pt.nome as procedimento,
                            pt.grupo,
                            ae.agenda_exames_id,
                            ag.imagens,
                            c.nome as convenio,
                            pc.convenio_id,
                            p.nome as paciente,
                            ar.assinatura,
                            ar.carimbo,
                            o.carimbo as medico_carimbo');
        $this->db->from('tb_ambulatorio_exame ar');
        $this->db->join('tb_ambulatorio_laudo ag', 'ag.ambulatorio_laudo_id = ar.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ag.medico_parecer2', 'left');
        $this->db->join('tb_exames e', 'e.exames_id = ag.exame_id ', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->join('tb_operador me', 'me.operador_id = ae.medico_solicitante', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'pc.convenio_id = c.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where("ar.ambulatorio_exame_id", $ambulatorio_laudo_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listaratestadoimpressao($ambulatorio_laudo_id) {

        $this->db->select('ag.ambulatorio_laudo_id,
                            ag.paciente_id,
                            ag.data_cadastro,
                            ag.exame_id,
                            ag.peso,
                            ag.altura,
                            ag.situacao,
                            ae.agenda_exames_nome_id,
                            ar.texto,
                            ar.data,
                            ar.imprimir_cid,
                            ar.cid1,
                            ar.cid2,
                            p.nascimento,
                            ag.situacao_revisor,
                            o.nome as medico,
                            o.conselho,
                            ag.assinatura,
                            ag.rodape,
                            ag.guia_id,
                            ag.cabecalho,
                            ag.medico_parecer1,
                            ag.medico_parecer2,
                            me.nome as solicitante,
                            op.nome as medicorevisor,
                            pt.nome as procedimento,
                            pt.grupo,
                            ae.agenda_exames_id,
                            ag.imagens,
                            c.nome as convenio,
                            pc.convenio_id,
                            p.nome as paciente,
                            p.nascimento,
                            p.cpf,
                            p.sexo,
                            ar.assinatura,
                            ar.carimbo,
                            o.carimbo as medico_carimbo');
        $this->db->from('tb_ambulatorio_atestado ar');
        $this->db->join('tb_ambulatorio_laudo ag', 'ag.ambulatorio_laudo_id = ar.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ag.medico_parecer2', 'left');
        $this->db->join('tb_exames e', 'e.exames_id = ag.exame_id ', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->join('tb_operador me', 'me.operador_id = ae.medico_solicitante', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'pc.convenio_id = c.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where("ar.ambulatorio_atestado_id", $ambulatorio_laudo_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listarformimpressao($ambulatorio_laudo_id) {

        $this->db->select('ag.ambulatorio_laudo_id,
                            ag.paciente_id,
                            ag.data_cadastro,
                            ag.exame_id,
                            ag.peso,
                            ag.altura,
                            ag.situacao,
                            ae.agenda_exames_nome_id,
                            ar.texto,
                            ar.data,
                            ar.imprimir_cid,
                            ar.cid1,
                            ar.cid2,
                            p.nascimento,
                            ag.situacao_revisor,
                            o.nome as medico,
                            o.conselho,
                            ag.assinatura,
                            ag.rodape,
                            ag.guia_id,
                            ag.cabecalho,
                            ag.medico_parecer1,
                            ag.medico_parecer2,
                            me.nome as solicitante,
                            op.nome as medicorevisor,
                            pt.nome as procedimento,
                            pt.grupo,
                            ae.agenda_exames_id,
                            ag.imagens,
                            c.nome as convenio,
                            pc.convenio_id,
                            p.nome as paciente,
                            p.nascimento,
                            p.cpf,
                            p.sexo,
                            ar.assinatura,
                            ar.carimbo,
                            o.carimbo as medico_carimbo');
        $this->db->from('tb_ambulatorio_atestado ar');
        $this->db->join('tb_ambulatorio_laudo ag', 'ag.ambulatorio_laudo_id = ar.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ag.medico_parecer2', 'left');
        $this->db->join('tb_exames e', 'e.exames_id = ag.exame_id ', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->join('tb_operador me', 'me.operador_id = ae.medico_solicitante', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'pc.convenio_id = c.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where("ar.ambulatorio_atestado_id", $ambulatorio_laudo_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listarcid($param) {
        $this->db->select('*');
        $this->db->from('tb_cid');
        $this->db->where("co_cid", $param);
        $return = $this->db->get();
        return $return->result();
    }

    function listarreceitaespecialimpressao($ambulatorio_laudo_id) {

        $this->db->select('ag.ambulatorio_laudo_id,
                            ag.paciente_id,
                            ag.data_cadastro,
                            ag.exame_id,
                            ag.situacao,
                            ae.agenda_exames_nome_id,
                            ar.texto,
                            p.bairro,
                            p.nascimento,
                            p.nome as paciente,
                            p.logradouro,
                            p.numero,
                            mp.nome as cidade,
                            mp.estado as estado,
                            p.rg,
                            p.uf_rg,
                            ag.situacao_revisor,
                            o.nome as medico,
                            o.conselho,
                            ag.assinatura,
                            ag.rodape,
                            ag.guia_id,
                            ag.cabecalho,
                            ag.medico_parecer1,
                            pt.nome as procedimento,
                            pt.grupo,
                            ae.agenda_exames_id,
                            ag.imagens,
                            ar.data_cadastro,
                            m.nome as cidaempresa,
                            m.estado as estadoempresa,
                            ep.bairro as bairroemp,
                            ep.logradouro as endempresa,
                            ep.numero as numeroempresa,
                            ep.telefone as telempresa,
                            ep.celular as celularempresa,
                            tl.descricao as tipologradouro,
                            c.nome as convenio,
                            pc.convenio_id,
                            ar.assinatura,
                            ar.carimbo,
                            o.carimbo as medico_carimbo,
                            cbo.descricao,
                            ar.receita_id,
                            amr.nome as modelo');
        $this->db->from('tb_ambulatorio_receituario_especial ar');
        $this->db->join('tb_ambulatorio_modelo_receita_especial amr', 'amr.ambulatorio_modelo_receita_especial_id = ar.receita_id', 'left');
        $this->db->join('tb_ambulatorio_laudo ag', 'ag.ambulatorio_laudo_id = ar.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ar.medico_parecer1', 'left');
        $this->db->join('tb_tipo_logradouro tl', 'tl.tipo_logradouro_id = o.tipo_logradouro', 'left');
        $this->db->join('tb_exames e', 'e.exames_id = ag.exame_id ', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'pc.convenio_id = c.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_empresa ep', 'ep.empresa_id = ae.empresa_id', 'left');
        $this->db->join('tb_municipio m', 'm.municipio_id = ep.municipio_id', 'left');
        $this->db->join('tb_municipio mp', 'mp.municipio_id = p.municipio_id', 'left');
        $this->db->join('tb_cbo_ocupacao cbo', 'cbo.cbo_ocupacao_id = o.cbo_ocupacao_id', 'left');
        $this->db->where("ar.ambulatorio_receituario_especial_id", $ambulatorio_laudo_id);
        $return = $this->db->get();
        return $return->result();
    }

    function excluir($ambulatorio_laudo_id) {

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('ambulatorio_laudo_id', $ambulatorio_laudo_id);
        $this->db->update('tb_ambulatorio_laudo');
        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return false;
        else
            return true;
    }

    function gravar($paciente_id) {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('paciente_id', $paciente_id);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_ambulatorio_laudo');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            else
                $ambulatorio_laudo_id = $this->db->insert_id();


            return $ambulatorio_laudo_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarencaminhamentoatendimento() {
        try {
            /* inicia o mapeamento no banco */
//            $horario = date("Y-m-d H:i:s");
//            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('encaminhado', 't');
            $this->db->set('medico_encaminhamento_id', $_POST['medico_id']);
            $this->db->where('ambulatorio_laudo_id', $_POST['ambulatorio_laudo_id']);
            $this->db->update('tb_ambulatorio_laudo');
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarhistorico($paciente_id) {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('paciente_id', $paciente_id);
            $this->db->set('laudo', $_POST['laudo']);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_laudoantigo');
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function listarautocompletelaudos($parametro = null) {
        $this->db->select('texto');
        $this->db->from('tb_ambulatorio_laudo');
        $this->db->where('ambulatorio_laudo_id', $parametro);
        $return = $this->db->get();
        return $return->result();
    }

    function listarautocompleterepetirreceituario($ambulatorio_laudo_id) {
        $this->db->select(' ag.ambulatorio_receituario_id ,
                            ag.data_cadastro,
                            ag.texto,
                            ag.especial,
                            ag.medico_parecer1');
        $this->db->from('tb_ambulatorio_receituario ag');
        $this->db->where('ag.ambulatorio_receituario_id', $ambulatorio_laudo_id);
        $this->db->where('ag.tipo', 'NORMAL');
        $return = $this->db->get();
        return $return->result();
    }

    function repetirreceituariopormes($ambulatorio_laudo_id, $meses){
        $this->db->select('data_cadastro, texto, medico_parecer1, laudo_id, tipo, assinatura, carimbo, especial');
        $this->db->from('tb_ambulatorio_receituario');
        $this->db->where('ambulatorio_receituario_id', $ambulatorio_laudo_id);
        $return = $this->db->get()->result();

        for($i = 1; $i<=$meses; $i++){
            $data = date('Y-m-d', strtotime($return[0]->data_cadastro .'+ '. $i. ' months'));
            $data = $data.' 00:00:00';
            $this->db->set('texto', $return[0]->texto);
            $this->db->set('medico_parecer1', $return[0]->medico_parecer1);
            $this->db->set('laudo_id', $return[0]->laudo_id);
            $this->db->set('tipo', $return[0]->tipo);
            $this->db->set('assinatura', $return[0]->assinatura);
            $this->db->set('carimbo', $return[0]->carimbo);
            $this->db->set('especial', $return[0]->especial);
            $this->db->set('data_cadastro', $data);
            $this->db->set('operador_cadastro', $this->session->userdata('operador_id'));
            $this->db->insert('tb_ambulatorio_receituario');
        }

        return true;
    }

    function repetirreceituariopormes_2($ambulatorio_laudo_id, $meses){
        $this->db->select('data_cadastro, texto, medico_parecer1, laudo_id, tipo, assinatura, carimbo, especial, receita_id, adendo');
        $this->db->from('tb_ambulatorio_receituario');
        $this->db->where('ambulatorio_receituario_id', $ambulatorio_laudo_id);
        $return = $this->db->get()->result();

            $data = date('Y-m-d', strtotime($return[0]->data_cadastro .' + '. $meses. ' months'));
            $data = $data.' 00:00:00';
            $this->db->set('texto', $return[0]->texto);
            $this->db->set('medico_parecer1', $return[0]->medico_parecer1);
            $this->db->set('laudo_id', $return[0]->laudo_id);
            $this->db->set('tipo', $return[0]->tipo);
            $this->db->set('assinatura', $return[0]->assinatura);
            $this->db->set('carimbo', $return[0]->carimbo);
            $this->db->set('especial', $return[0]->especial);
            $this->db->set('data_cadastro', $data);
            $this->db->set('receita_id', $return[0]->receita_id);
            $this->db->set('adendo', $return[0]->adendo);
            $this->db->set('operador_cadastro', $this->session->userdata('operador_id'));
            $this->db->insert('tb_ambulatorio_receituario');

            $insert_id = $this->db->insert_id();

        return $insert_id;
    }

    function listarautocompleterepetirexame($ambulatorio_exame_id) {
        $this->db->select(' ag.ambulatorio_exame_id ,
                            ag.data_cadastro,
                            ag.texto,
                            ag.medico_parecer1');
        $this->db->from('tb_ambulatorio_exame ag');
        $this->db->where('ag.ambulatorio_exame_id', $ambulatorio_exame_id);
        $this->db->where('ag.tipo', 'NORMAL');
        $return = $this->db->get();
        return $return->result();
    }

    function listarautocompleterepetirrotina($ambulatorio_laudo_id) {
        $this->db->select(' ar.ambulatorio_rotinas_id ,
                            ar.data_cadastro,
                            ar.texto,
                            ar.medico_parecer1');
        $this->db->from('tb_ambulatorio_rotinas ar');
        $this->db->where('ar.ambulatorio_rotinas_id', $ambulatorio_laudo_id);
        $this->db->where('ar.tipo', 'NORMAL');
        $return = $this->db->get();
        return $return->result();
    }

    function listarautocompleteeditarreceituario($ambulatorio_laudo_id) {
        $this->db->select(' ag.ambulatorio_receituario_id ,
                            ag.texto,
                            ag.medico_parecer1');
        $this->db->from('tb_ambulatorio_receituario ag');
        $this->db->where('ag.ambulatorio_receituario_id', $ambulatorio_laudo_id);
        $this->db->where('ag.tipo', 'NORMAL');
        $return = $this->db->get();
        return $return->result();
    }

    function listarlaudos($parametro, $ambulatorio_laudo_id) {

        $this->db->select('al.data_cadastro,
                            pt.nome as procedimento,
                            p.nome as paciente,
                            o.nome as medico,
                            pi.nome as indicacao,
                            al.peso,
                            al.altura,
                            al.exame_id,
                            al.ambulatorio_laudo_id');
        $this->db->from('tb_ambulatorio_laudo al');
        $this->db->join('tb_paciente p', 'p.paciente_id = al.paciente_id', 'left');
        $this->db->join('tb_paciente_indicacao pi', 'pi.paciente_indicacao_id = p.indicacao', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = al.medico_parecer1', 'left');
        $this->db->join('tb_exames e', 'e.exames_id = al.exame_id ', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where('al.paciente_id', $parametro);
        $this->db->where('al.ambulatorio_laudo_id !=', $ambulatorio_laudo_id);
        $this->db->orderby('al.data_cadastro desc');
        $return = $this->db->get();
        return $return->result();
    }



    function listaratendimento($ambulatorio_laudo_id){
        $this->db->select('al.data,
                           o.nome as medico,
                           p.nome as paciente,
                           p.cns as email,
                           p.cns2 as email2,
                           pt.nome as procedimento,
                           p.paciente_id');
        $this->db->from('tb_ambulatorio_laudo al');
        $this->db->join('tb_operador o', 'al.medico_parecer1 = o.operador_id', 'left');
        $this->db->join('tb_paciente p', 'al.paciente_id = p.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = al.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pc.procedimento_tuss_id = pt.procedimento_tuss_id', 'left');
        $this->db->where('al.ambulatorio_laudo_id', $ambulatorio_laudo_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listarlaudospesoaltura($parametro) {

        $this->db->select('al.data_cadastro,
                            pt.nome as procedimento,
                            p.nome as paciente,
                            o.nome as medico,
                            pi.nome as indicacao,
                            ag.peso,
                            ag.altura,
                            ag.pulso,
                            ag.temperatura,
                            ag.pressao_arterial,
                            ag.f_respiratoria,
                            ag.spo2,
                            ag.medicacao,
                            al.diabetes,
                            al.hipertensao,
                            al.exame_id,
                            al.ambulatorio_laudo_id');
        $this->db->from('tb_ambulatorio_laudo al');
        $this->db->join('tb_paciente p', 'p.paciente_id = al.paciente_id', 'left');
        $this->db->join('tb_paciente_indicacao pi', 'pi.paciente_indicacao_id = p.indicacao', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = al.medico_parecer1', 'left');
        $this->db->join('tb_exames e', 'e.exames_id = al.exame_id ', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_guia ag', 'ag.ambulatorio_guia_id = ae.guia_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where('al.paciente_id', $parametro);
        $this->db->where("(ag.peso > 0 OR ag.altura > 0 OR al.diabetes != '' OR al.hipertensao != '')");
        // $this->db->where('');
//        $this->db->where('al.ambulatorio_laudo_id !=', $ambulatorio_laudo_id);
        $this->db->orderby('al.data_cadastro desc');
        $return = $this->db->get();
        return $return->result();
    }

    function listarlaudosintegracao($agenda_exames_id) {

        $this->db->select('exame_id');
        $this->db->from('tb_integracao_laudo');
        $this->db->where('exame_id', $agenda_exames_id);
        $this->db->where('laudo_status', 'PUBLICADO');
        $return = $this->db->get();
        return $return->result();
    }

    function atualizacaolaudosintegracao($agenda_exames_id) {


        $this->db->select('il.integracao_laudo_id,
                            il.exame_id,
                            il.laudo_texto,
                            il.laudo_data_hora,
                            al.ambulatorio_laudo_id,
                            il.laudo_status,
                            al.ambulatorio_laudo_id,
                            o.operador_id as medico,
                            op.operador_id as revisor');
        $this->db->from('tb_integracao_laudo il');
        $this->db->join('tb_operador o', 'o.conselho = il.laudo_conselho_medico', 'left');
        $this->db->join('tb_operador op', 'op.conselho = il.laudo_conselho_medico_revisor', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = il.exame_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->where('il.exame_id', $agenda_exames_id);
        $this->db->where('il.laudo_status', 'PUBLICADO');
        $this->db->where('o.ativo', 't');
        $this->db->where('op.ativo', 't');
        $this->db->orderby('il.integracao_laudo_id');
        $query = $this->db->get();
        $return = $query->result();

        foreach ($return as $value) {
            $laudo_texto = $value->laudo_texto;
            $laudo_data_hora = $value->laudo_data_hora;
            $ambulatorio_laudo_id = $value->ambulatorio_laudo_id;
            $medico = $value->medico;
            $revisor = $value->revisor;
            $this->db->set('texto', $laudo_texto);
            $this->db->set('situacao', 'FINALIZADO');
            $this->db->set('medico_parecer1', $medico);
            $this->db->set('medico_parecer2', $revisor);
            $this->db->set('data_atualizacao', $laudo_data_hora);
            $this->db->where('ambulatorio_laudo_id', $ambulatorio_laudo_id);
            $this->db->update('tb_ambulatorio_laudo');
        }

        $this->db->set('medico_consulta_id', $medico);
        $this->db->where('agenda_exames_id', $agenda_exames_id);
        $this->db->update('tb_agenda_exames');

        $this->db->set('laudo_status', 'LIDO');
        $this->db->where('exame_id', $agenda_exames_id);
        $this->db->update('tb_integracao_laudo');
    }

    function listarlaudoscontador($parametro, $ambulatorio_laudo_id) {

        $this->db->select('al.data_cadastro,
                            pt.nome,
                            p.nome as paciente,
                            o.nome as medico,
                            al.exame_id,
                            al.ambulatorio_laudo_id');
        $this->db->from('tb_ambulatorio_laudo al');
        $this->db->join('tb_paciente p', 'p.paciente_id = al.paciente_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = al.medico_parecer1', 'left');
        $this->db->join('tb_exames e', 'e.exames_id = al.exame_id ', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where('al.paciente_id', $parametro);
        $this->db->where('al.ambulatorio_laudo_id !=', $ambulatorio_laudo_id);
        $return = $this->db->count_all_results();
        return $return;
    }

    function gravarlaudo($ambulatorio_laudo_id, $exame_id, $sala_id, $procedimento_tuss_id) {
        try {
            /* inicia o mapeamento no banco */

            // var_dump($_POST); die;
           $empresa_id = $this->session->userdata('empresa_id');
            
       $this->db->select('e.empresa_id,
                            ordem_chegada,
                            oftamologia,convenio_padrao,
                            diagnostico_medico
                            ');
            $this->db->from('tb_empresa e');
            $this->db->where('e.empresa_id', $empresa_id);
            $this->db->join('tb_empresa_permissoes ep', 'ep.empresa_id = e.empresa_id', 'left');
            $this->db->orderby('e.empresa_id');
            $permissao = $this->db->get()->result();

            $this->db->select('medico_parecer1');
            $this->db->from('tb_ambulatorio_laudo');
            $this->db->where('ambulatorio_laudo_id', $ambulatorio_laudo_id);
            $query = $this->db->get();
            $medico_r = $query->result();

            if(count($medico_r) > 0){
                if($medico_r[0]->medico_parecer1 != $_POST['medico'] && $medico_r[0]->medico_parecer1 > 0){
                    $this->backupLaudo($ambulatorio_laudo_id, 'Alterou profissional');
                }
            }
            // die;
            $this->backupLaudo($ambulatorio_laudo_id);
            $this->backupLaudo($ambulatorio_laudo_id, 'Finalizou o Laudo');
            
            
            $this->db->set('ativo', 't');
            $this->db->where('exame_sala_id', $sala_id);
            $this->db->update('tb_exame_sala');

            if (isset($_POST['indicado'])) {
                $this->db->set('indicado', 't');
            } else {
                $this->db->set('indicado', 'f');
            }
            $this->db->set('situacao', 'FINALIZADO');
            $this->db->where('exames_id', $exame_id);
            $this->db->update('tb_exames');

            $this->db->select('agenda_exames_id');
            $this->db->from('tb_exames');
            $this->db->where("exames_id", $exame_id);
            $query = $this->db->get();
            $return = $query->result();


            $this->db->select('paciente_id, tipo_desconto');
            $this->db->from('tb_agenda_exames');
            $this->db->where('agenda_exames_id', $return[0]->agenda_exames_id);
            $query = $this->db->get();
            $paciente = $query->result();

                    
            $this->db->set('todos_visualizar', null);
            $this->db->where('agenda_exames_id', $return[0]->agenda_exames_id);
            $this->db->update('tb_agenda_exames');


            // var_dump($paciente); die;

            $this->db->set('informacao_laudo', $_POST['informacao_laudo']);
            $this->db->where('paciente_id', $paciente[0]->paciente_id);
            $this->db->update('tb_paciente');

            $this->db->select('mc.valor as perc_medico, mc.percentual');
            $this->db->from('tb_procedimento_percentual_medico_convenio mc');
            $this->db->join('tb_procedimento_percentual_medico m', 'm.procedimento_percentual_medico_id = mc.procedimento_percentual_medico_id', 'left');
            $this->db->where('m.procedimento_tuss_id', $procedimento_tuss_id);
            $this->db->where('mc.medico', $_POST['medico']);
            $this->db->where('mc.ativo', 'true');
            $this->db->where('mc.revisor', 'false');
            $percentual = $this->db->get()->result();

            if (count($percentual) == 0) {
                $this->db->select('pt.perc_medico, pt.percentual');
                $this->db->from('tb_procedimento_convenio pc');
                $this->db->join('tb_procedimento_tuss pt', 'pc.procedimento_tuss_id = pt.procedimento_tuss_id', 'left');
                $this->db->where('pc.procedimento_convenio_id', $procedimento_tuss_id);
//                        $this->db->where('pc.ativo', 'true');
//                        $this->db->where('pt.ativo', 'true');
                $percentual = $this->db->get()->result();
            }
            if ($_POST['medicorevisor'] != '') {
                $this->db->select('mc.valor as perc_medico, mc.percentual');
                $this->db->from('tb_procedimento_percentual_medico_convenio mc');
                $this->db->join('tb_procedimento_percentual_medico m', 'm.procedimento_percentual_medico_id = mc.procedimento_percentual_medico_id', 'left');
                $this->db->where('m.procedimento_tuss_id', $procedimento_tuss_id);
                $this->db->where('mc.medico', $_POST['medicorevisor']);
                $this->db->where('mc.ativo', 'true');
                $this->db->where('mc.revisor', 'true');
                $percentualrevisor = $this->db->get()->result();
                if (count($percentualrevisor) == 0) {
                    $this->db->select('pt.valor_revisor as perc_medico, pt.percentual_revisor as percentual');
                    $this->db->from('tb_procedimento_convenio pc');
                    $this->db->join('tb_procedimento_tuss pt', 'pc.procedimento_tuss_id = pt.procedimento_tuss_id', 'left');
                    $this->db->where('pc.procedimento_convenio_id', $procedimento_tuss_id);
//                        $this->db->where('pc.ativo', 'true');
//                        $this->db->where('pt.ativo', 'true');
                    $percentualrevisor = $this->db->get()->result();
                }
            }

            if (isset($_POST['rev'])) {
                switch ($_POST['tempoRevisao']) {
                    case '1a':
                        $dias = '+1 year';
                        break;
                    case '6m':
                        $dias = '+6 month';
                        break;
                    case '3m':
                        $dias = '+3 month';
                        break;
                    case '1m':
                        $dias = '+1 month';
                        break;
                    default:
                        $dias = '';
                }
                if ($dias != '') {
                    $diaRevisao = date('Y-m-d', strtotime($dias));
                    $this->db->set('data_revisao', $diaRevisao);
                }
            }
            if ($paciente[0]->tipo_desconto == '') {
                $this->db->set('valor_medico', $percentual[0]->perc_medico);
                $this->db->set('percentual_medico', $percentual[0]->percentual);
            }

            if ($_POST['medicorevisor'] != '') {
                $this->db->set('valor_revisor', $percentualrevisor[0]->perc_medico);
                $this->db->set('percentual_revisor', $percentualrevisor[0]->percentual);
            }


          if ($permissao[0]->convenio_padrao == 't') {
            
                if ($_POST['medico'] != "") {
                    if ($_POST['medico'] == 1 && $this->session->userdata('operador_id') == 1) {
                        $this->db->set('medico_agenda', $_POST['medico']);
                        $this->db->set('medico_consulta_id', $_POST['medico']);
                       
                    } elseif ($_POST['medico'] != 1 && $this->session->userdata('operador_id') == $_POST['medico']) {
                        $this->db->set('medico_agenda', $_POST['medico']);
                        $this->db->set('medico_consulta_id', $_POST['medico']);
                        
                    } elseif ($_POST['medico'] != $this->session->userdata('operador_id')) {
                        $this->db->set('medico_agenda', $this->session->userdata('operador_id'));
                        $this->db->set('medico_consulta_id', $this->session->userdata('operador_id'));                        
                    } else {
                        $this->db->set('medico_agenda', $_POST['medico']);
                        $this->db->set('medico_consulta_id', $_POST['medico']);
                    }
                }
            } else {
                if ($_POST['medico'] != "") {
                    $this->db->set('medico_agenda', $_POST['medico']);
                    $this->db->set('medico_consulta_id', $_POST['medico']);
                }
            }
                    

            $this->db->where('agenda_exames_id', $return[0]->agenda_exames_id);
            $this->db->update('tb_agenda_exames');

            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            if (isset($_POST['revisor'])) {
                $this->db->set('revisor', 't');
                $this->db->set('situacao_revisor', 'AGUARDANDO');
            } else {
                $this->db->set('revisor', 'f');
            }
            $this->db->set('texto_laudo', $_POST['laudo']);

            $this->db->set('texto', $_POST['laudo']);
            if ($_POST['adendo'] != '') {
                $post_adendo = str_replace('<!DOCTYPE html>', '', $_POST['adendo']);
                $post_adendo = str_replace('<html>', '', $post_adendo);
                $post_adendo = str_replace('<head>', '', $post_adendo);
                $post_adendo = str_replace('</head>', '', $post_adendo);
                $post_adendo = str_replace('<body>', '', $post_adendo);
                $adendo_coluna = "<p><strong>Adendo de: " . date("d/m/Y H:i:s") . "<br></strong></p>" . $post_adendo;
                $adendo = str_replace('</body>', '', $_POST['laudo']);
                $adendo = str_replace('</html>', '', $adendo);

                $adendo = $adendo . $adendo_coluna . "</body></html>";
//                $this->db->set('adendo', $adendo_coluna);
                $this->db->set("texto", $adendo);
            }
//            if ($_POST['adendo'] != '') {
//                $post_adendo = str_replace('<!DOCTYPE html>', '', $_POST['adendo']) ;
//                $post_adendo = str_replace('<html>', '', $post_adendo) ;
//                $post_adendo = str_replace('<head>', '', $post_adendo) ;
//                $post_adendo = str_replace('</head>', '', $post_adendo) ;
//                $post_adendo = str_replace('<body>', '', $post_adendo) ;
//                $adendo_coluna = "<p><strong>Adendo de: " . date("d/m/Y H:i:s") . "<br></strong></p>" . $post_adendo;
//                $adendo = str_replace('</body>', '', $_POST['laudo']);
//                $adendo = str_replace('</html>', '', $adendo);
//                
//                $adendo = $adendo . $adendo_coluna . "</body></html>";
////                $this->db->set('adendo', $adendo_coluna);
//                $this->db->set("texto", $adendo);
//            }
//            var_dump($adendo); die;


            if ($permissao[0]->convenio_padrao == 't') {

                if ($_POST['medico'] != '') {
//                $this->db->set('medico_parecer1', $_POST['medico']);     
                    if ($_POST['medico'] == 1 && $this->session->userdata('operador_id') == 1) {
                        $this->db->set('medico_parecer1', $_POST['medico']);
                    } elseif ($_POST['medico'] != 1 && $this->session->userdata('operador_id') == $_POST['medico']) {
                        $this->db->set('medico_parecer1', $_POST['medico']);
                    } elseif ($_POST['medico'] != $this->session->userdata('operador_id')) {
                        $this->db->set('medico_parecer1', $this->session->userdata('operador_id'));
                    } else {
                        $this->db->set('medico_parecer1', $_POST['medico']);
                    }
                }
            } else {
                if ($_POST['medico'] != '') {
                    $this->db->set('medico_parecer1', $_POST['medico']);
                }
            }



            if ($_POST['medicorevisor'] != '') {
                $this->db->set('medico_parecer2', $_POST['medicorevisor']);
            } else {
                $this->db->set('medico_parecer2', null);
            }
            if (isset($_POST['assinatura'])) {
                $this->db->set('assinatura', 't');
            } else {
                $this->db->set('assinatura', 'f');
            }
            if (isset($_POST['carimbo'])) {
                $this->db->set('carimbo', 't');
            } else {
                $this->db->set('carimbo', 'f');
            }

            $this->db->set('cabecalho', $_POST['cabecalho']);

            if (@$_POST['txtDiagnostico'] != "") {
                $this->db->set('diagnostico', @$_POST['txtDiagnostico']);
            } else {
                $this->db->set('diagnostico', null);
            }

            if (isset($_POST['imagem'])) {
                $this->db->set('imagens', $_POST['imagem']);
            }
            if ($_POST['exame'] > 0) {
                $this->db->set('laudo_modelo_id', $_POST['exame']);
            }


            if ($permissao[0]->diagnostico_medico == 't') {
                if(@$_POST['opcoes'] != ''){
                $this->db->set('opcoes_diagnostico', $_POST['opcoes']);
                $this->db->set('nivel1_diagnostico', $_POST['nivel1']);
                $this->db->set('nivel2_diagnostico', $_POST['nivel2']);
                $this->db->set('nivel3_diagnostico', $_POST['nivel3']);
                }
            }

            $this->db->set('situacao', 'FINALIZADO');
            $this->db->set('data_finalizado', $horario);
            $this->db->set('operador_finalizado', $operador_id);
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('ambulatorio_laudo_id', $ambulatorio_laudo_id);
            $this->db->update('tb_ambulatorio_laudo');
            
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarlaudolaboratorial($ambulatorio_laudo_id, $exame_id) {
        try {
            /* inicia o mapeamento no banco */
            if (isset($_POST['indicado'])) {
                $this->db->set('indicado', 't');
            } else {
                $this->db->set('indicado', 'f');
            }
            $this->db->where('exames_id', $exame_id);
            $this->db->update('tb_exames');



            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            if (isset($_POST['revisor'])) {
                $this->db->set('revisor', 't');
                $this->db->set('situacao_revisor', 'AGUARDANDO');
            } else {
                $this->db->set('revisor', 'f');
            }
            $this->db->set('texto_laudo', $_POST['laudo']);
            $this->db->set('texto', $_POST['laudo']);
            if ($_POST['medico'] != '') {
                $this->db->set('medico_parecer1', $_POST['medico']);
            }
            if ($_POST['medicorevisor'] != '') {
                $this->db->set('medico_parecer2', $_POST['medicorevisor']);
            } else {
                $this->db->set('medico_parecer2', null);
            }
            if (isset($_POST['assinatura'])) {
                $this->db->set('assinatura', 't');
            } else {
                $this->db->set('assinatura', 'f');
            }

            $this->db->set('cabecalho', $_POST['cabecalho']);
            if (isset($_POST['imagem'])) {
                $this->db->set('imagens', $_POST['imagem']);
            }
            $this->db->set('situacao', 'FINALIZADO');
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('ambulatorio_laudo_id', $ambulatorio_laudo_id);
            $this->db->update('tb_ambulatorio_laudo');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarlaudoeco($ambulatorio_laudo_id) {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            if (isset($_POST['revisor'])) {
                $this->db->set('revisor', 't');
                $this->db->set('situacao_revisor', 'AGUARDANDO');
            } else {
                $this->db->set('revisor', 'f');
            }
            $this->db->set('texto_laudo', $_POST['laudo']);
            $this->db->set('texto', $_POST['laudo']);
            if ($_POST['medico'] != '') {
                $this->db->set('medico_parecer1', $_POST['medico']);
            }
            if ($_POST['Peso'] != '') {
                $this->db->set('peso', str_replace(",", ".", $_POST['peso']));
            }
            if ($_POST['Altura'] != '') {
                $this->db->set('altura', $_POST['Altura']);
            }
            if ($_POST['Superf'] != '0.00') {
                $this->db->set('superficie_corporea', $_POST['Superf']);
            }
            if ($_POST['Volume_telediastolico'] != '0.00') {
                $this->db->set('ve_volume_telediastolico', $_POST['Volume_telediastolico']);
            }
            if ($_POST['Volume_telessistolico'] != '0.00') {
                $this->db->set('ve_volume_telessistolico', $_POST['Volume_telessistolico']);
            }
            if ($_POST['Diametro_telediastolico'] != '') {
                $this->db->set('ve_diametro_telediastolico', $_POST['Diametro_telediastolico']);
            }
            if ($_POST['Diametro_telessistolico'] != '') {
                $this->db->set('ve_diametro_telessistolico', $_POST['Diametro_telessistolico']);
            }
            if ($_POST['indice_diametro_diastolico'] != 'NaN') {
                $this->db->set('ve_indice_do_diametro_diastolico', $_POST['indice_diametro_diastolico']);
            }
            if ($_POST['Septo_interventricular'] != '') {
                $this->db->set('ve_septo_interventricular', $_POST['Septo_interventricular']);
            }
            if ($_POST['Parede_posterior'] != '') {
                $this->db->set('ve_parede_posterior', $_POST['Parede_posterior']);
            }
            if ($_POST['Relacao_septo_parede'] != 'NaN') {
                $this->db->set('ve_relacao_septo_parede_posterior', $_POST['Relacao_septo_parede']);
            }
            if ($_POST['Espessura_relativa'] != 'NaN') {
                $this->db->set('ve_espessura_relativa_paredes', $_POST['Espessura_relativa']);
            }
            if ($_POST['Massa_ventricular'] != '0.60') {
                $this->db->set('ve_massa_ventricular', $_POST['Massa_ventricular']);
            }
            if ($_POST['indice_massa'] != 'Infinity') {
                $this->db->set('ve_indice_massa', $_POST['indice_massa']);
            }
            if ($_POST['Relacao_volume_massa'] != '0.00') {
                $this->db->set('ve_relacao_volume_massa', $_POST['Relacao_volume_massa']);
            }
            if ($_POST['Fracao_ejecao'] != 'NaN') {
                $this->db->set('ve_fracao_ejecao', $_POST['Fracao_ejecao']);
            }
            if ($_POST['Fracao_encurtamento'] != 'NaN') {
                $this->db->set('ve_fracao_encurtamento', $_POST['Fracao_encurtamento']);
            }
            if ($_POST['Diametro_telediastolico'] != '') {
                $this->db->set('vd_diametro_telediastolico', $_POST['Diametro_telediastolico']);
            }
            if ($_POST['area_telediastolica'] != '') {
                $this->db->set('vd_area_telediastolica', $_POST['area_telediastolica']);
            }
            if ($_POST['Diametro'] != '') {
                $this->db->set('ae_diametro', $_POST['Diametro']);
            }
            if ($_POST['indice_diametro'] != '') {
                $this->db->set('ae_indice_diametro', $_POST['indice_diametro']);
            }
            if ($_POST['Diametro_raiz'] != '') {
                $this->db->set('ao_diametro_raiz', $_POST['Diametro_raiz']);
            }
            if ($_POST['Relacao_atrio_esquerdo'] != 'NaN') {
                $this->db->set('ao_relacao_atrio_esquerdo_aorta', $_POST['Relacao_atrio_esquerdo']);
            }
            if ($_POST['medicorevisor'] != '') {
                $this->db->set('medico_parecer2', $_POST['medicorevisor']);
            } else {
                $this->db->set('medico_parecer2', null);
            }
            if (isset($_POST['assinatura'])) {
                $this->db->set('assinatura', 't');
            } else {
                $this->db->set('assinatura', 'f');
            }
            if (isset($_POST['rodape'])) {
                $this->db->set('rodape', 't');
            } else {
                $this->db->set('rodape', 'f');
            }
            $this->db->set('cabecalho', $_POST['cabecalho']);
            if (isset($_POST['imagem'])) {
                $this->db->set('imagens', $_POST['imagem']);
            }
            $this->db->set('situacao', 'FINALIZADO');
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('ambulatorio_laudo_id', $ambulatorio_laudo_id);
            $this->db->update('tb_ambulatorio_laudo');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravaroit() {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('medico_parecer', $_POST['medico_parecer']);
            if (@$_POST['data'] != "") {
                $this->db->set('data', $_POST['data']);
            }

            $this->db->set('rx_digital', @$_POST['rx_digital']);
            $this->db->set('leitura_negatoscopio', @$_POST['leitura_negatoscopio']);
            $this->db->set('qualidade_tecnica', @$_POST['qualidade_tecnica']);
            $this->db->set('radiografia_normal', @$_POST['radiografia_normal']);
            $this->db->set('comentario_1a', @$_POST['comentario_1a']);

            if(@$_POST['radiografia_normal'] == 'f'){

                $this->db->set('anormalidade_parenquima', @$_POST['anormalidade_parenquima']);
                    if(@$_POST['anormalidade_parenquima'] == 't'){


                        if (@count($_POST['forma_primaria']) > 0) {
                            $this->db->set('forma_primaria_array', json_encode(@$_POST['forma_primaria']));
                        } else {
                            $this->db->set('forma_primaria_array', NULL);
                        }

                        if (@count($_POST['forma_secundaria']) > 0) {
                            $this->db->set('forma_secundaria_array', json_encode(@$_POST['forma_secundaria']));
                        } else {
                            $this->db->set('forma_secundaria_array', NULL);
                        }

                        if (@count($_POST['zona_d']) > 0) {
                            $this->db->set('zonas_d_e_array', json_encode(@$_POST['zona_d']));
                        } else {
                            $this->db->set('zonas_d_e_array', NULL);
                        }

                        if (@count($_POST['profusao']) > 0) {
                            $this->db->set('profusao_array', json_encode(@$_POST['profusao']));
                        } else {
                            $this->db->set('profusao_array', NULL);
                        }

                        if (@count($_POST['grandes_opacidades']) > 0) {
                            $this->db->set('grandes_opacidades_array', json_encode(@$_POST['grandes_opacidades']));
                        } else {
                            $this->db->set('grandes_opacidades_array', NULL);
                        }

                    }else{
                        $this->db->set('forma_primaria_array',NULL);
                        $this->db->set('forma_secundaria_array', NULL);
                        $this->db->set('zonas_d_e_array', NULL);
                        $this->db->set('profusao_array', NULL);
                        $this->db->set('grandes_opacidades_array', NULL);
                    }

                $this->db->set('anormalidade_pleural', @$_POST['anormalidade_pleural']);
                    if(@$_POST['anormalidade_pleural'] == 't'){
                        $this->db->set('placa_pleuras', @$_POST['placa_pleuras']);

                        if (@count($_POST['local_paredeperfil_3b']) > 0) {
                            $this->db->set('local_paredeperfil_3b_array', json_encode(@$_POST['local_paredeperfil_3b']));
                        } else {
                            $this->db->set('local_paredeperfil_3b_array', NULL);
                        }

                        if (@count($_POST['local_frontal_3b']) > 0) {
                            $this->db->set('local_frontal_3b_array', json_encode(@$_POST['local_frontal_3b']));
                        } else {
                            $this->db->set('local_frontal_3b_array', NULL);
                        }

                        if (@count($_POST['local_diafragma_3b']) > 0) {
                            $this->db->set('local_diafragma_3b_array', json_encode(@$_POST['local_diafragma_3b']));
                        } else {
                            $this->db->set('local_diafragma_3b_array', NULL);
                        }

                        if (@count($_POST['local_outroslocais_3b']) > 0) {
                            $this->db->set('local_outroslocais_3b_array', json_encode(@$_POST['local_outroslocais_3b']));
                        } else {
                            $this->db->set('local_outroslocais_3b_array', NULL);
                        }

                        if (@count($_POST['calcificacao_paredeperfil_3b']) > 0) {
                            $this->db->set('calcificacao_paredeperfil_3b_array', json_encode(@$_POST['calcificacao_paredeperfil_3b']));
                        } else {
                            $this->db->set('calcificacao_paredeperfil_3b_array', NULL);
                        }

                        if (@count($_POST['calcificacao_frontal_3b']) > 0) {
                            $this->db->set('calcificacao_frontal_3b_array', json_encode(@$_POST['calcificacao_frontal_3b']));
                        } else {
                            $this->db->set('calcificacao_frontal_3b_array', NULL);
                        }

                        if (@count($_POST['calcificacao_diafragma_3b']) > 0) {
                            $this->db->set('calcificacao_diafragma_3b_array', json_encode(@$_POST['calcificacao_diafragma_3b']));
                        } else {
                            $this->db->set('calcificacao_diafragma_3b_array', NULL);
                        }

                        if (@count($_POST['calcificacao_outroslocais_3b']) > 0) {
                            $this->db->set('calcificacao_outroslocais_3b_array', json_encode(@$_POST['calcificacao_diafragma_3b']));
                        } else {
                            $this->db->set('calcificacao_outroslocais_3b_array', NULL);
                        }

                        if (@count($_POST['extensao_parede_d_3b']) > 0) {
                            $this->db->set('extensao_parede_d_3b_array', json_encode(@$_POST['extensao_parede_d_3b']));
                        } else {
                            $this->db->set('extensao_parede_d_3b_array', NULL);
                        }

                        if (@count($_POST['extensao_parede_e_3b']) > 0) {
                            $this->db->set('extensao_parede_e_3b_array', json_encode(@$_POST['extensao_parede_e_3b']));
                        } else {
                            $this->db->set('extensao_parede_e_3b_array', NULL);
                        }

                        if (@count($_POST['largura_d_3b']) > 0) {
                            $this->db->set('largura_d_3b_array', json_encode(@$_POST['largura_d_3b']));
                        } else {
                            $this->db->set('largura_d_3b_array', NULL);
                        }

                        if (@count($_POST['largura_e_3b']) > 0) {
                            $this->db->set('largura_e_3b_array', json_encode(@$_POST['largura_e_3b']));
                        } else {
                            $this->db->set('largura_e_3b_array', NULL);
                        }

                        if (@count($_POST['obliteracao']) > 0) {
                            $this->db->set('obliteracao_array', json_encode(@$_POST['obliteracao']));
                        } else {
                            $this->db->set('obliteracao_array', NULL);
                        }

                        $this->db->set('espessamento_pleural_difuso', @$_POST['espessamento_pleural_difuso']);

                        if (@count($_POST['local_parede_perfil_3d']) > 0) {
                            $this->db->set('local_parede_perfil_3d_array', json_encode(@$_POST['local_parede_perfil_3d']));
                        } else {
                            $this->db->set('local_parede_perfil_3d_array', NULL);
                        }

                        if (@count($_POST['local_parede_frontal_3d']) > 0) {
                            $this->db->set('local_parede_frontal_3d_array', json_encode(@$_POST['local_parede_frontal_3d']));
                        } else {
                            $this->db->set('local_parede_frontal_3d_array', NULL);
                        }

                        if (@count($_POST['calcificacao_parede_perfil_3d']) > 0) {
                            $this->db->set('calcificacao_parede_perfil_3d_array', json_encode(@$_POST['calcificacao_parede_perfil_3d']));
                        } else {
                            $this->db->set('calcificacao_parede_perfil_3d_array', NULL);
                        }

                        if (@count($_POST['calcificacao_parede_frontal_3d']) > 0) {
                            $this->db->set('calcificacao_parede_frontal_3d_array', json_encode(@$_POST['calcificacao_parede_frontal_3d']));
                        } else {
                            $this->db->set('calcificacao_parede_frontal_3d_array', NULL);
                        }

                        if (@count($_POST['extensao_parede_d_3d']) > 0) {
                            $this->db->set('extensao_parede_d_3d_array', json_encode(@$_POST['extensao_parede_d_3d']));
                        } else {
                            $this->db->set('extensao_parede_d_3d_array', NULL);
                        }

                        if (@count($_POST['extensao_parede_e_3d']) > 0) {
                            $this->db->set('extensao_parede_e_3d_array', json_encode(@$_POST['extensao_parede_e_3d']));
                        } else {
                            $this->db->set('extensao_parede_e_3d_array', NULL);
                        }

                        if (@count($_POST['largura_d_3d']) > 0) {
                            $this->db->set('largura_d_3d_array', json_encode(@$_POST['largura_d_3d']));
                        } else {
                            $this->db->set('largura_d_3d_array', NULL);
                        }

                        if (@count($_POST['largura_e_3d']) > 0) {
                            $this->db->set('largura_e_3d_array', json_encode(@$_POST['largura_e_3d']));
                        } else {
                            $this->db->set('largura_e_3d_array', NULL);
                        }

                    }else{

                        $this->db->set('local_paredeperfil_3b_array', NULL);
                        $this->db->set('local_frontal_3b_array', NULL);
                        $this->db->set('local_diafragma_3b_array', NULL);
                        $this->db->set('local_outroslocais_3b_array', NULL);
                        $this->db->set('calcificacao_paredeperfil_3b_array', NULL);
                        $this->db->set('calcificacao_frontal_3b_array', NULL);
                        $this->db->set('calcificacao_diafragma_3b_array', NULL);
                        $this->db->set('calcificacao_outroslocais_3b_array', NULL);
                        $this->db->set('extensao_parede_d_3b_array', NULL);
                        $this->db->set('extensao_parede_e_3b_array', NULL);
                        $this->db->set('largura_d_3b_array', NULL);
                        $this->db->set('largura_e_3b_array', NULL);
                        $this->db->set('obliteracao_array', NULL);
                        $this->db->set('espessamento_pleural_difuso', NULL);
                        $this->db->set('local_parede_perfil_3d_array', NULL);
                        $this->db->set('local_parede_frontal_3d_array', NULL);
                        $this->db->set('calcificacao_parede_perfil_3d_array', NULL);
                        $this->db->set('calcificacao_parede_frontal_3d_array', NULL);
                        $this->db->set('extensao_parede_d_3d_array', NULL);
                        $this->db->set('extensao_parede_e_3d_array', NULL);
                        $this->db->set('largura_d_3d_array', NULL);
                        $this->db->set('largura_e_3d_array', NULL);
                    }

            }else{
                $this->db->set('anormalidade_parenquima', NULL);
                $this->db->set('forma_primaria_array', NULL);
                $this->db->set('forma_secundaria_array', NULL);
                $this->db->set('zonas_d_e_array', NULL);
                $this->db->set('profusao_array', NULL);
                $this->db->set('grandes_opacidades_array', NULL);
                $this->db->set('anormalidade_pleural', NULL);
                $this->db->set('placa_pleuras', NULL);
                $this->db->set('local_paredeperfil_3b_array', NULL);
                $this->db->set('local_frontal_3b_array', NULL);
                $this->db->set('local_diafragma_3b_array', NULL);
                $this->db->set('local_outroslocais_3b_array', NULL);
                $this->db->set('calcificacao_paredeperfil_3b_array', NULL);
                $this->db->set('calcificacao_frontal_3b_array', NULL);
                $this->db->set('calcificacao_diafragma_3b_array', NULL);
                $this->db->set('calcificacao_outroslocais_3b_array', NULL);
                $this->db->set('extensao_parede_d_3b_array', NULL);
                $this->db->set('extensao_parede_e_3b_array', NULL);
                $this->db->set('largura_d_3b_array', NULL);
                $this->db->set('largura_e_3b_array', NULL);
                $this->db->set('obliteracao_array', NULL);
                $this->db->set('espessamento_pleural_difuso', NULL);
                $this->db->set('local_parede_perfil_3d_array', NULL);
                $this->db->set('local_parede_frontal_3d_array', NULL);
                $this->db->set('calcificacao_parede_perfil_3d_array', NULL);
                $this->db->set('calcificacao_parede_frontal_3d_array', NULL);
                $this->db->set('extensao_parede_d_3d_array', NULL);
                $this->db->set('extensao_parede_e_3d_array', NULL);
                $this->db->set('largura_d_3d_array', NULL);
                $this->db->set('largura_e_3d_array',NULL);
                $this->db->set('anormalidade_parenquima', NULL);
                $this->db->set('comentario_4c', NULL);
            }

            $this->db->set('outras_anormalidades', @$_POST['outras_anormalidades']);
            if(@$_POST['outras_anormalidades'] == 't'){

                if (@count($_POST['simbolos']) > 0) {
                    $this->db->set('simbolos_array', json_encode(@$_POST['simbolos']));
                } else {
                    $this->db->set('simbolos_array', NULL);
                }

            }else{
                $this->db->set('simbolos_array', NULL); 
            }
            $this->db->set('comentario_4c', @$_POST['comentario_4c']);

            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('ambulatorio_laudooit_id', @$_POST['ambulatorio_laudooit_id']);
            $this->db->update('tb_ambulatorio_laudooit');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return 0;
//            $this->db->set('situacao', 'FINALIZADO');
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarlaudodigitando($ambulatorio_laudo_id, $exame_id, $procedimento_tuss_id) {
        try {
            /* inicia o mapeamento no banco */

            if (isset($_POST['indicado'])) {
                $this->db->set('indicado', 't');
            } else {
                $this->db->set('indicado', 'f');
            }
            $this->db->where('exames_id', $exame_id);
            $this->db->update('tb_exames');

            $this->db->select('agenda_exames_id');
            $this->db->from('tb_exames');
            $this->db->where("exames_id", $exame_id);
            $query = $this->db->get();
            $return = $query->result();

            $this->db->select('medico_parecer1');
            $this->db->from('tb_ambulatorio_laudo');
            $this->db->where('ambulatorio_laudo_id', $ambulatorio_laudo_id);
            $query = $this->db->get();
            $medico_r = $query->result();

            if(count($medico_r) > 0){
                if($medico_r[0]->medico_parecer1 != $_POST['medico'] && $medico_r[0]->medico_parecer1 > 0){
                    $this->backupLaudo($ambulatorio_laudo_id, 'Alterou profissional');
                }
            }
                    
            $this->backupLaudo($ambulatorio_laudo_id);

            $this->db->select('mc.valor as perc_medico, mc.percentual');
            $this->db->from('tb_procedimento_percentual_medico_convenio mc');
            $this->db->join('tb_procedimento_percentual_medico m', 'm.procedimento_percentual_medico_id = mc.procedimento_percentual_medico_id', 'left');
            $this->db->where('m.procedimento_tuss_id', $procedimento_tuss_id);
            $this->db->where('mc.medico', $_POST['medico']);
            $this->db->where('mc.ativo', 'true');
            $percentual = $this->db->get()->result();

            if (count($percentual) == 0) {
                $this->db->select('pt.perc_medico, pt.percentual');
                $this->db->from('tb_procedimento_convenio pc');
                $this->db->join('tb_procedimento_tuss pt', 'pc.procedimento_tuss_id = pt.procedimento_tuss_id', 'left');
                $this->db->where('pc.procedimento_convenio_id', $procedimento_tuss_id);
//                        $this->db->where('pc.ativo', 'true');
//                        $this->db->where('pt.ativo', 'true');
                $percentual = $this->db->get()->result();
            }

//            var_dump($percentual); die;
            $this->db->set('valor_medico', $percentual[0]->perc_medico);
            $this->db->set('percentual_medico', $percentual[0]->percentual);
            $this->db->set('medico_agenda', $_POST['medico']);
            $this->db->where('agenda_exames_id', $return[0]->agenda_exames_id);
            $this->db->update('tb_agenda_exames');

            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            if (isset($_POST['revisor'])) {
                $this->db->set('revisor', 't');
                $this->db->set('situacao_revisor', 'AGUARDANDO');
            } else {
                $this->db->set('revisor', 'f');
            }
            $this->db->set('texto_laudo', $_POST['laudo']);
            $this->db->set('texto', $_POST['laudo']);
            if ($_POST['medico'] != '') {
                $this->db->set('medico_parecer1', $_POST['medico']);
            }
            if ($_POST['medicorevisor'] != '') {
                $this->db->set('medico_parecer2', $_POST['medicorevisor']);
            } else {
                $this->db->set('medico_parecer2', null);
            }
            if (isset($_POST['assinatura'])) {
                $this->db->set('assinatura', 't');
            } else {
                $this->db->set('assinatura', 'f');
            }

            $this->db->set('cabecalho', $_POST['cabecalho']);

            if ($_POST['txtDiagnostico'] != "") {
                $this->db->set('diagnostico', $_POST['txtDiagnostico']);
            } else {
                $this->db->set('diagnostico', null);
            }

            if (isset($_POST['imagem'])) {
                $this->db->set('imagens', $_POST['imagem']);
            }
            if ($_POST['situacao'] != 'FINALIZADO') {
                $this->db->set('situacao', $_POST['situacao']);
            } else {
                $this->db->set('situacao', 'DIGITANDO');
            }
            if ($_POST['exame'] > 0) {
                $this->db->set('laudo_modelo_id', $_POST['exame']);
            }

                if(@$_POST['opcoes'] != ''){
                $this->db->set('opcoes_diagnostico', $_POST['opcoes']);
                $this->db->set('nivel1_diagnostico', $_POST['nivel1']);
                $this->db->set('nivel2_diagnostico', $_POST['nivel2']);
                $this->db->set('nivel3_diagnostico', $_POST['nivel3']);
                }

//            var_dump($_POST['situacao']); die;
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('ambulatorio_laudo_id', $ambulatorio_laudo_id);
            $this->db->update('tb_ambulatorio_laudo');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarlaudodigitandolaboratorial($ambulatorio_laudo_id, $exame_id) {
        try {
            /* inicia o mapeamento no banco */

            if (isset($_POST['indicado'])) {
                $this->db->set('indicado', 't');
            } else {
                $this->db->set('indicado', 'f');
            }
            $this->db->where('exames_id', $exame_id);
            $this->db->update('tb_exames');






            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            if (isset($_POST['revisor'])) {
                $this->db->set('revisor', 't');
                $this->db->set('situacao_revisor', 'AGUARDANDO');
            } else {
                $this->db->set('revisor', 'f');
            }
            $this->db->set('texto_laudo', $_POST['laudo']);
            $this->db->set('texto', $_POST['laudo']);
            if ($_POST['medico'] != '') {
                $this->db->set('medico_parecer1', $_POST['medico']);
            }
            if ($_POST['medicorevisor'] != '') {
                $this->db->set('medico_parecer2', $_POST['medicorevisor']);
            } else {
                $this->db->set('medico_parecer2', null);
            }
            if (isset($_POST['assinatura'])) {
                $this->db->set('assinatura', 't');
            } else {
                $this->db->set('assinatura', 'f');
            }

            $this->db->set('cabecalho', $_POST['cabecalho']);
            if (isset($_POST['imagem'])) {
                $this->db->set('imagens', $_POST['imagem']);
            }
            $this->db->set('situacao', 'DIGITANDO');
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('ambulatorio_laudo_id', $ambulatorio_laudo_id);
            $this->db->update('tb_ambulatorio_laudo');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarlaudodigitandoeco($ambulatorio_laudo_id) {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            if (isset($_POST['revisor'])) {
                $this->db->set('revisor', 't');
                $this->db->set('situacao_revisor', 'AGUARDANDO');
            } else {
                $this->db->set('revisor', 'f');
            }
            $this->db->set('texto_laudo', $_POST['laudo']);
            $this->db->set('texto', $_POST['laudo']);
            if ($_POST['Peso'] != '') {
                $this->db->set('peso', str_replace(",", ".", $_POST['peso']));
            }
            if ($_POST['Altura'] != '') {
                $this->db->set('altura', $_POST['Altura']);
            }
            if ($_POST['Superf'] != '0.00') {
                $this->db->set('superficie_corporea', $_POST['Superf']);
            }
            if ($_POST['Volume_telediastolico'] != '0.00') {
                $this->db->set('ve_volume_telediastolico', $_POST['Volume_telediastolico']);
            }
            if ($_POST['Volume_telessistolico'] != '0.00') {
                $this->db->set('ve_volume_telessistolico', $_POST['Volume_telessistolico']);
            }
            if ($_POST['Diametro_telediastolico'] != '') {
                $this->db->set('ve_diametro_telediastolico', $_POST['Diametro_telediastolico']);
            }
            if ($_POST['Diametro_telessistolico'] != '') {
                $this->db->set('ve_diametro_telessistolico', $_POST['Diametro_telessistolico']);
            }
            if ($_POST['indice_diametro_diastolico'] != 'NaN') {
                $this->db->set('ve_indice_do_diametro_diastolico', $_POST['indice_diametro_diastolico']);
            }
            if ($_POST['Septo_interventricular'] != '') {
                $this->db->set('ve_septo_interventricular', $_POST['Septo_interventricular']);
            }
            if ($_POST['Parede_posterior'] != '') {
                $this->db->set('ve_parede_posterior', $_POST['Parede_posterior']);
            }
            if ($_POST['Relacao_septo_parede'] != 'NaN') {
                $this->db->set('ve_relacao_septo_parede_posterior', $_POST['Relacao_septo_parede']);
            }
            if ($_POST['Espessura_relativa'] != 'NaN') {
                $this->db->set('ve_espessura_relativa_paredes', $_POST['Espessura_relativa']);
            }
            if ($_POST['Massa_ventricular'] != '0.60') {
                $this->db->set('ve_massa_ventricular', $_POST['Massa_ventricular']);
            }
            if ($_POST['indice_massa'] != 'Infinity') {
                $this->db->set('ve_indice_massa', $_POST['indice_massa']);
            }
            if ($_POST['Relacao_volume_massa'] != '0.00') {
                $this->db->set('ve_relacao_volume_massa', $_POST['Relacao_volume_massa']);
            }
            if ($_POST['Fracao_ejecao'] != 'NaN') {
                $this->db->set('ve_fracao_ejecao', $_POST['Fracao_ejecao']);
            }
            if ($_POST['Fracao_encurtamento'] != 'NaN') {
                $this->db->set('ve_fracao_encurtamento', $_POST['Fracao_encurtamento']);
            }
            if ($_POST['Diametro_telediastolico'] != '') {
                $this->db->set('vd_diametro_telediastolico', $_POST['Diametro_telediastolico']);
            }
            if ($_POST['area_telediastolica'] != '') {
                $this->db->set('vd_area_telediastolica', $_POST['area_telediastolica']);
            }
            if ($_POST['Diametro'] != '') {
                $this->db->set('ae_diametro', $_POST['Diametro']);
            }
            if ($_POST['indice_diametro'] != '') {
                $this->db->set('ae_indice_diametro', $_POST['indice_diametro']);
            }
            if ($_POST['Diametro_raiz'] != '') {
                $this->db->set('ao_diametro_raiz', $_POST['Diametro_raiz']);
            }
            if ($_POST['Relacao_atrio_esquerdo'] != 'NaN') {
                $this->db->set('ao_relacao_atrio_esquerdo_aorta', $_POST['Relacao_atrio_esquerdo']);
            }
            if ($_POST['medico'] != '') {
                $this->db->set('medico_parecer1', $_POST['medico']);
            }
            if ($_POST['medicorevisor'] != '') {
                $this->db->set('medico_parecer2', $_POST['medicorevisor']);
            } else {
                $this->db->set('medico_parecer2', null);
            }
            if (isset($_POST['assinatura'])) {
                $this->db->set('assinatura', 't');
            } else {
                $this->db->set('assinatura', 'f');
            }
            if (isset($_POST['rodape'])) {
                $this->db->set('rodape', 't');
            } else {
                $this->db->set('rodape', 'f');
            }
            $this->db->set('cabecalho', $_POST['cabecalho']);
            if (isset($_POST['imagem'])) {
                $this->db->set('imagens', $_POST['imagem']);
            }
            $this->db->set('situacao', 'DIGITANDO');
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('ambulatorio_laudo_id', $ambulatorio_laudo_id);
            $this->db->update('tb_ambulatorio_laudo');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function listardadoservicoemail($ambulatorio_laudo_id, $exame_id) {
        /* inicia o mapeamento no banco */
        $horario = date("Y-m-d H:i:s");
        $empresa_id = $this->session->userdata('empresa_id');

        $this->db->select('p.cns');
        $this->db->from('tb_exames e');
        $this->db->join('tb_paciente p', 'p.paciente_id = e.paciente_id');
        $this->db->where("exames_id", $exame_id);
        $this->db->where("p.ativo", 't');
        $query = $this->db->get();
        $return1 = $query->result();

        $this->db->select('email, email_mensagem_agradecimento as msg, razao_social');
        $this->db->from('tb_empresa e');
        $this->db->where("empresa_id", $empresa_id);
        $query = $this->db->get();
        $return2 = $query->result();

        $this->db->select('email_enviado');
        $this->db->from('tb_ambulatorio_laudo al');
        $this->db->where("ambulatorio_laudo_id", $ambulatorio_laudo_id);
        $query = $this->db->get();
        $return3 = $query->result();

        $retorno = array(
            "pacienteEmail" => $return1[0]->cns,
            "empresaEmail" => $return2[0]->email,
            "mensagem" => $return2[0]->msg,
            "enviado" => $return3[0]->email_enviado,
            "razaoSocial" => $return2[0]->razao_social
        );
        return $retorno;
    }

    function setaemailparaenviado($ambulatorio_laudo_id) {
        $this->db->set('email_enviado', 't');
        $this->db->where("ambulatorio_laudo_id", $ambulatorio_laudo_id);
        $this->db->update('tb_ambulatorio_laudo');
    }

    function gravaranamineseodontologia($ambulatorio_laudo_id, $exame_id, $procedimento_tuss_id) {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            $this->db->select('agenda_exames_id');
            $this->db->from('tb_exames');
            $this->db->where("exames_id", $exame_id);
            $query = $this->db->get();
            $return = $query->result();

            if ($empresa_id == null) {
                $empresa_id = $this->session->userdata('empresa_id');
            }

            $this->db->select('e.empresa_id,
                            ordem_chegada,
                            oftamologia,
                            ');
            $this->db->from('tb_empresa e');
            $this->db->where('e.empresa_id', $empresa_id);
            $this->db->join('tb_empresa_permissoes ep', 'ep.empresa_id = e.empresa_id', 'left');
            $this->db->orderby('e.empresa_id');
            $permissao = $this->db->get()->result();


            $this->db->select('mc.valor as perc_medico, mc.percentual');
            $this->db->from('tb_procedimento_percentual_medico_convenio mc');
            $this->db->join('tb_procedimento_percentual_medico m', 'm.procedimento_percentual_medico_id = mc.procedimento_percentual_medico_id', 'left');
            $this->db->where('m.procedimento_tuss_id', $procedimento_tuss_id);
            $this->db->where('mc.medico', $_POST['medico']);
            $this->db->where('mc.ativo', 'true');
            $percentual = $this->db->get()->result();

            if (count($percentual) == 0) {
                $this->db->select('pt.perc_medico, pt.percentual');
                $this->db->from('tb_procedimento_convenio pc');
                $this->db->join('tb_procedimento_tuss pt', 'pc.procedimento_tuss_id = pt.procedimento_tuss_id', 'left');
                $this->db->where('pc.procedimento_convenio_id', $procedimento_tuss_id);
//                        $this->db->where('pc.ativo', 'true');
//                        $this->db->where('pt.ativo', 'true');
                $percentual = $this->db->get()->result();
            }
            if (isset($_POST['rev'])) {
                switch ($_POST['tempoRevisao']) {
                    case '1a':
                        $dias = '+1 year';
                        break;
                    case '6m':
                        $dias = '+6 month';
                        break;
                    case '3m':
                        $dias = '+3 month';
                        break;
                    case '1m':
                        $dias = '+1 month';
                        break;
                    default:
                        $dias = '';
                }

                if ($dias != '') {
                    $diaRevisao = date('Y-m-d', strtotime($dias));
                    $this->db->set('data_revisao', $diaRevisao);
                }
            }

            $this->db->set('valor_medico', $percentual[0]->perc_medico);
            $this->db->set('percentual_medico', $percentual[0]->percentual);
            $this->db->set('medico_agenda', $_POST['medico']);
            $this->db->set('medico_consulta_id', $_POST['medico']);
            $this->db->where('agenda_exames_id', $return[0]->agenda_exames_id);
            $this->db->update('tb_agenda_exames');

            ////////////////////////////// OFTAMOLOGIA ///////////////////////////////////////////////
            if ($permissao[0]->oftamologia == 't') {

                $this->db->set('inspecao_geral', $_POST['inspecao_geral']);
                $this->db->set('motilidade_ocular', $_POST['motilidade_ocular']);
                $this->db->set('biomicroscopia', $_POST['biomicroscopia']);
                $this->db->set('mapeamento_retinas', $_POST['mapeamento_retinas']);
                $this->db->set('conduta', $_POST['conduta']);
                $this->db->set('acuidade_oe', $_POST['acuidade_oe']);
                $this->db->set('acuidade_od', $_POST['acuidade_od']);
                if ($_POST['pressao_ocular_oe'] != '') {
                    $this->db->set('pressao_ocular_oe', str_replace(",", ".", $_POST['pressao_ocular_oe']));
                }
                if ($_POST['pressao_ocular_od'] != '') {
                    $this->db->set('pressao_ocular_od', str_replace(",", ".", $_POST['pressao_ocular_od']));
                }
                if ($_POST['pressao_ocular_hora'] != '') {
                    $this->db->set('pressao_ocular_hora', date('H:i:s', strtotime($_POST['pressao_ocular_hora'])));
                } else {
                    $this->db->set('pressao_ocular_hora', null);
                }

                if ($_POST['refracao_retinoscopia'] != '') {
                    $this->db->set('refracao_retinoscopia', $_POST['refracao_retinoscopia']);
                } else {
                    $this->db->set('refracao_retinoscopia', '');
                }
                if ($_POST['dinamica_estatica'] != '') {
                    $this->db->set('dinamica_estatica', $_POST['dinamica_estatica']);
                } else {
                    $this->db->set('dinamica_estatica', '');
                }


                if (isset($_POST['carregar_refrator'])) {
                    $this->db->set('carregar_refrator', $_POST['carregar_refrator']);
                } else {
                    $this->db->set('carregar_refrator', 'f');
                }
                if (isset($_POST['carregar_oculos'])) {
                    $this->db->set('carregar_oculos', $_POST['carregar_oculos']);
                } else {
                    $this->db->set('carregar_oculos', 'f');
                }

//            var_dump($_POST['oftamologia_od_cilindrico']); die;
                $this->db->set('oftamologia_od_esferico', $_POST['oftamologia_od_esferico']);
                $this->db->set('oftamologia_oe_esferico', $_POST['oftamologia_oe_esferico']);
                $this->db->set('oftamologia_od_cilindrico', $_POST['oftamologia_od_cilindrico']);
                $this->db->set('oftamologia_oe_cilindrico', $_POST['oftamologia_oe_cilindrico']);
                $this->db->set('oftamologia_oe_eixo', $_POST['oftamologia_oe_eixo']);
                $this->db->set('oftamologia_oe_av', $_POST['oftamologia_oe_av']);
                $this->db->set('oftamologia_od_eixo', $_POST['oftamologia_od_eixo']);
                $this->db->set('oftamologia_od_av', $_POST['oftamologia_od_av']);
                $this->db->set('oftamologia_ad_esferico', $_POST['oftamologia_ad_esferico']);
                $this->db->set('oftamologia_ad_cilindrico', $_POST['oftamologia_ad_cilindrico']);
            }
            /////////////////////////// FIM DA OFTAMOLOGIA////////////////////////////////////////////

            $this->db->set('texto', $_POST['laudo']);
            if ($_POST['txtCICPrimario'] != '') {
                $this->db->set('cid', $_POST['txtCICPrimario']);
            }
            if ($_POST['txtCICSecundario'] != '') {
                $this->db->set('cid2', $_POST['txtCICSecundario']);
            }
            if ($_POST['medico'] != '') {
                $this->db->set('medico_parecer1', $_POST['medico']);
            }
            if ($_POST['diabetes'] != '') {
                $this->db->set('diabetes', $_POST['diabetes']);
            }
            if ($_POST['hipertensao'] != '') {
                $this->db->set('hipertensao', $_POST['hipertensao']);
            }

            if (isset($_POST['ret'])) {
                $this->db->set('dias_retorno', $_POST['ret_dias']);
            }

            if (isset($_POST['assinatura'])) {
                $this->db->set('assinatura', 't');
            } else {
                $this->db->set('assinatura', 'f');
            }
            if (isset($_POST['rodape'])) {
                $this->db->set('rodape', 't');
            } else {
                $this->db->set('rodape', 'f');
            }
            if ($_POST['status'] != 'FINALIZADO') {
                $this->db->set('data_finalizado', $horario);
                $this->db->set('operador_finalizado', $operador_id);
            }
            $this->db->set('cabecalho', $_POST['cabecalho']);
            $this->db->set('situacao', 'FINALIZADO');
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->set('data_revisor', $horario);
            $this->db->set('operador_revisor', $operador_id);
            $this->db->where('ambulatorio_laudo_id', $ambulatorio_laudo_id);
            $this->db->update('tb_ambulatorio_laudo');


            if ($_POST['Peso'] != '') {
                $this->db->set('peso', str_replace(",", ".", $_POST['Peso']));
            } else {
                $this->db->set('peso', null);
            }
            if ($_POST['Altura'] != '') {
                $this->db->set('altura', $_POST['Altura']);
            } else {
                $this->db->set('altura', null);
            }
            $this->db->where('ambulatorio_guia_id', $_POST['guia_id']);
            $this->db->update('tb_ambulatorio_guia');
        } catch (Exception $exc) {
            return -1;
        }
    }

    function auditoriaLaudo($ambulatorio_laudo_id, $alteracao = 'Salvou'){
        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
                    
        $this->db->select('*');
        $this->db->from('tb_ambulatorio_laudo');
        $this->db->where('ambulatorio_laudo_id', $ambulatorio_laudo_id);
        $laudo_backup = $this->db->get()->result();

        $laudo_json = json_encode($laudo_backup);
        $this->db->set('alteracao', $alteracao);
        $this->db->set('laudo_json', $laudo_json);
        $this->db->set('ambulatorio_laudo_id', $ambulatorio_laudo_id);
        $this->db->set('data_cadastro', $horario);
        $this->db->set('operador_cadastro', $operador_id);
        $this->db->insert('tb_ambulatorio_laudo_backup'); 
        if($alteracao == "Saiu do Laudo"){  
            if(preg_replace("/\s+/", "",strip_tags($laudo_backup[0]->texto)) == ""){  
                $this->db->set('ambulatorio_laudo_id', $ambulatorio_laudo_id);
                $this->db->set('laudo_json', $alteracao);
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_laudo_sem_digitar');
            }
        
        } 
        
    }

    function listarlaudoauditoria($ambulatorio_laudo_id){

        $this->db->select('alteracao, alb.data_cadastro, o.nome as operador');
        $this->db->from('tb_ambulatorio_laudo_backup alb');
        $this->db->join('tb_operador o', 'o.operador_id = alb.operador_cadastro', 'left');
        $this->db->where('alb.ambulatorio_laudo_id', $ambulatorio_laudo_id);
        $this->db->orderby('alb.ambulatorio_laudo_backup_id');
        $laudo_backup = $this->db->get()->result();

        return $laudo_backup;
    }

    function backupLaudo($ambulatorio_laudo_id, $alteracao = 'Salvou Laudo'){
        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');

        $this->db->select('*');
        $this->db->from('tb_ambulatorio_laudo');
        $this->db->where('ambulatorio_laudo_id', $ambulatorio_laudo_id);
        $laudo_backup = $this->db->get()->result();

        $laudo_json = json_encode($laudo_backup);
        $this->db->set('alteracao', $alteracao);
        $this->db->set('laudo_json', $laudo_json);
        $this->db->set('ambulatorio_laudo_id', $ambulatorio_laudo_id);
        $this->db->set('data_cadastro', $horario);
        $this->db->set('operador_cadastro', $operador_id);
        $this->db->insert('tb_ambulatorio_laudo_backup');
        
    }

    function gravaranaminese_adendo($ambulatorio_laudo_id, $exame_id, $procedimento_tuss_id, $evolucao) {
        try {
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            $this->db->select('texto');
            $this->db->from('tb_ambulatorio_laudo');
            $this->db->where('ambulatorio_laudo_id', $ambulatorio_laudo_id);
            $return = $this->db->get()->result();

            $laudo = $return[0]->texto;

            // $adendo_coluna = "<p><strong>Atendimento Extra de: </strong>" . date("d/m/Y H:i:s") . "</p>" . $_POST['laudo'];
            $adendo = $laudo . "<p><strong>Atendimento Extra em: </strong>" . date("d/m/Y H:i:s") . "</p>" . $_POST['laudo'];
            
            if(isset($_POST['btnFinalizar'])){
            $this->db->set("texto", $adendo);
            }
            $this->db->set('adendo', $_POST['laudo']);
            $this->db->set('operador_adendo', $operador_id);
            $this->db->set('data_adendo', $horario);
            $this->db->where('ambulatorio_laudo_id', $ambulatorio_laudo_id);
            $this->db->update('tb_ambulatorio_laudo');
            

        }catch (Exception $exc) {
            return -1;
        }
    }

    function gravaranaminese($ambulatorio_laudo_id, $exame_id, $procedimento_tuss_id, $evolucao) {
        try {

            /* inicia o mapeamento no banco */

            // var_dump($_POST); die;
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            $this->db->select('agenda_exames_id, exames_id');
            $this->db->from('tb_exames');
            $this->db->where("exames_id", $exame_id);
            $query = $this->db->get();
            $return = $query->result();


            $this->db->select('valor_total, tipo_desconto');
            $this->db->from('tb_agenda_exames ae');
            $this->db->where("ae.agenda_exames_id", $return[0]->agenda_exames_id);
            $tipo_desconto = $this->db->get()->result();

            $this->db->set('todos_visualizar', null);
            $this->db->where('agenda_exames_id', $return[0]->agenda_exames_id);
            $this->db->update('tb_agenda_exames');

            // var_dump($tipo_desconto); die;

            if (@$empresa_id == null) {
                $empresa_id = $this->session->userdata('empresa_id');
            }

            $this->db->select('e.empresa_id,
                            ordem_chegada,
                            oftamologia,convenio_padrao
                            ');
            $this->db->from('tb_empresa e');
            $this->db->where('e.empresa_id', $empresa_id);
            $this->db->join('tb_empresa_permissoes ep', 'ep.empresa_id = e.empresa_id', 'left');
            $this->db->orderby('e.empresa_id');
            $permissao = $this->db->get()->result();


            $this->db->select('mc.valor as perc_medico, mc.percentual');
            $this->db->from('tb_procedimento_percentual_medico_convenio mc');
            $this->db->join('tb_procedimento_percentual_medico m', 'm.procedimento_percentual_medico_id = mc.procedimento_percentual_medico_id', 'left');
            $this->db->where('m.procedimento_tuss_id', $procedimento_tuss_id);
            $this->db->where('mc.medico', $_POST['medico']);
            $this->db->where('mc.ativo', 'true');
            $percentual = $this->db->get()->result();

            if (count($percentual) == 0) {
                $this->db->select('pt.perc_medico, pt.percentual');
                $this->db->from('tb_procedimento_convenio pc');
                $this->db->join('tb_procedimento_tuss pt', 'pc.procedimento_tuss_id = pt.procedimento_tuss_id', 'left');
                $this->db->where('pc.procedimento_convenio_id', $procedimento_tuss_id);
//                        $this->db->where('pc.ativo', 'true');
//                        $this->db->where('pt.ativo', 'true');
                $percentual = $this->db->get()->result();
            }
            if (isset($_POST['rev'])) {
                if(isset($_POST['tempoRevisao'])){

                    if(isset($_POST['tipodata'])){
                        switch($_POST['tipodata']){
                            case 'dias':
                                $dias = $_POST['tempoRevisao']. ' day';
                            break;
                            case 'meses':
                                $dias = $_POST['tempoRevisao']. ' month';
                            break;
                            case 'anos':
                                $dias = $_POST['tempoRevisao']. ' year';
                            break;
                            default:
                                $dias = '';
                        }
                    }else{
                        $dias = $_POST['tempoRevisao']. ' month';
                    }

                }
                // switch ($_POST['tempoRevisao']) {
                //     case '1a':
                //         $dias = '+1 year';
                //         break;
                //     case '6m':
                //         $dias = '+6 month';
                //         break;
                //     case '3m':
                //         $dias = '+3 month';
                //         break;
                //     case '1m':
                //         $dias = '+1 month';
                //         break;
                //     default:
                //         $dias = '';
                // }

                if ($dias != '') {
                    $diaRevisao = date('Y-m-d', strtotime($dias));
                    $this->db->set('data_revisao', $diaRevisao);
                }
            }
            if ($tipo_desconto[0]->tipo_desconto == '') {
                $this->db->set('valor_medico', $percentual[0]->perc_medico);
                $this->db->set('percentual_medico', $percentual[0]->percentual);
            }



            if ($permissao[0]->convenio_padrao == 't') {
                if ($_POST['medico'] != "") {
                    if ($_POST['medico'] == 1 && $this->session->userdata('operador_id') == 1) {
                        $this->db->set('medico_agenda', $_POST['medico']);
                        $this->db->set('medico_consulta_id', $_POST['medico']);
                    } elseif ($_POST['medico'] != 1 && $this->session->userdata('operador_id') == $_POST['medico']) {
                        $this->db->set('medico_agenda', $_POST['medico']);
                        $this->db->set('medico_consulta_id', $_POST['medico']);
                    } elseif ($_POST['medico'] != $this->session->userdata('operador_id')) {
                        $this->db->set('medico_agenda', $this->session->userdata('operador_id'));
                        $this->db->set('medico_consulta_id', $this->session->userdata('operador_id'));
                    } else {
                        $this->db->set('medico_agenda', $_POST['medico']);
                        $this->db->set('medico_consulta_id', $_POST['medico']);
                    }
                }
            } else {
                if ($_POST['medico'] != "") {
                    $this->db->set('medico_agenda', $_POST['medico']);
                    $this->db->set('medico_consulta_id', $_POST['medico']);
                }
            }




            $this->db->where('agenda_exames_id', $return[0]->agenda_exames_id);
            $this->db->update('tb_agenda_exames');



            if(isset($_POST['btnSalvar']) || isset($_POST['btnFechar'])){
                $this->db->set('situacao', 'AGUARDANDO');
            }else{
                $this->db->set('situacao', 'FINALIZADO');  
            }

            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('exames_id', $return[0]->exames_id);
            $this->db->update('tb_exames');

            $this->db->select('paciente_id');
            $this->db->from('tb_agenda_exames');
            $this->db->where('agenda_exames_id', $return[0]->agenda_exames_id);
            $query = $this->db->get();
            $paciente = $query->result();

            $this->db->set('informacao_laudo', $_POST['informacao_laudo']);
            $this->db->where('paciente_id', $paciente[0]->paciente_id);
            $this->db->update('tb_paciente');

            $this->db->select('medico_parecer1, situacao');
            $this->db->from('tb_ambulatorio_laudo');
            $this->db->where('ambulatorio_laudo_id', $ambulatorio_laudo_id);
            $query = $this->db->get();
            $medico_r = $query->result();

            if(count($medico_r) > 0){
                if($medico_r[0]->medico_parecer1 != $_POST['medico'] && $medico_r[0]->medico_parecer1 > 0){
                    $this->backupLaudo($ambulatorio_laudo_id, 'Alterou profissional');
                }
                if($medico_r[0]->situacao != 'FINALIZADO'){
                    $this->backupLaudo($ambulatorio_laudo_id, 'Finalizou o Atendimento');
                }
            }

            $this->backupLaudo($ambulatorio_laudo_id);
            ////////////////////////////// OFTAMOLOGIA ///////////////////////////////////////////////
            if ($permissao[0]->oftamologia == 't') {

                $this->db->set('inspecao_geral', @$_POST['inspecao_geral']);
                $this->db->set('motilidade_ocular', @$_POST['motilidade_ocular']);
                $this->db->set('biomicroscopia', @$_POST['biomicroscopia']);
                $this->db->set('mapeamento_retinas', @$_POST['mapeamento_retinas']);
                $this->db->set('conduta', @$_POST['conduta']);
                $this->db->set('acuidade_oe', @$_POST['acuidade_oe']);
                $this->db->set('acuidade_od', @$_POST['acuidade_od']);
                if (@$_POST['pressao_ocular_oe'] != '') {
                    $this->db->set('pressao_ocular_oe', str_replace(",", ".", @$_POST['pressao_ocular_oe']));
                }
                if (@$_POST['pressao_ocular_od'] != '') {
                    $this->db->set('pressao_ocular_od', str_replace(",", ".", @$_POST['pressao_ocular_od']));
                }
                if (@$_POST['pressao_ocular_hora'] != '') {
                    $this->db->set('pressao_ocular_hora', date('H:i:s', strtotime(@$_POST['pressao_ocular_hora'])));
                } else {
                    $this->db->set('pressao_ocular_hora', null);
                }

                if (@$_POST['refracao_retinoscopia'] != '') {
                    $this->db->set('refracao_retinoscopia', @$_POST['refracao_retinoscopia']);
                } else {
                    $this->db->set('refracao_retinoscopia', '');
                }
                if (@$_POST['dinamica_estatica'] != '') {
                    $this->db->set('dinamica_estatica', @$_POST['dinamica_estatica']);
                } else {
                    $this->db->set('dinamica_estatica', '');
                }


                if (isset($_POST['carregar_refrator'])) {
                    $this->db->set('carregar_refrator', @$_POST['carregar_refrator']);
                } else {
                    $this->db->set('carregar_refrator', 'f');
                }
                if (isset($_POST['carregar_oculos'])) {
                    $this->db->set('carregar_oculos', @$_POST['carregar_oculos']);
                } else {
                    $this->db->set('carregar_oculos', 'f');
                }


//            var_dump($_POST['oftamologia_od_cilindrico']); die;
                $this->db->set('oftamologia_od_esferico', @$_POST['oftamologia_od_esferico']);
                $this->db->set('oftamologia_oe_esferico', @$_POST['oftamologia_oe_esferico']);
                $this->db->set('oftamologia_od_cilindrico', @$_POST['oftamologia_od_cilindrico']);
                $this->db->set('oftamologia_oe_cilindrico', @$_POST['oftamologia_oe_cilindrico']);
                $this->db->set('oftamologia_oe_eixo', @$_POST['oftamologia_oe_eixo']);
                $this->db->set('oftamologia_oe_av', @$_POST['oftamologia_oe_av']);
                $this->db->set('oftamologia_od_eixo', @$_POST['oftamologia_od_eixo']);
                $this->db->set('oftamologia_od_av', @$_POST['oftamologia_od_av']);
                $this->db->set('oftamologia_ad_esferico', @$_POST['oftamologia_ad_esferico']);
                $this->db->set('oftamologia_ad_cilindrico', @$_POST['oftamologia_ad_cilindrico']);
            }
            /////////////////////////// FIM DA OFTAMOLOGIA////////////////////////////////////////////

            $this->db->set('texto', $_POST['laudo']);

            if($evolucao == NULL){

            }else{
                $this->db->set('enfermagem', $evolucao);
            }

            $this->db->set('dados', $_POST['dados']);

            if(isset($_POST['objTemplate'])){
                $this->db->set('template_obj', $_POST['objTemplate']);
                if($_POST['template'] > 0){
                    $this->db->set('template_id', $_POST['template']);
                }else{
                    $this->db->set('template_id', null);
                }
            }
            
             
                  
            if(isset($_POST['queixa_princialEvolucao'])){  
                    $objEvolucao = new stdClass; 
                    $objEvolucao->queixa_princialEvolucao = $_POST['queixa_princialEvolucao'];
                    $objEvolucao->historia_doenca_atualEvolucao = $_POST['historia_doenca_atualEvolucao'];
                    $objEvolucao->comorbidadesEvolucao = $_POST['comorbidadesEvolucao'];
                    $objEvolucao->diagnosticoEvolucao = $_POST['diagnosticoEvolucao'];
                    $objEvolucao->condutaEvolucao = $_POST['condutaEvolucao'];
                    $objEvolucao->examefisicoEvolucao = $_POST['examefisicoEvolucao'];
                    $objEvolucao->examesatentioresEvolucao = $_POST['examesatentioresEvolucao'];
                    $json_evolucao = json_encode($objEvolucao); 
                    $this->db->set('obj_evolucao',$json_evolucao);
              } 
                            
                            

            if (@$_POST['adendo'] != '') {
                $adendo_coluna = "<p>Adendo de: " . date("d/m/Y H:i:s") . "<br></p>" . $_POST['adendo'];
                $adendo = $_POST['laudo'] . "<p><strong>Adendo de: " . date("d/m/Y H:i:s") . "<br></strong></p>" . $_POST['adendo'];
//                $this->db->set('adendo', $adendo_coluna);
                $this->db->set("texto", $adendo);
            }
            //    var_dump($_POST['medico']);
            //    die;
            if ($_POST['txtCICPrimario'] != '') {
                $this->db->set('cid', $_POST['txtCICPrimario']);
            }
            if ($_POST['txtCICSecundario'] != '') {
                $this->db->set('cid2', $_POST['txtCICSecundario']);
            }

            if ($_POST['txtDiagnostico'] != '') {
                $this->db->set('diagnostico', $_POST['txtDiagnostico']);
            } else {
                $this->db->set('diagnostico', null);
            }


            if ($permissao[0]->convenio_padrao == 't') {


                if ($_POST['medico'] != '') {
//                $this->db->set('medico_parecer1', $_POST['medico']);     
                    if ($_POST['medico'] == 1 && $this->session->userdata('operador_id') == 1) {
                        $this->db->set('medico_parecer1', $_POST['medico']);
                    } elseif ($_POST['medico'] != 1 && $this->session->userdata('operador_id') == $_POST['medico']) {
                        $this->db->set('medico_parecer1', $_POST['medico']);
                    } elseif ($_POST['medico'] != $this->session->userdata('operador_id')) {
                        $this->db->set('medico_parecer1', $this->session->userdata('operador_id'));
                    } else {
                        $this->db->set('medico_parecer1', $_POST['medico']);
                    }
                }
            } else {
                if ($_POST['medico'] != '') {
                    $this->db->set('medico_parecer1', $_POST['medico']);
                }
            }


            if ($_POST['diabetes'] != '') {
                $this->db->set('diabetes', $_POST['diabetes']);
            }
            if ($_POST['hipertensao'] != '') {
                $this->db->set('hipertensao', $_POST['hipertensao']);
            }

            if (isset($_POST['ret'])) {
                $this->db->set('dias_retorno', $_POST['ret_dias']);
            }

            if (isset($_POST['assinatura'])) {
                $this->db->set('assinatura', 't');
            } else {
                $this->db->set('assinatura', 'f');
            }
            if (isset($_POST['rodape'])) {
                $this->db->set('rodape', 't');
            } else {
                $this->db->set('rodape', 'f');
            }
            if ($_POST['status'] != 'FINALIZADO') {
                // echo 'teste'; die;
                $this->db->set('data_finalizado', $horario);
                $this->db->set('operador_finalizado', $operador_id);
            }
            $this->db->set('cabecalho', $_POST['cabecalho']);

            if((isset($_POST['btnEnviar']) || isset($_POST['btnSalvar']) || isset($_POST['btnFechar']) || isset($_POST['btnAtivarprecriscao'])) && $_POST['situacao'] != "FINALIZADO"){
                $this->db->set('situacao', $_POST['situacao']);
            }else{
                $this->db->set('situacao', 'FINALIZADO');
                $this->db->set('data_finalizado', $horario);
                $this->db->set('operador_finalizado', $operador_id);
            }

            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->set('data_revisor', $horario);
            $this->db->set('operador_revisor', $operador_id);
            $this->db->where('ambulatorio_laudo_id', $ambulatorio_laudo_id);
            $this->db->update('tb_ambulatorio_laudo');
                            

            if ($_POST['Peso'] != '') {
                $this->db->set('peso', str_replace(",", ".", $_POST['Peso']));
            } else {
                $this->db->set('peso', null);
            }
            if ($_POST['Altura'] != '') {
                $this->db->set('altura', $_POST['Altura']);
            } else {
                $this->db->set('altura', null);
            }
            $this->db->where('ambulatorio_guia_id', $_POST['guia_id']);
            $this->db->update('tb_ambulatorio_guia');
            // die;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function listarreceitaoculosimpressao($ambulatorio_laudo_id) {

        $this->db->select('ag.ambulatorio_laudo_id,
                          ag.paciente_id,
                          ag.data_cadastro,
                          ag.exame_id,
                          ag.peso,
                          ag.altura,
                          ag.data_cadastro,
                          ag.data,
                          ag.situacao,
                          ae.agenda_exames_nome_id,
                          ag.inspecao_geral,
                          ag.motilidade_ocular,
                          ag.biomicroscopia,
                          ag.mapeamento_retinas,
                          ag.conduta,
                          ag.acuidade_od,
                          ag.acuidade_oe,
                          ag.pressao_ocular_oe,
                          ag.pressao_ocular_od,
                          ag.pressao_ocular_hora,
                          ag.refracao_retinoscopia,
                          ag.dinamica_estatica,
                          ag.carregar_refrator,
                          ag.carregar_oculos,
                          ag.oftamologia_od_esferico,
                          ag.oftamologia_oe_esferico,
                          ag.oftamologia_od_cilindrico,
                          ag.oftamologia_oe_cilindrico,
                          ag.oftamologia_oe_eixo,
                          ag.oftamologia_oe_av,
                          ag.oftamologia_od_eixo,
                          ag.oftamologia_od_av,
                          ag.oftamologia_ad_esferico,
                          ag.oftamologia_ad_cilindrico,
                          p.nascimento,
                          ag.situacao_revisor,
                          o.nome as medico,
                          o.conselho,
                          ag.assinatura,
                          ag.rodape,
                          ag.guia_id,
                          ag.cabecalho,
                          ag.medico_parecer1,
                          ag.medico_parecer2,
                          me.nome as solicitante,
                          op.nome as medicorevisor,
                          pt.nome as procedimento,
                          pt.grupo,
                          ae.agenda_exames_id,
                          ag.imagens,
                          c.nome as convenio,
                          pc.convenio_id,
                          p.nome as paciente,
                          p.cpf,
                          p.nascimento,
                          p.sexo,
                          o.carimbo as medico_carimbo');
        $this->db->from('tb_ambulatorio_laudo ag');
        //        $this->db->join('tb_ambulatorio_laudo ag', 'ag.ambulatorio_laudo_id = ar.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = ag.paciente_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ag.medico_parecer1', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ag.medico_parecer2', 'left');
        $this->db->join('tb_exames e', 'e.exames_id = ag.exame_id ', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = e.agenda_exames_id', 'left');
        $this->db->join('tb_operador me', 'me.operador_id = ae.medico_solicitante', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'pc.convenio_id = c.convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where("ag.ambulatorio_laudo_id", $ambulatorio_laudo_id);
        $return = $this->db->get();
        return $return->result();
    }

    function gravarreceituario() {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('texto', $_POST['laudo']);
            if ($_POST['carimbo'] == "on") {
                $this->db->set('carimbo', 't');
            }
            if ($_POST['assinatura'] == "on") {
                $this->db->set('assinatura', 't');
            }
//            $this->db->set('paciente_id', $_POST['paciente_id']);
//            $this->db->set('procedimento_tuss_id', $_POST['procedimento_tuss_id']);
            $this->db->set('laudo_id', $_POST['ambulatorio_laudo_id']);
            $this->db->set('medico_parecer1', $_POST['medico']);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $_POST['medico']);
            $this->db->set('tipo', 'NORMAL');

            $this->db->insert('tb_ambulatorio_receituario');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarreceituarioatendimento($ambulatorio_laudo_id, $receituario = null, $especial = NULL, $id = NULL, $adendo = false) {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            if($receituario == null){
                $this->db->set('texto', $_POST['receituario']);
            }else{
                $this->db->set('texto', $receituario);
            }
            if (isset($_POST['carimbo_receituario'])) {
                $this->db->set('carimbo', 't');
            }
            if (isset($_POST['assinatura_receituario'])) {
                $this->db->set('assinatura', 't');
            }
            if($especial != NULL){
                $this->db->set('especial', 't');
            }

            if (isset($_POST['receituario_especial'])) {
                $this->db->set('especial', 't');
            }
//            $this->db->set('paciente_id', $_POST['paciente_id']);
//            $this->db->set('procedimento_tuss_id', $_POST['procedimento_tuss_id']);
            $this->db->set('laudo_id', $ambulatorio_laudo_id);
            $this->db->set('medico_parecer1', $_POST['medico']);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $_POST['medico']);
            $this->db->set('tipo', 'NORMAL');
            $this->db->set('receita_id', $id);
            if($adendo){
                $this->db->set('adendo', 't');
            }

            $this->db->insert('tb_ambulatorio_receituario');

            $insert_id = $this->db->insert_id();
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return $insert_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarreceituarioatendimentoantigo($ambulatorio_laudo_id, $receita_nova, $adendo) {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            
            $this->db->set('texto', $receita_nova[0]->texto);
            $this->db->set('carimbo', $receita_nova[0]->carimbo);
            $this->db->set('assinatura', $receita_nova[0]->assinatura);
            $this->db->set('especial', $receita_nova[0]->especial);
            $this->db->set('laudo_id', $ambulatorio_laudo_id);
            $this->db->set('medico_parecer1', $_POST['medico']);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $_POST['medico']);
            $this->db->set('tipo', $receita_nova[0]->tipo);
            $this->db->set('receita_id', $receita_nova[0]->receita_id);
            if($adendo){
                $this->db->set('adendo', 't');
            }
            $this->db->insert('tb_ambulatorio_receituario');

            $insert_id = $this->db->insert_id();
            
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return $insert_id;
        } catch (Exception $exc) {
            return -1;
        }
    }


    function gravarexamesatendimentoantigo($ambulatorio_laudo_id, $receita_nova, $adendo) {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            
            $this->db->set('texto', $receita_nova[0]->texto);
            $this->db->set('carimbo', $receita_nova[0]->carimbo);
            $this->db->set('assinatura', $receita_nova[0]->assinatura);
            $this->db->set('laudo_id', $ambulatorio_laudo_id);
            $this->db->set('medico_parecer1', $_POST['medico']);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $_POST['medico']);
            $this->db->set('tipo', $receita_nova[0]->tipo);
            if($adendo){
                $this->db->set('adendo', 't');
            }
            $this->db->set('exame_id', $receita_nova[0]->exame_id);
            $this->db->insert('tb_ambulatorio_exame');

            $insert_id = $this->db->insert_id();
            
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return $insert_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function pegarpacienteimagem($ambulatorio_laudo_id){
        $this->db->set('paciente_id');
        $this->db->from('tb_ambulatorio_laudo');
        $this->db->where('ambulatorio_laudo_id', $ambulatorio_laudo_id);

        $result = $this->db->get()->result();
        return $result[0]->paciente_id;
    }

    function gravarterapeuticasatendimentoantigo($ambulatorio_laudo_id, $receita_nova, $adendo) {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            
            $this->db->set('texto', $receita_nova[0]->texto);
            $this->db->set('carimbo', $receita_nova[0]->carimbo);
            $this->db->set('assinatura', $receita_nova[0]->assinatura);
            $this->db->set('laudo_id', $ambulatorio_laudo_id);
            $this->db->set('medico_parecer1', $_POST['medico']);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $_POST['medico']);
            if($adendo){
                $this->db->set('adendo', 't');
            }
            $this->db->set('terapeuticas_id', $receita_nova[0]->terapeuticas_id);
            $this->db->insert('tb_ambulatorio_terapeuticas');

            $insert_id = $this->db->insert_id();
            
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return $insert_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarrelatorioatendimentoantigo($ambulatorio_laudo_id, $receita_nova, $adendo) {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            
            $this->db->set('texto', $receita_nova[0]->texto);
            $this->db->set('carimbo', $receita_nova[0]->carimbo);
            $this->db->set('assinatura', $receita_nova[0]->assinatura);
            $this->db->set('laudo_id', $ambulatorio_laudo_id);
            $this->db->set('medico_parecer1', $_POST['medico']);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $_POST['medico']);
            $this->db->set('relatorio_id', $receita_nova[0]->relatorio_id);
            if($adendo){
                $this->db->set('adendo', 't');
            }
            $this->db->insert('tb_ambulatorio_relatorio');

            $insert_id = $this->db->insert_id();
            
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return $insert_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravaranotacaoprivada() {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('texto', $_POST['anotacao_privada']);
//            $this->db->set('paciente_id', $_POST['paciente_id']);
//            $this->db->set('procedimento_tuss_id', $_POST['procedimento_tuss_id']);
            $this->db->set('ambulatorio_laudo_id', $_POST['ambulatorio_laudo_id']);
            $this->db->set('medico_parecer1', $_POST['medico']);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_ambulatorio_privado');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarrotinas() {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('texto', $_POST['laudo']);
            if ($_POST['carimbo'] == "on") {
                $this->db->set('carimbo', 't');
            }
            if ($_POST['assinatura'] == "on") {
                $this->db->set('assinatura', 't');
            }
//            $this->db->set('paciente_id', $_POST['paciente_id']);
//            $this->db->set('procedimento_tuss_id', $_POST['procedimento_tuss_id']);
            $this->db->set('laudo_id', $_POST['ambulatorio_laudo_id']);
            $this->db->set('medico_parecer1', $_POST['medico']);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $_POST['medico']);
            $this->db->set('tipo', 'NORMAL');

            $this->db->insert('tb_ambulatorio_rotinas');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarreceituariosollis($ambulatorio_laudo_id, $prescricao_id) {
        try {
            /* inicia o mapeamento no banco */

            $this->db->select('al.paciente_id, al.medico_parecer1 ');
            $this->db->from('tb_ambulatorio_laudo al');
            $this->db->where('ambulatorio_laudo_id', $ambulatorio_laudo_id);
            $return = $this->db->get()->result();

//            var_dump($prescricao_id);die;

            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('laudo_id', $ambulatorio_laudo_id);
            $this->db->set('medico_parecer1', $return[0]->medico_parecer1);
            $this->db->set('paciente_id', $return[0]->paciente_id);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('cid_id', $_POST['cid']);
            $this->db->set('frequencia', $_POST['freq']);
            $this->db->set('frequnit', $_POST['frequnit']);
            $this->db->set('qtdmed', $_POST['qtdmed']);
            $this->db->set('medid', $_POST['medid']);
            $this->db->set('periodo', $_POST['periodo']);
            $this->db->set('perunit', $_POST['perunit']);
            $this->db->set('observacao', $_POST['observacao']);
            $this->db->set('prescricao_id', $prescricao_id);
            $this->db->set('operador_cadastro', $return[0]->medico_parecer1);
            $this->db->set('tipo', 'NORMAL');

            $this->db->insert('tb_receituario_sollis');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function preencherparecer($paciente_id, $guia_id) {

        $this->db->select('*');
        $this->db->from('tb_laudo_parecer lp');
        $this->db->where('lp.guia_id', $guia_id);
        $this->db->where('lp.paciente_id', $paciente_id);
        $return = $this->db->get();
        $result = $return->result();
        return $result;
    }

    function preencherlaudous($paciente_id, $guia_id) {

        $this->db->select('*');
        $this->db->from('tb_laudo_apendicite lap');
        $this->db->where('lap.guia_id', $guia_id);
        $this->db->where('lap.paciente_id', $paciente_id);
        $return = $this->db->get();
        $result = $return->result();
        return $result;
    }

    function preencherecocardio($paciente_id, $guia_id) {

        $this->db->select('*');
        $this->db->from('tb_laudo_ecocardio lec');
        $this->db->where('lec.guia_id', $guia_id);
        $this->db->where('lec.paciente_id', $paciente_id);
        $return = $this->db->get();
        $result = $return->result();
        return $result;
    }

    function preencherecostress($paciente_id, $guia_id) {

        $this->db->select('*');
        $this->db->from('tb_laudo_ecostress les');
        $this->db->where('les.guia_id', $guia_id);
        $this->db->where('les.paciente_id', $paciente_id);
        $return = $this->db->get();
        $result = $return->result();
        return $result;
    }

    function preenchercate($paciente_id, $guia_id) {

        $this->db->select('*');
        $this->db->from('tb_laudo_cate lca');
        $this->db->where('lca.guia_id', $guia_id);
        $this->db->where('lca.paciente_id', $paciente_id);
        $return = $this->db->get();
        $result = $return->result();
        return $result;
    }

    function preencherholter($paciente_id, $guia_id) {

        $this->db->select('*');
        $this->db->from('tb_laudo_holter lh');
        $this->db->where('lh.guia_id', $guia_id);
        $this->db->where('lh.paciente_id', $paciente_id);
        $return = $this->db->get();
        $result = $return->result();
        return $result;
    }

    function preenchercintilografia($paciente_id, $guia_id) {

        $this->db->select('*');
        $this->db->from('tb_laudo_cintil lc');
        $this->db->where('lc.guia_id', $guia_id);
        $this->db->where('lc.paciente_id', $paciente_id);
        $return = $this->db->get();
        $result = $return->result();
        return $result;
    }

    function preenchermapa($paciente_id, $guia_id) {

        $this->db->select('*');
        $this->db->from('tb_laudo_mapa lm');
        $this->db->where('lm.guia_id', $guia_id);
        $this->db->where('lm.paciente_id', $paciente_id);
        $return = $this->db->get();
        $result = $return->result();
        return $result;
    }

    function preenchertergometrico($paciente_id, $guia_id) {

        $this->db->select('*');
        $this->db->from('tb_laudo_tergometrico lt');
        $this->db->where('lt.guia_id', $guia_id);
        $this->db->where('lt.paciente_id', $paciente_id);
        $return = $this->db->get();
        $result = $return->result();
        return $result;
    }

    function preenchercirurgia($paciente_id, $guia_id) {

        $this->db->select('*');
        $this->db->from('tb_laudo_cirurgias lc');
        $this->db->where('lc.guia_id', $guia_id);
        $this->db->where('lc.paciente_id', $paciente_id);
        $return = $this->db->get();
        $result = $return->result();
        return $result;
    }

    function preencherexameslab($paciente_id, $guia_id=NULL) {

        $this->db->select('*');
        $this->db->from('tb_laudo_exameslab le');
        $this->db->where('le.guia_id', $guia_id);
        $this->db->where('le.paciente_id', $paciente_id);
        $return = $this->db->get();
        $result = $return->result();
        return $result;
    }

    function preencherformulario($paciente_id, $guia_id) {

        $this->db->select('*');
        $this->db->from('tb_laudo_form lf');
        $this->db->where('lf.guia_id', $guia_id);
        $this->db->where('lf.paciente_id', $paciente_id);
        $return = $this->db->get();
        $result = $return->result();
        return $result;
    }

    function preencheravaliacao($paciente_id, $guia_id) {

        $this->db->select('*');
        $this->db->from('tb_laudo_avaliacao la');
        $this->db->where('la.guia_id', $guia_id);
        $this->db->where('la.paciente_id', $paciente_id);
        $return = $this->db->get();
        $result = $return->result();
        return $result;
    }

    function gravarsolicitarparecer() {
        try {
            $operador_id = $this->session->userdata('operador_id');
            $horario = date("Y-m-d H:i:s");

            $this->db->set('paciente_id', $_POST['paciente_id']);
            $this->db->set('guia_id', $_POST['guia_id']);
            $this->db->set('ambulatorio_laudo_id', $_POST['ambulatorio_laudo_id']);
            $this->db->set('especialidade_parecer_id', $_POST['especialidade_parecer']);
            $this->db->set('subrotina_id', $_POST['sub_rotina']);
            $this->db->set('status', 'ESPERA');
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->set('data_cadastro', $horario);
            $this->db->insert('tb_centrocirurgico_parecer');

            $insert_id = $this->db->insert_id();


            return $insert_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function especialidadeparecersubrotinatela($especialidade_parecer_subrotina_id) {

        $this->db->select('
                            eps.especialidade_parecer_subrotina_id,
                            eps.nome as subrotina,
                            eps.tela
                               ');
        $this->db->from('tb_especialidade_parecer_subrotina eps ');
        $this->db->where('eps.especialidade_parecer_subrotina_id', $especialidade_parecer_subrotina_id);
        $return = $this->db->get()->result();
        return $return[0]->tela;
    }

    function listarsolicitacaoparecer($paciente_id) {

        $this->db->select('
                        cp.centrocirurgico_parecer_id,
                        cp.paciente_id,
                        cp.ambulatorio_laudo_id,
                        cp.status,
                        eps.nome as subrotina,
                        ep.nome as especialidade,
                        o.nome as solicitante,
                        cp.data_cadastro
                               ');
        $this->db->from('tb_centrocirurgico_parecer cp');
        $this->db->join('tb_operador o', 'cp.operador_cadastro = o.operador_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = cp.paciente_id', 'left');
        $this->db->join('tb_especialidade_parecer ep', 'cp.especialidade_parecer_id = ep.especialidade_parecer_id', 'left');
        $this->db->join('tb_especialidade_parecer_subrotina eps', 'eps.especialidade_parecer_subrotina_id = cp.subrotina_id', 'left');
        $this->db->where('cp.paciente_id', $paciente_id);
        $this->db->where('cp.ativo', 't');
        $return = $this->db->get()->result();
        return $return;
    }

    function gravarformulario() {
        try {
            $paciente_id = $this->session->userdata('paciente_id');
            //         $this->db->set('paciente_id', $_POST['paciente_id']);
            $guia_id = $this->session->userdata('guia_id');
            //         $this->db->set('guia_id', $_POST['guia_id']);

            $perguntas_form = array(
                "pergunta1" => $_POST["pergunta1"],
                "pergunta2" => $_POST["pergunta2"],
                "pergunta3" => $_POST["pergunta3"],
                "pergunta4" => $_POST["pergunta4"],
                "pergunta5" => $_POST["pergunta5"],
                "pergunta6" => $_POST["pergunta6"],
                "pergunta7" => $_POST["pergunta7"],
                "pergunta8" => $_POST["pergunta8"],
                "pergunta9" => $_POST["pergunta9"],
                "pergunta10" => $_POST["pergunta10"],
                "pergunta11" => $_POST["pergunta11"]
            );


            $this->db->select('lf.guia_id, lf.paciente_id');
            $this->db->from('tb_laudo_form lf');
            $this->db->where('lf.guia_id', $_POST['guia_id']);
            $this->db->where('lf.paciente_id', $_POST['paciente_id']);
            $return = $this->db->get();
            $result = $return->result();

            if (count($result) > 0) {


                if (count($perguntas_form) > 0) {
                    $this->db->set('questoes', json_encode($perguntas_form));
                } else {
                    $this->db->set('questoes', '');
                }

                if ($_POST['obesidade'] != '') {
                    $this->db->set('obesidade', $_POST['obesidade']);
                }
                if ($_POST['diabetes'] != '') {
                    $this->db->set('diabetes', $_POST['diabetes']);
                }
                if ($_POST['sedentarismo'] != '') {
                    $this->db->set('sedentarismo', $_POST['sedentarismo']);
                }
                if ($_POST['hipertensao'] != '') {
                    $this->db->set('hipertensao', $_POST['hipertensao']);
                }
                if ($_POST['dac'] != '') {
                    $this->db->set('dac', $_POST['dac']);
                }
                if ($_POST['tabagismo'] != '') {
                    $this->db->set('tabagismo', $_POST['tabagismo']);
                }
                if ($_POST['dislipidemia'] != '') {
                    $this->db->set('dislipidemia', $_POST['dislipidemia']);
                }
                if ($_POST['diabetespe'] != '') {
                    $this->db->set('diabetespe', $_POST['diabetespe']);
                }
                if ($_POST['haspe'] != '') {
                    $this->db->set('haspe', $_POST['haspe']);
                }
                if ($_POST['dacpe'] != '') {
                    $this->db->set('dacpe', $_POST['dacpe']);
                }
                if ($_POST['ircpe'] != '') {
                    $this->db->set('ircpe', $_POST['ircpe']);
                }
                if ($_POST['sopros'] != '') {
                    $this->db->set('sopros', $_POST['sopros']);
                }

                $this->db->where('guia_id', $_POST['guia_id']);
                $this->db->where('paciente_id', $_POST['paciente_id']);
                $this->db->set('paciente_id', $_POST['paciente_id']);
                $this->db->set('guia_id', $_POST['guia_id']);
                $this->db->update('tb_laudo_form');
            } else {
                if (count($perguntas_form) > 0) {
                    $this->db->set('questoes', json_encode($perguntas_form));
                } else {
                    $this->db->set('questoes', '');
                }

                if ($_POST['obesidade'] != '') {
                    $this->db->set('obesidade', $_POST['obesidade']);
                }
                if ($_POST['diabetes'] != '') {
                    $this->db->set('diabetes', $_POST['diabetes']);
                }
                if ($_POST['sedentarismo'] != '') {
                    $this->db->set('sedentarismo', $_POST['sedentarismo']);
                }
                if ($_POST['hipertensao'] != '') {
                    $this->db->set('hipertensao', $_POST['hipertensao']);
                }
                if ($_POST['dac'] != '') {
                    $this->db->set('dac', $_POST['dac']);
                }
                if ($_POST['tabagismo'] != '') {
                    $this->db->set('tabagismo', $_POST['tabagismo']);
                }
                if ($_POST['dislipidemia'] != '') {
                    $this->db->set('dislipidemia', $_POST['dislipidemia']);
                }
                if ($_POST['diabetespe'] != '') {
                    $this->db->set('diabetespe', $_POST['diabetespe']);
                }
                if ($_POST['haspe'] != '') {
                    $this->db->set('haspe', $_POST['haspe']);
                }
                if ($_POST['dacpe'] != '') {
                    $this->db->set('dacpe', $_POST['dacpe']);
                }
                if ($_POST['ircpe'] != '') {
                    $this->db->set('ircpe', $_POST['ircpe']);
                }
                if ($_POST['sopros'] != '') {
                    $this->db->set('sopros', $_POST['sopros']);
                }
                $this->db->where('guia_id', $_POST['guia_id']);
                $this->db->where('paciente_id', $_POST['paciente_id']);
                $this->db->set('paciente_id', $_POST['paciente_id']);
                $this->db->set('guia_id', $_POST['guia_id']);
                $this->db->insert('tb_laudo_form');
            }
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarlaudoapendicite() {
        try {
            $paciente_id = $this->session->userdata('paciente_id');
            $guia_id = $this->session->userdata('guia_id');


            $simnao_form = array(
                "comfluido" => (isset($_POST["comfluido"])) ? $_POST["comfluido"] : '',
                "compressivel" => (isset($_POST["compressivel"])) ? $_POST["compressivel"] : '',
                "apendicolito" => (isset($_POST["apendicolito"])) ? $_POST["apendicolito"] : '',
                "hiperemia" => (isset($_POST["hiperemia"])) ? $_POST["hiperemia"] : '',
                "espessamento" => (isset($_POST["espessamento"])) ? $_POST["espessamento"] : '',
                "pemural" => (isset($_POST["pemural"])) ? $_POST["pemural"] : '',
                "fluidolivre" => (isset($_POST["fluidolivre"])) ? $_POST["fluidolivre"] : '',
                "aegperiapendicular" => (isset($_POST["aegperiapendicular"])) ? $_POST["aegperiapendicular"] : '',
                "abscesso" => (isset($_POST["abscesso"])) ? $_POST["abscesso"] : '',
                "abcessovolume" => (isset($_POST["abcessovolume"])) ? $_POST["abcessovolume"] : ''
            );
            $pergunta_form = array(
                "historicoclinico" => (isset($_POST["historicoclinico"])) ? $_POST["historicoclinico"] : '',
                "estudosanteriores" => (isset($_POST["estudosanteriores"])) ? $_POST["estudosanteriores"] : '',
                "descobertas" => (isset($_POST["descobertas"])) ? $_POST["descobertas"] : '',
                "visualizado" => (isset($_POST["visualizado"])) ? $_POST["visualizado"] : '',
                "diametromax" => (isset($_POST["diametromax"])) ? $_POST["diametromax"] : '',
                "descobertasadc" => (isset($_POST["descobertasadc"])) ? $_POST["descobertasadc"] : '',
                "escoreapendicite" => (isset($_POST["escoreapendicite"])) ? $_POST["escoreapendicite"] : '',
                "diagnosticoadc" => (isset($_POST["diagnosticoadc"])) ? $_POST["diagnosticoadc"] : ''
            );

            $this->db->select('la.guia_id, la.paciente_id');
            $this->db->from('tb_laudo_apendicite la');
            $this->db->where('la.guia_id', $_POST['guia_id']);
            $this->db->where('la.paciente_id', $_POST['paciente_id']);
            $return = $this->db->get();
            $result = $return->result();
//              var_dump(count($result));die;

            if (count($result) > 0) {
                if (count($simnao_form) > 0) {
                    $this->db->set('simnao', json_encode($simnao_form));
                } else {
                    $this->db->set('simnao', '');
                }
                if (count($pergunta_form) > 0) {
                    $this->db->set('perguntas', json_encode($pergunta_form));
                } else {
                    $this->db->set('perguntas', '');
                }

                $this->db->where('guia_id', $_POST['guia_id']);
                $this->db->where('paciente_id', $_POST['paciente_id']);
                $this->db->set('paciente_id', $_POST['paciente_id']);
                $this->db->set('guia_id', $_POST['guia_id']);
                $this->db->update('tb_laudo_apendicite');
            } else {
                if (count($simnao_form) > 0) {
                    $this->db->set('simnao', json_encode($simnao_form));
                } else {
                    $this->db->set('simnao', '');
                }
                if (count($pergunta_form) > 0) {
                    $this->db->set('perguntas', json_encode($pergunta_form));
                } else {
                    $this->db->set('perguntas', '');
                }

                $this->db->where('guia_id', $_POST['guia_id']);
                $this->db->where('paciente_id', $_POST['paciente_id']);
                $this->db->set('paciente_id', $_POST['paciente_id']);
                $this->db->set('guia_id', $_POST['guia_id']);
                $this->db->insert('tb_laudo_apendicite');
            }
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarparecer() {
        try {
            $paciente_id = $this->session->userdata('paciente_id');
            $guia_id = $this->session->userdata('guia_id');


            $dados_form = array(
                "dado1" => (isset($_POST["dado1"])) ? $_POST["dado1"] : '',
                "dado2" => (isset($_POST["dado2"])) ? $_POST["dado2"] : '',
                "dado3" => (isset($_POST["dado3"])) ? $_POST["dado3"] : '',
                "dado4" => (isset($_POST["dado4"])) ? $_POST["dado4"] : '',
                "dado5" => (isset($_POST["dado5"])) ? $_POST["dado5"] : '',
                "dado6" => (isset($_POST["dado6"])) ? $_POST["dado6"] : '',
                "dado7" => (isset($_POST["dado7"])) ? $_POST["dado7"] : '',
                "dado8" => (isset($_POST["dado8"])) ? $_POST["dado8"] : '',
                "dado9" => (isset($_POST["dado9"])) ? $_POST["dado9"] : '',
                "dado10" => (isset($_POST["dado10"])) ? $_POST["dado10"] : '',
                "dado11" => (isset($_POST["dado11"])) ? $_POST["dado11"] : '',
                "dado12" => (isset($_POST["dado12"])) ? $_POST["dado12"] : '',
                "dado13" => (isset($_POST["dado13"])) ? $_POST["dado13"] : ''
            );
            $exames_form = array(
                "exame1" => (isset($_POST["exame1"])) ? $_POST["exame1"] : '',
                "exame2" => (isset($_POST["exame2"])) ? $_POST["exame2"] : '',
                "exame3" => (isset($_POST["exame3"])) ? $_POST["exame3"] : '',
                "exame4" => (isset($_POST["exame4"])) ? $_POST["exame4"] : '',
                "exame5" => (isset($_POST["exame5"])) ? $_POST["exame5"] : '',
                "exame6" => (isset($_POST["exame6"])) ? $_POST["exame6"] : '',
                "exame7" => (isset($_POST["exame7"])) ? $_POST["exame7"] : '',
                "exame8" => (isset($_POST["exame8"])) ? $_POST["exame8"] : '',
                "exame9" => (isset($_POST["exame9"])) ? $_POST["exame9"] : '',
                "exame10" => (isset($_POST["exame10"])) ? $_POST["exame10"] : ''
            );
            $examesc_form = array(
                "examec1" => (isset($_POST["examec1"])) ? $_POST["examec1"] : '',
                "examec2" => (isset($_POST["examec2"])) ? $_POST["examec2"] : '',
                "examec3" => (isset($_POST["examec3"])) ? $_POST["examec3"] : '',
                "examec4" => (isset($_POST["examec4"])) ? $_POST["examec4"] : ''
            );

            $hipotese_diagnostica = array(
                "diagnostico1" => (isset($_POST["diagnostico1"])) ? $_POST["diagnostico1"] : '',
                "diagnostico2" => (isset($_POST["diagnostico2"])) ? $_POST["diagnostico2"] : '',
                "diagnostico3" => (isset($_POST["diagnostico3"])) ? $_POST["diagnostico3"] : '',
                "diagnostico4" => (isset($_POST["diagnostico4"])) ? $_POST["diagnostico4"] : '',
                "diagnostico5" => (isset($_POST["diagnostico5"])) ? $_POST["diagnostico5"] : '',
                "diagnostico6" => (isset($_POST["diagnostico6"])) ? $_POST["diagnostico6"] : '',
                "diagnostico7" => (isset($_POST["diagnostico7"])) ? $_POST["diagnostico7"] : '',
                "diagnostico8" => (isset($_POST["diagnostico8"])) ? $_POST["diagnostico8"] : '',
                "diagnostico9" => (isset($_POST["diagnostico9"])) ? $_POST["diagnostico9"] : '',
                "diagnostico10" => (isset($_POST["diagnostico10"])) ? $_POST["diagnostico10"] : '',
                "texto_outros" => (isset($_POST["texto_outros"])) ? $_POST["texto_outros"] : ''
            );



            $this->db->select('lp.guia_id, lp.paciente_id');
            $this->db->from('tb_laudo_parecer lp');
            $this->db->where('lp.guia_id', $_POST['guia_id']);
            $this->db->where('lp.paciente_id', $_POST['paciente_id']);
            $return = $this->db->get();
            $result = $return->result();
            //  var_dump($result);die;

            if (count($result) > 0) {
                if (count($dados_form) > 0) {
                    $this->db->set('dados', json_encode($dados_form));
                } else {
                    $this->db->set('dados', '');
                }
                if (count($exames_form) > 0) {
                    $this->db->set('exames', json_encode($exames_form));
                } else {
                    $this->db->set('exames', '');
                }
                if (count($examesc_form) > 0) {
                    $this->db->set('exames_complementares', json_encode($examesc_form));
                } else {
                    $this->db->set('exames_complementares', '');
                }
                if (count($hipotese_diagnostica) > 0) {
                    $this->db->set('hipotese_diagnostica', json_encode($hipotese_diagnostica));
                } else {
                    $this->db->set('hipotese_diagnostica', '');
                }
                if (isset($_POST['sim'])) {
                    $this->db->set('antibiotico', "SIM");
                }
                if (isset($_POST['nao'])) {
                    $this->db->set('antibiotico', "NÃO");
                }

                $this->db->where('guia_id', $_POST['guia_id']);
                $this->db->where('paciente_id', $_POST['paciente_id']);
                $this->db->set('paciente_id', $_POST['paciente_id']);
                $this->db->set('guia_id', $_POST['guia_id']);
                $this->db->set('observacao', $_POST['observacao']);
                $this->db->update('tb_laudo_parecer');
            } else {
                if (count($dados_form) > 0) {
                    $this->db->set('dados', json_encode($dados_form));
                } else {
                    $this->db->set('dados', '');
                }
                if (count($exames_form) > 0) {
                    $this->db->set('exames', json_encode($exames_form));
                } else {
                    $this->db->set('exames', '');
                }
                if (count($examesc_form) > 0) {
                    $this->db->set('exames_complementares', json_encode($examesc_form));
                } else {
                    $this->db->set('exames_complementares', '');
                }
                if (count($hipotese_diagnostica) > 0) {
                    $this->db->set('hipotese_diagnostica', json_encode($hipotese_diagnostica));
                } else {
                    $this->db->set('hipotese_diagnostica', '');
                }
                if (isset($_POST['sim'])) {
                    $this->db->set('antibiotico', "SIM");
                }
                if (isset($_POST['nao'])) {
                    $this->db->set('antibiotico', "NÃO");
                }

                $this->db->where('guia_id', $_POST['guia_id']);
                $this->db->where('paciente_id', $_POST['paciente_id']);
                $this->db->set('paciente_id', $_POST['paciente_id']);
                $this->db->set('guia_id', $_POST['guia_id']);
                $this->db->set('observacao', $_POST['observacao']);
                $this->db->insert('tb_laudo_parecer');
            }
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarcirurgia() {
        try {
            $paciente_id = $this->session->userdata('paciente_id');
            $guia_id = $this->session->userdata('guia_id');


            $cirurgias_form = array(
                "mie" => (isset($_POST["mie"])) ? $_POST["mie"] : '',
                "mid" => (isset($_POST["mid"])) ? $_POST["mid"] : '',
                "pvs1" => (isset($_POST["pvs1"])) ? $_POST["pvs1"] : '',
                "pvs2" => (isset($_POST["pvs2"])) ? $_POST["pvs2"] : '',
                "pvs3" => (isset($_POST["pvs3"])) ? $_POST["pvs3"] : '',
                "radiald" => (isset($_POST["radiald"])) ? $_POST["radiald"] : '',
                "radiale" => (isset($_POST["radiale"])) ? $_POST["radiale"] : '',
                "gastroepiploica" => (isset($_POST["gastroepiploica"])) ? $_POST["gastroepiploica"] : '',
                "endarterectomia1" => (isset($_POST["endarterectomia1"])) ? $_POST["endarterectomia1"] : '',
                "endarterectomia2" => (isset($_POST["endarterectomia2"])) ? $_POST["endarterectomia2"] : '',
                "protesevalvar1" => (isset($_POST["protesevalvar1"])) ? $_POST["protesevalvar1"] : '',
                "protesevalvar2" => (isset($_POST["protesevalvar2"])) ? $_POST["protesevalvar2"] : '',
                "plastiavalvar1" => (isset($_POST["plastiavalvar1"])) ? $_POST["plastiavalvar1"] : '',
                "plastiavalvar2" => (isset($_POST["plastiavalvar2"])) ? $_POST["plastiavalvar2"] : '',
                "congenitas" => (isset($_POST["congenitas"])) ? $_POST["congenitas"] : '',
                "outrascirurgias" => (isset($_POST["outrascirurgias"])) ? $_POST["outrascirurgias"] : ''
            );
            $complicacao_form = array(
                "complicacao1" => (isset($_POST["complicacao1"])) ? $_POST["complicacao1"] : '',
                "complicacao2" => (isset($_POST["complicacao2"])) ? $_POST["complicacao2"] : '',
                "complicacao3" => (isset($_POST["complicacao3"])) ? $_POST["complicacao3"] : '',
                "complicacao4" => (isset($_POST["complicacao4"])) ? $_POST["complicacao4"] : '',
                "complicacao5" => (isset($_POST["complicacao5"])) ? $_POST["complicacao5"] : '',
                "complicacao6" => (isset($_POST["complicacao6"])) ? $_POST["complicacao6"] : '',
                "complicacao7" => (isset($_POST["complicacao7"])) ? $_POST["complicacao7"] : '',
                "complicacao8" => (isset($_POST["complicacao8"])) ? $_POST["complicacao8"] : '',
                "complicacao9" => (isset($_POST["complicacao9"])) ? $_POST["complicacao9"] : '',
                "complicacao10" => (isset($_POST["complicacao10"])) ? $_POST["complicacao10"] : '',
                "complicacao11" => (isset($_POST["complicacao11"])) ? $_POST["complicacao11"] : '',
                "complicacao12" => (isset($_POST["complicacao12"])) ? $_POST["complicacao12"] : '',
                "complicacao13" => (isset($_POST["complicacao13"])) ? $_POST["complicacao13"] : '',
                "complicacao14" => (isset($_POST["complicacao14"])) ? $_POST["complicacao14"] : '',
                "complicacao15" => (isset($_POST["complicacao15"])) ? $_POST["complicacao15"] : '',
                "complicacao16" => (isset($_POST["complicacao16"])) ? $_POST["complicacao16"] : '',
                "complicacao17" => (isset($_POST["complicacao17"])) ? $_POST["complicacao17"] : '',
                "complicacao18" => (isset($_POST["complicacao18"])) ? $_POST["complicacao18"] : '',
                "complicacao19" => (isset($_POST["complicacao19"])) ? $_POST["complicacao19"] : '',
                "complicacao20" => (isset($_POST["complicacao20"])) ? $_POST["complicacao20"] : '',
                "complicacao21" => (isset($_POST["complicacao21"])) ? $_POST["complicacao21"] : '',
                "complicacao22" => (isset($_POST["complicacao22"])) ? $_POST["complicacao22"] : '',
                "complicacao23" => (isset($_POST["complicacao23"])) ? $_POST["complicacao23"] : '',
                "complicacao24" => (isset($_POST["complicacao24"])) ? $_POST["complicacao24"] : '',
                "complicacao25" => (isset($_POST["complicacao25"])) ? $_POST["complicacao25"] : '',
                "complicacao26" => (isset($_POST["complicacao26"])) ? $_POST["complicacao26"] : '',
                "complicacao27" => (isset($_POST["complicacao27"])) ? $_POST["complicacao27"] : '',
                "complicacao28" => (isset($_POST["complicacao28"])) ? $_POST["complicacao28"] : '',
                "complicacao29" => (isset($_POST["complicacao29"])) ? $_POST["complicacao29"] : '',
                "complicacao30" => (isset($_POST["complicacao30"])) ? $_POST["complicacao30"] : '',
                "complicacao31" => (isset($_POST["complicacao31"])) ? $_POST["complicacao31"] : '',
                "complicacao32" => (isset($_POST["complicacao32"])) ? $_POST["complicacao32"] : '',
                "complicacao33" => (isset($_POST["complicacao33"])) ? $_POST["complicacao33"] : ''
            );



            $this->db->select('lc.guia_id, lc.paciente_id');
            $this->db->from('tb_laudo_cirurgias lc');
            $this->db->where('lc.guia_id', $_POST['guia_id']);
            $this->db->where('lc.paciente_id', $_POST['paciente_id']);
            $return = $this->db->get();
            $result = $return->result();

//            var_dump($result); die;
            if (count($result) > 0) {
                if (count($cirurgias_form) > 0) {
                    $this->db->set('cirurgias', json_encode($cirurgias_form));
                } else {
                    $this->db->set('cirurgias', '');
                }
                if (count($complicacao_form) > 0) {
                    $this->db->set('complicacoes', json_encode($complicacao_form));
                } else {
                    $this->db->set('complicacoes', '');
                }

                if ($_POST['ressonanciamag'] == "on") {
                    $this->db->set('ressonanciamag', "SIM");
                } else {
                    $this->db->set('ressonanciamag', '');
                }


                $this->db->where('guia_id', $_POST['guia_id']);
                $this->db->where('paciente_id', $_POST['paciente_id']);
                $this->db->set('paciente_id', $_POST['paciente_id']);
                $this->db->set('guia_id', $_POST['guia_id']);
                $this->db->update('tb_laudo_cirurgias');
            } else {

                if (count($cirurgias_form) > 0) {
                    $this->db->set('cirurgias', json_encode($cirurgias_form));
                } else {
                    $this->db->set('cirurgias', '');
                }
                if (count($complicacao_form) > 0) {
                    $this->db->set('complicacoes', json_encode($complicacao_form));
                } else {
                    $this->db->set('complicacoes', '');
                }

                if ($_POST['ressonanciamag'] == "on") {
                    $this->db->set('ressonanciamag', "SIM");
                } else {
                    $this->db->set('ressonanciamag', '');
                }

                $this->db->where('guia_id', $_POST['guia_id']);
                $this->db->where('paciente_id', $_POST['paciente_id']);
                $this->db->set('paciente_id', $_POST['paciente_id']);
                $this->db->set('guia_id', $_POST['guia_id']);
                $this->db->insert('tb_laudo_cirurgias');
            }

            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarexameslab() {
        try {
            $paciente_id = $this->session->userdata('paciente_id');
            $guia_id = $this->session->userdata('guia_id');


            $exameslab_form = array(
                "ct" => (isset($_POST["ct"])) ? $_POST["ct"] : '',
                "hdl" => (isset($_POST["hdl"])) ? $_POST["hdl"] : '',
                "tg" => (isset($_POST["tg"])) ? $_POST["tg"] : '',
                "ldl" => (isset($_POST["ldl"])) ? $_POST["ldl"] : '',
                "cthdl" => (isset($_POST["cthdl"])) ? $_POST["cthdl"] : '',
                "ldlhdl" => (isset($_POST["ldlhdl"])) ? $_POST["ldlhdl"] : '',
                "glicose" => (isset($_POST["glicose"])) ? $_POST["glicose"] : '',
                "glpp" => (isset($_POST["glpp"])) ? $_POST["glpp"] : '',
                "hemoglic" => (isset($_POST["hemoglic"])) ? $_POST["hemoglic"] : '',
                "ureia" => (isset($_POST["ureia"])) ? $_POST["ureia"] : '',
                "creatina" => (isset($_POST["creatina"])) ? $_POST["creatina"] : '',
                "hb" => (isset($_POST["hb"])) ? $_POST["hb"] : '',
                "ht" => (isset($_POST["ht"])) ? $_POST["ht"] : '',
                "leucocitos" => (isset($_POST["leucocitos"])) ? $_POST["leucocitos"] : '',
                "t3" => (isset($_POST["t3"])) ? $_POST["t3"] : '',
                "t4" => (isset($_POST["t4"])) ? $_POST["t4"] : '',
                "tsh" => (isset($_POST["tsh"])) ? $_POST["tsh"] : '',
                "tapinr" => (isset($_POST["tapinr"])) ? $_POST["tapinr"] : '',
                "acurico" => (isset($_POST["acurico"])) ? $_POST["acurico"] : '',
                "digoxina" => (isset($_POST["digoxina"])) ? $_POST["digoxina"] : ''
            );



            $this->db->select('le.guia_id, le.paciente_id');
            $this->db->from('tb_laudo_exameslab le');
            $this->db->where('le.guia_id', $_POST['guia_id']);
            $this->db->where('le.paciente_id', $_POST['paciente_id']);
            $return = $this->db->get();
            $result = $return->result();

//            var_dump($result); die;
            if (count($result) > 0) {
                if (count($exameslab_form) > 0) {
                    $this->db->set('exames_laboratoriais', json_encode($exameslab_form));
                } else {
                    $this->db->set('exames_laboratoriais', '');
                }

                $this->db->where('guia_id', $_POST['guia_id']);
                $this->db->where('paciente_id', $_POST['paciente_id']);
                $this->db->set('paciente_id', $_POST['paciente_id']);
                $this->db->set('guia_id', $_POST['guia_id']);
                $this->db->update('tb_laudo_exameslab');
            } else {

                if (count($exameslab_form) > 0) {
                    $this->db->set('exames_laboratoriais', json_encode($exameslab_form));
                } else {
                    $this->db->set('exames_laboratoriais', '');
                }

                $this->db->where('guia_id', $_POST['guia_id']);
                $this->db->where('paciente_id', $_POST['paciente_id']);
                $this->db->set('paciente_id', $_POST['paciente_id']);
                $this->db->set('guia_id', $_POST['guia_id']);
                $this->db->insert('tb_laudo_exameslab');
            }

            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarecocardio() {
        try {
            $paciente_id = $this->session->userdata('paciente_id');
            $guia_id = $this->session->userdata('guia_id');


            $ecocardio_form = array(
                "diastve" => (isset($_POST["diastve"])) ? $_POST["diastve"] : '',
                "sistve" => (isset($_POST["sistve"])) ? $_POST["sistve"] : '',
                "septoiv" => (isset($_POST["septoiv"])) ? $_POST["septoiv"] : '',
                "diastppve" => (isset($_POST["diastppve"])) ? $_POST["diastppve"] : '',
                "massave" => (isset($_POST["massave"])) ? $_POST["massave"] : '',
                "diastvi" => (isset($_POST["diastvi"])) ? $_POST["diastvi"] : '',
                "sistae" => (isset($_POST["sistae"])) ? $_POST["sistae"] : '',
                "ao" => (isset($_POST["ao"])) ? $_POST["ao"] : '',
                "fe" => (isset($_POST["fe"])) ? $_POST["fe"] : '',
                "sistad" => (isset($_POST["sistad"])) ? $_POST["sistad"] : '',
                "vdfve" => (isset($_POST["vdfve"])) ? $_POST["vdfve"] : '',
                "vsfve" => (isset($_POST["vsfve"])) ? $_POST["vsfve"] : '',
                "cavidades" => (isset($_POST["cavidades"])) ? $_POST["cavidades"] : '',
                "contratilidade" => (isset($_POST["contratilidade"])) ? $_POST["contratilidade"] : '',
                "valvulas" => (isset($_POST["valvulas"])) ? $_POST["valvulas"] : '',
                "aorta" => (isset($_POST["aorta"])) ? $_POST["aorta"] : '',
                "pericardio" => (isset($_POST["pericardio"])) ? $_POST["pericardio"] : '',
                "conclusao" => (isset($_POST["conclusao"])) ? $_POST["conclusao"] : ''
            );



            $this->db->select('lec.guia_id, lec.paciente_id');
            $this->db->from('tb_laudo_ecocardio lec');
            $this->db->where('lec.guia_id', $_POST['guia_id']);
            $this->db->where('lec.paciente_id', $_POST['paciente_id']);
            $return = $this->db->get();
            $result = $return->result();


            if (count($result) > 0) {
                if (count($ecocardio_form) > 0) {
                    $this->db->set('ecocardio', json_encode($ecocardio_form));
                } else {
                    $this->db->set('ecocardio', '');
                }

                $this->db->where('guia_id', $_POST['guia_id']);
                $this->db->where('paciente_id', $_POST['paciente_id']);
                $this->db->set('paciente_id', $_POST['paciente_id']);
                $this->db->set('guia_id', $_POST['guia_id']);
                $this->db->update('tb_laudo_ecocardio');
            } else {

                if (count($ecocardio_form) > 0) {
                    $this->db->set('ecocardio', json_encode($ecocardio_form));
                } else {
                    $this->db->set('ecocardio', '');
                }

                $this->db->where('guia_id', $_POST['guia_id']);
                $this->db->where('paciente_id', $_POST['paciente_id']);
                $this->db->set('paciente_id', $_POST['paciente_id']);
                $this->db->set('guia_id', $_POST['guia_id']);
                $this->db->insert('tb_laudo_ecocardio');
            }

            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarecostress() {
        try {
            $paciente_id = $this->session->userdata('paciente_id');
            $guia_id = $this->session->userdata('guia_id');


            $ecostress_form = array(
                "hipocinesiaanterior" => (isset($_POST["hipocinesiaanterior"])) ? $_POST["hipocinesiaanterior"] : '',
                "hipocinesiamedial" => (isset($_POST["hipocinesiamedial"])) ? $_POST["hipocinesiamedial"] : '',
                "hipocinesiaapical" => (isset($_POST["hipocinesiaapical"])) ? $_POST["hipocinesiaapical"] : '',
                "hipocinesiainferior" => (isset($_POST["hipocinesiainferior"])) ? $_POST["hipocinesiainferior"] : '',
                "hipocinesialateral" => (isset($_POST["hipocinesialateral"])) ? $_POST["hipocinesialateral"] : '',
                "disfuncao" => (isset($_POST["disfuncao"])) ? $_POST["disfuncao"] : ''
            );



            $this->db->select('les.guia_id, les.paciente_id');
            $this->db->from('tb_laudo_ecostress les');
            $this->db->where('les.guia_id', $_POST['guia_id']);
            $this->db->where('les.paciente_id', $_POST['paciente_id']);
            $return = $this->db->get();
            $result = $return->result();


            if (count($result) > 0) {
                if (count($ecostress_form) > 0) {
                    $this->db->set('ecostress', json_encode($ecostress_form));
                } else {
                    $this->db->set('ecostress', '');
                }

                $this->db->where('guia_id', $_POST['guia_id']);
                $this->db->where('paciente_id', $_POST['paciente_id']);
                $this->db->set('paciente_id', $_POST['paciente_id']);
                $this->db->set('guia_id', $_POST['guia_id']);
                $this->db->update('tb_laudo_ecostress');
            } else {

                if (count($ecostress_form) > 0) {
                    $this->db->set('ecostress', json_encode($ecostress_form));
                } else {
                    $this->db->set('ecostress', '');
                }

                $this->db->where('guia_id', $_POST['guia_id']);
                $this->db->where('paciente_id', $_POST['paciente_id']);
                $this->db->set('paciente_id', $_POST['paciente_id']);
                $this->db->set('guia_id', $_POST['guia_id']);
                $this->db->insert('tb_laudo_ecostress');
            }

            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarcate() {
        try {
            $paciente_id = $this->session->userdata('paciente_id');
            $guia_id = $this->session->userdata('guia_id');


            $cateterismo_form = array(
                "da" => (isset($_POST["da"])) ? $_POST["da"] : '',
                "cx" => (isset($_POST["cx"])) ? $_POST["cx"] : '',
                "mgcx1" => (isset($_POST["mgcx1"])) ? $_POST["mgcx1"] : '',
                "mgcx2" => (isset($_POST["mgcx2"])) ? $_POST["mgcx2"] : '',
                "mgcx3" => (isset($_POST["mgcx3"])) ? $_POST["mgcx3"] : '',
                "diag" => (isset($_POST["diag"])) ? $_POST["diag"] : '',
                "diagonalis" => (isset($_POST["diagonalis"])) ? $_POST["diagonalis"] : '',
                "cd" => (isset($_POST["cd"])) ? $_POST["cd"] : '',
                "dpcd" => (isset($_POST["dpcd"])) ? $_POST["dpcd"] : '',
                "vpcd" => (isset($_POST["vpcd"])) ? $_POST["vpcd"] : '',
                "colaterais" => (isset($_POST["colaterais"])) ? $_POST["colaterais"] : '',
                "ve" => (isset($_POST["ve"])) ? $_POST["ve"] : '',
                "vm" => (isset($_POST["vm"])) ? $_POST["vm"] : '',
                "vao" => (isset($_POST["vao"])) ? $_POST["vao"] : '',
                "vt" => (isset($_POST["vt"])) ? $_POST["vt"] : '',
                "vp" => (isset($_POST["vp"])) ? $_POST["vp"] : '',
                "circpulmonar" => (isset($_POST["circpulmonar"])) ? $_POST["circpulmonar"] : '',
                "observacoes" => (isset($_POST["observacoes"])) ? $_POST["observacoes"] : ''
            );



            $this->db->select('lca.guia_id, lca.paciente_id');
            $this->db->from('tb_laudo_cate lca');
            $this->db->where('lca.guia_id', $_POST['guia_id']);
            $this->db->where('lca.paciente_id', $_POST['paciente_id']);
            $return = $this->db->get();
            $result = $return->result();


            if (count($result) > 0) {
                if (count($cateterismo_form) > 0) {
                    $this->db->set('cate', json_encode($cateterismo_form));
                } else {
                    $this->db->set('cate', '');
                }

                $this->db->where('guia_id', $_POST['guia_id']);
                $this->db->where('paciente_id', $_POST['paciente_id']);
                $this->db->set('paciente_id', $_POST['paciente_id']);
                $this->db->set('guia_id', $_POST['guia_id']);
                $this->db->update('tb_laudo_cate');
            } else {

                if (count($cateterismo_form) > 0) {
                    $this->db->set('cate', json_encode($cateterismo_form));
                } else {
                    $this->db->set('cate', '');
                }

                $this->db->where('guia_id', $_POST['guia_id']);
                $this->db->where('paciente_id', $_POST['paciente_id']);
                $this->db->set('paciente_id', $_POST['paciente_id']);
                $this->db->set('guia_id', $_POST['guia_id']);
                $this->db->insert('tb_laudo_cate');
            }

            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarholter() {
        try {
            $paciente_id = $this->session->userdata('paciente_id');
            $guia_id = $this->session->userdata('guia_id');


            $holter_form = array(
                "ritmo" => (isset($_POST["ritmo"])) ? $_POST["ritmo"] : '',
                "fcmax" => (isset($_POST["fcmax"])) ? $_POST["fcmax"] : '',
                "fcmin" => (isset($_POST["fcmin"])) ? $_POST["fcmin"] : '',
                "fcmed" => (isset($_POST["fcmed"])) ? $_POST["fcmed"] : '',
                "essv" => (isset($_POST["essv"])) ? $_POST["essv"] : '',
                "esv" => (isset($_POST["esv"])) ? $_POST["esv"] : '',
                "taquiarritmias" => (isset($_POST["taquiarritmias"])) ? $_POST["taquiarritmias"] : '',
                "bradiarritmias" => (isset($_POST["bradiarritmias"])) ? $_POST["bradiarritmias"] : '',
                "sintomas" => (isset($_POST["sintomas"])) ? $_POST["sintomas"] : '',
                "pausas" => (isset($_POST["pausas"])) ? $_POST["pausas"] : '',
                "arventricular" => (isset($_POST["arventricular"])) ? $_POST["arventricular"] : '',
                "conclusao" => (isset($_POST["conclusao"])) ? $_POST["conclusao"] : ''
            );

            $this->db->select('lh.guia_id, lh.paciente_id');
            $this->db->from('tb_laudo_holter lh');
            $this->db->where('lh.guia_id', $_POST['guia_id']);
            $this->db->where('lh.paciente_id', $_POST['paciente_id']);
            $return = $this->db->get();
            $result = $return->result();


            if (count($result) > 0) {
                if (count($holter_form) > 0) {
                    $this->db->set('holter', json_encode($holter_form));
                } else {
                    $this->db->set('holter', '');
                }

                $this->db->where('guia_id', $_POST['guia_id']);
                $this->db->where('paciente_id', $_POST['paciente_id']);
                $this->db->set('paciente_id', $_POST['paciente_id']);
                $this->db->set('guia_id', $_POST['guia_id']);
                $this->db->update('tb_laudo_holter');
            } else {

                if (count($holter_form) > 0) {
                    $this->db->set('holter', json_encode($holter_form));
                } else {
                    $this->db->set('holter', '');
                }

                $this->db->where('guia_id', $_POST['guia_id']);
                $this->db->where('paciente_id', $_POST['paciente_id']);
                $this->db->set('paciente_id', $_POST['paciente_id']);
                $this->db->set('guia_id', $_POST['guia_id']);
                $this->db->insert('tb_laudo_holter');
            }

            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarcintilografia() {
        try {
            $paciente_id = $this->session->userdata('paciente_id');
            $guia_id = $this->session->userdata('guia_id');


            $cintil_form = array(
                "tipo" => (isset($_POST["tipo"])) ? $_POST["tipo"] : '',
                "sss" => (isset($_POST["sss"])) ? $_POST["sss"] : '',
                "fe" => (isset($_POST["fe"])) ? $_POST["fe"] : '',
                "afibrose" => (isset($_POST["afibrose"])) ? $_POST["afibrose"] : '',
                "aisquemia" => (isset($_POST["aisquemia"])) ? $_POST["aisquemia"] : '',
                "disfuncao" => (isset($_POST["disfuncao"])) ? $_POST["disfuncao"] : '',
                "tergometrico" => (isset($_POST["tergometrico"])) ? $_POST["tergometrico"] : '',
                "outrosachados" => (isset($_POST["outrosachados"])) ? $_POST["outrosachados"] : ''
            );



            $this->db->select('lc.guia_id, lc.paciente_id');
            $this->db->from('tb_laudo_cintil lc');
            $this->db->where('lc.guia_id', $_POST['guia_id']);
            $this->db->where('lc.paciente_id', $_POST['paciente_id']);
            $return = $this->db->get();
            $result = $return->result();


            if (count($result) > 0) {
                if (count($cintil_form) > 0) {
                    $this->db->set('cintil', json_encode($cintil_form));
                } else {
                    $this->db->set('cintil', '');
                }

                $this->db->where('guia_id', $_POST['guia_id']);
                $this->db->where('paciente_id', $_POST['paciente_id']);
                $this->db->set('paciente_id', $_POST['paciente_id']);
                $this->db->set('guia_id', $_POST['guia_id']);
                $this->db->update('tb_laudo_cintil');
            } else {

                if (count($cintil_form) > 0) {
                    $this->db->set('cintil', json_encode($cintil_form));
                } else {
                    $this->db->set('cintil', '');
                }

                $this->db->where('guia_id', $_POST['guia_id']);
                $this->db->where('paciente_id', $_POST['paciente_id']);
                $this->db->set('paciente_id', $_POST['paciente_id']);
                $this->db->set('guia_id', $_POST['guia_id']);
                $this->db->insert('tb_laudo_cintil');
            }

            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarmapa() {
        try {
            $paciente_id = $this->session->userdata('paciente_id');
            $guia_id = $this->session->userdata('guia_id');


            $mapa_form = array(
                "medidas" => (isset($_POST["medidas"])) ? $_POST["medidas"] : '',
                "pasvigilia" => (isset($_POST["pasvigilia"])) ? $_POST["pasvigilia"] : '',
                "padvigilia" => (isset($_POST["padvigilia"])) ? $_POST["padvigilia"] : '',
                "passono" => (isset($_POST["passono"])) ? $_POST["passono"] : '',
                "padsono" => (isset($_POST["padsono"])) ? $_POST["padsono"] : '',
                "sistolico" => (isset($_POST["sistolico"])) ? $_POST["sistolico"] : '',
                "distolico" => (isset($_POST["distolico"])) ? $_POST["distolico"] : '',
                "conclusao" => (isset($_POST["conclusao"])) ? $_POST["conclusao"] : ''
            );



            $this->db->select('lm.guia_id, lm.paciente_id');
            $this->db->from('tb_laudo_mapa lm');
            $this->db->where('lm.guia_id', $_POST['guia_id']);
            $this->db->where('lm.paciente_id', $_POST['paciente_id']);
            $return = $this->db->get();
            $result = $return->result();


            if (count($result) > 0) {
                if (count($mapa_form) > 0) {
                    $this->db->set('mapa', json_encode($mapa_form));
                } else {
                    $this->db->set('mapa', '');
                }

                $this->db->where('guia_id', $_POST['guia_id']);
                $this->db->where('paciente_id', $_POST['paciente_id']);
                $this->db->set('paciente_id', $_POST['paciente_id']);
                $this->db->set('guia_id', $_POST['guia_id']);
                $this->db->update('tb_laudo_mapa');
            } else {

                if (count($mapa_form) > 0) {
                    $this->db->set('mapa', json_encode($mapa_form));
                } else {
                    $this->db->set('mapa', '');
                }

                $this->db->where('guia_id', $_POST['guia_id']);
                $this->db->where('paciente_id', $_POST['paciente_id']);
                $this->db->set('paciente_id', $_POST['paciente_id']);
                $this->db->set('guia_id', $_POST['guia_id']);
                $this->db->insert('tb_laudo_mapa');
            }

            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravartergometrico() {
        try {
            $paciente_id = $this->session->userdata('paciente_id');
            $guia_id = $this->session->userdata('guia_id');


            $tergometrico_form = array(
                "estagio" => (isset($_POST["estagio"])) ? $_POST["estagio"] : '',
                "pa" => (isset($_POST["pa"])) ? $_POST["pa"] : '',
                "arritmias" => (isset($_POST["arritmias"])) ? $_POST["arritmias"] : '',
                "isquemia" => (isset($_POST["isquemia"])) ? $_POST["isquemia"] : '',
                "aptidaofisica" => (isset($_POST["aptidaofisica"])) ? $_POST["aptidaofisica"] : '',
                "conclusao" => (isset($_POST["conclusao"])) ? $_POST["conclusao"] : ''
            );



            $this->db->select('lt.guia_id, lt.paciente_id');
            $this->db->from('tb_laudo_tergometrico lt');
            $this->db->where('lt.guia_id', $_POST['guia_id']);
            $this->db->where('lt.paciente_id', $_POST['paciente_id']);
            $return = $this->db->get();
            $result = $return->result();


            if (count($result) > 0) {
                if (count($tergometrico_form) > 0) {
                    $this->db->set('tergometrico', json_encode($tergometrico_form));
                } else {
                    $this->db->set('tergometrico', '');
                }

                $this->db->where('guia_id', $_POST['guia_id']);
                $this->db->where('paciente_id', $_POST['paciente_id']);
                $this->db->set('paciente_id', $_POST['paciente_id']);
                $this->db->set('guia_id', $_POST['guia_id']);
                $this->db->update('tb_laudo_tergometrico');
            } else {

                if (count($tergometrico_form) > 0) {
                    $this->db->set('tergometrico', json_encode($tergometrico_form));
                } else {
                    $this->db->set('tergometrico', '');
                }

                $this->db->where('guia_id', $_POST['guia_id']);
                $this->db->where('paciente_id', $_POST['paciente_id']);
                $this->db->set('paciente_id', $_POST['paciente_id']);
                $this->db->set('guia_id', $_POST['guia_id']);
                $this->db->insert('tb_laudo_tergometrico');
            }

            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravaravaliacao() {
        try {
            $paciente_id = $this->session->userdata('paciente_id');
            $guia_id = $this->session->userdata('guia_id');


            $criterios_tb1 = array(
                "c1tb1" => $_POST["c1tb1"],
                "c2tb1" => $_POST["c2tb1"],
                "c3tb1" => $_POST["c3tb1"],
                "c4tb1" => $_POST["c4tb1"],
                "c5tb1" => $_POST["c5tb1"],
                "c6tb1" => $_POST["c6tb1"]
            );
            $criterios_tb2 = array(
                "c1tb2" => $_POST["c1tb2"],
                "c2tb2" => $_POST["c2tb2"],
                "c3tb2" => $_POST["c3tb2"],
                "c4tb2" => $_POST["c4tb2"],
                "c5tb2" => $_POST["c5tb2"],
                "c6tb2" => $_POST["c6tb2"],
                "c7tb2" => $_POST["c7tb2"],
                "c8tb2" => $_POST["c8tb2"],
                "c9tb2" => $_POST["c9tb2"],
                "c10tb2" => $_POST["c10tb2"],
                "c11tb2" => $_POST["c11tb2"],
                "c12tb2" => $_POST["c12tb2"]
            );
            $criterios_tb3 = array(
                "c1tb3" => $_POST["c1tb3"],
                "c2tb3" => $_POST["c2tb3"],
                "c3tb3" => $_POST["c3tb3"],
                "c4tb3" => $_POST["c4tb3"],
                "c5tb3" => $_POST["c5tb3"],
                "c6tb3" => $_POST["c6tb3"],
                "c7tb3" => $_POST["c7tb3"],
                "c8tb3" => $_POST["c8tb3"]
            );
            $criterios_tb4 = array(
                "riscoalto" => $_POST["riscoalto"],
                "riscomedio" => $_POST["riscomedio"],
                "riscobaixo" => $_POST["riscobaixo"]
            );
            $this->db->select('la.guia_id, la.paciente_id');
            $this->db->from('tb_laudo_avaliacao la');
            $this->db->where('la.guia_id', $_POST['guia_id']);
            $this->db->where('la.paciente_id', $_POST['paciente_id']);
            $return = $this->db->get();
            $result = $return->result();

            if (count($result) > 0) {


                if (count($criterios_tb1) > 0) {
                    $this->db->set('avaliacao_tabela1', json_encode($criterios_tb1));
                } else {
                    $this->db->set('avaliacao_tabela1', '');
                }
                if (count($criterios_tb2) > 0) {
                    $this->db->set('avaliacao_tabela2', json_encode($criterios_tb2));
                } else {
                    $this->db->set('avaliacao_tabela2', '');
                }
                if (count($criterios_tb3) > 0) {
                    $this->db->set('avaliacao_tabela3', json_encode($criterios_tb3));
                } else {
                    $this->db->set('avaliacao_tabela3', '');
                }
                if (count($criterios_tb4) > 0) {
                    $this->db->set('avaliacao_tabela4', json_encode($criterios_tb4));
                } else {
                    $this->db->set('avaliacao_tabela4', '');
                }
                $this->db->where('guia_id', $_POST['guia_id']);
                $this->db->where('paciente_id', $_POST['paciente_id']);
                $this->db->set('paciente_id', $_POST['paciente_id']);
                $this->db->set('guia_id', $_POST['guia_id']);
                $this->db->update('tb_laudo_avaliacao');
            } else {


                if (count($criterios_tb1) > 0) {
                    $this->db->set('avaliacao_tabela1', json_encode($criterios_tb1));
                } else {
                    $this->db->set('avaliacao_tabela1', '');
                }
                if (count($criterios_tb2) > 0) {
                    $this->db->set('avaliacao_tabela2', json_encode($criterios_tb2));
                } else {
                    $this->db->set('avaliacao_tabela2', '');
                }
                if (count($criterios_tb3) > 0) {
                    $this->db->set('avaliacao_tabela3', json_encode($criterios_tb3));
                } else {
                    $this->db->set('avaliacao_tabela3', '');
                }
                if (count($criterios_tb4) > 0) {
                    $this->db->set('avaliacao_tabela4', json_encode($criterios_tb3));
                } else {
                    $this->db->set('avaliacao_tabela4', '');
                }
                $this->db->where('guia_id', $_POST['guia_id']);
                $this->db->where('paciente_id', $_POST['paciente_id']);
                $this->db->set('paciente_id', $_POST['paciente_id']);
                $this->db->set('guia_id', $_POST['guia_id']);
                $this->db->insert('tb_laudo_avaliacao');
            }
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravaratestado() {
        try {
//            var_dump($_POST['cid1ID']);
//            die;
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('texto', $_POST['laudo']);
            if ($_POST['carimbo'] == "on") {
                $this->db->set('carimbo', 't');
            }
            if ($_POST['assinatura'] == "on") {
                $this->db->set('assinatura', 't');
            }

            $this->db->set('laudo_id', $_POST['ambulatorio_laudo_id']);
            $this->db->set('medico_parecer1', $_POST['medico']);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $_POST['medico']);
            if ($_POST['data'] != "") {
                $this->db->set('data', date("Y-m-d", strtotime(str_replace("/", "-", $_POST['data']))));
            }
            if ($_POST['cid1ID'] != "") {
                $this->db->set('cid1', $_POST['cid1ID']);
            }
            if ($_POST['cid2ID'] != "") {
                $this->db->set('cid2', $_POST['cid2ID']);
            }
            if ($_POST['imprimircid'] == "on") {
                $this->db->set('imprimir_cid', 't');
            }
            $this->db->set('tipo', 'NORMAL');

            $this->db->insert('tb_ambulatorio_atestado');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarexame() {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('texto', $_POST['laudo']);

            if ($_POST['carimbo'] == "on") {
                $this->db->set('carimbo', 't');
            }
            if ($_POST['assinatura'] == "on") {
                $this->db->set('assinatura', 't');
            }
//            $this->db->set('paciente_id', $_POST['paciente_id']);
//            $this->db->set('procedimento_tuss_id', $_POST['procedimento_tuss_id']);
            $this->db->set('laudo_id', $_POST['ambulatorio_laudo_id']);
            $this->db->set('medico_parecer1', $_POST['medico']);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $_POST['medico']);
            $this->db->set('tipo', 'NORMAL');

            $this->db->insert('tb_ambulatorio_exame');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarexameatendimento($ambulatorio_laudo_id, $exames = NULL, $id = NULL, $adendo = FALSE) {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            
            if($exames == NULL){
                $this->db->set('texto', $_POST['solicitar_exames']); 
            }else{
                $this->db->set('texto', $exames); 
            }         


            if (isset($_POST['carimbo']) || isset($_POST['carimbo_exame'])) {
                $this->db->set('carimbo', 't');
            }
            if (isset($_POST['assinatura'])  || isset($_POST['assinatura_exame'])) {
                $this->db->set('assinatura', 't');
            }               
            
//            $this->db->set('paciente_id', $_POST['paciente_id']);
//            $this->db->set('procedimento_tuss_id', $_POST['procedimento_tuss_id']);
            $this->db->set('laudo_id', $ambulatorio_laudo_id);
            $this->db->set('medico_parecer1', $_POST['medico']);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $_POST['medico']);
            $this->db->set('tipo', 'NORMAL');
            $this->db->set('exame_id', $id);
            if($adendo){
                $this->db->set('adendo', 't');
            }

            $this->db->insert('tb_ambulatorio_exame');
            $id = $this->db->insert_id();
            
            $this->db->set('laudo_id',$ambulatorio_laudo_id);
            $this->db->set('data_cadastro',$horario);
            $this->db->set('operador_cadastro',$operador_id);
            $this->db->set('ambulatorio_exame_id',$id);
            $this->db->insert('tb_solicitacao_exame_chamar');
            
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return $id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarterapeuticasatendimento($ambulatorio_laudo_id, $terapeuticas, $id = NULL, $adendo = FALSE) {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            
            $this->db->set('texto', $terapeuticas); 
                    
            if (isset($_POST['carimbo'])) {
                $this->db->set('carimbo', 't');
            }
            if (isset($_POST['assinatura'])) {
                $this->db->set('assinatura', 't');
            }

            $this->db->set('laudo_id', $ambulatorio_laudo_id);
            $this->db->set('medico_parecer1', $_POST['medico']);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $_POST['medico']);
            $this->db->set('terapeuticas_id', $id);
            if($adendo){
                $this->db->set('adendo', 't');
            }

            $this->db->insert('tb_ambulatorio_terapeuticas');
            $id = $this->db->insert_id();
            
            
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return $id;
        } catch (Exception $exc) {
            return -1;
        }
    }


    function gravarrelatorioatendimento($ambulatorio_laudo_id, $relatorio, $id = NULL, $adendo = FALSE) {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            
            $this->db->set('texto', $relatorio); 
                    
            if (isset($_POST['carimbo'])) {
                $this->db->set('carimbo', 't');
            }
            if (isset($_POST['assinatura'])) {
                $this->db->set('assinatura', 't');
            }

            $this->db->set('laudo_id', $ambulatorio_laudo_id);
            $this->db->set('medico_parecer1', $_POST['medico']);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $_POST['medico']);
            $this->db->set('relatorio_id', $id);

            if($adendo){
                $this->db->set('adendo', 't');
            }

            $this->db->insert('tb_ambulatorio_relatorio');
            $id = $this->db->insert_id();
            
            
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return $id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarreceituarioespecial() {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
       
            $this->db->set('texto', $_POST['laudo']);
//            $this->db->set('paciente_id', $_POST['paciente_id']);
//            $this->db->set('procedimento_tuss_id', $_POST['procedimento_tuss_id']);
            if ($_POST['carimbo'] == "on") {
                $this->db->set('carimbo', 't');
            }
            if ($_POST['assinatura'] == "on") {
                $this->db->set('assinatura', 't');
            }
            $this->db->set('laudo_id', $_POST['ambulatorio_laudo_id']);
            $this->db->set('medico_parecer1', $_POST['medico']);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $_POST['medico']);
            $this->db->set('tipo', 'ESPECIAL');

            $this->db->insert('tb_ambulatorio_receituario_especial');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function editarreceituarioespecial() {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('texto', $_POST['laudo']);
//            $this->db->set('paciente_id', $_POST['paciente_id']);
//            $this->db->set('procedimento_tuss_id', $_POST['procedimento_tuss_id']);
            $this->db->set('laudo_id', $_POST['ambulatorio_laudo_id']);
            $this->db->set('medico_parecer1', $_POST['medico']);
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('ambulatorio_receituario_especial_id', $_POST['receituario_id']);
            $this->db->update('tb_ambulatorio_receituario_especial');
        } catch (Exception $exc) {
            return -1;
        }
    }

    function editarreceituario() {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('texto', $_POST['laudo']);
//            $this->db->set('paciente_id', $_POST['paciente_id']);
//            $this->db->set('procedimento_tuss_id', $_POST['procedimento_tuss_id']);
            $this->db->set('laudo_id', $_POST['ambulatorio_laudo_id']);
            $this->db->set('medico_parecer1', $_POST['medico']);
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('ambulatorio_receituario_id', $_POST['receituario_id']);
            $this->db->update('tb_ambulatorio_receituario');
        } catch (Exception $exc) {
            return -1;
        }
    }

    function editarrotina() {
        try {
//            var_dump($_POST);die;
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('texto', $_POST['laudo']);
//            $this->db->set('paciente_id', $_POST['paciente_id']);
//            $this->db->set('procedimento_tuss_id', $_POST['procedimento_tuss_id']);
            $this->db->set('laudo_id', $_POST['ambulatorio_laudo_id']);
            $this->db->set('medico_parecer1', $_POST['medico']);
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('ambulatorio_rotinas_id', $_POST['rotinas_id']);
            $this->db->update('tb_ambulatorio_rotinas');
        } catch (Exception $exc) {
            return -1;
        }
    }

    function repetirreceituario() {
        try {

            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('texto', $_POST['laudo']);

            $this->db->set('laudo_id', $_POST['ambulatorio_laudo_id']);
            $this->db->set('medico_parecer1', $_POST['medico']);
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $_POST['medico']);
            $this->db->set('tipo', 'NORMAL');
            $this->db->where('ambulatorio_receituario_id', $_POST['receituario_id']);
            $this->db->insert('tb_ambulatorio_receituario');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function editaratestado() {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('texto', $_POST['laudo']);
//            $this->db->set('paciente_id', $_POST['paciente_id']);
//            $this->db->set('procedimento_tuss_id', $_POST['procedimento_tuss_id']);
            $this->db->set('laudo_id', $_POST['ambulatorio_laudo_id']);
            $this->db->set('medico_parecer1', $_POST['medico']);
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('ambulatorio_atestado_id', $_POST['receituario_id']);
            $this->db->update('tb_ambulatorio_atestado');
        } catch (Exception $exc) {
            return -1;
        }
    }

    function editarsolicitarexame() {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('texto', $_POST['laudo']);
//            $this->db->set('paciente_id', $_POST['paciente_id']);
//            $this->db->set('procedimento_tuss_id', $_POST['procedimento_tuss_id']);
            $this->db->set('laudo_id', $_POST['ambulatorio_laudo_id']);
            $this->db->set('medico_parecer1', $_POST['medico']);
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('ambulatorio_exame_id', $_POST['receituario_id']);
            $this->db->update('tb_ambulatorio_exame');
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravaranaminesedigitando($ambulatorio_laudo_id) {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('texto', $_POST['laudo']);
            if ($_POST['medico'] != '') {
                $this->db->set('medico_parecer1', $_POST['medico']);
            }
            if ($_POST['txtCICPrimario'] != '') {
                $this->db->set('cid', $_POST['txtCICPrimario']);
            }
            if (isset($_POST['assinatura'])) {
                $this->db->set('assinatura', 't');
            } else {
                $this->db->set('assinatura', 'f');
            }
            if (isset($_POST['rodape'])) {
                $this->db->set('rodape', 't');
            } else {
                $this->db->set('rodape', 'f');
            }
            $this->db->set('cabecalho', $_POST['cabecalho']);
            $this->db->set('situacao', 'DIGITANDO');
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('ambulatorio_laudo_id', $ambulatorio_laudo_id);
            $this->db->update('tb_ambulatorio_laudo');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarlaudotodos($ambulatorio_laudo_id) {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            if (isset($_POST['revisor'])) {
                $this->db->set('revisor', 't');
                $this->db->set('situacao_revisor', 'AGUARDANDO');
            } else {
                $this->db->set('revisor', 'f');
            }
            $this->db->set('texto_laudo', $_POST['laudo']);
            $this->db->set('texto', $_POST['laudo']);
            if ($_POST['medico'] != '') {
                $this->db->set('medico_parecer1', $_POST['medico']);
            }
            if ($_POST['medicorevisor'] != '') {
                $this->db->set('medico_parecer2', $_POST['medicorevisor']);
            } else {
                $this->db->set('medico_parecer2', null);
            }
            if (isset($_POST['assinatura'])) {
                $this->db->set('assinatura', 't');
            } else {
                $this->db->set('assinatura', 'f');
            }
            if (isset($_POST['rodape'])) {
                $this->db->set('rodape', 't');
            } else {
                $this->db->set('rodape', 'f');
            }
            $this->db->set('cabecalho', $_POST['cabecalho']);
            if (isset($_POST['imagem'])) {
                $this->db->set('imagens', $_POST['imagem']);
            }
            $this->db->set('situacao', 'FINALIZADO');
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('ambulatorio_laudo_id', $ambulatorio_laudo_id);
            $this->db->update('tb_ambulatorio_laudo');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarlaudodigitandotodos($ambulatorio_laudo_id) {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            if (isset($_POST['revisor'])) {
                $this->db->set('revisor', 't');
                $this->db->set('situacao_revisor', 'AGUARDANDO');
            } else {
                $this->db->set('revisor', 'f');
            }
            $this->db->set('texto_laudo', $_POST['laudo']);
            $this->db->set('texto', $_POST['laudo']);
            if ($_POST['medico'] != '') {
                $this->db->set('medico_parecer1', $_POST['medico']);
            }
            if ($_POST['medicorevisor'] != '') {
                $this->db->set('medico_parecer2', $_POST['medicorevisor']);
            } else {
                $this->db->set('medico_parecer2', null);
            }
            if (isset($_POST['assinatura'])) {
                $this->db->set('assinatura', 't');
            } else {
                $this->db->set('assinatura', 'f');
            }
            if (isset($_POST['rodape'])) {
                $this->db->set('rodape', 't');
            } else {
                $this->db->set('rodape', 'f');
            }
            $this->db->set('cabecalho', $_POST['cabecalho']);
            if (isset($_POST['imagem'])) {
                $this->db->set('imagens', $_POST['imagem']);
            }
            $this->db->set('situacao', 'DIGITANDO');
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('ambulatorio_laudo_id', $ambulatorio_laudo_id);
            $this->db->update('tb_ambulatorio_laudo');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarrevisao($ambulatorio_laudo_id) {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('texto_revisor', $_POST['laudo']);
            $this->db->set('texto', $_POST['laudo']);
            $this->db->set('medico_parecer2', $_POST['medicorevisor']);
            $this->db->set('situacao_revisor', $_POST['situacao']);
            $this->db->set('situacao', $_POST['situacao']);
            $this->db->set('data_revisor', $horario);
            $this->db->set('operador_revisor', $operador_id);
            $this->db->where('ambulatorio_laudo_id', $ambulatorio_laudo_id);
            $this->db->update('tb_ambulatorio_laudo');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravaralterardata($ambulatorio_laudo_id) {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $hora = date("H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $dataautorizacao = $_POST['data'] . " " . $hora;
//            var_dump($dataautorizacao);
//            die;
            $sql = "UPDATE ponto.tb_ambulatorio_laudo
                    SET data_antiga = data_cadastro
                    WHERE ambulatorio_laudo_id = $ambulatorio_laudo_id;";

            $this->db->query($sql);

//            $this->db->set('data_antiga', 'data');
//            $this->db->set('data_aterardatafaturamento', $horario);
//            $this->db->set('data_autorizacao', $dataautorizacao);
            $this->db->set('operador_alteradata', $operador_id);
            $this->db->set('data_cadastro', $_POST['data']);
            $this->db->where('ambulatorio_laudo_id', $ambulatorio_laudo_id);
            $this->db->update('tb_ambulatorio_laudo');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarrevisaodigitando($ambulatorio_laudo_id) {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('texto_revisor', $_POST['laudo']);
            $this->db->set('texto', $_POST['laudo']);
            $this->db->set('medico_parecer2', $_POST['medicorevisor']);
            $this->db->set('situacao_revisor', $_POST['situacao']);
            if ($_POST['situacao'] != 'FINALIZADO') {
                $this->db->set('situacao', $_POST['situacao']);
            } else {
                $this->db->set('situacao', 'DIGITANDO');
            }
            $this->db->set('data_revisor', $horario);
            $this->db->set('operador_revisor', $operador_id);
            $this->db->where('ambulatorio_laudo_id', $ambulatorio_laudo_id);
            $this->db->update('tb_ambulatorio_laudo');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarlaudointegracaoweb($paciente_id, $paciente_web_id, $laudo_obj) {
        try {
//            var_dump($laudo_obj);
//            die;
            $horario = date("Y-m-d H:i:s");

            $this->db->select('operador_id');
            $this->db->from('tb_operador');
            $this->db->where("ativo", 't');
            $this->db->where("cpf", $laudo_obj[0]->cpf);
            $query = $this->db->get();
            $return = $query->result();

            $this->db->select('ambulatorio_laudoweb_id');
            $this->db->from('tb_ambulatorio_laudo_integracao');
            $this->db->where("ambulatorio_laudoweb_id", $laudo_obj[0]->ambulatorio_laudo_id);
            $query2 = $this->db->get();
            $return2 = $query2->result();


            if (count($return) > 0) {
                $medico_id = $return[0]->operador_id;

//                var_dump($return2); die;
                $this->db->set('paciente_id', $paciente_id);
                $this->db->set('paciente_web_id', $paciente_web_id);
                $this->db->set('procedimento', $laudo_obj[0]->procedimento);
                $this->db->set('texto', $laudo_obj[0]->texto);
                $this->db->set('ambulatorio_laudoweb_id', $laudo_obj[0]->ambulatorio_laudo_id);
                $this->db->set('empresa', $laudo_obj[0]->empresa);
                $this->db->set('tipo', $laudo_obj[0]->tipo);
                $this->db->set('data', $laudo_obj[0]->data);
                $this->db->set('medico_id', $medico_id);
                $this->db->set('convenio', $laudo_obj[0]->convenio);

                $this->db->set('laudo_json', $_POST['laudo_json']);
                $this->db->set('paciente_json', $_POST['paciente_json']);
                if (count($return2) > 0) {
                    $this->db->set('data_atualizacao', $horario);
                    $this->db->where('ambulatorio_laudoweb_id', $laudo_obj[0]->ambulatorio_laudo_id);
                    $this->db->update('tb_ambulatorio_laudo_integracao');
                } else {
                    $this->db->set('data_cadastro', $horario);
                    $this->db->insert('tb_ambulatorio_laudo_integracao');
                }
            }

            return true;
        } catch (Exception $exc) {
            return false;
        }
    }

    function gravarexames() {
        try {
            $this->db->set('convenio_id', $_POST['convenio']);
            $this->db->where('ambulatorio_laudo_id', $_POST['txtlaudo_id']);
            $this->db->update('tb_ambulatorio_laudo');
            $i = -1;
            foreach ($_POST['procedimento'] as $procedimento_tuss_id) {
                $z = -1;
                $i++;
                foreach ($_POST['valor'] as $itemnome) {
                    $z++;
                    if ($i == $z) {
                        $valor = $itemnome;
                        break;
                    }
                }
                $hora = date("H:i:s");
                $data = date("Y-m-d");
                $this->db->set('procedimento_tuss_id', $procedimento_tuss_id);
                $this->db->set('valor', $valor);
                $this->db->set('inicio', $hora);
                $this->db->set('fim', $hora);
                $this->db->set('confirmado', 't');
                $this->db->set('ativo', 'f');
                $this->db->set('situacao', 'OK');
                $this->db->set('laudo_id', $_POST['txtlaudo_id']);
                $horario = date("Y-m-d H:i:s");
                $operador_id = $this->session->userdata('operador_id');
                $this->db->set('paciente_id', $_POST['txtpaciente_id']);
                $this->db->set('data_cadastro', $horario);
                $this->db->set('data', $data);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_agenda_exames');
                $erro = $this->db->_error_message();
                if (trim($erro) != "") { // erro de banco
                    return -1;
                } else {
                    $agenda_exames_id = $this->db->insert_id();
                }
            }
            return $agenda_exames_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    private function instanciar($ambulatorio_laudo_id) {

        if ($ambulatorio_laudo_id != 0) {
            $this->db->select('ag.ambulatorio_laudo_id,
                            ag.paciente_id,
                            ag.data_cadastro,
                            ag.texto,
                            ag.medico_parecer1,
                            ag.medico_parecer2,
                            ag.id_chamada,
                            ag.revisor,
                            ag.diabetes,
                            ag.hipertensao,
                            ag.carimbo,
                            p.nome,
                            pt.nome as procedimento,
                            p.idade,
                            age.data,
                            age.agenda_exames_id,
                            age.quantidade,
                            age.agrupador_fisioterapia,
                            age.atendimento,
                            ag.assinatura,
                            ag.cabecalho,
                            ag.situacao,
                            ag.imagens,
                            ag.rodape,
                            ag.data_cadastro,
                            o.nome as solicitante,
                            ae.sala_id,
                            pt.grupo,
                            p.nascimento,
                            pc.convenio_id,
                            
                            ag.cid,
                            ag.cid2,
                            agi.peso,
                            agi.altura,
                            agi.pasistolica,
                            agi.padiastolica,
                            agi.pulso,
                            agi.temperatura,
                            agi.pressao_arterial,
                            agi.f_respiratoria,
                            agi.spo2,
                            agi.medicacao,
                            
                            ag.inspecao_geral,
                            ag.motilidade_ocular,
                            ag.biomicroscopia,
                            ag.mapeamento_retinas,
                            ag.conduta,

                            ag.acuidade_od,
                            ag.acuidade_oe,
                            ag.pressao_ocular_oe,
                            ag.pressao_ocular_od,
                            ag.pressao_ocular_hora,

                            ag.refracao_retinoscopia,
                            ag.dinamica_estatica,
                            ag.carregar_refrator,
                            ag.carregar_oculos,

                            ag.oftamologia_od_esferico,
                            ag.oftamologia_oe_esferico,
                            ag.oftamologia_od_cilindrico,
                            ag.oftamologia_oe_cilindrico,
                            ag.oftamologia_oe_eixo,
                            ag.oftamologia_oe_av,
                            ag.oftamologia_od_eixo,
                            ag.oftamologia_od_av,
                            ag.oftamologia_ad_esferico,
                            ag.oftamologia_ad_cilindrico,
                            
                            ag.superficie_corporea,
                            ag.ve_volume_telediastolico,
                            ag.ve_volume_telessistolico,
                            ag.ve_diametro_telediastolico,
                            ag.ve_diametro_telessistolico,
                            ag.ve_indice_do_diametro_diastolico,
                            ag.ve_septo_interventricular,
                            ag.ve_parede_posterior,
                            ag.ve_relacao_septo_parede_posterior,
                            ag.ve_espessura_relativa_paredes,
                            ag.ve_massa_ventricular,
                            ag.ve_indice_massa,
                            ag.ve_relacao_volume_massa,
                            ag.ve_fracao_ejecao,
                            ag.ve_fracao_encurtamento,
                            ag.vd_diametro_telediastolico,
                            ag.vd_area_telediastolica,
                            ag.vd_diametro_pel,
                            ag.vd_diametro_basal,
                            ag.ve_volume_telessistolico,
                            ag.ae_diametro,
                            ag.ad_volume,
                            ag.ad_volume_indexado,
                            ag.ae_volume,
                            ag.ao_diametro_raiz,
                            ag.ao_relacao_atrio_esquerdo_aorta,
                            ag.dias_retorno,
                            ag.medico_encaminhamento_id,
                            ag.adendo,
                            ag.dados,

                            ag.opcoes_diagnostico,
                            ag.nivel1_diagnostico,
                            ag.nivel2_diagnostico,
                            ag.nivel3_diagnostico,


                            c.no_cid,
                            c2.no_cid as no_cid2,
                            ae.exames_id,
                            ae.indicado,
                            es.nome as sala,
                            p.sexo,
                            p.paciente_id,
                            p.logradouro,
                            p.telefone,
                            p.whatsapp,
                            p.numero,
                            p.bairro,
                            p.senha,
                            p.nome_mae,
                            p.nome_pai,
                            p.ocupacao_mae,
                            p.ocupacao_pai,
                            p.estado_civil_id,
                            p.nome_conjuge,
                            p.cpf_mae,
                            p.cpf_pai,
                            p.celular,
                            p.nascimento_conjuge,
                            cbo.descricao as profissao_cbo,
                            p.toten_fila_id,
                            ag.toten_senha_id,
                            ag.data_senha,
                            o2.nome as medico_nome,
                            o2.link_reuniao,
                            p.cpf,
                            es.toten_sala_id,
                            pi.nome as indicacao,
                            m.estado as uf,
                            m.nome as cidade,
                            age.guia_id,
                            p.informacao_laudo,
                            age.medico_solicitante,
                            age.inicio,
                            ag.primeiro_atendimento,
                            ag.laudo_modelo_id,
                            co.nome as convenio,
                            ag.situacao as situacaolaudo,
                            p.nome as paciente,
                            ag.diagnostico,
                            ag.template_obj,
                            ag.template_id,
                            p.prontuario_antigo,
                            cop.nome as convenio_paciente,
                            p.outro_convenio,
                            p.cep,
                            p.cns,
                            p.cns2,
                            opa.nome as operadorautorizacao,
                            age.data_autorizacao,
                            ag.obj_evolucao');
            $this->db->from('tb_ambulatorio_laudo ag');
            $this->db->join('tb_exames ae', 'ae.exames_id = ag.exame_id', 'left');
            $this->db->join('tb_agenda_exames age', 'age.agenda_exames_id = ae.agenda_exames_id', 'left');
            $this->db->join('tb_paciente p', 'p.paciente_id = age.paciente_id', 'left');        
            $this->db->join('tb_exame_sala es', 'es.exame_sala_id = ae.sala_id', 'left');
            $this->db->join('tb_municipio m', 'm.municipio_id = p.municipio_id', 'left');
            $this->db->join('tb_cbo_ocupacao cbo', 'cbo.cbo_ocupacao_id = p.profissao', 'left');
            $this->db->join('tb_paciente_indicacao pi', 'pi.paciente_indicacao_id = p.indicacao', 'left');
            $this->db->join('tb_ambulatorio_guia agi', 'agi.ambulatorio_guia_id = ae.guia_id', 'left');
            $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = age.procedimento_tuss_id', 'left');
            
            $this->db->join('tb_convenio co', 'co.convenio_id = pc.convenio_id', 'left');
            $this->db->join('tb_convenio cop', 'cop.convenio_id = p.convenio_id', 'left');
            $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
            $this->db->join('tb_cid c', 'c.co_cid = ag.cid', 'left');
            $this->db->join('tb_cid c2', 'c2.co_cid = ag.cid2', 'left');
            $this->db->join('tb_operador o', 'o.operador_id = age.medico_solicitante', 'left');
            $this->db->join('tb_operador o2', 'o2.operador_id = ag.medico_parecer1', 'left');
            $this->db->join('tb_operador opa', 'opa.operador_id = age.operador_autorizacao', 'left');
            $this->db->where("ambulatorio_laudo_id", $ambulatorio_laudo_id);
            $query = $this->db->get();
            $return = $query->result();
            $this->_ambulatorio_laudo_id = $ambulatorio_laudo_id;
            $this->_indicado = $return[0]->indicado;
            $this->_indicacao = $return[0]->indicacao;
            $this->_situacaolaudo = $return[0]->situacaolaudo;
            $this->_agenda_exames_id = $return[0]->agenda_exames_id;
            $this->_carimbo = $return[0]->carimbo;
            $this->_atendimento = $return[0]->atendimento;
            $this->_paciente_id = $return[0]->paciente_id;
            $this->_cidade = $return[0]->cidade;
            $this->_inicio = $return[0]->inicio;
            $this->_cpf = $return[0]->cpf;
            $this->_cpf_mae = $return[0]->cpf_mae;
            $this->_cpf_pai = $return[0]->cpf_pai;
            $this->_celular = $return[0]->celular;
            $this->_laudo_modelo_id = $return[0]->laudo_modelo_id;
            $this->_link_reuniao = $return[0]->link_reuniao;
            $this->_profissao_cbo = $return[0]->profissao_cbo;
            $this->_estado_civil_id = $return[0]->estado_civil_id;
            $this->_logradouro = $return[0]->logradouro;
            $this->_numero = $return[0]->numero;
            $this->_informacao_laudo = $return[0]->informacao_laudo;
            $this->_id_chamada = $return[0]->id_chamada;
            $this->_bairro = $return[0]->bairro;
            $this->_medico_solicitante = $return[0]->medico_solicitante;
            $this->_primeiro_atendimento = $return[0]->primeiro_atendimento;
            $this->_adendo = $return[0]->adendo;
            $this->_template_obj = $return[0]->template_obj;
            $this->_template_id = $return[0]->template_id;
            $this->_uf = $return[0]->uf;
            $this->_outro_convenio = $return[0]->outro_convenio;
            $this->_cep = $return[0]->cep;
            $this->_email = $return[0]->cns;
            $this->_email2 = $return[0]->cns2;
            $this->_data_cadastro = $return[0]->data_cadastro;
            $this->_telefone = $return[0]->telefone;
            $this->_whatsapp = $return[0]->whatsapp;
            $this->_texto = $return[0]->texto;
            $this->_dados = $return[0]->dados;
            $this->_medico_parecer1 = $return[0]->medico_parecer1;
            $this->_medico_parecer2 = $return[0]->medico_parecer2;
            $this->_revisor = $return[0]->revisor;
            $this->_grupo = $return[0]->grupo;
            $this->_convenio_paciente = $return[0]->convenio_paciente;
            $this->_sala = $return[0]->sala;
            $this->_sala_id = $return[0]->sala_id;
            $this->_guia_id = $return[0]->guia_id;
            $this->_nome = $return[0]->nome;
            $this->_senha = $return[0]->senha;
            $this->_toten_fila_id = $return[0]->toten_fila_id;
            $this->_toten_senha_id = $return[0]->toten_senha_id;
            $this->_data_senha = $return[0]->data_senha;
            $this->_medico_nome = $return[0]->medico_nome;
            $this->_toten_sala_id = $return[0]->toten_sala_id;
            $this->_nome_conjuge = $return[0]->nome_conjuge;
            $this->_nascimento_conjuge = $return[0]->nascimento_conjuge;
            ///////////// OFTAMOLOGIA////////////
            $this->_oftamologia_od_esferico = $return[0]->oftamologia_od_esferico;
            $this->_oftamologia_oe_esferico = $return[0]->oftamologia_oe_esferico;
            $this->_oftamologia_od_cilindrico = $return[0]->oftamologia_od_cilindrico;
            $this->_oftamologia_oe_cilindrico = $return[0]->oftamologia_oe_cilindrico;
            $this->_oftamologia_oe_eixo = $return[0]->oftamologia_oe_eixo;
            $this->_oftamologia_oe_av = $return[0]->oftamologia_oe_av;
            $this->_oftamologia_od_eixo = $return[0]->oftamologia_od_eixo;
            $this->_oftamologia_od_av = $return[0]->oftamologia_od_av;
            $this->_oftamologia_ad_esferico = $return[0]->oftamologia_ad_esferico;
            $this->_oftamologia_ad_cilindrico = $return[0]->oftamologia_ad_cilindrico;
            $this->_inspecao_geral = $return[0]->inspecao_geral;
            $this->_motilidade_ocular = $return[0]->motilidade_ocular;
            $this->_biomicroscopia = $return[0]->biomicroscopia;
            $this->_mapeamento_retinas = $return[0]->mapeamento_retinas;
            $this->_conduta = $return[0]->conduta;
            $this->_acuidade_od = $return[0]->acuidade_od;
            $this->_acuidade_oe = $return[0]->acuidade_oe;
            $this->_pressao_ocular_oe = $return[0]->pressao_ocular_oe;
            $this->_pressao_ocular_od = $return[0]->pressao_ocular_od;
            $this->_pressao_ocular_hora = $return[0]->pressao_ocular_hora;
            $this->_refracao_retinoscopia = $return[0]->refracao_retinoscopia;
            $this->_dinamica_estatica = $return[0]->dinamica_estatica;
            $this->_carregar_refrator = $return[0]->carregar_refrator;
            $this->_carregar_oculos = $return[0]->carregar_oculos;
            /////////////FIM OFTAMOLOGIA////////////
            $this->_idade = $return[0]->idade;
            $this->_status = $return[0]->situacao;
            $this->_agrupador_fisioterapia = $return[0]->agrupador_fisioterapia;
            $this->_procedimento = $return[0]->procedimento;
            $this->_solicitante = $return[0]->solicitante;
            $this->_nascimento = $return[0]->nascimento;
            $this->_assinatura = $return[0]->assinatura;
            $this->_rodape = $return[0]->rodape;
            $this->_cabecalho = $return[0]->cabecalho;
            $this->_quantidade = $return[0]->quantidade;
            $this->_convenio = $return[0]->convenio;
            $this->_sexo = $return[0]->sexo;
            $this->_imagens = $return[0]->imagens;
            $this->_diabetes = $return[0]->diabetes;
            $this->_hipertensao = $return[0]->hipertensao;
            $this->_cid = $return[0]->cid;
            $this->_ciddescricao = $return[0]->no_cid;
            $this->_cid2 = $return[0]->cid2;
            $this->_cid2descricao = $return[0]->no_cid2;
            $this->_peso = $return[0]->peso;
            $this->_altura = $return[0]->altura;
            $this->_pulso = $return[0]->pulso;
            $this->_temperatura = $return[0]->temperatura;
            $this->_pressao_arterial = $return[0]->pressao_arterial;
            $this->_f_respiratoria = $return[0]->f_respiratoria;
            $this->_spo2 = $return[0]->spo2;
            $this->_medicacao = $return[0]->medicacao;

            $this->_pasistolica = $return[0]->pasistolica;
            $this->_padiastolica = $return[0]->padiastolica;
            if ($return[0]->peso != 0 && $return[0]->altura != 0) {
                $this->_superficie_corporea = sqrt(($return[0]->peso * $return[0]->altura) / 3600);
            } else {
                $this->_superficie_corporea = $return[0]->superficie_corporea;
            }
            $this->_ve_volume_telediastolico = $return[0]->ve_volume_telediastolico;
            $this->_ve_volume_telessistolico = $return[0]->ve_volume_telessistolico;
            $this->_ve_diametro_telediastolico = $return[0]->ve_diametro_telediastolico;
            $this->_ve_diametro_telessistolico = $return[0]->ve_diametro_telessistolico;
            $this->_ve_indice_do_diametro_diastolico = $return[0]->ve_indice_do_diametro_diastolico;
            $this->_ve_septo_interventricular = $return[0]->ve_septo_interventricular;
            $this->_ve_parede_posterior = $return[0]->ve_parede_posterior;
            $this->_ve_relacao_septo_parede_posterior = $return[0]->ve_relacao_septo_parede_posterior;
            $this->_ve_espessura_relativa_paredes = $return[0]->ve_espessura_relativa_paredes;
            $this->_ve_massa_ventricular = $return[0]->ve_massa_ventricular;
            $this->_ve_indice_massa = $return[0]->ve_indice_massa;
            $this->_ve_relacao_volume_massa = $return[0]->ve_relacao_volume_massa;
            $this->_ve_fracao_ejecao = $return[0]->ve_fracao_ejecao;
            $this->_ve_fracao_encurtamento = $return[0]->ve_fracao_encurtamento;
            $this->_vd_diametro_telediastolico = $return[0]->vd_diametro_telediastolico;
            $this->_vd_area_telediastolica = $return[0]->vd_area_telediastolica;
            $this->_vd_diametro_pel = $return[0]->vd_diametro_pel;
            $this->_vd_diametro_basal = $return[0]->vd_diametro_basal;
            $this->_ae_diametro = $return[0]->ae_diametro;
            $this->_ae_volume = $return[0]->ae_volume;
            $this->_ad_volume = $return[0]->ad_volume;
            $this->_ad_volume_indexado = $return[0]->ad_volume_indexado;
            $this->_ao_diametro_raiz = $return[0]->ao_diametro_raiz;
            $this->_ao_relacao_atrio_esquerdo_aorta = $return[0]->ao_relacao_atrio_esquerdo_aorta;
            $this->_dias_retorno = $return[0]->dias_retorno;
            $this->_medico_encaminhamento_id = $return[0]->medico_encaminhamento_id;
            $this->_convenio_id = $return[0]->convenio_id;
            $this->_nome_pai = $return[0]->nome_pai;
            $this->_nome_mae = $return[0]->nome_mae;
            $this->_ocupacao_mae = $return[0]->ocupacao_mae;
            $this->_ocupacao_pai = $return[0]->ocupacao_pai;
            $this->_diagnostico = $return[0]->diagnostico;
            $this->_prontuario_antigo = $return[0]->prontuario_antigo;

            $this->_opcoes_diagnostico = $return[0]->opcoes_diagnostico;
            $this->_nivel1_diagnostico = $return[0]->nivel1_diagnostico;
            $this->_nivel2_diagnostico = $return[0]->nivel2_diagnostico;
            $this->_nivel3_diagnostico = $return[0]->nivel3_diagnostico;
            $this->_data_autorizacao = $return[0]->data_autorizacao;
            $this->_obj_evolucao = $return[0]->obj_evolucao;
        } else {
            $this->_ambulatorio_laudo_id = null;
        }
    }

    function listarholter() {

        $sql = $this->db->select('');
        $this->db->from('tb_laudo_holter');

        $this->db->order_by('laudo_holter_id');
        return $sql->get()->result();
    }

    function listarexames() {
        $sql = $this->db->select('pt.nome, p.nome as paciente,pt.grupo,ae.tipo,ae.agenda_exames_id');
        $this->db->from('tb_empresa_associacoes_grupo as ea');
        $this->db->join('tb_ambulatorio_grupo as ag', 'ag.ambulatorio_grupo_id = ea.ambulatorio_grupo_id');
        $this->db->join('tb_procedimento_tuss as pt', 'pt.grupo = ag.nome');
        $this->db->join('tb_procedimento_convenio as pc', 'pc.procedimento_tuss_id = pt.procedimento_tuss_id');
        $this->db->join('tb_agenda_exames as ae', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id');
        $this->db->join('tb_paciente as p', 'p.paciente_id = ae.paciente_id');
        $this->db->where('ea.tela_atendimento', 'holter');
        $this->db->where('ea.ativo', 't');

        $this->db->where('pt.ativo', 't');
//        $this->db->where('ae.ativo', 't');
        return $sql->get()->result();
    }

    function listarexamesecostress() {
        $sql = $this->db->select('pt.nome, p.nome as paciente,pt.grupo,ae.tipo,ae.agenda_exames_id');
        $this->db->from('tb_empresa_associacoes_grupo as ea');
        $this->db->join('tb_ambulatorio_grupo as ag', 'ag.ambulatorio_grupo_id = ea.ambulatorio_grupo_id');
        $this->db->join('tb_procedimento_tuss as pt', 'pt.grupo = ag.nome');
        $this->db->join('tb_procedimento_convenio as pc', 'pc.procedimento_tuss_id = pt.procedimento_tuss_id');
        $this->db->join('tb_agenda_exames as ae', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id');
        $this->db->join('tb_paciente as p', 'p.paciente_id = ae.paciente_id');
        $this->db->where('ea.tela_atendimento', 'ecostress');
        $this->db->where('ea.ativo', 't');

        $this->db->where('pt.ativo', 't');
//        $this->db->where('ae.ativo', 't');
        return $sql->get()->result();
    }

    function listarecostress() {

        $sql = $this->db->select('');

        $this->db->from('tb_laudo_ecostress');



        return $sql->get()->result();
    }

    function pacienteemails($paciente_id){
        $this->db->select('cns, cns2, nome, paciente_id');
        $this->db->from('tb_paciente');
        $this->db->where('paciente_id', $paciente_id);

        return $this->db->get()->result();
    }

    function listalaudosantigos(){
        $this->db->select('paciente_id, ambulatorio_laudo_id, exame_id, tipo');
        $this->db->from('tb_ambulatorio_laudo');
        $this->db->where('tipo', 'EXAME');
        $this->db->where('situacao', 'FINALIZADO');

        return $this->db->get()->result();
    }

    function salvararquivolaudo($paciente_id, $nome, $laudo_id, $medico_id){
        $this->db->select('laudo_id');
        $this->db->from('tb_ambulatorio_arquivos_anexados');
        $this->db->where('laudo_id', $laudo_id);
        $this->db->where('caminho', 'upload/laudopdf/'.$laudo_id.'/'.$nome);
        $return = $this->db->get()->result();
        
        if(count($return) > 0){

        }else{
            $operador_id = $this->session->userdata('operador_id');
            $horario = date("Y-m-d H:i:s");
    
            $this->db->set('paciente_id', $paciente_id);
            $this->db->set('nome', $nome);
            $this->db->set('caminho', 'upload/laudopdf/'.$laudo_id.'/'.$nome);
            $this->db->set('laudo_id', $laudo_id);
            $this->db->set('medico_id', $medico_id);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_ambulatorio_arquivos_anexados');
        }
    }

    function atualizaremail($paciente_id, $email = '', $email2 = ''){
        if($email != ""){
            $this->db->set('cns', $email);
           }else{
             $this->db->set('cns', null);
           }

           if($email2 != ""){
               $this->db->set('cns2', $email2);
              }else{
                $this->db->set('cns2', null);
              }
        $this->db->where('paciente_id', $paciente_id);
        $this->db->update('tb_paciente');
    }


    function salvaralteracaoemail($paciente_id){
        if($_POST['cns'] != ""){
            $this->db->set('cns', $_POST['cns']);
           }else{
             $this->db->set('cns', null);
           }

           if($_POST['cns2'] != ""){
               $this->db->set('cns2', $_POST['cns2']);
              }else{
                $this->db->set('cns2', null);
              }
        $this->db->where('paciente_id', $paciente_id);
        $this->db->update('tb_paciente');
    }

    function listarcateterismo() {

        $sql = $this->db->select('');

        $this->db->from('tb_laudo_cate');



        return $sql->get()->result();
    }

    function listarexamescateterismo() {
        $sql = $this->db->select('pt.nome, p.nome as paciente,pt.grupo,ae.tipo,ae.agenda_exames_id');
        $this->db->from('tb_empresa_associacoes_grupo as ea');
        $this->db->join('tb_ambulatorio_grupo as ag', 'ag.ambulatorio_grupo_id = ea.ambulatorio_grupo_id');
        $this->db->join('tb_procedimento_tuss as pt', 'pt.grupo = ag.nome');
        $this->db->join('tb_procedimento_convenio as pc', 'pc.procedimento_tuss_id = pt.procedimento_tuss_id');
        $this->db->join('tb_agenda_exames as ae', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id');
        $this->db->join('tb_paciente as p', 'p.paciente_id = ae.paciente_id');
        $this->db->where('ea.tela_atendimento', 'cate');
        $this->db->where('ea.ativo', 't');
        $this->db->where('pt.ativo', 't');

//      $this->db->where('ae.ativo', 't');
        return $sql->get()->result();
    }

    function listarcintil() {

        $sql = $this->db->select('');

        $this->db->from('tb_laudo_cintil');


        return $sql->get()->result();
    }

    function listarexamescintil() {
        $sql = $this->db->select('pt.nome, p.nome as paciente,pt.grupo,ae.tipo,ae.agenda_exames_id');
        $this->db->from('tb_empresa_associacoes_grupo as ea');
        $this->db->join('tb_ambulatorio_grupo as ag', 'ag.ambulatorio_grupo_id = ea.ambulatorio_grupo_id');
        $this->db->join('tb_procedimento_tuss as pt', 'pt.grupo = ag.nome');
        $this->db->join('tb_procedimento_convenio as pc', 'pc.procedimento_tuss_id = pt.procedimento_tuss_id');
        $this->db->join('tb_agenda_exames as ae', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id');
        $this->db->join('tb_paciente as p', 'p.paciente_id = ae.paciente_id');
        $this->db->where('ea.tela_atendimento', 'cintil');
        $this->db->where('ea.ativo', 't');
        $this->db->where('pt.ativo', 't');
//      $this->db->where('ae.ativo', 't');


        return $sql->get()->result();
    }

    function listarmapa() {

        $sql = $this->db->select('');

        $this->db->from('tb_laudo_mapa');


        return $sql->get()->result();
    }

    function listarexamesmapa() {
        $sql = $this->db->select('pt.nome, p.nome as paciente,pt.grupo,ae.tipo,ae.agenda_exames_id');
        $this->db->from('tb_empresa_associacoes_grupo as ea');
        $this->db->join('tb_ambulatorio_grupo as ag', 'ag.ambulatorio_grupo_id = ea.ambulatorio_grupo_id');
        $this->db->join('tb_procedimento_tuss as pt', 'pt.grupo = ag.nome');
        $this->db->join('tb_procedimento_convenio as pc', 'pc.procedimento_tuss_id = pt.procedimento_tuss_id');
        $this->db->join('tb_agenda_exames as ae', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id');
        $this->db->join('tb_paciente as p', 'p.paciente_id = ae.paciente_id');
        $this->db->where('ea.tela_atendimento', 'mapa');
        $this->db->where('ea.ativo', 't');
        $this->db->where('pt.ativo', 't');
//      $this->db->where('ae.ativo', 't');

        return $sql->get()->result();
    }

    function listarecocardiograma() {

        $sql = $this->db->select('');

        $this->db->from('tb_laudo_ecocardio');

        return $sql->get()->result();
    }

    function listarexamesecocardiograma() {
        $sql = $this->db->select('pt.nome, p.nome as paciente,pt.grupo,ae.tipo,ae.agenda_exames_id');
        $this->db->from('tb_empresa_associacoes_grupo as ea');
        $this->db->join('tb_ambulatorio_grupo as ag', 'ag.ambulatorio_grupo_id = ea.ambulatorio_grupo_id');
        $this->db->join('tb_procedimento_tuss as pt', 'pt.grupo = ag.nome');
        $this->db->join('tb_procedimento_convenio as pc', 'pc.procedimento_tuss_id = pt.procedimento_tuss_id');
        $this->db->join('tb_agenda_exames as ae', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id');
        $this->db->join('tb_paciente as p', 'p.paciente_id = ae.paciente_id');
        $this->db->where('ea.tela_atendimento', 'eco');
        $this->db->where('ea.ativo', 't');
        $this->db->where('pt.ativo', 't');
//      $this->db->where('ae.ativo', 't');

        return $sql->get()->result();
    }

    function gravarhora_agendamento() {

        if ($_POST['turno'] != "manha" && $_POST['turno'] != "tarde") {
            return -5;
        }

        $operador_id = $this->session->userdata('operador_id');
        $horario = date("Y-m-d H:i:s");
        $data = date("Y-m-d");

//        $this->db->select('*');
//        $this->db->from('tb_exame_sala');
//        $this->db->where('exame_sala_id', $_POST['sala_id']);
//        $return = $this->db->get();
//        $result = $return->result();

        $this->db->select('sum(tempo_atendimento) as tempo_consumido');
        $this->db->from('tb_hora_agendamento lh');
        $this->db->where('lh.data', date("Y-m-d", strtotime(str_replace("/", "-", $_POST['data']))));
//        $this->db->where('lh.sala_id', $_POST['sala_id']);
        $this->db->where('lh.ativo', 't');
//        $this->db->groupby('lh.sala_id');
        $return2 = $this->db->get();
        $result2 = $return2->result();
        // Descobrindo a quantidade de minutos que tem naquela sala por dia
//        list($h_inicio, $m_inicio) = explode(':', @$result[0]->hora_inicio);
//        list($h_fim, $m_fim) = explode(':', @$result[0]->hora_fim);
//        $minutos_inicio = $h_inicio * 60 + $m_inicio;
//        $minutos_fim = $h_fim * 60 + $m_fim;
//        $tempo_total = $minutos_fim - $minutos_inicio;
        /////////////////////////////////////////
        // Tempo necessário para o atendimento

        list($h_necessario, $m_necessario) = explode(':', $_POST['tempo_atendimento']);
        $tempo_necessario = $h_necessario * 60 + $m_necessario;
        //////////////////////////
        // Tempo Consumido durante o dia
        $tempo_consumido = 0;
        if (count(@$result2) > 0) {
            @list($h_consumido, $m_consumido) = explode(':', @$result2[0]->tempo_consumido);
            $tempo_consumido = $h_consumido * 60 + $m_consumido;
        }
        ////////////////////////////
        // Tempo Restante
//        $tempo_restante = $tempo_total - $tempo_consumido;
        // echo '<pre>';
        // var_dump($result2); 
        // var_dump('Tempo Total: ', $tempo_total); 
        // var_dump('Tempo Consumido: ', $tempo_consumido); 
        // var_dump('Tempo Restante: ', $tempo_restante); 
        // var_dump('Tempo Necessario: ', $tempo_necessario); 
        // die;        
//        if ($tempo_restante < $tempo_necessario) {
//            return -1;
//        } 
////////////////////////////  
////////PEGANDO O HORARIO TOTAL QUE O MEDICO PODE FAZER EM UM DIA DE TRABALHO



        $datatest = date("d-m-Y", strtotime(str_replace('/', '-', $_POST['data'])));
        $dia = $datatest;
        $diaa = substr($dia, 0, 2) . "-";
        $mes = substr($dia, 3, 2) . "-";
        $ano = substr($dia, 6, 4);

        @$diasemana = date("w", mktime(0, 0, 0, $mes, $diaa, $ano));

        switch ($diasemana) {

            case"0": $dia_semana = "domingo";
                break;

            case"1": $dia_semana = "segunda";
                break;

            case"2": $dia_semana = "terca";
                break;

            case"3": $dia_semana = "quarta";
                break;

            case"4": $dia_semana = "quinta";
                break;

            case"5": $dia_semana = "sexta";
                break;

            case"6": $dia_semana = "sabado";
                break;
        }




        if ($dia_semana == "segunda") {

            $this->db->select('seg_manha as hora_manha,seg_tarde as hora_tarde');
            $this->db->from('tb_operador');
            $this->db->where('operador_id', $_POST['medico_id']);
            $return_hora_dia = $this->db->get()->result();
        } elseif ($dia_semana == "terca") {

            $this->db->select('ter_manha as hora_manha,ter_tarde as hora_tarde');
            $this->db->from('tb_operador');
            $this->db->where('operador_id', $_POST['medico_id']);
            $return_hora_dia = $this->db->get()->result();
        } elseif ($dia_semana == "quarta") {


            $this->db->select('qua_manha as hora_manha,qua_tarde as hora_tarde');
            $this->db->from('tb_operador');
            $this->db->where('operador_id', $_POST['medico_id']);
            $return_hora_dia = $this->db->get()->result();
        } elseif ($dia_semana == "quinta") {


            $this->db->select('qui_manha as hora_manha,qui_tarde as hora_tarde');
            $this->db->from('tb_operador');
            $this->db->where('operador_id', $_POST['medico_id']);



            $return_hora_dia = $this->db->get()->result();
        } elseif ($dia_semana == "sexta") {
            $this->db->select('sex_manha as hora_manha,sex_tarde as hora_tarde');
            $this->db->from('tb_operador');
            $this->db->where('operador_id', $_POST['medico_id']);

            $return_hora_dia = $this->db->get()->result();
        } elseif ($dia_semana == "sabado") {

            $this->db->select('sab_manha as hora_manha,sab_tarde as hora_tarde');
            $this->db->from('tb_operador');
            $this->db->where('operador_id', $_POST['medico_id']);
            $return_hora_dia = $this->db->get()->result();
        } else {


            $this->db->select('sex_manha as hora_manha,sex_tarde as hora_tarde');
            $this->db->from('tb_operador');
            $this->db->where('operador_id', null);
            $return_hora_dia = $this->db->get()->result();
        }




////////////////////////////         
////////SOMANDO TODOS OS TEMPOS JÁ USADOS PELO MEDICO NO DIA SELECIONADO
        $this->db->select('sum(tempo_atendimento) as tempo_consumido');
        $this->db->from('tb_hora_agendamento lh');
        $this->db->where('lh.data', date("Y-m-d", strtotime(str_replace("/", "-", $_POST['data']))));
        $this->db->where('lh.ativo', 't');
        $this->db->where('lh.medico_id', $_POST['medico_id']);
//      $this->db->groupby('lh.sala_id');
        $return_hora_consumida = $this->db->get()->result();
////////////////////////////      
////////TRANSFORMANDO HORAS ENVIADA PELO POST (tempo_atendimento) EM MINUTOS
        list($h_post, $m_post) = explode(':', $_POST['tempo_atendimento']);
        $tempo_total_post = $h_post * 60 + $m_post;
////////////////////////////                   
//////// TRANSFORMANDO HORAS (tempo_consumido) EM MINUTOS       
        if (@$return_hora_consumida[0]->tempo_consumido > 0) {
            list($h_consumido, $m_consumido) = explode(':', $return_hora_consumida[0]->tempo_consumido);
            $tempo_total_consumido = $h_consumido * 60 + $m_consumido;
        }
////////////////////////////                  
////////TRANSFORMANDO HORAS (hora_manha) E (hora_tarde)  EM MINUTOS  
        list($h_manha, $m_manha) = explode(':', $return_hora_dia[0]->hora_manha);
        list($h_tarde, $m_tarde) = explode(':', $return_hora_dia[0]->hora_tarde);
        $minutos_inicio = $h_manha * 60 + $m_manha;
        $minutos_fim = $h_tarde * 60 + $m_tarde;
        $tempo_total = $minutos_fim + $minutos_inicio;

////////////////////////////          
////////TRANSFORMANDO HORAS (hora_manha) EM MINUTOS  
        list($h_manha, $m_manha) = explode(':', $return_hora_dia[0]->hora_manha);
        $tempo_total_manha = $h_manha * 60 + $m_manha;
        $this->db->select('sum(tempo_atendimento) as tempo_consumido');
        $this->db->from('tb_hora_agendamento lh');
        $this->db->where('lh.data', date("Y-m-d", strtotime(str_replace("/", "-", $_POST['data']))));
        $this->db->where('lh.ativo', 't');
        $this->db->where('lh.turno', 'manha');
        $this->db->where('lh.medico_id', $_POST['medico_id']);
//        $this->db->groupby('lh.sala_id');
        $return_hora_consumida_manha = $this->db->get()->result();
        echo "HORAS CONSUMIDA DE AMANHA<pre>";
//        print_r($return_hora_consumida_manha);


        if (@$return_hora_consumida_manha[0]->tempo_consumido > 0) {
            list($h_consumido, $m_consumido) = explode(':', @$return_hora_consumida_manha[0]->tempo_consumido);
            $tempo_total_consumido_manha = $h_consumido * 60 + $m_consumido;
        } else {
            
        }




////////////////////////////          
////////TRANSFORMANDO HORAS (hora_tarde) EM MINUTOS  
        list($h_tarde, $m_tarde) = explode(':', $return_hora_dia[0]->hora_tarde);
        $tempo_total_tarde = $h_tarde * 60 + $m_tarde;

        $this->db->select('sum(tempo_atendimento) as tempo_consumido');
        $this->db->from('tb_hora_agendamento lh');
        $this->db->where('lh.data', date("Y-m-d", strtotime(str_replace("/", "-", $_POST['data']))));
        $this->db->where('lh.ativo', 't');
        $this->db->where('lh.turno', 'tarde');
        $this->db->where('lh.medico_id', $_POST['medico_id']);
//        $this->db->groupby('lh.sala_id');
        $return_hora_consumida_tarde = $this->db->get()->result();
        echo "HORAS CONSUMIDAS NA TARDE<pre>";
//        print_r($return_hora_consumida_tarde);


        if (@$return_hora_consumida_tarde[0]->tempo_consumido > 0) {
            @list($h_consumido, $m_consumido) = explode(':', @$return_hora_consumida_tarde[0]->tempo_consumido);
            $tempo_total_consumido_tarde = $h_consumido * 60 + $m_consumido;
        } else {
            
        }


//////////////////////////// 
////////VERIFICANDO SE O (TEMPO CONSUMIDO NO DIA + O QUE FOI ENVIADO PELO POST) É MAIOR QUE O TEMPO QUE O MEDICO PODE TRABALHAR
////////DURANDO UM DIA
        if ((@$tempo_total_consumido + @$tempo_total_post) > @$tempo_total) {
//            echo "fechar";
            return -2;
        } else {

            if ($_POST['turno'] == "manha") {

                if ((@$tempo_total_consumido_manha + @$tempo_total_post) > @$tempo_total_manha) {
                    return -3;
//                    echo "horario manha cheio"; 
                } else {
                    $this->db->set('turno', 'manha');
//                    echo "enviar manha";
                }
            }


            if ($_POST['turno'] == "tarde") {

                if ((@$tempo_total_consumido_tarde + @$tempo_total_post) > @$tempo_total_tarde) {
//                    echo "horario tarde cheio";
                    return -4;
                } else {
                    $this->db->set('turno', 'tarde');
//                    echo "enviar tarde";
                }
            }


            $this->db->set('paciente_id', $_POST['paciente_id']);
            $this->db->set('guia_id', $_POST['guia_id']);
            $this->db->set('tempo_atendimento', $_POST['tempo_atendimento']);
            $this->db->set('observacao', $_POST['observacao']);
//            $this->db->set('sala_id', $_POST['sala_id']);
            $this->db->set('medico_id', $_POST['medico_id']);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('data', date("Y-m-d", strtotime(str_replace("/", "-", $_POST['data']))));
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->set('ativo', 't');
            $this->db->set('medicamentos', $_POST['medicamentos']);
            $this->db->insert('tb_hora_agendamento');
            return 0;
        }
    }

    function listarhorasala($sala_id = NULL) {


        $sql = $this->db->select('');
        $this->db->from('tb_exame_sala');
        $this->db->where('exame_sala_id', $sala_id);
        return $sql->get()->result();
    }

    function listaragendaatendimentos($sala_id = NULL) {

        $sql = $this->db->select('hg.*,p.nome, es.nome as sala');


        $this->db->from('tb_hora_agendamento hg');
        $this->db->join('tb_paciente as p', 'p.paciente_id = hg.paciente_id', 'left');
        $this->db->join('tb_exame_sala es', 'es.exame_sala_id = hg.sala_id', 'left');
        // $this->db->where('hg.sala_id', $sala_id);
        $this->db->where('hg.ativo', 't');
        $this->db->orderby('hg.data desc');
        $this->db->orderby('hg.data_cadastro');


        return $sql->get()->result();
    }

    function listarpermissao($empresa_id = NULL) {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ep.*');
        $this->db->from('tb_empresa_permissoes ep');

        $this->db->where('ep.empresa_id', $empresa_id);
        return $this->db->get()->result();
    }

    function desativarhoraagenda($hora_agendamento_id = NULL) {

        $operador_id = $this->session->userdata('operador_id');
        $horario = date("Y-m-d H:i:s");

        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->set('ativo', 'f');
        $this->db->where('hora_agendamento_id', $hora_agendamento_id);
        $this->db->update('tb_hora_agendamento');
    }

    function confirmarhoraagenda($hora_agendamento_id) {
        $operador_id = $this->session->userdata('operador_id');
        $horario = date("Y-m-d H:i:s");
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->set('situacao', 't');
        $this->db->where('hora_agendamento_id', $hora_agendamento_id);
        $this->db->update('tb_hora_agendamento');
    }

    function atualizarsala() {

        try {

            $operador_id = $this->session->userdata('operador_id');
            $horario = date("Y-m-d H:i:s");
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->set('sala_id', $_POST['sala']);
            $this->db->where('exames_id', $_POST['exame_id']);
            $this->db->update('tb_exames');

            $this->db->select('e.*');
            $this->db->from('tb_exames e');
            $this->db->where('e.exames_id', $_POST['exame_id']);
            $agenda = $this->db->get()->result();

            if (count($agenda) > 0) {
                $agenda_exames_id = $agenda[0]->agenda_exames_id;

                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $this->db->set('agenda_exames_nome_id', $_POST['sala']);
                $this->db->where('agenda_exames_id', $agenda_exames_id);
                $this->db->update('tb_agenda_exames');
            }
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function listarmedico($medico_id = NULL) {
        $sql = $this->db->select('o.hora_tarde,o.hora_manha');
        $this->db->from('tb_operador as o');
        $this->db->where('o.operador_id', $medico_id);
        return $this->db->get()->result();
    }

    function listarpoltronascomplete($data = NULL, $medico_id = NULL) {
        $this->db->select('op.cor_mapa,p.nome as paciente,op.nome as medico,hg.turno,hg.tempo_atendimento,op.operador_id,hg.tempo_atendimento,op.hora_manha,op.hora_tarde,p.paciente_id,p.cpf,p.toten_fila_id,hg.hora_agendamento_id,hg.exame_sala_id,es.toten_sala_id, op.id_totem');
        $this->db->from('tb_hora_agendamento as hg');
        $this->db->join('tb_operador as op', 'op.operador_id = hg.medico_id');
        $this->db->join('tb_paciente as p', 'p.paciente_id = hg.paciente_id', 'left');
        $this->db->join('tb_exame_sala as es', 'es.exame_sala_id = hg.exame_sala_id', 'left');
        $this->db->where('hg.ativo', 't');
//      $this->db->where('op.operador_id', $medico_id);
        $this->db->where("hg.data", date("Y-m-d", strtotime(str_replace('/', '-', $data))));
        return $this->db->get()->result();
    }

    function listarpoltronascompletemanha($data = NULL, $medico_id = NULL) {
        $datatest = date("d-m-Y", strtotime(str_replace('/', '-', $data)));

        $dia = $datatest;
        $diaa = substr($dia, 0, 2) . "-";

        $mes = substr($dia, 3, 2) . "-";

        $ano = substr($dia, 6, 4);

        @$diasemana = date("w", mktime(0, 0, 0, $mes, $diaa, $ano));

        switch ($diasemana) {

            case"0": $dia_semana = "domingo";
                break;

            case"1": $dia_semana = "segunda";
                break;

            case"2": $dia_semana = "terca";
                break;

            case"3": $dia_semana = "quarta";
                break;

            case"4": $dia_semana = "quinta";
                break;

            case"5": $dia_semana = "sexta";
                break;

            case"6": $dia_semana = "sabado";
                break;
        }


        if ($dia_semana == "segunda") {

            $this->db->select('op.nome as medico,hg.turno,hg.tempo_atendimento,op.operador_id,hg.tempo_atendimento,op.seg_manha as hora_manha,op.seg_tarde as hora_tarde');
            $this->db->from('tb_hora_agendamento as hg');
            $this->db->join('tb_operador as op', 'op.operador_id = hg.medico_id');
            $this->db->where('hg.turno', 'manha');
            $this->db->where('hg.ativo', 't');
            $this->db->where('op.operador_id', $medico_id);
            $this->db->where("hg.data", date("Y-m-d", strtotime(str_replace('/', '-', $data))));
            return $this->db->get()->result();
        } elseif ($dia_semana == "terca") {


            $this->db->select('op.nome as medico,hg.turno,hg.tempo_atendimento,op.operador_id,hg.tempo_atendimento,op.ter_manha as hora_manha,op.ter_tarde as hora_tarde');
            $this->db->from('tb_hora_agendamento as hg');
            $this->db->join('tb_operador as op', 'op.operador_id = hg.medico_id');
            $this->db->where('hg.turno', 'manha');
            $this->db->where('hg.ativo', 't');
            $this->db->where('op.operador_id', $medico_id);
            $this->db->where("hg.data", date("Y-m-d", strtotime(str_replace('/', '-', $data))));
            return $this->db->get()->result();
        } elseif ($dia_semana == "quarta") {


            $this->db->select('op.nome as medico,hg.turno,hg.tempo_atendimento,op.operador_id,hg.tempo_atendimento,op.qua_manha as hora_manha,op.qua_tarde as hora_tarde');
            $this->db->from('tb_hora_agendamento as hg');
            $this->db->join('tb_operador as op', 'op.operador_id = hg.medico_id');
            $this->db->where('hg.turno', 'manha');
            $this->db->where('hg.ativo', 't');
            $this->db->where('op.operador_id', $medico_id);
            $this->db->where("hg.data", date("Y-m-d", strtotime(str_replace('/', '-', $data))));
            return $this->db->get()->result();
        } elseif ($dia_semana == "quinta") {

            $this->db->select('op.nome as medico,hg.turno,hg.tempo_atendimento,op.operador_id,hg.tempo_atendimento,op.qui_manha as hora_manha,op.qui_tarde as hora_tarde');
            $this->db->from('tb_hora_agendamento as hg');
            $this->db->join('tb_operador as op', 'op.operador_id = hg.medico_id');
            $this->db->where('hg.turno', 'manha');
            $this->db->where('hg.ativo', 't');
            $this->db->where('op.operador_id', $medico_id);
            $this->db->where("hg.data", date("Y-m-d", strtotime(str_replace('/', '-', $data))));
            return $this->db->get()->result();
        } elseif ($dia_semana == "sexta") {


            $this->db->select('op.nome as medico,hg.turno,hg.tempo_atendimento,op.operador_id,hg.tempo_atendimento,op.sex_manha as hora_manha,op.sex_tarde as hora_tarde');
            $this->db->from('tb_hora_agendamento as hg');
            $this->db->join('tb_operador as op', 'op.operador_id = hg.medico_id');
            $this->db->where('hg.turno', 'manha');
            $this->db->where('hg.ativo', 't');
            $this->db->where('op.operador_id', $medico_id);
            $this->db->where("hg.data", date("Y-m-d", strtotime(str_replace('/', '-', $data))));
            return $this->db->get()->result();
        } elseif ($dia_semana == "sabado") {


            $this->db->select('op.nome as medico,hg.turno,hg.tempo_atendimento,op.operador_id,hg.tempo_atendimento,op.sab_manha as hora_manha,op.sab_tarde as hora_tarde');
            $this->db->from('tb_hora_agendamento as hg');
            $this->db->join('tb_operador as op', 'op.operador_id = hg.medico_id');
            $this->db->where('hg.turno', 'manha');
            $this->db->where('hg.ativo', 't');
            $this->db->where('op.operador_id', $medico_id);
            $this->db->where("hg.data", date("Y-m-d", strtotime(str_replace('/', '-', $data))));
            return $this->db->get()->result();
        } else {

            $this->db->select('op.nome as medico,hg.turno,hg.tempo_atendimento,op.operador_id,hg.tempo_atendimento,op.seg_manha as hora_manha,op.seg_tarde as hora_tarde');
            $this->db->from('tb_hora_agendamento as hg');
            $this->db->join('tb_operador as op', 'op.operador_id = hg.medico_id');
            $this->db->where('hg.turno', 'manha');
            $this->db->where('hg.ativo', 't');
            $this->db->where('op.operador_id', null);

            return $this->db->get()->result();
        }
    }

    function listarpoltronascompletetarde($data = NULL, $medico_id = NULL) {
        $datatest = date("d-m-Y", strtotime(str_replace('/', '-', $data)));

        $dia = $datatest;
        $diaa = substr($dia, 0, 2) . "-";

        $mes = substr($dia, 3, 2) . "-";

        $ano = substr($dia, 6, 4);

        @$diasemana = date("w", mktime(0, 0, 0, $mes, $diaa, $ano));

        switch ($diasemana) {

            case"0": $dia_semana = "domingo";
                break;

            case"1": $dia_semana = "segunda";
                break;

            case"2": $dia_semana = "terca";
                break;

            case"3": $dia_semana = "quarta";
                break;

            case"4": $dia_semana = "quinta";
                break;

            case"5": $dia_semana = "sexta";
                break;

            case"6": $dia_semana = "sabado";
                break;
        }


        if ($dia_semana == "segunda") {

            $this->db->select('op.nome as medico,hg.turno,hg.tempo_atendimento,op.operador_id,hg.tempo_atendimento,op.seg_manha as hora_manha,op.seg_tarde as hora_tarde');
            $this->db->from('tb_hora_agendamento as hg');
            $this->db->join('tb_operador as op', 'op.operador_id = hg.medico_id');
            $this->db->where('hg.turno', 'tarde');
            $this->db->where('hg.ativo', 't');
            $this->db->where('op.operador_id', $medico_id);
            $this->db->where("hg.data", date("Y-m-d", strtotime(str_replace('/', '-', $data))));
            return $this->db->get()->result();
        } elseif ($dia_semana == "terca") {

            $this->db->select('op.nome as medico,hg.turno,hg.tempo_atendimento,op.operador_id,hg.tempo_atendimento,op.ter_manha as hora_manha,op.ter_tarde as hora_tarde');
            $this->db->from('tb_hora_agendamento as hg');
            $this->db->join('tb_operador as op', 'op.operador_id = hg.medico_id');
            $this->db->where('hg.turno', 'tarde');
            $this->db->where('hg.ativo', 't');
            $this->db->where('op.operador_id', $medico_id);
            $this->db->where("hg.data", date("Y-m-d", strtotime(str_replace('/', '-', $data))));

            return $this->db->get()->result();
        } elseif ($dia_semana == "quarta") {

            $this->db->select('op.nome as medico,hg.turno,hg.tempo_atendimento,op.operador_id,hg.tempo_atendimento,op.qua_manha as hora_manha,op.qua_tarde as hora_tarde');
            $this->db->from('tb_hora_agendamento as hg');
            $this->db->join('tb_operador as op', 'op.operador_id = hg.medico_id');
            $this->db->where('hg.turno', 'tarde');
            $this->db->where('hg.ativo', 't');
            $this->db->where('op.operador_id', $medico_id);
            $this->db->where("hg.data", date("Y-m-d", strtotime(str_replace('/', '-', $data))));

            return $this->db->get()->result();
        } elseif ($dia_semana == "quinta") {

            $this->db->select('op.nome as medico,hg.turno,hg.tempo_atendimento,op.operador_id,hg.tempo_atendimento,op.qui_manha as hora_manha,op.qui_tarde as hora_tarde');
            $this->db->from('tb_hora_agendamento as hg');
            $this->db->join('tb_operador as op', 'op.operador_id = hg.medico_id');
            $this->db->where('hg.turno', 'tarde');
            $this->db->where('hg.ativo', 't');
            $this->db->where('op.operador_id', $medico_id);
            $this->db->where("hg.data", date("Y-m-d", strtotime(str_replace('/', '-', $data))));
            return $this->db->get()->result();
        } elseif ($dia_semana == "sexta") {

            $this->db->select('op.nome as medico,hg.turno,hg.tempo_atendimento,op.operador_id,hg.tempo_atendimento,op.sex_manha as hora_manha,op.sex_tarde as hora_tarde');
            $this->db->from('tb_hora_agendamento as hg');
            $this->db->join('tb_operador as op', 'op.operador_id = hg.medico_id');
            $this->db->where('hg.turno', 'tarde');
            $this->db->where('hg.ativo', 't');
            $this->db->where('op.operador_id', $medico_id);
            $this->db->where("hg.data", date("Y-m-d", strtotime(str_replace('/', '-', $data))));
            return $this->db->get()->result();
        } elseif ($dia_semana == "sabado") {


            $this->db->select('op.nome as medico,hg.turno,hg.tempo_atendimento,op.operador_id,hg.tempo_atendimento,op.sab_manha as hora_manha,op.sab_tarde as hora_tarde');
            $this->db->from('tb_hora_agendamento as hg');
            $this->db->join('tb_operador as op', 'op.operador_id = hg.medico_id');
            $this->db->where('hg.turno', 'tarde');
            $this->db->where('hg.ativo', 't');
            $this->db->where('op.operador_id', $medico_id);
            $this->db->where("hg.data", date("Y-m-d", strtotime(str_replace('/', '-', $data))));
            return $this->db->get()->result();
        } else {

            $this->db->select('op.nome as medico,hg.turno,hg.tempo_atendimento,op.operador_id,hg.tempo_atendimento,op.sab_manha as hora_manha,op.sab_tarde as hora_tarde');
            $this->db->from('tb_hora_agendamento as hg');
            $this->db->join('tb_operador as op', 'op.operador_id = hg.medico_id');
            $this->db->where('hg.turno', 'tarde');
            $this->db->where('hg.ativo', 't');
            $this->db->where('op.operador_id', null);
        }
    }

    function gravarhorapoltronamedico() {
        $paciente_id = 0;
        $medico_id = $_POST['medico_id'];
        if ($_POST['turno'] != "manha" && $_POST['turno'] != "tarde") {
            return -5;
        }

        if ($_POST['paciente_id'] == "" || $_POST['prontu_antigo_v'] == "") {

            $this->db->select('');
            $this->db->from('tb_paciente');
            $this->db->where('prontuario_antigo', $_POST['prontu_antigo']);
            $verifi_pront = $this->db->get()->result();

            if (count($verifi_pront) >= 1) {
                return -6;
            }
        }
        if ($_POST['paciente_id'] == "") {
            $this->db->set('nome', $_POST['txtNome']);
            if ($_POST['nascimento'] != '') {
                $this->db->set('nascimento', date("Y-m-d", strtotime(str_replace("/", "-", $_POST['nascimento']))));
            }

            $this->db->set('prontuario_antigo', $_POST['prontu_antigo']);
            $this->db->insert('tb_paciente');
            $paciente_id_novo = $this->db->insert_id();
            $paciente_id =   $paciente_id_novo;

        }
        if ($_POST['paciente_id'] != "") {
            $this->db->set('prontuario_antigo', $_POST['prontu_antigo']);
            $this->db->where('paciente_id', $_POST['paciente_id']);
            $this->db->update('tb_paciente');
            $paciente_id = $_POST['paciente_id'];
        }
        /////associando senha do toten ao paciente
        $this->listarsenhatotenassociarpaciente($medico_id,$paciente_id);
        ///////////////////////////////////////
        $operador_id = $this->session->userdata('operador_id');
        $horario = date("Y-m-d H:i:s");
        $data = date("Y-m-d"); 

        $this->db->select('sum(tempo_atendimento) as tempo_consumido');
        $this->db->from('tb_hora_agendamento lh');
        $this->db->where('lh.data', date("Y-m-d", strtotime(str_replace("/", "-", $_POST['data']))));
        $this->db->where('lh.ativo', 't');
        $return2 = $this->db->get();
        $result2 = $return2->result();
            
        // Tempo necessário para o atendimento

        list($h_necessario, $m_necessario) = explode(':', $_POST['tempo_atendimento']);
        $tempo_necessario = $h_necessario * 60 + $m_necessario;
        //////////////////////////
        // Tempo Consumido durante o dia
        $tempo_consumido = 0;
        if (count(@$result2) > 0) {
            @list($h_consumido, $m_consumido) = explode(':', @$result2[0]->tempo_consumido);
            $tempo_consumido = $h_consumido * 60 + $m_consumido;
        } 
            
////////////////////////////  
////////PEGANDO O HORARIO TOTAL QUE O MEDICO PODE FAZER EM UM DIA DE TRABALHO

        $datatest = date("d-m-Y", strtotime(str_replace('/', '-', $_POST['data'])));
        $dia = $datatest;
        $diaa = substr($dia, 0, 2) . "-";
        $mes = substr($dia, 3, 2) . "-";
        $ano = substr($dia, 6, 4);

        @$diasemana = date("w", mktime(0, 0, 0, $mes, $diaa, $ano));

        switch ($diasemana) {

            case"0": $dia_semana = "domingo";
                break;

            case"1": $dia_semana = "segunda";
                break;

            case"2": $dia_semana = "terca";
                break;

            case"3": $dia_semana = "quarta";
                break;

            case"4": $dia_semana = "quinta";
                break;

            case"5": $dia_semana = "sexta";
                break;

            case"6": $dia_semana = "sabado";
                break;
        }




        if ($dia_semana == "segunda") {

            $this->db->select('seg_manha as hora_manha,seg_tarde as hora_tarde');
            $this->db->from('tb_operador');
            $this->db->where('operador_id', $_POST['medico_id']);
            $return_hora_dia = $this->db->get()->result();
        } elseif ($dia_semana == "terca") {

            $this->db->select('ter_manha as hora_manha,ter_tarde as hora_tarde');
            $this->db->from('tb_operador');
            $this->db->where('operador_id', $_POST['medico_id']);
            $return_hora_dia = $this->db->get()->result();
        } elseif ($dia_semana == "quarta") {


            $this->db->select('qua_manha as hora_manha,qua_tarde as hora_tarde');
            $this->db->from('tb_operador');
            $this->db->where('operador_id', $_POST['medico_id']);
            $return_hora_dia = $this->db->get()->result();
        } elseif ($dia_semana == "quinta") {


            $this->db->select('qui_manha as hora_manha,qui_tarde as hora_tarde');
            $this->db->from('tb_operador');
            $this->db->where('operador_id', $_POST['medico_id']);



            $return_hora_dia = $this->db->get()->result();
        } elseif ($dia_semana == "sexta") {
            $this->db->select('sex_manha as hora_manha,sex_tarde as hora_tarde');
            $this->db->from('tb_operador');
            $this->db->where('operador_id', $_POST['medico_id']);

            $return_hora_dia = $this->db->get()->result();
        } elseif ($dia_semana == "sabado") {

            $this->db->select('sab_manha as hora_manha,sab_tarde as hora_tarde');
            $this->db->from('tb_operador');
            $this->db->where('operador_id', $_POST['medico_id']);
            $return_hora_dia = $this->db->get()->result();
        } else {


            $this->db->select('sex_manha as hora_manha,sex_tarde as hora_tarde');
            $this->db->from('tb_operador');
            $this->db->where('operador_id', null);
            $return_hora_dia = $this->db->get()->result();
        }


            
////////////////////////////         
////////SOMANDO TODOS OS TEMPOS JÁ USADOS PELO MEDICO NO DIA SELECIONADO
        $this->db->select('sum(tempo_atendimento) as tempo_consumido');
        $this->db->from('tb_hora_agendamento lh');
        $this->db->where('lh.data', date("Y-m-d", strtotime(str_replace("/", "-", $_POST['data']))));
        $this->db->where('lh.ativo', 't');
        $this->db->where('lh.medico_id', $_POST['medico_id']);
//      $this->db->groupby('lh.sala_id');
        $return_hora_consumida = $this->db->get()->result();
////////////////////////////      
////////TRANSFORMANDO HORAS ENVIADA PELO POST (tempo_atendimento) EM MINUTOS
        list($h_post, $m_post) = explode(':', $_POST['tempo_atendimento']);
        $tempo_total_post = $h_post * 60 + $m_post;
////////////////////////////                   
//////// TRANSFORMANDO HORAS (tempo_consumido) EM MINUTOS       
        if (@$return_hora_consumida[0]->tempo_consumido > 0) {
            list($h_consumido, $m_consumido) = explode(':', $return_hora_consumida[0]->tempo_consumido);
            $tempo_total_consumido = $h_consumido * 60 + $m_consumido;
        }
////////////////////////////                  
////////TRANSFORMANDO HORAS (hora_manha) E (hora_tarde)  EM MINUTOS  
        list($h_manha, $m_manha) = explode(':', $return_hora_dia[0]->hora_manha);
        list($h_tarde, $m_tarde) = explode(':', $return_hora_dia[0]->hora_tarde);
        $minutos_inicio = $h_manha * 60 + $m_manha;
        $minutos_fim = $h_tarde * 60 + $m_tarde;
        $tempo_total = $minutos_fim + $minutos_inicio;

////////////////////////////          
////////TRANSFORMANDO HORAS (hora_manha) EM MINUTOS  
        list($h_manha, $m_manha) = explode(':', $return_hora_dia[0]->hora_manha);
        $tempo_total_manha = $h_manha * 60 + $m_manha;
        $this->db->select('sum(tempo_atendimento) as tempo_consumido');
        $this->db->from('tb_hora_agendamento lh');
        $this->db->where('lh.data', date("Y-m-d", strtotime(str_replace("/", "-", $_POST['data']))));
        $this->db->where('lh.ativo', 't');
        $this->db->where('lh.turno', 'manha');
        $this->db->where('lh.medico_id', $_POST['medico_id']);
//        $this->db->groupby('lh.sala_id');
        $return_hora_consumida_manha = $this->db->get()->result();
        echo "HORAS CONSUMIDA DE AMANHA<pre>";
//        print_r($return_hora_consumida_manha);


        if (@$return_hora_consumida_manha[0]->tempo_consumido > 0) {
            list($h_consumido, $m_consumido) = explode(':', @$return_hora_consumida_manha[0]->tempo_consumido);
            $tempo_total_consumido_manha = $h_consumido * 60 + $m_consumido;
        } else {
            
        }




////////////////////////////          
////////TRANSFORMANDO HORAS (hora_tarde) EM MINUTOS  
        list($h_tarde, $m_tarde) = explode(':', $return_hora_dia[0]->hora_tarde);
        $tempo_total_tarde = $h_tarde * 60 + $m_tarde;

        $this->db->select('sum(tempo_atendimento) as tempo_consumido');
        $this->db->from('tb_hora_agendamento lh');
        $this->db->where('lh.data', date("Y-m-d", strtotime(str_replace("/", "-", $_POST['data']))));
        $this->db->where('lh.ativo', 't');
        $this->db->where('lh.turno', 'tarde');
        $this->db->where('lh.medico_id', $_POST['medico_id']);
//        $this->db->groupby('lh.sala_id');
        $return_hora_consumida_tarde = $this->db->get()->result();
        echo "HORAS CONSUMIDAS NA TARDE<pre>";
//        print_r($return_hora_consumida_tarde);


        if (@$return_hora_consumida_tarde[0]->tempo_consumido > 0) {
            @list($h_consumido, $m_consumido) = explode(':', @$return_hora_consumida_tarde[0]->tempo_consumido);
            $tempo_total_consumido_tarde = $h_consumido * 60 + $m_consumido;
        } else {
            
        }


//////////////////////////// 
////////VERIFICANDO SE O (TEMPO CONSUMIDO NO DIA + O QUE FOI ENVIADO PELO POST) É MAIOR QUE O TEMPO QUE O MEDICO PODE TRABALHAR
////////DURANDO UM DIA
        if ((@$tempo_total_consumido + @$tempo_total_post) > @$tempo_total) {
//            echo "fechar";
            return -2;
        } else {

            if ($_POST['turno'] == "manha") {

                if ((@$tempo_total_consumido_manha + @$tempo_total_post) > @$tempo_total_manha) {
                    return -3;
//                    echo "horario manha cheio"; 
                } else {
                    $this->db->set('turno', 'manha');
//                    echo "enviar manha";
                }
            }


            if ($_POST['turno'] == "tarde") {

                if ((@$tempo_total_consumido_tarde + @$tempo_total_post) > @$tempo_total_tarde) {
//                    echo "horario tarde cheio";
                    return -4;
                } else {
                    $this->db->set('turno', 'tarde');
//                    echo "enviar tarde";
                }
            }

            if ($_POST['paciente_id'] == "") { 
                $this->db->set('paciente_id', $paciente_id_novo);
            } else {
                $this->db->set('paciente_id', $_POST['paciente_id']);
            }

            
//          $this->db->set('guia_id', $_POST['guia_id']);
            $this->db->set('tempo_atendimento', $_POST['tempo_atendimento']);
            $this->db->set('observacao', $_POST['observacao']);
//          $this->db->set('sala_id', $_POST['sala_id']);
            $this->db->set('medico_id', $_POST['medico_id']);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('exame_sala_id',$_POST['exame_sala_id']); 
            $this->db->set('data', date("Y-m-d", strtotime(str_replace("/", "-", $_POST['data']))));


            $this->db->set('operador_cadastro', $operador_id);
            $this->db->set('ativo', 't');
            $this->db->set('medicamentos', $_POST['medicamentos']);
            $this->db->insert('tb_hora_agendamento');


            return 0;
        }
    }

    function listarpoltronascompletehorasdia($data = NULL, $medico_id = NULL) {
        $datatest = date("d-m-Y", strtotime(str_replace('/', '-', $data)));

        $dia = $datatest;
        $diaa = substr($dia, 0, 2) . "-";

        $mes = substr($dia, 3, 2) . "-";

        $ano = substr($dia, 6, 4);

        @$diasemana = date("w", mktime(0, 0, 0, $mes, $diaa, $ano));

        switch ($diasemana) {

            case"0": $dia_semana = "domingo";
                break;

            case"1": $dia_semana = "segunda";
                break;

            case"2": $dia_semana = "terca";
                break;

            case"3": $dia_semana = "quarta";
                break;

            case"4": $dia_semana = "quinta";
                break;

            case"5": $dia_semana = "sexta";
                break;

            case"6": $dia_semana = "sabado";
                break;
        }


        if ($dia_semana == "segunda") {



            $this->db->select('seg_manha as hora_manha,seg_tarde as hora_tarde');

            $this->db->from('tb_operador ');
            $this->db->where('operador_id', $medico_id);
//        $this->db->where("hg.data", date("Y-m-d", strtotime(str_replace('/', '-', $data))));

            return $this->db->get()->result();
        } elseif ($dia_semana == "terca") {


            $this->db->select('ter_manha as hora_manha,ter_tarde as hora_tarde');

            $this->db->from('tb_operador ');
            $this->db->where('operador_id', $medico_id);
//        $this->db->where("hg.data", date("Y-m-d", strtotime(str_replace('/', '-', $data))));

            return $this->db->get()->result();
        } elseif ($dia_semana == "quarta") {

            $this->db->select('qua_manha as hora_manha,qua_tarde as hora_tarde');

            $this->db->from('tb_operador ');
            $this->db->where('operador_id', $medico_id);
//        $this->db->where("hg.data", date("Y-m-d", strtotime(str_replace('/', '-', $data))));

            return $this->db->get()->result();
        } elseif ($dia_semana == "quinta") {


            $this->db->select('qui_manha as hora_manha,qui_tarde as hora_tarde');
            $this->db->from('tb_operador ');
            $this->db->where('operador_id', $medico_id);
//        $this->db->where("hg.data", date("Y-m-d", strtotime(str_replace('/', '-', $data))));
            return $this->db->get()->result();
        } elseif ($dia_semana == "sexta") {

            $this->db->select('sex_manha as hora_manha,sex_tarde as hora_tarde');
            $this->db->from('tb_operador ');
            $this->db->where('operador_id', $medico_id);
//        $this->db->where("hg.data", date("Y-m-d", strtotime(str_replace('/', '-', $data))));
            return $this->db->get()->result();
        } elseif ($dia_semana == "sabado") {

            $this->db->select('sab_manha as hora_manha,sab_tarde as hora_tarde');
            $this->db->from('tb_operador ');
            $this->db->where('operador_id', $medico_id);
//        $this->db->where("hg.data", date("Y-m-d", strtotime(str_replace('/', '-', $data))));

            return $this->db->get()->result();
        } else {

            $this->db->select('sab_manha as hora_manha,sab_tarde as hora_tarde');
            $this->db->from('tb_operador ');
            $this->db->where('operador_id', null);
//        $this->db->where("hg.data", date("Y-m-d", strtotime(str_replace('/', '-', $data))));

            return $this->db->get()->result();
        }
    }

    function removerCaracterEsp($string) {
        return preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/"), explode(" ", "a A e E i I o O u U n N"), $string);
    }

     function listaratestadotarefa($tarefa_medico_id) {

        $this->db->select(' ta.tarefa_atestado_id,
                            ta.texto,
                            ta.data_cadastro,
                            ta.medico_responsavel');
        $this->db->from('tb_tarefa_atestado ta');
        $this->db->where('ta.tarefa_medico_id', $tarefa_medico_id); 
        $this->db->orderby('ta.data_cadastro DESC');
        $return = $this->db->get();
        return $return->result();
    }
    
    
      function gravaratestadotarefa() {
        try {
            
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('texto', $_POST['laudo']);
            if ($_POST['carimbo'] == "on") {
                $this->db->set('carimbo', 't');
            }
            if ($_POST['assinatura'] == "on") {
                $this->db->set('assinatura', 't');
            }

            $this->db->set('tarefa_medico_id', $_POST['tarefa_medico_id']);
            $this->db->set('medico_responsavel', $_POST['medico']);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            if ($_POST['data'] != "") {
                $this->db->set('data', date("Y-m-d", strtotime(str_replace("/", "-", $_POST['data']))));
            }
            if ($_POST['cid1ID'] != "") {
                $this->db->set('cid1', $_POST['cid1ID']);
            }
            if ($_POST['cid2ID'] != "") {
                $this->db->set('cid2', $_POST['cid2ID']);
            }
            if ($_POST['imprimircid'] == "on") {
                $this->db->set('imprimir_cid', 't');
            }
            $this->db->set('tipo', 'NORMAL');

            $this->db->insert('tb_tarefa_atestado');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }
    
    function listareditaratestadotarefa($tarefa_medico_id) {

        $this->db->select(' ta.tarefa_medico_id ,
                            ta.texto,
                            ta.medico_responsavel,
                            ta.tarefa_atestado_id');
        $this->db->from('tb_tarefa_atestado ta');
        $this->db->where('ta.tarefa_atestado_id', $tarefa_medico_id);
        $this->db->where('ta.tipo', 'NORMAL');
        $return = $this->db->get();
        return $return->result();
    }
    
    
     function editaratestadotarefa($tarefa_atestado_id) {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('texto', $_POST['laudo']); 
            $this->db->set('medico_responsavel', $_POST['medico']);
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('tarefa_atestado_id', $tarefa_atestado_id);
            $this->db->update('tb_tarefa_atestado');
        } catch (Exception $exc) {
            return -1;
        }
    }
    
    
    
     function listaratestadoimpressaotarefa($tarefa_atestado_id) {

        $this->db->select(' tm.tarefa_medico_id,
                            tm.paciente_id,
                            tm.data_cadastro, 
                            ta.texto,
                            ta.data,
                            ta.imprimir_cid,
                            ta.cid1,
                            ta.cid2,
                            p.nascimento,    
                            o.nome as medico,
                            o.nome  as solicitante,
                            o.conselho,
                            tm.assinatura, 
                            tm.medico_responsavel,
                            tm.medico_responsavel as medico_parecer1, 
                            p.nome as paciente,
                            p.nascimento,
                            p.cpf,
                            p.sexo,
                            ta.assinatura,
                            ta.carimbo,
                            o.carimbo as medico_carimbo,
                            c.nome as convenio,
                            c.convenio_id');
        $this->db->from('tb_tarefa_atestado ta');
        $this->db->join('tb_tarefa_medico tm', 'tm.tarefa_medico_id = ta.tarefa_medico_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = tm.paciente_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = tm.medico_responsavel', 'left');  
        $this->db->join('tb_convenio c', 'c.convenio_id = p.convenio_id', 'left');
        $this->db->where("ta.tarefa_atestado_id", $tarefa_atestado_id);
        $return = $this->db->get();
        return $return->result();
    }
    
      function listarreceitatarefa($paciente_id) {

        $this->db->select(' tr.tarefa_receituario_id,
                            tr.texto,
                            tr.data_cadastro,
                            tr.medico_parecer1, 
                            tm.cabecalho,
                            o.nome as medico,
                            o.operador_id,
                            o.nome as solicitante,
                            o.operador_id as medico_parecer1,
                            c.nome as convenio,
                            c.convenio_id,
                            o.nome as medico,
                            tm.medico_responsavel
                            ');
        $this->db->from('tb_tarefa_receituario tr');
        $this->db->join('tb_tarefa_medico tm', 'tm.tarefa_medico_id = tr.tarefa_medico_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = tm.paciente_id', 'left'); 
        $this->db->join('tb_operador o', 'o.operador_id = tm.medico_responsavel', 'left');
         $this->db->join('tb_convenio c', 'c.convenio_id = p.convenio_id', 'left');
        $this->db->where('tm.paciente_id', $paciente_id);
        $this->db->where('tr.tipo', 'NORMAL');
        $this->db->orderby('tr.data_cadastro DESC');

        $return = $this->db->get();
        return $return->result();
    }
    
    function gravarreceituariotarefa() {
        try {
            
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('texto', $_POST['laudo']);
            if ($_POST['carimbo'] == "on") {
                $this->db->set('carimbo', 't');
            }
            if ($_POST['assinatura'] == "on") {
                $this->db->set('assinatura', 't');
            }
            
            $this->db->set('tarefa_medico_id', $_POST['tarefa_medico_id']);
            $this->db->set('medico_parecer1', $_POST['medico']);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $_POST['medico']);
            $this->db->set('tipo', 'NORMAL');

            $this->db->insert('tb_tarefa_receituario');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }
    
    
      function listarautocompleterepetirreceituariotarefa($tarefa_receituario_id) {
        $this->db->select(' tr.tarefa_receituario_id ,
                            tr.data_cadastro,
                            tr.texto,                         
                            tr.medico_parecer1');
        $this->db->from('tb_tarefa_receituario tr');
        $this->db->where('tr.tarefa_receituario_id', $tarefa_receituario_id);
        $this->db->where('tr.tipo', 'NORMAL');
        $return = $this->db->get();
        return $return->result();
    }
    
      function listareditarreceitatarefa($tarefa_receituario_id) {

        $this->db->select(' ag.tarefa_receituario_id ,
                            ag.texto,
                            ag.medico_parecer1');
        $this->db->from('tb_tarefa_receituario ag');
        $this->db->where('ag.tarefa_receituario_id', $tarefa_receituario_id);
        $this->db->where('ag.tipo', 'NORMAL');
        $return = $this->db->get();
        return $return->result();
    }
    
      function editarreceituariotarefa($tarefa_receituario_id) {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('texto', $_POST['laudo']); 
            $this->db->set('tarefa_medico_id', $_POST['tarefa_medico_id']);
            $this->db->set('medico_parecer1', $_POST['medico']);
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('tarefa_receituario_id',$tarefa_receituario_id);
            $this->db->update('tb_tarefa_receituario');
        } catch (Exception $exc) {
            return -1;
        }
    }
    
     function listarreceitaimpressaotarefa($tarefa_receituario_id) {

        $this->db->select(' tm.tarefa_medico_id,
                            tm.paciente_id,
                            tm.data_cadastro, 
                            tm.data, 
                            tr.texto,
                            tr.data_cadastro,
                            p.nascimento, 
                            o.nome as medico,
                            o.conselho,
                            o.carimbo as carimbo_medico,
                            tm.assinatura,  
                            tm.cabecalho,
                            tm.medico_responsavel,  
                            tm.medico_responsavel as medico_parecer1,  
                            c.nome as convenio, 
                            p.nome as paciente,
                            p.cpf,
                            p.nascimento,
                            p.sexo,
                            tr.assinatura,
                            tr.carimbo,
                            o.carimbo as medico_carimbo,
                            o.nome as solicitante');
        $this->db->from('tb_tarefa_receituario tr');
        $this->db->join('tb_tarefa_medico tm', 'tm.tarefa_medico_id = tr.tarefa_medico_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = tm.paciente_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = tm.medico_responsavel', 'left'); 
        $this->db->join('tb_convenio c', 'p.convenio_id = c.convenio_id', 'left'); 
        $this->db->where("tr.tarefa_receituario_id", $tarefa_receituario_id);
        
        $return = $this->db->get();
        return $return->result();
    }
    
     function listarreceitasespeciaispacientetarefa($paciente_id) {

        $this->db->select(' tre.tarefa_receituario_especial_id ,
                            tre.texto,
                            tre.data_cadastro,
                            tre.medico_parecer1');
        $this->db->from('tb_tarefa_receituario_especial tre');
        $this->db->join('tb_tarefa_medico tm', 'tm.tarefa_medico_id = tre.tarefa_medico_id', 'left');
        $this->db->where('tm.paciente_id', $paciente_id);
        $this->db->where('tre.tipo', 'ESPECIAL');
        $this->db->orderby('tre.data_cadastro DESC');
        $return = $this->db->get();
        return $return->result();
        
    }
            
     function gravarreceituarioespecialtarefa() {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('texto', $_POST['laudo']); 
            if ($_POST['carimbo'] == "on") {
                $this->db->set('carimbo', 't');
            }
            if ($_POST['assinatura'] == "on") {
                $this->db->set('assinatura', 't');
            }
            $this->db->set('tarefa_medico_id', $_POST['tarefa_medico_id']);
            $this->db->set('medico_parecer1', $_POST['medico']);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $_POST['medico']);
            $this->db->set('tipo', 'ESPECIAL'); 
            $this->db->set('empresa_id',$this->session->userdata('empresa_id'));
            $this->db->insert('tb_tarefa_receituario_especial');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }
    
    function listarreceitaespecialtarefa($tarefa_receituario_especial_id) {

        $this->db->select(' tre.tarefa_receituario_especial_id ,
                            tre.texto,
                            tre.medico_parecer1');
        $this->db->from('tb_tarefa_receituario_especial tre');
        $this->db->where('tre.tarefa_receituario_especial_id', $tarefa_receituario_especial_id);
        $this->db->where('tre.tipo', 'ESPECIAL');
        $return = $this->db->get();
        return $return->result();
    }
    
    
      function editarreceituarioespecialtarefa() {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('texto', $_POST['laudo']);
//            $this->db->set('paciente_id', $_POST['paciente_id']);
//            $this->db->set('procedimento_tuss_id', $_POST['procedimento_tuss_id']);
            $this->db->set('tarefa_medico_id', $_POST['tarefa_medico_id']);
            $this->db->set('medico_parecer1', $_POST['medico']);
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('tarefa_receituario_especial_id', $_POST['receituario_id']);
            $this->db->update('tb_tarefa_receituario_especial');
        } catch (Exception $exc) {
            return -1;
        }
    }
    
    
     function listarreceitaespecialimpressaotarefa($tarefa_receituario_especial_id) {

        $this->db->select('tm.tarefa_medico_id,
                            tm.paciente_id,
                            tm.data_cadastro, 
                            tre.texto,
                            p.bairro,
                            p.nascimento,
                            p.nome as paciente,
                            p.logradouro,
                            p.numero, 
                            p.rg,
                            p.uf_rg, 
                            o.nome as medico,
                            o.conselho,
                            tm.assinatura, 
                            tm.cabecalho,
                            tm.medico_responsavel as medico_parecer1,   
                            tre.data_cadastro,   
                            c.nome as convenio, 
                            tre.assinatura,
                            tre.carimbo,
                            o.carimbo as medico_carimbo,
                            ep.bairro as bairroemp,
                            ep.logradouro as endempresa,
                            ep.numero as numeroempresa,
                            ep.telefone as telempresa,
                            ep.celular as celularempresa, 
                            mp.nome as cidade,
                            mp.estado as estado, 
                            m.nome as cidaempresa,
                            m.estado as estadoempresa');
        $this->db->from('tb_tarefa_receituario_especial tre');
        $this->db->join('tb_tarefa_medico tm', 'tm.tarefa_medico_id = tre.tarefa_medico_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = tm.paciente_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = tre.medico_parecer1', 'left'); 
        $this->db->join('tb_convenio c', 'p.convenio_id = c.convenio_id', 'left');  
            $this->db->join('tb_empresa ep', 'ep.empresa_id = tre.empresa_id', 'left');
        $this->db->join('tb_municipio m', 'm.municipio_id = ep.municipio_id', 'left');
        $this->db->join('tb_municipio mp', 'mp.municipio_id = p.municipio_id', 'left');
      
        $this->db->where("tre.tarefa_receituario_especial_id", $tarefa_receituario_especial_id);
        
       
        $return = $this->db->get();
        return $return->result();
    }
    
    function listartarefashistorico($paciente_id,$tarefa_medico_id){
        
        $this->db->select('p.nome_mae,p.paciente_id as prontuario_antigo,tm.laudo as texto,tm.status as situacao,o.conselho,o.carimbo,tm.data_cadastro,p.nascimento,p.telefone,p.celular,p.whatsapp,orv.nome as medicorevisor,p.sexo,p.cpf,p.convenio_id,c.nome as convenio,p.rg,tm.nome as nome_tarefa,o.nome as medico_responsavel,tm.data,tm.status,p.nome as paciente,tm.tarefa_medico_id,tm.paciente_id,o.operador_id as medico_id,tm.laudo,tm.observacao,tm.nome_laudo,tm.diagnostico,tm.modelo_laudo_id,tm.indicado,tm.revisor,tm.assinatura,tm.medico_revisor,o.operador_id as medico_parecer1,o.nome as solicitante,o.nome as medico');
        $this->db->from('tb_tarefa_medico tm');
        $this->db->join('tb_operador o', 'o.operador_id = tm.medico_responsavel', 'left');
        $this->db->join('tb_operador orv', 'orv.operador_id = tm.medico_revisor', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = tm.paciente_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = p.convenio_id', 'left');
        $this->db->where('tm.ativo', 't'); 
        $this->db->where('tm.paciente_id', $paciente_id);
        $this->db->where('tm.tarefa_medico_id !=', $tarefa_medico_id);
        $this->db->where('tm.status','FINALIZADO');  
        return $this->db->get()->result();
            
    }
    
    function cancelarpoltrona($hora_agendamento_id){
        $horario = date('Y-m-d H:i:s');
        $operador  = $this->session->userdata('operador_id'); 
        $this->db->set('data_cancelamento',$horario);
        $this->db->set('operador_cancelamento',$operador);
        $this->db->where('hora_agendamento_id',$hora_agendamento_id);
        $this->db->set('ativo','f');
        $this->db->update('tb_hora_agendamento');
            
    }
    
    function listarsenhatotenassociarpaciente($operador_id, $paciente_id) {
        $horario = date("Y-m-d H:i:s");
        $data_ficha = date("Y-m-d");

        $this->db->select('toten_senha_id, id, senha');
        $this->db->from('tb_toten_senha');
        $this->db->orderby('toten_senha_id desc');
        $this->db->where('ativo', 'true');
        $this->db->where('associada', 'false');
//        $this->db->where('atendida', 'true');
        $this->db->where('operador_cadastro', $operador_id);
        $this->db->where("data_cadastro >=", date("Y-m-d") . ' 00:00:00');
        $this->db->where("data_cadastro <=", date("Y-m-d") . ' 23:59:59');
//        $this->db->where('data', $operador_id);
        $this->db->orderby('toten_senha_id desc');
        $return = $this->db->get()->result();
        if (count($return) > 0) {
            $toten_id = @$return[0]->toten_senha_id;
            $senha = @$return[0]->senha;
            $toten_fila_id = @$return[0]->id;
            if($toten_id > 0 && $senha != '' && $toten_fila_id > 0){
                $this->db->set('associada', 't');
                $this->db->set('atendida', 't');
                $this->db->set('operador_associada', $operador_id);
                $this->db->set('data_associada', $horario);
                $this->db->where('toten_senha_id', $toten_id);
                $this->db->update('tb_toten_senha');
    
                $this->db->set('data_senha', $data_ficha);
                $this->db->set('toten_senha_id', $toten_id);
                $this->db->set('toten_fila_id', $toten_fila_id);
                $this->db->set('senha', $senha);
                $this->db->where('paciente_id', $paciente_id);
                $this->db->update('tb_paciente');
            } 
        }   
            
        return $return;
    }
    
    function listarsolicitacoeschamar(){
        $this->db->select('p.nome as paciente,age.agenda_exames_id,p.toten_fila_id,  p.cpf, age.medico_consulta_id,o.nome as medicoconsulta,
            an.nome as sala,
            an.toten_sala_id,se.solicitacao_exame_chamar_id,se.data_cadastro');
        $this->db->from('tb_solicitacao_exame_chamar se');
        $this->db->join('tb_ambulatorio_exame ae','ae.ambulatorio_exame_id = se.ambulatorio_exame_id','left');
        $this->db->join('tb_ambulatorio_laudo al','al.ambulatorio_laudo_id = se.laudo_id','left');
        $this->db->join('tb_exames aes', 'aes.exames_id = al.exame_id', 'left');
        $this->db->join('tb_agenda_exames age', 'age.agenda_exames_id = aes.agenda_exames_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = age.paciente_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = age.medico_consulta_id', 'left');
        $this->db->join('tb_exame_sala an', 'an.exame_sala_id = age.agenda_exames_nome_id', 'left');
        $this->db->orderby('se.data_cadastro');
        $this->db->where('se.ativo','t');
     
        return $this->db->get()->result();
    }
    
    function excluirsolicitacaochamar($solicitacao_exame_chamar_id){
        $horario = date('Y-m-d H:i:s');
        $operador = $this->session->userdata('operador_id');        
        $this->db->set('ativo','f');
        $this->db->set('operador_atualizacao',$operador);
        $this->db->set('data_atualizacao',$horario);
        $this->db->where('solicitacao_exame_chamar_id',$solicitacao_exame_chamar_id);
        $this->db->update('tb_solicitacao_exame_chamar');
    }




    // Essas funções são utilizadas para Salvar as Impressões Receitas / R. Especial / S. exames / Terapeuticas / Relatorios
    //  Na Nova tela do Médico 
    // Criado para a Regenerati

    function adicionalcabecalho($cabecalho, $laudo) {
        $ano = 0;
        $mes = 0;
//        $cabecalho = $impressaolaudo[0]->texto;
        $cabecalho = str_replace("_paciente_", $laudo['0']->paciente, $cabecalho);
        $cabecalho = str_replace("_sexo_", $laudo['0']->sexo, $cabecalho);
        $cabecalho = str_replace("_nascimento_", date("d/m/Y", strtotime($laudo['0']->nascimento)), $cabecalho);
        $cabecalho = str_replace("_convenio_", $laudo['0']->convenio, $cabecalho);
        $cabecalho = str_replace("_sala_", $laudo['0']->sala, $cabecalho);
        $cabecalho = str_replace("_CPF_", $laudo['0']->cpf, $cabecalho);
        $cabecalho = str_replace("_RG_", $laudo['0']->rg, $cabecalho);
        $cabecalho = str_replace("_solicitante_", $laudo['0']->solicitante, $cabecalho);
        $cabecalho = str_replace("_data_", substr($laudo['0']->data, 8, 2) . '/' . substr($laudo['0']->data, 5, 2) . '/' . substr($laudo['0']->data, 0, 4), $cabecalho);
        $cabecalho = str_replace("_hora_", date("H:i:s", strtotime($laudo[0]->data_cadastro)), $cabecalho);
        $cabecalho = str_replace("_medico_", $laudo['0']->medico, $cabecalho);
        $cabecalho = str_replace("_revisor_", $laudo['0']->medicorevisor, $cabecalho);
        $cabecalho = str_replace("_procedimento_", $laudo['0']->procedimento, $cabecalho);
        $cabecalho = str_replace("_laudo_", $laudo['0']->texto, $cabecalho);
        $cabecalho = str_replace("_nomedolaudo_", $laudo['0']->cabecalho, $cabecalho);
        $cabecalho = str_replace("_queixa_", $laudo['0']->cabecalho, $cabecalho);
        $cabecalho = str_replace("_peso_", $laudo['0']->peso, $cabecalho);
        $cabecalho = str_replace("_altura_", $laudo['0']->altura, $cabecalho);
        $cabecalho = str_replace("_cid1_", $laudo['0']->cid1, $cabecalho);
        $cabecalho = str_replace("_cid2_", $laudo['0']->cid2, $cabecalho);
        $cabecalho = str_replace("_guia_", $laudo[0]->guia_id, $cabecalho);
        $operador_id = $this->session->userdata('operador_id');
        $operador_atual = $this->operador_m->operadoratualsistema($operador_id);
        @$cabecalho = str_replace("_usuario_logado_", @$operador_atual[0]->nome, $cabecalho);
        @$cabecalho = str_replace("_usuario_salvar_", $laudo['laudo'][0]->usuario_salvar, $cabecalho);
        $cabecalho = str_replace("_telefone1_", $laudo[0]->telefone, $cabecalho);
        $cabecalho = str_replace("_telefone2_", $laudo[0]->celular, $cabecalho);
        $cabecalho = str_replace("_whatsapp_", $laudo[0]->whatsapp, $cabecalho);
        $cabecalho = str_replace("_prontuario_antigo_", $laudo[0]->prontuario_antigo, $cabecalho);
        $cabecalho = str_replace("_prontuario_", $laudo[0]->paciente_id, $cabecalho);
        $cabecalho = str_replace("_nome_mae_", $laudo[0]->nome_mae, $cabecalho);
        $cabecalho = str_replace("_especialidade_", $laudo[0]->grupo, $cabecalho);
        $dataFuturo2 = date("Y-m-d");
        $dataAtual2 = $laudo[0]->nascimento;
        $date_time2 = new DateTime($dataAtual2);
        $diff2 = $date_time2->diff(new DateTime($dataFuturo2));
        $idade = $diff2->format('%Y anos'); 
     
        $ano = $diff2->format('%Y'); 
        $mes = $diff2->format('%m');  
        
        if ($ano > 1) {
           $s = "s"; 
        }else{
           $s = ""; 
        }
        
        if ($ano == 0) { 
             if ($mes > 1) {
                    $sm = "es";
                }else{
                    $sm = "";
                } 
            $ano_formado = $mes." mes".$sm;   
        }else{
            if ($mes > 0) { 
                if ($mes > 1) {
                    $sm = " meses";
                }else{
                    $sm = " mês";
                }
                  $ano_formado = $ano." ano".$s." e ".$mes.$sm; 
            }else{
                  $ano_formado = $ano." ano".$s;  
            }
        } 
        
        $cabecalho = str_replace("_idade_", $ano_formado, $cabecalho);
          
        return $cabecalho;
    }

    function impressaoreceitaespecialsalvar($ambulatorio_laudo_id,$receituario=NULL, $laudo_id) {
        $empresa_id = $this->session->userdata('empresa_id');
        $this->load->plugin('mpdf');
        $empresa = $this->session->userdata('empresa_id');
        if(!$empresa > 0){
            $empresa = 1;
        }
        if(!$empresa_id > 0){
            $empresa_id = 1;
        }
        $data['permissao'] = $this->empresa->listarverificacaopermisao2($empresa);
       
        if ($receituario== "true") {
          $data['laudo'] = $this->laudo->listarreceitaimpressao($ambulatorio_laudo_id);   
        }else{
           $data['laudo'] = $this->laudo->listarreceitaespecialimpressao($ambulatorio_laudo_id);
        }
         
        $permissao = $this->empresa->listarverificacaopermisao2($empresa);
        
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresamunicipio();
        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo'][0]->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        if ($data['laudo'][0]->assinatura == 't') {
            $data['operador_assinatura'] = $data['laudo'][0]->medico_parecer1;
        }
       
        $this->load->helper('directory');
        $arquivos = directory_map("./upload/operadorLOGO/");
        $data['assinatura'] = directory_map("./upload/1ASSINATURAS/");
        $data['arquivo_existe'] = false;
//        var_dump($arquivos); die;
//        var_dump($arquivos);die;
        if (@$arquivos != false) {
            foreach (@$arquivos as $value) {
                if (@$value == @$data['laudo'][0]->medico_parecer1 . ".jpg") {
                    @$data['arquivo_existe'] = true;
                    @$data['medico_parecer1'] = @$data['laudo'][0]->medico_parecer1;
                    break;
                }
            }
        } 
        
        $cabecalho =  $this->load->view('ambulatorio/cabecalhorelatorioespecial', $data, true);
        $rodape =  $this->load->view('ambulatorio/rodaperelatorioespecial', $data, true);
        
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressaoreceituario($empresa_id);
        $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['laudo'][0]->medico_parecer1);
        @$data['cabecalho'][0]->cabecalho = str_replace("_minicurriculum_", $data['cabecalhomedico'][0]->curriculo,$data['cabecalho'][0]->cabecalho);
        
        if ($data['cabecalho'][0]->receituario_especial == "t") {
            $cabecalho_config = $data['cabecalho'][0]->cabecalho;
            if ($data['permissao'][0]->a4_receituario_especial == "t") { 
                 $cabecalho = "<table border='0'  width=100%><tr><td>".$cabecalho_config."</td></tr></table>";      
            }else{
                 $cabecalho = "<table border='0'  width=100%><tr><td>".$cabecalho_config."</td><td>".$cabecalho_config."</td></tr></table>";      
            }  
        }else{ 
           if ($data['permissao'][0]->a4_receituario_especial == "t") { 
              $cabecalho =  $this->load->view('ambulatorio/cabecalhorelatorioespecialA4', $data, true);
            }   
        } 
        
        $data['laudo'][0]->texto = str_replace("<!DOCTYPE html>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<head>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</head>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<html>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<body>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</html>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</body>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="center"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="left"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="right"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="justify"', '', $data['laudo'][0]->texto);
        
$filename = 'receituarioEspecial_'.$ambulatorio_laudo_id.'.pdf';
// echo $laudo_id;
// die;

        if ($data['permissao'][0]->a4_receituario_especial == "t") { 
            if ($data['cabecalhomedico'][0]->cabecalho != '') { // Cabeçalho do Profissional
                $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
                $cabecalho .=  $this->load->view('ambulatorio/cabecalhorelatorioespecialA4', $data, true);
            }
        }

        if ($permissao[0]->a4_receituario_especial == "t") { 
           $html = $this->load->View('ambulatorio/impressaoreceituarioespecialA4', $data,true);
           pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 5, 0, 4, $laudo_id); 


        }else{ 
           $html = $this->load->View('ambulatorio/impressaoreceituarioespecial', $data,true);
           pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 5, 0, 4, $laudo_id); 
        }
        
       
    }

    function impressaoreceitasalvar($ambulatorio_laudo_id, $laudo_id) {
        $empresa_id = $this->session->userdata('empresa_id');
        $this->load->plugin('mpdf');
        $data['laudo'] = $this->laudo->listarreceitaimpressao($ambulatorio_laudo_id);
        $data['laudo'][0]->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $data['laudo'][0]->texto);
        $data['medico'] = $this->operador_m->medicoreceituario($data['laudo'][0]->medico_parecer1);
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['empresa'] = $this->guia->listarempresa();
        $data['empresa_m'] = $this->empresa->listarempresamunicipio();
        @$municipio_empresa = @$data['empresa_m'][0]->municipio;
        $data['receituario'] = true;
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressaoreceituario($empresa_id);
        if(!$empresa_id > 0){
            $empresa_id = 1;
        }
        $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['laudo'][0]->medico_parecer1);

        $data['cabecalho'][0]->cabecalho = str_replace("_minicurriculum_", $data['cabecalhomedico'][0]->curriculo,$data['cabecalho'][0]->cabecalho);         
        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudoreceituario($empresa_id); 
        $cabecalho_config = $data['cabecalho'][0]->cabecalho;
        $rodape_config = $data['cabecalho'][0]->rodape;  
        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        if ($data['laudo'][0]->assinatura == 't') {
            $data['operador_assinatura'] = $data['laudo'][0]->medico_parecer1;
        }
        $base_url = base_url();



        if ($data['laudo'][0]->assinatura == 't') {
            if (isset($data['laudo'][0]->medico_parecer1)) {
                $this->load->helper('directory');
                $arquivo_pasta = directory_map("./upload/1ASSINATURAS/");
                foreach ($arquivo_pasta as $value) {
                    if ($value == $data['laudo'][0]->medico_parecer1 . ".jpg") {
                        $carimbo = "<img width='200px;' height='100px;' src='$base_url" . "upload/1ASSINATURAS/$value' />";
                    }
                }
            }
        } elseif ($data['laudo'][0]->carimbo == 't') {
            $carimbo = $data['laudo'][0]->medico_carimbo;
        } else {
            $carimbo = "";
        }
        $data['assinatura'] = $carimbo;

        //    var_dump($carimbo);die;
        $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");

        $dia = substr($data['laudo'][0]->data_cadastro, 8, 2);
        $mes = substr($data['laudo'][0]->data_cadastro, 5, 2);
        $ano = substr($data['laudo'][0]->data_cadastro, 0, 4);

        $nomemes = $meses[$mes];

        $filename = 'receita_'.$ambulatorio_laudo_id.'.pdf';  

        $texto_rodape = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;
        $data['texto_rodape'] = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;
 
         
        $cabecalho = "";
        if ($data['empresa'][0]->ficha_config == 't') {


            
            if ($data['cabecalhomedico'][0]->cabecalho != '') { // Cabeçalho do Profissional
                $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
            } else {
                if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) { // Logo do Profissional
                    $cabecalho = '<img style="width: 100%; heigth: 35%;" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
                } else {
                    if ($data['impressaolaudo'][0]->cabecalho == 't') {
                        if ($data['empresa'][0]->cabecalho_config == 't') { // Cabeçalho Da clinica
                            $cabecalho = "$cabecalho_config";
                        } else {
                            $cabecalho = "<table><tr><td><img width='1000px' height='180px' src='img/cabecalho.jpg'></td></tr></table>";
                        }
                    } else {
                        $cabecalho = '';
                    }
                }
            } 
      
            
            $cabecalho = str_replace("_paciente_", $data['laudo'][0]->paciente, $cabecalho);
            $cabecalho = str_replace("_sexo_", $data['laudo'][0]->sexo, $cabecalho);
            $cabecalho = str_replace("_nascimento_", date("d/m/Y", strtotime($data['laudo'][0]->nascimento)), $cabecalho);
            $cabecalho = str_replace("_convenio_", $data['laudo'][0]->convenio, $cabecalho);
            $cabecalho = str_replace("_sala_", $data['laudo'][0]->sala, $cabecalho);
            $cabecalho = str_replace("_CPF_", $data['laudo'][0]->cpf, $cabecalho);
            $cabecalho = str_replace("_RG_", $data['laudo'][0]->rg, $cabecalho);
            $cabecalho = str_replace("_solicitante_", $data['laudo'][0]->solicitante, $cabecalho);
            $cabecalho = str_replace("_data_", substr($data['laudo'][0]->data, 8, 2) . '/' . substr($data['laudo'][0]->data, 5, 2) . '/' . substr($data['laudo'][0]->data, 0, 4), $cabecalho);
            $cabecalho = str_replace("_hora_", date("H:i:s", strtotime($data['laudo'][0]->data_cadastro)), $cabecalho);
            $cabecalho = str_replace("_medico_", $data['laudo'][0]->medico, $cabecalho);
            $cabecalho = str_replace("_revisor_", $data['laudo'][0]->medicorevisor, $cabecalho);
            $cabecalho = str_replace("_procedimento_", $data['laudo'][0]->procedimento, $cabecalho);
            $cabecalho = str_replace("_nomedolaudo_", $data['laudo'][0]->cabecalho, $cabecalho);
            $cabecalho = str_replace("_queixa_", $data['laudo'][0]->cabecalho, $cabecalho);
            $cabecalho = str_replace("_cid1_", $data['laudo'][0]->cid1, $cabecalho);
            $cabecalho = str_replace("_guia_", $data['laudo'][0]->guia_id, $cabecalho);
            $operador_id = $this->session->userdata('operador_id');
            $operador_atual = $this->operador_m->operadoratualsistema($operador_id);
            @$cabecalho = str_replace("_usuario_logado_", @$operador_atual[0]->nome, $cabecalho);
            @$cabecalho = str_replace("_usuario_salvar_", $data['laudo'][0]->usuario_salvar, $cabecalho);
            $cabecalho = str_replace("_prontuario_antigo_", $data['laudo'][0]->prontuario_antigo, $cabecalho);
            $cabecalho = str_replace("_prontuario_", $data['laudo'][0]->paciente_id, $cabecalho);
            $cabecalho = str_replace("_telefone1_", $data['laudo'][0]->telefone, $cabecalho);
            $cabecalho = str_replace("_telefone2_", $data['laudo'][0]->celular, $cabecalho);
            $cabecalho = str_replace("_whatsapp_", $data['laudo'][0]->whatsapp, $cabecalho);
            $cabecalho = str_replace("_nome_mae_", $data['laudo'][0]->nome_mae, $cabecalho);
            $cabecalho = str_replace("_especialidade_", $data['laudo'][0]->grupo, $cabecalho);
            $cabecalho = str_replace("_municipiodata_",$texto_rodape, $cabecalho);
            $data['impressaolaudo'][0]->adicional_cabecalho = str_replace("_municipiodata_",$texto_rodape, $data['impressaolaudo'][0]->adicional_cabecalho);
             
            $dataFuturo2 = date("Y-m-d");
            $dataAtual2 = $data['laudo'][0]->nascimento;
            $date_time2 = new DateTime($dataAtual2);
            $diff2 = $date_time2->diff(new DateTime($dataFuturo2));
            $idade = $diff2->format('%Y anos');
            $cabecalho = str_replace("_idade_", $idade, $cabecalho); 
            $cabecalho = $cabecalho . "{$data['impressaolaudo'][0]->adicional_cabecalho}";
            $cabecalho = $this->adicionalcabecalho($cabecalho, $data['laudo']); 
              
               
            if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg") && $data['cabecalhomedico'][0]->cabecalho == '') {
                $cabecalho = '<img width="300px" height="50px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
            } 
             
            if ($data['empresa'][0]->rodape_config == 't') {
                if ($data['cabecalhomedico'][0]->rodape != '') {
                    $rodape_config = $data['cabecalhomedico'][0]->rodape;
                }
                $rodape_config = str_replace("_assinatura_", $carimbo, $rodape_config);
                $rodape_config = str_replace("_municipiodata_",$texto_rodape, $rodape_config);
                $rodape = $rodape_config;
            } else {
                $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td  >$carimbo</td></tr></table><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            } 
            
            
            $html = $this->load->view('ambulatorio/impressaoreceituarioconfiguravel', $data, true);
             
            $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;


                if ($data['empresapermissoes'][0]->remove_margem_cabecalho_rodape == 't') {
                    $cabecalho = "<div style=' margin-left:7%;width:86%;'>".$cabecalho."</div>";
                    $rodape = "<div style=' margin-left:7%;width:86%;'>".$rodape."</div>"; 
                    pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 0, 0, 0, $laudo_id); 
                }else{ 
                    if ($sem_margins == 't') {
                        pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 0, 0, 0, $laudo_id);
                    } else {
                        pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);
                    }
                } 
     
        }else{
        if ($data['empresa'][0]->impressao_tipo == 1) {//HUMANA  
            
            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/humana.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);
            // $this->load->View('ambulatorio/impressaoreceituario', $data);
        }

/////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 13) {//CAGE 
            
            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/cage.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapecage.jpg'>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);
            // $this->load->View('ambulatorio/impressaoreceituario', $data);
        }

///////////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 10) {//CDC
            
            $cabecalho = "<table><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td><img align = 'left'  width='180px' height='80px' src='img/logo2.png'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<table><tr><td>Rua Juiz Renato Silva, 20 - Papicu | Fone (85)3234-3907</td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);
            // $this->load->View('ambulatorio/impressaoreceituario', $data);

/////////////////////////////////////////////////////////////////////////////////////////////////            
        } elseif ($data['empresa'][0]->impressao_tipo == 6) {//CLINICA DEZ     
            

            $cabecalho = "<table><tr><td><img align = 'left'  src='img/cabecalho.jpg'></td></tr><tr><td>&nbsp;</td></tr><tr><td>Paciente:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
            $rodape = "<table><tr><td><center><img align = 'left'  src='img/rodape.jpg'></center></td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);
            // $this->load->View('ambulatorio/impressaoreceituario', $data);
        }

/////////////////////////////////////////////////////////////////////////////////////////////        
        elseif ($data['empresa'][0]->impressao_tipo == 8) {//RONALDO BARREIRA
            $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>
                        <tr><td style='vertical-align: bottom; font-family: serif; font-size: 14pt;' colspan='2'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td></tr>
                        <tr><td colspan='2'><center>Rua 24 de maio, 961 - Fone: 3226-9536<center></td></tr>
                        <tr><td></td><td></td></tr>
                        <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                        <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                        <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                        <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . $data['laudo']['0']->solicitante . "<br></td></tr>
                        </table>";
            if ($data['laudo']['0']->convenio_id >= 29 && $data['laudo']['0']->convenio_id <= 84) {
                $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt;'>
                        <tr><td width='70%' style='vertical-align: bottom; font-family: serif; font-size: 12pt;'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td><td rowspan='2'><center><img align = 'left'  width='140px' height='40px' src='img/sesi.jpg'><center></td></tr>
                        <tr><td ><center>Rua 24 de maio, 961-Fone: 3226-9536<center></td><td></td></tr>            
                        <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                        <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                        <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                        <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . substr($data['laudo']['0']->solicitante, 0, 15) . "<br></td></tr>
                        </table>";
            }
            $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                        <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
            if ($data['laudo']['0']->medico_parecer1 == 929) {
                $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                        <tr><td><center>Radiologista - Leitor Qualificado Padrao OIT</td></tr>
                        <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
            }
            $grupo = $data['laudo']['0']->grupo;
            $html = $this->load->view('ambulatorio/impressaolaudo_2', $data, true);
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);

///////////////////////////////////////////////////////////////////////////////////////            
        } else {//GERAL        //  este item fica sempre por ultimo


            if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
                $img = '<img width="700px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
            } else {
                $img = "<img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'>";
            }

            $cabecalho = "<table ><tr><td>" . $img . "</td></tr><tr><td><center><b><p style='text-align: center; font-weight: bold;'>Receita Médica</p></b></center></td></tr><tr><td>Para:" . $data['laudo']['0']->paciente . "<br></td></tr></table>";
            $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td ></td></tr></table><div>$carimbo</div><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
            $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);
            // $this->load->View('ambulatorio/impressaoreceituario', $data);
        }
    }
}

function impressaosolicitarexamesalvar($ambulatorio_laudo_id, $laudo_id) {

    $this->load->plugin('mpdf');
    $data['laudo'] = $this->laudo->listarexameimpressao($ambulatorio_laudo_id);
    $data['medico'] = $this->operador_m->medicoreceituario($data['laudo'][0]->medico_parecer1);
    $empresa_id = $this->session->userdata('empresa_id');
    if(!@$empresa_id > 0){
        $empresa_id = 1;
    }
    $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
    $data['empresa'] = $this->guia->listarempresa();
    $data['empresa_m'] = $this->empresa->listarempresamunicipio();
    @$municipio_empresa = @$data['empresa_m'][0]->municipio;
    $data['receituario'] = true;
    $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($empresa_id);
    $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['laudo'][0]->medico_parecer1);
    $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudo($empresa_id);
    $cabecalho_config = $data['cabecalho'][0]->cabecalho;
    $rodape_config = $data['cabecalho'][0]->rodape;

    $dataFuturo = date("Y-m-d");
    $dataAtual = $data['laudo']['0']->nascimento;
    $date_time = new DateTime($dataAtual);
    $diff = $date_time->diff(new DateTime($dataFuturo));
    $teste = $diff->format('%Ya %mm %dd');

    if ($data['laudo'][0]->assinatura == 't') {
        $data['operador_assinatura'] = $data['laudo'][0]->medico_parecer1;
    }

    $base_url = base_url();
    $filename = "solicitacaoexames_".$ambulatorio_laudo_id.".pdf";

    if ($data['laudo'][0]->assinatura == 't') {
        if (isset($data['laudo'][0]->medico_parecer1)) {
            $this->load->helper('directory');
            $arquivo_pasta = directory_map("./upload/1ASSINATURAS/");
            foreach ($arquivo_pasta as $value) {
                if ($value == $data['laudo'][0]->medico_parecer1 . ".jpg") {
                    $carimbo = "<img width='200px;' height='100px;' src='$base_url" . "upload/1ASSINATURAS/$value' />";
                }
            }
        }
    } elseif ($data['laudo'][0]->carimbo == 't') {
        $carimbo = $data['laudo'][0]->medico_carimbo;
    } else {
        $carimbo = "";
    }
    $data['assinatura'] = $carimbo;

//        var_dump($carimbo);die;
    $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");

    $dia = substr($data['laudo'][0]->data_cadastro, 8, 2);
    $mes = substr($data['laudo'][0]->data_cadastro, 5, 2);
    $ano = substr($data['laudo'][0]->data_cadastro, 0, 4);

    $nomemes = $meses[$mes];



    $texto_rodape = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;

    if ($data['empresa'][0]->ficha_config == 't') {
        if ($data['empresa'][0]->cabecalho_config == 't') {          
            if ($data['cabecalhomedico'][0]->cabecalho2 != '') { // Cabeçalho do Profissional 2
                $cabecalho = $data['cabecalhomedico'][0]->cabecalho2;
            } elseif($data['cabecalhomedico'][0]->cabecalho != ''){ // Cabeçalho do Profissional
               $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
           } else {
                $cabecalho = "$cabecalho_config";
            }
//                $cabecalho = $cabecalho_config;
        } else {
            $cabecalho = "<table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'></td></tr></table>";
        }

        if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg") && $data['cabecalhomedico'][0]->cabecalho == '') {
            $cabecalho = '<img width="300px" height="50px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
        }

        if ($data['empresa'][0]->rodape_config == 't') {
            if ($data['cabecalhomedico'][0]->rodape != '') {
                $rodape_config = $data['cabecalhomedico'][0]->rodape;
            }
            $rodape_config = str_replace("_assinatura_", $carimbo, $rodape_config);
            $rodape = $texto_rodape . $rodape_config;
        } else {
            $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td  >$carimbo</td></tr></table><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
        }
        // var_dump($data['laudo'][0]->texto); die;
//            $cabecalho = "<table ><tr><td><img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'></td></tr><tr><td><center><b><p style='text-align: center; font-weight: bold;'>Receita Médica</p></b></center></td></tr><tr><td>Para:" . $data['laudo']['0']->paciente . "<br></td></tr></table>";
//            $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td ></td></tr></table><div>$carimbo</div><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
        $data['laudo'][0]->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<head>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</head>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<html>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<body>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</html>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</body>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="center"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="left"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="right"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="justify"', '', $data['laudo'][0]->texto);
        // Por causa do Tinymce Novo.

        // $data['extra'] = 'Terapeuticas';
        $data['laudo'][0]->texto = '<b><font size="7">Solicitações de Exames </font></b><br><br>'. $data['laudo'][0]->texto;
        // $data['laudo'][0]->texto = '1';
        // print_r($data['laudo'][0]->texto);
        // die;
        if (false) {
            // echo 'asduha'; die;
            $html = $this->load->view('ambulatorio/impressaosolicitacaoexamesreceita', $data, true);
        } else {
            $html = $this->load->view('ambulatorio/impressaosolicitacaoexamesconfiguravel', $data, true);
        }
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;
        
        if ($sem_margins == 't') {
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 0, 0, 0, $laudo_id);
        } else {
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '',9,0,15, $laudo_id);
        }
    }else{

    if ($data['empresa'][0]->impressao_tipo == 1) {//HUMANA        
        // $filename = "laudo.pdf";
        $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/humana.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
        $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'>";
        $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
        pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '',9,0,15, $laudo_id);
        // $this->load->View('ambulatorio/impressaoreceituario', $data);
    }

/////////////////////////////////////////////////////////////////////////////////////        
    elseif ($data['empresa'][0]->impressao_tipo == 13) {//CAGE        
        // $filename = "laudo.pdf";
        $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/cage.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
        $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapecage.jpg'>";
        $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
        pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '',9,0,15, $laudo_id);
        // $this->load->View('ambulatorio/impressaoreceituario', $data);
    }

///////////////////////////////////////////////////////////////////////////////////////////        
    elseif ($data['empresa'][0]->impressao_tipo == 10) {//CDC
        // $filename = "laudo.pdf";
        $cabecalho = "<table><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td><img align = 'left'  width='180px' height='80px' src='img/logo2.png'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
        $rodape = "<table><tr><td>Rua Juiz Renato Silva, 20 - Papicu | Fone (85)3234-3907</td></tr></table>";
        $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
        pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '',9,0,15, $laudo_id);
        // $this->load->View('ambulatorio/impressaoreceituario', $data);

/////////////////////////////////////////////////////////////////////////////////////////////////            
    } elseif ($data['empresa'][0]->impressao_tipo == 6) {//CLINICA DEZ     
        // $filename = "laudo.pdf";
        $cabecalho = "<table><tr><td><img align = 'left'  src='img/cabecalho.jpg'></td></tr><tr><td>&nbsp;</td></tr><tr><td>Paciente:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
        $rodape = "<table><tr><td><center><img align = 'left'  src='img/rodape.jpg'></center></td></tr></table>";
        $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
        pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '',9,0,15, $laudo_id);
        // $this->load->View('ambulatorio/impressaoreceituario', $data);
    }

/////////////////////////////////////////////////////////////////////////////////////////////        
    elseif ($data['empresa'][0]->impressao_tipo == 8) {//RONALDO BARREIRA
        $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>
                    <tr><td style='vertical-align: bottom; font-family: serif; font-size: 14pt;' colspan='2'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td></tr>
                    <tr><td colspan='2'><center>Rua 24 de maio, 961 - Fone: 3226-9536<center></td></tr>
                    <tr><td></td><td></td></tr>
                    <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                    <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                    <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                    <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . $data['laudo']['0']->solicitante . "<br></td></tr>
                    </table>";
        if ($data['laudo']['0']->convenio_id >= 29 && $data['laudo']['0']->convenio_id <= 84) {
            $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt;'>
                    <tr><td width='70%' style='vertical-align: bottom; font-family: serif; font-size: 12pt;'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td><td rowspan='2'><center><img align = 'left'  width='140px' height='40px' src='img/sesi.jpg'><center></td></tr>
                    <tr><td ><center>Rua 24 de maio, 961-Fone: 3226-9536<center></td><td></td></tr>            
                    <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                    <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                    <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                    <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . substr($data['laudo']['0']->solicitante, 0, 15) . "<br></td></tr>
                    </table>";
        }
        $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                    <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
        if ($data['laudo']['0']->medico_parecer1 == 929) {
            $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                    <tr><td><center>Radiologista - Leitor Qualificado Padrao OIT</td></tr>
                    <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
        }
        $grupo = $data['laudo']['0']->grupo;
        $html = $this->load->view('ambulatorio/impressaolaudo_2', $data, true);
        pdfreceitassalvas($html, $filename, $cabecalho, $rodape, $grupo,9,0,15, $laudo_id);

///////////////////////////////////////////////////////////////////////////////////////            
    } else {//GERAL        //  este item fica sempre por ultimo
        // $filename = "laudo.pdf";

        if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
            $img = '<img width="700px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
        } else {
            $img = "<img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'>";
        }

        $cabecalho = "<table ><tr><td>" . $img . "</td></tr><tr><td><center><b><p style='text-align: center; font-weight: bold;'></p></b></center></td></tr><tr><td><b>Para:" . $data['laudo']['0']->paciente . "<br></b></td></tr></table>";
        $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td ></td></tr></table><div>$carimbo</div><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
        $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;

        if ($sem_margins == 't') {
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 0, 0, 0, $laudo_id);
        } else {
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '',9,0,15, $laudo_id);
        }
        // $this->load->View('ambulatorio/impressaoreceituario', $data);
    }
}
}

function impressaoteraupeticasalvar($ambulatorio_laudo_id, $laudo_id) {

    $this->load->plugin('mpdf');
    $data['laudo'] = $this->laudo->listarteraupeticaimpressao($ambulatorio_laudo_id);
    $data['medico'] = $this->operador_m->medicoreceituario($data['laudo'][0]->medico_parecer1);
    $empresa_id = $this->session->userdata('empresa_id');
    if(!@$empresa_id > 0){
        $empresa_id = 1;
    }
    $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
    $data['empresa'] = $this->guia->listarempresa();
    $data['empresa_m'] = $this->empresa->listarempresamunicipio();
    @$municipio_empresa = @$data['empresa_m'][0]->municipio;
    $data['receituario'] = true;
    $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($empresa_id);
    $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['laudo'][0]->medico_parecer1);
    $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudo($empresa_id);
    $cabecalho_config = $data['cabecalho'][0]->cabecalho;
    $rodape_config = $data['cabecalho'][0]->rodape;

    $dataFuturo = date("Y-m-d");
    $dataAtual = $data['laudo']['0']->nascimento;
    $date_time = new DateTime($dataAtual);
    $diff = $date_time->diff(new DateTime($dataFuturo));
    $teste = $diff->format('%Ya %mm %dd');

    if ($data['laudo'][0]->assinatura == 't') {
        $data['operador_assinatura'] = $data['laudo'][0]->medico_parecer1;
    }

    $base_url = base_url();
    $filename = "terapeuticas_".$ambulatorio_laudo_id.".pdf";


    if ($data['laudo'][0]->assinatura == 't') {
        if (isset($data['laudo'][0]->medico_parecer1)) {
            $this->load->helper('directory');
            $arquivo_pasta = directory_map("./upload/1ASSINATURAS/");
            foreach ($arquivo_pasta as $value) {
                if ($value == $data['laudo'][0]->medico_parecer1 . ".jpg") {
                    $carimbo = "<img width='200px;' height='100px;' src='$base_url" . "upload/1ASSINATURAS/$value' />";
                }
            }
        }
    } elseif ($data['laudo'][0]->carimbo == 't') {
        $carimbo = $data['laudo'][0]->medico_carimbo;
    } else {
        $carimbo = "";
    }
    $data['assinatura'] = $carimbo;

//        var_dump($carimbo);die;
    $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");

    $dia = substr($data['laudo'][0]->data_cadastro, 8, 2);
    $mes = substr($data['laudo'][0]->data_cadastro, 5, 2);
    $ano = substr($data['laudo'][0]->data_cadastro, 0, 4);

    $nomemes = $meses[$mes];



    $texto_rodape = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;

    if ($data['empresa'][0]->ficha_config == 't') {
        if ($data['empresa'][0]->cabecalho_config == 't') {
            if ($data['cabecalhomedico'][0]->cabecalho2 != '') { // Cabeçalho do Profissional 2
                $cabecalho = $data['cabecalhomedico'][0]->cabecalho2;
            } elseif($data['cabecalhomedico'][0]->cabecalho != ''){ // Cabeçalho do Profissional
               $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
           } else {
                $cabecalho = "$cabecalho_config";
            }
//                $cabecalho = $cabecalho_config;
        } else {
            $cabecalho = "<table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'></td></tr></table>";
        }

        if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg") && $data['cabecalhomedico'][0]->cabecalho == '') {
            $cabecalho = '<img width="300px" height="50px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
        }

        if ($data['empresa'][0]->rodape_config == 't') {
            if ($data['cabecalhomedico'][0]->rodape != '') {
                $rodape_config = $data['cabecalhomedico'][0]->rodape;
            }
            $rodape_config = str_replace("_assinatura_", $carimbo, $rodape_config);
            $rodape = $texto_rodape . $rodape_config;
        } else {
            $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td  >$carimbo</td></tr></table><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
        }
        $data['extra'] = "<font size='7'><b>Terapeuticas</b></font><br><br>";
        // var_dump($data['laudo'][0]->texto); die;
//            $cabecalho = "<table ><tr><td><img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'></td></tr><tr><td><center><b><p style='text-align: center; font-weight: bold;'>Receita Médica</p></b></center></td></tr><tr><td>Para:" . $data['laudo']['0']->paciente . "<br></td></tr></table>";
//            $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td ></td></tr></table><div>$carimbo</div><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
        $data['laudo'][0]->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<head>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</head>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<html>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<body>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</html>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</body>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="center"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="left"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="right"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="justify"', '', $data['laudo'][0]->texto);
        // Por causa do Tinymce Novo.
        if (false) {
            // echo 'asduha'; die;
            $html = $this->load->view('ambulatorio/impressaosolicitacaoexamesreceita', $data, true);
        } else {
            $html = $this->load->view('ambulatorio/impressaosolicitacaoexamesconfiguravel', $data, true);
        }
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;

        if ($sem_margins == 't') {
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 0, 0, 0, $laudo_id);
        } else {
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);
        }
    }else{

    if ($data['empresa'][0]->impressao_tipo == 1) {//HUMANA        
        // $filename = "laudo.pdf";
        $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/humana.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
        $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'>";
        $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
        pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);
        // $this->load->View('ambulatorio/impressaoreceituario', $data);
    }

/////////////////////////////////////////////////////////////////////////////////////        
    elseif ($data['empresa'][0]->impressao_tipo == 13) {//CAGE        
        // $filename = "laudo.pdf";
        $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/cage.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
        $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapecage.jpg'>";
        $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
        pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);
        // $this->load->View('ambulatorio/impressaoreceituario', $data);
    }

///////////////////////////////////////////////////////////////////////////////////////////        
    elseif ($data['empresa'][0]->impressao_tipo == 10) {//CDC
        // $filename = "laudo.pdf";
        $cabecalho = "<table><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td><img align = 'left'  width='180px' height='80px' src='img/logo2.png'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
        $rodape = "<table><tr><td>Rua Juiz Renato Silva, 20 - Papicu | Fone (85)3234-3907</td></tr></table>";
        $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
        pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);
        // $this->load->View('ambulatorio/impressaoreceituario', $data);

/////////////////////////////////////////////////////////////////////////////////////////////////            
    } elseif ($data['empresa'][0]->impressao_tipo == 6) {//CLINICA DEZ     
        // $filename = "laudo.pdf";
        $cabecalho = "<table><tr><td><img align = 'left'  src='img/cabecalho.jpg'></td></tr><tr><td>&nbsp;</td></tr><tr><td>Paciente:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
        $rodape = "<table><tr><td><center><img align = 'left'  src='img/rodape.jpg'></center></td></tr></table>";
        $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
        pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);
        // $this->load->View('ambulatorio/impressaoreceituario', $data);
    }

/////////////////////////////////////////////////////////////////////////////////////////////        
    elseif ($data['empresa'][0]->impressao_tipo == 8) {//RONALDO BARREIRA
        $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>
                    <tr><td style='vertical-align: bottom; font-family: serif; font-size: 14pt;' colspan='2'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td></tr>
                    <tr><td colspan='2'><center>Rua 24 de maio, 961 - Fone: 3226-9536<center></td></tr>
                    <tr><td></td><td></td></tr>
                    <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                    <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                    <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                    <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . $data['laudo']['0']->solicitante . "<br></td></tr>
                    </table>";
        if ($data['laudo']['0']->convenio_id >= 29 && $data['laudo']['0']->convenio_id <= 84) {
            $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt;'>
                    <tr><td width='70%' style='vertical-align: bottom; font-family: serif; font-size: 12pt;'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td><td rowspan='2'><center><img align = 'left'  width='140px' height='40px' src='img/sesi.jpg'><center></td></tr>
                    <tr><td ><center>Rua 24 de maio, 961-Fone: 3226-9536<center></td><td></td></tr>            
                    <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                    <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                    <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                    <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . substr($data['laudo']['0']->solicitante, 0, 15) . "<br></td></tr>
                    </table>";
        }
        $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                    <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
        if ($data['laudo']['0']->medico_parecer1 == 929) {
            $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                    <tr><td><center>Radiologista - Leitor Qualificado Padrao OIT</td></tr>
                    <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
        }
        $grupo = $data['laudo']['0']->grupo;
        $html = $this->load->view('ambulatorio/impressaolaudo_2', $data, true);
        pdfreceitassalvas($html, $filename, $cabecalho, $rodape, $grupo, 9, 0, 15, $laudo_id);

///////////////////////////////////////////////////////////////////////////////////////            
    } else {//GERAL        //  este item fica sempre por ultimo
        // $filename = "laudo.pdf";

        if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
            $img = '<img width="700px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
        } else {
            $img = "<img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'>";
        }

        $cabecalho = "<table ><tr><td>" . $img . "</td></tr><tr><td><center><b><p style='text-align: center; font-weight: bold;'></p></b></center></td></tr><tr><td><b>Para:" . $data['laudo']['0']->paciente . "<br></b></td></tr></table>";
        $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td ></td></tr></table><div>$carimbo</div><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
        $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;

        if ($sem_margins == 't') {
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 0, 0, 0, $laudo_id);
        } else {
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);
        }
        // $this->load->View('ambulatorio/impressaoreceituario', $data);
    }
}
}


function impressaorelatoriosalvar($ambulatorio_laudo_id, $laudo_id) {

    $this->load->plugin('mpdf');
    $data['laudo'] = $this->laudo->listarrelatorioimpressao($ambulatorio_laudo_id);
    $data['medico'] = $this->operador_m->medicoreceituario($data['laudo'][0]->medico_parecer1);
    $empresa_id = $this->session->userdata('empresa_id');
    if(!@$empresa_id > 0){
        $empresa_id = 1;
    }
    $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
    $data['empresa'] = $this->guia->listarempresa();
    $data['empresa_m'] = $this->empresa->listarempresamunicipio();
    @$municipio_empresa = @$data['empresa_m'][0]->municipio;
    $data['receituario'] = true;
    $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($empresa_id);
    $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['laudo'][0]->medico_parecer1);
    $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudo($empresa_id);
    $cabecalho_config = $data['cabecalho'][0]->cabecalho;
    $rodape_config = $data['cabecalho'][0]->rodape;

    $dataFuturo = date("Y-m-d");
    $dataAtual = $data['laudo']['0']->nascimento;
    $date_time = new DateTime($dataAtual);
    $diff = $date_time->diff(new DateTime($dataFuturo));
    $teste = $diff->format('%Ya %mm %dd');

    if ($data['laudo'][0]->assinatura == 't') {
        $data['operador_assinatura'] = $data['laudo'][0]->medico_parecer1;
    }

    $base_url = base_url();
    $filename = "relatorios_".$ambulatorio_laudo_id.".pdf";


    if ($data['laudo'][0]->assinatura == 't') {
        if (isset($data['laudo'][0]->medico_parecer1)) {
            $this->load->helper('directory');
            $arquivo_pasta = directory_map("./upload/1ASSINATURAS/");
            foreach ($arquivo_pasta as $value) {
                if ($value == $data['laudo'][0]->medico_parecer1 . ".jpg") {
                    $carimbo = "<img width='200px;' height='100px;' src='$base_url" . "upload/1ASSINATURAS/$value' />";
                }
            }
        }
    } elseif ($data['laudo'][0]->carimbo == 't') {
        $carimbo = $data['laudo'][0]->medico_carimbo;
    } else {
        $carimbo = "";
    }
    $data['assinatura'] = $carimbo;

//        var_dump($carimbo);die;
    $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");

    $dia = substr($data['laudo'][0]->data_cadastro, 8, 2);
    $mes = substr($data['laudo'][0]->data_cadastro, 5, 2);
    $ano = substr($data['laudo'][0]->data_cadastro, 0, 4);

    $nomemes = $meses[$mes];



    $texto_rodape = "{$municipio_empresa}, " . $dia . " de " . $nomemes . " de " . $ano;

    if ($data['empresa'][0]->ficha_config == 't') {
        if ($data['empresa'][0]->cabecalho_config == 't') {
            if ($data['cabecalhomedico'][0]->cabecalho2 != '') { // Cabeçalho do Profissional 2
                $cabecalho = $data['cabecalhomedico'][0]->cabecalho2;
            } elseif($data['cabecalhomedico'][0]->cabecalho != ''){ // Cabeçalho do Profissional
               $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
           } else {
                $cabecalho = "$cabecalho_config";
            }
//                $cabecalho = $cabecalho_config;
        } else {
            $cabecalho = "<table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'></td></tr></table>";
        }

        if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg") && $data['cabecalhomedico'][0]->cabecalho == '') {
            $cabecalho = '<img width="300px" height="50px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
        }

        if ($data['empresa'][0]->rodape_config == 't') {
            if ($data['cabecalhomedico'][0]->rodape != '') {
                $rodape_config = $data['cabecalhomedico'][0]->rodape;
            }
            $rodape_config = str_replace("_assinatura_", $carimbo, $rodape_config);
            $rodape = $texto_rodape . $rodape_config;
        } else {
            $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td  >$carimbo</td></tr></table><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
        }
        $data['extra'] = "<font size='7'><b>Relatório</b></font><br><br>";
        // var_dump($data['laudo'][0]->texto); die;
//            $cabecalho = "<table ><tr><td><img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'></td></tr><tr><td><center><b><p style='text-align: center; font-weight: bold;'>Receita Médica</p></b></center></td></tr><tr><td>Para:" . $data['laudo']['0']->paciente . "<br></td></tr></table>";
//            $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td ></td></tr></table><div>$carimbo</div><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
        $data['laudo'][0]->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<head>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</head>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<html>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<body>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</html>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</body>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="center"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="left"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="right"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="justify"', '', $data['laudo'][0]->texto);
        // Por causa do Tinymce Novo.
        if (false) {
            // echo 'asduha'; die;
            $html = $this->load->view('ambulatorio/impressaosolicitacaoexamesreceita', $data, true);
        } else {
            $html = $this->load->view('ambulatorio/impressaosolicitacaoexamesconfiguravel', $data, true);
        }
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;

        if ($sem_margins == 't') {
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 0, 0, 0, $laudo_id);
        } else {
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);
        }
    } else{

    if ($data['empresa'][0]->impressao_tipo == 1) {//HUMANA        
        // $filename = "laudo.pdf";
        $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/humana.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
        $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'>";
        $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
        pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);
        // $this->load->View('ambulatorio/impressaoreceituario', $data);
    }

/////////////////////////////////////////////////////////////////////////////////////        
    elseif ($data['empresa'][0]->impressao_tipo == 13) {//CAGE        
        // $filename = "laudo.pdf";
        $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/cage.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
        $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapecage.jpg'>";
        $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
        pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);
        // $this->load->View('ambulatorio/impressaoreceituario', $data);
    }

///////////////////////////////////////////////////////////////////////////////////////////        
    elseif ($data['empresa'][0]->impressao_tipo == 10) {//CDC
        // $filename = "laudo.pdf";
        $cabecalho = "<table><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td><img align = 'left'  width='180px' height='80px' src='img/logo2.png'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
        $rodape = "<table><tr><td>Rua Juiz Renato Silva, 20 - Papicu | Fone (85)3234-3907</td></tr></table>";
        $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
        pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);
        // $this->load->View('ambulatorio/impressaoreceituario', $data);

/////////////////////////////////////////////////////////////////////////////////////////////////            
    } elseif ($data['empresa'][0]->impressao_tipo == 6) {//CLINICA DEZ     
        // $filename = "laudo.pdf";
        $cabecalho = "<table><tr><td><img align = 'left'  src='img/cabecalho.jpg'></td></tr><tr><td>&nbsp;</td></tr><tr><td>Paciente:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
        $rodape = "<table><tr><td><center><img align = 'left'  src='img/rodape.jpg'></center></td></tr></table>";
        $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
        pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);
        // $this->load->View('ambulatorio/impressaoreceituario', $data);
    }

/////////////////////////////////////////////////////////////////////////////////////////////        
    elseif ($data['empresa'][0]->impressao_tipo == 8) {//RONALDO BARREIRA
        $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>
                    <tr><td style='vertical-align: bottom; font-family: serif; font-size: 14pt;' colspan='2'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td></tr>
                    <tr><td colspan='2'><center>Rua 24 de maio, 961 - Fone: 3226-9536<center></td></tr>
                    <tr><td></td><td></td></tr>
                    <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                    <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                    <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                    <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . $data['laudo']['0']->solicitante . "<br></td></tr>
                    </table>";
        if ($data['laudo']['0']->convenio_id >= 29 && $data['laudo']['0']->convenio_id <= 84) {
            $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt;'>
                    <tr><td width='70%' style='vertical-align: bottom; font-family: serif; font-size: 12pt;'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td><td rowspan='2'><center><img align = 'left'  width='140px' height='40px' src='img/sesi.jpg'><center></td></tr>
                    <tr><td ><center>Rua 24 de maio, 961-Fone: 3226-9536<center></td><td></td></tr>            
                    <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                    <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                    <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                    <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . substr($data['laudo']['0']->solicitante, 0, 15) . "<br></td></tr>
                    </table>";
        }
        $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                    <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
        if ($data['laudo']['0']->medico_parecer1 == 929) {
            $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                    <tr><td><center>Radiologista - Leitor Qualificado Padrao OIT</td></tr>
                    <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
        }
        $grupo = $data['laudo']['0']->grupo;
        $html = $this->load->view('ambulatorio/impressaolaudo_2', $data, true);
        pdfreceitassalvas($html, $filename, $cabecalho, $rodape, $grupo, 9, 0, 15, $laudo_id);

///////////////////////////////////////////////////////////////////////////////////////            
    } else {//GERAL        //  este item fica sempre por ultimo
        // $filename = "laudo.pdf";

        if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
            $img = '<img width="700px" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
        } else {
            $img = "<img align = 'left'  width='1000px' height='300px' src='img/cabecalho.jpg'>";
        }

        $cabecalho = "<table ><tr><td>" . $img . "</td></tr><tr><td><center><b><p style='text-align: center; font-weight: bold;'></p></b></center></td></tr><tr><td><b>Para:" . $data['laudo']['0']->paciente . "<br></b></td></tr></table>";
        $rodape = "<table><tr><td>$texto_rodape</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td ></td></tr></table><div>$carimbo</div><table><tr><td><img align = 'left'  width='1000px' height='300px' src='img/rodape.jpg'></td></tr></table>";
        $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;

        if ($sem_margins == 't') {
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 0, 0, 0, $laudo_id);
        } else {
            pdfreceitassalvas($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $laudo_id);
        }
        // $this->load->View('ambulatorio/impressaoreceituario', $data);
    }
  }
}

function gravarreceituarioatendimentosimples($ambulatorio_laudo_id, $receituario = null, $especial = NULL, $id = NULL, $adendo = false) {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            if($receituario == null){
                $this->db->set('texto', $_POST['receituario_simples']);
            }else{
                $this->db->set('texto', $receituario);
            }
            if (isset($_POST['carimbo_receituario_simples'])) {
                $this->db->set('carimbo', 't');
            }
            if (isset($_POST['assinatura_receituario_simples'])) {
                $this->db->set('assinatura', 't');
            }
            
            $this->db->set('laudo_id', $ambulatorio_laudo_id);
            $this->db->set('medico_parecer1', $_POST['medico']);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $_POST['medico']);
            $this->db->set('tipo', 'NORMAL');
            $this->db->set('receita_id', $id);
            if($adendo){
                $this->db->set('adendo', 't');
            } 
            $this->db->insert('tb_ambulatorio_receituario');

            $insert_id = $this->db->insert_id();
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return $insert_id;
        } catch (Exception $exc) {
            return -1;
        }
    }
    
    
    function gravarreceituarioatendimentoespecial($ambulatorio_laudo_id, $receituario = null, $especial = NULL, $id = NULL, $adendo = false) {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            if($receituario == null){
                $this->db->set('texto', $_POST['receituario_especial']);
            }else{
                $this->db->set('texto', $receituario);
            }
            if (isset($_POST['carimbo_receituario_especial'])) {
                $this->db->set('carimbo', 't');
            }
            if (isset($_POST['assinatura_receituario_especial'])) {
                $this->db->set('assinatura', 't');
            }
            
            $this->db->set('especial', 't'); 
            
            $this->db->set('laudo_id', $ambulatorio_laudo_id);
            $this->db->set('medico_parecer1', $_POST['medico']);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $_POST['medico']);
            $this->db->set('tipo', 'NORMAL');
            $this->db->set('receita_id', $id);
            if($adendo){
                $this->db->set('adendo', 't');
            }

            $this->db->insert('tb_ambulatorio_receituario');

            $insert_id = $this->db->insert_id();
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            return $insert_id;
        } catch (Exception $exc) {
            return -1;
        }
    }
    
    
    function gravarimpressaoatestado(){
            
        $horario  = date('Y-m-d H:i:s');
        $operador = $this->session->userdata('operador_id');
        $this->db->set('texto', json_encode($_POST));
        $this->db->set('data_cadastro',$horario);
        $this->db->set('operador_cadastro',$operador); 
        $this->db->set('agenda_exames_id',$_POST['agenda_exames_id']); 
        $this->db->set('empresa_id',$_POST['empresa_id']); 
        $this->db->insert('tb_impressao_atestado'); 
        return $this->db->insert_id();
    }
    
    function listarimpressaoatestado($impressao_atestado){ 
        $horario  = date('Y-m-d H:i:s');
        $operador = $this->session->userdata('operador_id');
        $this->db->select('ia.texto,pt.nome as procedimento,ia.empresa_id,p.nome as paciente,ae.data_autorizacao');    
        $this->db->from('tb_impressao_atestado ia'); 
        $this->db->join('tb_agenda_exames ae','ae.agenda_exames_id = ia.agenda_exames_id','left');
        $this->db->join('tb_procedimento_convenio pc','pc.procedimento_convenio_id = ae.procedimento_tuss_id','left');
        $this->db->join('tb_procedimento_tuss pt','pt.procedimento_tuss_id = pc.procedimento_tuss_id','left');
        $this->db->join('tb_paciente p','p.paciente_id = ae.paciente_id','left');
        
        $this->db->where('ia.impressao_atestado_id',$impressao_atestado);  
        return $this->db->get()->result();
    } 
    
    
     function listardatashistoricotipo($paciente_id,$tipo=NULL) {  
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select("distinct(to_char(ag.data_cadastro, 'YYYY-MM-DD')) as data_cadastro",false);
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
        if($tipo != ""){
            if($tipo == "exame"){
                $this->db->where('agr.tipo','EXAME');
            }else{
                $this->db->where('agr.tipo !=','EXAME');
            }
        }
        $this->db->where("ag.cancelada", 'false');
        $this->db->orderby("to_char(ag.data_cadastro, 'YYYY-MM-DD') desc");
        $return = $this->db->get()->result();
            
        return $return;
    }
    
     function listarreceitatipo($paciente_id,$dataescolhida,$tipo=NULL) {

        $this->db->select(' ag.ambulatorio_receituario_id,
                            ag.texto,
                            ag.data_cadastro,
                            ag.medico_parecer1,
                            ag.especial,
                            al.cabecalho,
                            o.nome as medico,
                            o.operador_id,
                            pt.nome as procedimento
                            ');
        $this->db->from('tb_ambulatorio_receituario ag');
        $this->db->join('tb_ambulatorio_laudo al', 'al.ambulatorio_laudo_id = ag.laudo_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = al.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ag.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_ambulatorio_grupo agr', 'agr.nome = pt.grupo', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = al.medico_parecer1', 'left');
        
        $this->db->join('tb_exames ae', 'ae.exames_id = al.exame_id', 'left');
        $this->db->join('tb_agenda_exames age', 'age.agenda_exames_id = ae.agenda_exames_id', 'left');
        
        $this->db->join('tb_procedimento_convenio pc2', 'pc2.procedimento_convenio_id = age.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt2', 'pt2.procedimento_tuss_id = pc2.procedimento_tuss_id', 'left');
        $this->db->join('tb_ambulatorio_grupo agr2', 'agr2.nome = pt2.grupo', 'left');
        
        
        $this->db->where('al.paciente_id', $paciente_id);
        $this->db->where('ag.tipo', 'NORMAL');
        
        $this->db->where('al.data_cadastro >=', $dataescolhida . " " . "00:00:00");
        $this->db->where('al.data_cadastro <=', $dataescolhida . " " . "23:59:59");
        
         if($tipo != ""){
            if($tipo == "exame"){
                $this->db->where('agr2.tipo','EXAME');
            }else{
                $this->db->where('agr2.tipo !=','EXAME');
            }
        }
        $this->db->orderby('ag.data_cadastro DESC');

        $return = $this->db->get();
        return $return->result();
    }
    
            

}

?>
