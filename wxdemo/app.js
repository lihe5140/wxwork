//app.js
var utilJS = require("/utils/util.js");
const TOKEN = 'token'
const OPENID = 'openid'
const host = utilJS.host
App({
  globalData: {
    token: '',
    openid: '',
    userInfo: null,
    host:host
  },
  onLaunch: function() {
    // 1.先从缓冲中取出token
    console.log(host)
    const token = wx.getStorageSync(TOKEN)
    // 2.判断token是否有值
    if (token && token.length !== 0) { // 已经有token,验证token是否过期
      this.check_token(token) // 验证token是否过期
    } else { // 没有token, 进行登录操作
      this.login()
    }
    //查看当前用户是否已经授权
    wx.getSetting({
      success: res => {
        if (res.authSetting['scope.userInfo']) {
          // 已经授权，可以直接调用 getUserInfo 获取头像昵称，不会弹框
          wx.getUserInfo({
            success: res => {
              // 授权成功后，直接将信息传到全局变量中
              this.globalData.userInfo = res.userInfo
              // 由于 getUserInfo 是网络请求，可能会在 Page.onLoad 之后才返回
              // 所以此处加入 callback 以防止这种情况
              if (this.userInfoReadyCallback) {
                this.userInfoReadyCallback(res)
              }
            }
          })
        } else {
          return;
        }
      }
    })
  },
  check_token(token) {
    console.log('执行了验证token操作')
    wx.request({
      url: host+'checktoken',
      method: 'POST',
      header: {
        token: token
      },
      success: (res) => {
        // console.log(res.data)
        if (res.data.status !== 10303) {
          // console.log('token有效')
          this.globalData.token = token;
          const openid = wx.getStorageSync(OPENID)
          this.globalData.openid = openid;
        } else {
          // console.log('token已过期')
          this.login()
        }
      },
      fail: function(err) {
        // console.log(err)
      }
    })
  },
  login() {
    console.log('执行了登录操作')
    wx.login({
      // code只有5分钟的有效期
      success: (res) => {
        // 1.获取code
        const code = res.code;
        // console.log(code);
        // 2.将code发送给服务器
        wx.request({
          url: host +'login',
          method: 'POST',
          data: {
            code
          },
          success: (res) => {
            // 1.取出token
            console.log(res)
            const token = res.data.token;
            const openid = res.data.openid;
            // 2.将token保存在globalData中
            this.globalData.token = token;
            this.globalData.openid = openid;
            // 3.进行本地存储
            wx.setStorageSync(TOKEN, token)
            wx.setStorageSync(OPENID, openid)
          }
        })
      }
    })
  },

})