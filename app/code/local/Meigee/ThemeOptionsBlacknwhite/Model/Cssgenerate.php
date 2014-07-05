<?php 
/**
 * Magento
 *
 * @author    Meigeeteam http://www.meaigeeteam.com <nick@meaigeeteam.com>
 * @copyright Copyright (C) 2010 - 2012 Meigeeteam
 *
 */
class Meigee_ThemeOptionsBlacknwhite_Model_Cssgenerate extends Mage_Core_Model_Abstract
{
    private $baseColors;
	private $catlabelsColors;
	private $menuColors;
    private $appearance;
    private $mediaPath;
    private $dirPath;
    private $filePath;
	private $headerSliderColors;
	private $buttonsColors;
	private $footerColors;
	private $socialLinksColors;
	private $productsColors;
	private $headerColors;

    private function setParams () {
        $this->baseColors = Mage::getStoreConfig('meigee_blacknwhite_design/base');
		$this->catlabelsColors = Mage::getStoreConfig('meigee_blacknwhite_design/catlabels');
		$this->menuColors = Mage::getStoreConfig('meigee_blacknwhite_design/menu');
        $this->appearance = Mage::getStoreConfig('meigee_blacknwhite_design/appearance');
		$this->headerSliderColors = Mage::getStoreConfig('meigee_blacknwhite_design/headerslider');
		$this->buttonsColors = Mage::getStoreConfig('meigee_blacknwhite_design/buttons');
		$this->footerColors = Mage::getStoreConfig('meigee_blacknwhite_design/footer');
		$this->socialLinksColors = Mage::getStoreConfig('meigee_blacknwhite_design/social_links');
		$this->productsColors = Mage::getStoreConfig('meigee_blacknwhite_design/products');
		$this->headerColors = Mage::getStoreConfig('meigee_blacknwhite_design/header');
    }

    private function setLocation () {
        $this->mediaPath = Mage::getBaseDir('media') . 'images/';
        $this->dirPath = Mage::getBaseDir('skin') . '/frontend/blacknwhite/default/css/';
        $this->filePath = $this->dirPath . 'skin.css';
    }

    public function saveCss()
    {

        $this->setParams();

$css = "/**
*
* This file is generated automaticaly. Please do no edit it directly cause all changes will be lost.
*
*/
";

if ($this->appearance['font_main'] == 1)
{
    $css .= '/*====== Font Replacement - Main Text =======*/ ';
    if ($this->appearance['main_default_sizes'] == 0)
        {
$css .= '
body{
    font-family: '. $this->appearance['gfontmain'] .', sans-serif; 
    font-size:'. $this->appearance['main_fontsize'] .'px !important;
    line-height:' . $this->appearance['main_lineheight'] .'px !important;
    font-weight:' .$this->appearance['main_fontweight'] .' !important;
}

';
	}else{
		$css .= '
		body{
			font-family: '. $this->appearance['gfontmain'] .', sans-serif;
		}
		
		';
	}
};


if ($this->appearance['font'] == 1)
{
    $css .= '/*====== Font Replacement - Titles =======*/ ';
    if ($this->appearance['default_sizes'] == 0)
        {
$css .= '
.nav-container li.level-top > a span,
header#header .top-cart .block-title .title-cart,
#footer .footer-block-title h2,
#footer .footer-topline .custom-footer-content li > span h3,
#footer .footer-address-block p,
.products-grid .product-name a,
.price,
.btn-quick-view span,
.products-list .product-name a,
.minimal-price-link .label,
.page-title h1,
.page-title h2,
.page-title h3,
.page-title h4,
.page-title h5,
.page-title h6,
.widget-title h1,
.widget-title h2,
.widget .widget-title h1,
.widget .widget-title h2,
aside.sidebar .block.block-layered-nav dl dt h2,
aside.sidebar .block.block-layered-nav dl dd a,
aside.sidebar .block.block-layered-nav dl dd li span,
aside.sidebar .block.block-layered-nav #amount,
aside.sidebar .block.block-layered-nav #amount-2,
aside.sidebar .block-title strong span,
.block-vertical-nav li.level-top a.level-top,
aside.sidebar .product-name a,
.block-poll label,
aside.sidebar .block .block-subtitle,
.sorter .sort-by label,
.toolbar .sbSelector > span,
.label-new,
.label-sale,
.products-grid .availability-only,
.products-list .availability-only,
.pager .pages ol li.current,
.pager .pages ol li a,
.product-view .product-shop .product-name h1,
.meigee-tabs a,
.rating-subtitle h2,
.catalog-product-view .box-reviews ul li h6 a,
.block-related .product-name a,
.block-related .block-title strong span,
.block-related .block-content .block-subtitle a,
.product-collateral h2,
.product-options-title h2,
.price-as-configured .price-label,
header#header .top-cart .product-name a,
.cart .page-title h1,
.data-table .product-name a,
.cart-blocks-title h2,
section .crosssell .product-details .product-name a,
#cart-accordion h3.accordion-title,
#cart-accordion .accordion-content .crosssell li.item .product-name a,
aside.sidebar .block-account li a,
aside.sidebar .block-account li strong,
.dashboard .welcome-msg .hello,
.dashboard .box-title h2,
.dashboard .box-title h3,
.dashboard .box-head h3,
.dashboard .box-head h2,
.fieldset .legend,
.addresses-list .addresses-primary h2,
.addresses-list .addresses-additional h2,
.product-review .product-name,
header#header .top-cart .block-content .subtotal span,
#login-holder .page-title h1,
#login-holder form p,
#login-holder .link-box a,
.onepagecheckout-index-index .main-container .page-title,
#onepagecheckout_orderform .col3-set.onepagecheckout_datafields .op_block_title,
.opc h3,
.opc .step-title h2,
.multiple-checkout h2,
.nav-wide#nav-wide .top-content .top-menu-features li span h3,
.nav-wide#nav-wide ul.level0 li.level1 span.subtitle,
.header-slider-container .iosSlider .slider .item h2,
.header-slider-container .iosSlider .slider .item h3,
.header-slider-container .iosSlider .slider .item .slide-container.slide-skin-2 p,
.header-slider-container .iosSlider .slider .item .slide-container.slide-skin-2 h4,
#login-form h2,
.quick-view-title h2,
.block-subscribe .form-subscribe-header label,
.block-subscribe-popup .indent h3,
aside.sidebar .block.block-layered-nav .currently .label {
    font-family: '. $this->appearance['gfont'] .', sans-serif; 
    font-size:'. $this->appearance['fontsize'] .'px !important;
    line-height:' . $this->appearance['lineheight'] .'px !important;
    font-weight:' .$this->appearance['fontweight'] .' !important;
}';
        } else {
        $css .= '
.nav-container li.level-top > a span,
header#header .top-cart .block-title .title-cart,
#footer .footer-block-title h2,
#footer .footer-topline .custom-footer-content li > span h3,
#footer .footer-address-block p,
.products-grid .product-name a,
.price,
.btn-quick-view span,
.products-list .product-name a,
.minimal-price-link .label,
.page-title h1,
.page-title h2,
.page-title h3,
.page-title h4,
.page-title h5,
.page-title h6,
.widget-title h1,
.widget-title h2,
.widget .widget-title h1,
.widget .widget-title h2,
aside.sidebar .block.block-layered-nav dl dt h2,
aside.sidebar .block.block-layered-nav dl dd a,
aside.sidebar .block.block-layered-nav dl dd li span,
aside.sidebar .block.block-layered-nav #amount,
aside.sidebar .block.block-layered-nav #amount-2,
aside.sidebar .block-title strong span,
.block-vertical-nav li.level-top a.level-top,
aside.sidebar .product-name a,
.block-poll label,
aside.sidebar .block .block-subtitle,
.sorter .sort-by label,
.toolbar .sbSelector > span,
.label-new,
.label-sale,
.pager .pages ol li.current,
.pager .pages ol li a,
.product-view .product-shop .product-name h1,
.meigee-tabs a,
.rating-subtitle h2,
.catalog-product-view .box-reviews ul li h6 a,
.block-related .product-name a,
.block-related .block-title strong span,
.block-related .block-content .block-subtitle a,
.product-collateral h2,
.product-options-title h2,
.price-as-configured .price-label,
header#header .top-cart .product-name a,
.cart .page-title h1,
.data-table .product-name a,
.cart-blocks-title h2,
section .crosssell .product-details .product-name a,
#cart-accordion h3.accordion-title,
#cart-accordion .accordion-content .crosssell li.item .product-name a,
aside.sidebar .block-account li a,
aside.sidebar .block-account li strong,
.dashboard .welcome-msg .hello,
.dashboard .box-title h2,
.dashboard .box-title h3,
.dashboard .box-head h3,
.dashboard .box-head h2,
.fieldset .legend,
.addresses-list .addresses-primary h2,
.addresses-list .addresses-additional h2,
.product-review .product-name,
header#header .top-cart .block-content .subtotal span,
#login-holder .page-title h1,
#login-holder form p,
#login-holder .link-box a,
.onepagecheckout-index-index .main-container .page-title,
#onepagecheckout_orderform .col3-set.onepagecheckout_datafields .op_block_title,
.opc h3,
.opc .step-title h2,
.multiple-checkout h2,
.nav-wide#nav-wide .top-content .top-menu-features li span h3,
.nav-wide#nav-wide ul.level0 li.level1 span.subtitle,
.header-slider-container .iosSlider .slider .item h2,
.header-slider-container .iosSlider .slider .item h3,
.header-slider-container .iosSlider .slider .item .slide-container.slide-skin-2 p,
.header-slider-container .iosSlider .slider .item .slide-container.slide-skin-2 h4,
#login-form h2,
.quick-view-title h2,
.block-subscribe .form-subscribe-header label,
.block-subscribe-popup .indent h3,
aside.sidebar .block.block-layered-nav .currently .label {font-family: ' . $this->appearance['gfont'] .', sans-serif;}';
    }
}

if ($this->appearance['custompatern']) {
$css .= '

/*====== Custom Patern =======*/
body,
body.boxed-layout{ background: url("' . MAGE::helper('ThemeOptionsBlacknwhite')->getThemeOptionsBlacknwhite('mediaurl') . $this->appearance['custompatern'] . '") center top repeat !important;}';
}
$css .= '

/*====== Site Bg =======*/
body {background-color:#' . $this->baseColors['sitebg'] . ';}

/*====== Skin Color #1 =======*/
a:hover,
#footer .footer-address-block p a:hover,
#footer address a:hover,
.products-grid .product-name a:hover,
.regular-price .price,
.special-price .price,
.price-from .price,
.price-to .price,
.products-grid .add-to-links li i:hover,
.products-list .product-name a:hover,
.minimal-price-link .price,
.products-list .add-to-links i:hover,
#categories-accordion li.level-top a.level-top:hover,
#categories-accordion li.level-top.parent ul.level0 li a:hover,
#categories-accordion .btn-cat.closed > i,
.block-compare li.item .btn-remove i:hover,
.availability.out-of-stock span,
.availability-only i,
div.quantity-decrease i:hover,
div.quantity-increase i:hover,
.catalog-product-view .box-reviews .form-add h3 span,
.catalog-product-view .box-reviews ul li small span,
.block-related .product-name a:hover,
.block-related .block-content .block-subtitle a:hover,
.product-options-bottom  i:hover,
.price-as-configured .price,
header#header .top-cart .product-name a:hover,
header#header .top-cart .block-content .mini-products-list .product-details .price,
.data-table .product-name a:hover,
.data-table .c_actions a i:hover,
.data-table .cart-price .price,
.data-table .remove i:hover,
.sp-methods .price,
.cart .totals .checkout-types li a:hover,
.dashboard .box-title a i:hover,
.dashboard .box-head a i:hover,
.my-account .addresses-list li.item a:hover,
.my-account .data-table a:hover,
.my-wishlist .data-table .table-buttons a i:hover,
header#header .top-cart .block-content .subtotal .price,
#login-holder form .actions > a:hover,
#login-holder .link-box a:hover,
.onepagecheckout_loginarea .onepagecheckout_loginlink:hover,
.onepagecheckout-index-index #onepagecheckout_forgotbox.op_login_area #forgot-password-form .onepagecheckout_loginlink:hover,
 .onepagecheckout-index-index #onepagecheckout_loginbox.op_login_area #login-form .onepagecheckout_forgotlink:hover,
.opc .grid_4 a:hover,
.multiple-checkout .grand-total .price,
nav.breadcrumbs li a:hover,
nav.breadcrumbs li a:hover + span,
.nav-wide#nav-wide .top-content .top-menu-links li a,
header#header .customer-name .user i,
.tags-list li a:hover,
.header-slider-container .iosSlider .slider .item .slide-container.slide-skin-2 h3 span,
.header-slider-container .iosSlider .slider .item .slide-container.slide-skin-3 h3 span,
.product-view .product-shop .add-to-links-box i:hover,
#cart-accordion .accordion-content .crosssell li.item .product-name a:hover {color: #' . $this->baseColors['maincolor'] . ';}

#footer .block-tags .actions a:hover,
button.button:hover > span,
ul.social-links li a i:hover,
#footer .block-tags .tags-list li a:hover,
.ui-slider .ui-slider-range,
.block-compare .actions a:hover,
.block-compare .actions button span,
aside.sidebar .block-tags li a:hover,
aside.sidebar .block-tags .actions a:hover,
aside.sidebar .block-reorder .actions a:hover,
.block-reorder .actions button span,
aside.sidebar .block.block-wishlist .actions a:hover,
.sorter .view-mode a:hover i,
.sorter .view-mode strong i,
.sorter a.desc i:hover,
.sorter a.asc i:hover,
div.label-sale,
.products-grid .availability-only,
.products-list .availability-only,
.add-to-cart button.button span,
.catalog-product-view .box-reviews .full-review,
.iwdbutton button.button span,
.cart .btn-proceed-checkout span,
header#header .top-cart .block-content .actions .button span,
.cart-table .buttons-row button.btn-continue:hover span,
.my-wishlist .buttons-set .btn-update:hover span,
header#header .top-cart .block-content .actions a:hover,
#checkout-coupon-discount-load .discount-form .buttons-set button.button:hover span span,
#onepagecheckout_orderform  #checkout-review-submit button span span,
#onepagecheckout_forgotbox button.button:hover span span,
#onepagecheckout_loginbox button.button:hover span span,
nav.breadcrumbs li span:after,
.header-slider-container .iosSlider .prev:hover i,
.header-slider-container .iosSlider .next:hover i,
.cart-remove-box a:hover,
.add-to-cart-success a:hover,
#login-holder form .actions button:hover span span,
table#wishlist-table td .cart-cell button.button:hover span span,
.block-subscribe-popup .indent button.button:hover span span,
aside.sidebar .block.block-layered-nav .actions a:hover {background-color: #' . $this->baseColors['maincolor'] . ';}

.label-type-5 div.label-sale:before,
.products-grid.label-type-5 .availability-only:before,
.products-list.label-type-5 .availability-only:before{
	border-top-color: #ff1341;
}
.label-type-5 div.label-sale:after,
.products-grid.label-type-5 .availability-only:after,
.products-list.label-type-5 .availability-only:after{
	border-bottom-color: #ff1341;
}

#footer .block-tags .actions a:hover,
button.button:hover span,
#footer .block-tags .tags-list li a:hover,
.block-compare .actions a:hover,
.block-compare .actions button span,
aside.sidebar .block-tags li a:hover,
aside.sidebar .block-tags .actions a:hover,
aside.sidebar .block-reorder .actions a:hover,
.block-reorder .actions button span,
aside.sidebar .block.block-wishlist .actions a:hover,
.add-to-cart button.button span,
.iwdbutton button.button span,
.cart .btn-proceed-checkout span,
header#header .top-cart .block-content .actions .button span,
.cart-table .buttons-row button.btn-continue:hover span,
.my-wishlist .buttons-set .btn-update:hover span,
header#header .top-cart .block-content .actions a:hover,
#checkout-coupon-discount-load .discount-form .buttons-set button.button:hover span,
#onepagecheckout_orderform  #checkout-review-submit button > span,
#onepagecheckout_forgotbox button.button:hover > span,
#onepagecheckout_loginbox button.button:hover > span,
.cart-remove-box a:hover,
.add-to-cart-success a:hover,
#login-holder form .actions button:hover > span,
.block-subscribe-popup .indent button.button:hover > span,
aside.sidebar .block.block-layered-nav .actions a:hover {border-color: #' . $this->baseColors['maincolor'] . ';}

/*====== Skin Color #2 =======*/
a,
.onepagecheckout_loginarea .onepagecheckout_loginlink,
.nav-wide#nav-wide .top-content .top-menu-links li a:hover,
.header-slider-container .iosSlider .slider .item h3,
.header-slider-container .iosSlider .slider .item .slide-container.slide-skin-2 h4,
.header-slider-container .iosSlider .slider .item .slide-container.slide-skin-3 h4 {color: #' . $this->baseColors['secondcolor'] . ';}

.products-grid .btn-quick-view > span,
.products-list .btn-quick-view > span {
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#CC' . $this->baseColors['secondcolor'] . ',endColorstr=#CC' . $this->baseColors['secondcolor'] . ');
	background-color: rgba('.MAGE::helper('ThemeOptionsBlacknwhite')->HexToRGB($this->baseColors['secondcolor']).', 0.8);
}

.products-grid .btn-quick-view:hover > span,
.products-list .btn-quick-view:hover > span,
span.label-new {background-color: #' . $this->baseColors['secondcolor'] . ';}

.label-type-5 span.label-new:before{
    border-top-color: #' . $this->baseColors['secondcolor'] . ';
}
.label-type-5 span.label-new:after{
    border-bottom-color: #' . $this->baseColors['secondcolor'] . ';
}

.product-view .product-name div.sku::selection {background-color: #' . $this->baseColors['secondcolor'] . ';}
.product-view .product-name div.sku::-moz-selection {background-color: #' . $this->baseColors['secondcolor'] . ';}

';

if ($this->baseColors['base_override'] == 1) {
    $css .= '/*====== Menu Color =======*/
    header#header .topline,
    body.boxed-layout header#header .topline .container_12 {background-color: #' . $this->menuColors['blockbg'] . ';}
    .topline .grid_12 {
    	border-top-color: #' . $this->menuColors['block_border'] . ';
    	border-bottom-color: #' . $this->menuColors['block_border'] . ';
    	border-top-width: ' . $this->menuColors['block_border_width'] . 'px;
    	border-bottom-width: ' . $this->menuColors['block_border_width'] . 'px;
    }
    .nav-container li.level-top > a span {color: #' . $this->menuColors['link_color'] . ';}
    .nav-container li.level-top > a:hover span {color: #' . $this->menuColors['link_color_h'] . ';}
    .nav-container li.level-top > a {background-color: #' . $this->menuColors['linkbg'] . ';}
    .nav-container li.level-top > a:hover {background-color: #' . $this->menuColors['linkbg_h'] . ';}
    .nav-container li.level-top.active > a {background-color: #' . $this->menuColors['linkbg_a'] . ';}
    .nav-container li.level-top.active > a span {color: #' . $this->menuColors['link_color_a'] . ';}

    /**** Dropdown Menu ****/
    .nav-wide#nav-wide .menu-wrapper {background-color: #' . $this->menuColors['menuwrapper'] . ';}
    .nav-wide#nav-wide .top-content .top-menu-links {border-bottom-color: #' . $this->menuColors['topmenulinks'] . ';}
    .nav-wide#nav-wide .top-content i {color: #' . $this->menuColors['topcontenticons'] . '; border-color: #' . $this->menuColors['topcontenticons'] . ';}
    .nav-wide#nav-wide .top-content h3 {color: #' . $this->menuColors['topcontenttitles'] . ';}
    .nav-wide#nav-wide .top-content {color: #' . $this->menuColors['topmenutext'] . ';}
    .nav-wide#nav-wide ul.level0 li.level1 span.subtitle {background-color: #' . $this->menuColors['subtitlebg'] . '; color: #' . $this->menuColors['subtitle'] . ';}
    .nav-wide#nav-wide ul.level1 a {color: #' . $this->menuColors['linkcolor'] . ';}
    .nav-wide#nav-wide ul.level1 a:hover {background-color: #' . $this->menuColors['linkcolorhover'] . ';}
    .nav-wide#nav-wide .bottom-content {background-color: #' . $this->menuColors['boottombg'] . '; color: #' . $this->menuColors['boottomtext'] . ';}

    /**** Cart and Wishlist ****/
    header#header .top-link-wishlist i,
    header#header .top-link-wishlist .wishlist-items {color: #' . $this->menuColors['wishlist_link_color'] . ';}
    header#header .top-link-wishlist:hover i,
    header#header .top-link-wishlist:hover .wishlist-items {color: #' . $this->menuColors['wishlist_link_color_h'] . ';}
    header#header .top-link-wishlist {background-color: #' . $this->menuColors['wishlist_link_bg'] . ';} /*  */
    header#header .top-link-wishlist:hover {background-color: #' . $this->menuColors['wishlist_link_bg_h'] . ';}
    header#header .top-cart .block-title {background-color: #' . $this->menuColors['cart_link_bg'] . ';}
    header#header .top-cart .block-title .title-cart {color: #' . $this->menuColors['cart_link_color'] . ';}
    header#header .top-cart .block-title:hover .title-cart,
    header#header .top-cart .block-title.active .title-cart {color: #' . $this->menuColors['cart_link_color_h'] . ';}
    header#header .top-cart .block-title:hover,
    header#header .top-cart .block-title.active {background-color: #' . $this->menuColors['cart_link_bg_h'] . ';}

    /*====== Category Labels =======*/
    .nav-wide#nav-wide li.level-top .category-label.label_one { 
        background-color: #' . $this->catlabelsColors['label_one'] . ';
        border-color: #' . $this->catlabelsColors['label_one'] . ';
        color: #' . $this->catlabelsColors['label_one_color'] . ';
    }
    .nav-wide#nav-wide li.level-top.over .category-label.label_one { 
        background-color: #' . $this->catlabelsColors['label_one_h'] . ';
        border-color: #' . $this->catlabelsColors['label_one_h'] . ';
        color: #' . $this->catlabelsColors['label_one_color_h'] . ';
    }
    .nav-wide#nav-wide li.level-top .category-label.label_two { 
        background-color: #' . $this->catlabelsColors['label_two'] . ';
        border-color: #' . $this->catlabelsColors['label_two'] . ';
        color: #' . $this->catlabelsColors['label_two_color'] . ';
    }
    .nav-wide#nav-wide li.level-top.over .category-label.label_two { 
        background-color: #' . $this->catlabelsColors['label_two_h'] . ';
        border-color: #' . $this->catlabelsColors['label_two_h'] . ';
        color: #' . $this->catlabelsColors['label_two_color_h'] . ';
    }
    .nav-wide#nav-wide li.level-top .category-label.label_three { 
        background-color: #' . $this->catlabelsColors['label_three'] . ';
        border-color: #' . $this->catlabelsColors['label_three'] . ';
        color: #' . $this->catlabelsColors['label_three_color'] . ';
    }
    .nav-wide#nav-wide li.level-top.over .category-label.label_three { 
        background-color: #' . $this->catlabelsColors['label_three_h'] . ';
        border-color: #' . $this->catlabelsColors['label_three_h'] . ';
        color: #' . $this->catlabelsColors['label_three_color_h'] . ';
    }

    /*====== Header =======*/
    header#header,
    header#header .welcome-msg {color: #' . $this->headerColors['text_color'] . ';}
    header#header a {color: #' . $this->headerColors['link_color'] . ';}
    header#header a:hover {color: #' . $this->headerColors['link_color_h'] . ';}
    header#header,
    body.boxed-layout header#header > .container_12 {background-color: #' . $this->headerColors['header_bg'] . ';}
    header#header .form-search .indent,
    header#header .sbHolder a,
    header#header .sbHolder .sbOptions {
    	background-color: #' . $this->headerColors['search_and_switchers_bg'] . ';
    	border-color: #' . $this->headerColors['search_and_switchers_border'] . ';
    }
    header#header .form-search input {border-color: #' . $this->headerColors['search_textfield_border'] . ';}
    header#header .form-search input,
    header#header a.sbSelector,
    header#header .form-search button span i,
    header#header .sbHolder a {color: #' . $this->headerColors['search_and_switchers_color'] . ';}
    header#header a.sbSelector span {border-top-color: #' . $this->headerColors['search_and_switchers_color'] . ';}

    /**** Account ****/
    header#header .customer-name,
    header#header .links li a {
    	color: #' . $this->headerColors['account_link_color'] . ';
    	background-color: #' . $this->headerColors['account_link_bg'] . ';
    }
    header#header .customer-name:hover,
    header#header .links li a:hover {
    	color: #' . $this->headerColors['account_link_color_h'] . ';
    	background-color: #' . $this->headerColors['account_link_bg_h'] . ';
    }
    header#header .customer-name + .links {background-color: #' . $this->headerColors['account_sub_bg'] . ';}
    header#header .customer-name + .links li a {
    	color: #' . $this->headerColors['account_sub_link_color'] . ';
    	background-color: #' . $this->headerColors['account_sub_link_bg'] . ';
    }
    header#header .customer-name + .links li a:hover {
    	color: #' . $this->headerColors['account_sub_link_color_h'] . ';
    	background-color: #' . $this->headerColors['account_sub_link_bg_h'] . ';
    }

    /*====== Header Slider =======*/
    .header-slider-container .iosSlider .slider .item h2 {color: #' . $this->headerSliderColors['title_one'] . ';}
    .header-slider-container .iosSlider .slider .item h2 span,
    .header-slider-container .iosSlider .slider .item h4,
    .header-slider-container .iosSlider .slider .item h5,
    .header-slider-container .iosSlider .slider .item p,
    .header-slider-container .iosSlider .slider .item .slide-container.slide-skin-2 h2,
    .header-slider-container .iosSlider .slider .item .slide-container.slide-skin-2 p,
    .header-slider-container .iosSlider .slider .item .slide-container.slide-skin-2 h3,
    .header-slider-container .iosSlider .slider .item .slide-container.slide-skin-3 h2,
    .header-slider-container .iosSlider .slider .item .slide-container.slide-skin-3 h3 {color: #' . $this->headerSliderColors['title_two'] . ';}
    .header-slider-container .iosSlider .slider .item h3,
    .header-slider-container .iosSlider .slider .item .slide-container.slide-skin-2 h4,
    .header-slider-container .iosSlider .slider .item .slide-container.slide-skin-3 h4 {color: #' . $this->headerSliderColors['title_three'] . ';}
    .header-slider-container .iosSlider .slider .item .slide-container.slide-skin-2 h3 span,
    .header-slider-container .iosSlider .slider .item .slide-container.slide-skin-3 h3 span {color: #' . $this->headerSliderColors['title_four'] . ';}
    .header-slider-container .iosSlider .prev i,
    .header-slider-container .iosSlider .next i {
    	background-color: #' . $this->headerSliderColors['arrowsbg'] . ';
    	color: #' . $this->headerSliderColors['arrowscolor'] . ';
    }
    .header-slider-container .iosSlider .prev:hover i,
    .header-slider-container .iosSlider .next:hover i {
    	background-color: #' . $this->headerSliderColors['arrowsbg_h'] . ';
    	color: #' . $this->headerSliderColors['arrowscolor_h'] . ';
    }

    /*====== Buttons =======*/
    header#header .top-cart .block-content .actions .button span,
    button.button:hover span,
    .block-compare .actions a:hover,
     aside.sidebar .block-reorder .actions a:hover,
    .block-compare .actions button span,
    .block-reorder .actions button span,
    aside.sidebar .block.block-wishlist .actions a:hover,
    .add-to-cart button.button span,
    aside.sidebar .block-tags .actions a:hover,
    .cart-remove-box a:hover,
    .add-to-cart-success a:hover,
    header#header .top-cart .block-content .actions a:hover {border-color: #' . $this->buttonsColors['buttons_border_h'] . ';}
    header#header .top-cart .block-content .actions .button span,
    button.button:hover > span,
    .block-compare .actions a:hover,
    aside.sidebar .block-reorder .actions a:hover,
    .block-compare .actions button span,
    .block-reorder .actions button span,
    .add-to-cart button.button span,
    aside.sidebar .block.block-wishlist .actions a:hover,
    aside.sidebar .block-tags .actions a:hover,
    .cart-remove-box a:hover,
    .add-to-cart-success a:hover,
    header#header .top-cart .block-content .actions a:hover {background-color: #' . $this->buttonsColors['buttonsbg_h'] . ';}
    header#header .top-cart .block-content .actions .button span span,
    button.button:hover span span,
    .block-compare .actions button span,
    .block-reorder .actions button span,
    aside.sidebar .block-reorder .actions a:hover,
    .add-to-cart button.button span,
    aside.sidebar .block.block-wishlist .actions a:hover,
    aside.sidebar .actions a:hover,
    .cart-remove-box a:hover,
    .add-to-cart-success a:hover,
    header#header .top-cart .block-content .actions a:hover {color: #' . $this->buttonsColors['buttons_link_h'] . ';}
    button.button span,
    header#header .top-cart .block-content .actions a,
    aside.sidebar .actions a,
    .block-reorder .actions button:hover  span span,
    .block-compare .actions button:hover span span,
    .add-to-cart button.button:hover span span,
    .cart-remove-box a,
    .add-to-cart-success a,
    header#header .top-cart .block-content .actions .button:hover > span span {background-color: #' . $this->buttonsColors['buttonsbg'] . ';}
    button.button span,
    header#header .top-cart .block-content .actions a,
    aside.sidebar .actions a,
    .block-reorder .actions button:hover > span,
    .block-compare .actions button:hover > span,
    .add-to-cart button.button:hover > span,
    .cart-remove-box a,
    .add-to-cart-success a,
    header#header .top-cart .block-content .actions .button:hover > span {border-color: #' . $this->buttonsColors['buttons_border'] . ';}
    button.button span span,
    header#header .top-cart .block-content .actions a,
    aside.sidebar .actions a,
    .block-reorder .actions button:hover span span,
    .block-compare .actions button:hover span span,
    .add-to-cart button.button:hover span span,
    .cart-remove-box a,
    .add-to-cart-success a,
    header#header .top-cart .block-content .actions .button:hover span span {color: #' . $this->buttonsColors['buttons_link'] . ';}

    button.button span,
    header#header .top-cart .block-content .actions a,
    aside.sidebar .actions a,
    .block-reorder .actions button > span,
    .block-compare .actions button > span,
    .add-to-cart button.button > span,
    .cart-remove-box a,
    .add-to-cart-success a,
    .cart .btn-proceed-checkout span {border-width: ' . $this->buttonsColors['buttons_border_width'] . 'px;}

    /**** Buttons Type 2 ****/
    #checkout-coupon-discount-load .discount-form .buttons-set button.button span span,
    #onepagecheckout_orderform #checkout-review-submit button:hover span span,
    #login-holder form .actions button span span,
    .cart-table .buttons-row button.btn-continue span,
    .my-wishlist .buttons-set .btn-update span,
    .iwdbutton button.button:hover span,
    .cart .btn-proceed-checkout:hover span {
    	background-color: #' . $this->buttonsColors['buttons_2_bg'] . ';
    	color: #' . $this->buttonsColors['buttons_2_link'] . ';
    	border-color: #' . $this->buttonsColors['buttons_2_border'] . ';
    }
    .iwdbutton button.button:hover span span, 
    .cart .btn-proceed-checkout:hover span span {
    	color: #' . $this->buttonsColors['buttons_2_link'] . ';
    	background-color: #' . $this->buttonsColors['buttons_2_bg'] . ';
    }
    #onepagecheckout_orderform #checkout-review-submit button:hover > span,
    #checkout-coupon-discount-load .discount-form .buttons-set button.button span,
    #login-holder form .actions button > span {border-color: #' . $this->buttonsColors['buttons_2_border'] . ';}
    #checkout-coupon-discount-load .discount-form .buttons-set button.button:hover span span,
    #onepagecheckout_orderform #checkout-review-submit button span span,
    #login-holder form .actions button:hover span span,
    .cart-table .buttons-row button.btn-continue:hover span,
    .my-wishlist .buttons-set .btn-update:hover span,
    .iwdbutton button.button span,
    .cart .btn-proceed-checkout span {
    	background-color: #' . $this->buttonsColors['buttons_2_bg_h'] . ';
    	color: #' . $this->buttonsColors['buttons_2_link_h'] . ';
    	border-color: #' . $this->buttonsColors['buttons_2_border_h'] . ';
    }
    .iwdbutton button.button span span, 
    .cart .btn-proceed-checkout span span {
    	color: #' . $this->buttonsColors['buttons_2_link_h'] . ';
    	background-color: #' . $this->buttonsColors['buttons_2_bg_h'] . ';
    }
    #onepagecheckout_orderform #checkout-review-submit button > span,
    #checkout-coupon-discount-load .discount-form .buttons-set button.button:hover span,
    #login-holder form .actions button:hover > span {border-color: #' . $this->buttonsColors['buttons_2_border_h'] . ';}

    #checkout-coupon-discount-load .discount-form .buttons-set button.button span span,
    #onepagecheckout_orderform #checkout-review-submit button span span,
    .cart-table .buttons-row button.btn-continue span,
    .my-wishlist .buttons-set .btn-update span,
    #login-holder form .actions button > span,
    .iwdbutton button.button span,
    .cart .btn-proceed-checkout span {border-width: ' . $this->buttonsColors['buttons_2_border_width'] . 'px;}

    /**** Quick View Button ****/
    .products-grid button.button.btn-quick-view > span,
    .products-list button.button.btn-quick-view > span {
    	background-color: rgba('.MAGE::helper('ThemeOptionsBlacknwhite')->HexToRGB($this->buttonsColors['quick_view_button_bg']).', 0.8);
    	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#CC' . $this->buttonsColors['' . $this->buttonsColors['quick_view_button_bg'] . ''] . ',endColorstr=#CC' . $this->buttonsColors['' . $this->buttonsColors['quick_view_button_bg'] . ''] . ');
    }
    .products-grid button.button.btn-quick-view span span,
    .products-list button.button.btn-quick-view span span {color: #' . $this->buttonsColors['quick_view_button_link'] . ';}
    .products-grid button.button.btn-quick-view:hover > span,
    .products-list button.button.btn-quick-view:hover > span {background-color: #' . $this->buttonsColors['quick_view_button_bg_h'] . ';}
    .products-grid button.button.btn-quick-view:hover span span,
    .products-list button.button.btn-quick-view:hover span span {color: #' . $this->buttonsColors['quick_view_button_link_h'] . ';}

    /*====== Products ======*/
    .products-list li.item .product-img-box,
    .products-grid li.item .product-img-box {
    	background-color: #' . $this->productsColors['products_bg'] . ';
    	border-width: ' . $this->productsColors['products_border_width'] . 'px;
    	border-color: #' . $this->productsColors['products_border'] . ';
    }
    .products-grid .product-name a,
    .products-list .product-name a {color: #' . $this->productsColors['products_title_color'] . ';}
    .products-grid .product-name a:hover,
    .products-list .product-name a:hover {color: #' . $this->productsColors['products_title_color_h'] . ';}
    .products-list .desc,
    .products-grid .desc {color: #' . $this->productsColors['produst_text_color'] . ';}
    .products-list .desc a,
    .products-grid .desc a {color: #' . $this->productsColors['products_links_color'] . ';}
    .products-list .desc a:hover,
    .products-grid .desc a:hover {color: #' . $this->productsColors['products_links_color_h'] . ';}
    .price-box .price {color: #' . $this->productsColors['produst_price_color'] . ';}
    .old-price .price {color: #' . $this->productsColors['produst_old_price_color'] . ';}
    .special-price .price {color: #' . $this->productsColors['produst_special_price_color'] . ';}
    .products-grid .price-box {
    	border-color: #' . $this->productsColors['products_divider_color'] . ';
    	border-width: ' . $this->productsColors['products_divider_width'] . 'px;
    }

    /**** Product Labels ****/
    span.label-new {
    	background-color: #' . $this->productsColors['label_new_bg'] . ';
    	color: #' . $this->productsColors['label_new_color'] . ';
    }
	.label-type-5 span.label-new:before{
		border-top-color: #' . $this->productsColors['label_new_bg'] . ';
	}
	.label-type-5 span.label-new:after{
		border-bottom-color: #' . $this->productsColors['label_new_bg'] . ';
	}
	
    div.label-sale,
	.products-grid .availability-only,
	.products-list .availability-only {
    	color: #' . $this->productsColors['label_sale_color'] . ';
    	background-color: #' . $this->productsColors['label_sale_bg'] . ';
    }
	.label-type-5 div.label-sale:before,
	.products-grid.label-type-5 .availability-only:before,
	.products-list.label-type-5 .availability-only:before{
		border-top-color: #' . $this->productsColors['label_sale_bg'] . ';
	}
	.label-type-5 div.label-sale:after,
	.products-grid.label-type-5 .availability-only:after,
	.products-list.label-type-5 .availability-only:after{
		border-bottom-color: #' . $this->productsColors['label_sale_bg'] . ';
	}

    /*====== Social Links =======*/
    ul.social-links li a i {
    	background-color: #' . $this->socialLinksColors['social_links_bg'] . ';
    	color: #' . $this->socialLinksColors['social_links_color'] . ';
    	border-color: #' . $this->socialLinksColors['social_links_border'] . ';
    	border-width: '. $this->socialLinksColors['social_links_border_width'] .'px;
    }
    ul.social-links li a i:hover {
    	color: #' . $this->socialLinksColors['social_links_color_h'] . ';
    	background-color: #' . $this->socialLinksColors['social_links_bg_h'] . ';
    	border-color: #' . $this->socialLinksColors['social_links_border_h'] . ';
    }

    /*====== Footer =======*/
    /**** Top Block ****/
	#footer .footer-topline,
	body.boxed-layout #footer .footer-topline .container_12 {background-color: #' . $this->footerColors['footer_top_bg'] . ';}
	#footer .footer-topline .footer-block-title h2 {color: #' . $this->footerColors['footer_top_title_color'] . ';}
	#footer .footer-topline .footer-block-title .right-divider {
		border-color: #' . $this->footerColors['footer_top_title_border'] . ';
		border-width: ' . $this->footerColors['footer_top_title_border_width'] . 'px;
	}
	#footer .footer-topline p,
	#footer .footer-topline .footer-links li:before,
	#footer .footer-topline .custom-footer-content.features li > span p {color: #' . $this->footerColors['footer_top_text'] . ';}
	#footer .footer-topline .custom-footer-content.features li > span h3 {color: #' . $this->footerColors['footer_top_subtitle_color'] . ';}
	#footer .footer-topline .footer-links ul li a,
	#footer ul.links li a {color: #' . $this->footerColors['footer_top_link'] . ';}
	#footer ul.links li a:hover {color: #' . $this->footerColors['footer_top_link_h'] . ';}
	#footer .footer-topline .footer-links li a:hover,
	#footer ul.links li:after {background-color: #' . $this->footerColors['footer_top_link_bg_h'] . ';}
	#footer ul.links li:before {background-color: #' . $this->footerColors['footer_top_link_bg'] . ';}
	#footer .footer-topline .custom-footer-content.features i {
    	background-color: #' . $this->footerColors['footer_top_icon_bg'] . ';
    	color: #' . $this->footerColors['footer_top_icon_color'] . ';
    	border-color: #' . $this->footerColors['footer_top_icon_border'] . ';
    }
    #footer .footer-topline .custom-footer-content.features i:hover {
    	background-color: #' . $this->footerColors['footer_top_icon_bg_h'] . ';
    	color: #' . $this->footerColors['footer_top_icon_color_h'] . ';
    }

    /**** Medium Block ****/
	#footer .footer-second-line,
	body.boxed-layout #footer .footer-second-line > .container_12 {background-color: #' . $this->footerColors['footer_medium_bg'] . ';}
	#footer .footer-second-line .footer-block-title h2 {color: #' . $this->footerColors['footer_medium_title_color'] . ';}
	#footer .footer-second-line .footer-block-title .right-divider {
		border-color: #' . $this->footerColors['footer_medium_title_border'] . ';
		border-width: ' . $this->footerColors['footer_medium_title_border_width'] . 'px;
	}
	#footer .footer-second-line .contacts-footer-content label,
	#footer .footer-second-line .footer-address-block p,
	#footer .footer-second-line .custom-footer-content.features li > span h3,
	#footer .footer-second-line p,
	#footer .footer-second-line .footer-address-block p a,
	#footer .footer-second-line p a {color: #' . $this->footerColors['footer_medium_text'] . ';}
	#footer .footer-second-line ul.links li a,
	#footer .footer-second-line .custom-footer-content.features li > span p,
	#footer .footer-second-line .footer-links li a,
	#footer .footer-second-line .footer-links li:before {color: #' . $this->footerColors['footer_medium_link'] . ';}
	#footer .footer-second-line ul.links li a:hover,
	#footer .footer-second-line .footer-links li a:hover,
	#footer .footer-second-line .footer-links li:hover:before {color: #' . $this->footerColors['footer_medium_link_h'] . ';}
	#footer .footer-second-line ul.links li:before,
	#footer .footer-second-line .footer-links li a {background-color: #' . $this->footerColors['footer_medium_link_bg'] . ';}
	#footer .footer-second-line ul.links li:after,
	#footer .footer-second-line .footer-links li a:hover {background-color: #' . $this->footerColors['footer_medium_link_bg_h'] . ';}

    /**** Bottom Block ****/
	#footer .footer-bottom-wrapper,
	#footer .footer-bottom-wrapper .container_12 {background-color: #' . $this->footerColors['footer_bottom_bg'] . ';}
	#footer .footer-bottom-wrapper .custom-footer-content.features li > span h3,
	#footer .footer-bottom-wrapper .custom-footer-content.features li > span p,
	#footer .footer-bottom-wrapper,
	#footer .footer-bottom-wrapper .contacts-footer-content label,
	#footer .footer-bottom-wrapper address{color: #' . $this->footerColors['footer_bottom_text'] . ';}
	#footer .footer-bottom-wrapper li a,
	#footer .footer-bottom-wrapper li a:hover,
	#footer .footer-bottom-wrapper ul.links li a,
	#footer .footer-bottom-wrapper ul.links li a:hover,
	#footer .footer-bottom-wrapper a {color: #' . $this->footerColors['footer_bottom_link'] . ';}
	#footer .footer-bottom-wrapper .footer-block-title .right-divider{border-bottom-color: #aaa;}
	#footer .footer-bottom-wrapper .sbSelector:hover > span {border-top-color: #' . $this->footerColors['footer_bottom_link'] . ';}
    #footer address a:hover,
    #footer .footer-bottom-wrapper a:hover {color: #' . $this->footerColors['footer_bottom_link_h'] . ';}
    #footer .footer-bottom-wrapper .sbSelector:hover > span {border-top-color: #' . $this->footerColors['footer_bottom_link_h'] . ';}


    ';
}

    	$this->saveData($css);
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('ThemeOptionsBlacknwhite')->__("CSS file with custom styles has been created"));
        Mage::getSingleton('adminhtml/session')->addNotice(Mage::helper('ThemeOptionsBlacknwhite')->__('<div class="meigee-please"><a target="_blank" href="http://themeforest.net/downloads"><img src="' . Mage::getBaseUrl('skin') . '/adminhtml/default/blacknwhite/images/rating.png" /></a><h2>Like us</h2>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, "script", "facebook-jssdk"));</script>
<div class="fb-like" data-href="http://facebook.com/meigeeteam" data-layout="button_count" data-action="like" data-show-faces="false" data-width="200" data-share="true"></div>&nbsp;
<a href="https://twitter.com/meigeeteam" class="twitter-follow-button" data-show-count="false" data-lang="en">Follow @meigeeteam</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
</div>'));




        return true;
    }

    private function saveData($data)
    {
        $this->setLocation ();

        try {
	        /*$fh = fopen($file, 'w');
	       	fwrite($fh, $data);
	        fclose($fh);*/

            $fh = new Varien_Io_File(); 
            $fh->setAllowCreateFolders(true); 
            $fh->open(array('path' => $this->dirPath));
            $fh->streamOpen($this->filePath, 'w+'); 
            $fh->streamLock(true); 
            $fh->streamWrite($data); 
            $fh->streamUnlock(); 
            $fh->streamClose(); 
    	}
    	catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ThemeOptionsBlacknwhite')->__('Failed creation custom css rules. '.$e->getMessage()));
        }
    }

}