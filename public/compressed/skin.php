<?php header("Content-type: text/css"); 

define('BUCKET_NAME', "http://bucket1." );
define('AMAZON_S3_URL', "s3.amazon.com/" );
$hasCdn = 1;
$urlStart = '';
$urlEnd = '';
if($hasCdn)
{
    $urlStart = 'md5("'.BUCKET_NAME.AMAZON_S3_URL;
    $urlEnd = '")';
}
function getEncriptedUrl($base_url, $str )
    {   $str = trim($str);
        $hasCdn = GP_ToolKit::getHasCdn();
        if( (""!= $str) && ("1" == $hasCdn )) {
            $azUrl = 'http:\\\\'.BUCKET_NAME.'.'. AMAZON_S3_URL;
            $jpg = explode('.', $str);
            $ext = '.'.$jpg[1];
            $str = $jpg[0];
        }
       // echo '<<br> str in  getEncriptedUrl =' . $hasCdn ? md5($str).$ext : $str;
        return $hasCdn ? $azUrl.'\\'.md5(trim($str)).$ext : trim($base_url).trim($str);
      }

echo $urlStart.'sajad'. $urlEnd;
//require_once ('../../../../../../gopogo/library/GP_ToolKit.php');
//$config = Zend_Registry::get('config');
//echo '<pre>';
//print_r($config);
//echo '<br>themePath from skin.php=' . $path  = $this->baseUrl();
//$path  = GP_ToolKit::getBasePath();
// echo '<br>themePath from skin.php=' . $themePath = THEME_URL;
 //$finalImageUrl ='';
 //$finalCssUrl ='';

//die();
?>
background-image:url(<?php echo   md5("/themes/default/images/bg-left"); ?>.png )

background-image:url(<?php  $urlStart ?>/themes/default/images/bg-left<?php  $urlEnd;?>.png )
body
{
    margin:0px;
    padding:0px;
    font-family:Arial, Helvetica, sans-serif;
    background-color:#e1dfd5;
    background-image:url(/themes/default/images/bg-left.png);
    background-repeat:repeat-x;
    background-attachment:fixed;
}
#master
{
    margin:0px;
    padding:0px;
    width:100%;
    height:auto;
}
#submaster
{
    margin:0px;
    padding:0px;
    width:1000px;
    height:100%;
}
.header
{
    position:fixed;
    width:100%;
    height:113px;
    top:0px;
    margin:0 auto;
    z-index: 1;

}
.head-mainbg
{
    position:relative;
    float:left;
    background-image:url(/themes/default/images/topbg01.png);
    background-repeat:no-repeat;
    width:1000px;
    height:113px;
}

.search
{
	position:relative;
	float:left;
	width:150px;;
	height:25px;
	top:52px;
	left:114px;
	font-size:15px;
}
.search input
{
	width:207px;
	height:16px;
	font-size:14px;
	font-weight:600;
	color:#CCC;
	text-align:center;
}

.submenu
{
	position:absolute;
	float:left;
	width:750px;
	height:25px;
	top:76px;
	left:200px;
	border:0px solid red;
	color:#FFF;
	font-size:15px;
	font-weight:600;
	line-height:20px;
	text-decoration:none;
}
.submenu a
{
	border:0px solid red;
	color:#FFF;
	font-size:15px;
	font-weight:600;
	line-height:20px;
	text-decoration:none;
}

.submenu-box
{
	position:relative;
	float:left;
	width:auto;
	height:auto;
	padding:4px;
}
.selected .submenu-left
{
	position:relative;
	float:left;
	width:7px;
	height:22px;
	background-image:url(/themes/default/images/sub-bg_01.png);
	background-repeat:no-repeat;
}
.selected .submenu-middle
{
	position:relative;
	float:left;
	/*background-image:url(/themes/default/images/sub-bg_02.png);*/
        background-color: #5ea6bf;
	background-repeat:repeat-x;
	height:22px;
}
.selected .submenu-right
{
	position:relative;
	float:left;
	background-image:url(/themes/default/images/sub-bg_03.png);
	background-repeat:no-repeat;
	width:7px;
	height:22px;
}
.submenu-left
{
	position:relative;
	float:left;
	width:7px;
	height:22px;
}
.submenu-middle
{
	position:relative;
	float:left;
	height:22px;
}
.submenu-right
{
	position:relative;
	float:left;
	width:7px;
	height:22px;
}
.logo
{
    position:relative;
    float:left;
    margin:10px;
    left:27px;
    top:6px;
    width:158px;
    height:59px;
    background:url(/themes/default/images/icons.png) no-repeat scroll 0px -243px transparent;
}
.menu
{
    position:relative;
    float:left;
    width:450px;
    margin:15px;
    left:20px;
    border:0px solid red;
}
.menu a
{
    outline:none;
}
.menu1
{
    position:relative;
    float:left;
    width:93px;
    height:52px;
    padding-right:10px;
    background-image:url(/themes/default/images/menu1.png);
    background-repeat:no-repeat;
    top:1px;
}
.menu1 a
{
    position:relative;
    float:left;
    width:93px;
    height:52px;
    padding-right:10px;
}

.menu1 a:hover
{
    position:relative;
    float:left;
    width:93px;
    height:52px;
    padding-right:10px;
    background-image:url(/themes/default/images/menu1_over.png);
    background-repeat:no-repeat;
    top:1px;
}

.menu2
{
    position:relative;
    float:left;
    width:62px;
    height:52px;
    padding-right:10px;
    top:3px;
    background-image:url(/themes/default/images/menu2.png);
    background-repeat:no-repeat;
}
.menu2 a
{
    position:relative;
    float:left;
    width:62px;
    height:52px;
    padding-right:10px;
}
.menu2 a:hover
{
    position:relative;
    float:left;
    width:62px;
    height:52px;
    padding-right:10px;
    background-image:url(/themes/default/images/menu2_over.png);
    background-repeat:no-repeat;
}

.menu3
{
    position:relative;
    float:left;
    width:73px;
    height:52px;
    padding-right:10px;
    top: 4px;
    background-image:url(/themes/default/images/menu3.png);
    background-repeat:no-repeat;
}
.menu3 a
{
    position:relative;
    float:left;
    width:73px;
    height:52px;
    padding-right:10px;
}
.menu3 a:hover
{
    position:relative;
    float:left;
    width:73px;
    height:52px;
    padding-right:10px;
    background-image:url(/themes/default/images/menu3_over.png);
    background-repeat:no-repeat;
}

.menu4
{
    position:relative;
    float:left;
    width:96px;
    height:52px;
    padding-right:10px;
    top:5px;
    background-image:url(/themes/default/images/menu4.png);
    background-repeat:no-repeat;
}
.menu4 a
{
    position:relative;
    float:left;
    width:96px;
    height:52px;
    padding-right:10px;
}
.menu4 a:hover
{
    position:relative;
    float:left;
    width:96px;
    height:52px;
    padding-right:10px;
    background-image:url(/themes/default/images/menu4_over.png);
    background-repeat:no-repeat;
}

.topmenu
{
    position:relative;
    float:left;
    width:300px;
    font-size:11px;
    text-align:left;
    left:35px;
    color:#FFF;
    font-weight:600;
    text-decoration:none;
}
.topmenu a
{
    font-size:11px;
    text-align:left;
    color:#FFF;
    font-weight:600;
    padding:0px 5px 0px 5px;
    text-decoration:underline;
}
.topmenu a:hover
{
    font-size:11px;
    text-align:left;
    color:#F00;
    font-weight:600;
    text-decoration:underline;
}

.container
{
    position:relative;
    background-color:#e1dfd5;
    float:none;
    width:960px;
    height:auto;
    border:0px solid red;
    margin:0 auto;
    overflow:hidden;
    text-align: left;
}
.con-box
{
    position:relative;
    float:left;
    width:100%;
    height:auto;
    margin:0 auto;
    left:10px;
}
.con-left
{
    position:relative;
    float:left;
    background-color:#FFF;
    width:59%;
    min-height:100%;
    border-left:1px solid grey;

}
.con-right
{
    position:relative;
    float:left;
    background-color:#FFF;
    width:39%;
    height:100%;
    border-right:1px solid grey;
    border-left:1px solid grey;

}
.con-left-row1
{
    position:relative;
    float:left;
    background-color:#cacaca;
    width:100%;
    height:30px;
}
.dropdown-bg
{
    position:relative;
    float:right;
    right:230px;
    top:-25px;
    background:url(/themes/default/images/icons.png) no-repeat scroll -6px -172px transparent;
    background-repeat:no-repeat;
    width:108px;
    height:22px;
}
.con-left-row2
{
    position:relative;
    float:left;
    left:22px;
    background-color:#FFF;
    min-height:320px;
}
.con-right-row1
{
    position:relative;
    float:left;
    background-color:#cacaca;
    width:100%;
    height:30px;
}
.con-right-row2
{
    position:relative;
    float:left;
    background-color:#eeeeee;
    width:100%;
    height:100px;
    border-bottom:1px solid #d1d1d1;
}
.con-right-row3
{
    position:relative;
    float:left;
    background-color:#eeeeee;
    width:100%;
    height:130px;
    border-bottom:1px solid #d1d1d1;
}
.con-right-row4
{
    position:relative;
    float:left;
    background-color:#eeeeee;
    width:100%;
    min-height:437px;
    border-bottom:1px solid #d1d1d1;
}
.rg-thum-img
{
    position:relative;
    float:left;
    width:100%;
    height:82px;
    border-bottom:1px solid #d1d1d1;
    margin-top:5px;
}
.rg-thum-img-cel1
{
	position:relative;
	float:left;
	width:67px;
	height:60px;
	background:transparent url(/themes/default/images/icon-right.png) no-repeat scroll 0px -6px;
}
.rg-thum-img-cel12
{
	position:relative;
	float:left;
	width:67px;
	height:60px;
	background:transparent url(/themes/default/images/icon-right.png) no-repeat scroll -78px -4px;
}
.rg-thum-iconbox
{
	position:relative;
	float:left;
	height:60px;
	width:70px;
	left:10px;
}
.rg-thum-img-cel2
{
	position:relative;
	float:left;
	width:40px;
	height:30px;
	background:url(/themes/default/images/thumb-icon.png) no-repeat scroll -47px 0px transparent;
	margin-left:-11px;
	margin-top:10px;
}
.rg-thum-img-cel22
{
	position:relative;
	float:left;
	width:40px;
	height:30px;
	background:url(/themes/default/images/thumb-icon.png) no-repeat scroll 0px -2px transparent;
	margin-left:-11px;
	margin-top:10px;
}
.rg-thum-img-cel3
{
	background:url(/themes/default/images/thumb-icon.png) no-repeat scroll -95px 0px transparent;
	float:left;
	height:30px;
	margin-left:-11px;
	margin-top:10px;
	position:relative;
	width:50px;
}
.rg-thum-img-cel33
{
	background:url(/themes/default/images/thumb-icon.png) no-repeat scroll -140px -2px transparent;
	float:left;
	height:30px;
	margin-left:-11px;
	margin-top:10px;
	position:relative;
	width:50px;
}
.rg-thum-img-cel4
{
    position:absolute;
    float:left;
    height:20px;
    width:65px;
    top:40px;
    font-size:12px;
    font-weight:800;
    color:#363231;
    text-align:center;
    border:0px solid red;
}
.rg-thum-content
{
    position:relative;
    float:left;
    height:auto;
    width:230px;
    border:0px solid red;
    font-size:12px;
    text-align:left;
    left:10px;
}
.radio-button
{
    position:relative;
    float:left;
    width:100px;
    text-align:left;
    margin:5px;
    font-size: 12px;
    font-weight:600;
    line-height: 21px;
    color: #363231;
}
.radio-button-right
{
    position:absolute;
    float:left;
    width:100px;
    text-align:left;
    top:100px;
    left:250px;
    height:25px;
    font-size:11px;
    background:url(/themes/default/images/icons.png) no-repeat scroll 27px -140px transparent;
}
.skip-txt
{
    clear: both;
    position:absolute;
    width:20px;
    text-align:left;
    top:108px;
    left:260px;
    height:25px;
    font-size:11px;
    border: 0px solid red;

}
.head-txt
{
    font-size:18px;
    font-weight:bold;
    color:#363231;
    text-align:left;
    line-height:28px;
    margin-left:55px;
}
.head-txt1
{
    font-size:18px;
    font-weight:bold;
    color:#363231;
    text-align:left;
    line-height:28px;
    margin-left:5px;
}

.heading-txt
{
    font-size:14px;
    font-weight:bold;
    color:#363231;
    text-align:left;
    margin:10px;
}
.bar-bottom-txt
{
    font-size:11px;
    text-align:right;
    color:#363231;
    margin:10px 27px;
    font-weight:600;
    text-decoration:none;
}
.bar-bottom-txt a
{
    font-size:11px;
    text-align:center;
    color:#3e718c;
    text-decoration:underline;
}
.bar-bottom-txt a:hover
{
    font-size:11px;
    text-align:center;
    color:#3e718c;
    text-decoration:underline;
}
.thum-img-box
{
    position:relative;
    float:left;
    margin:15px 20px 0px 20px;
    width:120px;
    height:280px;
}
.thum-txt
{
    position:relative;
    float:left;
    width:120px;
    height:36px;
    font-size:12px;
    font-weight:600;
    color:#535353;
    text-align:center;
}
.thum-img
{
    position:relative;
    float:left;
    width:110px;
    height:150px;
}
.doit-button
{
    position:relative;
    float:left;
    width:111px;
    height:26px;
    margin-top:5px;
    background:url(/themes/default/images/icons.png) no-repeat scroll -6px -202px transparent;
}
.interest-button
{
	position:relative;
	float:left;
	width:96px;
	height:25px;
	left: 10px;
	margin-top:5px;
	background:url(/themes/default/images/icons.png) no-repeat scroll -2px -73px transparent;
}
.interest-button a
{
	position:relative;
	float:left;
	width:96px;
	height:25px;
}
.interest-button a:hover
{
	position:relative;
	float:left;
	width:96px;
	height:25px;
	background:url(/themes/default/images/icons.png) no-repeat scroll -2px -49px transparent;
}

.interest-button-over
{
	position:relative;
	float:left;
	width:96px;
	height:25px;
	left: 10px;
	margin-top:5px;
	background:url(/themes/default/images/icons.png) no-repeat scroll -2px -49px transparent;
}

.star-button
{
    position:relative;
    float:left;
    width:110px;
}
.star-button-yellow
{
    position:relative;
    float:left;
    width:20px;
    height:20px;
    padding-left:2px;
    background:url(/themes/default/images/icons.png) no-repeat scroll -55px -23px transparent;
}
.star-button-grey
{
    position:relative;
    float:left;
    width:20px;
    height:20px;
    padding-left:2px;
    background:url(/themes/default/images/icons.png) no-repeat scroll -3px -23px transparent;
}
.star-button-blue
{
    position:relative;
    float:left;
    width:20px;
    height:20px;
    padding-left:2px;
    background:url(/themes/default/images/icons.png) no-repeat scroll -30px -23px transparent;
}

.link-txt
{
    width:120px;
    font-size:10px;
    font-weight:600;
    text-align:left;
    color:#535353;
}
.link-txt a
{
    width:120px;
    font-size:10px;
    font-weight:600;
    text-align:left;
    color:#3e718c;
    text-decoration:underline;
}
.link-txt a:hover
{
    width:120px;
    font-size:10px;
    font-weight:600;
    text-align:left;
    color:#3e718c;
    text-decoration:underline;
}

.arrow-buton
{
    position:relative;
    top:120px;
    float:left;
    width:18px;
    height:28px;
    background:url(/themes/default/images/icons.png) no-repeat scroll -7px -101px transparent;
}
.arrow-buton1
{
    position:relative;
    top:120px;
    float:left;
    width:18px;
    height:30px;
    background:url(/themes/default/images/icons.png) no-repeat scroll -35px -101px transparent;
}

.footer
{
    position:fixed;
    width:100%;
    height:auto;
    bottom:0px;
    left: 0px;
}
.footer-topbg
{
    position:relative;
    background-image:url(/themes/default/images/fotter-top-bg.png);
    background-repeat:repeat-x;
    width:100%;
    height:34px;
    bottom:0px;
}
.footer-middle
{
    position:relative;
    background-image:url(/themes/default/images/footer-middle-bg.jpg);
    background-repeat:repeat-x;
    width:100%;
    height:132px;
}
.footer-control
{
    margin:auto;
    width:530px;
}

.footer-cel1
{
    position:relative;
    float:left;
    width:140px;
    height:120px;
    border-right:1px solid #FFFFFF;
}
.footer-cel2
{
    position:relative;
    float:left;
    width:160px;
    height:120px;
    left:20px;
    border-right:1px solid #FFFFFF;
}
.footer-cel3
{
    position:relative;
    float:left;
    width:160px;
    height:120px;
    left:40px;
    border-right:0px solid #FFFFFF;
}

.footer-header-txt
{
    position:relative;
    float:left;
    font-size:14px;
    color:#FFF;
    font-weight:600;
    width:100%;
}
.footer-lefticon1
{
    position:relative;
    float:left;
    width:30px;
    height:100px;
    top:8px;
    left:20px;
    background:url(/themes/default/images/footer-icons.png) no-repeat scroll 0px -2px transparent;
}
.footer-lefticon2
{
    position:relative;
    float:left;
    width:30px;
    height:100px;
    top:8px;
    left:20px;
    background:url(/themes/default/images/footer-icons.png) no-repeat scroll -40px -2px transparent;
}

.footer-input-box
{
    position:relative;
    float:left;
    width:110px;
    left:22px;
    top:4px;
}
.footer-input1
{
    position:relative;
    float:left;
    width:70px;
    height:15px;
    top:4px;
}
.footer-input12
{
    background:url(/themes/default/images/footer-icons.png) no-repeat scroll -23px -104px transparent;
    float: left;
    height:33px;
    position: relative;
    top: 4px;
    width: 108px;
}
.footer-input13
{
    background:url(/themes/default/images/footer-icons.png) no-repeat scroll 20px -1px transparent;
    float: left;
    height:25px;
    position: relative;
    top: 4px;
    width:137px;
}
.footer-input-option
{
    position:relative;
    float:left;
    width:25px;
    height:15px;
    background:url(/themes/default/images/footer-icons.png) no-repeat scroll -85px -37px transparent;
}
.footer-menu
{
    position:relative;
    float:left;
    padding-top:5px;
}
.footer-white-txt
{
    position:relative;
    width:60px;
    float:left;
    font-size:12px;
    color:#FFF;
    font-weight:600;
}
.footer-input2
{
    position:relative;
    float:left;
    width:70px;
    height:15px;
    top:22px;
}
.footer-input3
{
    position:relative;
    float:left;
    width:70px;
    height:15px;
    top:40px;
}
.footer-bottombg
{
    position:relative;
    width:100%;
    height:34px;
    background-color:#5ea6bf
}
.your-personal-button
{
/*
    float:left;
    top:-20px;
    left:35%;*/
    width:350px;
    height:43px;
    margin:0px auto;
    top:-20px;
    position:relative;

}
.your-personal-button a
{
    position:relative;
    float:left;
    width:350px;
    height:43px;
    outline:none;
}
.footerlink
{
	position:relative;
	float:left;
	font-size:12px;
	color:#363231;
	font-weight:600;
	text-align:center;
	width:100%;
	padding-left:10px;
	padding-right:10px;
        padding-top: 10px;
	text-decoration:none;
}
.footerlink a
{
	font-size:12px;
	color:#363231;
	text-align:center;
	padding-left:10px;
	padding-right:10px;
	text-decoration:none;
}
.footerlink a:hover
{
	font-size:12px;
	color:#FFF;
	text-align:center;
	text-decoration:underline;
	padding-left:10px;
	padding-right:10px;
}
.blankdiv
{
    position:relative;
    float:left;
    width:100%;
    height:75px;
}

                                                /*************************************Create Account START**************************************/
.create-ac-box
{
    position:relative;
    float:left;
    width:588px;
    height:341px;
    border:1px solid red;
}

#signupBox
{
    position:absolute;
    z-index: 999;
    overflow: auto;
    float:left;
    width:601px;
    height:352px;
    border:0px solid red;
    display: none;
    top: 0px;
    left: 0px;
}

.create-ac-head
{
    position:relative;
    float:left;
    width:100%;
    height:53px;
    cursor: move;
}
.create-ac-leftbg
{
    position:relative;
    float:left;
    width:21px;
    height:53px;
    background:url(/themes/default/images/Create-account.png) no-repeat scroll 0px 2px transparent;
}
.create-ac-middlebg
{
    position:relative;
    float:left;
    width:522px;
    height:51px;
    top:2px;
    background-image:url(/themes/default/images/Create-account-tbg.png);
    background-repeat:repeat-x;
}
.create-ac-rightbg
{
    position:relative;
    float:left;
    width:45px;
    height:53px;
    background:url(/themes/default/images/Create-account.png) no-repeat scroll -51px 2px transparent;
    cursor: move;
}
.create-ac-close
{
    position:absolute;
    float:left;
    left: 545px;
    width:30px;
    height:31px;
    background:url(/themes/default/images/Create-account.png) no-repeat scroll -91px -12px transparent;
    cursor: pointer;
    cursor: hand;
}
.create-ac-centerbg
{
    position:relative;
    float:left;
    width:584px;
    min-height:243px;
    background-image:url(/themes/default/images/Create-account-centerbg.png);
    background-repeat:repeat-y;
}
.create-ac-bottombg
{
    position:relative;
    float:left;
    width:583px;
    min-height:50px;
    background:url(/themes/default/images/Create-account.png) no-repeat scroll -302px -10px transparent;
}
.create-ac-left
{
    position:relative;
    float:left;
    width:39%;
    height:auto;
    margin:20px;
}
.create-ac-right
{
    position:relative;
    float:left;
    width:50%;
    height:auto;
    border:0px solid red;
    top:20px;
}
.create-ac-input1
{
    position:relative;
    float:left;
}
.create-ac-input2
{
    position:relative;
    float:left;
    top:20px;
}
.create-ac-input3
{
    position:relative;
    float:left;
    top:30px;
    font-size:12px;
    font-weight:600;
    color:#757575;
    width: 100%;
}
.create-ac-input4
{
    position:relative;
    float:left;
    width:162px;
    height:29px;
    border:0px solid red;
    top:38px;
    background:url(/themes/default/images/Create-account.png) no-repeat scroll -130px -29px transparent;
}
.create-ac-txt
{
    position:relative;
    float:left;
    font-size:12px;
    color:#6c6c6c;
}
.create-ac-login
{
     position:relative;
     float:left;
    font-size:12px;
    color:#538ea3;
    text-decoration:underline;
}
.create-ac-create-but
{
    position:relative;
    float:left;
    width:112px;
    height:23px;
    left:4px;
    background:url(/themes/default/images/Create-account.png) no-repeat scroll -134px -4px transparent;
}


                                                /**************************************Create Account END**************************************/
                                                /************************************** Sign in START **************************************/

#loginBox
{
    position:absolute;
    z-index: 999;
    overflow: auto;
    float:left;
    width:346px;
    height:302px;
    border:0px solid red;
    display: none;
    top:0px;
    left:0px
}
.sign-in-leftbg
{
    position:relative;
    float:left;
    width:21px;
    height:53px;
    background:url(/themes/default/images/Create-account.png) no-repeat scroll 0px 2px transparent;
}

.create-ac-txt
{
    font-size:12px;
    color:#6c6c6c;
}

.sign-in-create-account
{
    font-size:12px;
    color:#538ea3;
    text-decoration:underline;
}

.sign-in-input3
{
    position:relative;
    float:left;
    top:30px;
    font-size:12px;
    font-weight:600;
    color:#757575;
    width: 100%;
}
.sign-in-centerbg
{
    position:relative;
    float:left;
    width:346px;
    min-height:185px;
    background-image:url(/themes/default/images/sign-in-bg-rep.png);
    background-repeat:repeat-y;
}
.sign-in-middlebg
{
    position:relative;
    float:left;
    width:285px;
    height:51px;
    top:2px;
    background-image:url(/themes/default/images/Create-account-tbg.png);
    background-repeat:repeat-x;
}
.sign-in-rightbg
{
    position:relative;
    float:left;
    width:38px;
    height:53px;
    background:url(/themes/default/images/Create-account.png) no-repeat scroll -51px 2px transparent;
    cursor: move;
}
.sign-in-bottombg
{
    position:relative;
    float:left;
    width:346px;
    height:57px;
    background:url(/themes/default/images/sign-in-botom-bg.png) no-repeat scroll 0px -15px transparent;
}
.sign-in-right
{
    position:relative;
    float:left;
    width:93%;
    height:auto;
    border:0px solid red;
    top:20px;
    left:12px;
}
.sign-in-button
{
    position:relative;
    float: left;
    padding-top: 12px;
    left: 240px;


}
.sign-in-input4
{
    position:relative;
    float:left;
    width:162px;
    height:29px;
    border:0px solid red;
    background:url(/themes/default/images/Create-account.png) no-repeat scroll -130px -29px transparent;
    clear: both;
}
.login-close
{
    position:absolute;
    float:left;
    left: 306px;
    width:30px;
    height:31px;
    background:url(/themes/default/images/Create-account.png) no-repeat scroll -91px -12px transparent;
    cursor: pointer;
    cursor: hand;
}

                                                /************************************** Sign in END **************************************/

                                                /**************************************Input styles START**************************************/
.create-ac-right input
{
    width:290px;
    height:28px;
    font-size:15px;
    font-weight:600;
    color:#a9a9a9;
    border:1px solid #a9a9a9;
}
.sign-in-right input
{
    width:290px;
    height:28px;
    font-size:15px;
    font-weight:600;
    color:#a9a9a9;
    border:1px solid #a9a9a9;
}

                                                /**************************************Input styles END**************************************/
                                                /**************************************TEXT Styles START**************************************/
.create-ac-head-txt
{
    font-size:26px;
    line-height:50px;
    color:#FFF;
    text-align:left;
}
                                                /**************************************TEXT Styles END**************************************/

#your-personal-button{
cursor: hand;
cursor: pointer;
border:1px solid red;
}


.h15{
    height: 15px;
}

.clear{
    clear:both;
}

/************** Message in START *********************/
#messageBox
{
    position:absolute;
    z-index: 999;
    overflow: auto;
    float:left;
    width:346px;
    height:302px;
    border:0px solid red;
}
.errorMsg
{
    color: #FF0000;
    font-size: 12px;
    padding-left: 10px;
    padding-top: 5px;
}

/************** Message in END *********************/
/***************** For Chrome  Start*********************/
@media screen and (-webkit-min-device-pixel-ratio:0)
{
    @font-face
    {
        font-family: Arial;
        src: url('/font/arial.ttf');
        font-weight:600;
    }
    .thum-txt
    {
        font-size: 12px;
        font-weight: 600;
    }
}

/***************** For Chrome End*********************/

