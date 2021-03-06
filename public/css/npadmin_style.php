<?
$NPADMIN_PATH = "../../";
require_once($NPADMIN_PATH."private/include/common.php");
header("Content-type: text/css");
?>
html {
    background-color: <?= npadmin_setting('NP-ADMIN_LNF', 'BODY_BG_COLOR') ?>;            
}

#main_body {
    margin: 20px;
    margin-top: 44px;
    background-color: #FFFFFF; 
    padding: 15px;
    border: #000000 1px solid;
    /*min-height: 80%;
    height: auto !important;
    height: 80%;*/
}

#mainTabs div.yui-content {
    padding: 15px;
}

.page_title {
    font-size: 22px;
    margin-top: 5px;
    margin-bottom: 15px;
    background-color: <?= npadmin_setting('NP-ADMIN_LNF', 'TITLE_BG_COLOR') ?>;         
    border: 1px solid black;
    padding: 10px;
}

.buttonBox {
    border: 1px solid black;
    margin-bottom:10px;
    padding:10px;
    background-color: rgb(190,211,206);
}

table .npadmin_login {
    margin: 10px;
}

table .npadmin_login td {
    padding: 10px;
    padding-bottom: 0px;
}
