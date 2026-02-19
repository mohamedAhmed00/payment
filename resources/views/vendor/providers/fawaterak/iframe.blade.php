<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pay With Fawaterak</title>
</head>
<body>
<div id="fawaterkDivId"></div>
</body>
<script src="{{$pluginConfig['plugin']}}"></script>
<script>
    let pluginConfig = {
        envType: "{{$pluginConfig['envType']}}",
        hashKey: "{{$pluginConfig['hashKey']}}",
        requestBody : {
            "cartTotal":"{{$pluginConfig['requestBody']['cartTotal']}}",
            "currency":"{{$pluginConfig['requestBody']['currency']}}",
            "customer": @json($pluginConfig['requestBody']['customer']),
            "redirectionUrls" : {
                'successUrl' : "{{$pluginConfig['requestBody']['redirectionUrls']['successUrl']}}",
                "failUrl" : "{{$pluginConfig['requestBody']['redirectionUrls']['failUrl']}}",
                "pendingUrl" : "{{$pluginConfig['requestBody']['redirectionUrls']['pendingUrl']}}",
            },
            "cartItems" : @json($pluginConfig['requestBody']['cartItems']),
        }

    };
    fawaterkCheckout(pluginConfig);


</script>
</html>
