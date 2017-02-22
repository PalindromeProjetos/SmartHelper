//@charset UTF-8
Ext.define( 'Ext.overrides.data.Connection', {
    override: 'Ext.data.Connection',

    // timeout: 30000,

    constructor: function () {
        var me = this;

        me.callParent(arguments);
        me.onAfter( 'requestcomplete', me.fnRequestComplete, me);
    },

    fnRequestComplete: function ( conn , response , options , eOpts ) {
        var result = Ext.decode(response.responseText),
            workstation = localStorage.getItem('workstation');

        workstation = workstation ? Ext.decode(workstation) : null;

        if((response.status == 200) && (result.text == 1)) {
            if(workstation) {
                workstation.session = 'A sua sessão expirou, a aplicação deverá ser autenticada novamente!';
                localStorage.setItem('workstation', Ext.encode(workstation));
            }
            window.location.reload();
        }
    },

    request: function(options) {
        options = options || {};

        options.headers = {
            'Authorization': '',
            'Credential-Type' : Ext.manifest.appType,
            'Credential-Name' : 'Palindrome Projetos',
            'Credential-Auth' : Ext.util.Cookies.get('Credential-Auth'),
            'Credential-Code' : Ext.util.Cookies.get('Credential-Code'),
            'Credential-Data' : Ext.util.Cookies.get('Credential-Data')
        };

        return this.callParent(arguments);
    }

});