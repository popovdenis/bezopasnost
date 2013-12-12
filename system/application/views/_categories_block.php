<link rel="stylesheet" href="<?=base_url()?>js/tree/jquery.treeview.css" />      
    
    <script src="<?=base_url()?>js/jquery-1.4.2.js" type="text/javascript"></script>
    <script src="<?=base_url()?>js/tree/jquery.cookie.js" type="text/javascript"></script>
    <script src="<?=base_url()?>js/tree/jquery.treeview.js" type="text/javascript"></script>
    
    <script type="text/javascript">
        $(function() {
            $("#tree").treeview({
                collapsed: false,
                animated: "medium",
                control:"#sidetreecontrol",
                persist: "location"
            });
        });
        function action_not_main(action) {
        	$('.not_main').each(function () {
        		if(action == 'close') {
        			$(this).hide();
        			$('#tree > *').find("div.-hitarea").each(function () {
		        		$(this)
		        		.removeClass()
		        		.addClass('hitarea not_active-hitarea -hitarea not_main-hitarea -hitarea expandable-hitarea')
		        		.end();
		        	});
        		}
        		else if(action == 'show') {
        			$(this).show();
        			$('#tree > *').find("div.-hitarea").each(function () {
		        		$(this)
		        		.removeClass()
		        		.addClass('hitarea not_active-hitarea -hitarea not_main-hitarea -hitarea collapsable-hitarea')
		        		.end();
		        	});
        		}
        	});
        }
        
	</script>
<!-- Продукция меню -->
<div class="menubox">
	<div class="t">
		<div class="b">
			<div class="l">
				<div class="r">
					<div class="bl">
						<div class="br">
							<div class="tl">
								<div class="tr">
<!--								<div style="font-size:12px;font-weight:bold;margin-bottom:3px;text-align:left;display:none;"><a href="#" onclick="javascript:action_not_main('close');return false;">Закрыть все</a> | <a href="#" onclick="javascript:action_not_main('show');return false;">Раскрыть все</a></div>-->
                                <ul id="tree">
                                    <li><span style="color:#CC6600;font-weight: bold;">Продукция</span>
                                    <?php
                                        echo $categories;
                                    ?>
                                    </li>
                                </ul>									
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>