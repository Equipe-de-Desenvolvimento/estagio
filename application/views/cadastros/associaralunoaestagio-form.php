<div class="panel-body">
<meta charset="utf-8">
        <style>
            body{
                background-color:silver !important;
            }
        </style>

                <h2>Associar Aluno a Vaga de Estagio</h2>
                <br>
            <?foreach($vagas as $item){?>
                    <table border="1">
                        <tr>
                            <th>Área</th>
                            <th>Tipo</th>
                            <th>Convenio</th>
                            <th>Qtd de Vagas</th>
                            
                        </tr>

                        <tr>
                            <td><?=$item->descricao?></td>
                            <td><?=$item->tipo_vaga?></td>   
                            <td><?=$item->nome?></td>                  
                            <td><?=$item->qtde_vagas?></td>
                        </tr>
                    </table>
            <?}?>
                <br>
                

                <form action="<?=base_url()?>cadastros/pacientes/gravaralunosvagas" method="post">
                    <table>
                        <tr>
                            <td>Aluno:</td>
                            <td>
                            <input type="hidden" name="instituicao_id" value="<?=$instituicao_id?>">
                                <input type="hidden" name="vaga_id" value="<?=$vaga_id?>">
                                <input type="hidden" name="convenio_id" value="<?=$convenio_id?>">
                                <select name="aluno_id" required>
                                    <option value=""> Selecione </option>
                                    <?foreach($alunos as $item){?>
                                        <option value="<?=$item->paciente_id?>"> <?=$item->nome?> </option>
                                    <?}?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td>Data Inicio: </td>
                            <td><input type="text" id="datainicio" class="data" name="data_inicio" required/> </td>
                        </tr>

                        <tr>
                            <td>Data Fim: </td>
                            <td><input type="text" id="datafinal" class="data" name="data_final" required/></td>
                        </tr>

                        <tr>
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
                        </tr>
                    </table>

                                        <br>
                    <input type="submit" value="Adicionar">
                </form>

</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.maskedinput.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.maskMoney.js"></script>

<script>
    $(document).ready(function () {
        $('.data').mask('99/99/9999');
    });
</script>