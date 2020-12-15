
<div class="content ficha_ceatox"> <!-- Inicio da DIV content -->
    <h3>Configurações Cabeçalho e Rodapé das impressões</h3>
    <!--<div style="width: 100%">-->
        <form name="form_exametemp" id="form_exametemp" action="<?= base_url() ?>ambulatorio/empresa/gravarimpressaocabecalhoreceituario" method="post">
            <fieldset>
                <legend>Cabeçalho</legend>
                <textarea name="cabecalho" id=""><?= @$impressao[0]->cabecalho ?></textarea>
                <input type="hidden" id="empresa_id" name="empresa_id" value="<?= @$empresa_id ?>"/>
                <input type="hidden" id="impressao_id" name="impressao_id" value="<?= @$empresa_impressao_cabecalho_receituario_id ?>"/>
                Cabeçalho no R. especial: <input type="checkbox" id="receituario_especial" name="receituario_especial" <?= (@$impressao[0]->receituario_especial == "t") ? "checked":""; ?>>
            </fieldset>

            <fieldset>
                 <legend>Rodapé</legend>
                <textarea name="rodape" id=""><?= @$impressao[0]->rodape ?></textarea>
                <div style="width: 100%">
                    <hr/>
                    <button type="submit" name="btnEnviar">Enviar</button>
                    <button type="reset" name="btnLimpar">Limpar</button>
                </div>

            </fieldset>
            
            <fieldset>
                 <legend>Timbrado/Marca d'gua</legend>
                <textarea style="width: 100%;height: 800px;" name="timbrado" id=""><?= @$impressao[0]->timbrado ?></textarea>
                <div>
                    <p>
                        Obs: O tamanho da imagem do timbrado é padrão: 800px X 600px <br>
                        Obs²: O formato da imagem do timbrado importada deverá ser .png (Sendo possivel dessa forma, aplicar opacidade na imagem através de edição da mesma por softwares de terceiros)
                    </p>
                </div>
                <div style="width: 100%;">
                    <hr/>
                    <button type="submit" name="btnEnviar">Enviar</button>
                    <button type="reset" name="btnLimpar">Limpar</button>
                </div>

            </fieldset>
        </form>
      <fieldset>
            <legend>Opções do cabeçalho</legend>
             <table border="1">
                <thead>
                <tr class="tabela_header">
                    <th style="text-align: center;">
                        OPÇÕES DE CONFIGURAÇÃO DO CABEÇALHO
                    </th>
                </tr>
                 </thead>
                <tr class="tabela_content01">
                    <td style="text-align: justify;">
                        Aqui se encontram as opções que você pode estar utilizando na hora de montar seu padrão de cabeçalho.
                        Formate na caixa acima o texto do cabeçalho como quiser e posicione as opções de acordo com sua necessidade.
                    </td>
                </tr>
                <tr class="tabela_content01">
                    <td style="text-align: justify;">
                        Segue abaixo a lista com as opções disponíveis de dados.

                        (Copie os traços e a palavra como estão descritos abaixo. Ou seja, o nome do minicurriculum não é minicurriculum e sim _minicurriculum_ )

                    </td>
                </tr>
            </table>
            <br>
        <table>
           <tr class="tabela_header">
               <th >
                   Descrição
               </th>
               <th >
                   Como fazer
               </th>
           </tr>
            <tr class="tabela_content01">
               <td>
                 Mini curriculum, colocado no cadastro do profissional   ----------->
               </td>
               <td style="text-align: left;">
                   _minicurriculum_ 
               </td>
           </tr>
        </table>
            
     </fieldset> 
    <!--</div>  Final da DIV content -->
</div> <!-- Final da DIV content -->
<style>
    textarea{
        width: 90%;
        /*font-size: 18pt;*/
        /*height: 50pt;*/
    }
</style>
<link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">

    $(function () {
        $("#accordion").accordion();
    });

    tinyMCE.init({
        // General options
        mode: "textareas",
        theme: "advanced",
        plugins: "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave,visualblocks",
        // Theme options
        theme_advanced_buttons1: "save,newdocument,|,bold,italic,underline,pagebreak,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
        theme_advanced_buttons2: "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor,|,fullscreen",
        theme_advanced_toolbar_location: "top",
        theme_advanced_toolbar_align: "left",
        theme_advanced_statusbar_location: "bottom",
        theme_advanced_resizing: true,
        browser_spellcheck: true,
        // Example content CSS (should be your site CSS)
        //                                    content_css : "css/content.css",
        content_css: "js/tinymce/jscripts/tiny_mce/themes/advanced/skins/default/img/content.css",
        // Drop lists for link/image/media/template dialogs
        template_external_list_url: "lists/template_list.js",
        external_link_list_url: "lists/link_list.js",
        external_image_list_url: "lists/image_list.js",
        media_external_list_url: "lists/media_list.js",
        // Style formats
        style_formats: [
            {title: 'Bold text', inline: 'b'},
            {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
            {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
            {title: 'Example 1', inline: 'span', classes: 'example1'},
            {title: 'Example 2', inline: 'span', classes: 'example2'},
            {title: 'Table styles'},
            {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
        ],
        // Replace values for the template plugin
        template_replace_values: {
            username: "Some User",
            staffid: "991234"
        }

    });
</script>
