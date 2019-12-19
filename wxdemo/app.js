//app.js
const app = getApp()
const TOKEN = 'token'
const OPENID = 'openid'
App({
  globalData: {
    token: '',
    openid: '',
    userInfo: ''
  },
  onLaunch: function() {
    // 1.先从缓冲中取出token
    const token = wx.getStorageSync(TOKEN)
    // 2.判断token是否有值
    if (token && token.length !== 0) { // 已经有token,验证token是否过期
      this.check_token(token) // 验证token是否过期
    } else { // 没有token, 进行登录操作
      this.login()
    }
  },
  check_token(token) {
    console.log('执行了验证token操作')
    wx.request({
      url: 'http://172.20.0.241:81/checktoken',
      method: 'post',
      header: {
        token: token
      },
      success: (res) => {
        console.log(res.data)
        if (res.data.status !== 10303) {
          console.log('token有效')
          this.globalData.token = token;
        } else {
          console.log('token已过期')
          this.login()
        }
      },
      fail: function(err) {
        console.log(err)
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
        console.log(code);
        // 2.将code发送给服务器
        wx.request({
          url: 'http://172.20.0.241:81/login',
          method: 'post',
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
            getUserInfo()
          }
        })
      }
    })
  },
  getUserInfo() {
    // console.log(e)
    app.globalData.userInfo = e.detail.userInfo
    this.setData({
      userInfo: e.detail.userInfo,
      hasUserInfo: true
    })
  }
})