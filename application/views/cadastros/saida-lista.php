<?
$empresa_id = $this->session->userdata('empresa_id');
if (@$_GET['txtempresa'] != '') {
    $empresa_form_id = @$_GET['txtempresa'];
} else {
    @$_GET['txtempresa'] = $empresa_id;
    $empresa_form_id = $empresa_id;
}
if (count($_GET) > 0) {
    $url = "idfinanceiro=".@$_GET['idfinanceiro']. "&txtempresa=".@$_GET['txtempresa']."&conta=".@$_GET['conta']."&datainicio=".@$_GET['datainicio']
            ."&datafim=".@$_GET['datafim']."&nome=".@$_GET['nome']."&nome_classe=".@$_GET['nome_classe']
            ."&empresa=".@$_GET['empresa']."&obs=".@$_GET['obs'];
}
?>
<div class="content"> <!-- Inicio da DIV content -->
    <table>
        <tr>
            <td>
                <div class="bt_link_new">
                    <button class="btn btn-outline-primary btn-round btn-sm" href="<?php echo base_url() ?>cadastros/caixa/novasaida/<?=@$empresa_form_id?>/<?=@$url?>">
                        Nova saida
                    </button>
                </div>

            </td>
            <td>
                <div class="bt_link_new">
                    <button class="btn btn-outline-default btn-round btn-sm" href="<?php echo base_url() ?>cadastros/caixa/transferencia">
                        Transferencia
                    </button>
                </div>

            </td>
        </tr>
    </table>


    <?
    $classe = $this->classe->listarclasse();
    $saldo = $this->caixa->saldo();
    $credores = $this->caixa->empresa();
    $empresas = $this->exame->listarempresas();
    $empresa_permissao = $this->guia->listarempresapermissoes();
    $conta = $this->forma->listarformaempresa(@$_GET['txtempresa']);
    $tipo = $this->tipo->listartipo();
    $id_financeiro = $empresa_permissao[0]->id_linha_financeiro;
    
    $perfil_id = $this->session->userdata('perfil_id');
//    $empresa_id = $this->session->userdata('empresa_id');
    if($empresa_permissao[0]->data_pesquisa_financeiro == 't'){
        if(@$_GET['datainicio'] == ''){
            @$_GET['datainicio'] = date("01/m/Y");
        }
        if(@$_GET['datafim'] == ''){
            @$_GET['datafim'] = date("t/m/Y");
        }
    }
    ?>

    <div id="accordion">
        <div class="alert alert-info"><b>Manter Saida</b></div>
        <div>
            <table>
                <thead>
                <form method="get" action="<?= base_url() ?>cadastros/caixa/pesquisar2">
                    <fieldset>

                        <div class="row">
                            <div class="col-lg-2">
                                <div>
                                    <?if($id_financeiro == 't'){?>
                                        <label>ID</label>
                                        <input type="number"  id="idfinanceiro"  name="idfinanceiro" class="texto01"  value="<?php echo @$_GET['idfinanceiro']; ?>" />
                                    <?}?>
                                </div>
                                <div>
                                    <?if($id_financeiro == 't'){?>
                                        <label class="tabela_title">ID Contas Receber</label>
                                        <input type="number"  id="idcontasreceber"  name="idcontasreceber" class="texto01"  value="<?php echo @$_GET['idcontasreceber']; ?>" />
                                    <?}?>
                                </div>
                                <div>
                                    <label class="tabela_title">Empresa</label>
                                    <select name="txtempresa" id="txtempresa" class="form-control" onchange="atualizaRestultados(this.value)">
                                        <option value="0">TODOS</option>
                                        <? foreach ($empresas as $value) : ?>
                                            <option value="<?= $value->empresa_id; ?>" <?
                                            if (@$_GET['txtempresa'] == $value->empresa_id || ($empresa_id == $value->empresa_id && @$_GET['txtempresa'] == '')):echo 'selected';
                                            endif;
                                            ?>><?php echo $value->nome; ?></option>
                                        <? endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="tabela_title">Classe</label>
                                    <select name="nome_classe" id="nome_classe" class="form-control">
                                        <option value="">TODOS</option>
                                        <? foreach ($classe as $value) : ?>
                                            <option value="<?= $value->descricao; ?>" <?
                                            if (@$_GET['nome_classe'] == $value->descricao):echo 'selected';
                                            endif;
                                            ?>><?php echo $value->descricao; ?></option>
                                        <? endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div>
                                    <label class="tabela_title">Conta</label>
                                    <select name="conta" id="conta" class="form-control">
                                        <option value="">TODAS</option>
                                        <? foreach ($conta as $value) : ?>
                                            <option value="<?= $value->forma_entradas_saida_id; ?>" <?
                                            if (@$_GET['conta'] == $value->forma_entradas_saida_id):echo 'selected';
                                            endif;
                                            ?>><?php echo $value->descricao; ?></option>
                                        <? endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="tabela_title">Credor/Devedor</label>
                                    <select name="empresa" id="empresa" class="form-control">
                                        <option value="">TODOS</option>
                                        <? foreach ($credores as $value) : ?>
                                            <option value="<?= $value->financeiro_credor_devedor_id; ?>" <?
                                            if (@$_GET['empresa'] == $value->financeiro_credor_devedor_id):echo 'selected';
                                            endif;
                                            ?>><?php echo $value->razao_social; ?></option>
                                        <? endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div>
                                    <label class="tabela_title">Data Inicio</label>
                                    <? if (isset($_GET['datainicio'])) { ?>
                                        <input type="text"  id="datainicio" alt="date" name="datainicio" class="form-control"  value="<?php echo @$_GET['datainicio']; ?>" />
                                    <? } else { ?>
                                        <!--                                <input type="text"  id="datainicio" alt="date" name="datainicio" class="size1"  value="<?php echo @date('01/m/Y'); ?>" /> -->
                                        <input type="text"  id="datainicio" alt="date" name="datainicio" class="form-control"  value="<?php echo @$_GET['datainicio']; ?>" />

                                    <? } ?>
                                </div>
                                <div>
                                    <label class="tabela_title">Observacao</label>
                                    <input type="text"  id="obs" name="obs" class="size2"  value="<?php echo @$_GET['obs']; ?>" />
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div>
                                    <label class="tabela_title">Data Fim</label>

                                    <? if (isset($_GET['datafim'])) { ?>
                                        <input type="text"  id="datafim" alt="date" name="datafim" class="form-control"  value="<?php echo @$_GET['datafim']; ?>" />
                                    <? } else { ?>
                                        <input type="text"  id="datafim" alt="date" name="datafim" class="form-control"  value="<?php echo @date('t/m/Y'); ?>" />
                                        <input type="text"  id="datafim" alt="date" name="datafim" class="form-control"  value="<?php echo @$_GET['datafim']; ?>" />

                                    <? } ?>
                                </div>
                                <div>
                                    <br>
                                    <button type="submit" id="enviar" class=" btn btn-outline-success btn-round form-control">Pesquisar</button>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div>
                                    <label class="tabela_title">Tipo</label>
                                    <select name="nome" id="nome" class="form-control texto08">
                                        <option value="">TODOS</option>
                                        <? foreach ($tipo as $value) : ?>
                                            <option value="<?= $value->tipo_entradas_saida_id; ?>" <?
                                            if (@$_GET['nome'] == $value->tipo_entradas_saida_id):echo 'selected';
                                            endif;
                                            ?>><?php echo $value->descricao; ?></option>
                                        <? endforeach; ?>
                                    </select>
                                </div>
                            </div>


                    </fieldset>
                <tr>

                    <th class="tabela_title" colspan="6">Saldo Total em Caixa:  <?= number_format($saldo[0]->sum, 2, ",", ".") ?></th>
                </tr>
                </thead>

            </table>
        <div class="table-responsive">
            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                    <tr>
                        <?if($id_financeiro == 't'){?>
                            <th class="tabela_header">ID</th>
                        <?}?>
                        <?if($id_financeiro == 't'){?>
                            <th class="tabela_header">ID Contas a Pagar</th>
                        <?}?>
                        <th class="tabela_header">Nome</th>
                        <th class="tabela_header">Tipo</th>
                        <th class="tabela_header">Classe</th>
                        <th class="tabela_header" width="90px;">Dt saida</th>
                        <th class="tabela_header" width="90px;">Valor</th>
                        <th class="tabela_header">Conta</th>
                        <th class="tabela_header">Observacao</th>
                        <th class="tabela_header" colspan="3">Detalhes</th>
                    </tr>
                </thead>
                <?php
                $url = $this->utilitario->build_query_params(current_url(), $_GET);
                $consulta = $this->caixa->listarsaida($_GET);
                $total = $consulta->count_all_results();
                $limit = 50;
                $valor_totalSelect = $this->caixa->listarsaidatotal($_GET);
                isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;

                if ($total > 0) {
                    ?>
                    <tbody>
                        <?php
                        $totaldalista = 0;
                        $lista = $this->caixa->listarsaida($_GET)->orderby('data desc, s.data_cadastro desc, saidas_id')->limit($limit, $pagina)->get()->result();
                        $estilo_linha = "tabela_content01";
                        foreach ($lista as $item) {
                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                            $totaldalista = $totaldalista + $item->valor;
                            ?>
                            <tr>
                                <?if($id_financeiro == 't'){?>
                                    <td class="<?php echo $estilo_linha; ?>"><?= $item->saidas_id; ?></td>
                                <?}?>
                                <?if($id_financeiro == 't'){?>
                                    <td class="<?php echo $estilo_linha; ?>"><b><?= $item->contas_pagar_id; ?></b></td>
                                <?}?>
                                <td class="<?php echo $estilo_linha; ?>">
                                    <?
                                if(substr($item->classe, 0,15) == 'CAIXA CIRURGICO' && strtoupper($item->razao_social) == "CAIXA FIXO" &&   $empresa_permissao[0]->internacao == 't'){
                                      echo "CAIXA CIRURGICO DINHEIRO";  
                                }elseif(substr($item->classe, 0,18) == 'CAIXA INTERNAÇÃO' && strtoupper($item->razao_social) == "CAIXA FIXO" && $empresa_permissao[0]->internacao == 't'){
                                       echo "CAIXA INTERNAÇÃO DINHEIRO";  
                                }else{  
                                      echo $item->razao_social; 
                                }  
                                        ?>
                                 </td>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->tipo; ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->classe; ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><?= substr($item->data, 8, 2) . "/" . substr($item->data, 5, 2) . "/" . substr($item->data, 0, 4); ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><b><?= number_format($item->valor, 2, ",", "."); ?></b></td>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->conta; ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->observacao; ?></td>

                               <?if($perfil_id != 10){?>  
                                <td class="<?php echo $estilo_linha; ?>" width="100px;"><div class="bt_link">
                                   <?if($item->tipo != 'TRANSFERENCIA'){?>   <a href="<?= base_url() ?>cadastros/caixa/carregar/<?= $item->saidas_id ?>">Editar</a><?}?></div>
                                </td>
                                <td class="<?php echo $estilo_linha; ?>" width="100px;"><div class="bt_link">
                                    <?if(($item->tipo == 'TRANSFERENCIA' && @$empresa_permissao[0]->excluir_transferencia == 't') || $item->tipo != 'TRANSFERENCIA'){?>  <a  style="cursor:pointer;" onclick="saidaexclusao(<?= $item->saidas_id ?>)" >Excluir</a><?}?></div>
                                </td>
                                <td class="<?php echo $estilo_linha; ?>" width="50px;"><div class="bt_link">
                                        <a href="<?= base_url() ?>cadastros/caixa/anexarimagemsaida/<?= $item->saidas_id ?>">Arquivos</a></div>
                                </td>
                                
                                <?}else{?>
                                 <td class="<?php echo $estilo_linha; ?>" width="100px;"><div class="bt_link">
                                   <?if($item->tipo != 'TRANSFERENCIA'){?> Editar<?}?></div>
                                </td>
                                <td class="<?php echo $estilo_linha; ?>" width="100px;"><div class="bt_link">
                                    <?if($item->tipo != 'TRANSFERENCIA'){?>   Excluir<?}?></div>
                                </td>
                                <td class="<?php echo $estilo_linha; ?>" width="50px;"><div class="bt_link">
                                        <a href="<?= base_url() ?>cadastros/caixa/anexarimagemsaida/<?= $item->saidas_id ?>">Arquivos</a></div>
                                </td>   
                                <?}
                                
                                ?>
                            </tr>

                        </tbody>
                        <?php
                    }
                }
                ?>
                <tfoot>
                    <tr>
                        <th class="tabela_footer" colspan="7">
                            <?php $this->utilitario->paginacao($url, $total, $pagina, $limit); ?>
                            Total de registros: <?php echo $total; ?>
                        </th>
                        <th class="tabela_footer" colspan="4">
                            <? if ($total > 0) { ?>
                                Valor Total : <?= number_format($valor_totalSelect, 2, ",", "."); ?>
                            <? } ?>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
            <br>
            <br>
            <table>
                <thead>
                <th class="tabela_header">Contas</th>
                <th class="tabela_header">Saldo</th>
                </thead>
                <tbody>
                    <?
                    $estilo_linha = "tabela_content01";
                    foreach ($conta as $item) {
                        ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                        $valor = $this->caixa->listarsomaconta($item->forma_entradas_saida_id);
                        ?>
                        <tr>
                            <td class="<?php echo $estilo_linha; ?>"><?= $item->descricao; ?></td>
                            <td class="<?php echo $estilo_linha; ?>"><?= number_format($valor[0]->total, 2, ",", "."); ?></td>
                        </tr>
                    <? } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th class="tabela_footer" colspan="2">
                            Saldo Total: <?= number_format($saldo[0]->sum, 2, ",", ".") ?>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div> <!-- Final da DIV content -->
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<script type="text/javascript">
    function saidaexclusao(saidas_id){
         window.open('<?= base_url(); ?>cadastros/caixa/saidaexclusao/'+saidas_id+'', '_blank');  
    }
    function atualizaRestultados(empresaID){
        var parametros = "txtempresa="+empresaID;
        parametros += "&datainicio=<?= @$_GET['datainicio'] ?>&datafim=<?= @$_GET['datafim'] ?>"; 
        parametros += "&nome=<?= @$_GET['nome'] ?>&nome_classe=<?= @$_GET['nome_classe'] ?>";
        parametros += "&empresa=<?= @$_GET['empresa'] ?>&obs=<?= @$_GET['obs'] ?>";
        window.location.replace("<?= base_url() ?>cadastros/caixa/pesquisar2?"+parametros);
    }
    
                                    $(function () {
                                        $('#nome').change(function () {
                                            if ($(this).val()) {
                                                $('.carregando').show();
                                                $.getJSON('<?= base_url() ?>autocomplete/classeportiposaidalista', {nome: $(this).val(), ajax: true}, function (j) {
                                                    options = '<option value=""></option>';
                                                    for (var c = 0; c < j.length; c++) {
                                                        options += '<option value="' + j[c].classe + '">' + j[c].classe + '</option>';
                                                    }
                                                    $('#nome_classe').html(options).show();
                                                    $('.carregando').hide();
                                                });
                                            } else {
                                                $('#nome_classe').html('<option value="">TODOS</option>');
                                            }
                                        });
                                    });

                                    $(function () {
                                        $("#datainicio").datepicker({
                                            autosize: true,
                                            changeYear: true,
                                            changeMonth: true,
                                            monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                                            dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                                            buttonImage: '<?= base_url() ?>img/form/date.png',
                                            dateFormat: 'dd/mm/yy'
                                        });
                                    });
                                    
                                    $(function () {
                                          $('#txtempresa').change(function () {
//                                            if ($(this).val()) {
                                              $('.carregando').show();
                                              $.getJSON('<?= base_url() ?>autocomplete/contaporempresa', {empresa: $(this).val(), ajax: true}, function (j) {
                                                  options = '<option value=""></option>';
                                                  for (var c = 0; c < j.length; c++) {
                                                      options += '<option value="' + j[c].forma_entradas_saida_id + '">' + j[c].descricao + '</option>';
                                                  }
                                                  $('#conta').html(options).show();
                                                  $('.carregando').hide();
                                              });
//                                            } else {
//                                                $('#nome_classe').html('<option value="">TODOS</option>');
//                                            }
                                          });
                                      });

                                      if ($('#txtempresa').val() > 0) {
//                                          $('.carregando').show();
                                          $.getJSON('<?= base_url() ?>autocomplete/contaporempresa', {empresa: $('#txtempresa').val(), ajax: true}, function (j) {
                                              options = '<option value=""></option>';
                                              <?
                                              if(@$_GET['conta'] > 0){
                                                 $conta = $_GET['conta']; 
                                              }else{
                                                 $conta = 0;
                                              }
                                              ?>  
                                              for (var c = 0; c < j.length; c++) {
                                                
                                                  if(<?=$conta?> == j[c].forma_entradas_saida_id){
                                                      options += '<option selected value="' + j[c].forma_entradas_saida_id + '">' + j[c].descricao + '</option>';
                                                  }else{
                                                      options += '<option value="' + j[c].forma_entradas_saida_id + '">' + j[c].descricao + '</option>'; 
                                                  }
                                                  
                                              }
                                              $('#conta').html(options).show();
                                              $('.carregando').hide();
                                          });
                                      }


                                    $(function () {
                                        $("#datafim").datepicker({
                                            autosize: true,
                                            changeYear: true,
                                            changeMonth: true,
                                            monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                                            dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                                            buttonImage: '<?= base_url() ?>img/form/date.png',
                                            dateFormat: 'dd/mm/yy'
                                        });
                                    });

                                    $(function () {
                                        $("#accordion").accordion();
                                    });

</script>
