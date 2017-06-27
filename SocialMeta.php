<?php
namespace cmsgears\social\meta;

// Yii Imports
use \Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreProperties;

use cmsgears\core\common\services\entities\SiteService;

class SocialMeta extends \yii\base\Component {

	public $page;
	public $content;

	public $homePageSlug	= 'home';
	public $postBasePath	= 'post';

	public $twitter			= false;

	public $facebook		= false;

	// TODO: Add config to use thumb or image.

	// TODO: Use DB Config to configure all the meta tags

	// TODO: Get real FB Author details instead of global author

	// TODO: Add code to configure card from page or post attributes

	// Helper to generate tags
	public static function getMetaTags( $params, $config = [] ) {

		if( isset( $params[ 'page' ] ) ) {

			$config[ 'page' ] 		= $params[ 'page' ];
			$config[ 'content' ] 	= $params[ 'content' ];

			$meta	= new SocialMeta( $config );

			return $meta->generateMetaTags();
		}
	}

	public function generateMetaTags() {

		$metaContent	= '';

		if( isset( $this->page ) ) {

			$ogUrl		= null;
			$banner		= $this->content->banner;

			if( $this->page->isPost() ) {

				$slug	= $this->page->slug;
				$ogUrl	= Url::toRoute( [ "/$this->postBasePath/$slug" ], true );
			}
			else {

				if( strcmp( $this->page->slug, $this->homePageSlug ) == 0 ) {

					$ogUrl	= Url::toRoute( [ '/' ], true );
				}
				else {

					$slug	= $this->page->slug;
					$ogUrl	= Url::toRoute( [ "/$slug" ], true );
				}
			}

			if( $this->twitter ) {

				$metaContent	.= $this->generateTwitterCard( $ogUrl, $banner );
			}

			if( $this->facebook ) {

				$metaContent	.= $this->generateFacebookTags( $ogUrl, $banner );
			}

			$metaContent	.= "<link rel='canonical' href='$ogUrl'/>";
		}

		return $metaContent;
	}

	/**
	 * Twitter Card - summary_large_image
	 */
	private function generateTwitterCard( $ogUrl, $banner ) {

		$settings	= TwitterSettings::getInstance();

		if( $settings->isActive() ) {

			$site		= $settings->getSite();
			$creator	= $settings->getCreator();
			$page		= $this->page;
			$content	= $this->content;
			$summary	 = filter_var($content->summary,FILTER_SANITIZE_STRING);
			
			if( strlen( $summary ) > 200 ) {

				$summary	= substr( $summary, 0, 120 );
			}

			// TODO: Add support for all type of cards generated by Twitter
			$metaContent	= "<meta name='twitter:card' content='summary_large_image' />
								<meta name='twitter:site' content='$site' />
								<meta name='twitter:creator' content='$creator' />
								<meta name='twitter:title' content='$page->name' />
								<meta name='twitter:description' content=\"$summary\" />";

			if( isset( $banner ) ) {

				$banner			= $banner->getFileUrl();

				$metaContent	.= "<meta name='twitter:image' content='$banner' />";
			}

			return $metaContent;
		}

		return '';
	}

	private function generateFacebookTags( $ogUrl, $banner ) {

		$settings	= FacebookSettings::getInstance();

		if( $settings->isActive() ) {

			$coreProperties	= CoreProperties::getInstance();
			$appId			= $settings->getAppId();
			$siteName		= $coreProperties->getSiteName();
			$locale			= $coreProperties->getLocale();
			$page			= $this->page;
			$content		= $this->content;
			$summary		= filter_var($content->summary, FILTER_SANITIZE_STRING);
			
			if( strlen( $summary ) > 200 ) {

				$summary	= substr( $summary, 0, 120 );
			}
			
			$metaContent	= "<meta property='og:title' content='$page->name' />
								<meta property='og:site_name' content='$siteName'/>
								<meta property='og:url' content='$ogUrl' />
								<meta property='og:description' content=\"$summary\"/>
								<meta property='og:locale' content='$locale' />
								<meta property='fb:app_id' content='$appId' />";

			if( isset( $banner ) ) {

				$banner			= $banner->getFileUrl();

				$metaContent	.= "<meta property='og:image' content='$banner'>";
			}

			if( $this->page->isPost() ) {

				$author			= $settings->getAuthor();
				$publisher		= $settings->getPublisher();

				$metaContent	.= "<meta property='og:type' content='article' />
									<meta property='article:author' content='$author' />
									<meta property='article:publisher' content='$publisher' />";
			}
			else {

				$metaContent	.= "<meta property='og:type' content='website' />";
			}

			return $metaContent;
		}

		return '';
	}
}

?>