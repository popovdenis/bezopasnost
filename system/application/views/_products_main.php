<?php require_once("_head.php"); ?>
	<!-- Header implementation -->
	<?php require_once("_header.php"); ?>
	<!-- Content implementation -->
	<div class="product_title" style="margin-left:34px;margin-bottom:30px;margin-top:0;"><h1>Продукция</h1></div>
	<div class="content">
        <div style="padding-left: 25px;">
        	<?php
        		$str = '';
        		$style = 'style="clear:both;"';
        		
        		foreach ($categories as $index=>$category) {        			
        			$cats = $category['cat'];
        			$subcats = $category['subcat'];
        			
        			if($index != 0 && $index%3 == 0) $str .= '<br /><div class="category" '.$style.'>';
        			else $str .= '<div class="category">';        				
        			$str .= '<h2><a href="'.base_url().'products/category/'.$category['cat']->category_id.'">'.$category['cat']->category_title.'</a></h2>';        			
        			if(!empty($subcats)) {
        				$str .= '<ul>';
        				foreach ($subcats as $cat) {
        					$item_count = $cat->items;
        					if($item_count == 0) $item_count = "";
        					$str .= '<li><h3><a href="'.base_url().'products/subcat/'.$cat->category_id.'">'.$cat->category_title.'</a> '.$item_count.'</h3>';
        					if(!empty($cat->subcats)) {
	                            $str .= '<div><ul>';                            
	                            foreach ($cat->subcats as $sub2cat) {
								    $item_count = $sub2cat->items;
								    if($item_count == 0) $item_count = "";
								    
								   	$str .= '<li><h4><a href="'.base_url().'products/subcat/'.$sub2cat->category_id.'">'.$sub2cat->category_title.'</a> <span>'.$item_count.'</span></h4>'; 
	                            }
	                            $str .= '</ul></div>'; 
	    					}         					
        					$str .= '</li>';
        				}
        				$str .= '<ul></div>';
        			}
        		}
        		echo $str;
        	?>
        </div>
        <div style="clear:both;">&nbsp;</div>
    </div>
    <?php require_once('_footer.php'); ?>