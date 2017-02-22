//@charset UTF-8
Ext.define( 'SmartHelper.model.module.Action', {
    extend: 'Smart.data.ModelBase',

    requires: [
        'Smart.data.ModelBase'
    ],

    route: {
        id:'actions/{id}',
        list: 'actions/list'
    },

    routePrefix: 'actions',

    fields: [
        {
            name: 'id',
            type: 'int'
        }, {
            name: 'directive',
            type: 'auto'
        }, {
            name: 'description',
            type: 'auto'
        }, {
            name: 'guid',
            type: 'auto'
        }, {
            name: 'isactive',
            type: 'boolean'
        }, {
            name: 'negation',
            type: 'auto'
        }
    ]

});