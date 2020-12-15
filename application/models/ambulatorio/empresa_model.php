<?php

class empresa_model extends Model {

    var $_empresa_id = null;
    var $_nome = null;
    var $_razao_social = null;
    var $_cnpj = null;
    var $_celular = null;
    var $_telefone = null;
    var $_tipo_logradouro_id = null;
    var $_logradouro = null;
    var $_numero = null;
    var $_bairro = null;
    var $_complemento = null;
    var $_municipio_id = null;
    var $_codigo_ibge = null;
    var $_cep = null;
    var $_chat = null;
    var $_servicoemail = null;
    var $_servicosms = null;
    var $_cnes = null;
    var $_botao_faturar_guia = null;
    var $_botao_faturar_proc = null;

    function Empresa_model($exame_empresa_id = null) {
        parent::Model();
        if (isset($exame_empresa_id)) {
            $this->instanciar($exame_empresa_id);
        }
    }

    function listar($args = array()) {

        $perfil_id = $this->session->userdata('perfil_id');
//        $operador_id = $this->session->userdata('operador_id');
        $empresa_id = $this->session->userdata('empresa_id');

        $this->db->select('empresa_id,
                            nome,
                            razao_social,
                            cnpj');
        $this->db->from('tb_empresa');
        if ($perfil_id != 1) {
            $this->db->where('empresa_id', $empresa_id);
        }
        $this->db->where('ativo', 't');
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('nome ilike', $args['nome'] . "%");
        }
        return $this->db;
    }

    function listartotensetor($args = array()) {

        $operador_id = $this->session->userdata('operador_id');
        $empresa_id = $this->session->userdata('empresa_id');

        $this->db->select('ts.toten_setor_id,
                            ts.nome,
                            ts.sigla,
                            ts.toten_webService_id,
                            e.nome as empresa');
        $this->db->from('tb_toten_setor ts');
        $this->db->join('tb_empresa e', "e.empresa_id = ts.empresa_id", 'left');
        if ($operador_id != 1) {
            $this->db->where('empresa_id', $empresa_id);
        }
        $this->db->where('ts.ativo', 't');
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('ts.nome ilike', $args['nome'] . "%");
        }
        return $this->db;
    }

    function listarempresasativo() {

        $this->db->select('empresa_id,
            razao_social,
            producaomedicadinheiro,
            nome');
        $this->db->from('tb_empresa');
        $this->db->where("ativo", 't');
        $this->db->orderby('empresa_id');
        $return = $this->db->get();
        return $return->result();
    }

    function listarlembretes($args = array()) {

        $operador_id = $this->session->userdata('operador_id');
        $empresa_id = $this->session->userdata('empresa_id');

        $this->db->select(" el.empresa_lembretes_id,
                            el.texto,
                            el.perfil_destino,
                            el.operador_destino,
                            el.ativo,
                            o.nome as operador,
                            (
                                SELECT COUNT(*) 
                                FROM ponto.tb_empresa_lembretes_visualizacao 
                                WHERE ponto.tb_empresa_lembretes_visualizacao.empresa_lembretes_id = el.empresa_lembretes_id 
                            ) as visualizado");
        $this->db->from('tb_empresa_lembretes el');
        $this->db->join('tb_operador o', "o.operador_id = el.operador_destino");
        $this->db->where('el.empresa_id', $empresa_id);

        if (isset($args['texto']) && strlen(@$args['texto']) > 0) {
            $this->db->where('el.texto ilike', "%" . $args['texto'] . "%");
        }

        if (@$args['operador_id'] != '') {
            $this->db->where('el.operador_destino', $args['operador_id']);
        }

        if (@$args['perfil_id'] != '') {
            $this->db->where('el.perfil_destino', $args['perfil_id']);
        }

        return $this->db;
    }

    function listarnumeroindentificacaosms() {

        $this->db->select('nome_empresa, numero_indentificacao');
        $this->db->from('tb_empresas_indentificacao_sms');
        $return = $this->db->get();
        return $return->result();
    }

    function listartemplateanamnese() {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ta.template_anamnese_id, ta.grupo, ta.nome_template, ta.template, ta.data_cadastro, ta.operador_cadastro');
        $this->db->from('tb_template_anamnese ta');
        $this->db->join('tb_empresa e', 'e.empresa_id = ta.empresa_id', 'left');
        $this->db->where('ta.ativo', 't');
//        $this->db->where('paciente_id', $paciente_id);
//        $this->db->where('data_criacao', $data);
        return $this->db;
    }

    function listartemplateanamneseform($template_id) {
        $this->db->select('ta.template_anamnese_id, ta.grupo, ta.nome_template, ta.template, ta.data_cadastro, ta.operador_cadastro');
        $this->db->from('tb_template_anamnese ta');
        $this->db->join('tb_empresa e', 'e.empresa_id = ta.empresa_id', 'left');
        $this->db->where('ta.ativo', 't');
        $this->db->where('ta.template_anamnese_id', $template_id);
//        $this->db->where('data_criacao', $data);
        $return = $this->db->get();
        return $return->result();
    }

    function listartemplateanamneseatendimento($grupo) {
        $this->db->select('ta.template_anamnese_id, ta.grupo, ta.nome_template, ta.template, ta.data_cadastro, ta.operador_cadastro');
        $this->db->from('tb_template_anamnese ta');
        $this->db->join('tb_empresa e', 'e.empresa_id = ta.empresa_id', 'left');
        $this->db->where('ta.ativo', 't');
        $this->db->where('ta.grupo', $grupo);
//        $this->db->where('data_criacao', $data);
        $return = $this->db->get();
        return $return->result();
    }

    function listarconfiguracaoimpressao() {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ei.empresa_impressao_cabecalho_id,ei.cabecalho,ei.rodape, e.nome as empresa');
        $this->db->from('tb_empresa_impressao_cabecalho ei');
        $this->db->join('tb_empresa e', 'e.empresa_id = ei.empresa_id', 'left');
        $this->db->where('ei.empresa_id', $empresa_id);
//        $this->db->where('paciente_id', $paciente_id);
//        $this->db->where('data_criacao', $data);
        return $this->db;
    }

    function listarconfiguracaoimpressaolaudo() {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ei.empresa_impressao_laudo_id,ei.nome as nome_laudo, ei.cabecalho,ei.ativo,ei.rodape, e.nome as empresa');
        $this->db->from('tb_empresa_impressao_laudo ei');
        $this->db->join('tb_empresa e', 'e.empresa_id = ei.empresa_id', 'left');
        $this->db->where('ei.empresa_id', $empresa_id);
//        $this->db->where('paciente_id', $paciente_id);
//        $this->db->where('data_criacao', $data);
        return $this->db;
    }

    function listarconfiguracaoimpressaolaudoform($empresa_impressao_cabecalho_id) {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ei.empresa_impressao_laudo_id, ei.nome as nome_laudo,ei.texto,ei.adicional_cabecalho, ei.cabecalho,ei.rodape, e.nome as empresa');
        $this->db->from('tb_empresa_impressao_laudo ei');
        $this->db->join('tb_empresa e', 'e.empresa_id = ei.empresa_id', 'left');
        $this->db->where('ei.empresa_impressao_laudo_id', $empresa_impressao_cabecalho_id);
//        $this->db->where('paciente_id', $paciente_id);
//        $this->db->where('data_criacao', $data);
        $return = $this->db->get();
        return $return->result();
    }

    function listarconfiguracaoimpressaointernacao() {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ei.empresa_impressao_internacao_id,ei.nome as nome_internacao, ei.cabecalho,ei.ativo,ei.rodape, e.nome as empresa');
        $this->db->from('tb_empresa_impressao_internacao ei');
        $this->db->join('tb_empresa e', 'e.empresa_id = ei.empresa_id', 'left');
        $this->db->where('ei.empresa_id', $empresa_id);
        $this->db->where('ei.ativo', 't');
//        $this->db->where('paciente_id', $paciente_id);
//        $this->db->where('data_criacao', $data);
        return $this->db;
    }

    function listarconfiguracaoimpressaointernacaoform($empresa_impressao_cabecalho_id) {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ei.empresa_impressao_internacao_id, ei.nome as nome_internacao,ei.texto,ei.adicional_cabecalho, ei.cabecalho,ei.rodape, e.nome as empresa');
        $this->db->from('tb_empresa_impressao_internacao ei');
        $this->db->join('tb_empresa e', 'e.empresa_id = ei.empresa_id', 'left');
        $this->db->where('ei.empresa_impressao_internacao_id', $empresa_impressao_cabecalho_id);
//        $this->db->where('paciente_id', $paciente_id);
//        $this->db->where('data_criacao', $data);
        $return = $this->db->get();
        return $return->result();
    }

    function listarconfiguracaoimpressaoorcamento() {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ei.empresa_impressao_orcamento_id,ei.nome as nome_orcamento, ei.cabecalho,ei.ativo,ei.rodape, e.nome as empresa');
        $this->db->from('tb_empresa_impressao_orcamento ei');
        $this->db->join('tb_empresa e', 'e.empresa_id = ei.empresa_id', 'left');
        $this->db->where('ei.empresa_id', $empresa_id);
//        $this->db->where('paciente_id', $paciente_id);
//        $this->db->where('data_criacao', $data);
        return $this->db;
    }

    function carregarlistarpostsblog($posts_blog_id) {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('posts_blog_id, titulo, corpo_html, data_cadastro');
        $this->db->from('tb_posts_blog e');
        // $this->db->where('e.ativo', 't');
        $this->db->where('posts_blog_id', $posts_blog_id);
//        $this->db->where('data_criacao', $data);
        $return = $this->db->get();
        return $return->result();
    }

    function listarpostsblog() {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('posts_blog_id, titulo, corpo_html, data_cadastro');
        $this->db->from('tb_posts_blog e');
        $this->db->where('e.ativo', 't');
//        $this->db->where('paciente_id', $paciente_id);
//        $this->db->where('data_criacao', $data);
        return $this->db;
    }

    function listarpesquisasatisfacao() {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('pp.paciente_pesquisa_satisfacao_id, pp.paciente_id, p.nome as paciente, pp.questionario, pp.data_cadastro');
        $this->db->from('tb_paciente_pesquisa_satisfacao pp');
        $this->db->join('tb_paciente p', 'p.paciente_id = pp.paciente_id', 'left');
        $this->db->where('pp.ativo', 't');
//        $this->db->where('paciente_id', $paciente_id);
//        $this->db->where('data_criacao', $data);
        return $this->db;
    }

    function listarsolicitacaoagendamento($args = array()) {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('pp.paciente_solicitar_agendamento_id, 
                            pp.paciente_id, 
                            pp.data, 
                            pp.hora, 
                            p.nome as paciente,
                            c.nome as convenio, 
                            pt.nome as procedimento, 
                            pp.convenio_text, 
                            pp.procedimento_text, 
                            pp.data_cadastro,
                            pp.turno,
                            pp.observacao,
                            o.nome as medico');
        $this->db->from('tb_paciente_solicitar_agendamento pp');
        $this->db->join('tb_paciente p', 'p.paciente_id = pp.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = pp.procedimento_convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = pp.medico_id', 'left');
        $this->db->where('pp.ativo', 't');
        $this->db->where('pp.confirmado', 'f');
        
        if (isset($args['paciente']) && strlen($args['paciente']) > 0) {
            $this->db->where('p.nome ilike', "%" . $args['paciente'] . "%");
        }
        if (isset($args['operador']) && strlen($args['operador']) > 0) {
            $this->db->where('pp.medico_id', $args['operador']);
        }
        if (isset($args['data_inicio']) && strlen($args['data_inicio']) > 0) {
            $data_inicial = date("Y-m-d", strtotime(str_replace('/', '-', $args['data_inicio'])));

            $this->db->where('pp.data', $data_inicial);
        }

        return $this->db;
    }

    function detalhespesquisasatisfacao($pesquisa_id) {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('pp.paciente_pesquisa_satisfacao_id, pp.paciente_id, p.nome as paciente, pp.questionario, pp.data_cadastro');
        $this->db->from('tb_paciente_pesquisa_satisfacao pp');
        $this->db->join('tb_paciente p', 'p.paciente_id = pp.paciente_id', 'left');
        $this->db->where('pp.paciente_pesquisa_satisfacao_id', $pesquisa_id);
//        $this->db->where('paciente_id', $paciente_id);
//        $this->db->where('data_criacao', $data);
        $return = $this->db->get();
        return $return->result();
    }

    function detalhesriscocirurgico($paciente_id) {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('pp.paciente_risco_cirurgico_id, pp.paciente_id, p.nome as paciente, pp.questionario, pp.data_cadastro');
        $this->db->from('tb_paciente_risco_cirurgico pp');
        $this->db->join('tb_paciente p', 'p.paciente_id = pp.paciente_id', 'left');
        $this->db->where('pp.paciente_id', $paciente_id);
//        $this->db->where('paciente_id', $paciente_id);
        $this->db->orderby('pp.data_cadastro desc');
        $return = $this->db->get();
        return $return->result();
    }

    function listarconfiguracaoimpressaorecibo() {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ei.empresa_impressao_recibo_id,ei.nome as nome_recibo, ei.cabecalho,ei.ativo,ei.rodape, e.nome as empresa');
        $this->db->from('tb_empresa_impressao_recibo ei');
        $this->db->join('tb_empresa e', 'e.empresa_id = ei.empresa_id', 'left');
        $this->db->where('ei.empresa_id', $empresa_id);
//        $this->db->where('paciente_id', $paciente_id);
//        $this->db->where('data_criacao', $data);
        return $this->db;
    }

    function listarconfiguracaoimpressaoorcamentoform($empresa_impressao_cabecalho_id) {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ei.empresa_impressao_orcamento_id, ei.nome as nome_orcamento,ei.texto, ei.cabecalho,ei.rodape, e.nome as empresa');
        $this->db->from('tb_empresa_impressao_orcamento ei');
        $this->db->join('tb_empresa e', 'e.empresa_id = ei.empresa_id', 'left');
        $this->db->where('ei.empresa_impressao_orcamento_id', $empresa_impressao_cabecalho_id);
//        $this->db->where('paciente_id', $paciente_id);
//        $this->db->where('data_criacao', $data);
        $return = $this->db->get();
        return $return->result();
    }

    function listarconfiguracaoimpressaoreciboform($empresa_impressao_cabecalho_id) {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ei.empresa_impressao_recibo_id, ei.repetir_recibo, ei.nome as nome_recibo,ei.texto, ei.cabecalho,ei.rodape, e.nome as empresa, linha_procedimento');
        $this->db->from('tb_empresa_impressao_recibo ei');
        $this->db->join('tb_empresa e', 'e.empresa_id = ei.empresa_id', 'left');
        $this->db->where('ei.empresa_impressao_recibo_id', $empresa_impressao_cabecalho_id);
//        $this->db->where('paciente_id', $paciente_id);
//        $this->db->where('data_criacao', $data);
        $return = $this->db->get();
        return $return->result();
    }

    function listarconfiguracaoimpressaoencaminhamento() {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ei.empresa_impressao_encaminhamento_id,ei.nome as nome_encaminhamento, ei.cabecalho,ei.ativo,ei.rodape, e.nome as empresa');
        $this->db->from('tb_empresa_impressao_encaminhamento ei');
        $this->db->join('tb_empresa e', 'e.empresa_id = ei.empresa_id', 'left');
        $this->db->where('ei.empresa_id', $empresa_id);
//        $this->db->where('paciente_id', $paciente_id);
//        $this->db->where('data_criacao', $data);
        return $this->db;
    }

    function listarconfiguracaoimpressaoencaminhamentoform($empresa_impressao_cabecalho_id) {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ei.empresa_impressao_encaminhamento_id, ei.nome as nome_encaminhamento,ei.texto, ei.cabecalho,ei.rodape, e.nome as empresa');
        $this->db->from('tb_empresa_impressao_encaminhamento ei');
        $this->db->join('tb_empresa e', 'e.empresa_id = ei.empresa_id', 'left');
        $this->db->where('ei.empresa_impressao_encaminhamento_id', $empresa_impressao_cabecalho_id);
//        $this->db->where('paciente_id', $paciente_id);
//        $this->db->where('data_criacao', $data);
        $return = $this->db->get();
        return $return->result();
    }

    function listarconfiguracaoimpressaocabecalho($empresa_impressao_cabecalho_id) {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ei.empresa_impressao_cabecalho_id,ei.cabecalho,ei.rodape,ei.timbrado, e.nome as empresa');
        $this->db->from('tb_empresa_impressao_cabecalho ei');
        $this->db->join('tb_empresa e', 'e.empresa_id = ei.empresa_id', 'left');
        $this->db->where('ei.empresa_impressao_cabecalho_id', $empresa_impressao_cabecalho_id);
//        $this->db->where('paciente_id', $paciente_id);
//        $this->db->where('data_criacao', $data);
        $return = $this->db->get();
        return $return->result();
    }

    function pacotesms() {

        $this->db->select('descricao_pacote, pacote_sms_id');
        $this->db->from('tb_pacote_sms');
        $this->db->where('ativo', 't');
        $return = $this->db->get();
        return $return->result();
    }

    function listarpacs() {

        $this->db->select('*');
        $this->db->from('tb_pacs');
//        $this->db->where('ativo', 't');
        $return = $this->db->get();
        return $return->result();
    }

    function listarempresas() {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('exame_empresa_id,
                            nome, tipo');
        $this->db->from('tb_exame_empresa');
        $this->db->orderby('nome');
        $this->db->where('empresa_id', $empresa_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listarempresatoten() {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('endereco_toten,
                            impressao_laudo,
                            nome');
        $this->db->from('tb_empresa');
        $this->db->orderby('nome');
        $this->db->where('empresa_id', $empresa_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listarempresasprocedimento() {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('empresa_id,
                            nome');
        $this->db->from('tb_empresa');
        $this->db->where('ativo', 't');
        $this->db->orderby('nome');

        $return = $this->db->get();
        return $return->result();
    }

    function listarinformacaolembrete($empresa_id) {
//        $empresa_id = $this->session->userdata('empresa_id');

        $this->db->select('texto');
        $this->db->from('tb_empresa_lembretes_aniversario');
        $this->db->where('empresa_id', $empresa_id);
        $this->db->where('ativo', 't');
        $return = $this->db->get();
        return $return->result();
    }

    function listarinformacaoemail($empresa_id) {
//        $empresa_id = $this->session->userdata('empresa_id');

        $this->db->select(' email_mensagem_confirmacao,
                            email_mensagem_agradecimento,
                            email_mensagem_falta');
        $this->db->from('tb_empresa');
        $this->db->where('empresa_id', $empresa_id);
        $this->db->where('ativo', 't');
        $return = $this->db->get();
        return $return->result();
    }

    function listarinformacaosms($empresa_id) {
//        $empresa_id = $this->session->userdata('empresa_id');

        $this->db->select(' pacote_id,
                            empresa_sms_id,
                            numero_indentificacao_sms,
                            enviar_excedentes,
                            endereco_externo,
                            remetente_sms,
                            mensagem_revisao, 
                            mensagem_confirmacao, 
                            mensagem_agradecimento,
                            mensagem_aniversariante');
        $this->db->from('tb_empresa_sms');
        $this->db->where('empresa_id', $empresa_id);
        $this->db->where('ativo', 't');
        $this->db->limit(1);
        $return = $this->db->get();
        return $return->result();
    }

    function listarempresamunicipio() {
        $empresa_id = $this->session->userdata('empresa_id');
        if(!@$empresa_id > 0){
            $empresa_id = 1;
        }
        $this->db->select('e.razao_social,
                            e.logradouro,
                            e.numero,
                            e.nome,
                            m.nome as municipio,
                            e.bairro');
        $this->db->from('tb_empresa e');
        $this->db->join('tb_municipio m', 'm.municipio_id = e.municipio_id', 'left');
        $this->db->where('empresa_id', $empresa_id);
        $this->db->orderby('empresa_id');
        $return = $this->db->get();
        return $return->result();
    }

    function listaripservidor() {
        $empresa_id = $this->session->userdata('empresa_id');

        $this->db->select('');
        $this->db->from('tb_empresas_acesso_servidores');
//        $this->db->where('empresa_id', $empresa_id);
//        $this->db->where('ativo', 't');
//        $this->db->limit(1);
        $return = $this->db->get();
        return $return->result();
    }

    function buscandolembreteoperador() {

        $operador_id = $this->session->userdata('operador_id');
        $empresa_id = $this->session->userdata('empresa_id');

        $this->db->select('empresa_lembretes_id,
                            texto,
                            (
                                SELECT COUNT(*) 
                                FROM ponto.tb_empresa_lembretes_visualizacao 
                                WHERE ponto.tb_empresa_lembretes_visualizacao.empresa_lembretes_id = el.empresa_lembretes_id 
                                AND ponto.tb_empresa_lembretes_visualizacao.operador_visualizacao = ' . $operador_id . '
                            ) as visualizado');
        $this->db->from('tb_empresa_lembretes el');
        $this->db->where('ativo', 't');
        $this->db->where('operador_destino', $operador_id);
        $this->db->where("(empresa_id = $empresa_id OR empresa_id is null)");
        $return = $this->db->get();
        $retorno = $return->result();

        return $retorno;
    }

    function buscandolembreteaniversariooperador() {

        $operador_id = $this->session->userdata('operador_id');
        $empresa_id = $this->session->userdata('empresa_id');

        $this->db->select('empresa_lembretes_aniversario_id,
                            texto,
                            aniversario,
                            (
                                SELECT COUNT(*) 
                                FROM ponto.tb_empresa_lembretesaniv_visualizacao 
                                WHERE ponto.tb_empresa_lembretesaniv_visualizacao.empresa_lembretes_aniversario_id = ela.empresa_lembretes_aniversario_id 
                                AND ponto.tb_empresa_lembretesaniv_visualizacao.operador_visualizacao = ' . $operador_id . '
                            ) as visualizado');
        $this->db->from('tb_empresa_lembretes_aniversario ela');
        $this->db->where('ativo', 't');
        $this->db->where('operador_destino', $operador_id);
        $this->db->where('empresa_id', $empresa_id);
        $this->db->where('aniversario is not null');
        $this->db->orderby('visualizado');
        $return = $this->db->get();
        $retorno = $return->result();

        return $retorno;
    }

    function visualizalembrete() {

        $operador_id = $this->session->userdata('operador_id');
        $empresa_id = $this->session->userdata('empresa_id');
        $horario = date("Y-m-d H:i:s");

        $this->db->set('empresa_lembretes_id', $_GET['lembretes_id']);
        $this->db->set('data_visualizacao', $horario);
        $this->db->set('operador_visualizacao', $operador_id);
        $this->db->set('empresa_id', $empresa_id);
        $this->db->insert('tb_empresa_lembretes_visualizacao');
    }

    function visualizalembreteaniv() {

        $operador_id = $this->session->userdata('operador_id');
        $empresa_id = $this->session->userdata('empresa_id');
        $horario = date("Y-m-d H:i:s");

        $this->db->set('empresa_lembretes_aniversario_id', $_GET['lembretes_id']);
        $this->db->set('data_visualizacao', $horario);
        $this->db->set('operador_visualizacao', $operador_id);
        $this->db->set('empresa_id', $empresa_id);
        $this->db->insert('tb_empresa_lembretesaniv_visualizacao');
    }

    function listarempresa($empresa_id) {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('exame_empresa_id,
                            nome,
                            impressao_orcamento,
                            tipo');
        $this->db->from('tb_exame_empresa');
        $this->db->where('exame_empresa_id', $empresa_id);
        $this->db->where('empresa_id', $empresa_id);
        $return = $this->db->get();
        return $return->result();
    }

    function excluirlembrete($empresa_lembretes_id) {

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('empresa_lembretes_id', $empresa_lembretes_id);
        $this->db->update('tb_empresa_lembretes');
        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return false;
        else
            return true;
    }

    function ativarconfiguracaolaudo($impressao_id) {
//        var_dump($impressao_id); die;
        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        $this->db->set('ativo', 't');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('empresa_impressao_laudo_id', $impressao_id);
        $this->db->update('tb_empresa_impressao_laudo');


        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('empresa_impressao_laudo_id !=', $impressao_id);
        $this->db->update('tb_empresa_impressao_laudo');
        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return false;
        else
            return true;
    }

    function excluirtemplateconsulta($impressao_id) {
//        var_dump($impressao_id); die;
        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('template_anamnese_id', $impressao_id);
        $this->db->update('tb_template_anamnese');

        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return false;
        else
            return true;
    }

    function excluirconfiguracaointernacao($impressao_id) {
//        var_dump($impressao_id); die;
        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('empresa_impressao_internacao_id', $impressao_id);
        $this->db->update('tb_empresa_impressao_internacao');

        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return false;
        else
            return true;
    }

    function ativarconfiguracaoorcamento($impressao_id) {
//        var_dump($impressao_id); die;
        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        $this->db->set('ativo', 't');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('empresa_impressao_orcamento_id', $impressao_id);
        $this->db->update('tb_empresa_impressao_orcamento');


        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('empresa_impressao_orcamento_id !=', $impressao_id);
        $this->db->update('tb_empresa_impressao_orcamento');
        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return false;
        else
            return true;
    }

    function excluir($exame_empresa_id) {

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('exame_empresa_id', $exame_empresa_id);
        $this->db->update('tb_exame_empresa');
        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return false;
        else
            return true;
    }

    function gravarlembrete($empresa_lembretes_id = NULL) {
        $perfil_id = $_POST['perfil_id'];
        $operador_id2 = $_POST['operador_id'];

        if ($_POST['perfil_id'] == "" && $_POST['operador_id'] == "") {


            return false;
        } else if ($_POST['perfil_id'] == "" && $_POST['perfil_id'] != "") {
            return false;
        } else if ($_POST['perfil_id'] != "" && $_POST['perfil_id'] == "") {
            
        } else {




            $sql = $this->db->select('operador_id, nome, perfil_id');
            $this->db->from('tb_operador o');


            if (!in_array('TODOS2', $_POST['perfil_id'])) {
                $this->db->where_in('perfil_id', $_POST['perfil_id']);
            }

            if (!in_array('TODOS', $_POST['operador_id'])) {
                $this->db->where_in('operador_id', $operador_id2);
            }

            $this->db->where('o.ativo', 't');
            $this->db->where('o.usuario IS NOT NULL');
            $return = $sql->get()->result();




// echo "<pre>";
// print_r($_POST['perfil_id']);
// echo "<pre>";
// print_r($_POST['operador_id']);
// echo "<pre>";
// print_r($return);


            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $empresa_id = $this->session->userdata('empresa_id');

            foreach ($return as $value) {

                $this->db->set('texto', $_POST['descricao']);
                $this->db->set('operador_destino', $value->operador_id);
                $this->db->set('perfil_destino', $value->perfil_id);
                $this->db->set('empresa_id', $empresa_id);
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_empresa_lembretes');

                $this->db->set('texto', $_POST['descricao']);
                $this->db->set('operador_destino', $value->operador_id);
                $this->db->set('perfil_destino', $value->perfil_id);
                $this->db->set('empresa_id', $empresa_id);
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $this->db->where('empresa_lembretes_id', $empresa_lembretes_id);
                $this->db->update('tb_empresa_lembretes');
            }













            // if (!empty($_POST['operador_id'])) {
            //     $sql2 = $this->db->select('operador_id, nome, perfil_id');
            //     $this->db->from('tb_operador o');
            //     // if(!in_array('TODOS2', $_POST['perfil_id'])){
            //     // $this->db->where_in('perfil_id', $perfil_id);
            //     // }
            //     if (!in_array('TODOS', $_POST['operador_id'])) {
            //         $this->db->where_in('operador_id', $_POST['operador_id']);
            //     }
            //     $this->db->where('o.ativo', 't');
            //     $this->db->where('o.usuario IS NOT NULL');
            //     $return2 = $sql2->get()->result();
            //     $horario = date("Y-m-d H:i:s");
            //     $operador_id = $this->session->userdata('operador_id');
            //     $empresa_id = $this->session->userdata('empresa_id');
            //     foreach ($return2 as $value) {
            //         if ($empresa_lembretes_id == "" || $empresa_lembretes_id == "0") {// insert
            //             $this->db->set('texto', $_POST['descricao']);
            //             $this->db->set('operador_destino', $value->operador_id);
            //             $this->db->set('perfil_destino', $value->perfil_id);
            //             $this->db->set('empresa_id', $empresa_id);
            //             $this->db->set('data_cadastro', $horario);
            //             $this->db->set('operador_cadastro', $operador_id);
            //             $this->db->insert('tb_empresa_lembretes');
            //         } else { // update
            //             $this->db->set('texto', $_POST['descricao']);
            //             $this->db->set('operador_destino', $value->operador_id);
            //             $this->db->set('perfil_destino', $value->perfil_id);
            //             $this->db->set('empresa_id', $empresa_id);
            //             $this->db->set('data_atualizacao', $horario);
            //             $this->db->set('operador_atualizacao', $operador_id);
            //             $this->db->where('empresa_lembretes_id', $empresa_lembretes_id);
            //             $this->db->update('tb_empresa_lembretes');
            //         }
            //     }
            // }


            return true;












            // $erro = $this->db->_error_message();
            // if (trim($erro) != "") // erro de banco
            //     return false;
            // else
            //     return true;
        }
    }

    function gravartemplateanamnese() {

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        $empresa_id = $this->session->userdata('empresa_id');
        $template_id = $_POST['template_id'];
//        echo'<pre>';
//        var_dump($return);die;        
        // foreach ($return as $value) {
        if ($template_id == '') {// insert
            $this->db->set('nome_template', $_POST['nomeTemplate']);
            $this->db->set('grupo', $_POST['grupo']);
            $this->db->set('template', json_encode($_POST['template']));
            $this->db->set('empresa_id', $empresa_id);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_template_anamnese');
        } else { // update
            $this->db->set('nome_template', $_POST['nomeTemplate']);
            $this->db->set('grupo', $_POST['grupo']);
            $this->db->set('template', json_encode($_POST['template']));
            $this->db->set('empresa_id', $empresa_id);
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('template_anamnese_id', $template_id);
            $this->db->update('tb_template_anamnese');
        }
        // }

        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return false;
        else
            return true;
    }

    function gravarlembreteaniversario($empresa_lembretes_aniversario_id) {

        $this->db->select('operador_id, nome, perfil_id, nascimento');
        $this->db->from('tb_operador o');

        $this->db->where('o.ativo', 't');
        $this->db->where('o.usuario IS NOT NULL');
        $return = $this->db->get()->result();

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        $empresa_id = $this->session->userdata('empresa_id');

//        echo'<pre>';
//        var_dump($return);die;        

        foreach ($return as $value) {
            if ($empresa_lembretes_aniversario_id == "" || $empresa_lembretes_aniversario_id == "0") {// insert
                $this->db->set('texto', $_POST['aniversario']);
                $this->db->set('operador_destino', $value->operador_id);
                $this->db->set('perfil_destino', $value->perfil_id);
                $this->db->set('empresa_id', $empresa_id);
                $this->db->set('aniversario', $value->nascimento);
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_empresa_lembretes_aniversario');
            } else { // update
                $this->db->set('texto', $_POST['aniversario']);
                $this->db->set('operador_destino', $value->operador_id);
                $this->db->set('perfil_destino', $value->perfil_id);
                $this->db->set('empresa_id', $empresa_id);
                $this->db->set('aniversario', $value->nascimento);
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $this->db->where('empresa_lembretes_aniversario_id', $empresa_lembretes_aniversario_id);
                $this->db->update('tb_empresa_lembretes_aniversario');
            }
        }

        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return false;
        else
            return true;
    }

    function gravarconfiguracaoimpressao() {
        try {
//            var_dump($_POST['impressao_id']); die;
            /* inicia o mapeamento no banco */
            $empresa_id = $this->session->userdata('empresa_id');
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            $this->db->select('ei.empresa_impressao_cabecalho_id,');
            $this->db->from('tb_empresa_impressao_cabecalho ei');
            $this->db->where('ei.empresa_id', $empresa_id);
            $teste = $this->db->get()->result();
            if (count($teste) > 0) {
                $impressao_id = $teste[0]->empresa_impressao_cabecalho_id;
            }

            if (count($teste) == 0) {
                $this->db->set('cabecalho', $_POST['cabecalho']);
                $this->db->set('rodape', $_POST['rodape']);
                $this->db->set('timbrado', $_POST['timbrado']);
                $this->db->set('empresa_id', $empresa_id);
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_empresa_impressao_cabecalho');
            } else {
                $this->db->set('cabecalho', $_POST['cabecalho']);
                $this->db->set('rodape', $_POST['rodape']);
                $this->db->set('timbrado', $_POST['timbrado']);
                $this->db->set('empresa_id', $empresa_id);
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $this->db->where('empresa_impressao_cabecalho_id', $impressao_id);
                $this->db->update('tb_empresa_impressao_cabecalho');
            }

            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            else
//                $ambulatorio_guia_id = $this->db->insert_id();
                return true;
        } catch (Exception $exc) {
            return false;
        }
    }

    function gravarconfiguracaoimpressaoorcamento() {
        try {
//            var_dump($_POST['impressao_id']); die;
            /* inicia o mapeamento no banco */
            $empresa_id = $this->session->userdata('empresa_id');
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            $this->db->select('ei.empresa_impressao_orcamento_id,');
            $this->db->from('tb_empresa_impressao_orcamento ei');
            $this->db->where('ei.empresa_impressao_orcamento_id', $_POST['impressao_id']);
            $teste = $this->db->get()->result();
            $this->db->select('ei.empresa_impressao_orcamento_id,');
            $this->db->from('tb_empresa_impressao_orcamento ei');
            $this->db->where('ei.empresa_id', $empresa_id);
            $teste2 = $this->db->get()->result();
            if (count($teste) > 0) {
                $impressao_id = $teste[0]->empresa_impressao_orcamento_id;
            }

            if (count($teste) == 0) {
                $this->db->set('nome', $_POST['nome']);
                $this->db->set('cabecalho', $_POST['cabecalho']);
                $this->db->set('rodape', $_POST['rodape']);
                $this->db->set('texto', $_POST['texto']);
                $this->db->set('empresa_id', $empresa_id);
                if (count($teste2) > 0) {
                    $this->db->set('ativo', 'f');
                }
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_empresa_impressao_orcamento');
            } else {
                $this->db->set('nome', $_POST['nome']);
                $this->db->set('cabecalho', $_POST['cabecalho']);
                $this->db->set('rodape', $_POST['rodape']);
                $this->db->set('texto', $_POST['texto']);
                $this->db->set('empresa_id', $empresa_id);
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $this->db->where('empresa_impressao_orcamento_id', $impressao_id);
                $this->db->update('tb_empresa_impressao_orcamento');
            }

            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            else
//                $ambulatorio_guia_id = $this->db->insert_id();
                return true;
        } catch (Exception $exc) {
            return false;
        }
    }

    function gravarpostsblog() {
        try {
//            var_dump($_POST['impressao_id']); die;
            /* inicia o mapeamento no banco */
            $empresa_id = $this->session->userdata('empresa_id');
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            
            $this->db->set('titulo', $_POST['titulo']);
            $this->db->set('corpo_html', $_POST['texto']);
            // $this->db->set('empresa_id', $empresa_id);

//            var_dump($_POST); die;
            if (!$_POST['posts_blog_id'] > 0) {
                
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_posts_blog');
            } else {
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $this->db->where('posts_blog_id', $_POST['posts_blog_id']);
                $this->db->update('tb_posts_blog');
            }

            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            else
//                $ambulatorio_guia_id = $this->db->insert_id();
                return true;
        } catch (Exception $exc) {
            return false;
        }
    }

    function excluirpostsblog($post_id) {
        try {
//            var_dump($_POST['impressao_id']); die;
            /* inicia o mapeamento no banco */
            $empresa_id = $this->session->userdata('empresa_id');
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            
            
            $this->db->set('ativo', 'f');
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('posts_blog_id', $post_id);
            $this->db->update('tb_posts_blog');
            

            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            else
//                $ambulatorio_guia_id = $this->db->insert_id();
                return true;
        } catch (Exception $exc) {
            return false;
        }
    }

    function confirmarsolicitacaoagendamento($solicitacao_id) {
        try {
//            var_dump($_POST['impressao_id']); die;
            /* inicia o mapeamento no banco */
            $empresa_id = $this->session->userdata('empresa_id');
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            
            
            $this->db->set('confirmado', 't');
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('paciente_solicitar_agendamento_id', $solicitacao_id);
            $this->db->update('tb_paciente_solicitar_agendamento');
            

            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            else
//                $ambulatorio_guia_id = $this->db->insert_id();
                return true;
        } catch (Exception $exc) {
            return false;
        }
    }

    function excluirsolicitacaoagendamento($solicitacao_id) {
        try {
//            var_dump($_POST['impressao_id']); die;
            /* inicia o mapeamento no banco */
            $empresa_id = $this->session->userdata('empresa_id');
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            
            
            $this->db->set('ativo', 'f');
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('paciente_solicitar_agendamento_id', $solicitacao_id);
            $this->db->update('tb_paciente_solicitar_agendamento');
            

            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            else
//                $ambulatorio_guia_id = $this->db->insert_id();
                return true;
        } catch (Exception $exc) {
            return false;
        }
    }

    function gravarconfiguracaoimpressaorecibo() {
        try {
//            var_dump($_POST['impressao_id']); die;
            /* inicia o mapeamento no banco */
            $empresa_id = $this->session->userdata('empresa_id');
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            $this->db->select('ei.empresa_impressao_recibo_id,');
            $this->db->from('tb_empresa_impressao_recibo ei');
            $this->db->where('ei.empresa_impressao_recibo_id', $_POST['impressao_id']);
            $teste = $this->db->get()->result();
            $this->db->select('ei.empresa_impressao_recibo_id,');
            $this->db->from('tb_empresa_impressao_recibo ei');
            $this->db->where('ei.empresa_id', $empresa_id);
            $teste2 = $this->db->get()->result();
            if (count($teste) > 0) {
                $impressao_id = $teste[0]->empresa_impressao_recibo_id;
            }
//            var_dump($_POST); die;
            if (count($teste) == 0) {
                $this->db->set('nome', $_POST['nome']);
                $this->db->set('linha_procedimento', $_POST['linha_procedimento']);
                $this->db->set('cabecalho', $_POST['cabecalho']);
                if ($_POST['repetir_recibo'] > 0) {
                    $this->db->set('repetir_recibo', $_POST['repetir_recibo']);
                }
                $this->db->set('rodape', $_POST['rodape']);
                $this->db->set('texto', $_POST['texto']);
                $this->db->set('empresa_id', $empresa_id);
                if (count($teste2) > 0) {
                    $this->db->set('ativo', 'f');
                }
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_empresa_impressao_recibo');
            } else {
                $this->db->set('nome', $_POST['nome']);
                if ($_POST['repetir_recibo'] > 0) {
                    $this->db->set('repetir_recibo', $_POST['repetir_recibo']);
                }
                $this->db->set('linha_procedimento', $_POST['linha_procedimento']);
                $this->db->set('cabecalho', $_POST['cabecalho']);
                $this->db->set('rodape', $_POST['rodape']);
                $this->db->set('texto', $_POST['texto']);
                $this->db->set('empresa_id', $empresa_id);
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $this->db->where('empresa_impressao_recibo_id', $impressao_id);
                $this->db->update('tb_empresa_impressao_recibo');
            }

            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            else
//                $ambulatorio_guia_id = $this->db->insert_id();
                return true;
        } catch (Exception $exc) {
            return false;
        }
    }

    function gravarconfiguracaoimpressaolaudo() {
        try {
//            var_dump($_POST['impressao_id']); die;
            /* inicia o mapeamento no banco */
            $empresa_id = $this->session->userdata('empresa_id');
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            $this->db->select('ei.empresa_impressao_laudo_id,');
            $this->db->from('tb_empresa_impressao_laudo ei');
            $this->db->where('ei.empresa_impressao_laudo_id', $_POST['impressao_id']);
            $teste = $this->db->get()->result();
            $this->db->select('ei.empresa_impressao_laudo_id,');
            $this->db->from('tb_empresa_impressao_laudo ei');
            $this->db->where('ei.empresa_id', $empresa_id);
            $teste2 = $this->db->get()->result();
            if (count($teste) > 0) {
                $impressao_id = $teste[0]->empresa_impressao_laudo_id;
            }

            if (count($teste) == 0) {
                $this->db->set('nome', $_POST['nome']);
                $this->db->set('cabecalho', $_POST['cabecalho']);
                $this->db->set('rodape', $_POST['rodape']);
                $this->db->set('texto', $_POST['texto']);
                $this->db->set('adicional_cabecalho', $_POST['adicional_cabecalho']);
                $this->db->set('empresa_id', $empresa_id);
                if (count($teste2) > 0) {
                    $this->db->set('ativo', 'f');
                }
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_empresa_impressao_laudo');
            } else {
                $this->db->set('adicional_cabecalho', $_POST['adicional_cabecalho']);
                $this->db->set('nome', $_POST['nome']);
                $this->db->set('cabecalho', $_POST['cabecalho']);
                $this->db->set('rodape', $_POST['rodape']);
                $this->db->set('texto', $_POST['texto']);
                $this->db->set('empresa_id', $empresa_id);
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $this->db->where('empresa_impressao_laudo_id', $impressao_id);
                $this->db->update('tb_empresa_impressao_laudo');
            }

            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            else
//                $ambulatorio_guia_id = $this->db->insert_id();
                return true;
        } catch (Exception $exc) {
            return false;
        }
    }

    function gravarconfiguracaoimpressaointernacao() {
        try {
//            var_dump($_POST['impressao_id']); die;
            /* inicia o mapeamento no banco */
            $empresa_id = $this->session->userdata('empresa_id');
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            $this->db->select('ei.empresa_impressao_internacao_id,');
            $this->db->from('tb_empresa_impressao_internacao ei');
            $this->db->where('ei.empresa_impressao_internacao_id', $_POST['impressao_id']);
            $teste = $this->db->get()->result();

            if (count($teste) > 0) {
                $impressao_id = $teste[0]->empresa_impressao_internacao_id;
            }

            if (count($teste) == 0) {
                $this->db->set('nome', $_POST['nome']);
                $this->db->set('cabecalho', $_POST['cabecalho']);
                $this->db->set('rodape', $_POST['rodape']);
                $this->db->set('texto', $_POST['texto']);
                $this->db->set('adicional_cabecalho', $_POST['adicional_cabecalho']);
                $this->db->set('empresa_id', $empresa_id);

                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_empresa_impressao_internacao');
            } else {
                $this->db->set('adicional_cabecalho', $_POST['adicional_cabecalho']);
                $this->db->set('nome', $_POST['nome']);
                $this->db->set('cabecalho', $_POST['cabecalho']);
                $this->db->set('rodape', $_POST['rodape']);
                $this->db->set('texto', $_POST['texto']);
                $this->db->set('empresa_id', $empresa_id);
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $this->db->where('empresa_impressao_internacao_id', $impressao_id);
                $this->db->update('tb_empresa_impressao_internacao');
            }

            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            else
//                $ambulatorio_guia_id = $this->db->insert_id();
                return true;
        } catch (Exception $exc) {
            return false;
        }
    }

    function gravaripservidor($servidor_id) {

        $this->db->set('ip_externo', $_POST['ipservidor']);
        $this->db->set('nome_clinica', $_POST['nome_clinica']);
        $this->db->insert('tb_empresas_acesso_servidores');

        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return false;
        else
            return true;
    }

    function excluiripservidor($servidor_id) {

        $this->db->where('empresas_acesso_externo_id', $servidor_id);
        $this->db->delete('tb_empresas_acesso_servidores');
        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return false;
        else
            return true;
    }

    function excluirempresa($servidor_id) {
        $this->db->set('ativo', 'f');
        $this->db->where('empresa_id', $servidor_id);
        $this->db->update('tb_empresa');
        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return false;
        else
            return true;
    }

    function gravarlogomarca() {
        try {
            if (isset($_POST['mostrarLogo'])) {
                $this->db->set('mostrar_logo_clinica', 't');
            } else {
                $this->db->set('mostrar_logo_clinica', 'f');
            }
            $this->db->where('empresa_id', $_POST['empresa_id']);
            $this->db->update('tb_empresa');

            return $empresa_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarconfiguracaoemail() {
        try {
//            var_dump($_POST['empresa_id']); die;

            $this->db->set('email_mensagem_confirmacao', $_POST['lembr']);
            $this->db->set('email_mensagem_agradecimento', $_POST['agrade']);
            $this->db->set('email_mensagem_falta', $_POST['falta']);

            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            $this->db->where('empresa_id', $_POST['empresa_id']);
            $this->db->update('tb_empresa');
            $empresa_id = $_POST['empresa_id'];

            return $empresa_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarconfiguracaosms() {
        try {
            /* inicia o mapeamento no banco */
            $this->db->set('pacote_id', $_POST['txtpacote']);
            $this->db->set('empresa_id', $_POST['empresa_id']);
            $this->db->set('endereco_externo', $_POST['endereco_externo']);
            $this->db->set('numero_indentificacao_sms', $_POST['numero_identificacao_sms']);

            if (isset($_POST['msgensExcedentes'])) {
                $this->db->set('enviar_excedentes', 't');
            } else {
                $this->db->set('enviar_excedentes', 'f');
            }

            $this->db->set('remetente_sms', $_POST['remetente_sms']);
            $this->db->set('mensagem_confirmacao', $_POST['txtMensagemConfirmacao']);
            $this->db->set('mensagem_agradecimento', $_POST['txtMensagemAgradecimento']);
            $this->db->set('mensagem_aniversariante', $_POST['txtMensagemAniversariantes']);
            $this->db->set('mensagem_revisao', $_POST['txtMensagemRevisao']);

            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            if ($_POST['sms_id'] == "") {// insert
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_empresa_sms');
            } else { // update
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);

                $sms_id = $_POST['sms_id'];

                $this->db->where('empresa_sms_id', $sms_id);
                $this->db->update('tb_empresa_sms');
            }
            return $empresa_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarconfiguracaopacs() {
        try {
            /* inicia o mapeamento no banco */
            $this->db->set('empresa_id', $_POST['empresa_id']);
//            $this->db->set('pacote_id', $_POST['txtpacote']);
//            if(isset($_POST['msgensExcedentes'])){
//                $this->db->set('enviar_excedentes', 't');
//            }
//            else{
//                $this->db->set('enviar_excedentes', 'f');
//            }
            $this->db->set('ip_local', $_POST['ip_local']);
            $this->db->set('ip_externo', $_POST['ip_externo']);
            $this->db->set('login', $_POST['login']);
            $this->db->set('senha', $_POST['senha']);

            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            if ($_POST['pacs_id'] == "") {// insert
//                $this->db->set('data_cadastro', $horario);
//                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_pacs');
            } else { // update
//                $this->db->set('data_atualizacao', $horario);
//                $this->db->set('operador_atualizacao', $operador_id);
                $pacs_id = $_POST['pacs_id'];

                $this->db->where('pacs_id', $pacs_id);
                $this->db->update('tb_pacs');
            }
//            echo 'something';
//            die;
            return $empresa_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function listardadosconvenio($exame_empresa_id) {

        $this->db->select(' c.convenio_id,
                            c.nome,
                            c.dinheiro,
                            c.conta_id,
                            c.tamanho_carteira');
        $this->db->from('tb_convenio c');
        $this->db->join('tb_convenio_empresa ce', 'ce.convenio_id = c.convenio_id', 'left');
        $this->db->where("c.ativo", 'true');

        $procedimento_multiempresa = $this->session->userdata('procedimento_multiempresa');
        if ($procedimento_multiempresa != 't') {
            $empresa_id = $this->session->userdata('empresa_id');
            $this->db->where("ce.empresa_id", $exame_empresa_id);
            $this->db->where("ce.ativo", 'true');
        }

        $this->db->orderby("c.nome");
        $this->db->groupby("c.convenio_id,
                            c.nome,
                            c.dinheiro,
                            c.conta_id");
        $query = $this->db->get();
        $return = $query->result();

        return $return;
    }

    function empresalog($empresa_id){
        $this->db->select('e.nome, o.nome as operador_cadastro,
                           e.data_cadastro, 
                           oa.nome as operador_atualizacao,
                           e.data_atualizacao');
        $this->db->from('tb_empresa e');
        $this->db->join('tb_operador o', 'o.operador_id = e.operador_cadastro', 'left');
        $this->db->join('tb_operador oa', 'oa.operador_id = e.operador_atualizacao', 'left');
        $this->db->where('empresa_id', $empresa_id);
        $return = $this->db->get();
        $retorno = $return->result();

        return $retorno;
    }

    function gravar() {
        try {
            // echo'<pre>';
            // var_dump($_POST);
            // die;
            // Ativando/Desativando o Crédito
            if (isset($_POST['credito'])) {
                $this->db->set('ativo', 't');
                $this->db->where('forma_pagamento_id', 1000);
                $this->db->update('tb_forma_pagamento');
            } else {
                $this->db->set('ativo', 'f');
                $this->db->where('forma_pagamento_id', 1000);
                $this->db->update('tb_forma_pagamento');
            }
            $operador_id = $this->session->userdata('operador_id');
            /* inicia o mapeamento no banco */ 
            $this->db->set('nome', @$_POST['txtNome']);
            $this->db->set('razao_social', @$_POST['txtrazaosocial']);
            $this->db->set('razao_socialxml', @$_POST['txtrazaosocialxml']);
            $this->db->set('cep', $_POST['CEP']);
            $this->db->set('cnes', @$_POST['txtCNES']);
            $this->db->set('cnae', @$_POST['cnae']);
            $this->db->set('item_lista', @$_POST['item_lista']);
            $this->db->set('aliquota', @$_POST['aliquota']);
            $this->db->set('inscri_municipal', @$_POST['inscri_municipal']);
            $this->db->set('email', @$_POST['email']);
            $this->db->set('endereco_integracao_lab', @$_POST['endereco_integracao_lab']);
            $this->db->set('identificador_lis', @$_POST['identificador_lis']);
            $this->db->set('origem_lis', @$_POST['origem_lis']);
            $this->db->set('link_certificado', @$_POST['link_certificado']);
            $this->db->set('client_id', @$_POST['client_id']);
            $this->db->set('client_secret', @$_POST['client_secret']);
            if(isset($_POST['client_sandbox'])){
                $this->db->set('client_sandbox', 't');
            }else{
                $this->db->set('client_sandbox', 'f');
            }

            if(isset($_POST['valor_consulta_app'])){
                $this->db->set('valor_consulta_app', str_replace(",", ".", str_replace(".", "", $_POST['valor_consulta_app'])));
            }

            if (@$_POST['convenio_padrao_id'] > 0) {
                $this->db->set('convenio_padrao_id', @$_POST['convenio_padrao_id']);
            } else {
                $this->db->set('convenio_padrao_id', null);
            }

            if ($operador_id == 1) {
                if ($_POST['impressao_tipo'] != "") {
                    $this->db->set('impressao_tipo', $_POST['impressao_tipo']);
                } else {
                    $this->db->set('impressao_tipo', null);
                }

                if ($_POST['impressao_orcamento'] != "") {
                    $this->db->set('impressao_orcamento', $_POST['impressao_orcamento']);
                } else {
                    $this->db->set('impressao_orcamento', null);
                }

                if ($_POST['horSegSexta_i'] != "") {
                    $this->db->set('horario_seg_sex_inicio', $_POST['horSegSexta_i']);
                }
                if ($_POST['horSegSexta_f'] != "") {
                    $this->db->set('horario_seg_sex_fim', $_POST['horSegSexta_f']);
                }
                if ($_POST['horSegSexta_i'] != "" || $_POST['horSegSexta_f'] != "") {
                    $this->db->set('horario_seg_sex', $_POST['horSegSexta_i'] . " às " . $_POST['horSegSexta_f'] . " hr(s)");
                }

                if ($_POST['horSab_i'] != "") {
                    $this->db->set('horario_sab_inicio', $_POST['horSab_i']);
                }
                if ($_POST['horSab_f'] != "") {
                    $this->db->set('horario_sab_fim', $_POST['horSab_f']);
                }else{
                    $this->db->set('horario_sab_fim',null);
                }
                
                if(isset($_POST['horFalta_p']) && $_POST['horFalta_p'] != ""){
                  $this->db->set('horario_para_informar_faltas', $_POST['horFalta_p']);
                }else{
                   $this->db->set('horario_para_informar_faltas', null); 
                }

                if(isset($_POST['qtdefaltas']) && $_POST['qtdefaltas'] != ""){
                  $this->db->set('qtdefaltas_pacientes', $_POST['qtdefaltas']); 
                }else{
                  $this->db->set('qtdefaltas_pacientes', null); 
                }
                if ($_POST['horSab_i'] != "" || $_POST['horSab_f'] != "") {
                    $this->db->set('horario_sab', $_POST['horSab_i'] . " às " . $_POST['horSab_f'] . " hr(s)");
                }

                if ($_POST['impressao_laudo'] != "") {
                    $this->db->set('impressao_laudo', $_POST['impressao_laudo']);
                } else {
                    $this->db->set('impressao_laudo', null);
                }
                if ($_POST['impressao_recibo'] != "") {
                    $this->db->set('impressao_recibo', $_POST['impressao_recibo']);
                } else {
                    $this->db->set('impressao_recibo', null);
                }
                if ($_POST['numero_empresa_painel'] != "") {
                    $this->db->set('numero_empresa_painel', (int) $_POST['numero_empresa_painel']);
                } else {
                    $this->db->set('numero_empresa_painel', null);
                }
                if ($_POST['impressao_declaracao'] != "") {
                    $this->db->set('impressao_declaracao', $_POST['impressao_declaracao']);
                } else {
                    $this->db->set('impressao_declaracao', null);
                }
                if ($_POST['impressao_internacao'] != "") {
                    $this->db->set('impressao_internacao', $_POST['impressao_internacao']);
                } else {
                    $this->db->set('impressao_internacao', null);
                }
            }

            if ($_POST['txtCNPJ'] != '') {
                $this->db->set('cnpj', str_replace("-", "", str_replace("/", "", str_replace(".", "", $_POST['txtCNPJ']))));
            }
            if ($_POST['txtCNPJxml'] != '') {
                $this->db->set('cnpjxml', str_replace("-", "", str_replace("/", "", str_replace(".", "", $_POST['txtCNPJxml']))));
            }
            $this->db->set('telefone', str_replace("(", "", str_replace(")", "", str_replace("-", "", $_POST['telefone']))));
            $this->db->set('celular', str_replace("(", "", str_replace(")", "", str_replace("-", "", $_POST['celular']))));
            if ($_POST['municipio_id'] != '') {
                $this->db->set('municipio_id', $_POST['municipio_id']);
            }
            $this->db->set('logradouro', $_POST['endereco']);
            $this->db->set('numero', $_POST['numero']);
            $this->db->set('bairro', $_POST['bairro']);

            if(isset($_POST['endereco_upload'])){
            $this->db->set('endereco_upload', $_POST['endereco_upload']);
            }
            if(isset($_POST['endereco_upload_pasta'])){
            $this->db->set('endereco_upload_pasta', $_POST['endereco_upload_pasta']);
            }
            if(isset($_POST['endereco_upload_pasta_paciente'])){
                $this->db->set('endereco_upload_pasta_paciente', $_POST['endereco_upload_pasta_paciente']);
                }
            
            if ($operador_id == 1) {
                if (isset($_POST['sms'])) {
                    $this->db->set('servicosms', 't');
                } else {
                    $this->db->set('servicosms', 'f');
                }
                if (isset($_POST['servicowhatsapp'])) {
                    $this->db->set('servicowhatsapp', 't');
                } else {
                    $this->db->set('servicowhatsapp', 'f');
                }

                if (isset($_POST['servicoemail'])) {
                    $this->db->set('servicoemail', 't');
                } else {
                    $this->db->set('servicoemail', 'f');
                }
                if (isset($_POST['chat'])) {
                    $this->db->set('chat', 't');
                } else {
                    $this->db->set('chat', 'f');
                }
                if (isset($_POST['farmacia'])) {
                    $this->db->set('farmacia', 't');
                } else {
                    $this->db->set('farmacia', 'f');
                }
                if (isset($_POST['imagem'])) {
                    $this->db->set('imagem', 't');
                } else {
                    $this->db->set('imagem', 'f');
                }

                if (isset($_POST['fila_caixa'])) {

                    $this->db->set('caixa', 't');
                } else {
                    $this->db->set('caixa', 'f');
                }



                if (isset($_POST['data_contaspagar'])) {
                    $this->db->set('data_contaspagar', 't');
                } else {
                    $this->db->set('data_contaspagar', 'f');
                }
                if (isset($_POST['medico_laudodigitador'])) {
                    $this->db->set('medico_laudodigitador', 't');
                } else {
                    $this->db->set('medico_laudodigitador', 'f');
                }
                if (isset($_POST['chamar_consulta'])) {
                    $this->db->set('chamar_consulta', 't');
                } else {
                    $this->db->set('chamar_consulta', 'f');
                }
                if (isset($_POST['procedimentos_multiempresa'])) {
                    $this->db->set('procedimento_multiempresa', 't');
                } else {
                    $this->db->set('procedimento_multiempresa', 'f');
                }

                if (isset($_POST['consulta'])) {
                    $this->db->set('consulta', 't');
                } else {
                    $this->db->set('consulta', 'f');
                }
                if (isset($_POST['especialidade'])) {
                    $this->db->set('especialidade', 't');
                } else {
                    $this->db->set('especialidade', 'f');
                }
                if (isset($_POST['odontologia'])) {
                    $this->db->set('odontologia', 't');
                } else {
                    $this->db->set('odontologia', 'f');
                }
                if (isset($_POST['laboratorio'])) {
                    $this->db->set('laboratorio', 't');
                } else {
                    $this->db->set('laboratorio', 'f');
                }
                if (isset($_POST['geral'])) {
                    $this->db->set('geral', 't');
                } else {
                    $this->db->set('geral', 'f');
                }
                if (isset($_POST['faturamento'])) {
                    $this->db->set('faturamento', 't');
                } else {
                    $this->db->set('faturamento', 'f');
                }
                if (isset($_POST['estoque'])) {
                    $this->db->set('estoque', 't');
                } else {
                    $this->db->set('estoque', 'f');
                }
                if (isset($_POST['financeiro'])) {
                    $this->db->set('financeiro', 't');
                } else {
                    $this->db->set('financeiro', 'f');
                }
                if (isset($_POST['marketing'])) {
                    $this->db->set('marketing', 't');
                } else {
                    $this->db->set('marketing', 'f');
                }
                if (isset($_POST['internacao'])) {
                    $this->db->set('internacao', 't');
                } else {
                    $this->db->set('internacao', 'f');
                }
                if (isset($_POST['centro_cirurgico'])) {
                    $this->db->set('centrocirurgico', 't');
                } else {
                    $this->db->set('centrocirurgico', 'f');
                }
                if (isset($_POST['ponto'])) {
                    $this->db->set('ponto', 't');
                } else {
                    $this->db->set('ponto', 'f');
                }
                if (isset($_POST['calendario'])) {
                    $this->db->set('calendario', 't');
                } else {
                    $this->db->set('calendario', 'f');
                }
                if (isset($_POST['botao_faturar_guia'])) {
                    $this->db->set('botao_faturar_guia', 't');
                } else {
                    $this->db->set('botao_faturar_guia', 'f');
                }
                if (isset($_POST['botao_faturar_proc'])) {
                    $this->db->set('botao_faturar_procedimento', 't');
                } else {
                    $this->db->set('botao_faturar_procedimento', 'f');
                }
                if (isset($_POST['producao_medica_saida'])) {
                    $this->db->set('producao_medica_saida', 't');
                } else {
                    $this->db->set('producao_medica_saida', 'f');
                }
                if (isset($_POST['cabecalho_config'])) {
                    $this->db->set('cabecalho_config', 't');
                } else {
                    $this->db->set('cabecalho_config', 'f');
                }
                if (isset($_POST['rodape_config'])) {
                    $this->db->set('rodape_config', 't');
                } else {
                    $this->db->set('rodape_config', 'f');
                }
                if (isset($_POST['laudo_config'])) {
                    $this->db->set('laudo_config', 't');
                } else {
                    $this->db->set('laudo_config', 'f');
                }
                if (isset($_POST['recibo_config'])) {
                    $this->db->set('recibo_config', 't');
                } else {
                    $this->db->set('recibo_config', 'f');
                }
                if (isset($_POST['ficha_config'])) { // Ficha
                    $this->db->set('ficha_config', 't');
                } else {
                    $this->db->set('ficha_config', 'f');
                }
                if (isset($_POST['declaracao_config'])) { // Declaracao
                    $this->db->set('declaracao_config', 't');
                } else {
                    $this->db->set('declaracao_config', 'f');
                }
                if (isset($_POST['atestado_config'])) { // Atestado
                    $this->db->set('atestado_config', 't');
                } else {
                    $this->db->set('atestado_config', 'f');
                }
            }
            $horario = date("Y-m-d H:i:s");


            $perfil_id = $this->session->userdata('perfil_id');
            if ($_POST['txtempresaid'] == "") {// insert
                if(isset($_POST['endereco_externo_base'])){
                $this->db->set('endereco_externo_base', $_POST['endereco_externo_base']);
                }
                if(isset($_POST['endereco_externo'])){
                $this->db->set('endereco_externo', $_POST['endereco_externo']);
                }
                if(isset($_POST['site_empresa'])){
                $this->db->set('site_empresa', $_POST['site_empresa']);
                }
                if(isset($_POST['facebook_empresa']) && $_POST['facebook_empresa'] != ""){
                 $this->db->set('facebook_empresa', $_POST['facebook_empresa']);
                }else{
                 $this->db->set('facebook_empresa',null);   
                }
                if(isset($_POST['instagram_empresa']) && $_POST['instagram_empresa'] != ""){
                 $this->db->set('instagram_empresa', $_POST['instagram_empresa']);
                }else{
                 $this->db->set('instagram_empresa',null);   
                }
                if(isset($_POST['endereco_toten'])){
                $this->db->set('endereco_toten', $_POST['endereco_toten']);
                }
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_empresa');
                $empresa_id = $this->db->insert_id();

                if ($operador_id == 1) {
                    if (isset($_POST['historico_completo'])) {
                        $this->db->set('historico_completo', 't');
                    } else {
                        $this->db->set('historico_completo', 'f');
                    }

                    if (isset($_POST['origem_agendamento'])) {
                        $this->db->set('origem_agendamento', 't');
                    } else {
                        $this->db->set('origem_agendamento', 'f');
                    }

                    if (isset($_POST['manter_gastos'])) {
                        $this->db->set('manter_gastos', 't');
                    } else {
                        $this->db->set('manter_gastos', 'f');
                    }

                    if (isset($_POST['producao_por_valor'])) {
                        $this->db->set('producao_por_valor', 't');
                    } else {
                        $this->db->set('producao_por_valor', 'f');
                    }

                    

                    if (isset($_POST['filaaparelho'])) {
                        $this->db->set('filaaparelho', 't');
                    } else {
                        $this->db->set('filaaparelho', 'f');
                    }

                    if (isset($_POST['setores'])) {
                        $this->db->set('setores', 't');
                    } else {
                        $this->db->set('setores', 'f');
                    }
                    if (isset($_POST['certificado_digital'])) {
                        $this->db->set('certificado_digital', 't');
                    } else {
                        $this->db->set('certificado_digital', 'f');
                    }
                    if (isset($_POST['certificado_digital_manual'])) {
                        $this->db->set('certificado_digital_manual', 't');
                    } else {
                        $this->db->set('certificado_digital_manual', 'f');
                    }
                    if (isset($_POST['dashboard_administrativo'])) {
                        $this->db->set('dashboard_administrativo', 't');
                    } else {
                        $this->db->set('dashboard_administrativo', 'f');
                    }
                    if (isset($_POST['integrar_google'])) {
                        $this->db->set('integrar_google', 't');
                    } else {
                        $this->db->set('integrar_google', 'f');
                    }
                    if (isset($_POST['entrega_laudos'])) {
                        $this->db->set('entrega_laudos', 't');
                    } else {
                        $this->db->set('entrega_laudos', 'f');
                    }
                    if (isset($_POST['bardeira_status'])) {
                        $this->db->set('bardeira_status', 't');
                    } else {
                        $this->db->set('bardeira_status', 'f');
                    }
                    if (isset($_POST['diagnostico_medico'])) {
                        $this->db->set('diagnostico_medico', 't');
                    } else {
                        $this->db->set('diagnostico_medico', 'f');
                    }

                    if (isset($_POST['impressoes_acompanhamento'])) {
                        $this->db->set('impressoes_acompanhamento', 't');
                    } else {
                        $this->db->set('impressoes_acompanhamento', 'f');
                    }

                    if (isset($_POST['procedimento_excecao'])) {
                        $this->db->set('procedimento_excecao', 't');
                    } else {
                        $this->db->set('procedimento_excecao', 'f');
                    }
                    if (isset($_POST['ordem_chegada'])) {
                        $this->db->set('ordem_chegada', 't');
                    } else {
                        $this->db->set('ordem_chegada', 'f');
                    }
                    if (isset($_POST['encaminhamento_email'])) {
                        $this->db->set('encaminhamento_email', 't');
                    } else {
                        $this->db->set('encaminhamento_email', 'f');
                    }
                    if (isset($_POST['valor_convenio_nao'])) {
                        $this->db->set('valor_convenio_nao', 't');
                    } else {
                        $this->db->set('valor_convenio_nao', 'f');
                    }
                    if (count(@$_POST['campos_obrigatorio']) > 0) {
                        $this->db->set('campos_cadastro', json_encode($_POST['campos_obrigatorio']));
                    } else {
                        $this->db->set('campos_cadastro', '');
                    }


                    if (count(@$_POST['opc_telatendimento']) > 0) {
                        $this->db->set('campos_atendimentomed', json_encode($_POST['opc_telatendimento']));
                    } else {
                        $this->db->set('campos_atendimentomed', '');
                    }

                    if (count(@$_POST['botoes_app']) > 0) {
                        $this->db->set('botoes_app', json_encode($_POST['botoes_app']));
                    } else {
                        $this->db->set('botoes_app', '');
                    }

                    if (count(@$_POST['modelos_atendimento']) > 0) {
                        $this->db->set('modelos_atendimento', json_encode($_POST['modelos_atendimento']));
                    } else {
                        $this->db->set('modelos_atendimento', '');
                    }

                    if (@count(@$_POST['abas_atendimento']) > 0) {
                        $this->db->set('abas_atendimento', json_encode($_POST['abas_atendimento']));
                    } else {
                        $this->db->set('abas_atendimento', '');
                    }

                    if (count(@$_POST['opc_listaatendimentomed']) > 0) {
                        $this->db->set('campos_listaatendimentomed', json_encode($_POST['opc_listaatendimentomed']));
                    } else {
                        $this->db->set('campos_listaatendimentomed', '');
                    }



                    if (count(@$_POST['opc_dadospaciente']) > 0) {
                        $this->db->set('dados_atendimentomed', json_encode($_POST['opc_dadospaciente']));
                    } else {
                        $this->db->set('dados_atendimentomed', '');
                    }
                    if (isset($_POST['valor_autorizar'])) {
                        $this->db->set('valor_autorizar', 't');
                    } else {
                        $this->db->set('valor_autorizar', 'f');
                    }
                    if (isset($_POST['gerente_recepcao_top_saude'])) {
                        $this->db->set('gerente_recepcao_top_saude', 't');
                    } else {
                        $this->db->set('gerente_recepcao_top_saude', 'f');
                    }
                    if (isset($_POST['agenda_modelo2'])) {
                        $this->db->set('agenda_modelo2', 't');
                    } else {
                        $this->db->set('agenda_modelo2', 'f');
                    }
                    if (isset($_POST['orcamento_multiplo'])) {
                        $this->db->set('orcamento_multiplo', 't');
                    } else {
                        $this->db->set('orcamento_multiplo', 'f');
                    }
                    if (isset($_POST['modelo_laudo_medico'])) {
                        $this->db->set('modelo_laudo_medico', 't');
                    } else {
                        $this->db->set('modelo_laudo_medico', 'f');
                    }
                    if (isset($_POST['autorizar_sala_espera'])) {
                        $this->db->set('autorizar_sala_espera', 't');
                    } else {
                        $this->db->set('autorizar_sala_espera', 'f');
                    }
                    if (isset($_POST['profissional_agendar'])) {
                        $this->db->set('profissional_agendar', 't');
                    } else {
                        $this->db->set('profissional_agendar', 'f');
                    }
                    if (isset($_POST['profissional_externo'])) {
                        $this->db->set('profissional_externo', 't');
                    } else {
                        $this->db->set('profissional_externo', 'f');
                    }
                    if (isset($_POST['conjuge'])) {
                        $this->db->set('conjuge', 't');
                    } else {
                        $this->db->set('conjuge', 'f');
                    }
                    if (isset($_POST['producao_alternativo'])) {
                        $this->db->set('producao_alternativo', 't');
                    } else {
                        $this->db->set('producao_alternativo', 'f');
                    }
                    if (isset($_POST['gerente_cancelar'])) {
                        $this->db->set('gerente_cancelar', 't');
                    } else {
                        $this->db->set('gerente_cancelar', 'f');
                    }
                    if (isset($_POST['reservar_escolher_proc'])) {
                        $this->db->set('reservar_escolher_proc', 't');
                    } else {
                        $this->db->set('reservar_escolher_proc', 'f');
                    }
                    if (isset($_POST['valor_laboratorio'])) {
                        $this->db->set('valor_laboratorio', 't');
                    } else {
                        $this->db->set('valor_laboratorio', 'f');
                    }
                    if (isset($_POST['gerente_contasapagar'])) {
                        $this->db->set('gerente_contasapagar', 't');
                    } else {
                        $this->db->set('gerente_contasapagar', 'f');
                    }
                    if (isset($_POST['gerente_relatorio_financeiro'])) {
                        $this->db->set('gerente_relatorio_financeiro', 't');
                    } else {
                        $this->db->set('gerente_relatorio_financeiro', 'f');
                    }
                    if (isset($_POST['botao_imagem_paciente'])) {
                        $this->db->set('botao_imagem_paciente', 't');
                    } else {
                        $this->db->set('botao_imagem_paciente', 'f');
                    }
                    if (isset($_POST['botao_arquivos_paciente'])) {
                        $this->db->set('botao_arquivos_paciente', 't');
                    } else {
                        $this->db->set('botao_arquivos_paciente', 'f');
                    }
                    if (isset($_POST['botao_laudo_paciente'])) {
                        $this->db->set('botao_laudo_paciente', 't');
                    } else {
                        $this->db->set('botao_laudo_paciente', 'f');
                    }
                    if (isset($_POST['cpf_obrigatorio'])) {
                        $this->db->set('cpf_obrigatorio', 't');
                    } else {
                        $this->db->set('cpf_obrigatorio', 'f');
                    }
                    if (isset($_POST['subgrupo'])) {
                        $this->db->set('subgrupo', 't');
                    } else {
                        $this->db->set('subgrupo', 'f');
                    }
                    if (isset($_POST['orcamento_recepcao'])) {
                        $this->db->set('orcamento_recepcao', 't');
                    } else {
                        $this->db->set('orcamento_recepcao', 'f');
                    }
                    if (isset($_POST['relatorio_ordem'])) {
                        $this->db->set('relatorio_ordem', 't');
                    } else {
                        $this->db->set('relatorio_ordem', 'f');
                    }
                    if (isset($_POST['desativar_taxa_administracao'])) {
                        $this->db->set('desativar_taxa_administracao', 't');
                    } else {
                        $this->db->set('desativar_taxa_administracao', 'f');
                    }
                    if (isset($_POST['relatorio_producao'])) {
                        $this->db->set('relatorio_producao', 't');
                    } else {
                        $this->db->set('relatorio_producao', 'f');
                    }
                    if (isset($_POST['relatorios_recepcao'])) {
                        $this->db->set('relatorios_recepcao', 't');
                    } else {
                        $this->db->set('relatorios_recepcao', 'f');
                    }
                    if (isset($_POST['manter_indicacao'])) {
                        $this->db->set('manter_indicacao', 't');
                    } else {
                        $this->db->set('manter_indicacao', 'f');
                    }
                    if (isset($_POST['faturamento_novo'])) {
                        $this->db->set('faturamento_novo', 't');
                    } else {
                        $this->db->set('faturamento_novo', 'f');
                    }

                    if (isset($_POST['fila_impressao'])) {
                        $this->db->set('fila_impressao', 't');
                    } else {
                        $this->db->set('fila_impressao', 'f');
                    }
                    if (isset($_POST['medico_solicitante'])) {
                        $this->db->set('medico_solicitante', 't');
                    } else {
                        $this->db->set('medico_solicitante', 'f');
                    }
                    if (isset($_POST['relatorio_operadora'])) {
                        $this->db->set('relatorio_operadora', 't');
                    } else {
                        $this->db->set('relatorio_operadora', 'f');
                    }
                    if (isset($_POST['relatorio_rm'])) {
                        $this->db->set('relatorio_rm', 't');
                    } else {
                        $this->db->set('relatorio_rm', 'f');
                    }
                    if (isset($_POST['relatorio_caixa'])) {
                        $this->db->set('relatorio_caixa', 't');
                    } else {
                        $this->db->set('relatorio_caixa', 'f');
                    }
                    if (isset($_POST['relatorio_demandagrupo'])) {
                        $this->db->set('relatorio_demandagrupo', 't');
                    } else {
                        $this->db->set('relatorio_demandagrupo', 'f');
                    }
                    if (isset($_POST['uso_salas'])) {
                        $this->db->set('uso_salas', 't');
                    } else {
                        $this->db->set('uso_salas', 'f');
                    }
                    if (isset($_POST['enfermagem'])) {
                        $this->db->set('enfermagem', 't');
                    } else {
                        $this->db->set('enfermagem', 'f');
                    }
                    if (isset($_POST['integracaosollis'])) {
                        $this->db->set('integracaosollis', 't');
                    } else {
                        $this->db->set('integracaosollis', 'f');
                    }
                    if (isset($_POST['medicinadotrabalho'])) {
                        $this->db->set('medicinadotrabalho', 't');
                    } else {
                        $this->db->set('medicinadotrabalho', 'f');
                    }
                    if (isset($_POST['ocupacao_mae'])) {
                        $this->db->set('ocupacao_mae', 't');
                    } else {
                        $this->db->set('ocupacao_mae', 'f');
                    }
                    if (isset($_POST['ocupacao_pai'])) {
                        $this->db->set('ocupacao_pai', 't');
                    } else {
                        $this->db->set('ocupacao_pai', 'f');
                    }
                    if (isset($_POST['limitar_acesso'])) {
                        $this->db->set('limitar_acesso', 't');
                    } else {
                        $this->db->set('limitar_acesso', 'f');
                    }
                    if (isset($_POST['perfil_marketing_p'])) {
                        $this->db->set('perfil_marketing_p', 't');
                    } else {
                        $this->db->set('perfil_marketing_p', 'f');
                    }
                    if (isset($_POST['filtrar_agenda'])) {
                        $this->db->set('filtrar_agenda', 't');
                    } else {
                        $this->db->set('filtrar_agenda', 'f');
                    }
                    if (isset($_POST['manternota'])) {
                        $this->db->set('manternota', 't');
                    } else {
                        $this->db->set('manternota', 'f');
                    }
                    if (isset($_POST['laboratorio_sc'])) {
                        $this->db->set('laboratorio_sc', 't');
                    } else {
                        $this->db->set('laboratorio_sc', 'f');
                    }
                    if (isset($_POST['financeiro_cadastro'])) {
                        $this->db->set('financeiro_cadastro', 't');
                    } else {
                        $this->db->set('financeiro_cadastro', 'f');
                    }
                    if (isset($_POST['valor_recibo_guia'])) {
                        $this->db->set('valor_recibo_guia', 't');
                    } else {
                        $this->db->set('valor_recibo_guia', 'f');
                    }
                    if (isset($_POST['orcamento_config'])) {
                        $this->db->set('orcamento_config', 't');
                    } else {
                        $this->db->set('orcamento_config', 'f');
                    }

                    if (isset($_POST['odontologia_valor_alterar'])) {
                        $this->db->set('odontologia_valor_alterar', 't');
                    } else {
                        $this->db->set('odontologia_valor_alterar', 'f');
                    }
                    if (isset($_POST['selecionar_retorno'])) {
                        $this->db->set('selecionar_retorno', 't');
                    } else {
                        $this->db->set('selecionar_retorno', 'f');
                    }

                    if (isset($_POST['excluir_transferencia'])) {
                        $this->db->set('excluir_transferencia', 't');
                    } else {
                        $this->db->set('excluir_transferencia', 'f');
                    }
                    if (isset($_POST['login_paciente'])) {
                        $this->db->set('login_paciente', 't');
                    } else {
                        $this->db->set('login_paciente', 'f');
                    }
                    if (isset($_POST['credito'])) {
                        $this->db->set('credito', 't');
                    } else {
                        $this->db->set('credito', 'f');
                    }
                    if (isset($_POST['administrador_cancelar'])) {
                        $this->db->set('administrador_cancelar', 't');
                    } else {
                        $this->db->set('administrador_cancelar', 'f');
                    }
                    if (isset($_POST['calendario_layout'])) {
                        $this->db->set('calendario_layout', 't');
                    } else {
                        $this->db->set('calendario_layout', 'f');
                    }
                    if (isset($_POST['cancelar_sala_espera'])) {
                        $this->db->set('cancelar_sala_espera', 't');
                    } else {
                        $this->db->set('cancelar_sala_espera', 'f');
                    }
                    if (isset($_POST['oftamologia'])) {
                        $this->db->set('oftamologia', 't');
                    } else {
                        $this->db->set('oftamologia', 'f');
                    }
                    if (isset($_POST['recomendacao_configuravel'])) {
                        $this->db->set('recomendacao_configuravel', 't');
                    } else {
                        $this->db->set('recomendacao_configuravel', 'f');
                    }
                    if (isset($_POST['orcamento_cadastro'])) {
                        $this->db->set('orcamento_cadastro', 't');
                    } else {
                        $this->db->set('orcamento_cadastro', 'f');
                    }

                    if (isset($_POST['recomendacao_obrigatorio'])) {
                        $this->db->set('recomendacao_obrigatorio', 't');
                    } else {
                        $this->db->set('recomendacao_obrigatorio', 'f');
                    }

                    if (isset($_POST['botao_ativar_sala'])) {
                        $this->db->set('botao_ativar_sala', 't');
                    } else {
                        $this->db->set('botao_ativar_sala', 'f');
                    }
                    if (isset($_POST['promotor_medico'])) {
                        $this->db->set('promotor_medico', 't');
                    } else {
                        $this->db->set('promotor_medico', 'f');
                    }

                    if (isset($_POST['retirar_botao_ficha'])) {
                        $this->db->set('retirar_botao_ficha', 't');
                    } else {
                        $this->db->set('retirar_botao_ficha', 'f');
                    }

                    if (isset($_POST['desativar_personalizacao_impressao'])) {
                        $this->db->set('desativar_personalizacao_impressao', 't');
                    } else {
                        $this->db->set('desativar_personalizacao_impressao', 'f');
                    }

                    if (isset($_POST['carregar_modelo_receituario'])) {
                        $this->db->set('carregar_modelo_receituario', 't');
                    } else {
                        $this->db->set('carregar_modelo_receituario', 'f');
                    }
//                    if (isset($_POST['fila_caixa'])) {
//                        $this->db->set('caixa', 't');
//                    } else {
//                        $this->db->set('caixa', 'f');
//                    }

                    if (isset($_POST['caixa_personalizado'])) {
                        $this->db->set('caixa_personalizado', 't');
                    } else {
                        $this->db->set('caixa_personalizado', 'f');
                    }

                    if (isset($_POST['desabilitar_trava_retorno'])) {
                        $this->db->set('desabilitar_trava_retorno', 't');
                    } else {
                        $this->db->set('desabilitar_trava_retorno', 'f');
                    }

                    if (isset($_POST['associa_credito_procedimento'])) {
                        $this->db->set('associa_credito_procedimento', 't');
                    } else {
                        $this->db->set('associa_credito_procedimento', 'f');
                    }

                    if (in_array("dt_nascimento", @$_POST['campos_obrigatorio'])) {
                        $this->db->set('campos_obrigatorios_pac_nascimento', 't');
                    } else {
                        $this->db->set('campos_obrigatorios_pac_nascimento', 'f');
                    }

                    if (in_array('sexo', @$_POST['campos_obrigatorio'])) {
                        $this->db->set('campos_obrigatorios_pac_sexo', 't');
                    } else {
                        $this->db->set('campos_obrigatorios_pac_sexo', 'f');
                    }

                    if (in_array('cpf', @$_POST['campos_obrigatorio'])) {
                        $this->db->set('campos_obrigatorios_pac_cpf', 't');
                    } else {
                        $this->db->set('campos_obrigatorios_pac_cpf', 'f');
                    }

                    if (in_array('telefone', @$_POST['campos_obrigatorio'])) {
                        $this->db->set('campos_obrigatorios_pac_telefone', 't');
                    } else {
                        $this->db->set('campos_obrigatorios_pac_telefone', 'f');
                    }

                    if (in_array('municipio', @$_POST['campos_obrigatorio'])) {
                        $this->db->set('campos_obrigatorios_pac_municipio', 't');
                    } else {
                        $this->db->set('campos_obrigatorios_pac_municipio', 'f');
                    }

                    if (isset($_POST['repetir_horarios_agenda'])) {
                        $this->db->set('repetir_horarios_agenda', 't');
                    } else {
                        $this->db->set('repetir_horarios_agenda', 'f');
                    }
                    if (isset($_POST['laudo_sigiloso'])) {
                        $this->db->set('laudo_sigiloso', 't');
                    } else {
                        $this->db->set('laudo_sigiloso', 'f');
                    }
                    if (isset($_POST['gerente_cancelar_sala'])) {
                        $this->db->set('gerente_cancelar_sala', 't');
                    } else {
                        $this->db->set('gerente_cancelar_sala', 'f');
                    }

                    if (isset($_POST['subgrupo_procedimento'])) {
                        $this->db->set('subgrupo_procedimento', 't');
                    } else {
                        $this->db->set('subgrupo_procedimento', 'f');
                    }

                    if (isset($_POST['senha_finalizar_laudo'])) {
                        $this->db->set('senha_finalizar_laudo', 't');
                    } else {
                        $this->db->set('senha_finalizar_laudo', 'f');
                    }

                    if (isset($_POST['retirar_flag_solicitante'])) {
                        $this->db->set('retirar_flag_solicitante', 't');
                    } else {
                        $this->db->set('retirar_flag_solicitante', 'f');
                    }

                    if (isset($_POST['cadastrar_painel_sala'])) {
                        $this->db->set('cadastrar_painel_sala', 't');
                    } else {
                        $this->db->set('cadastrar_painel_sala', 'f');
                    }

                    if (isset($_POST['apenas_procedimentos_multiplos'])) {
                        $this->db->set('apenas_procedimentos_multiplos', 't');
                    } else {
                        $this->db->set('apenas_procedimentos_multiplos', 'f');
                    }

                    if (isset($_POST['percentual_multiplo'])) {
                        $this->db->set('percentual_multiplo', 't');
                    } else {
                        $this->db->set('percentual_multiplo', 'f');
                    }

                    if (isset($_POST['ajuste_pagamento_procedimento'])) {
                        $this->db->set('ajuste_pagamento_procedimento', 't');
                    } else {
                        $this->db->set('ajuste_pagamento_procedimento', 'f');
                    }

                    if (isset($_POST['retirar_preco_procedimento'])) {
                        $this->db->set('retirar_preco_procedimento', 't');
                    } else {
                        $this->db->set('retirar_preco_procedimento', 'f');
                    }

                    if (isset($_POST['relatorios_clinica_med'])) {
                        $this->db->set('relatorios_clinica_med', 't');
                    } else {
                        $this->db->set('relatorios_clinica_med', 'f');
                    }
                    if (isset($_POST['impressao_cimetra'])) {
                        $this->db->set('impressao_cimetra', 't');
                    } else {
                        $this->db->set('impressao_cimetra', 'f');
                    }
                    if (isset($_POST['botao_ficha_convenio'])) {
                        $this->db->set('botao_ficha_convenio', 't');
                    } else {
                        $this->db->set('botao_ficha_convenio', 'f');
                    }
                    if (isset($_POST['ordenacao_situacao'])) {
                        $this->db->set('ordenacao_situacao', 't');
                    } else {
                        $this->db->set('ordenacao_situacao', 'f');
                    }
                    if (isset($_POST['financ_4n'])) {
                        $this->db->set('financ_4n', 't');
                    } else {
                        $this->db->set('financ_4n', 'f');
                    }
                    if (isset($_POST['liberar_perfil'])) {
                        $this->db->set('liberar_perfil', 't');
                    } else {
                        $this->db->set('liberar_perfil', 'f');
                    }
                    if (isset($_POST['bloquear_botao'])) {
                        $this->db->set('bloquear_botao', 't');
                    } else {
                        $this->db->set('bloquear_botao', 'f');
                    }
                    if (isset($_POST['atendimento_medico'])) {
                        $this->db->set('atendimento_medico', 't');
                    } else {
                        $this->db->set('atendimento_medico', 'f');
                    }

                    if (isset($_POST['atendimento_medico_3'])) {
                        $this->db->set('atendimento_medico_3', 't');
                    } else {
                        $this->db->set('atendimento_medico_3', 'f');
                    }
                    
                    if (isset($_POST['grupo_convenio_proc'])) {
                        $this->db->set('grupo_convenio_proc', 't');
                    } else {
                        $this->db->set('grupo_convenio_proc', 'f');
                    }

                    if (isset($_POST['pagar_todos'])) {
                        $this->db->set('pagar_todos', 't');
                    } else {
                        $this->db->set('pagar_todos', 'f');
                    }

                    if (isset($_POST['faturar_parcial'])) {
                        $this->db->set('faturar_parcial', 't');
                    } else {
                        $this->db->set('faturar_parcial', 'f');
                    }

                    if (isset($_POST['caixa_grupo'])) {
                        $this->db->set('caixa_grupo', 't');
                    } else {
                        $this->db->set('caixa_grupo', 'f');
                    }
                    if (isset($_POST['medico_estoque'])) {
                        $this->db->set('medico_estoque', 't');
                    } else {
                        $this->db->set('medico_estoque', 'f');
                    }
                    if (isset($_POST['filtro_exame_cadastro'])) {
                        $this->db->set('filtro_exame_cadastro', 't');
                    } else {
                        $this->db->set('filtro_exame_cadastro', 'f');
                    }
                    if (isset($_POST['editar_historico_antigo'])) {
                        $this->db->set('editar_historico_antigo', 't');
                    } else {
                        $this->db->set('editar_historico_antigo', 'f');
                    }
                    if (isset($_POST['relatorio_dupla'])) {
                        $this->db->set('relatorio_dupla', 't');
                    } else {
                        $this->db->set('relatorio_dupla', 'f');
                    }
                    if (isset($_POST['convenio_paciente'])) {
                        $this->db->set('convenio_paciente', 't');
                    } else {
                        $this->db->set('convenio_paciente', 'f');
                    }

                    if (isset($_POST['informacao_adicional'])) {
                        $this->db->set('informacao_adicional', 't');
                    } else {
                        $this->db->set('informacao_adicional', 'f');
                    }  


                    if (isset($_POST['agenda_atend'])) {
                        $this->db->set('agenda_atend', 't');
                    } else {
                        $this->db->set('agenda_atend', 'f');
                    }

                    if (isset($_POST['carteira_administrador'])) {
                        $this->db->set('carteira_administrador', 't');
                    } else {
                        $this->db->set('carteira_administrador', 'f');
                    }


                    if (isset($_POST['prontuario_antigo'])) {
                        $this->db->set('prontuario_antigo', 't');
                    } else {
                        $this->db->set('prontuario_antigo', 'f');
                    }


                    if (isset($_POST['aparecer_orcamento'])) {
                        $this->db->set('aparecer_orcamento', 't');
                    } else {
                        $this->db->set('aparecer_orcamento', 'f');
                    }

                    if (isset($_POST['guia_procedimento'])) {
                        $this->db->set('guia_procedimento', 't');
                    } else {
                        $this->db->set('guia_procedimento', 'f');
                    }



                    if (isset($_POST['agenda_modificada'])) {
                        $this->db->set('agenda_modificada', 't');
                    } else {
                        $this->db->set('agenda_modificada', 'f');
                    }



                    if (isset($_POST['cirugico_manual'])) {
                        $this->db->set('cirugico_manual', 't');
                    } else {
                        $this->db->set('cirugico_manual', 'f');
                    }



                    if (isset($_POST['fidelidade_paciente_antigo'])) {
                        $this->db->set('fidelidade_paciente_antigo', 't');
                    } else {
                        $this->db->set('fidelidade_paciente_antigo', 'f');
                    }

                    if (isset($_POST['hora_agendamento'])) {
                        $this->db->set('hora_agendamento', 't');
                    } else {
                        $this->db->set('hora_agendamento', 'f');
                    }

                    if (isset($_POST['historico_antigo_administrador'])) {
                        $this->db->set('historico_antigo_administrador', 't');
                    } else {
                        $this->db->set('historico_antigo_administrador', 'f');
                    }

                    if (isset($_POST['imprimir_medico'])) {
                        $this->db->set('imprimir_medico', 't');
                    } else {
                        $this->db->set('imprimir_medico', 'f');
                    }
                    if (isset($_POST['retirar_ordem_medico'])) {
                        $this->db->set('retirar_ordem_medico', 't');
                    } else {
                        $this->db->set('retirar_ordem_medico', 'f');
                    }

                    if (isset($_POST['convenio_padrao'])) {
                        $this->db->set('convenio_padrao', 't');
                    } else {
                        $this->db->set('convenio_padrao', 'f');
                    }

                    if (isset($_POST['tecnico_recepcao_editar'])) {
                        $this->db->set('tecnico_recepcao_editar', 't');
                    } else {
                        $this->db->set('tecnico_recepcao_editar', 'f');
                    }

                    if (isset($_POST['finalizar_atendimento_medico'])) {
                        $this->db->set('finalizar_atendimento_medico', 't');
                    } else {
                        $this->db->set('finalizar_atendimento_medico', 'f');
                    }

                    if (isset($_POST['sem_margens_laudo'])) {
                        $this->db->set('sem_margens_laudo', 't');
                    } else {
                        $this->db->set('sem_margens_laudo', 'f');
                    }

                    if (isset($_POST['gerente_cancelar_atendimento'])) {
                        $this->db->set('gerente_cancelar_atendimento', 't');
                    } else {
                        $this->db->set('gerente_cancelar_atendimento', 'f');
                    }


                    if (isset($_POST['enviar_para_atendimento'])) {
                        $this->db->set('enviar_para_atendimento', 't');
                    } else {
                        $this->db->set('enviar_para_atendimento', 'f');
                    }



                    if (isset($_POST['inadimplencia'])) {
                        $this->db->set('inadimplencia', 't');
                    } else {
                        $this->db->set('inadimplencia', 'f');
                    }

                    if (isset($_POST['relatorio_caixa_antigo'])) {
                        $this->db->set('relatorio_caixa_antigo', 't');
                    } else {
                        $this->db->set('relatorio_caixa_antigo', 'f');
                    }




                    $this->db->set('endereco_ficha', $_POST['endereco_ficha']);




                    if (isset($_POST['tecnico_acesso_acesso'])) {
                        $this->db->set('tecnico_acesso_acesso', 't');
                    } else {
                        $this->db->set('tecnico_acesso_acesso', 'f');
                    }
                    if (isset($_POST['nao_sobrepor_laudo'])) {
                        $this->db->set('nao_sobrepor_laudo', 't');
                    } else {
                        $this->db->set('nao_sobrepor_laudo', 'f');
                    }


                    if (isset($_POST['tabela_bpa'])) {
                        $this->db->set('tabela_bpa', 't');
                    } else {
                        $this->db->set('tabela_bpa', 'f');
                    }

                    if (isset($_POST['empresas_unicas'])) {
                        $this->db->set('empresas_unicas', 't');
                    } else {
                        $this->db->set('empresas_unicas', 'f');
                    }

                    if (isset($_POST['alterar_data_emissao'])) {
                        $this->db->set('alterar_data_emissao', 't');
                    } else {
                        $this->db->set('alterar_data_emissao', 'f');
                    }
                    
                     if (isset($_POST['a4_receituario_especial'])) {
                        $this->db->set('a4_receituario_especial', 't');
                    } else {
                        $this->db->set('a4_receituario_especial', 'f');
                    }


                    if (isset($_POST['pesquisar_responsavel'])) {
                        $this->db->set('pesquisar_responsavel', 't');
                    } else {
                        $this->db->set('pesquisar_responsavel', 'f');
                    }

                    if (isset($_POST['agendahias'])) {
                        $this->db->set('agendahias', 't');
                    } else {
                        $this->db->set('agendahias', 'f');
                    }

                    if (isset($_POST['desativarelatend'])) {
                        $this->db->set('desativarelatend', 't');
                    } else {
                        $this->db->set('desativarelatend', 'f');
                    }

                    if (isset($_POST['whatsapp'])) {
                        $this->db->set('whatsapp', 't');
                    } else {
                        $this->db->set('whatsapp', 'f');
                    }

                    if (isset($_POST['travar_convenio_paciente'])) {
                        $this->db->set('travar_convenio_paciente', 't');
                    } else {
                        $this->db->set('travar_convenio_paciente', 'f');
                    }

                    if (isset($_POST['prontuario_antigo_pesquisar'])) {
                        $this->db->set('prontuario_antigo_pesquisar', 't');
                    } else {
                        $this->db->set('prontuario_antigo_pesquisar', 'f');
                    }
                    
                    if (isset($_POST['tarefa_medico'])) {
                        $this->db->set('tarefa_medico', 't');
                    } else {
                        $this->db->set('tarefa_medico', 'f');
                    }

                    if (isset($_POST['precadastro'])) {
                        $this->db->set('precadastro', 't');
                    } else {
                        $this->db->set('precadastro', 'f');
                    }

                    if (isset($_POST['solicitar_sabin'])) {
                        $this->db->set('solicitar_sabin', 't');
                    } else {
                        $this->db->set('solicitar_sabin', 'f');
                    }
                    if (isset($_POST['data_pesquisa_financeiro'])) {
                        $this->db->set('data_pesquisa_financeiro', 't');
                    } else {
                        $this->db->set('data_pesquisa_financeiro', 'f');
                    }

                    if (isset($_POST['agenda_albert'])) {
                        $this->db->set('agenda_albert', 't');
                    } else {
                        $this->db->set('agenda_albert', 'f');
                    }
                    
                    if (isset($_POST['espera_intercalada'])) {
                        $this->db->set('espera_intercalada', 't');
                    } else {
                        $this->db->set('espera_intercalada', 'f');
                    }

                    if (isset($_POST['corretor_ortografico'])) {
                        $this->db->set('corretor_ortografico', 't');
                    } else {
                        $this->db->set('corretor_ortografico', 'f');
                    }

                    if (isset($_POST['nguia_autorizacao'])) {
                        $this->db->set('nguia_autorizacao', 't');
                    } else {
                        $this->db->set('nguia_autorizacao', 'f');
                    }

                    if (isset($_POST['id_linha_financeiro'])) {
                        $this->db->set('id_linha_financeiro', 't');
                    } else {
                        $this->db->set('id_linha_financeiro', 'f');
                    }
                    if (isset($_POST['valores_recepcao'])) {
                        $this->db->set('valores_recepcao', 't');
                    } else {
                        $this->db->set('valores_recepcao', 'f');
                    }
                     if (isset($_POST['remove_margem_cabecalho_rodape'])) {
                        $this->db->set('remove_margem_cabecalho_rodape', 't');
                    } else {
                        $this->db->set('remove_margem_cabecalho_rodape', 'f');
                    }
                    if (isset($_POST['laudo_status_f'])) {
                        $this->db->set('laudo_status_f', 't');
                    } else {
                        $this->db->set('laudo_status_f', 'f');
                    }
                    if ($_POST['solicitacaotempo'] != "") {
                        $this->db->set('solicitacaotempo', $_POST['solicitacaotempo']);
                    } else {
                        $this->db->set('solicitacaotempo', null);
                    }
                    if (isset($_POST['atender_todos'])) {
                        $this->db->set('atender_todos', 't');
                    } else {
                        $this->db->set('atender_todos', 'f');
                    }
                    if (isset($_POST['btn_encaixe'])) {
                        $this->db->set('btn_encaixe', 't');
                    } else {
                        $this->db->set('btn_encaixe', 'f');
                    }
                    
                    
                    if (isset($_POST['filtrar_agenda_2'])) {
                        $this->db->set('filtrar_agenda_2', 't');
                    } else {
                        $this->db->set('filtrar_agenda_2', 'f');
                    }

                    if (isset($_POST['informar_faltas'])) {
                        $this->db->set('informar_faltas', 't');
                    } else {
                        $this->db->set('informar_faltas', 'f');
                    }

                    if (isset($_POST['data_hora_sala_espera'])) {
                        $this->db->set('data_hora_sala_espera', 't');
                    } else {
                        $this->db->set('data_hora_sala_espera', 'f');
                    }

                    if (isset($_POST['email_obrigatorio'])) {
                        $this->db->set('email_obrigatorio', 't');
                    } else {
                        $this->db->set('email_obrigatorio', 'f');
                    }

                    if (isset($_POST['nota_fiscal_sp'])) {
                        $this->db->set('nota_fiscal_sp', 't');
                    } else {
                        $this->db->set('nota_fiscal_sp', 'f');
                    }

                    if (isset($_POST['status_faltou_manual'])) {
                        $this->db->set('status_faltou_manual', 't');
                    } else {
                        $this->db->set('status_faltou_manual', 'f');
                    }
                    
                    if ($_POST['link_sistema_paciente'] != "") {
                        $this->db->set('link_sistema_paciente', $_POST['link_sistema_paciente']);
                    } else {
                        $this->db->set('link_sistema_paciente', null);
                    }
                    
                    if (isset($_POST['modificar_btn_multifuncao'])) {
                        $this->db->set('modificar_btn_multifuncao', 't');
                    } else {
                        $this->db->set('modificar_btn_multifuncao', 'f');
                    }
                    
                    if (isset($_POST['redutor_valor_liquido'])) {
                        $this->db->set('redutor_valor_liquido', 't');
                    } else {
                        $this->db->set('redutor_valor_liquido', 'f');
                    }
                    

                }


                $this->db->set('empresa_id', $empresa_id);
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_empresa_permissoes');




                $this->db->select('*');
                $this->db->from('tb_empresa_permissoes');
                $this->db->where('ativo', 't');
                $this->db->orderby('empresa_id desc');
                $return_perm = $this->db->get()->result_array();
//                echo '<pre>';
//                var_dump($return_perm);
//                die;
                if (count($return_perm) > 0) {

                    foreach ($return_perm[1] as $key_select => $value_select) {
                        if ($key_select != 'empresa_permissoes_id' && $key_select != 'empresa_id') {
                            $this->db->set("$key_select", $value_select);
                        }
                    }
                }




                $this->db->where('empresa_id', $empresa_id);
                $this->db->update('tb_empresa_permissoes');

                $this->db->select('internacao,
                                    chat,
                                    impressao_declaracao,
                                    impressao_recibo,
                                    email,
                                    impressao_laudo,
                                    centrocirurgico,
                                    relatoriorm,
                                    servicosms,
                                    servicoemail,
                                    email_mensagem_confirmacao,
                                    email_mensagem_agradecimento,
                                    imagem,
                                    consulta,
                                    especialidade,
                                    geral,
                                    faturamento,
                                    estoque,
                                    financeiro,
                                    marketing,
                                    laboratorio,
                                    ponto,
                                    calendario,
                                    email_mensagem_falta,
                                    botao_faturar_guia,
                                    botao_faturar_procedimento,
                                    chamar_consulta,
                                    procedimento_multiempresa,
                                    data_contaspagar,
                                    medico_laudodigitador,
                                    cabecalho_config,
                                    rodape_config,
                                    laudo_config,
                                    recibo_config,
                                    ficha_config,
                                    odontologia,
                                    producao_medica_saida,

                                    impressao_orcamento,
                                    mostrar_logo_clinica,
                                    declaracao_config,
                                    atestado_config,
                                    horario_sab,
                                    horario_seg_sex,
                                    farmacia,
                                    numero_empresa_painel,
                                    endereco_toten,
                                    horario_seg_sex_inicio,
                                    horario_seg_sex_fim,
                                    horario_sab_inicio,
                                    horario_sab_fim,
                                    endereco_upload,
                                    endereco_upload_pasta,
                                    endereco_upload_pasta_paciente,
                                    impressao_internacao');
                $this->db->from('tb_empresa');
                $this->db->where('ativo', 't');
                $this->db->where('empresa_id !=', $empresa_id);
                $this->db->orderby('empresa_id desc');
                $return_emp = $this->db->get()->result_array();

                if (count($return_emp) > 0) {

                    foreach ($return_emp[0] as $key_select => $value_select) {
                        if ($key_select != 'empresa_id') {
                            $this->db->set("$key_select", $value_select);
                        }
                    }
                }

                $this->db->where('empresa_id', $empresa_id);
                $this->db->update('tb_empresa');
                $erro = $this->db->_error_message();
                if (trim($erro) != "") // erro de banco
                    return -1;
                else
                    $empresa_id = $this->db->insert_id();
            }
            else { // update
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $this->db->set('endereco_externo', $_POST['endereco_externo']);
                $this->db->set('endereco_externo_base', $_POST['endereco_externo_base']);
                if(isset($_POST['site_empresa']) && $_POST['site_empresa'] != ""){
                   $this->db->set('site_empresa', $_POST['site_empresa']);
                }else{
                   $this->db->set('site_empresa', null);
                }
                if(isset($_POST['facebook_empresa']) && $_POST['facebook_empresa'] != ""){
                 $this->db->set('facebook_empresa', $_POST['facebook_empresa']);
                }else{
                 $this->db->set('facebook_empresa',null);   
                }
                if(isset($_POST['instagram_empresa']) && $_POST['instagram_empresa'] != ""){
                 $this->db->set('instagram_empresa', $_POST['instagram_empresa']);
                }else{
                 $this->db->set('instagram_empresa',null);   
                }
                
                $this->db->set('endereco_toten', $_POST['endereco_toten']);
                $empresa_id = $_POST['txtempresaid'];
                $this->db->where('empresa_id', $empresa_id);
                $this->db->update('tb_empresa');
                if ($operador_id == 1) {
                    if (isset($_POST['historico_completo'])) {
                        $this->db->set('historico_completo', 't');
                    } else {
                        $this->db->set('historico_completo', 'f');
                    }
                    if (isset($_POST['origem_agendamento'])) {
                        $this->db->set('origem_agendamento', 't');
                    } else {
                        $this->db->set('origem_agendamento', 'f');
                    }
                    if (isset($_POST['manter_gastos'])) {
                        $this->db->set('manter_gastos', 't');
                    } else {
                        $this->db->set('manter_gastos', 'f');
                    }
                    if (isset($_POST['producao_por_valor'])) {
                        $this->db->set('producao_por_valor', 't');
                    } else {
                        $this->db->set('producao_por_valor', 'f');
                    }
                    if (isset($_POST['filaaparelho'])) {
                        $this->db->set('filaaparelho', 't');
                    } else {
                        $this->db->set('filaaparelho', 'f');
                    }
                    if (isset($_POST['setores'])) {
                        $this->db->set('setores', 't');
                    } else {
                        $this->db->set('setores', 'f');
                    }
                    if (isset($_POST['certificado_digital'])) {
                        $this->db->set('certificado_digital', 't');
                    } else {
                        $this->db->set('certificado_digital', 'f');
                    }
                    if (isset($_POST['certificado_digital_manual'])) {
                        $this->db->set('certificado_digital_manual', 't');
                    } else {
                        $this->db->set('certificado_digital_manual', 'f');
                    }
                    if (isset($_POST['dashboard_administrativo'])) {
                        $this->db->set('dashboard_administrativo', 't');
                    } else {
                        $this->db->set('dashboard_administrativo', 'f');
                    }
                    if (isset($_POST['integrar_google'])) {
                        $this->db->set('integrar_google', 't');
                    } else {
                        $this->db->set('integrar_google', 'f');
                    }
                    if (isset($_POST['entrega_laudos'])) {
                        $this->db->set('entrega_laudos', 't');
                    } else {
                        $this->db->set('entrega_laudos', 'f');
                    }
                    if (isset($_POST['bardeira_status'])) {
                        $this->db->set('bardeira_status', 't');
                    } else {
                        $this->db->set('bardeira_status', 'f');
                    }
                    if (isset($_POST['diagnostico_medico'])) {
                        $this->db->set('diagnostico_medico', 't');
                    } else {
                        $this->db->set('diagnostico_medico', 'f');
                    }
                    if (isset($_POST['impressoes_acompanhamento'])) {
                        $this->db->set('impressoes_acompanhamento', 't');
                    } else {
                        $this->db->set('impressoes_acompanhamento', 'f');
                    }
                    if (isset($_POST['procedimento_excecao'])) {
                        $this->db->set('procedimento_excecao', 't');
                    } else {
                        $this->db->set('procedimento_excecao', 'f');
                    }
                    if (isset($_POST['valor_autorizar'])) {
                        $this->db->set('valor_autorizar', 't');
                    } else {
                        $this->db->set('valor_autorizar', 'f');
                    }
                    if (count(@$_POST['campos_obrigatorio']) > 0) {
                        $this->db->set('campos_cadastro', json_encode(@$_POST['campos_obrigatorio']));
                    } else {
                        $this->db->set('campos_cadastro', '');
                    }
                    if (isset($_POST['gerente_cancelar'])) {
                        $this->db->set('gerente_cancelar', 't');
                    } else {
                        $this->db->set('gerente_cancelar', 'f');
                    }
                    if (isset($_POST['profissional_agendar'])) {
                        $this->db->set('profissional_agendar', 't');
                    } else {
                        $this->db->set('profissional_agendar', 'f');
                    }
                    if (@isset($_POST['profissional_externo'])) {
                        $this->db->set('profissional_externo', 't');
                    } else {
                        $this->db->set('profissional_externo', 'f');
                    }
                    if (count(@$_POST['opc_telatendimento']) > 0) {
                        $this->db->set('campos_atendimentomed', json_encode($_POST['opc_telatendimento']));
                    } else {
                        $this->db->set('campos_atendimentomed', '');
                    }
                    if (@count($_POST['botoes_app']) > 0) {
                        $this->db->set('botoes_app', json_encode($_POST['botoes_app']));
                    } else {
                        $this->db->set('botoes_app', '');
                    }
                    if (@count(@$_POST['modelos_atendimento']) > 0) {
                        $this->db->set('modelos_atendimento', json_encode($_POST['modelos_atendimento']));
                    } else {
                        $this->db->set('modelos_atendimento', '');
                    }
                    if (@count(@$_POST['abas_atendimento']) > 0) {
                        $this->db->set('abas_atendimento', json_encode($_POST['abas_atendimento']));
                    } else {
                        $this->db->set('abas_atendimento', '');
                    }
                    if (@count($_POST['opc_listaatendimentomed']) > 0) {
                        $this->db->set('campos_listaatendimentomed', json_encode($_POST['opc_listaatendimentomed']));
                    } else {
                        $this->db->set('campos_listaatendimentomed', '');
                    }





                    if (@count($_POST['opc_dadospaciente']) > 0) {
                        $this->db->set('dados_atendimentomed', json_encode($_POST['opc_dadospaciente']));
                    } else {
                        $this->db->set('dados_atendimentomed', '');
                    }
                    if (isset($_POST['modelo_laudo_medico'])) {
                        $this->db->set('modelo_laudo_medico', 't');
                    } else {
                        $this->db->set('modelo_laudo_medico', 'f');
                    }
                    if (isset($_POST['orcamento_multiplo'])) {
                        $this->db->set('orcamento_multiplo', 't');
                    } else {
                        $this->db->set('orcamento_multiplo', 'f');
                    }
                    if (isset($_POST['profissional_completo'])) {
                        $this->db->set('profissional_completo', 't');
                    } else {
                        $this->db->set('profissional_completo', 'f');
                    }
                    if (isset($_POST['agenda_modelo2'])) {
                        $this->db->set('agenda_modelo2', 't');
                    } else {
                        $this->db->set('agenda_modelo2', 'f');
                    }
                    if (isset($_POST['autorizar_sala_espera'])) {
                        $this->db->set('autorizar_sala_espera', 't');
                    } else {
                        $this->db->set('autorizar_sala_espera', 'f');
                    }
                    if (isset($_POST['reservar_escolher_proc'])) {
                        $this->db->set('reservar_escolher_proc', 't');
                    } else {
                        $this->db->set('reservar_escolher_proc', 'f');
                    }
                    if (isset($_POST['gerente_cancelar_sala'])) {
                        $this->db->set('gerente_cancelar_sala', 't');
                    } else {
                        $this->db->set('gerente_cancelar_sala', 'f');
                    }
                    if (isset($_POST['tecnica_promotor'])) {
                        $this->db->set('tecnica_promotor', 't');
                    } else {
                        $this->db->set('tecnica_promotor', 'f');
                    }
                    if (isset($_POST['botao_imagem_paciente'])) {
                        $this->db->set('botao_imagem_paciente', 't');
                    } else {
                        $this->db->set('botao_imagem_paciente', 'f');
                    }
                    if (isset($_POST['botao_arquivos_paciente'])) {
                        $this->db->set('botao_arquivos_paciente', 't');
                    } else {
                        $this->db->set('botao_arquivos_paciente', 'f');
                    }
//                    if (isset($_POST['fila_caixa'])) {
//                        $this->db->set('caixa', 't');
//                    } else {
//                        $this->db->set('caixa', 'f');
//                    }
                    // var_dump($_POST['botao_laudo_paciente']); die;
                    if (isset($_POST['botao_laudo_paciente'])) {
                        $this->db->set('botao_laudo_paciente', 't');
                    } else {
                        $this->db->set('botao_laudo_paciente', 'f');
                    }
                    if (isset($_POST['gerente_recepcao_top_saude'])) {
                        $this->db->set('gerente_recepcao_top_saude', 't');
                    } else {
                        $this->db->set('gerente_recepcao_top_saude', 'f');
                    }
                    if (isset($_POST['gerente_relatorio_financeiro'])) {
                        $this->db->set('gerente_relatorio_financeiro', 't');
                    } else {
                        $this->db->set('gerente_relatorio_financeiro', 'f');
                    }
                    if (isset($_POST['valor_convenio_nao'])) {
                        $this->db->set('valor_convenio_nao', 't');
                    } else {
                        $this->db->set('valor_convenio_nao', 'f');
                    }
                    if (isset($_POST['orcamento_cadastro'])) {
                        $this->db->set('orcamento_cadastro', 't');
                    } else {
                        $this->db->set('orcamento_cadastro', 'f');
                    }
                    if (isset($_POST['desativar_taxa_administracao'])) {
                        $this->db->set('desativar_taxa_administracao', 't');
                    } else {
                        $this->db->set('desativar_taxa_administracao', 'f');
                    }
                    if (isset($_POST['producao_alternativo'])) {
                        $this->db->set('producao_alternativo', 't');
                    } else {
                        $this->db->set('producao_alternativo', 'f');
                    }
                    if (isset($_POST['tecnica_enviar'])) {
                        $this->db->set('tecnica_enviar', 't');
                    } else {
                        $this->db->set('tecnica_enviar', 'f');
                    }
                    if (isset($_POST['subgrupo'])) {
                        $this->db->set('subgrupo', 't');
                    } else {
                        $this->db->set('subgrupo', 'f');
                    }
                    if (isset($_POST['conjuge'])) {
                        $this->db->set('conjuge', 't');
                    } else {
                        $this->db->set('conjuge', 'f');
                    }
                    if (isset($_POST['valor_laboratorio'])) {
                        $this->db->set('valor_laboratorio', 't');
                    } else {
                        $this->db->set('valor_laboratorio', 'f');
                    }
                    if (isset($_POST['laudo_sigiloso'])) {
                        $this->db->set('laudo_sigiloso', 't');
                    } else {
                        $this->db->set('laudo_sigiloso', 'f');
                    }
                    if (isset($_POST['faturamento_novo'])) {
                        $this->db->set('faturamento_novo', 't');
                    } else {
                        $this->db->set('faturamento_novo', 'f');
                    }
                    if (isset($_POST['gerente_contasapagar'])) {
                        $this->db->set('gerente_contasapagar', 't');
                    } else {
                        $this->db->set('gerente_contasapagar', 'f');
                    }
                    if (isset($_POST['encaminhamento_email'])) {
                        $this->db->set('encaminhamento_email', 't');
                    } else {
                        $this->db->set('encaminhamento_email', 'f');
                    }
                    if (isset($_POST['cpf_obrigatorio'])) {
                        $this->db->set('cpf_obrigatorio', 't');
                    } else {
                        $this->db->set('cpf_obrigatorio', 'f');
                    }
                    if (isset($_POST['orcamento_recepcao'])) {
                        $this->db->set('orcamento_recepcao', 't');
                    } else {
                        $this->db->set('orcamento_recepcao', 'f');
                    }
                    if (isset($_POST['relatorio_ordem'])) {
                        $this->db->set('relatorio_ordem', 't');
                    } else {
                        $this->db->set('relatorio_ordem', 'f');
                    }
                    if (isset($_POST['relatorio_producao'])) {
                        $this->db->set('relatorio_producao', 't');
                    } else {
                        $this->db->set('relatorio_producao', 'f');
                    }
                    if (isset($_POST['relatorios_recepcao'])) {
                        $this->db->set('relatorios_recepcao', 't');
                    } else {
                        $this->db->set('relatorios_recepcao', 'f');
                    }
                    if (isset($_POST['financeiro_cadastro'])) {
                        $this->db->set('financeiro_cadastro', 't');
                    } else {
                        $this->db->set('financeiro_cadastro', 'f');
                    }

                    if (isset($_POST['ordem_chegada'])) {
                        $this->db->set('ordem_chegada', 't');
                    } else {
                        $this->db->set('ordem_chegada', 'f');
                    }
                    if (isset($_POST['login_paciente'])) {
                        $this->db->set('login_paciente', 't');
                    } else {
                        $this->db->set('login_paciente', 'f');
                    }

                    if (isset($_POST['credito'])) {
                        $this->db->set('credito', 't');
                    } else {
                        $this->db->set('credito', 'f');
                    }

                    if (isset($_POST['orcamento_config'])) {
                        $this->db->set('orcamento_config', 't');
                    } else {
                        $this->db->set('orcamento_config', 'f');
                    }

                    if (isset($_POST['subgrupo'])) {
                        $this->db->set('subgrupo', 't');
                    } else {
                        $this->db->set('subgrupo', 'f');
                    }

                    if (isset($_POST['odontologia_valor_alterar'])) {
                        $this->db->set('odontologia_valor_alterar', 't');
                    } else {
                        $this->db->set('odontologia_valor_alterar', 'f');
                    }
                    if (isset($_POST['selecionar_retorno'])) {
                        $this->db->set('selecionar_retorno', 't');
                    } else {
                        $this->db->set('selecionar_retorno', 'f');
                    }
                    if (isset($_POST['administrador_cancelar'])) {
                        $this->db->set('administrador_cancelar', 't');
                    } else {
                        $this->db->set('administrador_cancelar', 'f');
                    }
                    if (isset($_POST['valor_recibo_guia'])) {
                        $this->db->set('valor_recibo_guia', 't');
                    } else {
                        $this->db->set('valor_recibo_guia', 'f');
                    }
                    if (isset($_POST['calendario_layout'])) {
                        $this->db->set('calendario_layout', 't');
                    } else {
                        $this->db->set('calendario_layout', 'f');
                    }
                    if (isset($_POST['excluir_transferencia'])) {
                        $this->db->set('excluir_transferencia', 't');
                    } else {
                        $this->db->set('excluir_transferencia', 'f');
                    }
                    if (isset($_POST['recomendacao_configuravel'])) {
                        $this->db->set('recomendacao_configuravel', 't');
                    } else {
                        $this->db->set('recomendacao_configuravel', 'f');
                    }
                    if (isset($_POST['recomendacao_obrigatorio'])) {
                        $this->db->set('recomendacao_obrigatorio', 't');
                    } else {
                        $this->db->set('recomendacao_obrigatorio', 'f');
                    }
                    if (isset($_POST['botao_ativar_sala'])) {
                        $this->db->set('botao_ativar_sala', 't');
                    } else {
                        $this->db->set('botao_ativar_sala', 'f');
                    }
                    if (isset($_POST['cancelar_sala_espera'])) {
                        $this->db->set('cancelar_sala_espera', 't');
                    } else {
                        $this->db->set('cancelar_sala_espera', 'f');
                    }
                    if (isset($_POST['oftamologia'])) {
                        $this->db->set('oftamologia', 't');
                    } else {
                        $this->db->set('oftamologia', 'f');
                    }
                    if (isset($_POST['promotor_medico'])) {
                        $this->db->set('promotor_medico', 't');
                    } else {
                        $this->db->set('promotor_medico', 'f');
                    }

                    if (isset($_POST['retirar_botao_ficha'])) {
                        $this->db->set('retirar_botao_ficha', 't');
                    } else {
                        $this->db->set('retirar_botao_ficha', 'f');
                    }

                    if (isset($_POST['desativar_personalizacao_impressao'])) {
                        $this->db->set('desativar_personalizacao_impressao', 't');
                    } else {
                        $this->db->set('desativar_personalizacao_impressao', 'f');
                    }

                    if (isset($_POST['carregar_modelo_receituario'])) {
                        $this->db->set('carregar_modelo_receituario', 't');
                    } else {
                        $this->db->set('carregar_modelo_receituario', 'f');
                    }

                    if (isset($_POST['caixa_personalizado'])) {
                        $this->db->set('caixa_personalizado', 't');
                    } else {
                        $this->db->set('caixa_personalizado', 'f');
                    }

                    if (isset($_POST['desabilitar_trava_retorno'])) {
                        $this->db->set('desabilitar_trava_retorno', 't');
                    } else {
                        $this->db->set('desabilitar_trava_retorno', 'f');
                    }

                    if (isset($_POST['associa_credito_procedimento'])) {
                        $this->db->set('associa_credito_procedimento', 't');
                    } else {
                        $this->db->set('associa_credito_procedimento', 'f');
                    }

                    if (in_array("dt_nascimento", $_POST['campos_obrigatorio'])) {
                        $this->db->set('campos_obrigatorios_pac_nascimento', 't');
                    } else {
                        $this->db->set('campos_obrigatorios_pac_nascimento', 'f');
                    }

                    if (in_array('sexo', $_POST['campos_obrigatorio'])) {
                        $this->db->set('campos_obrigatorios_pac_sexo', 't');
                    } else {
                        $this->db->set('campos_obrigatorios_pac_sexo', 'f');
                    }

                    if (in_array('cpf', $_POST['campos_obrigatorio'])) {
                        $this->db->set('campos_obrigatorios_pac_cpf', 't');
                    } else {
                        $this->db->set('campos_obrigatorios_pac_cpf', 'f');
                    }

                    if (in_array('telefone', $_POST['campos_obrigatorio'])) {
                        $this->db->set('campos_obrigatorios_pac_telefone', 't');
                    } else {
                        $this->db->set('campos_obrigatorios_pac_telefone', 'f');
                    }

                    if (in_array('municipio', $_POST['campos_obrigatorio'])) {
                        $this->db->set('campos_obrigatorios_pac_municipio', 't');
                    } else {
                        $this->db->set('campos_obrigatorios_pac_municipio', 'f');
                    }

                    if (isset($_POST['repetir_horarios_agenda'])) {
                        $this->db->set('repetir_horarios_agenda', 't');
                    } else {
                        $this->db->set('repetir_horarios_agenda', 'f');
                    }

                    if (isset($_POST['subgrupo_procedimento'])) {
                        $this->db->set('subgrupo_procedimento', 't');
                    } else {
                        $this->db->set('subgrupo_procedimento', 'f');
                    }

                    if (isset($_POST['senha_finalizar_laudo'])) {
                        $this->db->set('senha_finalizar_laudo', 't');
                    } else {
                        $this->db->set('senha_finalizar_laudo', 'f');
                    }

                    if (isset($_POST['retirar_flag_solicitante'])) {
                        $this->db->set('retirar_flag_solicitante', 't');
                    } else {
                        $this->db->set('retirar_flag_solicitante', 'f');
                    }

                    if (isset($_POST['cadastrar_painel_sala'])) {
                        $this->db->set('cadastrar_painel_sala', 't');
                    } else {
                        $this->db->set('cadastrar_painel_sala', 'f');
                    }

                    if (isset($_POST['apenas_procedimentos_multiplos'])) {
                        $this->db->set('apenas_procedimentos_multiplos', 't');
                    } else {
                        $this->db->set('apenas_procedimentos_multiplos', 'f');
                    }

                    if (isset($_POST['percentual_multiplo'])) {
                        $this->db->set('percentual_multiplo', 't');
                    } else {
                        $this->db->set('percentual_multiplo', 'f');
                    }

                    if (isset($_POST['ajuste_pagamento_procedimento'])) {
                        $this->db->set('ajuste_pagamento_procedimento', 't');
                    } else {
                        $this->db->set('ajuste_pagamento_procedimento', 'f');
                    }

                    if (isset($_POST['retirar_preco_procedimento'])) {
                        $this->db->set('retirar_preco_procedimento', 't');
                    } else {
                        $this->db->set('retirar_preco_procedimento', 'f');
                    }

                    if (isset($_POST['relatorios_clinica_med'])) {
                        $this->db->set('relatorios_clinica_med', 't');
                    } else {
                        $this->db->set('relatorios_clinica_med', 'f');
                    }
                    if (isset($_POST['impressao_cimetra'])) {
                        $this->db->set('impressao_cimetra', 't');
                    } else {
                        $this->db->set('impressao_cimetra', 'f');
                    }
                    if (isset($_POST['botao_ficha_convenio'])) {
                        $this->db->set('botao_ficha_convenio', 't');
                    } else {
                        $this->db->set('botao_ficha_convenio', 'f');
                    }
                    if (isset($_POST['manter_indicacao'])) {
                        $this->db->set('manter_indicacao', 't');
                    } else {
                        $this->db->set('manter_indicacao', 'f');
                    }
                    if (isset($_POST['fila_impressao'])) {
                        $this->db->set('fila_impressao', 't');
                    } else {
                        $this->db->set('fila_impressao', 'f');
                    }
                    if (isset($_POST['medico_solicitante'])) {
                        $this->db->set('medico_solicitante', 't');
                    } else {
                        $this->db->set('medico_solicitante', 'f');
                    }
                    if (isset($_POST['relatorio_operadora'])) {
                        $this->db->set('relatorio_operadora', 't');
                    } else {
                        $this->db->set('relatorio_operadora', 'f');
                    }
                    if (isset($_POST['relatorio_demandagrupo'])) {
                        $this->db->set('relatorio_demandagrupo', 't');
                    } else {
                        $this->db->set('relatorio_demandagrupo', 'f');
                    }
                    if (isset($_POST['relatorio_rm'])) {
                        $this->db->set('relatorio_rm', 't');
                    } else {
                        $this->db->set('relatorio_rm', 'f');
                    }
                    if (isset($_POST['relatorio_caixa'])) {
                        $this->db->set('relatorio_caixa', 't');
                    } else {
                        $this->db->set('relatorio_caixa', 'f');
                    }
                    if (isset($_POST['uso_salas'])) {
                        $this->db->set('uso_salas', 't');
                    } else {
                        $this->db->set('uso_salas', 'f');
                    }
                    if (isset($_POST['enfermagem'])) {
                        $this->db->set('enfermagem', 't');
                    } else {
                        $this->db->set('enfermagem', 'f');
                    }
                    if (isset($_POST['integracaosollis'])) {
                        $this->db->set('integracaosollis', 't');
                    } else {
                        $this->db->set('integracaosollis', 'f');
                    }
                    if (isset($_POST['medicinadotrabalho'])) {
                        $this->db->set('medicinadotrabalho', 't');
                    } else {
                        $this->db->set('medicinadotrabalho', 'f');
                    }
                    if (isset($_POST['ocupacao_pai'])) {
                        $this->db->set('ocupacao_pai', 't');
                    } else {
                        $this->db->set('ocupacao_pai', 'f');
                    }
                    if (isset($_POST['ocupacao_mae'])) {
                        $this->db->set('ocupacao_mae', 't');
                    } else {
                        $this->db->set('ocupacao_mae', 'f');
                    }
                    if (isset($_POST['limitar_acesso'])) {
                        $this->db->set('limitar_acesso', 't');
                    } else {
                        $this->db->set('limitar_acesso', 'f');
                    }
                    if (isset($_POST['perfil_marketing_p'])) {
                        $this->db->set('perfil_marketing_p', 't');
                    } else {
                        $this->db->set('perfil_marketing_p', 'f');
                    }
                    if (isset($_POST['filtrar_agenda'])) {
                        $this->db->set('filtrar_agenda', 't');
                    } else {
                        $this->db->set('filtrar_agenda', 'f');
                    }
                    if (isset($_POST['manternota'])) {
                        $this->db->set('manternota', 't');
                    } else {
                        $this->db->set('manternota', 'f');
                    }
                    if (isset($_POST['laboratorio_sc'])) {
                        $this->db->set('laboratorio_sc', 't');
                    } else {
                        $this->db->set('laboratorio_sc', 'f');
                    }
                    if (isset($_POST['ordenacao_situacao'])) {
                        $this->db->set('ordenacao_situacao', 't');
                    } else {
                        $this->db->set('ordenacao_situacao', 'f');
                    }
                    if (isset($_POST['financ_4n'])) {
                        $this->db->set('financ_4n', 't');
                    } else {
                        $this->db->set('financ_4n', 'f');
                    }
                    if (isset($_POST['liberar_perfil'])) {
                        $this->db->set('liberar_perfil', 't');
                    } else {
                        $this->db->set('liberar_perfil', 'f');
                    }
                    if (isset($_POST['bloquear_botao'])) {
                        $this->db->set('bloquear_botao', 't');
                    } else {
                        $this->db->set('bloquear_botao', 'f');
                    }
                    if (isset($_POST['atendimento_medico'])) {
                        $this->db->set('atendimento_medico', 't');
                    } else {
                        $this->db->set('atendimento_medico', 'f');
                    }

                    if (isset($_POST['atendimento_medico_3'])) {
                        $this->db->set('atendimento_medico_3', 't');
                    } else {
                        $this->db->set('atendimento_medico_3', 'f');
                    }

                    if (isset($_POST['grupo_convenio_proc'])) {
                        $this->db->set('grupo_convenio_proc', 't');
                    } else {
                        $this->db->set('grupo_convenio_proc', 'f');
                    }

                    if (isset($_POST['pagar_todos'])) {
                        $this->db->set('pagar_todos', 't');
                    } else {
                        $this->db->set('pagar_todos', 'f');
                    }



                    if (isset($_POST['faturar_parcial'])) {
                        $this->db->set('faturar_parcial', 't');
                    } else {
                        $this->db->set('faturar_parcial', 'f');
                    }
                    if (isset($_POST['caixa_grupo'])) {
                        $this->db->set('caixa_grupo', 't');
                    } else {
                        $this->db->set('caixa_grupo', 'f');
                    }

                    if (isset($_POST['medico_estoque'])) {
                        $this->db->set('medico_estoque', 't');
                    } else {
                        $this->db->set('medico_estoque', 'f');
                    }

                    if (isset($_POST['filtro_exame_cadastro'])) {
                        $this->db->set('filtro_exame_cadastro', 't');
                    } else {
                        $this->db->set('filtro_exame_cadastro', 'f');
                    }
                    if (isset($_POST['editar_historico_antigo'])) {
                        $this->db->set('editar_historico_antigo', 't');
                    } else {
                        $this->db->set('editar_historico_antigo', 'f');
                    }

                    if (isset($_POST['relatorio_dupla'])) {
                        $this->db->set('relatorio_dupla', 't');
                    } else {
                        $this->db->set('relatorio_dupla', 'f');
                    }

                    if (isset($_POST['convenio_paciente'])) {
                        $this->db->set('convenio_paciente', 't');
                    } else {
                        $this->db->set('convenio_paciente', 'f');
                    }

                    if (isset($_POST['informacao_adicional'])) {
                        $this->db->set('informacao_adicional', 't');
                    } else {
                        $this->db->set('informacao_adicional', 'f');
                    }


                    if (isset($_POST['agenda_atend'])) {
                        $this->db->set('agenda_atend', 't');
                    } else {
                        $this->db->set('agenda_atend', 'f');
                    }



                    if (isset($_POST['carteira_administrador'])) {
                        $this->db->set('carteira_administrador', 't');
                    } else {
                        $this->db->set('carteira_administrador', 'f');
                    }

                    if (isset($_POST['prontuario_antigo'])) {
                        $this->db->set('prontuario_antigo', 't');
                    } else {
                        $this->db->set('prontuario_antigo', 'f');
                    }

                    if (isset($_POST['aparecer_orcamento'])) {
                        $this->db->set('aparecer_orcamento', 't');
                    } else {
                        $this->db->set('aparecer_orcamento', 'f');
                    }

                    if (isset($_POST['guia_procedimento'])) {
                        $this->db->set('guia_procedimento', 't');
                    } else {
                        $this->db->set('guia_procedimento', 'f');
                    }

                    if (isset($_POST['agenda_modificada'])) {
                        $this->db->set('agenda_modificada', 't');
                    } else {
                        $this->db->set('agenda_modificada', 'f');
                    }


                    if (isset($_POST['cirugico_manual'])) {
                        $this->db->set('cirugico_manual', 't');
                    } else {
                        $this->db->set('cirugico_manual', 'f');
                    }



                    if (isset($_POST['fidelidade_paciente_antigo'])) {
                        $this->db->set('fidelidade_paciente_antigo', 't');
                    } else {
                        $this->db->set('fidelidade_paciente_antigo', 'f');
                    }
                    if (isset($_POST['hora_agendamento'])) {
                        $this->db->set('hora_agendamento', 't');
                    } else {
                        $this->db->set('hora_agendamento', 'f');
                    }

                    if (isset($_POST['historico_antigo_administrador'])) {
                        $this->db->set('historico_antigo_administrador', 't');
                    } else {
                        $this->db->set('historico_antigo_administrador', 'f');
                    }

                    if (isset($_POST['imprimir_medico'])) {
                        $this->db->set('imprimir_medico', 't');
                    } else {
                        $this->db->set('imprimir_medico', 'f');
                    }
                    if (isset($_POST['retirar_ordem_medico'])) {
                        $this->db->set('retirar_ordem_medico', 't');
                    } else {
                        $this->db->set('retirar_ordem_medico', 'f');
                    }

                    if (isset($_POST['convenio_padrao'])) {
                        $this->db->set('convenio_padrao', 't');
                    } else {
                        $this->db->set('convenio_padrao', 'f');
                    }

                    if (isset($_POST['tecnico_recepcao_editar'])) {
                        $this->db->set('tecnico_recepcao_editar', 't');
                    } else {
                        $this->db->set('tecnico_recepcao_editar', 'f');
                    }

                    if (isset($_POST['finalizar_atendimento_medico'])) {
                        $this->db->set('finalizar_atendimento_medico', 't');
                    } else {
                        $this->db->set('finalizar_atendimento_medico', 'f');
                    }

                    if (isset($_POST['sem_margens_laudo'])) {
                        $this->db->set('sem_margens_laudo', 't');
                    } else {
                        $this->db->set('sem_margens_laudo', 'f');
                    }


                    if (isset($_POST['gerente_cancelar_atendimento'])) {
                        $this->db->set('gerente_cancelar_atendimento', 't');
                    } else {
                        $this->db->set('gerente_cancelar_atendimento', 'f');
                    }


                    if (isset($_POST['enviar_para_atendimento'])) {
                        $this->db->set('enviar_para_atendimento', 't');
                    } else {
                        $this->db->set('enviar_para_atendimento', 'f');
                    }





                    if (isset($_POST['inadimplencia'])) {
                        $this->db->set('inadimplencia', 't');
                    } else {
                        $this->db->set('inadimplencia', 'f');
                    }

                    if (isset($_POST['relatorio_caixa_antigo'])) {
                        $this->db->set('relatorio_caixa_antigo', 't');
                    } else {
                        $this->db->set('relatorio_caixa_antigo', 'f');
                    }

                    $this->db->set('endereco_ficha', $_POST['endereco_ficha']);

                    if (isset($_POST['tecnico_acesso_acesso'])) {
                        $this->db->set('tecnico_acesso_acesso', 't');
                    } else {
                        $this->db->set('tecnico_acesso_acesso', 'f');
                    }
                    if (isset($_POST['nao_sobrepor_laudo'])) {
                        $this->db->set('nao_sobrepor_laudo', 't');
                    } else {
                        $this->db->set('nao_sobrepor_laudo', 'f');
                    }
                    if (isset($_POST['tabela_bpa'])) {
                        $this->db->set('tabela_bpa', 't');
                    } else {
                        $this->db->set('tabela_bpa', 'f');
                    }
                    if (isset($_POST['empresas_unicas'])) {
                        $this->db->set('empresas_unicas', 't');
                    } else {
                        $this->db->set('empresas_unicas', 'f');
                    }

                    if (isset($_POST['alterar_data_emissao'])) {
                        $this->db->set('alterar_data_emissao', 't');
                    } else {
                        $this->db->set('alterar_data_emissao', 'f');
                    }
                    
                    if (isset($_POST['a4_receituario_especial'])) {
                        $this->db->set('a4_receituario_especial', 't');
                    } else {
                        $this->db->set('a4_receituario_especial', 'f');
                    }

                    if (isset($_POST['pesquisar_responsavel'])) {
                        $this->db->set('pesquisar_responsavel', 't');
                    } else {
                        $this->db->set('pesquisar_responsavel', 'f');
                    }

                    if (isset($_POST['agendahias'])) {
                        $this->db->set('agendahias', 't');
                    } else {
                        $this->db->set('agendahias', 'f');
                    }

                    if (isset($_POST['desativarelatend'])) {
                        $this->db->set('desativarelatend', 't');
                    } else {
                        $this->db->set('desativarelatend', 'f');
                    }

                    if (isset($_POST['whatsapp'])) {
                        $this->db->set('whatsapp', 't');
                    } else {
                        $this->db->set('whatsapp', 'f');
                    }

                    if (isset($_POST['travar_convenio_paciente'])) {
                        $this->db->set('travar_convenio_paciente', 't');
                    } else {
                        $this->db->set('travar_convenio_paciente', 'f');
                    }
                    if (isset($_POST['prontuario_antigo_pesquisar'])) {
                        $this->db->set('prontuario_antigo_pesquisar', 't');
                    } else {
                        $this->db->set('prontuario_antigo_pesquisar', 'f');
                    }

                    if (isset($_POST['tarefa_medico'])) {
                        $this->db->set('tarefa_medico', 't');
                    } else {
                        $this->db->set('tarefa_medico', 'f');
                    }

                    if (isset($_POST['precadastro'])) {
                        $this->db->set('precadastro', 't');
                    } else {
                        $this->db->set('precadastro', 'f');
                    }
                    
                    if (isset($_POST['solicitar_sabin'])) {
                        $this->db->set('solicitar_sabin', 't');
                    } else {
                        $this->db->set('solicitar_sabin', 'f');
                    }

                    if (isset($_POST['data_pesquisa_financeiro'])) {
                        $this->db->set('data_pesquisa_financeiro', 't');
                    } else {
                        $this->db->set('data_pesquisa_financeiro', 'f');
                    }
                    
                    if (isset($_POST['agenda_albert'])) {
                        $this->db->set('agenda_albert', 't');
                    } else {
                        $this->db->set('agenda_albert', 'f');
                    }
                    
                    if (isset($_POST['espera_intercalada'])) {
                        $this->db->set('espera_intercalada', 't');
                    } else {
                        $this->db->set('espera_intercalada', 'f');
                    }
                    
                    if (isset($_POST['corretor_ortografico'])) {
                        $this->db->set('corretor_ortografico', 't');
                    } else {
                        $this->db->set('corretor_ortografico', 'f');
                    }
                    
                    if (isset($_POST['nguia_autorizacao'])) {
                        $this->db->set('nguia_autorizacao', 't');
                    } else {
                        $this->db->set('nguia_autorizacao', 'f');
                    }
                    
                    if (isset($_POST['id_linha_financeiro'])) {
                        $this->db->set('id_linha_financeiro', 't');
                    } else {
                        $this->db->set('id_linha_financeiro', 'f');
                    }
                    if (isset($_POST['valores_recepcao'])) {
                        $this->db->set('valores_recepcao', 't');
                    } else {
                        $this->db->set('valores_recepcao', 'f');
                    }
                    if (isset($_POST['remove_margem_cabecalho_rodape'])) {
                        $this->db->set('remove_margem_cabecalho_rodape', 't');
                    } else {
                        $this->db->set('remove_margem_cabecalho_rodape', 'f');
                    }
                    if (isset($_POST['laudo_status_f'])) {
                        $this->db->set('laudo_status_f', 't');
                    } else {
                        $this->db->set('laudo_status_f', 'f');
                    }
                    if ($_POST['solicitacaotempo'] != "") {
                        $this->db->set('solicitacaotempo', $_POST['solicitacaotempo']);
                    } else {
                        $this->db->set('solicitacaotempo', null);
                    } 
                    
                    if (isset($_POST['atender_todos'])) {
                        $this->db->set('atender_todos', 't');
                    } else {
                        $this->db->set('atender_todos', 'f');
                    }
                    
                    
                    if (isset($_POST['filtrar_agenda_2'])) {
                        $this->db->set('filtrar_agenda_2', 't');
                    } else {
                        $this->db->set('filtrar_agenda_2', 'f');
                    } 

                    if (isset($_POST['informar_faltas'])) {
                        $this->db->set('informar_faltas', 't');
                    } else {
                        $this->db->set('informar_faltas', 'f');
                    }

                    if (isset($_POST['data_hora_sala_espera'])) {
                        $this->db->set('data_hora_sala_espera', 't');
                    } else {
                        $this->db->set('data_hora_sala_espera', 'f');
                    }
                    if (isset($_POST['email_obrigatorio'])) {
                        $this->db->set('email_obrigatorio', 't');
                    } else {
                        $this->db->set('email_obrigatorio', 'f');
                    }
                    if (isset($_POST['nota_fiscal_sp'])) {
                        $this->db->set('nota_fiscal_sp', 't');
                    } else {
                        $this->db->set('nota_fiscal_sp', 'f');
                    }
                    if (isset($_POST['status_faltou_manual'])) {
                        $this->db->set('status_faltou_manual', 't');
                    } else {
                        $this->db->set('status_faltou_manual', 'f');
                    }
                    
                    
                    
                    if (isset($_POST['btn_encaixe'])) {
                        $this->db->set('btn_encaixe', 't');
                        
                    } else {
                        $this->db->set('btn_encaixe', 'f'); 
                    }
                      
                    if ($_POST['link_sistema_paciente'] != "") {
                        $this->db->set('link_sistema_paciente', $_POST['link_sistema_paciente']);
                    } else {
                        $this->db->set('link_sistema_paciente', null);
                    }
                    
                    if (isset($_POST['modificar_btn_multifuncao'])) {
                        $this->db->set('modificar_btn_multifuncao', 't');
                    } else {
                        $this->db->set('modificar_btn_multifuncao', 'f');
                    }
                     
                    if (isset($_POST['redutor_valor_liquido'])) {
                        $this->db->set('redutor_valor_liquido', 't');
                    } else {
                        $this->db->set('redutor_valor_liquido', 'f');
                    }
                    
                    
                }

                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $this->db->where('empresa_id', $empresa_id);
                $this->db->update('tb_empresa_permissoes');

                if (!empty($_POST["associados_grupos"]) and is_array($_POST["associados_grupos"])) {
                    $arrayteste = $_POST["associados_grupos"];
//
                    for ($i = 0; $i < count($_POST["associados_grupos"]); $i++) {
                        $this->db->where('empresa_associacoes_grupo_id !=', $_POST["associados_grupos"][$i]);
                        $this->db->where('empresa_id', $empresa_id);
                        $this->db->set('ativo', 'f');
                        $this->db->update('tb_empresa_associacoes_grupo');
                    }
                } else {
                    $this->db->where('empresa_id', $empresa_id);
                    $this->db->set('ativo', 'f');
                    $this->db->update('tb_empresa_associacoes_grupo');
                }

                if (!empty($_POST["associados_grupos"]) and is_array($_POST["associados_grupos"])) {
                    $arrayteste = $_POST["associados_grupos"];
//
                    for ($i = 0; $i < count($_POST["associados_grupos"]); $i++) {
                        $this->db->where('empresa_associacoes_grupo_id', $_POST["associados_grupos"][$i]);
                        $this->db->set('ativo', 't');
                        $this->db->where('empresa_id', $empresa_id);
                        $this->db->update('tb_empresa_associacoes_grupo');
                    }
                }

                $array = $_POST['opc_telatendimento_assoc'];
                for ($i = 0; $i < count($array); $i++) {
                    $this->db->set('ambulatorio_grupo_id', $_POST['opc_grupos']);
                    $this->db->set('tela_atendimento', $array[$i]);
                    $this->db->set('empresa_id', $empresa_id);
                    $this->db->set('ativo', 't');
                    $this->db->insert('tb_empresa_associacoes_grupo');
                }
            }
            return $empresa_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    private function instanciar($empresa_id) {

        if ($empresa_id != 0) {
            $this->db->select('f.empresa_id, 
                               f.nome,
                               razao_social,
                               cnpj,
                               celular,
                               telefone,
                               email,
                               cep,
                               logradouro,
                               numero,
                               bairro,
                               cnes,
                               f.municipio_id,
                               c.nome as municipio,
                               c.estado,
                               c.codigo_ibge,
                               cep,
                               consulta,
                               internacao,
                               centrocirurgico,
                               especialidade,
                               geral,
                               faturamento,
                               estoque,
                               chamar_consulta,
                               procedimento_multiempresa,
                               financeiro,
                               data_contaspagar,
                               medico_laudodigitador,
                               laboratorio,
                               ep.laboratorio_sc,
                               ponto,
                               marketing,
                               imagem,
                               odontologia,
                               impressao_tipo,
                               impressao_laudo,
                               impressao_recibo,
                               impressao_declaracao,
                               cabecalho_config,
                               rodape_config,
                               laudo_config,
                               recibo_config,
                               ficha_config,
                               declaracao_config,
                               atestado_config,
                               oftamologia,
                               farmacia,
                               caixa,
                               cancelar_sala_espera,
                               promotor_medico,
                               calendario,
                               login_paciente,
                               servicosms,
                               orcamento_config,
                               impressao_internacao,
                               credito,
                               valor_recibo_guia,
                               impressao_orcamento,
                               odontologia_valor_alterar,
                               selecionar_retorno,
                               administrador_cancelar,
                               servicoemail,
                               endereco_toten,
                               endereco_externo,
                               excluir_transferencia,
                               chat,
                               procedimento_excecao,
                               endereco_externo_base,
                               valor_consulta_app,
                               client_id,
                               client_secret,
                               client_sandbox,
                               ordem_chegada,
                               f.horario_sab,
                               f.horario_seg_sex,
                               ep.valor_autorizar,
                               ep.gerente_contasapagar,
                               ep.cpf_obrigatorio,
                               ep.orcamento_recepcao,
                               ep.bloquear_botao,
                               ep.liberar_perfil,
                               ep.relatorio_ordem,
                               ep.relatorio_producao,
                               ep.relatorios_recepcao,
                               ep.financeiro_cadastro,
                               ep.ocupacao_mae,
                               ep.ocupacao_pai,
                               botao_faturar_guia,
                               botao_faturar_procedimento,
                               producao_medica_saida,
                               ep.procedimento_excecao,
                               ep.calendario_layout,
                               ep.botao_ativar_sala,
                               ep.retirar_botao_ficha,
                               ep.encaminhamento_email,
                               ep.desativar_personalizacao_impressao,
                               ep.recomendacao_configuravel,
                               f.mostrar_logo_clinica,
                               ep.recomendacao_obrigatorio,
                               ep.caixa_personalizado,
                               ep.carregar_modelo_receituario,
                               ep.desabilitar_trava_retorno,
                               ep.associa_credito_procedimento,
                               ep.valor_convenio_nao,
                               ep.conjuge,
                               ep.subgrupo,
                               ep.laudo_sigiloso,
                               f.numero_empresa_painel,
                               ep.campos_obrigatorios_pac_cpf,
                               ep.valor_laboratorio,
                               ep.profissional_completo,
                               ep.tecnica_promotor,
                               ep.tecnica_enviar,
                               ep.campos_obrigatorios_pac_sexo,
                               ep.campos_obrigatorios_pac_nascimento,
                               ep.campos_obrigatorios_pac_telefone,
                               ep.campos_obrigatorios_pac_municipio,
                               ep.repetir_horarios_agenda,
                               ep.desativar_taxa_administracao,
                               ep.producao_alternativo,
                               ep.modelo_laudo_medico,
                               ep.subgrupo_procedimento,
                               ep.senha_finalizar_laudo,
                               ep.retirar_flag_solicitante,
                               ep.campos_cadastro,
                               ep.orcamento_multiplo,
                               ep.campos_atendimentomed,
                               ep.botoes_app,
                               ep.campos_listaatendimentomed,
                               ep.dados_atendimentomed,
                               ep.cadastrar_painel_sala,
                               ep.apenas_procedimentos_multiplos,
                               ep.orcamento_cadastro,
                               ep.gerente_cancelar,
                               ep.gerente_relatorio_financeiro,
                               ep.botao_arquivos_paciente,
                               ep.botao_imagem_paciente,
                               ep.gerente_cancelar_sala,
                               ep.autorizar_sala_espera,
                               f.endereco_upload,
                               f.horario_seg_sex_inicio,
                               f.horario_seg_sex_fim,
                               f.horario_sab_inicio,
                               f.horario_sab_fim,
                               f.endereco_integracao_lab,
                               f.identificador_lis,
                               f.origem_lis,
                               f.link_certificado,
                               f.site_empresa,
                               f.convenio_padrao_id,
                               ep.percentual_multiplo,
                               ep.botao_laudo_paciente,
                               ep.ajuste_pagamento_procedimento,
                               ep.retirar_preco_procedimento,
                               ep.relatorios_clinica_med,
                               ep.reservar_escolher_proc,
                               ep.impressao_cimetra,
                               ep.gerente_recepcao_top_saude,
                               ep.manter_indicacao,
                               ep.fila_impressao,
                               ep.medico_solicitante,
                               ep.uso_salas,
                               ep.relatorio_operadora,
                               ep.profissional_externo,
                               ep.profissional_agendar,
                               ep.relatorio_demandagrupo,
                               ep.relatorio_rm,
                               ep.relatorio_caixa,
                               ep.enfermagem,
                               ep.integracaosollis,
                               ep.medicinadotrabalho,
                               ep.limitar_acesso,
                               ep.faturamento_novo,
                               ep.manternota,
                               ep.grupo_convenio_proc,
                               ep.pagar_todos,
                               ep.agenda_modelo2,
                               ep.relatorio_dupla,
                               ep.perfil_marketing_p,
                               ep.ordenacao_situacao,
                               ep.financ_4n,
                               ep.atendimento_medico,
                               ep.endereco_ficha,
                               ep.filtrar_agenda,
                               ep.convenio_paciente,
                               ep.botao_ficha_convenio,
                               ep.faturar_parcial,
                               ep.medico_estoque,
                               ep.filtro_exame_cadastro,
                               ep.editar_historico_antigo,
                               ep.informacao_adicional,
                               ep.carteira_administrador,
                               ep.caixa_grupo,
                               ep.guia_procedimento,
                               ep.aparecer_orcamento,
                               ep.prontuario_antigo,
                               ep.fidelidade_paciente_antigo,    
                               ep.prontuario_antigo,
                               ep.historico_antigo_administrador,
                               ep.hora_agendamento,
                               ep.imprimir_medico,
                               ep.retirar_ordem_medico,
                               ep.convenio_padrao,
                               ep.agenda_atend,
                               ep.cirugico_manual,
                               ep.inadimplencia,
                               ep.agenda_modificada,
                               f.servicowhatsapp,
                               f.cnae,
                               f.item_lista,
                               ep.tecnico_recepcao_editar,
                               ep.finalizar_atendimento_medico,
                               ep.sem_margens_laudo,
                               ep.relatorio_caixa_antigo,
                               f.aliquota,
                               f.inscri_municipal,
                               f.cnpjxml,
                               ep.gerente_cancelar_atendimento,
                               ep.enviar_para_atendimento,
                               ep.tecnico_acesso_acesso,
                               ep.nao_sobrepor_laudo,
                               ep.tabela_bpa,
                               ep.pesquisar_responsavel,
                               ep.alterar_data_emissao,
                               ep.agendahias,
                               ep.whatsapp,
                               ep.travar_convenio_paciente,
                               ep.prontuario_antigo_pesquisar,
                               ep.desativarelatend,
                               ep.empresas_unicas,
                               ep.precadastro,
                               ep.solicitar_sabin,
                               ep.corretor_ortografico,
                               ep.nguia_autorizacao,
                               ep.agenda_albert,
                               ep.data_pesquisa_financeiro,
                               ep.tarefa_medico,
                               ep.espera_intercalada,
                               ep.id_linha_financeiro,
                               ep.valores_recepcao,
                               ep.remove_margem_cabecalho_rodape,
                               ep.a4_receituario_especial,
                               ep.laudo_status_f,
                               ep.solicitacaotempo,
                               ep.historico_completo,
                               ep.origem_agendamento,
                               ep.manter_gastos,
                               ep.filaaparelho,
                               ep.setores,
                               ep.certificado_digital,
                               ep.certificado_digital_manual,
                               ep.dashboard_administrativo,
                               ep.integrar_google,
                               ep.entrega_laudos,
                               ep.bardeira_status,
                               ep.diagnostico_medico,
                               ep.impressoes_acompanhamento,
                               f.endereco_upload_pasta,
                               f.endereco_upload_pasta_paciente,
                               ep.producao_por_valor,
                               ep.modelos_atendimento,
                               ep.abas_atendimento,
                               ep.atendimento_medico_3,
                               ep.cancelar_sala_espera,
                               ep.atender_todos,
                               ep.filtrar_agenda_2,
                               ep.informar_faltas,
                               f.horario_para_informar_faltas,
                               f.qtdefaltas_pacientes,
                               ep.data_hora_sala_espera,
                               ep.email_obrigatorio,
                               ep.nota_fiscal_sp,
                               ep.status_faltou_manual,
                               ep.btn_encaixe,
                               ep.link_sistema_paciente,
                               f.facebook_empresa,
                               f.instagram_empresa,
                               ep.modificar_btn_multifuncao,
                               ep.redutor_valor_liquido
                               ');
            $this->db->from('tb_empresa f');
            $this->db->join('tb_municipio c', 'c.municipio_id = f.municipio_id', 'left');
            $this->db->join('tb_empresa_permissoes ep', 'ep.empresa_id = f.empresa_id', 'left');
            $this->db->where("f.empresa_id", $empresa_id);
            $query = $this->db->get();
            $return = $query->result();
            $this->_empresa_id = $empresa_id;
            $this->_nome = $return[0]->nome;
            $this->_cnpj = $return[0]->cnpj;
            $this->_razao_social = $return[0]->razao_social;
            $this->_cancelar_sala_espera = $return[0]->cancelar_sala_espera;
            $this->_celular = $return[0]->celular;
            $this->_farmacia = $return[0]->farmacia;
            $this->_telefone = $return[0]->telefone;
            $this->_historico_antigo_administrador = $return[0]->historico_antigo_administrador;
            $this->_orcamento_multiplo = $return[0]->orcamento_multiplo;
            $this->_profissional_agendar = $return[0]->profissional_agendar;
            $this->_profissional_externo = $return[0]->profissional_externo;
            $this->_fidelidade_paciente_antigo = $return[0]->fidelidade_paciente_antigo;
            $this->_grupo_convenio_proc = $return[0]->grupo_convenio_proc;
            $this->_pagar_todos = $return[0]->pagar_todos;
            $this->_campos_listaatendimentomed = $return[0]->campos_listaatendimentomed;
            $this->_solicitar_sabin = $return[0]->solicitar_sabin;
            $this->_agenda_albert = $return[0]->agenda_albert;
            $this->_valor_consulta_app = $return[0]->valor_consulta_app;
            $this->_valores_recepcao = $return[0]->valores_recepcao;
            $this->_client_id = $return[0]->client_id;
            $this->_client_secret = $return[0]->client_secret;
            $this->_client_sandbox = $return[0]->client_sandbox;
            $this->_data_pesquisa_financeiro = $return[0]->data_pesquisa_financeiro;
            $this->_convenio_padrao = $return[0]->convenio_padrao;
            $this->_convenio_padrao_id = $return[0]->convenio_padrao_id;
            $this->_relatorio_caixa_antigo = $return[0]->relatorio_caixa_antigo;
            $this->_faturamento_novo = $return[0]->faturamento_novo;
            $this->_convenio_paciente = $return[0]->convenio_paciente;
            $this->_faturar_parcial = $return[0]->faturar_parcial;
            $this->_caixa_grupo = $return[0]->caixa_grupo;
            $this->_corretor_ortografico = $return[0]->corretor_ortografico;
            $this->_nguia_autorizacao = $return[0]->nguia_autorizacao;
            $this->_guia_procedimento = $return[0]->guia_procedimento;
            $this->_email = $return[0]->email;
            $this->_inadimplencia = $return[0]->inadimplencia;
            $this->_travar_convenio_paciente = $return[0]->travar_convenio_paciente;
            $this->_prontuario_antigo_pesquisar = $return[0]->prontuario_antigo_pesquisar;
            $this->_cep = $return[0]->cep;
            $this->_retirar_ordem_medico = $return[0]->retirar_ordem_medico;
            $this->_filtro_exame_cadastro = $return[0]->filtro_exame_cadastro;
            $this->_imprimir_medico = $return[0]->imprimir_medico;
            $this->_agenda_modelo2 = $return[0]->agenda_modelo2;
            $this->_relatorio_dupla = $return[0]->relatorio_dupla;
            $this->_endereco_integracao_lab = $return[0]->endereco_integracao_lab;
            $this->_identificador_lis = $return[0]->identificador_lis;
            $this->_informacao_adicional = $return[0]->informacao_adicional;
            $this->_carteira_administrador = $return[0]->carteira_administrador;
            $this->_origem_lis = $return[0]->origem_lis;
            $this->_link_certificado = $return[0]->link_certificado;
            $this->_pesquisar_responsavel = $return[0]->pesquisar_responsavel;
            $this->_site_empresa = $return[0]->site_empresa;
            $this->_subgrupo = $return[0]->subgrupo;
            $this->_ordenacao_situacao = $return[0]->ordenacao_situacao;
            $this->_financ_4n = $return[0]->financ_4n;
            $this->_liberar_perfil = $return[0]->liberar_perfil;
            $this->_bloquear_botao = $return[0]->bloquear_botao;
            $this->_endereco_ficha = $return[0]->endereco_ficha;
            $this->_medico_estoque = $return[0]->medico_estoque;
            $this->_atendimento_medico = $return[0]->atendimento_medico;
            $this->_botao_imagem_paciente = $return[0]->botao_imagem_paciente;
            $this->_tecnico_recepcao_editar = $return[0]->tecnico_recepcao_editar;
            $this->_finalizar_atendimento_medico = $return[0]->finalizar_atendimento_medico;
            $this->_sem_margens_laudo = $return[0]->sem_margens_laudo;
            $this->_reservar_escolher_proc = $return[0]->reservar_escolher_proc;
            $this->_botao_arquivos_paciente = $return[0]->botao_arquivos_paciente;
            $this->_gerente_relatorio_financeiro = $return[0]->gerente_relatorio_financeiro;
            $this->_laudo_sigiloso = $return[0]->laudo_sigiloso;
            $this->_whatsapp = $return[0]->whatsapp;
            $this->_gerente_cancelar = $return[0]->gerente_cancelar;
            $this->_impressao_internacao = $return[0]->impressao_internacao;
            $this->_editar_historico_antigo = $return[0]->editar_historico_antigo;
            $this->_campos_cadastro = $return[0]->campos_cadastro;
            $this->_gerente_cancelar_sala = $return[0]->gerente_cancelar_sala;
            $this->_campos_atendimentomed = $return[0]->campos_atendimentomed;
            $this->_botoes_app = $return[0]->botoes_app;
            $this->_dados_atendimentomed = $return[0]->dados_atendimentomed;
            $this->_modelo_laudo_medico = $return[0]->modelo_laudo_medico;
            $this->_orcamento_cadastro = $return[0]->orcamento_cadastro;
            $this->_endereco_upload = $return[0]->endereco_upload;
            $this->_conjuge = $return[0]->conjuge;
            $this->_horario_seg_sex = $return[0]->horario_seg_sex;
            $this->_horario_sab = $return[0]->horario_sab;
            $this->_logradouro = $return[0]->logradouro;
            $this->_numero = $return[0]->numero;
            $this->_producao_alternativo = $return[0]->producao_alternativo;
            $this->_autorizar_sala_espera = $return[0]->autorizar_sala_espera;
            $this->_bairro = $return[0]->bairro;
            $this->_municipio_id = $return[0]->municipio_id;
            $this->_caixa = $return[0]->caixa;
            $this->_gerente_recepcao_top_saude = $return[0]->gerente_recepcao_top_saude;
            $this->_valor_convenio_nao = $return[0]->valor_convenio_nao;
            $this->_desativar_taxa_administracao = $return[0]->desativar_taxa_administracao;
            $this->_promotor_medico = $return[0]->promotor_medico;
            $this->_municipio = $return[0]->municipio;
            $this->_codigo_ibge = $return[0]->codigo_ibge;
            $this->_encaminhamento_email = $return[0]->encaminhamento_email;
            $this->_nome = $return[0]->nome;
            $this->_orcamento_config = $return[0]->orcamento_config;
            $this->_odontologia_valor_alterar = $return[0]->odontologia_valor_alterar;
            $this->_selecionar_retorno = $return[0]->selecionar_retorno;
            $this->_impressao_orcamento = $return[0]->impressao_orcamento;
            $this->_administrador_cancelar = $return[0]->administrador_cancelar;
            $this->_profissional_completo = $return[0]->profissional_completo;
            $this->_tecnica_promotor = $return[0]->tecnica_promotor;
            $this->_tecnica_enviar = $return[0]->tecnica_enviar;
            $this->_endereco_toten = $return[0]->endereco_toten;
            $this->_estado = $return[0]->estado;
            $this->_cep = $return[0]->cep;
            $this->_chat = $return[0]->chat;
            $this->_valor_laboratorio = $return[0]->valor_laboratorio;
            $this->_servicoemail = $return[0]->servicoemail;
            $this->_servicosms = $return[0]->servicosms;
            $this->_cnes = $return[0]->cnes;
            $this->_internacao = $return[0]->internacao;
            $this->_centro_cirurgico = $return[0]->centrocirurgico;
            $this->_consulta = $return[0]->consulta;
            $this->_especialidade = $return[0]->especialidade;
            $this->_odontologia = $return[0]->odontologia;
            $this->_geral = $return[0]->geral;
            $this->_faturamento = $return[0]->faturamento;
            $this->_id_linha_financeiro = $return[0]->id_linha_financeiro;
            $this->_estoque = $return[0]->estoque;
            $this->_financeiro = $return[0]->financeiro;
            $this->_marketing = $return[0]->marketing;
            $this->_excluir_transferencia = $return[0]->excluir_transferencia;
            $this->_imagem = $return[0]->imagem;
            $this->_laboratorio = $return[0]->laboratorio;
            $this->_laboratorio_sc = $return[0]->laboratorio_sc;
            $this->_ponto = $return[0]->ponto;
            $this->_impressao_tipo = $return[0]->impressao_tipo;
            $this->_impressao_laudo = $return[0]->impressao_laudo;
            $this->_impressao_declaracao = $return[0]->impressao_declaracao;
            $this->_impressao_recibo = $return[0]->impressao_recibo;
            $this->_calendario = $return[0]->calendario;
            $this->_botao_faturar_guia = $return[0]->botao_faturar_guia;
            $this->_data_contaspagar = $return[0]->data_contaspagar;
            $this->_login_paciente = $return[0]->login_paciente;
            $this->_endereco_externo_base = $return[0]->endereco_externo_base;
            $this->_endereco_externo = $return[0]->endereco_externo;
            $this->_medico_laudodigitador = $return[0]->medico_laudodigitador;
            $this->_botao_faturar_proc = $return[0]->botao_faturar_procedimento;
            $this->_chamar_consulta = $return[0]->chamar_consulta;
            $this->_procedimento_multiempresa = $return[0]->procedimento_multiempresa;
            $this->_cabecalho_config = $return[0]->cabecalho_config;
            $this->_rodape_config = $return[0]->rodape_config;
            $this->_laudo_config = $return[0]->laudo_config;
            $this->_recibo_config = $return[0]->recibo_config;
            $this->_ficha_config = $return[0]->ficha_config;
            $this->_declaracao_config = $return[0]->declaracao_config;
            $this->_atestado_config = $return[0]->atestado_config;
            $this->_producao_medica_saida = $return[0]->producao_medica_saida;
            $this->_procedimento_excecao = $return[0]->procedimento_excecao;
            $this->_ordem_chegada = $return[0]->ordem_chegada;
            $this->_calendario_layout = $return[0]->calendario_layout;
            $this->_recomendacao_configuravel = $return[0]->recomendacao_configuravel;
            $this->_credito = $return[0]->credito;
            $this->_valor_recibo_guia = $return[0]->valor_recibo_guia;
            $this->_recomendacao_obrigatorio = $return[0]->recomendacao_obrigatorio;
            $this->_botao_ativar_sala = $return[0]->botao_ativar_sala;
            $this->_oftamologia = $return[0]->oftamologia;
            $this->_valor_autorizar = $return[0]->valor_autorizar;
            $this->_gerente_contasapagar = $return[0]->gerente_contasapagar;
            $this->_cpf_obrigatorio = $return[0]->cpf_obrigatorio;
            $this->_orcamento_recepcao = $return[0]->orcamento_recepcao;
            $this->_relatorio_ordem = $return[0]->relatorio_ordem;
            $this->_relatorio_producao = $return[0]->relatorio_producao;
            $this->_relatorios_recepcao = $return[0]->relatorios_recepcao;
            $this->_financeiro_cadastro = $return[0]->financeiro_cadastro;
            $this->_retirar_botao_ficha = $return[0]->retirar_botao_ficha;
            $this->_desativar_personalizacao_impressao = $return[0]->desativar_personalizacao_impressao;
            $this->_mostrar_logo_clinica = $return[0]->mostrar_logo_clinica;
            $this->_carregar_modelo_receituario = $return[0]->carregar_modelo_receituario;
            $this->_caixa_personalizado = $return[0]->caixa_personalizado;
            $this->_desabilitar_trava_retorno = $return[0]->desabilitar_trava_retorno;
            $this->_numero_empresa_painel = $return[0]->numero_empresa_painel;
            $this->_associa_credito_procedimento = $return[0]->associa_credito_procedimento;
            $this->_campos_obrigatorios_pac_municipio = $return[0]->campos_obrigatorios_pac_municipio;
            $this->_campos_obrigatorios_pac_telefone = $return[0]->campos_obrigatorios_pac_telefone;
            $this->_campos_obrigatorios_pac_nascimento = $return[0]->campos_obrigatorios_pac_nascimento;
            $this->_campos_obrigatorios_pac_sexo = $return[0]->campos_obrigatorios_pac_sexo;
            $this->_campos_obrigatorios_pac_cpf = $return[0]->campos_obrigatorios_pac_cpf;
            $this->_repetir_horarios_agenda = $return[0]->repetir_horarios_agenda;
            $this->_subgrupo_procedimento = $return[0]->subgrupo_procedimento;
            $this->_senha_finalizar_laudo = $return[0]->senha_finalizar_laudo;
            $this->_retirar_flag_solicitante = $return[0]->retirar_flag_solicitante;
            $this->_cadastrar_painel_sala = $return[0]->cadastrar_painel_sala;
            $this->_apenas_procedimentos_multiplos = $return[0]->apenas_procedimentos_multiplos;
            $this->_horario_seg_sex_inicio = $return[0]->horario_seg_sex_inicio;
            $this->_horario_seg_sex_fim = $return[0]->horario_seg_sex_fim;
            $this->_horario_sab_inicio = $return[0]->horario_sab_inicio;
            $this->_horario_sab_fim = $return[0]->horario_sab_fim;
            $this->_percentual_multiplo = $return[0]->percentual_multiplo;
            $this->_precadastro = $return[0]->precadastro;
            $this->_botao_laudo_paciente = $return[0]->botao_laudo_paciente;
            $this->_ajuste_pagamento_procedimento = $return[0]->ajuste_pagamento_procedimento;
            $this->_retirar_preco_procedimento = $return[0]->retirar_preco_procedimento;
            $this->_relatorios_clinica_med = $return[0]->relatorios_clinica_med;
            $this->_botao_ficha_convenio = $return[0]->botao_ficha_convenio;
            $this->_impressao_cimetra = $return[0]->impressao_cimetra;
            $this->_manter_indicacao = $return[0]->manter_indicacao;
            $this->_fila_impressao = $return[0]->fila_impressao;
            $this->_medico_solicitante = $return[0]->medico_solicitante;
            $this->_uso_salas = $return[0]->uso_salas;
            $this->_relatorio_operadora = $return[0]->relatorio_operadora;
            $this->_relatorio_demandagrupo = $return[0]->relatorio_demandagrupo;
            $this->_relatorio_rm = $return[0]->relatorio_rm;
            $this->_relatorio_caixa = $return[0]->relatorio_caixa;
            $this->_enfermagem = $return[0]->enfermagem;
            $this->_integracaosollis = $return[0]->integracaosollis;
            $this->_medicinadotrabalho = $return[0]->medicinadotrabalho;
            $this->_ocupacao_mae = $return[0]->ocupacao_mae;
            $this->_ocupacao_pai = $return[0]->ocupacao_pai;
            $this->_manternota = $return[0]->manternota;
            $this->_limitar_acesso = $return[0]->limitar_acesso;
            $this->_perfil_marketing_p = $return[0]->perfil_marketing_p;
            $this->_filtrar_agenda = $return[0]->filtrar_agenda;
            $this->_aparecer_orcamento = $return[0]->aparecer_orcamento;
            $this->_prontuario_antigo = $return[0]->prontuario_antigo;
            $this->_hora_agendamento = $return[0]->hora_agendamento;
            $this->_agenda_atend = $return[0]->agenda_atend;
            $this->_cirugico_manual = $return[0]->cirugico_manual;
            $this->_agenda_modificada = $return[0]->agenda_modificada;
            $this->_servicowhatsapp = $return[0]->servicowhatsapp;
            $this->_cnae = $return[0]->cnae;
            $this->_item_lista = $return[0]->item_lista;
            $this->_aliquota = $return[0]->aliquota;
            $this->_inscri_municipal = $return[0]->inscri_municipal;
            $this->_cnpjxml = $return[0]->cnpjxml;
            $this->_gerente_cancelar_atendimento = $return[0]->gerente_cancelar_atendimento;
            $this->_enviar_para_atendimento = $return[0]->enviar_para_atendimento;
            $this->_tecnico_acesso_acesso = $return[0]->tecnico_acesso_acesso;
            $this->_nao_sobrepor_laudo = $return[0]->nao_sobrepor_laudo;
            $this->_tabela_bpa = $return[0]->tabela_bpa;
            $this->_alterar_data_emissao = $return[0]->alterar_data_emissao;
            $this->_agendahias = $return[0]->agendahias;
            $this->_desativarelatend = $return[0]->desativarelatend;
            $this->_empresas_unicas = $return[0]->empresas_unicas;
            $this->_tarefa_medico = $return[0]->tarefa_medico;
            $this->_espera_intercalada = $return[0]->espera_intercalada;
            $this->_remove_margem_cabecalho_rodape = $return[0]->remove_margem_cabecalho_rodape;
            $this->_a4_receituario_especial = $return[0]->a4_receituario_especial;
            $this->_laudo_status_f = $return[0]->laudo_status_f;
            $this->_solicitacaotempo = $return[0]->solicitacaotempo;
            $this->_historico_completo = $return[0]->historico_completo;
            $this->_origem_agendamento = $return[0]->origem_agendamento;
            $this->_manter_gastos = $return[0]->manter_gastos;
            $this->_filaaparelho = $return[0]->filaaparelho;
            $this->_setores = $return[0]->setores;
            $this->_certificado_digital = $return[0]->certificado_digital;
            $this->_certificado_digital_manual = $return[0]->certificado_digital_manual;
            $this->_dashboard_administrativo = $return[0]->dashboard_administrativo;
            $this->_integrar_google = $return[0]->integrar_google;
            $this->_entrega_laudos = $return[0]->entrega_laudos;
            $this->_bardeira_status = $return[0]->bardeira_status;
            $this->_diagnostico_medico = $return[0]->diagnostico_medico;
            $this->_endereco_upload_pasta = $return[0]->endereco_upload_pasta;
            $this->_endereco_upload_pasta_paciente = $return[0]->endereco_upload_pasta_paciente;
            $this->_impressoes_acompanhamento = $return[0]->impressoes_acompanhamento;
            $this->_producao_por_valor = $return[0]->producao_por_valor;
            $this->_modelos_atendimento = $return[0]->modelos_atendimento;
            $this->_abas_atendimento = $return[0]->abas_atendimento;
            $this->_atendimento_medico_3 = $return[0]->atendimento_medico_3;
            $this->_atender_todos = $return[0]->atender_todos;
            $this->_filtrar_agenda_2 = $return[0]->filtrar_agenda_2;
            $this->_informar_faltas = $return[0]->informar_faltas;
            $this->_data_hora_sala_espera = $return[0]->data_hora_sala_espera;
            $this->_email_obrigatorio = $return[0]->email_obrigatorio;
            $this->_nota_fiscal_sp = $return[0]->nota_fiscal_sp;
            $this->_status_faltou_manual = $return[0]->status_faltou_manual;
            $this->_horario_para_informar_faltas = $return[0]->horario_para_informar_faltas;
            $this->_qtdefaltas_pacientes = $return[0]->qtdefaltas_pacientes;
            $this->_btn_encaixe = $return[0]->btn_encaixe;
            $this->_link_sistema_paciente = $return[0]->link_sistema_paciente; 
            $this->_facebook_empresa = $return[0]->facebook_empresa;
            $this->_instagram_empresa = $return[0]->instagram_empresa;
            $this->_modificar_btn_multifuncao = $return[0]->modificar_btn_multifuncao; 
            $this->_redutor_valor_liquido = $return[0]->redutor_valor_liquido; 
        } else {
            $this->_empresa_id = null;
        }
        
    }

    function listarverificacaopreco($agenda_exames_id = NULL) {


        $this->db->select('
                                        aef.guia_id,
                                        sum(aef.valor_total) as valor_total,
                                        array_agg(aef.valor_total),
                                        (sum(aef.valor_total) - (select sum(valor_bruto) + sum(desconto)  as valorTotPag from ponto.tb_agenda_exames_faturar aef2
                                        where aef2.guia_id = aef.guia_id and ativo = true)) as valor_restante,
                                        sum(aef.valor) as valor,
                                        sum(valor_bruto) + sum(desconto) as valor_total_pago,

                                        sum(aef.desconto) as desconto', false);
        $this->db->from('tb_agenda_exames_faturar aef');
        $this->db->join('tb_forma_pagamento fp', 'fp.forma_pagamento_id = aef.forma_pagamento_id', 'left');
        $this->db->where('aef.agenda_exames_id', $agenda_exames_id);
        $this->db->where('aef.ativo', 't');
        $this->db->groupby('
                                        aef.guia_id,
                                        ');


        $return = $this->db->get();
        $retorno = $return->result();



        return $retorno;
    }

    function listarverificacaopermisao($empresa_id = NULL) {

        $this->db->select('ep.*');
        $this->db->from('tb_empresa ep');
        $this->db->where('ep.empresa_id', $empresa_id);
        $retorno_header = $this->db->get()->result();
        return $retorno_header;
    }

    function listarverificacaopermisao2($empresa_id = NULL) {

        $this->db->select('ep.*');
        $this->db->from('tb_empresa_permissoes ep');
        $this->db->where('ep.empresa_id', $empresa_id);
        $retorno_header2 = $this->db->get()->result();
        return $retorno_header2;
    }

    function listargrupos() {
        $sql = $this->db->select('');
        $this->db->from('tb_ambulatorio_grupo');

        return $sql->get()->result();
    }

    function listarassociados($exame_empresa_id = NULL) {



        $sql = $this->db->select('ea.*,ag.nome');
        $this->db->from('tb_empresa_associacoes_grupo as ea');
        $this->db->join('tb_ambulatorio_grupo as ag', 'ag.ambulatorio_grupo_id = ea.ambulatorio_grupo_id');
        $this->db->where('ea.empresa_id', $exame_empresa_id);
        $this->db->where('ea.ativo', 't');
        return $sql->get()->result();
    }

    function listarinformacaowhatsapp($empresa_id) {
//        $empresa_id = $this->session->userdata('empresa_id'); 

        $this->db->select('');
        $this->db->from('tb_empresa_whatsapp');
        $this->db->where('empresa_id', $empresa_id);
        $this->db->where('ativo', 't');
        $this->db->limit(1);
        $return = $this->db->get();
        return $return->result();
    }

    function listardadosempresa($empresa_id = NULL) {
        $this->db->select('cnpj');
        $this->db->from('tb_empresa');
        $this->db->where('empresa_id', $empresa_id);
        return $this->db->get()->result();
    }

    function gravarconfiguracaowhatsapp() {
        try {

            $this->db->select('pacote');
            $this->db->from('tb_empresa_whatsapp');
            $this->db->where('empresa_id', $this->session->userdata('empresa_id'));
            $ver_pacote = $this->db->get()->result();
            if (@$_POST['num_pacote'] != $ver_pacote[0]->pacote) {
                $this->db->set('pacote', @$_POST['num_pacote']);
                $this->db->set('contador', 0);
            } else {
                
            }

            $this->db->set('cnpj', @$_POST['numero_identificacao_whatsapp']);
            $this->db->set('mensagem', @$_POST['mensagem']);
            $this->db->set('empresa_id', @$_POST['empresa_id']);
            $this->db->set('endereco_externo', @$_POST['endereco_externo']);
            $this->db->set('endereco_clinica', @$_POST['endereco_clinica']);
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            if ($_POST['empresa_whatsapp_id'] == "") {// insert
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_empresa_whatsapp');
            } else { // update         
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $empresa_whatsapp_id = $_POST['empresa_whatsapp_id'];
                $this->db->where('empresa_whatsapp_id', @$empresa_whatsapp_id);
                $this->db->update('tb_empresa_whatsapp');
            }
            return $empresa_id;
        } catch (Exception $exc) {
            return -1;
        }
    }
    
     function listarconfiguracaoimpressaoreceituario() {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ei.empresa_impressao_cabecalho_receituario_id,ei.cabecalho,ei.rodape, e.nome as empresa');
        $this->db->from('tb_empresa_impressao_cabecalho_receituario ei');
        $this->db->join('tb_empresa e', 'e.empresa_id = ei.empresa_id', 'left');
        $this->db->where('ei.empresa_id', $empresa_id); 
        return $this->db;
    }
    
    function gravarconfiguracaoimpressaoreceituario() {
           try {
   //            var_dump($_POST['impressao_id']); die;
               /* inicia o mapeamento no banco */
               $empresa_id = $this->session->userdata('empresa_id');
               $horario = date("Y-m-d H:i:s");
               $operador_id = $this->session->userdata('operador_id');

               $this->db->select('ei.empresa_impressao_cabecalho_receituario_id,');
               $this->db->from('tb_empresa_impressao_cabecalho_receituario ei');
               $this->db->where('ei.empresa_id', $empresa_id);
               $teste = $this->db->get()->result();
               if (count($teste) > 0) {
                   $impressao_id = $teste[0]->empresa_impressao_cabecalho_receituario_id;
               }

               if (count($teste) == 0) {
                   $this->db->set('cabecalho', $_POST['cabecalho']);
                   $this->db->set('rodape', $_POST['rodape']);
                   $this->db->set('timbrado', $_POST['timbrado']);
                   $this->db->set('empresa_id', $empresa_id);
                   $this->db->set('data_cadastro', $horario);
                   $this->db->set('operador_cadastro', $operador_id);
                   $this->db->insert('tb_empresa_impressao_cabecalho_receituario');
               } else {
                   $this->db->set('cabecalho', $_POST['cabecalho']);
                   $this->db->set('rodape', $_POST['rodape']);
                   $this->db->set('timbrado', $_POST['timbrado']);
                   $this->db->set('empresa_id', $empresa_id);
                   $this->db->set('data_atualizacao', $horario);
                   $this->db->set('operador_atualizacao', $operador_id);
                   $this->db->where('empresa_impressao_cabecalho_receituario_id', $impressao_id);
                   if (isset($_POST['receituario_especial'])) {
                      $this->db->set('receituario_especial', 't'); 
                   }else{
                      $this->db->set('receituario_especial', 'f'); 
                   }
                   $this->db->update('tb_empresa_impressao_cabecalho_receituario');
               }

               $erro = $this->db->_error_message();
               if (trim($erro) != "") // erro de banco
                   return -1;
               else
   //                $ambulatorio_guia_id = $this->db->insert_id();
                   return true;
           } catch (Exception $exc) {
               return false;
           }
       }
    
    
    function listarconfiguracaoimpressaocabecalhoreceituario($empresa_impressao_cabecalho_receituario_id) {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ei.empresa_impressao_cabecalho_receituario_id,ei.cabecalho,ei.rodape,ei.timbrado, e.nome as empresa,ei.receituario_especial');
        $this->db->from('tb_empresa_impressao_cabecalho_receituario ei');
        $this->db->join('tb_empresa e', 'e.empresa_id = ei.empresa_id', 'left');
        $this->db->where('ei.empresa_impressao_cabecalho_receituario_id', $empresa_impressao_cabecalho_receituario_id); 
        $return = $this->db->get();
        return $return->result();
   }
       
    function listarconfiguracaoimpressaolaudoreceituario() {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ei.empresa_impressao_laudo_receituario_id,ei.nome as nome_laudo, ei.cabecalho,ei.ativo,ei.rodape, e.nome as empresa');
        $this->db->from('tb_empresa_impressao_laudo_receituario ei');
        $this->db->join('tb_empresa e', 'e.empresa_id = ei.empresa_id', 'left');
        $this->db->where('ei.empresa_id', $empresa_id); 
        return $this->db;
    }
       
     function listarconfiguracaoimpressaolaudoformreceituario($empresa_impressao_cabecalho_receituario_id) {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ei.empresa_impressao_laudo_receituario_id, ei.nome as nome_laudo,ei.texto,ei.adicional_cabecalho, ei.cabecalho,ei.rodape, e.nome as empresa');
        $this->db->from('tb_empresa_impressao_laudo_receituario ei');
        $this->db->join('tb_empresa e', 'e.empresa_id = ei.empresa_id', 'left');
        $this->db->where('ei.empresa_impressao_laudo_receituario_id', $empresa_impressao_cabecalho_receituario_id);
 
        $return = $this->db->get();
        return $return->result();
    }
    
    
     function gravarconfiguracaoimpressaolaudoreceituario() {
        try {
//            var_dump($_POST['impressao_id']); die;
            /* inicia o mapeamento no banco */ 
            if ($_POST['impressao_id'] == "") {
                $_POST['impressao_id'] = 0;
            }
            $empresa_id = $this->session->userdata('empresa_id');
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            $this->db->select('ei.empresa_impressao_laudo_receituario_id,');
            $this->db->from('tb_empresa_impressao_laudo_receituario ei');
            $this->db->where('ei.empresa_impressao_laudo_receituario_id', $_POST['impressao_id']);
            $teste = $this->db->get()->result();
            
            
            $this->db->select('ei.empresa_impressao_laudo_receituario_id,');
            $this->db->from('tb_empresa_impressao_laudo_receituario ei');
            $this->db->where('ei.empresa_id', $empresa_id);
            $teste2 = $this->db->get()->result();
            if (count($teste) > 0) {
                $impressao_id = $teste[0]->empresa_impressao_laudo_receituario_id;
            } 
            if (count($teste) == 0) {
                $this->db->set('nome', $_POST['nome']);
                $this->db->set('cabecalho', $_POST['cabecalho']);
                $this->db->set('rodape', $_POST['rodape']);
                $this->db->set('texto', $_POST['texto']);
                $this->db->set('adicional_cabecalho', $_POST['adicional_cabecalho']);
                $this->db->set('empresa_id', $empresa_id);
                if (count($teste2) > 0) {
                    $this->db->set('ativo', 'f');
                }
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_empresa_impressao_laudo_receituario');
            } else {
                $this->db->set('adicional_cabecalho', $_POST['adicional_cabecalho']);
                $this->db->set('nome', $_POST['nome']);
                $this->db->set('cabecalho', $_POST['cabecalho']);
                $this->db->set('rodape', $_POST['rodape']);
                $this->db->set('texto', $_POST['texto']);
                $this->db->set('empresa_id', $empresa_id);
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $this->db->where('empresa_impressao_laudo_receituario_id', $impressao_id);
                $this->db->update('tb_empresa_impressao_laudo_receituario');
            }

            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            else
//                $ambulatorio_guia_id = $this->db->insert_id();
                return true;
        } catch (Exception $exc) {
            return false;
        }
    }
    
    function ativarconfiguracaolaudoreceituario($impressao_id) {
//        var_dump($impressao_id); die;
        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        $this->db->set('ativo', 't');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('empresa_impressao_laudo_receituario_id', $impressao_id);
        $this->db->update('tb_empresa_impressao_laudo_receituario');


        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('empresa_impressao_laudo_receituario_id !=', $impressao_id);
        $this->db->update('tb_empresa_impressao_laudo_receituario');
        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return false;
        else
            return true;
    }
    
    
    function listarconfiguracaoimpressaoorcamentoreceituario() {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ei.empresa_impressao_orcamento_receituario_id,ei.nome as nome_orcamento, ei.cabecalho,ei.ativo,ei.rodape, e.nome as empresa');
        $this->db->from('tb_empresa_impressao_orcamento_receituario ei');
        $this->db->join('tb_empresa e', 'e.empresa_id = ei.empresa_id', 'left');
        $this->db->where('ei.empresa_id', $empresa_id); 
        return $this->db;
    }
    
    function listarconfiguracaoimpressaoorcamentoformreceituario($empresa_impressao_cabecalho_receituario_id) {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ei.empresa_impressao_orcamento_receituario_id, ei.nome as nome_orcamento,ei.texto, ei.cabecalho,ei.rodape, e.nome as empresa');
        $this->db->from('tb_empresa_impressao_orcamento_receituario ei');
        $this->db->join('tb_empresa e', 'e.empresa_id = ei.empresa_id', 'left');
        $this->db->where('ei.empresa_impressao_orcamento_receituario_id', $empresa_impressao_cabecalho_receituario_id); 
        $return = $this->db->get();
        return $return->result();
    }
    
    
    
    function gravarconfiguracaoimpressaoorcamentoreceituario() {
        try {
//            var_dump($_POST['impressao_id']); die;
            /* inicia o mapeamento no banco */
            $empresa_id = $this->session->userdata('empresa_id');
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            $this->db->select('ei.empresa_impressao_orcamento_receituario_id,');
            $this->db->from('tb_empresa_impressao_orcamento_receituario ei');
            $this->db->where('ei.empresa_impressao_orcamento_receituario_id', $_POST['impressao_id']);
            $teste = $this->db->get()->result();
            $this->db->select('ei.empresa_impressao_orcamento_receituario_id,');
            $this->db->from('tb_empresa_impressao_orcamento_receituario ei');
            $this->db->where('ei.empresa_id', $empresa_id);
            $teste2 = $this->db->get()->result();
            if (count($teste) > 0) {
                $impressao_id = $teste[0]->empresa_impressao_orcamento_receituario_id;
            }

            if (count($teste) == 0) {
                $this->db->set('nome', $_POST['nome']);
                $this->db->set('cabecalho', $_POST['cabecalho']);
                $this->db->set('rodape', $_POST['rodape']);
                $this->db->set('texto', $_POST['texto']);
                $this->db->set('empresa_id', $empresa_id);
                if (count($teste2) > 0) {
                    $this->db->set('ativo', 'f');
                }
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_empresa_impressao_orcamento_receituario');
            } else {
                $this->db->set('nome', $_POST['nome']);
                $this->db->set('cabecalho', $_POST['cabecalho']);
                $this->db->set('rodape', $_POST['rodape']);
                $this->db->set('texto', $_POST['texto']);
                $this->db->set('empresa_id', $empresa_id);
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $this->db->where('empresa_impressao_orcamento_receituario_id', $impressao_id);
                $this->db->update('tb_empresa_impressao_orcamento_receituario');
            }

            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            else
//                $ambulatorio_guia_id = $this->db->insert_id();
                return true;
        } catch (Exception $exc) {
            return false;
        }
    }
    
    function ativarconfiguracaoorcamentoreceituario($impressao_id) {
//        var_dump($impressao_id); die;
        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        $this->db->set('ativo', 't');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('empresa_impressao_orcamento_receituario_id', $impressao_id);
        $this->db->update('tb_empresa_impressao_orcamento_receituario');
 
        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('empresa_impressao_orcamento_receituario_id !=', $impressao_id);
        $this->db->update('tb_empresa_impressao_orcamento_receituario');
        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return false;
        else
            return true;
    }
    
    
    function listarconfiguracaoimpressaoreciboreceituario() {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ei.empresa_impressao_recibo_receituario_id,ei.nome as nome_recibo, ei.cabecalho,ei.ativo,ei.rodape, e.nome as empresa');
        $this->db->from('tb_empresa_impressao_recibo_receituario ei');
        $this->db->join('tb_empresa e', 'e.empresa_id = ei.empresa_id', 'left');
        $this->db->where('ei.empresa_id', $empresa_id);
 
        return $this->db;
    }
    
    
    function listarconfiguracaoimpressaoreciboformreceituario($empresa_impressao_cabecalho_receituario_id) {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ei.empresa_impressao_recibo_receituario_id, ei.repetir_recibo, ei.nome as nome_recibo,ei.texto, ei.cabecalho,ei.rodape, e.nome as empresa, linha_procedimento');
        $this->db->from('tb_empresa_impressao_recibo_receituario ei');
        $this->db->join('tb_empresa e', 'e.empresa_id = ei.empresa_id', 'left');
        $this->db->where('ei.empresa_impressao_recibo_receituario_id', $empresa_impressao_cabecalho_receituario_id);
        $return = $this->db->get();
        return $return->result();
    }
    
    
    function gravarconfiguracaoimpressaoreciboreceituario() {
        try {
//            var_dump($_POST['impressao_id']); die;
            /* inicia o mapeamento no banco */
            $empresa_id = $this->session->userdata('empresa_id');
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            
            if ($_POST['impressao_id'] == "") {
                $_POST['impressao_id'] = 0;
            }
            $this->db->select('ei.empresa_impressao_recibo_receituario_id,');
            $this->db->from('tb_empresa_impressao_recibo_receituario ei');
            $this->db->where('ei.empresa_impressao_recibo_receituario_id', $_POST['impressao_id']);
            $teste = $this->db->get()->result();
            
            
            $this->db->select('ei.empresa_impressao_recibo_receituario_id,');
            $this->db->from('tb_empresa_impressao_recibo_receituario ei');
            $this->db->where('ei.empresa_id', $empresa_id);
            $teste2 = $this->db->get()->result();
            if (count($teste) > 0) {
                $impressao_id = $teste[0]->empresa_impressao_recibo_receituario_id;
            }
//            var_dump($_POST); die;
            if (count($teste) == 0) {
                $this->db->set('nome', $_POST['nome']);
                $this->db->set('linha_procedimento', $_POST['linha_procedimento']);
                $this->db->set('cabecalho', $_POST['cabecalho']);
                if ($_POST['repetir_recibo'] > 0) {
                    $this->db->set('repetir_recibo', $_POST['repetir_recibo']);
                }
                $this->db->set('rodape', $_POST['rodape']);
                $this->db->set('texto', $_POST['texto']);
                $this->db->set('empresa_id', $empresa_id);
                if (count($teste2) > 0) {
                    $this->db->set('ativo', 'f');
                }
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_empresa_impressao_recibo_receituario');
            } else {
                $this->db->set('nome', $_POST['nome']);
                if ($_POST['repetir_recibo'] > 0) {
                    $this->db->set('repetir_recibo', $_POST['repetir_recibo']);
                }
                $this->db->set('linha_procedimento', $_POST['linha_procedimento']);
                $this->db->set('cabecalho', $_POST['cabecalho']);
                $this->db->set('rodape', $_POST['rodape']);
                $this->db->set('texto', $_POST['texto']);
                $this->db->set('empresa_id', $empresa_id);
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $this->db->where('empresa_impressao_recibo_receituario_id', $impressao_id);
                $this->db->update('tb_empresa_impressao_recibo_receituario');
            }

            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            else
//                $ambulatorio_guia_id = $this->db->insert_id();
                return true;
        } catch (Exception $exc) {
            return false;
        }
    }
    
      function ativarconfiguracaoreciboreceituario($impressao_id) {
//        var_dump($impressao_id); die;
        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        $this->db->set('ativo', 't');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('empresa_impressao_recibo_receituario_id', $impressao_id);
        $this->db->update('tb_empresa_impressao_recibo_receituario');
 
        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('empresa_impressao_recibo_receituario_id !=', $impressao_id);
        $this->db->update('tb_empresa_impressao_recibo_receituario');
        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return false;
        else
            return true;
    }
    
}

?>
                              
