// pages/article/article.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
    list: [],
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    var that = this
    wx.request({
      url: 'http://172.20.0.241:81/article',
      header: {
        'content-type': 'application/json'
      },
      method: 'get', //上传方式
      success: function(res) {
        console.log(res.data)
        var posts_content = res.data;
        console.log(posts_content.data)
        that.setData({
          list: posts_content.data,
        })
      }
    })
  },
  /*********跳转留言页面 *******/
  gomsg: function() {
    wx.navigateTo({
      url: '../message/message'
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