//app.js
App({
  onLaunch: function () {
    //调用API从本地缓存中获取数据
    var logs = wx.getStorageSync('logs') || []
    logs.unshift(Date.now())
    wx.setStorageSync('logs', logs)
  },
  getUserInfo:function(cb){
    var that = this
    if(this.globalData.userInfo){
      typeof cb == "function" && cb(this.globalData.userInfo)
    }else{
      //调用登录接口
      wx.login({
        timeout: 1000,
        success: function (d) {
          // console.log(d.code)
          wx.getUserInfo({
            success: function (res) {
              that.globalData.userInfo = res.userInfo
              // console.log(that.globalData.userInfo)
              wx.request({
                url: 'https://brocadesoar.cn/callme/wx_session/index',
                data: {
                  code: d.code,
                },
                header: {},
                method: 'GET',
                dataType: 'json',
                responseType: 'text',
                success: function(res) {
                  console.log(res.data)
                  console.log(res.data.session_id)
                  wx.setStorageSync('session_id', res.data.session_id)
                  console.log(wx.getStorageSync('session_id'))
                },
                fail: function(res) {},
                complete: function(res) {},
              })
              typeof cb == "function" && cb(that.globalData.userInfo)
            }
          })
         
        }
      })
    }
  },
  globalData:{
    userInfo:null
  }
})