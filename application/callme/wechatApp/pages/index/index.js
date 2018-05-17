//index.js
//获取应用实例
var md5 = require('../../utils/md5.js');
var app = getApp();
Page({
  data: {
    motto: 'Hello World',
    userInfo: {}
  },
  //事件处理函数
  bindViewTap: function () {
    wx.navigateTo({
      url: '../logs/logs'
    });
  },

  formSub: function (res) {
    console.log(res);
    console.log(res.detail.formId);
    wx.request({
      url: 'https://brocadesoar.cn/callme/wx_session/debug_msg',
      data: {
        formId: res.detail.formId,
        session_id: wx.getStorageSync('session_id')
      },
      header: {},
      method: 'POST',
      dataType: 'json',
      responseType: 'text',
      success: function (res) {
        console.log(res.data);
      }
      // fail: function (res) { },
      // complete: function (res) { }
    });
  },
  // 发送wss消息
  wsstest: function () {
    console.log('ttt');
    wx.sendSocketMessage({
      data: 'hello server',
      success: function(res) {
        console.log(res);
      },
        fail: function(res) {
        console.log(res);
      },
      complete: function (res) {
        console.log(res);
      }
    });
  },

    wxpaytest1: function (){
      console.log(md5('33333'))
        console.log("dd");
        wx.request({
          url: 'https://brocadesoar.cn/callme/wx_pay/unifiedorder',
          data: {
            session_id: wx.getStorageSync('session_id'),
            product_id: 1,
          },
          header: {},
          method: 'POST',
          dataType: 'json',
          responseType: 'text',
          success: function(res) {
            console.log(res.data);
            if(typeof(res.data.errmsg) == 'undefined'){
              // 成功
              wx.requestPayment({
                timeStamp: res.data.timeStamp,
                nonceStr: res.data.nonceStr,
                package: res.data.package,
                signType: res.data.signType,
                paySign: res.data.paySign,
                success: function (res) {
                  console.log('paysuccess');
                },
                fail: function (res) {
                  console.log(res);
                },
                complete: function (res) { },
              })
            }else{
              // 失败
              console.log(res.data);
            }
            
          },
          fail: function(res) {},
          complete: function(res) {},
        })
    },
    wxpaytest2: function () {
      console.log(md5('33333'))
      console.log("dd");
      wx.request({
        url: 'https://brocadesoar.cn/callme/wx_pay/unifiedorder',
        data: {
          session_id: wx.getStorageSync('session_id'),
          product_id: 2,
        },
        header: {},
        method: 'POST',
        dataType: 'json',
        responseType: 'text',
        success: function (res) {
          console.log(res.data);
          if (typeof (res.data.errmsg) == 'undefined') {
            // 成功
            wx.requestPayment({
              timeStamp: res.data.timeStamp,
              nonceStr: res.data.nonceStr,
              package: res.data.package,
              signType: res.data.signType,
              paySign: res.data.paySign,
              success: function (res) {
                console.log('paysuccess');
              },
              fail: function (res) {
                console.log(res);
              },
              complete: function (res) { },
            })
          } else {
            // 失败
            console.log(res.data);
          }

        },
        fail: function (res) { },
        complete: function (res) { },
      })
    },
    wxpaytest3: function () {
      console.log(md5('33333'))
      console.log("dd");
      wx.request({
        url: 'https://brocadesoar.cn/callme/wx_pay/unifiedorder',
        data: {
          session_id: wx.getStorageSync('session_id'),
          product_id: 3,
        },
        header: {},
        method: 'POST',
        dataType: 'json',
        responseType: 'text',
        success: function (res) {
          console.log(res.data);
          if (typeof (res.data.errmsg) == 'undefined') {
            // 成功
            wx.requestPayment({
              timeStamp: res.data.timeStamp,
              nonceStr: res.data.nonceStr,
              package: res.data.package,
              signType: res.data.signType,
              paySign: res.data.paySign,
              success: function (res) {
                console.log('paysuccess');
              },
              fail: function (res) {
                console.log(res);
              },
              complete: function (res) { },
            })
          } else {
            // 失败
            console.log(res.data);
          }

        },
        fail: function (res) { },
        complete: function (res) { },
      })
    },
    wxpaytest4: function () {
      console.log(md5('33333'))
      console.log("dd");
      wx.request({
        url: 'https://brocadesoar.cn/callme/wx_pay/unifiedorder',
        data: {
          session_id: wx.getStorageSync('session_id'),
          product_id: 4,
        },
        header: {},
        method: 'POST',
        dataType: 'json',
        responseType: 'text',
        success: function (res) {
          console.log(res.data);
          if (typeof (res.data.errmsg) == 'undefined') {
            // 成功
            wx.requestPayment({
              timeStamp: res.data.timeStamp,
              nonceStr: res.data.nonceStr,
              package: res.data.package,
              signType: res.data.signType,
              paySign: res.data.paySign,
              success: function (res) {
                console.log('paysuccess');
              },
              fail: function (res) {
                console.log(res);
              },
              complete: function (res) { },
            })
          } else {
            // 失败
            console.log(res.data);
          }

        },
        fail: function (res) { },
        complete: function (res) { },
      })
    },
    wxpaytest5: function () {
      console.log(md5('33333'))
      console.log("dd");
      wx.request({
        url: 'https://brocadesoar.cn/callme/wx_pay/unifiedorder',
        data: {
          session_id: wx.getStorageSync('session_id'),
          product_id: 5,
        },
        header: {},
        method: 'POST',
        dataType: 'json',
        responseType: 'text',
        success: function (res) {
          console.log(res.data);
          if (typeof (res.data.errmsg) == 'undefined') {
            // 成功
            wx.requestPayment({
              timeStamp: res.data.timeStamp,
              nonceStr: res.data.nonceStr,
              package: res.data.package,
              signType: res.data.signType,
              paySign: res.data.paySign,
              success: function (res) {
                console.log('paysuccess');
              },
              fail: function (res) {
                console.log(res);
              },
              complete: function (res) { },
            })
          } else {
            // 失败
            console.log(res.data);
          }

        },
        fail: function (res) { },
        complete: function (res) { },
      })
    },
  blueprint: function(){
    wx.getBluetoothDevices({
      success: function(res) {
        console.log(res);
        var ind;
        for(ind in res.devices){
          console.log('debug : ' + ind);
          if(res.devices[ind].name == 'BlueTooth Printer'){
            wx.setStorageSync('printer', res.devices[ind]);
            // wx.stopBluetoothDevicesDiscovery({
            //   success: function(res) {
            //     console.log('找到打印机了');
            //     console.log(wx.getStorageSync('printer').deviceId);
            //     console.log(wx.getBLEDeviceServices({
            //       deviceId: wx.getStorageSync('printer').deviceId,
            //       success: function(res) {
            //         console.log(res);
            //       },
            //     }));
            //     // wx.getBluetoothAdapterState({
            //     //   success: function(res) {
            //     //     console.log(res);
            //     //   },
            //     // });
            //   },
            // });
          }else{
            console.log('DEBUG : ' + ind);
          }
        }
        console.log(wx.getStorageSync('printer'));
        wx.createBLEConnection({
          deviceId: wx.getStorageSync('printer').deviceId,
          success: function (res) {
            console.log(res);
          },
          fail: function (res) {
            console.log(res);
            if(res.errCode == 10003){
              console.log('进行重新搜索再次连接');
              wx.stopBluetoothDevicesDiscovery({
                success: function(res) {},
              });
              wx.startBluetoothDevicesDiscovery({
                success: function(res) {},
              });
              wx.createBLEConnection({
                deviceId: wx.getStorageSync('printer').deviceId,
                success: function(res) {
                  console.log(res);
                },
                fail: function(res){
                  console.log(res);
                },
              });
            }
          },
        });
      },
    })
  },
  onLoad: function () {
    console.log('onLoad');
  }
});
