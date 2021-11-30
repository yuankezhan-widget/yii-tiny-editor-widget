tinymce.PluginManager.add('insertcard', function(editor, url) {
    var pluginName='生成商品卡片';
    window.insertcard=[]; //扔外部公共变量，也可以扔一个自定义的位置

    var baseURL=tinymce.baseURL;
    var iframe1 = baseURL+'/plugins/insertcard/insertcard.php';
    insertcard.res=[];
    var openDialog = function() {
        return editor.windowManager.openUrl({
            title: pluginName,
            size: 'large',
            url: iframe1,
            buttons: [
                {
                    type: 'cancel',
                    text: 'Close'
                },
                {
                    type: 'custom',
                    text: 'CreatCard',
                    name: 'save',
                    primary: true
                },
            ],
            onAction: function (api, details) {
                switch (details.name) {
                    case 'save':
                        editor.insertContent(insertcard.res);
                        insertcard.res=[];
                        api.close();
                        break;
                    default:
                        break;
                }

            }
        });
    };

    editor.ui.registry.getAll().icons.insertcard || editor.ui.registry.addIcon('insertcard','<svg t="1610357948882" class="icon" viewBox="0 0 1210 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="2125" width="16" height="16"><path d="M1108.060587 917.829866h0.558545l-0.418908-816.174174-1007.289724 0.279273-0.139637 816.267265 1007.289724-0.372364z m0-917.829401c55.668338-0.139636 100.770863 45.335252 100.9105 101.655227v816.546538c-0.279273 56.319974-45.381798 101.655227-100.9105 101.655226H100.9105C45.381798 1019.997093 0.139636 974.522204 0 918.20223V101.655692C0.139636 45.475354 45.242161-0.139171 100.9105 0.000465h1007.150087zM251.903885 611.933278a50.734522 50.734522 0 0 1-50.408704-50.967249 50.734522 50.734522 0 0 1 50.408704-50.96725h201.541727c27.787624 0 50.362159 22.760717 50.362159 50.96725a50.734522 50.734522 0 0 1-50.408704 50.967249h-201.541727z m403.036908 101.934499a50.734522 50.734522 0 0 1 50.362159 50.920704 50.734522 50.734522 0 0 1-50.362159 50.96725H251.810795a50.734522 50.734522 0 0 1-50.362159-50.96725 50.734522 50.734522 0 0 1 50.408704-50.967249h403.036908zM604.532089 203.916009h403.083453v407.970724h-403.083453V203.962555z m302.172953 305.896588h0.139637V305.897054h-201.541727v203.962089h201.40209z" fill="#000000" p-id="2126"></path></svg>');

    editor.ui.registry.addButton('insertcard', {
        icon: 'insertcard',
        tooltip: pluginName,
        onAction: function() {
            openDialog();
        }
    });
    return {
        getMetadata: function() {
            return  {
                name: pluginName,
            };
        }
    };
});
