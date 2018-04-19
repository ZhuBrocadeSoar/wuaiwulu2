//index.js
//获取应用实例
var app = getApp()
Page({
  data: {
    motto: 'Hello World',
    userInfo: {}
  },
  //事件处理函数
  bindViewTap: function() {
    wx.navigateTo({
      url: '../logs/logs'
    })
  },

  formSub: function(res){
    console.log(res)
    console.log(res.detail.formId)
    wx.request({
      url: 'https://brocadesoar.cn/callme/wx_session/debug_msg',
      data: {
        formId: res.detail.formId,
        session_id: wx.getStorageSync('session_id'),
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
// 发送wss消息
  wsstest: function () {
    console.log('ttt')
    wx.sendSocketMessage({
      data: ['hello server'],
    })
  },
  

  onLoad: function () {
    console.log('onLoad')
    var that = this
    //调用应用实例的方法获取全局数据
    app.getUserInfo(function(userInfo){
      //更新数据
      that.setData({
        userInfo:userInfo
      })
    })
  }
})
