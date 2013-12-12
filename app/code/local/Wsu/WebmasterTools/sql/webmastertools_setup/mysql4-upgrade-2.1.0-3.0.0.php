<?php
$installer = $this;
$installer->startSetup();
 
$installer->addAttribute('catalog_product', 'exclude_from_sitemap', array(
    'group'             => 'Meta Information',
    'type'              => 'int',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Exclude from XML Sitemap',
    'input'             => 'select',
    'class'             => '',
    'source'            => 'eav/entity_attribute_source_boolean',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '0',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'sort_order'        => 70
));

$installer->addAttribute('catalog_category', 'exclude_from_sitemap',  array(
    'type'     => 'int',
    'label'    => 'Exclude from XML Sitemap',
    'input'    => 'select',
    'source'   => 'eav/entity_attribute_source_boolean',
    'global'   => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'required' => false,
    'default'  => 0
));


if (!$installer->getConnection()->tableColumnExists($installer->getTable('cms_page'), 'exclude_from_sitemap')) {
    $installer->getConnection()->addColumn($installer->getTable('cms_page'), 'exclude_from_sitemap', "tinyint(3) NOT NULL DEFAULT '0'");
}

$installer->endSetup();