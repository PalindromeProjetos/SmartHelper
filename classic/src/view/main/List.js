//@charset UTF-8
Ext.define( 'SmartHelper.view.main.List', {
    extend: 'Ext.grid.Panel',

    xtype: 'mainlist',

    requires: [
        'Ext.toolbar.Paging',
        'SmartHelper.store.module.Action'
    ],

    title: 'Personnel',

    store: {
        type: 'action'
    },

    columns: [
        {
            flex: 1,
            align: 'left',
            text: 'Diretiva',
            dataIndex: 'directive'
        }, {
            align: 'left',
            text: 'Descrição',
            dataIndex: 'description',
            flex: 2
        }
    ],

    listeners: {
        select: 'onItemSelected'
    },

    bbar: {
        xtype: 'pagingtoolbar',
        displayInfo: true
    },

    buttons: [
        {
            text: 'Request',
            handler: 'onRestFull'
        }
    ]

});
