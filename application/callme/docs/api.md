# 取个号 API

## WebSocket

url: wss://brocadesoar.cn:4431

数据格式：JSON字符串

握手前，登录到服务端获取session\_id并存储

握手后，由小程序发送交互的第一条消息：

```
{
    "session_id" : "SESSION_ID",
}
```

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
}

## Pay

### unifiedorder 统一下单

* url : https://brocadesoar.cn/callme/wx_pay/unifiedorder

* type : POST

* fields : {
    "session\_id" : SESSION\_ID,
    "product\_id" : PRODUCT\_ID,
}

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
