# 取个号 API

## 目录

[流](#1)

<h2 id='1'></h2>## 流

![流](http://on-img.com/chart_image/5afbd4c6e4b0026862677068.png)

## WebSocket

url: wss://brocadesoar.cn:4431

数据格式：JSON字符串

握手前，登录到服务端获取session_id并存储

握手后，由小程序发送交互的第一条消息：

* fields : 
```
{
    "session_id" : "SESSION_ID",
}
    
```

如果数据符合要求则保持连接，并确保25秒的心跳

如果数据不符合要求连接被服务端主动关闭

超过心跳时间服务端主动关闭连接

## Session 用户状态维持

### login 登录

* url : https://brocadesoar.cn/callme/wx_session/index

* type : POST

* fields : 

```
{
    "code" : "CODE",
}
```

* return : 

```
{
    "session_id" : SESSION_ID,
    "is_seller" : IS_SELLER,
}
```

* Cases :

`IS_SELLER (string)`

| 值|说明|
|---|---|
|true|商家列表有该id|
|false|商家列表没有该id|

## 信息查询

### content 获取第一个渲染用数据包

* url : https://brocadesoar.cn/callme/wx_session/content

* type : POST

* fields : 

```
{
    "session_id" : SESSION_ID,
}
```

* return : 

```
{
    "ads_img_url" : [
        URL,
        URL,
        URL,
    ],
    "history" : [
        {
            "food_list" : [
                {
                    "product_id" : PRODUCT_ID,
                    "product_img_url" : PRODUCT_IMG_URL,
                    "product_name" : PRODUCT_NAME,
                },
                {},
                {}
            ],
            "food_quantity" : [
                2,
                1,
                1,
            ],
        },
        {},
        {},
        {},
        {},
    ],
    "random" : [
        {
            "food_list" : [
                {
                    "product_id" : PRODUCT_ID,
                    "product_img_url" : PRODUCT_IMG_URL,
                    "product_name" : PRODUCT_NAME,
                    "user_img_url" : USER_IMG_URL,
                }
            ],
            "food_quantity" : [
                2,
                1,
                1,
            ],
        },
        {},
        {},
        {},
        {},
        {},
    ]
}
```

### shop 获取某商家店面信息

* url : https://brocadesoar.cn/callme/wx_session/shop

* type : POST

* fields :

```
{
    "session_id" : SESSION_ID,
    "seller_id" : SELLER_ID
}
```

* return :

```
{
    "labels" : [
        "label1",
        "label2"
    ],
    "seller_img_url" : SELLER_IMG_URL,
    "seller_menu_list" : [
        {
            "img_url" : IMG_URL,
            "inventory" : INVENTORY,
            "label" : LABEL,
            "name" : NAME,
            "product_id" : PRODUCT_ID,
            "total_fee" : TOTAL_FEE
        },
        {},
        {}
    ],
    "seller_name" : SELLER_NAME
}
```

### history 获取用户历史订单

* url : https://brocadesoar.cn/callme/wx_session/history

* type : POST

* fields :

```
{
    "session_id" : SESSION_ID,
}

```

* return :

```
{
    "history_done" : [
        {
            "food_list" : [
                {
                    "create_time" : CREATE_TIME,
                    "product_id" : PRODUCT_ID,
                    "product_img_url" : PRODUCT_IMG_URL,
                    "product_name" : PRODUCT_NAME,
                    "product_total_fee" : PRODUCT_TOTAL_FEE,
                    "product_name" : PRODUCT_NAME,
                },
                {},
                {},
            ],
            "food_quantity" : [
                2,
                1,
                1,
            ],
        },
        {},
        {},
    ],
    "history_wait" : [
        {},
        {},
        {},
    ]
}
```

### diningHall 获取某食堂的商家列表

* url : https://brocadesoar.cn/callme/wx_session/diningHall

* type : POST

* fields :

```
{
    "session_id" : SESSION_ID,
    "dining_hall_id" : DINING_HALL_ID,
}
```

* return :

```
{
    "name" : NAME_OF_DINING_HALL,
    "seller_list" : [
        1,
        3,
        5,
        OTHER_SELLER_ID
    ],
}
```

### picture 获取一张图片

* url : https://brocadesoar.cn/callme/wx_session/picture

* type : GET

* fields : 

```
{
    "pic_id" : PIC_ID,
}
```

* return : 图片资源 或 

```
{
    "errmsg" : "No that Picture",
}
```

## 商家入驻

### register 入驻注册

* url : https://brocadesoar.cn/callme/seller_man/register

* type : POST

* fields :

```
{
    "session_id" : SESSION_ID,
    "owner_tell" : OWNER_TELL,
    "owner_name" : OWNER_NAME,
    "seller_NAME" : SELLER_NAME,
    "dining_hall_id" : DINING_HALL_ID,
}
```

* return : SUCCESS_INFO

### registerList 获取注册列表

* url : https://brocadesoar.cn/callme/seller_man/registerList

* type : POST

* fields :

```
{
    "session_id" : SESSION_ID,
    // "pass_state" : PASS_STATE,
}
```

* return : 

```
{
    "register_list" : [
        {
            "create_time" : CREATE_TIME,
            "owner_name" : OWNER_NAME,
            "owner_tell" : OWNER_TELL,
            "pass_state" : PASS_STATE,
            "register_id" : REGISTER_ID,
            "seller_name" : SELLER_NAME,
            "staff_list" : [
                ARRAY_OF_USER_ID_OF_STAFF
            ],
            "update_time" : UPDATE_TIME
        },
        {},
        {},
    ]
}
```

### registerConfirm

* url : https://brocadesoar.cn/callme/seller_man/registerConfirm

* type : POST

* fields :

```
{
    "session_id" : SESSION_ID,
    "register_id" : REGISTER_ID,
}
```

* return : SUCCESS_INFO

## 修改店铺信息和商品信息

### updateSellerPicture 更新店铺图片

* url : https://brocadesoar.cn/callme/product_man/updateSellerPicture

* type : POST(Upload a file)

* filename of upload : picture

* fields :

```
{
    "session_id" : SESSION_ID
}
```

* return : SUCCESS_INFO

### updateSellerName 更新店铺名字，考虑到审核问题暂不开放

### update 新增或和修改商品信息

* url : https://brocadesoar.cn/callme/product_man/update

* type : POST(Upload a file)

* filename of upload : picture

* fields :

```
{
    "session_id" : SESSION_ID,
    // "product_id" : PRODUCT_ID,
    "product_name" : PRODUCT_NAME,
    "product_total_fee" : PRODUCT_TOTAL_FEE,
    "product_inventory" : PRODUCT_INVENTORY,
    "product_label" : PRODUCT_LABEL,
    "is_picture_changed" : IS_PICTURE_CHANGED,
}
```

* return : SUCCESS_INFO

## Pay 交易

### unifiedorder 统一下单

* url : https://brocadesoar.cn/callme/wx_pay/unifiedorder

* type : POST

* fields : 

```
{
    "session_id" : SESSION_ID,
    "product_id" : STRING_OF_PRODUCT_ID_ARRAY_WITH_COMMA,
    "product_qu" : STRING_OF_PRODUCT_QU_ARRAY_WITH_COMMA,
}
```

* return : 

```
{
    "appid" : APPID,
    "nonceStr" : NONCESTR,
    "package" : PACKAGE,
    "paySign" : PAYSIGN,
    "signType" : "MD5",
    "timeStamp" : TIMESTAMP,
}
```

### list 获取订单列表

### close 关闭订单

### cash 提现

## Audio 语言合成接口

### getAudioUrl 获得合成语音url

* url : https://brocadesoar.cn/callme/bd_api/getAudioUrl/TEXT/SPD/PIT/VOL/PER

* type : PATHINFO

* 解释:
    1. TEXT 需要合成的语句
    2. SPD 语速 0 - 9
    3. PIT 语调 0 - 9
    4. VOL 音量 0 - 15
    5. PER 发音人 0普通女生, 1普通男生, 3情感合成-度逍遥, 4情感合成-度丫丫
