// pages/article/article.js
const app = getApp()
var utilJS = require("../../utils/util.js");
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
    num: '',
    page: '',
    list: {},
    art_wxid: '' //公众号id
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    var that = this
    that.setData({
      art_wxid: options.wx_id
    })
    wx.request({
      url: host + 'article',
      header: {
        'content-type': 'application/json'
      },
      data: {
        // num: '6',
        art_wxid: options.wx_id
      },
      method: 'GET', //上传方式
      success: function(res) {
        console.log(res)
        that.setData({
          list: res.data.data,
        })
      }
    })
  },
  getarticlelist: function() {
    var that = this;
    wx.request({
      url: host + 'article',
      header: {
        'content-type': 'application/json'
      },
      data: {},
      method: 'GET', //上传方式
      success: function(res) {
        that.setData({
          list: res.data.data,
        })
      }
    })
  },
  /*********跳转留言页面 *******/
  gomsg: function(event) {
    var index = event.currentTarget.dataset.index;
    var article_info = this.data.list[index]
    var art_id = article_info.art_id
    var art_title = article_info.art_title
    var art_wxid = article_info.art_wxid
    wx.navigateTo({
      url: '../message/message?art_id=' + art_id + '&art_title=' + art_title + '&art_wxid=' + art_wxid
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
    var that = this
    wx.request({
      url: host + 'article',
      header: {
        'content-type': 'application/json'
      },
      data: {
        // num: '6',
        art_wxid: that.data.art_wxid
      },
      method: 'GET', //上传方式
      success: function(res) {
        console.log(res)
        if (res.statusCode == 200) {
          if (res.data.status == 1) {
            that.setData({
              list: res.data.data,
            })
          } else {
            wx.showToast({
              title: '作者还没有发表文章哦',
              icon: 'none',
            })
          }
        } else {
          wx.showModal({
            title: '服务器错误',
            content: 'none',
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
      complete: function() {
        wx.hideNavigationBarLoading(); //完成停止加载 
        // 动态设置导航条标题 
        wx.stopPullDownRefresh(); //停止下拉刷新 
      }
    })

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