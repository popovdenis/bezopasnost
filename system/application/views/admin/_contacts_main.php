<script type="text/javascript" src="<?=base_url()?>js/highslide/highslide.js"></script>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>js/highslide/highslide.css" />
<script type="text/javascript">
	hs.graphicsDir = '<?=base_url()?>js/highslide/graphics/';
	hs.wrapperClassName = 'wide-border';
</script>
<script type="text/javascript">
$(function(){
	new AjaxUpload('#photo1', {
		// Location of the server-side upload script
		action: '<?=base_url()?>admin/home/upload',
		// File upload name
		name: 'userfile',
		// Additional data to send
		data: {
			upload_type: 'contacts',
			field_name: 'contact_photo1'
		},
	  responseType: false,
	  onChange: function(file, extension){},
	  onSubmit : function(file , ext){
		    if (! (ext && /^(<?=$allowed_types?>)$/.test(ext))){
		        // extension is not allowed
		        alert('Error: invalid file extension');
		        // cancel upload
		        return false;
		    } else {

		    	$("#photo1_img").html('<img alt="loading..." border="0" src="<?php echo base_url() ?>images/loading-blue.gif" />');
		    }
		} ,
	  onComplete: function(file, response) {
	  	if(response) {
		  	var result = window["eval"]("(" + response + ")");
		  	var file = '<img width="50" src="<?=base_url()?>'+result.file_path+'" />';
		  	$('#photo1_img').html(file);
		  	/*$.post(
				"<?=base_url()?>admin/home/upload",
				{attach_id: result.attach_id, file_type: 'contacts', field_name: 'contact_photo1'}

			)*/
	  	}
	  }
	});
});
$(function(){
	new AjaxUpload('#photo2', {
		// Location of the server-side upload script
		action: '<?=base_url()?>admin/home/upload',
		// File upload name
		name: 'userfile',
		// Additional data to send
		data: {
			upload_type: 'contacts',
			field_name: 'contact_photo2'
		},
	  responseType: false,
	  onChange: function(file, extension){},
	  onSubmit : function(file , ext){
		    if (! (ext && /^(<?=$allowed_types?>)$/.test(ext))){
		        // extension is not allowed
		        alert('Error: invalid file extension');
		        // cancel upload
		        return false;
		    } else {

		    	$("#photo2_img").html('<img width="50" alt="loading..." border="0" src="<?php echo base_url() ?>images/loading-blue.gif" />');
		    }
		} ,
	  onComplete: function(file, response) {
	  	if(response) {
		  	var result = window["eval"]("(" + response + ")");
		  	var file = '<img width="50" src="<?=base_url()?>'+result.file_path+'" />';
		  	$('#photo2_img').html(file);
		  	/*$.post(
				"<?=base_url()?>admin/home/upload",
				{attach_id: result.attach_id, file_type: 'contacts', field_name: 'contact_photo2'}

			)*/
	  	}
	  }
	});
});
/*$(function($){
   $(".phone").mask("9(999)999-99-99",{placeholder:" "});
});*/
</script>
<?php
	$address1 = null;
	$address2 = null;
	if(!empty($contacts->contact_address)){
		$contacts->contact_address = unserialize($contacts->contact_address);
		$address1 = (isset($contacts->contact_address[0]) && empty($contacts->contact_address[0]['contact_address'])) ? null : $contacts->contact_address[0]['contact_address'];
		$address2 = (isset($contacts->contact_address[1]) && empty($contacts->contact_address[1]['contact_address'])) ? null : $contacts->contact_address[1]['contact_address'];

	}
	$time1 = null;
	$time2 = null;
	if(!empty($contacts->contact_times)){
		$contacts->contact_times = unserialize($contacts->contact_times);
		$time1 = isset($contacts->contact_times[0]) ? $contacts->contact_times[0] : null;
		$time2 = isset($contacts->contact_times[1]) ? $contacts->contact_times[1] : null;
	}
	$photo1 = null;
	$photo2 = null;
	if(!empty($contacts->contact_photos)){
		$contacts->contact_photos = unserialize($contacts->contact_photos);
		$photo1 = isset($contacts->contact_photos['contact_photo1']) ? $contacts->contact_photos['contact_photo1'] : null;
		$photo2 = isset($contacts->contact_photos['contact_photo2']) ? $contacts->contact_photos['contact_photo2'] : null;
	}
	$map1 = null;
	$map2 = null;
	if(!empty($contacts->contact_maps)){
		$contacts->contact_maps = unserialize($contacts->contact_maps);
		$map1 = isset($contacts->contact_maps['contact_map1']) ? $contacts->contact_maps['contact_map1'] : null;
		$map2 = isset($contacts->contact_maps['contact_map2']) ? $contacts->contact_maps['contact_map2'] : null;
	}
?>
<div id="contacts_main_block">
	<div class="contact_object">
		<div>
			<div class="mb10">Магазин 1</div>
			<div class="floatL mb10" style="width:100px;">Фото объекта</div>
			<div class="clear" />
			<div class="floatL mb10" style="width:100px;"><a href="#" id="photo1"><span>Фото объекта</span></a></div>
			<div class="clear" />
			<div class="floatL mb10" style="width:100px;" id="photo1_img">
			<?php
			if($photo1) { ?>
				<a href="<?=base_url().$photo1?>" class="highslide" onclick="return hs.expand(this)">
					<img src="<?=base_url().$photo1?>" alt="Highslide JS" title="Click to enlarge" height="107" width="107" />
				</a>
			<?php } ?>
			</div>
		</div>
		<div class="clear" />
		<div>
			<div class="mb10">
				<span class="floatL mr5">Адрес:</span>
				<input id="contact_address_1" type="text" value="<?=$address1?>" size="54" />
			</div>
			<div class="mb10">
			<?php
			$from_h = null;
			$from_m = null;
			$to_h = null;
			$to_m = null;
			$tm_from_h = null;
			$tm_from_m = null;
			$tm_to_h = null;
			$tm_to_m = null;

			if($time1) {
				$from_h = $time1['time_from_h'];
				$from_m = $time1['time_from_m'];
				$to_h = $time1['time_to_h'];
				$to_m = $time1['time_to_m'];
				$tm_from_h = $time1['time_tm_from_h'];
				$tm_from_m = $time1['time_tm_from_m'];
				$tm_to_h = $time1['time_tm_to_h'];
				$tm_to_m = $time1['time_tm_to_m'];
			}
			?>
				<div class="mb10">
					<span>Время работы.</span>
						<span>с:</span>
						<input id="contact_time_1_f_h" type="text" value="<?=$from_h?>" style="width:20px;" />:
						<input id="contact_time_1_f_m" type="text" value="<?=$from_m?>" style="width:20px;" /> -
						<span>до:</span>
						<input id="contact_time_1_t_h" type="text" value="<?=$to_h?>" style="width:20px;" />:
						<input id="contact_time_1_t_m" type="text" value="<?=$to_m?>" style="width:20px;" />
						<br />
				</div>
				<div class="mb10">
					<span>Перерыв.</span><span>с:</span>
						<input id="contact_time_1_tm_f_h" type="text" value="<?=$tm_from_h?>" style="width:20px;" />:
						<input id="contact_time_1_tm_f_m" type="text" value="<?=$tm_from_m?>" style="width:20px;" />
						-
						<span>до:</span>
						<input id="contact_time_1_tm_t_h" type="text" value="<?=$tm_to_h?>" style="width:20px;" />:
						<input id="contact_time_1_tm_t_m" type="text" value="<?=$tm_to_m?>" style="width:20px;" />
				</div>
			</div>
		</div>
	</div>
	<style type="text/css">
	.hr {
		border-bottom:1px solid #F4F4F4;
		border-top:1px solid #9B9B9B;
		float:left;
		margin-left:65px;
		width:876px;
	}
	</style>
	<div class="hr">&nbsp;</div>
	<div class="contact_object">
		<div>
			<div class="mb10">Магазин 2</div>
			<div class="floatL mb10" style="width:100px;">Фото объекта</div>
			<div class="clear" />
			<div class="floatL mb10" style="width:100px;"><a href="#" id="photo2"><span>Фото объекта</span></a></div>
			<div class="clear" />
			<div class="floatL mb10" style="width:100px;" id="photo2_img">
			<?php
			if($photo2) { ?>
				<a href="<?=base_url().$photo2?>" class="highslide" onclick="return hs.expand(this)">
					<img src="<?=base_url().$photo2?>" alt="Highslide JS" title="Click to enlarge" height="107" width="107" />
				</a>
			<?php } ?>
			</div>
		</div>
		<div class="clear" />
		<div class="mb10">
			<div class="mb10">
				<span class="floatL mr5">?????:</span><input id="contact_address_2" type="text" value="<?=$address2?>" size="54" />
			</div>
			<div>
			<?php
			$from_h = null;
			$from_m = null;
			$to_h = null;
			$to_m = null;
			$tm_from_h = null;
			$tm_from_m = null;
			$tm_to_h = null;
			$tm_to_m = null;

			if($time1) {
				$from_h = $time2['time_from_h'];
				$from_m = $time2['time_from_m'];
				$to_h = $time2['time_to_h'];
				$to_m = $time2['time_to_m'];
				$tm_from_h = $time2['time_tm_from_h'];
				$tm_from_m = $time2['time_tm_from_m'];
				$tm_to_h = $time2['time_tm_to_h'];
				$tm_to_m = $time2['time_tm_to_m'];
			}
			?>
				<div class="mb10">
					<span>????? ??????.</span>
					<span>?:</span>
					<input id="contact_time_2_f_h" type="text" value="<?=$from_h?>" style="width:20px;" />:
					<input id="contact_time_2_f_m" type="text" value="<?=$from_m?>" style="width:20px;" /> -
					<span>??:</span>
					<input id="contact_time_2_t_h" type="text" value="<?=$to_h?>" style="width:20px;" />:
					<input id="contact_time_2_t_m" type="text" value="<?=$to_m?>" style="width:20px;" /><br />
				</div>
				<div class="mb10">
					<span>???????.</span><span>?:</span>
					<input id="contact_time_2_tm_f_h" type="text" value="<?=$tm_from_h?>" style="width:20px;" />:
					<input id="contact_time_2_tm_f_m" type="text" value="<?=$tm_from_m?>" style="width:20px;" />
					-
					<span>??:</span>
					<input id="contact_time_2_tm_t_h" type="text" value="<?=$tm_to_h?>" style="width:20px;" />:
					<input id="contact_time_2_tm_t_m" type="text" value="<?=$tm_to_m?>" style="width:20px;" />
				</div>
			</div>
		</div>
	</div>
	<div class="innerTableHeaderGreen">
		<div id="" class="left padAll5">Контакты</div>
		<div class="padAll5 right">
			<img class="marRight5" src="<?=base_url()?>images/big-plus.gif" alt=""/>
			<a id="" onclick="javascript: return add_form('contacts');" href="#">Добавить</a>
		</div>
	</div>
	<div id="contacts_block_header" style="float: left; width: 100%;">
		<div id="new_contacts_block" style="float:left;width:917px;margin-bottom:10px;display:none;">
			<div style="width:100%;float:left;">
			<select id="contact_type">
				<option value="phone">телефон</option>
				<option value="fax">факс</option>
				<option value="email">e-mail</option>
			</select>
			<input type="text" id="contact_value" value="" />&nbsp;
			<input type="button" onclick="javascript:add_contact();" value="Добавить" />
		</div>
		<span><img id="contacts_img" border="0" src="<?=base_url()?>images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;display:none;"/></span>
	</div>
	<div id="contacts_section">
		<?php
			$contact_block_index = 0;
			if(!empty($contacts->contact_phones)) {
				$phones = unserialize($contacts->contact_phones);
				$index = 0;
				foreach ($phones as $index=>$phone) {
			?>
				<div id="contact_block_<?=$contact_block_index?>">
					<select id="contact_type_<?=$contact_block_index?>">
						<option value="phone" selected>телефон</option>
						<option value="fax">факс</option>
						<option value="email">e-mail</option>
					</select>
					<input type="text" id="contact_value_<?=$contact_block_index?>" value="<?=$phone?>" />
				</div>
			<?php
					$contact_block_index++;
				}
			}
			if(!empty($contacts->contact_faxes)) {
				$faxes = unserialize($contacts->contact_faxes);
				$index = 0;
				foreach ($faxes as $index=>$fax) {
			?>
				<div id="contact_block_<?=$contact_block_index?>">
					<select id="contact_type_<?=$contact_block_index?>">
						<option value="phone">телефон</option>
						<option value="fax" selected>факс</option>
						<option value="email">e-mail</option>
					</select>
					<input type="text" id="contact_value_<?=$contact_block_index?>" value="<?=$fax?>" />
				</div>
			<?php
					$contact_block_index++;
				}
			}
			if(!empty($contacts->contact_emails)) {
				$emails = unserialize($contacts->contact_emails);
				$index = 0;
				foreach ($emails as $index=>$email) {
			?>
				<div id="contact_block_<?=$contact_block_index?>">
					<select id="contact_type_<?=$contact_block_index?>">
						<option value="phone">телефон</option>
						<option value="fax">факс</option>
						<option value="email" selected>e-mail</option>
					</select>
					<input type="text" id="contact_value_<?=$contact_block_index?>" value="<?=$email?>" />
				</div>
			<?php
					$contact_block_index++;
				}
			}
		?>
	</div>
</div>
<div style="float:right;margin-top:15px;">
	<input type="button" onclick="javascript:update_contacts();return false;" value="Обновить контакты" />
</div>
