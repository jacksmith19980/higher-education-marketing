var message = {
    ajaxRoute: window.frontAjaxRoute,
    init: function () {
    },

    saveDirectMessageReplay: function (el, messageId, recipientId) {

        var formData = new FormData(document.getElementById("replayContainer"));
        formData.append('action', 'message.storeReplay');

        app.appAjax('POST', formData, window.frontAjaxRoute, 'file').then(function (response) {
            if (response.response == 'success') {
                $('#messageReplies').html(response.extra.html);
                window.editor.value = "";
                $('#saveReplay').attr('disabled', 'disabled');
                app.initPlugins();
            }
        });

    },

    searchMessages : function (el, recipientId) {

        const query = $(el).val();

        if (query.length >= 4 || query.length == 0) {

            var data = {
                action: 'message.search',
                payload: {
                    query: query,
                    recipient: recipientId,
                }
            };

            app.appAjax('POST', data, message.ajaxRoute, data).then(function (response) {
                if (response.response == 'success') {
                    $('#MessagesTable').html(response.extra.html);
                }
            });

        }

    }


}


$(function () {
    message.init();
});
