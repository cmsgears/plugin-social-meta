<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\social\meta\components;

// Yii Imports
use Yii;
use yii\base\Component;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreProperties;
use cmsgears\cms\common\config\CmsGlobal;
use cmsgears\core\frontend\config\SiteProperties;

use cmsgears\social\meta\config\FacebookMetaProperties;
use cmsgears\social\meta\config\TwitterMetaProperties;

use cmsgears\core\common\utilities\CodeGenUtil;

/**
 * The SocialMeta component generates the meta tags required for social network to show the
 * content on their site and application.
 */
class SocialMeta extends Component {

	public $model;
	public $summary;

	public $homePage;

	public $twitter		= false;
	public $facebook	= false;

	// TODO: Add config to use thumb or image.

	// TODO: Use DB Config to configure all the meta tags

	// TODO: Get real FB Author details instead of global author

	// TODO: Add code to configure card from page or post attributes

	// Helper to generate tags
	public static function getMetaTags( $params, $config = [] ) {

		if( empty( $params[ 'model' ] ) ) {

			return;
		}

		$config[ 'model' ]		= $params[ 'model' ];
		$config[ 'summary' ]	= $params[ 'summary' ];
		$config[ 'homePage' ]	= isset( $params[ 'homePage' ] ) ? $params[ 'homePage' ] : 'home';

		$meta = new SocialMeta( $config );

		return $meta->generatePageMetaTags();
	}

	public function generatePageMetaTags() {

		$content = '';

		if( isset( $this->model ) ) {

			$ogUrl	= Yii::$app->request->absoluteUrl;
			$banner	= ( isset( $this->model->modelContent ) ) ? $this->model->modelContent->banner : ( isset( $this->model->banner ) ? $this->model->banner : null );

			// Home Page
			if( isset( $this->model->slug ) && strcmp( $this->model->slug, $this->homePage ) == 0 ) {

				$ogUrl	= Url::toRoute( [ '/' ], true );
			}

			$metaContent = [];

			if( $this->facebook ) {

				$metaContent = $this->generateFacebookTags( $ogUrl, $banner, $metaContent );
			}

			if( $this->twitter ) {

				$metaContent = $this->generateTwitterCard( $banner, $metaContent );
			}

			foreach( $metaContent as $value ) {

				$content = $content . $value;
			}

			$content = $content . "<link rel=\"canonical\" href=\"$ogUrl\"/>";
		}

		return $content;
	}

	private function generateFacebookTags( $ogUrl, $banner, $metaContent ) {

		$properties = FacebookMetaProperties::getInstance();

		if( $properties->isActive() ) {

			$coreProperties	= CoreProperties::getInstance();
			$siteName		= $coreProperties->getSiteName();
			$locale			= $coreProperties->getLocale();

			$appId	= $properties->getAppId();

			$model		= $this->model;
			$summary	= filter_var( $this->summary, FILTER_SANITIZE_STRING );

			$metaContent[ 'otitle' ]	= "<meta property=\"og:title\" content=\"$model->displayName\" />";
			$metaContent[ 'osite' ]		= "<meta property=\"og:site_name\" content=\"$siteName\"/>";
			$metaContent[ 'ourl' ]		= "<meta property=\"og:url\" content=\"$ogUrl\" />";
			$metaContent[ 'odesc' ]		= "<meta property=\"og:description\" content=\"$summary\"/>";
			$metaContent[ 'olocale' ]	= "<meta property=\"og:locale\" content=\"$locale\" />";
			$metaContent[ 'otype' ]		= "<meta property=\"og:type\" content=\"website\" />";

			if( isset( $appId ) ) {

				$metaContent[ 'fid' ] = "<meta property=\"fb:app_id\" content=\"$appId\" />";
			}

			$defaultBanner = SiteProperties::getInstance()->getDefaultBanner();

			if( isset( $banner ) || !empty( $defaultBanner ) ) {

				$banner		= isset( $model->bannerId ) && isset( $model->banner ) ? $model->banner : ( isset( $model->modelContent ) ? $model->modelContent->banner : null );
				$imageUrl	= CodeGenUtil::getFileUrl( $banner, [ 'image' => $defaultBanner ] );

				$filePath = !empty( $banner ) ? $banner->getFilePath() : Yii::getAlias( '@webroot' ) . "/images/$defaultBanner";

				$metaContent[ 'oimage' ] = "<meta property=\"og:image\" content=\"$imageUrl\">";

				if( isset( $filePath ) ) {

					list($width, $height, $type, $attr) = getimagesize( $filePath );

					$metaContent[ 'oimagew' ] = "<meta property=\"og:image:width\" content=\"$width\">";
					$metaContent[ 'oimageh' ] = "<meta property=\"og:image:height\" content=\"$height\">";
					$metaContent[ 'oimaget' ] = "<meta property=\"og:image:type\" content=\"$type\">";
				}
			}

			if( in_array( $this->model->type, [ CmsGlobal::TYPE_ARTICLE, CmsGlobal::TYPE_POST ] ) ) {

				// TODO: User Facebook Id of the model creator
				$author = $properties->getAuthor();

				$publisher = $properties->getPublisher();

				$metaContent[ 'otype' ] = "<meta property=\"og:type\" content=\"article\" />";

				if( !empty( $author ) ) {

					$metaContent[ 'aauthor' ] = "<meta property=\"article:author\" content=\"$author\" />";
				}

				if( !empty( $publisher ) ) {

					$metaContent[ 'apublisher' ] = "<meta property=\"article:publisher\" content=\"$publisher\" />";
				}
			}

			return $metaContent;
		}

		return '';
	}

	/**
	 * Generate and return appropriate Twitter Card.
	 *
	 * @param string $banner
	 * @param array $metaContent
	 * @return array
	 */
	private function generateTwitterCard( $banner, $metaContent ) {

		$properties = TwitterMetaProperties::getInstance();

		if( $properties->isActive() ) {

			$card		= $properties->getCard();

			$site		= $properties->getSite();
			$creator	= $properties->getCreator();
			$model		= $this->model;
			$summary	= filter_var( $this->summary, FILTER_SANITIZE_STRING );

			// Configure Card, Site and Creator
			if( isset( $card ) ) {

				$metaContent[ 'tcard' ] = "<meta name=\"twitter:card\" content=\"summary_large_image\" />";

				if( !empty( $site ) ) {

					$metaContent[ 'tsite' ] = "<meta name=\"twitter:site\" content=\"$site\" />";
				}

				if( !empty( $creator ) ) {

					$metaContent[ 'tcreator' ] = "<meta name=\"twitter:creator\" content=\"$creator\" />";
				}
			}

			$defaultBanner = SiteProperties::getInstance()->getDefaultBanner();

			// TODO: Add support for player and app cards generated by Twitter
			switch( $card ) {

				case 'summary': {

					if( empty( $metaContent[ 'otitle' ] ) ) {

						$metaContent[ 'otitle' ] = "<meta name=\"twitter:title\" content=\"$model->displayName\" />";
					}

					if( empty( $metaContent[ 'odesc' ] ) ) {

						$metaContent[ 'tdesc' ] = "<meta name=\"twitter:description\" content=\"$summary\" />";
					}

					if( empty( $metaContent[ 'oimage' ] ) && ( isset( $banner ) || isset( $defaultBanner ) ) ) {

						$imageUrl = isset( $banner ) ? $banner->getMediumUrl() : Yii::getAlias( '@webroot' ) . "/images/$defaultBanner";

						$metaContent[ 'timage' ] = "<meta name=\"twitter:image\" content=\"$imageUrl\" />";
					}

					if( isset( $banner ) && !empty( $banner->altText ) ) {

						$metaContent[ 'timagealt' ] = "<meta name=\"twitter:image:alt\" content=\"$banner->altText\" />";
					}

					break;
				}
				case 'summary_large_image': {

					if( empty( $metaContent[ 'otitle' ] ) ) {

						$metaContent[ 'otitle' ] = "<meta name=\"twitter:title\" content=\"$model->displayName\" />";
					}

					if( empty( $metaContent[ 'odesc' ] ) ) {

						$metaContent[ 'tdesc' ] = "<meta name=\"twitter:description\" content=\"$summary\" />";
					}

					if( empty( $metaContent[ 'oimage' ] ) && ( isset( $banner ) || isset( $defaultBanner ) ) ) {

						$imageUrl = isset( $banner ) ? $banner->getFileUrl() : Yii::getAlias( '@webroot' ) . "/images/$defaultBanner";

						$metaContent[ 'timage' ] = "<meta name=\"twitter:image\" content=\"$imageUrl\" />";
					}

					if( isset( $banner ) && !empty( $banner->altText ) ) {

						$metaContent[ 'timagealt' ] = "<meta name=\"twitter:image:alt\" content=\"$banner->altText\" />";
					}

					break;
				}
				case 'app': {

					break;
				}
				case 'player': {

					break;
				}
			}
		}

		return $metaContent;
	}

}
