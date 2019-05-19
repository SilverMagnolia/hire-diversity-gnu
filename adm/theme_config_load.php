<?php
$sub_menu = "100280";
include_once('./_common.php');
include_once(GML_LIB_PATH.'/json.lib.php');

$data = array();
$data['error'] = '';

$data['error'] = auth_check($auth[$sub_menu], 'w', true);
if($data['error'])
    die(json_encode($data));

if(!$config['cf_theme']) {
    $data['error'] = __('No theme is in use.');
    die(json_encode($data));
}

$theme_dir = get_theme_dir();
if(!in_array($config['cf_theme'], $theme_dir)) {
    $data['error'] = sprintf(__('The theme %s is not an installed theme.'), $config['cf_theme']);
    die(json_encode($data));
}

$type = $_POST['type'];
$arr_type = array('board', 'conf_skin', 'conf_member');
if(!in_array($type, $arr_type)) {
    $data['error'] = __('Please use the correct method.');
    die(json_encode($data));
}

if($type == 'board') {
    $keys = array('bo_gallery_cols', 'bo_gallery_width', 'bo_gallery_height', 'bo_mobile_gallery_width', 'bo_mobile_gallery_height', 'bo_image_width');
    $tconfig = get_theme_config_value($config['cf_theme'], implode(',', $keys));

    $i = 0;
    foreach($keys as $val) {
        if($tconfig[$val]) {
            $data[$val] = (int)preg_replace('#[^0-9]#', '', $tconfig[$val]);
            $i++;
        }
    }

    if($i == 0)
        $data['error'] = __('There are no board image settings to apply.');
} else if($type == 'conf_skin') {
    $keys = array('cf_new_skin', 'cf_mobile_new_skin', 'cf_search_skin', 'cf_mobile_search_skin', 'cf_connect_skin', 'cf_mobile_connect_skin', 'cf_faq_skin', 'cf_mobile_faq_skin');

    $tconfig = get_theme_config_value($config['cf_theme'], implode(',', $keys));

    $i = 0;
    foreach($keys as $val) {
        if($tconfig[$val]) {
            $data[$val] = preg_match('#^theme/.+$#', $tconfig[$val]) ? $tconfig[$val] : 'theme/'.$tconfig[$val];
            $i++;
        }
    }

    if($i == 0)
        $data['error'] = __('There are no default environment skin settings to apply.');
} else if($type == 'conf_member') {
    $keys = array('cf_member_skin', 'cf_mobile_member_skin');

    $tconfig = get_theme_config_value($config['cf_theme'], implode(',', $keys));

    $i = 0;
    foreach($keys as $val) {
        if($tconfig[$val]) {
            $data[$val] = preg_match('#^theme/.+$#', $tconfig[$val]) ? $tconfig[$val] : 'theme/'.$tconfig[$val];
            $i++;
        }
    }

    if($i == 0)
        $data['error'] = __('There are no default environment member skin settings to apply.');
}

die(json_encode($data));
?>