//@charset UTF-8
Ext.Loader.setConfig({
    enabled: true,
    paths: {
        'Smart.data': 'library/data',
        'Smart.data.proxy': 'library/data/proxy'
    }
});

Ext.application({
    name: 'SmartHelper',

    extend: 'SmartHelper.Application',

    requires: [
        'SmartHelper.view.main.Main'
    ],

    mainView: 'SmartHelper.view.main.Main'
	
});