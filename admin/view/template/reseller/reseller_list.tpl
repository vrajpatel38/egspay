<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="button" data-toggle="tooltip" title="<?php echo $button_filter; ?>" onclick="$('#filter-customer').toggleClass('hidden-sm hidden-xs');" class="btn btn-default hidden-md hidden-lg"><i class="fa fa-filter"></i></button>
                <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-customer').submit() : false;"><i class="fa fa-trash-o"></i></button>
            </div>
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid"><?php if ($error_warning) { ?>
            <div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php } ?>
        <?php if ($success) { ?>
            <div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php } ?>
        <div class="row">
            <div id="filter-customer" class="col-md-12 col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-filter"></i> <?php echo $text_filter; ?></h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                                            <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="control-label" for="input-date-added"><?php echo $entry_date_added; ?></label>
                                            <div class="input-group date">
                                                <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="control-label" for="input-email"><?php echo $entry_email; ?></label>
                                            <input type="text" name="filter_email" value="<?php echo $filter_email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="control-label" for="input-customer-group"><?php echo $entry_whatsapp; ?></label>
                                            <input type="text" name="filter_whatsapp" value="<?php echo $filter_whatsapp; ?>" placeholder="<?php echo $entry_whatsapp; ?>" id="input-email" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="control-label" for="input-customer-group"><?php echo $entry_customer_group; ?></label>
                                            <select name="filter_customer_group" id="input-customer-group" class="form-control">
                                                <option value=""></option>
                                                <?php foreach ($customer_groups as $customer_group) { ?>
                                                    <?php if ($customer_group['customer_group_id'] == filter_customer_group) { ?>
                                                        <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                                                    <?php } else { ?>
                                                        <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                                            <select name="filter_status" id="input-status" class="form-control">
                                                <option value=""></option>
                                                <?php if ($filter_status == '1') { ?>
                                                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                                <?php } else { ?>
                                                    <option value="1"><?php echo $text_enabled; ?></option>
                                                <?php } ?>
                                                <?php if ($filter_status == '0') { ?>
                                                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                                <?php } else { ?>
                                                    <option value="0"><?php echo $text_disabled; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>

                             <div class="col-sm-12">
                                <div class="row">
                              
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="control-label"><?php echo $entry_zone; ?></label>
                                              <select name="filter_zone_id" class="form-control">
                                               <option value=""></option>
                                                <?php foreach ($zones as $zone) { ?>
                                                    <option <?php if ($filter_zone_id == $zone['zone_id'] ) { ?> selected <?php } ?> value=" <?php echo $zone['zone_id']; ?> "><?php echo $zone['name']; ?> </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                  
                                   
                                </div>
                            </div>
                            <div class="col-sm-12">
                       
                                <div class="form-group pull-right" style="margin-top: 23px;">
                                    <button type="button" id="button-filter" class="btn btn-info"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
                                </div>
                         
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
                    </div>
                    <div class="panel-body">
                        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-customer">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                                        <td class="text-left"><?php if ($sort == 'name') { ?><a href="<?php echo $sort_name; ?>" class="<?php echo $order|lower; ?>"><?php echo $column_name; ?></a><?php } else { ?><a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a><?php } ?></td>
                                        <td class="text-left"><?php if ($sort == 'c['email']') { ?><a href="<?php echo $sort_email; ?>" class="<?php echo $order|lower; ?>"><?php echo $column_email; ?></a><?php } else { ?><a href="<?php echo $sort_email; ?>"><?php echo $column_email; ?></a><?php } ?></td>
                                        <td class="text-left" style="width: 150px;"><?php if ($sort == 'c['customer_group_id']') { ?><a href="<?php echo $sort_customer_group; ?>" class="<?php echo $order|lower; ?>"><?php echo $column_customer_group; ?></a> <?php } else { ?> <a href="<?php echo $sort_customer_group; ?>"><?php echo $column_customer_group; ?></a><?php } ?></td>
                                        <td class="text-left"><?php echo $column_lampiran; ?></td>
                                        <td class="text-left"><?php if ($sort == 'c['status']') { ?><a href="<?php echo $sort_status; ?>" class="<?php echo $order|lower; ?>"><?php echo $column_status; ?></a><?php } else { ?><a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a><?php } ?></td>
                                        <td class="text-left" style="width: 100px;"><?php if ($sort == 'c['date_added']') { ?><a href="<?php echo $sort_date_added; ?>" class="<?php echo $order|lower; ?>"><?php echo $column_date_added; ?></a><?php } else { ?><a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a><?php } ?></td>
                                        <td class="text-right" style="width: 145px;"><?php echo $column_action; ?></td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if ($resellers) { ?>
                                        <?php foreach ($resellers as $reseller) { ?>
                                            <tr>
                                                <td class="text-center"><?php if ($reseller['customer_to_reseller_id'] in selected) { ?>
                                                        <input type="checkbox" name="selected[]" value="<?php echo $reseller['customer_to_reseller_id']; ?>" checked="checked" />
                                                    <?php } else { ?>
                                                        <input type="checkbox" name="selected[]" value="<?php echo $reseller['customer_to_reseller_id']; ?>" />
                                                    <?php } ?>
                                                </td>
                                                <td class="text-left"><?php echo $reseller['name']; ?></td>
                                                <td class="text-left"><?php echo $reseller['email']; ?></td>
                                                <td class="text-left"><?php echo $reseller['customer_group']; ?></td>
                                                <td class="text-left"><a href="<?php echo $reseller['lampiran']['href']; ?>"><?php echo $reseller['lampiran']['value']; ?></a></td>
                                                <td class="text-left"><?php echo $reseller['text_status']; ?></td>
                                                <td class="text-left"><?php echo $reseller['date_added']; ?></td>
                                                <td class="text-right">
                                                    <div style="min-width: 250px;">
                                                        <?php if ($reseller['status']) { ?>
                                                            <a href="<?php echo $reseller['enable']; ?>" class="btn btn-success btn-sm" data-toggle="tooltip" title="<?php echo $buttom_up; ?>" disabled="disabled"><i class="fa fa-thumbs-up"></i></a>
                                                            <a href="<?php echo $reseller['disable']; ?>" class="btn btn-danger btn-sm" data-toggle="tooltip" title="<?php echo $button_down; ?>"><i class="fa fa-thumbs-down"></i></a>
                                                        <?php } else { ?>
                                                            <a href="<?php echo $reseller['enable']; ?>" class="btn btn-success btn-sm" data-toggle="tooltip" title="<?php echo $buttom_up; ?>"><i class="fa fa-thumbs-up"></i></a>
                                                            <a href="<?php echo $reseller['disable']; ?>" class="btn btn-danger btn-sm" data-toggle="tooltip" title="<?php echo $button_down; ?>" disabled="disabled"><i class="fa fa-thumbs-down"></i></a>
                                                        <?php } ?>
                                                        <a target="_blank" class="btn btn-success btn-sm" href="<?php echo $reseller['send_wa']; ?>"><i class="fa fa-whatsapp"></i> <?php echo $button_send_wa; ?></a>
                                                        <a href="<?php echo $reseller['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
                                                        <a class="btn btn-danger btn-sm" onclick="confirm('<?php echo $text_confirm; ?>') ? location = '<?php echo $reseller['delete']; ?> ' : false;"><i class="fa fa-trash-o"></i></a>
                                                    </div>
                                                    <!--
                                                      <div class="btn-group" style="min-width: 65px;">
                                                        <a href="<?php // echo $reseller['edit']; ?>" data-toggle="tooltip" title="<?php // echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                                                      </div>
-->
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
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
        <!--
        $('.table-responsive').on('shown.bs.dropdown', function(e) {
            var t = $(this),
                    m = $(e.target).find('.dropdown-menu'),
                    tb = t.offset().top + t.height(),
                    mb = m.offset().top + m.outerHeight(true),
                    d = 20;
            if (t[0].scrollWidth > t.innerWidth()) {
                if (mb + d > tb) {
                    t.css('padding-bottom', ((mb + d) - tb));
                }
            } else {
                t.css('overflow', 'visible');
            }
        }).on('hidden.bs.dropdown', function() {
            $(this).css({ 'padding-bottom': '', 'overflow': '' });
        });
        //-->
    </script>
    <script type="text/javascript">
        <!--
        $('#button-filter').on('click', function() {
            url = 'index.php?route=reseller/reseller&token=<?php echo $token; ?>';
            var filter_name = $('input[name=\'filter_name\']').val();
            if (filter_name) {
                url += '&filter_name=' + encodeURIComponent(filter_name);
            }
            var filter_email = $('input[name=\'filter_email\']').val();
            if (filter_email) {
                url += '&filter_email=' + encodeURIComponent(filter_email);
            }

            var filter_whatsapp = $('input[name=\'filter_whatsapp\']').val();
            if (filter_whatsapp) {
                url += '&filter_whatsapp=' + encodeURIComponent(filter_whatsapp);
            }

            var filter_customer_group = $('select[name=\'filter_customer_group\']').val();
            if (filter_customer_group !== '') {
                url += '&filter_customer_group=' + encodeURIComponent(filter_customer_group);
            }
            var filter_status = $('select[name=\'filter_status\']').val();
            if (filter_status !== '') {
                url += '&filter_status=' + encodeURIComponent(filter_status);
            }

            var filter_zone_id = $('select[name=\'filter_zone_id\']').val();
            if (filter_zone_id !== '') {
                url += '&filter_zone_id=' + encodeURIComponent(filter_zone_id);
            }

            var filter_date_added = $('input[name=\'filter_date_added\']').val();
            if (filter_date_added) {
                url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
            }
            location = url;
        });
        //-->
    </script>
    <script type="text/javascript">
        <!--
        $('input[name=\'filter_name\']').autocomplete({
            'source': function(request, response) {
                $.ajax({
                    url: 'index.php?route=reseller/reseller/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
                    dataType: 'json',
                    success: function(json) {
                        response($.map(json, function(item) {
                            return {
                                label: item['name'],
                                value: item['customer_to_reseller_id']
                            }
                        }));
                    }
                });
            },
            'select': function(item) {
                $('input[name=\'filter_name\']').val(item['label']);
            }
        });
        $('input[name=\'filter_email\']').autocomplete({
            'source': function(request, response) {
                $.ajax({
                    url: 'index.php?route=reseller/reseller/autocomplete&token=<?php echo $token; ?>&filter_email=' + encodeURIComponent(request),
                    dataType: 'json',
                    success: function(json) {
                        response($.map(json, function(item) {
                            return {
                                label: item['email'],
                                value: item['customer_id']
                            }
                        }));
                    }
                });
            },
            'select': function(item) {
                $('input[name=\'filter_email\']').val(item['label']);
            }
        });

        $('input[name=\'filter_whatsapp\']').autocomplete({
            'source': function(request, response) {
                $.ajax({
                    url: 'index.php?route=reseller/reseller/autocomplete&token=<?php echo $token; ?>&filter_whatsapp=' + encodeURIComponent(request),
                    dataType: 'json',
                    success: function(json) {
                        response($.map(json, function(item) {
                            return {
                                label: item['telephone'],
                                value: item['customer_id']
                            }
                        }));
                    }
                });
            },
            'select': function(item) {
                $('input[name=\'filter_whatsapp\']').val(item['label']);
            }
        });
        //-->
    </script>
    <script type="text/javascript">
        <!--
        $('.date').datetimepicker({
            language: '<?php echo $datepicker; ?>',
            pickTime: false
        });
        //-->
    </script>
</div>
<?php echo $footer; ?>
