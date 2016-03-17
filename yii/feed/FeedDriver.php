<?php
/**
 * FeedDriver.php
 * @author: Ivan Shaposhnikov <haposhnikoff2008@ukr.net>
 * @date  : 17.03.2016
 */

namespace yii\feed;

use Yii;
use yii\base\Component;
use yii\base\ErrorException;
use Zend\Feed\Writer\Feed;
use Zend\Feed\Reader\Reader;

/**
 * Class FeedDriver
 * The main class to wrap Zend Feed Extension
 * @package yii\feed
 */
class FeedDriver extends Component
{
        /**
         * Loads read Zend-feed component
         * @return mixed object Zend\Feed\Reader component
         */
        public function reader(){
            
            return new Reader;
        }
        /**
         * Loads read Zend-feed component
         * @return mixed object Zend\Feed\Writer component
         */
        public function writer(){
            
            return new Feed;
        }
}
?>
