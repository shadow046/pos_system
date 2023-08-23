<!doctype html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>{{$transaction->receipt_no}}</title>

<style type="text/css">
    * {
        font-family: Verdana, Arial, sans-serif;
    }
    table{
        font-size: x-small;
    }
    tfoot tr td{
        font-weight: bold;
        font-size: x-small;
    }
    .gray {
        background-color: lightgray
    }

</style>

<style>
        *,
        *::before,
        *::after {
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }
        ::-moz-focus-inner {
            border-style: none;
            padding: 0;
        }
        :-moz-focusring {
            outline: 1px dotted ButtonText;
        }
        h2,
        p {
            margin: 0;
        }
        *,
        ::before,
        ::after {
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            border-width: 0;
            border-style: solid;
            border-color: #e5e7eb;
        }
        h2 {
            font-size: inherit;
            font-weight: inherit;
        }
        svg {
            display: block;
            vertical-align: middle;
        }
        .container {
            width: 100%;
        }
        @media (min-width: 640px) {
            .container {
                max-width: 640px;
            }
        }
        @media (min-width: 768px) {
            .container {
                max-width: 768px;
            }
        }
        @media (min-width: 1024px) {
            .container {
                max-width: 1024px;
            }
        }
        @media (min-width: 1280px) {
            .container {
                max-width: 1280px;
            }
        }
        @media (min-width: 1536px) {
            .container {
                max-width: 1536px;
            }
        }
        .bg-gray-100 {
            --tw-bg-opacity: 1;
            background-color: rgba(243, 244, 246, var(--tw-bg-opacity));
        }
        .bg-gray-800 {
            --tw-bg-opacity: 1;
            background-color: rgba(31, 41, 55, var(--tw-bg-opacity));
        }
        .hover\:bg-gray-300:hover {
            --tw-bg-opacity: 1;
            background-color: rgba(209, 213, 219, var(--tw-bg-opacity));
        }
        .rounded-lg {
            border-radius: 0.5rem;
        }
        .rounded-full {
            border-radius: 9999px;
        }
        .border-t {
            border-top-width: 1px;
        }
        .border-b {
            border-bottom-width: 1px;
        }
        .cursor-pointer {
            cursor: pointer;
        }
        .block {
            display: block;
        }
        .inline-block {
            display: inline-block;
        }
        .flex {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
        }
        .inline-flex {
            display: -webkit-inline-box;
            display: -ms-inline-flexbox;
            display: inline-flex;
        }
        .hidden {
            display: none;
        }
        .flex-wrap {
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
        }
        .items-start {
            -webkit-box-align: start;
            -ms-flex-align: start;
            align-items: flex-start;
        }
        .items-center {
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
        }
        .justify-center {
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
        }
        .justify-between {
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
        }
        .flex-1 {
            -webkit-box-flex: 1;
            -ms-flex: 1 1 0%;
            flex: 1 1 0%;
        }
        .font-medium {
            font-weight: 500;
        }
        .font-bold {
            font-weight: 700;
        }
        .font-extrabold {
            font-weight: 800;
        }
        .h-10 {
            height: 2.5rem;
        }
        .text-xs {
            font-size: 0.75rem;
            line-height: 1rem;
        }
        .text-sm {
            font-size: 0.875rem;
            line-height: 1.25rem;
        }
        .text-md {
            font-size: 1rem;
            line-height: 1.5rem;
        }
        .text-xl {
            font-size: 1.25rem;
            line-height: 1.75rem;
        }
        .text-2xl {
            font-size: 1.5rem;
            line-height: 2rem;
        }
        .text-3xl {
            font-size: 1.875rem;
            line-height: 2.25rem;
        }
        .leading-none {
            line-height: 1;
        }
        .leading-tight {
            line-height: 1.25;
        }
        .mx-auto {
            margin-left: auto;
            margin-right: auto;
        }
        .-mx-1 {
            margin-left: -0.25rem;
            margin-right: -0.25rem;
        }
        .mb-1 {
            margin-bottom: 0.25rem;
        }
        .mb-2 {
            margin-bottom: 0.5rem;
        }
        .mb-3 {
            margin-bottom: 0.75rem;
        }
        .mr-4 {
            margin-right: 1rem;
        }
        .mt-5 {
            margin-top: 1.25rem;
        }
        .mb-6 {
            margin-bottom: 1.5rem;
        }
        .mb-8 {
            margin-bottom: 2rem;
        }
        .mt-10 {
            margin-top: 2.5rem;
        }
        .mb-10 {
            margin-bottom: 2.5rem;
        }
        .mt-12 {
            margin-top: 3rem;
        }
        .mt-20 {
            margin-top: 5rem;
        }
        .mt-1 {
            margin-top: 0.25rem;
        }
        .mt-2 {
            margin-top: 0.5rem;
        }
        .ml-auto {
            margin-left: auto;
        }
        .p-2 {
            padding: 0.5rem;
        }
        .px-1 {
            padding-left: 0.25rem;
            padding-right: 0.25rem;
        }
        .py-2 {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }
        .px-4 {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        .py-6 {
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }
        .pb-2 {
            padding-bottom: 0.5rem;
        }
        .absolute {
            position: absolute;
        }
        .relative {
            position: relative;
        }
        .top-0 {
            top: 0px;
        }
        .right-0 {
            right: 0px;
        }
        * {
            --tw-shadow: 0 0 #0000;
        }
        .shadow-lg {
            --tw-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            -webkit-box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow);
            box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow);
        }
        * {
            --tw-ring-inset: var(--tw-empty, );
            --tw-ring-offset-width: 0px;
            --tw-ring-offset-color: #fff;
            --tw-ring-color: rgba(59, 130, 246, 0.5);
            --tw-ring-offset-shadow: 0 0 #0000;
            --tw-ring-shadow: 0 0 #0000;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .text-white {
            --tw-text-opacity: 1;
            color: rgba(255, 255, 255, var(--tw-text-opacity));
        }
        .text-gray-500 {
            --tw-text-opacity: 1;
            color: rgba(107, 114, 128, var(--tw-text-opacity));
        }
        .text-gray-600 {
            --tw-text-opacity: 1;
            color: rgba(75, 85, 99, var(--tw-text-opacity));
        }
        .text-gray-700 {
            --tw-text-opacity: 1;
            color: rgba(55, 65, 81, var(--tw-text-opacity));
        }
        .text-gray-800 {
            --tw-text-opacity: 1;
            color: rgba(31, 41, 55, var(--tw-text-opacity));
        }
        .text-gray-900 {
            --tw-text-opacity: 1;
            color: rgba(17, 24, 39, var(--tw-text-opacity));
        }
        .text-green-500 {
            --tw-text-opacity: 1;
            color: rgba(16, 185, 129, var(--tw-text-opacity));
        }
        .uppercase {
            text-transform: uppercase;
        }
        .tracking-wide {
            letter-spacing: 0.025em;
        }
        .tracking-wider {
            letter-spacing: 0.05em;
        }
        .w-10 {
            width: 2.5rem;
        }
        .w-20 {
            width: 5rem;
        }
        .w-32 {
            width: 8rem;
        }
        .w-40 {
            width: 10rem;
        }
        .w-1\/2 {
            width: 50%;
        }
        .w-2\/4 {
            width: 50%;
        }
        .w-full {
            width: 100%;
        }
        .z-40 {
            z-index: 40;
        }
        @media (min-width: 640px) {
            .sm\:w-2\/4 {
                width: 50%;
            }
        }
        @media (min-width: 768px) {
            .md\:block {
                display: block;
            }
            .md\:flex {
                display: -webkit-box;
                display: -ms-flexbox;
                display: flex;
            }
            .md\:mb-0 {
                margin-bottom: 0px;
            }
            .md\:mb-1 {
                margin-bottom: 0.25rem;
            }
            .md\:w-1\/3 {
                width: 33.333333%;
            }
        }
        @media (min-width: 1024px) {
            .lg\:w-1\/4 {
                width: 25%;
            }
        }
        footer {
            position: fixed; 
            bottom: 0cm; 
            left: 0cm; 
            right: 0cm;
            height: 2cm;
        }
</style>

</head>
<body>

  <table width="100%">
    <tr>
        <td align="center" colspan="2"><h2 class="text-2xl font-bold  tracking-wider uppercase">POS</h2></td>
    </tr>
    <tr>
        <td align="center" colspan="2"><p class="text-xs tracking-wider">432 Banana St.</p></td>
    </tr>
    <tr>
        <td align="center" colspan="2"><p class="text-xs tracking-wider">Manila, Metro Manila, 1010</p></td>
    </tr>
    <tr>
        <td align="center" colspan="2"><p class="text-xs tracking-wider">+21345678</p></td>
    </tr>
    {{-- <tr>
        <td style="border-top: 1px solid; border-bottom: 1px solid; color:#454544;  x-small;">
            <p class="text-sm tracking-wider mt-2 mb-2 font-bold">Receipt: {{$transaction->receipt_no}}</p>
        </td>
        <td style="border-top: 1px solid; border-bottom: 1px solid; color:#454544;  x-small;">
            <p class="text-xs tracking-wider mt-2 mb-2">Cashier: Juan Dela Cruz</p>
        </td>
        <td style="border-top: 1px solid; border-bottom: 1px solid; color:#454544;  x-small;">
            <p class="text-xs tracking-wider mt-2 mb-2">{{date('d F y H:i:s A', strtotime($transaction->sold_at))}}</p>
        </td>
    </tr> --}}
  </table>
  
  <div class="mt-5"></div>
  <table width="100%">
    <tr>
        <td style="border-top: 1px solid; border-bottom: 1px solid; color:#454544;  x-small;" colspan="2">
            <p class="text-sm tracking-wider mt-2 font-bold">Receipt: {{$transaction->receipt_no}}</p>
            <p class="text-xs tracking-wider">Cashier: Juan Dela Cruz</p>
            <p class="text-xs tracking-wider mt-2 mb-2">{{date('d F y h:i:s A', strtotime($transaction->sold_at))}}</p>
        </td>
    </tr>

  </table>
  <table width="100%" align="center">
    <tr>
        <td style="x-small;" colspan="2">
            <p class="text-xs tracking-wider mt-2 mb-2" align="center">---{{ucwords($transaction->order_type)}}---</p>
        </td>
    </tr>

  </table>

  <table width="100%" align="center">
    
    <tbody>
        @foreach($transaction->orders as $order)
        <tr>
            <td align="left">
                <p class="tracking-wide text-xs">{{$order->quantity}} {{$order->product->name}}</p>
            </td>
            <td align="right">
                <p class="tracking-wide text-xs">{{number_format($order->price, 2)}}</p>
            </td>
        </tr>
        @endforeach
        <div class="mt-5"></div>
        <tr>
            <td style="border-bottom: 1px solid; color:#454544;  x-small;" colspan="2">
            </td>
        </tr>
        <tr>
            <td align="left">
                <p class="text-sm tracking-wider mt-2 font-bold">Amount Due:</p>
            </td>
            <td align="right">
                <p class="text-sm tracking-wider mt-2 font-bold">P{{number_format($transaction->amount, 2)}}</p>
            </td>
        </tr>
        <div class="mt-5"></div>
        <tr>
            <td align="left">
                Cash:
            </td>
            <td align="right">
                P{{number_format($transaction->amount_paid, 2)}}
            </td>
        </tr>
        <tr>
            <td align="left">
                Change:
            </td>
            <td align="right">
                P{{number_format($transaction->change, 2)}}
            </td>
        </tr>
        <tr>
            <td align="left">
                Vatable Sales:
            </td>
            <td align="right">
                P{{number_format($transaction->sales, 2)}}
            </td>
        </tr>
        <tr>
            <td align="left">
                VAT Amount 12 %:
            </td>
            <td align="right">
                P{{number_format($transaction->vat, 2)}}
            </td>
        </tr>
        <tr>
            <td align="left">
                Discount:
            </td>
            <td align="right">
                P{{number_format($transaction->discount, 2)}}
            </td>
        </tr>
        <div class="mt-5"></div>
        <tr>
            <td>
                Trans No: {{$transaction->id}}
            </td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2" align="center" style="border-top: 1px solid;">
                Thank you, Please come again.
            </td>
        </tr>
        
    </tbody>
  </table>
</body>
</html>