// pages/message/message.js
Page({

  /**
   * 页面的初始数据
   */
  data: {

  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    var that = this
    wx.request({
      url: '',
      header: {
        'content-type': 'application/json'
      },
      method: 'post', //上传方式
      success: function(res) {
        console.log(res.data)
        // that.setData(
        //   {
        //     list: res.data.result
        //   }
        // )
      }
    })
  },
  writemessage: function () {
    wx.navigateTo({
      url: '../writemsg/writemsg'
    })
  },
  //获取公众号信息
  // getGongInfo: function() {
  //   var that = this;
  //   wx.request({
  //     url: 'http://api.wxapi.com/message/index', //获取公众号信息
  //     data: {},
  //     header: {
  //       'content-type': 'application/json' // 数据格式（默认值）
  //     },
  //     method: 'post', //上传方式
  //     success: function(res) { //回调成功
  //       console.log(res.data)
  //       if (res.statusCode == 200) {
  //         if (res.status == '1') {
  //           var posts_content = res.data;
  //           console.log(posts_content)
  //           that.setData({
  //             gongMessage: res.data.content,
  //           })
  //         } else {
  //           wx.showToast({
  //             title: '获取失败',
  //             icon: 'none',
  //           })
  //         }
  //       } else {
  //         wx.showModal({
  //           title: '服务器错误',
  //           content: 'none',
  //         })
  //       }
  //     },
  //     //回调失败
  //     fail: function(res) {
  //       console.log(res.errMsg)
  //       wx.showToast({
  //         title: '联网失败',
  //         icon: 'fail',
  //       })
  //     },
  //   })
  // },

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