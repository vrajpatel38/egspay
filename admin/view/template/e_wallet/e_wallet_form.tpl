<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-option" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
            <?php  if($error) { ?>
            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i><?php echo $error; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php } ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-pencil"></i><?php echo $text_form; ?></h3>
                </div>
                <div class="panel-body">
                    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-option" class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-customer_id"><?php echo $entry_customer_id; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="customer" value="<?php echo $customer; ?>" id="input-customer" class="form-control" />
                                <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-amount"><?php echo $entry_amount; ?></label>
                            <div class="col-sm-10">
                                <input type="number" name="amount" value="<?php echo $amount;?>" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-description"><?php echo $entry_description; ?></label>
                            <div class="col-sm-10">
                                <textarea name="description" class="form-control"><?php echo $description; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-amount"><?php echo $n_customer; ?></label>
                            <div class="col-sm-10">
                                <input type="checkbox" name="notify_customer" class="form-control" />
                                 <small class="mute"><p><?php echo $notify_note; ?></p></small>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $('input[name=\'customer\']').autocomplete({
            'source': function(request, response) {
                $.ajax({
                    url: 'index.php?route=e_wallet/e_wallet/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                    dataType: 'json',     
                    success: function(json) {
                        response($.map(json, function(item) {
                            return {
                                label: item['name'] +' - '+item['email'],
                                value: item['customer_id']
                            }
                        }));
                    }
                });
            },
            'select': function(item) {
                $('input[name=\'customer\']').val(item['label']);
                $('input[name=\'customer_id\']').val(item['value']);
            } 
        });
    </script>
    <?php echo $footer; ?>