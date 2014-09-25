<?php
    $classes = ['black_block', 'white_block'];
    for ($i = 0; $i < 2; $i++) {
        for ($j = 0; $j < 7; $j++) {
            $position = (int)($j % 2 == 0);
    ?>
        <span class="<?php echo $classes[$position]; ?>" data-horder="<?php echo $j; ?>" data-vorder="<?php echo $i; ?>">&nbsp;</span>
    <?php
        }
        $classes = array_reverse($classes);
    ?>
        <div style="clear: both;"></div>
    <?php
    }
?>
