//@charset UTF-8
Ext.define( 'Smart.data.proxy.RestBase', {
    extend: 'Ext.data.proxy.Rest',

    alias: 'proxy.restbase',

    reader: {
        type: 'json',
        idProperty: 'id',
        rootProperty: 'rows',
        messageProperty: 'text',
        totalProperty: 'records',
        successProperty: 'success'
    },

    writer: {
        type: 'json',
        encode: true,
        idProperty: 'id',
        dateFormat: 'Y-m-d',
        rootProperty: 'model',
        writeAllFields: false
    },

    setRoute: function (route) {
        var me = this;
        me.setUrl(Ext.String.format('{0}/{1}',Ext.manifest.routeBase,route));
    }

});