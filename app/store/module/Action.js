//@charset UTF-8
Ext.define( 'SmartHelper.store.module.Action', {
    extend: 'Ext.data.Store',

    alias: 'store.action',

    pageSize: 10,

    storeId: 'action',

    model: Ext.create('SmartHelper.model.module.Action')

});