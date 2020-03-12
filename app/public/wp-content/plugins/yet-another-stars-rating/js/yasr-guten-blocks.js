var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

//setting costants
var __ = wp.i18n.__; // Import __() from wp.i18n

var registerBlockType = wp.blocks.registerBlockType; // Import from wp.blocks

var _wp$components = wp.components,
    PanelBody = _wp$components.PanelBody,
    PanelRow = _wp$components.PanelRow;
var Fragment = wp.element.Fragment;
var _wp$editor = wp.editor,
    BlockControls = _wp$editor.BlockControls,
    InspectorControls = _wp$editor.InspectorControls;


var yasrOptionalText = __('All these settings are optional', 'yet-another-stars-rating');

var yasrLabelSelectSize = __('Choose Size', 'yet-another-stars-rating');

var yasrSelectSizeChoose = __('Choose stars size', 'yet-another-stars-rating');
var yasrSelectSizeSmall = __('Small', 'yet-another-stars-rating');
var yasrSelectSizeMedium = __('Medium', 'yet-another-stars-rating');
var yasrSelectSizeLarge = __('Large', 'yet-another-stars-rating');

var yasrLeaveThisBlankText = __('Leave this blank if you don\'t know what you\'re doing.', 'yet-another-stars-rating');

var yasrOverallDescription = __('Remember: only the post author can rate here.', 'yet-another-stars-rating');
var yasrVisitorVotesDescription = __('This is the star set where your users will be able to vote', 'yet-another-stars-rating');

/******* function to check ajax response *****/
/*function checkResponse(res) {
    if (res.status >= 400 && res.status < 600) {
        throw new Error(`Failed with status ${res.status}`);
    }

    return res;
}*/

function YasrProText() {

    var YasrProText1 = __('To be able to customize this ranking, you need', 'yet-another-stars-rating');
    var YasrProText2 = __('You can buy the plugin, including one year of support, updates and upgrades, on', 'yet-another-stars-rating');

    return React.createElement(
        'h3',
        null,
        YasrProText1,
        '\xA0',
        React.createElement(
            'a',
            { href: 'https://yetanotherstarsrating.com/#yasr-pro' },
            'Yasr Pro.'
        ),
        React.createElement('br', null),
        YasrProText2,
        '\xA0',
        React.createElement(
            'a',
            { href: 'https://yetanotherstarsrating.com/#yasr-pro' },
            'yetanotherstarsrating.com'
        )
    );
}

function YasrNoSettingsPanel(props) {

    return React.createElement(
        InspectorControls,
        null,
        React.createElement(
            PanelBody,
            { title: 'Settings' },
            React.createElement(YasrProText, null)
        )
    );
}

/*function YasrPrintSelectRows () {

    let optionValueRows = [];

    for (let i=2; i<=30; i++) {
        if (i === 9) {
            optionValueRows.push(<option value={i} selected disabled>{i}</option>);
        } else {
            optionValueRows.push(<option value={i} disabled>{i}</option>);
        }

    }

    return(
        <form>
            <select>
                {optionValueRows}
            </select>
            <span className="dashicons dashicons-lock"></span>
        </form>
    )

}


function YasrPrintSelectSize () {
    return (
        <form>
            <select>
                <option value="--">{yasrSelectSizeChoose}</option>
                <option value="small" disabled>{yasrSelectSizeSmall}</option>
                <option value="medium" disabled>{yasrSelectSizeMedium}</option>
                <option value="large" disabled>{yasrSelectSizeLarge}</option>
            </select>
            <span className="dashicons dashicons-lock"></span>
        </form>
    );
}

function YasrPrintTextPosition () {
    return (
        <form>
            <select>
                <option value="before" selected disabled>Before</option>
                <option value="after" disabled>After</option>
            </select>
            <span className="dashicons dashicons-lock"></span>
        </form>
    );
}
*/
/**
 * Register: a Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */

registerBlockType('yet-another-stars-rating/yasr-overall-rating', {
    title: __('Yasr: Overall Rating', 'yet-another-stars-rating'),
    description: __('Insert the author rating', ''),
    icon: 'star-half',
    category: 'yet-another-stars-rating',
    keywords: [__('rating', 'yet-another-stars-rating'), __('author', 'yet-another-stars-rating'), __('overall', 'yet-another-stars-rating')],
    attributes: {
        overallRatingMeta: {
            type: 'number',
            source: 'meta',
            meta: 'yasr_overall_rating'
        },
        size: {
            type: 'string',
            default: '--'
        },
        postId: {
            type: 'number',
            default: ''
        }
    },

    edit: function edit(props) {
        var _props$attributes = props.attributes,
            overallRatingMeta = _props$attributes.overallRatingMeta,
            size = _props$attributes.size,
            postId = _props$attributes.postId,
            setAttributes = props.setAttributes,
            isSelected = props.isSelected;


        var overallRating = overallRatingMeta;

        var sizeAttribute = null;
        var postIdAttribute = null;
        var isNum = false;

        if (size !== '--') {
            sizeAttribute = ' size="' + size + '"';
        }

        isNum = /^\d+$/.test(postId);

        if (postId && isNum === true) {
            postIdAttribute = ' postid="' + postId + '"';
        }

        var YasrDivRatingOverall = function (_React$Component) {
            _inherits(YasrDivRatingOverall, _React$Component);

            function YasrDivRatingOverall(props) {
                _classCallCheck(this, YasrDivRatingOverall);

                var _this = _possibleConstructorReturn(this, (YasrDivRatingOverall.__proto__ || Object.getPrototypeOf(YasrDivRatingOverall)).call(this, props));

                _this.yasrOverallRateThis = __("Rate this article / item", 'yet-another-stars-rating');
                return _this;
            }

            _createClass(YasrDivRatingOverall, [{
                key: 'render',
                value: function render() {
                    return React.createElement(
                        'div',
                        null,
                        this.yasrOverallRateThis,
                        React.createElement(
                            'div',
                            null,
                            React.createElement('div', { id: 'overall-rater', ref: function ref() {
                                    return raterJs({
                                        starSize: 32,
                                        step: 0.1,
                                        showToolTip: false,
                                        rating: overallRating,
                                        readOnly: false,
                                        element: document.querySelector("#overall-rater"),
                                        rateCallback: function rateCallback(rating, done) {

                                            rating = rating.toFixed(1);
                                            rating = parseFloat(rating);

                                            setAttributes({ overallRatingMeta: rating });

                                            this.setRating(rating);

                                            done();
                                        }
                                    });
                                }
                            })
                        )
                    );
                }
            }]);

            return YasrDivRatingOverall;
        }(React.Component);

        function YasrPrintSelectSize() {
            return React.createElement(
                'form',
                null,
                React.createElement(
                    'select',
                    { value: size, onChange: yasrSetStarsSize },
                    React.createElement(
                        'option',
                        { value: '--' },
                        yasrSelectSizeChoose
                    ),
                    React.createElement(
                        'option',
                        { value: 'small' },
                        yasrSelectSizeSmall
                    ),
                    React.createElement(
                        'option',
                        { value: 'medium' },
                        yasrSelectSizeMedium
                    ),
                    React.createElement(
                        'option',
                        { value: 'large' },
                        yasrSelectSizeLarge
                    )
                )
            );
        }

        function yasrSetStarsSize(event) {
            var selected = event.target.querySelector('option:checked');
            setAttributes({ size: selected.value });
            event.preventDefault();
        }

        function YasrPrintInputId() {
            return React.createElement(
                'div',
                null,
                React.createElement('input', { type: 'text', size: '4', onKeyPress: yasrSetPostId })
            );
        }

        function yasrSetPostId(event) {
            if (event.key === 'Enter') {
                var postIdValue = event.target.value;

                //postID is always a string, here I check if this string is made only by digits
                var isNum = /^\d+$/.test(postIdValue);

                if (isNum === true) {
                    setAttributes({ postId: postIdValue });
                }
                event.preventDefault();
            }
        }

        function YasrOverallPanel(props) {

            return React.createElement(
                InspectorControls,
                null,
                React.createElement(
                    'div',
                    { 'class': 'yasr-guten-block-panel yasr-guten-block-panel-center' },
                    React.createElement(YasrDivRatingOverall, null)
                ),
                React.createElement(
                    PanelBody,
                    { title: 'Settings' },
                    React.createElement(
                        'h3',
                        null,
                        yasrOptionalText
                    ),
                    React.createElement(
                        'div',
                        { className: 'yasr-guten-block-panel' },
                        React.createElement(
                            'label',
                            null,
                            yasrLabelSelectSize
                        ),
                        React.createElement(
                            'div',
                            null,
                            React.createElement(YasrPrintSelectSize, null)
                        )
                    ),
                    React.createElement(
                        'div',
                        { className: 'yasr-guten-block-panel' },
                        React.createElement(
                            'label',
                            null,
                            'Post ID'
                        ),
                        React.createElement(YasrPrintInputId, null),
                        React.createElement(
                            'div',
                            { className: 'yasr-guten-block-explain' },
                            yasrLeaveThisBlankText
                        )
                    ),
                    React.createElement(
                        'div',
                        { className: 'yasr-guten-block-panel' },
                        yasrOverallDescription
                    )
                )
            );
        }

        return React.createElement(
            Fragment,
            null,
            React.createElement(YasrOverallPanel, null),
            React.createElement(
                'div',
                { className: props.className },
                '[yasr_overall_rating',
                sizeAttribute,
                postIdAttribute,
                ']',
                isSelected && React.createElement(YasrPrintSelectSize, null)
            )
        );
    },

    /**
     * The save function defines the way in which the different attributes should be combined
     * into the final markup, which is then serialized by Gutenberg into post_content.
     *
     * The "save" property must be specified and must be a valid function.
     *
     * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
     */
    save: function save(props) {
        var _props$attributes2 = props.attributes,
            size = _props$attributes2.size,
            postId = _props$attributes2.postId;


        var yasrOverallAttributes = null;

        if (size) {
            yasrOverallAttributes += ' size="' + size + '"';
        }
        if (postId) {
            yasrOverallAttributes += ' postid="' + postId + '"';
        }

        return React.createElement(
            'div',
            null,
            '[yasr_overall_rating ',
            yasrOverallAttributes,
            ']'
        );
    }

});

registerBlockType('yet-another-stars-rating/yasr-visitor-votes', {

    title: __('Yasr: Visitor Votes', 'yet-another-stars-rating'),
    description: __('Insert the ability for your visitors to vote', 'yet-another-stars-rating'),
    icon: 'star-half',
    category: 'yet-another-stars-rating',
    keywords: [__('rating', 'yet-another-stars-rating'), __('visitor', 'yet-another-stars-rating'), __('votes', 'yet-another-stars-rating')],
    attributes: {
        //name of the attribute
        size: {
            type: 'string',
            default: '--'
        },
        postId: {
            type: 'number',
            default: ''
        }
    },

    edit: function edit(props) {
        var _props$attributes3 = props.attributes,
            size = _props$attributes3.size,
            postId = _props$attributes3.postId,
            setAttributes = props.setAttributes,
            isSelected = props.isSelected;


        var sizeAttribute = null;
        var postIdAttribute = null;
        var isNum = false;

        isNum = /^\d+$/.test(postId);

        if (size !== '--') {
            sizeAttribute = ' size="' + size + '"';
        }

        if (postId && isNum === true) {
            postIdAttribute = ' postid="' + postId + '"';
        }

        function YasrPrintSelectSize() {
            return React.createElement(
                'form',
                null,
                React.createElement(
                    'select',
                    { value: size, onChange: yasrSetStarsSize },
                    React.createElement(
                        'option',
                        { value: '--' },
                        yasrSelectSizeChoose
                    ),
                    React.createElement(
                        'option',
                        { value: 'small' },
                        yasrSelectSizeSmall
                    ),
                    React.createElement(
                        'option',
                        { value: 'medium' },
                        yasrSelectSizeMedium
                    ),
                    React.createElement(
                        'option',
                        { value: 'large' },
                        yasrSelectSizeLarge
                    )
                )
            );
        }

        function yasrSetStarsSize(event) {
            var selected = event.target.querySelector('option:checked');
            setAttributes({ size: selected.value });
            event.preventDefault();
        }

        function YasrPrintInputId() {
            return React.createElement(
                'div',
                null,
                React.createElement('input', { type: 'text', size: '4', onKeyPress: yasrSetPostId })
            );
        }

        function yasrSetPostId(event) {
            if (event.key === 'Enter') {
                var postIdValue = event.target.value;

                //postID is always a string, here I check if this string is made only by digits
                var isNum = /^\d+$/.test(postIdValue);

                if (isNum === true) {
                    setAttributes({ postId: postIdValue });
                }
                event.preventDefault();
            }
        }

        function YasrVVPanel(props) {

            return React.createElement(
                InspectorControls,
                null,
                React.createElement(
                    PanelBody,
                    { title: 'Settings' },
                    React.createElement(
                        'h3',
                        null,
                        yasrOptionalText
                    ),
                    React.createElement(
                        'div',
                        { className: 'yasr-guten-block-panel' },
                        React.createElement(
                            'label',
                            null,
                            yasrLabelSelectSize
                        ),
                        React.createElement(
                            'div',
                            null,
                            React.createElement(YasrPrintSelectSize, null)
                        )
                    ),
                    React.createElement(
                        'div',
                        { className: 'yasr-guten-block-panel' },
                        React.createElement(
                            'label',
                            null,
                            'Post ID'
                        ),
                        React.createElement(YasrPrintInputId, null),
                        React.createElement(
                            'div',
                            { className: 'yasr-guten-block-explain' },
                            yasrLeaveThisBlankText
                        )
                    ),
                    React.createElement(
                        'div',
                        { className: 'yasr-guten-block-panel' },
                        yasrVisitorVotesDescription
                    )
                )
            );
        }

        return React.createElement(
            Fragment,
            null,
            React.createElement(YasrVVPanel, null),
            React.createElement(
                'div',
                { className: props.className },
                '[yasr_visitor_votes',
                sizeAttribute,
                postIdAttribute,
                ']',
                isSelected && React.createElement(YasrPrintSelectSize, null)
            )
        );
    },

    /**
     * The save function defines the way in which the different attributes should be combined
     * into the final markup, which is then serialized by Gutenberg into post_content.
     *
     * The "save" property must be specified and must be a valid function.
     *
     * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
     */
    save: function save(props) {
        var _props$attributes4 = props.attributes,
            size = _props$attributes4.size,
            postId = _props$attributes4.postId;


        var yasrVVAttributes = null;

        if (size) {
            yasrVVAttributes += ' size="' + size + '"';
        }
        if (postId) {
            yasrVVAttributes += ' postid="' + postId + '"';
        }

        return React.createElement(
            'div',
            null,
            '[yasr_visitor_votes ',
            yasrVVAttributes,
            ']'
        );
    }

});

registerBlockType('yet-another-stars-rating/overall-rating-ranking', {

    title: __('Yasr: Top 10 Reviews', 'yet-another-stars-rating'),
    description: __('This ranking shows the highest rated posts rated through the overall_rating shortcode.', 'yet-another-stars-rating'),
    icon: 'star-half',
    category: 'yet-another-stars-rating',
    keywords: [__('ranking', 'yet-another-stars-rating'), __('highest', 'yet-another-stars-rating'), __('chart', 'yet-another-stars-rating')],

    edit: function edit(props) {

        return React.createElement(
            Fragment,
            null,
            React.createElement(YasrNoSettingsPanel, null),
            React.createElement(
                'div',
                { className: props.className },
                '[yasr_top_ten_highest_rated]'
            )
        );
    },

    /**
     * The save function defines the way in which the different attributes should be combined
     * into the final markup, which is then serialized by Gutenberg into post_content.
     *
     * The "save" property must be specified and must be a valid function.
     *
     * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
     */
    save: function save(props) {
        return React.createElement(
            'div',
            null,
            '[yasr_top_ten_highest_rated]'
        );
    }

});

registerBlockType('yet-another-stars-rating/visitor-votes-ranking', {

    title: __('Yasr: Top 10 By Visitors Votes', 'yet-another-stars-rating'),
    description: __('This ranking shows both the highest and most rated posts rated through the yasr_visitor_votes shortcode. For an item to appear in this chart, it has to be rated at least twice. ', 'yet-another-stars-rating'),
    icon: 'star-half',
    category: 'yet-another-stars-rating',
    keywords: [__('ranking', 'yet-another-stars-rating'), __('highest', 'yet-another-stars-rating'), __('most', 'yet-another-stars-rating'), __('chart', 'yet-another-stars-rating')],

    edit: function edit(props) {

        return React.createElement(
            Fragment,
            null,
            React.createElement(YasrNoSettingsPanel, null),
            React.createElement(
                'div',
                { className: props.className },
                '[yasr_most_or_highest_rated_posts]'
            )
        );
    },

    /**
     * The save function defines the way in which the different attributes should be combined
     * into the final markup, which is then serialized by Gutenberg into post_content.
     *
     * The "save" property must be specified and must be a valid function.
     *
     * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
     */
    save: function save(props) {
        return React.createElement(
            'div',
            null,
            '[yasr_most_or_highest_rated_posts]'
        );
    }

});

registerBlockType('yet-another-stars-rating/most-active-reviewers', {

    title: __('Yasr: Most Active Authors', 'yet-another-stars-rating'),
    description: __('This ranking shows the 5 most active reviewers on your site.', 'yet-another-stars-rating'),
    icon: 'star-half',
    category: 'yet-another-stars-rating',
    keywords: [__('ranking', 'yet-another-stars-rating'), __('highest', 'yet-another-stars-rating'), __('most', 'yet-another-stars-rating'), __('chart', 'yet-another-stars-rating'), __('authors', 'yet-another-stars-rating')],

    edit: function edit(props) {

        return React.createElement(
            Fragment,
            null,
            React.createElement(YasrNoSettingsPanel, null),
            React.createElement(
                'div',
                { className: props.className },
                '[yasr_top_5_reviewers]'
            )
        );
    },

    /**
     * The save function defines the way in which the different attributes should be combined
     * into the final markup, which is then serialized by Gutenberg into post_content.
     *
     * The "save" property must be specified and must be a valid function.
     *
     * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
     */
    save: function save(props) {
        return React.createElement(
            'div',
            null,
            '[yasr_top_5_reviewers]'
        );
    }

});

registerBlockType('yet-another-stars-rating/most-active-users', {

    title: __('Yasr: Most Active Visitors', 'yet-another-stars-rating'),
    description: __('This ranking shows the 10 most active users, displaying the login name if logged in or “Anonymous” if not.', 'yet-another-stars-rating'),
    icon: 'star-half',
    category: 'yet-another-stars-rating',
    keywords: [__('ranking', 'yet-another-stars-rating'), __('highest', 'yet-another-stars-rating'), __('most', 'yet-another-stars-rating'), __('chart', 'yet-another-stars-rating'), __('visitors', 'yet-another-stars-rating')],

    edit: function edit(props) {

        return React.createElement(
            Fragment,
            null,
            React.createElement(YasrNoSettingsPanel, null),
            React.createElement(
                'div',
                { className: props.className },
                '[yasr_top_ten_active_users]'
            )
        );
    },

    /**
     * The save function defines the way in which the different attributes should be combined
     * into the final markup, which is then serialized by Gutenberg into post_content.
     *
     * The "save" property must be specified and must be a valid function.
     *
     * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
     */
    save: function save(props) {
        return React.createElement(
            'div',
            null,
            '[yasr_top_ten_active_users]'
        );
    }

});