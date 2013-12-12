<?php require_once("_head.php"); ?>
	<!-- Header implementation -->
	<?php require_once("_header.php"); ?>
	<!-- Content implementation -->
	<div class="content">
		<div class="map_title"><h1>Карта сайта</h1></div>
        <div class="map">
        	<?php
        	$str = '';        	
        	$sc_index = 1;
        	foreach ($categories as $m_category) {
        		$main = $m_category['main'];
        		$submain = $m_category['submain'];
        		
        		$str .= '<div class="map_main_cat"><h1><a href="'.base_url().$main->link.'">'.$main->category_title.'<img style="margin-left:7px;" src="'.base_url().'images/right_btn.png"/></a></h1>';
        		if(!empty($submain)) {
        			foreach ($submain as $scat) {
        				$cat = $scat['cat'];
        				$subcat = $scat['subcat'];
        				
        				if($sc_index == 1) $style = 'float:left;';
        				elseif($sc_index == 2) $style = 'float:left;margin-left:5%;';
        				elseif($sc_index == 3) $style = 'float:right;';
        				
        				$link = '';
    					if($main->link == 'about' && $cat->category_title == 'Контакты') {
    						$link = 'contacts/';
    						
    					} elseif ($main->link == 'products' || $main->link == 'information') {
    						$link = $main->link.'/category/'.$cat->category_id;
    						
    					}
        				
        				$str .= '<div class="map_sub_cat" style="'.$style.'"><h2><a href="'.base_url().$link.'">'.$cat->category_title.'</a></h2>';
        				if(!empty($subcat)) {
        					$str .= '<ul>';
        					foreach ($subcat as $cat) {
        						
	        					if($cat->items != 0) {
	        						$item_count = $cat->items;
	        						$item_count = '('.$item_count.')';
	        					}
	        					else $item_count = "";
	        					
	        					$link = '';
	        					if($main->link == 'products') {
	        						$link = 'products/subcat/';
	        					}
	        					
	        					$str .= '<li><a href="'.base_url().$link.$cat->category_id.'">'.$cat->category_title.'</a> <span style="color:#76797C;">'.$item_count.'</span></li>';
        					}
        					$str .= '</ul>';
        				}
        				$str .= '</div>';
        				$sc_index++;
        				if($sc_index > 3) $sc_index = 1;
        			}
        		}
        		$str .= '</div>';
        		$sc_index = 1;
        	}
        	echo $str;
        		/*$str = '';
        		$style = 'style="clear:both;"';
        		
        		$products = $categories['products'];
        		$str .= '<div style="margin-bottom:10px;"><h1>Продукция</h1></div>';
        		if(!empty($products)) {
	        		foreach ($products as $index=>$category) {        			
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
	        					$str .= '<li><a href="'.base_url().'products/subcat/'.$cat->category_id.'">'.$cat->category_title.'</a> '.$item_count.'</li>';
	        				}
	        				$str .= '</ul>';
	        			}
	        			$str .= '</div>';
	        		}
        		}
        		$str .= '</div><div style="padding-left: 25px;float:left;">';
        		$str .= '<div class="category"><h1>Партнеры</h1>
        					';
        		$str .= '</div><div style="float:left;">';
        		$str .= '<div class="category"><h1>О Компании</h1></div>';
        		$str .= '</div><div style="float:left;">';
        		$str .= '<div class="category"><h1>Контакты</h1></div>';
        		$str .= '</div><div style="float:left;">';
        		$str .= '</div>';
        		$str .= '</div><div style="padding-left: 25px;float:left;">';
        		$str .= '<div class="category"><h1>Информация</h1>
        			<ul>
        						<li><a href="#">Обзоры и Статьи</a></li>
        						<li><a href="#">Инструкции</a></li>
        						<li><a href="#">Чертежи \ Схемы установки</a></li>
        						<li><a href="#">Что такое взлом и как с ним бороться?</a></li>
        						<li><a href="#">Сервис</a></li>
        					</ul>
        		</div>';
        		$str .= '</div>';
        		echo $str;*/
        	?>
        </div>
        <div style="clear:both;">&nbsp;</div>
    </div>
    <?php require_once('_footer.php'); ?>