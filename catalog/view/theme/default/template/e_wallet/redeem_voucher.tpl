<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php  foreach($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if($column_left && $column_right) { ?>
    <?php  $class = 'col-sm-6';  ?>
    <?php } elseif($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>    
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
      <div class="col-md-6 col-sm-3 col-sm-offset-3">
        <div class="panel panel panel-default">  
          <div class="panel-body">
            <?php if($error_warning) { ?>
              <div style="width:100%;" class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?php echo  $error_warning; ?></div>
            <?php } ?>
            <form id="re_voucher" action="<?php echo $redeem_voucher; ?>" method="post">
              <div class="form-group">
                <label class="control-label" for="input-amount"><?php echo $entry_voucher_code; ?></label>
                <input type="text" name="vouchar_code" value="" placeholder="Enter Vouchar Code" id="input-vouchar-code" class="form-control">
              </div>
              <div class="form-group">
                <input type="submit" value="Redeem" class="btn btn-primary">
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
        $("#re_voucher").submit(function(){
            var isFormValid = true;
            $("#re_voucher input").each(function(){
                if ($.trim($(this).val()).length == 0){
                    isFormValid = false;
                }
                else{
                }
            });
            if (isFormValid){
                return confirm('<?php echo $confirm_voucher_msg; ?>');
            }
        });
    });
</script>
<?php echo $footer; ?>