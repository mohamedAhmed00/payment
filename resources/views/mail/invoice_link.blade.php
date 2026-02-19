@extends('mail.mail_template')
@section('email')

    <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
        <td class="content-wrap"
            style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px;"
            valign="top">
            <table width="100%" cellpadding="0" cellspacing="0"
                   style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                    <td class="content-block"
                        style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;"
                        valign="top">
                        Thanks with dealing with <strong>{{ucfirst($organization?->name)}}</strong>.
                    </td>
                </tr>
                <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                    <td class="content-block"
                        style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;"
                        valign="top">
                       Pay by clicking button below.
                    </td>
                </tr>

                <tr style="text-align: center; vertical-align: middle;">

                    <td class="content-block"
                        style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;"
                        valign="top">
                        <a
                            href="{{$url}}"
                            style="font-size: 14px;
            color: white;
            width: 120px;
            text-decoration: none;
            background-color: #02c0ce;
            border-radius: 5px;
            padding: 10px;text-align: center;
            font-weight: bold;
            display: block;">
                            Invoice link
                        </a>
                    </td>


                </tr>
            </table>
        </td>
    </tr>
@endsection
