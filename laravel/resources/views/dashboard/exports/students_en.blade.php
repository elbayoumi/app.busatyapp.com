
<table dir="ltr">
    <thead>
    <tr>
        <th>name</th>
        <th>first code</th>
        <th>second code</th>
        <th>phone</th>
        <th>Date of birth</th>
        <th>School name</th>
        <th>Stage name</th>
        <th>Class name</th>
        <th>Title</th>
        <th>City Name</th>
        <th>bus </th>
        <th>Driver's name</th>
        <th>Admin name</th>
        <th>Type</th>
        <th>Religion</th>
        <th>Blood type</th>
        <th>latitude</th>
        <th>longitude</th>
        <th>Date added</th>


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
            <td>{{ isset($student->bus->driver) != null ? $student->bus->driver->name : 'Driver not identified' }}</td>
            <td>{{ isset($student->bus->admin) != null ? $student->bus->admin->name : 'Admin not specified' }}</td>
             @else
             <td>The bus is not used</td>
             <td>The bus is not used</td>
             <td>The bus is not used</td>
             @endif

            <td>{{isset($student->gender->name) ? $student->gender->name : 'undefined'  }}</td>
            <td>{{isset($student->religion->name) ? $student->religion->name  : 'undefined' }}</td>
            <td>{{isset($student->typeBlood->name) ? $student->typeBlood->name : 'undefined'}}</td>
            <td>{{isset($student->latitude) ? $student->latitude  : 'undefined' }}</td>
            <td>{{isset($student->longitude) ? $student->longitude : 'undefined'}}</td>
            <td>{{ $student->created_at}}</td>
        </tr>
    @endforeach
    </tbody>
</table>


