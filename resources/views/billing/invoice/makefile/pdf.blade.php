<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
        <title>{{ config('app.name', 'Laravel') }}</title>

		<style>
			.invoice-box {
				max-width: 800px;
				margin: auto;
				padding: 30px;
				border: 1px solid #eee;
				box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
				font-size: 16px;
				line-height: 24px;
				font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
				color: #555;
			}

			.invoice-box table {
				width: 100%;
				line-height: inherit;
				text-align: left;
			}

			.invoice-box table td {
				padding: 5px;
				vertical-align: top;
			}

			.invoice-box table tr td:nth-child(2) {
				text-align: right;
			}

			.invoice-box table tr.top table td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.top table td.title {
				font-size: 45px;
				line-height: 45px;
				color: #333;
			}

			.invoice-box table tr.information table td {
				padding-bottom: 40px;
			}

			.invoice-box table tr.heading td {
				background: #eee;
				border-bottom: 1px solid #ddd;
				font-weight: bold;
			}

			.invoice-box table tr.details td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.item td {
				border-bottom: 1px solid #eee;
			}

			.invoice-box table tr.item.last td {
				border-bottom: none;
			}

			.invoice-box table tr.total td:nth-child(2) {
				border-top: 2px solid #eee;
				font-weight: bold;
			}

			@media only screen and (max-width: 600px) {
				.invoice-box table tr.top table td {
					width: 100%;
					display: block;
					text-align: center;
				}

				.invoice-box table tr.information table td {
					width: 100%;
					display: block;
					text-align: center;
				}
			}

			/** RTL **/
			.invoice-box.rtl {
				direction: rtl;
				font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
			}

			.invoice-box.rtl table {
				text-align: right;
			}

			.invoice-box.rtl table tr td:nth-child(2) {
				text-align: left;
			}
		</style>
	</head>

	<body>
		<div class="invoice-box">
			<table cellpadding="0" cellspacing="0">
				<tr class="top">
					<td colspan="2">
						<table>
							<tr>
								<td class="title">
									<img src="{{$company_logo}}" style="width: 100%; max-width: 300px" />
								</td>

								<td>
									Invoice #: {{$invoice_code}} <br />
									Created: {{$invoice_date}} <br />
									Due: {{$invoice_due}}
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr class="information">
					<td colspan="2">
						<table>
							<tr>
								<td>
									{{$company_name}}.<br />
									{{$company_contact}} <br />
									{{$company_address}}
								</td>

								<td>
									{{$cus_code}}.<br />
									{{$cus_name}} <br />
									{{$cus_contact}}
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr class="heading">
					<td>Item</td>

					<td>Price</td>
				</tr>

				<tr class="item">
					<td> {{$product_service}} </td>

					<td> {{$price_sub}} </td>
				</tr>

				@if ($price_active_after_cutoff > 0)
				<tr class="item">
					<td> Harga setelah aktivasi, diluar cutoff </td>

					<td> {{$price_after_cutoff_format}} </td>
				</tr>
				@endif

				<tr class="item">
					<td></td>

					<td>PPN: {{$price_ppn}} </td>
				</tr>

				<tr class="item">
					<td></td>

					<td>Discount: {{$price_discount}} </td>
				</tr>

				<tr class="item">
					<td></td>

					<td>Total: {{$price_total}} </td>
				</tr>

                <tr class="total">
                    <td>Terbilang:</b></td>
                    <td> {{ $sayit }} </td>
                </tr>
			</table>
		</div>
	</body>
</html>