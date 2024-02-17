<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php  foreach($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php }  ?>
  </ul>
  <?php if($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php  if($column_left && $column_right) { ?>
    <?php  $class = 'col-sm-6'; ?>
    <?php  } elseif($column_left || $column_right) { ?>
    <?php  $class = 'col-sm-9'; ?>
    <?php  }  else { ?>
    <?php  $class = 'col-sm-12'; ?>    
    <?php  } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
      <div class="col-sm-12">
        <span style="font-size: 17px;"><?php echo $text_balance; ?> <b> <?php echo $balance; ?></b></span>
      </div>
      <div class="col-md-6 col-sm-3 col-sm-offset-3">
        <div class="panel panel panel-default"> 
          <div class="panel-heading">&nbsp;</div>
          <div class="panel-body">
            <form id="send_money" action="<?php echo $send_money; ?>" method="post">
              <div class="form-group">
                <label class="control-label" for="input-email"><?php echo $text_email; ?></label>
                <input type="text" name="email" value="" placeholder="<?php echo $text_email; ?>" id="input-email" class="form-control">
              </div>
              <div class="form-group">
                <label class="control-label" for="input-amount"><?php echo $text_amount; ?></label>
                <div class="input-group">
                    <?php if($symbol_left) { ?> <span class="input-group-addon"><?php echo $symbol_left; ?></span> <?php } ?>
                    <input type="number" name="amount" value="" placeholder="<?php echo $text_amount; ?>" id="input-amount" class="form-control">
                    <?php if($symbol_right) { ?> <span class="input-group-addon"><?php echo $symbol_right; ?></span><?php } ?>
                </div>
              </div>
              <div class="form-group">
                <input type="submit" value="<?php echo $send; ?>" class="btn btn-primary">
              </div>
            </form>
          </div>
        </div>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    $("#send_money").submit(function(){
        var isFormValid = true;
        $("#send_money input").each(function(){
            if ($.trim($(this).val()).length == 0){
                isFormValid = false;
            }
            else{
            }
        });
        if (isFormValid){
            return confirm('<?php echo $confirm_send_msg; ?>');
        }
    });
  });
</script>
<?php echo $footer; ?>