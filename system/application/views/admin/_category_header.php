<div id="search_cat_name">
	Имя категории:
	<select id="search_category_list" class="Txt67 fwNormal" name="search_category_list">
		<option value="0">Выберите имя категории</option>
		<?=$categories?>
	</select>
	<input type="button" onclick="search_category();" value="Найти" />
</div>
<div id="new_category_block" style="float:left;width:917px;margin-bottom:10px;display:none;">
	<div id="" class="left padAll5" style="color:#676767;">Новая категория</div>
	<div style="width:100%;float:left;">
		<div style="float:left;">
			Имя категории:
			<input type="text" id="new_category_title" style="width:320px;"/>
		</div>
		<div id="new_category_list" style="float:right;">
			Родительская категория:
			<select id="categories_new"><option value="0">Выберите имя категории</option>
				<?=$categories?>
			</select>
		</div>
	</div>
	<div style="float: left; width: 100%;">
		<div style="float:left;">
			Описание категории:<br />
			<textarea id="new_cat_desc" style="width:700px;"></textarea>
		</div>
		<div style="float:right;position:relative;top:50px;">
			<input type="button" onclick="new_category();" value="Добавить" />
		</div>
	</div>
</div>