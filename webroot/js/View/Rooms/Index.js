/*
    View:Rooms/Index
    ----------------

    @file       Index.js
    @package    Kords/View/Rooms
    @author     Gideon Farrell <me@gideonfarrell.co.uk>
 */

define(
    ['Lib/jstorage', 'View/Kords', 'text!Template/Rooms/Filter.html', 'Collection/Rooms', 'Collection/Locations', 'Collection/RentBands'],
    function(jStorage, KordsView, filter_html, RoomsCollection, LocationsCollection, RentBandsCollection) {
        var RoomsIndexView = KordsView.extend({
            tagName: 'div',
            className: 'row',

            templates: {
                filter: filter_html
            },

            events: {
                'change input.filter.search-query':  'searchFilterWithControl',
                'change select.filter':              'setFilterWithControl',
                'click  .filter>button':             'setFilterWithControl'
            },

            _filters: {
                'available': 'yes',
                'contract':  'any',
                'ensuite':   'all',
                'set':       'all',
                'double':    'all',
                'piano':     'all',
                'smoking':   'all',
                'location':  '',
                'rent_band': ''
            },

            initialize: function(opts) {
                this.processTemplates();

                // Set up a side filter
                // We need to template the html (filter_html) and pass in the helper
                // Along with some data that we're going to get now...
                this.Locations = new LocationsCollection();
                    this.Locations.reset(this.bootstrap().Locations, {parse: true});
                this.RentBands = new RentBandsCollection();
                    this.RentBands.reset(this.bootstrap().RentBands, {parse: true});

                this.$filter = $(this.make('div', {'class':'sidebar left span4'}));

                this.$filter.html(this.template(this.templates.filter, {
                                    'locations':    this.Locations.list(),
                                    'rent_bands':   this.RentBands.list()
                                }));

                this.$filter.appendTo(this.$el);

                this.Rooms = new RoomsCollection();
            },

            setFilter: function(filter, state) {
                this.Rooms.fetch();
            getFilterState: function(filter) {
                var state;

                if(filter == 'all') {
                    state = {};
                    this._filters.each(function(value, name) {
                        state[name] = value;
                    });
                } else {
                    if(arguments.length > 1) {
                        state = {};
                        arguments.each(function(arg) {
                            ret[arg] = this.getFilterState(arg);
                        });
                    }
                }

                return state;
            },

            render: function() {

            }
        });

        return RoomsIndexView;
    }
);