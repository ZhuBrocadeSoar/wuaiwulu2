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
        wx.request({
          url: 'https://brocadesoar.cn/callme/wx_pay/unifiedorder',
          data: {
            session_id: wx.getStorageSync('session_id'),
            product_id: '1',
            product_qu:'2',
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
    wxpaytest6: function () {
      wx.request({
        url: 'https://brocadesoar.cn/callme/wx_pay/unifiedorder',
        data: {
          session_id: wx.getStorageSync('session_id'),
          product_id: '1,3',
          product_qu: '2,1',
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
  shop2: function(){
    wx.request({
      url: 'https://brocadesoar.cn/callme/wx_session/shop',
      data: {
        session_id: wx.getStorageSync('session_id'),
        seller_id: 1,
      },
      method: 'POST',
      dataType: 'json',
      responseType: 'text',
      success: function(res) {
        console.log(res.data)
      },
      fail: function(res) {},
      complete: function(res) {},
    })
  },
  contenttest: function(){
    wx.request({
      url: 'https://brocadesoar.cn/callme/wx_session/content',
      data: {
        session_id: wx.getStorageSync('session_id'),
      },
      method: 'POST',
      dataType: 'json',
      responseType: 'text',
      success: function(res) {
        console.log(res.data)
      },
      fail: function(res) {},
      complete: function(res) {},
    })
  },
  historytest: function(){
    wx.request({
      url: 'https://brocadesoar.cn/callme/wx_session/history',
      data: {
        session_id: wx.getStorageSync('session_id')
      },
      header: {},
      method: 'GET',
      dataType: 'json',
      responseType: 'text',
      success: function(res) {
        console.log(res.data)
      },
      fail: function(res) {},
      complete: function(res) {},
    })
  },
  updatetest1: function(){
    wx.chooseImage({
      count: 1,
      sizeType: [],
      sourceType: [],
      success: function(res) {
        console.log(res)
        wx.uploadFile({
          url: 'https://brocadesoar.cn/callme/product_man/update',
          filePath: res.tempFilePaths[0],
          name: 'picture',
          header: {},
          formData: {
            session_id: wx.getStorageSync('session_id'),
            product_id: 3,
            product_name: '招牌3',
            product_total_fee: 15,
            product_inventory: 4,
            product_label: '招牌菜',
            is_picture_changed: 'false',
          },
          success: function (res) {
            console.log(res.data)
          },
          fail: function (res) { },
          complete: function (res) { },
        })
      },
      fail: function(res) {},
      complete: function(res) {},
    })
               
  },

  updatetest2: function(){
    wx.chooseImage({
      count: 1,
      sizeType: [],
      sourceType: [],
      success: function(res) {
        wx.uploadFile({
          url: 'https://brocadesoar.cn/callme/product_man/updateSellerPicture',
          filePath: res.tempFilePaths[0],
          name: 'picture',
          header: {},
          formData: {
            session_id: wx.getStorageSync('session_id')
          },
          success: function(res) {
            console.log(res.data)
          },
          fail: function(res) {},
          complete: function(res) {},
        })
      },
      fail: function(res) {},
      complete: function(res) {},
    })
  },

  updatetest3: function(){
    wx.request({
      url: 'https://brocadesoar.cn/callme/product_man/updateSellerName',
      data: {
        session_id: wx.getStorageSync('session_id'),
        name: 'tester1',
      },
      header: {},
      method: 'POST',
      dataType: 'json',
      responseType: 'text',
      success: function(res) {
        console.log(res.data)
      },
      fail: function(res) {},
      complete: function(res) {},
    })
  },

  updatetest4: function(){
    wx.request({
      url: 'https://brocadesoar.cn/callme/product_man/remove',
      data: {
        session_id: wx.getStorageSync('session_id'),
        product_id: 2,
      },
      header: {},
      method: 'POST',
      dataType: 'json',
      responseType: 'text',
      success: function(res) {
        console.log(res.data)
      },
      fail: function(res) {},
      complete: function(res) {},
    })
  },

  dininghalltest: function(){
    wx.request({
      url: 'https://brocadesoar.cn/callme/wx_session/diningHall',
      data: {
        session_id: wx.getStorageSync('session_id'),
        dining_hall_id: 1,
      },
      header: {},
      method: 'POST',
      dataType: 'json',
      responseType: 'text',
      success: function(res) {
        console.log(res.data)
      },
      fail: function(res) {},
      complete: function(res) {},
    })
  },

  registertest: function(){
    wx.request({
      url: 'https://brocadesoar.cn/callme/seller_man/register',
      data: {
        session_id: wx.getStorageSync('session_id'),
        owner_tell: '17857021969',
        owner_name: '朱锦腾',
        seller_name: '朱锦腾的美食',
        dining_hall_id: 1,
      },
      header: {},
      method: 'POST',
      dataType: 'json',
      responseType: 'text',
      success: function(res) {
        console.log(res.data)
      },
      fail: function(res) {},
      complete: function(res) {},
    })
  },

  registerlisttest: function(){
    wx.request({
      url: 'https://brocadesoar.cn/callme/seller_man/registerList',
      data: {
        session_id: wx.getStorageSync('session_id'),
        // pass_state: 'submited', //可选的
      },
      header: {},
      method: 'POST',
      dataType: 'json',
      responseType: 'text',
      success: function(res) {
        console.log(res.data)
      },
      fail: function(res) {},
      complete: function(res) {},
    })
  },

  registerconfirmtest: function(){
    wx.request({
      url: 'https://brocadesoar.cn/callme/seller_man/registerConfirm',
      data: {
        session_id: wx.getStorageSync('session_id'),
        register_id: 1
      },
      header: {},
      method: 'POST',
      dataType: 'json',
      responseType: 'text',
      success: function(res) {
        console.log(res.data)
      },
      fail: function(res) {},
      complete: function(res) {},
    })
  },

  debugdininghalladdtest: function(){
    wx.request({
      url: 'https://brocadesoar.cn/callme/seller_man/debugDiningHallAdd',
      data: {
        session_id: wx.getStorageSync('session_id')
      },
      header: {},
      method: 'POST',
      dataType: 'json',
      responseType: 'text',
      success: function(res) {
        console.log(res.data)
      },
      fail: function(res) {},
      complete: function(res) {},
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
