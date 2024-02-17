<?php echo $header; ?>
<style type="text/css">
      @media only screen and (max-width: 489px) {
          .input-group-btn>.btn_list {
          position: relative !important;
          margin: 5px 5px 5px 5px !important;
          display: block !important;
          width: 45% !important;
        }
        .add_money{
         margin-top: 5px;
        }

      }
      @media only screen and (max-width: 315px){
        #pad-auto{
          padding: 0px;
        }
      }
    </style>
<!-- <link rel="stylesheet" type="text/css" href="view/javascript/bootstrap/css/bootstrap.minupdate.css?j2v=<?php echo JOURNAL_VERSION ?>"> -->
<div class="container">
  <ul class="breadcrumb">
    <?php  foreach($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?> }}"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if($success) { ?>
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i><span>&nbsp;&nbsp;<?php echo $success; ?></span></div>
 <?php } ?>
  <?php if($error) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i><span>&nbsp;&nbsp;<?php echo $error; ?></span></div>
  <?php } ?>
  <div class="alert alert-danger" style="display: none;"><i class="fa fa-exclamation-circle"></i><span></span></div>
  <div class="row"><?php echo $column_left; ?>
    <?php if($column_left && $column_right) { ?>
    <?php  $class = 'col-sm-6'; ?>
    <?php  } elseif($column_left || $column_right) { ?>
    <?php  $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php  $class = 'col-sm-12'; ?>    
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
      <div class="row">
          <div class="col-xs-12">
            <?php if($e_wallet_add_money) { ?>
            <div class="well">
              <form action="<?php echo $add_money; ?>" id="add-money-form" method="post">
                <div class="row">
                  <div class="col-sm-2" style="padding: 0;">
                   <div style="z-index: 10;position: relative;">
                      <a style="color: #666;"> 
                        <div style="float: left;line-height: 42px;">
                          <img src="<?php echo $e_wallet_icon_url; ?>" />
                        </div>
                        <div style="float: left;margin-left: 5px;line-height: 21px;">
                          <div style="font-size: 14px;"><?php echo $text_balance; ?>&nbsp;</div>
                          <div><b><span style="font-size: 17px;" ><?php echo $balance; ?></span></b></div>
                        </div>
                      </a>
                    </div>
                  </div>
                  <div class="col-sm-6">
                     <div class="input-group">
                        <?php if($symbol_left) { ?> <span class="input-group-addon"><?php echo $symbol_left; ?></span> <?php } ?>
                         <input type="number" name="amount" value="" placeholder="<?php echo $text_amount; ?>" id="input-amount" class="form-control">
                         <?php if($symbol_right) { ?> <span class="input-group-addon"><?php echo $symbol_right; ?></span><?php } ?>
                      </div>
                  </div>
                  <div class="col-sm-3">
                    <button type="submit" class="btn btn-primary pull-right add_money"><?php echo $text_add_money; ?></button>
                  </div>
                </div>
              </form>
              <div class="pp-form" style="display: none;" class="hide"></div>
            </div>
           <?php } ?>
          </div>
          <div class="col-xs-12" style="margin-left:-21px;">
            <span class="input-group-btn" id="pad-auto">
             <?php if($voucher_status) { ?>
                <a class="btn btn-primary pull-right btn_list" href="<?php echo $redeem_voucher; ?>"><?php echo $text_redeem_voucher; ?></a>
              <?php } ?>
              <?php  if($e_wallet_bank_detail) { ?>
                <a class="btn btn-primary pull-right btn_list" href id="add_bank_btn"><?php echo $text_add_bank; ?></a>&nbsp;
              <?php } ?>
              <?php  if($e_wallet_withdraw_requests) { ?>
                <a class="btn btn-primary pull-right btn_list" href="<?php echo $withdrawreq; ?>"><?php echo $text_withdrawreq; ?></a>&nbsp;
              <?php } ?>
              <?php if($e_wallet_send_money) { ?>
                <a class="btn btn-primary pull-right btn_list" href="<?php echo $send_money; ?>"><?php echo $text_send_money; ?></a>
              <?php } ?>
            </span>
           <br>
          </div>
        <div class="col-md-12" style="clear: both;padding-left:1px;">
            <form class="" action="<?php echo $formurl;?>" method="post">
              <div class="col-xs-4">
                <div class="form-group">
                  <label for="datefrom"><?php echo $entry_from_date; ?></label>
                  <input type="text" class="form-control date" value="<?php echo $datefrom;?>" name="datefrom" id="datefrom">
                </div>
              </div>
              <div class="col-xs-4">
                <div class="form-group">
                  <label for="dateto"><?php echo $entry_to; ?></label>
                  <input type="text" class="form-control date" value="<?php echo $dateto; ?>" name="dateto" id="dateto">
                </div>
              </div>
              <div class="col-xs-4" style="padding-top: 23px;">
                <button type="submit" class="btn btn-default"><?php echo $entry_generate; ?></button>
              </div>
            </form>
            <br>
          </div>
          <div class="col-md-12" style="clear: both;">
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead style="background: #eee;">
                  <tr>
                    <th><?php echo $text_date; ?></th>
                    <th><?php echo $text_desc; ?></th>
                    <th><?php echo $text_credit; ?></th>
                    <th><?php echo $text_debit; ?></th>
                    <th><?php echo $column_balance; ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php $debittotal = 0; 
                  $balancetotal = 0; 
                  $credittotal = $openningbalance; 
                  if($e_wallet_list) { 
                   $i = 0; 
                   $list = count($e_wallet_list);                  
                   foreach($e_wallet_list as $v) {  
                   $i++; 
                   $credittotal = $credittotal + $v['o_credit'];
                   $debittotal = $debittotal + $v['o_debit']; ?>
                        <tr>
                          <td><?php echo $v['date']; ?></td>
                          <td><?php echo $v['description']; ?></td>
                          <td><?php echo $v['credit'];?></td>
                          <td><?php echo $v['debit'];?></td>
                          <td><?php echo $v['balance'];?></td>
                        </tr>
                   <?php } ?>
                   <?php } ?>
                    <tr>
                      <td><?php echo date('d-m-Y h:i A',strtotime($datefrom.' -1 days')) ?></td>
                      <td>Opening Balance</td>
                      <td><?php echo $ccurrency->format($openningbalance,$config_currency) ?></td>
                      <td></td>
                      <td><?php echo $ccurrency->format($openningbalance,$config_currency) ?></td>
                    </tr>
                </tbody>
                <tfoot style="background: #eee;">
                  <tr>
                    <th colspan="2">Total</th>
                    <th><?php echo $ccurrency->format($credittotal,$config_currency) ?></th>
                    <th><?php echo $ccurrency->format($debittotal,$config_currency) ?></th>
                    <th><?php echo $ccurrency->format($credittotal - $debittotal,$config_currency) ?></th>
                  </tr>
                </tfoot>
              </table>
            </div>
            <div class="row">
              <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
              <div class="col-sm-6 text-right"><?php echo $results; ?></div>
            </div>
          </div>
        </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<div id="add_bank_modal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header text-center well">
        <h3><?php echo $entry_bank_detail; ?></h3>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-xs-12 well">
            <form class="form-horizontal" id="form-bank" action="<?php echo $add_bank; ?>" method="post">
              <?php foreach($bank_data as $_key => $bank) { ?> 
                  <?php if ($bank['status'] == 1) { ?>
                    <div class="form-group required">
                      <label class="col-sm-3 control-label" for="input-bank-name"><?php echo $bank['display_name']; ?></label>
                      <div class="col-sm-9">
                        <?php if ($bank['type'] == 'text') { ?>
                            <input type="text" name="bank[<?php echo $bank['key']; ?>]" value="<?php echo $bank['value']; ?>" placeholder="<?php echo $bank['display_name']; ?>" class="form-control">
                        <?php } else { ?>
                        <textarea name="bank[<?php echo $bank['key']; ?>]" placeholder="<?php echo $bank['display_name']; ?>" class="form-control"><?php echo $bank['value']; ?></textarea>
                        <?php } ?>
                      </div>
                    </div>
                  <?php } else { ?>
                  <div class="form-group">
                      <label class="col-sm-3 control-label" for="input-bank-name"><?php echo $bank['display_name']; ?></label>
                      <div class="col-sm-9">
                        <?php if ($bank['type'] == 'text') { ?>
                            <input type="text" name="bank[<?php echo $bank['key']; ?>]" value="<?php echo $bank['value']; ?>" placeholder="<?php echo $bank['display_name']; ?>" class="form-control">
                        <?php } else { ?>
                        <textarea name="bank[<?php echo $bank['key']; ?>]" placeholder="<?php echo $bank['display_name']; ?>" class="form-control"><?php echo $bank['value']; ?></textarea>
                        <?php } ?>
                      </div>
                    </div>
                  <?php } ?>
              <?php } ?>
            </form>
          </div>
        </div>
      </div>
      <div class="modal-footer well">
        <button type="button" class="btn btn-primary" id="bank_save_btn" data-dismiss="modal"><?php echo $entry_save; ?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $entry_close; ?></button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $('#add_bank_btn').click(function(){
    $('#add_bank_modal').modal('show');
    return false;
  });
  $('#bank_save_btn').click(function(){
    var _btn = $(this),
        url = $('#form-bank').attr('action'),
        data = $('#form-bank').serialize();
    $('.text-danger').remove();
    $.ajax({
      url:url,
      data:data,
      type:'post',
      dataType:'json',
      beforeSend:function(json){
       $(_btn).button('loading');
      },complete:function(){
        $(_btn).button('reset');
      },success:function(json){
         if (json['error']){
          for(key in json['error']) {
             $('[name="bank['+key+']"]').after("<div class='text-danger'>"+json['error'][key]+"</div>");
          }
        }else{
          $('#add_bank_modal').modal('hide');
          $(_btn).button('reset');
        }
        if (json['success']) {
        $('.breadcrumb').after('<div class="alert alert-success alert-dismissible">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
       }
      }
    });
    return false;
  });
  $('.date').datetimepicker({
    pickTime: false
  });
</script>
<?php echo $footer; ?>