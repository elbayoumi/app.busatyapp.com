<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>بيانات باص #{{ $bus->id }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900;1000&display=swap" rel="stylesheet">

    <style>
        html,
        body {
            margin: 10px; 
            padding: 10px;
            font-family: sans-serif;
        }
        h1,h2,h3,h4,h5,h6,p,span,label {
            font-family: sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0px !important;
        }
        table thead th {
            height: 28px;
            text-align: right;
            font-size: 16px;
            font-family: sans-serif;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 14px;
        }

        .heading {
            font-size: 24px;
            margin-top: 12px;
            margin-bottom: 12px;
            font-family: sans-serif;
        }
        .small-heading {
            font-size: 18px;
            font-family: sans-serif;
        }
        .total-heading {
            font-size: 18px;
            font-weight: 700;
            font-family: sans-serif;
        }
        .order-details tbody tr td:nth-child(1) {
            width: 20%;
        }
        .order-details tbody tr td:nth-child(3) {
            width: 20%;
        }

        .text-start {
            text-align: right;
        }
        .text-end {
            text-align: left;
        }
        .text-center {
            text-align: center;
        }
        .company-data span {
            margin-bottom: 2px;
            display: inline-block;
            font-family: 'Cairo', sans-serif;
            font-size: 15px;
            font-weight: 400;
        }
        .no-border {
            border: 1px solid #fff !important;
        }
        .bg-blue {
            background-color: #414ab1;
            color: #fff;
        }

        body{
            font-family: 'Cairo', sans-serif;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <table class="order-details">
        <thead>
            <tr>
                <th width="50%" colspan="2">
                    <h1 class="text-center">Busaty</h1>
                </th>
                <th width="50%" colspan="2" class="text-start company-data">
                    <span> المدرسة : {{ $bus->schools->name }}</span> <br>
                    <span>تاريخ : {{ date('d / m / y') }}</span> <br>
                    <span>العنوان : {{ $bus->schools->address != null ? $bus->schools->address  : 'Undefined'}}</span> <br>
                    <span>رقم الهاتف :{{ $bus->schools->phone }}</span> <br>
                </th>
            </tr>
            <tr class="bg-blue text-center">
                <th width="100%" colspan="4">بيانات الباص رقم : #{{  $bus->id }}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>رقم الباص :</td>
                <td>{{ $bus->car_number }}</td>

                <td>خط سير الباص :</td>
                <td>{{ $bus->name }}</td>
            </tr>
            <tr>
                <td>السائق :</td>
                <td>{{ isset($bus->driver) != null ? $bus->driver->name : 'Undefined'}}</td>

                <td>المشرف :</td>
                <td>{{ isset($bus->admin->name) != null ? $bus->admin->name : 'Undefined'}}</td>
            </tr>
            <tr>
                <td>رقم السائق :</td>
                <td>{{ isset($bus->driver->phone) != null ? $bus->driver->phone: 'Undefined'}}</td>

                <td>رقم المشرف:</td>
                <td>{{ isset($bus->admin) != null ? $bus->admin->phone : 'Undefined'}}</td>
            </tr>
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th class="no-border text-start heading" colspan="5">
                    الطلاب
                </th>
            </tr>
            <tr class="bg-blue">
                <th>#NO</th>
                <th>اسم الطالب</th>
                <th>الكود الاول (KEY)</th>
                <th>الكود الثاني (SECRET)</th>
                <th>المرحلة</th>
                <th>الصف</th>
                <th>اشتراك الباص</th>

            </tr>
        </thead>
        <tbody>

    
       @forelse ($bus->students as $index => $student)
           <tr>
               <td width="5%">{{ $index+1 }}</td>
               <td width="25%">{{ $student->name }}</td>
               <td width="10%">{{ $student->parent_key }}</td>
               <td width="10%">{{ $student->parent_secret }}</td>
               <td width="10%">{{ $student->grade->name }}</td>
               <td width="10%">{{ $student->classroom->name }}</td>
               <td width="10%">{{ $student->tr_trip_type() }}</td>

           </tr>
       @empty
           <tr>
               <td colspan="6">No student Data</td>
           </tr>
       @endforelse
       <tr>
        <td colspan="1"  class="fw-bold"> بيان :</td>
        <td colspan="2"  class="fw-bold">طباعة بيانات الباص </td>
        <td colspan="1"  class="fw-bold"> عدد الطلاب :</td>
        <td colspan="3"  class="fw-bold">{{ $bus->students->count() }} </td>

       </tr>
        </tbody>
    </table>
    <script type="text/javascript">
        window.onload = function() { window.print(); }
   </script>
</body>
</html>