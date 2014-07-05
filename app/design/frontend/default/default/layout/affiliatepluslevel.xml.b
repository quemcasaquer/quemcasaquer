<?xml version="1.0"?>
<layout version="0.1.0">
    <default>
    </default>
    <affiliateplus_default>
		<reference name="account_navigator">
			<!--action method="addLink" translate="label" module="affiliateplus">
				<name>tiercommission</name><path>affiliatepluslevel/index/listTierTransaction</path><label>Tier Commissions</label><disabled helper="affiliateplus/account/accountNotLogin" /><order>115</order>
			</action-->
			<action method="addLink" translate="label" module="affiliateplus">
				<name>tiercommission</name><path>affiliatepluslevel/index/listTier</path><label>Tier Affiliates</label><disabled helper="affiliateplus/account/accountNotLogin" /><order>118</order>
			</action>
		</reference>
	</affiliateplus_default>
	
	<affiliateplus_transaction>
		<reference name="sales_statistic">
			<action method="addTransactionBlock" translate="label" module="affiliatepluslevel">
				<name>tier</name>
				<label>Tier Commissions</label>
				<link>affiliatepluslevel/index/listTierTransaction</link>
				<type>affiliatepluslevel/tiertransactions</type>
				<template>affiliatepluslevel/tiertransactions.phtml</template>
			</action>
		</reference>
	</affiliateplus_transaction>
	<affiliatepluslevel_index_listtiertransaction>
    	<update handle="affiliateplus_transaction" />
    	<reference name="affiliateplus_sales">
			<action method="activeTransactionBlock"><name>tier</name></action>
		</reference>
    </affiliatepluslevel_index_listtiertransaction>
	
	<affiliatepluslevel_index_listtier>
    	<update handle="affiliateplus_default" />
    	<reference name="content">
    		<block type="affiliatepluslevel/tiers" name="affiliateplus_tiers" template="affiliatepluslevel/tiers.phtml">
    		</block>
    	</reference>
    </affiliatepluslevel_index_listtier>
	
	<!--affiliateplus_index_listtransaction>
		<reference name="head" translate="title">
			<action method="setTitle"><title>Standard Commissions</title></action>
		</reference>
    	<reference name="affiliateplus_sales">
    		<action method="setTemplate"><template>affiliatepluslevel/sales.phtml</template></action>
			<block type="affiliatepluslevel/statistictransactions" name="statistictransactions" />
    	</reference>
    </affiliateplus_index_listtransaction-->
</layout> 