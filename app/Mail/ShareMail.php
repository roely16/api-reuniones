<?php 

	namespace App\Mail;

	use Illuminate\Bus\Queueable;
	use Illuminate\Mail\Mailable;
	use Illuminate\Queue\SerializesModels;

	class ShareMail extends Mailable{

		protected $data;

		public function __construct($data){

			$this->data = $data;

		}

		public function build(){

            $filename = $this->data['filename'];

			return $this->subject('Minuta de Reunión')
                        ->markdown('mails.share')
                        ->with($this->data)
                        ->attach('pdf/' . $filename, [
                            'as' => 'minuta_reunion.pdf',
                            'mime' => 'application/pdf',
                        ]);;

		}

	}

?>