<style type="text/css">
  @media (max-width: 768px) {
    .filter{
      margin-top: 20px;
    }
    .email{
      margin-bottom: 7px;
    }

  }
</style>
<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-transaction').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php  if($success) { ?>
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
          <div class="col-md-12" style="padding:10px;">
            <form class="form-inline" action="<?php echo $formurl; ?>" method="post">
                <div class="col-md-3 col-xs-6">
                 <label for="datefrom"><?php echo $entry_from_date; ?></label>
                 <input type="text" class="form-control date" value="<?php echo $datefrom; ?>" name="datefrom" id="datefrom">
                </div>
                <div class="col-md-3 col-xs-6">
                  <label for="dateto"><?php echo $entry_to; ?></label>
                  <input type="text" class="form-control date" value="<?php echo $dateto; ?>" name="dateto" id="dateto">
                </div>
                <div class="col-md-4 col-xs-6">
                  <label for="dateto"><?php echo $entry_email_phone; ?></label>
                  <input type="text" class="form-control email" value="<?php echo $email; ?>" name="email">
                </div>
                <div class="col-md-2 col-xs-6">
                  <button type="submit" class="btn btn-primary filter"><?php echo $entry_filter; ?></button>
                </div>
            </form>
            <br>
          </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-transaction">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>

                    <td class="text-left"><?php  if($sort == 'customer_name') { ?>
                      <a href="<?php echo $sort_customer_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_customer_name; ?></a>
                      <?php } else { ?>
                      <a href="<?php echo $sort_customer_name; ?>"><?php echo $column_customer_name; ?></a>
                      <?php } ?>
                    </td>
                    <td class="text-left"><?php if($sort == 'customer_email') { ?>
                    <a href="<?php echo $sort_customer_email; ?>" class="<?php echo strtolower($order); ?>"><?php echo  $column_customer; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_customer_email; ?>"><?php echo $column_customer; ?></a>
                    <?php } ?>
                  </td>
                  <td class="text-right"><?php echo $column_description; ?></td>
                  <td class="text-right"><?php echo $column_price; ?></td>
                  <td class="text-right"><?php echo $column_balance; ?></td>
                  <td class="text-right"><?php echo $column_date; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php $total = 0 ;
                if($transactions) { 
                foreach($transactions as $transaction) { ?>
                <?php  $total = $total + $transaction['o_price']; ?>                  
                <tr>
                  <td class="text-center"><?php if (in_array($transaction['transaction_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $transaction['transaction_id'];?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $transaction['transaction_id']; ?>" />
                    <?php } ?>
                  </td>
                  <td class="text-left"><a href="<?php echo $transaction['c_link']; ?>"><?php echo $transaction['customer_name']; ?></a></td>
                  <td class="text-left"><a href="<?php echo $transaction['c_link'];?>"><?php echo $transaction['customer']; ?></a></td>
                  <td class="text-left"><?php echo $transaction['description'];?></td>
                  <td class="text-right"><?php echo $transaction['price'];?></td>
                  <td class="text-right"><?php echo $transaction['balance'];?></td>
                  <td class="text-right"><?php echo $transaction['date'];?></td>
                </tr>
                 <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="100%"><?php echo $text_no_results; ?></td>
                </tr>
               <?php } ?>
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="4" class="text-right"><?php echo $entry_total; ?></th>
                  <th class="text-right"><?php echo $t_balance_format; ?></th>
                  <th colspan="2"></th>
                </tr>
              </tfoot>
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
<script type="text/javascript">
    $('.date').datetimepicker({
    pickTime: false
  });
</script>
<?php echo $footer; ?>