<?php
$sub_menu = "200100";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from {$gml['member_table']} ";

$sql_search = " where (1) ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'mb_point' :
            $sql_search .= " ({$sfl} >= '{$stx}') ";
            break;
        case 'mb_level' :
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
        case 'mb_tel' :
        case 'mb_hp' :
            $sql_search .= " ({$sfl} like '%{$stx}') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

if ($is_admin != 'super')
    $sql_search .= " and mb_level <= '{$member['mb_level']}' ";

if (!$sst) {
    $sst = "mb_datetime";
    $sod = "desc";
}

$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

// 탈퇴회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_leave_date <> '' {$sql_order} ";
$row = sql_fetch($sql);
$leave_count = $row['cnt'];

// 차단회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_intercept_date <> '' {$sql_order} ";
$row = sql_fetch($sql);
$intercept_count = $row['cnt'];

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">'.__('ALL list').'</a>';

$gml['title'] = __('Member Manage');
include_once('./admin.head.php');

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$colspan = 11;
?>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt"><?php e__('Total members'); ?> </span><span class="ov_num"> <?php echo number_format($total_count) ?> </span></span>
    <a href="?sst=mb_intercept_date&amp;sod=desc&amp;sfl=<?php echo $sfl ?>&amp;stx=<?php echo $stx ?>" class="btn_ov01"> <span class="ov_txt"><?php e__('Block'); ?> </span><span class="ov_num"><?php echo number_format($intercept_count) ?></span></a>
    <a href="?sst=mb_leave_date&amp;sod=desc&amp;sfl=<?php echo $sfl ?>&amp;stx=<?php echo $stx ?>" class="btn_ov01"> <span class="ov_txt"><?php e__('Withdrawal'); ?> </span><span class="ov_num"><?php echo number_format($leave_count) ?></span></a>
</div>

<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">

<label for="sfl" class="sound_only"><?php e__('Search target'); ?></label>
<select name="sfl" id="sfl">
    <option value="mb_id"<?php echo get_selected($_GET['sfl'], "mb_id"); ?>><?php e__('Memeber ID'); ?></option>
    <option value="mb_nick"<?php echo get_selected($_GET['sfl'], "mb_nick"); ?>><?php e__('Nickname'); ?></option>
    <option value="mb_name"<?php echo get_selected($_GET['sfl'], "mb_name"); ?>><?php e__('Name'); ?></option>
    <option value="mb_level"<?php echo get_selected($_GET['sfl'], "mb_level"); ?>><?php e__('Level'); ?></option>
    <option value="mb_email"<?php echo get_selected($_GET['sfl'], "mb_email"); ?>>E-MAIL</option>
    <option value="mb_tel"<?php echo get_selected($_GET['sfl'], "mb_tel"); ?>><?php e__('Phone number'); ?></option>
    <option value="mb_hp"<?php echo get_selected($_GET['sfl'], "mb_hp"); ?>><?php e__('Mobile number'); ?></option>
    <option value="mb_point"<?php echo get_selected($_GET['sfl'], "mb_point"); ?>><?php e__('Point'); ?></option>
    <option value="mb_datetime"<?php echo get_selected($_GET['sfl'], "mb_datetime"); ?>><?php e__('Registration date'); ?></option>
    <option value="mb_ip"<?php echo get_selected($_GET['sfl'], "mb_ip"); ?>>IP</option>
    <option value="mb_recommend"<?php echo get_selected($_GET['sfl'], "mb_recommend"); ?>><?php e__('Recommender'); ?></option>
</select>
<label for="stx" class="sound_only"><?php e__('Search term'); ?><strong class="sound_only"> <?php e__('Required'); ?></strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required frm_input">
<input type="submit" class="btn_submit" value="<?php e__('Search'); ?>">

</form>

<div class="local_desc01 local_desc">
    <p>
        <?php e__('In order to prevent other members from using existing member ID when member data are deleted, member ID, name, and nickname are stored permanently.');    //회원자료 삭제 시 다른 회원이 기존 회원아이디를 사용하지 못하도록 회원아이디, 이름, 닉네임은 삭제하지 않고 영구 보관합니다. ?>
    </p>
</div>

<form name="fmemberlist" id="fmemberlist" action="./member_list_update.php" onsubmit="return fmemberlist_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="">

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $gml['title']; ?> <?php e__('List'); ?></caption>
    <thead>
    <tr>
        <th scope="col" id="mb_list_chk" rowspan="2" >
            <label for="chkall" class="sound_only"><?php e__('All Members'); ?></label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
        <th scope="col" id="mb_list_id" colspan="2"><?php echo subject_sort_link('mb_id') ?><?php e__('ID'); ?></a></th>
        <th scope="col" id="mb_list_mailc"><?php echo subject_sort_link('mb_email_certify', '', 'desc') ?><?php e__('Mail authentication'); ?></a></th>
        <th scope="col" id="mb_list_open"><?php echo subject_sort_link('mb_open', '', 'desc') ?><?php e__('Open Profile'); ?></a></th>
        <th scope="col" id="mb_list_mailr"><?php echo subject_sort_link('mb_mailling', '', 'desc') ?><?php e__('Receiving mail'); ?></a></th>
        <th scope="col" id="mb_list_auth"><?php e__('State'); ?></th>
        <th scope="col" id="mb_list_mobile"><?php e__('Mobile number'); ?></th>
        <?php start_event('admin_member_list_tbl_th1', $total_count); ?>
        <th scope="col" id="mb_list_lastcall"><?php echo subject_sort_link('mb_today_login', '', 'desc') ?><?php e__('Last Login'); ?></a></th>
        <th scope="col" id="mb_list_grp"><?php e__('Access Group'); ?></th>
        <th scope="col" rowspan="2" id="mb_list_mng"><?php e__('Edit'); ?></th>
    </tr>
    <tr>
        <th scope="col" id="mb_list_name"><?php echo subject_sort_link('mb_name') ?><?php e__('Name'); ?></a></th>
        <th scope="col" id="mb_list_nick"><?php echo subject_sort_link('mb_nick') ?><?php e__('Nickname'); ?></a></th>
        <th scope="col" id="mb_list_sms"><?php echo subject_sort_link('mb_sms', '', 'desc') ?><?php e__('SMS Received'); ?></a></th>
        <th scope="col" id="mb_list_auth1"><?php echo subject_sort_link('mb_intercept_date', '', 'desc') ?><?php e__('Access Blocking'); ?></a></th>
        <th scope="col" id="mb_list_deny"><?php echo subject_sort_link('mb_level', '', 'desc') ?><?php e__('Level'); ?></a></th>
        <?php start_event('admin_member_list_tbl_th2', $total_count); ?>
        <th scope="col" id="mb_list_tel"><?php e__('Phone number'); ?></th>
        <th scope="col" id="mb_list_join"><?php echo subject_sort_link('mb_datetime', '', 'desc') ?><?php e__('Registration date'); ?></a></th>
        <th scope="col" id="mb_list_point" colspan="2"><?php echo subject_sort_link('mb_point', '', 'desc') ?><?php ep__('Point', 'Point column'); ?></a></th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        // 접근가능한 그룹수
        $sql2 = " select count(*) as cnt from {$gml['group_member_table']} where mb_id = '{$row['mb_id']}' ";
        $row2 = sql_fetch($sql2);
        $group = '';
        if ($row2['cnt'])
            $group = '<a href="./boardgroupmember_form.php?mb_id='.$row['mb_id'].'">'.$row2['cnt'].'</a>';

        if ($is_admin == 'group') {
            $s_mod = '';
        } else {
            $s_mod = '<a href="./member_form.php?'.$qstr.'&amp;w=u&amp;mb_id='.$row['mb_id'].'" class="btn_03">'.__('Edit').'</a>';
        }
        $s_grp = '<a href="./boardgroupmember_form.php?mb_id='.$row['mb_id'].'" class="btn_02">'.__('Group').'</a>';

        $leave_date = $row['mb_leave_date'] ? $row['mb_leave_date'] : date('Ymd', GML_SERVER_TIME);
        $intercept_date = $row['mb_intercept_date'] ? $row['mb_intercept_date'] : date('Ymd', GML_SERVER_TIME);

        $mb_nick = get_sideview($row['mb_id'], get_text($row['mb_nick']), $row['mb_email'], $row['mb_homepage']);

        $mb_id = $row['mb_id'];
        $leave_msg = '';
        $intercept_msg = '';
        $intercept_title = '';
        if ($row['mb_leave_date']) {
            $leave_msg = '<span class="mb_leave_msg">'.__('Withdrawal').'</span>';
        }
        else if ($row['mb_intercept_date']) {
            $intercept_msg = '<span class="mb_intercept_msg">'.__('Blocked').'</span>';
            $intercept_title = __('Unblock');
        }
        if ($intercept_title == '')
            $intercept_title = __('Blocking');

        $address = $row['mb_zip'] ? print_address($row['mb_addr1'], $row['mb_addr2'], $row['mb_addr3']) : '';

        $bg = 'bg'.($i%2);
    ?>

    <tr class="<?php echo $bg; ?>">
        <td headers="mb_list_chk" class="td_chk" rowspan="2">
            <input type="hidden" name="mb_id[<?php echo $i ?>]" value="<?php echo $row['mb_id'] ?>" id="mb_id_<?php echo $i ?>">
            <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['mb_name']); ?> <?php echo get_text($row['mb_nick']); ?></label>
            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
        </td>
        <td headers="mb_list_id" colspan="2" class="td_name sv_use">
            <?php echo $mb_id ?>
            <?php
            //소셜계정이 있다면
            if(function_exists('social_login_link_account')){
                if( $my_social_accounts = social_login_link_account($row['mb_id'], false, 'get_data') ){

                    echo '<div class="member_social_provider sns-wrap-over sns-wrap-32">';
                    foreach( (array) $my_social_accounts as $account){     //반복문
                        if( empty($account) || empty($account['provider']) ) continue;

                        $provider = strtolower($account['provider']);
                        $provider_name = social_get_provider_service_name($provider);

                        echo '<span class="sns-icon sns-'.$provider.'" title="'.$provider_name.'">';
                        echo '<span class="ico"></span>';
                        echo '<span class="txt">'.$provider_name.'</span>';
                        echo '</span>';
                    }
                    echo '</div>';
                }
            }
            ?>
        </td>
        <td headers="mb_list_mailc"><?php echo preg_match('/[1-9]/', $row['mb_email_certify'])?'<span class="txt_true">Yes</span>':'<span class="txt_false">No</span>'; ?></td>
        <td headers="mb_list_open">
            <label for="mb_open_<?php echo $i; ?>" class="sound_only"><?php e__('Open Profile'); ?></label>
            <input type="checkbox" name="mb_open[<?php echo $i; ?>]" <?php echo $row['mb_open']?'checked':''; ?> value="1" id="mb_open_<?php echo $i; ?>">
        </td>
        <td headers="mb_list_mailr">
            <label for="mb_mailling_<?php echo $i; ?>" class="sound_only"><?php e__('Receive mail'); ?></label>
            <input type="checkbox" name="mb_mailling[<?php echo $i; ?>]" <?php echo $row['mb_mailling']?'checked':''; ?> value="1" id="mb_mailling_<?php echo $i; ?>">
        </td>
        <td headers="mb_list_auth" class="td_mbstat">
            <?php
            if ($leave_msg || $intercept_msg) echo $leave_msg.' '.$intercept_msg;
            else echo "정상";
            ?>
        </td>
        <td headers="mb_list_mobile" class="td_tel"><?php echo get_text($row['mb_hp']); ?></td>
        <?php start_event('admin_member_list_tbl_td1', $total_count, $row['mb_id']); ?>
        <td headers="mb_list_lastcall" class="td_date"><?php echo substr($row['mb_today_login'],2,8); ?></td>
        <td headers="mb_list_grp" class="td_numsmall"><?php echo $group ?></td>
        <td headers="mb_list_mng" rowspan="2" class="td_mng td_mng_s"><?php echo $s_mod ?><br><?php echo $s_grp ?></td>
    </tr>
    <tr class="<?php echo $bg; ?>">
        <td headers="mb_list_name" class="td_mbname"><?php echo get_text($row['mb_name']); ?></td>
        <td headers="mb_list_nick" class="td_name sv_use"><div><?php echo $mb_nick ?></div></td>

        <td headers="mb_list_sms">
            <label for="mb_sms_<?php echo $i; ?>" class="sound_only"><?php e__('SMS Received'); ?></label>
            <input type="checkbox" name="mb_sms[<?php echo $i; ?>]" <?php echo $row['mb_sms']?'checked':''; ?> value="1" id="mb_sms_<?php echo $i; ?>">
        </td>
        <td headers="mb_list_deny">
            <?php if(empty($row['mb_leave_date'])){ ?>
            <input type="checkbox" name="mb_intercept_date[<?php echo $i; ?>]" <?php echo $row['mb_intercept_date']?'checked':''; ?> value="<?php echo $intercept_date ?>" id="mb_intercept_date_<?php echo $i ?>" title="<?php echo $intercept_title ?>">
            <label for="mb_intercept_date_<?php echo $i; ?>" class="sound_only"><?php e__('Access Blocking'); ?></label>
            <?php } ?>
        </td>
        <td headers="mb_list_auth1" class="td_mbstat">
            <?php echo get_member_level_select("mb_level[$i]", 1, $member['mb_level'], $row['mb_level']) ?>
        </td>
        <td headers="mb_list_tel" class="td_tel"><?php echo get_text($row['mb_tel']); ?></td>
        <td headers="mb_list_join" class="td_date"><?php echo substr($row['mb_datetime'],2,8); ?></td>
        <?php start_event('admin_member_list_tbl_td2', $total_count, $row['mb_id']); ?>
        <td headers="mb_list_point" class="td_num" colspan="2"><a href="point_list.php?sfl=mb_id&amp;stx=<?php echo $row['mb_id'] ?>"><?php echo number_format($row['mb_point']) ?></a></td>

    </tr>

    <?php
    }
    if ($i == 0)
        echo "<tr><td colspan=\"".$colspan."\" class=\"empty_table\">".__('No data')."</td></tr>";
    ?>
    </tbody>
    </table>
</div>


<div class="btn_fixed_top">
    <button type="submit" name="act_button" value="modify_selection" title="<?php e__('Modify Selection'); ?>" onclick="document.pressed=this.title" class="btn btn_02"><?php e__('Modify Selection'); ?></button>
    <button type="submit" name="act_button" value="delete_selection" title="<?php e__('Delete Selection'); ?>" onclick="document.pressed=this.title" class="btn btn_02"><?php e__('Delete Selection'); ?></button>
    <?php if ($is_admin == 'super') { ?>
    <a href="./member_form.php" id="member_add" class="btn btn_01"><?php e__('Add Member'); ?></a>
    <?php } ?>
</div>


</form>

<?php echo get_paging(GML_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page='); ?>

<?php
get_localize_script('member_list',
array(
'check_msg'     =>  __('Please select at least one item to %s.'),  // %s 하실 항목을 하나 이상 선택하세요.
'delete_msg'    =>  __('Are you sure you want to delete it?'),    // 정말 삭제하시겠습니까?
'delete_pressed' => __('Delete selected'),  //선택삭제
),
true);
?>
<script>
function fmemberlist_submit(f)
{
    if (!is_checked("chk[]")) {
        alert( js_sprintf(member_list.check_msg, document.pressed) );
        return false;
    }

    if(document.pressed == member_list.delete_pressed ) {
        if(!confirm( member_list.delete_msg )) {
            return false;
        }
    }

    return true;
}
</script>

<?php
include_once ('./admin.tail.php');
?>
