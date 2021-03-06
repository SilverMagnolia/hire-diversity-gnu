<?php
if (!defined('_GNUBOARD_')) exit; // Unable to access direct pages
include_once(GML_PLUGIN_PATH.'/jquery-ui/datepicker.php');
// add_stylesheet('css file path', Output order); Smaller numbers printed first
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
?>

<section id="bo_w">

    <!-- Start creating / modifying posts { -->
    <form name="fwrite" id="fwrite" action="<?php echo $action_url ?>" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
    <input type="hidden" name="w" value="<?php echo $w ?>">
    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
    <input type="hidden" name="wr_id" value="<?php echo $wr_id ?>">
    <input type="hidden" name="sca" value="<?php echo $sca ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="spt" value="<?php echo $spt ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <?php echo $option_hidden; ?>

    <div class="form_01 write_div">
        <h2 class="sound_only"><?php echo $gml['title'] ?></h2>

        <?php if ($is_category) { ?>
        <div class="bo_w_select write_div">
            <label for="ca_name" class="sound_only"><?php e__('Category'); ?><strong><?php e__('Required'); ?></strong></label>
            <select id="ca_name" name="ca_name" required>
                <option value=""><?php e__('Select a Category'); ?></option>
                <?php echo $category_option ?>
            </select>
        </div>
        <?php } ?>

        <?php if ($is_name) { ?>
        <div class="write_div">
            <label for="wr_name" class="sound_only"><?php e__('Name'); ?><strong><?php e__('Required'); ?></strong></label>
            <input type="text" name="wr_name" value="<?php echo $name ?>" id="wr_name" required class="frm_input full_input required" maxlength="20" placeholder="<?php e__('Name'); ?>">
        </div>
        <?php } ?>

        <?php if ($is_password) { ?>
        <div class="write_div">
            <label for="wr_password" class="sound_only"><?php e__('Password'); ?><strong><?php e__('Required'); ?></strong></label>
            <input type="password" name="wr_password" id="wr_password" <?php echo $password_required ?> class="frm_input full_input <?php echo $password_required ?>" maxlength="20" placeholder="<?php e__('Password'); ?>">
        </div>
        <?php } ?>

        <?php if ($is_email) { ?>
        <div class="write_div">
            <label for="wr_email" class="sound_only"><?php e__('Email'); ?></label>
            <input type="email" name="wr_email" value="<?php echo $email ?>" id="wr_email" class="frm_input full_input  email" maxlength="100" placeholder="<?php e__('Email'); ?>">
        </div>
        <?php } ?>

        <?php if ($is_homepage) { ?>
        <div class="write_div">
            <label for="wr_homepage" class="sound_only"><?php e__('Homepage'); ?></label>
            <input type="text" name="wr_homepage" value="<?php echo $homepage ?>" id="wr_homepage" class="frm_input full_input " placeholder="<?php e__('Homepage'); ?>">
        </div>
        <?php } ?>

        <?php if ($option) { ?>
        <div class="write_div">
            <span class="sound_only"><?php e__('Option'); ?></span>
            <?php echo $option ?>
        </div>
        <?php } ?>

        <div class="bo_w_tit write_div">
            <label for="wr_subject" class="sound_only"><?php e__('Subject'); ?><strong><?php e__('Required'); ?></strong></label>
            <input type="text" name="wr_subject" value="<?php echo $subject ?>" id="wr_subject" required class="frm_input full_input required" placeholder="<?php e__('Subject'); ?>">
        </div>

        <!-- 마감일 입력 필드-->
        <div >
            <span style="font-weight:bold; font-size: 15px; color: #555">
                <br/>&nbsp;Due Date
            </span>
            <br/>
            <br/>
            <label for='deadline_date'></label>
            <input type="text" name="deadline_date" id="deadline_date" value="<?php echo $deadline_date ?>" style="height: 25px; width:100px; text-align: center;" placeholder="select date" readonly/>

           <!--  <label for='deadline_hour'></label>
            <input type="number" name="deadline_hour" id="deadline_hour" min=0 max=23 value="<?php echo $deadline_hour ?>" style="height: 25px; width:60px; text-align: center;" placeholder="hour" />

            <label for='deadline_min'></label>
            <input type="number" name="deadline_min" id="deadline_min" min=0 max=59 value="<?php echo $deadline_min ?>" style="height: 25px; width:60px; text-align: center;" placeholder="minute"/> -->
            &nbsp;&nbsp;&nbsp;
            <button type="button" id="deadline_clear" style="width: 60px; height: 20px;">
                Clear
            </button>
            <div style="height:10px"></div>
        </div>

        <!-- 롤링 베이스 체크 박스 -->
        <span class="lb_block">
            <label for="is_rolling_base" style="font-weight:bold; font-size: 15px; color: #555">Rolling Base</label>
        </span>
        &nbsp;&nbsp;
        <input type="checkbox" name="is_rolling_base" id="is_rolling_base" <?php if ($is_rolling_base) { ?> checked <?php } ?>>
        <br/><br/><br/>
        <!-- END custom -->

        <div class="write_div">
            <label for="wr_content" class="sound_only"><?php e__('Content'); ?><strong><?php e__('Required'); ?></strong></label>
            <?php if($use_character_number) { ?>
            <!-- When using minimum / maximum number of characters -->
            <p id="char_count_desc"><?php echo sprintf(__('This bulletin board can be written at least <strong>%s</strong> characters or up to <strong>%s</strong> characters.'), $write_min, $write_max); ?></p>
            <?php } ?>
            <?php echo $editor_html; // When using the editor, expose it as an editor or as a textarea ?>
            <?php if($use_character_number) { ?>
            <!-- When using minimum / maximum number of characters -->
            <div id="char_count_wrap"><span id="char_count"></span><?php e__('Length'); ?></div>
            <?php } ?>
        </div>

        <?php for ($i=1; $is_link && $i<=GML_LINK_COUNT; $i++) { ?>
        <div class="bo_w_link write_div">
            <label for="wr_link<?php echo $i ?>"><i class="fa fa-link" aria-hidden="true"></i> <span class="sound_only"><?php e__('Link #'); ?><?php echo $i ?></span></label>
            <input type="text" name="wr_link<?php echo $i ?>" value="<?php if($w=="u"){echo $write['wr_link'.$i];} ?>" id="wr_link<?php echo $i ?>" class="frm_input wr_link" placeholder=" <?php e__('Please enter a link.'); ?>">
        </div>
        <?php } ?>

        <?php for ($i=0; $is_file && $i<$file_count; $i++) { ?>
        <div class="bo_w_flie write_div">
            <div class="file_wr write_div">
                <label for="bf_file_<?php echo $i+1 ?>" class="lb_icon"><i class="fa fa-download" aria-hidden="true"></i><span class="sound_only"><?php e__('File #'); ?><?php echo $i+1 ?></span></label>
                <input type="file" name="bf_file[]" id="bf_file_<?php echo $i+1 ?>" title="<?php echo sprintf(__('File Attachment %s : Upload capacity less than %s'), $i+1, $upload_max_filesize); ?>" class="frm_file">
            </div>
            <?php if ($is_file_content) { ?>
            <input type="text" name="bf_content[]" value="<?php echo $file_bf_content[$i] ?>" title="<?php e__('Enter file description.'); ?>" class="full_input frm_input" size="50" placeholder="<?php e__('Enter file description.'); ?>">
            <?php } ?>

            <?php if($file_deletable[$i]) { ?>
            <span class="file_del">
                <input type="checkbox" id="bf_file_del<?php echo $i ?>" name="bf_file_del[<?php echo $i;  ?>]" value="1"> <label for="bf_file_del<?php echo $i ?>"><?php echo $file_delete_lable[$i]  ?> <?php e__('Delete File'); ?></label>
            </span>
            <?php } ?>
        </div>
        <?php } ?>

        <?php if ($is_use_captcha) { // USE Captcha ?>
        <div class="write_div">
            <span class="sound_only"><?php e__('Captcha') ?></span>
            <?php echo $captcha_html ?>
        </div>
        <?php } ?>
    </div>

    <div class="bo_w_btn">
        <a href="<?php echo get_pretty_url($bo_table) ?>" class="btn_cancel"><?php e__('Cancel'); ?></a>
        <button type="submit" id="btn_submit" class="btn_submit" accesskey="s"><?php e__('Save'); ?></button>
    </div>
    </form>
    <!-- } End creating / modifying posts -->
    <!-- Custom script -->
    <script>

        // 캘린더
        $(function(){
            $("#deadline_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99"});
        });

        $(document).ready(function() { 

            // 마감일 텍스트 필드 리셋 버튼 온클릭
            const clearBtn = document.getElementById('deadline_clear');
            clearBtn.onclick = () => {
                document.getElementById('deadline_date').value = '';
                document.getElementById('deadline_hour').value = '';
                document.getElementById('deadline_min').value = '';
            };

            // 마감일-시간 입력된 값 유효성 체크(0 ~ 23)
            // document.getElementById('deadline_hour').onkeyup = function(event) {
            //     const n = parseInt(this.value);

            //     if ((isNaN(n)) || (n > 23) || (n < 0)) {
            //         this.value = '';
            //     }
            // }

            // // 마감일-분 입력된 값 유효성 체크(0 ~ 59)
            // document.getElementById('deadline_min').onkeyup = function(event) {
            //     const n = parseInt(this.value);

            //     if ((isNaN(n)) || (n > 59) || (n < 0)) {
            //         this.value = '';
            //     }
            // }
        });
    </script>
    <!-- END custom -->
</section>
