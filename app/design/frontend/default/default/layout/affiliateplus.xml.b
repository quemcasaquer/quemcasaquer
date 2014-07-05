<?xml version="1.0"?>
<layout version="0.1.0">
    <default>
        <reference name="head">
            <action method="addJs">
                <script>magestore/affiliateplus.js</script>
            </action>
            <action method="addCss">
                <styleSheet>css/magestore/affiliateplus.css</styleSheet>
            </action>
			<action method="addCss">
                <styleSheet>css/magestore/affiliateplus_responsive.css</styleSheet>
            </action>
			
            <block type="affiliateplus/CheckIframe" name="affiliateplus_checkiframe" template="affiliateplus/checkiframe.phtml"/>
        </reference>
        <reference name="footer_links">
            <block type="affiliateplus/affiliateplus" name="affiliateplus">
                <action method="addFooterLink" />
            </block>
        </reference>
    </default>
    
    <customer_account>
        <reference name="customer_account_navigation">
            <action method="addLink" translate="label" module="affiliateplus">
                <name>affiliateplus</name>
                <path>affiliateplus</path>
                <label>Affiliates</label>
            </action>
        </reference>
    </customer_account>
    
    <affiliateplus_default>
        <update handle="page_two_columns_left" />
        <reference name="left">
            <block type="affiliateplus/account_navigation" before="-" name="account_navigator" template="affiliateplus/navigation.phtml">
                <action method="setNavigationTitle">
                    <title helper="affiliateplus/account/getNavigationLabel" />
                </action>
                <action method="addLink" translate="label" module="affiliateplus">
                    <name>balance</name>
                    <path>affiliateplus/index/paymentForm</path>
                    <label helper="affiliateplus/account/getBalanceLabel" />
                    <disabled helper="affiliateplus/account/accountNotLogin" />
                    <order>6</order>
                </action>
                <action method="addLink" translate="label" module="affiliateplus">
                    <name>home</name>
                    <path>affiliateplus</path>
                    <label>Affiliate Home</label>
                    <disabled>0</disabled>
                    <order>10</order>
                </action>
                <action method="addLink" translate="label" module="affiliateplus">
                    <name>login</name>
                    <path>affiliateplus/account/login</path>
                    <label>Login</label>
                    <disabled helper="affiliateplus/account/customerLoggedIn" />
                    <order>20</order>
                </action>
                <action method="addLink" translate="label" module="affiliateplus">
                    <name>register</name>
                    <path>affiliateplus/account/register</path>
                    <label>Signup</label>
                    <disabled helper="affiliateplus/account/isRegistered" />
                    <order>30</order>
                </action>
                <action method="addLink" translate="label" module="affiliateplus">
                    <name>banners</name>
                    <path>affiliateplus/banner/list</path>
                    <label><![CDATA[Banners & Links]]>
                    </label>
                    <disabled helper="affiliateplus/account/accountNotLogin" />
                    <order>40</order>
                </action>
                <action method="addLink" translate="label" module="affiliateplus">
                    <name>materials</name>
                    <path>affiliateplus/index/materials</path>
                    <label>Materials</label>
                    <disabled helper="affiliateplus/config/disableMaterials" />
                    <order>43</order>
                </action>
                <action method="addLink" translate="label" module="affiliateplus">
                    <name>sales</name>
                    <path>affiliateplus/index/listTransaction</path>
                    <label>Commissions</label>
                    <disabled helper="affiliateplus/account/accountNotLogin" />
                    <order>110</order>
                </action>
                <action method="addLink" translate="label" module="affiliateplus">
                    <name>payments</name>
                    <path>affiliateplus/index/payments</path>
                    <!-- <label>Withdrawals</label> -->
                    <label helper="affiliateplus/account/getWithdrawalLabel" />
                    <disabled helper="affiliateplus/account/hideWithdrawalMenu" />
                    <order>120</order>
                </action>
                <action method="addLink" translate="label" module="affiliateplus">
                    <name>referrers</name>
                    <path>affiliateplus/index/referrers</path>
                    <label>Traffics</label>
                    <disabled helper="affiliateplus/account/accountNotLogin" />
                    <order>180</order>
                </action>
                <action method="addLink" translate="label" module="affiliateplus">
                    <name>edit</name>
                    <path>affiliateplus/account/edit</path>
                    <label>Settings</label>
                    <disabled helper="affiliateplus/account/accountNotLogin" />
                    <order>190</order>
                </action>
                <action method="addLink" translate="label" module="affiliateplus">
                    <name>logout</name>
                    <path>affiliateplus/account/logout</path>
                    <label>Logout</label>
                    <disabled helper="affiliateplus/account/accountNotLogin" />
                    <order>200</order>
                </action>
            </block>
        </reference>
		<reference name="content">
            <block type="affiliateplus/account_navigation" before="-" name="account_navigator_mobile" template="affiliateplus/navigationmobile.phtml">
                <action method="setNavigationTitle">
                    <title helper="affiliateplus/account/getNavigationLabel" />
                </action>
                <action method="addLink" translate="label" module="affiliateplus">
                    <name>balance</name>
                    <path>affiliateplus/index/paymentForm</path>
                    <label helper="affiliateplus/account/getBalanceLabel" />
                    <disabled helper="affiliateplus/account/accountNotLogin" />
                    <order>6</order>
                </action>
                <action method="addLink" translate="label" module="affiliateplus">
                    <name>home</name>
                    <path>affiliateplus</path>
                    <label>Affiliate Home</label>
                    <disabled>0</disabled>
                    <order>10</order>
                </action>
                <action method="addLink" translate="label" module="affiliateplus">
                    <name>login</name>
                    <path>affiliateplus/account/login</path>
                    <label>Login</label>
                    <disabled helper="affiliateplus/account/customerLoggedIn" />
                    <order>20</order>
                </action>
                <action method="addLink" translate="label" module="affiliateplus">
                    <name>register</name>
                    <path>affiliateplus/account/register</path>
                    <label>Signup</label>
                    <disabled helper="affiliateplus/account/isRegistered" />
                    <order>30</order>
                </action>
                <action method="addLink" translate="label" module="affiliateplus">
                    <name>banners</name>
                    <path>affiliateplus/banner/list</path>
                    <label><![CDATA[Banners & Links]]>
                    </label>
                    <disabled helper="affiliateplus/account/accountNotLogin" />
                    <order>40</order>
                </action>
                <action method="addLink" translate="label" module="affiliateplus">
                    <name>materials</name>
                    <path>affiliateplus/index/materials</path>
                    <label>Materials</label>
                    <disabled helper="affiliateplus/config/disableMaterials" />
                    <order>43</order>
                </action>
                <action method="addLink" translate="label" module="affiliateplus">
                    <name>sales</name>
                    <path>affiliateplus/index/listTransaction</path>
                    <label>Commissions</label>
                    <disabled helper="affiliateplus/account/accountNotLogin" />
                    <order>110</order>
                </action>
                <action method="addLink" translate="label" module="affiliateplus">
                    <name>payments</name>
                    <path>affiliateplus/index/payments</path>
                    <!-- <label>Withdrawals</label> -->
                    <label helper="affiliateplus/account/getWithdrawalLabel" />
                    <disabled helper="affiliateplus/account/hideWithdrawalMenu" />
                    <order>120</order>
                </action>
                <action method="addLink" translate="label" module="affiliateplus">
                    <name>referrers</name>
                    <path>affiliateplus/index/referrers</path>
                    <label>Traffics</label>
                    <disabled helper="affiliateplus/account/accountNotLogin" />
                    <order>180</order>
                </action>
                <action method="addLink" translate="label" module="affiliateplus">
                    <name>edit</name>
                    <path>affiliateplus/account/edit</path>
                    <label>Settings</label>
                    <disabled helper="affiliateplus/account/accountNotLogin" />
                    <order>190</order>
                </action>
                <action method="addLink" translate="label" module="affiliateplus">
                    <name>logout</name>
                    <path>affiliateplus/account/logout</path>
                    <label>Logout</label>
                    <disabled helper="affiliateplus/account/accountNotLogin" />
                    <order>200</order>
                </action>
            </block>
        </reference>
    </affiliateplus_default>
    
    <affiliateplus_index_index>
        <update handle="affiliateplus_default" />
        <reference name="content">
            <block type="affiliateplus/account_welcome" name="account_welcome">
                <block type="core/template" name="page_content_heading" template="cms/content_heading.phtml"/>
            </block>
            <block type="affiliateplus/account_program" name="account_program" template="affiliateplus/account/program.phtml" />
            <block type="affiliateplus/account_bottom" name="account_bottom" template="affiliateplus/account/bottom.phtml" />
        </reference>
    </affiliateplus_index_index>
    
    <affiliateplus_index_materials>
        <update handle="affiliateplus_default" />
        <reference name="content">
            <block type="affiliateplus/account_materials" name="affiliateplus_materials">
                <block type="core/template" name="page_content_heading" template="cms/content_heading.phtml"/>
            </block>
        </reference>
    </affiliateplus_index_materials>
    
    <affiliateplus_transaction>
        <update handle="affiliateplus_default" />
        <reference name="head" translate="title">
            <action method="setTitle">
                <title>Commissions</title>
            </action>
        </reference>
        <reference name="content">
            <block type="affiliateplus/sales" name="affiliateplus_sales" template="affiliateplus/sales.phtml">
                <block type="affiliateplus/payment_miniform" name="payment_miniform" />
                <block type="affiliateplus/sales_statistic" name="sales_statistic" template="affiliateplus/sales/statistic.phtml">
                    <action method="addTransactionBlock" translate="label" module="affiliateplus">
                        <name>standard</name>
                        <label>Pay per Sales</label>
                        <link>affiliateplus/index/listTransaction</link>
                        <type>affiliateplus/sales_standard</type>
                        <template>affiliateplus/sales/standard.phtml</template>
                    </action>
                </block>
            </block>
        </reference>
    </affiliateplus_transaction>
    <affiliateplus_index_listtransaction>
        <update handle="affiliateplus_transaction" />
        <reference name="affiliateplus_sales">
            <action method="activeTransactionBlock">
                <name>standard</name>
            </action>
        </reference>
    </affiliateplus_index_listtransaction>
    
    <affiliateplus_index_payments>
        <update handle="affiliateplus_default" />
        <reference name="content">
            <block type="affiliateplus/payment_list" name="affiliateplus_payment_list" template="affiliateplus/payment/list.phtml">
                <block type="affiliateplus/payment_miniform" name="payment_miniform" />
            </block>
        </reference>
    </affiliateplus_index_payments>
    
    <affiliateplus_index_viewpayment>
        <reference name="head" >
            <action method="addJs">
                <script>tinybox/tinybox.js</script>
            </action>
            <action method="addCss">
                <styleSheet>css/tinybox/style.css</styleSheet>
            </action>
        </reference>
        <update handle="affiliateplus_default" />
        <reference name="content">
            <block type="affiliateplus/payment_view" name="affiliateplus_payment_view" template="affiliateplus/payment/view.phtml" />
        </reference>
    </affiliateplus_index_viewpayment>
    
    <affiliateplus_index_paymentform>
        <reference name="head" >
            <action method="addJs">
                <script>tinybox/tinybox.js</script>
            </action>
            <action method="addCss">
                <styleSheet>css/tinybox/style.css</styleSheet>
            </action>
        </reference>
        <update handle="affiliateplus_default" />
        <reference name="content">
            <block type="affiliateplus/payment_request" name="affiliateplus_payment_request" template="affiliateplus/payment/request.phtml" />
        </reference>
    </affiliateplus_index_paymentform>
    <affiliateplus_index_verifyPayment>
        <reference name="content">
            <block type="affiliateplus/payment_verify" name="affiliateplus_payment_verify" template="affiliateplus/payment/verify.phtml" />
        </reference>
    </affiliateplus_index_verifyPayment>
    
    <affiliateplus_index_confirmrequest>
        <update handle="affiliateplus_default" />
        <reference name="content">
            <block type="affiliateplus/payment_confirm" name="affiliateplus_payment_confirm" template="affiliateplus/payment/confirm.phtml" />
        </reference>
    </affiliateplus_index_confirmrequest>
    
    <affiliateplus_index_referrers>
        <update handle="affiliateplus_default" />
        <reference name="content">
            <block type="affiliateplus/referrer" name="affiliateplus_referer" template="affiliateplus/referrer.phtml" />
        </reference>
    </affiliateplus_index_referrers>
    
    <affiliateplus_account_register>
        <update handle="affiliateplus_default" />
        <reference name="content">
            <block type="affiliateplus/account_edit" name="affiliateplus_register" template="affiliateplus/account/register.phtml" />
        </reference>
    </affiliateplus_account_register>
    
    <affiliateplus_account_login>
        <update handle="affiliateplus_default" />
        <reference name="content">
            <block type="affiliateplus/account_login" name="affiliateplus_login" template="affiliateplus/account/login.phtml" />
        </reference>
    </affiliateplus_account_login>
    
    <affiliateplus_account_edit>
        <update handle="affiliateplus_default" />
        <reference name="content">
            <block type="affiliateplus/account_edit" name="affiliateplus_register" template="affiliateplus/account/edit.phtml" />
        </reference>
    </affiliateplus_account_edit>
    
    <affiliateplus_banner_list>
        <update handle="affiliateplus_default" />
        <reference name="content">
            <block type="affiliateplus/account_banner" name="affiliateplus_banner" template="affiliateplus/account/banner.phtml" />
        </reference>
    </affiliateplus_banner_list>
    
    <affiliateplus_email_report>
        <block type="affiliateplus/email_report" name="affiliateplus_email_report" template="affiliateplus/email/report.phtml" />
    </affiliateplus_email_report>
    
    <sales_order_view>
        <reference name="order_totals">
            <block type="affiliateplus/sales_ordertotalaffiliate" />
            <block type="affiliateplus/sales_credit_order" />
        </reference>
    </sales_order_view>
	
    <sales_order_invoice>
        <reference name="invoice_totals">
            <block type="affiliateplus/sales_ordertotalaffiliate" />
            <block type="affiliateplus/sales_credit_invoice" />
        </reference>		
    </sales_order_invoice>
	
    <sales_order_creditmemo>
        <reference name="creditmemo_totals">
            <block type="affiliateplus/sales_ordertotalaffiliate" />
            <block type="affiliateplus/sales_credit_creditmemo" />
        </reference>		
    </sales_order_creditmemo>

    <sales_order_print>
        <reference name="order_totals">
            <block type="affiliateplus/sales_ordertotalaffiliate" />
            <block type="affiliateplus/sales_credit_order" />
        </reference>	
    </sales_order_print>	
	
    <sales_order_printinvoice>
        <reference name="invoice_totals">
            <block type="affiliateplus/sales_ordertotalaffiliate" />
            <block type="affiliateplus/sales_credit_invoice" />
        </reference>	
    </sales_order_printinvoice>		
	
    <sales_order_printcreditmemo>
        <reference name="creditmemo_totals">
            <block type="affiliateplus/sales_ordertotalaffiliate" />
            <block type="affiliateplus/sales_credit_creditmemo" />
        </reference>	
    </sales_order_printcreditmemo>		
	
    <sales_email_order_items>
        <reference name="order_totals">
            <block type="affiliateplus/sales_ordertotalaffiliate" />
            <block type="affiliateplus/sales_credit_order" />
        </reference>		
    </sales_email_order_items>	
	
    <sales_email_order_invoice_items>
        <reference name="invoice_totals">
            <block type="affiliateplus/sales_ordertotalaffiliate" />
            <block type="affiliateplus/sales_credit_invoice" />
        </reference>		
    </sales_email_order_invoice_items>
	
    <sales_email_order_creditmemo_items>
        <reference name="creditmemo_totals">
            <block type="affiliateplus/sales_ordertotalaffiliate" />
            <block type="affiliateplus/sales_credit_creditmemo" />
        </reference>		
    </sales_email_order_creditmemo_items>
    
</layout>