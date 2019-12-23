// pages/writemsg/writemsg.js
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
    condition: true,
    m_artid: '',
    m_artitle: '',
    m_wxid: '',
    m_name: '',
    m_avatar: '',
    m_msg:'',
    m_msgs: {}
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    var that = this;
    console.log("填写留言的文章编号为:" + options.m_artid)
    that.setData({
      m_name: wx.getStorageSync('username'),
      m_avatar: wx.getStorageSync('headpath'),
      m_artid: options.m_artid,
      m_wxid: options.m_wxid,
      m_artitle: options.m_artitle
    }),
    this.getusermsg()

  },
  //获取留言本文域信息
  getmessages: function(e) {
    this.setData({
      m_msg: e.detail.value,
    })
  },
  //服务推送通知
  orderSign: function(e) {
    var that = this;
    console.log(e)
    var fId = e.detail.formId;
    console.log('formid' + fId)
    that.setData({
      formId: fId
    })
  },
  //点击提交留言
  btnmessage: function(e) {
    var that = this;
    console.log(e)
    console.log("提交的留言信息为" + that.data.m_msg)
    if (that.data.m_msg == "") {
      wx: wx.showToast({
        title: '请输入留言内容...',
        icon: 'none',
      })
    }
    else {
      wx: wx.showToast({
        title: '正在发布',
        icon: 'loading',
      })
      that.addmsg()
    }
  },
  // 获取此用户留言
  getusermsg: function() {
    var that = this;
    wx.request({
      url: host + 'getusermsg', //获取已精选留言内容
      data: {
        m_artid: that.data.m_artid, //文章ID
        m_uid: wx.getStorageSync('uid')
      },
      header: {
        'content-type': 'application/json' // 数据格式（默认值）
      },
      method: 'GET', //上传方式
      success: function(res) { //回调成功
        console.log(res)
        if (res.statusCode == 200) {
          if (res.data.status == 1) {
            
            // wx: wx.showToast({
            //   title: '获取留言成功',
            //   icon: 'success',
            // })
            that.setData({
              condition: false,
              m_msgs: res.data.data, //留言内容
              m_name: that.data.m_name, //用户名
              m_avatar: that.data.m_avatar, //用户头像
            })
          } else {
            wx.showToast({
              title: '获取留言失败',
              icon: 'none',
            })
          }
        } else {
          wx.showToast({
            title: '服务器错误',
            icon: 'none',
          })
        }
      }
    })
  },
  // 用户留言
  addmsg: function() {
    var that = this;
    wx.request({
      url: host + 'msg', //获取已精选留言内容
      data: {
        m_msg: that.data.m_msg, //留言内容
        m_artid: that.data.m_artid, //文章ID
        m_name: that.data.m_name, //用户名
        m_avatar: that.data.m_avatar, //用户头像
        m_wxid: that.data.m_wxid, //公众号ID
        m_uid: wx.getStorageSync('uid')
      },
      header: {
        'content-type': 'application/json' // 数据格式（默认值）
      },
      method: 'POST', //上传方式
      success: function(res) { //回调成功
        console.log(res)
        if (res.statusCode == 200) {
          if (res.data.status == 1) {
            that.setData({
              condition: false,
              m_msg: '' ,//留言内容
              m_msgs: that.data.m_msgs.concat(that.data)
            })
            wx: wx.showToast({
              title: '留言成功',
              icon: 'success',
            })
          } else {
            wx.showToast({
              title: '留言失败',
              icon: 'none',
            })
          }
        } else {
          wx.showToast({
            title: '服务器错误',
            icon: 'none',
          })
        }
      }
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