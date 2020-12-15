<html>
    <head>
        <title>Informações</title>
        <meta charset="utf-8">
        <style>
            body{
                background-color:silver;
            }
        </style>
    </head>
    <body>
        <table border=1 cellspacing=0 cellpadding=2 bordercolor="black">
            <?php
            foreach ($listas as $item) {
                $telefone = "(" . substr(@$item->telefone, 0, 2) . ")" . substr(@$item->telefone, 2, strlen(@$item->telefone) - 2);
                ?>
                <tr>
                    <td>Nome</td>
                    <td><?= $item->nome; ?></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><?= $item->email; ?></td>
                </tr>
                <tr>
                    <td>Telefone</td>
                    <td><?= $telefone; ?></td>
                </tr>
                <tr>
                    <td>Cidade</td>
                    <td><?= $item->municipio; ?></td>
                </tr>
                <tr>
                    <td>Crm</td>
                    <td><?= $item->crm; ?></td>
                </tr>
                <tr>
                    <td>Estado CRM</td>
                    <td><?= $item->crm_estado; ?></td>
                </tr>
                <tr>
                    <td>Instituição onde realizou Residência/aperfeiçoamento</td>
                    <td><?= $item->instituto_resid; ?></td>
                </tr>
                <tr>
                    <td>Título do especialista</td>
                    <td><?= $item->instituicao_especialista; ?></td>
                </tr>
                <tr>
                    <td>Área de Especialização</td>
                    <td><?= $item->area_especialista; ?></td>
                </tr>
                <tr>
                    <td>Instituição Subespecializacao</td>
                    <td><?= $item->instituicao_subespecializacao; ?></td>
                </tr>
                <tr>
                    <td>Área de Subespecializacao</td>
                    <td><?= $item->area_subesp; ?></td>
                </tr>
                <tr>
                    <td>Instituição Mestrado</td>
                    <td><?= $item->instituicao_mestrado; ?></td>
                </tr>
                <tr>
                    <td>Área de Mestrado</td>
                    <td><?= $item->area_mestrado; ?></td>
                </tr>
                <tr>
                    <td>Instituição Doutorado</td>
                    <td><?= $item->instituicao_doutorado; ?></td>
                </tr>
                <tr>
                    <td>Área de Doutorado</td>
                    <td><?= $item->area_doutorado; ?></td>
                </tr>
                <tr>
                    <td>Disponibilidade de atendimento</td>
                    <td><?= $item->disponibilidade_atendimento; ?></td>
                </tr>
                <tr>
                    <td>Outros</td>
                    <td><?= $item->outros; ?></td>
                </tr>
                <?
            }
            ?>
        </table>

        <h3>Vizualizar Arquivos</h3>
        <div>
            <table>                  
                <tr>                       
                    <?
                    $arquivo_pasta = directory_map("./upload/curriculumprecadastro/$pacientes_precadastro_id");
                    if ($arquivo_pasta != false) {
                        sort($arquivo_pasta);
                    }
                    $i = 0;
                    if ($arquivo_pasta != false) {
                        foreach ($arquivo_pasta as $value) {
                            $i++;
                            ?> 
                            <td width="10px"><img  width="50px" height="50px" onclick="javascript:window.open('<?= base_url() . "upload/curriculumprecadastro/" . $pacientes_precadastro_id . "/" . $value ?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=1200,height=600');" src="<?= base_url() . "img/paste_on.gif" ?>"><br><? echo substr($value, 0, 10) ?><br> </td>
                            <?
                            if ($i == 8) {
                                ?>
                                <!--</tr><tr>-->
                                <?
                            }
                        }
                    }
                    ?>
                </tr>                    
            </table>
        </div> <!-- Final da DIV content -->
    </body>
</html>
