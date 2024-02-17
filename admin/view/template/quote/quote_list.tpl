<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-quote-data').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach($breadcrumbs as $_key => $breadcrumb) { ?> 
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-quote-data">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*='selected']').prop('checked', this.checked);" /></td>
                  <td class="text-left"><?php echo $column_name; ?></td>
                  <td class="text-right"><?php echo $column_city; ?></td>
                  <td class="text-right"><?php echo $column_email; ?></td>
                  <td class="text-right"><?php echo $column_phone; ?></td>
                  <td class="text-right"><?php echo $column_file; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if (isset($quote_data)) { ?>
                <?php foreach($quote_data as $_key => $quote_data) { ?> 
                <tr>
                  <td class="text-center"><?php if (in_array($quote_data['p_q_o_value_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $quote_data['p_q_o_value_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $quote_data['p_q_o_value_id']; ?>" />
                    <?php } ?>
</td>
                  <td class="text-left"><?php echo $quote_data['name']; ?></td>
                  <td class="text-right"><?php echo $quote_data['city']; ?></td>
                  <td class="text-right"><?php echo $quote_data['email']; ?></td>
                  <td class="text-right"><?php echo $quote_data['phone']; ?></td>
                  <td class="text-right"><a href="<?php echo $quote_data['href']; ?>"><?php echo $quote_data['file']; ?></a></td>
                   <td class="text-right"><a href="<?php echo $quote_data['info']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-primary"><i class="fa fa-eye"></i></a></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
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
<?php echo $footer; ?>