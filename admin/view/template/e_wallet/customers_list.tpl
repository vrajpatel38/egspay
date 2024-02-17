<style type="text/css">
   @media (max-width: 768px) {
    .filter{
      margin-top:40px;
    }
  }
   @media (min-width: 768px) {
    .filter{
      margin-top:21px;
    }
  }
</style>
<?php echo $header; ?><?php echo  $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php  if($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
   <?php } ?>    
    <?php  if($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
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
            <form  action="<?php echo $formurl; ?>" method="post">
              <div class="col-md-2 col-xs-6">
                <label for="dateto"><?php echo $customer_email; ?>&nbsp;&nbsp;</label>
                <input type="text" class="form-control" value="<?php echo $email; ?>" name="email">
              </div>  
              <div class="col-md-2 col-xs-6">
                <button type="submit" class="btn btn-primary filter"><?php echo $entry_filter; ?></button>
              </div>
              <div class="col-md-6 col-xs-12" style="padding-top:16px;">
                <table class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th class="text-left"><h4><b><?php echo $entry_customer_balance; ?></b></h4></th>
                    <td class="text-left"><?php echo $totallabance_format; ?></td>
                  </tr>
                </thead>
              </table>
              </div>
            </form>
            <br>
          </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-customer">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-center">Id</td>
                  <td class="text-center"><?php echo $column_customer; ?></td>
                  <td class="text-center"><?php echo $column_email; ?></td>
                  <td class="text-right"><?php echo $column_bank; ?></td>
                  <td class="text-right"><?php echo $column_balance; ?></td>
                  <td class="text-right">Percentage (%)</td>
                </tr>
              </thead>
              <tbody>
               <?php  if($customers) { ?>
                <?php foreach($customers as $t) { ?>
                <tr>
                  <td class="text-left"><a href="<?php echo $t['c_link'];?>"><?php echo $t['customer_id']; ?></a></td>
                  <td class="text-left"><a href="<?php echo $t['c_link'];?>"><?php echo $t['customer']; ?></a></td>
                  <td class="text-left"><a href="<?php echo $t['c_link']; ?>"><?php echo $t['email']; ?></a></td>
                  <td class="text-right"><a href class="btn btn-success view_bank"><?php echo $text_view_bank; ?>
                    <div class="hide bank_detail">
                      <table class="table table-bordered table-hover">
                        <thead>
                          <tr><th class="text-center">Title</th><th class="text-center">Value</th></tr>
                        </thead>
                         <tbody>
                          <?php if(isset($t['data'])) { ?>
                            <?php foreach($t['data'] as $key => $value) { ?> 
                                <tr><th><?php echo $key; ?></th><td><?php echo $value; ?></td></tr>
                            <?php } ?>
                          <?php } ?>
                        </tbody>
                      </table>
                    </div>
                  </a></td>
                  <td class="text-right"><?php echo $t['balance']; ?></td>
                  <td class="text-right"><?php echo $t['per']; ?> %</td>
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
<div id="bank-modal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h3>Bank Detail</h3>
      </div>
      <div class="modal-body well">
        <div class="row"><div class="col-xs-8 col-xs-offset-2 content"></div></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $('.view_bank').click(function(){
    var html = $(this).find('.bank_detail').html();
    $('#bank-modal').find('.modal-body .content').html(html);
    $('#bank-modal').modal('show');
    return false;
  });
  $('.date').datetimepicker({
    pickTime: false
  });
</script>
<?php echo $footer; ?>