<!DOCTYPE html>
<html dir="rtl" lang="ar">
	<head>
		<meta charset="utf-8" />
		<title>{{ $bus?->name}}</title>

		<style>
			.invoice-box {
				max-width: 100%;
				margin: auto;
				padding: 30px;
				border: 1px solid #eee;
				box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
				font-size: 14px;
				line-height: 24px;
                font-family: 'Cairo', sans-serif;
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
				font-weight: 400;
			}
			.top-header {
				background: #eee;
				border-bottom: 1px solid #ddd;
				font-weight: 500;
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
				font-weight: 500;
			}

			.no-border {
            border: 1px solid #dddddd!important;
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
            font-family: 'Cairo', sans-serif;
			}

			.invoice-box.rtl table {
				text-align: right;
			}

			.invoice-box.rtl table tr td:nth-child(2) {
				text-align: left;
			}
			.data-box {
				max-width: 100%;
				margin: auto;
				padding: 30px;
				border: 1px solid #eee;
				box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
				font-size: 16px;
				line-height: 24px;
                font-family: 'Cairo', sans-serif;
				color: #555;
			}
			.heading2 {
            font-size: 14px;
            margin-top: 12px;
            margin-bottom: 12px;
        }
		.heading2 th {
			padding: 10px;
			font-size: 14px;
		}

		.data td, th {
            border: 1px solid #dddddd;
            text-align: right;
            padding: 8px;
}
        @page {
            header: page-header;
            footer: page-footer;
        }
		</style>
	</head>

	<body>

		<div class="invoice-box rtl">
			<table cellpadding="0" cellspacing="0">
				<tr class="top">

					<td colspan="2">
						<table>
							<tr>
								<td>
									خط السير : {{ $bus?->name }}<br />
									تاريخ الاضافة: {{ date_format($bus?->created_at,'d / m / y') }}<br />
									تاريخ انشاء الملف: {{ date('d / m / y') }}<br />
									رقم الباص: {{ $bus?->car_number }}<br />

								</td>

								<td class="title">
									<h3>{{ settings()?->name }}</h3>

									</td>
									<td class="brand-logo">

                                        {{-- <img src="" alt=""/> --}}
                                        {{-- <img src="{{ image_or_placeholder(settings()?->dashboard_logo_full_path)??'' }}" alt="{{settings()?->name}}"> --}}
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
									المدرسة : {{ $bus?->schools?->name }}<br />
									عنوان المدرسة : {{ $bus?->schools?->address }}<br />
									السائق : {{ $bus?->driver?->name}}<br />
									المشرف : {{ $bus?->admin?->name }}<br />
								</td>
							</tr>

						</table>
					</td>
				</tr>
				<tr class="heading">
					<td>الطلاب</td>
					<td>{{ $bus?->students?->count() }}</td>
				</tr>

			</table>


		</div>
		<div class="data-box rtl" style="margin-top: 10px;width: 100%" dir="rtl">
			<table class="data" style="border-collapse: collapse;width: 100%"  colspan="6">
				<thead>
					<tr class="no-border heading2" style="background-color: #eee;color: #555;padding: 10px;">
						<th width="40%" class="no-border">اسم الطالب</th>
						<th width="20%" class="no-border">المرحلة</th>
						<th width="20%" class="no-border">الصف</th>
						<th width="20%" class="no-border">الكود</th>
						<th width="20%" class="no-border">كلمة المرور</th>
						<th width="20%" class="no-border">اشتراك الباص</th>
					</tr>
				</thead>



			   @forelse ($bus?->students as $index => $student)
				   <tr style="font-size: 14px !important">
					   <td>{{ $student?->name }}</td>
					   <td>{{ $student?->grade?->name }}</td>
					   <td>{{ $student?->classroom?->name }}</td>
					   <td>{{ $student?->parent_key }}</td>
					   <td>{{ $student?->parent_secret }}</td>
					   <td>{{ $student?->tr_trip_type() }}</td>
				   </tr>
			   @empty
				   <tr>
					   <td>لا توجد بيانات الطالب</td>
				   </tr>
			   @endforelse
			</table>

		</div>

	</body>
</html>
