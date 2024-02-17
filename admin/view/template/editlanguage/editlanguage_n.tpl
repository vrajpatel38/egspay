<?php echo $header; ?>
<link href="https://use.fontawesome.com/releases/v5.0.7/css/all.css" rel="stylesheet">
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <div class="box">
        <div class="heading">
            <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
            <div class="buttons">
                <a onclick="$('#form-module').submit();" class="button"><?php echo $button_save; ?></a>
                <a href="<?php echo $cancel; ?>" class="button"><?php echo $text_cancel; ?></a>
            </div>
        </div>
        <div>
            <?php if ($error_warning) { ?>
            <div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i><?php echo $error_warning; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php } ?>
        </div>
        <br>
        <div class="header">
                <?php echo $text_edit; ?>
        </div>
        <div class="container-fluid">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-module">
                <div>
                    <label class="status-label" for="input-status"><?php echo $entry_status; ?></label>
                    <div>
                        <select name="module_editlanguage_status" id="input-status" class="status-block">
                        <?php if ($module_editlanguage_status) { ?>
                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                        <option value="0"><?php echo $text_disabled; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_enabled; ?></option>
                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                        <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="row" id="lang-content" name="module-lang">
                    <div>
                        <h4><?php echo $text_language; ?></h4>
                        <div>
                            <select class="status-block" name="module_lang">
                                <?php foreach($directory as $_key => $dir) { ?> 
                                <option value="<?php echo $dir['name']; ?>"><?php echo $dir['name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <br>
                </div>
            </form>
            <div class="twig">
                <?php echo $text_twig; ?>
            </div>
            <div class="main-path">
                <div class="paths">
                    <div><?php echo $text_language_file; ?>
                        <button type="button" id="mybtn-folder" name="" data-target="#modal-create-folder" onclick="folder();"><i class="fa fa-folder fa-fw pull-left icon_path"></i></button>
                        <button type="button" id="mybtn-file" data-toggle="modal" data-target="#modal-create-file" onclick="file();"><i class="fa fa-file fa-fw pull-left icon_path"></i></button>
                    </div>
                    <br>
                    <div class="in_path">
                        <div id="path">
                        </div>
                    </div>
                </div>
                <div class="w_path">
                    <div id="code">
                        <ul class="nav nav-tabs file_nav"></ul>  
                        <div class="tab-content"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Trigger/Open The Modal -->

<!-- The Modal -->
<div id="modal-create-folder" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">Create Directory
      <span class="close">&times;</span>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label class="label">Name</label>
            <input type="text" name="directory_name" class="form-control" />
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary btn-save-create-directory">Save</button>
            <button type="button" class="btn btn-secondary close_modal" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div id="modal-create-file" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">Create file
      <span class="close">&times;</span>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label class="label">Name</label>
            <input type="text" name="create_file" class="form-control" />
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary btn-save-create-file">Save</button>
            <button type="button" class="btn btn-secondary close_modal" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
    var current_directory = '';
    var current_file = '';
    $('.change-lang').click(function(e){
        e.preventDefault();
        $('#lang-content').toggle();
    });
    function getlist(){
        var str = '';
        if(current_directory){
            str = '&path='+current_directory;
        }
        if(current_file){
            str = '&path='+current_file;    
        }
        $.ajax({
            url: 'index.php?route=<?php echo $route ?>/getlist&<?php echo $token_str; ?>&module_lang=' + $('select[name="module_lang"]').val() + str,
            dataType: 'json',
            beforeSend: function() {
                $('select[name="module_lang"]').prop('disabled', true);
            },
            complete: function() {
                $('select[name="module_lang"]').prop('disabled', false);
            },
            success: function(json) {
                html = '';
                if (json['missing_file']) {
                    for (i = 0; i < json['missing_file'].length; i++){ 
                        html += '<a href="'+ json['missing_file'][i]['path'] +'" class="file" ><i class="fa fa-plus-square mis_file"></i>';
                        html += '<span data-toggle="tooltip" title="<?php echo $help_copy_file; ?>">&emsp;' + json['missing_file'][i]['name'] + ' </span>';
                        html += '<button data-file = "'+ json['missing_file'][i]['path'] +'" id="missed_file" class="btn btn-sm btn-primary"><i class="fa fa-copy"></i></button>';
                        html += '</a>';
                    }
                }
                if (json['directory']) {
                    for (i = 0; i < json['directory'].length; i++) {
                        html += '<a href="' + json['directory'][i]['path'] + '" class="directory" ><i class="fa fa-folder fa-fw pull-left"></i> &emsp;' + json['directory'][i]['name'] + ' </a><i class="icon_path"></i>';
                    }
                }
                if (json['file']) {
                    for (i = 0; i < json['file'].length; i++) {
                        html += '<a href="' + json['file'][i]['path'] + '"  class="file"><i class="fa fa-file fa-fw pull-left"></i> &emsp;' + json['file'][i]['name'] + ' </a><i class="icon_path"></i>';
                        
                    }
                }
                $('#path').html(html);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }  
    $('select[name="module_lang"]').on('change', function(e) {
        getlist();
    });

    $('select[name="module_lang"]').trigger('change');

    $('#path').on('click', 'a.directory', function(e) {
        e.preventDefault();

        var node = this;
        current_file = $(node).attr('href');
        current_directory = $(node).attr('href');

        $.ajax({
            url: 'index.php?route=<?php echo $route; ?>/getlist&<?php echo $token_str; ?>&module_lang=' + $('select[name="module_lang"]').val() + '&path=' + $(node).attr('href'),
            dataType: 'json',
            beforeSend: function() {
                $(node).find('.icon_path').after('<i class="fa fa-circle-o-notch fa-spin fa-fw pull-right loading"></i>');
            },
            complete: function() {
                $(node).find('.loading').remove();
            },
            success: function(json) {
                html = '';

                if (json['missing_file']) {
                    for (i = 0; i < json['missing_file'].length; i++){ 
                        html += '<a href="'+ json['missing_file'][i]['path'] +'" class="file" ><i class="fa fa-plus-square fa-fw pull-left" style="background: red;padding: 2px;color: #fff;"></i>';
                        html += '<span data-toggle="tooltip" title="<?php echo $help_copy_file; ?>">&emsp;' + json['missing_file'][i]['name'] + ' </span>';
                        html += '<button data-file = "'+ json['missing_file'][i]['path'] +'" id="missed_file" class="btn btn-sm btn-primary"><i class="fa fa-copy"></i></button>';
                        html += '</a>';
                    }
                }

                if (json['directory']) {
                    for (i = 0; i < json['directory'].length; i++) {
                        html += '<a href="' + json['directory'][i]['path'] + '" class="directory"><i class="fa fa-folder fa-fw pull-left"></i>&emsp;' + json['directory'][i]['name'] + '</a><i class="icon_path"></i>';
                    }
                }

                if (json['file']) {
                    for (i = 0; i < json['file'].length; i++) {
                        html += '<a href="' + json['file'][i]['path'] + '" class="file"><i class="fa fa-file fa-fw pull-left" ></i>&emsp;' + json['file'][i]['name'] + ' </a><i class="icon_path"></i>';
                    }
                }

                if (json['back']) {
                    html += '<a href="' + json['back']['path'] + '" class="directory back_directory">&emsp;' + json['back']['name'] + ' <i class="fa fa-arrow-circle-left fa-fw pull-right"></i></a>';
                }

                $('#path').html(html);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $('#path').on('click', 'a.file', function(e) {
        e.preventDefault();
        var node = $(this);
        var pos = $(node).attr('href').lastIndexOf('.');

        if (pos != -1) {
            var tab_id = $('select[name="module_lang"]').val() + '-' + $(node).attr('href').slice(0, pos).replace(/\//g, '-').replace(/_/g, '-');
        } else {
            var tab_id = $('select[name="module_lang"]').val() + '-' + $(node).attr('href').replace(/\//g, '-').replace(/_/g, '-');
        }

        if (!$('#tab-' + tab_id).length) {
            if ( node.data('requestRunning') ) {
                return;
            }

            node.data('requestRunning', true);

            $.ajax({
                url: 'index.php?route=<?php echo $route ?>/display_design&<?php echo $token_str; ?>&module_lang=' + $('select[name="module_lang"]').val() + '&path=' + $(node).attr('href'),
                dataType: 'json',
                beforeSend: function() {
                    $(node).find('.icon_path').after('<i class="fa fa-circle-o-notch fa-spin fa-fw pull-right loading"></i>');
                },
                complete: function() {
                    node.data('requestRunning', false);
                    $(node).find('.loading').remove();
                },
                success: function(json) {
                    if (json['code'] ) {

                        html = '';
                        $('#code').show();
                        $('#recent').hide();

                        $('.nav-tabs').append('<li>' + $('select[name="module_lang"]').val() + ' / '+ $(node).attr('href').split('/').join(' / ') + '&nbsp;&nbsp;<button class="button" data-toggle="tab"><i class="fa fa-minus-square"></li>');
                        html += '<div class="tab-pane" id="tab-' + tab_id + '">';
                        html += '<table class="table table-bordered table-responsive table-hover new-table">';
                        html += '<tr class="info">';
                        html +=     '<th class="text-center"> Variable </th>';
                        html +=     '<th class="text-center"> Value </th>';
                        html +=     '<th class="text-center"> Remove </th>';
                        html += '</tr>';

                        iterate(json['code'] , '');

                        html += '</table>'; 
                        html += '  <input type="hidden" name="module_lang" value="' + $('select[name="module_lang"]').val() + '" />';
                        html += '  <input type="hidden" name="path" value="' + $(node).attr('href') + '" />';
                        html += '  <br />';
                        html += '    <button class="add_element button"><i class="fa fa-plus-square"></i></button>';

                        html += '  <div class="pull-right">';
                        html += '    <button type="button" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"> <?php echo $button_save; ?></button>';
                        html += '    <button data-loading-text="<?php echo $text_loading; ?>" class="btn btn-danger reset-file"> <?php echo $button_reset; ?></button>';
                        html += '  </div>';
                        html += '</div>';

                        $('.tab-content').append(html);
                        $('.nav-tabs a[href=\'#tab-' + tab_id + '\']').show();
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        } else {
            $('.nav-tabs a[href=\'#tab-' + tab_id + '\']').show();
        }
    });

    $(document).on('click', function(e) {
        e.preventDefault();

        if ($(this).parent().parent().is('li.active')) {
            index = $(this).parent().parent().index();

            if (index == 0) {
                $(this).parent().parent().parent().find('li').eq(index + 1).find('a').tab('show');
            } else {
                $(this).parent().parent().parent().find('li').eq(index - 1).find('a').tab('show');
            }
        }

        $(this).parent().parent().remove();

        $($(this).parent().attr('href')).remove();

        if (!$('#code > ul > li').length) {
            $('#code').hide();
            $('#recent').show();
        }
    });

    $('.tab-content').on('click', '.btn-primary', function(e) {
        var node = this;

        var ittem_id = $('table tr td #module_file_code').map(function(){
            var t = $(this).text();
            if(t || t != ''){
                return $.trim(t);
            }
            var v = this.value;
            if(v || v != ''){
                return $.trim(v);
            }
        }).get();

        var input_val = $('table tr td textarea[name="module_file_valuecode"]').map(function(){
            var v = this.value;
            if(v || v != ''){
                return $.trim(v);
            }
        }).get();
        
        var label_val = JSON.stringify(ittem_id);
        var data_val = JSON.stringify(input_val);

        var file_name =  $('input[name="module_lang"]').val() + '/'+ $('input[name="path"]').val() ;

        $.ajax({
            url: 'index.php?route=<?php echo $route ?>/save&<?php echo $token_str; ?>&module_lang=' + $('input[name="module_lang"]').val() + '&path=' + $('input[name="path"]').val(),
            type: 'post',
            data: {'myData':data_val,'myLabel':label_val,'file_name':file_name},
            dataType: 'json',
            beforeSend: function() {
                $(node).button('loading');
            },
            complete: function() {
                $(node).button('reset');
            },
            success: function(json) {
                $('.alert-dismissible').remove();

                if (json['error']) {
                    $('#content > .container-fluid').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '  <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                }

                if (json['success']) {
                    $('#content > .container-fluid').prepend('<div class="alert alert-success alert-dismissible"><i class="fa fa-exclamation-circle"></i> ' + json['success'] + '  <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                }

                if (json['warning']) {
                    $('#content > .container-fluid').prepend('<div class="alert alert-warning alert-dismissible"><i class="fa fa-exclamation-circle"></i> ' + json['warning'] + '  <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                }

                $(document).scrollTop(0);

            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $('.tab-content').on('click', '.reset-file', function(e) {
        e.preventDefault();
        if (confirm('<?php echo $text_confirm; ?>')) {
            var node = this;

            $.ajax({
                url: 'index.php?route=<?php echo $route ?>/reset&<?php echo $token_str; ?>&module_lang=' + $('input[name="module_lang"]').val() + '&path=' + $('input[name="path"]').val(),
                dataType: 'json',
                beforeSend: function() {
                    $(node).button('loading');
                },
                complete: function() {
                    $(node).button('reset');
                },
                success: function(json) {
                    $('.alert-dismissible').remove();    
                    if (json['success']) {
                        if(json['code']){
                            $('table').remove();
                            html = '';
                            html += '<table class="table table-bordered table-responsive table-hover">';
                            html += '<tr class="info">';
                            html +=     '<th class="text-center"> Variable </th>';
                            html +=     '<th class="text-center"> Value </th>';
                            html +=     '<th class="text-center"> Remove </th>';
                            html += '</tr>';

                            iterate(json['code'] , '');

                            html += '</table>'; 
                            $('input[name="module_lang"]').before(html);
                        }
                        $('#content > .container-fluid').prepend('<div class="alert alert-success alert-dismissible"><i class="fa fa-exclamation-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                    } 

                    if (json['error']) {
                        $('#content > .container-fluid').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                    }

                    $(document).scrollTop(0);
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
            getlist();
        }
    });

    $('#path').on('click', 'button#missed_file', function(e) {
        e.preventDefault();
        var node = $(this);
        var pos = $(node).data('file').lastIndexOf('.');

        if (pos != -1) {
            var tab_id = $('select[name="module_lang"]').val() + '-' + $(node).data('file').slice(0, pos).replace(/\//g, '-').replace(/_/g, '-');
        } else {
            var tab_id = $('select[name="module_lang"]').val() + '-' + $(node).data('file').replace(/\//g, '-').replace(/_/g, '-');
        }

        if (!$('#tab' + tab_id).length) {
            if ( node.data('requestRunning') ) {
                return;
            }
            node.data('requestRunning', true);
            $.ajax({
                url: 'index.php?route=<?php echo $route ?>/copy_file&<?php echo $token_str; ?>&module_lang=' + $('select[name="module_lang"]').val() + '&path=' + $(node).data('file'),
                dataType: 'json',
                beforeSend: function() {
                }, 
                complete: function() {
                },
                success: function(json) {
                    if (json['code'] ) {

                        html = '';
                        $('#code').show();
                        $('#recent').hide();

                        $('.nav-tabs').append('<li><a href="#tab-' + tab_id + '" data-toggle="tab">' + $('select[name="module_lang"]').val() + ' / '+ $(node).data('file').split('/').join(' / ') + '&nbsp;&nbsp;<i class="fa fa-minus-square"></i></a></li>');

                        html += '<div class="tab-pane" id="tab-' + tab_id + '">';
                        html += '<table class="table table-bordered table-responsive table-hover">';
                        html += '<tr class="info">';
                        html += '<th class="text-center"> Variable </th>';
                        html += '<th class="text-center"> Value </th>';
                        html += '</tr>';

                        iterate(json['code'] , '');

                        html += '</table>'; 
                        html += '  <input type="hidden" name="module_lang" value="' + $('select[name="module_lang"]').val() + '" />';
                        html += '  <input type="hidden" name="path" value="' + $(node).data('file') + '" />';
                        html += '  <br />';
                        html += '    <button class="add_element"><i class="fa fa-plus-square"></i></button>';
                        html += '  <div class="pull-right">';
                        html += '    <button type="button" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"> <?php echo $button_save; ?></button>';
                        html += '    <button data-loading-text="<?php echo $text_loading; ?>" class="btn btn-danger reset-file"> <?php echo $button_reset; ?></button>';
                        html += '  </div>';
                        html += '</div>';

                        $('.tab-content').append(html);
                        $('.nav-tabs a[href=\'#tab-' + tab_id + '\']').show(); 
                    }
                    getlist();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        } else {
            $('.nav-tabs a[href=\'#tab-' + tab_id + '\']');
        }
    });

    html = "";
    function iterate(obj, stack = '') {
        for (var property in obj) {
            if (obj.hasOwnProperty(property)) {
                if (typeof obj[property] == "object") {
                    iterate(obj[property], stack + property + " - " );
                } else {
                    html += '<tr><td><label id="module_file_code"> '+ stack + property + '</label></td>';
                    html += '<td><textarea name="module_file_valuecode" class="form-control" id="lang_val" >'+ obj[property] +'</textarea></td>';
                    html += '<td class="btn-center"><button class="btn btn-info btn-sm remove_element btn-danger" name="remove_element"><i class="fa fa-minus-square"></i></button></td></tr>';
                }
            }
        }
        return html;
    }
                            
    $(document).delegate(".add_element" , "click" , function(e){
        e.preventDefault();
        html = '';
        html += '<tr id="add_new_element"><td><input class="form-control" id="module_file_code" type="text" id="lang_val"  value=""/></td>';
        html += '<td><textarea name="module_file_valuecode" class="form-control" id="lang_val"></textarea></td>';
        html += '<td><button class="remove_element btn-danger" name="remove_element"><i class="fa fa-minus-square"></i></button></td>';

        $('table').append(html);
    });
    $(document).delegate("table tr td .remove_element" , "click" , function(e){
        $(this).parent().parent().remove();
    });

    function folder(){
        $('#modal-create-folder').show();
    }
    $('#modal-create-folder .close').on('click', function(){
        $('#modal-create-folder').hide();
    });
    $('.close_modal').on('click', function(){
        $('#modal-create-folder').hide();
    });
    function file(){
        $('#modal-create-file').show();
    }
    $('#modal-create-file .close').on('click', function(){
        $('#modal-create-file').hide();
    });
    $('.close_modal').on('click', function(){
        $('#modal-create-file').hide();
    });


    $('.btn-save-create-directory').on('click', function(){
        $this = $(this);
        var name = $('#modal-create-folder [name="directory_name"]').val();    
        $('#modal-create-folder').find('.alert').remove();
        $.ajax({
            url:'index.php?route=<?php echo $route;?>/create_folder&<?php echo $token_str?>&module_lang=' + $('select[name="module_lang"]').val() + '&path=' + current_directory,
            dataType:'json',
            data:{
                name: name
            },
            beforeSend:function(){
                $this.button("loading");
            },
            complete:function(){
                $this.button("reset");
            },
            success:function(json){
                if(json.success){
                    $('#modal-create-folder').hide();
                    getlist();
                }else{
                    $('#modal-create-folder .modal-body').append('<div class="alert alert-danger">'+json.error+'</div>');
                }
            },
        });
    });
    $('.btn-save-create-file').on('click', function(){
        $this = $(this);
        var name = $('#modal-create-file [name="create_file"]').val();
        $('#modal-create-file').find('.alert').remove();
        $.ajax({
            url:'index.php?route=<?php echo $route;?>/create_file&<?php echo $token_str;?>&module_lang=' + $('select[name="module_lang"]').val() +'&path=' + current_file,
            type:'POST',
            dataType:'json',
            data:{
                name: name
            },
            beforeSend:function(){
                $this.button("loading");
            },
            complete:function(){
                $this.button("reset");
            },
            success:function(json){
                if(json.success){
                    $('#modal-create-file').hide();
                    getlist();
                }else{
                    $('#modal-create-file .modal-body').append('<div class="alert alert-danger">'+json.error+'</div>');
                }
            },
        });
    });
</script>
<style type="text/css">
    #missed_file{
        border-radius: unset;
        outline: none;
        float: right;
        margin: -5px;
    }
    textarea[name="module_file_valuecode"]{
        resize: vertical;
    }       
    .icon_path{
        font-size: 17px;
    }
    .back_directory , .back_directory:hover{
        background-color: #d9edf7 !important;
        border-color: #bce8f1 !important;
        color: #214c61 !important;
    }
    i.btn_yellow{
        text-size: 20px;
    }
    .main_content{
        padding-right: 30px;
    }
    i.fa-folder{
        color: #f3a638;
    }
    .warning{
        height: 2px;
    }
    .close{
        position: relative;
        top: -2px;
        right: -21px;
        color: inherit;
        overflow: auto;
    }
    .header{
        font-size: 16px;
    }
    .status-block{
        width: 100%;
        border-radius: 5px;
        height: 26px;
        padding: 5px;
    }
    button.close{
        padding: 0;
        cursor: pointer;
        background: transparent;
        border: 0;
    }
    .container-fluid{
      padding-right: 15px;
      padding-left: 15px;
      margin-right: auto;
      margin-left: auto;
    }
    @media (min-width: 768px) {
        .container-fluid {
            width: 750px;
        }
    }
    @media (min-width: 992px) {
        .container-fluid {
            width: 970px;
        }
    }
    @media (min-width: 1200px) {
        .container-fluid {
            width: 1170px;
        }
    }
    .container-fluid .col {
        background: lightblue;
    }
    .container-fluid .col p {
         padding: .25rem .75rem;
    }
    @media only screen and (min-width:600px) {
        .container-fluid .col {
            float: left;
            width: 50%;
        }
    }
    @media only screen and (min-width:768px) {
        .container-fluid .col {
            width: 33.333%;
        }
    }
    @media only screen and (min-width:992px) {
        .container-fluid .col {
            width: 25%;
        }
    }
    .in_path{
        width: 100%;
    }
    .in_path a{
        display: block;
        margin-bottom: 15px;
    }
    .paths{        
        width: 20%;
        border: 1px solid #767676;
        padding: 10px;
        margin-right: 0;
    }
    a{
        text-decoration: none;
    }
    .w_path{
        width: 80%;
        display: block;
        margin-left: 50px;
    }
    .ul-list {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }
    .main-path{
        width: 100%;
        display: flex;
        justify-content: space-between;
    }
    ul li {
      margin-top: -1px;
    }
    .new-table{
        width: 100%;
    }
    textarea {
        width: 95%;
    }
    .twig{
        margin: 10px;
        text-align: center;
    }
    #content{
        font-size: 14px;
    }
    .mis_file{
        background: red;
        color: #fff;
    }
    .icon_path {
        font-size: 13px;
    }
    .modal {
        display: none; 
        position: fixed; 
        z-index: 1; 
        padding-top: 100px; 
        left: 0;
        top: 0;
        width: 100%; 
        height: 100%; 
        overflow: auto;
        background-color: rgb(0,0,0); 
        background-color: rgba(0,0,0,0.4); 
    }
    .modal-content {
        background-color: #fefefe;
        margin: auto;
        padding: 20px;
        border: 1px solid #888;
        width: 30%;
    }

    /* The Close Button */
    .close {
        color: #aaaaaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }
    .modal-header {
        background-color: #fefefe;
        color: black;
    }
    .modal-body {padding: 2px 16px;}
    span.close{
        margin-right: 20px;
        top: -10px;
    }

    .form-control{
        display: block;
        width: 100%;
        height: 36px;
        padding: 8px 13px;
        font-size: 13px;
        line-height: 1.428571429;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 3px;
    }
    .label{
        display: inline-block;
        max-width: 100%;
        margin-bottom: 5px;
        font-weight: bold;
    }
    .btn-primary{
        color: #fff;
        background-color: #1e91cf;
        border-color: #197bb0;
    }
    .modal-footer{
        text-align: right;
        border-top: 1px solid #e5e5e5;
    }
    td.btn-center{
        text-align: center;
    }
</style>
<?php echo $footer; ?>

