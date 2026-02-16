<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('chat:socket {host=127.0.0.1} {port=8081}', function (string $host, int $port) {
    $server = @stream_socket_server("tcp://{$host}:{$port}", $errno, $errorString);

    if (!$server) {
        $this->error("Unable to start socket server: {$errorString} ({$errno})");
        return 1;
    }

    stream_set_blocking($server, false);
    $this->info("Chat socket server started on ws://{$host}:{$port}");
    $this->line('Press Ctrl+C to stop.');

    $clients = [];
    $closeClient = static function (array &$clients, int $id): void {
        if (!isset($clients[$id])) {
            return;
        }

        @fclose($clients[$id]['stream']);
        unset($clients[$id]);
    };

    $makeFrame = static function (string $payload, int $opcode = 0x1): string {
        $head = chr(0x80 | ($opcode & 0x0F));
        $length = strlen($payload);

        if ($length <= 125) {
            return $head.chr($length).$payload;
        }

        if ($length <= 65535) {
            return $head.chr(126).pack('n', $length).$payload;
        }

        return $head.chr(127).pack('NN', 0, $length).$payload;
    };

    $decodeFrames = static function (string &$buffer): array {
        $frames = [];

        while (strlen($buffer) >= 2) {
            $first = ord($buffer[0]);
            $second = ord($buffer[1]);
            $opcode = $first & 0x0F;
            $masked = ($second & 0x80) === 0x80;
            $length = $second & 0x7F;
            $offset = 2;

            if ($length === 126) {
                if (strlen($buffer) < $offset + 2) {
                    break;
                }
                $length = unpack('n', substr($buffer, $offset, 2))[1];
                $offset += 2;
            } elseif ($length === 127) {
                if (strlen($buffer) < $offset + 8) {
                    break;
                }
                $parts = unpack('Nhigh/Nlow', substr($buffer, $offset, 8));
                $offset += 8;
                if ($parts['high'] !== 0) {
                    $buffer = '';
                    break;
                }
                $length = $parts['low'];
            }

            if ($masked) {
                if (strlen($buffer) < $offset + 4) {
                    break;
                }
                $mask = substr($buffer, $offset, 4);
                $offset += 4;
            } else {
                $mask = '';
            }

            if (strlen($buffer) < $offset + $length) {
                break;
            }

            $payload = substr($buffer, $offset, $length);
            $buffer = substr($buffer, $offset + $length);

            if ($masked) {
                $unmasked = '';
                for ($i = 0; $i < $length; $i++) {
                    $unmasked .= $payload[$i] ^ $mask[$i % 4];
                }
                $payload = $unmasked;
            }

            $frames[] = [
                'opcode' => $opcode,
                'payload' => $payload,
            ];
        }

        return $frames;
    };

    while (true) {
        $readStreams = [$server];
        foreach ($clients as $client) {
            $readStreams[] = $client['stream'];
        }

        $write = null;
        $except = null;
        $ready = @stream_select($readStreams, $write, $except, 1);

        if ($ready === false) {
            usleep(100000);
            continue;
        }

        foreach ($readStreams as $stream) {
            if ($stream === $server) {
                $connection = @stream_socket_accept($server, 0);
                if (!$connection) {
                    continue;
                }

                stream_set_blocking($connection, false);
                $connectionId = (int) $connection;
                $clients[$connectionId] = [
                    'stream' => $connection,
                    'handshake' => false,
                    'buffer' => '',
                    'user_id' => null,
                ];

                continue;
            }

            $id = (int) $stream;
            if (!isset($clients[$id])) {
                continue;
            }

            $chunk = @fread($stream, 8192);
            if ($chunk === '' || $chunk === false) {
                if (feof($stream)) {
                    $closeClient($clients, $id);
                }
                continue;
            }

            if (!$clients[$id]['handshake']) {
                $clients[$id]['buffer'] .= $chunk;
                if (!str_contains($clients[$id]['buffer'], "\r\n\r\n")) {
                    continue;
                }

                $request = $clients[$id]['buffer'];
                $clients[$id]['buffer'] = '';

                if (!preg_match('/Sec-WebSocket-Key:\s*(.+)\r\n/i', $request, $keyMatch)) {
                    $closeClient($clients, $id);
                    continue;
                }

                preg_match('/GET\s+([^\s]+)\s+HTTP\/1\.1/i', $request, $targetMatch);
                $target = $targetMatch[1] ?? '/';
                parse_str(parse_url($target, PHP_URL_QUERY) ?? '', $queryParams);

                $socketKey = trim($keyMatch[1]);
                $accept = base64_encode(sha1($socketKey.'258EAFA5-E914-47DA-95CA-C5AB0DC85B11', true));
                $response =
                    "HTTP/1.1 101 Switching Protocols\r\n".
                    "Upgrade: websocket\r\n".
                    "Connection: Upgrade\r\n".
                    "Sec-WebSocket-Accept: {$accept}\r\n\r\n";

                @fwrite($stream, $response);
                $clients[$id]['handshake'] = true;
                $clients[$id]['user_id'] = isset($queryParams['user_id']) ? (int) $queryParams['user_id'] : null;
                continue;
            }

            $clients[$id]['buffer'] .= $chunk;
            $frames = $decodeFrames($clients[$id]['buffer']);

            foreach ($frames as $frame) {
                $opcode = $frame['opcode'];

                if ($opcode === 0x8) {
                    $closeClient($clients, $id);
                    continue 2;
                }

                if ($opcode === 0x9) {
                    @fwrite($stream, $makeFrame($frame['payload'], 0xA));
                    continue;
                }

                if ($opcode !== 0x1) {
                    continue;
                }

                $payload = json_decode($frame['payload'], true);
                if (!is_array($payload) || ($payload['type'] ?? null) !== 'message.created') {
                    continue;
                }

                $message = $payload['message'] ?? [];
                $senderId = isset($message['sender_id']) ? (int) $message['sender_id'] : 0;
                $receiverId = isset($message['receiver_id']) ? (int) $message['receiver_id'] : 0;
                if ($senderId <= 0 || $receiverId <= 0) {
                    continue;
                }

                $encoded = $makeFrame(json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
                foreach ($clients as $targetClient) {
                    if (!$targetClient['handshake']) {
                        continue;
                    }
                    $targetUserId = (int) ($targetClient['user_id'] ?? 0);
                    if ($targetUserId !== $senderId && $targetUserId !== $receiverId) {
                        continue;
                    }
                    @fwrite($targetClient['stream'], $encoded);
                }
            }
        }
    }
})->purpose('Run websocket relay server for chat updates');
