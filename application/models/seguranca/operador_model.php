<?php

require_once APPPATH . 'models/base/BaseModel.php';

class Operador_model extends BaseModel {

    var $_operador_id = null;
    var $_usuario = null;
    var $_senha = null;
    var $_perfil_id = null;
    var $_ativo = null;
    var $_nome = null;
    var $_cns = null;
    var $_conselho = null;
    var $_email = null;
    var $_nascimento = null;
    var $_cpf = null;
    var $_sexo = null;
    var $_celular = null;
    var $_telefone = null;
    var $_tipoLogradouro = null;
    var $_tipo_id = null;
    var $_ir = null;
    var $_pis = null;
    var $_cofins = null;
    var $_csll = null;
    var $_iss = null;
    var $_taxa_administracao = null;
    var $_credor_devedor_id = null;
    var $_valor_base = null;
    var $_conta_id = null;
    var $_numero = null;
    var $_bairro = null;
    var $_complemento = null;
    var $_cidade = null;
    var $_cep = null;
    var $_cidade_nome = null;
    var $_cbo_nome = null;
    var $_cbo_ocupacao_id = null;
    var $_logradouro = null;
    var $_carimbo = null;
    var $_solicitante = null;
    var $_hora_manha = null;
    var $_hora_tarde = null;
    var $_sigla_id = null;
    var $_uf_profissional = null;
    var $_seg_manha = null;
    var $_seg_tarde = null;
    var $_ter_manha = null;
    var $_ter_tarde = null;
    var $_qua_manha = null;
    var $_qua_tarde = null;
    var $_qui_manha = null;
    var $_qui_tarde = null;
    var $_sex_manha = null;
    var $_sex_tarde = null;
    var $_sab_manha = null;
    var $_sab_tarde = null;
    var $_cod_cnes_prof = null;
    var $_desconto_seguro = null;
    var $_tipo_desc_seguro = null;

    function Operador_model($operador_id = null) {
        parent::Model();
        if (isset($operador_id)) {
            $this->instanciar($operador_id);
        }
    }

    function totalRegistros($parametro) {
        $this->db->select('operador_id');
        $this->db->from('tb_operador');
        if ($parametro != null && $parametro != -1) {

            $this->db->where('usuario ilike', $parametro . "%");
        }
        $return = $this->db->count_all_results();
        return $return;
    }

    function listar($args = array()) {

        $this->db->from('tb_operador')
                ->join('tb_perfil', 'tb_perfil.perfil_id = tb_operador.perfil_id', 'left')
                ->select('"tb_operador".*, tb_perfil.nome as nomeperfil');

        $this->db->where('tb_operador.usuario IS NOT NULL');
//        $this->db->where('tb_operador.senha IS NOT NULL');
        $operador_id = $this->session->userdata('operador_id');

//        var_dump($args); die;
        if ($args) {
            if (isset($args['nome']) && strlen($args['nome']) > 0) {  
               $this->db->where("(tb_operador.nome ilike '%" . $args['nome'] . "%' OR tb_operador.usuario ilike '%" . $args['nome'] . "%')");  
            }
            if (@$args['ativo'] != '') {
                $this->db->where('tb_operador.ativo', $args['ativo']);
            } else {
                $this->db->where('tb_operador.ativo', 't');
            }
        } else {
            $this->db->where('tb_operador.ativo', 't');
        }
        if ($operador_id != 1) {
            $this->db->where('tb_operador.operador_id != 1');
        }
        return $this->db;
    }

    function listargrupo() {
        $this->db->distinct();
        $this->db->select('ambulatorio_grupo_id, 
                            nome');
        $this->db->from('tb_ambulatorio_grupo');
        $this->db->orderby('nome');
        $return = $this->db->get();
        return $return->result();
    }

    function listarusuarioadminsenha() {
        // $this->db->distinct();
        $this->db->select('operador_id, 
                            nome');
        $this->db->from('tb_operador');
        $this->db->where('senha', md5($_POST['senha']));
        $this->db->where('perfil_id', 1);
        $this->db->where('ativo', 't');

        $return = $this->db->get();
        return $return->result();
    }

    function listarmedicosolicitante($args = array()) {
        $this->db->select('operador_id, nome, conselho');
        $this->db->from('tb_operador');
        $this->db->where('medico', 'true');
        $this->db->where('ativo', 'true');
        $this->db->where('solicitante', 'true');

        if ($args) {
            if (isset($args['nome']) && strlen($args['nome']) > 0) {
                $this->db->where("(tb_operador.nome ilike '%" . $args['nome'] . "%' OR tb_operador.usuario ilike '%" . $args['nome'] . "%')");
            }
        }
        return $this->db;
    }

    function listaragendatelefonica($args = array()) {
        $this->db->select('agenda_telefonica_id, nome, telefone1, telefone2, telefone3, email, observacao');
        $this->db->from('tb_agenda_telefonica');
        $this->db->where('ativo', 'true');

        if ($args) {
            if (isset($args['nome']) && strlen($args['nome']) > 0) {
                $this->db->where("(tb_agenda_telefonica.nome ilike '%" . $args['nome'] . "%')");
            }
        }
        return $this->db;
    }

    function gravarcopiaroperadorconvenioempresa() {
        try {
            $operador_id = (int) $_POST['txtoperador_id'];
            $empresa_id_origem = (int) $_POST['empresa_id_origem'];
            $empresa_id_destino = (int) $_POST['empresa_id_destino'];

            $horario = date("Y-m-d H:i:s");
            $operador_cadastro_id = $this->session->userdata('operador_id');

            $this->db->set('ativo', 'f');
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_cadastro_id);
            $this->db->where('empresa_id', $empresa_id_destino);
            $this->db->update('tb_ambulatorio_empresa_operador');

            $this->db->set('operador_id', $operador_id);
            $this->db->set('empresa_id', $empresa_id_destino);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_cadastro_id);
            $this->db->insert('tb_ambulatorio_empresa_operador');

            $this->db->select('convenio_id');
            $this->db->from('tb_ambulatorio_convenio_operador');
            $this->db->where('ativo', 't');
            $this->db->where('operador_id', $operador_id);
            $this->db->where('empresa_id', $empresa_id_origem);
            $return = $this->db->get()->result();

            foreach ($return as $conv) {

                $convenio_id = (int) $conv->convenio_id;

                $this->db->set('ativo', 'f');
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_cadastro_id);
                $this->db->where('operador_id', $operador_id);
                $this->db->where('convenio_id', $convenio_id);
                $this->db->where('empresa_id', $empresa_id_destino);
                $this->db->update('tb_ambulatorio_convenio_operador');

                $this->db->set('operador_id', $operador_id);
                $this->db->set('convenio_id', $convenio_id);
                $this->db->set('empresa_id', $empresa_id_destino);
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_cadastro_id);
                $this->db->insert('tb_ambulatorio_convenio_operador');

                $this->db->set('ativo', 'f');
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_cadastro_id);
                $this->db->where('operador', $operador_id);
                $this->db->where('convenio_id', $convenio_id);
                $this->db->where('empresa_id', $empresa_id_destino);
                $this->db->update('tb_convenio_operador_procedimento');

                $this->db->select('cop.procedimento_convenio_id');
                $this->db->from('tb_convenio_operador_procedimento cop');
                $this->db->where('cop.ativo', 't');
                $this->db->where('cop.operador', $operador_id);
                $this->db->where('cop.convenio_id', $convenio_id);
                $this->db->where('cop.empresa_id', $empresa_id_origem);
                $return2 = $this->db->get()->result();

                foreach ($return2 as $value) {
                    $this->db->set('operador', $operador_id);
                    $this->db->set('convenio_id', $convenio_id);
                    $this->db->set('empresa_id', $empresa_id_destino);
                    $this->db->set('procedimento_convenio_id', $value->procedimento_convenio_id);
                    $this->db->set('data_cadastro', $horario);
                    $this->db->set('operador_cadastro', $operador_cadastro_id);
                    $this->db->insert('tb_convenio_operador_procedimento');
                }
            }
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravareplicaroperadorconvenio() {
        try {
            $operador_id_origem = (int) $_POST['txtoperador_id'];
            $operador_id_destino = (int) $_POST['operador_destino'];

            $this->db->select('empresa_id');
            $this->db->from('tb_empresa');
            if ($_POST['empresa_id'] != '') {
                $this->db->where('empresa_id', $_POST['empresa_id']);
            } else {
                $this->db->where("empresa_id IN (
                    SELECT empresa_id FROM ponto.tb_ambulatorio_empresa_operador
                    WHERE ativo = 't' AND operador_id = $operador_id_origem
                )");
            }
            $return = $this->db->get()->result();

            $horario = date("Y-m-d H:i:s");
            $operador_cadastro_id = $this->session->userdata('operador_id');

            $this->db->set('ativo', 'f');
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_cadastro_id);
            $this->db->where('operador_id', $operador_id_destino);
            $this->db->update('tb_ambulatorio_empresa_operador');

            foreach ($return as $item) {

                $this->db->set('operador_id', $operador_id_destino);
                $this->db->set('empresa_id', $item->empresa_id);
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_cadastro_id);
                $this->db->insert('tb_ambulatorio_empresa_operador');

                $this->db->select('convenio_id');
                $this->db->from('tb_ambulatorio_convenio_operador');
                $this->db->where('ativo', 't');
                $this->db->where('operador_id', $operador_id_origem);
                $this->db->where('empresa_id', $item->empresa_id);
                $return2 = $this->db->get()->result();

                foreach ($return2 as $conv) {

                    $convenio_id = (int) $conv->convenio_id;

                    $this->db->set('ativo', 'f');
                    $this->db->set('data_atualizacao', $horario);
                    $this->db->set('operador_atualizacao', $operador_cadastro_id);
                    $this->db->where('operador_id', $operador_id_destino);
                    $this->db->where('convenio_id', $convenio_id);
                    $this->db->where('empresa_id', $item->empresa_id);
                    $this->db->update('tb_ambulatorio_convenio_operador');

                    $this->db->set('operador_id', $operador_id_destino);
                    $this->db->set('convenio_id', $convenio_id);
                    $this->db->set('empresa_id', $item->empresa_id);
                    $this->db->set('data_cadastro', $horario);
                    $this->db->set('operador_cadastro', $operador_cadastro_id);
                    $this->db->insert('tb_ambulatorio_convenio_operador');

                    $this->db->set('ativo', 'f');
                    $this->db->set('data_atualizacao', $horario);
                    $this->db->set('operador_atualizacao', $operador_cadastro_id);
                    $this->db->where('operador', $operador_id_destino);
                    $this->db->where('convenio_id', $convenio_id);
                    $this->db->where('empresa_id', $item->empresa_id);
                    $this->db->update('tb_convenio_operador_procedimento');

                    $this->db->select('cop.procedimento_convenio_id');
                    $this->db->from('tb_convenio_operador_procedimento cop');
                    $this->db->where('cop.ativo', 't');
                    $this->db->where('cop.operador', $operador_id_origem);
                    $this->db->where('cop.convenio_id', $convenio_id);
                    $this->db->where('cop.empresa_id', $item->empresa_id);
                    $return3 = $this->db->get()->result();

                    foreach ($return3 as $value) {
                        $this->db->set('operador', $operador_id_destino);
                        $this->db->set('convenio_id', $convenio_id);
                        $this->db->set('empresa_id', $item->empresa_id);
                        $this->db->set('procedimento_convenio_id', $value->procedimento_convenio_id);
                        $this->db->set('data_cadastro', $horario);
                        $this->db->set('operador_cadastro', $operador_cadastro_id);
                        $this->db->insert('tb_convenio_operador_procedimento');
                    }
                }
            }
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravaprocedimentosoperadorescompleto($operador_id) {
        try {
            $horario = date("Y-m-d H:i:s");
            $operador_atual_id = $this->session->userdata('operador_id');
//            $empresa_id_origem = (int) $_POST['empresa_id_origem'];
//            $empresa_id_destino = (int) $_POST['empresa_id_destino'];

            $this->db->select('empresa_id');
            $this->db->from('tb_empresa');
            $this->db->where('ativo', 't');
            $empresas = $this->db->get()->result();

            $this->db->select('convenio_id');
            $this->db->from('tb_convenio');
            $this->db->where('ativo', 't');
            $convenio = $this->db->get()->result();

            foreach ($empresas as $emp) {
                // EMPRESA
                $this->db->set('operador_id', $operador_id);
                $this->db->set('empresa_id', $emp->empresa_id);
                $horario = date("Y-m-d H:i:s");
//                $operador_id = $this->session->userdata('operador_id');
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_atual_id);
                $this->db->insert('tb_ambulatorio_empresa_operador');
                $ambulatorio_empresa_id = $this->db->insert_id();

                foreach ($convenio as $conv) {
                    // CONVENIO
                    $this->db->set('operador_id', $operador_id);
                    $this->db->set('convenio_id', $conv->convenio_id);
                    $this->db->set('empresa_id', $emp->empresa_id);
                    $horario = date("Y-m-d H:i:s");
//                    $operador_id = $this->session->userdata('operador_id');
                    $this->db->set('data_cadastro', $horario);
                    $this->db->set('operador_cadastro', $operador_atual_id);
                    $this->db->insert('tb_ambulatorio_convenio_operador');
                    $ambulatorio_convenio_id = $this->db->insert_id();

                    // SELECT PROCEDIMENTOS
                }
                $procedimento_multiempresa = $this->listarempresapermissoes($emp->empresa_id);

                $this->db->select('pc.procedimento_convenio_id,pc.convenio_id');
                $this->db->from('tb_procedimento_convenio pc');
                $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
                $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
                $this->db->join('tb_ambulatorio_grupo ag', 'ag.nome = pt.grupo');
                $this->db->where("ag.tipo !=", 'CIRURGICO');
                $this->db->where("pc.ativo", 't');
//                    $this->db->where('pc.convenio_id', $conv->convenio_id);
//                    $procedimento_multiempresa = $this->session->userdata('procedimento_multiempresa');
                if ($procedimento_multiempresa == 't') {
                    $this->db->where('pc.empresa_id', $emp->empresa_id);
                }
                $procedimentos = $this->db->get()->result();


                foreach ($procedimentos as $proc) {
                    $this->db->set('operador', $operador_id);
                    $this->db->set('convenio_id', $proc->convenio_id);
                    $this->db->set('empresa_id', $emp->empresa_id);
                    $this->db->set('procedimento_convenio_id', $proc->procedimento_convenio_id);
                    $horario = date("Y-m-d H:i:s");
//                    $operador_id = $this->session->userdata('operador_id');
                    $this->db->set('data_cadastro', $horario);
                    $this->db->set('operador_cadastro', $operador_atual_id);
                    $this->db->insert('tb_convenio_operador_procedimento');
                }
            }
        } catch (Exception $exc) {
            return -1;
        }
    }

    function vinculaoperadorconveniotodos($operador_id) {
        try {
            $horario = date("Y-m-d H:i:s");
            $operador_atual_id = $this->session->userdata('operador_id');

            // SETANDO REGISTROS ANTIGOS PARA FALSE PARA NÃO DUPLICAR
            $this->db->set('ativo', 'f');
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_atual_id);
            $this->db->where('operador_id', $operador_id);
            $this->db->update('tb_ambulatorio_empresa_operador');

            $this->db->set('ativo', 'f');
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_atual_id);
            $this->db->where('operador_id', $operador_id);
            $this->db->update('tb_ambulatorio_convenio_operador');

            $this->db->set('ativo', 'f');
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_atual_id);
            $this->db->where('operador', $operador_id);
            $this->db->update('tb_convenio_operador_procedimento');

            $this->db->select('empresa_id');
            $this->db->from('tb_empresa');
            $this->db->where('ativo', 't');
            $empresas = $this->db->get()->result();

            $this->db->select('convenio_id');
            $this->db->from('tb_convenio');
            $this->db->where('ativo', 't');
            $convenio = $this->db->get()->result();

            foreach ($empresas as $emp) {
                // EMPRESA
                $this->db->set('operador_id', $operador_id);
                $this->db->set('empresa_id', $emp->empresa_id);
                $horario = date("Y-m-d H:i:s");
//                $operador_id = $this->session->userdata('operador_id');
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_atual_id);
                $this->db->insert('tb_ambulatorio_empresa_operador');
                $ambulatorio_empresa_id = $this->db->insert_id();

                foreach ($convenio as $conv) {
                    // CONVENIO
                    $this->db->set('operador_id', $operador_id);
                    $this->db->set('convenio_id', $conv->convenio_id);
                    $this->db->set('empresa_id', $emp->empresa_id);
                    $horario = date("Y-m-d H:i:s");
//                    $operador_id = $this->session->userdata('operador_id');
                    $this->db->set('data_cadastro', $horario);
                    $this->db->set('operador_cadastro', $operador_atual_id);
                    $this->db->insert('tb_ambulatorio_convenio_operador');
                    $ambulatorio_convenio_id = $this->db->insert_id();
                }
                // SELECT PROCEDIMENTOS
                $procedimento_multiempresa = $this->listarempresapermissoes($emp->empresa_id);

                $this->db->select('pc.procedimento_convenio_id,pc.convenio_id');
                $this->db->from('tb_procedimento_convenio pc');
                $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
                $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
                $this->db->join('tb_ambulatorio_grupo ag', 'ag.nome = pt.grupo');
                $this->db->where("ag.tipo !=", 'CIRURGICO');
                $this->db->where("pc.ativo", 't');
//                    $this->db->where('pc.convenio_id', $conv->convenio_id);
//                    $procedimento_multiempresa = $this->session->userdata('procedimento_multiempresa');
                if ($procedimento_multiempresa == 't') {
                    $this->db->where('pc.empresa_id', $emp->empresa_id);
                }
                $procedimentos = $this->db->get()->result();


                foreach ($procedimentos as $proc) {
                    $this->db->set('operador', $operador_id);
                    $this->db->set('convenio_id', $proc->convenio_id);
                    $this->db->set('empresa_id', $emp->empresa_id);
                    $this->db->set('procedimento_convenio_id', $proc->procedimento_convenio_id);
                    $horario = date("Y-m-d H:i:s");
//                    $operador_id = $this->session->userdata('operador_id');
                    $this->db->set('data_cadastro', $horario);
                    $this->db->set('operador_cadastro', $operador_atual_id);
                    $this->db->insert('tb_convenio_operador_procedimento');
                }
            }
        } catch (Exception $exc) {
            return -1;
        }
    }

    function listarempresapermissoes($empresa_id) {
//        if ($empresa_id == null) {
//            $empresa_id = $this->session->userdata('empresa_id');
//        }

        $this->db->select('e.empresa_id,
                            ordem_chegada,
                            promotor_medico,
                            excluir_transferencia,
                            orcamento_config,
                            rodape_config,
                            cabecalho_config,
                            valor_recibo_guia,
                            valor_autorizar,
                            gerente_contasapagar,
                            cpf_obrigatorio,
                            orcamento_recepcao,
                            relatorio_ordem,
                            relatorio_producao,
                            relatorios_recepcao,
                            encaminhamento_email,
                            financeiro_cadastro,
                            odontologia_valor_alterar,
                            selecionar_retorno,
                            oftamologia,
                            procedimento_multiempresa,
                            carregar_modelo_receituario,
                            retirar_botao_ficha,
                            ep.desabilitar_trava_retorno,
                            ep.conjuge,
                            ep.associa_credito_procedimento,
                            ep.valor_laboratorio,
                            ep.desativar_personalizacao_impressao,
                            ep.campos_obrigatorios_pac_cpf,
                            ep.profissional_completo,
                            ep.tecnica_promotor,
                            ep.tecnica_enviar,
                            ep.campos_obrigatorios_pac_sexo,
                            ep.campos_obrigatorios_pac_nascimento,
                            ep.campos_obrigatorios_pac_telefone,
                            ep.campos_obrigatorios_pac_municipio,
                            ep.repetir_horarios_agenda,
                            e.nome');
        $this->db->from('tb_empresa e');
        $this->db->where('e.empresa_id', $empresa_id);
        $this->db->join('tb_empresa_permissoes ep', 'ep.empresa_id = e.empresa_id', 'left');
        $this->db->orderby('e.empresa_id');
        $return = $this->db->get()->result();
        $procedimento_multiempresa = $return[0]->procedimento_multiempresa;
        return $procedimento_multiempresa;
    }

    function gravarcopiaroperadorconvenio() {
        try {
            $operador_id = $_POST['txtoperador_id'];
            $convenio_id = $_POST['convenio_id'];
            $empresa_id_origem = $_POST['empresa_id_origem'];
            $empresa_id_destino = $_POST['empresa_id_destino'];

            $horario = date("Y-m-d H:i:s");
            $operador_cadastro_id = $this->session->userdata('operador_id');

            $this->db->set('ativo', 'f');
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_cadastro_id);
            $this->db->where('operador_id', $operador_id);
            $this->db->where('empresa_id', $empresa_id_destino);
            $this->db->update('tb_ambulatorio_empresa_operador');

            $this->db->set('operador_id', $operador_id);
            $this->db->set('empresa_id', $empresa_id_destino);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_cadastro_id);
            $this->db->insert('tb_ambulatorio_empresa_operador');

            $this->db->set('ativo', 'f');
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_cadastro_id);
            $this->db->where('operador_id', $operador_id);
            $this->db->where('convenio_id', $convenio_id);
            $this->db->where('empresa_id', $empresa_id_destino);
            $this->db->update('tb_ambulatorio_convenio_operador');

            $this->db->set('operador_id', $operador_id);
            $this->db->set('convenio_id', $convenio_id);
            $this->db->set('empresa_id', $empresa_id_destino);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_cadastro_id);
            $this->db->insert('tb_ambulatorio_convenio_operador');

            $this->db->set('ativo', 'f');
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_cadastro_id);
            $this->db->where('operador', $operador_id);
            $this->db->where('convenio_id', $convenio_id);
            $this->db->where('empresa_id', $empresa_id_destino);
            $this->db->update('tb_convenio_operador_procedimento');

            $this->db->select('cop.procedimento_convenio_id');
            $this->db->from('tb_convenio_operador_procedimento cop');
            $this->db->where('cop.ativo', 't');
            $this->db->where('cop.operador', $operador_id);
            $this->db->where('cop.convenio_id', $convenio_id);
            $this->db->where('cop.empresa_id', $empresa_id_origem);
            $return = $this->db->get()->result();

            foreach ($return as $value) {

                $this->db->set('operador', $operador_id);
                $this->db->set('convenio_id', $convenio_id);
                $this->db->set('empresa_id', $empresa_id_destino);
                $this->db->set('procedimento_convenio_id', $value->procedimento_convenio_id);
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_convenio_operador_procedimento');
            }
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravaroperadorconvenioempresa() {
        try {
            $this->db->select('ambulatorio_empresa_operador_id');
            $this->db->from('tb_ambulatorio_empresa_operador aeo');
            $this->db->where('ativo', 't');
            $this->db->where('operador_id', $_POST['txtoperador_id']);
            $this->db->where('empresa_id', $_POST['empresa_id']);
            $return = $this->db->get()->result();

            if (count($return) == 0) {
                /* inicia o mapeamento no banco */
                $this->db->set('operador_id', $_POST['txtoperador_id']);
                $this->db->set('empresa_id', $_POST['empresa_id']);
                $horario = date("Y-m-d H:i:s");
                $operador_id = $this->session->userdata('operador_id');
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_ambulatorio_empresa_operador');
                $erro = $this->db->_error_message();
                if (trim($erro) != "") // erro de banco
                    return -1;
                else
                    $estoque_menu_produtos_id = $this->db->insert_id();

                return $estoque_menu_produtos_id;
            }
            else {
                return -2;
            }
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravaroperadorconvenio() {
        try {
            $this->db->select('cop.ambulatorio_convenio_operador_id');
            $this->db->from('tb_ambulatorio_convenio_operador cop');
            $this->db->where('cop.ativo', 't');
            $this->db->where('operador_id', $_POST['txtoperador_id']);
            $this->db->where('convenio_id', $_POST['convenio_id']);
            $this->db->where('empresa_id', $_POST['empresa']);
            $return = $this->db->get()->result();

            if (count($return) == 0) {
                /* inicia o mapeamento no banco */
                $this->db->set('operador_id', $_POST['txtoperador_id']);
                $this->db->set('convenio_id', $_POST['convenio_id']);
                $this->db->set('empresa_id', $_POST['empresa']);
                $horario = date("Y-m-d H:i:s");
                $operador_id = $this->session->userdata('operador_id');
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_ambulatorio_convenio_operador');
                $erro = $this->db->_error_message();
                if (trim($erro) != "") // erro de banco
                    return -1;
                else
                    $estoque_menu_produtos_id = $this->db->insert_id();

                return $estoque_menu_produtos_id;
            }
            else {
                return -2;
            }
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravaroperadorconvenioprocedimento($procedimento, $desconto, $valor_personalizado) {
        $desconto = str_replace(",", ".", str_replace(".", "", $desconto));
        $valor_personalizado = str_replace(",", ".", str_replace(".", "", $valor_personalizado));

        $this->db->select('convenio_operador_procedimento_id');
        $this->db->from('tb_convenio_operador_procedimento');
        $this->db->where("ativo", 't');
        $this->db->where('operador', $_POST['txtoperador_id']);
        $this->db->where('convenio_id', $_POST['txtconvenio_id']);
        $this->db->where('empresa_id', $_POST['txtempresa_id']);
        $this->db->where('procedimento_convenio_id', $procedimento);
        $pr = $this->db->get()->result();

        if (count($pr) == 0) {

            $this->db->set('operador', $_POST['txtoperador_id']);
            $this->db->set('convenio_id', $_POST['txtconvenio_id']);
            $this->db->set('empresa_id', $_POST['txtempresa_id']);
            $this->db->set('procedimento_convenio_id', $procedimento);
            $this->db->set('desconto_procedimento', $desconto);
            $this->db->set('valor_personalizado', $valor_personalizado);
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_convenio_operador_procedimento');
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            else
                $estoque_menu_produtos_id = $this->db->insert_id();
        }
    }

    function excluiroperadorconvenioempresa($operador, $empresa_id) {

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('operador_id', $operador);
        $this->db->where('empresa_id', $empresa_id);
        $this->db->update('tb_ambulatorio_convenio_operador');

        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('operador', $operador);
        $this->db->where('empresa_id', $empresa_id);
        $this->db->update('tb_convenio_operador_procedimento');

        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('operador_id', $operador);
        $this->db->where('empresa_id', $empresa_id);
        $this->db->update('tb_ambulatorio_empresa_operador');
        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return -1;
        else
            return 0;
    }

    function listarempresasconvenio() {

//        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('empresa_id,
                            nome');
        $this->db->from('tb_empresa');
        $this->db->where('ativo', 't');
        $this->db->orderby('nome');
        $return = $this->db->get();
        return $return->result();
    }

    function excluiroperadorconvenio($ambulatorio_convenio_operador_id, $operador, $empresa_id, $convenio_id) {

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('ambulatorio_convenio_operador_id', $ambulatorio_convenio_operador_id);
        $this->db->update('tb_ambulatorio_convenio_operador');

        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('operador', $operador);
        $this->db->where('empresa_id', $empresa_id);
        $this->db->where('convenio_id', $convenio_id);
        $this->db->update('tb_convenio_operador_procedimento');
        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return -1;
        else
            return 0;
    }

    function excluiroperadorconvenioprocedimento($convenio_operador_procedimento_id) {

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('convenio_operador_procedimento_id', $convenio_operador_procedimento_id);
        $this->db->update('tb_convenio_operador_procedimento');
        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return -1;
        else
            return 0;
    }

    function listaroperadordadosconvenio($convenio_id, $operador_id, $empresa_id) {
        $this->db->select('o.operador_id,
                           o.nome as operador,
                           c.nome as convenio,
                           aco.convenio_id,
                           e.nome as empresa,
                           e.empresa_id');
        $this->db->from('tb_ambulatorio_convenio_operador aco');
        $this->db->join('tb_operador o', 'o.operador_id = aco.operador_id');
        $this->db->join('tb_convenio c', 'c.convenio_id = aco.convenio_id');
        $this->db->join('tb_empresa e', 'e.empresa_id = aco.empresa_id');
        $this->db->where('aco.operador_id', $operador_id);
        $this->db->where('aco.convenio_id', $convenio_id);
        $this->db->where('aco.empresa_id', $empresa_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listarCada($operador_id) {
        $this->db->select('o.operador_id,
                               o.usuario,
                               o.perfil_id,
                               p.nome,
                               o.credor_devedor_id,
                               o.ir,
                               o.pis,
                               o.cofins,
                               o.classe,
                               o.conta_id,
                               o.tipo_id,
                               o.csll,
                               o.iss,
                               o.valor_base,
                               o.nome as operador,
                               o.piso_medico');
        $this->db->from('tb_operador o');
        $this->db->join('tb_perfil p', 'p.perfil_id = o.perfil_id');
        $this->db->where('o.operador_id', $operador_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listarVarios($operador_id) {
        $this->db->select('o.operador_id,
                               o.usuario,
                               o.perfil_id,
                               p.nome,
                               o.credor_devedor_id,
                               o.ir,
                               o.pis,
                               o.cofins,
                               o.classe,
                               o.conta_id,
                               o.tipo_id,
                               o.csll,
                               o.iss,
                               o.valor_base,
                               o.nome as operador');
        $this->db->from('tb_operador o');
        $this->db->join('tb_perfil p', 'p.perfil_id = o.perfil_id');
        $medicos = array_unique($_POST['medico']);
        $medicos = implode(', ', $medicos);
        $this->db->where("o.operador_id IN ($medicos)");
        $return = $this->db->get();
        return $return->result();
    }

    function listarconveniooperador($operador_id, $empresa_id) {
        $this->db->select('co.convenio_id,
                            c.nome,
                            co.operador_id,
                            co.empresa_id,
                            co.ambulatorio_convenio_operador_id,
                            e.nome as empresa');
        $this->db->from('tb_ambulatorio_convenio_operador co');
        $this->db->join('tb_convenio c', 'c.convenio_id = co.convenio_id');
        $this->db->join('tb_empresa e', 'e.empresa_id = co.empresa_id', 'left');
        $this->db->where('co.operador_id', $operador_id);
        $this->db->where('co.empresa_id', $empresa_id);
        $this->db->where('co.ativo', 't');
        $this->db->orderby("c.nome");
        $return = $this->db->get();
        return $return->result();
    }

    function listarempresaconvenioorigem($empresa_id) {
        $this->db->select('empresa_id,
                            nome');
        $this->db->from('tb_empresa');
        $this->db->orderby('nome');
        $this->db->where('empresa_id', $empresa_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listarempresasoperadorconvenio($operador) {

//        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('e.empresa_id,
                            e.nome');
        $this->db->from('tb_ambulatorio_empresa_operador aeo');
        $this->db->join('tb_empresa e', 'e.empresa_id = aeo.empresa_id');
        $this->db->where('aeo.operador_id', $operador);
        $this->db->where('aeo.ativo', 't');
        $this->db->orderby('e.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function listarprocedimentoconvenio($convenio_id) {
        $this->db->select('pc.procedimento_convenio_id,
                            pt.nome as procedimento,
                            c.convenio_id,
                            c.nome as convenio,
                            pt.grupo');
        $this->db->from('tb_procedimento_convenio pc');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id');
        $this->db->where('pc.convenio_id', $convenio_id);

        $procedimento_multiempresa = $this->session->userdata('procedimento_multiempresa');
        if ($procedimento_multiempresa == 't') {
            $empresa_id = $this->session->userdata('empresa_id');
            $this->db->where('pc.empresa_id', $empresa_id);
        }

        $this->db->where('pc.ativo', 't');
        $this->db->orderby("pt.nome");
        $return = $this->db->get();
        return $return->result();
    }

    function listarprocedimentoconveniooperador($operador_id, $convenio_id, $empresa_id) {
        $this->db->select('cop.convenio_operador_procedimento_id,
                            pt.nome as procedimento,
                            c.nome as convenio,
                            e.nome as empresa,
                            cop.desconto_procedimento,
                            cop.valor_personalizado');
        $this->db->from('tb_convenio_operador_procedimento cop');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = cop.procedimento_convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_empresa e', 'e.empresa_id = cop.empresa_id', 'left');
        $this->db->where('cop.operador', $operador_id);
        $this->db->where('cop.empresa_id', $empresa_id);
        $this->db->where('pc.convenio_id', $convenio_id);
        $this->db->where('cop.ativo', 't');
        $this->db->orderby("c.nome");
        $this->db->orderby("pt.nome");
        $return = $this->db->get();
        return $return->result();
    }

    function listaroperadoreslembrete() {
        $this->db->select('    o.operador_id,
                               o.usuario,
                               o.nome,
                               o.perfil_id,
                               o.solicitante,
                               p.nome as perfil');
        $this->db->from('tb_operador o');
        $this->db->where('o.ativo', 't');
        $this->db->where('o.solicitante', 'f');
        $this->db->where('o.usuario IS NOT NULL');
        $this->db->join('tb_perfil p', 'p.perfil_id = o.perfil_id', 'left');
        $this->db->orderby('o.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function listaroperadores() {
        $this->db->select('o.operador_id,
                               o.usuario,
                               o.nome,
                               o.perfil_id,
                               p.nome as perfil');
        $this->db->from('tb_operador o');
        $this->db->join('tb_perfil p', 'p.perfil_id = o.perfil_id', 'left');
        $this->db->orderby('o.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function listaroperadorguiche() {
        $this->db->select('    o.operador_id,
                               o.guiche, 
                               o.nome,
                               o.perfil_id,
                               p.nome as perfil');
        $this->db->from('tb_operador o');
        $this->db->join('tb_perfil p', 'p.perfil_id = o.perfil_id', 'left');
        $this->db->where('o.ativo', 't');
        $this->db->where('o.guiche > 0');
        $this->db->orderby('o.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function listaroperadorguiche2($operador_id) {

        $operador_id = $this->session->userdata('operador_id');

        $this->db->select('    o.operador_id,
                               o.guiche, 
                               o.nome');
        $this->db->from('tb_operador o');
        $this->db->where('o.operador_id', $operador_id);

//        $this->db->orderby('o.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function listaroperador($operador) {
        $this->db->select('o.operador_id,
                               o.usuario,
                               o.nome,
                               o.perfil_id,
                               p.nome as perfil,
                               o.faixa_etaria,
                               o.faixa_etaria_final');
        $this->db->from('tb_operador o');
        $this->db->join('tb_perfil p', 'p.perfil_id = o.perfil_id', 'left');
        $this->db->where('o.operador_id', $operador);
        $this->db->orderby('o.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function verificarCodigoProf($conselho,$array,$chave) {
        $horario = date('Y-m-d H:i:s');
        $operador = $this->session->userdata('operador_id');
        
        $this->db->select('o.operador_id');
        $this->db->from('tb_operador o');
        $this->db->where('o.conselho', (string) $conselho);
        $this->db->orderby('o.nome');
//        $this->db->where('ativo','t');
        $return = $this->db->get()->result();
          
        if (count($return) > 0) {            
           return $return;
        }else{             
            $login1 = explode(" ", $array[1]);
            $login2 = $login1[0]."_".$login1[1];
            $this->db->set('operador_atualizacao',$operador);
            $this->db->set('data_cadastro',$horario);
            $this->db->set('nome',$array[1]);
            $this->db->set('conselho',$array[0]);
            $this->db->set('usuario',$login2);
            $this->db->set('senha',md5("123"));
            $this->db->set('perfil_id',4);
            $this->db->insert('tb_operador');
             
            $this->db->select('o.operador_id');
            $this->db->from('tb_operador o');
            $this->db->where('o.conselho', (string) $array[0]);
            $this->db->orderby('o.nome');
            $retorno = $this->db->get()->result();
             
            $this->db->set('operador_id',$retorno[0]->operador_id);
            $this->db->set('chave',$chave);
            $this->db->set('data_cadastro',$horario);
            $this->db->set('operador_cadastro',$operador);
            $this->db->insert('tb_medicos_importacao_add');
             
            return $retorno;
        }
       
        
    }

    function listaroperadoratendimento($operador) {
        $this->db->select('o.operador_id,
                               o.usuario,
                               o.atendimento_medico,
                               o.nome,
                               o.perfil_id,
                               p.nome as perfil');
        $this->db->from('tb_operador o');
        $this->db->join('tb_perfil p', 'p.perfil_id = o.perfil_id', 'left');
        $this->db->where('o.operador_id', $operador);
        $this->db->orderby('o.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function listaroperadorarray($operador) {
        $this->db->select('o.operador_id,
                               o.usuario,
                               o.nome,
                               o.perfil_id,
                               p.nome as perfil');
        $this->db->from('tb_operador o');
        $this->db->join('tb_perfil p', 'p.perfil_id = o.perfil_id', 'left');
        $this->db->where('o.ativo', 'true');
        // $this->db->where('o.medico', 'false');    
        // $this->db->where('o.solicitante', 'false');    

        if (count($operador) > 0) {

            $this->db->where_in('o.operador_id', $operador);
        }

        $this->db->orderby('o.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function listaradminitradores() {
        $this->db->select('o.operador_id,
                               o.usuario,
                               o.nome');
        $this->db->from('tb_operador o');
        $this->db->join('tb_perfil p', 'p.perfil_id = o.perfil_id', 'left');
        $this->db->where('p.perfil_id', 1);
        $this->db->where('o.ativo', 'true');
        $return = $this->db->get();
        return $return->result();
    }

    function listarteleoperadora() {
        $this->db->select('o.operador_id,
                               o.usuario,
                               o.nome,
                               o.conselho,
                               o.perfil_id,
                               p.nome as perfil');
        $this->db->from('tb_operador o');
        $this->db->join('tb_perfil p', 'p.perfil_id = o.perfil_id');
        $this->db->where('consulta', 'f');
        $this->db->where('o.ativo', 'true');
        $this->db->orderby('o.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function listarmedicos() {
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('o.operador_id,
                               o.usuario,
                               o.nome,
                               o.conselho,
                               o.perfil_id,
                               p.nome as perfil');
        $this->db->from('tb_operador o');
        $this->db->join('tb_perfil p', 'p.perfil_id = o.perfil_id');
        $this->db->join('tb_operador_empresas oe', 'oe.operador_id = o.operador_id');
        $this->db->where("(consulta = 'true' OR (consulta = 'true' and medico_cirurgiao = 'true') )");
        $this->db->where('o.ativo', 'true');
        $this->db->where('oe.ativo', 'true');
        $this->db->where('oe.empresa_id', $empresa_id);
        $this->db->orderby('o.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function listarmedicostodos() {
        $this->db->select('o.operador_id,
                               o.usuario,
                               o.nome,
                               o.conselho,
                               o.perfil_id,
                               p.nome as perfil');
        $this->db->from('tb_operador o');
        $this->db->join('tb_perfil p', 'p.perfil_id = o.perfil_id');
        $this->db->where('consulta', 'true');
        $this->db->where('o.ativo', 'true');
        // $this->db->where('o.medico_cirurgiao', 'false');
        $this->db->orderby('o.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function listarmedicocoordenador() {
        $this->db->select('o.operador_id,
                               o.usuario,
                               o.nome,
                               o.conselho,
                               o.perfil_id,
                               p.nome as perfil');
        $this->db->from('tb_operador o');
        $this->db->join('tb_perfil p', 'p.perfil_id = o.perfil_id');
        $this->db->where('consulta', 'true');
        $this->db->where('o.ativo', 'true');
        $this->db->orderby('o.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function listarmedicosespecialidade() {
        $this->db->select('o.operador_id,
                               o.usuario,
                               o.nome,
                               o.conselho,
                               o.perfil_id,
                               p.nome as perfil');
        $this->db->from('tb_operador o');
        $this->db->join('tb_perfil p', 'p.perfil_id = o.perfil_id');
        $this->db->where('consulta', 'true');
        $this->db->where('o.ativo', 'true');
        $this->db->where('o.usuario is not null');
        $this->db->orderby('o.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function listarmedicossolicitante() {
        $this->db->select('o.operador_id,
                               o.usuario,
                               o.nome,
                               o.perfil_id');
        $this->db->from('tb_operador o');
        $this->db->where("(solicitante = true OR medico = true)");
        $this->db->where('o.ativo', 'true');
        $this->db->orderby('o.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function listartecnicospercentual() {
        $this->db->select('o.operador_id,
                               o.usuario,
                               o.nome,
                               o.perfil_id,
                               p.nome as perfil');
        $this->db->from('tb_operador o');
        $this->db->join('tb_perfil p', 'p.perfil_id = o.perfil_id');
        // $this->db->where('consulta', 'false');
        $this->db->where('p.perfil_id IN (7, 15)');
        $this->db->where('o.ativo', 'true');
        $this->db->orderby('o.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function listartecnicos() {
        $this->db->select('o.operador_id,
                               o.usuario,
                               o.nome,
                               o.perfil_id,
                               p.nome as perfil');
        $this->db->from('tb_operador o');
        $this->db->join('tb_perfil p', 'p.perfil_id = o.perfil_id');
        $this->db->where('consulta', 'false');
        $this->db->where('medico', 'false');
        $this->db->where('o.ativo', 'true');
        $this->db->orderby('o.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function listarcbo($parametro = null) {
        $this->db->select('cbo_grupo_id,
                            descricao
                            ');
        if ($parametro != null) {
            $this->db->where('descricao ilike', $parametro . "%");
        }
        $return = $this->db->get('tb_cbo');
        return $return->result();
    }

    function medicocabecalhorodape($operador_id) {
        $this->db->select('o.nome,
                            o.operador_id,
                            o.rodape,
                            o.cabecalho,
                            o.cabecalho2,
                            o.rodape2,
                            c.descricao as ocupacao,
                            o.conselho,
                            o.curriculo
                            ');
        $this->db->from('tb_operador o');
        $this->db->join('tb_cbo_ocupacao c', 'c.cbo_ocupacao_id = o.cbo_ocupacao_id', 'left');
        $this->db->where('o.operador_id', $operador_id);
        $return = $this->db->get();
        return $return->result();
    }

    function carimbomedico($operador_id) {
        $this->db->select('o.nome,
                            o.operador_id,
                            o.rodape,
                            o.cabecalho,
                            o.cabecalho2,
                            o.rodape2,
                            c.descricao as ocupacao,
                            o.carimbo
                            ');
        $this->db->from('tb_operador o');
        $this->db->join('tb_cbo_ocupacao c', 'c.cbo_ocupacao_id = o.cbo_ocupacao_id', 'left');
        $this->db->where('o.operador_id', $operador_id);
        $return = $this->db->get();
        return $return->result();
    }

    function operadoratualsistema($operador_id) {
        $this->db->select('o.nome,
                            o.operador_id,
                            o.rodape,
                            o.usuario,
                            o.cabecalho,
                            o.cabecalho2,
                            o.rodape2,
                            c.descricao as ocupacao,
                            o.conselho
                            ');
        $this->db->from('tb_operador o');
        $this->db->join('tb_cbo_ocupacao c', 'c.cbo_ocupacao_id = o.cbo_ocupacao_id', 'left');
        $this->db->where('o.operador_id', $operador_id);
        $return = $this->db->get();
        return $return->result();
    }

    function medicoreceituario($operador_id) {
        $this->db->select('o.nome,
                            o.operador_id,
                            c.descricao as ocupacao,
                            o.conselho
                            ');
        $this->db->from('tb_operador o');
        $this->db->join('tb_cbo_ocupacao c', 'c.cbo_ocupacao_id = o.cbo_ocupacao_id', 'left');
        $this->db->where('o.operador_id', $operador_id);
        $return = $this->db->get();
        return $return->result();
    }

    function medicoenderecoweb($operador_id) {
        $this->db->select('o.nome,
                            o.operador_id,
                            o.endereco_sistema,
                            o.cpf,
                            c.descricao as ocupacao,
                            o.conselho
                            ');
        $this->db->from('tb_operador o');
        $this->db->join('tb_cbo_ocupacao c', 'c.cbo_ocupacao_id = o.cbo_ocupacao_id', 'left');
        $this->db->where('o.operador_id', $operador_id);
        $return = $this->db->get();
        return $return->result();
    }

    function relatorioemailoperador() {
        $this->db->select('o.operador_id,
                               o.usuario,
                               o.nome,
                               o.email,
                               o.perfil_id,
                               p.nome as perfil,
                               o.nascimento,
                               o.cpf,
                               o.telefone,
                               o.celular,
                               c.descricao as cbo');
        $this->db->from('tb_operador o');
        $this->db->join('tb_perfil p', 'p.perfil_id = o.perfil_id');
        $this->db->join('tb_cbo_ocupacao c', 'c.cbo_ocupacao_id = o.cbo_ocupacao_id');
        $this->db->where('o.ativo', 'true');
        $this->db->where('o.email !=', '');
        $this->db->orderby('o.nome');
        if ($_POST['perfil'] > 0) {
            $this->db->where('o.perfil_id', $_POST['perfil']);
        }

        $return = $this->db->get();
        return $return->result();
    }

    function listarempresas() {
        $this->db->select('empresa_id,
                            nome');
        $this->db->from('tb_empresa');
        $this->db->where('ativo', 't');
        $return = $this->db->get();

        return $return->result();
    }

    function listarempresasoperador($operador) {
        $this->db->select('oe.operador_empresa_id,
                            oe.empresa_id,
                            oe.operador_id,
                            e.nome');
        $this->db->from('tb_operador_empresas oe');
        $this->db->join('tb_empresa e', 'e.empresa_id = oe.empresa_id', 'left');
        $this->db->where('operador_id', $operador);
        $this->db->where('oe.ativo', 't');
        $this->db->where('e.ativo', 't');

        $return = $this->db->get();

        return $return->result();
    }

    function senhasretiradas() {
        try {
            
            $this->db->select('sh.senha as senha');
            $this->db->from('tb_toten_senha sh');
            // $this->db->where('atendida', 't');
            $this->db->groupby('senha');
            // $this->db->orderby('toten_senha_id desc');
            $return = $this->db->get()->result();
            return $return;
        } catch (Exception $ex) {
            return false;
        }
    }

    function listardatahorariosenha($id) {
        try {
            
            $this->db->select('sh.data');
            $this->db->from('tb_senha_horario sh');
            $this->db->where('senha_id', $id);
            $return = $this->db->get()->result();
            return $return;
        } catch (Exception $ex) {
            return false;
        }
    }

    function gravarsenhahorario($array) {
        try {
            foreach ($array as $key => $value) {
                $this->db->select('sh.senha_horario_id');
                $this->db->from('tb_senha_horario sh');
                $this->db->where('senha_id', $value->id);
                $return = $this->db->get()->result();
                if (count($return) == 0) {
                    $this->db->set('senha_id', $value->id);
                    $this->db->set('nome', $value->senha);
                    $this->db->set('data', date("Y-m-d H:i:s"));
                    $this->db->insert('tb_senha_horario');
                }
            }
        } catch (Exception $ex) {
            return false;
        }
    }

    function gravarassociarempresas() {
        try {
            $this->db->select('oe.operador_empresa_id');
            $this->db->from('tb_operador_empresas oe');
            $this->db->where('oe.ativo', 't');
            $this->db->where('operador_id', $_POST['txtoperador_id']);
            $this->db->where('empresa_id', $_POST['empresa_id']);
            $return = $this->db->get()->result();
            if (count($return) == 0) {
                $this->db->set('operador_id', $_POST['txtoperador_id']);
                $this->db->set('empresa_id', $_POST['empresa_id']);
                $this->db->insert('tb_operador_empresas');
                return 20;
            } else {
                return 10;
            }
        } catch (Exception $ex) {
            return false;
        }
    }

    function excluirassociarempresas($operador_empresa_id) {
        try {
            $this->db->set('ativo', 'f');
            $this->db->where('operador_empresa_id', $operador_empresa_id);
            $this->db->update('tb_operador_empresas');

            $erro = $this->db->_error_message();
            if (trim($erro) != "") { // erro de banco
                return false;
            } else {
                return true;
            }
        } catch (Exception $ex) {
            return false;
        }
    }

    function listarPerfil() {
        $this->db->select('perfil_id,
                               nome,
                               ');
        $this->db->from('tb_perfil');
        $this->db->where('ativo', 't');
        $this->db->orderby('nome');

        $return = $this->db->get();
        return $return->result();
    }

    function listaragendatelefonicaeditar($agenda_telefonica_id) {
        $this->db->select('agenda_telefonica_id,
                               nome,
                               telefone1,
                               telefone2,
                               telefone3,
                               email,
                               observacao
                               ');
        $this->db->from('tb_agenda_telefonica');
        $this->db->where('ativo', 't');
        $this->db->where('agenda_telefonica_id', $agenda_telefonica_id);
        $this->db->orderby('nome');

        $return = $this->db->get();
        return $return->result();
    }

    function listarcpfcontador() {
        $this->db->select('cpf
                               ');
        $this->db->from('tb_operador');
        $this->db->where('cpf', str_replace("-", "", str_replace(".", "", $_POST['cpf'])));
//        $this->db->where('ativo', 't');

        $return = $this->db->count_all_results();
        return $return;
    }

    function listarusuariocontador() {
        $this->db->select('usuario
                               
                               ');
        $this->db->from('tb_operador');
        $this->db->where('usuario', $_POST['txtUsuario']);
        $this->db->where('ativo', 't');

        $return = $this->db->count_all_results();
        return $return;
    }

    function gravar() {
        try {

            $result = array();
            $this->db->select('financeiro_credor_devedor_id')->from('tb_financeiro_credor_devedor');
            $this->db->where('cpf', str_replace("-", "", str_replace(".", "", $_POST['cpf'])));
            $this->db->where('ativo', 't');
            $result = $this->db->get()->result();

            if (count($result) == 0) {
                $this->db->set('razao_social', $_POST['nome']);
                $this->db->set('cep', $_POST['cep']);
                if ($_POST['cpf'] != '') {
                    $this->db->set('cpf', str_replace("-", "", str_replace(".", "", $_POST['cpf'])));
                } else {
                    $this->db->set('cpf', null);
                }
                $this->db->set('telefone', str_replace("(", "", str_replace(")", "", str_replace("-", "", $_POST['telefone']))));
                $this->db->set('celular', str_replace("(", "", str_replace(")", "", str_replace("-", "", $_POST['celular']))));
                if ($_POST['tipo_logradouro'] != '') {
                    $this->db->set('tipo_logradouro_id', $_POST['tipo_logradouro']);
                }
                if ($_POST['municipio_id'] != '') {
                    $this->db->set('municipio_id', $_POST['municipio_id']);
                }
                $this->db->set('logradouro', $_POST['endereco']);
                $this->db->set('numero', $_POST['numero']);
                $this->db->set('bairro', $_POST['bairro']);
                $this->db->set('complemento', $_POST['complemento']);
                $horario = date("Y-m-d H:i:s");
                $operador_id = $this->session->userdata('operador_id');
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_financeiro_credor_devedor');
                $financeiro_credor_devedor_id = $this->db->insert_id();
            } else {
                $financeiro_credor_devedor_id = $result[0]->financeiro_credor_devedor_id;
            }


            if ($_POST['txtcolor'] != '' && $_POST['txtcolor'] != '#000000') {
                $this->db->set('cor_mapa', $_POST['txtcolor']);
            }

            /* inicia o mapeamento no banco */
            $this->db->set('nome', $_POST['nome']);
            $this->db->set('sexo', $_POST['sexo']);
            $this->db->set('carimbo', $_POST['carimbo']);
            $this->db->set('curriculo', $_POST['curriculo']);
            $this->db->set('endereco_sistema', @$_POST['endereco_sistema']);
            $this->db->set('link_reuniao', @$_POST['link_reuniao']);
            $this->db->set('coragenda', @$_POST['coragenda']);
            
            if(isset($_POST['faixa_etaria']) && $_POST['faixa_etaria'] > 0){
                $this->db->set('faixa_etaria', $_POST['faixa_etaria']);           
            }else{
                $this->db->set('faixa_etaria', null);   
            }
            
            if(isset($_POST['faixa_etaria_final']) && $_POST['faixa_etaria_final'] > 0){
                $this->db->set('faixa_etaria_final', $_POST['faixa_etaria_final']);           
            }else{
                $this->db->set('faixa_etaria_final', null);   
            }
            
            // $this->db->set('certificado_digital', @$_POST['txtcertificado']);
            $this->db->set('senha_cert', @$_POST['senha_cert']);
            $this->db->set('taxaadm_perc', @$_POST['taxaadm_perc']);
            if(count($_POST['grupo_agenda']) > 0){
                $this->db->set('grupo_agenda', json_encode($_POST['grupo_agenda']));
            }else{
                $this->db->set('grupo_agenda', null);
            }
            
            if ($_POST['nascimento'] != '')
                $this->db->set('nascimento', substr($_POST['nascimento'], 6, 4) . '-' . substr($_POST['nascimento'], 3, 2) . '-' . substr($_POST['nascimento'], 0, 2));
            else
                $this->db->set('nascimento', null);
            $this->db->set('conselho', $_POST['conselho']);
            if ($_POST['cpf'] != '') {
                $this->db->set('cpf', str_replace("-", "", str_replace(".", "", $_POST['cpf'])));
            } else {
                $this->db->set('cpf', null);
            }

            if (@$_POST['siglaconselho'] != '') {
                $this->db->set('sigla_id', $_POST['siglaconselho']);
            } else {
                $this->db->set('sigla_id', null);
            }
            if (@$_POST['uf_profissional'] != '') {
                $this->db->set('uf_profissional', $_POST['uf_profissional']);
            } else {
                $this->db->set('uf_profissional', null);
            }

            if ($_POST['tipo_logradouro'] != "") {
                $this->db->set('tipo_logradouro', $_POST['tipo_logradouro']);
            }
            if (@$_POST['profissional_agendar_o'] != "") {
                $this->db->set('profissional_agendar_o', 't');
            } else {
                $this->db->set('profissional_agendar_o', 'f');
            }

            if (@$_POST['medico_cirurgiao'] != "") {
                $this->db->set('medico_cirurgiao', 't');
            } else {
                $this->db->set('medico_cirurgiao', 'f');
            }

            if (@$_POST['atendimento_medico'] != "") {
                $this->db->set('atendimento_medico', 't');
            } else {
                $this->db->set('atendimento_medico', 'f');
            }

            $this->db->set('logradouro', $_POST['endereco']);
            $this->db->set('numero', $_POST['numero']);
            $this->db->set('bairro', $_POST['bairro']);
            $this->db->set('complemento', $_POST['complemento']);
            if ($_POST['municipio_id'] != null)
                $this->db->set('municipio_id', $_POST['municipio_id']);
            else
                $this->db->set('municipio_id', null);
            $this->db->set('cep', $_POST['cep']);
            $this->db->set('celular', str_replace("(", "", str_replace(")", "", str_replace("-", "", $_POST['celular']))));
            $this->db->set('telefone', str_replace("(", "", str_replace(")", "", str_replace("-", "", $_POST['telefone']))));
            $this->db->set('email', $_POST['email']);

            if ($_POST['piso_medico'] != "") {
                $this->db->set('piso_medico', str_replace(",", ".", str_replace(".", "", $_POST['piso_medico'])));
            }

            if ($_POST['taxaadm'] != "") {
                $this->db->set('taxa_administracao', str_replace(",", ".", str_replace(".", "", $_POST['taxaadm'])));
            }
            if ($_POST['ir'] != "") {
                $this->db->set('ir', str_replace(",", ".", $_POST['ir']));
            }
            if ($_POST['pis'] != "") {
                $this->db->set('pis', str_replace(",", ".", $_POST['pis']));
            }
            if ($_POST['cofins'] != "") {
                $this->db->set('cofins', str_replace(",", ".", $_POST['cofins']));
            }
            if ($_POST['csll'] != "") {
                $this->db->set('csll', str_replace(",", ".", $_POST['csll']));
            }
            if ($_POST['iss'] != "") {
                $this->db->set('iss', str_replace(",", ".", $_POST['iss']));
            }
            if ($_POST['valor_base'] != "") {
                $this->db->set('valor_base', str_replace(",", ".", $_POST['valor_base']));
            }



            if (@$_POST['seg_manha'] != "") {
                $this->db->set('seg_manha', str_replace(",", ".", @$_POST['seg_manha']));
            } else {
                $this->db->set('seg_manha', '00:00');
            }
            if (@$_POST['seg_tarde'] != "") {
                $this->db->set('seg_tarde', str_replace(",", ".", @$_POST['seg_tarde']));
            } else {
                $this->db->set('seg_tarde', '00:00');
            }
            if (@$_POST['ter_manha'] != "") {
                $this->db->set('ter_manha', str_replace(",", ".", @$_POST['ter_manha']));
            } else {
                $this->db->set('ter_manha', '00:00');
            }
            if (@$_POST['ter_tarde'] != "") {
                $this->db->set('ter_tarde', str_replace(",", ".", @$_POST['ter_tarde']));
            } else {
                $this->db->set('ter_tarde', '00:00');
            }


            if (@$_POST['qua_manha'] != "") {
                $this->db->set('qua_manha', str_replace(",", ".", @$_POST['qua_manha']));
            } else {
                $this->db->set('qua_manha', '00:00');
            }

            if (@$_POST['qua_tarde'] != "") {
                $this->db->set('qua_tarde', str_replace(",", ".", @$_POST['qua_tarde']));
            } else {
                $this->db->set('qua_tarde', '00:00');
            }


            if (@$_POST['qui_manha'] != "") {
                $this->db->set('qui_manha', str_replace(",", ".", @$_POST['qui_manha']));
            } else {
                $this->db->set('qui_manha', '00:00');
            }

            if (@$_POST['qui_tarde'] != "") {
                $this->db->set('qui_tarde', str_replace(",", ".", @$_POST['qui_tarde']));
            } else {
                $this->db->set('qui_tarde', '00:00');
            }
            if (@$_POST['sex_manha'] != "") {
                $this->db->set('sex_manha', str_replace(",", ".", @$_POST['sex_manha']));
            } else {
                $this->db->set('sex_manha', '00:00');
            }

            if (@$_POST['sex_tarde'] != "") {
                $this->db->set('sex_tarde', str_replace(",", ".", @$_POST['sex_tarde']));
            } else {
                $this->db->set('sex_tarde', '00:00');
            }

            if (@$_POST['sab_manha'] != "") {
                $this->db->set('sab_manha', str_replace(",", ".", @$_POST['sab_manha']));
            } else {
                $this->db->set('sab_manha', '00:00');
            }

            if (@$_POST['sab_tarde'] != "") {
                $this->db->set('sab_tarde', str_replace(",", ".", @$_POST['sab_tarde']));
            } else {
                $this->db->set('sab_tarde', '00:00');
            }





            if (@$_POST['hora_manha'] != "") {
                $this->db->set('hora_manha', str_replace(",", ".", $_POST['hora_manha']));
            } else {
                $this->db->set('hora_manha', '00:00');
            }



            if (@$_POST['hora_tarde'] != "") {
                $this->db->set('hora_tarde', str_replace(",", ".", $_POST['hora_tarde']));
            } else {
                $this->db->set('hora_tarde', '00:00');
            }

            if ($_POST['conta'] != "") {
                $this->db->set('conta_id', $_POST['conta']);
            }
            if (isset($financeiro_credor_devedor_id) && @$financeiro_credor_devedor_id != '') {
                $this->db->set('credor_devedor_id', $financeiro_credor_devedor_id);
            }

            $this->db->set('cabecalho', $_POST['cabecalho']);
            $this->db->set('rodape', $_POST['rodape']);

            $this->db->set('cabecalho2', $_POST['cabecalho2']);
            $this->db->set('rodape2', $_POST['rodape2']);

            $this->db->set('timbrado', $_POST['timbrado']);

            $this->db->set('classe', $_POST['classe']);
            $this->db->set('tipo_id', $_POST['tipo']);
            if (@$_POST['txtconsulta'] != null) {
                $this->db->set('consulta', $_POST['txtconsulta']);
                $this->db->set('medico', 't');
            }
            if (@$_POST['txtconsulta'] == null) {
                $this->db->set('consulta', 'f');
                $this->db->set('medico', 'f');
            }

            if (isset($_POST['txtsolicitante'])) {
                $this->db->set('solicitante', 't');
            } else {
                $this->db->set('solicitante', 'f');
            }

            if (isset($_POST['ocupacao_painel'])) {
                $this->db->set('ocupacao_painel', 't');
            } else {
                $this->db->set('ocupacao_painel', 'f');
            }

            if ($_POST['txtcboID'] != "" && $_POST['txtcbo'] != "") {
                $this->db->set('cbo_ocupacao_id', $_POST['txtcboID']);
            } elseif ($_POST['txtcbo'] == "") {
                $this->db->set('cbo_ocupacao_id', null);
            }

            $this->db->set('usuario', $_POST['txtUsuario']);
            if ($_POST['txtSenha'] != "") {
                $this->db->set('senha', md5($_POST['txtSenha']));
            }
            if ($_POST['txtPerfil'] != "") {
                $this->db->set('perfil_id', $_POST['txtPerfil']);
            }

            if (@$_POST['cod_cnes_prof'] != "") {
                $this->db->set('cod_cnes_prof', @$_POST['cod_cnes_prof']);
            }

            if (isset($_POST['medico_agenda'])) {
                $this->db->set('medico_agenda', 't');
            } else {
                $this->db->set('medico_agenda', 'f');
            }

            if (isset($_POST['profissional_aluguel'])) {
                $this->db->set('profissional_aluguel', 't');
            } else {
                $this->db->set('profissional_aluguel', 'f');
            }
             
            if ($_POST['desconto_seguro'] != "") {
                $this->db->set('desconto_seguro',  str_replace(",", ".", str_replace(".", "", $_POST['desconto_seguro'])));
            } else {
                $this->db->set('desconto_seguro', null);
            }
             
            if ($_POST['tipo_desc_seguro'] != "") {
                $this->db->set('tipo_desc_seguro', $_POST['tipo_desc_seguro']);
            } else {
                $this->db->set('tipo_desc_seguro', null);
            }
            if ($_POST['txtConta']  != "") {
                $this->db->set('conta',$_POST['txtConta']);
            }else{
                $this->db->set('conta', null);
            }
            
            if ($_POST['txtAgencia']  != "") {
                $this->db->set('agencia',$_POST['txtAgencia']);
            }else{
                $this->db->set('agencia', null);
            }
            
            if(isset($_POST['qtd_retorno_dia']) && $_POST['qtd_retorno_dia'] != ""){
               $this->db->set('qtd_retorno_dia', @$_POST['qtd_retorno_dia']);
            }else{
               $this->db->set('qtd_retorno_dia', null);
            }
            
            
            $this->db->set('ativo', 't');
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            if ($_POST['operador_id'] == "") {// insert
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_operador');
                $operador_id = $this->db->insert_id();
                $empresa_id = $this->session->userdata('empresa_id');


                $this->db->select('empresa_id
                               
                               ');
                $this->db->from('tb_empresa');
                $this->db->where('ativo', 't');
                $return = $this->db->get()->result();

                if (count($return) > 0) {
                    foreach ($return as $value) {
                        $this->db->set('operador_id', $operador_id);
                        $this->db->set('empresa_id', $value->empresa_id);
                        $this->db->insert('tb_operador_empresas');
                    }
                }



                $erro = $this->db->_error_message();
                if (trim($erro) != "") { // erro de banco
                    return false;
                } else
                if ($_POST['txtcboID'] != "") {
                    $operador_id = $this->db->insert_id();
                    $this->db->set('operador_id', $operador_id);
                    $this->db->set('cbo_ocupacao_id', $_POST['txtcboID']);
                    $this->db->insert('tb_operador_cbo');
                }
                return $operador_id;
            } else { // update
                $operador_id = $_POST['operador_id'];
//                var_dump($horario); die;
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $this->db->where('operador_id', $operador_id);
                $this->db->update('tb_operador');
                if ($_POST['txtcboID'] != "") {
                    $this->db->set('cbo_ocupacao_id', $_POST['txtcboID']);
                    $this->db->where('operador_id', $operador_id);
                    $this->db->update('tb_operador_cbo');
                }
            }
            $erro = $this->db->_error_message();
            if (trim($erro) != "") { // erro de banco
                return false;
            } else {
                return true;
            }
        } catch (Exception $exc) {
            return false;
        }
    }

    function gravar_pessoal(){
        $this->db->set('nome', $_POST['nome']);
        $this->db->set('sexo', $_POST['sexo']);
        if ($_POST['nascimento'] != '')
        $this->db->set('nascimento', substr($_POST['nascimento'], 6, 4) . '-' . substr($_POST['nascimento'], 3, 2) . '-' . substr($_POST['nascimento'], 0, 2));
        else
        $this->db->set('nascimento', null);
        $this->db->set('conselho', $_POST['conselho']);
        if ($_POST['cpf'] != '') {
        $this->db->set('cpf', str_replace("-", "", str_replace(".", "", $_POST['cpf'])));
        } else {
        $this->db->set('cpf', null);
        }
        if ($_POST['txtcolor'] != '' && $_POST['txtcolor'] != '#000000') {
            $this->db->set('cor_mapa', $_POST['txtcolor']);
        }
        $this->db->set('logradouro', $_POST['endereco']);
        $this->db->set('numero', $_POST['numero']);
        $this->db->set('bairro', $_POST['bairro']);
        $this->db->set('complemento', $_POST['complemento']);
            if ($_POST['municipio_id'] != null)
                $this->db->set('municipio_id', $_POST['municipio_id']);
            else
        $this->db->set('municipio_id', null);
        $this->db->set('cep', $_POST['cep']);
        $this->db->set('celular', str_replace("(", "", str_replace(")", "", str_replace("-", "", $_POST['celular']))));
        $this->db->set('telefone', str_replace("(", "", str_replace(")", "", str_replace("-", "", $_POST['telefone']))));
        $this->db->set('email', $_POST['email']);

        $this->db->set('usuario', $_POST['txtUsuario']);
            if ($_POST['txtSenha'] != "") {
                $this->db->set('senha', md5($_POST['txtSenha']));
            }

        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $_POST['operador_id']);
        $this->db->where('operador_id', $_POST['operador_id']);
        $this->db->update('tb_operador');
            if ($_POST['txtcboID'] != "") {
                    $this->db->set('cbo_ocupacao_id', $_POST['txtcboID']);
                    $this->db->where('operador_id', $_POST['operador_id']);
                    $this->db->update('tb_operador_cbo');
                }

                return true;
    }

    function operadorlog($operador_id){
        $this->db->select('c.nome, o.nome as operador_cadastro,
                           c.data_cadastro, 
                           oa.nome as operador_atualizacao,
                           c.data_atualizacao');
        $this->db->from('tb_operador c');
        $this->db->join('tb_operador o', 'o.operador_id = c.operador_cadastro', 'left');
        $this->db->join('tb_operador oa', 'oa.operador_id = c.operador_atualizacao', 'left');
        $this->db->where('c.operador_id', $operador_id);
        $return = $this->db->get();
        $retorno = $return->result();

        return $retorno;
    }

    function gravarconfirmarprecadastro($precadastro_id) {
        try {

            $result = array();
            $this->db->select('financeiro_credor_devedor_id')->from('tb_financeiro_credor_devedor');
            $this->db->where('cpf', str_replace("-", "", str_replace(".", "", $_POST['cpf'])));
            $this->db->where('ativo', 't');
            $result = $this->db->get()->result();

            if (count($result) == 0) {
                $this->db->set('razao_social', $_POST['nome']);
                $this->db->set('cep', $_POST['cep']);
                if ($_POST['cpf'] != '') {
                    $this->db->set('cpf', str_replace("-", "", str_replace(".", "", $_POST['cpf'])));
                } else {
                    $this->db->set('cpf', null);
                }
                $this->db->set('telefone', str_replace("(", "", str_replace(")", "", str_replace("-", "", $_POST['telefone']))));
                $this->db->set('celular', str_replace("(", "", str_replace(")", "", str_replace("-", "", $_POST['celular']))));
                if ($_POST['tipo_logradouro'] != '') {
                    $this->db->set('tipo_logradouro_id', $_POST['tipo_logradouro']);
                }
                if ($_POST['municipio_id'] != '') {
                    $this->db->set('municipio_id', $_POST['municipio_id']);
                }
                $this->db->set('logradouro', $_POST['endereco']);
                $this->db->set('numero', $_POST['numero']);
                $this->db->set('bairro', $_POST['bairro']);
                $this->db->set('complemento', $_POST['complemento']);
                $horario = date("Y-m-d H:i:s");
                $operador_id = $this->session->userdata('operador_id');
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_financeiro_credor_devedor');
                $financeiro_credor_devedor_id = $this->db->insert_id();
            } else {
                $financeiro_credor_devedor_id = $result[0]->financeiro_credor_devedor_id;
            }

            if ($_POST['txtcolor'] != '' && $_POST['txtcolor'] != '#000000') {
                $this->db->set('cor_mapa', $_POST['txtcolor']);
            }

            /* inicia o mapeamento no banco */
            $this->db->set('pacientes_precadastro_id', $precadastro_id);
            $this->db->set('nome', $_POST['nome']);
            $this->db->set('sexo', $_POST['sexo']);
            // $this->db->set('carimbo', $_POST['carimbo']);
            if ($_POST['nascimento'] != '')
                $this->db->set('nascimento', substr($_POST['nascimento'], 6, 4) . '-' . substr($_POST['nascimento'], 3, 2) . '-' . substr($_POST['nascimento'], 0, 2));
            else
                $this->db->set('nascimento', null);
            $this->db->set('conselho', $_POST['conselho']);
            if ($_POST['cpf'] != '') {
                $this->db->set('cpf', str_replace("-", "", str_replace(".", "", $_POST['cpf'])));
            } else {
                $this->db->set('cpf', null);
            }

            if (@$_POST['siglaconselho'] != '') {
                $this->db->set('sigla_id', $_POST['siglaconselho']);
            } else {
                $this->db->set('sigla_id', null);
            }
            if (@$_POST['uf_profissional'] != '') {
                $this->db->set('uf_profissional', $_POST['uf_profissional']);
            } else {
                $this->db->set('uf_profissional', null);
            }

            if ($_POST['tipo_logradouro'] != "") {
                $this->db->set('tipo_logradouro', $_POST['tipo_logradouro']);
            }

            $this->db->set('atendimento_medico', 't');

            $this->db->set('logradouro', $_POST['endereco']);
            $this->db->set('numero', $_POST['numero']);
            $this->db->set('bairro', $_POST['bairro']);
            $this->db->set('complemento', $_POST['complemento']);
            if ($_POST['municipio_id'] != null)
                $this->db->set('municipio_id', $_POST['municipio_id']);
            else
                $this->db->set('municipio_id', null);
            $this->db->set('cep', $_POST['cep']);
            $this->db->set('celular', str_replace("(", "", str_replace(")", "", str_replace("-", "", $_POST['celular']))));
            $this->db->set('telefone', str_replace("(", "", str_replace(")", "", str_replace("-", "", $_POST['telefone']))));
            $this->db->set('email', $_POST['email']);

            if (isset($financeiro_credor_devedor_id) && @$financeiro_credor_devedor_id != '') {
                $this->db->set('credor_devedor_id', $financeiro_credor_devedor_id);
            }

            $this->db->set('consulta', 't');
            $this->db->set('medico', 't');

            if ($_POST['txtcboID'] != "" && $_POST['txtcbo'] != "") {
                $this->db->set('cbo_ocupacao_id', $_POST['txtcboID']);
            } elseif ($_POST['txtcbo'] == "") {
                $this->db->set('cbo_ocupacao_id', null);
            }
            $this->db->set('usuario', $_POST['txtUsuario']);
            if ($_POST['txtSenha'] != "") {
                $this->db->set('senha', md5($_POST['txtSenha']));
            }
            if ($_POST['txtPerfil'] != "") {
                $this->db->set('perfil_id', $_POST['txtPerfil']);
            }

            $this->db->set('ativo', 't');
            $horario = date("Y-m-d H:i:s");


            if ($_POST['operador_id'] == "") {// insert
                $this->db->set('data_cadastro', $horario);
                // $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_operador');
                $operador_id = $this->db->insert_id();

                $this->db->select('empresa_id
                               
                               ');
                $this->db->from('tb_empresa');
                $this->db->where('ativo', 't');
                $return = $this->db->get()->result();

                if (count($return) > 0) {
                    foreach ($return as $value) {
                        $this->db->set('operador_id', $operador_id);
                        $this->db->set('empresa_id', $value->empresa_id);
                        $this->db->insert('tb_operador_empresas');
                    }
                }

                $erro = $this->db->_error_message();
                if (trim($erro) != "") { // erro de banco
                    return false;
                } else
                if ($_POST['txtcboID'] != "") {
                    $operador_id = $this->db->insert_id();
                    $this->db->set('operador_id', $operador_id);
                    $this->db->set('cbo_ocupacao_id', $_POST['txtcboID']);
                    $this->db->insert('tb_operador_cbo');
                }
                $this->db->set('cadastrado', 't');
                $this->db->where('pacientes_precadastro_id', $precadastro_id);
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_id',$operador_id);
                // $this->db->set('operador_atualizacao', $operador);
                $this->db->update('tb_pacientes_precadastro');
                return $operador_id;
            }
        } catch (Exception $exc) {
            return false;
        }
    }

    function listarprecadastroinfo($pacientes_precadastro_id) {
        $this->db->select('m.nome as municipio, m.municipio_id, pp.*');
        $this->db->from('tb_pacientes_precadastro pp');
        $this->db->join('tb_municipio m', 'm.municipio_id = pp.municipio_id', 'left');
        $this->db->where('pp.pacientes_precadastro_id', $pacientes_precadastro_id);
        $this->db->where('ativo', 't');
        return $this->db->get()->result();
    }

    function gravarfinanceiro() {
        try {

            if (@$_POST['criarcredor'] == "on") {
                $this->db->set('razao_social', $_POST['nome']);
                $this->db->set('cep', $_POST['cep']);
                if ($_POST['cpf'] != '') {
                    $this->db->set('cpf', str_replace("-", "", str_replace(".", "", $_POST['cpf'])));
                } else {
                    $this->db->set('cpf', null);
                }
                $this->db->set('telefone', str_replace("(", "", str_replace(")", "", str_replace("-", "", $_POST['telefone']))));
                $this->db->set('celular', str_replace("(", "", str_replace(")", "", str_replace("-", "", $_POST['celular']))));
                if ($_POST['tipo_logradouro'] != '') {
                    $this->db->set('tipo_logradouro_id', $_POST['tipo_logradouro']);
                }
                if ($_POST['municipio_id'] != '') {
                    $this->db->set('municipio_id', $_POST['municipio_id']);
                }
                $this->db->set('logradouro', $_POST['endereco']);
                $this->db->set('numero', $_POST['numero']);
                $this->db->set('bairro', $_POST['bairro']);
                $this->db->set('complemento', $_POST['complemento']);
                $horario = date("Y-m-d H:i:s");
                $operador_id = $this->session->userdata('operador_id');
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_financeiro_credor_devedor');
                $financeiro_credor_devedor_id = $this->db->insert_id();
            }

            if ($_POST['taxaadm'] != "") {
                $this->db->set('taxa_administracao', str_replace(",", ".", $_POST['taxaadm']));
            }
            if ($_POST['ir'] != "") {
                $this->db->set('ir', str_replace(",", ".", $_POST['ir']));
            }
            if ($_POST['pis'] != "") {
                $this->db->set('pis', str_replace(",", ".", $_POST['pis']));
            }
            if ($_POST['cofins'] != "") {
                $this->db->set('cofins', str_replace(",", ".", $_POST['cofins']));
            }
            if ($_POST['csll'] != "") {
                $this->db->set('csll', str_replace(",", ".", $_POST['csll']));
            }
            if ($_POST['iss'] != "") {
                $this->db->set('iss', str_replace(",", ".", $_POST['iss']));
            }
            if ($_POST['valor_base'] != "") {
                $this->db->set('valor_base', str_replace(",", ".", $_POST['valor_base']));
            }
            if ($_POST['conta'] != "") {
                $this->db->set('conta_id', $_POST['conta']);
            }
            if (@$_POST['criarcredor'] == "on") {
                $this->db->set('credor_devedor_id', $financeiro_credor_devedor_id);
            } elseif ($_POST['credor_devedor'] != "") {
                $this->db->set('credor_devedor_id', $_POST['credor_devedor']);
            }


            $this->db->set('classe', $_POST['classe']);
            $this->db->set('tipo_id', $_POST['tipo']);

            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            if ($_POST['operador_id'] == "") {// insert
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_operador');
                $operador_id = $this->db->insert_id();
                $empresa_id = $this->session->userdata('empresa_id');


                $this->db->select('empresa_id
                               
                               ');
                $this->db->from('tb_empresa');
                $this->db->where('ativo', 't');
                $return = $this->db->get()->result();

                if (count($return) > 0) {
                    foreach ($return as $value) {
                        $this->db->set('operador_id', $operador_id);
                        $this->db->set('empresa_id', $value->empresa_id);
                        $this->db->insert('tb_operador_empresas');
                    }
                }



                $erro = $this->db->_error_message();
                if (trim($erro) != "") { // erro de banco
                    return false;
                }

                return $operador_id;
            } else { // update
                $operador_id = $_POST['operador_id'];
//                var_dump($horario); die;
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $this->db->where('operador_id', $operador_id);
                $this->db->update('tb_operador');
            }
            $erro = $this->db->_error_message();
            if (trim($erro) != "") { // erro de banco
                return false;
            } else {
                return true;
            }
        } catch (Exception $exc) {
            return false;
        }
    }

    function gravarrecepcao() {
        try {
            if ($_POST['criarcredor'] == "on") {

                $this->db->set('razao_social', $_POST['nome']);
                $this->db->set('cep', $_POST['cep']);
                if ($_POST['cpf'] != '') {
                    $this->db->set('cpf', str_replace("-", "", str_replace(".", "", $_POST['cpf'])));
                } else {
                    $this->db->set('cpf', null);
                }
                $this->db->set('telefone', str_replace("(", "", str_replace(")", "", str_replace("-", "", $_POST['telefone']))));
                $this->db->set('celular', str_replace("(", "", str_replace(")", "", str_replace("-", "", $_POST['celular']))));
                if ($_POST['tipo_logradouro'] != '') {
                    $this->db->set('tipo_logradouro_id', $_POST['tipo_logradouro']);
                }
                if ($_POST['municipio_id'] != '') {
                    $this->db->set('municipio_id', $_POST['municipio_id']);
                }
                $this->db->set('logradouro', $_POST['endereco']);
                $this->db->set('numero', $_POST['numero']);
                $this->db->set('bairro', $_POST['bairro']);
                $this->db->set('complemento', $_POST['complemento']);
                $horario = date("Y-m-d H:i:s");
                $operador_id = $this->session->userdata('operador_id');
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_financeiro_credor_devedor');
                $financeiro_credor_devedor_id = $this->db->insert_id();
            }

            /* inicia o mapeamento no banco */
            $this->db->set('nome', $_POST['nome']);
            $this->db->set('sexo', $_POST['sexo']);
            $this->db->set('conselho', $_POST['conselho']);
            $this->db->set('ativo', 't');
            // $this->db->set('uf_conselho', $_POST['uf_conselho']);
            if (@$_POST['siglaconselho'] != '') {
                $this->db->set('sigla_id', $_POST['siglaconselho']);
            } else {
                $this->db->set('sigla_id', null);
            }
            if (@$_POST['uf_profissional'] != '') {
                $this->db->set('uf_profissional', $_POST['uf_profissional']);
            } else {
                $this->db->set('uf_profissional', null);
            }
            if ($_POST['criarcredor'] == "on") {
                $this->db->set('credor_devedor_id', $financeiro_credor_devedor_id);
            } elseif ($_POST['credor_devedor'] != "") {
                $this->db->set('credor_devedor_id', $_POST['credor_devedor']);
            }
            if ($_POST['txtcboID'] != "") {
                $this->db->set('cbo_ocupacao_id', $_POST['txtcboID']);
            }
            $this->db->set('medico', 't');
            $this->db->set('solicitante', 't');
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            if ($_POST['operador_id'] == "") {// insert
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_operador');
                $erro = $this->db->_error_message();
                if (trim($erro) != "") { // erro de banco
                    return false;
                } else
                if ($_POST['txtcboID'] != "") {
                    $operador_id = $this->db->insert_id();
                    $this->db->set('operador_id', $operador_id);
                    $this->db->set('cbo_ocupacao_id', $_POST['txtcboID']);
                    $this->db->insert('tb_operador_cbo');
                }
            } else { // update
                $operador_id = $_POST['operador_id'];
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $this->db->where('operador_id', $operador_id);
                $this->db->update('tb_operador');
                if ($_POST['txtcboID'] != "") {
                    $this->db->set('cbo_ocupacao_id', $_POST['txtcboID']);
                    $this->db->where('operador_id', $operador_id);
                    $this->db->update('tb_operador_cbo');
                }
            }
            $erro = $this->db->_error_message();
            if (trim($erro) != "") { // erro de banco
                return false;
            } else {
                return true;
            }
        } catch (Exception $exc) {
            return false;
        }
    }

    function listaoperadorautocomplete($parametro = null) {
        $this->db->select('operador_id,
                            nome,
                            conselho,
                            cpf');
        $this->db->from('tb_operador');
        $this->db->where('ativo', 'true');
        if ($parametro != null) {
            $this->db->where('nome ilike', "%" . $parametro . "%");
            $this->db->orwhere('conselho ilike', "%" . $parametro . "%");
            $this->db->orwhere('cpf ilike', "%" . $parametro . "%");
        }
        $return = $this->db->get();
        return $return->result();
    }

    function listaoperadorunificarautocomplete($parametro = null) {
        $this->db->select('o.operador_id,
                               o.usuario,
                               o.nome,
                               o.perfil_id,
                               p.nome as perfil');
        $this->db->from('tb_operador o');
        $this->db->join('tb_perfil p', 'p.perfil_id = o.perfil_id', 'left');
        $this->db->where('o.ativo', 'true');
        $this->db->where('o.usuario is not null');
        if ($parametro != null) {
            $this->db->where('o.nome ilike', "%" . $parametro . "%");
            $this->db->orwhere('o.conselho ilike', "%" . $parametro . "%");
            $this->db->orwhere('o.cpf ilike', "%" . $parametro . "%");
        }
        $return = $this->db->get();
        return $return->result();
    }

    function listacboprofissionaisautocomplete($parametro = null) {
        $this->db->select('cbo_ocupacao_id,
                            descricao');
        $this->db->from('tb_cbo_ocupacao');
        if ($parametro != null) {
            $this->db->where('descricao ilike', "%" . $parametro . "%");
        }

        $return = $this->db->get();
        return $return->result();
    }

    function listarautocompleteoperador($parametro = null) {

        $this->db->select(' distinct(o.operador_id),
                            pe.perfil_id,
                            o.nome
                                            ');

        $this->db->from('tb_operador o');
        $this->db->join('tb_perfil pe', 'pe.perfil_id = o.perfil_id', 'left');

        $this->db->where('o.ativo', 'true');

//        echo'<pre>';
//        var_dump($parametro);die;
        if (count($parametro) > 0 && !in_array('TODOS', $parametro)) {
            $this->db->where_in('pe.perfil_id', $parametro);
        }

        $this->db->groupby("o.operador_id, pe.perfil_id, o.nome");
        $this->db->orderby("o.nome");
        $return = $this->db->get();
        return $return->result();
    }

    function gravarNovaSenha() {
        try {

            $novasenha = md5($_POST['txtNovaSenha']);
            $operador_id = $_POST['txtOperadorID'];
            /* inicia o mapeamento no banco */
//                $this->db->set('senha', md5($_POST['txtNovaSenha']));
//                $this->db->update('tb_operador');
//                $this->db->where('operador_id', $_POST['txtOperadorID']);
            $sql = ("UPDATE ponto.tb_operador SET senha = '$novasenha' WHERE operador_id= '$operador_id'");

            $this->db->query($sql);
            $erro = $this->db->_error_message();

            if (trim($erro) != "") { // erro de banco
                return false;
            } else {
                return true;
            }
        } catch (Exception $exc) {
            return false;
        }
    }

    function excluiragendatelefonica($agenda_telefonica_id) {
        $horario = date("Y-m-d H:i:s");
        $operador_exclusao_id = $this->session->userdata('operador_id');

        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_exclusao_id);
        $this->db->where('agenda_telefonica_id', $agenda_telefonica_id);
        $this->db->update('tb_agenda_telefonica');
    }

    function gravaragendatelefonica() {
        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');

        $this->db->set('nome', $_POST['nome']);
        $this->db->set('telefone1', $_POST['telefone1']);
        $this->db->set('telefone2', $_POST['telefone2']);
        $this->db->set('telefone3', $_POST['telefone3']);
        $this->db->set('email', $_POST['email']);
        $this->db->set('observacao', $_POST['obs']);
        if ($_POST['agenda_telefonica_id'] != "") {
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('agenda_telefonica_id', $_POST['agenda_telefonica_id']);
            $this->db->update('tb_agenda_telefonica');
        } else {
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_agenda_telefonica');
        }
    }

    function excluirOperador($operador_id) {
        $horario = date("Y-m-d H:i:s");
        $operador_exclusao_id = $this->session->userdata('operador_id');

        // Excluindo credor associado a esse operador
        $result = $this->db->select('credor_devedor_id')->from('tb_operador')->where('operador_id', $operador_id)->get()->result();
        @$credor_id = (int) $result[0]->credor_devedor_id;

        $this->db->select('convenio_id')->from('tb_convenio')->where('ativo', 't')->where('credor_devedor_id', @$credor_id);
        $convenio = $this->db->get()->result();

        $this->db->select('cep')->from('tb_estoque_fornecedor')->where('ativo', 't')->where('credor_devedor_id', @$credor_id);
        $fornecedor = $this->db->get()->result();

//        $this->db->select('operador_id')->from('tb_operador')->where('ativo', 't')->where('credor_devedor_id', @$credor_id);
//        $operador = $this->db->get()->result();
        if (count($convenio) == 0 && count($fornecedor) == 0) {
            $this->db->set('ativo', 'f');
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_exclusao_id);
            $this->db->where('financeiro_credor_devedor_id', $credor_id);
            $this->db->update('tb_financeiro_credor_devedor');
        }

        // Excluindo operador
        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_exclusao_id);
        $this->db->where('operador_id', $operador_id);
        $this->db->update('tb_operador');
    }

    function reativaroperador($operador_id) {
        // Excluindo credor associado a esse operador
        $result = $this->db->select('credor_devedor_id')->from('tb_operador')->where('operador_id', $operador_id)->get()->result();
        @$credor_id = (int) $result[0]->credor_devedor_id;
        $this->db->set('ativo', 't');
        $this->db->where('financeiro_credor_devedor_id', $credor_id);
        $this->db->update('tb_financeiro_credor_devedor');

        $this->db->set('ativo', 't');
        $this->db->where('operador_id', $operador_id);
        $this->db->update('tb_operador');
    }

    function gravarunificacao() {
        try {
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

//            $this->db->set('antigopaciente_id', $_POST['pacienteid']);
//            $this->db->set('paciente_id', $_POST['paciente_id']);
//            $this->db->set('data_unificacao', $horario);
//            $this->db->set('operador_unificacao', $operador_id);
//            $this->db->where('paciente_id', $_POST['pacienteid']);
//            $this->db->update('tb_exames');

            $this->db->set('medico_agenda', $_POST['operador_id']);
            $this->db->set('medico_consulta_id', $_POST['operador_id']);
            $this->db->set('medico_antigo', $_POST['operadorid']);
            $this->db->set('data_unificacaomedico', $horario);
            $this->db->set('operador_unificacaomedico', $operador_id);
            $this->db->where('medico_agenda', $_POST['operadorid']);
            $this->db->update('tb_agenda_exames');

//            $this->db->set('antigopaciente_id', $_POST['pacienteid']);
//            $this->db->set('paciente_id', $_POST['paciente_id']);
//            $this->db->set('data_unificacao', $horario);
//            $this->db->set('operador_unificacao', $operador_id);
//            $this->db->where('paciente_id', $_POST['pacienteid']);
//            $this->db->update('tb_ambulatorio_consulta');
//            $this->db->set('antigopaciente_id', $_POST['pacienteid']);
//            $this->db->set('paciente_id', $_POST['paciente_id']);
//            $this->db->set('data_unificacao', $horario);
//            $this->db->set('operador_unificacao', $operador_id);
//            $this->db->where('paciente_id', $_POST['pacienteid']);
//            $this->db->update('tb_ambulatorio_guia');

            $this->db->set('medico_parecer1', $_POST['operador_id']);
            $this->db->set('medico_antigo', $_POST['operadorid']);
            $this->db->set('data_unificacaomedico', $horario);
            $this->db->set('operador_unificacaomedico', $operador_id);
            $this->db->where('medico_parecer1', $_POST['operadorid']);
            $this->db->update('tb_ambulatorio_laudo');

            // TB_laudo antigo
//            $this->db->set('paciente_id', $_POST['paciente_id']);
//            $this->db->where('paciente_id', $_POST['pacienteid']);
//            $this->db->update('tb_laudoantigo');

            if ($_POST['operador_id'] != $_POST['operadorid']) {
                $this->db->set('ativo', 'f');
                $this->db->where('operador_id', $_POST['operadorid']);
                $this->db->update('tb_operador');
            }
//            echo 'Testa';
//            die;
            return 0;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function instanciar($operador_id) {
        $admin_id = $this->session->userdata('operador_id'); 
        if ($operador_id == 1 && $admin_id != 1) {
            redirect(base_url()."seguranca/operador");
        }
        if ($operador_id != 0) {
            $this->db->select('o.operador_id,
                                o.usuario,
                                o.senha,
                                o.perfil_id,
                                o.ativo,
                                o.nome,
                                o.cns,
                                o.conselho,
                                o.email,
                                o.nascimento,
                                o.cpf,
                                o.sexo,
                                o.celular,
                                o.telefone,
                                o.tipo_logradouro,
                                o.logradouro,
                                o.numero,
                                o.bairro,
                                o.consulta,
                                o.complemento,
                                o.municipio_id,
                                o.credor_devedor_id,
                                o.classe,
                                o.conta_id,
                                o.curriculo,
                                o.tipo_id,
                                o.taxa_administracao,
                                o.ir,
                                o.pis,
                                o.cofins,
                                o.csll,
                                o.iss,
                                o.valor_base,
                                o.cep,
                                o.cor_mapa,
                                o.ocupacao_painel,
                                o.cbo_ocupacao_id,
                                o.solicitante,
                                o.profissional_agendar_o,
                                o.medico_cirurgiao,
                                o.endereco_sistema,
                                o.cabecalho,
                                o.rodape,
                                o.cabecalho2,
                                o.rodape2,
                                o.timbrado,
                                m.nome as cidade_nome,
                                c.descricao as cbo_nome,
                                o.carimbo,
                                fcd.razao_social as credor,
                                o.link_reuniao,
                                o.coragenda,
                                o.atendimento_medico,
                                o.hora_manha,
                                o.hora_tarde,
                                o.sigla_id,
                                o.uf_profissional,
                                o.seg_manha,
                                o.seg_tarde,
                                o.ter_manha,
                                o.ter_tarde,
                                o.qua_manha,
                                o.qua_tarde,
                                o.qui_manha,
                                o.qui_tarde,
                                o.sex_manha,
                                o.sex_tarde,
                                o.sab_manha,
                                o.sab_tarde,
                                o.taxaadm_perc,
                                o.piso_medico,
                                o.cod_cnes_prof,
                                o.medico_agenda,
                                o.profissional_aluguel,
                                o.grupo_agenda,
                                o.desconto_seguro,
                                o.tipo_desc_seguro,
                                o.conta,
                                o.agencia,
                                o.uf_conselho,
                                o.certificado_digital,
                                o.senha_cert,
                                o.qtd_retorno_dia,
                                o.faixa_etaria,
                                o.faixa_etaria_final');
            $this->db->from('tb_operador o');
            $this->db->join('tb_municipio m', 'm.municipio_id = o.municipio_id', 'left');
            $this->db->join('tb_cbo_ocupacao c', 'c.cbo_ocupacao_id = o.cbo_ocupacao_id', 'left');
            $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = o.credor_devedor_id', 'left');
            $this->db->where("o.operador_id", $operador_id); 
            $query = $this->db->get();
            $return = $query->result();

            $this->_operador_id = $return[0]->operador_id;
            $this->_usuario = $return[0]->usuario;
            $this->_senha = $return[0]->senha;
            $this->_perfil_id = $return[0]->perfil_id;
            $this->_ativo = $return[0]->ativo;
            $this->_endereco_sistema = $return[0]->endereco_sistema;
            $this->_cor_mapa = $return[0]->cor_mapa;
            $this->_nome = $return[0]->nome;
            $this->_ocupacao_painel = $return[0]->ocupacao_painel;
            $this->_cns = $return[0]->cns;
            $this->_link_reuniao = $return[0]->link_reuniao;
            $this->_coragenda = $return[0]->coragenda;
            $this->_conselho = $return[0]->conselho;
            $this->_grupo_agenda = $return[0]->grupo_agenda;
            $this->_email = $return[0]->email;
            $this->_medico_cirurgiao = $return[0]->medico_cirurgiao;
            $this->_profissional_agendar_o = $return[0]->profissional_agendar_o;
            $this->_nascimento = $return[0]->nascimento;
            $this->_cpf = $return[0]->cpf;
            $this->_sexo = $return[0]->sexo;
            $this->_piso_medico = $return[0]->piso_medico;
            $this->_celular = $return[0]->celular;
            $this->_telefone = $return[0]->telefone;
            $this->_classe = $return[0]->classe;
            $this->_tipoLogradouro = $return[0]->tipo_logradouro;
            $this->_logradouro = $return[0]->logradouro;
            $this->_taxaadm_perc = $return[0]->taxaadm_perc;
            $this->_numero = $return[0]->numero;
            $this->_bairro = $return[0]->bairro;
            $this->_complemento = $return[0]->complemento;
            $this->_cidade = $return[0]->municipio_id;
            $this->_cep = $return[0]->cep;
            $this->_consulta = $return[0]->consulta;
            $this->_atendimento_medico = $return[0]->atendimento_medico;
            $this->_cidade_nome = $return[0]->cidade_nome;
            $this->_cbo_nome = $return[0]->cbo_nome;
            $this->_cbo_ocupacao_id = $return[0]->cbo_ocupacao_id;
            $this->_credor_devedor_id = $return[0]->credor_devedor_id;
            $this->_conta_id = $return[0]->conta_id;
            $this->_tipo_id = $return[0]->tipo_id;
            $this->_ir = $return[0]->ir;
            $this->_pis = $return[0]->pis;
            $this->_cofins = $return[0]->cofins;
            $this->_csll = $return[0]->csll;
            $this->_iss = $return[0]->iss;
            $this->_taxa_administracao = $return[0]->taxa_administracao;
            $this->_valor_base = $return[0]->valor_base;
            $this->_carimbo = $return[0]->carimbo;
            $this->_curriculo = $return[0]->curriculo;
            $this->_cabecalho = $return[0]->cabecalho;
            $this->_rodape = $return[0]->rodape;
            $this->_cabecalho2 = $return[0]->cabecalho2;
            $this->_rodape2 = $return[0]->rodape2;
            $this->_timbrado = $return[0]->timbrado;
            $this->_solicitante = $return[0]->solicitante;
            $this->_hora_manha = $return[0]->hora_manha;
            $this->_hora_tarde = $return[0]->hora_tarde;
            $this->_sigla_id = $return[0]->sigla_id;
            $this->_uf_profissional = $return[0]->uf_profissional;
            $this->_seg_manha = $return[0]->seg_manha;
            $this->_seg_tarde = $return[0]->seg_tarde;
            $this->_ter_manha = $return[0]->ter_manha;
            $this->_ter_tarde = $return[0]->ter_tarde;
            $this->_qua_manha = $return[0]->qua_manha;
            $this->_qua_tarde = $return[0]->qua_tarde;
            $this->_qui_manha = $return[0]->qui_manha;
            $this->_qui_tarde = $return[0]->qui_tarde;
            $this->_sex_manha = $return[0]->sex_manha;
            $this->_sex_tarde = $return[0]->sex_tarde;
            $this->_sab_manha = $return[0]->sab_manha;
            $this->_sab_tarde = $return[0]->sab_tarde;
            $this->_cod_cnes_prof = $return[0]->cod_cnes_prof;
            $this->_medico_agenda = $return[0]->medico_agenda;
            $this->_profissional_aluguel = $return[0]->profissional_aluguel;
            $this->_desconto_seguro = $return[0]->desconto_seguro;
            $this->_tipo_desc_seguro = $return[0]->tipo_desc_seguro;
            $this->_conta = $return[0]->conta;
            $this->_agencia = @$return[0]->agencia;
            $this->_uf_conselho = @$return[0]->uf_conselho;
            $this->_certificado_digital = @$return[0]->certificado_digital;
            $this->_senha_cert = @$return[0]->senha_cert;
            $this->_qtd_retorno_dia = @$return[0]->qtd_retorno_dia;
            $this->_faixa_etaria = @$return[0]->faixa_etaria;
            $this->_faixa_etaria_final = @$return[0]->faixa_etaria_final;
        }
    }

    function salvarsenhacertificado($operador, $senha){
        $this->db->set('senha_cert', $senha);
        $this->db->where('operador_id', $operador);
        $this->db->update('tb_operador');
    }

    function listardocumentosprofissional() {
        $this->db->select('');
        $this->db->from('tb_documentacao_profissional');
        $this->db->where('ativo', 't');
        $this->db->orderby('nome', 'desc');
        return $this->db->get()->result();
    }

    function listarmedicoscirurgia() {
        $this->db->select('o.operador_id,
                               o.usuario,
                               o.nome,
                               o.conselho,
                               o.perfil_id,
                               p.nome as perfil');
        $this->db->from('tb_operador o');
        $this->db->join('tb_perfil p', 'p.perfil_id = o.perfil_id');
        $this->db->where("(medico_cirurgiao = 'true' OR (medico_cirurgiao = 'true' and  consulta = 'true') )");
        $this->db->where('o.ativo', 'true');
        // $this->db->where('o.medico_cirurgiao', 'false');
        $this->db->orderby('o.nome');
        $return = $this->db->get();
        return $return->result();
    }
    
    
//  chat  
    function listarmensagem($meu_id=NULL,$para_id=NULL){
            if ($meu_id != "") {
                    $this->db->select('');
                    $this->db->from('tb_mensagem');
                    $this->db->where("( de = '".$para_id."' or para = '".$para_id."' )");
                    $this->db->order_by('mensagem_id','desc');
                    $this->db->limit(200);
                    return $this->db->get()->result();
            }else{
                    return;
            }

    }
 
    
    function buscarusuarios(){
          header('Access-Control-Allow-Origin: *');
          $this->db->select('');
          $this->db->from('tb_operador');
          $this->db->order_by('nome');
          $this->db->where('perfil_id',4);
          $this->db->where('ativo','t');
          return $this->db->get()->result();
  }


 

function listar_mensagens(){
     header('Access-Control-Allow-Origin: *');
    $medico = @$_GET['medico_id'];

if ($medico  == "") {
    $array = Array("status"=>"erro","mensagem"=>"medico_id vazio");
	return;
}else{
	$this->db->select('mensagem,data_cadastro as horario_mensagem,empresa,visto');
	$this->db->from('tb_mensagem');
	$this->db->where("(de = '$medico' or para = '$medico')");
        $this->db->limit(200);
        $this->db->order_by("mensagem_id","asc");  
        
    return $this->db->get()->result();
	 
 }

}

function listarmensagensnaovistas($medico_id=NULL){
    $this->db->select('');
    $this->db->from('tb_mensagem');
    $this->db->where('de',$medico_id);
    $this->db->where('visto','f');
    return $this->db->get()->result();
    
}

 function listartodasmensagem(){
      $this->db->select('de');
      $this->db->from('tb_mensagem');
      $this->db->where("(visto = 'f' or visto is null)");
      $this->db->where("(de is not null and de != '1')");
      $this->db->order_by('mensagem_id','desc');
      return $this->db->get()->result();
      
 }

 
 function atualizarmensagem($meu_id=NULL,$para_id=NULL){
    
     $this->db->set('visto','t');
     $this->db->where("( de = '".$para_id."' )");
     $this->db->update('tb_mensagem');
     
    
 }
 
 function buscarmedico($medico_id=NULL){
     
     $this->db->select('nome,operador_id,qtd_retorno_dia');
     $this->db->from('tb_operador');
     $this->db->where('operador_id',$medico_id);
     return $this->db->get()->result();
 }

 
 function atualizarstatusmsg($para_id=NULL){
     $this->db->set('visto','t');
     $this->db->where('para',$para_id);
     $this->db->update('tb_mensagem');
     
 } 
 
 function buscarmensagemgrupo($grupo){
        $this->db->select('mensagem,data_cadastro as horario_mensagem,empresa,visto');
	$this->db->from('tb_mensagem');
	$this->db->where('grupo',$grupo);
        $this->db->limit(300);
        $this->db->order_by("mensagem_id","asc");          
        return $this->db->get()->result();
 }

// fim chat   
    
 function listarmedicospoltrona() {
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('o.operador_id,
                               o.usuario,
                               o.nome,
                               o.conselho,
                               o.perfil_id,
                               p.nome as perfil');
        $this->db->from('tb_operador o');
        $this->db->join('tb_perfil p', 'p.perfil_id = o.perfil_id');
        $this->db->join('tb_operador_empresas oe', 'oe.operador_id = o.operador_id'); 
        $this->db->where('o.ativo', 'true');
        $this->db->where('oe.ativo', 'true');
        $this->db->where('oe.empresa_id', $empresa_id);
        $this->db->where('p.perfil_id','22');
        $this->db->orderby('o.nome');
        $return = $this->db->get();
        return $return->result();
    }
    
    
function listarmedicoempresa($empresa=null) { 
        $this->db->select('o.operador_id,
                               o.usuario,
                               o.nome,
                               o.conselho,
                               o.perfil_id,
                               p.nome as perfil');
        $this->db->from('tb_operador o');
        $this->db->join('tb_perfil p', 'p.perfil_id = o.perfil_id');
        $this->db->join('tb_operador_empresas oe', 'oe.operador_id = o.operador_id');
        $this->db->where("(consulta = 'true' OR (consulta = 'true' and medico_cirurgiao = 'true') )");
        $this->db->where('o.ativo', 'true');
        $this->db->where('oe.ativo', 'true');
        if($empresa != ""){
          $this->db->where('oe.empresa_id', $empresa);
        }
        $this->db->orderby('o.nome');
        $this->db->groupby('o.operador_id,o.usuario,o.conselho, p.nome');
        $return = $this->db->get();
        return $return->result();
    }
    
    
}
