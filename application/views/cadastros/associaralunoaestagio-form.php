<div class="panel-body">
<meta charset="utf-8">
        <style>
            body{
                background-color:silver !important;
            }
        </style>

                <h2>Associar Aluno a Vaga de Estagio</h2>
                <br>
            <?
   
            foreach($vagas as $item){?>
                    <table border="1">
                        <tr>
                            <th>Área</th>
                            <th>Tipo</th>
                            <th>Convenio</th>
                            <th>Qtd de Vagas</th>
                            <th>Setor</th>
                            <th>Disciplina</th>
                            <th>Data Inicial</th>
                            <th>Data Final</th> 
                        </tr>

                        <tr>
                            <td><?=$item->descricao?></td>
                            <td><?=$item->tipo_vaga?></td>   
                            <td><?=$item->nome?></td>                  
                            <td><?=$item->qtde_vagas?></td> 
                            <td><?=$item->setor1; ?></td>
                            <td><?=$item->disciplina1; ?> </td>
                            <td><?= ($item->data_inicio != "") ? date('d/m/Y',strtotime($item->data_inicio)) : ""; ?></td>
                            <td><?= ($item->data_final != "") ? date('d/m/Y',strtotime($item->data_final)) : ""; ?></td>
                        </tr>
                    </table>
            <?}?>
                <br>
                

                <form action="<?=base_url()?>cadastros/pacientes/gravaralunosvagas" method="post">
                    <input type="text" value="<?= $item->qtde_vagas?>" id="limite_alunos" name="limite_alunos" hidden="">
                    <table>
                        <tr>
                            <td>Aluno:</td>
                            <td>
                            <input type="hidden" name="instituicao_id" value="<?=$instituicao_id?>">
                                <input type="hidden" name="vaga_id" value="<?=$vaga_id?>">
                                <input type="hidden" name="convenio_id" value="<?=$convenio_id?>">
                                <select name="aluno_id[]" id="aluno_id" class="size10 chosen-select" required multiple="">
                                    <option value=""> Selecione </option>
                                    <?foreach($alunos as $item){?>
                                        <option value="<?=$item->paciente_id?>" > <?=$item->nome?> </option>
                                    <?}?>
                                </select>
                            </td>
                        </tr>

<!--                        <tr>
                            <td>Data Inicio: </td>
                            <td><input type="text" id="datainicio" class="data" name="data_inicio" required/> </td>
                        </tr>-->

<!--                        <tr>
                            <td>Data Fim: </td>
                            <td><input type="text" id="datafinal" class="data" name="data_final" required/></td>
                        </tr>-->

<!--                        <tr>
                            <td>Tipo da Vaga: </td>
                            <td>
                                <select name="tipodavaga" id="" class="form-control" required>
                                    <option value="">Selecione</option>
                                    <?foreach($informacoes as $item){?>
                                        <?if($item->tipo == 'tipovaga'){?>
                                            <option value="<?=$item->descricao?>"><?=$item->descricao?></option>
                                        <?}?>
                                    <?}?>
                                </select>
                            </td>
                        </tr>-->
                    </table>

                                        <br>
                                        <input id="Adicionar" type="submit" value="Adicionar">
                </form>

</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.maskedinput.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.maskMoney.js"></script>

<link rel="stylesheet" href="<?= base_url() ?>js/chosen/chosen.css">
<!--<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/style.css">-->
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/prism.css">
<script type="text/javascript" src="<?= base_url() ?>js/chosen/chosen.jquery.js"></script>
<!--<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/prism.js"></script>-->
<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/init.js"></script>

<script>
    
    $(document).ready(function(){
        $("#aluno_id").change(function(){ 
          var total = $("#aluno_id :selected").length; 
//               alert(total);
         if(total > $("#limite_alunos").val()){
           alert('Limite de alunos atingidos');
           $("#Adicionar").prop("disabled", true);
         }else{
           $("#Adicionar").prop("disabled", false);
         }
         
        });
    });
    $(document).ready(function () {
        $('.data').mask('99/99/9999');
    });
</script>