<?php

// Menu
$RootMenu = new cMenu("RootMenu", TRUE);
$RootMenu->AddMenuItem(21, "mi_brand_transactions", $Language->MenuPhrase("21", "MenuText"), "brand_transactionslist.php", -1, "", AllowListMenu('{B6A7A568-E606-4B9C-A82F-C80372444DA0}brand_transactions'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(22, "mi_other_brands", $Language->MenuPhrase("22", "MenuText"), "other_brandslist.php", -1, "", AllowListMenu('{B6A7A568-E606-4B9C-A82F-C80372444DA0}other_brands'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(17, "mi_store_transaction_view2", $Language->MenuPhrase("17", "MenuText"), "store_transaction_view2list.php", -1, "", AllowListMenu('{B6A7A568-E606-4B9C-A82F-C80372444DA0}store_transaction_view2'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(1, "mi_content", $Language->MenuPhrase("1", "MenuText"), "contentlist.php", -1, "", AllowListMenu('{B6A7A568-E606-4B9C-A82F-C80372444DA0}content'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(2, "mi_location_target", $Language->MenuPhrase("2", "MenuText"), "location_targetlist.php", -1, "", AllowListMenu('{B6A7A568-E606-4B9C-A82F-C80372444DA0}location_target'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(3, "mi_merchandizer_location", $Language->MenuPhrase("3", "MenuText"), "merchandizer_locationlist.php", -1, "", AllowListMenu('{B6A7A568-E606-4B9C-A82F-C80372444DA0}merchandizer_location'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(4, "mi_messages", $Language->MenuPhrase("4", "MenuText"), "messageslist.php", -1, "", AllowListMenu('{B6A7A568-E606-4B9C-A82F-C80372444DA0}messages'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(5, "mi_product_categories", $Language->MenuPhrase("5", "MenuText"), "product_categorieslist.php", -1, "", AllowListMenu('{B6A7A568-E606-4B9C-A82F-C80372444DA0}product_categories'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(6, "mi_products", $Language->MenuPhrase("6", "MenuText"), "productslist.php", -1, "", AllowListMenu('{B6A7A568-E606-4B9C-A82F-C80372444DA0}products'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(7, "mi_stores", $Language->MenuPhrase("7", "MenuText"), "storeslist.php", -1, "", AllowListMenu('{B6A7A568-E606-4B9C-A82F-C80372444DA0}stores'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(8, "mi_transactions", $Language->MenuPhrase("8", "MenuText"), "transactionslist.php", -1, "", AllowListMenu('{B6A7A568-E606-4B9C-A82F-C80372444DA0}transactions'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(9, "mi_user", $Language->MenuPhrase("9", "MenuText"), "userlist.php", -1, "", AllowListMenu('{B6A7A568-E606-4B9C-A82F-C80372444DA0}user'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(10, "mi_userlevelpermissions", $Language->MenuPhrase("10", "MenuText"), "userlevelpermissionslist.php", -1, "", IsAdmin(), FALSE, FALSE, "");
$RootMenu->AddMenuItem(11, "mi_userlevels", $Language->MenuPhrase("11", "MenuText"), "userlevelslist.php", -1, "", IsAdmin(), FALSE, FALSE, "");
$RootMenu->AddMenuItem(12, "mi_location_target_view", $Language->MenuPhrase("12", "MenuText"), "location_target_viewlist.php", -1, "", AllowListMenu('{B6A7A568-E606-4B9C-A82F-C80372444DA0}location_target_view'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(15, "mi_target_report_php", $Language->MenuPhrase("15", "MenuText"), "target_report.php", -1, "", AllowListMenu('{B6A7A568-E606-4B9C-A82F-C80372444DA0}target_report.php'), FALSE, TRUE, "");
$RootMenu->AddMenuItem(16, "mi_connectme_php", $Language->MenuPhrase("16", "MenuText"), "connectme.php", -1, "", AllowListMenu('{B6A7A568-E606-4B9C-A82F-C80372444DA0}connectme.php'), FALSE, TRUE, "");
echo $RootMenu->ToScript();
?>
<div class="ewVertical" id="ewMenu"></div>
