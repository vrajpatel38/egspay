<?php echo $header; ?> <?php echo $column_left; ?>
<div id="content">
<div class="container-fluid">
    <div class="row">
      <div class="col-md-4">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-user"></i>Customer Details</h3>
          </div>
          <table class="table">
            <tr>
              <td style="width: 1%;"><button data-toggle="tooltip" title="<?php echo $text_customer; ?>" class="btn btn-info btn-xs"><i class="fa fa-user fa-fw"></i></button></td>
              <td><?php if ($q_data) { ?>
               <a href="<?php echo $customer; ?>" target="_blank"><?php echo $q_data['name']; ?></a> <?php } else { ?>
                                <?php echo $name; ?> 
                              <?php } ?>
              </td>
            </tr>
            <tr>
              <td><button data-toggle="tooltip" title="<?php echo $text_customer_group; ?>" class="btn btn-info btn-xs"><i class="fa fa-group fa-fw"></i></button></td>
              <td><?php echo $q_data['city']; ?></td>
            </tr>
            <tr>
              <td><button data-toggle="tooltip" title="<?php echo $text_email; ?>" class="btn btn-info btn-xs"><i class="fa fa-envelope-o fa-fw"></i></button></td>
              <td><a href="mailto:<?php echo $q_data['email']; ?>"><?php echo $q_data['email']; ?></a></td>
            </tr>
            <tr>
              <td><button data-toggle="tooltip" title="<?php echo $text_telephone; ?>" class="btn btn-info btn-xs"><i class="fa fa-phone fa-fw"></i></button></td>
              <td><?php echo $q_data['phone']; ?></td>
            </tr>
            <tr>
              <td><button data-toggle="tooltip" title="<?php echo $text_telephone; ?>" class="btn btn-info btn-xs"><i class="fa fa-comment"></i></button></td>
              <td><?php echo $q_data['comment']; ?></td>
            </tr>
            <tr>
              <td><button data-toggle="tooltip" title="<?php echo $text_telephone; ?>" class="btn btn-info btn-xs"><i class="fa fa-download" aria-hidden="true"></i></button></td>
              <td><a href="<?php echo $q_data['href_file']; ?>"><?php echo $q_data['file']; ?></a></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-info-circle"></i> <?php echo $text_order; ?></h3>
      </div>
      <div class="panel-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <td class="text-left">Image</td>
              <td class="text-left">Product Details</td>
              <td class="text-left">Model</td>
              <td class="text-left">Unit Price</td>
              <td class="text-left">Quantity</td>
              <td class="text-left">Total</td>
            </tr>
          </thead>
          <tbody>
             <?php foreach($quote_info as $quote_info) { ?>
              <tr>
                <td> <a class="thumbnail" href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>"><img src="<?php echo $quote_info['image']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a></td>
                <td>
                  <ul>
                   <h4><a href="<?php echo $quote_info['href']; ?>" target="_blank">- <?php echo $quote_info['product_name']; ?></a></h4>
                     <?php foreach($quote_info['quote_options'] as $_key => $quote_key) { ?> 
                     <?php foreach($quote_key as $_key => $q_value) { ?> 
                      <li><small class="quote_option_json_data"><b><?php echo $q_value['name']; ?>:</b></small><small><?php echo $q_value['value']; ?></small></li>
                     <?php } ?>
                    <?php } ?>
                  </ul>
                </td>
                <td><?php echo $quote_info['model']; ?></td>
                <td><?php echo $quote_info['price']; ?></td>
                <td><?php echo $quote_info['quantity']; ?></td>
                <td><?php echo $quote_info['total']; ?></td>
              </tr>
              <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
</div>
</div>
<?php echo $footer; ?>