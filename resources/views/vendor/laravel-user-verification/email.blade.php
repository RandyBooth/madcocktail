<!DOCTYPE html>
<html lang="en" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;margin-top:0 !important;margin-bottom:0 !important;margin-right:auto !important;margin-left:auto !important;padding-top:0 !important;padding-bottom:0 !important;padding-right:0 !important;padding-left:0 !important;height:100% !important;width:100% !important;" >
<head style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;" >
    <meta charset="utf-8" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;" > <!-- utf-8 works for most cases -->
    <meta name="viewport" content="width=device-width" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;" > <!-- Forcing initial-scale shouldn't be necessary -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;" > <!-- Use the latest (edge) version of IE rendering engine -->
    <meta name="x-apple-disable-message-reformatting" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;" >  <!-- Disable auto-scale in iOS 10 Mail entirely -->
    <title style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;" ></title> <!-- The title tag shows in email notifications, like Android 4.4. -->

    <!-- Web Font / @font-face : BEGIN -->
    <!-- NOTE: If web fonts are not required, lines 10 - 27 can be safely removed. -->

    <!-- Desktop Outlook chokes on web font references and defaults to Times New Roman, so we force a safe fallback font. -->
    <!--[if mso]>
    <style style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;" >
        * {
            font-family: 'Source Sans Pro', sans-serif !important !important;
        }
    </style>
    <![endif]-->

    <!-- All other clients get the webfont reference; some will render the font and others will silently fail to the fallbacks. More on that here: http://stylecampaign.com/blog/2015/02/webfont-support-in-email/ -->
    <!--[if !mso]><!-->
    <link href="https://fonts.googleapis.com/css?family=Oxygen:700|Source+Sans+Pro:400,600" rel="stylesheet" type="text/css" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;" >
    <!--<![endif]-->

    <!-- Web Font / @font-face : END -->

    <!-- CSS Reset -->
    <style style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;" >



        html,
        body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
        }


        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }


        div[style*="margin: 16px 0"] {
            margin:0 !important;
        }


        table,
        td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }


        table {
            border-spacing: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            margin: 0 auto !important;
        }
        table table table {
            table-layout: auto;
        }


        img {
            -ms-interpolation-mode:bicubic;
        }


        *[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
        }


        .x-gmail-data-detectors,
        .x-gmail-data-detectors *,
        .aBn {
            border-bottom: 0 !important;
            cursor: default !important;
        }


        .a6S {
            display: none !important;
            opacity: 0.01 !important;
        }

        img.g-img + div {
            display:none !important;
        }


        .button-link {
            text-decoration: none !important;
        }




        @media only screen and (min-device-width: 375px) and (max-device-width: 413px) {
            .email-container {
                min-width: 375px !important;
            }
        }

    </style>

    <!-- Progressive Enhancements -->
    <style style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;" >


        .button-td,
        .button-a {
            transition: all 100ms ease-in;
        }
        .button-td:hover,
        .button-a:hover {
            background: #CD4713 !important;
            border-color: #CD4713 !important;
        }


        @media screen and (max-width: 480px) {


            .fluid {
                width: 100% !important;
                max-width: 100% !important;
                height: auto !important;
                margin-left: auto !important;
                margin-right: auto !important;
            }


            .stack-column,
            .stack-column-center {
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
                direction: ltr !important;
            }

            .stack-column-center {
                text-align: center !important;
            }


            .center-on-narrow {
                text-align: center !important;
                display: block !important;
                margin-left: auto !important;
                margin-right: auto !important;
                float: none !important;
            }
            table.center-on-narrow {
                display: inline-block !important;
            }
        }

    </style>

</head>
<body width="100%" bgcolor="#FFFCF2" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;margin-top:0 !important;margin-bottom:0 !important;margin-right:auto !important;margin-left:auto !important;mso-line-height-rule:exactly;padding-top:0 !important;padding-bottom:0 !important;padding-right:0 !important;padding-left:0 !important;height:100% !important;width:100% !important;" >
<center style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;width:100%;background-color:#FFFCF2;background-image:none;background-repeat:repeat;background-position:top left;background-attachment:scroll;text-align:left;" >

    <!-- Visually Hidden Preheader Text : BEGIN -->
    <div style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;display:none;font-size:1px;line-height:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden;mso-hide:all;font-family:sans-serif;" >

    </div>
    <!-- Visually Hidden Preheader Text : END -->

    <!--
        Set the email width. Defined in two places:
        1. max-width for all clients except Desktop Windows Outlook, allowing the email to squish on narrow but never go wider than 680px.
        2. MSO tags for Desktop Windows Outlook enforce a 680px width.
        Note: The Fluid and Responsive templates have a different width (600px). The hybrid grid is more "fragile", and I've found that 680px is a good width. Change with caution.
    -->
    <div  class="email-container" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;max-width:680px;margin-top:auto;margin-bottom:auto;margin-right:auto;margin-left:auto;" >
        <!--[if mso]>
        <table role="presentation" aria-hidden="true" cellspacing="0" cellpadding="0" border="0" width="680" align="center" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;mso-table-lspace:0pt !important;mso-table-rspace:0pt !important;border-spacing:0 !important;border-collapse:collapse !important;table-layout:fixed !important;margin-top:0 !important;margin-bottom:0 !important;margin-right:auto !important;margin-left:auto !important;" >
            <tr style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;" >
                <td style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;mso-table-lspace:0pt !important;mso-table-rspace:0pt !important;" >
        <![endif]-->

        <!-- Email Header : BEGIN -->
        <table role="presentation" aria-hidden="true" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;max-width:680px;mso-table-lspace:0pt !important;mso-table-rspace:0pt !important;border-spacing:0 !important;border-collapse:collapse !important;table-layout:fixed !important;margin-top:0 !important;margin-bottom:0 !important;margin-right:auto !important;margin-left:auto !important;" >
            <tr style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;" >
                <td bgcolor="#252422" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;padding-top:20px;padding-bottom:20px;padding-right:0;padding-left:0;text-align:center;mso-table-lspace:0pt !important;mso-table-rspace:0pt !important;" >
                    <a href="https://www.madcocktail.com" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;font-family:'Oxygen', sans-serif;font-size:20px;line-height:1.1;color:#EB5E28;text-align:center;text-decoration:none;display:block;" >
                        Mad Cocktail
                    </a>
                </td>
            </tr>
        </table>
        <!-- Email Header : END -->

        <!-- Email Body : BEGIN -->
        <table role="presentation" aria-hidden="true" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;max-width:680px;mso-table-lspace:0pt !important;mso-table-rspace:0pt !important;border-spacing:0 !important;border-collapse:collapse !important;table-layout:fixed !important;margin-top:0 !important;margin-bottom:0 !important;margin-right:auto !important;margin-left:auto !important;" >

            <!-- 1 Column Text + Button : BEGIN -->
            <tr bgcolor="#FFFCF2" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;" >
                <td style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;mso-table-lspace:0pt !important;mso-table-rspace:0pt !important;" >
                    <table role="presentation" aria-hidden="true" cellspacing="0" cellpadding="0" border="0" width="100%" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;mso-table-lspace:0pt !important;mso-table-rspace:0pt !important;border-spacing:0 !important;border-collapse:collapse !important;margin-top:0 !important;margin-bottom:0 !important;margin-right:auto !important;margin-left:auto !important;table-layout:fixed !important;" >
                        <tr style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;" >
                            <td style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;padding-top:40px;padding-bottom:40px;padding-right:40px;padding-left:40px;font-family:'Source Sans Pro', sans-serif;font-size:16px;line-height:24px;color:#252422;mso-table-lspace:0pt !important;mso-table-rspace:0pt !important;" >
                                Hello
                                <br style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;" ><br style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;" >
                                Thank you for sign up at Mad Cocktail.
                                <br style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;" ><br style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;" >
                                To complete your registration you must click on the next confirmation link:
                                <br style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;" ><br style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;" >
                                <!-- Button : BEGIN -->
                                <table role="presentation" aria-hidden="true" cellspacing="0" cellpadding="0" border="0" align="center" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;margin-top:0 !important;margin-bottom:0 !important;margin-right:auto !important;margin-left:auto !important;mso-table-lspace:0pt !important;mso-table-rspace:0pt !important;border-spacing:0 !important;border-collapse:collapse !important;table-layout:fixed !important;" >
                                    <tr style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;" >
                                        <td  class="button-td" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;border-radius:4px;background-color:#EB5E28;background-image:none;background-repeat:repeat;background-position:top left;background-attachment:scroll;text-align:center;mso-table-lspace:0pt !important;mso-table-rspace:0pt !important;transition:all 100ms ease-in;" >
                                            <a href="{{ $link = route('email-verification.check', $user->verification_token) . '?email=' . urlencode($user->email) }}" class="button-a" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;background-color:#EB5E28;background-image:none;background-repeat:repeat;background-position:top left;background-attachment:scroll;border-width:9px;border-style:solid;border-color:#EB5E28;font-family:'Source Sans Pro', sans-serif;font-size:16px;line-height:1.1;text-align:center;text-decoration:none;display:block;border-radius:4px;transition:all 100ms ease-in;" >
                                                <span  class="button-link" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;color:#FFFCF2;text-decoration:none !important;" >&nbsp;&nbsp;&nbsp;&nbsp;Confirm Email&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                                <!-- Button : END -->
                                <br style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;" ><br style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;" >
                                If you’re having trouble clicking the "Confirm Email" button, copy and paste the URL below into your web browser:

                                <a href="{{ $link = route('email-verification.check', $user->verification_token) . '?email=' . urlencode($user->email) }}" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;font-family:'Source Sans Pro', sans-serif;font-size:16px;line-height:20px;color:#EB5E28;text-decoration:none;display:block;word-break:break-all;" >{{ $link }}</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <!-- 1 Column Text + Button : END -->

            <!-- Clear Spacer : BEGIN -->
            <tr style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;" >
                <td height="40" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;font-size:0;line-height:0;mso-table-lspace:0pt !important;mso-table-rspace:0pt !important;" >
                    &nbsp;
                </td>
            </tr>
            <!-- Clear Spacer : END -->

        </table>
        <!-- Email Body : END -->

        <!--[if mso]>
        </td>
        </tr>
        </table>
        <![endif]-->
    </div>
</center>
</body>
</html>
