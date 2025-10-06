<table>
    <thead>
    <tr>
        <th>الاسم</th>
        <th>الكود الاول</th>
        <th> الكود الثاني</th>
        <th>الهاتف</th>
        <th>تاريخ الميلاد</th>
        <th>اسم المدرسة</th>
        <th>اسم المرحلة</th>
        <th>اسم الصف الدراسي</th>
        <th>العنوان</th>
        <th>اسم المدينة</th>
        <th> الباص </th>
        <th>اسم السائق</th>
        <th>اسم المشرف</th>
        <th>النوع</th>
        <th>الديانة</th>
        <th>فصيلة الدم</th>
        <th>خط العرض</th>
        <th>خط الطول</th>
        <th>تاريخ الاضافة</th>


    </tr>
    </thead>
    <tbody>
    @foreach($students as $student)
        <tr>
            <td>{{ $student->name }}</td>
            <td>{{ $student->parent_key }}</td>
            <td>{{ $student->parent_secret }}</td>
            <td>{{ $student->phone }}</td>
            <td>{{ $student->Date_Birth }}</td>
            <td>{{ $student->schools->name }}</td>
            <td>{{ $student->grade->name}}</td>
            <td>{{ $student->classroom->name}}</td>
            <td>{{ $student->address }}</td>
            <td>{{ $student->city_name }}</td>
            @if ($student->bus_id != null)
            <td>{{ $student->bus->name}}</td>
            <td>{{ isset($student->bus->driver) != null ? $student->bus->driver->name : 'لم يتم تحديد السائق' }}</td>
            <td>{{ isset($student->bus->admin) != null ? $student->bus->admin->name : 'لم يتم تحديد المشرف' }}</td>
             @else
             <td>لا يسفخدم الباص</td>
             <td>لا يسفخدم الباص</td>
             <td>لا يسفخدم الباص</td>
             @endif

            <td>{{isset($student->gender->name) ? $student->gender->name : 'غير معرف'  }}</td>
            <td>{{isset($student->religion->name) ? $student->religion->name  : 'غير معرف' }}</td>
            <td>{{isset($student->typeBlood->name) ? $student->typeBlood->name : 'غير معرف'}}</td>
            <td>{{isset($student->latitude) ? $student->latitude  : 'غير معرف' }}</td>
            <td>{{isset($student->longitude) ? $student->longitude : 'غير معرف'}}</td>
            <td>{{ $student->created_at}}</td>
        </tr>
    @endforeach
    </tbody>
</table>


