<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\social\meta\config;

// CMG Imports
use cmsgears\core\common\config\Properties;

/**
 * FacebookMetaProperties provide methods to access the meta properties specific to Facebook.
 *
 * @since 1.0.0
 */
class FacebookMetaProperties extends Properties {

	// Variables ---------------------------------------------------

	// Globals ----------------

	const CONFIG_ACTIVE		= 'active';

 	const CONFIG_APP_ID		= 'app_id';

	const CONFIG_AUTHOR		= 'author';

	const CONFIG_PUBLISHER	= 'publisher';

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private static $instance;

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	/**
	 * Return Singleton instance.
	 */
	public static function getInstance() {

		if( !isset( self::$instance ) ) {

			self::$instance	= new FacebookMetaProperties();

			self::$instance->init( 'facebook-meta' );
		}

		return self::$instance;
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// FacebookMetaProperties ----------------

	public function isActive() {

		return $this->properties[ self::CONFIG_ACTIVE ];
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
