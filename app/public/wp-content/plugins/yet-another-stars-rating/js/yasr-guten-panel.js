var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var registerPlugin = wp.plugins.registerPlugin;
var _wp$editPost = wp.editPost,
    PluginSidebar = _wp$editPost.PluginSidebar,
    PluginSidebarMoreMenuItem = _wp$editPost.PluginSidebarMoreMenuItem;
var _wp$components = wp.components,
    TextControl = _wp$components.TextControl,
    PanelBody = _wp$components.PanelBody,
    PanelRow = _wp$components.PanelRow;
var _wp$editor = wp.editor,
    BlockControls = _wp$editor.BlockControls,
    InspectorControls = _wp$editor.InspectorControls;


var yasrDisableAutoInsert = __('Disable auto insert for this post or page?', 'yet-another-stars-rating');
var yasrRichSnippetOptions = __('Rich snippet options', 'yet-another-stars-rating');
var yasrPostExcluded = __('Is this a review?', 'yet-another-stars-rating');
var yasrSelectSnippetTitle = __('This review is about a... ', 'yet-another-stars-rating');
var yasrSetItemTypeProduct = __('Product', 'yet-another-stars-rating');
var yasrSetItemTypeBook = __('Book', 'yet-another-stars-rating');
var yasrSetItemTypeMovie = __('Movie', 'yet-another-stars-rating');
var yasrSetItemTypeGame = __('Game', 'yet-another-stars-rating');
var yasrSetItemTypePlace = __('LocalBusiness', 'yet-another-stars-rating');
var yasrSetItemTypeRecipe = __('Recipe', 'yet-another-stars-rating');
var yasrSetItemBlogPosting = __('BlogPosting', 'yet-another-stars-rating');

var YasrDivRatingOverall = function (_React$Component) {
    _inherits(YasrDivRatingOverall, _React$Component);

    _createClass(YasrDivRatingOverall, [{
        key: 'shouldComponentUpdate',
        value: function shouldComponentUpdate(nextProps, nextState) {
            return false;
        }
    }]);

    function YasrDivRatingOverall(props) {
        _classCallCheck(this, YasrDivRatingOverall);

        var _this = _possibleConstructorReturn(this, (YasrDivRatingOverall.__proto__ || Object.getPrototypeOf(YasrDivRatingOverall)).call(this, props));

        _this.yasrOverallRateThis = __("Rate this article / item", 'yet-another-stars-rating');
        _this.yasrOverallMoreInfo = __("This is the same value that you find the \"Yasr: Overall Rating\" block.", 'yet-another-stars-rating');
        return _this;
    }

    _createClass(YasrDivRatingOverall, [{
        key: 'printDivOverallRater',
        value: function printDivOverallRater() {
            return React.createElement(
                'div',
                null,
                React.createElement('div', { id: 'overall-rater-panel', ref: function ref() {
                        return raterJs({
                            starSize: 32,
                            step: 0.1,
                            showToolTip: false,
                            rating: wp.data.select('core/editor').getCurrentPost().meta.yasr_overall_rating,
                            readOnly: false,
                            element: document.querySelector("#overall-rater-panel"),
                            rateCallback: function rateCallback(rating, done) {

                                rating = rating.toFixed(1);
                                rating = parseFloat(rating);

                                wp.data.dispatch('core/editor').editPost({ meta: { yasr_overall_rating: rating } });

                                this.setRating(rating);

                                done();
                            }
                        });
                    }
                }),
                React.createElement('br', null),
                this.yasrOverallMoreInfo,
                React.createElement('hr', null)
            );
        }
    }, {
        key: 'render',
        value: function render() {
            return React.createElement(
                'div',
                null,
                this.yasrOverallRateThis,
                React.createElement(
                    'div',
                    null,
                    this.printDivOverallRater()
                )
            );
        }
    }]);

    return YasrDivRatingOverall;
}(React.Component);

var YasrSideBarAutoInsert = function (_React$Component2) {
    _inherits(YasrSideBarAutoInsert, _React$Component2);

    function YasrSideBarAutoInsert(props) {
        _classCallCheck(this, YasrSideBarAutoInsert);

        var _this2 = _possibleConstructorReturn(this, (YasrSideBarAutoInsert.__proto__ || Object.getPrototypeOf(YasrSideBarAutoInsert)).call(this, props));

        var isThisPostExluded = wp.data.select('core/editor').getCurrentPost().meta.yasr_auto_insert_disabled;
        var isThisPostExludedCheckbox = false;

        if (isThisPostExluded === 'yes') {
            isThisPostExludedCheckbox = true;
        }

        _this2.state = { postExcluded: isThisPostExludedCheckbox };

        _this2.yasrUpdatePostMetaAutoInsert = _this2.yasrUpdatePostMetaAutoInsert.bind(_this2);
        return _this2;
    }

    _createClass(YasrSideBarAutoInsert, [{
        key: 'yasrUpdatePostMetaAutoInsert',
        value: function yasrUpdatePostMetaAutoInsert(event) {

            var target = event.target;
            var postExcluded = target.type === 'checkbox' ? target.checked : target.value;

            this.setState({ postExcluded: postExcluded });

            if (postExcluded === true) {
                wp.data.dispatch('core/editor').editPost({ meta: { yasr_auto_insert_disabled: 'yes' } });
            } else {
                wp.data.dispatch('core/editor').editPost({ meta: { yasr_auto_insert_disabled: 'no' } });
            }
        }
    }, {
        key: 'render',
        value: function render() {
            return React.createElement(
                'div',
                { className: 'yasr-guten-block-panel-center' },
                React.createElement(
                    'label',
                    null,
                    React.createElement(
                        'span',
                        null,
                        yasrDisableAutoInsert
                    )
                ),
                React.createElement(
                    'div',
                    { className: 'yasr-onoffswitch-big', id: 'yasr-switcher-disable-auto-insert' },
                    React.createElement('input', { type: 'checkbox',
                        name: 'yasr_auto_insert_disabled',
                        className: 'yasr-onoffswitch-checkbox',
                        value: 'yes',
                        id: 'yasr-auto-insert-disabled-switch',
                        defaultChecked: this.state.postExcluded,
                        onChange: this.yasrUpdatePostMetaAutoInsert
                    }),
                    React.createElement(
                        'label',
                        { className: 'yasr-onoffswitch-label', htmlFor: 'yasr-auto-insert-disabled-switch' },
                        React.createElement('span', { className: 'yasr-onoffswitch-inner' }),
                        React.createElement('span', { className: 'yasr-onoffswitch-switch' })
                    )
                ),
                React.createElement('hr', null)
            );
        }
    }]);

    return YasrSideBarAutoInsert;
}(React.Component);

var YasrDivIsReview = function (_React$Component3) {
    _inherits(YasrDivIsReview, _React$Component3);

    function YasrDivIsReview(props) {
        _classCallCheck(this, YasrDivIsReview);

        var _this3 = _possibleConstructorReturn(this, (YasrDivIsReview.__proto__ || Object.getPrototypeOf(YasrDivIsReview)).call(this, props));

        var isThisPostReview = wp.data.select('core/editor').getCurrentPost().meta.yasr_post_is_review;
        var isThisPostReviewCheckbox = false;

        if (isThisPostReview === 'yes') {
            isThisPostReviewCheckbox = true;
        }

        _this3.state = { postIsReview: isThisPostReviewCheckbox };

        _this3.yasrUpdatePostMetaIsReview = _this3.yasrUpdatePostMetaIsReview.bind(_this3);
        return _this3;
    }

    _createClass(YasrDivIsReview, [{
        key: 'yasrUpdatePostMetaIsReview',
        value: function yasrUpdatePostMetaIsReview(event) {

            var target = event.target;
            var postIsReview = target.type === 'checkbox' ? target.checked : target.value;

            this.setState({ postIsReview: postIsReview });

            if (postIsReview === true) {
                wp.data.dispatch('core/editor').editPost({ meta: { yasr_post_is_review: 'yes' } });
            } else {
                wp.data.dispatch('core/editor').editPost({ meta: { yasr_post_is_review: 'no' } });
            }
        }
    }, {
        key: 'render',
        value: function render() {
            return React.createElement(
                'div',
                { className: 'yasr-guten-block-panel-center' },
                React.createElement(
                    'div',
                    { className: 'rich-snippet-title' },
                    yasrRichSnippetOptions
                ),
                React.createElement(
                    'label',
                    null,
                    React.createElement(
                        'span',
                        null,
                        yasrPostExcluded
                    )
                ),
                React.createElement(
                    'div',
                    { className: 'yasr-onoffswitch-big', id: 'yasr-switcher-post-is-review' },
                    React.createElement('input', { type: 'checkbox',
                        name: 'yasr_post_is_review',
                        className: 'yasr-onoffswitch-checkbox',
                        value: 'yes',
                        id: 'yasr-post-is-review-switch',
                        defaultChecked: this.state.postIsReview,
                        onChange: this.yasrUpdatePostMetaIsReview
                    }),
                    React.createElement(
                        'label',
                        { className: 'yasr-onoffswitch-label', htmlFor: 'yasr-post-is-review-switch' },
                        React.createElement('span', { className: 'yasr-onoffswitch-inner' }),
                        React.createElement('span', { className: 'yasr-onoffswitch-switch' })
                    )
                ),
                React.createElement('p', null)
            );
        }
    }]);

    return YasrDivIsReview;
}(React.Component);

var YasrSideBarSelectSnippets = function (_React$Component4) {
    _inherits(YasrSideBarSelectSnippets, _React$Component4);

    function YasrSideBarSelectSnippets(props) {
        _classCallCheck(this, YasrSideBarSelectSnippets);

        //at the begin, get the default type
        var _this4 = _possibleConstructorReturn(this, (YasrSideBarSelectSnippets.__proto__ || Object.getPrototypeOf(YasrSideBarSelectSnippets)).call(this, props));

        var snippetType = yasrConstantGutenberg.defaultItemType;

        //postmeta itemtype
        var postMetaItemType = wp.data.select('core/editor').getCurrentPost().meta.yasr_review_type;

        //if post meta value is one of this overwrite snippetType
        if (postMetaItemType === 'Product' || postMetaItemType === 'Book' || postMetaItemType === 'Movie' || postMetaItemType === 'Game' || postMetaItemType === 'Place' || postMetaItemType === 'Recipe' || postMetaItemType === 'Other') {
            snippetType = postMetaItemType;
        }

        _this4.state = { snippet: snippetType };

        _this4.yasrSetItemType = _this4.yasrSetItemType.bind(_this4);
        return _this4;
    }

    _createClass(YasrSideBarSelectSnippets, [{
        key: 'yasrSetItemType',
        value: function yasrSetItemType(event) {
            this.setState({ snippet: event.target.value });

            wp.data.dispatch('core/editor').editPost({ meta: { yasr_review_type: event.target.value } });
        }
    }, {
        key: 'render',
        value: function render() {
            return React.createElement(
                'div',
                { className: 'yasr-guten-block-panel-center' },
                React.createElement(
                    'label',
                    null,
                    React.createElement(
                        'span',
                        null,
                        yasrSelectSnippetTitle
                    )
                ),
                React.createElement(
                    'form',
                    null,
                    React.createElement(
                        'select',
                        { value: this.state.snippet, onChange: this.yasrSetItemType },
                        React.createElement(
                            'option',
                            { value: 'Product' },
                            yasrSetItemTypeProduct
                        ),
                        React.createElement(
                            'option',
                            { value: 'Book' },
                            yasrSetItemTypeBook
                        ),
                        React.createElement(
                            'option',
                            { value: 'Movie' },
                            yasrSetItemTypeMovie
                        ),
                        React.createElement(
                            'option',
                            { value: 'Game' },
                            yasrSetItemTypeGame
                        ),
                        React.createElement(
                            'option',
                            { value: 'Place' },
                            yasrSetItemTypePlace
                        ),
                        React.createElement(
                            'option',
                            { value: 'Recipe' },
                            yasrSetItemTypeRecipe
                        ),
                        React.createElement(
                            'option',
                            { value: 'Other' },
                            yasrSetItemBlogPosting
                        )
                    )
                )
            );
        }
    }]);

    return YasrSideBarSelectSnippets;
}(React.Component);

var yasrSidebar = function (_React$Component5) {
    _inherits(yasrSidebar, _React$Component5);

    function yasrSidebar(props) {
        _classCallCheck(this, yasrSidebar);

        var _this5 = _possibleConstructorReturn(this, (yasrSidebar.__proto__ || Object.getPrototypeOf(yasrSidebar)).call(this, props));

        var yasrAutoInsertEnabled = false;

        //this is not for the post, but from settings
        if (yasrConstantGutenberg.autoInsert !== 'disabled') {
            yasrAutoInsertEnabled = true;
        }

        _this5.state = { yasrAutoInsertEnabled: yasrAutoInsertEnabled };

        return _this5;
    }

    _createClass(yasrSidebar, [{
        key: 'render',
        value: function render() {
            return React.createElement(
                Fragment,
                null,
                React.createElement(
                    PluginSidebarMoreMenuItem,
                    { name: 'yasr-sidebar', type: 'sidebar', target: 'yasr-guten-sidebar' },
                    __('YASR post settings', 'yet-another-stars-rating')
                ),
                React.createElement(
                    PluginSidebar,
                    { name: 'yasr-guten-sidebar', title: 'YASR Settings' },
                    React.createElement(
                        PanelBody,
                        null,
                        React.createElement(
                            'div',
                            { className: 'yasr-guten-block-panel yasr-guten-block-panel-center' },
                            React.createElement(YasrDivRatingOverall, null),
                            this.state.yasrAutoInsertEnabled && React.createElement(YasrSideBarAutoInsert, null),
                            React.createElement(YasrDivIsReview, null),
                            React.createElement(YasrSideBarSelectSnippets, null)
                        )
                    )
                )
            );
        }
    }]);

    return yasrSidebar;
}(React.Component);

//Custom sidebar


registerPlugin('yasr-sidebar', {
    icon: 'star-half',
    title: __('Yasr: Page Settings', 'yet-another-stars-rating'),
    render: yasrSidebar
});