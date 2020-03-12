//setting costants
const { __ } = wp.i18n; // Import __() from wp.i18n
const {registerBlockType} = wp.blocks; // Import from wp.blocks
const {PanelBody, PanelRow} = wp.components;
const {Fragment} = wp.element;
const {BlockControls,InspectorControls} = wp.editor;


const yasrOptionalText = __('All these settings are optional', 'yet-another-stars-rating');

const yasrLabelSelectSize = __('Choose Size', 'yet-another-stars-rating');

const yasrSelectSizeChoose = __('Choose stars size', 'yet-another-stars-rating');
const yasrSelectSizeSmall = __('Small', 'yet-another-stars-rating');
const yasrSelectSizeMedium = __('Medium', 'yet-another-stars-rating');
const yasrSelectSizeLarge = __('Large', 'yet-another-stars-rating');

const yasrLeaveThisBlankText = __('Leave this blank if you don\'t know what you\'re doing.', 'yet-another-stars-rating');

const yasrOverallDescription = __('Remember: only the post author can rate here.', 'yet-another-stars-rating');
const yasrVisitorVotesDescription = __('This is the star set where your users will be able to vote', 'yet-another-stars-rating');

/******* function to check ajax response *****/
/*function checkResponse(res) {
    if (res.status >= 400 && res.status < 600) {
        throw new Error(`Failed with status ${res.status}`);
    }

    return res;
}*/

function YasrProText () {

    const YasrProText1 = __('To be able to customize this ranking, you need', 'yet-another-stars-rating');
    const YasrProText2 =  __('You can buy the plugin, including one year of support, updates and upgrades, on',
        'yet-another-stars-rating');

    return (
        <h3>
            {YasrProText1}
            &nbsp;<a href="https://yetanotherstarsrating.com/#yasr-pro">Yasr Pro.</a><br />
            {YasrProText2}
            &nbsp;<a href="https://yetanotherstarsrating.com/#yasr-pro">yetanotherstarsrating.com</a>
        </h3>
    )

}

function YasrNoSettingsPanel (props) {

    return (
        <InspectorControls>
            <PanelBody title='Settings'>
                <YasrProText/>
            </PanelBody>
        </InspectorControls>
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

registerBlockType(
    'yet-another-stars-rating/yasr-overall-rating', {
        title: __( 'Yasr: Overall Rating', 'yet-another-stars-rating' ),
        description: __('Insert the author rating', ''),
        icon: 'star-half',
        category: 'yet-another-stars-rating',
        keywords: [
                    __('rating', 'yet-another-stars-rating'),
                    __('author', 'yet-another-stars-rating'),
                    __('overall', 'yet-another-stars-rating')
                    ],
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
            },
        },

        edit:

            function(props) {

                const { attributes: {overallRatingMeta, size, postId}, setAttributes, isSelected } = props;

                let overallRating = overallRatingMeta;

                let sizeAttribute = null;
                let postIdAttribute = null;
                let isNum = false;

                if (size !== '--') {
                    sizeAttribute = ' size="' + size + '"';
                }

                isNum = /^\d+$/.test(postId);

                if (postId && isNum === true) {
                    postIdAttribute = ' postid="' +postId + '"';
                }

                class YasrDivRatingOverall extends React.Component  {

                    constructor(props) {
                        super(props);
                        this.yasrOverallRateThis = __("Rate this article / item", 'yet-another-stars-rating');
                    }

                    render () {
                        return (
                            <div>
                                {this.yasrOverallRateThis}
                                <div>
                                    <div id="overall-rater" ref={()=>
                                        raterJs({
                                            starSize: 32,
                                            step: 0.1,
                                            showToolTip: false,
                                            rating: overallRating,
                                            readOnly: false,
                                            element: document.querySelector("#overall-rater"),
                                            rateCallback: function rateCallback(rating, done) {

                                                rating = rating.toFixed(1);
                                                rating = parseFloat(rating);

                                                setAttributes( { overallRatingMeta: rating } );

                                                this.setRating(rating);

                                                done();

                                            }
                                        })
                                    }
                                    />
                                </div>
                            </div>

                        );
                    }

                }

                function YasrPrintSelectSize () {
                    return (
                        <form>
                            <select value={size} onChange={ yasrSetStarsSize }>
                                <option value="--">{yasrSelectSizeChoose}</option>
                                <option value="small">{yasrSelectSizeSmall}</option>
                                <option value="medium">{yasrSelectSizeMedium}</option>
                                <option value="large">{yasrSelectSizeLarge}</option>
                            </select>
                        </form>
                    );
                }

                function yasrSetStarsSize(event) {
                    const selected = event.target.querySelector( 'option:checked' );
                    setAttributes( { size: selected.value } );
                    event.preventDefault();
                }

                function YasrPrintInputId() {
                    return (
                        <div>
                            <input type="text" size="4" onKeyPress={yasrSetPostId} />
                        </div>
                    );
                }

                function yasrSetPostId (event) {
                    if (event.key === 'Enter') {
                        const postIdValue = event.target.value;

                        //postID is always a string, here I check if this string is made only by digits
                        var isNum = /^\d+$/.test(postIdValue);

                        if (isNum === true) {
                            setAttributes({postId: postIdValue})
                        }
                        event.preventDefault();
                    }
                }

                function YasrOverallPanel (props) {

                    return (
                        <InspectorControls>
                            <div class="yasr-guten-block-panel yasr-guten-block-panel-center">
                                <YasrDivRatingOverall />
                            </div>

                            <PanelBody title='Settings'>
                                <h3>{yasrOptionalText}</h3>

                                <div className="yasr-guten-block-panel">
                                    <label>{yasrLabelSelectSize}</label>
                                    <div>
                                        <YasrPrintSelectSize />
                                    </div>
                                </div>

                                <div className="yasr-guten-block-panel">
                                    <label>Post ID</label>
                                    <YasrPrintInputId/>
                                    <div className="yasr-guten-block-explain">
                                        {yasrLeaveThisBlankText}
                                    </div>
                                </div>

                                <div className="yasr-guten-block-panel">
                                    {yasrOverallDescription}
                                </div>
                            </PanelBody>
                        </InspectorControls>
                    );

                }

                return (
                    <Fragment>
                        <YasrOverallPanel />
                        <div className={ props.className }>
                            [yasr_overall_rating{sizeAttribute}{postIdAttribute}]
                            {isSelected && <YasrPrintSelectSize />}
                        </div>
                    </Fragment>
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
        save:
            function( props ) {
                const {attributes: {size, postId}} = props;

                let yasrOverallAttributes = null;

                if (size) {
                    yasrOverallAttributes += ' size="' +size+ '"';
                }
                if (postId) {
                    yasrOverallAttributes += ' postid="'+postId+'"';
                }

                return (
                    <div>[yasr_overall_rating {yasrOverallAttributes}]</div>
                );
            },

    });

registerBlockType(
    'yet-another-stars-rating/yasr-visitor-votes', {

        title: __( 'Yasr: Visitor Votes', 'yet-another-stars-rating' ),
        description: __('Insert the ability for your visitors to vote', 'yet-another-stars-rating'),
        icon: 'star-half',
        category: 'yet-another-stars-rating',
        keywords: [
            __('rating', 'yet-another-stars-rating'),
            __('visitor', 'yet-another-stars-rating'),
            __('votes', 'yet-another-stars-rating')
        ],
        attributes: {
            //name of the attribute
            size: {
                type: 'string',
                default: '--'
            },
            postId: {
                type: 'number',
                default: ''
            },
        },

        edit:

            function( props ) {

                const { attributes: { size, postId }, setAttributes, isSelected } = props;

                let sizeAttribute = null;
                let postIdAttribute = null;
                let isNum = false;

                isNum = /^\d+$/.test(postId);

                if (size !== '--') {
                    sizeAttribute = ' size="' + size + '"';
                }

                if (postId && isNum === true) {
                    postIdAttribute = ' postid="' +postId + '"';
                }


                function YasrPrintSelectSize () {
                    return (
                        <form>
                            <select value={size} onChange={ yasrSetStarsSize }>
                                <option value="--">{yasrSelectSizeChoose}</option>
                                <option value="small">{yasrSelectSizeSmall}</option>
                                <option value="medium">{yasrSelectSizeMedium}</option>
                                <option value="large">{yasrSelectSizeLarge}</option>
                            </select>
                        </form>
                    );
                }

                function yasrSetStarsSize(event) {
                    const selected = event.target.querySelector( 'option:checked' );
                    setAttributes( { size: selected.value } );
                    event.preventDefault();
                }

                function YasrPrintInputId() {
                    return (
                        <div>
                            <input type="text" size="4" onKeyPress={yasrSetPostId} />
                        </div>
                    );
                }

                function yasrSetPostId (event) {
                    if (event.key === 'Enter') {
                        const postIdValue = event.target.value;

                        //postID is always a string, here I check if this string is made only by digits
                        var isNum = /^\d+$/.test(postIdValue);

                        if (isNum === true) {
                            setAttributes({postId: postIdValue})
                        }
                        event.preventDefault();
                    }
                }

                function YasrVVPanel (props) {

                    return (
                        <InspectorControls>
                            <PanelBody title='Settings'>
                                <h3>{yasrOptionalText}</h3>

                                <div className="yasr-guten-block-panel">
                                    <label>{yasrLabelSelectSize}</label>
                                    <div>
                                        <YasrPrintSelectSize />
                                    </div>
                                </div>

                                <div className="yasr-guten-block-panel">
                                    <label>Post ID</label>
                                    <YasrPrintInputId/>
                                    <div className="yasr-guten-block-explain">
                                        {yasrLeaveThisBlankText}
                                    </div>
                                </div>

                                <div className="yasr-guten-block-panel">
                                    {yasrVisitorVotesDescription}
                                </div>
                            </PanelBody>
                        </InspectorControls>
                    );

                }

                return (
                    <Fragment>
                        <YasrVVPanel />
                        <div className={props.className}>
                            [yasr_visitor_votes{sizeAttribute}{postIdAttribute}]
                            {isSelected && <YasrPrintSelectSize />}
                        </div>
                    </Fragment>
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
        save:
            function( props ) {
                const { attributes: {size, postId} } = props;

                let yasrVVAttributes = null;

                if (size) {
                    yasrVVAttributes += ' size="' +size+ '"';
                }
                if (postId) {
                    yasrVVAttributes += ' postid="'+postId+'"';
                }

                return (
                    <div>[yasr_visitor_votes {yasrVVAttributes}]</div>
                );
            },

});

registerBlockType(
    'yet-another-stars-rating/overall-rating-ranking', {

        title: __('Yasr: Top 10 Reviews', 'yet-another-stars-rating'),
        description: __(
            'This ranking shows the highest rated posts rated through the overall_rating shortcode.',
            'yet-another-stars-rating'
        ),
        icon: 'star-half',
        category: 'yet-another-stars-rating',
        keywords: [
            __('ranking', 'yet-another-stars-rating'),
            __('highest', 'yet-another-stars-rating'),
            __('chart', 'yet-another-stars-rating')
        ],

        edit:
            function(props) {
                return (
                    <Fragment>
                        <YasrNoSettingsPanel />
                        <div className={props.className}>
                            [yasr_top_ten_highest_rated]
                        </div>
                    </Fragment>
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
        save:
            function(props) {
                return (
                    <div>[yasr_top_ten_highest_rated]</div>
                );
            },

    });

registerBlockType(
    'yet-another-stars-rating/visitor-votes-ranking', {

        title: __('Yasr: Top 10 By Visitors Votes', 'yet-another-stars-rating'),
        description: __(
            'This ranking shows both the highest and most rated posts rated through the yasr_visitor_votes shortcode. For an item to appear in this chart, it has to be rated at least twice. ',
            'yet-another-stars-rating'
        ),
        icon: 'star-half',
        category: 'yet-another-stars-rating',
        keywords: [
            __('ranking', 'yet-another-stars-rating'),
            __('highest', 'yet-another-stars-rating'),
            __('most', 'yet-another-stars-rating'),
            __('chart', 'yet-another-stars-rating')
        ],

        edit:

            function(props) {

                return (
                    <Fragment>
                        <YasrNoSettingsPanel />
                        <div className={props.className}>
                            [yasr_most_or_highest_rated_posts]
                        </div>
                    </Fragment>
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
        save:
            function(props) {
                return (
                    <div>[yasr_most_or_highest_rated_posts]</div>
                );
            },

    });

registerBlockType(
    'yet-another-stars-rating/most-active-reviewers', {

        title: __('Yasr: Most Active Authors', 'yet-another-stars-rating'),
        description: __('This ranking shows the 5 most active reviewers on your site.',
        'yet-another-stars-rating'
        ),
        icon: 'star-half',
        category: 'yet-another-stars-rating',
        keywords: [
            __('ranking', 'yet-another-stars-rating'),
            __('highest', 'yet-another-stars-rating'),
            __('most', 'yet-another-stars-rating'),
            __('chart', 'yet-another-stars-rating'),
            __('authors', 'yet-another-stars-rating')
        ],

        edit:

            function(props) {

                return (
                    <Fragment>
                        <YasrNoSettingsPanel />
                        <div className={props.className}>
                            [yasr_top_5_reviewers]
                        </div>
                    </Fragment>
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
        save:
            function(props) {
                return (
                    <div>[yasr_top_5_reviewers]</div>
                );
            },

    });

registerBlockType(
    'yet-another-stars-rating/most-active-users', {

        title: __('Yasr: Most Active Visitors', 'yet-another-stars-rating'),
        description: __('This ranking shows the 10 most active users, displaying the login name if logged in or “Anonymous” if not.',
            'yet-another-stars-rating'
        ),
        icon: 'star-half',
        category: 'yet-another-stars-rating',
        keywords: [
            __('ranking', 'yet-another-stars-rating'),
            __('highest', 'yet-another-stars-rating'),
            __('most', 'yet-another-stars-rating'),
            __('chart', 'yet-another-stars-rating'),
            __('visitors', 'yet-another-stars-rating')
        ],

        edit:

            function(props) {

                return (
                    <Fragment>
                        <YasrNoSettingsPanel />
                        <div className={props.className}>
                            [yasr_top_ten_active_users]
                        </div>
                    </Fragment>
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
        save:
            function(props) {
                return (
                    <div>[yasr_top_ten_active_users]</div>
                );
            },

    });