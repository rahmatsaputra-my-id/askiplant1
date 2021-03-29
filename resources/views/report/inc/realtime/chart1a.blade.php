<table class="table table-bordered" style="font-weight: bold" id="dataTable">
    <thead style="font-size: 20px">
        <tr>
            <th style="text-align: center;">Mesin</th>
            <th style="text-align: center;">Nama Part</th>
            <th style="text-align: center;">SPH</th>
            <th style="text-align: center;">Plan</th>
            <th style="text-align: center;">Actual</th>
            <th style="text-align: center;">Progress</th>
        </tr>
    </thead>

    <tbody style="font-size: 16px">
        @foreach ($planachiev as $data)
        <tr>
        @if ($data->no_mc)
        <td class="text-center">{{$data->no_mc}}</td>
        @else
        <td class="text-center"></td>
        @endif
        @if ($data->nama_part)
        <td class="text-center">{{$data->nama_part}}</td>
        @else
        <td class="text-center"></td>
        @endif
        @if ($data->sph)
        <td class="text-center">{{$data->sph}}</td>
        @else
        <td class="text-center"></td>
        @endif
        @if ($data->target_total)
        <td class="text-center">{{$data->target_total}}</td>
        @else
        <td class="text-center"></td>
        @endif
        @if ($data->achiev)
        <td class="text-center">{{$data->achiev}}</td>
        @else
        <td class="text-center"></td>
        @endif
        @if ($data->progres)
        <td class="text-center">
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: {{round($data->progres, 2)}}%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">{{round($data->progres, 2)}}%</div>
            </div>
        </td>
        @else
        <td class="text-center">
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
            </div>
        </td>
        @endif
        </tr>
        @endforeach
    </tbody>
</table>
