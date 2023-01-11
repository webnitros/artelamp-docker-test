<?php
include_once 'setting.inc.php';

$_lang['antibot'] = 'antiBot';
$_lang['antibot_menu_desc'] = 'Parsers and Bots Blocker';
$_lang['antibot_intro_msg'] = 'You can select multiple items at once with Shift or Ctrl.';

$_lang['antibot_grid_search'] = 'Search';
$_lang['antibot_grid_actions'] = 'Actions';


$_lang['antibot_hits'] = 'List of hits';
$_lang['antibot_hit_id'] = 'Id';
$_lang['antibot_hit_hits'] = 'Hits';
$_lang['antibot_hit_url'] = 'Transition';
$_lang['antibot_hit_context'] = 'Context';
$_lang['antibot_hit_url_from'] = 'From';
$_lang['antibot_hit_method'] = 'Method';
$_lang['antibot_hit_guest_id'] = 'Guest Id';
$_lang['antibot_hit_user_id'] = 'Id user';
$_lang['antibot_hit_username'] = 'User';
$_lang['antibot_hit_code_response'] = 'Code XXX';
$_lang['antibot_hit_ip'] = 'IP';
$_lang['antibot_hit_user_agent'] = 'USER AGENT';
$_lang['antibot_hit_blocked'] = 'Blocked';
$_lang['antibot_hit_createdon'] = 'First run';
$_lang['antibot_hit_updatedon'] = 'Last run';

$_lang['antibot_hit_remove'] = 'Remove Hit';
$_lang['antibot_hits_remove'] = 'Remove Hits';
$_lang['antibot_hit_remove_confirm'] = 'Are you sure you want to delete this Hit?';
$_lang['antibot_hits_remove_confirm'] = 'Are you sure you want to delete these Hits?';

$_lang['antibot_hit_err_nf'] = 'Hit not found.';
$_lang['antibot_hit_err_ns'] = 'Hit not specified.';
$_lang['antibot_hit_err_remove'] = 'Error deleting Hit.';
$_lang['antibot_hit_err_save'] = 'Error while saving Hit.';


$_lang['antibot_hit_all_remove'] = 'Clear all hits';
$_lang['antibot_hit_all_remove_confirm'] = 'Are you sure you want to delete all hits?';
$_lang['antibot_hit_btn_remove_all'] = 'Clear all hits';



$_lang['antibot_guests'] = 'Visitor List';
$_lang['antibot_guest_id'] = 'Id';
$_lang['antibot_guest_hits'] = 'Hits';
$_lang['antibot_guest_user_id'] = 'Id user';
$_lang['antibot_guest_username'] = 'User';
$_lang['antibot_guest_ip'] = 'IP';
$_lang['antibot_guest_user_agent'] = 'USER AGENT';
$_lang['antibot_guest_createdon'] = 'First run';
$_lang['antibot_guest_updatedon'] = 'Last run';
$_lang['antibot_guest_fake'] = 'Fake bot';


$_lang['antibot_guest_remove'] = 'Delete Users';
$_lang['antibot_guests_remove'] = 'Delete By User';
$_lang['antibot_guest_remove_confirm'] = 'Are you sure you want to delete this Account?';
$_lang['antibot_guests_remove_confirm'] = 'Are you sure you want to delete these Users?';
$_lang['antibot_guest_active'] = 'Enabled';

$_lang['antibot_guest_err_nf'] = 'User not found.';
$_lang['antibot_guest_err_ns'] = 'User not specified.';
$_lang['antibot_guest_err_remove'] = 'Error deleting the Account.';
$_lang['antibot_guest_err_save'] = 'Error saving the Account.';

$_lang['antibot_guest_all_remove'] = 'Clear all hits';
$_lang['antibot_guest_all_remove_confirm'] = 'Are you sure you want to delete all users? Together with the users will be deleted and all hits. ';
$_lang['antibot_guest_btn_remove_all'] = 'Clear all users';


// Stop List
$_lang['antibot_stoplists'] = 'Stop Lists';
$_lang['antibot_stoplist_id'] = 'Id';
$_lang['antibot_stoplist_user_agent'] = 'USER AGENT';
$_lang['antibot_stoplist_user_agent_desc'] = 'You can keep some text from the bot / user user agent. For example, "compatible; AhrefsBot /" or "compatible; SemrushBot" ';
$_lang['antibot_stoplist_user_id'] = 'Id user';
$_lang['antibot_stoplist_context'] = 'Context';
$_lang['antibot_stoplist_context_desc'] = 'Select the context for which you want to check. Or leave everything to account for all contexts';
$_lang['antibot_stoplist_comment'] = 'Comment for administrator';
$_lang['antibot_stoplist_message'] = 'Returned Message';
$_lang['antibot_stoplist_redirect_url'] = 'Redirect page';
$_lang['antibot_stoplist_username'] = 'User';
$_lang['antibot_stoplist_ip_bloks'] = 'Block IP';
$_lang['antibot_stoplist_ip_bloks_desc'] = 'Enter the IP address you want to block. For example, 222.221.221.121 ';
$_lang['antibot_stoplist_ip_1'] = 'IP';
$_lang['antibot_stoplist_ip_2'] = 'IP';
$_lang['antibot_stoplist_ip_3'] = 'IP';
$_lang['antibot_stoplist_ip_4'] = 'IP';
$_lang['antibot_stoplist_context_all'] = 'All Contexts';
$_lang['antibot_stoplist_active'] = 'Active';
$_lang['antibot_stoplist_message_value'] = 'Access denied';
$_lang['antibot_stoplist_comment_value'] = 'Blocking the bot';
$_lang['antibot_stoplist_redirect_url_desc'] = 'If you specify the page to redirect, then this page will be displayed in place of the message';

$_lang['antibot_stoplist_create'] = 'Add stop list';
$_lang['antibot_stoplist_remove'] = 'Delete Stop List';
$_lang['antibot_stoplists_remove'] = 'Delete Stop Lists';
$_lang['antibot_stoplist_remove_confirm'] = 'Are you sure you want to delete this Stop List?';
$_lang['antibot_stoplists_remove_confirm'] = 'Are you sure you want to delete these Stoplists?';
$_lang['antibot_stoplist_active'] = 'Enabled';
$_lang['antibot_stoplist_enable'] = 'Include stop list';
$_lang['antibot_stoplists_enable'] = 'Enable stop lists';
$_lang['antibot_stoplist_disable'] = 'Disable stop list';
$_lang['antibot_stoplists_disable'] = 'Disable stop lists';

$_lang['antibot_stoplist_err_nf'] = 'Stop list not found.';
$_lang['antibot_stoplist_err_ns'] = 'Stop list is not specified.';
$_lang['antibot_stoplist_err_remove'] = 'Error deleting Stop List.';
$_lang['antibot_stoplist_err_save'] = 'Error saving the stop list.';
$_lang['antibot_stoplist_err_ae'] = 'USER AGENT with the same parameters already exists';
$_lang['antibot_stoplist_username_guest'] = 'Guest';


// Fake bot
$_lang['antibot_guest_fake_yandex'] = 'Check Yandex bot';
$_lang['antibot_guest_fake_mail'] = 'Check Mail Bot';
$_lang['antibot_guest_fake_google'] = 'Check Google bot';