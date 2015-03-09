<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Procesando transacción</title>
    <link href="/css/process.css" rel="stylesheet">
</head>
<body>
<input type="hidden" id="token_ws" name="token_ws" value="<?= $token ?>" />
<div id="wpcontenedor">
    <div id="wpcabecera">
        <div id="tiendalogo">
            <img src="https://webpay3g.transbank.cl/imagingservlet/LogoServlet?commerceId=<?= COMMERCE_ID ?>" alt="" />
        </div>
        <div id="wplogo">
            <img src="https://webpay3g.transbank.cl/webpayserver/imagenes/logowp2.gif" alt="logowp2" />
        </div>
        <div id="wpprincipal">
            <div id="wpproceso"><img src="https://webpay3g.transbank.cl/webpayserver/imagenes/barracargadora.gif"  alt="barra" />
                <br />
                <p class="Estilo7">Su transacci&oacute;n est&aacute; siendo procesada...</p>
            </div>
        </div>
    </div>
    <div id="wpfooter">
        <div id="wpfooter">
            <div id="wpimage" align="right">
                <img src="https://webpay3g.transbank.cl/webpayserver/imagenes/Visa.gif" alt="Visa" />&nbsp;
                <img src="https://webpay3g.transbank.cl/webpayserver/imagenes/MasterCard.gif" alt="MasterCard" />&nbsp;
                <img src="https://webpay3g.transbank.cl/webpayserver/imagenes/Magna.gif" alt="Magna" />&nbsp;
                <img src="https://webpay3g.transbank.cl/webpayserver/imagenes/AMEX.gif" alt="AMEX" />&nbsp;
                <img src="https://webpay3g.transbank.cl/webpayserver/imagenes/Diners.gif" alt="Diners" />&nbsp;
                <img src="https://webpay3g.transbank.cl/webpayserver/imagenes/rcompra.gif" alt="redcompra" />&nbsp;
                <br>
                <img src="https://webpay3g.transbank.cl/webpayserver/imagenes/lock.gif" alt="logoCandado">
                <SPAN CLASS=Info>
                    Esta transacci&oacute;n se est&aacute; realizando sobre un sistema seguro.</SPAN>
                </img>
                <br>
                <table align="right">
                    <tr></tr>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="/js/jquery.min.js"></script>
<script src="/js/process.js"></script>
</body>
</html>