<?php require_once("_head.php"); ?>
<!-- Header implementation -->
<?php require_once("_header.php"); ?>
<!-- Content implementation -->
<div class="content">
	<div style="width:100%;height:60px;">
		<div class="product_title" style="margin-top:0;margin-left:25px;"><h1 style="font-size:25px;">Продукция <?=$partner->item_title?></h1></div>
		<div style="margin-left:25px;width:300px;position:relative;top:3px;">
			<div style="float:left;font-size:14px;position:relative;top:10px;margin-right:10px;">
				<img src="<?=base_url()?>images/active.gif"><span style="margin-left:3px;color:#CC6600;">Товар присутствует</span>&nbsp;
				<img src="<?=base_url()?>images/notactive.gif"><span style="margin-left:3px;">Товар отсутствует</span>
				
			</div>
		</div>
	</div>
    <div style="float:left;padding-left: 25px;position:relative;top:15px;">
    	<?php
    	    $str = '';
    		$style = 'style="clear:both;"';
    		foreach ($categories as $index=>$category) {        			
    			$cats = $category['cat'];
    			$subcats = $category['subcat'];    			
    			
    			$c = $cats;
    			unset($c->items);
    			$class = "";
    			if(!in_array($c, $cats_partner)) $class = 'class="not_active"';
    			
    			if($index != 0 && $index%3 == 0) $str .= '<br /><div class="category" '.$style.'>';
    			else $str .= '<div class="category">';        				
    			$str .= '<h2 '.$class.'><a href="'.base_url().'products/category/'.$category['cat']->category_id.'">'.$category['cat']->category_title.'</a></h2>';        			
    			if(!empty($subcats)) {
    				$str .= '<ul>';
    				foreach ($subcats as $cat) {
    					$item_count = $cat->items;
    					if($item_count == 0) $item_count = "";
    					
    					$c = $cat;
    					$sub2cats = $cat->subcats;
    					
		    			unset($c->items);
		    			unset($c->subcats);
		    			$class = "";
		    			$color = 'style="color:#993300;"';
		    			if(!in_array($c, $cats_partner)) {$class = 'class="not_active"';$item_count = "";$color = '';}
    					
    					$str .= '<li><h3 '.$class.'><a href="'.base_url().'products/subcat/'.$cat->category_id.'">'.$cat->category_title.'</a> </h3>';
    					if(!empty($sub2cats)) {
                            $str .= '<div><ul>';                            
                            foreach ($sub2cats as $sub2cat) {
							    $item_count = $sub2cat->items;
							    if($item_count == 0) $item_count = "";
							    
							    $c = $sub2cat;
							    unset($c->items);
							    $class = "";
							    $color = 'style="color:#993300;"';
							    if(!in_array($c, $cats_partner)) {$class = 'class="not_active"';$item_count = "";$color = '';}
                                $str .= '<li '.$class.'><h4><a href="'.base_url().'products/subcat/'.$sub2cat->category_id.'">'.$sub2cat->category_title.'</a> <span>'.$item_count.'</span></h4>'; 
                            }
                            $str .= '</ul></div>'; 
    					}    					
    					$str .= '</li>';
    				}
    				$str .= '<ul></div>';
    			}
    		} echo $str;
    	?>
    </div>
    <div style="clear:both;">&nbsp;</div>
</div>
<?php require_once('_footer.php'); ?>