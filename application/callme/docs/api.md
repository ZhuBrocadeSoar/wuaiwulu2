# 取个号 API

## 流

![流](http://on-img.com/chart_image/5afbd4c6e4b0026862677068.png)

## WebSocket

url: wss://brocadesoar.cn:4431

数据格式：JSON字符串

握手前，登录到服务端获取session\_id并存储

握手后，由小程序发送交互的第一条消息：

* fields : {
    "session\_id" : "SESSION\_ID",
}

如果数据符合要求则保持连接，并确保25秒的心跳

如果数据不符合要求连接被服务端主动关闭

超过心跳时间服务端主动关闭连接

## Session

### login 登录

* url : https://brocadesoar.cn/callme/wx_session/index

* type : POST

* fields : {
    "code" : "CODE",
}

* return : {
    "session\_id" : SESSION\_ID,
    "is\_seller" : IS\_SELLER,
}

* Cases :

`IS\_SELLER (string)`

| 值|说明|
|---|---|
|true|商家列表有该id|
|false|商家列表没有该id|

### content 获取第一个渲染用数据包

* url : https://brocadesoar.cn/callme/wx_session/content

* type : POST

* fields : {
    "session\_id" : SESSION\_ID,
}

* return : {
    "ads\_img\_url" : \[
        URL,
        URL,
        URL,
    \],
    "history" : \[
        {
            "product\_id" : PRODUCT\_ID,
            "product\_img\_url" : PRODUCT\_IMG\_URL,
            "product\_name" : PRODUCT\_NAME,
        },
        {},
        {},
        {},
        {},
    \],
    "random" : \[
        {
            "product\_id" : PRODUCT\_ID,
            "product\_img\_url" : PRODUCT\_IMG\_URL,
            "product\_name" : PRODUCT\_NAME,
            "user\_img\_url" : USER\_IMG\_URL,
        },
        {},
        {},
        {},
        {},
        {},
    \]
}

### picture 获取一张图片

* url : https://brocadesoar.cn/callme/wx_session/picture

* type : GET

* fields : {
    "pic\_id" : PIC\_ID,
}

* return : 图片资源 或 {
    "errmsg" : "No that Picture",
}

> todo 商家入驻相关
## Signin

### index 商家发出入驻申请的请求

* url : https://brocadesoar.cn/callme/signin/index

* type : POST

* fields : {
    "session\_id" : SESSION\_ID,
    ...other infomation...
}

* return : {
    "errmsg" : "ok",
} 或 {
    "errmsg" : ERRMSG,
}

### list 管理员拉取申请列表

* url : https://brocadesoar.cn/callme/signin/list

* type : POST

* fields : {
    "session\_id" : SESSION\_ID,
}

* return : {
    "list" : \[
        {
            ...
        }, 
        {
            ...
        }, 
        ...
    \],
}

### auth 管理员授权申请

* url : https://brocadesoar.cn/callme/signin/auth

* type : POST

* fields : {
    "session\_id" : SESSION\_ID,
    "seller\_id" : SELLER\_ID,
}

* return : {
    "errmsg" : "ok",
}

> todo 商家商品管理相关
## ProductMan

### list 获取商品列表

* url : https://brocadesoar.cn/callme/product_man/list

* type : POST

* fields : {
    "session\_id" : session\_ID,
}

### modify 修改

* url : https://brocadesoar.cn/callme/product_man/modify

* type : POST

* fields : {
    "session\_id" : SESSION\_ID,
    ...other infomation...
}

### putOn 上架

### putOff 下架

> todo 交易相关
## Pay

### unifiedorder 统一下单

* url : https://brocadesoar.cn/callme/wx_pay/unifiedorder

* type : POST

* fields : {
    "session\_id" : SESSION\_ID,
    "product\_id" : PRODUCT\_ID,
}

### list 获取订单列表

### close 关闭订单

### cash 提现

## Audio

### getAudioUrl 获得合成语音url

* url : https://brocadesoar.cn/callme/bd_api/getAudioUrl/TEXT/SPD/PIT/VOL/PER

* type : PATHINFO

* 解释:
    1. TEXT 需要合成的语句
    2. SPD 语速 0 - 9
    3. PIT 语调 0 - 9
    4. VOL 音量 0 - 15
    5. PER 发音人 0普通女生, 1普通男生, 3情感合成-度逍遥, 4情感合成-度丫丫
