//@charset UTF-8
Ext.define( 'SmartHelper.Application', {
    extend: 'Ext.app.Application',
    
    name: 'SmartHelper',

    stores: [
        // TODO: add global / shared stores here
    ],

    init: function() {
        var me = this;
        // me.initQuickTips();
        SmartHelper.app = me;
        Ext.USE_NATIVE_JSON = true;
        SmartHelper.appType = 'pro';
        Ext.enableAriaButtons = false;
        me.setDefaultToken(Ext.manifest.name.toLowerCase());
    },

    launch: function () {
        //<debug>
            SmartHelper.appType = 'dev';
            document.cookie = 'XDEBUG_SESSION=PHPSTORM;path=/;';
        //</debug>

        Ext.manifest.appType = SmartHelper.appType;
    }

});