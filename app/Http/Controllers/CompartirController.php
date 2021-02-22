<?php 

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    use Barryvdh\DomPDF\Facade as PDF;

    use App\Reunion;
    use App\ReunionEnvio;
    use App\ReunionEnvioDetalle;
    use App\Persona;

    // require base_path() . '/vendor/phpmailer/phpmailer/src/PHPMailer.php';
    // require base_path() . '/vendor/phpmailer/phpmailer/src/Exception.php';
    // require base_path() . '/vendor/phpmailer/phpmailer/src/SMTP.php';

    require base_path() . '/vendor/PHPMailer_old/PHPMailerAutoload.php';


    class CompartirController extends Controller{

        public function compartir_bitacora(Request $request){

            $meses = [
                "enero",
                "febrero",
                "marzo",
                "abril",
                "mayo",
                "junio",
                "julio",
                "agosto",
                "septiembre",
                "octubre",
                "noviembre",
                "diciembre"
            ];

            $fecha = "Guatemala " . date('d') . ' de ' . $meses[date('n') - 1] . ' del ' . date('Y');

            // Buscar la reunión
            //$reunion = Reunion::find($request->id);

            $reunion = app('db')->select("  SELECT *, DATE_FORMAT(created_at, '%d/%m/%Y') as created_at
                                            FROM reunion
                                            WHERE id = $request->id
                                            AND deleted_at IS NULL");

            $reunion = $reunion[0];

            $data_pdf = [
                "content" => $reunion->contenido,
                "fecha" => $fecha
            ];

            PDF::setOptions(['defaultFont' => 'arial', 'isRemoteEnabled' => true, 'debugKeepTemp' => true, 'tempDir' => '/public/pdf/']);
            $pdf = PDF::loadView('pdf', $data_pdf);

            $nombre_archivo = uniqid() . '.pdf';

            $pdf->save('pdf/' . $nombre_archivo);

            // Registrar el envio
            $reunion_envio = new ReunionEnvio();

            $reunion_envio->id_reunion = $request->id;
            $reunion_envio->documento = $nombre_archivo;
            $reunion_envio->enviado_por = $request->enviado_por;

            $reunion_envio->save();

            // Registrar el envio a cada uno de los integrantes
            foreach ($request->compartir as $contacto) {
                
                $persona = Persona::find($contacto);

                if ($persona->email) {
                    
                    // Enviar la bitácora a cada uno de los correos

                    $mail = new \PHPMailer(true);

                    $mail->SMTPDebug  = 0; 
                    $mail->Host = 'mail2.muniguate.com';  
                    $mail->isSMTP();  
                    $mail->Username   = 'soportecatastro';                  
                    $mail->Password   = 'catastro2015';
                    $mail->CharSet = 'UTF-8';


                    $mail->setFrom('noreply@muniguate.com');
                    $mail->addAddress($persona->email); 
                    $mail->Subject = 'Bitácora de Reunión ' . $reunion->created_at;
                    $mail->Body    = '<p>Se adjunta bitácora de reunión con fecha: ' . $reunion->created_at . '</p>';
                    $mail->addAttachment('pdf/' . $nombre_archivo, 'Bitácora ' . $reunion->created_at);
                    $mail->isHTML(true);  

                    try {
                        
                        $result_send = $mail->send();

                        $envio_detalle = new ReunionEnvioDetalle();

                        $envio_detalle->id_envio = $reunion_envio->id;
                        $envio_detalle->id_persona = $persona->id;
                        $envio_detalle->email = $persona->email;

                        $envio_detalle->save();
                        
                    } catch (\Throwable $th) {
                        //throw $th;
                    }

                }

            }

            $data = [
                "status" => 200,
                "title" => "Excelente!",
                "message" => "La reunión a sido compartida exitosamente",
                "type" => "success"
            ];

            return response()->json($data);

        }

        public function historial_envios(Request $request){

            // $envios = ReunionEnvio::where('id_reunion', $request->id)->get();

            $envios = app('db')->select("   SELECT *, DATE_FORMAT(created_at, '%d/%m/%Y %H:%m:%i') as created_at
                                            FROM reunion_envio
                                            WHERE id_reunion = $request->id");

            foreach ($envios as &$envio) {
                
                $envio->active = false;

                $persona = Persona::find($envio->enviado_por);

                $envio->persona_envia = $persona->nombres . ' ' . $persona->apellidos;

                // Detalle del envio

                $detalle_envio = ReunionEnvioDetalle::where('id_envio', $envio->id)->get();

                foreach ($detalle_envio as &$item) {
                    
                    $persona = Persona::find($item->id_persona);

                    $item->persona_envio = $persona->nombres . ' ' . $persona->apellidos;
                    $item->avatar = $persona->avatar;
                    $item->cargo = $persona->cargo;

                }

                $envio->detalle_envio = $detalle_envio;

            }

            return response()->json($envios);

        }

        public function test_mail(){

            $mail = new \PHPMailer(true);

            $mail->SMTPDebug  = 1; 
            $mail->Host = 'mail2.muniguate.com';  
            $mail->isSMTP();  
            $mail->charset = 'UTF-8';                
            $mail->Username   = 'soportecatastro';                  
            $mail->Password   = 'catastro2015';

            $mail->setFrom('noreply@muniguate.com');
            $mail->addAddress('hchur@muniguate.com'); 
            $mail->Subject = 'Bitácora de Reunión';
            $mail->Body    = 'Se les comparte la bitácora de la reunión.';
            $mail->isHTML(true);  

            $mail->send();
        }

    }

?>