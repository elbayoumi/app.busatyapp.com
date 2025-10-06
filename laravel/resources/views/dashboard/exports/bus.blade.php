
    <table dir="rtl" lang="ar">
        <thead>
            <tr>
                <th class="no-border text-start heading" colspan="7">
              {{ date('Y-m-d') }} -  تاريخ  / {{ $bus->name }}  -  بيانات طلاب / باص 
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
               <td width="100%">{{ $index+1 }}</td>
               <td width="100%">{{ $student->name }}</td>
               <td width="100%">{{ $student->parent_key }}</td>
               <td width="100%">{{ $student->parent_secret }}</td>
               <td width="100%">{{ $student->grade->name }}</td>
               <td width="100%">{{ $student->classroom->name }}</td>
               <td width="100%">{{ $student->tr_trip_type() }}</td>

           </tr>
       @empty
           <tr>
               <td colspan="7">No student Data</td>
           </tr>
       @endforelse
        </tbody>
    </table>

