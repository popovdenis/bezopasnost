<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?= config_item('base_name') ?></title>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
</head>
<body>
	<div id="main">
		<?php if(isset($upload_form)) echo $upload_form; ?>
		<?php
			if(isset($excel)) {
				/*echo '<pre>';
				var_dump($excel->sheets);
				echo '</pre>';*/
				$x=1;
				while($x <= $excel->sheets[0]['numRows']) {
					$y=1;
					while($y <= $excel->sheets[0]['numCols']) {
						$cell = isset($excel->sheets[0]['cells'][$x][$y]) ? $excel->sheets[0]['cells'][$x][$y] : '';
						echo "\t\t<div>$cell</div>\n";
						$y++;
					}				
					$x++;
				}
			}
		?>
	</div>
</body>
</html>