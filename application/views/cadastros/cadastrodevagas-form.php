<div class="panel-body">
    <div class="alert alert-primary"><b>Cadastro de Vagas</b></div>
  
 <script type="text/javascript" src="<?= base_url() ?>js/jquery.maskedinput.js"></script>
    <form action="<?=base_url()?>cadastros/pacientes/gravarcadastrovagas" method="POST">
    <div class="row">
        <input type="hidden" name="vaga_id" id="vaga_id" value="<?=$vagas_id?>">
        <!-- <div class="col-lg-3">
            <label for="vaganome">Vaga</label>
            <input type="hidden" name="vaga_id" value="<?=$vagas_id?>">
            <input type="text" name="vaganome" id="vaganome" class="form-control" value="<?=@$obj[0]->nome_vaga?>" required>
            <div class="invalid-feedback">
                    Preechar o seguinte campo!
            </div>
        </div>

        <div class="col-lg-2">
            <label for="tipovaga">Tipo</label>
            <input type="text" name="tipovaga" id="tipovaga" class="form-control" value="<?=@$obj[0]->tipo_vaga?>" required>
            <div class="invalid-feedback">
                Preechar o seguinte campo!
            </div>
        </div> -->
        <div class="col-lg-2">
            <label for="">Tipo da Vaga</label>
            <select id="tipodavaga" name="tipodavaga" id="" class="form-control" required>
                  <option value="">Selecione</option> 
                  <option value="1"  <?=(@$obj[0]->tipodavaga == 1)?'selected':''?>>Pactuada</option>
                  <option value="2" <?=(@$obj[0]->tipodavaga == 2)?'selected':''?>>Não-pactuada</option>
            </select>
        </div>
        <div class="col-lg-3" >
            <label for="">Convênio</label>
            <select name="convenio_id" id="convenio_id" class="form-control" required> 
                <option value="">Selecione</option>
                <?foreach($convenios as $item){?>
                    <option value="<?=$item->convenio_id?>" <?=(@$obj[0]->convenio_id == $item->convenio_id)? 'selected': ''?>><?=$item->nome?></option>
                <?}?>
            </select>
            <div class="invalid-feedback">
                    Selecione uma opção da lista!
            </div>
        </div>

        <div class="col-lg-3"    >
            <label for="">Instituição de Origem</label>
            <select name="instituicao_id" id="instituicao_id" class="form-control" required>
                  <option value="">Selecione</option>
            </select>
        </div>

        <div class="col-lg-3">
            <label for="">Disciplina</label>
            <select name="disciplina" id="" class="form-control" required>
                  <option value="">Selecione</option>
                  <?foreach($informacoes as $item){?>
                    <?if($item->tipo == 'disciplina'){?>
                        <option value="<?=$item->informacaovaga_id?>" <?=(@$obj[0]->disciplina == $item->informacaovaga_id)?'selected':''?>><?=$item->descricao?></option>
                    <?}?>
                  <?}?>
            </select>
        </div>

          <div class="col-lg-2">
            <label for="">Nível de Formação</label>
            <select name="formacao" id="" class="form-control" required>
                  <option value="">Selecione</option>
                  <?foreach($informacoes as $item){?>
                    <?if($item->tipo == 'escolaridade'){?>
                        <option value="<?=$item->informacaovaga_id?>" <?=(@$obj[0]->formacao == $item->informacaovaga_id)?'selected':''?>><?=$item->descricao?></option>
                    <?}?>
                  <?}?>
            </select>
        </div>

        <div class="col-lg-3">
            <label for="">Curso</label>
            <select name="curso" id="" class="form-control" required>
                  <option value="">Selecione</option>
                  <?foreach($informacoes as $item){?>
                    <?if($item->tipo == 'curso'){?>
                        <option value="<?=$item->informacaovaga_id?>" <?=(@$obj[0]->curso == $item->informacaovaga_id)?'selected':''?>><?=$item->descricao?></option>
                    <?}?>
                  <?}?>
            </select>
        </div>

        <div class="col-lg-2">
            <label for="">Tipo de Estágio</label>
            <select name="tipovaga"  class="form-control">
                <option value="">Selecione</option>
                <option value="Obrigatório" <?=@(isset($obj[0]->tipo_vaga) && $obj[0]->tipo_vaga == "Obrigatório") ? "selected": "";?> >Obrigatório</option>
                <option value="Não Obrigatório" <?=@(isset($obj[0]->tipo_vaga) && $obj[0]->tipo_vaga == "Não Obrigatório") ? "selected": "";?>  >Não Obrigatório</option>
            </select> 
        </div>

        <div class="col-lg-3">
            <label for="">Responsável na Origem</label>
            <select name="resporigem" id="" class="form-control" required>
                  <option value="">Selecione</option>
                  <?foreach($resporigem as $item){?>
                        <option value="<?=$item->responsavel_origem_id?>" <?=(@$obj[0]->responsavel_origem == $item->responsavel_origem_id)?'selected':''?>><?=$item->nome?></option>
                  <?}?>
            </select>
        </div>

        <div class="col-lg-3">
            <label for="">Responsável no IJF</label>
            <select name="respifj" id="" class="form-control" required>
                  <option value="">Selecione</option>
                  <?foreach($respijf as $item){?>
                        <option value="<?=$item->responsavel_ifj_id?>" <?=(@$obj[0]->responsavel_ijf == $item->responsavel_ifj_id)?'selected':''?>><?=$item->nome?></option>
                  <?}?>
            </select>
        </div>
    </div>

    <br>
      <div class="row"> 
          <div class="col-lg-12">
              <label for="">Objetivos do Estagio e Plano de Atividades</label>
              <textarea class="tinymce" name="objetivo"> <?=@$obj[0]->objetivo ?></textarea>
          </div>
      </div>

      <br>
      <div class="row"> 
          <div class="col-lg-12">
              <label for="">Distribuição da Carga Horaria</label>
                <div id="calendar"></div>
          </div>
      </div>
      <br>

      <div class="row">
           
            <div class="col-lg-3">
                <label for="">Valor Por Aluno</label>
                <input type="text" readonly class="form-control" id="valorconvenio">
            </div>

        <div class="col-lg-2"> 
            <label for="">Setor</label>
            <select name="setor" id="" class="form-control" required>
                  <option value="">Selecione</option>
                  <?foreach($informacoes as $item){?>
                    <?if($item->tipo == 'setor'){?>
                        <option value="<?=$item->informacaovaga_id?>" <?=(@$obj[0]->setor == $item->informacaovaga_id)?'selected':''?>><?=$item->descricao?></option>
                    <?}?>
                  <?}?>
            </select>
        </div>

      
           <div class="col-lg-2">
            <label for="">Data Início</label>
            <input type="text" name="data_inicio" class="form-control datas" value="<?=@(isset($obj[0]->data_inicio) && $obj[0]->data_inicio != "") ? date('d/m/Y',strtotime($obj[0]->data_inicio)) : "";?>" required>
            <div class="invalid-feedback">
                    Preechar o seguinte campo!
            </div>
        </div>
            <div class="col-lg-2">
            <label for="">Data Final</label>
            <input type="text" name="data_final" class="form-control datas" value="<?=@(isset($obj[0]->data_final) && $obj[0]->data_final != "") ? date('d/m/Y',strtotime($obj[0]->data_final)) : "";?>" required>
            <div class="invalid-feedback">
                    Preechar o seguinte campo!
            </div>
        </div>


        <div class="col-lg-2">
            <label for="">Qtde de Vagas</label>
            <input type="number" name="qtdvagas" id="qtdvagas" class="form-control" value="<?=@$obj[0]->qtde_vagas?>" required>
            <div class="invalid-feedback">
                    Preechar o seguinte campo!
            </div>
        </div>

        <? if($vagas_id > 0){?>
        <div class="col-lg-2">
            <label>Qtde Inicial</label>
            <input type="number" name="qtdinicial" class="form-control" value="<?=@$obj[0]->qtde_inicial?>" readonly>
        </div>
            <?}?>

        </div>

        <br>
        <button class="btn btn-outline-default btn-round btn-sm" type="submit">Enviar</button>
        <button class="btn btn-outline-default btn-round btn-sm" type="reset">Limpar</button>

        </form>
</div>

 <link href="<?= base_url() ?>bootstrap/vendor/confirm/jquery-confirm.min.css" rel="stylesheet">
 <script  src="<?= base_url() ?>bootstrap/vendor/confirm/jquery-confirm.min.js" type="text/javascript"></script>
  
<script>
    function ativarmascara(){ 
         $('#horario_inicial').mask("99:99");
         $('#horario_final').mask("99:99");
    }
   
     
//     if($('#tipodavaga').val() == "1"){
//        $('#convenio_id').attr('required', true);
//        $('#instituicao_id').attr('required', true);
//        $("#div_convenio").show();
//        $("#div_instituicao").show();
//     }else{
//        $('#convenio_id').attr('required', false);
//        $('#instituicao_id').attr('required', false);
//        $("#div_convenio").hide();
//        $("#div_instituicao").hide();
//     }
     
//    $(function () {
//          $('#tipodavaga').change(function () {
//              if($('#tipodavaga').val() == "1"){
//                  $('#convenio_id').attr('required', true);
//                  $('#instituicao_id').attr('required', true); 
//                  $("#div_convenio").show();
//                  $("#div_instituicao").show();
//              }else{
//                $('#convenio_id').attr('required', false);
//                $('#instituicao_id').attr('required', false);
//                $("#div_convenio").hide();
//                $("#div_instituicao").hide();
//              }
//             
//          });
//      });
 
    
    $(function () {
        if($('#convenio_id').val != ''){
            $.getJSON('<?= base_url() ?>autocomplete/listarinstituicaoconvenio', {convenio_id: $('#convenio_id').val()}, function (j) {
                      options = '<option value=""></option>';

                      instituicao_id = '<?=@$obj[0]->institu_id?>';
                      for (var c = 0; c < j.length; c++) {
                          if(instituicao_id == j[c].instituicao_id){
                            options += '<option value="' + j[c].instituicao_id + '" selected>' + j[c].nome +'</option>';
                          }else{
                            options += '<option value="' + j[c].instituicao_id + '">' + j[c].nome +'</option>';
                          }
                          
                      }
                      $('#instituicao_id option').remove();
                      $('#instituicao_id').append(options);
                      $('.carregando').hide();
                  });
        }else{
            $('#instituicao_id').html('<option value="">Selecione um Convenio</option>');
        }


        if($('#convenio_id').val != ''){
            $.getJSON('<?= base_url() ?>autocomplete/valorestagioporconvenio', {convenio_id: $('#convenio_id').val()}, function (j) {
                    $('#valorconvenio').val(j[0].valor_por_estagio);
                });
        }else{
            $('#valorconvenio').val(''); 
        }
    });

      $(function () {
          $('#convenio_id').change(function () {
              if ($(this).val()) {
                  $('.carregando').show();                                                  
                  $.getJSON('<?= base_url() ?>autocomplete/listarinstituicaoconvenio', {convenio_id: $('#convenio_id').val()}, function (j) {
                      options = '<option value=""></option>';

                      for (var c = 0; c < j.length; c++) {
                          options += '<option value="' + j[c].instituicao_id + '">' + j[c].nome +'</option>';
                      }
                      $('#instituicao_id option').remove();
                      $('#instituicao_id').append(options);
                      $('.carregando').hide();
                  });

              } else {
                  $('#instituicao_id').html('<option value="">Selecione um Convenio</option>');
              }

              if ($(this).val()) {
                $.getJSON('<?= base_url() ?>autocomplete/valorestagioporconvenio', {convenio_id: $('#convenio_id').val()}, function (j) {
                    $('#valorconvenio').val(j[0].valor_por_estagio);
                });
              }else{
                $('#valorconvenio').val('');
              }
          });
      });


tinymce.init({
  selector: 'textarea.tinymce',
  // width: 800,
  height: 350,
  plugins: [
      "advlist autolink autosave save link image lists charmap print preview hr anchor pagebreak",
      "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking help",
      "table directionality emoticons template textcolor paste fullpage colorpicker spellchecker"
  ],
  toolbar: "fontselect | fontsizeselect | bold italic underline strikethrough  | alignleft aligncenter alignright alignjustify | newdocument fullpage | styleselect formatselect | cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | media code | insertdatetime preview | forecolor backcolor | table |hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | visualchars visualblocks nonbreaking template pagebreak restoredraft help",
  menubar: false,
  language: 'pt_BR',
  forced_root_block : '',
  browser_spellcheck: true,
  contextmenu: false,

  setup: function (editor) {
      editor.on('init', function()
      {
      this.getDoc().body.style.fontSize = '12pt';
      this.getDoc().body.style.fontFamily = 'Arial';
      });
      editor.on('SetContent', function (e) {
      this.getDoc().body.style.fontSize = '12pt';
      this.getDoc().body.style.fontFamily = 'Arial';
      });
},
toolbar_items_size: 'small',
      style_formats: [{
      title: 'Bold text',
              inline: 'b'
      }, {
      title: 'Red text',
              inline: 'span',
              styles: {
              color: '#ff0000'
              }
      }, {
      title: 'Red header',
              block: 'h1',
              styles: {
              color: '#ff0000'
              }
      }, {
      title: 'Example 1',
              inline: 'span',
              classes: 'example1'
      }, {
      title: 'Example 2',
              inline: 'span',
              classes: 'example2'
      }, {
      title: 'Table styles'
      }, {
      title: 'Table row 1',
              selector: 'tr',
              classes: 'tablerow1'
      }],
      fontsize_formats: 'xx-small x-small 8pt 10pt 12pt 14pt 18pt 24pt 36pt 48pt',
      init_instance_callback: function () {
      window.setTimeout(function () {
      $("#div").show();
      }, 1000);
      }
});


document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');

  var calendar = new FullCalendar.Calendar(calendarEl, {
    locale: 'pt-br',
    selectable: true,
    editable: false,
    eventLimit: false,
    schedulerLicenseKey: 'CC-Attribution-Commercial-NoDerivatives',
    showNonCurrentDates: false,
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },
    // dateClick: function(info) {
    // //   alert('clicked ' + info.dateStr);
    // },
    select: function(info) {
//        console.log(info.view.type);
        
      var date = info.startStr.replace('-03:00', '');
     if($("#vaga_id").val() == 0){  
        $.alert('Erro, é preciso cadastro uma vaga primeiro.'); 
        return;
     }
     
      
//       console.log(date);

     $.confirm({
                title: 'Informar os Dados Adicionais!',
                boxWidth: '36%',
                useBootstrap: false,
                theme: 'modern',
                type: 'blue',
                typeAnimated: true,
//                icon: 'fas fa-briefcase-medical',
                content: '' +
            '<form action="" class="formName">' + 
            '<div class="row">'+
                '<div class="col-lg-5">' +
                '<label>Horario Inicial</label>' +
                '<input type="text" name="horario_inicial" id="horario_inicial" onclick="ativarmascara()"  placeholder="00:00" class="form-control" required />' +
                '</div>' +

                '<div class="col-lg-5">' +
                 '<label>Horario Final</label>' +
                '<input type="text" name="horario_final" id="horario_final"  onclick="ativarmascara()"  placeholder="00:00" class="form-control" required />' +
                '</div>' +  
            '</div>'+
            '<br><br>'+
            '</form>',
               buttons: {
                   confirm:{
                    text: 'Confirmar',
                    action: function () {  
                      var vaga_id = $("#vaga_id").val(); 
                      var date_marcada = info.startStr.replace('-03:00', '');
//                       $.alert('Confirmed!'); 
                       
                        $.ajax({
                            type: "POST",
                            data: {
                                vaga_id: vaga_id,
                                horario_inicial: $("#horario_inicial").val(),
                                horario_final: $("#horario_final").val(),
                                data:date_marcada
                            },
                            url: "<?= base_url() ?>cadastros/pacientes/salvarcargahorario/",
                            dataType: 'json',
                            success: function(data) {
                                $.alert('Horário salvo com Sucesso!');
                                var date = new Date(info.startStr + 'T00:00:00');
                                var dateend = new Date(info.endStr + 'T00:00:00');
                                console.log(data);
                                calendar.addEvent({
                                    title: 'Horário',
                                    description: '',
                                    start: date_marcada+'T'+$("#horario_inicial").val(),
                                    end: date_marcada+'T'+$("#horario_final").val(),
                                    allDay: false,
                                    resourceId: 1
                                });
 
                                return true;
                            },
                            error: function(data) {
                                $.alert('Erro ao gravar o Horário.');
                                return true;
                            }
                        });
                       
//                        if(info.view.type == 'dayGridMonth'){
//
//                            var date = new Date(info.startStr + 'T00:00:00');
//                            var dateend = new Date(info.endStr + 'T00:00:00');
//
//                            calendar.addEvent({
//                              title: 'Horario Trabalho',
//                              start: date,
//                              end: dateend,
//                              allDay: true
//                            });
//
//                        }else{
//                            var date = info.startStr.replace('-03:00', '');
//                            var dateend = info.endStr.replace('-03:00', '');
//
//                            calendar.addEvent({
//                              title: 'Horario Trabalho',
//                              start: date,
//                              end: dateend,
//                              allDay: false
//                            });
//                        }
                       
                   }},
                   cancel: {
                       text: 'Cancelar',
                         action: function () { 
                              $.alert('Cancelado!');
                       }
               }
//               ,
//                   somethingElse: {
//                       text: 'Something else',
//                       btnClass: 'btn-blue',
//                       keys: ['enter', 'shift'],
//                       action: function(){
//                          $.alert('Something else?');
//                       }
//                   }
               }
           }); 
           
       

//            alert('Evento adicionado com sucesso...');
    },
    eventSources: [
        {
            url: '<?= base_url() ?>cadastros/pacientes/listarhorarioscalendario',
            method: 'POST',
            extraParams: {
                 vaga_id: $("#vaga_id").val()
            },
            success: function (e){
//                console.log(e); 
            },
            error: function (e) {
//                alert('tt');
               console.log(e);
            }
        }

    ],
  });

  calendar.render();
});


</script>
