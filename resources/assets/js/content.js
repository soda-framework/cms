Soda.contentTable = new Vue({
    el: '#app',
    data: {
        content: {},
        contentItemTypes: {},
        contentFolderTypes: {},
        selectedContentType: null,
    },
    mounted: function() {
        window.initVue(this);
    },
    methods: {
        routeTo: function(route, contentId) {
            return route.replace(new RegExp('###ID###', 'g'), contentId);
        },
        newContentItem: function() {
            var numContentTypes = Object.keys(this.contentItemTypes).length;
            var modal = $('#contentItemTypeModal');
            var form = modal.find('form');

            this.$set(this, 'selectedContentType', numContentTypes ? Object.keys(this.contentItemTypes)[0] : null);

            if(numContentTypes > 1) {
                modal.modal('show');
            } else {
                this.$nextTick(function() {
                    form.submit();
                });
            }
        },
        newContentFolder: function() {
            var numContentTypes = Object.keys(this.contentFolderTypes).length;
            var modal = $('#contentFolderTypeModal');
            var form = modal.find('form');

            this.$set(this, 'selectedContentType', numContentTypes ? Object.keys(this.contentFolderTypes)[0] : null);
            modal.modal('show');
        },
    }
});
