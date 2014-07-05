<?xml version="1.0"?>
<layout version="0.1.0">
    <affiliateplus_default>
    	<reference name="account_navigator">
    		<action method="addLink" translate="label" module="affiliatepluswidget">
    			<name>widget</name><path>affiliatepluswidget/index/index</path><label>My Widgets</label><disabled helper="affiliatepluswidget/disableMenu" /><order>42</order>
    		</action>
    	</reference>
    </affiliateplus_default>
    
    <affiliatepluswidget_index_index>
    	<update handle="affiliateplus_default" />
    	<reference name="head">
    		<action method="addItem"><type>skin_css</type><name>css/affiliatepluswidget/style.css</name></action>
    	</reference>
    	<reference name="content">
    		<block type="affiliatepluswidget/list" name="widget_list" template="affiliatepluswidget/list.phtml" />
    	</reference>
    </affiliatepluswidget_index_index>
    
    <affiliatepluswidget_index_edit>
    	<update handle="page_one_column" />
    	<reference name="head">
    		<action method="addJs"><script>magestore/affiliatepluswidget.js</script></action>
    		<!--action method="addItem"><type>skin_js</type><name>js/affiliatepluswidget/jscolor.js</name></action-->
    		<action method="addItem"><type>skin_js</type><name>js/affiliatepluswidget/tinybox.js</name></action>
    		<action method="addItem"><type>skin_css</type><name>css/affiliatepluswidget/style.css</name></action>
    		<action method="addItem"><type>skin_css</type><name>css/affiliatepluswidget/widget.css</name></action>
    	</reference>
    	<reference name="content">
    		<block type="affiliatepluswidget/form" name="widget_form" template="affiliatepluswidget/form.phtml" />
    	</reference>
    </affiliatepluswidget_index_edit>
    
    <catalog_product_view>
    	<reference name="head">
    		<action method="addItem"><type>skin_js</type><name>js/affiliatepluswidget/tinybox.js</name></action>
    		<action method="addItem"><type>skin_css</type><name>css/affiliatepluswidget/style.css</name></action>
    	</reference>
    	<reference name="product.info.extrahint">
    		<block type="affiliatepluswidget/link" name="product.get.widget" template="affiliatepluswidget/link.phtml" />
    	</reference>
    </catalog_product_view>
</layout>