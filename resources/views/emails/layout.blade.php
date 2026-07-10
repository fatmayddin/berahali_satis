<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0; padding:0; background:#f4f5f8; font-family: Arial, Helvetica, sans-serif; color:#1a1a1c;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f5f8; padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="560" cellpadding="0" cellspacing="0" style="max-width:560px; width:100%;">
                    <tr>
                        <td style="background:#1a1a1c; border-radius:16px 16px 0 0; padding:20px 28px;">
                            <span style="color:#ffffff; font-size:20px; font-weight:bold;">{{ \App\Models\Setting::get('site_title', 'Bera Halı') }}<span style="color:#a585ff;">.</span></span>
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#ffffff; padding:28px; border:1px solid #e3e7ed; border-top:none;">
                            @yield('body')
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#ffffff; border-radius:0 0 16px 16px; border:1px solid #e3e7ed; border-top:1px solid #eceff4; padding:16px 28px; font-size:12px; color:#8a8a8f;">
                            {{ \App\Models\Setting::get('site_title', 'Bera Halı') }} · {{ \App\Models\Setting::get('phone') }} · {{ \App\Models\Setting::get('email') }}<br>
                            {{ \App\Models\Setting::get('address') }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
