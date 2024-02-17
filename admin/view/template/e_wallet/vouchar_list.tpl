<style type="text/css">
   @media (max-width: 768px) {
    .filter{
      margin-top:21px;
    }
  }
</style>
<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="button" class="btn btn-primary add_vouchar_popup_btn" data-toggle="modal" data-target="#exampleModal">
            <i class="fa fa-plus"></i>
        </button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('{{ text_confirm }}') ? $('#form-vouchar').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
       <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
       <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="ajax_responce_container"></div>
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo  $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo  $text_list; ?></h3>
      </div>
      <div class="row">
          <div class="col-md-10" style="margin-top:15px;">
            <form class="form-inline" action="<?php echo $formurl; ?>" method="post">
              <div class="col-md-5 col-xs-6">
                <label for="dateto"> &nbsp; <?php echo $entry_voucher_code; ?> </label>
                 <input type="text" class="form-control" value="<?php echo $search_vouchar; ?>" name="search_vouchar">
              </div>  
              <div class="col-md-5 col-xs-6">
               <button type="submit" class="btn btn-primary filter"><?php echo $entry_filter; ?></button>
              </div>
            </form>
            <br>
          </div>
      </div>
      <div class="panel-body vouchar_body_container">
        <div class="vouchar_body">
            <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-vouchar">
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                      <td class="text-right"><?php echo $entry_voucher_name; ?></td>
                      <td class="text-right"><?php echo $entry_voucher_code_text; ?></td>
                      <td class="text-right"><?php echo $entry_voucher_amount; ?></td>
                      <td class="text-right"><?php echo $entry_voucher_limit; ?></td>
                      <td class="text-right"><?php echo $entry_total_used; ?></td>
                      <td class="text-right"><?php echo $entry_date_added; ?></td>
                      <td class="text-right"><?php echo $entry_status; ?></td>
                      <td class="text-right"><?php echo $entry_action; ?></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $total = 0; ?>
                    <?php if($vouchars) { ?>
                      <?php foreach ($vouchars as $vouchar) { ?>
                    <tr>
                        <td class="text-center"><?php if (in_array($vouchar['vouchar_id'], $selected)) { ?>
                            <input type="checkbox" name="selected[]" value="<?php echo $vouchar['vouchar_id']; ?>" checked="checked" />
                            <?php } else { ?>
                            <input type="checkbox" name="selected[]" value="<?php echo $vouchar['vouchar_id'];?>" />
                            <?php } ?>
                        </td>
                        <td class ="text-left"><?php  echo $vouchar['vouchar_name']; ?></td>
                        <td class ="text-left"><?php  echo $vouchar['vouchar_code']; ?></td>
                        <td class ="text-left"><?php  echo $vouchar['vouchar_amount']; ?></td>
                        <td class ="text-right"><?php echo $vouchar['user_limit']; ?></td>
                        <td class ="text-right"><?php echo $vouchar['total_used']; ?></td>
                        <td class ="text-right"><?php echo $vouchar['date_added']; ?></td>
                        <td class="text-right">
                            <?php if($vouchar['status'] == 1) { ?>
                                <span class="label label-success badge-pill" ><?php echo $entry_active; ?> </span>
                           <?php } else { ?>
                                <span class="label label-danger badge-pill"> <?php echo $entry_deactive; ?> </span>
                            <?php } ?>
                        </td>
                        <td class="text-right"> 
                            <span class="edit_vouchar btn btn-warning" 
                                data-vouchar-id="<?php echo $vouchar['vouchar_id']; ?>" 
                                data-vouchar-name="<?php echo $vouchar['vouchar_name']; ?>" 
                                data-vouchar-code="<?php echo $vouchar['vouchar_code']; ?>" 
                                data-vouchar-amount="<?php echo $vouchar['vouchar_amount']; ?>" 
                                data-user-limit="<?php echo $vouchar['user_limit']; ?>" 
                                data-vouchar-status="<?php echo $vouchar['status']; ?>" 
                                ><?php echo $entry_edit; ?></span> 
                            <span class="delete_vouchar btn btn-danger" data-vouchar-id="<?php echo $vouchar['vouchar_id'];?>" ><?php echo $entry_delete; ?></span> 
                        </td>
                    </tr>
                   <?php }  ?>
                    <?php } else { ?>
                    <tr>
                      <td class="text-center" colspan="8"><?php echo $entry_no_voucher_add; ?></td>
                    </tr>
                   <?php } ?>
                  </tbody>
                </table>
              </div>
            </form>
            <div class="row">
              <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
              <div class="col-sm-6 text-right"><?php echo $results; ?></div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
    $('.date').datetimepicker({
      pickTime: false
    });
</script>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="add_vouchar_popupLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="add_vouchar_popupLabel"><?php echo $entry_new_voucher_add; ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="add_vouchar_form" name="add_vouchar_form" method="POST">
            <input type="hidden" id="vouchar_id" name="vouchar_id" value="0">
            <div class="form-group">
                <label for="vouchar_name"><?php echo $entry_voucher_name; ?></label>
                <input name="vouchar_name" type="text" class="form-control" id="vouchar_name" placeholder="Vouchar Name">
            </div>
                
            <div class="form-group">
                <label for="vouchar_code"><?php echo $entry_voucher_code_text; ?></label>
                <input name="vouchar_code" type="text" class="form-control" id="vouchar_code" placeholder="Vouchar Code">
            </div>

            <div class="form-group">
                <label for="vouchar_amount"><?php echo $entry_voucher_amount; ?></label>
                <input name="vouchar_amount" type="number" class="form-control" id="vouchar_amount" placeholder="Vouchar Amount">
            </div>

            <div class="form-group">
                <label for="user_limit"><?php echo $entry_voucher_limit; ?></label>
                <input name="user_limit" type="number" class="form-control" id="user_limit" placeholder="voucher limit">
            </div>

            <div class="form-group">
                <label for="user_status"><?php echo $entry_status; ?></label>
                <select id="user_status" name="user_status" class="form-control">
                    <option value="1"><?php echo $entry_active; ?></option>
                    <option value="0"><?php echo $entry_deactive; ?></option>
                </select>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $entry_close; ?></button>
        <button type="button" class="btn btn-primary btn_add_vouchar"><?php echo $entry_voucher_add; ?></button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
    $(".btn_add_vouchar").on('click',function(){
        $.ajax({
            url: 'index.php?route=e_wallet/e_wallet/add_vouchar_ajax&token=' + getURLVar('token'),
            type:'POST',
            dataType:'json',
            data:$(".add_vouchar_form").serialize(),
            success:function(json){
                if (json.success) {
                    var html = '<div class="alert alert-success" role="alert">' + json.success + '</div>';
                    jQuery(".ajax_responce_container").html(html);

                    $('#exampleModal').modal('hide');
                }

                if (json.error) {
                    var html = '';

                    Object.keys(json.error).forEach(function(key) {
                        var value = json.error[key];
                        html += '<div class="alert alert-danger" role="alert">' + value + '</div>';
                    });
                    jQuery(".ajax_responce_container").html(html);

                    if (json.error.exist) {
                        $('#exampleModal').modal('hide');
                    }
                }
                $('.vouchar_body_container').load('index.php?route=e_wallet/e_wallet/vouchar_list&token=' + getURLVar('token') +' .vouchar_body_container .vouchar_body ');

                setTimeout(function() {
                    jQuery(".ajax_responce_container").html('');
                }, 3000);

                $("#add_vouchar_form").trigger("reset");

            },
        })
    });

    $(document).delegate(".edit_vouchar","click",function(){

        var vouchar_id = $(this).attr('data-vouchar-id');   
        var vouchar_name = $(this).attr('data-vouchar-name');
        var vouchar_code =  $(this).attr('data-vouchar-code');
        var vouchar_amount = $(this).attr('data-vouchar-amount');
        var user_limit = $(this).attr('data-user-limit');
        var vouchar_status = $(this).attr('data-vouchar-status');

        $("#vouchar_id").val(vouchar_id);
        $("#vouchar_name").val(vouchar_name);
        $("#vouchar_code").val(vouchar_code);
        $("#vouchar_amount").val(vouchar_amount);
        $("#user_limit").val(user_limit);
        $("#user_limit").val(user_limit);

        $(".btn_add_vouchar").html("Update Vouchar");

        $("#user_status").val(vouchar_status);

        $('#exampleModal').modal('show');
    });

    $('#exampleModal').on('hidden.bs.modal', function (e) {
        $(this).find("input,textarea,select").val('').end().find("input[type=checkbox], input[type=radio]")
           .prop("checked", "")
           .end();
        $(".btn_add_vouchar").html("Add Vouchar");
    });

    $(document).delegate(".delete_vouchar","click",function(){
        var vouchar_id = $(this).attr('data-vouchar-id');  
        $.ajax({
            url: 'index.php?route=e_wallet/e_wallet/deletevoucharajax&token=' + getURLVar('token'),
            type:'POST',
            dataType:'json',
            data:{
                'vouchar_id': vouchar_id
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

                $('.vouchar_body_container').load('index.php?route=e_wallet/e_wallet/vouchar_list&token=' + getURLVar('token') +' .vouchar_body_container .vouchar_body ');

                setTimeout(function() {
                    jQuery(".ajax_responce_container").html('');
                }, 3000);
            },
        })
    });

</script>
<?php echo $footer; ?>