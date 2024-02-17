<style type="text/css">
  /* @media (max-width: 768px) {
    .filter{
      margin-top:35px;
    }
  }*/
   @media (min-width: 768px) {
    .filter{
      margin-top:35px;
    }
  }
  @media (max-width: 768px) {
    .filter{
      margin: 20px 0px 15px 280px;
    }
  }
</style>
<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="button" data-toggle="tooltip" title="Approve" class="btn btn-success" onclick="if(!$('.checkbox:checked').length){alert('Please Select Request..!'); return false;}$('#form-request').attr('action','<?php echo $approveurl; ?>');confirm('Are You Sure To Approve Selected ?') ? $('#form-request').submit() : false;"><i class="fa fa-check"></i></button>
        <button type="button" data-toggle="tooltip" title="Reject" class="btn btn-danger" onclick="if(!$('.checkbox:checked').length){alert('Please Select Request..!'); return false;}$('#form-request').attr('action','<?php echo $rejecturl;?>'); confirm('Are You Sure To Reject Selected ?') ? $('#form-request').submit() : false;"><i class="fa fa-close "></i></button>
        <!-- <button type="button" data-toggle="tooltip" title="{{ button_delete }}" class="btn btn-danger" onclick="confirm('{{ text_confirm }}') ? $('#form-request').submit() : false;"><i class="fa fa-trash-o"></i></button> -->
      </div>
      <h1><?php echo $heading_title;?></h1>
      <ul class="breadcrumb">
        <?php foreach($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php  if($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i><?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i><?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i><?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-md-12">
            <form class="" action="<?php echo $formurl; ?>" method="post">
              <div class="col-md-3 col-xs-6">
                <div class="form-group">
                  <label for="datefrom">From Date</label>
                  <input type="text" class="form-control date" value="<?php echo $datefrom; ?>" name="datefrom" id="datefrom">
                </div>
              </div>
              <div class="col-md-3 col-xs-6">
                <div class="form-group">
                  <label for="dateto"><?php echo $entry_to; ?></label>
                  <input type="text" class="form-control date" value="<?php echo $dateto; ?>" name="dateto" id="dateto">
                </div>
              </div>
              <div class="col-md-3 col-xs-6">
                <div class="form-group">
                  <label > &nbsp;  <?php echo $entry_email_phone; ?></label>
                  <input type="text" class="form-control" value="<?php echo $email; ?>" name="email">
                </div> &nbsp; 
              </div>
              <div class="col-md-2 col-xs-6">
                <div class="form-group">
                  <label> &nbsp; Status</label>
                  <select name="status" class="form-control">
                    <option value="" <?php echo (($status == '')? 'selected' : '') ?> > - </option>
                    <option value="0" <?php echo (($status == '0')? 'selected' : '') ?>><?php echo $status_pending; ?></option>
                    <option value="1" <?php echo (($status == '1')? 'selected' : '') ?>><?php echo $status_approve; ?></option>
                    <option value="2" <?php echo (($status == '2')? 'selected' : '') ?>><?php echo $status_reject; ?></option>
                  </select>
                </div> &nbsp; 
              </div>
              <div class="col-md-1 col-xs-6">
                <button type="submit" class="btn btn-primary filter" style=""><?php echo $entry_filter; ?></button>
              </div>
            </form>
            <br>
          </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-request" style="margin-top:7px;">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-right"><?php echo $column_customer; ?></td>
                  <td class="text-right"><?php echo $column_description; ?></td>
                  <td class="text-right"><?php echo $column_price; ?></td>
                  <td class="text-right">Status</td>
                  <td class="text-right"><?php echo $column_date; ?></td>
                </tr>
              </thead>
              <tbody>
                  <?php if($requests) { ?>
                  <?php  foreach($requests as $request) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($request['request_id'], $selected)) { ?>
                    <input type="checkbox" class="checkbox" name="selected[]" value="<?php echo $request['request_id'];?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" class="checkbox" name="selected[]" value="<?php echo $request['request_id'];?>" />
                    <?php } ?></td>
                  <td class="text-left"><a href="<?php echo $request['c_link']; ?>"><?php echo $request['customer']; ?></a></td>
                  <td class="text-left"><div class="dis_edit" id="<?php echo $request['request_id']; ?>"><?php echo $request['description'];?></div></td>
                  <td class="text-right"><?php echo $request['price']; ?></td>
                  <td class="text-right">
                 <?php if($request['status'] == 1) { ?>
                    <span style="color:green;"><?php echo $status_approve; ?></span>
                  <?php } elseif($request['status'] == 2) { ?>
                    <span style="color:red;"><?php echo $status_reject; ?></span>
                  <?php } else { ?>
                    <span style="color:#5bc0de;"><?php echo $status_pending; ?></span>
                  <?php } ?>
                  </td>
                  <td class="text-right"><?php echo $request['date']; ?></td>
                </tr>                
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
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
<input  type="hidden" class="dis_edit_url" value="{{ dis_edit }}">
<script type="text/javascript">
  $('.date').datetimepicker({
    pickTime: false
  });
  $('.dis_edit').dblclick(function(){
    if(!$(this).find('button').length){
      var id = $(this).attr('id');
      var text = $(this).text();
      html = "<form><textarea name='description' style='width: 80%;'>"+text+"</textarea><input type='hidden' name='request_id' value='"+id+"'><button class='update btn'>Save</button></form>";
      $(this).html(html);
    }
  });
  $('.dis_edit').delegate('.update','click',function(){
    var form = $(this).parents('form');
    var id = $(form).find("[name='request_id']").val();
    var text = $(form).find('textarea').val();
    var _t = $(this);
    $(this).button('loading')
    $.ajax({
      url:$('.dis_edit_url').val(),
      data:$(form).serialize(),
      type:'POST',
      success:function(){
        $(_t).button('reset');
        $('.dis_edit[id='+id+']').html(text);
      }
    });
    return false;
  });
</script>
<?php echo $footer; ?>