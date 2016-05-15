<?php
class Wsu_WebmasterTools_Model_System_Config_Source_Analytics_College
{
    public function toOptionArray()
    {
		return [
            ['value' => 'none', 'label' => 'none'],
			['value' => 'arts-and-sciences', 'label' => 'Arts & Sciences'],
			['value' => 'cahnrs', 'label' => 'CAHNRS & Extension'],
			['value' => 'carson', 'label' => 'Carson'],
			['value' => 'education', 'label' => 'Education'],
			['value' => 'honors', 'label' => 'Honors'],
			['value' => 'medicine', 'label' => 'Medicine'],
			['value' => 'murrow', 'label' => 'Murrow'],
			['value' => 'nursing', 'label' => 'Nursing'],
			['value' => 'pharmacy', 'label' => 'Pharmacy'],
			['value' => 'vetmed', 'label' => 'VetMed'],
			['value' => 'voiland', 'label' => 'Voiland']
        ];
    }
}