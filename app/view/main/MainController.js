//@charset UTF-8
Ext.define('SmartHelper.view.main.MainController', {
    extend: 'Ext.app.ViewController',

    alias: 'controller.main',

    onItemSelected: function (sender, record) {
        Ext.Msg.confirm('Confirm', 'Are you sure?', function (choice) {
            if (choice === 'yes') {
                var routeList = record.getRouteList(),
                    oldUrl = record.getProxy().getUrl();

                record.getProxy().setRoute(routeList.routePrefix);
                record.set('description','_' + record.get('description'));
                record.save();
                record.getProxy().setUrl(oldUrl);
            }
        }, this);
    },

    onRestFull: function () {
        var store = Ext.getStore('action'),
            model = store.getModel(),
            routeList = (new model).getRouteList();

        store.getProxy().setRoute(routeList.route.list);
        // store.getProxy().setRoute(routeList.routePrefix);
        // store.getProxy().setRoute(routeList.route.id.replace('{id}',1));
        store.load();
    }

});
