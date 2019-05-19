<?php
define('_INDEX_', true);
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (GML_IS_MOBILE) {
    include_once(GML_THEME_MOBILE_PATH.'/index.php');
    return;
}

include_once(GML_THEME_PATH.'/head.php');
// include 'pic_tab' skin's javascript file.
if(file_exists(GML_SKIN_PATH.'/latest/pic_tab/latest.js.php')) {
    include_once(GML_SKIN_PATH.'/latest/pic_tab/latest.js.php');
}
?>

<h2 class="sound_only"><?php e__('Latest articles');  //최신글 ?></h2>

<div class="latest_wr">
<!-- Latest start { -->

    <?php
    //  최신글
    $sql = " select bo_table
                from `{$gml['board_table']}` a left join `{$gml['group_table']}` b on (a.gr_id=b.gr_id)
                where a.bo_device <> 'mobile' ";
    if(!$is_admin)
        $sql .= " and a.bo_use_cert = '' ";
    $sql .= " and a.bo_table not in ('notice', 'gallery') ";     //공지사항과 갤러리 게시판은 제외
    $sql .= " order by b.gr_order, a.bo_order ";
    $result = sql_query($sql);
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        if ($i%2==1) $lt_style = "margin-left:2%";
        else $lt_style = "";
    ?>
    <div style="float:left;<?php echo $lt_style ?>" class="lt_wr">
        <?php
        // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
        // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수, 캐시지속시간, 옵션);
        // 테마의 스킨을 사용하려면 basic 과 같이 지정
        $options = '70,45';
        echo latest('basic', $row['bo_table'], 6, 24, 1, $options);
        ?>
    </div>
    <?php
    }
    ?>
    <!-- } Latest end -->
</div>



<?php
include_once(GML_THEME_PATH.'/tail.php');
?>
