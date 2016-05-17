<?php
class Wsu_WebmasterTools_Model_System_Config_Source_Analytics_Jtrackversion
{
    public function toOptionArray()
    {
		return [
            ['value' => '1', 'label' => 'up to date'],
			['value' => 'develop', 'label' => 'dev'],
        ];
    }
}