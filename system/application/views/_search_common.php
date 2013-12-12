<script type="text/javascript">
function action_form(id){
	if($('#results-'+id).attr('class') == 'section') {
		$('#results-'+id+'-h3').css('background','transparent url(<?=base_url()?>images/expand_big.png) no-repeat scroll 0 6px');
		$('#results-'+id+' ul').hide('fast');
		$('#results-'+id+' p').hide('fast');
		$('#results-'+id).addClass('collapced');

	} else if($('#results-'+id).attr('class') == 'section collapced') {
		$('#results-'+id+'-h3').css('background','transparent url(<?=base_url()?>images/collapse_big.png) no-repeat scroll 0 6px');
		$('#results-'+id+' ul').show('fast');
		$('#results-'+id+' p').show('fast');
		$('#results-'+id).removeClass('collapced');
	}
	return false;
}
</script>
<style type="text/css">

</style>
<div id="main" style="margin-top:15px;width:630px;">
	<?php
		if(!empty($items)){
			$search_result = "";
			foreach($items as $index=>$category) {
				//echo '<pre>';print_r ($category->search_count);echo '</pre>';
				if(empty($category->search_count)) continue;
				$search_count = $category->search_count;
				$count_result = count($category->search_result);

				$count_display = ($count_result >= $per_page) ? $per_page : count($category->search_result);

				$search_result .= '<div id="results-'.$category->category_id.'" class="section" style="margin-bottom:10px;">';
				$search_result .= '<div class="heading" onclick="action_form(\''.$category->category_id.'\');"><h3 id="results-'.$category->category_id.'-h3">'.$category->category_title.'</h3>
				<div class="results"><span id="results_'.$category->category_id.'_from">'.$count_display.'</span> результатов из '.$search_count.'</div></div>';

				$search_result .= '<ul id="results-Продукция-ul" class="results" style="overflow: visible;">';
				//echo "<pre>"; print_r($category->search_result); echo "</pre>";
				foreach($category->search_result as $index=>$item) {

					$class = 'top-results';
					if($index > 4) $class = '';

					if(mb_strlen($item->item_preview) > 575) $item_desc = mb_substr($item->item_preview,0,570)."...";
					else $item_desc = $item->item_preview;
					$item_desc = preg_replace("/<p><img(.*?)\/><\/p>/si", "", $item_desc);
					$item_desc = str_replace(array('<p>', '</p>'), '', $item_desc);

					if($item->category_title == 'Бренды')
						$search_result .= '<li class="'.$class.'"><h4><a href="'.base_url().'partners/category/'.$item->category_id.'">'.$item->item_title.'</a></h4><p class="desc">'.$item_desc.'</p></li>';
					else
						$search_result .= '<li class="'.$class.'"><h4><a href="'.base_url().$item->item_type.'/subcat/'.$item->category_id.'/about/'.$item->item_id.'">'.$item->item_title.'</a></h4><p class="desc">'.$item_desc.'</p></li>';

				}
				if($type == 'main') {
					if($count_result > 5) $search_result .= '</ul><p class="meta" style=""><a href="#'.$category->category_id.'" class="viewall" onclick="search_other(\''.$category->category_id.'\');">Показать другие результаты</a></p></div>';
					else $search_result .= '</ul></div>';
				} else {
					$search_result .= '</ul><p class="meta" style=""><a href="'.base_url().'search" class="viewall">Просмотреть результаты по категориям</a></p></div>';
				}
			}
			echo $search_result;
		}
	?>
</div>