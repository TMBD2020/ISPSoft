<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISP Service Receipt</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            width: 58mm; /* Standard POS receipt width */
        }
        .receipt {
            width: 100%;
            max-width: 58mm;
            margin: auto;
            text-align: center;
        }
        .bold {
            font-weight: bold;
        }
        .line {
            border-top: 1px dashed black;
            margin: 5px 0;
        }
        .left {
            text-align: left;
            display: inline-block;
            width: 60%;
        }
        .right {
            text-align: right;
            display: inline-block;
            width: 35%;
        }
        .center {
            text-align: center;
        }
        p{
            margin: 0;
        }
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .noprint {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="receipt">
        <p class="bold">{{ auth()->user()->admin->name }}</p>
        <p>{{ auth()->user()->admin->address }}</p>
        <p>Phone: {{ auth()->user()->admin->mobile }}</p>
        <div class="line"></div>

        <p class="bold">Customer Details</p>
        <p><span class="left">Name:</span> <span class="right">{{ $bills->client->client_name }}</span></p>
        <p><span class="left">Account #:</span> <span class="right">{{ $bills->client->cell_no }}</span></p>
        
        <div class="line"></div>
        <p class="bold">Billing Details</p>
        <p><span class="left">Plan:</span> <span class="right">{{ $bills->client->package->package_name }}</span></p>
        <p><span class="left">Charge:</span> <span class="right">৳{{ number_format($bills->client->package->package_price,2,".","") }}</span></p>

        <div class="line"></div>
        <p class="bold">Payment Details</p>
        <p><span class="left">Paid</span> <span class="right">৳{{ number_format($bills->receive_amount,2,".","") }}</span></p>
        <p><span class="left">Discount</span> <span class="right">৳{{ number_format($bills->discount_amount,2,".","") }}</span></p>
        <div class="line"></div>
        <p class="bold"><span class="left">Current Due</span> <span class="right">৳{{ number_format($due,2,".","")}}</span></p>
        <p><span class="left">Payment Date</span> <span class="right">{{ $bills->receive_date }}</span></p>

        <div class="line"></div>
        <p class="center">Thank you for using our service!</p>
    </div>

    <button class="noprint" onclick="window.print()">Print Receipt</button>


</body>
</html>
