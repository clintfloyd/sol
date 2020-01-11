<!DOCTYPE html>
<html>
<head>
  <title>Transfer Request</title>

  <style>
    body{
      background:#fff;
      font-family:Arial, sans-serif;
      color:#1d1d1d;
      font-size: 12px;
    }
    body img
    {
      width:200px
    }
    h1{
      font-size: 26px;
    }
  </style>
</head>
<body>

    <table style="width: 100%;">
      <tr>
        <td><img src="images/soleil-logo.png" /></td>
        <td valign="top" style="text-align: right;"><h1>Stock Transfer</h1></td>
      </tr>
      <tr>
        <td style="font-size: 10px; vertical-align: top; color: #dbae27;">
          SOLEIL DOR GIFTS TRADING L.L.C.<br/>
          P.O. BOX 239618 - DUBAI, UAE
        </td>
        <td valign="top" style="text-align: right;">&nbsp;</td>
      </tr>
    </table>

    <table style="width: 100%; margin-top: 30px;">
      <tr>
        <td style=" width: 40%; background: #D5A323; color: #fff; padding: 5px;"><strong style="font-size: 11px;">Vendor Information</strong></td>
        <td valign="top" style="width:10%;text-align: right;">&nbsp;</td>
        <td style=" width: 25%;background: #D5A323; color: #fff; padding: 5px;" valign="top"><strong style="font-size: 11px;">Date</strong></td>
        <td style="width: 25%;background: #D5A323; color: #fff; padding: 5px;" valign="top"><strong style="font-size: 11px;">Purchase Order Number</strong></td>
      </tr>
      <tr>
        <td rowspan="3" valign="top">
          ##SUPPLIER
        </td>
        <td valign="top">&nbsp;</td>
        <td valign="top">##DATE##</td>
        <td valign="top">##PONUMBER##</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="2" valign="top" style="background: #D5A323; color: #fff; padding: 5px;"><strong style="font-size: 11px;">Deliver To:</strong></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="2">Clint Monzon<br />Office 114, Hamsah Building - Office Block A<br />Al Karama, Dubai - UAE</td>
      </tr>
    </table>

    <table style="width: 100%; margin-top: 30px;">
      <thead>
        <tr style="border: 1px solid black;">
          <th style="background: #D5A323; color: #fff; padding: 5px;">Description</th>
          <th style="background: #D5A323; color: #fff; padding: 5px;text-align: right;">Unit Price</th>
          <th style="background: #D5A323; color: #fff; padding: 5px;text-align: center;">Quantity</th>
          <th style="background: #D5A323; color: #fff; padding: 5px;text-align: right;">Amount</th>
        </tr>
      </thead>
      <tbody>
        ##BODY##
      </tbody>
    </table>

</body>
</html>
