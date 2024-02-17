<?php echo $header; ?>
<div class="container" id="hprm">
	<ul class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
		<?php } ?>
	</ul>
	<div class="row">
		<?php echo $column_left; ?>
		<?php if ($column_left && $column_right) { ?>
			<?php $class = 'col-sm-6'; ?>
		<?php } elseif ($column_left || $column_right) { ?>
			<?php $class = 'col-sm-9'; ?>
		<?php } else { ?>
			<?php $class = 'col-sm-12'; ?>
		<?php } ?>
		<div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
			<h1><?php echo $text_heading; ?></h1>
			<div class="clearfix"></div>

		<fieldset>

				<div class="subtitle"><?php echo $text_instruction; ?></div>

				<div id="input-search-reseller">
					<div class="input-search col-sm-5 col-md-3 col-lg-3 col-xl-2 col-12">
						<select data-live-search="true" name="filter_type_id" class="form-control selectpicker">
								
								<option value=""><?php echo $text_selection_seller; ?></option>

								<?php foreach ($reseller_groups as $reseller_group) { ?>
									  <option <?php if ($reseller_group['name']|lower == $filter_type) { ?> selected <?php } ?> value="<?php echo $reseller_group['customer_group_id'] ?> "><?php echo $reseller_group['name'] ?></option>
								 <?php } ?>
						</select>
					</div>

					
					
					<div class="input-search col-sm-5 col-md-3 col-lg-3 col-xl-2 col-12">
						<select data-live-search="true" name="filter_zone_id" class="form-control selectpicker">
								<option value=""><?php echo $text_selection_zone; ?></option>

								<?php foreach ($zones as $zone) { ?>
									  <option value="<?php echo $zone['zone_id']; ?> "><?php echo $zone['name']; ?></option>
								 <?php } ?>
						</select>
					</div>

					
					
					<div class="input-search col-sm-5 col-md-3 col-lg-3 col-xl-2 col-12">
						<input placeholder="Search by Name" type="text" name="filter_name" class="form-control"/>
					</div>
				</div>

				<div id="button-search-reseller">
				  <button id="search-reseller" type="button" class="btn btn-primary btn-block hprm-button">Search</button>
				</div>
			 <div id="search-content">
				 <?php echo $reseller_list; ?>
			  </div>

			</fieldset>
				</div>
				<?php echo $column_right; ?>



		<?php echo $content_bottom; ?>
	</div>
</div>
</div>

<script>

$(document).on('click', '#search-reseller', function(){
		url = 'index.php?route=account/reseller_list/load';

		var filter_name = $('input[name=\'filter_name\']').val();

		if (filter_name) {
			url += '&filter_name=' + encodeURIComponent(filter_name);
		}

		var filter_zone_id = $('select[name=\'filter_zone_id\']').val();

		if (filter_zone_id) {
			url += '&filter_zone_id=' + encodeURIComponent(filter_zone_id);
		}

		var filter_type_id = $('select[name=\'filter_type_id\']').val();

		if (filter_type_id) {
			url += '&filter_type_id=' + encodeURIComponent(filter_type_id);
		}

		$('#search-content').html('<div class="loader"></div>');

		$.get(url, function(data, status){
		   $('#search-content').html(data);
		});

});

  $(document).on('click', '.pagination li a', function(){
		let url = $(this).data('url');

		$('#search-content').html('<div class="loader"></div>');

		$.get(url, function(data, status){
		   $('#search-content').html(data);
		});
	});


</script>
<?php echo $footer; ?>
