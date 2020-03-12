<?php

/**
 * INTERNAL CSS TEMPLATE
 *
 * @link       http://wordpress.org/plugins/rate-my-post/
 * @since      3.0.0
 *
 * @package    Rate_My_Post
 * @subpackage Rate_My_Post/public/partials
 */
?>
<?php if( $basic_options['widgetAlign'] === 2 ): ?>
.rmp-widgets-container.rmp-wp-plugin.rmp-main-container {
  text-align:left;
}
<?php endif; ?>

<?php if( $basic_options['widgetAlign'] === 3 ): ?>
.rmp-widgets-container.rmp-wp-plugin.rmp-main-container {
  text-align:right;
}
<?php endif; ?>

<?php if( $customization_options['iconColorResults'] ): // highlighted icons color in results widget ?>
.rmp-icon--full-highlight {
	color: <?php echo $customization_options['iconColorResults']; ?>;
}
.rmp-icon--half-highlight {
  background: -webkit-gradient(linear, left top, right top, color-stop(50%, <?php echo $customization_options['iconColorResults']; ?>), color-stop(50%, #ccc));
  background: linear-gradient(to right, <?php echo $customization_options['iconColorResults']; ?> 50%, #ccc 50%);
	-webkit-background-clip: text;
	-webkit-text-fill-color: transparent;
}
<?php endif; ?>

<?php if( $customization_options['iconColorAvg'] ): // avg rating in rating widget ?>
.rmp-rating-widget .rmp-icon--half-highlight {
    background: -webkit-gradient(linear, left top, right top, color-stop(50%, <?php echo $customization_options['iconColorAvg']; ?>), color-stop(50%, #ccc));
    background: linear-gradient(to right, <?php echo $customization_options['iconColorAvg']; ?> 50%, #ccc 50%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.rmp-rating-widget .rmp-icon--full-highlight {
  color: <?php echo $customization_options['iconColorAvg']; ?>;
}
<?php endif; ?>

<?php if( $customization_options['iconColorAvg'] && ! $customization_options['iconColorHover'] ):?>
@media (hover: hover) {
  .rmp-rating-widget .rmp-icon--hovered {
    color: #FFCC36;
    -webkit-background-clip: initial;
    -webkit-text-fill-color: initial;
    background: transparent;
    -webkit-transition: .1s color ease-in;
    transition: .1s color ease-in;
  }
}
<?php endif; ?>

<?php if( $customization_options['iconColorAvg'] && ! $customization_options['iconColorRated'] ):?>
.rmp-rating-widget .rmp-icon--processing-rating {
  color: #FF912C;
  -webkit-background-clip: initial;
  -webkit-text-fill-color: initial;
  background: transparent;
}
<?php endif; ?>

<?php if( $customization_options['iconColorHover'] ): // hover color in rating widget ?>
@media (hover: hover) {
	.rmp-rating-widget .rmp-icon--hovered {
		color: <?php echo $customization_options['iconColorHover']; ?>;
    -webkit-background-clip: initial;
    -webkit-text-fill-color: initial;
    background: transparent;
    -webkit-transition: .1s color ease-in;
    transition: .1s color ease-in;
	}
}
<?php endif; ?>

<?php if( $customization_options['iconColorHover'] && ! $customization_options['iconColorRated'] ): ?>
.rmp-rating-widget .rmp-icon--processing-rating {
  color: #FF912C;
  -webkit-background-clip: initial;
  -webkit-text-fill-color: initial;
  background: transparent;
}
<?php endif; ?>

<?php if( $customization_options['iconColorRated'] ): // processing rating color in rating widget ?>
.rmp-rating-widget .rmp-icon--processing-rating {
	color: <?php echo $customization_options['iconColorRated']; ?>;
  -webkit-background-clip: initial;
  -webkit-text-fill-color: initial;
  background: transparent;
}
<?php endif; ?>

<?php if( intval( $customization_options['borderWidth'] ) || $customization_options['borderColor'] ): // rating widget border width and color ?>
.rmp-widgets-container {
	border: <?php echo intval( $customization_options['borderWidth'] ? $customization_options['borderWidth'] : '1' ); ?>px solid <?php echo  $customization_options['borderColor'] ? $customization_options['borderColor'] : 'grey'; ?>;
}
<?php endif; ?>

<?php if( intval( $customization_options['borderRadius'] ) ): // rating widget border radius ?>
.rmp-widgets-container {
	border-radius: <?php echo intval( $customization_options['borderRadius'] ); ?>px;
}
<?php endif; ?>

<?php if( $customization_options['backgroundColor'] ):  // rating widgetbackground color ?>
.rmp-widgets-container {
	background-color: <?php echo $customization_options['backgroundColor']; ?>;
}
<?php endif; ?>

<?php if( intval( $customization_options['iconMargin'] ) ): // icons margin in rating widget ?>
.rmp-widgets-container.rmp-wp-plugin.rmp-main-container .rmp-rating-widget__icons-list__icon {
	margin-left: <?php echo intval( $customization_options['iconMargin'] ); ?>px;
	margin-right: <?php echo intval( $customization_options['iconMargin'] ); ?>px;
}
<?php endif; ?>

<?php if( intval( $customization_options['titleFontSize'] ) ): // title font size ?>
.rmp-widgets-container.rmp-wp-plugin.rmp-main-container .rmp-heading--title {
  font-size: <?php echo intval( $customization_options['titleFontSize'] ); ?>px;
}
<?php endif; ?>

<?php if( intval( $customization_options['subtitleFontSize'] ) ): // subtitle font size ?>
.rmp-widgets-container.rmp-wp-plugin.rmp-main-container .rmp-heading--subtitle {
  font-size: <?php echo intval( $customization_options['subtitleFontSize'] ); ?>px;
}
<?php endif; ?>

<?php if( intval( $customization_options['textFontSize'] ) ): // text font size ?>
 .rmp-widgets-container p {
  font-size: <?php echo intval( $customization_options['textFontSize'] ); ?>px;
}
<?php endif; ?>

<?php if( intval( $customization_options['titleMarginBottom'] ) ): // title margin bottom ?>
.rmp-widgets-container.rmp-wp-plugin.rmp-main-container .rmp-heading.rmp-heading--title {
  margin-bottom: <?php echo intval( $customization_options['titleMarginBottom'] ); ?>px;
}
<?php endif; ?>

<?php if( intval( $customization_options['subtitleMarginBottom'] ) ): // subtitle margin bottom ?>
.rmp-widgets-container.rmp-wp-plugin.rmp-main-container .rmp-heading.rmp-heading--subtitle {
  margin-bottom: <?php echo intval( $customization_options['subtitleMarginBottom'] ); ?>px;
}
<?php endif; ?>

<?php if( intval( $customization_options['socialFontSize'] ) ): // social icon size ?>
.rmp-social-widget .rmp-icon--social {
  font-size: <?php echo intval( $customization_options['socialFontSize'] ); ?>px;
  padding: <?php echo intval( $customization_options['socialFontSize'] * 0.67 ); ?>px;
  width: <?php echo intval( $customization_options['socialFontSize'] * 2.33 ); ?>px;
}
<?php endif; ?>

<?php if( intval( $customization_options['iconSize'] ) ): // rate icon size ?>
.rmp-rating-widget .rmp-icon--ratings {
  font-size: <?php echo intval( $customization_options['iconSize'] ); ?>px;
}
<?php endif; ?>
