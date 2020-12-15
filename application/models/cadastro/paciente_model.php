    <?php

require_once APPPATH . 'models/base/BaseModel.php';

//require_once APPPATH . 'models/base/ConvertXml.php';



class paciente_model extends BaseModel {

    var $_paciente_id = null;
    var $_nome = null;
    var $_paciente_indicacao_id = null;
    var $_cns = null;
    var $_nascimento = null;
    var $_descricaoconvenio = null;
    var $_idade = null;
    var $_cpf = null;
    var $_documento = null;
    var $_orgao = null;
    var $_estado_id_expedidor = null;
    var $_titulo_eleitor = null;
    var $_raca_cor = null;
    var $_sexo = null;
    var $_estado_civil = null;
    var $_nomepai = null;
    var $_nomemae = null;
    var $_celular = null;
    var $_whatsapp = null;
    var $_telefone = null;
    var $_telefoneresp = null;
    var $_tipoLogradouro = null;
    var $_rua = null;
    var $_numero = null;
    var $_bairro = null;
    var $_complemento = null;
    var $_estado_id = null;
    var $_cidade = null;
    var $_cep = null;
    var $_observacao = null;
    var $__convenio = null;
    var $_cidade_nome = null;
    var $_data_emissao = null;
    var $_cbo_ocupacao_id = null;
    var $_cbo_nome = null;
    var $_indicacao = null;

//    var $_prontuario_antigo = null;



    function Paciente_model($paciente_id = null) {
        parent::Model();
        if (isset($paciente_id)) {
            $this->instanciar($paciente_id);
        }
    }

    function contador() {

        $this->db->select();
        $this->db->from('tb_paciente');
        $this->db->where('nome', $_POST['nome']);
        $this->db->where('nascimento', date("Y-m-d", strtotime(str_replace("/", "-", $_POST['nascimento']))));
        $this->db->where('nome_mae', $_POST['nome_mae']);
        // $this->db->where('ativo', 't');
        $return = $this->db->count_all_results();
        return $return;
    }

    function contadorcpf() {
        $this->db->select();
        $this->db->from('tb_paciente');
        $this->db->where('cpf', str_replace("-", "", str_replace(".", "", $_POST['cpf'])));
        // $this->db->where('ativo', 't');
        $this->db->where('cpf_responsavel_flag', 'f');
        $return = $this->db->count_all_results();
        return $return;
    }

    function pacientelog($paciente_id){
        $this->db->select('c.nome, o.nome as operador_cadastro,
        c.data_cadastro, 
        oa.nome as operador_atualizacao,
        c.data_atualizacao,
        c.nascimento');
        $this->db->from('tb_paciente c');
        $this->db->join('tb_operador o', 'o.operador_id = c.operador_cadastro', 'left');
        $this->db->join('tb_operador oa', 'oa.operador_id = c.operador_atualizacao', 'left');
        $this->db->where('c.paciente_id', $paciente_id);
        $return = $this->db->get();
        $retorno = $return->result();

        return $retorno;
    }

    function contadorcpf2() {
        $this->db->select();
        $this->db->from('tb_paciente');
        $this->db->where('cpf', str_replace("-", "", str_replace(".", "", $_POST['cpf'])));
        // $this->db->where('ativo', 't');
        if ($_POST['paciente_id'] > 0) {
            $this->db->where('paciente_id !=', $_POST['paciente_id']);
        }
        $this->db->where('cpf_responsavel_flag', 'f');
        $return = $this->db->count_all_results();
        return $return;
    }

    function contadorcpfautocomplete($cpf, $paciente_id) {
        $this->db->select();
        $this->db->from('tb_paciente');
        $this->db->where('cpf', str_replace("-", "", str_replace(".", "", $cpf)));
        // $this->db->where('ativo', 't');
        if ($paciente_id > 0) {
            $this->db->where('paciente_id !=', $paciente_id);
        }
        $this->db->where('cpf_responsavel_flag', 'f');
        $return = $this->db->count_all_results();
        return $return;
    }

    function contadorpacientefidelidade($obj_paciente) {
        $this->db->select();
        $this->db->from('tb_paciente');
        $this->db->where('cpf', str_replace("-", "", str_replace(".", "", $obj_paciente[0]->cpf)));
        $this->db->where('ativo', 't');
        $this->db->where('prontuario_antigo', $obj_paciente[0]->paciente_id);
        $return = $this->db->count_all_results();
        return $return;
    }

    function listar($args = array()) {
        $this->db->from('tb_paciente')
                ->join('tb_municipio', 'tb_municipio.municipio_id = tb_paciente.municipio_id', 'left')
                ->select('"tb_paciente".*, tb_municipio.nome as ciade, tb_municipio.estado')
                ->where('ativo', 'true');

        if ($args) {
            if (isset($args['prontuario']) && strlen($args['prontuario']) > 0) {
                $this->db->where('paciente_id', $args['prontuario']);
                $this->db->where('ativo', 'true');
            }
            if (isset($args['prontuario_antigo']) && strlen($args['prontuario_antigo']) > 0) {
                $this->db->where('prontuario_antigo', $args['prontuario_antigo']);
                $this->db->where('ativo', 'true');
            }
            if (isset($args['nome']) && strlen($args['nome']) > 0) {
                $nome = $this->removerCaracterEsp($args['nome']);
                // var_dump($nome); die;
                $this->db->where("translate(tb_paciente.nome,  
                'áàâãäåaaaÁÂÃÄÅAAAÀéèêëeeeeeEEEÉEEÈÊìíîïìiiiÌÍÎÏÌIIIóôõöoooòÒÓÔÕÖOOOùúûüuuuuÙÚÛÜUUUUçÇñÑýÝ',  
                'aaaaaaaaaAAAAAAAAAeeeeeeeeeEEEEEEEEiiiiiiiiIIIIIIIIooooooooOOOOOOOOuuuuuuuuUUUUUUUUcCnNyY'   
                 ) ilike", '%' . $nome . '%');
                $this->db->where('ativo', 'true');
            }
            if (isset($args['nome_mae']) && strlen($args['nome_mae']) > 0) {
                $nome_mae = $this->removerCaracterEsp($args['nome_mae']);
                $this->db->where("translate(tb_paciente.nome_mae,  
                'áàâãäåaaaÁÂÃÄÅAAAÀéèêëeeeeeEEEÉEEÈÊìíîïìiiiÌÍÎÏÌIIIóôõöoooòÒÓÔÕÖOOOùúûüuuuuÙÚÛÜUUUUçÇñÑýÝ',  
                'aaaaaaaaaAAAAAAAAAeeeeeeeeeEEEEEEEEiiiiiiiiIIIIIIIIooooooooOOOOOOOOuuuuuuuuUUUUUUUUcCnNyY'   
                 ) ilike", '%' . $nome_mae . '%');
                $this->db->where('ativo', 'true');
            }

            if (isset($args['nome_pai']) && strlen($args['nome_pai']) > 0) {
                $nome_mae = $this->removerCaracterEsp($args['nome_pai']);
                $this->db->where("translate(tb_paciente.nome_pai,  
                'áàâãäåaaaÁÂÃÄÅAAAÀéèêëeeeeeEEEÉEEÈÊìíîïìiiiÌÍÎÏÌIIIóôõöoooòÒÓÔÕÖOOOùúûüuuuuÙÚÛÜUUUUçÇñÑýÝ',  
                'aaaaaaaaaAAAAAAAAAeeeeeeeeeEEEEEEEEiiiiiiiiIIIIIIIIooooooooOOOOOOOOuuuuuuuuUUUUUUUUcCnNyY'   
                 ) ilike", '%' . $nome_mae . '%');
                $this->db->where('ativo', 'true');
            }

            // if (isset($args['nome']) && strlen($args['nome']) > 0) {
            //     $this->db->where('tb_paciente.nome ilike', '%' . $args['nome'] . '%');
            //     $this->db->where('ativo', 'true');
            // }
            // if (isset($args['nome_mae']) && strlen($args['nome_mae']) > 0) {
            //     $this->db->where('tb_paciente.nome_mae ilike', '%' . $args['nome_mae'] . '%');
            //     $this->db->where('ativo', 'true');
            // }
            if (isset($args['nascimento']) && strlen($args['nascimento']) > 0) {
                $this->db->where('tb_paciente.nascimento', date("Y-m-d", strtotime(str_replace("/", "-", $args['nascimento']))));
                $this->db->where('ativo', 'true');
            }

            if (isset($args['cpf']) && strlen($args['cpf']) > 0) {
                $this->db->where('tb_paciente.cpf ilike', '%' . $args ['cpf'] . '%');
                $this->db->where('ativo', 'true');
            }

            if (isset($args ['telefone']) && strlen($args ['telefone']) > 0) {
                $this->db->where("(tb_paciente.celular ilike '%" . $args['telefone'] . "%' OR tb_paciente.telefone ilike '%" . $args['telefone'] . "%')");
                $this->db->where('ativo', 'true');
            }

            if (isset($args['guia_id']) && @$args['guia_id'] > 0) {
                $guia_id = $args['guia_id'];
                $this->db->where("paciente_id IN (SELECT paciente_id 
                                                FROM ponto.tb_ambulatorio_guia
                                                WHERE ambulatorio_guia_id = $guia_id) ");
            }
        }

        return $this->db;
    }

    function listarpesquisardesativado($args = array()) {
        $this->db->from('tb_paciente')
                ->join('tb_municipio', 'tb_municipio.municipio_id = tb_paciente.municipio_id', 'left')
                ->select('"tb_paciente".*, tb_municipio.nome as ciade, tb_municipio.estado')
                ->where('ativo', 'false');

        if ($args) {
            if (isset($args['prontuario']) && strlen($args['prontuario']) > 0) {
                $this->db->where('paciente_id', $args['prontuario']);
                $this->db->where('ativo', 'false');
            }
            if (isset($args['prontuario_antigo']) && strlen($args['prontuario_antigo']) > 0) {
                $this->db->where('prontuario_antigo', $args['prontuario_antigo']);
                $this->db->where('ativo', 'false');
            }
            if (isset($args['nome']) && strlen($args['nome']) > 0) {
                $nome = $this->removerCaracterEsp($args['nome']);
                // var_dump($nome); die;
                $this->db->where("translate(tb_paciente.nome,  
                'áàâãäåaaaÁÂÃÄÅAAAÀéèêëeeeeeEEEÉEEÈÊìíîïìiiiÌÍÎÏÌIIIóôõöoooòÒÓÔÕÖOOOùúûüuuuuÙÚÛÜUUUUçÇñÑýÝ',  
                'aaaaaaaaaAAAAAAAAAeeeeeeeeeEEEEEEEEiiiiiiiiIIIIIIIIooooooooOOOOOOOOuuuuuuuuUUUUUUUUcCnNyY'   
                 ) ilike", '%' . $nome . '%');
                $this->db->where('ativo', 'false');
            }
            if (isset($args['nome_mae']) && strlen($args['nome_mae']) > 0) {
                $nome_mae = $this->removerCaracterEsp($args['nome_mae']);
                $this->db->where("translate(tb_paciente.nome_mae,  
                'áàâãäåaaaÁÂÃÄÅAAAÀéèêëeeeeeEEEÉEEÈÊìíîïìiiiÌÍÎÏÌIIIóôõöoooòÒÓÔÕÖOOOùúûüuuuuÙÚÛÜUUUUçÇñÑýÝ',  
                'aaaaaaaaaAAAAAAAAAeeeeeeeeeEEEEEEEEiiiiiiiiIIIIIIIIooooooooOOOOOOOOuuuuuuuuUUUUUUUUcCnNyY'   
                 ) ilike", '%' . $nome_mae . '%');
                $this->db->where('ativo', 'false');
            }

            if (isset($args['nome_pai']) && strlen($args['nome_pai']) > 0) {
                $nome_mae = $this->removerCaracterEsp($args['nome_pai']);
                $this->db->where("translate(tb_paciente.nome_pai,  
                'áàâãäåaaaÁÂÃÄÅAAAÀéèêëeeeeeEEEÉEEÈÊìíîïìiiiÌÍÎÏÌIIIóôõöoooòÒÓÔÕÖOOOùúûüuuuuÙÚÛÜUUUUçÇñÑýÝ',  
                'aaaaaaaaaAAAAAAAAAeeeeeeeeeEEEEEEEEiiiiiiiiIIIIIIIIooooooooOOOOOOOOuuuuuuuuUUUUUUUUcCnNyY'   
                 ) ilike", '%' . $nome_mae . '%');
                $this->db->where('ativo', 'false');
            }

            // if (isset($args['nome']) && strlen($args['nome']) > 0) {
            //     $this->db->where('tb_paciente.nome ilike', '%' . $args['nome'] . '%');
            //     $this->db->where('ativo', 'true');
            // }
            // if (isset($args['nome_mae']) && strlen($args['nome_mae']) > 0) {
            //     $this->db->where('tb_paciente.nome_mae ilike', '%' . $args['nome_mae'] . '%');
            //     $this->db->where('ativo', 'true');
            // }
            if (isset($args['nascimento']) && strlen($args['nascimento']) > 0) {
                $this->db->where('tb_paciente.nascimento', date("Y-m-d", strtotime(str_replace("/", "-", $args['nascimento']))));
                $this->db->where('ativo', 'false');
            }

            if (isset($args['cpf']) && strlen($args['cpf']) > 0) {
                $this->db->where('tb_paciente.cpf ilike', '%' . $args ['cpf'] . '%');
                $this->db->where('ativo', 'false');
            }

            if (isset($args ['telefone']) && strlen($args ['telefone']) > 0) {
                $this->db->where("(tb_paciente.celular ilike '%" . $args['telefone'] . "%' OR tb_paciente.telefone ilike '%" . $args['telefone'] . "%')");
                $this->db->where('ativo', 'false');
            }

            if (isset($args['guia_id']) && @$args['guia_id'] > 0) {
                $guia_id = $args['guia_id'];
                $this->db->where("paciente_id IN (SELECT paciente_id 
                                                FROM ponto.tb_ambulatorio_guia
                                                WHERE ambulatorio_guia_id = $guia_id) ");
            }
        }

        return $this->db;
    }

    function listardadospacienterelatorionota($paciente_id) {
        $this->db->select('tp.tipo_logradouro_id as codigo_logradouro,co.nome as nome_convenio, co.convenio_id as convenio,tp.descricao,p.*,c.estado, c.nome as cidade_desc,c.municipio_id as cidade_cod');
        $this->db->from('tb_paciente p');
        $this->db->join('tb_municipio c', 'c.municipio_id = p.municipio_id', 'left');
        $this->db->join('tb_convenio co', 'co.convenio_id = p.convenio_id', 'left');
        $this->db->join('tb_tipo_logradouro tp', 'p.tipo_logradouro = tp.tipo_logradouro_id', 'left');
        $this->db->where("p.paciente_id", $paciente_id);
        $return = $this->db->get();
        return $return->result();
    }

    function removerCaracterEsp($string) {
        return preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/"), explode(" ", "a A e E i I o O u U n N"), $string);
    }

    function listarCidadesibge($parametro = null) {
        $this->db->select('municipio_id,
                           nome,estado');
        $this->db->where('codigo_ibge', $parametro);

        $this->db->from('tb_municipio');
        $return = $this->db->get();

        return $return->result();
    }

    function listardados($paciente_id) {
        $this->db->select('p.convenionumero,tp.tipo_logradouro_id as codigo_logradouro,co.nome as nome_convenio, co.convenio_id as convenio,tp.descricao,p.*,c.estado, c.nome as cidade_desc,c.municipio_id as cidade_cod,p.paciente_id,p.nascimento,p.numero,p.sexo');
        $this->db->from('tb_paciente p');
        $this->db->join('tb_municipio c', 'c.municipio_id = p.municipio_id', 'left');
        $this->db->join('tb_convenio co', 'co.convenio_id = p.convenio_id', 'left');
        $this->db->join('tb_tipo_logradouro tp', 'p.tipo_logradouro = tp.tipo_logradouro_id', 'left');
        $this->db->where("p.paciente_id", $paciente_id);
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriocancelamento($paciente_id) {

        $this->db->select('ac.agenda_exames_id,
                            ac.data_cadastro as data,
                            ac.operador_cadastro,
                            o.nome as operador,
                            c.nome as convenio,
                            ac.paciente_id,
                            ae.data_autorizacao,
                            ac.observacao_cancelamento,
                            p.nome as paciente,
                            ac.procedimento_tuss_id,
                            pt.nome as exame,
                            pt.grupo,
                            ca.descricao,
                            pt.descricao as procedimento,
                            pt.codigo');
        $this->db->from('tb_ambulatorio_atendimentos_cancelamento ac');
        $this->db->join('tb_agenda_exames ae', 'ae.agenda_exames_id = ac.agenda_exames_id', 'left');
        $this->db->join('tb_paciente p', 'p.paciente_id = ac.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ac.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->join('tb_ambulatorio_cancelamento ca', 'ca.ambulatorio_cancelamento_id = ac.ambulatorio_cancelamento_id', 'left');
        $this->db->join('tb_operador o', 'o.operador_id = ac.operador_cadastro', 'left');
        $this->db->where("ac.paciente_id ", $paciente_id);

        $this->db->orderby('c.convenio_id');
        $this->db->orderby('ac.data_cadastro');
        $this->db->orderby('p.nome');
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriocancelamentocontador($paciente_id) {

        $this->db->select('ac.agenda_exames_id');
        $this->db->from('tb_ambulatorio_atendimentos_cancelamento ac');
        $this->db->join('tb_paciente p', 'p.paciente_id = ac.paciente_id', 'left');
        $this->db->join('tb_procedimento_convenio pc', 'pc.procedimento_convenio_id = ac.procedimento_tuss_id', 'left');
        $this->db->join('tb_procedimento_tuss pt', 'pt.procedimento_tuss_id = pc.procedimento_tuss_id', 'left');
        $this->db->join('tb_convenio c', 'c.convenio_id = pc.convenio_id', 'left');
        $this->db->where("ac.paciente_id ", $paciente_id);

        $return = $this->db->count_all_results();
        return $return;
    }

    function listarCidades($parametro = null) {
        $this->db->select('municipio_id,
                           nome,estado,codigo_ibge');
        if ($parametro != null) {
            $this->db->where('nome ilike', $parametro . "%");
        }
        $this->db->from('tb_municipio');
        $return = $this->db->get();

        return $return->result();
    }

    function cep($parametro = null) {

        $this->db->select('cl.cep,
                           cl.logradouro_nome,
                           cl.tipo_logradouro,
                           cloc.localidade_nome,
                           cloc.uf,
                           cb.nome_bairro');
        $this->db->from('tb_cep_logradouro cl');
        $this->db->join('tb_cep_localidade cloc', 'cloc.localidade_id = cl.localidade_id', 'left');
        $this->db->join('tb_cep_bairro cb', 'cb.cep_bairro_id = cl.bairro_inicial', 'left');

        if ($parametro != null) {
//            $parametro = intval($parametro);
            $this->db->where('cl.cep ilike', $parametro . "%");
        }

        $return = $this->db->get();
        return $return->result();
    }

//    function cep_buscarcidade($cidadenome) {
//
//        $this->db->select('municipio_id,
//                               nome');
//        $this->db->from('tb_municipio');
//        $this->db->where('nome', $cidadenome);
//
//        $return = $this->db->get();
//        return $return->result();
//    }

    private function instanciar($paciente_id) {
        if ($paciente_id != 0) {
            $this->db->select('p.prontuario_antigo,p.observacao, tp.tipo_logradouro_id as codigo_logradouro,co.convenio_id as convenio,p.escolaridade_id, co.nome as descricaoconvenio,cbo.descricao as cbo_nome, tp.descricao,p.*,c.nome as cidade_desc,c.municipio_id as cidade_cod,p.whatsapp');
            $this->db->from('tb_paciente p');
            $this->db->join('tb_municipio c', 'c.municipio_id = p.municipio_id', 'left');
            $this->db->join('tb_convenio co', 'co.convenio_id = p.convenio_id', 'left');
            $this->db->join('tb_tipo_logradouro tp', 'p.tipo_logradouro = tp.tipo_logradouro_id', 'left');
            $this->db->join('tb_cbo_ocupacao cbo', 'cbo.cbo_ocupacao_id = p.profissao', 'left');
            $this->db->where("paciente_id", $paciente_id);
            $query = $this->db->get();
            $return = $query->result();
            $this->_paciente_id = $paciente_id;
            $this->_cpf = $return[0]->cpf;
            $this->_cpf_mae = $return[0]->cpf_mae;
            $this->_cpf_pai = $return[0]->cpf_pai;
            $this->_outro_convenio = $return[0]->outro_convenio;
            $this->_cpf_responsavel = @$return[0]->cpf_responsavel;
            $this->_nome = $return[0]->nome;
            $this->_cns = $return[0]->cns;
            $this->_cns2 = $return[0]->cns2;
            if (isset($return[0]->nascimento)) {
                $this->_nascimento = $return[0]->nascimento;
            }
            $this->_idade = $return[0]->idade;
            $this->_nacionalidade = @$return[0]->nacionalidade;
            $this->_cbo_nome = $return[0]->cbo_nome;
            $this->_cbo_ocupacao_id = $return[0]->profissao;
            $this->_documento = $return[0]->rg;
//            $this->_grau_parentesco = $return[0]->grau_parentesco;
            $this->_estado_id_expedidor = $return[0]->uf_rg;
            $this->_vencimento_carteira = @$return[0]->vencimento_carteira;
            $this->_vencimento_cnh = @$return[0]->vencimento_cnh;
            $this->_cnh = @$return[0]->cnh;
            $this->_titulo_eleitor = $return[0]->titulo_eleitor;
            $this->_raca_cor = $return[0]->raca_cor;
            $this->_sexo = $return[0]->sexo;
            $this->_empresa = $return[0]->empresa;
            $this->_sexo_real = @$return[0]->sexo_real;
            $this->_estado_civil = $return[0]->estado_civil_id;
            $this->_escolaridade_id = $return[0]->escolaridade_id;
            $this->_nomepai = $return[0]->nome_pai;
            $this->_nomemae = $return[0]->nome_mae;
            $this->_nome_conjuge = @$return[0]->nome_conjuge;
            $this->_nascimento_conjuge = @$return[0]->nascimento_conjuge;
            $this->_celular = $return[0]->celular;
            $this->_whatsapp = $return[0]->whatsapp;
            $this->_instagram = @$return[0]->instagram;
            $this->_prontuario_antigo = $return[0]->prontuario_antigo;
            $this->_telefone = $return[0]->telefone;
            $this->_ocupacao_pai = @$return[0]->ocupacao_pai;
            $this->_ocupacao_mae = @$return[0]->ocupacao_mae;
            $this->_telefoneresp = $return[0]->telefoneresp;
            $this->_nomeresp = $return[0]->nomeresp;
            $this->_tipoLogradouro = $return[0]->codigo_logradouro;
            $this->_numero = $return[0]->numero;
            $this->_endereco = $return[0]->logradouro;
            $this->_uf_rg = $return[0]->uf_rg;
            $this->_bairro = $return[0]->bairro;
            $this->_numeroresp = $return[0]->numeroresp;
            $this->_enderecoresp = $return[0]->logradouroresp;
            $this->_bairroresp = $return[0]->bairroresp;
            $this->_cidade = $return[0]->municipio_id;
            $this->_cep = $return[0]->cep;
            $this->_observacao = $return[0]->observacao;
            $this->_complementoresp = $return[0]->complementoresp;
            $this->_complemento = $return[0]->complemento;
            $this->_cidade_nome = $return[0]->cidade_desc;
            $this->_convenio = $return[0]->convenio;
            $this->_descricaoconvenio = $return[0]->descricaoconvenio;
            $this->_convenionumero = $return[0]->convenionumero;
            $this->_data_emissao = $return[0]->data_emissao;
            $this->_indicacao = $return[0]->indicacao;
            $this->_cpf_responsavel_flag = @$return[0]->cpf_responsavel_flag;
            $this->_leito = @$return[0]->leito;
            $this->_whatsapp = $return[0]->whatsapp;
            $this->_usuario_app = @$return[0]->usuario_app;
            $this->_senha_app = $return[0]->senha_app;
        }
    }

    function listaTipoLogradouro() {

        $this->db->select('tipo_logradouro_id, descricao');
        $this->db->from('tb_tipo_logradouro');
        $this->db->orderby('tipo_logradouro_id');
        $return = $this->db->get();
        return $return->result();
    }

    function listaconvenio() {

        $empresa_id = $this->session->userdata('empresa_id');

        $this->db->select(' c.convenio_id,
                            c.nome,
                            c.dinheiro,
                            c.conta_id');
        $this->db->from('tb_convenio c');
        $this->db->join('tb_convenio_empresa ce', 'ce.convenio_id = c.convenio_id', 'left');
        $this->db->where("c.ativo", 'true');
        $this->db->where("ce.empresa_id", $empresa_id);
        $this->db->where("ce.ativo", 'true');
        $this->db->orderby("c.nome");
        $query = $this->db->get();
        $return = $query->result();

        return $return;
    }

    function listaindicacaoranqueada() {
//        die('teste');
        $this->db->select("paciente_indicacao_id, 
                           nome, 
                           registro");
        $this->db->from('tb_paciente_indicacao pi');
        $this->db->where('ativo', 't');
        // $this->db->orderby("rank DESC");
        $this->db->orderby("nome");
        $return = $this->db->get();
        return $return->result();
    }

    function listaindicacao() {

        $this->db->select('paciente_indicacao_id, nome, registro');
        $this->db->from('tb_paciente_indicacao');
        $this->db->where('ativo', 't');
        $this->db->orderby('nome');
        $return = $this->db->get();
        return $return->result();
    }

    function listacadaindicacao($paciente_indicacao_id) {

        $this->db->select('paciente_indicacao_id, nome');
        $this->db->from('tb_paciente_indicacao');
        $this->db->where('paciente_indicacao_id', $paciente_indicacao_id);
        $return = $this->db->get();
        return $return->result();
    }

    function gravar() {
        $empresa_id  = $this->session->userdata('empresa_id');

        try {
            if ($_POST['txtcbo'] != $_POST['txtcbohidden']) {
                $this->db->select('cbo_ocupacao_id');
                $this->db->from('tb_cbo_ocupacao');
                $this->db->orderby('cbo_ocupacao_id desc');
                $this->db->limit(1);
                $ultimaprofissao = $this->db->get()->result();
//                var_dump($ultimaprofissao); die;
                $last_id = $ultimaprofissao[0]->cbo_ocupacao_id + 1;

                $this->db->set('cbo_ocupacao_id', $last_id);
                $this->db->set('descricao', $_POST['txtcbo']);
                $this->db->insert('tb_cbo_ocupacao');
                $ocupacao_id = $last_id;

                $this->db->set('profissao', $ocupacao_id);
            } elseif ($_POST['txtcboID'] != "") {
                $this->db->set('profissao', $_POST['txtcboID']);
            }


            $this->db->set('nome', $_POST['nome']);
            if ($_POST['cpf'] != '') {
                $this->db->set('cpf', str_replace("-", "", str_replace(".", "", $_POST['cpf'])));
            }
            if ($_POST['cpf_mae'] != '') {
                $this->db->set('cpf_mae', str_replace("-", "", str_replace(".", "", $_POST['cpf_mae'])));
            }
            if ($_POST['cpf_pai'] != '') {
                $this->db->set('cpf_pai', str_replace("-", "", str_replace(".", "", $_POST['cpf_pai'])));
            }
            if (isset($_POST['cpf_responsavel'])) {
                $this->db->set('cpf_responsavel_flag', 't');
            } else {
                $this->db->set('cpf_responsavel_flag', 'f');
            }
            if($_POST['idade2'] != ""){
              $this->db->set('idade', $_POST['idade2']);
            }else{
               $this->db->set('idade', null);
            }
            
            if (isset($_POST['nascimento']) && $_POST['nascimento'] != '') {
                $this->db->set('nascimento', date("Y-m-d", strtotime(str_replace("/", "-", $_POST['nascimento']))));
            } else {
                $this->db->set('nascimento', null);
            }
//            if ($_POST['data_emissao'] != '') {
//                $this->db->set('data_emissao', $_POST['data_emissao']);
//            }

            if ($_POST['convenio'] != '' && $_POST['convenio'] != 'OUTROS') { 
                $this->db->set('convenio_id', $_POST['convenio']);
            }else{
                $this->db->set('convenio_id', null);
            }
            if ($_POST['outroplano'] != '') { 
                $this->db->set('outro_convenio', $_POST['outroplano']);
            }else{
                 $this->db->set('outro_convenio', null);
            }
            
            
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
            if (@$_POST['indicacao'] != '') {
                $this->db->set('indicacao', $_POST['indicacao']);
            }else{
                  $this->db->set('indicacao', null);
            }
            
            if ($_POST['instagram'] != '') {
                $this->db->set('instagram', $_POST['instagram']);
            }else{
                 $this->db->set('instagram', null);
            }
            if (isset($_POST['nome_conjuge']) && $_POST['nome_conjuge'] != '') {
                $this->db->set('nome_conjuge', $_POST['nome_conjuge']);
            }else{
                   $this->db->set('nome_conjuge', null);
            }
            if (isset($_POST['ocupacao_mae']) && $_POST['ocupacao_mae'] != '') {
                $this->db->set('ocupacao_mae', $_POST['ocupacao_mae']);
            }else{
                 $this->db->set('ocupacao_mae', null);  
            }
            
            if (isset($_POST['ocupacao_pai']) && $_POST['ocupacao_pai'] != '') {
                $this->db->set('ocupacao_pai', $_POST['ocupacao_pai']);
            }else{
                $this->db->set('ocupacao_pai', null);   
            }
            if (isset($_POST['nascimento_conjuge']) && $_POST['nascimento_conjuge'] != '') {
                $this->db->set('nascimento_conjuge', date("Y-m-d", strtotime(str_replace("/", "-", $_POST['nascimento_conjuge']))));
            }else{
                $this->db->set('nascimento_conjuge', null);   
            }
            if ($_POST['vencimento_carteira'] != '') {
                $this->db->set('vencimento_carteira', date("Y-m-d", strtotime(str_replace("/", "-", $_POST['vencimento_carteira']))));
            }else{
                $this->db->set('vencimento_carteira', null);   
            }
            if(isset($_POST['cnh']) && $_POST['cnh'] != ""){
                $this->db->set('cnh', $_POST['cnh']);
            }else{
                 $this->db->set('cnh', null); 
            }
            if ($_POST['vencimento_cnh'] != '') {
                $this->db->set('vencimento_cnh', date("Y-m-d", strtotime(str_replace("/", "-", $_POST['vencimento_cnh']))));
            }else{
                  $this->db->set('vencimento_cnh', null); 
            }
            if ($_POST['escolaridade'] != '') {
                $this->db->set('escolaridade_id', $_POST['escolaridade']);
            }else{
               $this->db->set('escolaridade_id', null); 
            }
            if(isset($_POST['rg']) && $_POST['rg'] != ""){
              $this->db->set('rg', $_POST['rg']);
            }else{
               $this->db->set('rg', null); 
            }
             if(isset($_POST['obs']) && $_POST['obs'] != ""){
                $this->db->set('observacao', $_POST['obs']);
             }else{
               $this->db->set('observacao', null); 
             }
//            $this->db->set('uf_rg', $_POST['uf_rg']);
//            $this->db->set('titulo_eleitor', $_POST['titulo_eleitor']);
            if(isset($_POST['sexo']) && $_POST['sexo'] != ""){
              $this->db->set('sexo', $_POST['sexo']);
            }else{
                  $this->db->set('sexo',null);
            }

            if (isset($_POST['sexo_real']) && $_POST['sexo_real'] != '') {
              $this->db->set('sexo_real', $_POST['sexo_real']); 
            }else{
                  $this->db->set('sexo_real',null);  
            }
            if(isset($_POST['txtempresa']) && $_POST['txtempresa'] != ""){
                 $this->db->set('empresa', @$_POST['txtempresa']);
            }else{
                 $this->db->set('empresa',null);  
            }
            if ($_POST['raca_cor'] != '') {
                $this->db->set('raca_cor', $_POST['raca_cor']);
            }else{
                $this->db->set('raca_cor',null);   
            }
            if ($_POST['estado_civil_id'] != '') {
                $this->db->set('estado_civil_id', $_POST['estado_civil_id']);
            }else{
                  $this->db->set('estado_civil_id',null); 
            }
            if ($_POST['leito'] != '') {
                $this->db->set('leito', $_POST['leito']);
            }else{
                $this->db->set('leito',null); 
            }
            if (isset($_POST['prontuario_antigo']) && $_POST['prontuario_antigo'] != '') {
                $this->db->set('prontuario_antigo', $_POST['prontuario_antigo']);
            }else{
              $this->db->set('prontuario_antigo',null);  
            }
            if(isset($_POST['nacionalidade']) && $_POST['nacionalidade'] != ""){
                $this->db->set('nacionalidade', $_POST['nacionalidade']);
            }else{
                 $this->db->set('nacionalidade',null);  
            }
            if(isset($_POST['nome_pai']) && $_POST['nome_pai'] != ""){
              $this->db->set('nome_pai', $_POST['nome_pai']);
            }else{
                  $this->db->set('nome_pai',null);  
            }
            if(isset($_POST['nome_mae']) && $_POST['nome_mae'] != ""){
              $this->db->set('nome_mae', $_POST['nome_mae']);
            }else{
                 $this->db->set('nome_mae',null);  
            }
           if(isset($_POST['celular']) && str_replace("(", "", str_replace(")", "", str_replace("-", "", $_POST['celular']))) != ""){
             $this->db->set('celular', str_replace("(", "", str_replace(")", "", str_replace("-", "", $_POST['celular']))));
           }else{
                $this->db->set('celular',null);
           }
            if(isset($_POST['telefone']) && str_replace("(", "", str_replace(")", "", str_replace("-", "", $_POST['telefone']))) != ""){
              $this->db->set('telefone', str_replace("(", "", str_replace(")", "", str_replace("-", "", $_POST['telefone']))));
            }else{
                $this->db->set('telefone',null);
            }
            if(isset($_POST['txtwhatsapp']) && str_replace("(", "", str_replace(")", "", str_replace("-", "", $_POST['txtwhatsapp']))) != ""){
                $this->db->set('whatsapp', str_replace("(", "", str_replace(")", "", str_replace("-", "", $_POST['txtwhatsapp']))));
            }else{
                $this->db->set('whatsapp',null);
            }
            
            if (isset($_POST['tipo_logradouro']) && @$_POST['tipo_logradouro'] != '') {
                $this->db->set('tipo_logradouro', $_POST['tipo_logradouro']);
            }else{
                $this->db->set('tipo_logradouro',null);
            }
            if($_POST['endereco'] != ""){
            $this->db->set('logradouro', $_POST['endereco']);
            }else{
                $this->db->set('logradouro',null);  
            }
            if($_POST['convenionumero'] != ""){
             $this->db->set('convenionumero', $_POST['convenionumero']);
            }else{
                $this->db->set('convenionumero',null);  
            }
            if($_POST['numero'] != ""){
              $this->db->set('numero', $_POST['numero']);
            }else{
                $this->db->set('numero', null); 
            }
            if($_POST['bairro'] != ""){
                  $this->db->set('bairro', $_POST['bairro']); 
            }else{
                $this->db->set('bairro',null);   
            }
               if($_POST['complemento'] != ""){ 
            $this->db->set('complemento', $_POST['complemento']);
               }else{
               $this->db->set('complemento', null);  
            }
            
            if ($_POST['municipio_id'] != '') {
                $this->db->set('municipio_id', $_POST['municipio_id']);
            }else{
                $this->db->set('municipio_id', null); 
            }
            if ($_POST['txtcboID'] != '') {
                $this->db->set('profissao', $_POST['txtcboID']);
            }else{
                 $this->db->set('profissao', null);
            }
            if($_POST['cep'] != ""){
              $this->db->set('cep', $_POST['cep']);
            }else{
               $this->db->set('cep', null);  
            }
            if(isset($_POST['txtUsuarioapp']) && $_POST['txtUsuarioapp'] != ""){
               $this->db->set('usuario_app', $_POST['txtUsuarioapp']);
            } 
            if(isset($_POST['txtSenhaapp']) && $_POST['txtSenhaapp'] != ''){
                $this->db->set('senha_app', md5($_POST['txtSenhaapp']));
            } 
            
            $this->db->set('empresa_id', $empresa_id);
             
            

            $horario = date("Y-m-d H:i:s");
            $data = date("Y-m-d");
            $operador_id = $this->session->userdata('operador_id');

            $dia = substr($horario, 8, 2);
            $mes = substr($horario, 5, 2);
            $ano = substr($horario, 0, 4);
            $dataatual = $dia . '/' . $mes . '/' . $ano;

            // $this->db->set('paciente_id',$_POST['txtPacienteId'] );

            if ($_POST['paciente_id'] == "") {// insert
                $this->db->set('data_cadastro', $data);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_paciente');
                $erro = $this->db->_error_message();
                if (trim($erro) != "") { // erro de banco
                    return false;
                } else
                    $paciente_id = $this->db->insert_id();
            }
            else { // update
                $paciente_id = $_POST['paciente_id'];
                $this->db->set('data_atualizacao', $data);
                $this->db->set('operador_atualizacao', $operador_id);
                $this->db->where('paciente_id', $paciente_id);
                $this->db->update('tb_paciente');
            }


            return $paciente_id;
        } catch (Exception $exc) {
            return false;
        }
    }

    function gravarpacientefidelidade($obj_paciente) {
        try {

            $this->db->select('');
            $this->db->from('tb_paciente');
            $this->db->where('cpf', str_replace("-", "", str_replace(".", "", $obj_paciente[0]->cpf)));
            $this->db->or_where('prontuario_antigo', $obj_paciente[0]->paciente_id);
            $verificar_cadastro = $this->db->get()->result();
            if (count($verificar_cadastro) >= 1) {
                return;
            }

            $this->db->set('nome', $obj_paciente[0]->nome);
            if ($obj_paciente[0]->cpf != '') {
                $this->db->set('cpf', str_replace("-", "", str_replace(".", "", $obj_paciente[0]->cpf)));
            }
            if ($obj_paciente[0]->nascimento != '') {
                $this->db->set('nascimento', date("Y-m-d", strtotime(str_replace("/", "-", $obj_paciente[0]->nascimento))));
            } else {
//                $this->db->set('nascimento', null);
            }
//            if ($_POST['data_emissao'] != '') {
//                $this->db->set('data_emissao', $_POST['data_emissao']);
//            }

            $this->db->set('cns', $obj_paciente[0]->cns);

            $this->db->set('rg', $obj_paciente[0]->rg);
//            $this->db->set('uf_rg', $_POST['uf_rg']);
//            $this->db->set('titulo_eleitor', $_POST['titulo_eleitor']);
            $this->db->set('sexo', $obj_paciente[0]->sexo);

            // if ($_POST['prontuario_antigo'] != '') {
            $this->db->set('prontuario_antigo', $obj_paciente[0]->paciente_id);
            // }
            $this->db->set('nome_pai', $obj_paciente[0]->nome_pai);
            $this->db->set('nome_mae', $obj_paciente[0]->nome_pai);
            $this->db->set('celular', str_replace("(", "", str_replace(")", "", str_replace("-", "", $obj_paciente[0]->celular))));
            $this->db->set('telefone', str_replace("(", "", str_replace(")", "", str_replace("-", "", $obj_paciente[0]->telefone))));
            $this->db->set('logradouro', $obj_paciente[0]->logradouro);
            $this->db->set('numero', $obj_paciente[0]->numero);
            $this->db->set('bairro', $obj_paciente[0]->bairro);
            $this->db->set('complemento', $obj_paciente[0]->complemento);
            if ($obj_paciente[0]->municipio_id != '') {
                $this->db->set('municipio_id', $obj_paciente[0]->municipio_id);
            }
            $this->db->set('cep', $obj_paciente[0]->cep);

            $horario = date("Y-m-d H:i:s");

            if ($obj_paciente[0]->data_cadastro != "") {
                $data = $obj_paciente[0]->data_cadastro;
            } else {
                $data = date("Y-m-d");
            }

            // $this->db->set('paciente_id',$_POST['txtPacienteId'] );
            $this->db->set('data_cadastro', $data);
            $this->db->insert('tb_paciente');
            $paciente_id = $this->db->insert_id();

            return $paciente_id;
        } catch (Exception $exc) {
            return false;
        }
    }

    function ajustarpaciente($paciente_id, $telefone) {

        try {
            if ($telefone != "") {
                $this->db->set('telefone', str_replace("(", "", str_replace(")", "", str_replace("-", "", $telefone))));
            }

            $data = date("Y-m-d");
            $operador_id = $this->session->userdata('operador_id');

            $this->db->set('data_atualizacao', $data);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('paciente_id', $paciente_id);
            $this->db->update('tb_paciente');



            return $paciente_id;
        } catch (Exception $exc) {
            return false;
        }
    }

    function gravarpacientetemp() {
        try {
            $this->db->set('nome', $_POST['txtNome']);
            if ($_POST['nascimento'] != '') {
                $this->db->set('nascimento', substr($_POST['nascimento'], 6, 4) . '-' . substr($_POST['nascimento'], 3, 2) . '-' . substr($_POST['nascimento'], 0, 2));
            }
            if ($_POST['idade'] != '') {
                $this->db->set('idade', $_POST['idade']);
            }
            $this->db->set('celular', str_replace("(", "", str_replace(")", "", str_replace("-", "", $_POST['celular']))));
            $this->db->set('telefone', str_replace("(", "", str_replace(")", "", str_replace("-", "", $_POST['telefone']))));
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');
            $dia = substr($horario, 8, 2);
            $mes = substr($horario, 5, 2);
            $ano = substr($horario, 0, 4);
            $dataatual = $dia . '/' . $mes . '/' . $ano;
            $this->db->set('data_cadastro', $dataatual);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_paciente');
            $paciente_id = $this->db->insert_id();
            return $paciente_id;
        } catch (Exception $exc) {
            return false;
        }
    }

    /**
     * Método criado para atender uma necessidade temporária
     * @author Vicente Armando
     * @return <type>
     */
    function gravarPaciente() {
        try {
            $this->db->set('nome', $_POST['nome_paciente']);
            if ($_POST['idade'] == '')
                $this->db->set('idade', null);
            else
                $this->db->set('idade', $_POST['idade']);
            $this->db->set('idade_tipo', $_POST['idadeTipo']);
            $this->db->set('sexo', $_POST['sexo_paciente']);
            $this->db->set('nome_mae', $_POST['mae_paciente']);
            $this->db->set('telefone', str_replace("(", "", str_replace(")", "", str_replace("-", "", $_POST['tel_paciente']))));
            $this->db->set('logradouro', $_POST['end_paciente']);
            $this->db->set('peso', $_POST['peso_paciente']);
            $this->db->set('cns', $_POST['sus_paciente']);
            $this->db->set('profissao', $_POST['profissao_paciente']);
            $this->db->set('bairro', $_POST['bairro_paciente']);
            if ($_POST['municipio_id_paciente'] != null)
                $this->db->set('municipio_id', $_POST['municipio_id_paciente']);
            else
                $this->db->set('municipio_id', null);
            $this->db->set('cep', $_POST['cep_paciente']);

            if ($_POST['id_paciente'] == "") {// insert
                $this->db->insert('tb_temp_paciente');
                $erro = $this->db->_error_message();
                if (trim($erro) != "") { // erro de banco
                    return false;
                } else
                    $temp_paciente_id = $this->db->insert_id();
            }
            else { // update
                $temp_paciente_id = $_POST['id_paciente'];
                $this->db->where('temp_paciente_id', $temp_paciente_id);
                $this->db->update('tb_temp_paciente');
            }
            return $temp_paciente_id;
        } catch (Exception $exc) {
            return false;
        }
    }

    function gravardemanda() {
        try {
            $this->db->set('demanda', $_POST['txtDemanda']);
            $this->db->set('diretoria', $_POST['txtDiretoria']);
            $this->db->set('ativo', 'true');
            $data = date("Ymd");
            $this->db->set('diretoria_data', $data);
            $this->db->insert('tb_censo_demanda_diretoria');
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

    function deletarclinicas($clinica) {
        $this->db->where('unidade', $clinica);
        $this->db->delete('tb_censo_clinicas');
    }

    function gravarcensoclinicas($args = array()) {
        try {
            $this->db->set('nome', $args['IB6NOME']);
            $this->db->set('municipio', $args['ID4DESCRICAO']);
            $this->db->set('unidade', $args['C14NOMEC']);
            $this->db->insert('tb_censo_clinicas');
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

    function atualizardemanda($demanda_id) {
        $this->db->set('ativo', 'false');
        $data = date("Ymd");
        $this->db->set('fechamento_data', $data);
        $this->db->where('censo_demanda_diretoria', $demanda_id);
        $this->db->update('tb_censo_demanda_diretoria');
        return true;
    }

    function gravarpacientecenso() {
        try {
            $verificador = $this->instanciarpacientecenso($_POST['txtProntuario']);
            $this->db->set('prontuario', $_POST['txtProntuario']);
            $this->db->set('nome', $_POST['txtNome']);
            $this->db->set('procedimento', $_POST['txtProcedimento']);
            $this->db->set('descricao_resumida', $_POST['txtDescricaoResumida']);
            $this->db->set('status', $_POST['txtStatus']);
            $this->db->set('unidade', $_POST['txtunidade']);
            if ($_POST['txtvalida'] == 1) {
                $this->db->set('diretoria', $_POST['txtDiretoria']);
                $this->db->set('ativo', 'true');
            }
            $data = date("Ymd");
            $this->db->set('diretoria_data', $data);


            if ($verificador == null) {// insert
                $this->db->insert('tb_censo_paciente');
                $erro = $this->db->_error_message();
                if (trim($erro) != "") { // erro de banco
                    return false;
                } else
                    return true;
            }
            else { // update
                $this->db->where('prontuario', $_POST['txtProntuario']);
                $this->db->update('tb_censo_paciente');
                return true;
            }
            return true;
        } catch (Exception $exc) {
            return false;
        }
    }

    function consultarprocedimento($args = array()) {
        $this->db->from('tb_procedimento')
                ->select('"tb_procedimento".*');
        if ($args) {
            if (isset($args['procedimento']) && strlen($args['procedimento']) > 0) {
                $this->db->where('tb_procedimento.procedimento', $args['procedimento']);
            }
        }
        return $this->db;
    }

    function consultarpacientecenso($args = array()) {
        $this->db->from('tb_censo_paciente')
                ->select('"tb_censo_paciente".*');
        if ($args) {
            if (isset($args['prontuario']) && strlen($args['prontuario']) > 0) {
                $this->db->where('tb_censo_paciente.prontuario', $args['prontuario']);
            }
        }
        return $this->db;
    }

    function instanciarpacientecenso($prontuario) {

        $this->db->from('tb_censo_paciente')
                ->select('"tb_censo_paciente".*');
        $this->db->where('tb_censo_paciente.prontuario', $prontuario);
        // $this->db->where('tb_censo_paciente.nome', $prontuario);
        $retorno = $this->db->get();
        $testes = $retorno->row_array();
        return $testes;
    }

    function listarbairros() {
        $this->db->select('bairro');
        $this->db->from('tb_paciente');
        $this->db->where('ativo', 't');
        $this->db->groupby('bairro');
        $retorno = $this->db->get()->result();
        return $retorno;
    }

    function listarpacienteporclinicas($clinica) {
        $clinica = trim($clinica);
        $this->db->from('tb_censo_paciente')
                ->select('"tb_censo_paciente".*');
        $this->db->where('unidade', $clinica);
        $this->db->where('tb_censo_paciente.ativo', 'true');
        $retorno = $this->db->get()->result();
        return $retorno;
    }

    function listarpacienterisco1() {
        $this->db->from('tb_censo_clinicas')
                ->select();
        $this->db->where('unidade', 'EIXO AMARELO - RISCO I   ');
        $return = $this->db->count_all_results();
        return $return;
    }

    function listarpacienterisco2() {
        $this->db->from('tb_censo_clinicas')
                ->select();
        $this->db->where('unidade', 'EIXO VERDE- RISCO 2      ');
        $return = $this->db->count_all_results();
        return $return;
    }

    function listarpacientecorredor() {
        $this->db->from('tb_censo_clinicas')
                ->select();
        $this->db->where('unidade ilike', 'EIXO AZUL%');
        $return = $this->db->count_all_results();
        return $return;
    }

    function listarpacientemunicipio() {
        $sql = "SELECT count(*)
              FROM ijf.tb_censo_clinicas
            where municipio = 'FORTALEZA                               '
            and (unidade ilike '%EIXO AZUL%'
            or unidade = 'EIXO AMARELO - RISCO I   '
            or unidade = 'EIXO VERDE- RISCO 2      ')
            ";
        $return = $this->db->query($sql)->result();
        return $return;
    }

    function atualizarpacienteporclinicas($prontuario) {
        $this->db->set('ativo', 'false');
        $this->db->where('prontuario', $prontuario);
        $this->db->update('tb_censo_paciente');
    }

    function instanciarprocedimento($procedimento) {
        $this->db->from('tb_procedimento')
                ->select('"tb_procedimento".*');
        $this->db->where('tb_procedimento.procedimento', $procedimento);
        $retorno = $this->db->get();
        $testes = $retorno->row_array();
        return $testes;
    }

    function listarCBO() {

        $this->db->select('cbo_grupo_id, descricao');
        $this->db->from('tb_cbo');
        $return = $this->db->get();
        return $return->result();
    }

    function listarProcedimentos() {
        $this->db->select();
        $this->db->from('tb_procedimento');
        $return = $this->db->get();
        return $return->result();
    }

    function listarProcedimentosPontos($competencia) {
        $this->db->select();
        $this->db->from('tb_emergencia_procedimento_sia_pontos');
        $this->db->where('competencia', $competencia);
        $return = $this->db->get();
        return $return->result();
    }

    function listarpacientecenso() {
        $this->db->select();
        $this->db->from('tb_censo_paciente');
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriopacientecenso($operador) {
        $this->db->select('p.descricao_resumida, p.procedimento, p.nome, d.descricao,
                p.prontuario, p.status, p.diretoria, p.diretoria_data, p.unidade');
        $this->db->from('tb_censo_paciente p');
        $this->db->where('p.diretoria', $operador);
        $this->db->where('p.ativo', 'true');
        $this->db->join('tb_censo_diretoria d', 'p.diretoria = d.codigo', 'left');
        $this->db->orderby('p.diretoria_data', 'asc');
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriopacientecensosuper() {
        $this->db->select('p.descricao_resumida, p.procedimento, p.nome, d.descricao,
                p.prontuario, p.status, p.diretoria, p.diretoria_data, p.unidade');
        $this->db->from('tb_censo_paciente p');
        $this->db->where('p.ativo', 'true');
        $this->db->join('tb_censo_diretoria d', 'p.diretoria = d.codigo', 'left');
        $this->db->orderby('p.diretoria_data', 'asc');
        $return = $this->db->get();
        return $return->result();
    }

    function relatoriobecircunstanciado($id) {
        $this->db->select();
        $this->db->from('tb_emergencia_relatoriocircuntanciado');
        $this->db->where('relatoriocircuntanciado_id', $id);
        $return = $this->db->get();
        return $return->result();
    }

    function listarrelatoriobecircunstanciado($args = array()) {
        $this->db->from('tb_emergencia_relatoriocircuntanciado er')
                ->select();
        if ($args) {
            if (isset($args['nome']) && strlen($args['nome']) > 0) {
                $this->db->where('er.nome ilike', $args['nome'] . "%", 'left');
                $this->db->orwhere('er.be ilike', $args['nome'], 'left');
                $this->db->orwhere('er.diretoria ilike', $args['nome'] . "%", 'left');
            }
        }
        return $this->db;
    }

    function gravarcircunstanciado() {
        try {
            $this->db->set('be', trim($_POST['txtbe']));
            $this->db->set('nome', $_POST['txtnome']);
            $this->db->set('data_nascimento', $_POST['txtnascimento']);
            $this->db->set('nome_mae', $_POST['txtmae']);
            $this->db->set('endereco', $_POST['txtendereco']);
            $this->db->set('ao', $_POST['txtAO']);
            $this->db->set('caro', $_POST['txtCARO']);
            $this->db->set('solicitante', $_POST['txtsolicitante']);
            $this->db->set('diretoria', $_POST['txtdiretoria']);
            $this->db->set('numero', $_POST['txtcpf']);
            $data = date("Ymd");
            $this->db->set('data', $data);


            $this->db->insert('tb_emergencia_relatoriocircuntanciado');
            $id = $this->db->insert_id();
            $erro = $this->db->_error_message();
            if (trim($erro) != "") { // erro de banco
                return false;
            } else
                return $id;
            return $id;
        } catch (Exception $exc) {
            return false;
        }
    }

    function relatoriodemandadiretoria() {
        $this->db->select('dd.demanda, d.descricao, dd.censo_demanda_diretoria');
        $this->db->from('tb_censo_demanda_diretoria dd');
        $this->db->where('dd.ativo', 'true');
        $this->db->join('tb_censo_diretoria d', 'dd.diretoria = d.codigo', 'left');
        $this->db->orderby('censo_demanda_diretoria', 'asc');
        $return = $this->db->get();
        return $return->result();
    }

    function atualizaProcedimentos() {
        $procedimento = $_POST['txtProcedimento'];
        $txtDescricaoResumida = $_POST['txtDescricaoResumida'];
        $sql = "update ijf.tb_procedimento set descricao_resumida = '$txtDescricaoResumida' where procedimento = $procedimento";
        $this->db->query($sql);
    }

    function conection() {
        try {

            $Obj = new SoapClient(null, array(
                'location' => 'http://172.30.40.252/webservice/hospub',
                'uri' => 'http://172.30.40.252/webservice/hospub',
                'trace' => 1,
                'exceptions' => 0));


            $aParam['be'] = $_POST['txtbe'];
            $pront = ($Obj->impressaobe($aParam));
//                $aParam['be']= $_POST['txtbe'];
//                $pront = ($Obj->testeijf($aParam));
//                echo "<pre>";
//                var_dump($pront);
//                echo "</pre>";
//                die;
            $this->db->select();
            $this->db->from('tb_emergencia_behospub');
            $this->db->where('be', $pront['N54NUMBOLET']);
            $return = $this->db->count_all_results();
            //var_dump($return);


            $args['nome'] = $pront['C54IDENTIFIC'];
            $args['be'] = $pront['N54NUMBOLET'];
            $args['nome_mae'] = $pront['C54MAE'];
            $data = substr($pront['D54NASC'], 0, 8);
            if ($data == "        ") {
                $pront['D54NASC'] = null;
            }
            $args['data_nascimento'] = $pront['D54NASC'];
            $args['sexo'] = $pront['C54SEXO'];
            $args['responsavel'] = $pront['C54RESP'];
            $args['endereco'] = $pront['C54END'] . " " . $pront['C54NUMERO'];
            $args['fone'] = $pront['C54TEL'];
            $args['municipio'] = $pront['ID4DESCRICAO'];
            $args['codigo_ibge'] = $pront['C54MUN'];
            $args['estado'] = $pront['C54UF'];
            $args['idade'] = $pront['I54IDADE'];
            $args['bairro'] = $pront['C54BAIRRO'];
            $args['data_abertura'] = $pront['D54INTER'];
            $args['hora_abertura'] = $pront['C54HINTER'];
            $args['documento'] = $pront['C54CPFPAC'];
            $args['caso_policial'] = $pront['C54CASOPOL'];
            $args['trauma'] = $pront['C54TRAUMA'];
            $args['acidente_trabalho'] = $pront['C54ACIDTRAB'];
            $args['ambulancia'] = $pront['C54AMBULAN'];
            $args['motivo_atendimento'] = $pront['C54MOTATEND'];
            $args['motivo_atendimento_descricao'] = $pront['C56DESCRICAO'];
            $args['complemento'] = $pront['C54COMPLEM'];
            $data = substr($pront['D54ALTA'], 0, 8);
            if ($data == "        ") {
                $pront['D54ALTA'] = null;
            }
            $args['data_alta'] = $pront['D54ALTA'];
            $args['hora_alta'] = $pront['C54HALTA'];
            $args['cpf_medico_alta'] = $pront['C54CPFALTA'];
            $args['cid'] = $pront['C54CID10'];
            $args['setor_entrada'] = $pront['C54SETOR_ENT'];
            $args['setor_alta'] = $pront['C54SETOR_SAI'];
            $args['motivo_alta'] = $pront['C54MOTIVO'];
            $args['tipo_motivo_alta'] = $pront['C54TIPALTA'];


            if ($return == '0') {
                $this->db->insert('tb_emergencia_behospub', $args);
                $id = $this->db->insert_id();
                $args['behospub_id'] = $id;

                return $args;
            } elseif ($return == '1') {
                $this->db->where('be', $pront['N54NUMBOLET']);
                $this->db->update('tb_emergencia_behospub', $args);

                $this->db->select();
                $this->db->from('tb_emergencia_behospub');
                $this->db->where('be', $pront['N54NUMBOLET']);
                $retorno = $this->db->get();
                $teste = $retorno->row_array();

                return $teste;
            }
//                $erro = $this->db->_error_message();
//                if (trim($erro) != "") { // erro de banco
//                    var_dump($erro);
//                }
            //var_dump($pront);
            //$nume = $pront["N54NUMBOLET"];
            //var_dump($nume);
            // echo "_______________________________________";
            //$string_xml = $Obj->getPaciente('000080', $opBuscaBe);
            //$xml = new ConvertXml();
            //$aResult = ($Obj->getPaciente('000080', $opBuscaBe));
            //$aResult = ($Obj->getPaciente('000080', $opBuscaBe));
            //$aResult = ($Obj->getDadosPaciente('80',$opBuscaBe));
            //$aResult = ($Obj->getPacienteInternado('99990084'));
            //$aResult = $xml->xml2array($Obj->getPaciente('000080', $opBuscaBe));
            //$aResult = $xml->$Obj->getPaciente('000080', $opBuscaBe);
            //print_r ($Obj->getPaciente('80',$opBuscaBe));
            //print_r ($Obj->getBpaiConteudo('000080'));
            //print_r ($Obj->getBEInfo('000080'));
            //print_r ($Obj->getProntuarioBe('000080'));
            //var_dump($aResult);
        } catch (Exception $e) {
            print $e->getMessage();
        }
    }

    function conectionctq() {
        try {

            $Obj = new SoapClient(null, array(
                'location' => 'http://172.30.40.252/webservice/hospub',
                'uri' => 'http://172.30.40.252/webservice/hospub',
                'trace' => 1,
                'exceptions' => 0));


            $aParam['be'] = $_POST['txtbe'];
            $pront = ($Obj->impressaobe($aParam));
//            $aParam['be'] = $_POST['txtbe'];
//            $pront = ($Obj->testeijf($aParam));
//            echo "<pre>";
//            var_dump($pront);
//            echo "</pre>";
//            die;
            $this->db->select();
            $this->db->from('tb_emergencia_behospub');
            $this->db->where('be', $pront['N54NUMBOLET']);
            $return = $this->db->count_all_results();
            //var_dump($return);


            $args['nome'] = $pront['C54IDENTIFIC'];
            $args['be'] = $pront['N54NUMBOLET'];
            $args['nome_mae'] = $pront['C54MAE'];
            $data = substr($pront['D54NASC'], 0, 8);
            if ($data == "        ") {
                $pront['D54NASC'] = null;
            }
            $args['data_nascimento'] = $pront['D54NASC'];
            $args['sexo'] = $pront['C54SEXO'];
            $args['responsavel'] = $pront['C54RESP'];
            $args['endereco'] = $pront['C54END'] . " " . $pront['C54NUMERO'];
            $args['fone'] = $pront['C54TEL'];
            $args['municipio'] = $pront['ID4DESCRICAO'];
            $args['codigo_ibge'] = $pront['C54MUN'];
            $args['estado'] = $pront['C54UF'];
            $args['idade'] = $pront['I54IDADE'];
            $args['bairro'] = $pront['C54BAIRRO'];
            $args['data_abertura'] = $pront['D54INTER'];
            $args['hora_abertura'] = $pront['C54HINTER'];
            $args['documento'] = $pront['C54CPFPAC'];
            $args['caso_policial'] = $pront['C54CASOPOL'];
            $args['trauma'] = $pront['C54TRAUMA'];
            $args['acidente_trabalho'] = $pront['C54ACIDTRAB'];
            $args['ambulancia'] = $pront['C54AMBULAN'];
            $args['motivo_atendimento'] = $pront['C54MOTATEND'];
            $args['motivo_atendimento_descricao'] = $pront['C56DESCRICAO'];
            $args['complemento'] = $pront['C54COMPLEM'];
            $data = substr($pront['D54ALTA'], 0, 8);
            if ($data == "        ") {
                $pront['D54ALTA'] = null;
            }
            $args['data_alta'] = $pront['D54ALTA'];
            $args['hora_alta'] = $pront['C54HALTA'];
            $args['cpf_medico_alta'] = $pront['C54CPFALTA'];
            $args['cid'] = $pront['C54CID10'];
            $args['setor_entrada'] = $pront['C54SETOR_ENT'];
            $args['setor_alta'] = $pront['C54SETOR_SAI'];
            $args['motivo_alta'] = $pront['C54MOTIVO'];
            $args['tipo_motivo_alta'] = $pront['C54TIPALTA'];


            if ($return == '0') {
                $this->db->insert('tb_emergencia_behospub', $args);
                $id = $this->db->insert_id();
                $args['behospub_id'] = $id;

                return $args;
            } elseif ($return == '1') {
                $this->db->where('be', $pront['N54NUMBOLET']);
                $this->db->update('tb_emergencia_behospub', $args);

                $this->db->select();
                $this->db->from('tb_emergencia_behospub');
                $this->db->where('be', $pront['N54NUMBOLET']);
                $retorno = $this->db->get();
                $teste = $retorno->row_array();

                return $teste;
            }
//                $erro = $this->db->_error_message();
//                if (trim($erro) != "") { // erro de banco
//                    var_dump($erro);
//                }
            //var_dump($pront);
            //$nume = $pront["N54NUMBOLET"];
            //var_dump($nume);
            // echo "_______________________________________";
            //$string_xml = $Obj->getPaciente('000080', $opBuscaBe);
            //$xml = new ConvertXml();
            //$aResult = ($Obj->getPaciente('000080', $opBuscaBe));
            //$aResult = ($Obj->getPaciente('000080', $opBuscaBe));
            //$aResult = ($Obj->getDadosPaciente('80',$opBuscaBe));
            //$aResult = ($Obj->getPacienteInternado('99990084'));
            //$aResult = $xml->xml2array($Obj->getPaciente('000080', $opBuscaBe));
            //$aResult = $xml->$Obj->getPaciente('000080', $opBuscaBe);
            //print_r ($Obj->getPaciente('80',$opBuscaBe));
            //print_r ($Obj->getBpaiConteudo('000080'));
            //print_r ($Obj->getBEInfo('000080'));
            //print_r ($Obj->getProntuarioBe('000080'));
            //var_dump($aResult);
        } catch (Exception $e) {
            print $e->getMessage();
        }
    }

    function listarclinicashospub() {
        try {

            $Obj = new SoapClient(null, array(
                'location' => 'http://172.30.40.252/webservice/hospub',
                'uri' => 'http://172.30.40.252/webservice/hospub',
                'trace' => 1,
                'exceptions' => 0));


            $aParam['clinica'] = 0;
            $pront = ($Obj->listarclinicas($aParam));
//                $aParam['be']= $_POST['txtclinica'];
//                $pront = ($Obj->testeijf($aParam));
//                echo "<pre>";
//                var_dump($pront);
//                echo "</pre>";
//                $aParam['clinica']= '028';
//                $pront = ($Obj->censohospubdiretoria($aParam));
//                echo "<pre>";
//                var_dump($pront);
//                echo "</pre>";
//                die;

            return $pront;
        } catch (Exception $e) {
            print $e->getMessage();
        }
    }

    function listarleitoshospub($clinica) {
        try {

            $Obj = new SoapClient(null, array(
                'location' => 'http://172.30.40.252/webservice/hospub',
                'uri' => 'http://172.30.40.252/webservice/hospub',
                'trace' => 1,
                'exceptions' => 0));


            $aParam['clinica'] = $clinica;
            $pront = ($Obj->listarleitos($aParam));
//                $aParam['be']= $_POST['txtclinica'];
//                $pront = ($Obj->testeijf($aParam));
//                echo "<pre>";
//                var_dump($pront);
//                echo "</pre>";
//                die;
//                $aParam['clinica']= 0;
//                $pront = ($Obj->listarclinicas($aParam));
//                echo "<pre>";
//                var_dump($pront);
//                echo "</pre>";
//                die;

            return $pront;
        } catch (Exception $e) {
            print $e->getMessage();
        }
    }

    function censohospub($clinica) {
        try {

            $Obj = new SoapClient(null, array(
                'location' => 'http://172.30.40.252/webservice/hospub',
                'uri' => 'http://172.30.40.252/webservice/hospub',
                'trace' => 1,
                'exceptions' => 0));


            $aParam['clinica'] = $clinica;
            $pront = ($Obj->censohospub($aParam));
//                $aParam['be']= $_POST['txtclinica'];
//                $pront = ($Obj->testeijf($aParam));
//                echo "<pre>";
//                var_dump($pront);
//                echo "</pre>";
//                die;
//                $aParam['clinica']= $_POST['txtclinica'];
//                $pront = ($Obj->censohospub($aParam));
//                echo "<pre>";
//                var_dump($pront);
//                echo "</pre>";
//                die;


            return $pront;
        } catch (Exception $e) {
            print $e->getMessage();
        }
    }

    function faturamentohospub() {
        try {

            $Obj = new SoapClient(null, array(
                'location' => 'http://172.30.40.252/webservice/hospub',
                'uri' => 'http://172.30.40.252/webservice/hospub',
                'trace' => 1,
                'exceptions' => 0));

            $aParam['PRONTUARIO'] = $_POST['txtprontuario'];
            $pront = ($Obj->faturamentohospub($aParam));
//                $aParam['be']= $_POST['txtprontuario'];
//                $pront = ($Obj->testeijf($aParam));
//                var_dump($pront);
//                die;
            $this->db->select();
            $this->db->from('tb_emergencia_internacaohospub');
            $this->db->where('prontuario', $pront['IB6REGIST']);
            $return = $this->db->count_all_results();
            //var_dump($return);


            $args['prontuario'] = $pront['IB6REGIST'];
            $args['codigo_ibge'] = $pront['IB6MUNICIP'];
            $args['data_abertura'] = $pront['D15INTER'];
            $data = substr($pront['D15ALTA'], 0, 8);
            if ($data == "        ") {
                $pront['D15ALTA'] = null;
            }
            $args['data_fechamento'] = $pront['D15ALTA'];
            $args['endereco'] = $pront['IB6LOGRAD'];
            $data = substr($pront['IB6DTNASC'], 0, 8);
            if ($data == "        ") {
                $pront['IB6DTNASC'] = null;
            }
            $args['data_nascimento'] = $pront['IB6DTNASC'];
            $args['nome'] = $pront['IB6NOME'];
            $args['municipio'] = $pront['ID4DESCRICAO'];
            $args['fone'] = $pront['IB6TELEF'];
            $args['sexo'] = $pront['IB6SEXO'];
            $args['nome_pai'] = $pront['IB6PAI'];
            $args['estado'] = $pront['IB6UF'];
            $args['nome_mae'] = $pront['IB6MAE'];
            $args['bairro'] = $pront['IB6BAIRRO'];
            $args['aih'] = $pront['N15AIH'];
            $args['nacionalidade'] = $pront['IC6DESCR'];
            $args['documento'] = $pront['IB6CPFPAC'];
            $args['clinica'] = $pront['C15CODCLIN'];
            $args['leito'] = $pront['C15CODLEITO'];
            $args['naturalidade'] = $pront['IB6NATURAL'];
            $args['be'] = $pront['N15NUMBOLET'];
            $args['cep'] = $pront['IB6CEP'];
            if ($return == '0') {
                $this->db->insert('tb_emergencia_internacaohospub', $args);

                return $args;
            } elseif ($return == '1') {
                $this->db->where('prontuario', $pront['IB6REGIST']);
                $this->db->update('tb_emergencia_internacaohospub', $args);

                return $args;
            }
        } catch (Exception $e) {
            print $e->getMessage();
        }
    }

    function faturamentohospubinternado() {
        try {

            $Obj = new SoapClient(null, array(
                'location' => 'http://172.30.40.252/webservice/hospub',
                'uri' => 'http://172.30.40.252/webservice/hospub',
                'trace' => 1,
                'exceptions' => 0));

            $aParam['PRONTUARIO'] = $_POST['txtprontuario'];
            $pront = ($Obj->faturamentohospubinternado($aParam));
//                $aParam['be']= $_POST['txtprontuario'];
//                $pront = ($Obj->testeijf($aParam));
//                var_dump($pront);
//                die;
            $this->db->select();
            $this->db->from('tb_emergencia_internacaohospub');
            $this->db->where('prontuario', $pront['IB6REGIST']);
            $return = $this->db->count_all_results();
            //var_dump($return);


            $args['prontuario'] = $pront['IB6REGIST'];
            $args['codigo_ibge'] = $pront['IB6MUNICIP'];
            $args['data_abertura'] = $pront['D02INTER'];
            $args['data_fechamento'] = null;
            $args['endereco'] = $pront['IB6LOGRAD'];
            $data = substr($pront['IB6DTNASC'], 0, 8);
            if ($data == "        ") {
                $pront['IB6DTNASC'] = null;
            }
            $args['data_nascimento'] = $pront['IB6DTNASC'];
            $args['nome'] = $pront['IB6NOME'];
            $args['municipio'] = $pront['ID4DESCRICAO'];
            $args['fone'] = $pront['IB6TELEF'];
            $args['sexo'] = $pront['IB6SEXO'];
            $args['nome_pai'] = $pront['IB6PAI'];
            $args['estado'] = $pront['IB6UF'];
            $args['nome_mae'] = $pront['IB6MAE'];
            $args['bairro'] = $pront['IB6BAIRRO'];
            $args['aih'] = $pront['N02AIH'];
            $args['nacionalidade'] = $pront['IC6DESCR'];
            $args['documento'] = $pront['IB6CPFPAC'];
            $args['clinica'] = $pront['C02CODCLIN'];
            $args['leito'] = $pront['C02CODLEITO'];
            $args['naturalidade'] = $pront['IB6NATURAL'];
            $args['be'] = $pront['N02NUMBOLET'];
            $args['cep'] = $pront['IB6CEP'];
            if ($return == '0') {
                $this->db->insert('tb_emergencia_internacaohospub', $args);

                return $args;
            } elseif ($return == '1') {
                $this->db->where('prontuario', $pront['IB6REGIST']);
                $this->db->update('tb_emergencia_internacaohospub', $args);

                return $args;
            }
        } catch (Exception $e) {
            print $e->getMessage();
        }
    }

    function samehospubimpressao($registro, $datainternacao) {
        try {

            $Obj = new SoapClient(null, array(
                'location' => 'http://172.30.40.252/webservice/hospub',
                'uri' => 'http://172.30.40.252/webservice/hospub',
                'trace' => 1,
                'exceptions' => 0));

            $aParam['PRONTUARIO'] = $registro;
            $aParam['DATAINTERNACAO'] = $datainternacao;
            $pront = ($Obj->samehospub($aParam));



            $this->db->select();
            $this->db->from('tb_emergencia_samehospub');
            $this->db->where('prontuario', $pront['IB6REGIST']);
            $this->db->where('data_internacao', $datainternacao);
            $return = $this->db->count_all_results();
            $operador_id = ($this->session->userdata('operador_id'));
            if ($return == '0') {
//                  $Obj =  new SoapClient(null,array(
//                  'location' => 'http://172.30.40.252/webservice/hospub',
//                  'uri' => 'http://172.30.40.252/webservice/hospub',
//                  'trace' =>  1,
//                  'exceptions' => 0));
//
//                $aParam['PRONTUARIO']= $registro;
//                $pront = ($Obj->samehospub($aParam));
                //var_dump($pront);
//                $aParam['be']= $_POST['txtprontuario'];
//                $pront = ($Obj->testeijf($aParam));
//                var_dump($pront);
//                die;



                $args['prontuario'] = $pront['IB6REGIST'];
                $data = substr($pront['IB6DTNASC'], 0, 8);
                if ($data == "        ") {
                    $pront['IB6DTNASC'] = null;
                }
                $args['data_nascimento'] = substr($pront['IB6DTNASC'], 0, 4) . "-" . substr($pront['IB6DTNASC'], 4, 2) . "-" . substr($pront['IB6DTNASC'], 6, 2);
                $args['nome'] = $pront['IB6NOME'];
                $args['sexo'] = $pront['IB6SEXO'];
                $args['nome_mae'] = $pront['IB6MAE'];
                $args['nome_pai'] = $pront['IB6PAI'];
                $args['data_internacao'] = $pront['D15INTER'];
                $data = substr($pront['D15ALTA'], 0, 8);
                if ($data == "        ") {
                    $pront['D15ALTA'] = null;
                }
                $args['data_alta'] = $pront['D15ALTA'];
                $args['be'] = $pront['N15NUMBOLET'];
                $args['descricao'] = $_POST['txtDescricao'];
                $args['hora_internacao'] = $pront['C15HINTER'];
                $args['hora_alta'] = $pront['C15HALTA'];
                $args['motivo'] = $pront['C15MOTIVO'];
                $args['motivoestado'] = $pront['C15ESTADO'];
                $args['crm'] = $pront['IC0ICR'];
                $args['medico'] = $pront['IC0NOME'];
                $data = substr($pront['D54INTER'], 0, 8);
                if (($data == "        ") || ($data == " 9999999")) {
                    $pront['D54INTER'] = null;
                }
                $args['data_abertura'] = $pront['D54INTER'];
                $args['hora_abertura'] = $pront['C54HINTER'];
                $args['operador_id'] = $operador_id;
                $inserir = $args;
                $inserir['data_nascimento'] = $pront['IB6DTNASC'];
                $this->db->insert('tb_emergencia_samehospub', $inserir);


                return $args;
            } elseif ($return == '1') {
                $this->db->select();
                $this->db->from('tb_emergencia_samehospub');
                $this->db->where('prontuario', $pront['IB6REGIST']);
                $this->db->where('data_internacao', $datainternacao);
                $retorno = $this->db->get();
                $teste = $retorno->row_array();
                $teste['operador_id'] = $operador_id;
                $teste['descricao'] = $_POST['txtDescricao'];
                $this->db->where('prontuario', $pront['IB6REGIST']);
                $this->db->where('data_internacao', $datainternacao);
                $this->db->update('tb_emergencia_samehospub', $teste);
                return $teste;
            }
        } catch (Exception $e) {
            print $e->getMessage();
        }
    }

    function samehospub($registro, $datainternacao) {
        try {

            $Obj = new SoapClient(null, array(
                'location' => 'http://172.30.40.252/webservice/hospub',
                'uri' => 'http://172.30.40.252/webservice/hospub',
                'trace' => 1,
                'exceptions' => 0));


            $aParam['PRONTUARIO'] = $registro;
            $aParam['DATAINTERNACAO'] = $datainternacao;
            $pront = ($Obj->samehospub($aParam));
//                $aParam['be']= $_POST['txtprontuario'];
//                $pront = ($Obj->testeijf($aParam));
//                echo "<pre>";
//                var_dump($pront);
//                echo "</pre>";
//                die;
            $this->db->select();
            $this->db->from('tb_emergencia_samehospub');
            $this->db->where('prontuario', $pront['IB6REGIST']);
            $this->db->where('data_internacao', $datainternacao);
            $return = $this->db->count_all_results();




            $args['prontuario'] = $pront['IB6REGIST'];
            $data = substr($pront['IB6DTNASC'], 0, 8);
            if ($data == "        ") {
                $pront['IB6DTNASC'] = null;
            }
            $args['data_nascimento'] = substr($pront['IB6DTNASC'], 0, 4) . "-" . substr($pront['IB6DTNASC'], 4, 2) . "-" . substr($pront['IB6DTNASC'], 6, 2);
            $args['nome'] = $pront['IB6NOME'];
            $args['sexo'] = $pront['IB6SEXO'];
            $args['nome_mae'] = $pront['IB6MAE'];
            $args['nome_pai'] = $pront['IB6PAI'];
            $args['data_internacao'] = $pront['D15INTER'];
            $data = substr($pront['D15ALTA'], 0, 8);
            if ($data == "        ") {
                $pront['D15ALTA'] = null;
            }
            $args['data_alta'] = $pront['D15ALTA'];
            $args['be'] = $pront['N15NUMBOLET'];
            $args['hora_internacao'] = $pront['C15HINTER'];
            $args['hora_alta'] = $pront['C15HALTA'];
            $args['motivo'] = $pront['C15MOTIVO'];
            $args['motivoestado'] = $pront['C15ESTADO'];
            $args['crm'] = $pront['IC0ICR'];
            $args['medico'] = $pront['IC0NOME'];
            $args['data_abertura'] = $pront['D54INTER'];
            $args['hora_abertura'] = $pront['C54HINTER'];
            $args['descricao'] = null;

            if ($return == '1') {
                $this->db->select();
                $this->db->from('tb_emergencia_samehospub');
                $this->db->where('prontuario', $pront['IB6REGIST']);
                $this->db->where('data_internacao', $datainternacao);
                $retorno = $this->db->get();
                $temp = $retorno->row_array();
                $args['descricao'] = $temp['descricao'];
            }

            return $args;
        } catch (Exception $e) {
            print $e->getMessage();
        }
    }

    function atualizar($be) {
        try {

            $Obj = new SoapClient(null, array(
                'location' => 'http://172.30.40.252/webservice/hospub',
                'uri' => 'http://172.30.40.252/webservice/hospub',
                'trace' => 1,
                'exceptions' => 0));


            $aParam['be'] = $be;
            $pront = ($Obj->testeijf($aParam));

            $args['nome'] = $pront['C54IDENTIFIC'];
            $args['be'] = $pront['N54NUMBOLET'];
            $args['nome_mae'] = $pront['C54MAE'];
            $data = substr($pront['D54NASC'], 0, 8);
            if ($data == "        ") {
                $pront['D54NASC'] = null;
            }
            $args['data_nascimento'] = $pront['D54NASC'];
            $args['sexo'] = $pront['C54SEXO'];
            $args['responsavel'] = $pront['C54RESP'];
            $args['endereco'] = $pront['C54END'] . " " . $pront['C54NUMERO'];
            $args['fone'] = $pront['C54TEL'];
            $args['municipio'] = $pront['ID4DESCRICAO'];
            $args['codigo_ibge'] = $pront['C54MUN'];
            $args['estado'] = $pront['C54UF'];
            $args['idade'] = $pront['I54IDADE'];
            $args['bairro'] = $pront['C54BAIRRO'];
            $args['data_abertura'] = $pront['D54INTER'];
            $args['hora_abertura'] = $pront['C54HINTER'];
            $args['documento'] = $pront['C54CPFPAC'];
            $args['caso_policial'] = $pront['C54CASOPOL'];
            $args['trauma'] = $pront['C54TRAUMA'];
            $args['acidente_trabalho'] = $pront['C54ACIDTRAB'];
            $args['ambulancia'] = $pront['C54AMBULAN'];
            $args['motivo_atendimento'] = $pront['C54MOTATEND'];
            $args['motivo_atendimento_descricao'] = $pront['C56DESCRICAO'];
            $args['complemento'] = $pront['C54COMPLEM'];
            $data = substr($pront['D54ALTA'], 0, 8);
            if ($data == "        ") {
                $pront['D54ALTA'] = null;
            }
            $args['data_alta'] = $pront['D54ALTA'];
            $args['hora_alta'] = $pront['C54HALTA'];
            $args['cpf_medico_alta'] = $pront['C54CPFALTA'];
            $args['cid'] = $pront['C54CID10'];
            $args['setor_entrada'] = $pront['C54SETOR_ENT'];
            $args['setor_alta'] = $pront['C54SETOR_SAI'];



            $this->db->where('be', $pront['N54NUMBOLET']);
            $this->db->update('tb_emergencia_behospub', $args);
        } catch (Exception $e) {
            print $e->getMessage();
        }
    }

    function listaAtualizar() {

        $this->db->select();
        $this->db->from('tb_emergencia_behospub');
        return $this->db->get()->result();


        return $retorno;
    }

    function listarProcedimento($parametro = null) {
        $this->db->select('co_procedimento,
                            no_procedimento');
        if ($parametro != null) {
            $this->db->where('co_procedimento ilike', $parametro . "%");
            $this->db->orwhere('no_procedimento ilike', $parametro . "%");
        }
        $return = $this->db->get('tb_emergencia_procedimentos');
        return $return->result();
    }

    function apac() {
        try {

            $Obj = new SoapClient(null, array(
                'location' => 'http://172.30.40.252/webservice/hospub',
                'uri' => 'http://172.30.40.252/webservice/hospub',
                'trace' => 1,
                'exceptions' => 0));


            $aParam['be'] = $_POST['txtbe'];
            $pront = ($Obj->impressaobe($aParam));
//                $aParam['be']= $_POST['txtbe'];
//                $pront = ($Obj->testeijf($aParam));
//                var_dump($pront);
//                die;
            $this->db->select();
            $this->db->from('tb_emergencia_tomografiahospub');
            $this->db->where('be', $pront['N54NUMBOLET']);
            $return = $this->db->count_all_results();
            //var_dump($return);


            $args['nome'] = $pront['C54IDENTIFIC'];
            $args['be'] = $pront['N54NUMBOLET'];
            $args['nome_mae'] = $pront['C54MAE'];
            $data = substr($pront['D54NASC'], 0, 8);
            if ($data == "        ") {
                $pront['D54NASC'] = null;
            }
            $args['data_nascimento'] = $pront['D54NASC'];
            $args['sexo'] = $pront['C54SEXO'];
            $args['responsavel'] = $pront['C54RESP'];
            $args['endereco'] = $pront['C54END'] . " " . $pront['C54NUMERO'];
            $args['fone'] = $pront['C54TEL'];
            $args['municipio'] = $pront['ID4DESCRICAO'];
            $args['codigo_ibge'] = $pront['C54MUN'];
            $args['estado'] = $pront['C54UF'];
            $args['idade'] = $pront['I54IDADE'];
            $args['bairro'] = $pront['C54BAIRRO'];
            $args['data_abertura'] = $pront['D54INTER'];
            $args['hora_abertura'] = $pront['C54HINTER'];
            $args['documento'] = $pront['C54CPFPAC'];
            $args['motivo_atendimento'] = $pront['C54MOTATEND'];
            $args['motivo_atendimento_descricao'] = $pront['C56DESCRICAO'];
            $args['complemento'] = $pront['C54COMPLEM'];
            $args['co_procedimento'] = $_POST['txtcodigo'];
            $args['no_procedimento'] = $_POST['txtdescricao'];



            if ($return == '0') {
                $this->db->insert('tb_emergencia_tomografiahospub', $args);

                return $args;
            } elseif ($return == '1') {
                $this->db->where('be', $pront['N54NUMBOLET']);
                $this->db->update('tb_emergencia_tomografiahospub', $args);

                return $args;
            }
//                $erro = $this->db->_error_message();
//                if (trim($erro) != "") { // erro de banco
//                    var_dump($erro);
//                }
            //var_dump($pront);
            //$nume = $pront["N54NUMBOLET"];
            //var_dump($nume);
            // echo "_______________________________________";
            //$string_xml = $Obj->getPaciente('000080', $opBuscaBe);
            //$xml = new ConvertXml();
            //$aResult = ($Obj->getPaciente('000080', $opBuscaBe));
            //$aResult = ($Obj->getPaciente('000080', $opBuscaBe));
            //$aResult = ($Obj->getDadosPaciente('80',$opBuscaBe));
            //$aResult = ($Obj->getPacienteInternado('99990084'));
            //$aResult = $xml->xml2array($Obj->getPaciente('000080', $opBuscaBe));
            //$aResult = $xml->$Obj->getPaciente('000080', $opBuscaBe);
            //print_r ($Obj->getPaciente('80',$opBuscaBe));
            //print_r ($Obj->getBpaiConteudo('000080'));
            //print_r ($Obj->getBEInfo('000080'));
            //print_r ($Obj->getProntuarioBe('000080'));
            //var_dump($aResult);
        } catch (Exception $e) {
            print $e->getMessage();
        }
    }

    function listarPacientes($parametro = null) {
        $this->db->select('nome,
                           be,
                           idade,
                           data_nascimento as nascimento');
        if ($parametro != null) {
            $this->db->where('nome ilike', "%" . $parametro . "%");
            $this->db->orwhere('be ilike', "%" . $parametro . "%");
        }
        $this->db->from('tb_emergencia_behospub');
        $return = $this->db->get();

        return $return->result();
    }

    function consultacpf() {
        try {

            $Obj = new SoapClient(null, array(
                'location' => 'http://172.30.40.252/webservice/hospub',
                'uri' => 'http://172.30.40.252/webservice/hospub',
                'trace' => 1,
                'exceptions' => 0));
            $aParam['be'] = 'S';
            $pront = ($Obj->consultacpfhospub($aParam));
//                echo "<pre>";
//                var_dump($pront);
//                echo "</pre>";
//                die;
            return $pront;
        } catch (Exception $e) {
            print $e->getMessage();
        }
    }

    function verificaproducaomedica($competencia) {


        $this->db->select();
        $this->db->from('tb_emergencia_faturamentomedico');
        $this->db->where('competencia', "$competencia");
        $return = $this->db->get()->row_array();
        if ($return == null) {
            return 0;
        } else {
            return 1;
        }
    }

    function consultaprocedimento($cpf, $nome, $competencia, $crm) {

        $Obj = new SoapClient(null, array(
            'location' => 'http://172.30.40.252/webservice/hospub',
            'uri' => 'http://172.30.40.252/webservice/hospub',
            'trace' => 1,
            'exceptions' => 0));

        $aParam['cpf'] = $cpf;
        $aParam['competencia'] = $competencia;
        $pront = ($Obj->consultaproducaohospub($aParam));
        if ($pront != null) {
            foreach ($pront as $value) {
                $args['procedimento'] = trim($value['N57PROCAMB']);
                $args['cpf'] = trim($value['C57CPFMED']);
                $args['nome'] = trim($nome);
                $args['crm'] = trim($crm);
                $args['competencia'] = trim($competencia);
                //var_dump($value['C57CPFMED']);
                $this->db->insert('tb_emergencia_faturamentomedico', $args);
            }
        }
    }

    function listarfaturamentomensal($competencia) {
        $this->db->select();
        $this->db->from('tb_emergencia_faturamentomedico');
        $this->db->orderby('nome');
        $this->db->orderby('procedimento');
        $this->db->where('competencia', "$competencia");
        $return = $this->db->get();
        return $return->result();
    }

    function samelistahospub() {


        $Obj = new SoapClient(null, array(
            'location' => 'http://172.30.40.252/webservice/hospub',
            'uri' => 'http://172.30.40.252/webservice/hospub',
            'trace' => 1,
            'exceptions' => 0));


        $aParam['PRONTUARIO'] = $_POST['txtprontuario'];
        $pront = ($Obj->samehospublista($aParam));
//                $aParam['be']= $_POST['txtprontuario'];
//                $pront = ($Obj->testeijf($aParam));
//                echo "<pre>";
//                var_dump($pront);
//                echo "</pre>";
//                die;


        return $pront;
    }

    function consultacodigomunicipio() {
        try {

            $Obj = new SoapClient(null, array(
                'location' => 'http://172.30.40.252/webservice/hospub',
                'uri' => 'http://172.30.40.252/webservice/hospub',
                'trace' => 1,
                'exceptions' => 0));
            $aParam['be'] = 'S';
            $pront = ($Obj->consultacodigomunicipio($aParam));
//                echo "<pre>";
//                var_dump($pront);
//                echo "</pre>";
//                die;
            return $pront;
        } catch (Exception $e) {
            print $e->getMessage();
        }
    }

    function listapacientes($municipio) {

        $Obj = new SoapClient(null, array(
            'location' => 'http://172.30.40.252/webservice/hospub',
            'uri' => 'http://172.30.40.252/webservice/hospub',
            'trace' => 1,
            'exceptions' => 0));

        $aParam['municipio'] = $municipio;
        $pront = ($Obj->consultapacientes($aParam));
//        var_dump($pront);
//        die;
        if ($pront != null) {
            foreach ($pront as $value) {
//                echo "<pre>";
//                var_dump($pront);
//                echo "</pre>";
//                die;
                $args['procedimento'] = trim($value['N57PROCAMB']);
                $args['cpf'] = trim($value['C57CPFMED']);
                $args['nome'] = trim($nome);
                $args['crm'] = trim($crm);
                $args['competencia'] = trim($competencia);
                //var_dump($value['C57CPFMED']);
                $this->db->insert('tb_emergencia_faturamentomedico', $args);
            }
        }
    }

    function listarpacienteholter($paciente_id = NULL) {
        $sql = $this->db->select('nome');
        $this->db->from('tb_paciente');
        $this->db->where('paciente_id', $paciente_id);
        return $sql->get()->result();
    }

    function listarverificacaopermisao2($empresa_id = NULL) {

        $this->db->select('ep.*');
        $this->db->from('tb_empresa_permissoes ep');
        $this->db->where('ep.empresa_id', $empresa_id);
        $retorno_header2 = $this->db->get()->result();
        return $retorno_header2;
    }

    function gravarprecadastro() {
        try {
            
        

        $hora = date('Y-m-d H:i:s');
        if (isset($_POST['nome'])) {
            $this->db->set("nome", @$_POST['nome']);
        }
        if ($_POST['telefone'] != "") {
            $this->db->set('telefone', str_replace("(", "", str_replace(")", "", str_replace("-", "", $_POST['telefone']))));
        }
        if ($_POST['email'] != "") {
            $this->db->set("email", @$_POST['email']);
        }
        if ($_POST['municipio_id'] != "") {
            $this->db->set("municipio_id", @$_POST['municipio_id']);
        }
        if ($_POST['txtCrm'] != "") {
            $this->db->set("crm", @$_POST['txtCrm']);
        }
        if ($_POST['crm_estado'] != "") {
            $this->db->set("crm_estado", @$_POST['crm_estado']);
        }
        if ($_POST['area_especialista'] != "") {
            $this->db->set("area_especialista", @$_POST['area_especialista']);
        }
        if ($_POST['area_subesp'] != "") {
            $this->db->set("area_subesp", @$_POST['area_subesp']);
        }
        if ($_POST['area_mestrado'] != "") {
            $this->db->set("area_mestrado", @$_POST['area_mestrado']);
        }
        if ($_POST['area_doutorado'] != "") {
            $this->db->set("area_doutorado", @$_POST['area_doutorado']);
        }
        if ($_POST['txtTitulo_especialistaOp'] != "") {
            if (@$_POST['txtTitulo_especialistaOp'] == "sim") {
                $this->db->set("titulo_especialista", "t");
            } else {
                $this->db->set("titulo_especialista", "f");
            }
        }
        if ($_POST['txtTitulo_especialista'] != "") {
            $this->db->set("instituicao_especialista", @$_POST['txtTitulo_especialista']);
        }
        if ($_POST['txtInstituResid'] != "") {
            $this->db->set("instituto_resid", @$_POST['txtInstituResid']);
        }
        if ($_POST['txtSubespeciOp'] != "") {
            if ($_POST['txtSubespeciOp'] == "sim") {
                $this->db->set("subespecializacao", "t");
            } else {
                $this->db->set("subespecializacao", "f");
            }
        }
        if ($_POST['txtSubespeci'] != "") {
            $this->db->set("instituicao_subespecializacao", @$_POST['txtSubespeci']);
        }
        if ($_POST['txtMestradoOp'] != "") {
            if ($_POST['txtMestradoOp'] == "sim") {
                $this->db->set("mestrado", "t");
            } else {
                $this->db->set("mestrado", "f");
            }
        }
        if ($_POST['txtMestrado'] != "") {
            $this->db->set("instituicao_mestrado", @$_POST['txtMestrado']);
        }
        if ($_POST['txtDoutoradoOp'] != "") {
            if ($_POST['txtDoutoradoOp'] == "sim") {
                $this->db->set("doutorado", "t");
            } else {
                $this->db->set("doutorado", "f");
            }
        }
        if ($_POST['txtDoutorado'] != "") {
            $this->db->set("instituicao_doutorado", @$_POST['txtDoutorado']);
        }
        if($_POST['disponibilidade_atendimento'] != ""){
            $this->db->set("disponibilidade_atendimento", @$_POST['disponibilidade_atendimento']);
        }
        if ($_POST['outros'] != "") {
            $this->db->set("outros", @$_POST['outros']);
        }
        $this->db->set("data_cadastro", $hora);
        $this->db->insert('tb_pacientes_precadastro');
        $paciente_precadastro_id = $this->db->insert_id();

        if (!is_dir("./upload/curriculumprecadastro/")) {
            mkdir("./upload/curriculumprecadastro/");
            $destino = "./upload/curriculumprecadastro/";
            chmod($destino, 0777);
        }

        if (!is_dir("./upload/curriculumprecadastro/$paciente_precadastro_id")) {
            mkdir("./upload/curriculumprecadastro/$paciente_precadastro_id");
            $destino = "./upload/curriculumprecadastro/$paciente_precadastro_id";
            chmod($destino, 0777);
        }

        for ($i = 0; $i < count($_FILES['arquivos']['name']); $i++) {
            echo $i;
            $_FILES['userfile']['name'] = $_FILES['arquivos']['name'][$i];
            $_FILES['userfile']['type'] = $_FILES['arquivos']['type'][$i];
            $_FILES['userfile']['tmp_name'] = $_FILES['arquivos']['tmp_name'][$i];
            $_FILES['userfile']['error'] = $_FILES['arquivos']['error'][$i];
            $_FILES['userfile']['size'] = $_FILES['arquivos']['size'][$i];
            $config['upload_path'] = "./upload/curriculumprecadastro/" . $paciente_precadastro_id;
            $config['allowed_types'] = 'gif|jpg|BMP|bmp|png|jpeg|pdf|doc|docx|xls|xlsx|ppt|zip|rar|xml|txt';
            $config['max_size'] = '0';
            $config['overwrite'] = FALSE;
            $config['encrypt_name'] = FALSE;
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload()) {
                $error = array('error' => $this->upload->display_errors());
                if ($error['error'] == '<p>The uploaded file exceeds the maximum allowed size in your PHP configuration file.</p>') {
                    @$erro_detectado = 'O Arquivo enviado excede o tamanho máximo permitido.';
                }
                $data['mensagem'] = 'Erro, ' . $erro_detectado;
            } else {
                $error = null;
                $data = array('upload_data' => $this->upload->data());
                $data['mensagem'] = 'Sucesso ao enviar Arquivo.';
            }
            
          
         }
         
           return true;
        } catch (Exception $exc) {
           return false;
        }
    }


    function listarprecadastro($args = array()) {
        $this->db->select('');
        $this->db->from('tb_pacientes_precadastro');
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $nome = $this->removerCaracterEsp($args['nome']);
            // var_dump($nome); die;
            $this->db->where("translate(nome,  
                'áàâãäåaaaÁÂÃÄÅAAAÀéèêëeeeeeEEEÉEEÈÊìíîïìiiiÌÍÎÏÌIIIóôõöoooòÒÓÔÕÖOOOùúûüuuuuÙÚÛÜUUUUçÇñÑýÝ',  
                'aaaaaaaaaAAAAAAAAAeeeeeeeeeEEEEEEEEiiiiiiiiIIIIIIIIooooooooOOOOOOOOuuuuuuuuUUUUUUUUcCnNyY'   
                 ) ilike", '%' . $nome . '%');
        }
        $this->db->where('ativo', 't');
        return $this->db;
    }

    // Eu comentei a função abaixo, por motivo que a função "listarprecadastro" inicialmente
    // Já era utilizado para a tela de Pré-cadastro no sistema, caminho Configuração->Recepção->Pré-Cadastro
    // No momento que foi feita a alteração, a clinica que utiliza o pré-cadastro foi impactada negativamente de imediato após 
    // a atualização. Quem realizou essa alteração, se possivel informar o motivo de utilizar
    // uma função já existente 
    // Leonardo --

    // function listarprecadastro($args = array()) {
    //     $this->db->select('');
    //     $this->db->from('tb_paciente_precadastro_c pp');
    //     if (isset($args['nome']) && strlen($args['nome']) > 0) {
    //         $nome = $this->removerCaracterEsp($args['nome']);
    //         // var_dump($nome); die;
    //         $this->db->where("translate(nome,  
    //             'áàâãäåaaaÁÂÃÄÅAAAÀéèêëeeeeeEEEÉEEÈÊìíîïìiiiÌÍÎÏÌIIIóôõöoooòÒÓÔÕÖOOOùúûüuuuuÙÚÛÜUUUUçÇñÑýÝ',  
    //             'aaaaaaaaaAAAAAAAAAeeeeeeeeeEEEEEEEEiiiiiiiiIIIIIIIIooooooooOOOOOOOOuuuuuuuuUUUUUUUUcCnNyY'   
    //              ) ilike", '%' . $nome . '%');
    //     }
    //     $this->db->where('ativo', 't');
    //     $this->db->where('confirmado', 'f');
    //     return $this->db;
    // }

    function listarprecadastroinfo($pacientes_precadastro_id) {
        $this->db->select('m.nome as municipio,pp.*');
        $this->db->from('tb_pacientes_precadastro pp');
        $this->db->join('tb_municipio m', 'm.municipio_id = pp.municipio_id', 'left');
        $this->db->where('pp.pacientes_precadastro_id', $pacientes_precadastro_id);
        $this->db->where('ativo', 't');
        return $this->db->get()->result();
    }

    function confirmarprecadastro($pacientes_precadastro_id) {
        $hora = date('Y-m-d H:i:s');
        $operador = $this->session->userdata('operador_id');
        $this->db->select('*');
        $this->db->from('tb_pacientes_precadastro');
        $this->db->where('pacientes_precadastro_id', $pacientes_precadastro_id);
        $retorno = $this->db->get()->result();
        foreach ($retorno as $item) {
            $this->db->set('pacientes_precadastro_id', $item->pacientes_precadastro_id);
            $this->db->set('nome', $item->nome);
            $this->db->set('municipio_id', $item->municipio_id);
            $this->db->set('email', $item->email);
            $this->db->set('telefone', $item->telefone);
            $this->db->set('usuario', "usuario");
            $this->db->set('data_cadastro', $hora);
            $this->db->set('operador_cadastro', $operador);
            $this->db->insert('tb_operador');
        }
        $this->db->set('ativo', 'f');
        $this->db->where('pacientes_precadastro_id', $pacientes_precadastro_id);
        $this->db->set('data_atualizacao', $hora);
        $this->db->set('operador_atualizacao', $operador);
        $this->db->update('tb_pacientes_precadastro');
    }

    function emailprecadastro($pacientes_precadastro_id) {
        $hora = date('Y-m-d H:i:s');
        $operador = $this->session->userdata('operador_id');

        $this->db->set('status', 'Email enviado');
        $this->db->where('pacientes_precadastro_id', $pacientes_precadastro_id);
        $this->db->set('data_atualizacao', $hora);
        $this->db->set('operador_atualizacao', $operador);
        $this->db->update('tb_pacientes_precadastro');
    }

    function desativarpaciente($paciente_id) {
        $hora = date('Y-m-d H:i:s');
        $operador = $this->session->userdata('operador_id');
        $this->db->set('desativado', 't');
        $this->db->set('ativo', 'f');
        $this->db->set('data_exclusao', $hora);
        $this->db->set('operador_exclusao', $operador);
        $this->db->where('paciente_id', $paciente_id);
        $this->db->update('tb_paciente');
        return true;
    }

    function excluirprecadastroPaciente($pacientes_precadastro_id) {
        $hora = date('Y-m-d H:i:s');
        $operador = $this->session->userdata('operador_id');
        $this->db->set('data_atualizacao', $hora);
        $this->db->set('operador_atualizacao', $operador);
        $this->db->set('ativo', 'f');
        $this->db->where('paciente_precadastro_c_id', $pacientes_precadastro_id);
        $this->db->update('tb_paciente_precadastro_c');
    }

    function confirmarprecadastroPaciente($pacientes_precadastro_id) {
        $hora = date('Y-m-d H:i:s');
        $operador = $this->session->userdata('operador_id');
        $this->db->set('data_atualizacao', $hora);
        $this->db->set('operador_atualizacao', $operador);
        $this->db->set('confirmado', 't');
        $this->db->where('paciente_precadastro_c_id', $pacientes_precadastro_id);
        $this->db->update('tb_paciente_precadastro_c');

        $this->db->select('pp.*');
        $this->db->from('tb_paciente_precadastro_c pp');
        $this->db->where('paciente_precadastro_c_id', $pacientes_precadastro_id);
        $return = $this->db->get()->result();
        foreach ($return as $key => $value) {
            $this->db->select('paciente_id, nome');
            $this->db->from('tb_paciente');
            // $this->db->where('nome', $json_post->nome);
            $this->db->where('cpf', str_replace(".", "", str_replace("-", "", $value->cpf)));
            $this->db->where('ativo', 't');
            $this->db->orderby('paciente_id');
            $contadorPaciente = $this->db->get()->result();

            $this->db->set('nome', $value->nome);
            $this->db->set('cns', $value->email);
            // $this->db->set('usuario_app', $json_post->usuario_app);
            $this->db->set('senha_app', $value->senha_app);
            if($value->data_nascimento != ''){
                $this->db->set('nascimento', date("Y-m-d", strtotime(str_replace("/", "-", $value->data_nascimento))));
            }
            $this->db->set('cpf', str_replace(".", "", str_replace("-", "", $value->cpf)));
            $this->db->set('telefone', str_replace("(", "", str_replace(")", "", str_replace("-", "", $value->telefone))));
            $this->db->set('whatsapp', str_replace("(", "", str_replace(")", "", str_replace("-", "", $value->whatsapp))));
            if(count($contadorPaciente) > 0){
                $this->db->set('data_atualizacao', $hora);
                $this->db->set('operador_atualizacao', $operador);
                $this->db->where('cpf', str_replace(".", "", str_replace("-", "", $value->cpf)));
                $this->db->update('tb_paciente');
                $paciente_id = $contadorPaciente[0]->paciente_id;
            }else{
                $this->db->set('data_cadastro', $hora);
                $this->db->set('operador_cadastro', 1);
                $this->db->insert('tb_paciente');
                $paciente_id = $this->db->insert_id();
            }
            return $paciente_id;
            
        }

    }

    function excluirprecadastro($pacientes_precadastro_id) {

        $this->db->set('ativo', 'f');
        $this->db->where('pacientes_precadastro_id', $pacientes_precadastro_id);
        $this->db->update('tb_pacientes_precadastro');
    }

    function gravarpacienteparcial() {

        $this->db->set('nome', $_POST['txtNome']);
        $this->db->set('telefone', $_POST['telefone']);
        $this->db->insert('tb_paciente');
        $paciente_id = $this->db->insert_id();
        return $paciente_id;
         
    }
    
    function listarocorrenciaform(){
     
    }
    
     function gravartemplateocorrencia() {

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        $empresa_id = $this->session->userdata('empresa_id');
        $template_id = $_POST['template_id']; 
        if ($template_id == '') {// insert
            $this->db->set('nome_template', $_POST['nomeTemplate']); 
            $this->db->set('template', json_encode($_POST['template']));
            $this->db->set('empresa_id', $empresa_id);
            $this->db->set('data_cadastro', $horario);
            $this->db->set('operador_cadastro', $operador_id);
            $this->db->insert('tb_template_ocorrencia');
        } else { // update
            $this->db->set('nome_template', $_POST['nomeTemplate']); 
            $this->db->set('template', json_encode($_POST['template']));
            $this->db->set('empresa_id', $empresa_id);
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('template_ocorrencia_id', $template_id);
            $this->db->update('tb_template_ocorrencia');
        }  
        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return false;
        else
            return true;
    }

    function listartemplateocorrencia() {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('ta.template_ocorrencia_id, ta.nome_template, ta.template, ta.data_cadastro, ta.operador_cadastro,ta.ativo,op.nome as op_atualizacao');
        $this->db->from('tb_template_ocorrencia ta');
        $this->db->join('tb_operador op','op.operador_id = ta.operador_atualizacao','left');
        $this->db->join('tb_empresa e', 'e.empresa_id = ta.empresa_id', 'left'); 
        return $this->db;
    }
    
    
     function listartemplateocorrenciaform($template_id) {
        $this->db->select('ta.template_ocorrencia_id,  ta.nome_template, ta.template, ta.data_cadastro, ta.operador_cadastro');
        $this->db->from('tb_template_ocorrencia ta');
        $this->db->join('tb_empresa e', 'e.empresa_id = ta.empresa_id', 'left'); 
        $this->db->where('ta.template_ocorrencia_id', $template_id); 
        $return = $this->db->get();
        return $return->result();
    }
    
    
    
    function excluirtemplateocorrencia($impressao_id) { 
        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('template_ocorrencia_id', $impressao_id);
        $this->db->update('tb_template_ocorrencia'); 
        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return false;
        else
            return true;
    }
    
    function reativartemplateocorrencia($impressao_id) { 
        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        $this->db->set('ativo', 't');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('template_ocorrencia_id', $impressao_id);
        $this->db->update('tb_template_ocorrencia'); 
        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return false;
        else
            return true;
    }
    
    function listarocorrencia() {
        $this->db->select('ta.template_ocorrencia_id,  ta.nome_template, ta.template, ta.data_cadastro, ta.operador_cadastro');
        $this->db->from('tb_template_ocorrencia ta');
        $this->db->join('tb_empresa e', 'e.empresa_id = ta.empresa_id', 'left');  
        $this->db->where('ta.ativo','t'); 
        $return = $this->db->get();
        return $return->result();
    }
    
    
    
    
    
}

?>
