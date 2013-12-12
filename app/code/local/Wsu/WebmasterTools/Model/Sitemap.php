<?php
class Wsu_WebmasterTools_Model_Sitemap extends Mage_Core_Model_Abstract {
    protected $_filePath;
    public $_sitemapInc = 1;
    public $_linkInc = 0;
    public $_totalProducts = 0;
    public $_currentInc = 0;

    protected function _construct() {
        $this->_init('webmastertools/sitemap');
    }

    public function generateAction() {
    	parent::generateAction();
    	Mage::dispatchEvent('sitemap_sitemap_generate', array('sitemap'=>$this));
    }


    protected function _beforeSave() {
        $io = new Varien_Io_File();
        $realPath = $io->getCleanPath(Mage::getBaseDir() . '/' . $this->getSitemapPath());

        if (!$io->allowedPath($realPath, Mage::getBaseDir())) {
            Mage::throwException(Mage::helper('wsu_webmastertools')->__('Please define correct path'));
        }

        if (!$io->fileExists($realPath, false)) {
            Mage::throwException(Mage::helper('wsu_webmastertools')->__('Please create the specified folder "%s" before saving the sitemap.', $this->getSitemapPath()));
        }

        if (!$io->isWriteable($realPath)) {
            Mage::throwException(Mage::helper('wsu_webmastertools')->__('Please make sure that "%s" is writable by web-server.', $this->getSitemapPath()));
        }

        if (!preg_match('#^[a-zA-Z0-9_\.]+$#', $this->getSitemapFilename())) {
            Mage::throwException(Mage::helper('wsu_webmastertools')->__('Please use only letters (a-z or A-Z), numbers (0-9) or underscore (_) in the filename. No spaces or other characters are allowed.'));
        }
        if (!preg_match('#\.xml$#', $this->getSitemapFilename())) {
            $this->setSitemapFilename($this->getSitemapFilename() . '.xml');
        }

        $this->setSitemapPath(rtrim(str_replace(str_replace('\\', '/', Mage::getBaseDir()), '', $realPath), '/') . '/');

        return parent::_beforeSave();
    }

    protected function getPath() {
        $storeId = $this->getStoreId();
        $store = Mage::app()->getStore($storeId);
        $code = ($store->getCode() == 'default') ? '' : $store->getCode();
        if (is_null($this->_filePath)) {
            $this->_filePath = $this->_deleteSpareSlashes(Mage::getBaseDir() . '/' . $code . '/' .$this->getSitemapPath());
        }
        return $this->_filePath;
    }

    protected function _deleteSpareSlashes($path) {
        $tempPath = str_replace('//', '/', $path);
        if (strpos($tempPath, '//') !== false) {
            return $this->_deleteSpareSlashes($tempPath);
        } else {
            return $tempPath;
        }
    }

    public function getPreparedFilename() {
        return $this->getPath() . $this->getSitemapFilename();
    }

    
    
    
    //$entity = 'category', 'product', 'tag', 'cms', 'additional_links', 'sitemap_finish'
    public function generateXml($entity=false) {
        $this->_useIndex = Mage::getStoreConfigFlag('webmastertools/google_sitemap/use_index');
        $this->_splitSize = (int) Mage::getStoreConfig('webmastertools/google_sitemap/split_size') * 1024;
        $this->_maxLinks = (int) Mage::getStoreConfig('webmastertools/google_sitemap/max_links');

        $io = new Varien_Io_File();
        $io->setAllowCreateFolders(true);
        $io->open(array('path' => $this->getPath()));
        
        // partial open or first open
        if (!$entity || $entity=='category') $this->_openXml($io); else $this->_openXml($io, true);
        
        $storeId = $this->getStoreId();
        $date = Mage::getSingleton('core/date')->gmtDate('Y-m-d');
        $mageUrl = $baseUrl = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);

        // generate categories
        if (!$entity || $entity=='category') {
            $changefreq = (string) Mage::getStoreConfig('webmastertools/google_sitemap/category_changefreq');
            $priority = (string) Mage::getStoreConfig('webmastertools/google_sitemap/category_priority');
                       
            // main page
            $xml = sprintf('<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%.1f</priority></url>',
                htmlspecialchars($baseUrl),
                $date,
                $changefreq,
                1
            );
            $io->streamWrite($xml);
            
            // categories
            $collection = Mage::getResourceModel('webmastertools/catalog_category')->getCollection($storeId);
            foreach ($collection as $item) {
                $xml = sprintf('<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%.1f</priority></url>',
                    htmlspecialchars($this->_trailingSlash($baseUrl . $item->getUrl())),
                    $date,
                    $changefreq,
                    $priority
                );
                $io->streamWrite($xml);

                $this->_checkSitemapLimits($io);
            }
            unset($collection);
        }    

        // generate products
        if (!$entity || $entity=='product') {
            $productImages = Mage::getStoreConfigFlag('webmastertools/google_sitemap/product_images');
            $imagesSize = (string) Mage::getStoreConfig('webmastertools/google_sitemap/product_images_size');
            if (!preg_match('/^\d+x\d+$/', $imagesSize)) {
                $imagesSize = false;
            }
            $changefreq = (string) Mage::getStoreConfig('webmastertools/google_sitemap/product_changefreq');
            $priority = (string) Mage::getStoreConfig('webmastertools/google_sitemap/product_priority');
            
            
            $this->_totalProducts = Mage::getResourceModel('webmastertools/catalog_product')->getCollection($this->getStoreId(), true);
            if ($this->_totalProducts>0) {
                if ($entity) {
                    $limit = 500;
                    if ($this->_currentInc<$this->_totalProducts) {
                        $collection = Mage::getResourceModel('webmastertools/catalog_product')->getCollection($storeId, false, $limit, $this->_currentInc);
                        $this->_currentInc += $limit;            
                        if ($this->_currentInc>=$this->_totalProducts) {
                            $this->_currentInc = $this->_totalProducts;
                            $result['stop'] = 1;
                        }                    
                    }
                } else {
                    $collection = Mage::getResourceModel('webmastertools/catalog_product')->getCollection($storeId);
                }    

                $useCategories = Mage::getStoreConfigFlag('catalog/seo/product_use_categories');

                foreach ($collection as $item) {
                    // apply crossDomain
                    
                    //print_r($item); exit;
                    
                    $crossDomainStore = false;
                    if ($item->getCanonicalCrossDomain()) {
                        $crossDomainStore = $item->getCanonicalCrossDomain();
                    } elseif (Mage::getStoreConfig('webmastertools/sitemaper/cross_domain')) {
                        $crossDomainStore = Mage::getStoreConfig('webmastertools/sitemaper/cross_domain');
                    }                    
                    
                    if ($crossDomainStore) $crossBaseUrl = Mage::app()->getStore($crossDomainStore)->getBaseUrl(); else $crossBaseUrl = $baseUrl;                    
                    
                    $images = '';
                    $gallery = $item->getGallery();
                    if (is_array($gallery) && $productImages) {
                        foreach ($gallery as $image) {
                            if ($image['disabled'] != 1) {
                                $images .= '<image:image><image:loc>' . htmlspecialchars($crossBaseUrl . 'catalog/product/image/size/' . ($imagesSize ? $imagesSize : '0x0') . $image['file']) . '</image:loc></image:image>';
                            }
                        }
                    }
                    
                    $xml = sprintf('<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%.1f</priority>%s</url>',
                                    htmlspecialchars($this->_trailingSlash($crossBaseUrl . $item->getUrl())),
                                    $date,
                                    $changefreq,
                                    $priority,
                                    $images
                    );
                    $io->streamWrite($xml);
                    $this->_checkSitemapLimits($io);
                }
                unset($collection);
            }   
        }    

        // generate tags
        if (!$entity || $entity=='tag') {
            $productTags = Mage::getStoreConfigFlag('webmastertools/google_sitemap/product_tags');
            if ($productTags) {
                $changefreq = (string) Mage::getStoreConfig('webmastertools/google_sitemap/product_tags_changefreq');
                $priority = (string) Mage::getStoreConfig('webmastertools/google_sitemap/product_tags_priority');
                $tags = Mage::getModel('tag/tag')->getPopularCollection()
                                ->joinFields(Mage::app()->getStore()->getId())
                                ->load();
                foreach ($tags as $item) {
                    $tagUrl = $this->_trailingSlash(str_replace($mageUrl, $baseUrl, $item->getTaggedProductsUrl()));
                    $xml = sprintf('<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%.1f</priority></url>',
                                    htmlspecialchars($tagUrl),
                                    $date,
                                    $changefreq,
                                    $priority
                    );
                    $io->streamWrite($xml);

                    $this->_checkSitemapLimits($io);
                }
                unset($collection);
            }
        }    

        // generate cms
        if (!$entity || $entity=='cms') {
            $changefreq = (string) Mage::getStoreConfig('webmastertools/google_sitemap/page_changefreq');
            $priority = (string) Mage::getStoreConfig('webmastertools/google_sitemap/page_priority');
            
            $collection = Mage::getResourceModel('webmastertools/cms_page')->getCollection($storeId);
            foreach ($collection as $item) {
                $xml = sprintf('<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%.1f</priority></url>',
                                htmlspecialchars($this->_trailingSlash($baseUrl . $item->getUrl())),
                                $date,
                                $changefreq,
                                $priority
                );
                $io->streamWrite($xml);
                $this->_checkSitemapLimits($io);
            }
            unset($collection);
            
            // add html sitemap
            $xml = sprintf('<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%.1f</priority></url>',
                    htmlspecialchars($this->_trailingSlash($baseUrl . 'sitemap')),
                    $date,
                    $changefreq,
                    $priority
            );
            $io->streamWrite($xml);
            $this->_checkSitemapLimits($io);
            
        }    

        
        if (!$entity || $entity=='additional_links') {
        
            $changefreq = (string) Mage::getStoreConfig('webmastertools/google_sitemap/link_changefreq');
            $priority = (string) Mage::getStoreConfig('webmastertools/google_sitemap/link_priority');
            $addLinks = array_filter(preg_split('/\r?\n/', Mage::getStoreConfig(Wsu_WebmasterTools_Block_Links::XML_PATH_ADD_LINKS, $storeId)));
            if (count($addLinks)) {
                foreach ($addLinks as $link) {
                    $_link = explode(',', $link, 2);
                    if (count($_link) == 2) {
                        $links[] = new Varien_Object(array('url' => Mage::getUrl((string) $_link[0])));
                    }
                }
            }
        
            $xml = Mage::getStoreConfig(Wsu_WebmasterTools_Block_Links::XML_PATH_ADD_LINKS, $storeId);
            try {
                $xmlLinks = simplexml_load_string($xml);
            } catch (Exception $e) {

            }
            if (!empty($xmlLinks) && count($xmlLinks)) {
                foreach ($xmlLinks as $link) {
                    $links[] = new Varien_Object(array('url' => (string) $link->href));
                }
            }
            if (!empty($links) && count($links)) {
                foreach ($links as $item) {
                    $xml = sprintf('<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%.1f</priority></url>',
                                    htmlspecialchars($baseUrl . $item->getUrl()),
                                    $date,
                                    $changefreq,
                                    $priority
                    );
                    $io->streamWrite($xml);
                    $this->_checkSitemapLimits($io);
                }
                unset($links);
            }
        }    

        if (!$entity || $entity=='sitemap_finish') Mage::dispatchEvent('webmastertools_sitemap_generate_after', array('io_sitemap' => $io));
        
        // partial close or final close
        if (!$entity || $entity=='sitemap_finish') $this->_closeXml($io); else $this->_closeXml($io, true);

        if (!$entity || $entity=='sitemap_finish') {
            $this->_generateSitemapIndex($io);
            $this->setSitemapTime(Mage::getSingleton('core/date')->gmtDate('Y-m-d H:i:s'));
            $this->save();
        }    

        return $this;
    }

    protected function _trailingSlash($url) {
        if (Mage::getStoreConfigFlag('webmastertools/sitemaper/trailing_slash') && substr($url, -1)!='/' && !in_array(substr(strrchr($url, '.'), 1), array('rss', 'html', 'htm', 'xml', 'php'))) {
            $url.= '/';
        }   
        return $url;
    }
    
    protected function _getSitemapFilename() {
        if ($this->_useIndex) {
            $sitemapFilename = $this->getData('sitemap_filename');
            $ext = strrchr($sitemapFilename, '.');
            $sitemapFilename = substr($sitemapFilename, 0, strlen($sitemapFilename) - strlen($ext)) . '_' . sprintf('%03s', $this->_sitemapInc) . $ext;

            return $sitemapFilename;
        }
        return $this->getData('sitemap_filename');
    }

    protected function _checkSitemapLimits($io) {
        if ($this->_useIndex) {
            $this->_linkInc++;
            if ($this->_linkInc == $this->_maxLinks || $io->streamStat('size') >= $this->_splitSize - 10240) {
                $this->_linkInc = 0;
                $this->_sitemapInc++;
                $this->_closeXml($io);
                $this->_openXml($io);
            }
        }
    }

    protected function _openXml($io, $append = false) {
        if ($io->fileExists($this->_getSitemapFilename()) && !$io->isWriteable($this->_getSitemapFilename())) {
            Mage::throwException(Mage::helper('wsu_webmastertools')->__('File "%s" cannot be saved. Please, make sure the directory "%s" is writeable by web server.', $this->_getSitemapFilename(), $this->getPath()));
        }

        if ($append) $mode = 'a+'; else $mode = 'w+';       
        $io->streamOpen($this->_getSitemapFilename(), $mode);

        if (!$append) {
            $io->streamWrite('<?xml version="1.0" encoding="UTF-8"?>' . "\n");
            $io->streamWrite('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n" . ' xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">');
        }    
    }

    protected function _closeXml($io, $append = false) {
        if (!$append) $io->streamWrite('</urlset>');
        $io->streamClose();
    }

    protected function _generateSitemapIndex($io) {
        if (!$this->_useIndex) {
            return;
        }

        if ($io->fileExists($this->getSitemapFilename()) && !$io->isWriteable($this->getSitemapFilename())) {
            Mage::throwException(Mage::helper('wsu_webmastertools')->__('File "%s" cannot be saved. Please, make sure the directory "%s" is writeable by web server.', $this->getSitemapFilename(), $this->getPath()));
        }

        $io->streamOpen($this->getSitemapFilename());

        $io->streamWrite('<?xml version="1.0" encoding="UTF-8"?>' . "\n");
        $io->streamWrite('<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">');

        $storeId = $this->getStoreId();
        $date = Mage::getSingleton('core/date')->gmtDate('Y-m-d');
        $baseUrl = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);

        $i = $this->_sitemapInc;

        for ($this->_sitemapInc = 1; $this->_sitemapInc <= $i; $this->_sitemapInc++) {
            $fileName = preg_replace('/^\//', '', $this->getSitemapPath() . $this->_getSitemapFilename());
            if (file_exists(BP . DS . $fileName)) {
                $xml = sprintf('<sitemap><loc>%s</loc><lastmod>%s</lastmod></sitemap>',
                                htmlspecialchars($baseUrl . $fileName),
                                $date
                );
                $io->streamWrite($xml);
            }
        }

        $io->streamWrite('</sitemapindex>');
        $io->streamClose();
    }
}
