<?php
class Wsu_WebmasterTools_Model_System_Config_Source_Analytics_Campus
{
    public function toOptionArray()
    {
		return [
            ['value' => 'all', 'label' => 'All'],
			['value' => 'pullman', 'label' => 'Pullman'],
			['value' => 'spokane', 'label' => 'Spokane'],
			['value' => 'vancouver', 'label' => 'Vancouver'],
			['value' => 'tri-cities', 'label' => 'Tri-Cities'],
			['value' => 'globalcampus', 'label' => 'Global Campus'],
			['value' => 'everett', 'label' => 'Everett']
        ];
    }
}