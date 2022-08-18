
<style>
    table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
    }
    table{
        width: 100%;
    }
    .row{
        height: 30px;
        padding: 5px;
    }
    .title{
        text-align: center;
    }
    .background{
        background-color: #204E79;
        color: #fff;
    }
    .column-number{
        text-align: center;
    }

</style>
<table>
    <tr class="title">
        <td colspan="3">
            <h3>MINUTA DE REUNIÓN</h3>
        </td>
    </tr>
    <tr>
        <td width="35%" class="row">
            Minuta de reunión número: 
        </td>
        <td width="35%" class="row">
            {{ $encabezado->id }}
        </td>
        <td class="row" rowspan="2">
            Fecha:
            <br>
            {{ $encabezado->fecha }}
        </td>
    </tr>
    <tr>
        <td class="row">
            Coordinación, Sección o Unidad:
        </td>
        <td class="row">
            {{ $encabezado->seccion }}
        </td>
    </tr>
    <tr>
        <td class="row">
            Método (presencial, virtual, etc):
        </td>
        <td class="row">
            {{ $encabezado->nombre_metodo }}
        </td>
        <td class="row" rowspan="2">
            Hora Inicio:
            <br>
            {{ $encabezado->hora_inicio }}
            <br>
            <br>
            Hora Finalización
            <br>
            {{ $encabezado->hora_fin }}
        </td>
    </tr>
    <tr>
        <td class="row">
            Responsable de organizar la agenda y transcribir acuerdos/tareas de la semana:
        </td>
        <td class="row">
            {{ $encabezado->responsable }}
        </td>
    </tr>
</table>

<table style="margin-top: 25px;">
    <tr>
        <td colspan="3" class="background title row">
            <span>PARTICIPANTES</span>
        </td>
    </tr>
    @foreach($participantes as $key => $participante)
        <tr width="100%">
            <td width="8%" class="row column-number">
                {{ $key + 1 }}
            </td>
            <td colspan="2" class="row">
                {{ $participante['nombre'] }} -  {{ $participante['area'] }}
            </td>
        </tr>
    @endforeach
</table>

<table style="margin-top: 25px;">
    <tr>
        <td colspan="3" class="background title row">
            <span>AGENDA</span>
        </td>
    </tr>
    @foreach ($puntos_agenda as $key => $punto)
        @if($punto['text'] != null)
        <tr>
            <td width="8%" class="row column-number">
                {{ $key + 1}}
            </td>
            <td colspan="2" class="row">
                {{ $punto['text'] }}
            </td>
        </tr>
        @endif
    @endforeach
</table>

<table style="margin-top: 25px;">
    <tr>
        <td colspan="2" class="background title row">
            <span>TAREAS PENDIENTES</span>
        </td>
        <td class="background title">
            <span>RESPONSABLE</span>
        </td>
    </tr>
    @foreach ($pendientes as $key => $pendiente)
        @if($pendiente['actividad'] != null)
        <tr>
            <td width="8%" class="row column-number">{{ $key + 1 }}</td>
            <td width="55%" class="row">
                {{ $pendiente['actividad']}}
            </td>
            <td width="37%" class="row">
                {{ array_key_exists('nombre_completo', $pendiente) ? $pendiente['nombre_completo'] : null }}
            </td>
        </tr>
        @endif
    @endforeach
</table>
