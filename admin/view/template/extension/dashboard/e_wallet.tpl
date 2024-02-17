 <div class="row">
  <div class="col-lg-3  col-md-3 col-sm-6">
    <div class="tile tile-primary">
      <div class="tile-heading"><?php echo $heading_title1; ?> </div>
      <div class="tile-body"><i class="fa fa-exchange"></i>
        <h2 class="pull-right"><?php echo $totaltrasaction;?></h2>        
      </div>
      <div class="tile-footer"><a href="<?php echo $e_wallet; ?>"><?php echo $text_view; ?></a></div>
    </div>      
  </div>
  <div class="col-lg-3  col-md-3 col-sm-6">
    <div class="tile tile-primary">
      <div class="tile-heading"><?php echo $heading_title3; ?></div>
      <div class="tile-body"><i class="fa fa-plus-square-o "></i>
      </div>
      <div class="tile-footer"><a href="<?php echo $add; ?>"><?php echo $text_view; ?></a></div>
    </div> 
  </div>
  <div class="col-lg-3  col-md-3 col-sm-6">
    <div class="tile tile-primary">
      <div class="tile-heading"><?php echo $heading_title4; ?> </div>
      <div class="tile-body"><i class="fa fa-credit-card"></i>
      <div class="pull-right" style="line-height: 25px;">        
        <div><?php echo $pendding; ?></div>
        <div><?php echo $approve; ?></div>
        <div><?php echo $reject; ?></div>
      </div>
      </div>
      <div class="tile-footer"><a href="<?php echo $money;?>"><?php echo $text_view; ?></a></div>
    </div> 
  </div>
  <div class="col-lg-3 col-md-3 col-sm-6">
    <div class="tile tile-primary">
      <div class="tile-heading"><?php echo $heading_title2; ?></div>
      <div class="tile-body"><i class="fa fa-money"></i>
        <h2 class="pull-right"><?php echo $balance; ?></h2>
      </div>
      <div class="tile-footer"><a href="<?php echo $customer; ?>"><?php echo $text_view; ?></a></div>
    </div> 
  </div>
</div>

