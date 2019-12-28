// pages/message/message.js
const app = getApp()
var utilJS = require("../../utils/util.js")
const host = utilJS.host
Page({

  /**
   * 页面的初始数据
   */
  data: {
    buttonDisabled: false,
    modalHidden: true,
    userInfo: {},
    hasUserInfo: false,
    //判断小程序的组件在当前版本是否可用
    canIUse: wx.canIUse('button.open-type.getUserInfo'),
    m_artid: '',
    m_artitle: '',
    m_wxid: '',
    m_msg: {}
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    var that = this;
    console.log("留言页面的文章编号为:" + options.art_id)
    that.setData({
      m_artid: options.art_id,
      m_artitle: options.art_title,
      m_wxid: options.art_wxid
    })
    //获取已精选留言内容
    that.getChooseCotent()
    // 如果获取到用户信息就存储
    if (app.globalData.userInfo) {
      console.log("用户信息存在")
      this.setData({
        userInfo: app.globalData.userInfo,
        hasUserInfo: true
      })
      wx.setStorageSync('username', that.data.userInfo.nickName)
      wx.setStorageSync('headpath', that.data.userInfo.avatarUrl)
      // console.log("在index页面全局app1中获取到的用户信息为：" + that.data.userInfo.nickName + " " + that.data.userInfo.avatarUrl);
    } else if (this.data.canIUse) {
      // 由于 getUserInfo 是网络请求，可能会在 Page.onLoad 之后才返回
      // 所以此处加入 callback 以防止这种情况
      app.userInfoReadyCallback = res => {
        // console.log("用户名2：" + res.userInfo.nickName + " " + res.userInfo.avatarUrl)
        this.setData({
          userInfo: res.userInfo,
          hasUserInfo: true
        })
        wx.setStorageSync('username', that.data.userInfo.nickName)
        wx.setStorageSync('headpath', that.data.userInfo.avatarUrl)
        // console.log("在index页面全局app2中获取到的用户信息为：" + that.data.userInfo.nickName + " " + that.data.userInfo.avatarUrl);
      }
    } else {
      console.log("用户信息不存在")
      // 没有获取到用户信息就发起授权窗口
      wx.getUserInfo({
        success: res => {
          // console.log("用户名3：" + res.userInfo.nickName + " " + res.userInfo.avatarUrl)
          app.globalData.userInfo = res.userInfo
          this.setData({
            userInfo: res.userInfo,
            hasUserInfo: true
          })
          wx.setStorageSync('username', that.data.userInfo.nickName)
          wx.setStorageSync('headpath', that.data.userInfo.avatarUrl)
          // console.log("在index页面全局app3中获取到的用户信息为：" + that.data.userInfo.nickName + " " + that.data.userInfo.avatarUrl);
        },
      })
    }

  },
  //授权弹窗
  //点击按钮授权
  getUserInfo: function(e) {
    var that = this;
    if (e.detail.userInfo) {
      console.log(e)
      app.globalData.userInfo = e.detail.userInfo
      this.setData({
        userInfo: e.detail.userInfo,
        hasUserInfo: true
      })
      wx.setStorageSync('username', that.data.userInfo.nickName)
      wx.setStorageSync('headpath', that.data.userInfo.avatarUrl)
      console.log("在index页面临时授权中获取到的用户信息为：" + that.data.userInfo.nickName + " " + that.data.userInfo.avatarUrl);
    } else {
      console.log('用户取消授权');
      return;
    }
  },
  //获取已精选留言内容
  getChooseCotent: function() {
    var that = this;
    wx.request({
      url: host + 'msg', //获取已精选留言内容
      data: {
        m_artid: that.data.m_artid, //文章编号 
        m_wxid: that.data.art_wxid, //公众号 id
        // openid: wx.getStorageSync('openid'), //用户唯一标识
      },
      header: {
        'content-type': 'application/json' // 数据格式（默认值）
      },
      method: 'GET', //上传方式
      success: function(res) { //回调成功
        console.log(res.data)
        if (res.statusCode == 200) {
          if (res.data.status == 1) {
            var posts_message = res.data.data;
            console.log(posts_message)
            if (posts_message == null) {
              wx.showToast({
                title: '还没有用户留言',
                icon: 'none',
              })
            } else {
              that.setData({
                msg: posts_message
              })
            }
          } else {
            wx.showToast({
              title: '获取失败',
              icon: 'none',
            })
          }
        } else {
          wx.showToast({
            title: '服务器错误',
            icon: 'none',
          })
        }
      },
      //回调失败
      fail: function(res) {
        console.log(res)
        wx.showToast({
          title: '联网失败',
          icon: 'fail',
        })
      },
    })
  },
  writemessage: function() {
    wx.navigateTo({
      url: '../writemsg/writemsg?m_artid=' + this.data.m_artid + '&m_wxid=' + this.data.m_wxid + '&m_artitle=' + this.data.m_artitle
    })
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */

  onReady: function() {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function() {

  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function() {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function() {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function() {
    wx.showNavigationBarLoading() //在标题栏中显示加载
    var that = this;
    that.getChooseCotent();

    //完成停止加载
    wx.hideNavigationBarLoading();

    //停止下拉刷新 
    wx.stopPullDownRefresh();    
   
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function() {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function() {

  }
})