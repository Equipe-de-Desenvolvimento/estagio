<?php

class procedimento_model extends Model {

    var $_procedimento_tuss_id = null;
    var $_nome = null;
    var $_codigo = null;
    var $_grupo = null;
    var $_descricao = null;
    var $_tuss_id = null;
    var $_perc_medico = null;
    var $_qtde = null;
    var $_dencidade_calorica = null;
    var $_descricao_procedimento = null;
    var $_proteinas = null;
    var $_carboidratos = null;
    var $_entrega = null;
    var $_percentual = null;
    var $_medico = null;
    var $_sala_preparo = null;
    var $_revisao = null;
    var $_revisao_dias = null;
    var $_tipo_aso = null;
    var $_estoque_unidade_id = null;

    function Procedimento_model($procedimento_tuss_id = null) {
        parent::Model();
        if (isset($procedimento_tuss_id)) {
            $this->instanciar($procedimento_tuss_id);
        }
    }

    function listar($args = array()) {
        $this->db->select('pt.procedimento_tuss_id,
                            pt.nome,
                            pt.codigo,
                            pt.descricao,
                            pt.grupo,
                            pt.agrupador,
                            sub.nome as subgrupo');
        $this->db->from('tb_procedimento_tuss pt');
        $this->db->join('tb_ambulatorio_subgrupo sub', 'sub.ambulatorio_subgrupo_id = pt.subgrupo_id', 'left');
        $this->db->where("pt.ativo", 't');
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('pt.nome ilike', "%" . $args['nome'] . "%");
        }
        if (isset($args['subgrupo']) && $args['subgrupo'] > 0) {
            $this->db->where('pt.subgrupo_id', $args['subgrupo']);
        }
        if (isset($args['grupo']) && strlen($args['grupo']) > 0) {

            $this->db->where('pt.grupo ilike', "%" . $args['grupo'] . "%");
        }
        if (isset($args['codigo']) && strlen($args['codigo']) > 0) {

            $this->db->where('pt.codigo ilike', "%" . $args['codigo'] . "%");
        }
        if (isset($args['descricao']) && strlen($args['descricao']) > 0) {

            $this->db->where('pt.descricao ilike', "%" . $args['descricao'] . "%");
        }
//            $this->db->orwhere('pt.grupo ilike', "%" . $args['nome'] . "%");
//            $this->db->where("pt.ativo", 't');
//            $this->db->orwhere('pt.codigo ilike', "%" . $args['nome'] . "%");
//            $this->db->where("pt.ativo", 't');


        return $this->db;
    }

    function listaresponsavelorcamento($orcamento_id) {
//        var_dump($paciente_id);die;
        $horario = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('o.nome');
        $this->db->from('tb_ambulatorio_orcamento ao');
        $this->db->join('tb_operador o', 'o.operador_id = ao.operador_cadastro', 'left');
        $this->db->where("ao.ambulatorio_orcamento_id", $orcamento_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listarorcamentosrecepcaoprincipal($orcamento_id = null, $paciente_id) {
//        var_dump($paciente_id);die;
        $horario = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('oi.ambulatorio_orcamento_item_id,
                            oi.data,
                            oi.orcamento_id,
                            oi.valor_total,
                            oi.dia_semana_preferencia,
                            oi.data_preferencia,
                            oi.horario_preferencia,
                            oi.turno_prefencia,
                            (oi.valor_ajustado * oi.quantidade) as valor_total_ajustado,
                            ao.paciente_id,
                            ao.autorizado,
                            ao.autorizacao_finalizada,
                            pc.convenio_id,
                            c.nome as convenio,
                            pc.procedimento_convenio_id,
                            pt.codigo,
                            pt.descricao_procedimento,
                            pt.grupo,
                            pt.nome as procedimento,
                            fp.nome as forma_pagamento,
                            e.nome as empresa');
        $this->db->from('tb_ambulatorio_orcamento_item oi');
        $this->db->join('tb_ambulatorio_orcamento ao', 'ao.ambulatorio_orcamento_id = oi.orcamento_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = oi.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_forma_pagamento fp', 'fp.forma_pagamento_id = oi.forma_pagamento', 'left');
        $this->db->join('tb_empresa e', 'e.empresa_id = oi.empresa_id', 'left');
//        $this->db->where('oi.empresa_id', $empresa_id);
        if ($orcamento_id != null) {
            $this->db->where("oi.orcamento_id !=", $orcamento_id);
        }
        $this->db->where("ao.paciente_id", $paciente_id);
        $this->db->where("oi.ativo", 't');
        $this->db->orderby("oi.data_cadastro");
        $return = $this->db->get();
        return $return->result();
    }

    function listarorcamentosrecepcao($orcamento_id) {
//        var_dump($paciente_id);die;
        $horario = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('oi.ambulatorio_orcamento_item_id,
                            oi.data,
                            oi.orcamento_id,
                            oi.valor_ajustado,
                            oi.forma_pagamento as forma_pagamento_id,
                            oi.valor_total,
                            oi.empresa_id,
                            oi.dia_semana_preferencia,
                            oi.data_preferencia,
                            oi.turno_prefencia,
                            oi.horario_preferencia,
                            (oi.valor_ajustado * oi.quantidade) as valor_total_ajustado,
                            ao.paciente_id,
                            ao.autorizado,
                            pc.convenio_id,
                            c.nome as convenio,
                            pc.procedimento_convenio_id,
                            pt.codigo,
                            pt.descricao_procedimento,
                            pt.grupo,
                            pt.nome as procedimento,
                            fp.nome as forma_pagamento,
                            e.nome as empresa');
        $this->db->from('tb_ambulatorio_orcamento_item oi');
        $this->db->join('tb_ambulatorio_orcamento ao', 'ao.ambulatorio_orcamento_id = oi.orcamento_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = oi.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_forma_pagamento fp', 'fp.forma_pagamento_id = oi.forma_pagamento', 'left');
        $this->db->join('tb_empresa e', 'e.empresa_id = oi.empresa_id', 'left');
//        $this->db->where('oi.empresa_id', $empresa_id);
        $this->db->where("oi.orcamento_id", $orcamento_id);
//        $this->db->where("ao.paciente_id", $paciente_id);
        $this->db->where("oi.ativo", 't');
        $this->db->orderby("oi.data_cadastro");
        $return = $this->db->get();
        return $return->result();
    }

    function listarorcamentosrecepcaotodos($orcamento_id = null, $paciente_id) {
//        var_dump($paciente_id);die;
//        $horario = date("Y-m-d");
//        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select("ao.ambulatorio_orcamento_id, o.nome as responsavel, 
                           (SELECT COUNT(*) FROM ponto.tb_ambulatorio_orcamento_item aoi
                           WHERE aoi.orcamento_id = ao.ambulatorio_orcamento_id AND ativo = 't') AS qtdeproc");
        $this->db->from('tb_ambulatorio_orcamento ao');
        $this->db->join('tb_operador o', 'o.operador_id = ao.operador_cadastro', 'left');
        $this->db->where("ao.paciente_id", $paciente_id);
        if ($orcamento_id != null) {
            $this->db->where("ao.ambulatorio_orcamento_id !=", $orcamento_id);
        }
        $this->db->where("ao.ativo", 't');
        $return = $this->db->get();
        return $return->result();
    }

    function listartuss($args = array()) {
        $this->db->select('tuss_id,
                            codigo,
                            ans,
                            descricao,
                            valor');
        $this->db->from('tb_tuss');
        $this->db->where("ativo", 't');
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('descricao ilike', "%" . $args['nome'] . "%");
            $this->db->orwhere('codigo ilike', "%" . $args['nome'] . "%");
            $this->db->where("ativo", 't');
            $this->db->orwhere('ans ilike', "%" . $args['nome'] . "%");
            $this->db->where("ativo", 't');
        }
        return $this->db;
    }

    function listarprocedimentos() {
        $this->db->select('procedimento_tuss_id,
                            nome,
                            codigo,
                            descricao,
                            tipo_aso');
        $this->db->from('tb_procedimento_tuss');
        $this->db->where("ativo", 't');
        $this->db->orderby("nome");
        $return = $this->db->get();
        return $return->result();
    }

    function pegarprocedimentos($diagnostico_id){
        $this->db->select('diagnostico, procedimentos');
        $this->db->from('tb_diagnostico');
        $this->db->where('diagnostico_id', $diagnostico_id);
        $return = $this->db->get();
        return $return->result();
    }

    
    function listarprocedimentosdiagnostico($Arrayprocedimento, $ativo) {
        $this->db->select('procedimento_tuss_id,
                            nome,
                            codigo,
                            descricao,
                            tipo_aso');
        $this->db->from('tb_procedimento_tuss');
        $this->db->where("ativo", 't');
        if($ativo == 'true'){
        $this->db->where_in("procedimento_tuss_id", $Arrayprocedimento);  
        }else{
        $this->db->where_not_in("procedimento_tuss_id", $Arrayprocedimento);   
        }
        $this->db->orderby("nome");
        $return = $this->db->get();
        return $return->result();
    }

    function listarprocedimentossetores($convenio_id) {

        $this->db->select('pc.procedimento_convenio_id,
                            pt.nome,
                            pt.codigo,
                            pt.descricao,
                            pt.grupo,
                            pt.tipo_aso');
        $this->db->from('tb_procedimento_convenio pc');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where("pc.ativo", 't');
        $this->db->where("pc.convenio_id", $convenio_id);
        $this->db->where("pt.grupo !=", 'ASO');
        $this->db->orderby("pt.nome");
        $return = $this->db->get();
        return $return->result();
    }

    function listarprocedimento() {
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('pc.procedimento_convenio_id,
                            pt.nome,
                            pt.codigo,
                            pt.descricao,
                            pt.grupo');
        $this->db->from('tb_procedimento_convenio pc');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where("pc.ativo", 't');
        $this->db->where("pc.empresa_id", $empresa_id);
        $this->db->orderby("pt.nome");
        $return = $this->db->get();
        return $return->result();
    }

    function verificarCodigoProcedimento($codigo, $convenio) {
        // echo '<pre>';
        // var_dump($codigo, $convenio); 
        // die;
        $this->db->select('pc.procedimento_convenio_id,
                            pt.procedimento_tuss_id,
                            pc.valortotal as valor,
                            pt.nome,
                            pt.codigo,
                            pt.descricao');
        $this->db->from('tb_procedimento_convenio pc');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where("pc.ativo", 't');
        $this->db->where("pt.codigo", (string) $codigo);
        if(count($convenio) > 0){
            $this->db->where("pc.convenio_id", $convenio[0]->convenio_id);
        }
        $return = $this->db->get()->result();
        if(count($convenio) > 0){
            $array = $return;
        }else{
            $array = array();
        }
        return $array;
    }

    function listarprocedimentossetor($item) {
        $this->db->select('pc.procedimento_convenio_id,
                            pt.nome,
                            pt.codigo,
                            pt.descricao,
                            pt.tipo_aso');
        $this->db->from('tb_procedimento_convenio pc');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where("pc.ativo", 't');
        $this->db->where("procedimento_convenio_id", $item);

        $this->db->orderby("pt.nome");
        $return = $this->db->get();
        return $return->result();
    }

    function listarprocedimento2() {
        $this->db->select('procedimento_tuss_id,
                            nome,
                            codigo');
        $this->db->from('tb_procedimento_tuss');
        $this->db->orderby('nome');
        $this->db->where("ativo", 't');
        $return = $this->db->get();
        return $return->result();
    }

    function listarprocedimento3() {
        $this->db->select('procedimento_tuss_id,
                            nome,
                            grupo,
                            codigo');
        $this->db->from('tb_procedimento_tuss');
        $this->db->orderby('nome');
        $this->db->where("ativo", 't');
        $return = $this->db->get();
        return $return->result();
    }

    function listarprocedimentoaso($procedimento_convenio_id) {
        $this->db->select(' pc.procedimento_convenio_id,
                            pt.nome,
                            pt.grupo,
                            pt.codigo                           
                            ');
        $this->db->from('tb_procedimento_convenio pc');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');

        $this->db->where('pc.ativo', 't');
        $this->db->where('pc.procedimento_convenio_id', $procedimento_convenio_id);
        $this->db->orderby('pt.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function listarprocedimentoasodata($procedimento_convenio_id, $guia_id) {
        $this->db->select('
                            ae.data
                            
                            ');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_cadastro_aso ca', 'ca.guia_id = ae.guia_id', 'left');


        $this->db->where('ae.procedimento_tuss_id', $procedimento_convenio_id);
        $this->db->where('ae.guia_id', $guia_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listarprocedimentoasonovo($guia_id, $cadastro_aso_id) {

        $this->db->select('ae.procedimento_tuss_id, ae.data');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where('ae.guia_id', $guia_id);
        $this->db->where('ae.cadastro_aso_id', $cadastro_aso_id);
        $this->db->where('pc.ativo', 't');
        $this->db->where('pt.grupo !=', 'ASO');
        $return = $this->db->get();
        return $return->result();
    }

    function listarprocedimentoagrupados($procedimento_agrupador_id) {
        $this->db->select('procedimentos_agrupados_ambulatorial_id as procedimento_agrupador_id,
                            pa.quantidade_agrupador,
                            pt.nome,
                            pt.procedimento_tuss_id,
                            pt.grupo,
                            pt.codigo');
        $this->db->from('tb_procedimentos_agrupados_ambulatorial pa');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pa.procedimento_tuss_id', 'left');
        $this->db->where("pa.ativo", 't');
        $this->db->where("pa.procedimento_agrupador_id", $procedimento_agrupador_id);
        $this->db->orderby('pt.grupo');
        $this->db->orderby('pt.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function listarprocedimentoagrupadosgastodesalagravar() {
        $this->db->select('procedimentos_agrupados_ambulatorial_id as procedimento_agrupador_id,
                            pa.quantidade_agrupador,
                            pt.nome,
                            pro.estoque_produto_id,
                            pt.procedimento_tuss_id,
                            pt.grupo,
                            pt.codigo,
                            pt.grupo');
        $this->db->from('tb_procedimentos_agrupados_ambulatorial pa');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pa.procedimento_tuss_id', 'left');
        $this->db->join('tb_estoque_produto pro', 'pt.procedimento_tuss_id = pro.procedimento_id', 'left');
        $this->db->join('tb_ambulatorio_grupo ag', 'ag.nome = pt.grupo', 'left');
        $this->db->where("pa.ativo", 't');
        $this->db->where("ag.tipo IN ('MATERIAL', 'MEDICAMENTO', 'TAXA')");
        $this->db->where("pa.procedimento_agrupador_id", $_POST['pacote_id']);
        $this->db->orderby('pt.grupo');
        $this->db->orderby('pt.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function listarprocedimentoagrupadosgastodesala($convenio_id) {
        $this->db->select('
                            pt.nome,
                            pt.procedimento_tuss_id,
                            pt.grupo,
                            pt.codigo');
        $this->db->from('tb_procedimento_convenio pc');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where("pc.ativo", 't');
        $this->db->where("pt.agrupador", 't');
        $this->db->where("pc.convenio_id", $convenio_id);
        $this->db->orderby('pt.grupo');
        $this->db->orderby('pt.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function gravaragrupadorprocedimento() {
        try {
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
//            echo '<pre>';
//            var_dump($_POST);
//            die;
            $this->db->set('nome', $_POST['txtNome']);
            $this->db->set('grupo', $_POST['agrupador_grupo']);
            $this->db->set('agrupador', 't');
            $this->db->set('agrupador_grupo', $_POST['agrupador_grupo']);
            $this->db->set('codigo', '');
            $this->db->set('qtde', 1);

            if ($_POST['txtprocedimentotussid'] == '0' || $_POST['txtprocedimentotussid'] == '') {
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_procedimento_tuss');
                $procedimento_agrupador_id = $this->db->insert_id();
            } else {
                $procedimento_agrupador_id = $_POST['txtprocedimentotussid'];
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $this->db->where('procedimento_tuss_id', $procedimento_agrupador_id);
                $this->db->update('tb_procedimento_tuss');
            }
            $procedimentos_retirar_id = array();
            foreach ($_POST['add_agrupador'] as $key => $value) {

                if ($_POST['add_agrupador'][$key] != "") { // insert
                    $this->db->select('procedimentos_agrupados_ambulatorial_id');
                    $this->db->from('tb_procedimentos_agrupados_ambulatorial');
                    $this->db->where('procedimento_agrupador_id', $procedimento_agrupador_id);
                    $this->db->where('procedimento_tuss_id', $_POST['procedimento_id'][$key]);
                    $this->db->where("ativo", 't');
                    $return = $this->db->get()->result();
                    array_push($procedimentos_retirar_id, $_POST['procedimento_id'][$key]);
                    if ($_POST['quantidade'][$key] > 0) {
                        $quantidade = $_POST['quantidade'][$key];
                    } else {
                        $quantidade = 1;
                    }
                    if (count($return) == 0) {

                        $this->db->set('procedimento_agrupador_id', $procedimento_agrupador_id);
                        $this->db->set('procedimento_tuss_id', $_POST['procedimento_id'][$key]);
                        $this->db->set('quantidade_agrupador', $quantidade);
                        $this->db->set('data_cadastro', $horario);
                        $this->db->set('operador_cadastro', $operador_id);
                        $this->db->insert('tb_procedimentos_agrupados_ambulatorial');
                    } else {

                        $this->db->set('quantidade_agrupador', $quantidade);
                        $this->db->set('data_atualizacao', $horario);
                        $this->db->set('operador_atualizacao', $operador_id);
                        $this->db->where('procedimentos_agrupados_ambulatorial_id', $return[0]->procedimentos_agrupados_ambulatorial_id);
                        $this->db->update('tb_procedimentos_agrupados_ambulatorial');
                    }
                } else {
                    continue;
                }
            }

            if (count($procedimentos_retirar_id) > 0) {
                $this->db->set('ativo', 'f');
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $this->db->where_not_in('procedimento_tuss_id', $procedimentos_retirar_id);
                $this->db->where('ativo', 't');
                $this->db->where('procedimento_agrupador_id', $procedimento_agrupador_id);
                $this->db->update('tb_procedimentos_agrupados_ambulatorial');
            }

            if ($_POST['agrupador_grupo'] != '') {
                // Caso tenha definido um grupo para o agrupador ele irá setar pra falso todos os procedimentos que não forem daquele grupo
//                $sql = "UPDATE ponto.tb_procedimentos_agrupados_ambulatorial paa
//                        SET ativo = 'f', data_atualizacao = '{$horario}', operador_atualizacao = {$operador_id}
//                        FROM ponto.tb_procedimentos_agrupados_ambulatorial paa2
//                        INNER JOIN ponto.tb_procedimento_tuss pt ON pt.procedimento_tuss_id = paa2.procedimento_tuss_id
//                        WHERE paa2.procedimentos_agrupados_ambulatorial_id = paa.procedimentos_agrupados_ambulatorial_id
//                        AND pt.grupo != '" . $_POST['agrupador_grupo'] . "'
//                        AND paa2.ativo = 't'
//                        AND paa2.procedimento_agrupador_id = {$procedimento_agrupador_id}";
//                $this->db->query($sql);
            }

            return $procedimento_agrupador_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function listarmedicoXML($crm) {
        $this->db->select('o.operador_id,
                           o.conselho,
                           o.nome,
                        ');
        $this->db->from('tb_operador o');
        $this->db->where('o.ativo', 't');
        $this->db->where('o.conselho', $crm);
        $medico = $this->db->get()->result();
        if(count($medico) > 0){
            return $medico[0]->operador_id;
        }else{
            return 1;
        }
    }

    function listarconvenioXML($codigPrestador) {
        $this->db->select('c.convenio_id,
                           c.codigoidentificador,
                           c.nome,
                        ');
        $this->db->from('tb_convenio c');
        $this->db->where('c.ativo', 't');
        $this->db->where('c.codigoidentificador', $codigPrestador);
        $convenio = $this->db->get()->result();
        return $convenio;
        
    }

    function procedimentolog($procedimento_tuss_id){
        $this->db->select('c.nome, o.nome as operador_cadastro,
                           c.data_cadastro, 
                           oa.nome as operador_atualizacao,
                           c.data_atualizacao');
        $this->db->from('tb_procedimento_tuss c');
        $this->db->join('tb_operador o', 'o.operador_id = c.operador_cadastro', 'left');
        $this->db->join('tb_operador oa', 'oa.operador_id = c.operador_atualizacao', 'left');
        $this->db->where('c.procedimento_tuss_id', $procedimento_tuss_id);
        $return = $this->db->get();
        $retorno = $return->result();

        return $retorno;
    }

    function listarprocedimentoXML($codigo, $convenio_id) {
        $this->db->select('pc.procedimento_convenio_id,
                            pt.procedimento_tuss_id,
                            pt.nome as procedimento,
                            pt.codigo,
                            pc.valortotal
                        ');
        $this->db->from('tb_procedimento_convenio pc');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where('pt.codigo', $codigo);
        $this->db->where('pc.convenio_id', $convenio_id);
        $this->db->where('pc.ativo', 't');
        $this->db->where('pt.ativo', 't');
        $this->db->orderby('pt.nome');
        $producao = $this->db->get()->result();
        return $producao;
        
    }

    function apagarProducaoImpTemp() {
        $this->db->where('procedimento_importacao_producao_temp_id > 0');
        $this->db->delete('tb_procedimento_importacao_producao_temp');
    }

    function listarprocedimentoconveniocodigo($convenio, $codigo) {
        $this->db->select('pc.procedimento_convenio_id,
                            pt.nome as procedimento,
                            pt.codigo
                        ');
        $this->db->from('tb_procedimento_convenio pc');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where('pt.codigo', $codigo);
        $this->db->where('pc.convenio_id', $convenio);
        $this->db->where('pc.ativo', 't');
        $this->db->where('pt.ativo', 't');
        $this->db->orderby('pt.nome');
        $producao = $this->db->get()->result();

        return $producao;
    }

    function listarprocedimentosproducaoduplo() {
        $this->db->select('pi.procedimento_importacao_producao_id,
                           pc.valortotal,
                           o.nome as medico,
                           o.operador_id as medico_id,
                           c.convenio_id,
                           c.nome as convenio,
                           pt.codigo,
                           pt.procedimento_tuss_id,
                        ');
        $this->db->from('tb_procedimento_importacao_producao pi');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = pi.procedimento_convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = pi.medico_id', 'left');
        $this->db->where('duplo', 't');
        $this->db->orderby('procedimento_importacao_producao_id');
        $producao = $this->db->get()->result();

        return $producao;
    }

    function gravarPercentualImpDuplo() {
        
        $retorno = 't';
        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');

        foreach ($_POST['producao_id'] as $key => $producao_id) {
            $procedimento_convenio_id = $_POST['procedimento'][$key];
            $medico_id = $_POST['medico_id'][$key];
            $percentual = $this->buscarPercentualMedico($procedimento_convenio_id, $medico_id);
            $procedimento_tuss_id = $this->listarprocedimentotuss($procedimento_convenio_id);
            $valor = $this->listarprocedimentotussvalor($procedimento_convenio_id);
            // var_dump($procedimento_convenio_id); die;
            
            $this->db->set('duplo', 'f');
            $this->db->set('procedimento_convenio_id', $procedimento_convenio_id);
            $this->db->set('procedimento_tuss_id', $procedimento_tuss_id);
            // $this->db->set('valor_medico', $percentual[0]->perc_medico);
            $this->db->set('valor_medico', $valor);
            $this->db->set('valor', $valor);
            // $this->db->set('percentual_medico', $percentual[0]->percentual);
            $this->db->set('percentual_medico', 'f');
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('procedimento_importacao_producao_id', $producao_id);
            $this->db->update('tb_procedimento_importacao_producao');
        }

        return $retorno;
       
    }

    function gravarPercentualImpXML() {
         
        $retorno = 'f';

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');

        $this->db->select('pi.*,
                           pc.valortotal,
                        ');
        $this->db->from('tb_procedimento_importacao_producao_temp pi');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = pi.procedimento_convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->orderby('procedimento_importacao_producao_temp_id');
        $producao = $this->db->get()->result();
          
      
        $procedimento_importacao_producao_ids = Array();
        foreach ($producao as $key => $value) {
            $percentual = $this->buscarPercentualMedico($value->procedimento_convenio_id, $value->medico_id);
            // var_dump($procedimento_tuss_id); die;

            $this->db->set('procedimento_convenio_id', $value->procedimento_convenio_id);
            $this->db->set('procedimento_tuss_id', $value->procedimento_tuss_id);
            $this->db->set('medico_id', $value->medico_id);
            $this->db->set('convenio_id', $value->convenio_id);
            $this->db->set('data_producao', $value->data_producao);
            $this->db->set('data_agendamento', $value->data_agendamento);
            $this->db->set('valor', $value->valor);
            $this->db->set('quantidade', $value->quantidade);
            $this->db->set('array_linha', $value->array_linha);
            $this->db->set('duplo', $value->duplo);
            $this->db->set('paciente_nome', $value->paciente_nome);
            
            if($value->duplo == 't'){
                $retorno = 't';
            }
            // $this->db->set('valor_medico', $percentual[0]->perc_medico);
            // $this->db->set('percentual_medico', $percentual[0]->percentual); 
            $this->db->set('valor_medico',$value->valor);
            $this->db->set('percentual_medico', 'f'); 
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->set('pedido',$value->pedido);
            $this->db->insert('tb_procedimento_importacao_producao');
            $procedimento_importacao_producao_id = $this->db->insert_id();
            
            $procedimento_importacao_producao_ids[] = $procedimento_importacao_producao_id;
        }
       
         $nome_arquivo = $_FILES['userfile']['name'];
         $this->db->set('operador_cadastro',$operador_id);
         $this->db->set('data_cadastro',$horario);
         $this->db->set('arquivo', json_encode($procedimento_importacao_producao_ids));
         $this->db->set('arquivo_duplo', '[]');
         $this->db->set('nome',$nome_arquivo);
         $this->db->insert('tb_procedimento_importacao_arquivo');      
         return $retorno;
       
    }

    function gravarPercentualImp() {
         
        $retorno = 'f';

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');

        $this->db->select('pi.*,
                           pc.valortotal,
                        ');
        $this->db->from('tb_procedimento_importacao_producao_temp pi');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = pi.procedimento_convenio_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->orderby('procedimento_importacao_producao_temp_id');
        $producao = $this->db->get()->result();
          
      
        $procedimento_importacao_producao_ids = Array();
        $procedimento_importacao_producao_duplo_ids = Array();
        foreach ($producao as $key => $value) {
            $percentual = $this->buscarPercentualMedico($value->procedimento_convenio_id, $value->medico_id);
            // var_dump($procedimento_tuss_id); die;

            $this->db->select('');
            $this->db->from('tb_procedimento_importacao_producao');
            $this->db->where('procedimento_convenio_id', $value->procedimento_convenio_id);
            $this->db->where('procedimento_tuss_id', $value->procedimento_tuss_id);
            $this->db->where('medico_id', $value->medico_id);
            $this->db->where('convenio_id', $value->convenio_id);
            $this->db->where('data_producao', $value->data_producao);
            $this->db->where('data_agendamento', $value->data_agendamento);
            $this->db->where('valor', $value->valortotal);
            $this->db->where('quantidade', $value->quantidade);
            $this->db->where('duplo', $value->duplo);
            if($value->duplo == 't'){
                $retorno = 't';
            }
            $this->db->where('pedido',$value->pedido);
            $this->db->where('ativo','t');
            // $this->db->where('valor_medico', $percentual[0]->perc_medico);
            // $this->db->where('percentual_medico', $percentual[0]->percentual);
            $this->db->where('valor_medico', $value->valortotal);
            $this->db->where('percentual_medico', 'f');
            $res =  $this->db->get()->result();
              
            
            if (count($res) == 0) {                 
                $this->db->set('procedimento_convenio_id', $value->procedimento_convenio_id);
                $this->db->set('procedimento_tuss_id', $value->procedimento_tuss_id);
                $this->db->set('medico_id', $value->medico_id);
                $this->db->set('convenio_id', $value->convenio_id);
                $this->db->set('data_producao', $value->data_producao);
                $this->db->set('data_agendamento', $value->data_agendamento);
                $this->db->set('valor', $value->valortotal);
                $this->db->set('quantidade', $value->quantidade);
                $this->db->set('array_linha', $value->array_linha);
                $this->db->set('duplo', $value->duplo);
              
                if($value->duplo == 't'){
                    $retorno = 't';
                }
                // $this->db->set('valor_medico', $percentual[0]->perc_medico);
                // $this->db->set('percentual_medico', $percentual[0]->percentual); 
                $this->db->set('valor_medico', $value->valortotal);
                $this->db->set('percentual_medico', 'f'); 
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->set('pedido',$value->pedido);
                $this->db->insert('tb_procedimento_importacao_producao');
                $procedimento_importacao_producao_id = $this->db->insert_id();
                
                $procedimento_importacao_producao_ids[] = $procedimento_importacao_producao_id;
                
           }else{    
              $this->db->where('array_linha', $value->array_linha);
              $this->db->set('data_cadastro', $horario);
              $this->db->set('operador_cadastro', $operador_id);
              $this->db->where('procedimento_importacao_producao_id',$res[0]->procedimento_importacao_producao_id);
              $this->db->update('tb_procedimento_importacao_producao');
                // ------------------------------------------------------------------------- //

              $this->db->set('procedimento_convenio_id', $value->procedimento_convenio_id);
                $this->db->set('procedimento_tuss_id', $value->procedimento_tuss_id);
                $this->db->set('medico_id', $value->medico_id);
                $this->db->set('convenio_id', $value->convenio_id);
                $this->db->set('data_producao', $value->data_producao);
                $this->db->set('data_agendamento', $value->data_agendamento);
                $this->db->set('valor', $value->valortotal);
                $this->db->set('quantidade', $value->quantidade);
                $this->db->set('array_linha', $value->array_linha);
                $this->db->set('duplo', $value->duplo);
                // $this->db->set('valor_medico', $percentual[0]->perc_medico);
                // $this->db->set('percentual_medico', $percentual[0]->percentual); 
                $this->db->set('valor_medico', $value->valortotal);
                $this->db->set('percentual_medico', 'f'); 
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->set('pedido',$value->pedido);
                $this->db->insert('tb_procedimento_importacao_producao_duplo');

                $procedimento_importacao_producao_duplo_id = $this->db->insert_id();
                
                $procedimento_importacao_producao_duplo_ids[] = $procedimento_importacao_producao_duplo_id;

                 // ------------------------------------------------------------------------- // 
              
              

           }
            
        }
       
         $nome_arquivo = $_FILES['userfile']['name'];
         $this->db->set('operador_cadastro',$operador_id);
         $this->db->set('data_cadastro',$horario);
         $this->db->set('arquivo', json_encode($procedimento_importacao_producao_ids));
         $this->db->set('arquivo_duplo', json_encode($procedimento_importacao_producao_duplo_ids));
         $this->db->set('nome',$nome_arquivo);
         $this->db->insert('tb_procedimento_importacao_arquivo');      
         return $retorno;
       
    }

    function listarprocedimentotussvalor($procedimento_convenio_id){
        $this->db->select('valortotal');
        $this->db->from('tb_procedimento_convenio');
        $this->db->where("procedimento_convenio_id", $procedimento_convenio_id);
        $this->db->where("ativo", 't');
        // $this->db->orderby("nome");
        $return = $this->db->get()->result();

        return $return[0]->valortotal;
    }

    function listarprocedimentotuss($procedimento_convenio_id){
        $this->db->select('procedimento_tuss_id');
        $this->db->from('tb_procedimento_convenio');
        $this->db->where("procedimento_convenio_id", $procedimento_convenio_id);
        $this->db->where("ativo", 't');
        // $this->db->orderby("nome");
        $return = $this->db->get()->result();

        return $return[0]->procedimento_tuss_id;
    }

    function buscarPercentualMedico($procedimento_convenio_id, $medico_id){
        $this->db->select('mc.valor as perc_medico, mc.percentual');
        $this->db->from('tb_procedimento_percentual_medico_convenio mc');
        $this->db->join('tb_procedimento_percentual_medico m', 'm.procedimento_percentual_medico_id = mc.procedimento_percentual_medico_id', 'left');
        $this->db->where('m.procedimento_tuss_id', $procedimento_convenio_id);
        $this->db->where('mc.medico', $medico_id);
        $this->db->where('mc.ativo', 'true');
        $this->db->where('mc.revisor', 'false');
        $percentual = $this->db->get()->result();

        if (count($percentual) == 0) {
            $this->db->select('pt.perc_medico, pt.percentual');
            $this->db->from('tb_procedimento_convenio pc');
            $this->db->join('tb_procedimento_tuss pt', 'pc.procedimento_tuss_id = pt.procedimento_tuss_id', 'left');
            $this->db->where('pc.procedimento_convenio_id', $procedimento_convenio_id);
//                        $this->db->where('pc.ativo', 'true');
//                        $this->db->where('pt.ativo', 'true');
            $percentual = $this->db->get()->result();
        }
        return $percentual;
    }

    function gravarPercentualTempXMLImp($medico_id, $convenio_id, $procedimentos, $paciente, $pacienteCarteira, $data, $valor, $array_linha) {
        try {
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
        //    echo '<pre>';
        //    var_dump(date("Y-m-d",strtotime(str_replace('/','-', $array[11]))));
        //    die;          
            $this->db->set('paciente_nome', $paciente);
            $this->db->set('procedimento_convenio_id', $procedimentos[0]->procedimento_convenio_id);
            $this->db->set('procedimento_tuss_id', $procedimentos[0]->procedimento_tuss_id);
            $this->db->set('medico_id', $medico_id);
            $this->db->set('convenio_id', $convenio_id);
            $this->db->set('data_producao', date("Y-m-d",strtotime(str_replace('/','-', $data))));
            $this->db->set('data_agendamento', date("Y-m-d",strtotime(str_replace('/','-', $data))));
            $this->db->set('valor', $valor);
            $this->db->set('quantidade', 1);
            $this->db->set('array_linha', json_encode($array_linha));
            
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_procedimento_importacao_producao_temp');
            $procedimento_agrupador_id = $this->db->insert_id();
  
            return $procedimento_agrupador_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarPercentualTempImp($medico, $convenio, $procedimento, $array) {
        try {
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
        //    echo '<pre>';
        //    var_dump(date("Y-m-d",strtotime(str_replace('/','-', $array[11]))));
        //    die;          
            $this->db->set('procedimento_convenio_id', $procedimento[0]->procedimento_convenio_id);
            $this->db->set('procedimento_tuss_id', $procedimento[0]->procedimento_tuss_id);
            $this->db->set('medico_id', $medico[0]->operador_id);
            $this->db->set('convenio_id', $convenio[0]->convenio_id);
            $this->db->set('data_producao', date("Y-m-d",strtotime(str_replace('/','-', $array[11]))));
            $this->db->set('data_agendamento', date("Y-m-d",strtotime(str_replace('/','-', $array[13]))));
            $this->db->set('valor', $procedimento[0]->valor);
            $this->db->set('quantidade', 1);
            $this->db->set('array_linha', json_encode($array));
            $this->db->set('pedido', $array[8]);
            if(count($procedimento) > 1){
                $this->db->set('duplo', 't');
            }
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_procedimento_importacao_producao_temp');
            $procedimento_agrupador_id = $this->db->insert_id();
  
            return $procedimento_agrupador_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function listarprocedimentoprodutovalor($procedimento_tuss_id) {
        $this->db->select('procedimento_tuss_id,
                            nome,
                            codigo,
                            descricao');
        $this->db->from('tb_procedimento_tuss');
        $this->db->where("procedimento_tuss_id", $procedimento_tuss_id);
        $this->db->where("ativo", 't');
        $this->db->orderby("nome");
        $return = $this->db->get();
        return $return->result();
    }

    function listarprocedimentoconveniovalor($procedimento_tuss_id) {
        $this->db->select('pc.procedimento_convenio_id,
                            pc.valortotal as valor,
                            pc.procedimento_tuss_id,
                            pc.convenio_id,
                            c.nome as convenio,
                            ');
        $this->db->from('tb_procedimento_convenio pc');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where("pc.ativo", 't');
        $this->db->where("pc.procedimento_tuss_id", $procedimento_tuss_id);
        $this->db->orderby("pc.procedimento_convenio_id");
        $return = $this->db->get();
        return $return->result();
    }

    function listargruposexame() {
        $this->db->select('ambulatorio_grupo_id,
                            nome,
                            ');
        $this->db->from('tb_ambulatorio_grupo');
        $this->db->where("tipo", 'EXAME');
        $this->db->orderby("nome");
        $return = $this->db->get();
        return $return->result();
    }

    function listargruposrelatoriofuncao() {
        $this->db->select('ambulatorio_grupo_id,
                            nome,
                            ');
        $this->db->from('tb_ambulatorio_grupo');
//        $this->db->where("tipo", 'EXAME');
        $this->db->orderby("nome");
        $return = $this->db->get();
        return $return->result();
    }

    function listargruposatendimento() {
        $this->db->select('ambulatorio_grupo_id,
                            nome');
        $this->db->from('tb_ambulatorio_grupo');
//        $this->db->where("tipo", 'CONSULTA');
        $this->db->orderby("nome");
        $return = $this->db->get();
        return $return->result();
    }

    function listargruposconsulta() {
        $this->db->select('ambulatorio_grupo_id,
                            nome,
                            ');
        $this->db->from('tb_ambulatorio_grupo');
        $this->db->where("tipo", 'CONSULTA');
        $this->db->orderby("nome");
        $return = $this->db->get();
        return $return->result();
    }

    function listargruposespecialidade() {
        $this->db->select('ambulatorio_grupo_id,
                            nome,
                            ');
        $this->db->from('tb_ambulatorio_grupo');
        $this->db->where("tipo", 'ESPECIALIDADE');
        $this->db->orderby("nome");
        $return = $this->db->get();
        return $return->result();
    }

    function listargruposprocedimentoplano() {
        $this->db->select('ambulatorio_grupo_id,
                            nome,
                            ');
        $this->db->from('tb_ambulatorio_grupo');
        $this->db->orderby("nome");
        $return = $this->db->get();
        return $return->result();
    }

    function listargrupos() {
        $this->db->select('ambulatorio_grupo_id,
                            nome,
                            ');
        $this->db->from('tb_ambulatorio_grupo');
//        $this->db->where("tipo !=", 'AGRUPADOR');
        $this->db->orderby("nome");
        $return = $this->db->get();
        return $return->result();
    }

    function listargruposmatmed() {
        $this->db->select('ambulatorio_grupo_id,
                            nome,
                            tipo
                            ');
        $this->db->from('tb_ambulatorio_grupo');
        $this->db->where("(tipo = 'MEDICAMENTO' OR tipo ='MATERIAL')");
        $this->db->orderby("nome");
        $return = $this->db->get();
        return $return->result();
    }

    function relatorioprocedimentos() {
        $this->db->select('pt.procedimento_tuss_id,
                            pt.nome,
                            pt.codigo,
                            pt.descricao,
                            pt.grupo,
                            pt.perc_medico,
                            pt.percentual,
                            pt.percentual_revisor,
                            pt.valor_revisor,
                            pt.qtde,
                            pt.home_care,
                            pt.entrega,
                            sub.nome as subgrupo,
                            
                            
                            pt.dencidade_calorica,
                            pt.proteinas,
                            pt.carboidratos,
                            pt.lipidios,
                            pt.kcal,
                            pt.valor_tcd');
        $this->db->from('tb_procedimento_tuss pt');
        $this->db->join('tb_ambulatorio_subgrupo sub', 'sub.ambulatorio_subgrupo_id = pt.subgrupo_id', 'left');
        $this->db->where("pt.ativo", 't');
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }

        if ($_POST['subgrupo'] > 0) {
            $this->db->where('pt.subgrupo_id', $_POST['subgrupo']);
        }

        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $this->db->orderby("pt.nome");
        $return = $this->db->get();
        return $return->result();
    }

    function relatorioprocedimentossubgrupo() {

        $this->db->select('sub.nome as subgrupo');
        $this->db->from('tb_ambulatorio_subgrupo sub');
        $this->db->where('sub.ambulatorio_subgrupo_id', $_POST['subgrupo']);
        $return = $this->db->get();
        return $return->result();
    }

    function relatorioprocedimentoconvenio() {
        $this->db->select('pt.procedimento_tuss_id,
                            pt.nome as procedimento,
                            pt.codigo,
                            o.nome as operador_cadastro,
                            op.nome as operador_atualizacao,
                            pc.data_atualizacao,
                            pc.data_cadastro,
                            c.nome as convenio,
                            e.nome as empresa,
                            pc.valortotal');
        $this->db->from('tb_procedimento_convenio pc');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_operador o', 'pc.operador_cadastro = o.operador_id', 'left');
        $this->db->join('tb_operador op', 'pc.operador_atualizacao = op.operador_id', 'left');
        $this->db->join('tb_empresa e', 'e.empresa_id = pc.empresa_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where("pc.ativo", 't');
        if ($_POST['empresa'] != "0") {
            $this->db->where('pc.empresa_id', $_POST['empresa']);
        }
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        if ($_POST['convenio'] != "0" && $_POST['convenio'] != "" && $_POST['convenio'] != "-1") {
            $this->db->where("pc.convenio_id", $_POST['convenio']);
        }
        if ($_POST['convenio'] == "") {
            $this->db->where("c.dinheiro", "f");
        }
        if ($_POST['convenio'] == "-1") {
            $this->db->where("c.dinheiro", "t");
        }
        $this->db->orderby("pc.convenio_id");
        $this->db->orderby("pt.nome");
        $this->db->orderby("e.nome");
        $return = $this->db->get();
        return $return->result();
    }

    function relatorioprocedimentotuss() {

        $this->db->select('codigo,
                           descricao,
                           valor,
                           classificacao');
        $this->db->from('tb_tuss');
        $this->db->where('ativo', 't');
        $this->db->orderby('descricao');
        $return = $this->db->get();
        return $return->result();
    }

    function listarprocedimentostuss($procedimento_tuss_id) {
        $this->db->select('tuss_id,
                            descricao,
                            codigo,
                            valor_bri,
                            valor_porte,
                            porte_descricao,
                            texto,
                            grupo_matmed,
                            classificacao,
                            valor');
        $this->db->from('tb_tuss');
        $this->db->where("tuss_id", $procedimento_tuss_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listarautocompletetuss($parametro = null) {
        $this->db->select('tuss_id,
                            codigo,
                            descricao,
                            ans');
        $this->db->from('tb_tuss');
        if ($parametro != null) {

            $this->db->where('codigo ilike', "%" . $parametro . "%");
            $this->db->orwhere('descricao ilike', "%" . $parametro . "%");
        }
//        $this->db->where('ativo', 'true');
        $return = $this->db->get();
        return $return->result();
    }

    function listarprocedimentoautocomplete($parametro = null) {
        $this->db->select('pt.procedimento_tuss_id,
                           pt.nome');
        $this->db->from('tb_procedimento_tuss pt');
        $this->db->where('pt.ativo', 'true');
        if ($parametro != null) {
            $this->db->where("(pt.nome ilike '%$parametro%' OR pt.codigo ilike '%$parametro%')");
        }
        $return = $this->db->get();
        return $return->result();
    }

    function listarprocedimentointernacaoautocomplete($parametro = null) {
        $this->db->select('pt.procedimento_tuss_id,
                           pt.nome');
        $this->db->from('tb_procedimento_tuss pt');
        $this->db->where('pt.ativo', 'true');
        if ($parametro != null) {
            $this->db->where("(pt.nome ilike '%$parametro%' OR pt.codigo ilike '%$parametro%')");
        }
        $return = $this->db->get();
        return $return->result();
    }

    function listarprocedimentocirurgia2autocomplete($parametro = null, $parametro2 = null) {
        $this->db->select('pc.procedimento_convenio_id,
                           pc.valortotal,
                           pt.codigo,
                           pt.nome,
                           pt.procedimento_tuss_id,
                           pt.descricao');
        $this->db->from('tb_procedimento_convenio pc');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where('pc.ativo', 'true');
        $this->db->where('pc.convenio_id', $parametro2);
        if ($parametro != null) {
            $this->db->where("(pt.nome ilike '%$parametro%' OR pt.codigo ilike '%$parametro%')");
        }
        $return = $this->db->get();
        return $return->result();
    }

    function carregavalorprocedimentocirurgico($procedimento_id) {
        $this->db->select('valortotal');
        $this->db->from('tb_procedimento_convenio pc');
        $this->db->where('pc.procedimento_convenio_id', $procedimento_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listarprocedimentocirurgiaautocomplete($parametro = null) {
        $this->db->select('pc.procedimento_convenio_id,
                           pt.descricao');
        $this->db->from('tb_procedimento_convenio pc');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where('pc.ativo', 'true');
        if ($parametro != null) {
            $this->db->where('pt.descricao ilike', "%" . $parametro . "%");
        }
        $return = $this->db->get();
        return $return->result();
    }

    function listarclassificacaotuss() {
        $this->db->select('tuss_classificacao_id,
                            nome');
        $this->db->from('tb_tuss_classificacao');
        $this->db->where('ativo', 'true');
        $return = $this->db->get();
        return $return->result();
    }

    function excluirorcamentorecepcao($ambulatorio_orcamento_item_id) {

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');

        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('ambulatorio_orcamento_item_id', $ambulatorio_orcamento_item_id);
        $this->db->update('tb_ambulatorio_orcamento_item');
        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return false;
        else
            return true;
    }

    function excluir($procedimento_tuss_id) {

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');

        // Pegar ID do Procedimento Convenio (Caso tenha)
        $this->db->select('procedimento_convenio_id');
        $this->db->from('tb_procedimento_convenio');
        $this->db->where('procedimento_tuss_id', $procedimento_tuss_id);
        $this->db->where('ativo', 't');
        $proc_conv = $this->db->get()->result();

        if(count($proc_conv) > 0){

            foreach($proc_conv as $item){

                // Apagando Procedimentos Cadastrado para o 
                // Médico em Manter Profissional(Caso Tenha)
                $this->db->set('ativo', 'f');
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $this->db->where('procedimento_convenio_id', $item->procedimento_convenio_id);
                $this->db->update('tb_convenio_operador_procedimento');


                // Verificando se possui Percentual Médico Cadastrado
                $this->db->select('procedimento_percentual_medico_id');
                $this->db->from('tb_procedimento_percentual_medico');
                $this->db->where('procedimento_tuss_id', $item->procedimento_convenio_id);
                $this->db->where('ativo', 't');
                $perc_med = $this->db->get()->result();

                // Caso tenha estaremos apagando
                if(count($perc_med) > 0){

                    foreach($perc_med as $value){
                        $this->db->set('ativo', 'f');
                        $this->db->set('data_atualizacao', $horario);
                        $this->db->set('operador_atualizacao', $operador_id);
                        $this->db->where('procedimento_percentual_medico_id', $value->procedimento_percentual_medico_id);
                        $this->db->update('tb_procedimento_percentual_medico');

                        $this->db->set('ativo', 'f');
                        $this->db->set('data_atualizacao', $horario);
                        $this->db->set('operador_atualizacao', $operador_id);
                        $this->db->where('procedimento_percentual_medico_id', $value->procedimento_percentual_medico_id);
                        $this->db->update('tb_procedimento_percentual_medico_convenio');
                    }

                }
            }

        }

        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('procedimento_tuss_id', $procedimento_tuss_id);
        $this->db->update('tb_procedimento_tuss');
        $erro = $this->db->_error_message();


        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('procedimento_tuss_id', $procedimento_tuss_id);
        $this->db->update('tb_procedimento_convenio');

        if (trim($erro) != "") // erro de banco
            return false;
        else
            return true;
    }

    function excluirprocedimentoagrupado($procedimento_agrupador_id) {

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');

        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('procedimentos_agrupados_ambulatorial_id', $procedimento_agrupador_id);
        $this->db->update('tb_procedimentos_agrupados_ambulatorial');
        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return false;
        else
            return true;
    }

    function verificarprocedimentoagrupador($procedimento_tuss_id) {
        $this->db->select('pa.procedimento_tuss_id');
        $this->db->from('tb_procedimentos_agrupados_ambulatorial pa');
        $this->db->where('pa.ativo', 'true');
        $this->db->where('pa.procedimento_tuss_id', $procedimento_tuss_id);

        $return = $this->db->get();
        return $return->result();
    }

    function excluirprocedimentotuss($tuss_id) {

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');

        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('tuss_id', $tuss_id);
        $this->db->update('tb_tuss');
        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return false;
        else
            return true;
    }

    /**
     * Função para gravar valores na tabela TB_SERVIDOR.
     * @author Equipe de desenvolvimento APH
     * @access public
     * @return Resposta true/false da conexão com o banco
     */
    function verificaexistenciaprocedimento($nome) {
        $this->db->select('procedimento_tuss_id');
        $this->db->from('tb_procedimento_tuss');
        $this->db->where('ativo', 't');
        $this->db->where('nome ', $nome);
        $return = $this->db->get();
        $result = $return->result();

        if (empty($result)) {
            return false;
        } else {
            return true;
        }
    }

    function gravarValorLabAPI($convenio, $codigo, $valor, $origemLis) {

        
        $this->db->select('e.empresa_id');
        $this->db->from('tb_empresa e');
        $this->db->where('e.origem_lis', $origemLis);
        $this->db->where("e.ativo", 't');
        $return1 = $this->db->get()->result();
        if(count($return1) == 0){
            return 'OrigemLis não existe';
        }

        $this->db->select('c.convenio_id');
        $this->db->from('tb_convenio c');
        $this->db->where('c.nome', $convenio);
        $this->db->where("c.ativo", 't');
        $return2 = $this->db->get()->result();
        if(count($return2) == 0){
            return 'Convênio não cadastrado';
        }

        $this->db->select('pc.procedimento_tuss_id, pc.procedimento_convenio_id, c.convenio_id');
        $this->db->from('tb_procedimento_convenio pc');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where('c.nome', $convenio);
        if($codigo != ''){
            $this->db->where("pt.codigo", $codigo);
        }
        $this->db->where("pt.ativo", 't');
        $this->db->where("pc.ativo", 't');
        $return = $this->db->get()->result();
        if(count($return) == 0){
            return 'Código não encontrado no convênio';
        }
        

        if (count($return) > 0) {

            $operador_id = $this->session->userdata('operador_id');
            $horario = date("Y-m-d H:i:s");
            $this->db->select('pc.procedimento_tuss_id, pc.procedimento_convenio_id, c.convenio_id');
            $this->db->from('tb_procedimento_convenio pc');
            $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
            $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
            $this->db->where('c.nome', $convenio);
            if($codigo != ''){
                $this->db->where("pt.codigo", $codigo);
            }
            if(count($return1) > 0){
                $this->db->where("pc.empresa_id", $return1[0]->empresa_id);
            }
            $this->db->where("pt.ativo", 't');
            $this->db->where("pc.ativo", 't');
            $result = $this->db->get()->result();

            if (count($result) > 0) {
                foreach ($result as $value) {
                    $this->db->set('qtdech', 0);
                    $this->db->set('valorch', 0);
                    $this->db->set('qtdefilme', 0);
                    $this->db->set('valorfilme', 0);
                    $this->db->set('qtdeporte', 0);
                    $this->db->set('valorporte', 0);
                    $this->db->set('qtdeuco', 0);
                    $this->db->set('valoruco', 0);
                    $this->db->set('valortotal', $valor);
                    $this->db->set('data_cadastro', $horario);
                    $this->db->set('operador_cadastro', $operador_id);
                    $this->db->where('procedimento_tuss_id', $value->procedimento_tuss_id);
                    $this->db->where('convenio_id', $value->convenio_id);
                    $this->db->update('tb_procedimento_convenio');
                }
            }
            return '';
        }
    }
    
    function atualizaprocedimentosconvenioscbhpm($procedimento_tuss_id) {
        $tuss_id = $_POST['txtprocedimento'];
        $this->db->select('valor_porte');
        $this->db->from('tb_tuss');
        $this->db->where('tuss_id', $tuss_id);
        $this->db->where("tabela = 'CBHPM'");
        $return = $this->db->get()->result();

        if (count($return) != 0) {

            $empresa_id = $this->session->userdata('empresa_id');
            $operador_id = $this->session->userdata('operador_id');
            $horario = date("Y-m-d H:i:s");

            // Insere procedimentos CBHPM nesse convenio
            $this->db->select('c.valor_ajuste_cbhpm, c.convenio_id');
            $this->db->from('tb_convenio c');
            $this->db->where("c.tabela", 'CBHPM');
            $this->db->where("c.ativo", 't');
            $result = $this->db->get()->result();

            if (count($result) > 0) {
                foreach ($result as $value) {
                    $this->db->set('convenio_id', $value->convenio_id);
                    $this->db->set('procedimento_tuss_id', $procedimento_tuss_id);
                    $this->db->set('empresa_id', $empresa_id);
                    $this->db->set('qtdech', 0);
                    $this->db->set('valorch', 0);
                    $this->db->set('qtdefilme', 0);
                    $this->db->set('valorfilme', 0);
                    $this->db->set('qtdeporte', 0);
                    $this->db->set('valorporte', 0);
                    $this->db->set('qtdeuco', 0);
                    $this->db->set('valoruco', 0);
                    $this->db->set('valortotal', (float) ( $return[0]->valor_porte + ($return[0]->valor_porte * $value->valor_ajuste_cbhpm / 100) ));
                    $this->db->set('data_cadastro', $horario);
                    $this->db->set('operador_cadastro', $operador_id);
                    $this->db->insert('tb_procedimento_convenio');
                }
            }
        }
    }

    function gravar() {
        try {

            /* inicia o mapeamento no banco */
            $procedimento_tuss_id = $_POST['txtprocedimentotussid'];
            $this->db->set('nome', $_POST['txtNome']);
            $this->db->set('tuss_id', $_POST['txtprocedimento']);
            $this->db->set('codigo', $_POST['txtcodigo']);
            $this->db->set('descricao', $_POST['txtdescricao']);
            if ($_POST['tipo_aso'] != '') {
                $this->db->set('tipo_aso', $_POST['tipo_aso']);
            } else {
                $this->db->set('tipo_aso', null);
            } if ($_POST['medida_id'] != '') {
                $this->db->set('farmacia_unidade_id', $_POST['medida_id']);
            } else {
                $this->db->set('farmacia_unidade_id', null);
            }


            if ($_POST['subgrupo_id'] != '') {
                $this->db->set('subgrupo_id', $_POST['subgrupo_id']);
            } else {
                $this->db->set('subgrupo_id', null);
            }
            if ($_POST['txtqtde'] != '') {
                $this->db->set('qtde', $_POST['txtqtde']);
            }
            if ($_POST['txtmeta'] != '') {
                $this->db->set('meta_mensal', $_POST['txtmeta']);
            }
            if ($_POST['percentual'] != '') {
                $this->db->set('percentual', $_POST['percentual']);
            }
            if ($_POST['percentual_tecnico'] != '') {
                $this->db->set('percentual_tecnico', $_POST['percentual_tecnico']);
            }
            if ($_POST['homecare'] != '') {
                $this->db->set('home_care', $_POST['homecare']);
            }
            if ($_POST['medico'] != '') {
                $this->db->set('medico', $_POST['medico']);
            }
            if ($_POST['descricao'] != '') {
                $this->db->set('descricao_procedimento', $_POST['descricao']);
            }
            if ($_POST['laboratorio_id'] != '') {
                $this->db->set('laboratorio_id', $_POST['laboratorio_id']);
            }
//            if ($_POST['observacao'] != '') {
//                $this->db->set('observacao_procedimento', $_POST['observacao']);
//            }
            if ($_POST['entrega'] != '') {
                $this->db->set('entrega', $_POST['entrega']);
            }

            if (isset($_POST['rev'])) {
                $this->db->set('revisao', 't');
                $this->db->set('revisao_dias', $_POST['dias']);
            } else {
                $this->db->set('revisao', 'f');
            }


            if ($_POST['procedimento_associacao'] != "" && $_POST['grupo'] == "RETORNO") {
                $this->db->set('associacao_procedimento_tuss_id', $_POST['procedimento_associacao']);
                $this->db->set('retorno_dias', $_POST['diasRetorno']);
            } else {
                $this->db->set('associacao_procedimento_tuss_id', null);
                $this->db->set('retorno_dias', null);
            }

            if (isset($_POST['salaPreparo'])) {
                $this->db->set('sala_preparo', 't');
            } else {
                $this->db->set('sala_preparo', 'f');
            }

            $this->db->set('grupo', $_POST['grupo']);
            if ($_POST['txtperc_medico'] != '') {
                $this->db->set('perc_medico', str_replace(",", ".", $_POST['txtperc_medico']));
            }

            if ($_POST['txtperc_revisor'] != '') {
                $this->db->set('valor_revisor', str_replace(",", ".", $_POST['txtperc_revisor']));
            }
            if ($_POST['valor_tecnico'] != '') {
                $this->db->set('valor_tecnico', str_replace(",", ".", $_POST['valor_tecnico']));
            }
            if ($_POST['percentual_revisor'] != '') {
                $this->db->set('percentual_revisor', $_POST['percentual_revisor']);
            }

            if ($_POST['txtperc_laboratorio'] != '') {
                $this->db->set('valor_laboratorio', str_replace(",", ".", $_POST['txtperc_laboratorio']));
            }
            if ($_POST['percentual_laboratorio'] != '') {
                $this->db->set('percentual_laboratorio', $_POST['percentual_laboratorio']);
            }
            if (@$_POST['cod_servico'] != '') {
                $this->db->set('cod_servico', @$_POST['cod_servico']);
            } else {
                $this->db->set('cod_servico', null);
            }
            if (@$_POST['cod_classificacao'] != '') {
                $this->db->set('cod_classificacao', @$_POST['cod_classificacao']);
            } else { 
                $this->db->set('cod_classificacao', null);
            }
            
             if ($_POST['txtvalor_tcd'] != '') {
                $this->db->set('valor_tcd', str_replace(",", ".", $_POST['txtvalor_tcd']));
            }

//
//            if ($_POST['txtperc_promotor'] != '') {
//                $this->db->set('valor_promotor', str_replace(",", ".", $_POST['txtperc_promotor']));
//            }
//
//            $this->db->set('percentual_promotor', $_POST['percentual_promotor']);
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            if ($_POST['txtprocedimentotussid'] == "") {// insert
                $nome = str_replace("     ", " ", $_POST['txtNome']);
                $nome = str_replace("    ", " ", $nome);
                $nome = str_replace("   ", " ", $nome);
                $nome = str_replace("  ", " ", $nome);
                if ($this->verificaexistenciaprocedimento($nome) == false) {
                    $this->db->set('data_cadastro', $horario);
                    $this->db->set('operador_cadastro', $operador_id);
                    $this->db->insert('tb_procedimento_tuss');
                    $erro = $this->db->_error_message();
                    if (trim($erro) != "") // erro de banco
                        return -1;
                    else
                        $procedimento_tuss_id = $this->db->insert_id();
                }else {
                    return 0;
                }
            } else { // update
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $procedimento_tuss_id = $_POST['txtprocedimentotussid'];
                $this->db->where('procedimento_tuss_id', $procedimento_tuss_id);
                $this->db->update('tb_procedimento_tuss');
            }

            return $procedimento_tuss_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function listarempresas() {

        $this->db->select('empresa_id,
            razao_social,
            nome');
        $this->db->from('tb_empresa');
        $this->db->orderby('empresa_id');
        $return = $this->db->get();
        return $return->result();
    }

    function gravarajustarportetusschpm() {
        try {

            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('valor_porte', (float) str_replace(",", ".", str_replace(".", "", $_POST['txtvalorporte'])));
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('porte_descricao', $_POST['descricaoporte']);
            $this->db->update('tb_tuss');

            $sql = "UPDATE ponto.tb_procedimento_convenio pc2
                    SET valorch = t.valor_porte + (c.valor_ajuste_cbhpm/100 * t.valor_porte), 
                        valortotal = t.valor_porte + (c.valor_ajuste_cbhpm/100 * t.valor_porte)
                    FROM ponto.tb_procedimento_convenio pc
                    LEFT JOIN ponto.tb_procedimento_tuss pt ON pc.procedimento_tuss_id = pt.procedimento_tuss_id
                    LEFT JOIN ponto.tb_convenio c ON c.convenio_id = pc.convenio_id
                    LEFT JOIN ponto.tb_tuss t ON t.tuss_id = pt.tuss_id
                    WHERE pc.ativo = 't'
                    AND pc2.procedimento_convenio_id = pc.procedimento_convenio_id
                    AND c.tabela = 'CBHPM'
                    AND t.tuss_id IN (SELECT tuss_id FROM ponto.tb_tuss WHERE porte_descricao = '" . $_POST['descricaoporte'] . "')";
            $this->db->query($sql);

            return 1;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravartuss() {
        try {

            /* inicia o mapeamento no banco */
            $tuss_id = $_POST['tuss_id'];
            if ($_POST['txtvalorbri'] != "" && $_POST['grupo'] != "") {
                if ($_POST['tuss_id'] != "") {
                    $valor_bri = str_replace(",", ".", str_replace(".", "", $_POST['txtvalorbri']));
//                    $valor_por = str_replace(",", ".", str_replace(".", "", $_POST['txtvalorporte']));

                    $sql = "UPDATE ponto.tb_procedimento_convenio pc
                            SET 
                            valorch= $valor_bri, valortotal= $valor_bri
                            FROM ponto.tb_procedimento_tuss pt, ponto.tb_tuss t
                            WHERE pc.procedimento_tuss_id = pt.procedimento_tuss_id
                            AND t.tuss_id = pt.tuss_id
                            AND t.tuss_id = $tuss_id";
                    $this->db->query($sql);
                }
                $this->db->set('valor_bri', str_replace(",", ".", str_replace(".", "", $_POST['txtvalorbri'])));
            }
            $this->db->set('valor_porte', (float) str_replace(",", ".", str_replace(".", "", $_POST['txtvalorporte'])));
            $this->db->set('porte_descricao', $_POST['descricaoporte']);

            if ($_POST['descricaoporte'] != '') {
                $this->db->set('tabela', 'CBHPM');
            }

            $this->db->set('descricao', $_POST['txtNome']);
            $this->db->set('codigo', $_POST['procedimento']);
            if ($_POST['classificaco'] != '') {
                $this->db->set('classificacao', $_POST['classificaco']);
            }
            if ($_POST['txtvalor'] != "") {
                $this->db->set('valor', str_replace(",", ".", str_replace(".", "", $_POST['txtvalor'])));
            }

            if ($_POST['grupo'] != "") {
                $this->db->set('grupo_matmed', $_POST['grupo']);
            }
            $this->db->set('texto', $_POST['laudo']);

            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            if ($_POST['tuss_id'] == "") {
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_tuss');
                $tuss_id = $this->db->insert_id();
                $erro = $this->db->_error_message();
            } else {
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $tuss_id = $_POST['tuss_id'];
                $this->db->where('tuss_id', $tuss_id);
                $this->db->update('tb_tuss');
            }

            if ($_POST['descricaoporte'] != '' && $_POST['tuss_id'] != "") {
                $sql = "UPDATE ponto.tb_procedimento_convenio pc2
                        SET valorch = t.valor_porte + (c.valor_ajuste_cbhpm/100 * t.valor_porte), 
                            valortotal = t.valor_porte + (c.valor_ajuste_cbhpm/100 * t.valor_porte)
                        FROM ponto.tb_procedimento_convenio pc
                        LEFT JOIN ponto.tb_procedimento_tuss pt ON pc.procedimento_tuss_id = pt.procedimento_tuss_id
                        LEFT JOIN ponto.tb_convenio c ON c.convenio_id = pc.convenio_id
                        LEFT JOIN ponto.tb_tuss t ON t.tuss_id = pt.tuss_id
                        WHERE pc.ativo = 't'
                        AND pc2.procedimento_convenio_id = pc.procedimento_convenio_id
                        AND c.tabela = 'CBHPM'
                        AND t.tuss_id = $tuss_id";
                $this->db->query($sql);
            }

            if ($_POST['descricaoporte'] != '') {
                $this->insereProcedimentoConvenioCBHPM($tuss_id);
            }

            return 1;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function insereProcedimentoConvenioCBHPM($tuss_id) {
        $operador_id = $this->session->userdata('operador_id');
        $empresa_id = $this->session->userdata('empresa_id');
        $horario = date("Y-m-d H:i:s");

        $this->db->select('procedimento_tuss_id');
        $this->db->from('tb_procedimento_tuss');
        $this->db->where('tuss_id', $tuss_id);
        $this->db->where('ativo', 't');
        $return = $this->db->get()->result();

        $this->db->select('codigo, descricao, valor_porte');
        $this->db->from('tb_tuss');
        $this->db->where('tuss_id', $tuss_id);
        $retorno = $this->db->get()->result();

        if (count($return) == 0) {

            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('nome', $retorno[0]->descricao);
            $this->db->set('tuss_id', $tuss_id);
            $this->db->set('codigo', $retorno[0]->codigo);
            $this->db->set('descricao', $retorno[0]->descricao);
            $this->db->set('grupo', 'CIRURGICO');
            $this->db->set('revisao', 'f');
            $this->db->set('associacao_procedimento_tuss_id', null);
            $this->db->set('retorno_dias', null);
            $this->db->set('sala_preparo', 'f');
            $this->db->set('qtde', 1);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_procedimento_tuss');
            $procedimento_tuss_id = $this->db->insert_id();
        } else {
            $procedimento_tuss_id = $return[0]->procedimento_tuss_id;
        }

        $this->db->select('convenio_id, valor_ajuste_cbhpm');
        $this->db->from('tb_convenio c');
        $this->db->where('tabela', 'CBHPM');
        $this->db->where('ativo', 't');
        $this->db->where("convenio_id NOT IN (
            SELECT DISTINCT(convenio_id) 
            FROM ponto.tb_procedimento_convenio pc 
            WHERE ativo = 't' 
            AND procedimento_tuss_id = $procedimento_tuss_id
        )");
        $result = $this->db->get()->result();

        if (count($result) > 0) {
            foreach ($result as $item) {
                $this->db->set('convenio_id', $item->convenio_id);
                $this->db->set('procedimento_tuss_id', $procedimento_tuss_id);
                $this->db->set('empresa_id', $empresa_id);
                $this->db->set('qtdech', 0);
                $this->db->set('valorch', 0);
                $this->db->set('qtdefilme', 0);
                $this->db->set('valorfilme', 0);
                $this->db->set('qtdeporte', 0);
                $this->db->set('valorporte', 0);
                $this->db->set('qtdeuco', 0);
                $this->db->set('valoruco', 0);
                $this->db->set('valortotal', (float) ($retorno[0]->valor_porte + ($retorno[0]->valor_porte * $item->valor_ajuste_cbhpm / 100)));
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_procedimento_convenio');
            }
        }
    }

    function gravarajustevalores() {
        try {

            $grupos_string = '';
            foreach ($_POST['grupo'] as $item) {
                if ($grupos_string == '') {
                    $grupos_string = $grupos_string . "'$item'";
                } else {
                    $grupos_string = $grupos_string . ", " . "'$item'";
                }
            }
            // var_dump($grupos_string); die;
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            if (isset($_POST['ajuste'])) {
                $vlr = str_replace(",", ".", $_POST['ajuste_percentual']);
                $sql = "UPDATE ponto.tb_procedimento_tuss pt
                        SET perc_medico = perc_medico + (perc_medico * {$vlr} / 100)
                        WHERE grupo IN ($grupos_string) 
                        AND pt.agrupador = 'f'";
//                die($sql);
            } else {
                $vlr = str_replace(",", ".", $_POST['txtperc_medico']);
                $sql = "UPDATE ponto.tb_procedimento_tuss pt
                        SET perc_medico = {$vlr}, percentual = '{$_POST['percentual']}'
                        WHERE grupo IN ($grupos_string)
                        AND pt.agrupador = 'f' ";
            }

            $this->db->query($sql);
        } catch (Exception $exc) {
            return false;
        }
    }

    function gravarprocedimentoconveniovalor($procedimento_tuss_id) {
        try {
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
//            var_dump((float)str_replace(".", "", str_replace(",", "", $_POST['valor']))); die;

            if ($_POST['convenio'] == "TODOS") {
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
                    $this->db->where("ce.empresa_id", $empresa_id);
                    $this->db->where("ce.ativo", 'true');
                }
                $this->db->orderby("c.nome");
                $this->db->groupby("c.convenio_id,
                            c.nome,
                            c.dinheiro,
                            c.conta_id");
                $res = $this->db->get()->result();
                foreach ($res as $value) {
                    $resultado = $this->verificarconvenioprocedimento($value->convenio_id, $procedimento_tuss_id);
                    if ($resultado != "-1") {
                        $this->db->set('procedimento_tuss_id', $procedimento_tuss_id);
                        $this->db->set('convenio_id', $value->convenio_id);
                        $this->db->set('qtdech', 1);
                        $this->db->set('qtdefilme', 0);
                        $this->db->set('valorfilme', 0);
                        $this->db->set('qtdeporte', 0);
                        $this->db->set('valorporte', 0);
                        $this->db->set('qtdeuco', 0);
                        $this->db->set('valoruco', 0);
                        $this->db->set('qtdech', 1);
                        $this->db->set('valorch', str_replace(",", ".", str_replace(".", "", $_POST['valor'])));
                        $this->db->set('valortotal', str_replace(",", ".", str_replace(".", "", $_POST['valor'])));
                        $this->db->set('data_cadastro', $horario);
                        $this->db->set('operador_cadastro', $operador_id);
                        $this->db->insert('tb_procedimento_convenio');
                    }
                }
            } else {
                /* inicia o mapeamento no banco */
                $this->db->set('procedimento_tuss_id', $procedimento_tuss_id);
                $this->db->set('convenio_id', $_POST['convenio']);
                $this->db->set('qtdech', 1);
                $this->db->set('qtdefilme', 0);
                $this->db->set('valorfilme', 0);
                $this->db->set('qtdeporte', 0);
                $this->db->set('valorporte', 0);
                $this->db->set('qtdeuco', 0);
                $this->db->set('valoruco', 0);
                $this->db->set('qtdech', 1);
                $this->db->set('valorch', str_replace(",", ".", str_replace(".", "", $_POST['valor'])));
                $this->db->set('valortotal', str_replace(",", ".", str_replace(".", "", $_POST['valor'])));
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_procedimento_convenio');
            }
            return 1;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function excluirprocedimentoconveniovalor($procedimento_convenio_produto_valor_id) {
        try {
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');


                // Apagando Procedimentos Cadastrado para o 
                // Médico em Manter Profissional(Caso Tenha)
                $this->db->set('ativo', 'f');
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $this->db->where('procedimento_convenio_id', $procedimento_convenio_produto_valor_id);
                $this->db->update('tb_convenio_operador_procedimento');


                // Verificando se possui Percentual Médico Cadastrado
                $this->db->select('procedimento_percentual_medico_id');
                $this->db->from('tb_procedimento_percentual_medico');
                $this->db->where('procedimento_tuss_id', $procedimento_convenio_produto_valor_id);
                $this->db->where('ativo', 't');
                $perc_med = $this->db->get()->result();

                // Caso tenha estaremos apagando
                if(count($perc_med) > 0){

                    foreach($perc_med as $value){
                        $this->db->set('ativo', 'f');
                        $this->db->set('data_atualizacao', $horario);
                        $this->db->set('operador_atualizacao', $operador_id);
                        $this->db->where('procedimento_percentual_medico_id', $value->procedimento_percentual_medico_id);
                        $this->db->update('tb_procedimento_percentual_medico');

                        $this->db->set('ativo', 'f');
                        $this->db->set('data_atualizacao', $horario);
                        $this->db->set('operador_atualizacao', $operador_id);
                        $this->db->where('procedimento_percentual_medico_id', $value->procedimento_percentual_medico_id);
                        $this->db->update('tb_procedimento_percentual_medico_convenio');
                    }

                }

            $this->db->set('ativo', 'f');
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('procedimento_convenio_id', $procedimento_convenio_produto_valor_id);
            $this->db->update('tb_procedimento_convenio');

            return 1;
        } catch (Exception $exc) {
            return -1;
        }
    }

    private function instanciar($procedimento_tuss_id) {

        if ($procedimento_tuss_id != 0) {
            $this->db->select('pt.farmacia_unidade_id,pt.nome, pt.codigo, pt.grupo, pt.tuss_id, pt.valor_tecnico, pt.percentual_tecnico,
                               pt.home_care, pt.descricao_procedimento, pt.entrega, 
                               pt.medico, pt.percentual,  t.descricao, pt.perc_medico,  pt.valor_promotor,  pt.percentual_promotor, 
                               pt.valor_revisor,  pt.percentual_revisor, 
                               pt.qtde, pt.dencidade_calorica, pt.proteinas, pt.percentual_laboratorio, pt.valor_laboratorio,
                               pt.carboidratos, pt.lipidios, pt.kcal,pt.laboratorio_id,
                               pt.revisao, pt.sala_preparo, pt.revisao_dias,
                               pt.associacao_procedimento_tuss_id, pt.retorno_dias,
                               pt.subgrupo_id, pt.agrupador_grupo, pt.tipo_aso, pt.meta_mensal,pt.cod_servico,pt.cod_classificacao,
                               pt.valor_tcd');
            $this->db->from('tb_procedimento_tuss pt');
            $this->db->join('tb_tuss t', 't.tuss_id = pt.tuss_id', 'left');
            $this->db->where("procedimento_tuss_id", $procedimento_tuss_id);
            $query = $this->db->get();
            $return = $query->result();
//            echo '<pre>';
//            var_dump($return); die;
            $this->_procedimento_tuss_id = $procedimento_tuss_id;

            $this->_tuss_id = $return[0]->tuss_id;
            $this->_nome = $return[0]->nome;
            $this->_grupo = $return[0]->grupo;
            $this->_codigo = $return[0]->codigo;
            $this->_home_care = $return[0]->home_care;
            $this->_descricao = $return[0]->descricao;
            $this->_descricao_procedimento = $return[0]->descricao_procedimento;
            $this->_perc_medico = $return[0]->perc_medico;
            $this->_qtde = $return[0]->qtde;
            $this->_meta_mensal = $return[0]->meta_mensal;
            $this->_dencidade_calorica = $return[0]->dencidade_calorica;
            $this->_proteinas = $return[0]->proteinas;
            $this->_carboidratos = $return[0]->carboidratos;
            $this->_percentual = $return[0]->percentual;
            $this->_medico = $return[0]->medico;
            $this->_entrega = $return[0]->entrega;
            $this->_revisao_dias = $return[0]->revisao_dias;
            $this->_revisao = $return[0]->revisao;
            $this->_sala_preparo = $return[0]->sala_preparo;
            $this->_valor_promotor = $return[0]->valor_promotor;
            $this->_percentual_promotor = $return[0]->percentual_promotor;
            $this->_valor_tecnico = $return[0]->valor_tecnico;
            $this->_percentual_tecnico = $return[0]->percentual_tecnico;
            $this->_valor_revisor = $return[0]->valor_revisor;
            $this->_percentual_revisor = $return[0]->percentual_revisor;
            $this->_associacao_procedimento_tuss_id = $return[0]->associacao_procedimento_tuss_id;
            $this->_percentual_laboratorio = $return[0]->percentual_laboratorio;
            $this->_valor_laboratorio = $return[0]->valor_laboratorio;
            $this->_laboratorio_id = $return[0]->laboratorio_id;
            $this->_retorno_dias = $return[0]->retorno_dias;
            $this->_subgrupo_id = $return[0]->subgrupo_id;
            $this->_agrupador_grupo = $return[0]->agrupador_grupo;
            $this->_tipo_aso = $return[0]->tipo_aso;
            $this->_estoque_unidade_id = $return[0]->farmacia_unidade_id;
            $this->_cod_servico = $return[0]->cod_servico;
            $this->_cod_classificacao = $return[0]->cod_classificacao;
            $this->_valor_tcd = $return[0]->valor_tcd;
        } else {
            $this->_procedimento_tuss_id = null;
        }
    }

    function listarorcamentosempresa($args = array()) {
        $horario = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('oi.ambulatorio_orcamento_item_id,
                            oi.data,
                            oi.orcamento_id,
                            oi.valor_total,
                            oi.dia_semana_preferencia,
                            oi.data_preferencia,
                            oi.horario_preferencia,
                            oi.turno_prefencia,
                            (oi.valor_ajustado * oi.quantidade) as valor_total_ajustado,
                            ao.paciente_id,
                            ao.autorizado,
                            pc.convenio_id,
                            c.nome as convenio,
                            pc.procedimento_convenio_id,
                            pt.codigo,
                            pt.descricao_procedimento,
                            pt.grupo,
                            pt.nome as procedimento,
                            fp.nome as forma_pagamento,
                            e.nome as empresa');
        $this->db->from('tb_ambulatorio_orcamento_item oi');
        $this->db->join('tb_ambulatorio_orcamento ao', 'ao.ambulatorio_orcamento_id = oi.orcamento_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = oi.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_forma_pagamento fp', 'fp.forma_pagamento_id = oi.forma_pagamento', 'left');
        $this->db->join('tb_empresa e', 'e.empresa_id = oi.empresa_id', 'left');
        $this->db->where('ao.empresa_id', $empresa_id);


        $this->db->where("oi.ativo", 't');


        if (@$args['nome'] != '') {
//            var_dump($args['nome']); die;
            $this->db->where('c.nome', $args['nome']);
        }
        if (isset($args['convenio']) && @$args['convenio'] != '') {
            $this->db->where('c.convenio_id', $args['convenio']);
        }
        if (isset($args['grupo_convenio']) && @$args['grupo_convenio'] != '') {
            $this->db->where('c.convenio_grupo_id', $args['grupo_convenio']);
        }
        if (isset($args['procedimento']) && strlen($args['procedimento']) > 0) {
            $this->db->where('pt.nome ilike', $args['procedimento'] . "%");
        }
        if (isset($args['codigo']) && strlen($args['codigo']) > 0) {
            $this->db->where('pt.codigo ilike', $args['codigo'] . "%");
        }
        if (isset($args['grupo']) && strlen($args['grupo']) > 0) {
            $this->db->where('pt.grupo', $args['grupo']);
        }





//        if ($orcamento_id != null) {
//            $this->db->where("oi.orcamento_id !=", $orcamento_id);
//        }
//        $this->db->where("ao.paciente_id", $paciente_id);
//        $this->db->orderby("oi.data_cadastro");

        return $this->db;
    }

    function listarmedidas() {

        $this->db->select('');
        $this->db->from('tb_estoque_unidade');
        $this->db->where('ativo', 't');
        return $this->db->get()->result();
    }
    
    function verificarconvenioprocedimento($convenio_id,$procedimento_tuss_id){
        $this->db->select('');
        $this->db->from('tb_procedimento_convenio');
        $this->db->where('ativo','t');
        $this->db->where('convenio_id',$convenio_id);
        $this->db->where('procedimento_tuss_id',$procedimento_tuss_id);
        $resultado  = $this->db->get()->result();
        if ((count($resultado) >= 1)) {
              return -1;
        }        
    }

    function listaroperadorimportacaoadd($chave){        
        $this->db->select('op.nome');
        $this->db->from('tb_medicos_importacao_add mi');
        $this->db->join('tb_operador op','op.operador_id = mi.operador_id','left');
        $this->db->where('mi.chave',$chave);
        $this->db->where('mi.ativo','t');
        return $this->db->get()->result();
        
    }
    
    function autualizartabelaoperadorimportados($chave,$nome_arq){        
        $this->db->set('chave',$nome_arq);
        $this->db->where('chave',$chave);
        $this->db->update('tb_medicos_importacao_add');        
    }
    
   
     function listarprocedimentogrupoautocomplete($parametro) {
        $this->db->select('pt.procedimento_tuss_id,
                           pt.nome');
        $this->db->from('tb_procedimento_tuss pt');
        $this->db->where('pt.ativo', 'true'); 
        if ($parametro != 0) {  
          $this->db->where_in("pt.grupo",$parametro);
        }
        $return = $this->db->get();
        return $return->result();
    }
    
}

?>
