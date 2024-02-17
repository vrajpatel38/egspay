<?php echo $header; ?>
<style>
    .personal-data-text p {
        width: 100%;
        border-bottom: 2px solid #333;
        font-size: 20px;
        margin-bottom: 15px;
    }
    .product-data-text{
        margin-top: 35px;
    }
    .product-data-text p{
        width: 100%;
        border-bottom: 2px solid #333;
        font-size: 20px;
        margin-bottom: 15px;
    }
    .quote_option_json_data {
        font-size: 1.5rem;
    }
    .file-upload-data-text{
        width: 100%;
        border-bottom: 2px solid #333;
        font-size: 20px;
        margin-bottom: 15px;
    }
    .parent-div {
        display: inline-block;
        position: relative;
        overflow: hidden;
    }
    .parent-div input[type=file] {
        left: 0;
        top: 0;
        opacity: 0;
        position: absolute;
        font-size: 90px;
    }
    .learn-more-text p {
        width: 100%;
        border-bottom: 2px solid #333;
        font-size: 20px;
        margin-bottom: 70px;
        margin-top: 15px;
    }

    .comment-more-text p {
        width: 100%;
        border-bottom: 2px solid #333;
        font-size: 20px;
        margin-bottom: 20px;
        margin-top: 15px;
    }
    .comment-more-text textarea{
        margin-bottom: 15px;
    }
</style>
<div id="quote_product_page" class="container">
<h1 class="text-center"><?php echo $heading_title; ?></h1>
<div class="row">
    <div class="personal-data-text">
        <p><b><?php echo $text_personal_data; ?></b></p>
    </div>
</div>
<div class="ajax_responce_container"></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
  <div class="row">
     <div class="col-sm-3">
        <div class="form-group required">
            <label class="control-label" for="name"><?php echo $entry_name; ?></label>
            <input type="text" class="form-control" id="input-name" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>">
            <?php if ($error_name) { ?>
            <div class="text-danger"><?php echo $error_name; ?></div>
            <?php } ?>
        </div>
     </div>
     <div class="col-sm-3">
        <div class="form-group required">
            <label class="control-label" for="name"><?php echo $entry_city; ?></label>
            <input type="text" class="form-control" id="input-city" name="city" value="<?php echo $city; ?>" placeholder="<?php echo $entry_city; ?>">
            <?php if ($error_city) { ?>
            <div class="text-danger"><?php echo $error_city; ?></div>
            <?php } ?>
        </div>
     </div>
     <div class="col-sm-3">
        <div class="form-group required">
            <label class="control-label" for="name"><?php echo $entry_email; ?></label>
            <input type="text" class="form-control" id="input-email" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>">
            <?php if ($error_email) { ?>
            <div class="text-danger"><?php echo $error_email; ?></div>
            <?php } ?>
        </div>
     </div>
     <div class="col-sm-3">
        <div class="form-group required">
            <label class="control-label" for="name"><?php echo $entry_phone; ?></label>
            <input type="text" class="form-control" id="input-phone" name="phone" value="<?php echo $phone; ?>" placeholder="<?php echo $entry_phone; ?>">
            <?php if ($error_phone) { ?>
            <div class="text-danger"><?php echo $error_phone; ?></div>
            <?php } ?>
        </div>
     </div>
  </div>
<div class="row">
    <div class="product-data-text">
        <p><b><?php echo $text_product_data; ?></b></p>
    </div>
</div>
<?php foreach($product_data_info as $key => $product_data) { ?> 
<div class="row">
    <div class="col-sm-4">
        <?php if ($product_data['image']) { ?>
        <ul class="thumbnails">
            <?php if ($product_data['image']) { ?>
            <li><a class="thumbnail" href="<?php echo $product_data['href']; ?>" title="<?php echo $heading_title; ?>"><img src="<?php echo $product_data['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /><input type="hidden" name="product_image" value="<?php echo $product_data['image']; ?>"></a></li>
            <?php } ?>
        </ul>
        <?php } ?>
    </div>
    <div class="col-sm-8">
        <div>
            <div class="set_product_name" style="float:left;"><h2><?php echo $product_data['name']; ?></h2></div>
            <div class="delete btn" style="float:right;">
                <a href="<?php echo $delete; ?>" class="delete_product" data-product-id="<?php echo $product_data['product_id']; ?>"title="<?php echo $button_delete; ?>"><i class="fa fa-trash-o"></i></a>
            </div>
        </div>
        <div style="float:right;padding-top: 50px;">
            <?php foreach($product_data['quote_option_value_json'] as $key => $quote_key) { ?> 
                <?php foreach($quote_key as $key => $q_value) { ?> 
                 <span class="quote_option_json_data"><b><?php echo $q_value['name']; ?>:</b></span><span><?php echo $q_value['value']; ?></span><br/>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>
<?php } ?>
<div class="row">
    <div class="file-upload-data-text">
        <p><b><?php echo $text_file_upload_data; ?></b></p>
    </div>
</div>
<div class="row">
    <div class="col-sm-3 parent-div">
       <button type="button" id="button-upload" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary btn-block"><i class="fa fa-upload"></i> Upload a document</button>
      <input type="hidden" name="quote_option_doc" value="" id="input-file" />  
      <?php if ($error_file) { ?>
        <div class="text-danger"><?php echo $error_file; ?></div>
      <?php } ?>
 
    </div>
</div>
<div class="row">
    <div class="comment-more-text">
        <p><b><?php echo $text_learn_more_data; ?></b></p>
          <textarea class="form-control" id="input-comment" name="comment" value="<?php echo $comment; ?>" placeholder="<?php echo $entry_comment; ?>"></textarea> 
      <?php if ($error_comment) { ?>
        <div class="text-danger"><?php echo $error_comment; ?></div>
      <?php } ?>
 
    </div>
</div>
<div class="row">
    <div class="pull-right">
        <a href="<?php echo $cancel; ?>" class="btn btn-default"><?php echo $button_cancel; ?></a>
        <button type="submit" class="btn btn-primary"><?php echo $button_save; ?></button>
    </div>
</div>
</form>
</div>
<?php echo $footer; ?>
<script type="text/javascript">
    $('button[id^="button-upload"]').on('click', function() {
    var node = this;
    $('#form-upload').remove();
    $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file"  name="file" /></form>');
    $('#form-upload input[name=\'file\']').trigger('click');
    if (typeof timer != 'undefined') {
        clearInterval(timer);
    }
    timer = setInterval(function() {
       if ($('#form-upload input[name=\'file\']').val() != '') {
            clearInterval(timer);

            $.ajax({
                url: 'index.php?route=tool/upload',
                type: 'post',
                dataType: 'json',
                data: new FormData($('#form-upload')[0]),
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $(node).button('loading');
                },
                complete: function() {
                    $(node).button('reset');
                },
                success: function(json) {
                    $('.text-danger').remove();

                    if (json['error']) {
                        $(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
                    }

                    if (json['success']) {
                        alert(json['success']);

                        $(node).parent().find('input').val(json['code']);
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "rn" + xhr.statusText + "rn" + xhr.responseText);
                }
            });
        }
    }, 500);
});

$(document).delegate(".delete_product","click",function(e){
    // e.preventDefault();
        var product_id = $(this).attr('data-product-id');  
        $.ajax({
            url: 'index.php?route=product/quote_data_page/deleteproductajax',
            type:'POST',
            dataType:'json',
            data:{
                'product_id': product_id
            },
            success:function(json){
                if (json.success) {
                    var html = '<div class="alert alert-success" role="alert">' + json.success + '</div>';
                    jQuery(".ajax_responce_container").html(html); 
                }
                if (json.error) {
                    var html = '<div class="alert alert-danger" role="alert">' + json.error + '</div>';
                    jQuery(".ajax_responce_container").html(html);
                }
                // $('.quote_product_page').load('index.php?route=product/quote_data_page');
                window.location.href = 'index.php?route=product/quote_data_page';
                setTimeout(function() {
                    jQuery(".ajax_responce_container").html('');
                }, 3000);
            },
        })
    });
</script>