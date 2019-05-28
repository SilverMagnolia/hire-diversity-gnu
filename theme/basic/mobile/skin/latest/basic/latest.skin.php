<?php
if (!defined('_GNUBOARD_')) exit; // Unable to access direct pages

// add_stylesheet('css file path', Output order); Smaller numbers printed first
add_stylesheet('<link rel="stylesheet" href="'.$latest_skin_url.'/style.css">', 0);
$cur_datetime = new DateTime("now");
?>

<div class="lt">
    <a href="<?php echo $see_more_href ?>" class="lt_title"><strong><?php echo $bo_subject ?></strong></a>
    <ul>
    <?php for ($i=0; $i<count($list); $i++) { ?>
        <li>
        <?php if ($list[$i]['icon_secret']) { ?>
            <i class="fa fa-lock" aria-hidden="true"></i>
        <?php } ?>

            <a href="<?php echo $list[$i]['href'] ?>" class="lt_tit">
                <span><?php echo $list[$i]['show_subject'] ?></span>
            <?php if ($list[$i]['icon_new']) { ?>
                <span class="new_icon">N</span>
            <?php } ?>
            <?php if ($list[$i]['icon_file']) { ?>
                <i class="fa fa-download" aria-hidden="true"></i>
            <?php } ?>
            <?php if ($list[$i]['icon_link ']) { ?>
                <?php echo $list[$i]['icon_link'] ?>
            <?php } ?>
            <?php if ($list[$i]['icon_hot']) { ?>
                <?php echo $list[$i]['icon_hot'] ?>
            <?php } ?>

            <!-- 마감일 표시 -->
            <?php if($board['activate_deadline']) { ?>
            <span style="color: #ddd">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
            <?php if ($list[$i]['deadline'] != null) { 
                // ]today or later
                $deadline_datetime = new DateTime($list[$i]['deadline']);

                $date = date_create($list[$i]['deadline']);
                $reformatted_date = date_format($date, 'Y-m-d');   

                $diff = $deadline_datetime->diff($cur_datetime);
                $diffDays = (integer)$diff->format( "%R%a" );
                
                if ($diffDays == 0 && $cur_datetime < $deadline_datetime) { ?>
                    <!-- 마감일 오늘이고 아직 지나지 않음. -->
                    <span style="color: #f63e54">TODAY</span>

                <?php } else { ?>
                    <!-- 마감일 오늘 이후. -->
                    <span style="color: #2980B9"><?php echo 'Due Date '.$reformatted_date ?></span>
                <?php } ?>

            <?php } else if ($list[$i]['is_rolling_base']) { ?>
                <span style="color: #2980B9">Rolling Base</span>
            <?php } else { ?>   
                <span style="color: #2980B9">O.E</span>
            <?php } ?>
            <?php } ?>
            <!-- END custom -->
            </a>
            
            <div class="cnt_cmt_bx">
            	<?php if ($list[$i]['comment_cnt']) { ?>
                    <span class="sound_only"><?php e__('Comment') ?></span><?php echo $list[$i]['comment_cnt']; ?><span class="sound_only"><?php e__('Count') ?></span>
                <?php } ?>  
            </div>
        </li>
    <?php } ?>
    <?php echo $show_no_list // No recent posts ?>
    </ul>
</div>
