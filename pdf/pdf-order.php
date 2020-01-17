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
    table.table{
      border: 1px solid #343a40;
      margin: 0;
      padding: 0;
      border-spacing: 0;
      border-left: none;
      border-bottom: none;
    }
    table.table th,
    table.table td{
      border-left: 1px solid #343a40;
      border-bottom: 1px solid #343a40;
      padding: 5px;
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

    <div style="text-align: right; font-size: 17px; margin: 30px 0;">
      ##DATE##<br />
      <strong style="display: block; margin-top: 20px;">TRANS-##PONUMBER##</strong>
    </div>

    <table style="width: 100%; margin-top: 30px;">
      <tr>
        <td style=" width: 47.5%; background: #343a40; color: #fff; padding: 5px;"><strong style="font-size: 11px;">Transfer From:</strong></td>
        <td valign="top" style="width:5%;text-align: right;">&nbsp;</td>
        <td style="width: 47.5%;background: #343a40; color: #fff; padding: 5px; text-align: center;" valign="top"><strong style="font-size: 11px;">Transfer To:</strong></td>
      </tr>
      <tr>
        <td rowspan="3" valign="top" style="padding: 5px;">
          ##TRANSFERFROM##
        </td>
        <td valign="top">&nbsp;</td>
        <td valign="top" style="font-weight: bold; font-size: 15px; text-align: center; padding: 5px;">##TRANSFERTO##</td>
      </tr>

    </table>

    <table style="width: 100%; margin-top: 30px;" class="table">
      <thead>
        <tr style="border: 1px solid black;">
          <th style="background: #343a40; color: #fff; padding: 5px;">Description</th>
          <th style="background: #343a40; color: #fff; padding: 5px;text-align: right;">Unit Price</th>
          <th style="background: #343a40; color: #fff; padding: 5px;text-align: center;">Quantity</th>
          <th style="background: #343a40; color: #fff; padding: 5px;text-align: right;">Stock Amount</th>
        </tr>
      </thead>
      <tbody>
        ##BODY##
      </tbody>
    </table>

    <p style="margin-top: 60px; text-align: center;">
      -- end --
    </p>

</body>
</html>
