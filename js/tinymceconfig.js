// plugin pravítko
  tinymce.PluginManager.add('ruler', function(editor) {
			
    var domHtml;
    var lastPageBreaks;
    
    function refreshRuler()
    {
        try {
      domHtml = $( editor.getDoc().getElementsByTagName('HTML')[0] );
   
   // HACK - erase this, I have to put my CSS here
  //  console.log($('tinystyle').html() );
   domHtml.find('head').append( $('<style>'+$('tinystyle').html()+'</style>'));
    
        } catch (e) {
            return setTimeout(refreshRuler, 50);
        }
        
        var dpi = 96
        var cm = dpi/2.54;
        var a4px = cm * (31.5); // A4 height in px, -5.5 are my additional margins in my PDF print

        // ruler begins (in px)
        var startMargin = 4;
        
        // max size (in px) = document size + extra to be sure, idk, the height is too small for some reason
        var imgH = domHtml.height() + a4px*5;
        
        var pageBreakHeight = 10; // height of the pagebreak line in tinyMce
        
        var pageBreaks = [];
        domHtml.find('.mce-pagebreak').each(function(){
            pageBreaks[pageBreaks.length] = $(this).offset().top;
        });
        pageBreaks.sort();
        
        // if pageBreak is too close next page, then ignore it
        
        if (lastPageBreaks == pageBreaks) {
            return; // no change
        }
        lastPageBreaks = pageBreaks;
        
        // console.log("Redraw ruler");
        
        var s = '';
        s+= '<svg width="100%" height="'+imgH+'" xmlns="http://www.w3.org/2000/svg">';

        s+= '<style>';
        s+= '.pageNumber{font-weight:bold;font-size:19px;font-family:verdana;text-shadow:1px 1px 1px rgba(0,0,0,.6);}';
        s+= '</style>';
        
        var pages = Math.ceil(imgH/a4px);
        // console.log(pages);

        var i, j, curY = startMargin;
        for (i=0; i<pages; i++)
        {
            var blockH = a4px;
            
            var isPageBreak = 0;
            for (var j=0; j<pageBreaks.length; j++) {
                if (pageBreaks[j] < curY + blockH) {
                    // musime zmensit velikost stranky
                    blockH = pageBreaks[j] - curY;
                    // pagebreak prijde na konec stranky
                    isPageBreak = 1;
                    pageBreaks.splice(j, 1);
                }
            }
            
            s+= '<line x1="0" y1="'+curY+'" x2="100%" y2="'+curY+'" stroke-width="1" stroke="red"/>';
            
            // zacneme pravitko
            s+= '<pattern id="ruler'+i+'" x="0" y="'+curY+'" width="37.79527559055118" height="37.79527559055118" patternUnits="userSpaceOnUse">';
            s+= '<line x1="0" y1="0" x2="100%" y2="0" stroke-width="1" stroke="black"/>';
            s+= '</pattern>';
            s+= '<rect x="0" y="'+curY+'" width="100%" height="'+blockH+'" fill="url(#ruler'+i+')" />';
            
            // napiseme cislo strany
            s+= '<text x="10" y="'+(curY+19+5)+'" class="pageNumber" fill="#ffffff">'+(i+1)+'.</text>';
            
            curY+= blockH;
            if (isPageBreak) {
                //s+= '<rect x="0" y="'+curY+'" width="100%" height="'+pageBreakHeight+'" fill="#FFFFFF" />';
                curY+= pageBreakHeight;
            }
        }

        s+= '</svg>';
        
        domHtml.css('background-image', 'url("data:image/svg+xml;utf8,'+encodeURIComponent(s)+'")');
    }
    editor.on('NodeChange', refreshRuler);
    editor.on("init", refreshRuler);
    
});

var specialChars = [];
  

tinymce.init({
selector: 'textarea.tinymce',
height: 600,
setup: function (editor) {
/* An autocompleter that allows you to insert special characters */
editor.ui.registry.addAutocompleter('specialchars', {
        ch: '$',
        minChars: 0,
        columns: 1,
        fetch: function (pattern) {
        var matchedChars = specialChars.filter(function (char) {
            return char.text.indexOf(pattern) !== -1;
        });

        return new tinymce.util.Promise(function (resolve) {
            var results = matchedChars.map(function (char) {
            return {
                value: char.value,
                text: char.text,
                //icon: char.value
            }
            });
            resolve(results);
        });
        },
        onAction: function (autocompleteApi, rng, value) {
        editor.selection.setRng(rng);
        editor.insertContent(value);
        autocompleteApi.hide();
        }
    });
},
language: 'pt_BR',
plugins: 'ruler print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help quickbars',
imagetools_cors_hosts: ['picsum.photos'],
menubar: 'file edit view insert format tools table help',
toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak  | fullscreen  | insertfile image media template link anchor codesample | ltr rtl',
toolbar_sticky: true,
autosave_ask_before_unload: true,
autosave_interval: "30s",
autosave_prefix: "{path}{query}-{id}-",
autosave_restore_when_empty: false,
autosave_retention: "2m",
image_advtab: true,
content_css: '//www.tiny.cloud/css/codepen.min.css',
link_list: [
{ title: 'My page 1', value: 'http://www.tinymce.com' },
{ title: 'My page 2', value: 'http://www.moxiecode.com' }
],
image_list: [
{ title: 'My page 1', value: 'http://www.tinymce.com' },
{ title: 'My page 2', value: 'http://www.moxiecode.com' }
],
image_class_list: [
{ title: 'None', value: '' },
{ title: 'Some class', value: 'class-name' }
],
importcss_append: true,
file_picker_callback: function (callback, value, meta) {
/* Provide file and text for the link dialog */
if (meta.filetype === 'file') {
callback('https://www.google.com/logos/google.jpg', { text: 'My text' });
}

/* Provide image and alt text for the image dialog */
if (meta.filetype === 'image') {
callback('https://www.google.com/logos/google.jpg', { alt: 'My alt text' });
}

/* Provide alternative source and posted for the media dialog */
if (meta.filetype === 'media') {
callback('movie.mp4', { source2: 'alt.ogg', poster: 'https://www.google.com/logos/google.jpg' });
}
},
templates: [
    { title: 'New Table', description: 'creates a new table', content: '<div class="mceTmpl"><table width="98%%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>' },
    { title: 'Starting my story', description: 'A cure for writers block', content: 'Once upon a time...' },
    { title: 'New list with dates', description: 'New List with dates', content: '<div class="mceTmpl"><span class="cdate">cdate</span><br /><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>' }
],
    template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
    template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
    image_caption: true,
    quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
    noneditable_noneditable_class: "mceNonEditable",
    toolbar_mode: 'sliding',
    contextmenu: "link image imagetools table",
});
