<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{$report->type}}</title>
</head>
<body>
<div style="text-align: center; font-size: 38px; font-weight: bold;font-family: Verdana">{{$report->type}} </div>
<div style="margin-top: 100px; text-align: center;">
@foreach ($attachments as $index=>  $value)

    <img width="30%" src="data:image/png;charset=utf-8;base64, {{ base64_encode($value->toPng()) }}" style="margin-top:20px; margin-bottom: 20px;margin-left: 10px;margin-right: 10px;"/>
@endforeach
    <img width="30%" src="data:image/png;base64, {{ base64_encode($qr) }}" />
</div>
<table>
    <tr>
        <th style="text-align: right">Nombre</th>
        <td>{{ $report->name }}</td>
    </tr>
    <tr>
        <th style="text-align: right">Departamento</th>
        <td>{{ $report->Department()->first()->name??'--' }}</td>
    </tr>
    <tr>
        <th style="text-align: right">Ciudad</th>
        <td>{{ $report->City()->first()->name??'--' }}</td>
    </tr>
    <tr>
        <th style="text-align: right">Barrio</th>
        <td>{{ $report->Neighborhood()->first()->name??'--' }}</td>
    </tr>
    <tr>
        <th style="text-align: right">Dirección</th>
        <td>{{ $report->address }}</td>
    </tr>
    <tr>
        <th style="text-align: right">Fecha</th>
        <td>{{ $report->date->isoFormat('DD/MMM/YYYY HH:mm') }}</td>
    </tr>
    <tr>
        <th style="text-align: right">Descripción</th>
        <td>{{ $report->description }}</td>
    </tr>
</table>
</body>
</html>
