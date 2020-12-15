<meta charset="UTF-8">
<body bgcolor="#C0C0C0">
    <div class="content"> <!-- Inicio da DIV content -->
        <h3 class="singular">Paciente Detalhe</h3>
        <div>
            <form name="form_faturar" id="form_faturar" action="<?= base_url() ?>ambulatorio/exame/gravarpacientedetalhes" method="post">
                <fieldset>
                    <? //echo'<pre>'; var_dump($guia[0]);die;?>
                    <dl class="dl_desconto_lista">
                        <dt>
                        <label>Nome</label>
                        </dt>
                        <dd>
                            <input type="text" name="paciente" id="paciente" class="texto02" style="width: 400px;" value="<?= $guia[0]->paciente; ?>" readonly/>
                            <input type="hidden" name="agenda_exames_id" id="agenda_exames_id" class="texto01" value="<?= $agenda_exames_id; ?>"/>
                            <input type="hidden" name="guia_id" id="guia_id" class="texto01" value="<?= $guia_id; ?>"/>
                            <input type="hidden" name="paciente_id" id="paciente_id" class="texto01" value="<?= $paciente_id; ?>"/>
                            <input type="hidden" name="procedimento_tuss_id" id="procedimento_tuss_id" class="texto01" value="<?= $procedimento_tuss_id; ?>"/>
                        </dd>
                        <dt>
                        <label>Prontuário/ID</label>
                        </dt>
                        <dd>
                            <input type="text" name="paciente_id" id="paciente_id" class="texto01" value="<?= $paciente_id; ?>" readonly/>
                        </dd>
                        <dt>
                        <label>Sexo</label>
                        </dt>
                        <dd>
                            <input type="text" style="width: 400px;"  name="nome_mae" id="nome_mae" class="texto01" value="<?= $guia[0]->sexo; ?>" readonly/>
                        </dd>
                        <dt>
                        <label>Nome da Mãe</label>
                        </dt>
                        <dd>
                            <input type="text" style="width: 400px;"  name="nome_mae" id="nome_mae" class="texto01" value="<?= $guia[0]->nome_mae; ?>" readonly/>
                        </dd>
                        <dt>
                        <label>CPF</label>
                        </dt>
                        <dd>
                            <input type="text"   name="cpf" id="cpf" class="texto01" value="<?= $guia[0]->cpf; ?>" readonly/>
                        </dd>
                        <dt>
                        <label>RG</label>
                        </dt>
                        <dd>
                            <input type="text"  name="RG" id="RG" class="texto01" value="<?= $guia[0]->rg; ?>" readonly/>
                        </dd>
                        <dt>
                        <label>Endereço</label>
                        </dt>
                        <dd>
                            <input type="text" style="width: 400px;"  name="endereco" id="endereco" class="texto01" value="<?= $guia[0]->logradouro . ' - ' . $guia[0]->numero; ?>" readonly/>
                        </dd>
                        <dt>
                        <label>Telefone</label>
                        </dt>
                        <dd>
                            <input type="text" style="width: 400px;"  name="endereco" id="endereco" class="texto01" value="<?= $guia[0]->telefone . ' - ' . $guia[0]->celular; ?>" readonly/>
                        </dd>
                        <dt>
                        <label>Observações</label>
                        </dt>
                        <dd>
                            <textarea name="observacoes" id="observacoes" cols="40" rows="5" readonly>
                               <?=$guia[0]->observacoes?> 
                            </textarea>
                        </dd>
                        <dt>
                        <label>Convenio</label>
                        </dt>
                        <dd>
                            <input type="text" name="convenio" id="convenio" class="texto01" value="<?= $guia[0]->convenio; ?>" readonly/>
                        </dd>
                        <dt>
                        <label>Nascimento</label>
                        </dt>
                        <?$nascimento = substr($guia[0]->nascimento, 8, 2) . "/" . substr($guia[0]->nascimento, 5, 2) . "/" . substr($guia[0]->nascimento, 0, 4)?>
                        <dd>
                            <input type="text" name="nascimento" id="nascimento" class="texto01" value="<?= $nascimento; ?>" readonly/>
                        </dd>
                        <dt>
                        <dt>
                        <label>Medico Solic.</label>
                        </dt>
                        <dd>
                            <input type="text" name="medico" id="medico" class="texto01" value="<?= $guia[0]->medico; ?>" readonly/>
                        </dd>
                        <dt>
                        <label>Operador Respons&aacute;vel</label>
                        </dt>
                        <dd>
                            <input type="text" name="operador" id="operador" class="texto01" value="<?= $guia[0]->operadorresp; ?>" readonly/>
                        </dd>
                        <dt>
                        <label>Peso</label>
                        </dt>
                        <dd>
                            <input type="text" name="peso" id="peso" class="texto01" alt="decimal" value="<?= $guia[0]->peso; ?>"/>Kg
                        </dd>
                        <dt>
                        <label>Altura</label>
                        </dt>
                        <dd>
                            <input type="text" name="altura" id="altura" class="texto01" alt="integer" value="<?= $guia[0]->altura; ?>"/>Cm
                        </dd>
                        <dt>
                        <label>P.A. sist&oacute;lica</label>
                        </dt>
                        <dd>
                            <input type="text" name="pasistolica" id="pasistolica" class="texto01" alt="999" value="<?= $guia[0]->pasistolica; ?>"/>
                        </dd>
                        <dt>
                        <label>P.A. diast&oacute;lica</label>
                        </dt>
                        <dd>
                            <input type="text" name="padiastolica" id="padiastolica" class="texto01" alt="99" value="<?= $guia[0]->padiastolica; ?>"/>
                        </dd>

                        <dt>
                        <label>Pulso</label>
                        </dt>
                        <dd>
                            <input type="text" name="pulso" id="pulso" class="texto01" value="<?= $guia[0]->pulso; ?>"/>Bpm
                        </dd>
                        <dt>
                        <label>Temperatura</label>
                        </dt>
                        <dd>
                            <input type="text" name="temperatura" id="temperatura" class="texto01" value="<?= $guia[0]->temperatura; ?>"/>ºC
                        </dd>
                        <dt>
                        <label>Pressao Arterial</label>
                        </dt>
                        <dd>
                            <input type="text" name="pressao_arterial" id="pressao_arterial" class="texto01" value="<?= $guia[0]->pressao_arterial; ?>"/>mm/Hg
                        </dd>
                        <dt>
                        <label>F. Respiratoria</label>
                        </dt>
                        <dd>
                            <input type="text" name="f_respiratoria" id="f_respiratoria" class="texto01" value="<?= $guia[0]->f_respiratoria; ?>"/>Rpm
                        </dd>
                        <dt>
                        <label>SPO2</label>
                        </dt>
                        <dd>
                            <input type="text" name="spo2" id="spo2" class="texto01" alt="99" value="<?= $guia[0]->spo2; ?>"/>%
                        </dd>
                        <dt>
                        <label>Medicação</label>
                        </dt>
                        <dd>
                            <select name="medicacao">
                                <option value=""></option>
                                <option value='f'<?
                                        if ($guia[0]->medicacao == 'f'):echo 'selected';
                                        endif;
                                        ?> >Não</option>
                                        <option value='t' <?
                                        if ($guia[0]->medicacao == 't'):echo 'selected';
                                        endif;
                                        ?> >Sim</option>
                            </select>
                        </dd>
                    </dl>    
 <table border="1">
        <thead>
            <tr>
                <th class="tabela_header">Procedimento</th>
                <th class="tabela_header">status</th>
                <th class="tabela_header">Operador Respons&aacute;vel</th>
                <th class="tabela_header">Operador Respons&aacute;vel - Ultima atualização</th>
                <th class="tabela_header">Data/Hora - Ultima atualização</th>
            </tr>
        </thead>
        <tbody>
            <?
            foreach ($exames as $item) :
            ?>
            <tr>
                <td width="400px;"><?= $item->procedimento ?></td>
                <td width="150px;"><?= $item->situacaolaudo ?></td>
                <td width="400px;"><?= $item->operadorresp ?></td>
                <td width="400px;"><?= $item->operador_atualizacao ?></td>
                <td width="400px;"><?= date('d/m/Y H:i:s',  strtotime($item->data_atualizacao))?></td>
            </tr>
            <? endforeach; ?>
        </tbody>
 </table>
                    <hr/>
                    <button type="submit" name="btnEnviar" >Enviar</button>
            </form>
            </fieldset>
        </div>
    </div> <!-- Final da DIV content -->
</body>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-meiomask.js" ></script>
        <script type="text/javascript">
            (function($) {
                $(function() {
                    $('input:text').setMask();
                });
            })(jQuery);

        </script>