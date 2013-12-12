<?php
class Wsu_WebmasterTools_Block_Adminhtml_Sitemapping_Grid extends Mage_Adminhtml_Block_Widget_Grid {
    public function __construct() {
		//die("Wsu_WebmasterTools_Block_Adminhtml_Sitemapping_Grid");
        parent::__construct();
        $this->setId('sitemapGrid');
        $this->setDefaultSort('sitemap_id');
		$this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
		//die("_prepareCollection");
        $collection = Mage::getModel('webmastertools/sitemap')->getCollection();
        $collection->setOrder('sitemap_id', 'DESC');
        $this->setCollection($collection);	
		return parent::_prepareCollection();
    }

	
    protected function _prepareColumns() {//die("_prepareColumns");
        $this->addColumn('sitemap_id', array(
            'header' => Mage::helper('sitemap')->__('ID'),
            'width' => '50px',
            'index' => 'sitemap_id'
        ));
        $this->addColumn('sitemap_filename', array(
            'header' => Mage::helper('sitemap')->__('Filename'),
            'index' => 'sitemap_filename'
        ));
        $this->addColumn('sitemap_path', array(
            'header' => Mage::helper('sitemap')->__('Path'),
            'index' => 'sitemap_path'
        ));
        $this->addColumn('link', array(
            'header' => Mage::helper('sitemap')->__('Link for Google'),
            'index' => 'concat(sitemap_path, sitemap_filename)',
            'renderer' => 'webmastertools/adminhtml_sitemapping_grid_renderer_link'
        ));
        $this->addColumn('sitemap_time', array(
            'header' => Mage::helper('sitemap')->__('Last Time Generated'),
            'width' => '150px',
            'index' => 'sitemap_time',
            'type' => 'datetime'
        ));
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header' => Mage::helper('sitemap')->__('Store View'),
                'index' => 'store_id',
                'type' => 'store'
            ));
        }
        $this->addColumn('action', array(
            'header' => Mage::helper('sitemap')->__('Action'),
            'filter' => false,
            'sortable' => false,
            'width' => '100',
            'renderer' => 'webmastertools/adminhtml_sitemapping_grid_renderer_action'
        ));
        return parent::_prepareColumns();
    }
    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array(
            'sitemap_id' => $row->getId()
        ));
    }
}
