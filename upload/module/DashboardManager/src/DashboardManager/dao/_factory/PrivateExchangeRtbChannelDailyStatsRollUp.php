<?php
/**
 * NGINAD Project
 *
 * @link http://www.nginad.com
 * @copyright Copyright (c) 2013-2016 NginAd Foundation. All Rights Reserved
 * @license GPLv3
 */

namespace _factory;

use Zend\Db\TableGateway\Feature;

class PrivateExchangeRtbChannelDailyStatsRollUp extends \_factory\CachedTableRead
{

    static protected $instance = null;

    public static function get_instance() {

            if (self::$instance == null):
                    self::$instance = new \_factory\PrivateExchangeRtbChannelDailyStatsRollUp();
            endif;
            return self::$instance;
    }
    
    function __construct() {

            $this->table = 'PrivateExchangeRtbChannelDailyStatsRollUp';
            $this->featureSet = new Feature\FeatureSet();
            $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
            $this->initialize();
    }

    public function get_row($params = null) {
        // http://files.zend.com/help/Zend-Framework/zend.db.select.html

        $obj_list = array();

        $resultSet = $this->select(function (\Zend\Db\Sql\Select $select) use ($params) {
        	foreach ($params as $name => $value):
        	$select->where(
        			$select->where->equalTo($name, $value)
        	);
        	endforeach;
        	$select->limit(1, 0);
        	$select->order('ImpressionsOfferedCounter');

        }
        	);

    	    foreach ($resultSet as $obj):
    	         return $obj;
    	    endforeach;

        	return null;
    }

    public function get($params = null, $parent_id_exclude = null) {
    	// http://files.zend.com/help/Zend-Framework/zend.db.select.html
    
    	$obj_list = array();
    
    	$resultSet = $this->select(function (\Zend\Db\Sql\Select $select) use ($params, $parent_id_exclude) {
    		foreach ($params as $name => $value):
    		$select->where(
    				$select->where->equalTo($name, $value)
    		);
    		endforeach;
    		
    		if ($parent_id_exclude !== null):
	    		$select->where(
	    				$select->where->notEqualTo('ParentID', $parent_id_exclude)
	    		);
    		endif;
    		
    		//$select->limit(10, 0);
    		$select->order('ImpressionsOfferedCounter');
    
    	}
    		);
    
    		foreach ($resultSet as $obj):
    		$obj_list[] = $obj;
    		endforeach;
    
    		return $obj_list;
    }

};
