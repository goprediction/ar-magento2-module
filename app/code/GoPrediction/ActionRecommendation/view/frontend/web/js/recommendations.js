define(['uiComponent', 'goPredictionApi', 'ko', 'jquery', 'goPredictionOwlCarousel'],
    function(Component, goPredictionApi, ko, $) {
    'use strict';

    ko.bindingHandlers.afterRender = {
        init: function () {

            if ($('#mp-list-items li').length === 0) {
                return;
            }

            $('#mp-list-items').owlCarousel({
                items: 8,
                dots: false,
                nav: true,
                navContainer: '#recommendations-nav',
                autoplay: true,
                navSpeed: 100,
                autoplaySpeed: 100,
                autoplayTimeout: 15000,
                responsive:{
                    0:{
                        items:1,
                        slideBy: 1
                    },
                    600: {
                        items: 3,
                        slideBy: 3
                    },
                    1000:{
                        items:5,
                        slideBy: 5
                    },
                    1200:{
                        items:6,
                        slideBy: 6
                    }
                }
            });
        }
    };

    return Component.extend({
        initialize: function () {
            this._super();
            this.recommendations = ko.observableArray([]);

            goPredictionApi.publicAccessKey = this.publicAccessKey;

            this.sendAction();
            this.loadRecommendations();
        },

        initObservable: function () {
            this._super()
                .observe('recommendations');

            return this;
        },

        sendAction: function() {
            goPredictionApi.action({
                "customer": goPredictionApi._getCustomerIdentifier(),
                "product_id": this.productId
            }, function (response) {});
        },

        loadRecommendations: function () {
            var self = this;
            goPredictionApi.query({
                "count": 20,
                "product_ids": [this.productId]
            }, function (response) {

                var data = JSON.parse(response);

                if (typeof (data.items) === 'undefined') {
                    return;
                }

                if (data.items.length === 0) {
                    return;
                }

                $('#recommendations').show();

                for (var i = 0; i < data.items.length; i++) {
                    self.recommendations.push(data.items[i]);
                }
            });
        }
    });
});
