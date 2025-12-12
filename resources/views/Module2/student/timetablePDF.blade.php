<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 6px; font-size: 10px; text-align: center; }
        th { background: #f5d96d; }
        .class-box { background: #4a90e2; color: white; padding: 4px; border-radius: 4px; font-size: 10px; }
    </style>
</head>
<body>

<h2>My Timetable</h2>
<table>
    <thead>
        <tr>
            <th>Time</th>
            @foreach($dayColumns as $day)
                <th>{{ $day }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($timeSlots as $slot)
        <tr>
            <td>{{ $slot['label'] }}</td>
            @foreach($dayColumns as $day)
                <td>
                    @if(isset($slot['classes'][$day]))
                        <div class="class-box">
                            <strong>{{ $slot['classes'][$day]->Title ?? '' }}</strong><br>
                            {{ $slot['classes'][$day]->teacher?->user?->name ?? 'N/A' }}<br>
                            {{ $slot['classes'][$day]->Start_time ?? '' }} - {{ $slot['classes'][$day]->end_time ?? '' }}
                        </div>
                    @endif
                </td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
