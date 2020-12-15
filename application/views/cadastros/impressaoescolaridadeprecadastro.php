<?php 
//echo "<pre>";
//print_r($lista);
?>


<html>
    <head>
        <title>Impressão</title>
        <meta charset="utf-8">
    </head>
    <body>
        <table border=1 cellspacing=0 cellpadding=2 width="100%">
            <tr>
                 <td>Crm</td>
                 <td><?= $lista[0]->crm; ?></td>
            </tr>
            <tr>
                 <td>Estado CRM</td>
                 <td><?= $lista[0]->crm_estado; ?></td>
            </tr>
            <tr>
                 <td>Instituição onde realizou Residência/Aperfeiçoamento</td>
                 <td><?= $lista[0]->instituto_resid; ?></td>
            </tr>     
            
            <tr>
                <td>Título do especialista</td>
                <td>Instituição: <?= $lista[0]->instituicao_especialista; ?><br>
                    Área : <?= $lista[0]->area_especialista; ?> </td>
            </tr>
            <tr>
                <td>Subespecialização</td>
                <td>Instituição: <?= $lista[0]->instituicao_subespecializacao; ?><br>
                    Área: <?= $lista[0]->area_subesp; ?></td>
            </tr>
            <tr>
                <td>Mestrado</td>
                <td>Instituição: <?= $lista[0]->instituicao_mestrado; ?><br>
                    Área: <?= $lista[0]->area_mestrado; ?></td>
            </tr>
            <tr>
                <td>Doutorado</td>
                <td>Instituição: <?= $lista[0]->instituicao_doutorado; ?><br>
                    Área: <?= $lista[0]->area_doutorado; ?></td>
            </tr>
            <tr>
                <td>Outros</td>
                <td><?= $lista[0]->outros; ?></td>
            </tr>
            
            
        </table>
        <?php $arquivo_pasta = directory_map("./upload/curriculumprecadastro/$pacientes_precadastro_id"); ?>
         <div>
            <table width="100%">
                <?  if ($arquivo_pasta != false) {
                ?>
                <tr>
                    <td>Arquivos importados </td>
                </tr>
                
                    <?php  }?>
                <tr>                       
                    <?
                    if ($arquivo_pasta != false) {
                        sort($arquivo_pasta);
                           echo "<h3>Arquivos importados</h3>";
                    }
                    $i = 0;
                    if ($arquivo_pasta != false) {
                        foreach ($arquivo_pasta as $value) {
                            $i++;
                            ?> 
                         <td width="10px"><a href="<?= base_url() . "upload/curriculumprecadastro/" . $pacientes_precadastro_id . "/" . $value ?>"><img  width="50px" height="50px" src="<?= base_url() . "img/paste_on.gif" ?>"><br><? echo substr($value, 0, 10) ?><br> </a></td>
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
