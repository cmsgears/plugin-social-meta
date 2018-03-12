<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\base\Migration;

use cmsgears\core\common\models\entities\Site;
use cmsgears\core\common\models\entities\User;
use cmsgears\core\common\models\resources\Form;
use cmsgears\core\common\models\resources\FormField;

use cmsgears\core\common\utilities\DateUtil;

/**
 * The social meta migration inserts the base data required to store social meta data.
 *
 * @since 1.0.0
 */
class m160622_061039_social_meta extends Migration {

	// Public Variables

	// Private Variables

	private $prefix;

	private $site;
	private $master;

	private $uploadsDir;
	private $uploadsUrl;

	public function init() {

		// Table prefix
		$this->prefix	= Yii::$app->migration->cmgPrefix;

		$this->site		= Site::findBySlug( CoreGlobal::SITE_MAIN );
		$this->master	= User::findByUsername( Yii::$app->migration->getSiteMaster() );

		$this->uploadsDir	= Yii::$app->migration->getUploadsDir();
		$this->uploadsUrl	= Yii::$app->migration->getUploadsUrl();

		Yii::$app->core->setSite( $this->site );
	}

	public function up() {

		// Create various config
		$this->insertFacebookConfig();
		$this->insertTwitterConfig();

		// Init default config
		$this->insertDefaultConfig();
	}

	private function insertFacebookConfig() {

		$this->insert( $this->prefix . 'core_form', [
			'siteId' => $this->site->id,
			'createdBy' => $this->master->id, 'modifiedBy' => $this->master->id,
			'name' => 'Config Facebook Meta', 'slug' => 'config-facebook-meta',
			'type' => CoreGlobal::TYPE_SYSTEM,
			'description' => 'Facebook Meta configuration form.',
			'successMessage' => 'All configurations saved successfully.',
			'captcha' => false,
			'visibility' => Form::VISIBILITY_PROTECTED,
			'active' => true, 'userMail' => false, 'adminMail' => false,
			'createdAt' => DateUtil::getDateTime(),
			'modifiedAt' => DateUtil::getDateTime()
		] );

		$config = Form::findBySlugType( 'config-facebook-meta', CoreGlobal::TYPE_SYSTEM );

		$columns = [ 'formId', 'name', 'label', 'type', 'compress', 'validators', 'order', 'icon', 'htmlOptions' ];

		$fields = [
			[ $config->id, 'active', 'Active', FormField::TYPE_TOGGLE, false, 'required', 0, null, '{"title":"activate or de-activate."}' ],
			[ $config->id, 'app_id', 'App Id', FormField::TYPE_TEXT, false, 'required', 0, null, '{"title":"fb app id","placeholder":"fb app id"}' ],
			[ $config->id, 'author', 'Author', FormField::TYPE_TEXT, false, null, 0, null, '{"title":"author","placeholder":"author"}' ],
			[ $config->id, 'publisher', 'Publisher', FormField::TYPE_TEXT, false, null, 0, null, '{"title":"publisher","placeholder":"publisher"}' ],
		];

		$this->batchInsert( $this->prefix . 'core_form_field', $columns, $fields );
	}

	private function insertTwitterConfig() {

		$this->insert( $this->prefix . 'core_form', [
			'siteId' => $this->site->id,
			'createdBy' => $this->master->id, 'modifiedBy' => $this->master->id,
			'name' => 'Config Twitter Meta', 'slug' => 'config-twitter-meta',
			'type' => CoreGlobal::TYPE_SYSTEM,
			'description' => 'Twitter Meta configuration form.',
			'successMessage' => 'All configurations saved successfully.',
			'captcha' => false,
			'visibility' => Form::VISIBILITY_PROTECTED,
			'active' => true, 'userMail' => false, 'adminMail' => false,
			'createdAt' => DateUtil::getDateTime(),
			'modifiedAt' => DateUtil::getDateTime()
		] );

		$config	= Form::findBySlugType( 'config-twitter-meta', CoreGlobal::TYPE_SYSTEM );

		$columns = [ 'formId', 'name', 'label', 'type', 'compress', 'validators', 'order', 'icon', 'htmlOptions' ];

		$fields = [
			[ $config->id, 'active', 'Active', FormField::TYPE_TOGGLE, false, 'required', 0, null, '{"title":"activate or de-activate."}' ],
			[ $config->id, 'card', 'Card', FormField::TYPE_SELECT, false, 'required', 0, null, '{"title":"Card types","items":["summary","summary_large_image","app","player"]}' ],
			[ $config->id, 'site', 'Site', FormField::TYPE_TEXT, false, null, 0, null, '{"title":"@username for the website used in the card footer.","placeholder":"@username"}' ],
			[ $config->id, 'creator', 'Creator', FormField::TYPE_TEXT, false, null, 0, null, '{"title":"@username for the content creator / author.","placeholder":"@username"}' ]
		];

		$this->batchInsert( $this->prefix . 'core_form_field', $columns, $fields );
	}

	private function insertDefaultConfig() {

		$columns = [ 'modelId', 'name', 'label', 'type', 'valueType', 'value' ];

		$metas = [
			[ $this->site->id, 'active', 'Active', 'facebook-meta', 'flag', '1' ],
			[ $this->site->id, 'app_id', 'App Id', 'facebook-meta', 'text', null ],
			[ $this->site->id, 'author', 'Author', 'facebook-meta', 'text', null ],
			[ $this->site->id, 'publisher', 'Publisher', 'facebook-meta', 'text', null ],
			[ $this->site->id, 'active', 'Active', 'twitter-meta', 'flag', '1' ],
			[ $this->site->id, 'card', 'Card', 'twitter-meta', 'text', 'summary_large_image' ],
			[ $this->site->id, 'site', 'Site', 'twitter-meta', 'text', null ],
			[ $this->site->id, 'creator', 'Creator', 'twitter-meta', 'text', null ]
		];

		$this->batchInsert( $this->prefix . 'core_site_meta', $columns, $metas );
	}

	public function down() {

		echo "m160622_061039_social_meta will be deleted with m160621_014408_core.\n";

		return true;
	}

}
