<?php

namespace App\Services;

use Gelf\Publisher;
use Gelf\Transport\UdpTransport;

class GraylogSetup
{
    public function getGelfPublisher() : Publisher
    {
        $transport = new UdpTransport('graylog', 12201,
            UdpTransport::CHUNK_SIZE_LAN);
        $publisher = new Publisher();
        $publisher->addTransport($transport);
        return $publisher;
    }
}
