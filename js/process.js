$(document).ready(function(){
    var redirectWithError = function(data) {
        var form = $('<form action="' + data.url + '" method="POST" name="confirm" style="display:none;"> ' +
        '<input type="hidden" id="token_ws" name="token_ws" value="' + $('#token_ws').val() + '" />'+
        '<input type="hidden" id="sessionId" name="sessionId" value="' + data.sessionId + '" />'+
        '<input type="hidden" id="error" name="error" value="' + data.error + '" />'+
        '</form>');
        $('body').append(form);
        form.submit();
    };

    $.post('/transaction/result', {token_ws: $('#token_ws').val() })
        .done(function(response){
            var form, detail, session;
            if(!response.error) {
                detail = response.detailOutput;
                if (detail) {
                    session = response.urlRedirection.indexOf('voucher') < 0 ? '<input type="hidden" id="sessionId" name="sessionId" value="' + response.sessionId + '" />' : '';
                    var form = $('<form action="' + response.urlRedirection + '" method="POST" name="confirm" style="display:none;"> ' +
                    '<input type="hidden" id="token_ws" name="token_ws" value="' + $('#token_ws').val() + '" />'+
                    session +
                    '</form>');
                    $('body').append(form);
                    form.submit();
                } else {
                    return redirectWithError({
                        url: response.urlRedirection,
                        sessionId: response.sessionId,
                        error: 'Ocurrió un error procesando la transacción'
                    });
                }
            } else {
                return redirectWithError({
                    url: response.finalUrl,
                    sessionId: response.sessionId,
                    error: response.error
                });
            }
        });
});
