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
 * TwitterMetaProperties provide methods to access the meta properties specific to Twitter.
 *
 * @since 1.0.0
 */
class TwitterMetaProperties extends Properties {

	// Variables ---------------------------------------------------

	// Globals ----------------

	const CONFIG_ACTIVE		= 'active';

 	const CONFIG_CARD		= 'card';

 	const CONFIG_SITE		= 'site';

 	const CONFIG_CREATOR	= 'creator';

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

			self::$instance	= new TwitterMetaProperties();

			self::$instance->init( 'twitter-meta' );
		}

		return self::$instance;
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// TwitterMetaProperties -----------------

	public function isActive() {

		return $this->properties[ self::CONFIG_ACTIVE ];
	}

	public function getCard() {

		return $this->properties[ self::CONFIG_CARD ];
	}

	public function getSite() {

		return $this->properties[ self::CONFIG_SITE ];
	}

	public function getCreator() {

		return $this->properties[ self::CONFIG_CREATOR ];
	}

}
