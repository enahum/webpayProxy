<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Inicio transaccion con Webpay</title>
</head>
<body>
<form name='redirect' action='<?= $url?>' method='POST'>
    <input type='hidden' name='token_ws' value='<?= $token; ?>'>
    <?php if(isset($error)): ?>
        <input type="hidden" name="error" id="error" value="<?= $error ?>" />
        <input type="hidden" name="sessionId" id="sessionId" value="<?= sessionId ?>" />
    <?php endif ?>
</form>

<script src="/js/init.js"></script>
</body>
</html>