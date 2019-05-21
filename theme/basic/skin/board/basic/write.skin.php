<?php
if (!defined('_GNUBOARD_')) exit; // Unable to access direct pages
include_once(GML_PLUGIN_PATH.'/jquery-ui/datepicker.php');
// add_stylesheet('css file path', Output order); Smaller numbers printed first
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
?>

<section id="bo_w">
    <h2 class="sound_only"><?php echo $gml['title']; ?></h2>

    <!-- Start creating / modifying posts { -->
    <form name="fwrite" id="fwrite" action="<?php echo $action_url ?>" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off" style="width:<?php echo $width; ?>">
    <input type="hidden" name="uid" value="<?php echo get_uniqid(); ?>">
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

    <?php if ($is_category) { ?>
    <div class="bo_w_select write_div">
        <label for="ca_name" class="sound_only"><?php e__('Category'); ?><strong><?php e__('Required'); ?></strong></label>
        <select name="ca_name" id="ca_name" required>
            <option value=""><?php e__('Select a Category'); ?></option>
            <?php echo $category_option ?>
        </select>
    </div>
    <?php } ?>

    <div class="bo_w_info write_div">
    <?php if ($is_name) { ?>
        <label for="wr_name" class="sound_only"><?php e__('Name'); ?><strong><?php e__('Required'); ?></strong></label>
        <input type="text" name="wr_name" value="<?php echo $name ?>" id="wr_name" required class="frm_input required" placeholder="<?php e__('Name'); ?>">
    <?php } ?>

    <?php if ($is_password) { ?>
        <label for="wr_password" class="sound_only"><?php e__('Password'); ?><strong><?php e__('Required'); ?></strong></label>
        <input type="password" name="wr_password" id="wr_password" <?php echo $password_required ?> class="frm_input <?php echo $password_required ?>" placeholder="<?php e__('Password'); ?>">
    <?php } ?>

    <?php if ($is_email) { ?>
    	<label for="wr_email" class="sound_only"><?php e__('Email'); ?></label>
    	<input type="text" name="wr_email" value="<?php echo $email ?>" id="wr_email" class="frm_input email" placeholder="<?php e__('Email'); ?>">
    <?php } ?>
    <?php if ($is_homepage) { ?>
        <label for="wr_homepage" class="sound_only"><?php e__('Homepage'); ?></label>
        <input type="text" name="wr_homepage" value="<?php echo $homepage ?>" id="wr_homepage" class="frm_input" size="50" placeholder="<?php e__('Homepage'); ?>">
    <?php } ?>
    </div>

    <?php if ($option) { ?>
    <div class="write_div">
        <span class="sound_only"><?php e__('Option'); ?></span>
        <?php echo $option ?>
    </div>
    <?php } ?>

    <div class="bo_w_tit write_div">
        <label for="wr_subject" class="sound_only"><?php e__('Subject'); ?><strong><?php e__('Required'); ?></strong></label>

        <div id="autosave_wrapper">
            <input type="text" name="wr_subject" value="<?php echo $subject ?>" id="wr_subject" required class="frm_input full_input required" size="50" maxlength="255" placeholder="<?php e__('Subject'); ?>">
            <?php if ($is_member) { // Temporary stored posts ?>
            <?php print_l10n_js_text('autosave_js'); ?>
            <script src="<?php echo GML_JS_URL; ?>/autosave.js"></script>
            <?php if($editor_content_js) echo $editor_content_js; ?>
            <button type="button" id="btn_autosave" class="btn_frmline">
            	<span class="sound_only"><?php e__('Temporary stored posts'); ?></span>
            	<!-- <span id="autosave_count"><?php echo $autosave_count; ?></span> -->
            	<i class="fa fa-floppy-o" aria-hidden="true"></i>
            </button>
            <div id="autosave_pop">
            	<div class="autosave_div">
					<strong><?php e__('Temporary list of saved posts'); ?></strong>
                	<ul></ul>  
				</div>
                <button type="button" class="autosave_close"><span class="sound_only"><?php e__('Close'); ?></span><i class="fa fa-times" aria-hidden="true"></i></button>
            </div>
            <?php } ?>
        </div>
    </div>

    <div class="write_div">
        <label for="wr_content" class="sound_only"><?php e__('Content'); ?><strong><?php e__('Required'); ?></strong></label>
        <div class="wr_content <?php echo $dhtml_editor_class ?>">
            <?php if($use_character_number) { ?>
            <!-- When using minimum / maximum number of characters -->
            <p id="char_count_desc"><?php echo sprintf(__('This bulletin board can be written at least <strong>%s</strong> characters or up to <strong>%s</strong> characters.'), $write_min, $write_max); ?></p>
            <?php } ?>
            <?php echo $editor_html; // When using the editor, expose it as an editor or as a textarea ?>
            <?php if($use_character_number) { ?>
            <div id="char_count_wrap"><span id="char_count"></span><?php e__('Length'); ?></div>
            <?php } ?>
        </div>
    </div>

    <?php for ($i=1; $is_link && $i<=GML_LINK_COUNT; $i++) { ?>
    <div class="bo_w_link write_div">
        <label for="wr_link<?php echo $i ?>"><i class="fa fa-link" aria-hidden="true"></i><span class="sound_only"> <?php e__('Link #'); ?><?php echo $i ?></span></label>
        <input type="text" name="wr_link<?php echo $i ?>" value="<?php if($w=="u"){echo$write['wr_link'.$i];} ?>" id="wr_link<?php echo $i ?>" class="frm_input full_input" size="50" placeholder=" <?php e__('Please enter a link.'); ?>">
    </div>
    <?php } ?>

    <?php for ($i=0; $is_file && $i<$file_count; $i++) { ?>
    <div class="bo_w_flie write_div">
        <div class="file_wr write_div">
            <label for="bf_file_<?php echo $i+1 ?>" class="lb_icon"><i class="fa fa-download" aria-hidden="true"></i><span class="sound_only"> <?php e__('File #'); ?><?php echo $i+1 ?></span></label>
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

    <!-- 마감일 입력 필드-->
    <div >
        <span style="font-weight:bold; font-size: 15px; color: #555">
            <br/>&nbsp;- 마감일 입력
        </span>
        <br/>
        <br/>
        <label for='deadline_date'>&nbsp;날짜</label>
        <input type="text" name="deadline_date" id="deadline_date" style="height: 25px; width:100px; text-align: center;" readonly/>

        <label for='deadline_hour'>&nbsp;시간</label>
        <input type="number" name="deadline_hour" id="deadline_hour" min=0 max=23 style="height: 25px; width:60px; text-align: center;"/>

        <label for='deadline_min'>&nbsp;분</label>
        <input type="number" name="deadline_min" id="deadline_min" min=0 max=59 style="height: 25px; width:60px; text-align: center;"/>
        &nbsp;&nbsp;&nbsp;
        <button type="button" id="deadline_clear" style="width: 60px; height: 20px;">
            Clear
        </button>
    </div>
    <!-- END custom -->

    <?php if ($is_use_captcha) { // USE Captcha  ?>
    <div class="write_div">
        <?php echo $captcha_html ?>
    </div>
    <?php } ?>

    <div class="btn_confirm write_div">
        <a href="<?php echo get_pretty_url($bo_table); ?>" class="btn_cancel btn"><?php e__('Cancel'); ?></a>
        <input type="submit" value="<?php e__('Save'); ?>" id="btn_submit" accesskey="s" class="btn_submit btn">
    </div>
    </form>

    <script>
        $(function(){
            $("#deadline_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", minDate: "+0d;"});
        });

        $(document).ready(function() { 
            const clearBtn = document.getElementById('deadline_clear');
            clearBtn.onclick = () => {
                document.getElementById('deadline_date').value = '';
                document.getElementById('deadline_hour').value = '';
                document.getElementById('deadline_min').value = '';
            };
        });
    </script>

</section>

<!-- } End creating / modifying posts -->
