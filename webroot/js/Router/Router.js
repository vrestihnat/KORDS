/*
    Main Router
    -----------

    @file     Router.js
    @package  Kords/Router
    @author   Gideon Farrell <me@gideonfarrell.co.uk>
 */

define(
    ['Mootools/core', 'backbone', 'Collection/Rooms', 'Collection/Locations', 'View/App'],
    function(_Mootools, Backbone, RoomsCollection, LocationsCollection, AppView) {
        var Router = Backbone.Router.extend({
            routes: {
                '':                    'landing',
                'rooms':               'room_index',
                'rooms/*filter':       'room_index',
                'rooms/(\w+\d+)':      'room_view',
                'rooms/(\w+\d+)/edit': 'room_edit',
                'rooms/add':           'room_add',

                'locations':           'location_index',
                'locations/:id':       'location_view',
                'locations/:id/edit':  'location_edit',
                'locations/add':       'location_add',
                
                'admin':               'load_admin',
                'admin/*page':         'load_admin'
            },

            initialize: function(opts) {
                // Set up the AppView
                this.AppView = new AppView({
                    el: '#KordsApp'
                });

            },

            landing: function() {
                this.AppView.loadView('Pages/Landing');
            },

            // Room Actions
            // ------------

            room_index: function(for_who) {
                var room_list = new RoomsCollection();
                this.AppView.loadView('Rooms/Index', function(index_view) {
                    index_view.setFilter('for', for_who);
                });
            },
            room_view: function(id) {},
            room_add: function() {},
            room_edit: function(id) {},

            // Location Actions
            // ----------------

            location_index: function() {},
            location_view: function(id) {},
            location_add: function() {},
            location_edit: function(id) {},

            // Admin Actions
            // -------------
            load_admin: function(page) {
                Backbone.history.stop();
                window.location.pathname = '/admin/#' + (page !== undefined ? '/'+page : '/');
            }
        });

        return Router;
    }
);