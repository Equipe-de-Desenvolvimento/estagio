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
                            <td><?=$item->nome_vaga?></td>
                        </tr>

                        <tr>
                            <th>Tipo</th>
                            <td><?=$item->tipo_vaga?></td>
                        </tr>

                        <tr>
                            <th>Qtd de Vagas</th>
                            <td><?=$item->qtde_vagas?></td>
                        </tr>
                    </table>
            <?}?>
                <br>
                

                <form action="<?=base_url()?>cadastros/pacientes/gravaralunosvagas" method="post">
                    <div>
                        <label for="">Aluno</label><br>
                        <input type="hidden" name="instituicao_id" value="<?=$instituicao_id?>">
                        <input type="hidden" name="vaga_id" value="<?=$vaga_id?>">
                        <input type="hidden" name="convenio_id" value="<?=$convenio_id?>">
                        <select name="aluno_id" required>
                            <option value=""> Selecione </option>
                            <?foreach($alunos as $item){?>
                                <option value="<?=$item->paciente_id?>"> <?=$item->nome?> </option>
                            <?}?>
                        </select>
                    </div>
                        <br>
                    <input type="submit" value="Adicionar">
                </form>

</div>

