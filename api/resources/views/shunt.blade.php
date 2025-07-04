<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Device Fingerprint</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fingerprintjs2/2.1.0/fingerprint2.min.js"></script>
</head>

<body>
    <script>
        // 确保页面加载完成
        window.onload = function() {
            // 使用 fingerprintjs2 生成设备指纹
            Fingerprint2.get(function(components) {
                var values = components.map(function(component) {
                    return component.value
                });
                var murmur = Fingerprint2.x64hash128(values.join(''), 31);

                // 发送设备指纹到服务器
                fetch('/api/getKefu', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            fingerprint: murmur
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        // 根据服务器响应进行跳转
                        window.location.href = data.redirectUrl;
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
            });
        };
    </script>
</body>

</html>