<?php
class Wsu_WebmasterTools_Model_System_Config_Source_Analytics_Unittypes
{
    public function toOptionArray()
    {
		return [
            ['value' => 'center', 'label' => 'Center'],
            ['value' => 'department', 'label' => 'Department'],
            ['value' => 'laboratory', 'label' => 'Laboratory'],
            ['value' => 'office', 'label' => 'Office'],
            ['value' => 'program', 'label' => 'Program'],
            ['value' => 'school', 'label' => 'School'],
            ['value' => 'unit',	 'label' => 'Unit']
        ];
    }
}