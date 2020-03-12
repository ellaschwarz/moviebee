const {registerPlugin} = wp.plugins;
const {PluginSidebar, PluginSidebarMoreMenuItem} = wp.editPost;
const {TextControl, PanelBody, PanelRow} = wp.components;
const {BlockControls,InspectorControls} = wp.editor;

const yasrDisableAutoInsert = __('Disable auto insert for this post or page?', 'yet-another-stars-rating');
const yasrRichSnippetOptions = __('Rich snippet options', 'yet-another-stars-rating');
const yasrPostExcluded = __('Is this a review?', 'yet-another-stars-rating');
const yasrSelectSnippetTitle = __('This review is about a... ', 'yet-another-stars-rating');
const yasrSetItemTypeProduct = __('Product', 'yet-another-stars-rating');
const yasrSetItemTypeBook = __('Book', 'yet-another-stars-rating');
const yasrSetItemTypeMovie = __('Movie', 'yet-another-stars-rating');
const yasrSetItemTypeGame = __('Game', 'yet-another-stars-rating');
const yasrSetItemTypePlace = __('LocalBusiness', 'yet-another-stars-rating');
const yasrSetItemTypeRecipe = __('Recipe', 'yet-another-stars-rating');
const yasrSetItemBlogPosting = __('BlogPosting', 'yet-another-stars-rating');


class YasrDivRatingOverall extends React.Component  {

    shouldComponentUpdate(nextProps, nextState) {
        return false;
    }

    constructor(props) {
        super(props);
        this.yasrOverallRateThis = __("Rate this article / item", 'yet-another-stars-rating');
        this.yasrOverallMoreInfo =
            __("This is the same value that you find the \"Yasr: Overall Rating\" block.",
            'yet-another-stars-rating');
    }

    printDivOverallRater() {
        return(
            <div>
                <div id="overall-rater-panel" ref={()=>
                    raterJs({
                        starSize: 32,
                        step: 0.1,
                        showToolTip: false,
                        rating: wp.data.select('core/editor').getCurrentPost().meta.yasr_overall_rating,
                        readOnly: false,
                        element: document.querySelector("#overall-rater-panel"),
                        rateCallback: function(rating, done){

                            rating = rating.toFixed(1);
                            rating = parseFloat(rating);

                            wp.data.dispatch('core/editor').editPost(
                                { meta: { yasr_overall_rating: rating } }
                            );

                            this.setRating(rating);

                            done();
                        }
                    })
                }
                />
                <br />
                {this.yasrOverallMoreInfo}
                <hr />
            </div>
        )
    }

    render () {
        return (
            <div>
                {this.yasrOverallRateThis}
                <div>
                    {this.printDivOverallRater()}
                </div>
            </div>

        );
    }

}

class YasrSideBarAutoInsert extends React.Component {

    constructor(props) {
        super(props);

        let isThisPostExluded = wp.data.select('core/editor').getCurrentPost().meta.yasr_auto_insert_disabled;
        let isThisPostExludedCheckbox = false;

        if (isThisPostExluded === 'yes') {
            isThisPostExludedCheckbox = true;
        }

        this.state = {postExcluded: isThisPostExludedCheckbox};

        this.yasrUpdatePostMetaAutoInsert = this.yasrUpdatePostMetaAutoInsert.bind(this);
    }

    yasrUpdatePostMetaAutoInsert(event) {

        const target = event.target;
        const postExcluded = target.type === 'checkbox' ? target.checked : target.value;

        this.setState({postExcluded: postExcluded});

        if (postExcluded === true) {
            wp.data.dispatch('core/editor').editPost(
                { meta: { yasr_auto_insert_disabled: 'yes' } }
            );
        } else {
            wp.data.dispatch('core/editor').editPost(
                { meta: { yasr_auto_insert_disabled: 'no' } }
            );
        }

    }

    render () {
        return (
            <div className="yasr-guten-block-panel-center">
                <label><span>{yasrDisableAutoInsert}</span></label>
                <div className="yasr-onoffswitch-big" id="yasr-switcher-disable-auto-insert">
                    <input type="checkbox"
                           name="yasr_auto_insert_disabled"
                           className="yasr-onoffswitch-checkbox"
                           value="yes"
                           id="yasr-auto-insert-disabled-switch"
                           defaultChecked={this.state.postExcluded}
                           onChange={this.yasrUpdatePostMetaAutoInsert}
                    />
                    <label className="yasr-onoffswitch-label" htmlFor="yasr-auto-insert-disabled-switch">
                        <span className="yasr-onoffswitch-inner"></span>
                        <span className="yasr-onoffswitch-switch"></span>
                    </label>
                </div>
                <hr/>
            </div>
        );
    }

}

class YasrDivIsReview extends React.Component {

    constructor(props) {
        super(props);

        let isThisPostReview = wp.data.select('core/editor').getCurrentPost().meta.yasr_post_is_review;
        let isThisPostReviewCheckbox = false;

        if (isThisPostReview === 'yes') {
            isThisPostReviewCheckbox = true;
        }

        this.state = {postIsReview: isThisPostReviewCheckbox};

        this.yasrUpdatePostMetaIsReview = this.yasrUpdatePostMetaIsReview.bind(this);
    }

    yasrUpdatePostMetaIsReview(event) {

        const target = event.target;
        const postIsReview = target.type === 'checkbox' ? target.checked : target.value;

        this.setState({postIsReview: postIsReview});

        if (postIsReview === true) {
            wp.data.dispatch('core/editor').editPost(
                { meta: { yasr_post_is_review: 'yes' } }
            );
        } else {
            wp.data.dispatch('core/editor').editPost(
                { meta: { yasr_post_is_review: 'no' } }
            );
        }

    }

    render () {
        return (
            <div className="yasr-guten-block-panel-center">
                <div className="rich-snippet-title">{yasrRichSnippetOptions}</div>
                <label><span>{yasrPostExcluded}</span></label>
                <div className="yasr-onoffswitch-big" id="yasr-switcher-post-is-review">
                    <input type="checkbox"
                           name="yasr_post_is_review"
                           className="yasr-onoffswitch-checkbox"
                           value="yes"
                           id="yasr-post-is-review-switch"
                           defaultChecked={this.state.postIsReview}
                           onChange={this.yasrUpdatePostMetaIsReview}
                    />
                    <label className="yasr-onoffswitch-label" htmlFor="yasr-post-is-review-switch">
                        <span className="yasr-onoffswitch-inner"></span>
                        <span className="yasr-onoffswitch-switch"></span>
                    </label>
                </div>
                <p></p>
            </div>
        );
    }

}


class YasrSideBarSelectSnippets extends React.Component {

    constructor(props) {
        super(props);

        //at the begin, get the default type
        let snippetType = yasrConstantGutenberg.defaultItemType;

        //postmeta itemtype
        let postMetaItemType = wp.data.select('core/editor').getCurrentPost().meta.yasr_review_type;

        //if post meta value is one of this overwrite snippetType
        if (postMetaItemType === 'Product'
            || postMetaItemType === 'Book'
            || postMetaItemType === 'Movie'
            || postMetaItemType === 'Game'
            || postMetaItemType === 'Place'
            || postMetaItemType === 'Recipe'
            || postMetaItemType === 'Other'
        ) {
            snippetType = postMetaItemType;
        }

        this.state = {snippet: snippetType};

        this.yasrSetItemType = this.yasrSetItemType.bind(this);
    }

    yasrSetItemType(event) {
        this.setState({snippet: event.target.value});

        wp.data.dispatch( 'core/editor' ).editPost(
            { meta: { yasr_review_type: event.target.value } }
        );
    }

    render() {
        return (
            <div className="yasr-guten-block-panel-center">
                <label><span>{yasrSelectSnippetTitle}</span></label>
                <form>
                    <select value={this.state.snippet} onChange={this.yasrSetItemType}>
                        <option value="Product">{yasrSetItemTypeProduct}</option>
                        <option value="Book">{yasrSetItemTypeBook}</option>
                        <option value="Movie">{yasrSetItemTypeMovie}</option>
                        <option value="Game">{yasrSetItemTypeGame}</option>
                        <option value="Place">{yasrSetItemTypePlace}</option>
                        <option value="Recipe">{yasrSetItemTypeRecipe}</option>
                        <option value="Other">{yasrSetItemBlogPosting}</option>
                    </select>
                </form>
            </div>
        );
    }
}

class yasrSidebar extends React.Component {

    constructor(props) {
        super(props);

        let yasrAutoInsertEnabled = false;

        //this is not for the post, but from settings
        if (yasrConstantGutenberg.autoInsert !== 'disabled') {
            yasrAutoInsertEnabled = true;
        }

        this.state = {yasrAutoInsertEnabled: yasrAutoInsertEnabled};

    }

    render() {
        return (
            <Fragment>
                <PluginSidebarMoreMenuItem name="yasr-sidebar"  type="sidebar" target="yasr-guten-sidebar" >
                    { __( 'YASR post settings', 'yet-another-stars-rating' ) }
                </PluginSidebarMoreMenuItem>
                <PluginSidebar name="yasr-guten-sidebar" title="YASR Settings">
                    <PanelBody>
                        <div className="yasr-guten-block-panel yasr-guten-block-panel-center">
                            <YasrDivRatingOverall />
                            {this.state.yasrAutoInsertEnabled && <YasrSideBarAutoInsert/>}
                            <YasrDivIsReview />
                            <YasrSideBarSelectSnippets/>
                        </div>
                    </PanelBody>
                </PluginSidebar>
            </Fragment>
        );
    }
}


//Custom sidebar
registerPlugin( 'yasr-sidebar', {
    icon: 'star-half',
    title: __( 'Yasr: Page Settings', 'yet-another-stars-rating' ),
    render: yasrSidebar
} );