<?php
class Wsu_WebmasterTools_Model_Mysql4_Core_Url_Rewrite_Collection extends Mage_Core_Model_Mysql4_Url_Rewrite_Collection {

    protected function _initSelect() {
        $this->getSelect()->from(array('main_table' => $this->getResource()->getMainTable()), array('*', new Zend_Db_Expr('LENGTH(request_path)')));
        return $this;
    }

    public function sortByLength($spec = 'DESC') {
        $this->getSelect()->order(new Zend_Db_Expr('LENGTH(request_path) ' . $spec));
        return $this;
    }
	//@todo refactor this function not to use the DB directly!
    public function filterAllByProductId($productId, $useCategories = false) {
        if ($productId != null) {
            if ($useCategories==1) {
                // longest
                $this->getSelect()->where('product_id = ? AND category_id is not null AND is_system = 1', $productId, Zend_Db::INT_TYPE);
                $this->sortByLength('DESC');
            } else if ($useCategories == 2) {
                // shortest
                $this->getSelect()->where('product_id = ? AND category_id is null AND is_system = 1', $productId, Zend_Db::INT_TYPE);
                $this->sortByLength('ASC');
            } else {
                // root or other
                $this->getSelect()->where('product_id = ? AND is_system = 1', $productId, Zend_Db::INT_TYPE);
            }
        }

        return $this;
    }

    public function groupByUrl() {
        $this->getSelect()->group('request_path');
        return $this;
    }
    
    public function filterByIdPath($idPath) {
        $this->getSelect()
                ->where('id_path = ?', $idPath);
        return $this;
    }

}