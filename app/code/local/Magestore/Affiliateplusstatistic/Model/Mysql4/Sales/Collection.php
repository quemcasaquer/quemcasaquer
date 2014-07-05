<?php

class Magestore_Affiliateplusstatistic_Model_Mysql4_Sales_Collection extends Magestore_Affiliateplus_Model_Mysql4_Transaction_Collection
{
    public function prepareSummary($range, $customStart, $customEnd, $isFilter = 0,$account_id = null){
    	// $this->setMainTable('affiliateplus/transaction');
    	$adapter = $this->getConnection();
    	
    	$this->getSelect()->reset(Zend_Db_Select::COLUMNS);
        
    	if($account_id)
            $this->getSelect()->where('account_id = '.$account_id);
    	$this->getSelect()->columns(array(
    		'amount' 		=> 'SUM(total_amount)',
    		'transactions'	=> 'COUNT(transaction_id)',
    		'commissions'	=> 'SUM(commission)',
    	));
    	
    	$dateRange = $this->getDateRange($range,$customStart,$customEnd);
    	//$tzRangeOffsetExpression = $this->_getTZRangeOffsetExpression($range,'created_time',$dateRange['from'], $dateRange['to']);
    	
    	$this->getSelect()->columns(array('range' => $this->_getRangeExpressionForAttribute($range,'created_time')))//$tzRangeOffsetExpression))
    		->where('main_table.status = 1')
    		->order('range',Zend_Db_Select::SQL_ASC)
    		->group('range');//$tzRangeOffsetExpression);
    	
    	$this->addFieldToFilter('created_time', $dateRange);
        //Zend_Debug::dump($this->getSelect()->__toString());die();
    	return $this;
    }
    
    public function prepareTotal($range, $customStart, $customEnd, $isFilter = 0,$account_id = null){
    	// $this->setMainTable('affiliateplus/transaction');
    	$adapter = $this->getConnection();
    	
    	$this->getSelect()->reset(Zend_Db_Select::COLUMNS);
    	if($account_id)
            $this->getSelect()->where('account_id = '.$account_id);
    	$this->getSelect()->columns(array(
    		'total_amount'		=> 'SUM(total_amount)',
    		'total_transaction'	=> 'COUNT(transaction_id)',
    		'total_commission'	=> 'SUM(commission)',
    	));
    	
    	$dateRange = $this->getDateRange($range,$customStart,$customEnd);
    	
    	$this->addFieldToFilter('status',1)
    		->addFieldToFilter('created_time',$dateRange);
    	return $this;
    }
    
    
        /* 23-04-2014 hainh update function fix error displaying google chart */
    public function prepareLifeTimeTotal($account_id = null){
    	// $this->setMainTable('affiliateplus/transaction');
    	$this->getSelect()->reset(Zend_Db_Select::COLUMNS);
    	if($account_id)
            $this->getSelect()->where('account_id = '.$account_id);
    	$this->getSelect()->columns(array(
    		'total'	=> 'COUNT(transaction_id)',
                'status' => 'status'
    	))->group('status')
    	->order('status',Zend_Db_Select::SQL_ASC)
    	->where("status in ('1','2','3')");
    	return $this;
    }
    
    /**
     * Get range expression
     *
     * @param string $range
     * @return Zend_Db_Expr
     */
    protected function _getRangeExpression($range){
        switch ($range)
        {
            case '24h':
                /*$expression = $this->getConnection()->getConcatSql(array(
                    $this->getConnection()->getDateFormatSql('{{attribute}}', '%Y-%m-%d %H:'),
                    $this->getConnection()->quote('00')
                ));*/
                $expression = 'DATE_FORMAT({{attribute}}, \'%Y-%m-%d %H:00\')';
                break;
            case '7d':
            case '1m':
                //$expression = $this->getConnection()->getDateFormatSql('{{attribute}}', '%Y-%m-%d');
                $expression = 'DATE_FORMAT({{attribute}}, \'%Y-%m-%d\')';
                break;
            case '1y':
            case '2y':
            case 'custom':
            default:
                //$expression = $this->getConnection()->getDateFormatSql('{{attribute}}', '%Y-%m');
                $expression = 'DATE_FORMAT({{attribute}}, \'%Y-%m\')';
                break;
        }

        return $expression;
    }
    
    /**
     * Retrieve range expression adapted for attribute
     *
     * @param string $range
     * @param string $attribute
     * @return string
     */
    protected function _getRangeExpressionForAttribute($range, $attribute){
        $expression = $this->_getRangeExpression($range);
        return str_replace('{{attribute}}', $this->getConnection()->quoteIdentifier($attribute), $expression);
    }
    
    /**
     * Retrieve query for attribute with timezone conversion
     *
     * @param string $range
     * @param string $attribute
     * @param mixed $from
     * @param mixed $to
     * @return string
     */
    protected function _getTZRangeOffsetExpression($range, $attribute, $from = null, $to = null){
        return str_replace(
            '{{attribute}}',
            Mage::getResourceModel('sales/report_order')
                    ->getStoreTZOffsetQuery($this->getMainTable(), $attribute, $from, $to),
            $this->_getRangeExpression($range)
        );
    }
    
    /**
     * Calculate From and To dates (or times) by given period
     *
     * @param string $range
     * @param string $customStart
     * @param string $customEnd
     * @param boolean $returnObjects
     * @return array
     */
    public function getDateRange($range, $customStart, $customEnd, $returnObjects = false){
        $dateEnd   = Mage::app()->getLocale()->date();
        $dateStart = clone $dateEnd;

        // go to the end of a day
        $dateEnd->setHour(23);
        $dateEnd->setMinute(59);
        $dateEnd->setSecond(59);

        $dateStart->setHour(0);
        $dateStart->setMinute(0);
        $dateStart->setSecond(0);

        switch ($range)
        {
            case '24h':
                $dateEnd = Mage::app()->getLocale()->date();
                $dateEnd->addHour(1);
                $dateStart = clone $dateEnd;
                $dateStart->subDay(1);
                break;

            case '7d':
                // substract 6 days we need to include
                // only today and not hte last one from range
                $dateStart->subDay(6);
                break;

            case '1m':
                $dateStart->setDay(Mage::getStoreConfig('affiliateplus/statistic/mtd_start'));
                break;

            case 'custom':
                $dateStart = $customStart ? $customStart : $dateEnd;
                $dateEnd   = $customEnd ? $customEnd : $dateEnd;
                break;

            case '1y':
            case '2y':
                $startMonthDay = explode(',', Mage::getStoreConfig('affiliateplus/statistic/ytd_start'));
                $startMonth = isset($startMonthDay[0]) ? (int)$startMonthDay[0] : 1;
                $startDay = isset($startMonthDay[1]) ? (int)$startMonthDay[1] : 1;
                $dateStart->setMonth($startMonth);
                $dateStart->setDay($startDay);
                if ($range == '2y') {
                    $dateStart->subYear(1);
                }
                $dateEnd->setDay(1);
                $dateEnd->addMonth(1);
                $dateEnd->subDay(1);
                break;
        }

        $dateStart->setTimezone('Etc/UTC');
        $dateEnd->setTimezone('Etc/UTC');

        if ($returnObjects) {
            return array($dateStart, $dateEnd);
        } else {
            return array('from' => $dateStart, 'to' => $dateEnd, 'datetime' => true);
        }
    }
}