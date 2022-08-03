@component('mail::message')
# Minuta de Reunión
 
Estimado(a) {{ $destino->nombre_completo }}, adjunto podrá encontrar la minuta de reunión correspondiente a los siguientes datos:

@component('mail::table')
|   Campo    |    Descripción      |
| :------------- |:-------------:|
| No.      | {{ $encabezado->id }}      |
| Fecha     | {{ $encabezado->fecha }} |
| Método     | {{ $encabezado->nombre_metodo }} |
@endcomponent

Saludos.<br>

@endcomponent