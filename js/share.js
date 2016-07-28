// JavaScript Document
var shareData = {
	title: '同是幸福回家人',
	desc: '在外打拼又一年，家里的父母在等着你，今年你回家吗？',
	link: 'http://aegon.8060608.com/gohome2015/',
	imgUrl: 'http://aegon.8060608.com/gohome2015/img/share/share.jpg',
	success: function (res) {
		//_hmt.push(['_trackPageview', '/share_end']);
		//location.href="shareEnd.html";
	},
	cancel: function (res) {

	},
	fail: function (res) {

	}
}
var shareData2 = {
	title: "同方全球人寿 同是幸福回家人",
	desc: shareData.desc,
	link: shareData.link,
	imgUrl: shareData.imgUrl,
	success: shareData.success,
	cancel: shareData.cancel,
	fail: shareData.fail
}

$.ajax({
	url:"http://uniqueevents.sinaapp.com/wx/getJsAPIA.php?callback=?",
	dataType:"jsonp",
	data:{
		url:location.href
	}
}).done(function(data) {
	//_hmt.push(['_trackPageview', '/share_end']);
	if(data) {
		var res = data.result;
		if(res == 1) {
			  wx.config({
				debug: false,
				appId: data.appId,
				timestamp: data.timestamp,
				nonceStr: data.nonceStr,
				signature: data.signature,
				jsApiList: [
					'checkJsApi',
					'onMenuShareTimeline',
					'onMenuShareAppMessage',
					'onMenuShareQQ',
					'onMenuShareWeibo'
				]
			});

			wx.ready(function () {
				wx.onMenuShareAppMessage(shareData);
				wx.onMenuShareTimeline(shareData2);
			});
		} else {
			//self.showError(data.msg);
		}
	}
}).always(function() {
	
});