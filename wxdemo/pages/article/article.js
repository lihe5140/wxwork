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
    // var that = this
    this.setData({
      art_wxid: options.wx_id
    })
    this.getarticlelist()
  },
  getarticlelist: function() {
    var that = this;
    wx.request({
      url: host + 'article',
      header: {
        'content-type': 'application/json'
      },
      data: {
        num: '6'
      },
      method: 'get', //上传方式
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