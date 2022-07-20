
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
        <td width="35%"></td>
        <td rowspan="2"></td>
    </tr>
    <tr>
        <td class="row">
            Coordinación, Sección o Unidad:
        </td>
        <td></td>
    </tr>
    <tr>
        <td class="row">
            Método (presencial, virtual, etc):
        </td>
        <td></td>
        <td rowspan="2"></td>
    </tr>
    <tr>
        <td class="row">
            Responsable de organizar la agenda y transcribir acuerdos/tareas de la semana:
        </td>
        <td></td>
    </tr>
</table>

<table style="margin-top: 25px;">
    <tr>
        <td colspan="3" class="background title row">
            <span>PARTICIPANTES</span>
        </td>
    </tr>
    <tr width="100%">
        <td width="8%" class="row column-number">1</td>
        <td colspan="2" class="row">
            Herson Roely Chur Chinchilla
        </td>
    </tr>
</table>

<table style="margin-top: 25px;">
    <tr>
        <td colspan="3" class="background title row">
            <span>AGENDA</span>
        </td>
    </tr>
    @foreach ($puntos_agenda as $key => $punto)
        <tr>
            <td width="8%" class="row column-number">
                {{ $key + 1}}
            </td>
            <td colspan="2" class="row">
                {{ $punto['text'] }}
            </td>
        </tr>
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
        <tr>
            <td width="8%" class="row column-number">{{ $key + 1 }}</td>
            <td width="55%" class="row">
                {{ $pendiente['actividad']}}
            </td>
            <td width="37%" class="row">
                {{ $pendiente['responsable'] }}
            </td>
        </tr>
    @endforeach
</table>
