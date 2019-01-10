<?php

namespace App\Components;

use Mail;

class Email
{
    private $mail_data;

    public function sendMail($mail_data)
    {
        $this->mail_data = $mail_data = $this->defaultMailData($mail_data);
        try{
            $mail = Mail::send(
            'email/ics',
            ['mail_data' => $mail_data],
            function ($message) use ($mail_data) {
                $message->from($mail_data['sender_email'], $mail_data['sender_name']);
                $message->to($mail_data['to']);
                $message->subject($mail_data['subject']);

                if (isset($mail_data['reply_to']))
                    $message->replyTo($mail_data['reply_to']);

                foreach ($mail_data['mail_headers'] ?? [] as $header_key => $header)
                    $message->getSwiftMessage()->getHeaders()->addTextHeader($header_key, $header);

            });

            return $mail;
        } catch(Exception $e) {
            return $e->getMessage();
        }
    }

    public function sendMailWithICSFile($mail_data)
    {
        $this->mail_data = $mail_data = $this->defaultMailData($mail_data);
        try{
        return Mail::send(
            'email/ics',
            ['mail_data' => $mail_data],
            function ($message) use ($mail_data) {
                $message->from($mail_data['sender_email'], $mail_data['sender_name']);
                $message->to($mail_data['to']);
                $message->subject($mail_data['subject']);

                if (isset($mail_data['reply_to']))
                    $message->replyTo($mail_data['reply_to']);

                foreach ($mail_data['mail_headers'] ?? [] as $header_key => $header) {
                    $message->getSwiftMessage()->getHeaders()->addTextHeader($header_key, $header);
                }

                //$file = fopen("invite.ics", "w");
                //echo fwrite($file, $this->getICSFile());
                //fclose($file);
                //$message->attach('invite.ics', array('mime' => 'text/calendar; charset="utf-8"; method=REQUEST'));

                $message->setBody($this->getICSFile(), 'text/calendar; charset="utf-8";');
                $message->addPart('<p>Hello All!</p>', "text/html");
            });
        } catch(Exception $e) {
            return $e->getMessage();
        }
    }

    private function getICSFile()
    {
        $ics = new ICS([
                'location' => $this->mail_data['location'] ?? 'confrence room, Ucreate.it PVT LTD',
                'description' => $this->mail_data['description'] ?? 'This is event invitation, you can ignore this',
                'dtstart' => $this->mail_data['date_start'] ?? '2018-12-25 9:00AM',
                'dtend' => $this->mail_data['date_end'] ?? '2018-12-25 11:00AM',
                'summary' => $this->mail_data['summary'] ?? 'This is event invitation',
                'url' =>$this->mail_data['url'] ?? 'http://example.com'
            ]);

        return $ics->to_string();
    }


    public function defaultMailData($mail_data){
        $mail_data['from']         = $mail_data['from'] ?? getenv("SENDER_EMAIL");
        $mail_data['sender_email'] = $mail_data['from']?? getenv("SENDER_EMAIL");
        $mail_data['sender_name']  = $mail_data['sender_name'] ?? getenv("SENDER_NAME");
        $mail_data['mail_headers'] = $mail_data['mail_headers'] ?? ['X-PM-Tag' => 'openmind-email'];
        $mail_data['path']         = $mail_data['path'] ?? getenv('APP_URL');
        return $mail_data;
    }
}
