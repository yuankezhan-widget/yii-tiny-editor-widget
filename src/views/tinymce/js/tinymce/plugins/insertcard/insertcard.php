
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <script src="/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="./plugin.min.js"></script>
    <style>
        .search-goods-line{
            display: flex;
            justify-content: space-between;
            padding: 10px;
        }
        .search-btn{
            cursor: pointer;
            float: right;
            text-align: center;
            line-height: 30px;
            width: 100px;
            height: 30px;
            background: #FF4200;
            border-radius: 5px;
            font-size: 16px;
            font-family: Microsoft YaHei;
            font-weight: 400;
            color: #FFFFFF;
            margin-left: 30px;
        }
        .error-hint{
            display: none;
        }
        .goodsUrl{
            width: 69%;
        }
        .search-goods-info-box{
            padding: 10px;
        }
        .search-goods-info{
            display: flex;
        }
        .search-goods-info-show{
            width: 100%;
        }
        .update-img{
            width: 180px;
            height: 180px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .goods-info {
            height: 30px;
            width: 70%;
            margin: 10px 0;
        }
        .createBtn{
            width: 100px;
            height: 30px;
            font-weight: 400;
            border-radius: 5px;
            font-size: 16px;
            font-family: Microsoft YaHei;
            color: #FFFFFF;
            background-color: #FF4200;
            text-align: center;
            line-height: 30px;
            cursor: pointer;
            float: right;
            margin-top: 15px;
            margin-right: 10px;
        }
        .errorbox{
            font-size: 14px;
            color: red;
            padding-left: 155px;
        }
    </style>
</head>
<body>
<div class="search-goods-line">
    <span class="title">商品链接：</span>
    <input type="text" value="" class="goodsUrl">
    <div class="search-btn" onclick="getGoodInfo()">获取信息</div>
</div>
<div class="error-hint">*未获取到商品信息，请手动填写</div>

<div class="search-goods-info-box">
    <div class="title">商品信息</div>
    <div class="search-goods-info">
        <div class="update-img">
            <div>
                <?php
                require_once $_SERVER["DOCUMENT_ROOT"] . '/controls/CommonPhotos.php';
                $upload_file_behind = new \CommonPhotos("goods-img", 1, true, true, true, 'common', [], '', $_SERVER['REQUEST_SCHEME'] . "://" . 'www.jpgoodbuy.com', "", true);
                echo $upload_file_behind->render();
                ?>
            </div>
        </div>
        <div class="search-goods-info-show">
            <div>
                <span class="title">商品名称：</span>
                <input type="text" value="" placeholder="请输入商品名称" class="goods-info goods-name">
            </div>
            <div>
                <span class="title">商家名称：</span>
                <input type="text" value="" placeholder="请输入商家名称" class="goods-info goods-store-name">
            </div>
            <div>
                <span class="title">商品价格：</span>
                <input type="text" value="" placeholder="请输入商品价格" class="goods-info goods-price">
                <span>日元</span>
            </div>
        </div>
    </div>
</div>
<div class="errorbox"></div>
<div class="createBtn" onclick="createCard()">生成卡片</div>
</body>
<script>
    let domain = '<?= $_SERVER['REQUEST_SCHEME'] . "://" . 'www.jpgoodbuy.com'?>';
    let folderName = 'common';
    let oldGoodsUrl = '';
    let insertcard = parent.insertcard;
    function getGoodInfo() {
        let goods_url = $('.goodsUrl').val();
        if (goods_url == undefined || goods_url == '')
        {
            showErrorHintBox('商品链接不能为空');
            return;
        }
        oldGoodsUrl = goods_url;
        goods_url = goods_url.replace(/http:\/\/amazon.jpgoodbuy.com\//, "http://www.amazon.co.jp/");
        // goods_url = goods_url.replace(/http:\/\/daigou.fba.jp\//, "http://www.amazon.co.jp/")
        $('.goodsUrl').val(goods_url);
        $.ajax({
            url:"/yii/web/index.php?r=home/purchasing-n/get-cache-goods-info-by-ajax",
            data:{
                goods_url : goods_url,
            },
            type:"post",
            dataType:"json",
            success:function(data) {
                if(data.Success == true)
                {
                    $('.goods-info').empty();
                    $('.deleteThis').click();
                    if (data.Data != null)
                    {
                        $('.goods-name').val(data.Data.goodsName);
                        $('.goods-price').val(parseInt(data.Data.goodsPrice));
                        addImgBox('goods-img', data.Data.goodsImg, 1);
                        $('.goods-store-name').val(data.Data.goodsStoreName);
                        if (!isNull(data.Data.goodsName) || !isNull(data.Data.goodsPrice) || !isNull(data.Data.goodsImg) || !isNull(data.Data.goodsStoreName))
                        {
                            loadGrab(goods_url);
                        }
                    }
                    else
                    {
                        loadGrab(goods_url);
                    }
                }
                else
                {
                    loadGrab(goods_url);
                    $('.goods-info').empty();
                    showErrorHintBox('商品信息查询失败');
                }
            }
        });
    }

    function loadGrab(goods_url) {
        $.ajax({
            url:"/yii/web/index.php?r=home/purchasing-n/get-goods-info-by-ajax",
            data:{
                goods_url : goods_url,
            },
            type:"post",
            dataType:"json",
            success:function(data) {
                if(data.Success == true && data.Data != null)
                {
                    $('.goods-info').empty();
                    $('.deleteThis').click();
                    if (!isNull($('.goods-name').val()))
                    {
                        $('.goods-name').val(data.Data.goodsName);
                    }
                    if (!isNull($('.goods-price').val()))
                    {
                        $('.goods-price').val(data.Data.goodsPrice);
                    }
                    if (!isNull($('.goods-store-name').val()))
                    {
                        $('.goods-store-name').val(data.Data.web.web_name);
                    }
                    addImgBox('goods-img', data.Data.goodsImg, 1);
                }
                else
                {
                    showErrorHintBox('商品信息查询失败');
                }
            }
        });
    }

    function isNull(variable) {
        if (variable != '' && variable != undefined)
        {
            return true;
        }
        return false;
    }

    function createCard() {
        let goods_url = $('.goodsUrl').val();
        if (goods_url == undefined || goods_url == '')
        {
            showErrorHintBox('商品链接不能为空');
            return;
        }
        let imgContent = getImgsList('goods-img');
        if (imgContent[0].indexOf("data:image") != -1)
        {
            imgContent = upImgFun(imgContent)
        }
        else
        {
            imgContent = imgContent[0];
        }
        if (!isNull(imgContent))
        {
            showErrorHintBox('商品图片不能为空');
            return;
        }
        let goodsName = $('.goods-name').val();
        if (goodsName == undefined || goodsName == '')
        {
            showErrorHintBox('商品名称不能为空');
            return;
        }
        let price = $('.goods-price').val();
        if (price == undefined || price == '')
        {
            showErrorHintBox('商品价格不能为空');
            return;
        }
        let storeName = $('.goods-store-name').val();
        if (storeName == undefined || storeName == '')
        {
            showErrorHintBox('商家名称不能为空');
            return;
        }
        let goodsDes = '';
        let content =
            '<div style="width: 100%;overflow: auto"><div style="width: 35rem;height: 10rem;box-sizing: border-box;padding: 1.3rem 1.6rem 1.3rem 1.3rem;background-color: #FFFFFF;margin: 0 auto;display: flex;box-shadow: 0px 0px 0.3rem 0px rgba(108, 108, 108, 0.24);">\n' +
            '    <div style="width: 7.5rem;height: 7.5rem;display: flex;justify-content: center;align-items: center;margin-right: 1.4rem">\n' +
            '        <img style="max-height: 100%;max-width: 100%;" src="' + imgContent + '" alt="">\n' +
            '    </div>\n' +
            '    <div>\n' +
            '        <div title="' + goodsName + '" style="width: 23.3rem;height: 2.25rem;line-height: 1.2rem;font-size: 0.8rem;font-family: Microsoft YaHei;font-weight: bold;color: #333333;text-overflow: ellipsis;overflow: hidden">\n' +
            '            ' + goodsName + '\n' +
            '        </div>\n' +
            '        <div title="' + goodsDes + '" style="width: 23.3rem;height: 2.25rem;line-height: 1rem;font-size: 0.8rem;font-family: Microsoft YaHei;font-weight: 400;color: #656565;text-overflow: ellipsis;overflow: hidden;margin: 0.5rem 0 0.8rem 0;">\n' +
            '            ' + goodsDes + '\n' +
            '        </div>\n' +
            '        <div style="display: flex;justify-content: space-between;align-items: center;">\n' +
            '            <div style="line-height: 0.7rem;font-size: 0.7rem;font-family: Microsoft YaHei;font-weight: 400;color: #319BEF;">\n' +
            '                ' + storeName + '\n' +
            '            </div>\n' +
            '            <div style="display: flex;align-items: center;">\n' +
            '                <div style="font-size: 0.7rem;font-family: Microsoft YaHei;font-weight: bold;color: #FF4100;margin-right: 1.1rem;">' + price + '日元</div>\n' +
            '                <a href="' + oldGoodsUrl + '" target="_blank" style="height: 1.4rem;background: #FF4100;border-radius: 5px;cursor: pointer;line-height: 0.6rem;font-size: 0.6rem;font-family: Microsoft YaHei;font-weight: 400;color: #FFFFFF;text-align: center;box-sizing: border-box;padding: 0.35rem 0.6rem;">去看看</a>\n' +
            '            </div>\n' +
            '        </div>\n' +
            '    </div>\n' +
            '</div></div>' +
            '<p><br></p>';
        parent.tinymce.editors[parent.tinymce.settings.selector.replace("#","")].insertContent(content);
        $("body",parent.document).find('.tox-button--icon').click();
    }

    function showErrorHintBox(content = '') {
        $('.errorbox').html(content);
        $('.errorbox').show();
    }

    function dataURLtoFile(base64Str, fileName) {
        let arr = base64Str[0].split(','),
            mime = arr[0].match(/:(.*?);/)[1],
            bstr = atob(arr[1]),
            len = bstr.length,
            ab = new ArrayBuffer(len),
            u8arr = new Uint8Array(ab);
        while (len--) {
            u8arr[len] = bstr.charCodeAt(len)
        };
        return new File([u8arr], fileName, {
            type: mime
        })
    }

    function upImgFun(dataURL) {
        let upImgUrl = ''
        let file = dataURLtoFile(dataURL, Date.now() + '.png');
        if (file.size <= 0)
        {
            alert("格式不正确");
            return;
        }
        if (file.size/1024 > 8192)
        {
            alert("图片大小请控制在8M以内");
            return;
        }
        let formFile = new FormData();
        formFile.append('file', file);
        formFile.append('name', file.name);
        formFile.append('size', file.size);
        formFile.append('type', file.type);
        formFile.append('domain', domain);
        formFile.append('indexCount', 0);
        formFile.append('totalCount', 1);
        formFile.append('domainNull', 1);
        formFile.append('folder_name', folderName);
        formFile.append('trueName', '');

        $.ajax({
            url: domain + '/controls/common_photos.php?act=upLode',
            dataType: 'json',
            type: "post",
            data: formFile,
            async: false,
            contentType: false,
            processData: false,
            success: function (data) {
                if (data.res)
                {
                    upImgUrl = data.url;
                }
            }
        });
        return upImgUrl;
    }
</script>
</html>