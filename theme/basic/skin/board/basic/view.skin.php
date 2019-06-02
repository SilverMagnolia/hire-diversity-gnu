<?php
if (!defined("_GNUBOARD_")) exit; // Unable to access direct pages

// add_stylesheet('css file path', Output order); Smaller numbers printed first
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
$showOverdueAtTheBottomOfContent = false;
?>

<script src="<?php echo GML_JS_URL; ?>/viewimageresize.js"></script>

<!-- Start reading posts { -->
<article id="bo_v" style="width:<?php echo $width; ?>">
    <header>
        <h2 id="bo_v_title">
            <?php if ($category_name) { ?>
            <span class="bo_v_cate"><?php echo $view['ca_name']; // Category Output End ?></span>
            <?php } ?>
            <span class="bo_v_tit"><?php echo $show_wr_subject;; ?></span>
        </h2>
    </header>

    <section id="bo_v_info">
        <!-- 마감일 생성 -->
        <?php 
            $cur_datetime = new DateTime("now");
            $deadline = $view['deadline'];
            $deadline_datetime = new DateTime($deadline);

            $diff = $deadline_datetime->diff($cur_datetime);
            $diffDays = (integer)$diff->format( "%R%a" );

            $date = date_create($deadline);
            $reformatted_date = date_format($date, 'Y-m-d');
            $is_rolling_base = $view['is_rolling_base']; 
        ?>

        <!-- 작성자, 생성일, 마감일, 조회수 -->
        <style>
            table, tbody, tr, th, td { 
                font-family: "Apple SD Gothic Neo", "Malgun Gothic", "맑은 고딕", sans-serif;
            } 
            th {
                font-size: 13px; 
                color: #999;   
                font-weight: normal;
                height: 30px;
            }
            td {
                color: black;
                font-size: 13px;
            }
            table {
                display: inline;
            }
        </style>
        <table>
            <colgroup>
                <col style="width: 110px;">
                <col style="width: 400px;">    
            </colgroup>
            <tbody> 
                <tr align="left">
                    <th>Writer</th>
                    <td><?php echo $view['name'] ?></td>
                </tr>
                <tr align="left">
                    <th>Hits</th>
                    <td>
                        <?php echo $show_hit_number; ?>
                    </td>
                </tr>
                <tr align="left">
                    <th>Created Date</th>
                    <td><?php echo $show_wr_datetime; ?></td>
                </tr>
                <tr align="left">
                    <th>Due Date</th>
                    <td>
                    <?php if ($deadline != null) {    

                            if ($deadline_datetime <= $cur_datetime) { 
                                $showOverdueAtTheBottomOfContent = true; ?>
                                <!-- overdue -->
                                <strike>
                                    <?php echo $reformatted_date; ?>
                                    <?php echo $is_rolling_base ? ' | Rolling Base' : '' ?>
                                </strike>
                                <span style="color: red">&nbsp;&nbsp;OVERDUE</span>
                            

                            <?php } else if ($diffDays == 0 && $cur_datetime < $deadline_datetime) { ?>
                                <!-- 마감일 오늘이고 아직 지나지 않음. -->
                                <?php echo $reformatted_date ?>
                                <? echo $is_rolling_base ? ' | Rolling Base' : ''?>
                    
                            <?php } else { ?>
                                <!-- 마감일 오늘 이후. -->
                                <?php echo $reformatted_date ?>
                                <?php echo $is_rolling_base ? ' | Rolling Base' : '' ?>

                            <?php } ?>

                    <?php } else { ?>
                            <!-- 마감일 없음 -->
                            <?php echo $is_rolling_base ? 'Rolling Base' : 'Occasion Employment'; ?>
                    <?php } ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="bo_v_info_r">
            <?php if($update_href || $delete_href || $copy_href || $move_href || $search_href) { ?>
            <button class="bo_v_opt"><span class="sound_only"><?php e__('Write Option Button'); ?></span><i class="fa fa-ellipsis-v"></i></button>
            <ul id="bo_v_opt">
                <?php if ($update_href) { ?><li><a href="<?php echo $update_href ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <?php e__('Edit'); ?></a></li><?php } ?>
                <?php if ($delete_href) { ?><li><a href="<?php echo $delete_href ?>" onclick="del(this.href); return false;"><i class="fa fa-trash-o" aria-hidden="true"></i> <?php e__('Delete'); ?></a></li><?php } ?>
                <?php if ($copy_href) { ?><li><a href="<?php echo $copy_href ?>" onclick="board_move(this.href); return false;"><i class="fa fa-files-o" aria-hidden="true"></i> <?php e__('Copy'); ?></a></li><?php } ?>
                <?php if ($move_href) { ?><li><a href="<?php echo $move_href ?>" onclick="board_move(this.href); return false;"><i class="fa fa-arrows" aria-hidden="true"></i> <?php e__('Move'); ?></a></li><?php } ?>
                <?php if ($search_href) { ?><li><a href="<?php echo $search_href ?>"><i class="fa fa-search" aria-hidden="true"></i> <?php e__('Search'); ?></a></li><?php } ?>
            </ul>
            <?php } ?>
        </div>
        <!-- END custom -->

    </section>

    <section id="bo_v_atc">
        <h2 id="bo_v_atc_title"><?php e__('Content'); ?></h2>

        <?php if($file_view_thumbnail) { // Output images attached as files ?>
        <div id="bo_v_img">
            <?php echo $file_view_thumbnail ?>
        </div>
        <?php } ?>

        <!-- Start body content { -->
        <div id="bo_v_con"><?php echo get_view_thumbnail($view['content']); ?>
            <!-- 마감일 지난 경우 글본문 마지막에 OVERDUE 삽입 -->
            <?php if ($showOverdueAtTheBottomOfContent) { ?>
                <br/><br/>
                <p style="text-align: center; color: black; font-weight: bold; font-size: 40px"> OVERDUE </p>
            <?php } ?>
            <!-- END custom -->
        </div>
        <?php //echo $view['rich_content']; // If you are using the same code as {image:0} ?>
        <!-- } End body content -->

        <?php if ($is_signature) { ?>
            <p><?php echo $signature ?></p>
        <?php } ?>

        <!--  Start of good or bad { -->
        <?php if ( $good_href || $nogood_href) { ?>
        <div id="bo_v_act">
            <?php if ($good_href) { ?>
            <span class="bo_v_act_gng">
                <a href="<?php echo $good_href.'&amp;'.$qstr ?>" id="good_button" class="bo_v_good"><?php e__('Good'); ?> <strong><?php echo $show_good_number ?></strong></a>
                <b id="bo_v_act_good"></b>
            </span>
            <?php } ?>
            <?php if ($nogood_href) { ?>
            <span class="bo_v_act_gng">
                <a href="<?php echo $nogood_href.'&amp;'.$qstr ?>" id="nogood_button" class="bo_v_nogood"><?php e__('Bad'); ?> <strong><?php echo $show_nogood_number ?></strong></a>
                <b id="bo_v_act_nogood"></b>
            </span>
            <?php } ?>
        </div>
        <?php
            } else {
                if($board['bo_use_good'] || $board['bo_use_nogood']) {
        ?>
        <div id="bo_v_act">
            <?php if($board['bo_use_good']) { ?><span class="bo_v_good"><?php e__('Good'); ?> <strong><?php echo $show_good_number ?></strong></span><?php } ?>
            <?php if($board['bo_use_nogood']) { ?><span class="bo_v_nogood"><?php e__('Bad'); ?> <strong><?php echo $show_nogood_number ?></strong></span><?php } ?>
        </div>
        <?php
                }
            }
        ?>
        <!-- } End of good or bad -->
    </section>

    <?php if($exist_file) { ?>
    <!-- Start Attachment { -->
    <section id="bo_v_file">
        <h2><?php e__('Attached file'); ?></h2>
        <ul>
        <?php // Variable file
        for ($i=0; $i<count($view['file']); $i++) {
            if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view']) {
        ?>
            <li>
                <i class="fa fa-download" aria-hidden="true"></i>
                <a href="<?php echo $view['file'][$i]['href'];  ?>" class="view_file_download">
                    <strong><?php echo $view['file'][$i]['source'] ?></strong>
                </a>
                <?php echo $view['file'][$i]['content'] ?> (<?php echo $view['file'][$i]['size'] ?>)
                <span class="bo_v_file_cnt"><?php echo sprintf(n__('%s download', '%s downloads', $view['file'][$i]['download']), $view['file'][$i]['download']); ?> | DATE : <?php echo $view['file'][$i]['datetime']; ?></span>
            </li>
        <?php
            }
        }
         ?>
        </ul>
    </section>
    <!-- } End Attachment -->
    <?php } ?>

    <?php if($exist_link) { ?>
    <!-- Start Related Links { -->
    <section id="bo_v_link">
        <h2><?php e__('Related Links'); ?></h2>
        <ul>
        <?php for ($i=1; $i<=count($view['link']); $i++) { ?>
            <li>
                <i class="fa fa-link" aria-hidden="true"></i> <a href="<?php echo $view['link_href'][$i] ?>" target="_blank">

                    <strong><?php echo $view['link'][$i] ?></strong>
                </a>
                <span class="bo_v_link_cnt"><?php echo sprintf(n__('%s hit', '%s hits', $view['link_hit'][$i]), $view['link_hit'][$i]); ?></span>
            </li>
        <?php
            }
        }
        ?>
        </ul>
    </section>
    <!-- } End Related Links -->
</article>
<!-- } End reading posts -->

<!-- Start Post bottom Button { -->
<div id="bo_v_btm">
    <ul class="bo_v_com">
       <li><a href="<?php echo $list_href ?>" class="btn_b01 btn"><?php e__('List'); ?></a></li>
        <?php if ($reply_href) { ?><li><a href="<?php echo $reply_href ?>" class="btn_b05 btn"><?php e__('Reply'); ?></a></li><?php } ?>
        <?php if ($write_href) { ?><li><a href="<?php echo $write_href ?>" class="btn_b02 btn"><?php e__('Write'); ?></a></li><?php } ?>
    </ul>
</div>
<!-- } End Post bottom Button -->

<?php if ($prev_href || $next_href) { ?>
<div class="bo_v_nb">
    <?php if ($prev_href) { ?>
    	<a href="<?php echo $prev_href ?>" class="btn_prv">
    		<span class="sound_only"><?php e__('Prev'); ?></span>
    		<i class="fa fa-chevron-left fa-2x" aria-hidden="true"></i>
    		<?php echo cut_str($prev_wr_subject, 15); ?>
    		<?php if ($prev_wr_comment) { ?>
	    		<span class="cnt_cmt"><?php echo $prev_wr_comment; ?></span>
			<?php } ?>
    	</a><?php } ?>
    <?php // echo $prev_wr_date ?>

    <?php if ($next_href) { ?>
    	<a href="<?php echo $next_href ?>" class="btn_next">
    		<span class="sound_only"><?php e__('Next'); ?></span>
    		<?php echo cut_str($next_wr_subject, 15); ?>
    		<?php if ($next_wr_comment) { ?>
    			<span class="cnt_cmt"><?php echo $next_wr_comment; ?></span>
    		<?php } ?>
    		<i class="fa fa-chevron-right fa-2x" aria-hidden="true"></i>
    	</a><?php } ?>
    <?php // echo $next_wr_date ?>
</div>
<?php } ?>

<?php
// Display Comment
include_once(GML_BBS_PATH.'/view_comment.php');
?>
