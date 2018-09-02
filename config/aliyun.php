<?php

$config['debug'] = false;    // 网站 Debug 模式

$config['appKey']          = '25035043';
$config['appSecret']       = '012afbfd959ea6996ac65573f584d537';


if (defined('ENV')) {
    switch (ENV) {

        case 'home':
            $config['commandConvertPdfToPng'] = 'pdftopng %s aliyunOcr';
            break;

        case 'test':
        default:
            $config['commandConvertPdfToPng'] = 'pdf2image  %s';
            break;

    }
} else {
    $config['commandConvertPdfToPng'] = 'pdf2image  %s';
}
