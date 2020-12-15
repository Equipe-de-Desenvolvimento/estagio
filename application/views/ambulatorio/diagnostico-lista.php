
<div class="content"> <!-- Inicio da DIV content -->
    <div class="bt_link_new">
        <a href="<?php echo base_url() ?>ambulatorio/exame/carregardiagnostico/0">
            Novo Diagnostico
        </a>
    </div>
    <div id="accordion">
        <h3 class="singular"><a href="#">Manter Diagnostico</a></h3>
        <div>
            <table>
                <thead> 
                    <tr>
                        <th class="tabela_header">Nome</th>
                        <th class="tabela_header">Opções</th>
                        <th class="tabela_header">Procedimentos</th>
                        <th class="tabela_header">Data criação</th> 
                        <th class="tabela_header">Operador Cadastro</th>
                        <th class="tabela_header">Data Alteração</th> 
                        <th class="tabela_header">Operador Alteração</th> 
                        <th class="tabela_header" colspan="2"><center>Detalhes</center></th>
                   </tr>
                </thead>
                <?php
                $url = $this->utilitario->build_query_params(current_url(), $_GET);
                $consulta = $this->exame->listardiagnostico($_GET);
                $total = $consulta->count_all_results();
                $limit = 10;
                isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;
                if ($total > 0) {
                ?>
                <tbody>
                <?php
                        $lista = $this->exame->listardiagnostico($_GET)->limit($limit, $pagina)->orderby("nome_diagnostico")->get()->result();
                        $estilo_linha = "tabela_content01";
                        foreach ($lista as $item) {
                            $ArrayTipo = Array();  
                            $json  = json_decode($item->diagnostico); 
                            if ($json ==  "") {
                                $json = Array();
                            }
                            // echo '<pre>';
                            // print_r($json);
                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";

                ?>
                <tr>
                <td class="<?php echo $estilo_linha; ?>"><?= $item->nome_diagnostico; ?></td>
                <td class="<?php echo $estilo_linha; ?>"> <?
                foreach($json as $key => $value){
                    $ArrayTipo = $value->opcoes;
                 } 
                 $i = 0;
                 foreach($ArrayTipo as  $value){ 
                    $i++;
                    $texto = str_replace('_', ' ', $value);
                    echo $texto;

                    if ($i != count($ArrayTipo)) {  
                        echo ","; 
                    } 
                 }
                 ?></td>
                 <td class="<?php echo $estilo_linha; ?>">
                 <a  onclick="javascript:window.open('<?= base_url() ?>ambulatorio/exame/procedimentosdiagnostico/<?= @$item->diagnostico_id ?>', '', 'height=600, width=800, left='+(window.innerWidth-600)/2+', top='+(window.innerHeight-800)/2);" >
                 Clique aqui
                 </td>
                 <td class="<?php echo $estilo_linha; ?>"><?= date('d/m/Y', strtotime($item->data_cadastro));?></td> 
                 <td class="<?php echo $estilo_linha; ?>"> <?= $item->op_cadastro; ?> </td>
                 <? if($item->data_atualizacao == null){?>
                    <td class="<?php echo $estilo_linha; ?>"></td>
                 <?} else {?>
                 <td class="<?php echo $estilo_linha; ?>"><?= date('d/m/Y', strtotime($item->data_atualizacao));?></td> 
                 <? } ?>
                 <td class="<?php echo $estilo_linha; ?>"> <?= $item->op_atualizacao; ?> </td> 

                 <!-- <td style="width: 120px;" class="<?php echo $estilo_linha; ?>"><div class="bt_link">
                                        <a href="<?= base_url() ?>ambulatorio/exame/carregardiagnostico/<?= $item->diagnostico_id; ?>">Editar</a></div>
                </td> -->
                <td style="width: 120px;" class="<?php echo $estilo_linha; ?>"><div class="bt_link">
                                        <a onclick="javascript: return confirm('Você deseja realmente Excluir o Diagnostico?');" href="<?= base_url() ?>ambulatorio/exame/excluirdiagnostico/<?= $item->diagnostico_id; ?>">Excluir</a></div>
                </td>

                
                </tr>
                <? } // Final do FOREACH ?>
                </tbody>
                <? } ?>
                <tfoot>
                    <tr>
                        <th class="tabela_footer" colspan="8">
                                <?php $this->utilitario->paginacao($url, $total, $pagina, $limit); ?>
                            Total de registros: <?php echo $total; ?>
                        </th>
                    </tr>
                </tfoot>
                   
            </table>
        </div>
    </div>

</div> <!-- Final da DIV content -->
<script type="text/javascript">

    $(function () {
        $("#accordion").accordion();
    });

</script>
