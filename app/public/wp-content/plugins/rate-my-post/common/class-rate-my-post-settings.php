<?php

/**
 * Plugin's default settings
 *
 * @link       http://wordpress.org/plugins/rate-my-post/
 * @since      3.0.0
 *
 * @package    Rate_My_Post
 * @subpackage Rate_My_Post/common
 */

class Rate_My_Post_Settings {

  // Plugin name - string
	private $rate_my_post;

	// Plugin version - string
	private $version;

	// Init
	public function __construct( $rate_my_post, $version ) {
		$this->rate_my_post = $rate_my_post;
		$this->version = $version;
	}

	// default options for the plugin
	public static function default_options() {
    $options = array(
      'posts'               => 1,
      'pages'               => 1,
      'rate_email'          => 1,
      'feedback_email'      => 1,
      'feedback'            => 1,
      'social'              => 1,
      'fb'                  => '',
      'pinterest'           => '',
      'flickr'              => '',
      'youtube'             => '',
      'instagram'           => '',
      'twitter'             => '',
      'linkedin'            => '',
      'exclude'             => array(),
      'cookieDisable'       => 1,
      'resultPost'          => 1,
      'resultPages'         => 1,
      'icon_type'           => 1,
      'notShowRating'       => 1,
      'positiveNegative'    => 2,
      'hoverTexts'          => 1,
      'multiLingual'        => 1,
      'cptRating'           => array(),
      'cptResult'           => array(),
      'preventAccidental'   => 1,
      'wipeOnUninstall'     => 1,
      'ampCompatibility'    => 1,
      'archivePages'        => 1,
      'socialShare'         => 1,
      'widgetAlign'         => 1,
      'structuredDataType'  => 'none',
      'ajaxLoad'            => 1,
    );
    return $options;
  }

  // numeric options for the plugin
  public static $numeric_options = array(
    'posts',
    'pages',
    'rate_email',
    'feedback_email',
    'feedback',
    'social',
    'cookieDisable',
    'resultPost',
    'resultPages',
    'icon_type',
    'notShowRating',
    'positiveNegative',
    'hoverTexts',
    'multiLingual',
    'preventAccidental',
    'wipeOnUninstall',
    'ampCompatibility',
    'archivePages',
    'socialShare',
    'widgetAlign',
    'ajaxLoad',
  );

	// url options for the plugin
  public static $url_options = array(
    'fb',
    'pinterest',
    'flickr',
    'youtube',
    'instagram',
    'twitter',
    'linkedin',
  );

	// string options for the plugin
  public static $string_options = array(
    'structuredDataType',
  );

	// default customization for the plugin
  public static function default_customization() {
    $customization = array(
      'rateTitle'             => esc_html__( 'How useful was this post?', 'rate-my-post' ),
      'rateSubtitle'          => esc_html__( 'Click on a star to rate it!', 'rate-my-post' ),
      'rateResult'            => esc_html__( 'Average rating', 'rate-my-post' ),
      'rateResult2'           => esc_html__( 'Vote count:', 'rate-my-post' ),
      'cookieNotice'          => esc_html__( 'You already voted! This vote will not be counted!', 'rate-my-post' ),
      'noRating'              => esc_html__( 'No votes so far! Be the first to rate this post.', 'rate-my-post' ),
      'afterVote'             => esc_html__( 'Thank you for rating this post!', 'rate-my-post' ),
      'star1'                 => esc_html__( 'Not at all useful', 'rate-my-post' ),
      'star2'                 => esc_html__( 'Somewhat useful', 'rate-my-post' ),
      'star3'                 => esc_html__( 'Useful', 'rate-my-post' ),
      'star4'                 => esc_html__( 'Fairly useful', 'rate-my-post' ),
      'star5'                 => esc_html__( 'Very useful', 'rate-my-post' ),
      'socialTitle'           => esc_html__( 'As you found this post useful...', 'rate-my-post' ),
      'socialSubtitle'        => esc_html__( 'Follow us on social media!', 'rate-my-post' ),
      'feedbackTitle'         => esc_html__( 'We are sorry that this post was not useful for you!', 'rate-my-post' ),
      'feedbackSubtitle'      => esc_html__( 'Let us improve this post!', 'rate-my-post' ),
      'feedbackText'          => esc_html__( 'Tell us how we can improve this post?', 'rate-my-post' ),
      'feedbackNotice'        => esc_html__( 'Thanks for your feedback!', 'rate-my-post' ),
      'feedbackButton'        => esc_html__( 'Submit Feedback', 'rate-my-post' ),
      'titleFontSize'         => '',
      'subtitleFontSize'      => '',
      'textFontSize'          => '',
      'titleMarginBottom'     => '',
      'subtitleMarginBottom'  => '',
      'socialFontSize'        => '',
      'feedbackAlert'         => esc_html__( 'Please insert your feedback in the box above!', 'rate-my-post' ),
      'iconSize'              => '',
      'iconColorResults'      => '',
      'iconColorHover'        => '',
      'iconColorRated'        => '',
      'iconColorAvg'          => '',
      'borderWidth'           => '',
      'borderRadius'          => '',
      'borderColor'           => '',
      'backgroundColor'       => '',
      'iconMargin'            => '',
      'customResultsText'     => '',
      'submitButtonText'      => esc_html__( 'Submit Rating', 'rate-my-post' ),

    );
    return $customization;
  }

	// numeric customization for the plugin
  public static $customization_numeric = array(
    'titleFontSize',
    'subtitleFontSize',
    'textFontSize',
    'titleMarginBottom',
    'subtitleMarginBottom',
    'socialFontSize',
    'iconSize',
    'borderWidth',
    'borderRadius',
    'iconMargin',
  );

	// default security options for the plugin
  public static function security_options() {
    $security = array(
      'privileges'          => 1,
      'recaptcha'           => 1,
      'siteKey'             => '',
      'secretKey'           => '',
      'votingPriv'          => 1,
      'ipTracking'          => 1,
      'userTracking'        => 1,
      'ipDoubleVote'        => 1,
    );
    return $security;
  }

	// numeric security options for the plugin
	public static $security_numeric = array(
    'privileges',
    'recaptcha',
    'votingPriv',
    'ipTracking',
    'userTracking',
    'ipDoubleVote',
  );

} //end of class
