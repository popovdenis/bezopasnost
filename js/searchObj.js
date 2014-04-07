/**
 * User: Denis
 * Date: 10.03.14
 * Time: 13:48
 */
var searchObj = {
    init: function () {
        this.initAjaxHandlers();
    },

    initAjaxHandlers: function () {
        $('.search-tag').click(function() {
            searchObj.search_by_tag($(this).text());
        });
    },

    search_by_tag: function(tag) {
        window.location.href = '/search/tags/' + tag;
    }
};