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
use yii\base\Component;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreProperties;

use cmsgears\social\meta\config\FacebookMetaProperties;
use cmsgears\social\meta\config\TwitterMetaProperties;

/**
 * The SocialMeta component generates the meta tags required for social network to show the
 * content on their site and application.
 */
class SocialMeta extends Component {

	public $page;
	public $model;
	public $content;

	public $homePage;
	public $basePath;

	public $twitter		= false;
	public $facebook	= false;

	// TODO: Add config to use thumb or image.

	// TODO: Use DB Config to configure all the meta tags

	// TODO: Get real FB Author details instead of global author

	// TODO: Add code to configure card from page or post attributes

	// Helper to generate tags
	public static function getMetaTags( $params, $config = [] ) {

		if( isset( $params[ 'page' ] ) ) {

			$config[ 'page' ]		= $params[ 'page' ];
			$config[ 'content' ]	= $params[ 'content' ];
			$config[ 'homePage' ]	= isset( $params[ 'homePage' ] ) ? $params[ 'homePage' ] : 'home';
			$config[ 'basePath' ]	= isset( $params[ 'basePath' ] ) ? $params[ 'basePath' ] : 'post';

			$meta = new SocialMeta( $config );

			return $meta->generatePageMetaTags();
		}
		else if( isset( $params[ 'model' ] ) ) {

			$config[ 'model' ]		= $params[ 'model' ];
			$config[ 'content' ]	= isset( $params[ 'content' ] ) ? $params[ 'content' ] : null;
			$config[ 'basePath' ]	= isset( $params[ 'basePath' ] ) ? $params[ 'basePath' ] : null;

			$meta = new SocialMeta( $config );

			return $meta->generateModelMetaTags();
		}
	}

	public function generatePageMetaTags() {

		if( isset( $this->page ) ) {

			$ogUrl	= null;
			$banner	= $this->content->banner;

			// Post
			if( $this->page->isPost() ) {

				$slug	= $this->page->slug;
				$ogUrl	= Url::toRoute( [ "/$this->postBasePath/$slug" ], true );
			}
			// Page
			else {

				if( strcmp( $this->page->slug, $this->homePage ) == 0 ) {

					$ogUrl	= Url::toRoute( [ '/' ], true );
				}
				else {

					$slug	= $this->page->slug;
					$ogUrl	= Url::toRoute( [ "/$slug" ], true );
				}
			}

			$metaContent	= [];
			$content		= '';

			if( $this->facebook ) {

				$metaContent = $this->generateFacebookTags( $ogUrl, $banner, $metaContent );
			}

			if( $this->twitter ) {

				$metaContent = $this->generateTwitterCard( $banner, $metaContent );
			}

			foreach( $metaContent as $key => $value ) {

				$content = $content . $value;
			}

			$content = $content . "<link rel='canonical' href='$ogUrl'/>";
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

			$page		= $this->page;
			$content	= $this->content;
			$summary	= filter_var( $content->summary, FILTER_SANITIZE_STRING );

			if( strlen( $summary ) > 120 ) {

				$summary = substr( $summary, 0, 120 );
			}

			$metaContent[ 'otitle' ]	= "<meta property=\"og:title\" content=\"$page->name\" />";
			$metaContent[ 'osite' ]		= "<meta property=\"og:site_name\" content=\"$siteName\"/>";
			$metaContent[ 'ourl' ]		= "<meta property=\"og:url\" content=\"$ogUrl\" />";
			$metaContent[ 'odesc' ]		= "<meta property=\"og:description\" content=\"$summary\"/>";
			$metaContent[ 'olocale' ]	= "<meta property=\"og:locale\" content=\"$locale\" />";
			$metaContent[ 'otype' ]		= "<meta property=\"og:type\" content=\"website\" />";
			$metaContent[ 'fid' ]		= "<meta property=\"fb:app_id\" content=\"$appId\" />";

			if( isset( $banner ) ) {

				$imageUrl	= $banner->getFileUrl();
				$filePath	= $banner->getFilePath();

				$metaContent[ 'oimage' ] = "<meta property=\"og:image\" content=\"$imageUrl\">";

				if( isset( $filePath ) ) {

					list($width, $height, $type, $attr) = getimagesize( $banner->getFilePath() );

					$metaContent[ 'oimagew' ] = "<meta property=\"og:image:width\" content=\"$width\">";
					$metaContent[ 'oimageh' ] = "<meta property=\"og:image:height\" content=\"$height\">";
					$metaContent[ 'oimaget' ] = "<meta property=\"og:image:type\" content=\"$type\">";
				}
			}

			if( $this->page->isPost() ) {

				$author		= $properties->getAuthor();
				$publisher	= $properties->getPublisher();

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
			$page		= $this->page;
			$content	= $this->content;
			$summary	= filter_var( $content->summary, FILTER_SANITIZE_STRING );

			if( strlen( $summary ) > 120 ) {

				$summary = substr( $summary, 0, 120 );
			}

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

			// TODO: Add support for player and app cards generated by Twitter
			switch( $card ) {

				case 'summary': {

					if( empty( $metaContent[ 'otitle' ] ) ) {

						$metaContent[ 'otitle' ] = "<meta name=\"twitter:title\" content=\"$page->name\" />";
					}

					if( empty( $metaContent[ 'odesc' ] ) ) {

						$metaContent[ 'tdesc' ] = "<meta name=\"twitter:description\" content=\"$summary\" />";
					}

					if( isset( $banner ) && empty( $metaContent[ 'oimage' ] ) ) {

						$imageUrl = $banner->getMediumUrl();

						$metaContent[ 'timage' ] = "<meta name=\"twitter:image\" content=\"$imageUrl\" />";
					}

					$metaContent[ 'timagealt' ] = "<meta name=\"twitter:image:alt\" content=\"$banner->altText\" />";

					break;
				}
				case 'summary_large_image': {

					if( empty( $metaContent[ 'otitle' ] ) ) {

						$metaContent[ 'otitle' ] = "<meta name=\"twitter:title\" content=\"$page->name\" />";
					}

					if( empty( $metaContent[ 'odesc' ] ) ) {

						$metaContent[ 'tdesc' ] = "<meta name=\"twitter:description\" content=\"$summary\" />";
					}

					if( isset( $banner ) && empty( $metaContent[ 'oimage' ] ) ) {

						$imageUrl	= $banner->getFileUrl();

						$metaContent[ 'timage' ] = "<meta name=\"twitter:image\" content=\"$imageUrl\" />";
					}

					$metaContent[ 'timagealt' ] = "<meta name=\"twitter:image:alt\" content=\"$banner->altText\" />";

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
