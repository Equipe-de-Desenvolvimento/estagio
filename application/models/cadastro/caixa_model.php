<?php

class caixa_model extends Model {

    var $_saida_id = null;
    var $_devedor = null;
    var $_razao_social = null;
    var $_data = null;
    var $_valor = null;
    var $_observacao = null;
    var $_tipo = null;
    var $_forma = null;
    var $_classe = null;

    function caixa_model($saida_id = null) {
        parent::Model();
        if (isset($saida_id)) {
            $this->instanciar($saida_id);
        }
    }

    function listarentrada($args = array()) {
        
        
      
        if (isset($args['nome']) && strlen($args['nome']) > 0 && ($args['nome'] != "CAIXA" && $args['nome'] != "TRANSFERENCIA")) {
            $this->db->select('
                            tes.descricao
                            ');
            $this->db->from('tb_tipo_entradas_saida tes');
            $this->db->where('tes.ativo', 'true');
            $this->db->where('tes.tipo_entradas_saida_id', $args['nome']);
            $return = $this->db->get()->result();
        }else{
            $return = Array();  
        }   

        $this->db->select('valor,
                            entradas_id,
                            contas_receber_id,
                            e.observacao,
                            fe.descricao as conta,
                            data,
                            fcd.razao_social,
                            tipo,
                            classe');
        $this->db->from('tb_entradas e');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = e.conta', 'left');
        $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = e.nome', 'left');
//        $this->db->join('tb_financeiro_classe fc', 'fc.descricao = e.classe', 'left');
        $this->db->where('e.ativo', 'true');
        $empresa_id = $this->session->userdata('empresa_id');
        
        if (isset($args['txtempresa']) && strlen($args['txtempresa']) > 0) {
            if ($args['txtempresa'] != '0'){
                $this->db->where('e.empresa_id', $args['txtempresa']);
            }
        }
        else{
            $this->db->where("e.empresa_id", $empresa_id);
        }
        if (isset($args['empresa']) && strlen($args['empresa']) > 0) {
            $this->db->where('e.nome', $args['empresa']);
        }
        
        if (isset($args['nome']) && strlen($args['nome']) > 0) { 
            
            if($args['nome'] == "CAIXA"){
              $this->db->where('tipo', 'CAIXA');  
            }elseif($args['nome'] == "TRANSFERENCIA"){
              $this->db->where('tipo', 'TRANSFERENCIA'); 
            }else{
              $this->db->where('tipo', $return[0]->descricao);
            }
        }
        if (isset($args['nome_classe']) && strlen($args['nome_classe']) > 0) {

            $this->db->where('classe', $args['nome_classe']);
        }
        if (isset($args['conta']) && strlen($args['conta']) > 0) {
            $this->db->where('e.conta', $args['conta']);
        }
        if (isset($args['idfinanceiro']) && $args['idfinanceiro'] > 0) {
            $this->db->where('e.entradas_id', $args['idfinanceiro']);
        }
        if (isset($args['idcontasreceber']) && $args['idcontasreceber'] > 0) {
            $this->db->where('e.contas_receber_id', $args['idcontasreceber']);
        }
        if (isset($args['datainicio']) && strlen($args['datainicio']) > 0) {
            $this->db->where('e.data >=', date("Y-m-d", strtotime(str_replace('/', '-', $args['datainicio']))));
        }
        if (isset($args['obs']) && strlen($args['obs']) != '') {
            $this->db->where('e.observacao ilike', "%" . $args['obs'] . "%");
        }
        if (isset($args['datafim']) && strlen($args['datafim']) > 0) {
            $this->db->where('e.data <=', date("Y-m-d", strtotime(str_replace('/', '-', $args['datafim']))));
        }
        return $this->db;
    }

    
    function listarentradatotal($args = array()) {
        if (isset($args['nome']) && strlen($args['nome']) > 0 && ($args['nome'] != "CAIXA" && $args['nome'] != "TRANSFERENCIA")) {
            $this->db->select('
                            tes.descricao
                            ');
            $this->db->from('tb_tipo_entradas_saida tes');
            $this->db->where('tes.ativo', 'true');
            $this->db->where('tes.tipo_entradas_saida_id', $args['nome']);
            $return = $this->db->get()->result();
        }else{
            $return = Array();
        }

        $this->db->select('sum(e.valor) as total');
        $this->db->from('tb_entradas e');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = e.conta', 'left');
        $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = e.nome', 'left');
//        $this->db->join('tb_financeiro_classe fc', 'fc.descricao = e.classe', 'left');
        $this->db->where('e.ativo', 'true');
        $empresa_id = $this->session->userdata('empresa_id');
        
        if (isset($args['txtempresa']) && strlen($args['txtempresa']) > 0) {
            if ($args['txtempresa'] != '0'){
                $this->db->where('e.empresa_id', $args['txtempresa']);
            }
        }
        else{
            $this->db->where("e.empresa_id", $empresa_id);
        }
        if (isset($args['empresa']) && strlen($args['empresa']) > 0) {
            $this->db->where('e.nome', $args['empresa']);
        }
        if (isset($args['nome']) && strlen($args['nome']) > 0) { 
            if($args['nome'] == "CAIXA"){
              $this->db->where('tipo', 'CAIXA');  
            }elseif($args['nome'] == "TRANSFERENCIA"){
              $this->db->where('tipo', 'TRANSFERENCIA'); 
            }else{
              $this->db->where('tipo', $return[0]->descricao);
            }
        }
        if (isset($args['nome_classe']) && strlen($args['nome_classe']) > 0) {

            $this->db->where('classe', $args['nome_classe']);
        }
        if (isset($args['conta']) && strlen($args['conta']) > 0) {
            $this->db->where('e.conta', $args['conta']);
        }
        if (isset($args['datainicio']) && strlen($args['datainicio']) > 0) {
            $this->db->where('e.data >=', date("Y-m-d", strtotime(str_replace('/', '-', $args['datainicio']))));
        }
        if (isset($args['obs']) && strlen($args['obs']) != '') {
            $this->db->where('e.observacao ilike', "%" . $args['obs'] . "%");
        }
        if (isset($args['datafim']) && strlen($args['datafim']) > 0) {
            $this->db->where('e.data <=', date("Y-m-d", strtotime(str_replace('/', '-', $args['datafim']))));
        }
        $retorno =$this->db->get()->result();
        if(count($retorno) > 0){
            $valor_total = $retorno[0]->total;
        }else{
            $valor_total = 0;
        }
        return $valor_total;
    }

    function listarsaida($args = array()) {


        if (isset($args['nome']) && strlen($args['nome']) > 0 && $args['nome'] != 'TRANSFERENCIA') {
            $this->db->select('
                            tes.descricao
                            ');
            $this->db->from('tb_tipo_entradas_saida tes');
            $this->db->where('tes.ativo', 'true');
            $this->db->where('tes.tipo_entradas_saida_id', $args['nome']);
            $return = $this->db->get()->result();
            $tipo = $return[0]->descricao;
        } else {
            $tipo = 'TRANSFERENCIA';
        }


        $this->db->select('s.valor,
                            s.saidas_id,
                            s.contas_pagar_id,
                            s.observacao,
                            s.data,
                            fcd.razao_social,
                            fe.descricao as conta,
                            s.tipo,
                            s.classe');
        $this->db->from('tb_saidas s');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = s.conta', 'left');
        $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = s.nome', 'left');
//        $this->db->join('tb_financeiro_classe fc', 'fc.descricao = s.classe', 'left');
        $this->db->where('s.ativo', 'true');
        $empresa_id = $this->session->userdata('empresa_id');
//        $this->db->where("empresa_id", $empresa_id);
        

        if (isset($args['empresa']) && strlen($args['empresa']) > 0) {
            $this->db->where('s.nome', $args['empresa']);
        }
        if (isset($args['txtempresa']) && strlen($args['txtempresa']) > 0) {
            if ($args['txtempresa'] != '0'){
                $this->db->where('s.empresa_id', $args['txtempresa']);
            }
        }
        else{
            $this->db->where("s.empresa_id", $empresa_id);
        }
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('tipo', $tipo);
        }
        if (isset($args['nome_classe']) && strlen($args['nome_classe']) > 0) {
            $this->db->where('classe', $args['nome_classe']);
        }
        if (isset($args['conta']) && strlen($args['conta']) > 0) {
            $this->db->where('s.conta', $args['conta']);
        }
        if (isset($args['idfinanceiro']) && $args['idfinanceiro'] > 0) {
            $this->db->where('s.saidas_id', $args['idfinanceiro']);
        }
        if (isset($args['idcontasapagar']) && $args['idcontasapagar'] > 0) {
            $this->db->where('s.contas_pagar_id', $args['idcontasapagar']);
        }
        if (isset($args['datainicio']) && strlen($args['datainicio']) > 0) {
            $this->db->where('s.data >=', date("Y-m-d", strtotime(str_replace('/', '-', $args['datainicio']))));
        }
        if (isset($args['datafim']) && strlen($args['datafim']) > 0) {
            $this->db->where('s.data <=', date("Y-m-d", strtotime(str_replace('/', '-', $args['datafim']))));
        }
        if (isset($args['obs']) && strlen($args['obs']) != '') {
            $this->db->where('s.observacao ilike', "%" . $args['obs'] . "%");
        }
        return $this->db;
    }

    function listarsaidatotal($args = array()) {


        if (isset($args['nome']) && strlen($args['nome']) > 0 && $args['nome'] != 'TRANSFERENCIA') {
            $this->db->select('
                            tes.descricao
                            ');
            $this->db->from('tb_tipo_entradas_saida tes');
            $this->db->where('tes.ativo', 'true');
            $this->db->where('tes.tipo_entradas_saida_id', $args['nome']);
            $return = $this->db->get()->result();
            $tipo = $return[0]->descricao;
        } else {
            $tipo = 'TRANSFERENCIA';
        }


        $this->db->select('sum(s.valor) as total');
        $this->db->from('tb_saidas s');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = s.conta', 'left');
        $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = s.nome', 'left');
//        $this->db->join('tb_financeiro_classe fc', 'fc.descricao = s.classe', 'left');
        $this->db->where('s.ativo', 'true');
        $empresa_id = $this->session->userdata('empresa_id');
//        $this->db->where("empresa_id", $empresa_id);
        

        if (isset($args['empresa']) && strlen($args['empresa']) > 0) {
            $this->db->where('s.nome', $args['empresa']);
        }
        if (isset($args['txtempresa']) && strlen($args['txtempresa']) > 0) {
            if ($args['txtempresa'] != '0'){
                $this->db->where('s.empresa_id', $args['txtempresa']);
            }
        }
        else{
            $this->db->where("s.empresa_id", $empresa_id);
        }
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('tipo', $tipo);
        }
        if (isset($args['nome_classe']) && strlen($args['nome_classe']) > 0) {
            $this->db->where('classe', $args['nome_classe']);
        }
        if (isset($args['conta']) && strlen($args['conta']) > 0) {
            $this->db->where('s.conta', $args['conta']);
        }
        if (isset($args['datainicio']) && strlen($args['datainicio']) > 0) {
            $this->db->where('s.data >=', date("Y-m-d", strtotime(str_replace('/', '-', $args['datainicio']))));
        }
        if (isset($args['datafim']) && strlen($args['datafim']) > 0) {
            $this->db->where('s.data <=', date("Y-m-d", strtotime(str_replace('/', '-', $args['datafim']))));
        }
        if (isset($args['obs']) && strlen($args['obs']) != '') {
            $this->db->where('s.observacao ilike', "%" . $args['obs'] . "%");
        }
        $retorno =$this->db->get()->result();
        if(count($retorno) > 0){
            $valor_total = $retorno[0]->total;
        }else{
            $valor_total = 0;
        }
        return $valor_total;
    }

    function listarpermissoesempresa(){


        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ep.financ_4n');
        $this->db->from('tb_empresa_permissoes ep');
        $this->db->where('ep.empresa_id', $empresa_id);
        $retorno = $this->db->get()->result();

        return $retorno;

    }

    function listarsangria($args = array()) {
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('s.valor,
                            s.sangria_id,
                            s.observacao,
                            s.data,
                            s.ativo,
                            o.nome as operador,
                            op.nome as operador_caixa');
        $this->db->from('tb_sangria s');
        $this->db->join('tb_operador o', 'o.operador_id = s.operador_cadastro', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = s.operador_caixa', 'left');
        $this->db->where('s.empresa_id', $empresa_id);
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('s.operador_cadastro', $args['nome']);
        }
        if (isset($args['datainicio']) && strlen($args['datainicio']) > 0) {
            $this->db->where('s.data >=', date("Y-m-d", strtotime(str_replace('/', '-', $args['datainicio']))));
        }
        if (isset($args['datafim']) && strlen($args['datafim']) > 0) {
            $this->db->where('s.data <=', date("Y-m-d", strtotime(str_replace('/', '-', $args['datafim']))));
        }
        if (isset($args['obs']) && strlen($args['obs']) != '') {
            $this->db->where('s.observacao ilike', "%" . $args['obs'] . "%");
        }
        return $this->db;
    }

    function listarsangriacaixa() {
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('s.valor,
                            s.sangria_id,
                            s.observacao,
                            s.data,
                            s.ativo,
                            o.nome as operador,
                            op.nome as operador_caixa');
        $this->db->from('tb_sangria s');
        $this->db->join('tb_operador o', 'o.operador_id = s.operador_cadastro', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = s.operador_caixa', 'left');
        $this->db->where('s.ativo', 't');
        if (@$_POST['empresa'] > 0) {
            $this->db->where('s.empresa_id', @$_POST['empresa']);
        }

        $this->db->where('s.data >=', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio']))));
        $this->db->where('s.data <=', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim']))));
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriosaida() {
        if ($_POST['tipo'] > 0) {
            $this->db->select('
                            tes.descricao
                            ');
            $this->db->from('tb_tipo_entradas_saida tes');
            $this->db->where('tes.ativo', 'true');
            $this->db->where('tes.tipo_entradas_saida_id', $_POST['tipo']);
            $return = $this->db->get()->result();
        }

        $this->db->select('s.valor,
                            s.saidas_id,
                            s.observacao,
                            s.data,
                            e.nome as empresa,
                            fcd.razao_social,
                            fe.descricao as conta,
                            s.tipo,
                            s.classe');
        $this->db->from('tb_saidas s');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = s.conta', 'left');
        $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = s.nome', 'left');
        $this->db->join('tb_empresa e', 's.empresa_id = e.empresa_id', 'left');
//        $this->db->join('tb_financeiro_classe fc', 'fc.descricao = s.classe', 'left');
        $this->db->where('s.ativo', 'true');
        if ($_POST['credordevedor'] != 0) {
            $this->db->where('fcd.financeiro_credor_devedor_id ', $_POST['credordevedor']);
        }
//        if ($_POST['tipo'] != 0) {
//            $this->db->where('tipo_id', $_POST['tipo']);
//        }
        if ($_POST['empresa'] != "") {
            $this->db->where('s.empresa_id', $_POST['empresa']);
        }

        if ($_POST['tipo'] > 0) {
//            var_dump($args['nome']); die;
            $this->db->where('tipo', @$return[0]->descricao);
        }
        if ($_POST['classe'] != '') {
            $this->db->where('s.classe', $_POST['classe']);
        }
        if ($_POST['conta'] != 0) {
            $this->db->where('s.conta', $_POST['conta']);
        }
        $this->db->where('s.data >=', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio']))));
        $this->db->where('s.data <=', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim']))));
        $this->db->orderby('s.data');
        $this->db->orderby('fcd.razao_social');
        $return = $this->db->get();
        return $return->result();
    }

    function painelfinanceirosaida() {
        if (@$_GET['txtdata_inicio'] != '') {
            $data_inicio = date("Y-m-d", strtotime(str_replace('/', '-', @$_GET['txtdata_inicio'])));
        } else {
            $data_inicio = date('Y-m-01');
        }
        if (@$_GET['txtdata_fim'] != '') {
            $data_fim = date("Y-m-d", strtotime(str_replace('/', '-', @$_GET['txtdata_fim'])));
        } else {
            $data_fim = date('Y-m-t');
        }


        $this->db->select('sum(s.valor) as valor_total');
        $this->db->from('tb_saidas s');
        $this->db->where('s.ativo', 'true');
        $this->db->where('s.data >=', $data_inicio);
        $this->db->where('s.data <=', $data_fim);
        $this->db->where("(s.tipo !='TRANSFERENCIA' OR s.tipo is null)");
        $empresa_id = $this->session->userdata('empresa_id');
        if (isset($args['txtempresa']) && strlen($args['txtempresa']) > 0) {
            $this->db->where('s.empresa_id', $args['txtempresa']);
        } else {
            $this->db->where("s.empresa_id", $empresa_id);
        }
//        $this->db->groupby('s.valor');
        $return = $this->db->get();
        return $return->result();
    }

    function painelfinanceiroentrada() {
        if (@$_GET['txtdata_inicio'] != '') {
            $data_inicio = date("Y-m-d", strtotime(str_replace('/', '-', @$_GET['txtdata_inicio'])));
        } else {
            $data_inicio = date('Y-m-01');
        }
        if (@$_GET['txtdata_fim'] != '') {
            $data_fim = date("Y-m-d", strtotime(str_replace('/', '-', @$_GET['txtdata_fim'])));
        } else {
            $data_fim = date('Y-m-t');
        }

        $this->db->select('sum(s.valor) as valor_total');
        $this->db->from('tb_entradas s');
        $this->db->where('s.ativo', 'true');
        $this->db->where('s.data >=', $data_inicio);
        $this->db->where('s.data <=', $data_fim);
        $this->db->where("(s.tipo !='TRANSFERENCIA' OR s.tipo is null)");
        $empresa_id = $this->session->userdata('empresa_id');
        if (isset($args['txtempresa']) && strlen($args['txtempresa']) > 0) {
            $this->db->where('s.empresa_id', $args['txtempresa']);
        } else {
            $this->db->where("s.empresa_id", $empresa_id);
        }
//        $this->db->groupby('s.valor');
        $return = $this->db->get();
        return $return->result();
    }

    function painelfinanceirocontaspagar() {
        if (@$_GET['txtdata_inicio'] != '') {
            $data_inicio = date("Y-m-d", strtotime(str_replace('/', '-', @$_GET['txtdata_inicio'])));
        } else {
            $data_inicio = date('Y-m-01');
        }
        if (@$_GET['txtdata_fim'] != '') {
            $data_fim = date("Y-m-d", strtotime(str_replace('/', '-', @$_GET['txtdata_fim'])));
        } else {
            $data_fim = date('Y-m-t');
        }


        $this->db->select('sum(s.valor) as valor_total');
        $this->db->from('tb_financeiro_contaspagar s');
        $this->db->where('s.ativo', 'true');
        $this->db->where('s.data >=', $data_inicio);
        $this->db->where('s.data <=', $data_fim);
        $empresa_id = $this->session->userdata('empresa_id');
        if (isset($args['txtempresa']) && strlen($args['txtempresa']) > 0) {
            $this->db->where('s.empresa_id', $args['txtempresa']);
        } else {
            $this->db->where("s.empresa_id", $empresa_id);
        }
//        $this->db->groupby('s.valor');
        $return = $this->db->get();
        return $return->result();
    }

    function painelfinanceirocontasreceber() {
        if (@$_GET['txtdata_inicio'] != '') {
            $data_inicio = date("Y-m-d", strtotime(str_replace('/', '-', @$_GET['txtdata_inicio'])));
        } else {
            $data_inicio = date('Y-m-01');
        }
        if (@$_GET['txtdata_fim'] != '') {
            $data_fim = date("Y-m-d", strtotime(str_replace('/', '-', @$_GET['txtdata_fim'])));
        } else {
            $data_fim = date('Y-m-t');
        }

        $this->db->select('sum(s.valor) as valor_total');
        $this->db->from('tb_financeiro_contasreceber s');
        $this->db->where('s.ativo', 'true');
        $this->db->where('s.data >=', $data_inicio);
        $this->db->where('s.data <=', $data_fim);
        $empresa_id = $this->session->userdata('empresa_id');
        if (isset($args['txtempresa']) && strlen($args['txtempresa']) > 0) {
            $this->db->where('s.empresa_id', $args['txtempresa']);
        } else {
            $this->db->where("s.empresa_id", $empresa_id);
        }
//        $this->db->groupby('s.valor');
        $return = $this->db->get();
        return $return->result();
    }

    function painelfinanceiromessaida() {
        if (@$_GET['txtdata_inicio'] != '') {
            $data_inicio = date("Y-m-01", strtotime(str_replace('/', '-', @$_GET['txtdata_inicio'])));
        } else {
            $data_inicio = date('Y-m-01');
        }
        if (@$_GET['txtdata_fim'] != '') {
            $data_fim = date("Y-m-t", strtotime(str_replace('/', '-', @$_GET['txtdata_fim'])));
        } else {
            $data_fim = date('Y-m-t');
        }

        $this->db->select('sum(s.valor) as valor_total');
        $this->db->from('tb_saidas s');
        $this->db->where('s.ativo', 'true');
        $this->db->where('s.data >=', $data_inicio);
        $this->db->where('s.data <=', $data_fim);
        $this->db->where("(s.tipo !='TRANSFERENCIA' OR s.tipo is null)");
        $empresa_id = $this->session->userdata('empresa_id');
        if (isset($args['txtempresa']) && strlen($args['txtempresa']) > 0) {
            $this->db->where('s.empresa_id', $args['txtempresa']);
        } else {
            $this->db->where("s.empresa_id", $empresa_id);
        }
//        $this->db->groupby('s.valor');
        $return = $this->db->get();
        return $return->result();
    }

    function painelfinanceiromesentrada() {
        if (@$_GET['txtdata_inicio'] != '') {
            $data_inicio = date("Y-m-01", strtotime(str_replace('/', '-', @$_GET['txtdata_inicio'])));
        } else {
            $data_inicio = date('Y-m-01');
        }
        if (@$_GET['txtdata_fim'] != '') {
            $data_fim = date("Y-m-t", strtotime(str_replace('/', '-', @$_GET['txtdata_fim'])));
        } else {
            $data_fim = date('Y-m-t');
        }

        $this->db->select('sum(s.valor) as valor_total');
        $this->db->from('tb_entradas s');
        $this->db->where('s.ativo', 'true');
        $this->db->where('s.data >=', $data_inicio);
        $this->db->where('s.data <=', $data_fim);
        $this->db->where("(s.tipo !='TRANSFERENCIA' OR s.tipo is null)");
        $empresa_id = $this->session->userdata('empresa_id');
        if (isset($args['txtempresa']) && strlen($args['txtempresa']) > 0) {
            $this->db->where('s.empresa_id', $args['txtempresa']);
        } else {
            $this->db->where("s.empresa_id", $empresa_id);
        }
//        $this->db->groupby('s.valor');
        $return = $this->db->get();
        return $return->result();
    }

    function painelfinanceiromescontaspagar() {
        if (@$_GET['txtdata_inicio'] != '') {
            $data_inicio = date("Y-m-01", strtotime(str_replace('/', '-', @$_GET['txtdata_inicio'])));
        } else {
            $data_inicio = date('Y-m-01');
        }
        if (@$_GET['txtdata_fim'] != '') {
            $data_fim = date("Y-m-t", strtotime(str_replace('/', '-', @$_GET['txtdata_fim'])));
        } else {
            $data_fim = date('Y-m-t');
        }


        $this->db->select('sum(s.valor) as valor_total');
        $this->db->from('tb_financeiro_contaspagar s');
        $this->db->where('s.ativo', 'true');
        $this->db->where('s.data >=', $data_inicio);
        $this->db->where('s.data <=', $data_fim);
        $empresa_id = $this->session->userdata('empresa_id');
        if (isset($args['txtempresa']) && strlen($args['txtempresa']) > 0) {
            $this->db->where('s.empresa_id', $args['txtempresa']);
        } else {
            $this->db->where("s.empresa_id", $empresa_id);
        }
//        $this->db->groupby('s.valor');
        $return = $this->db->get();
        return $return->result();
    }

    function painelfinanceiromescontasreceber() {
        if (@$_GET['txtdata_inicio'] != '') {
            $data_inicio = date("Y-m-01", strtotime(str_replace('/', '-', @$_GET['txtdata_inicio'])));
        } else {
            $data_inicio = date('Y-m-01');
        }
        if (@$_GET['txtdata_fim'] != '') {
            $data_fim = date("Y-m-t", strtotime(str_replace('/', '-', @$_GET['txtdata_fim'])));
        } else {
            $data_fim = date('Y-m-t');
        }

        $this->db->select('sum(s.valor) as valor_total');
        $this->db->from('tb_financeiro_contasreceber s');
        $this->db->where('s.ativo', 'true');
        $this->db->where('s.data >=', $data_inicio);
        $this->db->where('s.data <=', $data_fim);
        $empresa_id = $this->session->userdata('empresa_id');
        if (isset($args['txtempresa']) && strlen($args['txtempresa']) > 0) {
            $this->db->where('s.empresa_id', $args['txtempresa']);
        } else {
            $this->db->where("s.empresa_id", $empresa_id);
        }
//        $this->db->groupby('s.valor');
        $return = $this->db->get();
        return $return->result();
    }

    function painelfinanceiromesatualsaida() {

        $data_inicio = date('Y-m-01');
        $data_fim = date('Y-m-t');

        $this->db->select('sum(s.valor) as valor_total');
        $this->db->from('tb_saidas s');
        $this->db->where('s.ativo', 'true');
        $this->db->where('s.data >=', $data_inicio);
        $this->db->where('s.data <=', $data_fim);
        $this->db->where("(s.tipo !='TRANSFERENCIA' OR s.tipo is null)");
        $empresa_id = $this->session->userdata('empresa_id');
        if (isset($args['txtempresa']) && strlen($args['txtempresa']) > 0) {
            $this->db->where('s.empresa_id', $args['txtempresa']);
        } else {
            $this->db->where("s.empresa_id", $empresa_id);
        }
//        $this->db->groupby('s.valor');
        $return = $this->db->get();
        return $return->result();
    }

    function painelfinanceiromesatualentrada() {
        $data_inicio = date('Y-m-01');
        $data_fim = date('Y-m-t');

        $this->db->select('sum(s.valor) as valor_total');
        $this->db->from('tb_entradas s');
        $this->db->where('s.ativo', 'true');
        $this->db->where('s.data >=', $data_inicio);
        $this->db->where('s.data <=', $data_fim);
        $this->db->where("(s.tipo !='TRANSFERENCIA' OR s.tipo is null)");
        $empresa_id = $this->session->userdata('empresa_id');
        if (isset($args['txtempresa']) && strlen($args['txtempresa']) > 0) {
            $this->db->where('s.empresa_id', $args['txtempresa']);
        } else {
            $this->db->where("s.empresa_id", $empresa_id);
        }
//        $this->db->groupby('s.valor');
        $return = $this->db->get();
        return $return->result();
    }

    function painelfinanceiromesatualcontaspagar() {
        $data_inicio = date('Y-m-01');
        $data_fim = date('Y-m-t');


        $this->db->select('sum(s.valor) as valor_total');
        $this->db->from('tb_financeiro_contaspagar s');
        $this->db->where('s.ativo', 'true');
        $this->db->where('s.data >=', $data_inicio);
        $this->db->where('s.data <=', $data_fim);
        $empresa_id = $this->session->userdata('empresa_id');
        if (isset($args['txtempresa']) && strlen($args['txtempresa']) > 0) {
            $this->db->where('s.empresa_id', $args['txtempresa']);
        } else {
            $this->db->where("s.empresa_id", $empresa_id);
        }
//        $this->db->groupby('s.valor');
        $return = $this->db->get();
        return $return->result();
    }

    function painelfinanceiromesatualcontasreceber() {
        $data_inicio = date('Y-m-01');
        $data_fim = date('Y-m-t');

        $this->db->select('sum(s.valor) as valor_total');
        $this->db->from('tb_financeiro_contasreceber s');
        $this->db->where('s.ativo', 'true');
        $this->db->where('s.data >=', $data_inicio);
        $this->db->where('s.data <=', $data_fim);
        $empresa_id = $this->session->userdata('empresa_id');
        if (isset($args['txtempresa']) && strlen($args['txtempresa']) > 0) {
            $this->db->where('s.empresa_id', $args['txtempresa']);
        } else {
            $this->db->where("s.empresa_id", $empresa_id);
        }
//        $this->db->groupby('s.valor');
        $return = $this->db->get()->result();
//        var_dump($return); die;
        return $return;
    }

    function painelfinanceirorecebimento() {
        if (@$_GET['txtdata_inicio'] != '') {
            $data_inicio = date("Y-m-d", strtotime(str_replace('/', '-', @$_GET['txtdata_inicio'])));
        } else {
            $data_inicio = date('Y-m-01');
        }
        if (@$_GET['txtdata_fim'] != '') {
            $data_fim = date("Y-m-t", strtotime(str_replace('/', '-', @$_GET['txtdata_fim'])));
        } else {
            $data_fim = date('Y-m-t');
        }

        $this->db->select('sum(s.valor) as valor_total');
        $this->db->from('tb_financeiro_contasreceber s');
        $this->db->where('s.ativo', 'true');
        $this->db->where('s.data >=', $data_inicio);
        $this->db->where('s.data <=', $data_fim);
        $empresa_id = $this->session->userdata('empresa_id');
        if (isset($args['txtempresa']) && strlen($args['txtempresa']) > 0) {
            $this->db->where('s.empresa_id', $args['txtempresa']);
        } else {
            $this->db->where("s.empresa_id", $empresa_id);
        }
//        $this->db->groupby('s.valor');
        $return = $this->db->get();
        return $return->result();
    }

    function relatorioprevisaolaboratoriocontaspagar() {

        if (@$_GET['txtdata_inicio'] != '') {
            $data_inicio = date("Y-m-d", strtotime(str_replace('/', '-', @$_GET['txtdata_inicio'])));
        } else {
            $data_inicio = date('Y-m-01');
        }
        if (@$_GET['txtdata_fim'] != '') {
            $data_fim = date("Y-m-d", strtotime(str_replace('/', '-', @$_GET['txtdata_fim'])));
        } else {
            $data_fim = date('Y-m-t');
        }

        $this->db->select(" ae.percentual_laboratorio,
                            ae.valor_laboratorio,
                            ae.data,
                            ae.valor_total,
                            lab.nome as laboratorio,
                            ae.laboratorio_id,
                            ae.confirmacao_previsao_labotorio,
                            lab.conta_id,
                            lab.credor_devedor_id,
                            lab.tipo,
                            lab.classe");
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_laboratorio lab', 'lab.laboratorio_id = ae.laboratorio_id', 'left');
        $this->db->where('ae.procedimento_tuss_id is not null');
        $this->db->where('ae.laboratorio_id is not null');

        $this->db->where("ae.data >=", $data_inicio);
        $this->db->where("ae.data <=", $data_fim);
        $empresa_id = $this->session->userdata('empresa_id');
        if (isset($args['txtempresa']) && strlen($args['txtempresa']) > 0) {
            $this->db->where('ae.empresa_id', $args['txtempresa']);
        } else {
            $this->db->where("ae.empresa_id", $empresa_id);
        }
        $this->db->orderby('lab.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function relatorioprevisaopromotorcontaspagar() {
        if (@$_GET['txtdata_inicio'] != '') {
            $data_inicio = date("Y-m-d", strtotime(str_replace('/', '-', @$_GET['txtdata_inicio'])));
        } else {
            $data_inicio = date('Y-m-01');
        }
        if (@$_GET['txtdata_fim'] != '') {
            $data_fim = date("Y-m-d", strtotime(str_replace('/', '-', @$_GET['txtdata_fim'])));
        } else {
            $data_fim = date('Y-m-t');
        }

        $this->db->select(' ae.valor_promotor,
                            ae.percentual_promotor, 
                            ae.valor_total,
                            pi.nome as promotor,
                            ae.indicacao,
                            ae.confirmacao_previsao_promotor,
                            pi.credor_devedor_id,
                            pi.conta_id,
                            pi.classe,
                            pi.tipo_id');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'ae.paciente_id = p.paciente_id');
        $this->db->join('tb_paciente_indicacao pi', 'ae.indicacao = pi.paciente_indicacao_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where('ae.procedimento_tuss_id is not null');
        $this->db->where('ae.indicacao is not null');

        $this->db->where("ae.data >=", $data_inicio);
        $this->db->where("ae.data <=", $data_fim);
        $empresa_id = $this->session->userdata('empresa_id');
        if (isset($args['txtempresa']) && strlen($args['txtempresa']) > 0) {
            $this->db->where('ae.empresa_id', $args['txtempresa']);
        } else {
            $this->db->where("ae.empresa_id", $empresa_id);
        }
        $this->db->orderby('pi.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function relatorioprevisaomedicacontaspagar() {
        if (@$_GET['txtdata_inicio'] != '') {
            $data_inicio = date("Y-m-d", strtotime(str_replace('/', '-', @$_GET['txtdata_inicio'])));
        } else {
            $data_inicio = date('Y-m-01');
        }
        if (@$_GET['txtdata_fim'] != '') {
            $data_fim = date("Y-m-d", strtotime(str_replace('/', '-', @$_GET['txtdata_fim'])));
        } else {
            $data_fim = date('Y-m-t');
        }

        $this->db->select(" ae.valor_total,
                            pc.procedimento_tuss_id,
                            pc.procedimento_convenio_id,
                            pt.perc_medico,
                            pt.percentual,
                            ae.medico_agenda,
                            op.nome as medico,
                            op.taxa_administracao,
                            (
                                SELECT mc.valor FROM ponto.tb_procedimento_percentual_medico_convenio mc
                                INNER JOIN ponto.tb_procedimento_percentual_medico m 
                                ON m.procedimento_percentual_medico_id = mc.procedimento_percentual_medico_id
                                WHERE m.procedimento_tuss_id = pc.procedimento_convenio_id
                                AND m.ativo = 't'
                                AND mc.medico = ae.medico_agenda
                                AND mc.ativo = 't'
                                LIMIT 1
                            ) AS perc_medico_excecao,
                            (
                                SELECT mc.percentual FROM ponto.tb_procedimento_percentual_medico_convenio mc
                                INNER JOIN ponto.tb_procedimento_percentual_medico m 
                                ON m.procedimento_percentual_medico_id = mc.procedimento_percentual_medico_id
                                WHERE m.procedimento_tuss_id = pc.procedimento_convenio_id
                                AND m.ativo = 't'
                                AND mc.medico = ae.medico_agenda
                                AND mc.ativo = 't'
                                LIMIT 1
                            ) AS percentual_excecao,
                            ae.confirmacao_previsao_medico,
                            op.tipo_id,
                            op.conta_id,
                            op.credor_devedor_id,
                            op.classe,
                            ae.confirmacao_previsao_medico");
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ae.medico_agenda', 'left');
        $this->db->where('ae.procedimento_tuss_id is not null');
//        $this->db->where('c.dinheiro', 't');

        $this->db->where("ae.data >=", $data_inicio);
        $this->db->where("ae.data <=", $data_fim);
        $empresa_id = $this->session->userdata('empresa_id');
        if (isset($args['txtempresa']) && strlen($args['txtempresa']) > 0) {
            $this->db->where('ae.empresa_id', $args['txtempresa']);
        } else {
            $this->db->where("ae.empresa_id", $empresa_id);
        }
        $this->db->orderby('op.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function relatorioprevisaoconveniocontasreceber() {
        if (@$_GET['txtdata_inicio'] != '') {
            $data_inicio = date("Y-m-d", strtotime(str_replace('/', '-', @$_GET['txtdata_inicio'])));
        } else {
            $data_inicio = date('Y-m-01');
        }
        if (@$_GET['txtdata_fim'] != '') {
            $data_fim = date("Y-m-d", strtotime(str_replace('/', '-', @$_GET['txtdata_fim'])));
        } else {
            $data_fim = date('Y-m-t');
        }
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select(' pc.valortotal as valor_procedimento,
                            ae.valor_total,
                            ae.data,
                            c.nome as convenio,
                            credor_devedor_id,
                            c.dia_aquisicao,
                            c.convenio_id,
                            c.conta_id,
                            ae.confirmacao_recebimento_convenio');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where("ae.data >=", $data_inicio);
        $this->db->where("ae.data <=", $data_fim);
        $this->db->where("c.dinheiro", 'f');
        $this->db->where("c.dia_aquisicao IS NOT NULL");
        $this->db->where('pt.grupo !=', 'CIRURGICO');
        $this->db->where('pt.grupo !=', 'OPME');
        $this->db->where('ae.cancelada', 'f');
        $empresa_id = $this->session->userdata('empresa_id');
        if (isset($args['txtempresa']) && strlen($args['txtempresa']) > 0) {
            $this->db->where('ae.empresa_id', $args['txtempresa']);
        } else {
            $this->db->where("ae.empresa_id", $empresa_id);
        }

        $this->db->orderby('ae.data');
        $this->db->orderby('c.nome');
        $return = $this->db->get();

        return $return->result();
    }

    function relatoriomesprevisaolaboratoriocontaspagar() {

        if (@$_GET['txtdata_inicio'] != '') {
            $data_inicio = date("Y-m-01", strtotime(str_replace('/', '-', @$_GET['txtdata_inicio'])));
        } else {
            $data_inicio = date('Y-m-01');
        }
        if (@$_GET['txtdata_fim'] != '') {
            $data_fim = date("Y-m-t", strtotime(str_replace('/', '-', @$_GET['txtdata_fim'])));
        } else {
            $data_fim = date('Y-m-t');
        }

        $this->db->select(" ae.percentual_laboratorio,
                            ae.valor_laboratorio,
                            ae.data,
                            ae.valor_total,
                            lab.nome as laboratorio,
                            ae.laboratorio_id,
                            ae.confirmacao_previsao_labotorio,
                            lab.conta_id,
                            lab.credor_devedor_id,
                            lab.tipo,
                            lab.classe");
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_laboratorio lab', 'lab.laboratorio_id = ae.laboratorio_id', 'left');
        $this->db->where('ae.procedimento_tuss_id is not null');
        $this->db->where('ae.laboratorio_id is not null');

        $this->db->where("ae.data >=", $data_inicio);
        $this->db->where("ae.data <=", $data_fim);
        $empresa_id = $this->session->userdata('empresa_id');
        if (isset($args['txtempresa']) && strlen($args['txtempresa']) > 0) {
            $this->db->where('ae.empresa_id', $args['txtempresa']);
        } else {
            $this->db->where("ae.empresa_id", $empresa_id);
        }
        $this->db->orderby('lab.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriomesprevisaopromotorcontaspagar() {
        if (@$_GET['txtdata_inicio'] != '') {
            $data_inicio = date("Y-m-01", strtotime(str_replace('/', '-', @$_GET['txtdata_inicio'])));
        } else {
            $data_inicio = date('Y-m-01');
        }
        if (@$_GET['txtdata_fim'] != '') {
            $data_fim = date("Y-m-t", strtotime(str_replace('/', '-', @$_GET['txtdata_fim'])));
        } else {
            $data_fim = date('Y-m-t');
        }

        $this->db->select(' ae.valor_promotor,
                            ae.percentual_promotor, 
                            ae.valor_total,
                            pi.nome as promotor,
                            ae.indicacao,
                            ae.confirmacao_previsao_promotor,
                            pi.credor_devedor_id,
                            pi.conta_id,
                            pi.classe,
                            pi.tipo_id');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'ae.paciente_id = p.paciente_id');
        $this->db->join('tb_paciente_indicacao pi', 'ae.indicacao = pi.paciente_indicacao_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->where('ae.procedimento_tuss_id is not null');
        $this->db->where('ae.indicacao is not null');

        $this->db->where("ae.data >=", $data_inicio);
        $this->db->where("ae.data <=", $data_fim);
        $empresa_id = $this->session->userdata('empresa_id');
        if (isset($args['txtempresa']) && strlen($args['txtempresa']) > 0) {
            $this->db->where('ae.empresa_id', $args['txtempresa']);
        } else {
            $this->db->where("ae.empresa_id", $empresa_id);
        }
        $this->db->orderby('pi.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriomesprevisaomedicacontaspagar() {
        if (@$_GET['txtdata_inicio'] != '') {
            $data_inicio = date("Y-m-01", strtotime(str_replace('/', '-', @$_GET['txtdata_inicio'])));
        } else {
            $data_inicio = date('Y-m-01');
        }
        if (@$_GET['txtdata_fim'] != '') {
            $data_fim = date("Y-m-t", strtotime(str_replace('/', '-', @$_GET['txtdata_fim'])));
        } else {
            $data_fim = date('Y-m-t');
        }

        $this->db->select(" ae.valor_total,
                            pc.procedimento_tuss_id,
                            pc.procedimento_convenio_id,
                            pt.perc_medico,
                            pt.percentual,
                            ae.medico_agenda,
                            op.nome as medico,
                            op.taxa_administracao,
                            (
                                SELECT mc.valor FROM ponto.tb_procedimento_percentual_medico_convenio mc
                                INNER JOIN ponto.tb_procedimento_percentual_medico m 
                                ON m.procedimento_percentual_medico_id = mc.procedimento_percentual_medico_id
                                WHERE m.procedimento_tuss_id = pc.procedimento_convenio_id
                                AND m.ativo = 't'
                                AND mc.medico = ae.medico_agenda
                                AND mc.ativo = 't'
                                LIMIT 1
                            ) AS perc_medico_excecao,
                            (
                                SELECT mc.percentual FROM ponto.tb_procedimento_percentual_medico_convenio mc
                                INNER JOIN ponto.tb_procedimento_percentual_medico m 
                                ON m.procedimento_percentual_medico_id = mc.procedimento_percentual_medico_id
                                WHERE m.procedimento_tuss_id = pc.procedimento_convenio_id
                                AND m.ativo = 't'
                                AND mc.medico = ae.medico_agenda
                                AND mc.ativo = 't'
                                LIMIT 1
                            ) AS percentual_excecao,
                            ae.confirmacao_previsao_medico,
                            op.tipo_id,
                            op.conta_id,
                            op.credor_devedor_id,
                            op.classe,
                            ae.confirmacao_previsao_medico");
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_operador op', 'op.operador_id = ae.medico_agenda', 'left');
        $this->db->where('ae.procedimento_tuss_id is not null');
//        $this->db->where('c.dinheiro', 't');

        $this->db->where("ae.data >=", $data_inicio);
        $this->db->where("ae.data <=", $data_fim);
        $empresa_id = $this->session->userdata('empresa_id');
        if (isset($args['txtempresa']) && strlen($args['txtempresa']) > 0) {
            $this->db->where('ae.empresa_id', $args['txtempresa']);
        } else {
            $this->db->where("ae.empresa_id", $empresa_id);
        }
        $this->db->orderby('op.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriomesprevisaoconveniocontasreceber() {
        if (@$_GET['txtdata_inicio'] != '') {
            $data_inicio = date("Y-m-01", strtotime(str_replace('/', '-', @$_GET['txtdata_inicio'])));
        } else {
            $data_inicio = date('Y-m-01');
        }
        if (@$_GET['txtdata_fim'] != '') {
            $data_fim = date("Y-m-t", strtotime(str_replace('/', '-', @$_GET['txtdata_fim'])));
        } else {
            $data_fim = date('Y-m-t');
        }
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select(' pc.valortotal as valor_procedimento,
                            ae.valor_total,
                            ae.data,
                            c.nome as convenio,
                            credor_devedor_id,
                            c.dia_aquisicao,
                            c.convenio_id,
                            c.conta_id,
                            ae.confirmacao_recebimento_convenio');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where("ae.data >=", $data_inicio);
        $this->db->where("ae.data <=", $data_fim);
        $this->db->where("c.dinheiro", 'f');
        $this->db->where("c.dia_aquisicao IS NOT NULL");
        $this->db->where('pt.grupo !=', 'CIRURGICO');
        $this->db->where('pt.grupo !=', 'OPME');
        $this->db->where('ae.cancelada', 'f');
        $empresa_id = $this->session->userdata('empresa_id');
        if (isset($args['txtempresa']) && strlen($args['txtempresa']) > 0) {
            $this->db->where('ae.empresa_id', $args['txtempresa']);
        } else {
            $this->db->where("ae.empresa_id", $empresa_id);
        }

        $this->db->orderby('ae.data');
        $this->db->orderby('c.nome');
        $return = $this->db->get();

        return $return->result();
    }

    function relatorioexamesgrupoprocedimentoacompanhamento() {

        $this->db->select('pt.nome,
            cg.nome as convenio,
            sum(ae.quantidade) as quantidade,
            sum(ae.valor_total)as valor');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ae.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_exames e', 'e.agenda_exames_id = ae.agenda_exames_id', 'left');
        $this->db->join('tb_ambulatorio_laudo al', 'al.exame_id = e.exames_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_convenio_grupo cg', 'cg.convenio_grupo_id = c.convenio_grupo_id');
        $this->db->where('ae.cancelada', 'false');
        $this->db->where('e.cancelada', 'false');
        $this->db->where('e.situacao', 'FINALIZADO');
        if ($_POST['grupo'] == "1") {
            $this->db->where('pt.grupo !=', 'RM');
        }
        if ($_POST['grupo'] != "0" && $_POST['grupo'] != "1") {
            $this->db->where('pt.grupo', $_POST['grupo']);
        }
        $this->db->where("ae.data >=", date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio']))));
        $this->db->where("ae.data <=", date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim']))));
        $this->db->groupby('pt.procedimento_tuss_id');
        $this->db->groupby('pt.nome');
        $this->db->groupby('cg.nome');
        $this->db->orderby('cg.nome');
        $this->db->orderby('pt.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriosaidaacompanhamentodecontas() {
        $this->db->select('sum(s.valor) as valor,
                            s.tipo');
        $this->db->from('tb_saidas s');
        $this->db->where('s.ativo', 'true');
        $this->db->where('s.data >=', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio']))));
        $this->db->where('s.data <=', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim']))));
        $this->db->groupby('s.tipo');
        $this->db->orderby('s.tipo');
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriosaidagrupo() {
        if ($_POST['tipo'] > 0) {
            $this->db->select('
                            tes.descricao,
                            tipo_entradas_saida_id
                            ');
            $this->db->from('tb_tipo_entradas_saida tes');
            $this->db->where('tes.ativo', 'true');
            $this->db->where('tes.tipo_entradas_saida_id', $_POST['tipo']);
            $return = $this->db->get()->result();
        }
        $this->db->select('s.valor,
                            s.saidas_id,
                            s.observacao,
                            s.data,
                            e.nome as empresa,
                            fcd.razao_social,
                            fe.descricao as conta,
                            s.tipo,
                            s.classe');
        $this->db->from('tb_saidas s');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = s.conta', 'left');
        $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = s.nome', 'left');
        $this->db->join('tb_empresa e', 'e.empresa_id = s.empresa_id', 'left');
        $this->db->where('s.ativo', 'true');
        if ($_POST['credordevedor'] != 0) {
            $this->db->where('fcd.financeiro_credor_devedor_id ', $_POST['credordevedor']);
        }
        if ($_POST['empresa'] != "") {
            $this->db->where('s.empresa_id', $_POST['empresa']);
        }
        if ($_POST['tipo'] != 0) {
            $this->db->where('tipo', @$return[0]->descricao);
        }
        if ($_POST['classe'] != '') {
            $this->db->where('s.classe', $_POST['classe']);
        }
        if ($_POST['conta'] != 0) {
            $this->db->where('s.conta', $_POST['conta']);
        }
        $this->db->where('s.data >=', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio']))));
        $this->db->where('s.data <=', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim']))));
        $this->db->orderby('s.tipo');
        $this->db->orderby('s.data');
        $this->db->orderby('fcd.razao_social');
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriofluxocaixasaldoanterior() {
        
        $ano_atual = date("Y");
        if($_POST['tipoPesquisa'] == 'MENSAL'){
            $mes = $_POST['mes'] - 1;
            $ano = $_POST['ano'];
        }else{
            $ano = $_POST['ano'] - 1;
            $mes = $_POST['mes'];
        }
        
        
        if($mes == 0){
            $mes = 12;
            $ano -=1;
        }
        if($_POST['tipoPesquisa'] == 'MENSAL'){
            $data_inicio = "$ano_atual-{$mes}-01";
            $data_fim = date("Y-m-t",strtotime($data_inicio));
        }else{
            $data_inicio = "{$ano}-01-01";
            $data_fim = "{$ano}-12-31";
        }
        // var_dump($data_inicio); 
        // var_dump($data_fim); 
        // die;

        $this->db->select("sum(sd.valor) as valor");
        $this->db->from('tb_saldo sd');

        // $this->db->where('s.ativo', 'true');
        $this->db->where('sd.ativo', 'true');
        // $this->db->where('sd.entrada_id is null');

        
        if ($_POST['empresa'] != "") {
            $this->db->where('sd.empresa_id', $_POST['empresa']);
        }
         $this->db->where('sd.data >=', $data_inicio);
        $this->db->where('sd.data <=', $data_fim);
        // $this->db->groupby('s.tipo, s.classe');
        
        $return = $this->db->get();
        return $return->result();
    }

    
    function relatoriosaidaporanosaldoanterior() {
        
        $ano_atual = date("Y");
        $ano_inicial = $_POST['ano_inicial'];
        $ano_final = $_POST['ano_final'];
       
        
        $data_inicio = "{$ano_inicial}-01-01";
        $data_fim = "{$ano_final}-12-31";
       

        $this->db->select("sum(sd.valor) as valor");
        $this->db->from('tb_saldo sd');

        // $this->db->where('s.ativo', 'true');
        $this->db->where('sd.ativo', 'true');
        // $this->db->where('sd.entrada_id is null');

        
        if ($_POST['empresa'] != "") {
            $this->db->where('sd.empresa_id', $_POST['empresa']);
        }
        $this->db->where('sd.data <=', $data_inicio);
        // $this->db->groupby('s.tipo, s.classe');
        
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriofluxocaixasaida() {
        
        $ano_atual = date("Y");
        
        if($_POST['tipoPesquisa'] == 'MENSAL'){
            $data_inicio = "$ano_atual-{$_POST['mes']}-01";
            $data_fim = date("Y-m-t",strtotime($data_inicio));
        }else if($_POST['tipoPesquisa'] == 'ANUAL'){
            $data_inicio = "{$_POST['ano']}-01-01";
            $data_fim = "{$_POST['ano']}-12-31";
        }else{
             $data_inicio = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio'])));
             $data_fim = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim'])));
        }

        $subquery = "SELECT sum(s2.valor) as valor
                     FROM ponto.tb_saldo sd2
                     LEFT JOIN ponto.tb_saidas s2 ON sd2.saida_id = s2.saidas_id
                     WHERE s2.tipo = s.tipo
                     AND sd2.data >= '$data_inicio'
                     AND sd2.data <= '$data_fim'
                     AND sd2.ativo = true
                     AND s2.ativo = true
                     AND s2.tipo != 'TRANSFERENCIA'
                     AND sd2.entrada_id is null
        ";

        $subquery2 = "SELECT sum(s2.valor) as valor
                     FROM ponto.tb_saldo sd2
                     LEFT JOIN ponto.tb_saidas s2 ON sd2.saida_id = s2.saidas_id
                     WHERE sd2.data >= '$data_inicio'
                     AND sd2.data <= '$data_fim'
                     AND sd2.ativo = true
                     AND s2.ativo = true
                     AND s2.tipo != 'TRANSFERENCIA'
                     AND sd2.entrada_id is null
        ";
        // Pra pegar o valor agrupado dos tipos


        
        $this->db->select("sum(s.valor) as valor,
                            s.tipo,
                            ($subquery) as valor_tipo,
                            ($subquery2) as relatorio_total,
                            s.classe");
        $this->db->from('tb_saldo sd');
        $this->db->join('tb_saidas s', 'sd.saida_id = s.saidas_id', 'left');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = s.conta', 'left');
        $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = s.nome', 'left');
        $this->db->join('tb_empresa e', 'e.empresa_id = s.empresa_id', 'left');
        $this->db->where('s.ativo', 'true');
        $this->db->where('sd.ativo', 'true');
        $this->db->where('sd.entrada_id is null');
        $this->db->where("s.tipo != 'TRANSFERENCIA'");
        
        if ($_POST['empresa'] != "") {
            $this->db->where('s.empresa_id', $_POST['empresa']);
        }

        $this->db->where('sd.data >=', $data_inicio);
        $this->db->where('sd.data <=', $data_fim);
        $this->db->groupby('s.tipo, s.classe');
        $this->db->orderby('s.tipo, s.classe');
        
        $return = $this->db->get();
        return $return->result();
    }


    function relatoriofluxocaixasaldoanteriorcompleto_saidas() {
        
        $ano_atual = date("Y");
        
        if($_POST['tipoPesquisa'] == 'MENSAL'){
            @$_POST['mes_antigo'] = $_POST['mes'] - 1;
            if($_POST['mes_antigo'] == 0){
                $_POST['mes_antigo'] = 12;
                $ano_atual = $ano_atual - 1;
            }else{
                $_POST['mes_antigo'] = '0'.$_POST['mes_antigo'];
            }
            $data_inicio = "$ano_atual-{$_POST['mes_antigo']}-01";

            $data_fim = date("Y-m-t",strtotime($data_inicio));
        }else if($_POST['tipoPesquisa'] == 'ANUAL'){
            $data_inicio = "{$_POST['ano']}-01-01";
            $data_fim = "{$_POST['ano']}-12-31";
        }else{
             $data_inicio = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio'])));
             $data_fim = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim'])));
        }

        $subquery = "SELECT sum(s2.valor) as valor
                     FROM ponto.tb_saldo sd2
                     LEFT JOIN ponto.tb_saidas s2 ON sd2.saida_id = s2.saidas_id
                     WHERE s2.tipo = s.tipo
                     --AND sd2.data >= '$data_inicio'
                     AND sd2.data <= '$data_fim'
                     AND sd2.ativo = true
                     AND s2.ativo = true
                     AND s2.tipo != 'TRANSFERENCIA'
                     AND sd2.entrada_id is null
        ";   

        $subquery2 = "SELECT sum(s2.valor) as valor
                     FROM ponto.tb_saldo sd2
                     LEFT JOIN ponto.tb_saidas s2 ON sd2.saida_id = s2.saidas_id
                     WHERE sd2.data <= '$data_fim'
                     --sd2.data >= '$data_inicio'
                     --AND sd2.data <= '$data_fim'
                     AND sd2.ativo = true
                     AND s2.ativo = true
                     AND s2.tipo != 'TRANSFERENCIA'
                     AND sd2.entrada_id is null
        ";
        // Pra pegar o valor agrupado dos tipos


        
        $this->db->select("sum(s.valor) as valor,
                            s.tipo,
                            ($subquery) as valor_tipo,
                            ($subquery2) as relatorio_total,
                            s.classe");
        $this->db->from('tb_saldo sd');
        $this->db->join('tb_saidas s', 'sd.saida_id = s.saidas_id', 'left');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = s.conta', 'left');
        $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = s.nome', 'left');
        $this->db->join('tb_empresa e', 'e.empresa_id = s.empresa_id', 'left');
        $this->db->where('s.ativo', 'true');
        $this->db->where('sd.ativo', 'true');
        $this->db->where('sd.entrada_id is null');
        $this->db->where("s.tipo != 'TRANSFERENCIA'");
        
        if ($_POST['empresa'] != "") {
            $this->db->where('s.empresa_id', $_POST['empresa']);
        }

        // $this->db->where('sd.data >=', $data_inicio);
        $this->db->where('sd.data <=', $data_fim);
        $this->db->groupby('s.tipo, s.classe');
        $this->db->orderby('s.tipo, s.classe');
        
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriosaidaporano() {
        
        $ano_atual = date("Y");

        if ($_POST['tipo'] > 0) {
            $this->db->select('
                            tes.descricao,
                            tipo_entradas_saida_id
                            ');
            $this->db->from('tb_tipo_entradas_saida tes');
            $this->db->where('tes.ativo', 'true');
            $this->db->where('tes.tipo_entradas_saida_id', $_POST['tipo']);
            $return_a = $this->db->get()->result();
        }
        
       
        $data_inicio = "{$_POST['ano_inicial']}-01-01";
        $data_fim = "{$_POST['ano_final']}-12-31";
    
        $this->db->select("sum(s.valor) as valor,
                            s.tipo,
                            s.classe,
                            ", false);
        $this->db->from('tb_saldo sd');
        $this->db->join('tb_saidas s', 'sd.saida_id = s.saidas_id', 'left');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = s.conta', 'left');
        $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = s.nome', 'left');
        $this->db->join('tb_empresa e', 'e.empresa_id = s.empresa_id', 'left');
        $this->db->where('s.ativo', 'true');
        $this->db->where('sd.ativo', 'true');
        $this->db->where('sd.entrada_id is null');
        $this->db->where("s.tipo != 'TRANSFERENCIA'");
        
        if ($_POST['empresa'] != "") {
            $this->db->where('s.empresa_id', $_POST['empresa']);
        }
        if ($_POST['tipo'] != 0) {
            $this->db->where('s.tipo', @$return_a[0]->descricao);
        }
        if ($_POST['classe'] != '') {
            $this->db->where('s.classe', $_POST['classe']);
        }
        if ($_POST['conta'] != 0) {
            $this->db->where('s.conta', $_POST['conta']);
        }

        
      
        $this->db->where('sd.data >=', $data_inicio);
        $this->db->where('sd.data <=', $data_fim);
        $this->db->groupby('s.tipo, s.classe');
        $this->db->orderby('s.tipo, s.classe');
        
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriosaidaporanoValorTipo($ano, $mes, $tipo) {


        if ($_POST['tipo'] > 0) {
            $this->db->select('
                            tes.descricao,
                            tipo_entradas_saida_id
                            ');
            $this->db->from('tb_tipo_entradas_saida tes');
            $this->db->where('tes.ativo', 'true');
            $this->db->where('tes.tipo_entradas_saida_id', $_POST['tipo']);
            $return_a = $this->db->get()->result();
        }
        
        $ano_atual = date("Y");
        $data_inicio = "{$_POST['ano_inicial']}-01-01";
        $data_fim = "{$_POST['ano_final']}-12-31";
        $this->db->select("sum(s.valor) as valor,
                            s.tipo,
                            ", false);
        $this->db->from('tb_saldo sd');
        $this->db->join('tb_saidas s', 'sd.saida_id = s.saidas_id', 'left');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = s.conta', 'left');
        $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = s.nome', 'left');
        $this->db->join('tb_empresa e', 'e.empresa_id = s.empresa_id', 'left');
        $this->db->where('s.ativo', 'true');
        $this->db->where('sd.ativo', 'true');
        $this->db->where('sd.entrada_id is null');
        $this->db->where("s.tipo", $tipo);
        if ($_POST['empresa'] != "") {
            $this->db->where('s.empresa_id', $_POST['empresa']);
        }
        if ($_POST['tipo'] != 0) {
            $this->db->where('s.tipo', @$return_a[0]->descricao);
        }
        if ($_POST['classe'] != '') {
            $this->db->where('s.classe', $_POST['classe']);
        }
        if ($_POST['conta'] != 0) {
            $this->db->where('s.conta', $_POST['conta']);
        }

        $this->db->where("extract(month from sd.data) = '$mes'");
        $this->db->where("extract(year from sd.data) = '$ano'");
        $this->db->groupby('s.tipo');
        $this->db->orderby('s.tipo');
        
        $return = $this->db->get()->result();

        if(count($return) > 0){
            $valor_tipo = $return[0]->valor;
            // var_dump($return); die;
        }else{
            $valor_tipo = 0;
        }
        

        return $valor_tipo;
    }

    function relatoriosaidaporanoValorClasse($ano, $mes, $classe) {


        if ($_POST['tipo'] > 0) {
            $this->db->select('
                            tes.descricao,
                            tipo_entradas_saida_id
                            ');
            $this->db->from('tb_tipo_entradas_saida tes');
            $this->db->where('tes.ativo', 'true');
            $this->db->where('tes.tipo_entradas_saida_id', $_POST['tipo']);
            $return_a = $this->db->get()->result();
        }
        
        $ano_atual = date("Y");
        $data_inicio = "{$_POST['ano_inicial']}-01-01";
        $data_fim = "{$_POST['ano_final']}-12-31";
        $this->db->select("sum(s.valor) as valor,
                            s.classe,
                            ", false);
        $this->db->from('tb_saldo sd');
        $this->db->join('tb_saidas s', 'sd.saida_id = s.saidas_id', 'left');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = s.conta', 'left');
        $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = s.nome', 'left');
        $this->db->join('tb_empresa e', 'e.empresa_id = s.empresa_id', 'left');
        $this->db->where('s.ativo', 'true');
        $this->db->where('sd.ativo', 'true');
        $this->db->where('sd.entrada_id is null');
        $this->db->where("s.classe", $classe);
        if ($_POST['empresa'] != "") {
            $this->db->where('s.empresa_id', $_POST['empresa']);
        }
        if ($_POST['tipo'] != 0) {
            $this->db->where('s.tipo', @$return_a[0]->descricao);
        }
        if ($_POST['classe'] != '') {
            $this->db->where('s.classe', $_POST['classe']);
        }
        if ($_POST['conta'] != 0) {
            $this->db->where('s.conta', $_POST['conta']);
        }

        $this->db->where("extract(month from sd.data) = '$mes'");
        $this->db->where("extract(year from sd.data) = '$ano'");
        $this->db->groupby('s.classe');
        $this->db->orderby('s.classe');
        
        $return = $this->db->get()->result();

        if(count($return) > 0){
            $valor = $return[0]->valor;
            // var_dump($return); die;
        }else{
            $valor = 0;
        }
        

        return $valor;
    }

    function relatoriofluxocaixasaldoanteriorcompleto_entradas() {

        $ano_atual = date("Y");
        
        if($_POST['tipoPesquisa'] == 'MENSAL'){

            @$_POST['mes_antigo'] = $_POST['mes'] - 1;
            if($_POST['mes_antigo'] == 0){
                $_POST['mes_antigo'] = 12;
                $ano_atual = $ano_atual - 1;
            }else{
                $_POST['mes_antigo'] = '0'.$_POST['mes_antigo'];
            }
            $data_inicio = "$ano_atual-{$_POST['mes_antigo']}-01";

            $data_fim = date("Y-m-t",strtotime($data_inicio));


        }else if($_POST['tipoPesquisa'] == 'ANUAL'){
            $data_inicio = "{$_POST['ano']}-01-01";
            $data_fim = "{$_POST['ano']}-12-31";
        }else{
             $data_inicio = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio'])));
              $data_fim = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim'])));
        }

        $subquery = "SELECT sum(sd2.valor) as valor
                     FROM ponto.tb_saldo sd2
                     LEFT JOIN ponto.tb_entradas s2 ON sd2.entrada_id = s2.entradas_id
                     WHERE s2.tipo = s.tipo
                     --AND sd2.data >= '$data_inicio'
                     AND sd2.data <= '$data_fim'
                     AND sd2.ativo = true
                     AND s2.ativo = true
                     AND s2.tipo != 'TRANSFERENCIA'
                     AND sd2.saida_id is null
        ";

        $subquery2 = "SELECT sum(sd2.valor) as valor
                     FROM ponto.tb_saldo sd2
                     LEFT JOIN ponto.tb_entradas s2 ON sd2.entrada_id = s2.entradas_id
                     WHERE sd2.data <= '$data_fim'
                     --sd2.data >= '$data_inicio'
                     --AND sd2.data <= '$data_fim'
                     AND sd2.ativo = true
                     AND s2.ativo = true
                     AND s2.tipo != 'TRANSFERENCIA'
                     AND sd2.saida_id is null
        ";
        
        $subquery3 = "SELECT sum(sd2.valor) as valor
                     FROM ponto.tb_saldo sd2
                     LEFT JOIN ponto.tb_entradas s2 ON sd2.entrada_id = s2.entradas_id
                     WHERE s2.nivel1 = s.nivel1
                     --AND sd2.data >= '$data_inicio'
                     AND sd2.data <= '$data_fim'
                     AND sd2.ativo = true
                     AND s2.ativo = true
                     AND s2.tipo != 'TRANSFERENCIA'
                     AND sd2.saida_id is null
        ";
        
        $subquery4 = "SELECT sum(sd2.valor) as valor
                     FROM ponto.tb_saldo sd2
                     LEFT JOIN ponto.tb_entradas s2 ON sd2.entrada_id = s2.entradas_id
                     WHERE s2.nivel2 = s.nivel2
                     --AND sd2.data >= '$data_inicio'
                     AND sd2.data <= '$data_fim'
                     AND sd2.ativo = true
                     AND s2.ativo = true
                     AND s2.tipo != 'TRANSFERENCIA'
                     AND sd2.saida_id is null
        ";
        // Pra pegar o valor agrupado dos tipos


        
        $this->db->select("sum(s.valor) as valor,
                            s.tipo,
                            s.nivel1,
                            s.nivel2,
                            ($subquery) as valor_tipo,
                            ($subquery2) as relatorio_total,
                            ($subquery3) as valor_nivel1,
                            ($subquery4) as valor_nivel2,
                            s.classe");
        $this->db->from('tb_saldo sd');
        $this->db->join('tb_entradas s', 'sd.entrada_id = s.entradas_id', 'left');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = s.conta', 'left');
        $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = s.nome', 'left');
        $this->db->join('tb_empresa e', 'e.empresa_id = s.empresa_id', 'left');
        $this->db->where('s.ativo', 'true');
        $this->db->where('sd.ativo', 'true');
        $this->db->where('sd.saida_id is null');
        $this->db->where("s.tipo != 'TRANSFERENCIA'");
        
        if ($_POST['empresa'] != "") {
            $this->db->where('s.empresa_id', $_POST['empresa']);
        }

        
      
        // $this->db->where('sd.data >=', $data_inicio);
        $this->db->where('sd.data <=', $data_fim);
        $this->db->groupby('s.tipo, s.classe, s.nivel1, s.nivel2');
        $this->db->orderby('s.tipo, s.classe, s.nivel2, s.nivel1');
        
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriofluxocaixaentrada() {

        $ano_atual = date("Y");
        
        if($_POST['tipoPesquisa'] == 'MENSAL'){
            $data_inicio = "$ano_atual-{$_POST['mes']}-01";
            $data_fim = date("Y-m-t",strtotime($data_inicio));
        }else if($_POST['tipoPesquisa'] == 'ANUAL'){
            $data_inicio = "{$_POST['ano']}-01-01";
            $data_fim = "{$_POST['ano']}-12-31";
        }else{
           $data_inicio = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio'])));
           $data_fim = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim'])));
        }

        $subquery = "SELECT sum(sd2.valor) as valor
                     FROM ponto.tb_saldo sd2
                     LEFT JOIN ponto.tb_entradas s2 ON sd2.entrada_id = s2.entradas_id
                     WHERE s2.tipo = s.tipo
                     AND sd2.data >= '$data_inicio'
                     AND sd2.data <= '$data_fim'
                     AND sd2.ativo = true
                     AND s2.ativo = true
                     AND s2.tipo != 'TRANSFERENCIA'
                     AND sd2.saida_id is null
        ";

        $subquery2 = "SELECT sum(sd2.valor) as valor
                     FROM ponto.tb_saldo sd2
                     LEFT JOIN ponto.tb_entradas s2 ON sd2.entrada_id = s2.entradas_id
                     WHERE sd2.data >= '$data_inicio'
                     AND sd2.data <= '$data_fim'
                     AND sd2.ativo = true
                     AND s2.ativo = true
                     AND s2.tipo != 'TRANSFERENCIA'
                     AND sd2.saida_id is null
        ";
        
        $subquery3 = "SELECT sum(sd2.valor) as valor
                     FROM ponto.tb_saldo sd2
                     LEFT JOIN ponto.tb_entradas s2 ON sd2.entrada_id = s2.entradas_id
                     WHERE s2.nivel1 = s.nivel1
                     AND sd2.data >= '$data_inicio'
                     AND sd2.data <= '$data_fim'
                     AND sd2.ativo = true
                     AND s2.ativo = true
                     AND s2.tipo != 'TRANSFERENCIA'
                     AND sd2.saida_id is null
        ";
        
        $subquery4 = "SELECT sum(sd2.valor) as valor
                     FROM ponto.tb_saldo sd2
                     LEFT JOIN ponto.tb_entradas s2 ON sd2.entrada_id = s2.entradas_id
                     WHERE s2.nivel2 = s.nivel2
                     AND sd2.data >= '$data_inicio'
                     AND sd2.data <= '$data_fim'
                     AND sd2.ativo = true
                     AND s2.ativo = true
                     AND s2.tipo != 'TRANSFERENCIA'
                     AND sd2.saida_id is null
        ";
        // Pra pegar o valor agrupado dos tipos


        
        $this->db->select("sum(s.valor) as valor,
                            s.tipo,
                            s.nivel1,
                            s.nivel2,
                            ($subquery) as valor_tipo,
                            ($subquery2) as relatorio_total,
                            ($subquery3) as valor_nivel1,
                            ($subquery4) as valor_nivel2,
                            s.classe");
        $this->db->from('tb_saldo sd');
        $this->db->join('tb_entradas s', 'sd.entrada_id = s.entradas_id', 'left');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = s.conta', 'left');
        $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = s.nome', 'left');
        $this->db->join('tb_empresa e', 'e.empresa_id = s.empresa_id', 'left');
        $this->db->where('s.ativo', 'true');
        $this->db->where('sd.ativo', 'true');
        $this->db->where('sd.saida_id is null');
        $this->db->where("s.tipo != 'TRANSFERENCIA'");
        
        if ($_POST['empresa'] != "") {
            $this->db->where('s.empresa_id', $_POST['empresa']);
        }

        
      
        $this->db->where('sd.data >=', $data_inicio);
        $this->db->where('sd.data <=', $data_fim);
        $this->db->groupby('s.tipo, s.classe, s.nivel1, s.nivel2');
        $this->db->orderby('s.tipo, s.classe, s.nivel2, s.nivel1');
        
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriocaixa_saldo() {

        $ano_atual = date("Y");
        
        if($_POST['tipoPesquisa'] == 'MENSAL'){
             $_POST['mes_antigo'] = $_POST['mes'] - 01;
             $_POST['mes_antigo'] = '0'.$_POST['mes_antigo'];
                // print_r($_POST['mes_antigo']);

            if($_POST['mes_antigo'] == 00){
                $ano_atual = $ano_atual - 1;
                $_POST['mes_antigo'] = 12;
            }
            $data_inicio = "$ano_atual-{$_POST['mes_antigo']}-01";
            $data_fim = date("Y-m-t",strtotime($data_inicio));
        }else{
            $data_inicio = "{$_POST['ano']}-01-01";
            $data_fim = "{$_POST['ano']}-12-31";
        }

        $subquery5 = "SELECT sum(sd2.valor) as valor
        FROM ponto.tb_saldo sd2
        LEFT JOIN ponto.tb_entradas s2 ON sd2.entrada_id = s2.entradas_id
        LEFT JOIN ponto.tb_ambulatorio_grupo ag ON ag.nome = s2.classe

        WHERE (s2.tipo = s.tipo OR (s2.tipo is null and (position('CAIXA' in s2.classe) > 0 OR ag.nome is not null)))
        AND sd2.ativo = true
        AND s2.ativo = true
        AND sd2.data >= '$data_inicio'
        AND sd2.data <= '$data_fim'
        AND sd2.saida_id is null
        ";
        // Pra pegar o valor agrupado dos tipos

        $this->db->select("($subquery5) as valor_caixa");
        $this->db->from('tb_saldo sd');
        $this->db->join('tb_entradas s', 'sd.entrada_id = s.entradas_id', 'left');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = s.conta', 'left');
        $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = s.nome', 'left');
        $this->db->join('tb_empresa e', 'e.empresa_id = s.empresa_id', 'left');
        $this->db->join('tb_ambulatorio_grupo ag', 'ag.nome = s.classe', 'left');
        $this->db->where('s.ativo', 'true');
        $this->db->where('sd.ativo', 'true');
        $this->db->where('sd.saida_id is null');
        $this->db->where("s.tipo is null and (position('CAIXA' in s.classe) > 0 OR ag.nome is not null)");
        
        if ($_POST['empresa'] != "") {
            $this->db->where('s.empresa_id', $_POST['empresa']);
        }

        $this->db->where('sd.data >=', $data_inicio);
        $this->db->where('sd.data <=', $data_fim);
        $this->db->groupby('s.tipo, s.classe, s.nivel1, s.nivel2');
        $this->db->orderby('s.tipo, s.classe, s.nivel2, s.nivel1');
        
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriocontrolefluxocaixaentrada() {

        $ano_atual = date("Y");
        
        if($_POST['tipoPesquisa'] == 'MENSAL'){
            $data_inicio = "$ano_atual-{$_POST['mes']}-01";
            $data_fim = date("Y-m-t",strtotime($data_inicio));
        }else{
            $data_inicio = "{$_POST['ano']}-01-01";
            $data_fim = "{$_POST['ano']}-12-31";
        }

        $subquery = "SELECT sum(sd2.valor) as valor
                     FROM ponto.tb_saldo sd2
                     LEFT JOIN ponto.tb_entradas s2 ON sd2.entrada_id = s2.entradas_id
                     LEFT JOIN ponto.tb_ambulatorio_grupo ag ON ag.nome = s2.classe
                     
                     WHERE (s2.tipo = s.tipo )
                     AND sd2.ativo = true
                     AND s2.ativo = true
                     AND sd2.data >= '$data_inicio'
                     AND sd2.data <= '$data_fim'
                     AND sd2.saida_id is null
        ";

        

        $subquery2 = "SELECT sum(sd2.valor) as valor
                     FROM ponto.tb_saldo sd2
                     LEFT JOIN ponto.tb_entradas s2 ON sd2.entrada_id = s2.entradas_id
                     LEFT JOIN ponto.tb_ambulatorio_grupo ag ON ag.nome = s2.classe
                     WHERE sd2.data >= '$data_inicio'
                     AND sd2.data <= '$data_fim'
                     AND sd2.ativo = true
                     AND s2.ativo = true
                     AND (s2.tipo != 'TRANSFERENCIA' OR (s2.tipo is null and (position('CAIXA' in s2.classe) > 0 OR ag.nome is not null)))
                     AND sd2.saida_id is null
        ";
        
        $subquery3 = "SELECT sum(sd2.valor) as valor
                     FROM ponto.tb_saldo sd2
                     LEFT JOIN ponto.tb_entradas s2 ON sd2.entrada_id = s2.entradas_id
                     LEFT JOIN ponto.tb_ambulatorio_grupo ag ON ag.nome = s2.classe
                     WHERE s2.nivel1 = s.nivel1
                     AND sd2.data >= '$data_inicio'
                     AND sd2.data <= '$data_fim'
                     AND sd2.ativo = true
                     AND s2.ativo = true
                     AND (s2.tipo != 'TRANSFERENCIA' OR (s2.tipo is null and (position('CAIXA' in s2.classe) > 0 OR ag.nome is not null)))
                     AND sd2.saida_id is null
        ";
        
        $subquery4 = "SELECT sum(sd2.valor) as valor
                     FROM ponto.tb_saldo sd2
                     LEFT JOIN ponto.tb_entradas s2 ON sd2.entrada_id = s2.entradas_id
                     LEFT JOIN ponto.tb_ambulatorio_grupo ag ON ag.nome = s2.classe
                     WHERE s2.nivel2 = s.nivel2
                     AND sd2.data >= '$data_inicio'
                     AND sd2.data <= '$data_fim'
                     AND sd2.ativo = true
                     AND s2.ativo = true
                     AND (s2.tipo != 'TRANSFERENCIA' OR (s2.tipo is null and (position('CAIXA' in s2.classe) > 0 OR ag.nome is not null)))
                     AND sd2.saida_id is null
        ";

        $subquery5 = "SELECT sum(sd2.valor) as valor
        FROM ponto.tb_saldo sd2
        LEFT JOIN ponto.tb_entradas s2 ON sd2.entrada_id = s2.entradas_id
        LEFT JOIN ponto.tb_ambulatorio_grupo ag ON ag.nome = s2.classe

        WHERE (s2.tipo = s.tipo OR (s2.tipo is null and (position('CAIXA' in s2.classe) > 0 OR ag.nome is not null)))
        AND sd2.ativo = true
        AND s2.ativo = true
        AND sd2.data >= '$data_inicio'
        AND sd2.data <= '$data_fim'
        AND sd2.saida_id is null
        ";
        // Pra pegar o valor agrupado dos tipos

        $this->db->select("sum(s.valor) as valor,
                            s.tipo,
                            s.nivel1,
                            s.nivel2,
                            ($subquery) as valor_tipo,
                            ($subquery2) as relatorio_total,
                            ($subquery3) as valor_nivel1,
                            ($subquery4) as valor_nivel2,
                            ($subquery5) as valor_caixa,
                            s.classe");
        $this->db->from('tb_saldo sd');
        $this->db->join('tb_entradas s', 'sd.entrada_id = s.entradas_id', 'left');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = s.conta', 'left');
        $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = s.nome', 'left');
        $this->db->join('tb_empresa e', 'e.empresa_id = s.empresa_id', 'left');
        $this->db->join('tb_ambulatorio_grupo ag', 'ag.nome = s.classe', 'left');
        $this->db->where('s.ativo', 'true');
        $this->db->where('sd.ativo', 'true');
        $this->db->where('sd.saida_id is null');
        $this->db->where("(s.tipo != 'TRANSFERENCIA' OR (s.tipo is null and (position('CAIXA' in s.classe) > 0 OR ag.nome is not null)))");
        
        if ($_POST['empresa'] != "") {
            $this->db->where('s.empresa_id', $_POST['empresa']);
        }

        $this->db->where('sd.data >=', $data_inicio);
        $this->db->where('sd.data <=', $data_fim);
        $this->db->groupby('s.tipo, s.classe, s.nivel1, s.nivel2');
        $this->db->orderby('s.tipo, s.classe, s.nivel2, s.nivel1');
        
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriosaidaclasse() {
        $this->db->select('s.valor,
                            s.saidas_id,
                            s.observacao,
                            s.data,
                            e.nome as empresa,
                            fcd.razao_social,
                            fe.descricao as conta,
                            s.tipo,
                            s.classe');
        $this->db->from('tb_saidas s');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = s.conta', 'left');
        $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = s.nome', 'left');
//        $this->db->join('tb_financeiro_classe fc', 'fc.descricao = s.classe', 'left');
        $this->db->join('tb_empresa e', 'e.empresa_id = s.empresa_id', 'left');
        $this->db->where('s.ativo', 'true');
        if ($_POST['credordevedor'] != 0) {
            $this->db->where('fcd.financeiro_credor_devedor_id ', $_POST['credordevedor']);
        }
        if ($_POST['empresa'] != "") {
            $this->db->where('s.empresa_id', $_POST['empresa']);
        }
        if ($_POST['tipo'] != 0) {
            $this->db->where('tipo_id', $_POST['tipo']);
        }
        if ($_POST['classe'] != '') {
            $this->db->where('classe', $_POST['classe']);
        }
        if ($_POST['conta'] != 0) {
            $this->db->where('s.conta', $_POST['conta']);
        }
        $this->db->where('s.data >=', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio']))));
        $this->db->where('s.data <=', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim']))));
        $this->db->orderby('s.classe');
        $this->db->orderby('s.data');
        $this->db->orderby('fcd.razao_social');
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriosaidacontadorclasse() {
        $this->db->select('s.valor');
        $this->db->from('tb_saidas s');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = s.conta', 'left');
        $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = s.nome', 'left');
        $this->db->where('s.ativo', 'true');
        if ($_POST['credordevedor'] != 0) {
            $this->db->where('fcd.financeiro_credor_devedor_id ', $_POST['credordevedor']);
        }
        if ($_POST['tipo'] != 0) {
            $this->db->where('tipo', $_POST['tipo']);
        }
        if ($_POST['classe'] != 0) {
            $this->db->where('classe', $_POST['classe']);
        }
        if ($_POST['conta'] != 0) {
            $this->db->where('s.conta', $_POST['conta']);
        }
        $this->db->where('s.data >=', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio']))));
        $this->db->where('s.data <=', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim']))));
        $return = $this->db->count_all_results();
        return $return;
    }

    function relatoriosaidacontador() {
        if ($_POST['tipo'] > 0) {
            $this->db->select('
                            tes.descricao,
                            tipo_entradas_saida_id
                            ');
            $this->db->from('tb_tipo_entradas_saida tes');
            $this->db->where('tes.ativo', 'true');
            $this->db->where('tes.tipo_entradas_saida_id', $_POST['tipo']);
            $return = $this->db->get()->result();
        }
        $this->db->select('s.valor');
        $this->db->from('tb_saidas s');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = s.conta', 'left');
        $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = s.nome', 'left');
        $this->db->where('s.ativo', 'true');
        if ($_POST['credordevedor'] != 0) {
            $this->db->where('fcd.financeiro_credor_devedor_id ', $_POST['credordevedor']);
        }
        if ($_POST['tipo'] != 0) {
            $this->db->where('tipo', @$return[0]->descricao);
        }
        if ($_POST['classe'] != 0) {
            $this->db->where('classe', $_POST['classe']);
        }
        if ($_POST['conta'] != 0) {
            $this->db->where('s.conta', $_POST['conta']);
        }
        $this->db->where('s.data >=', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio']))));
        $this->db->where('s.data <=', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim']))));
        $return = $this->db->count_all_results();
        return $return;
    }

    function listarcancelarsangria($sangria_id) {
        $this->db->select('s.valor,
            s.sangria_id,
            s.operador_caixa,
            s.observacao,
            o.nome as operador');
        $this->db->from('tb_sangria s');
        $this->db->join('tb_operador o', 'o.operador_id = s.operador_caixa', 'left');
        $this->db->where('s.sangria_id', $sangria_id);
        $return = $this->db->get();
        return $return->result();
    }

    function relatorioentradagrupo() {
        $this->db->select('s.valor,
                            s.entradas_id,
                            s.observacao,
                            s.data,
                            e.nome as empresa,
                            fcd.razao_social,
                            fe.descricao as conta,
                            s.tipo,
                            s.classe');
        $this->db->from('tb_entradas s');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = s.conta', 'left');
        $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = s.nome', 'left');
        $this->db->join('tb_empresa e', 'e.empresa_id = s.empresa_id', 'left');
//        $this->db->join('tb_financeiro_classe fc', 'fc.descricao = s.classe', 'left');
        $this->db->where('s.ativo', 'true');
        if ($_POST['credordevedor'] != 0) {
            $this->db->where('fcd.financeiro_credor_devedor_id ', $_POST['credordevedor']);
        }
        if ($_POST['empresa'] != "") {
            $this->db->where('s.empresa_id', $_POST['empresa']);
        }
        if ($_POST['tipo'] != 0) {
            $this->db->where('tipo_id', $_POST['tipo']);
        }
        if ($_POST['classe'] != '') {
            $this->db->where('classe', $_POST['classe']);
        }
        if ($_POST['conta'] != 0) {
            $this->db->where('s.conta', $_POST['conta']);
        }
        $this->db->where('s.data >=', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio']))));
        $this->db->where('s.data <=', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim']))));
        $this->db->orderby('s.conta');
        $this->db->orderby('s.data');
        $this->db->orderby('fcd.razao_social');
        $return = $this->db->get();
        return $return->result();
    }

    function relatorioentredacontador() {
        $this->db->select('s.valor');
        $this->db->from('tb_entradas s');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = s.conta', 'left');
        $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = s.nome', 'left');
        $this->db->where('s.ativo', 'true');
        if ($_POST['credordevedor'] != 0) {
            $this->db->where('fcd.financeiro_credor_devedor_id ', $_POST['credordevedor']);
        }
        if ($_POST['tipo'] != 0) {
            $this->db->where('tipo', $_POST['tipo']);
        }
        if ($_POST['conta'] != 0) {
            $this->db->where('s.conta', $_POST['conta']);
        }
        $this->db->where('s.data >=', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio']))));
        $this->db->where('s.data <=', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim']))));
        $return = $this->db->count_all_results();
        return $return;
    }

    function relatorioentrada() {
        if ($_POST['tipo'] > 0) {
            $this->db->select('
                            tes.descricao
                            ');
            $this->db->from('tb_tipo_entradas_saida tes');
            $this->db->where('tes.ativo', 'true');
            $this->db->where('tes.tipo_entradas_saida_id', $_POST['tipo']);
            $return = $this->db->get()->result();
        }

        $this->db->select('s.valor,
                            s.valor_bruto,
                            s.entradas_id,
                            s.observacao,
                            s.data,
                            e.nome as empresa,
                            fcd.razao_social,
                            fe.descricao as conta,
                            s.tipo,
                            s.classe');
        $this->db->from('tb_entradas s');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = s.conta', 'left');
        $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = s.nome', 'left');
        $this->db->join('tb_empresa e', 'e.empresa_id = s.empresa_id', 'left');
//        $this->db->join('tb_financeiro_classe fc', 'fc.descricao = s.classe', 'left');
        $this->db->where('s.ativo', 'true');
        if ($_POST['credordevedor'] != 0) {
            $this->db->where('fcd.financeiro_credor_devedor_id ', $_POST['credordevedor']);
        }
        //        if ($_POST['tipo'] != 0) {
//            $this->db->where('tipo_id', $_POST['tipo']);
//        }
        if ($_POST['empresa'] != "") {
            $this->db->where('s.empresa_id', $_POST['empresa']);
        }
        if ($_POST['tipo'] > 0) {
//            var_dump($args['nome']); die;
            $this->db->where('tipo', @$return[0]->descricao);
        }
        if ($_POST['classe'] != '') {
            $this->db->where('s.classe', $_POST['classe']);
        }
        if ($_POST['conta'] != 0) {
            $this->db->where('s.conta', $_POST['conta']);
        }
        $this->db->where('s.data >=', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio']))));
        $this->db->where('s.data <=', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim']))));
        $this->db->orderby('s.conta');
        $this->db->orderby('s.data');
        $this->db->orderby('fcd.razao_social');
        $return = $this->db->get();
        return $return->result();
    }

    function relatorioentradaacompanhamentodecontas() {
        $this->db->select('sum(s.valor) as valor,
                            s.tipo');
        $this->db->from('tb_entradas s');
        $this->db->where('s.ativo', 'true');
        $this->db->where('s.data >=', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio']))));
        $this->db->where('s.data <=', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim']))));
        $this->db->groupby('s.tipo');
        $this->db->orderby('s.tipo');
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriomovimento() {
        if ($_POST['tipo'] > 0) {
            $this->db->select('tes.descricao');
            $this->db->from('tb_tipo_entradas_saida tes');
            $this->db->where('tes.ativo', 'true');
            $this->db->where('tes.tipo_entradas_saida_id', $_POST['tipo']);
            $return = $this->db->get()->result();
        }
        $this->db->select('s.valor,
                            s.data,
                            s.data_cadastro,
                            fcd.razao_social,
                            sa.tipo as tiposaida,
                            e.tipo as tipoentrada,
                            sa.observacao as observacaosaida,
                            e.observacao as observacaoentrada,
                            fe.descricao as contanome,
                            s.conta,
                            ep.nome as empresa,
                            sa.classe as classesaida,
                            e.classe as classeentrada');
        $this->db->from('tb_saldo s');
        $this->db->join('tb_saidas sa', 'sa.saidas_id = s.saida_id', 'left');
        $this->db->join('tb_entradas e', 'e.entradas_id = s.entrada_id', 'left');
        $this->db->join('tb_empresa ep', 'ep.empresa_id = s.empresa_id', 'left');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = s.conta', 'left');
        $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = s.nome', 'left');
//        $this->db->join('tb_financeiro_classe fc', 'fc.descricao = sa.classe AND fc.descricao = e.classe', 'left');
        if ($_POST['tipo'] > 0) {
//            var_dump($args['nome']); die;
            $this->db->where("(sa.tipo = '{$return[0]->descricao}' OR e.tipo = '{$return[0]->descricao}')");
        }
        if ($_POST['classe'] != '') {
            $this->db->where('e.classe', $_POST['classe']);
            $this->db->orwhere('sa.classe', $_POST['classe']);
        }
        if ($_POST['empresa'] != 0) {
            $this->db->where('s.empresa_id', $_POST['empresa']);
        }
        if ($_POST['conta'] != 0) {
            $this->db->where('s.conta', $_POST['conta']);
        }
        if ($_POST['credordevedor'] != 0) {
            $this->db->where('fcd.financeiro_credor_devedor_id ', $_POST['credordevedor']);
        }
        $this->db->where('s.ativo', 'true');
        $this->db->where('s.data >=', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio']))) . ' ' . '00:00:00');
        $this->db->where('s.data <=', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim']))) . ' ' . '23:59:59');
        $this->db->orderby('s.data');
        $this->db->orderby('fcd.razao_social');
        $return = $this->db->get();
        return $return->result();
    }

    function relatorioduplaAssinatura() {
       
        $this->db->select('s.valor,
                            s.data,
                            s.data_cadastro,
                            s.operador_cadastro,
                            o.nome as operador,
                            fcd.razao_social,
                            sa.saidas_id, 
                            sa.confirmacao_saida,
                            e.entradas_id,
                            e.confirmacao_entrada,
                            o2.nome as operador_confirmacao_e,
                            o3.nome as operador_confirmacao_s,
                            fe.descricao as contanome,
                            s.conta,
                            ep.nome as empresa');
        $this->db->from('tb_saldo s');
        $this->db->join('tb_saidas sa', 'sa.saidas_id = s.saida_id', 'left');
        $this->db->join('tb_entradas e', 'e.entradas_id = s.entrada_id', 'left');
        $this->db->join('tb_empresa ep', 'ep.empresa_id = s.empresa_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = s.operador_cadastro', 'left');
        $this->db->join('tb_operador o2', 'o2.operador_id = e.operador_confirmacao', 'left');
        $this->db->join('tb_operador o3', 'o3.operador_id = sa.operador_confirmacao', 'left');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = s.conta', 'left');
        $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = s.nome', 'left');
//        $this->db->join('tb_financeiro_classe fc', 'fc.descricao = sa.classe AND fc.descricao = e.classe', 'left');
       
        if ($_POST['empresa'] != 0) {
            $this->db->where('s.empresa_id', $_POST['empresa']);
        }
        if ($_POST['status'] != '') {
            if($_POST['status'] == 'CONFIRMADO'){
                $this->db->where('(sa.confirmacao_saida = true OR e.confirmacao_entrada = true)');
            }else{
                $this->db->where('(sa.confirmacao_saida = false OR e.confirmacao_entrada = false)');
            }
            
        }
        if ($_POST['movimentacao'] != '') {
            if($_POST['movimentacao'] == 'ENTRADA'){
                $this->db->where('s.entrada_id is not null');
            }elseif($_POST['movimentacao'] == 'SAIDA'){
                $this->db->where('s.saida_id is not null');
            }
        }

    
        $this->db->where('s.ativo', 'true');
        $this->db->where('s.data >=', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio']))) . ' ' . '00:00:00');
        $this->db->where('s.data <=', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim']))) . ' ' . '23:59:59');
        $this->db->orderby('s.data');
        $this->db->orderby('fcd.razao_social');
        $return = $this->db->get();
        return $return->result();
    }

    function relatorioduplaAssinaturaDesconto() {
       
        $this->db->select('s.valor_total as valor,
                            s.data,
                            s.data_cadastro,
                            s.operador_id,
                            o.nome as operador_desconto,
                            o2.nome as operador_confirmacao,
                            ae.agenda_exames_id,
                            ep.nome as empresa,
                            s.confirmacao_desconto,
                            p.nome as paciente,
                            ');
        $this->db->from('tb_ambulatorio_desconto s');
        $this->db->join('tb_paciente p', 'p.paciente_id = s.paciente_id', 'left');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = s.agenda_exames_id', 'left');
        $this->db->join('tb_empresa ep', 'ep.empresa_id = ae.empresa_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = s.operador_id', 'left');
        $this->db->join('tb_operador o2', 'o2.operador_id = s.operador_confirmacao', 'left');
//        $this->db->join('tb_financeiro_classe fc', 'fc.descricao = sa.classe AND fc.descricao = e.classe', 'left');
       
        if ($_POST['empresa'] != 0) {
            $this->db->where('ae.empresa_id', $_POST['empresa']);
        }
        if ($_POST['status'] != '') {
            if($_POST['status'] == 'CONFIRMADO'){
                $this->db->where('s.confirmacao_desconto', 't');
            }else{
                $this->db->where('s.confirmacao_desconto', 'f');
            }
            
        }
    
        $this->db->where('ae.agenda_exames_id is not null');
        $this->db->where('s.data >=', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio']))) . ' ' . '00:00:00');
        $this->db->where('s.data <=', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim']))) . ' ' . '23:59:59');
        $this->db->orderby('s.data');
        $this->db->orderby('p.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriomovimentosaldoantigo() {
        if ($_POST['tipo'] > 0) {
            $this->db->select('
                            tes.descricao
                            ');
            $this->db->from('tb_tipo_entradas_saida tes');
            $this->db->where('tes.ativo', 'true');
            $this->db->where('tes.tipo_entradas_saida_id', $_POST['tipo']);
            $return = $this->db->get()->result();
        }
        $this->db->select('sum(s.valor) as total');
        $this->db->from('tb_saldo s');
        $this->db->join('tb_saidas sa', 'sa.saidas_id = s.saida_id', 'left');
        $this->db->join('tb_entradas e', 'e.entradas_id = s.entrada_id', 'left');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = s.conta', 'left');
        $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = s.nome', 'left');
       $this->db->join('tb_financeiro_classe fc', 'fc.descricao = sa.classe AND fc.descricao = e.classe', 'left');
        $this->db->where('s.ativo', 'true');
        if ($_POST['credordevedor'] != 0) {
            $this->db->where('fcd.financeiro_credor_devedor_id ', $_POST['credordevedor']);
        }
        if ($_POST['tipo'] > 0) {
//            var_dump($args['nome']); die;
            $this->db->where("(sa.tipo = '{$return[0]->descricao}' OR e.tipo = '{$return[0]->descricao}')");
        }
        if ($_POST['conta'] != 0) {
            // print_r($_POST['conta']);
            $this->db->where('s.conta', $_POST['conta']);
        }
//        var_dump(date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio'])))); die;
        $this->db->where('s.data <', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio']))));
        $return = $this->db->get();
        return $return->result();
    }

    function relatorioentreda() {
        $this->db->select('s.valor');
        $this->db->from('tb_entradas s');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = s.conta', 'left');
        $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = s.nome', 'left');
        $this->db->where('s.ativo', 'true');
        if ($_POST['credordevedor'] != 0) {
            $this->db->where('fcd.financeiro_credor_devedor_id ', $_POST['credordevedor']);
        }
        if ($_POST['tipo'] != 0) {
            $this->db->where('tipo', $_POST['tipo']);
        }
        if ($_POST['conta'] != 0) {
            $this->db->where('s.conta', $_POST['conta']);
        }
        $this->db->where('s.data >=', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio']))));
        $this->db->where('s.data <=', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim']))));
        $return = $this->db->count_all_results();
        return $return;
    }

    function listarsomaconta($forma_entradas_saida_id) {
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('sum(valor) as total');
        $this->db->from('tb_saldo');
        $this->db->where('ativo', 'true');
        $this->db->where('conta', $forma_entradas_saida_id);
        if ((isset($_GET['txtempresa']) && strlen($_GET['txtempresa']) > 0) || @$_GET['txtempresa'] != '0') {
            $this->db->where('empresa_id', @$_GET['txtempresa']);
        }
        else{
            $this->db->where("empresa_id", $empresa_id);
        }
//        $this->db->where('empresa_id', $empresa_id);
        $return = $this->db->get();
        return $return->result();
    }

    function statusparcelas() {
        $this->db->select('caixa_parcelas_id,
                            paga,
                            data,
                            caixa_id,
                            valor_parcela');
        $this->db->from('tb_caixa_parcelas');
        $this->db->where('paga', 'false');
        $this->db->orderby('data');
        $return = $this->db->get();
        return $return->result();
    }

    function listarcredordevedor() {
        $this->db->select('financeiro_credor_devedor_id,
                            razao_social');
        $this->db->from('tb_financeiro_credor_devedor');
        $this->db->where('ativo', 'true');
        $this->db->orderby('razao_social');
        $return = $this->db->get();
        return $return->result();
    }

    function buscarcredordevedor($financeiro_credor_devedor_id) {
        $this->db->select('financeiro_credor_devedor_id,
                            razao_social');
        $this->db->from('tb_financeiro_credor_devedor');
        $this->db->where('financeiro_credor_devedor_id', "$financeiro_credor_devedor_id");
        $this->db->where('ativo', 'true');
        $return = $this->db->get();
        return $return->result();
    }

    function confirmarduplaAssinatura($id, $movimentacao) {
        try {
            $operador_id = $this->session->userdata('operador_id');
            $horario = date("Y-m-d H:i:s");

            if($movimentacao == 'ENTRADA'){

                $this->db->set('confirmacao_entrada', 't');
                $this->db->set('operador_confirmacao', $operador_id);
                $this->db->set('data_confirmacao', $horario);
                $this->db->where('entradas_id', $id);
                $this->db->update('tb_entradas');
    
            }elseif($movimentacao == 'SAIDA'){

                $this->db->set('confirmacao_saida', 't');
                $this->db->set('operador_confirmacao', $operador_id);
                $this->db->set('data_confirmacao', $horario);
                $this->db->where('saidas_id', $id);
                $this->db->update('tb_saidas');
            }else{

                $this->db->set('confirmacao_desconto', 't');
                $this->db->set('operador_confirmacao', $operador_id);
                $this->db->set('data_confirmacao', $horario);
                $this->db->where('agenda_exames_id', $id);
                $this->db->update('tb_ambulatorio_desconto');
            }
            

          

            return $entradas_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarentrada() {
        try {
            if($_POST['empresa_id'] != ''){
                $empresa_id = $_POST['empresa_id'];
            }
            else{
                $empresa_id = $this->session->userdata('empresa_id');
            }
            
            $operador_id = $this->session->userdata('operador_id');
            $horario = date("Y-m-d H:i:s");

            $_POST['inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['inicio'])));
            //busca tipo
            $this->db->select('t.descricao');
            $this->db->from('tb_tipo_entradas_saida t');
            $this->db->where('t.tipo_entradas_saida_id', $_POST['tipo']);
            $return = $this->db->get();
            $result = $return->result();
            $tipo = $result[0]->descricao;
            //busca nível1
            if(isset($_POST['nivel1']) && $_POST['nivel1'] != ""){
            $this->db->select('n1.descricao');
            $this->db->from('tb_nivel1 n1');            
            $this->db->where('n1.ativo', 't');
            $this->db->where('n1.nivel1_id', $_POST['nivel1']);
            $return = $this->db->get();
            $result = $return->result();
            $nivel1 = $result[0]->descricao;
            }
            //busca nível2
            if(isset($_POST['nivel2']) && $_POST['nivel2'] != ""){
            $this->db->select('n2.descricao');
            $this->db->from('tb_nivel2 n2');            
            $this->db->where('n2.ativo', 't');
            $this->db->where('n2.nivel2_id', $_POST['nivel2']);
            $return = $this->db->get();
            $result = $return->result();
            $nivel2 = $result[0]->descricao;
            }
            if ($_POST['devedor'] == "") { 
                $this->db->set('razao_social', $_POST['devedorlabel']);
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_financeiro_credor_devedor');
                $devedor = $this->db->insert_id();
            } else {
                $devedor = $_POST['devedor'];
            }

            /* inicia o mapeamento no banco */
            $operador_id = $this->session->userdata('operador_id');
            $horario = date("Y-m-d H:i:s");
            $data = date("Y-m-d");
            $this->db->set('valor', str_replace(",", ".", str_replace(".", "", $_POST['valor'])));
            $inicio = $_POST['inicio'];
            $dia = substr($inicio, 0, 2);
            $mes = substr($inicio, 3, 2);
            $ano = substr($inicio, 6, 4);
            $datainicio = $ano . '-' . $mes . '-' . $dia;
            $this->db->set('data', $_POST['inicio']);
            $this->db->set('tipo', $tipo);
            $this->db->set('empresa_id', $empresa_id);
            $this->db->set('classe', $_POST['classe']);
            if(isset($_POST['nivel1']) && $_POST['nivel1'] != ""){
            $this->db->set('nivel1', $nivel1);
            }
            if(isset($_POST['nivel2']) && $_POST['nivel2'] != ""){
            $this->db->set('nivel2', $nivel2);
            }
            $this->db->set('nome', $devedor);
            $this->db->set('conta', $_POST['conta']);
            $this->db->set('observacao', $_POST['Observacao']);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_entradas');
            $entradas_id = $this->db->insert_id();
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            else
                $this->db->set('valor', str_replace(",", ".", str_replace(".", "", $_POST['valor'])));
            $this->db->set('entrada_id', $entradas_id);
            $this->db->set('conta', $_POST['conta']);
            $this->db->set('nome', $devedor);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('data', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['inicio']))));
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->set('empresa_id', $empresa_id);
            $this->db->insert('tb_saldo');

            return $entradas_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarsaida() {
        try {
            
            if($_POST['empresa_id'] != ''){
                $empresa_id = $_POST['empresa_id'];
            }
            else{
                $empresa_id = $this->session->userdata('empresa_id');
            }
            
            $_POST['inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['inicio'])));
            if ($_POST['devedor'] == "") {

                $this->db->set('razao_social', $_POST['devedorlabel']);
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_financeiro_credor_devedor');
                $devedor = $this->db->insert_id();
            } else {
                $devedor = $_POST['devedor'];
            }

            //busca tipo
            $this->db->select('t.descricao');
            $this->db->from('tb_tipo_entradas_saida t');
            $this->db->where('t.tipo_entradas_saida_id', $_POST['tipo']);
            $return = $this->db->get();
            $result = $return->result();
            $tipo = $result[0]->descricao;

            if ($_POST['saida_id'] == "") {
                /* inicia o mapeamento no banco */
                $horario = date("Y-m-d H:i:s");
                $data = date("Y-m-d");
                $operador_id = $this->session->userdata('operador_id');
                $this->db->set('valor', str_replace(",", ".", str_replace(".", "", $_POST['valor'])));
                $inicio = $_POST['inicio'];
                $dia = substr($inicio, 0, 2);
                $mes = substr($inicio, 3, 2);
                $ano = substr($inicio, 6, 4);
                $datainicio = $ano . '-' . $mes . '-' . $dia;
                $this->db->set('data', $_POST['inicio']);
                $this->db->set('tipo', $tipo);
                $this->db->set('empresa_id', $empresa_id);
                $this->db->set('classe', $_POST['classe']);
                $this->db->set('conta', $_POST['conta']);
                $this->db->set('nome', $devedor);
                $this->db->set('observacao', $_POST['Observacao']);
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_saidas');
                $saida_id = $this->db->insert_id();
                $erro = $this->db->_error_message();
                if (trim($erro) != "") // erro de banco
                    return -1;
                else
                    $valor = str_replace(",", ".", str_replace(".", "", $_POST['valor']));
                $this->db->set('valor', -$valor);
                $this->db->set('conta', $_POST['conta']);
                $this->db->set('nome', $devedor);
                $this->db->set('saida_id', $saida_id);
                $this->db->set('empresa_id', $empresa_id);
                $this->db->set('data_cadastro', $horario);
                $this->db->set('data', $_POST['inicio']);
                $this->db->set('empresa_id', $empresa_id);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_saldo');
            }else {
                /* inicia o mapeamento no banco */
                $horario = date("Y-m-d H:i:s");
                $data = date("Y-m-d");
                $operador_id = $this->session->userdata('operador_id');
                $this->db->set('valor', str_replace(",", ".", str_replace(".", "", $_POST['valor'])));
                $inicio = $_POST['inicio'];
                $dia = substr($inicio, 0, 2);
                $mes = substr($inicio, 3, 2);
                $ano = substr($inicio, 6, 4);
                $datainicio = $ano . '-' . $mes . '-' . $dia;
                $this->db->set('data', $_POST['inicio']);
                $this->db->set('tipo', $tipo);
                $this->db->set('classe', $_POST['classe']);
                $this->db->set('conta', $_POST['conta']);
                $this->db->set('nome', $devedor);
                $this->db->set('observacao', $_POST['Observacao']);
                $this->db->set('data_cadastro', $horario);
                $this->db->set('empresa_id', $empresa_id);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->where('saidas_id', $_POST['saida_id']);
                $this->db->update('tb_saidas');
//                $saida_id = $this->db->insert_id();
                $erro = $this->db->_error_message();
                if (trim($erro) != "") // erro de banco
                    return -1;

                $valor = str_replace(",", ".", str_replace(".", "", $_POST['valor']));
                $this->db->set('valor', -$valor);
                $this->db->set('conta', $_POST['conta']);
                $this->db->set('nome', $devedor);
                $this->db->set('data_cadastro', $horario);
                $this->db->set('data', $_POST['inicio']);
                $this->db->set('empresa_id', $empresa_id);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->where('saida_id', $_POST['saida_id']);
                $this->db->update('tb_saldo');
            }


            return $saida_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravartransferencia() {
        try {
//            var_dump($_POST); die;
          

            $empresa_id = $this->session->userdata('empresa_id');
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $inicio = $_POST['inicio'];
            $dia = substr($inicio, 0, 2);
            $mes = substr($inicio, 3, 2);
            $ano = substr($inicio, 6, 4);
            $datainicio = $ano . '-' . $mes . '-' . $dia;
            $this->db->set('valor', str_replace(",", ".", str_replace(".", "", $_POST['valor'])));
            $this->db->set('data', $datainicio);
            $this->db->set('tipo', 'TRANSFERENCIA');
            $this->db->set('conta', $_POST['conta']);
            $this->db->set('observacao', $_POST['Observacao']);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('empresa_id', $empresa_id);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_transferencia');
            $transferencia_id = $this->db->insert_id();

            $this->db->set('valor', str_replace(",", ".", str_replace(".", "", $_POST['valor'])));
            $this->db->set('transferencia_id', $transferencia_id);
            $this->db->set('data', $datainicio);
            $this->db->set('tipo', 'TRANSFERENCIA');
            $this->db->set('conta', $_POST['conta']);
            $this->db->set('observacao', $_POST['Observacao']);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('empresa_id', $_POST['empresa']);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_saidas');
            $saida_id = $this->db->insert_id();

            $valor = str_replace(",", ".", str_replace(".", "", $_POST['valor']));
            $this->db->set('transferencia_id', $transferencia_id);
            $this->db->set('valor', -$valor);
            $this->db->set('conta', $_POST['conta']);
            $this->db->set('saida_id', $saida_id);
            $this->db->set('data', $datainicio);
            $this->db->set('empresa_id', $_POST['empresa']);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_saldo');

            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $this->db->set('valor', str_replace(",", ".", str_replace(".", "", $_POST['valor'])));
            $inicio = $_POST['inicio'];
            $dia = substr($inicio, 0, 2);
            $mes = substr($inicio, 3, 2);
            $ano = substr($inicio, 6, 4);
            $datainicio = $ano . '-' . $mes . '-' . $dia;
            $this->db->set('transferencia_id', $transferencia_id);
            $this->db->set('data', $datainicio);
            $this->db->set('tipo', 'TRANSFERENCIA');
            $this->db->set('empresa_id', $_POST['empresaentrada']);
            $this->db->set('conta', $_POST['contaentrada']);
            $this->db->set('observacao', $_POST['Observacao']);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_entradas');
            $entrada_id = $this->db->insert_id();

            $valorentrada = str_replace(",", ".", str_replace(".", "", $_POST['valor']));
            $this->db->set('transferencia_id', $transferencia_id);
            $this->db->set('valor', $valorentrada);
            $this->db->set('data', $datainicio);
            $this->db->set('conta', $_POST['contaentrada']);
            $this->db->set('entrada_id', $entrada_id);
            $this->db->set('saida_id', $saida_id);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('empresa_id', $_POST['empresaentrada']);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_saldo');

 
            return $entradas_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarsangria() {
        try {
            /* inicia o mapeamento no banco */
            $empresa_id = $this->session->userdata('empresa_id');

            $this->db->select(' o.operador_id,
                                o.perfil_id');
            $this->db->from('tb_operador o');
            $this->db->where('o.operador_id', $_POST['operador']);
            $this->db->where('o.senha', md5($_POST['senha']));
            $return = $this->db->get()->result();
            if (isset($return) && count($return) > 0) {
                $horario = date("Y-m-d H:i:s");
                $data = date("Y-m-d");
                $this->db->set('valor', str_replace(",", ".", str_replace(".", "", $_POST['valor'])));
                $this->db->set('data', $data);
                $this->db->set('observacao', $_POST['Observacao']);
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $_POST['operador']);
                $this->db->set('operador_caixa', $_POST['caixa']);
                $this->db->set('empresa_id', $empresa_id);
                $this->db->insert('tb_sangria');
                $erro = $this->db->_error_message();
                if (trim($erro) != "") // erro de banco
                    return -1;
                else
                    return 1;
            }else {
                return 0;
            }
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravarcancelarsangria() {
        try {
            /* inicia o mapeamento no banco */
            $operador_id = $this->session->userdata('operador_id');

            $this->db->select(' o.operador_id,
                                o.perfil_id');
            $this->db->from('tb_operador o');
            $this->db->where('o.operador_id', $_POST['operador_id']);
            $this->db->where('o.senha', md5($_POST['senha']));
            $return = $this->db->get()->result();

            if (isset($return) && count($return) > 0) {
                $horario = date("Y-m-d H:i:s");
                $this->db->set('observacao', $_POST['Observacao']);
                $this->db->set('data_cancelamento', $horario);
                $this->db->set('ativo', 'f');
                $this->db->set('operador_cancelamento', $operador_id);
                $this->db->set('operador_caixa_cancelamento', $_POST['operador_id']);
                $this->db->where('sangria_id', $_POST['sangria_id']);
                $this->db->update('tb_sangria');
                $erro = $this->db->_error_message();
                if (trim($erro) != "") // erro de banco
                    return -1;
                else
                    return 1;
            }else {
                return 0;
            }
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravaremailmensagem($html) {
        try {
            /* inicia o mapeamento no banco */
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            $this->db->set('mensagem', $html);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_financeiro_email');
            $financeiro_email_id = $this->db->insert_id();
            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            else
                return $financeiro_email_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function listaremailmensagem($email_id) {

        $this->db->select('mensagem');
        $this->db->from('tb_financeiro_email');
        $this->db->where('financeiro_email_id', $email_id);
        $return = $this->db->get();
        $result = $return->result();
        return $result[0]->mensagem;
    }

    function saldo() {
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('sum(s.valor)');
        $this->db->from('tb_saldo s');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = s.conta', 'left');
        $this->db->where('fe.ativo', 'true');
        $this->db->where('s.ativo', 'true');
        $this->db->where('s.empresa_id', $empresa_id);
        $return = $this->db->get();
        return $return->result();
    }

    function empresa() {

        $this->db->select('financeiro_credor_devedor_id,
            razao_social');
        $this->db->from('tb_financeiro_credor_devedor');
        $this->db->where('ativo', 'true');
        $this->db->orderby('razao_social');
        $return = $this->db->get();
        return $return->result();
    }

    function excluirentrada($entrada) {
        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
         
        $this->db->select('transferencia_id');
        $this->db->from('tb_entradas');
        $this->db->where('entradas_id', $entrada);
        $return = $this->db->get()->result();
//        var_dump($return); die;

        
//        var_dump($horario); die;
        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('entrada_id', $entrada);
        $this->db->update('tb_saldo');

        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('entradas_id', $entrada);
        $this->db->update('tb_entradas');
        
        
        $this->db->select('');
        $this->db->from('tb_entradas');
        $this->db->where('entradas_id', $entrada);
        $dados  = $this->db->get()->result();
        
        $this->db->set('operador_cadastro',$operador_id);
        $this->db->set('data_cadastro',$horario);
        $this->db->set('chave_primaria',$entrada);
        if (isset($_POST['observacaocancelamento'])) {
             $this->db->set('observacaocancelamento',$_POST['observacaocancelamento']);
        }
        if (isset($_POST['txtmotivo'])) {
             $this->db->set('ambulatorio_cancelamento_id',$_POST['txtmotivo']);
        } 
        $this->db->set('dados', json_encode($dados));
        $this->db->set('tipo',"MANTER_ENTRADA");
        $this->db->set('empresa_id', $this->session->userdata('empresa_id'));
        $this->db->insert('tb_financeiro_excluido');
       
        if ($return[0]->transferencia_id > 0) { 
            $this->db->set('ativo', 'f');
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('transferencia_id', $return[0]->transferencia_id);
            $this->db->update('tb_saldo');

            $this->db->set('ativo', 'f');
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('transferencia_id', $return[0]->transferencia_id);
            $this->db->update('tb_saidas');

            $this->db->set('ativo', 'f');
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('transferencia_id', $return[0]->transferencia_id);
            $this->db->update('tb_entradas');
        }
    }

    function excluirsaida($saida) {

        $this->db->select('transferencia_id');
        $this->db->from('tb_saidas');
        $this->db->where('saidas_id', $saida);
        $return = $this->db->get()->result();
//        var_dump($return); die;

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('saida_id', $saida);
        $this->db->update('tb_saldo');

        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('saidas_id', $saida);
        $this->db->update('tb_saidas');
        
        
        $this->db->select('');
        $this->db->from('tb_saidas');
        $this->db->where('saidas_id', $saida);
        $dados  = $this->db->get()->result();
        
        $this->db->set('operador_cadastro',$operador_id);
        $this->db->set('data_cadastro',$horario);
        $this->db->set('chave_primaria',$saida);
        $this->db->set('dados', json_encode($dados));
        $this->db->set('tipo',"MANTER_SAIDA");
        $this->db->set('empresa_id', $this->session->userdata('empresa_id'));
        if (isset($_POST['observacaocancelamento'])) {
             $this->db->set('observacaocancelamento',$_POST['observacaocancelamento']);
        }
        if (isset($_POST['txtmotivo'])) {
             $this->db->set('ambulatorio_cancelamento_id',$_POST['txtmotivo']);
        } 
        $this->db->insert('tb_financeiro_excluido');


        if ($return[0]->transferencia_id > 0) {

            $this->db->set('ativo', 'f');
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('transferencia_id', $return[0]->transferencia_id);
            $this->db->update('tb_saldo');

            $this->db->set('ativo', 'f');
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('transferencia_id', $return[0]->transferencia_id);
            $this->db->update('tb_saidas');

            $this->db->set('ativo', 'f');
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('transferencia_id', $return[0]->transferencia_id);
            $this->db->update('tb_entradas');
        }
    }

    function excluirsangria($saida) {


        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');

        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('saidas_id', $saida);
        $this->db->update('tb_sangria');
    }

    private function instanciar($saida_id) {

        if ($saida_id != 0) {
            $this->db->select('s.valor,
                            s.saidas_id,
                            s.observacao,
                            s.data,
                            s.valor,
                            s.tipo,
                            s.nome,
                            fcd.razao_social,
                            s.conta,
                            fe.descricao as contadescricao,
                            s.tipo,
                            s.classe');
            $this->db->from('tb_saidas s');
            $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = s.conta', 'left');
            $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = s.nome', 'left');
            $this->db->where("s.saidas_id", $saida_id);
            $query = $this->db->get();
            $return = $query->result();
            $this->_saida_id = $saida_id;
            $this->_valor = $return[0]->valor;
            $this->_devedor = $return[0]->nome;
            $this->_observacao = $return[0]->observacao;
            $this->_tipo = $return[0]->tipo;
            $this->_data = $return[0]->data;
            $this->_razao_social = $return[0]->razao_social;
            $this->_forma = $return[0]->conta;
            $this->_classe = $return[0]->classe;
        } else {
            $this->_estoque_produto_id = null;
        }
    }

    
    function dadosentrada($entrada_id){
           $this->db->select('valor,
                            entradas_id,
                            e.observacao,
                            fe.descricao as conta,
                            data,
                            fcd.razao_social,
                            tipo,
                            classe');
        $this->db->from('tb_entradas e');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = e.conta', 'left');
        $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = e.nome', 'left');
        $this->db->where('e.entradas_id',$entrada_id);
        return $this->db->get()->result();
    }
    
    function dadossaida($saidas_id){
            $this->db->select('s.valor,
                            s.saidas_id,
                            s.observacao,
                            s.data,
                            fcd.razao_social,
                            fe.descricao as conta,
                            s.tipo,
                            s.classe');
        $this->db->from('tb_saidas s');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = s.conta', 'left');
        $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = s.nome', 'left');
        $this->db->where('s.saidas_id',$saidas_id);
        return $this->db->get()->result();
    }
     
         function relatorioentradatipo() {
        if ($_POST['tipo'] != "" && ($_POST['tipo'] != "CAIXA" && $_POST['tipo'] != "TRANSFERENCIA")) {
            $this->db->select('
                            tes.descricao,
                            tipo_entradas_saida_id
                            ');
            $this->db->from('tb_tipo_entradas_saida tes');
            $this->db->where('tes.ativo', 'true');
            $this->db->where('tes.tipo_entradas_saida_id', $_POST['tipo']);
            $return = $this->db->get()->result();
        }else{
             $return = Array();
        }
        $this->db->select('s.valor,
                            s.entradas_id,
                            s.observacao,
                            s.data,
                            e.nome as empresa,
                            fcd.razao_social,
                            fe.descricao as conta,
                            s.tipo,
                            s.classe');
        $this->db->from('tb_entradas s');
        $this->db->join('tb_forma_entradas_saida fe', 'fe.forma_entradas_saida_id = s.conta', 'left');
        $this->db->join('tb_financeiro_credor_devedor fcd', 'fcd.financeiro_credor_devedor_id = s.nome', 'left');
        $this->db->join('tb_empresa e', 'e.empresa_id = s.empresa_id', 'left');
        $this->db->where('s.ativo', 'true');
        if ($_POST['credordevedor'] != 0) {
            $this->db->where('fcd.financeiro_credor_devedor_id ', $_POST['credordevedor']);
        }
        if ($_POST['empresa'] != "") {
            $this->db->where('s.empresa_id', $_POST['empresa']);
        } 
         
        if ($_POST['tipo'] != "") { 
            if($_POST['tipo'] == "CAIXA"){
              $this->db->where('tipo', 'CAIXA');  
            }elseif($_POST['tipo'] == "TRANSFERENCIA"){
              $this->db->where('tipo', 'TRANSFERENCIA'); 
            }else{
              $this->db->where('tipo', $return[0]->descricao);
            } 
        }
       
        
        if ($_POST['classe'] != '') {
            $this->db->where('s.classe', $_POST['classe']);
        }
        if ($_POST['conta'] != 0) {
            $this->db->where('s.conta', $_POST['conta']);
        }
        $this->db->where('s.data >=', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_inicio']))));
        $this->db->where('s.data <=', date("Y-m-d", strtotime(str_replace('/', '-', $_POST['txtdata_fim']))));
        $this->db->orderby('s.tipo');
        $this->db->orderby('s.data');
        $this->db->orderby('fcd.razao_social');
        $return = $this->db->get();
        return $return->result();
    }
    
}

?>
