<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv=" Content-Type" content="text/html; charset=utf-8"/>

    <title>{{$data['title']}}</title>

    <style type="text/css">
        @media only screen and (min-device-width: 601px) {
            .content {
                width: 600px !important;
            }
        }

        body[yahoo] .class {
        }

        .button {
            text-align: center;
            font-size: 18px;
            font-family: sans-serif;
            font-weight: bold;
            padding: 0 30px 0 30px;
        }

        .button a {
            color: #ffffff !important;
            text-decoration: none;
        }

        .button a:hover {
            text-decoration: underline;
        }

        @media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
            body[yahoo] .buttonwrapper {
                background-color: transparent !important;
            }

            body[yahoo] .button a {
                background-color: #e05443;
                padding: 15px 15px 13px !important;
                display: block !important;
            }
    </style>
</head>

<body yahoo bgcolor="#f6f8f1" style="margin: 0; padding: 0; min-width: 100%; background-color: #f6f8f1;">
<!--[if (gte mso 9)|(IE)]>
<table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td><![endif]-->
<table class="content" align="center" cellpadding="0" cellspacing="0" border="0" style="width: 100%; max-width: 600px;">
    <tr>
        <td class="content" bgcolor="#ffffff"
            style="width: 100%; max-width: 1000px; padding: 30px 30px 30px 30px; border-bottom: 1px solid #f2eeed;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td style="color: #153643; font-family: sans-serif; padding: 0 0 15px 0; font-size: 24px; line-height: 28px; font-weight: bold;">
                        Файл загружен в облако!
                    </td>
                </tr>
                <tr>
                    <td style="color: #153643; font-family: sans-serif; font-size: 16px; line-height: 22px;">
                        <p>Доступен по ссылке:<br><a href="{{$data['url']}}">{{$data['url']}}</a></p>
                        @if (isset($data['description']) && $data['description'])
                            <p>Описание файла:<br>{{$data['description']}}.</p>
                        @else
                            <p>Описание файла отсутствует.</p>
                        @endif
                        <table class="buttonwrapper" bgcolor="#e05443" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td class="button" height="45"
                                    style="text-align: center; font-size: 18px; font-family: sans-serif; font-weight: bold; padding: 0 30px 0 30px;">
                                    <a style="color: #ffffff; text-decoration: none;" href="{{$data['url']}}">Скачать</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="color: #153643; font-family: sans-serif; font-size: 16px; line-height: 22px;">
                        <p>С уважением, <br/>
                            <strong>kar333N</strong></p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="footer" bgcolor="#44525f" style="padding: 20px 30px 15px 30px;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="center" style="font-family: sans-serif; font-size: 14px; color: #ffffff;">
                        <a href="cloud-nine-store.ru"
                           style="color: #ffffff; text-decoration: underline;">cloud-nine-store.ru</a>
                    </td>
                </tr>
            </table>
            <!--[if (gte mso 9)|(IE)]>
            </td>
            </tr>
            </table>
            <![endif]-->
</body>
</html>