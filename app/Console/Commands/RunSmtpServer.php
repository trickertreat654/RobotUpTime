<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpMimeMailParser\Parser;
use Illuminate\Support\Facades\Storage;


class RunSmtpServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:run-smtp-server';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $server = stream_socket_server("192.168.96.190:1025", $errno, $errstr);
        if (!$server) {
            $this->error("$errstr ($errno)");
            return;
        }

        $this->info("SMTP server running on port 1025...");

        while ($conn = stream_socket_accept($server)) {
            $this->handleConnection($conn);
        }
    }

    protected function handleConnection($conn)
    {
        fwrite($conn, "220 Welcome to the SMTP server\r\n");

        $data = '';
        while ($line = fgets($conn)) {
            $data .= $line;
            if (rtrim($line) == '.') {
                break;
            }
        }

        // Log the raw data for debugging purposes
        Storage::put('raw_email.txt', $data);

        // Handle the data
        $this->handleData($data);

        fwrite($conn, "250 Message accepted for delivery\r\n");
        fclose($conn);
    }

    protected function handleData($data)
    {
        try {
            // Parse email data
            $parser = new Parser();
            $parser->setText($data);

            $from = $parser->getHeader('from');
            $to = $parser->getHeader('to');

            $this->info("From: $from");
            $this->info("To: $to");

            // Save attachments
            foreach ($parser->getAttachments() as $attachment) {
                $filename = $attachment->getFilename();
                $content = $attachment->getContent();
                Storage::put("attachments/$filename", $content);
                $this->info("Attachment saved: $filename");
            }
        } catch (\Exception $e) {
            $this->error("Error parsing email: " . $e->getMessage());
            // Log detailed error message for debugging
            Storage::put('parsing_error.txt', $e->getMessage());
        }
    }
}
