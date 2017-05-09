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
        getFormattedDate: function(date) {
            return moment(date, 'YYYY-MM-DD hh:mm:ss').format('Do MMMM YYYY');
        },
        routeTo: function(route, contentId) {
            return route.replace(new RegExp('###ID###', 'g'), contentId);
        },
        newContentItem: function(contentTypeId) {
            var modal = $('#contentItemTypeModal');
            var form = modal.find('form');

            if(contentTypeId !== null && contentTypeId !== '') {
                this.$set(this, 'selectedContentType', contentTypeId);
                this.$nextTick(function() {
                    form.submit();
                });
            } else {
                var numContentTypes = Object.keys(this.contentItemTypes).length;
                this.$set(this, 'selectedContentType', numContentTypes ? Object.keys(this.contentItemTypes)[0] : null);

                if(numContentTypes > 1) {
                    modal.modal('show');
                } else {
                    this.$nextTick(function() {
                        form.submit();
                    });
                }
            }
        },
        newContentFolder: function(contentTypeId) {
            var modal = $('#contentFolderTypeModal');
            var form = modal.find('form');

            if(contentTypeId !== null && contentTypeId !== '') {
                this.$set(this, 'selectedContentType', contentTypeId);
                modal.modal('show');
            } else {
                var numContentTypes = Object.keys(this.contentFolderTypes).length;
                this.$set(this, 'selectedContentType', numContentTypes ? Object.keys(this.contentFolderTypes)[0] : null);
                modal.modal('show');
            }
        },
        deleteContent: function(event) {
            Soda.confirmDelete($(event.target));
        }
    }
});
