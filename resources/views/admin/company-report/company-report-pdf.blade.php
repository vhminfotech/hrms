<style>
    body
    {
      margin: 0mm 0mm 0mm 0mm;
      font-family: sans-serif;
    }
    @page { 
        margin-top: 0px;
        margin-left: 10px;
        margin-right: 10px;
        margin-bottom: 0px; 
    }
    @media print {
        body
        {
          margin: 0px;
        }
        .wrap{word-break: break-all;
        display: inline-block;
        word-break: break-word;}
    }
    table
    {
        border-color: #000;
        color: #000;
        border-collapse: collapse;
        font-family: sans-serif;
    }
    .table_field{
        font-size: 14px;
    }
    .font-small{
        font-size: 13px;
        height: 13px;
    }
    .dark{
        /*color: white;*/
        /*background-color: #75b979;*/
        background-color: #ffaa00;
        font-weight: bold;
    }
    .white{
        color: white;
    }
    .light{
        font-size: 12px;
        background-color: #DCDCDC;
        font-weight: bold;
    }
    .pfont{
        font-size: 10px;
    }
    * {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }
    .brdr{
        border: 2px solid orange;
    }
</style>
    <table  style="width: 100%; margin:10px;">
        <tr>
            <td style="font-size: 25px; font-weight: bold; width: 20%;">
                HRMS
            </td>
            <td style="font-size: 25px; font-weight: bold; width: 60%; text-align: center;">
                Company Report
            </td>
            <td valign="top" style="width: 20%;">
            </td>
         </tr>
    </table>

    <table style="width: 100%; margin-top: 10px;" cellpadding="3" border='1'>
        <tr class="light">
            <td>Company Name</td>
            <td>Email</td>
            <td>Status</td>
            <td>Subscription</td>
            <td>Expiry Date</td>
            <td>Created Date</td>
        </tr>
        @foreach($companyDetails as $objCompanyReport)
            <tr class="font-small">
                <td>{{ $objCompanyReport['company_name'] }}</td>
                <td>{{ $objCompanyReport['email'] }}</td>
                <td>{{ $objCompanyReport['status'] }}</td>
                <td>{{ $objCompanyReport['subcription'] }}</td>
                <td>{{ date('d-m-Y',strtotime($objCompanyReport['expiry_at'])) }}</td>
                <td>{{ date('d-m-Y',strtotime($objCompanyReport['created_at'])) }}</td>
            </tr>  
        @endforeach
    </table>


{{-- =======
<html>
    <head>
        <meta charset="utf-8">
        <title>Pay Slip</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style>
            body
            {
              margin: 0mm 0mm 0mm 0mm;
              font-family: sans-serif;
            }
            @page { 
                margin-top: 0px;
                margin-left: 10px;
                margin-right: 10px;
                margin-bottom: 0px; 
            }
            @media print {
                body
                {
                  margin: 0px;
                }
                .wrap{word-break: break-all;
                display: inline-block;
                word-break: break-word;}
            }
            table
            {
                border-color: #000;
                color: #000;
                border-collapse: collapse;
                font-family: sans-serif;
            }
            .table_field{
                font-size: 14px;
            }
            .font-small{
                font-size: 13px;
                height: 13px;
            }
            .dark{
                /*color: white;*/
                /*background-color: #75b979;*/
                background-color: #ffaa00;
                font-weight: bold;
            }
            .white{
                color: white;
            }
            .light{
                font-size: 12px;
                background-color: #DCDCDC;
                font-weight: bold;
            }
            .pfont{
                font-size: 10px;
            }
            * {
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
            }
            .brdr{
                border: 2px solid orange;
            }
            .main-header{
                font-size: 35px;
                /*line-height: 35px;
                text-align: right !important;*/
            }
            .text-undeline{
                text-decoration: underline;
            }
            .small-fornt{
                font-size: 11px;
                text-align: right;
            }
            .page-break {
                page-break-after: always;
            }
            .padding-l-5{
                padding-left: 5px;
            }
        </style>
        <!-- Favicon -->
        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    </head>
    
    <body>
        <table width="100%">
            <tr>
                <td class="main-header"><span >HRMS</span></td>
                <td style="text-align: right;">Company Report</td>
            </tr>
        </table>

        <table width="100%" border="1">
            <thead>
                <tr>
                    <td class="padding-l-5">Company Name</td>
                    <td class="padding-l-5">Company Email</td>
                    <td class="padding-l-5">Subscription</td>
                    <td class="padding-l-5">Request Type</td>
                    <td class="padding-l-5">Payment Type</td>
                    <td class="padding-l-5">Status</td>
                </tr>
            </thead>
            <tbody>
            @foreach($companyDetails as $key => $value)
                <tr>
                    <td>  {{ $value['company_name'] }}</td>
                    <td>  {{ $value['email'] }}</td>
                    <td>  {{ $value['subcription'] }}</td>
                    <td>  {{ $value['request_type'] }}</td>
                    <td>  {{ $value['payment_type'] }}</td>
                    <td>  {{ $value['status'] }}</td>
                </tr>  
            @endforeach
            </tbody>
        </table>
            
    </body>
</html>
>>>>>>> 0663674d4da32a663eb20a7854942882821f2e4f
 --}}