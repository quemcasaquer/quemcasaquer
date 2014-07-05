<?xml version="1.0"?>
<layout version="0.1.0">
	<affiliateplus_default>
		<reference name="account_navigator">
			<action method="addLink" translate="label" module="affiliatepluscoupon">
				<name>banners</name><path>affiliatepluscoupon/index/index</path><label><![CDATA[Coupon]]></label><disabled helper="affiliatepluscoupon/couponIsDisable" /><order>40</order>
			</action>
		</reference>
	</affiliateplus_default>
	<affiliatepluscoupon_index_index>
		<update handle="affiliateplus_default" />
		<reference name="head">
			<action method="addCss"><styleSheet>css/magestore/affiliatepluscoupon.css</styleSheet></action>
		</reference>
		<reference name="content">
			<block type="affiliatepluscoupon/affiliatepluscoupon" name="affiliatepluscoupon" template="affiliatepluscoupon/affiliatepluscoupon.phtml" />
		</reference>
	</affiliatepluscoupon_index_index>
</layout>