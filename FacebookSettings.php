<?php
namespace cmsgears\social\meta;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\config\CmgProperties;

class FacebookSettings extends CmgProperties {
	
	const CONFIG_ACTIVE		= 'active';
 	const CONFIG_PAGE		= 'page';
 	const CONFIG_POST		= 'post';
 	const CONFIG_APP_ID		= 'appId';
 	const CONFIG_AUTHOR		= 'author';
 	const CONFIG_PUBLISHER	= 'publisher';
 
	// Singleton instance
	private static $instance;

	// Constructor and Initialisation ------------------------------

 	private function __construct() {

	}

	/**
	 * Return Singleton instance.
	 */
	public static function getInstance() {

		if( !isset( self::$instance ) ) {

			self::$instance	= new TwitterSettings();

			self::$instance->init( 'twitter-meta' );
		}

		return self::$instance;
	}
	
	public function isActive() {
		
		return $this->properties[ self::CONFIG_ACTIVE ];
	}

	public function isPage() {
		
		return $this->properties[ self::CONFIG_PAGE ];
	}

	public function isPost() {
		
		return $this->properties[ self::CONFIG_POST ];
	}

	public function getAppId() {
		
		return $this->properties[ self::CONFIG_APP_ID ];
	}

	public function getAuthor() {
		
		return $this->properties[ self::CONFIG_AUTHOR ];
	}

	public function getPublisher() {
		
		return $this->properties[ self::CONFIG_PUBLISHER ];
	}
}

?>