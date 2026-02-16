<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\application;
use App\Models\Message;
use App\Models\pets;
use App\Models\usersdata;
use Carbon\Carbon;

class messagescontroller extends Controller
{
    function index(Request $request)
    {
        $user = usersdata::where('email', $request->session()->get('useremail'))->first();
        if (!$user) {
            return redirect('/login');
        }

        $allMessages = Message::with(['sender', 'receiver', 'pet'])
            ->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->orWhere('receiver_id', $user->id);
            })
            ->latest('created_at')
            ->get();

        $threads = $allMessages
            ->groupBy(function (Message $message) use ($user) {
                $otherId = $message->sender_id === $user->id ? $message->receiver_id : $message->sender_id;
                return $otherId.'|'.($message->pet_id ?? 0);
            })
            ->map(function ($threadMessages, $key) use ($user) {
                $latest = $threadMessages->first();
                [$otherId, $petId] = explode('|', $key);
                $otherUser = $latest->sender_id === $user->id ? $latest->receiver : $latest->sender;

                return [
                    'key' => $key,
                    'other_user' => $otherUser,
                    'pet' => $latest->pet,
                    'latest' => $latest,
                    'unread_count' => $threadMessages->where('receiver_id', $user->id)->where('read_at', null)->count(),
                ];
            })
            ->sortByDesc(fn ($thread) => $thread['latest']->created_at)
            ->values();

        $selectedThreadKey = $request->query('thread');
        if (!$selectedThreadKey && $threads->isNotEmpty()) {
            $selectedThreadKey = $threads->first()['key'];
        }

        $selectedMessages = collect();
        $selectedThread = null;

        if ($selectedThreadKey) {
            $selectedThread = $threads->firstWhere('key', $selectedThreadKey);
            if (!$selectedThread) {
                [$otherIdRaw, $petIdRaw] = array_pad(explode('|', $selectedThreadKey, 2), 2, null);
                $otherId = (int) $otherIdRaw;
                $petId = (int) $petIdRaw;

                if ($otherId > 0 && $petId > 0) {
                    $otherUser = usersdata::find($otherId);
                    $pet = pets::find($petId);

                    if ($otherUser && $pet) {
                        $ownerId = (int) $pet->user_id;
                        $currentUserId = (int) $user->id;
                        $isCurrentUserOwner = $currentUserId === $ownerId;
                        $isOtherUserOwner = $otherId === $ownerId;

                        if ($isCurrentUserOwner || $isOtherUserOwner) {
                            $applicantId = $isCurrentUserOwner ? $otherId : $currentUserId;
                            $hasApplication = application::where('pet_id', $petId)
                                ->where('user_id', $applicantId)
                                ->exists();

                            if ($hasApplication) {
                                $selectedThread = [
                                    'key' => $selectedThreadKey,
                                    'other_user' => $otherUser,
                                    'pet' => $pet,
                                    'latest' => null,
                                    'unread_count' => 0,
                                ];
                            }
                        }
                    }
                }
            }

            if ($selectedThread) {
                $selectedMessages = Message::with(['sender', 'receiver'])
                    ->where('pet_id', $selectedThread['pet']?->id)
                    ->where(function ($query) use ($user, $selectedThread) {
                        $query
                            ->where('sender_id', $user->id)
                            ->where('receiver_id', $selectedThread['other_user']->id)
                            ->orWhere(function ($q) use ($user, $selectedThread) {
                                $q->where('sender_id', $selectedThread['other_user']->id)
                                    ->where('receiver_id', $user->id);
                            });
                    })
                    ->orderBy('created_at')
                    ->get();

                Message::whereIn('id', $selectedMessages->pluck('id'))
                    ->where('receiver_id', $user->id)
                    ->whereNull('read_at')
                    ->update(['read_at' => Carbon::now()]);
            }
        }

        return view('messages', [
            'user' => $user,
            'threads' => $threads,
            'selectedThread' => $selectedThread,
            'selectedMessages' => $selectedMessages,
        ]);
    }

    function store(Request $request)
    {
        $data = $request->validate([
            'receiver_id' => ['required', 'integer', 'exists:usersdatas,id'],
            'pet_id' => ['required', 'integer', 'exists:pets,id'],
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $user = usersdata::where('email', $request->session()->get('useremail'))->first();
        if (!$user) {
            return redirect('/login');
        }

        $pet = pets::findOrFail($data['pet_id']);
        $senderId = (int) $user->id;
        $receiverId = (int) $data['receiver_id'];
        $ownerId = (int) $pet->user_id;

        if ($senderId === $receiverId) {
            return back()->with('error', 'You cannot send a message to yourself.');
        }

        $isSenderOwner = $senderId === $ownerId;
        $isReceiverOwner = $receiverId === $ownerId;

        if (!$isSenderOwner && !$isReceiverOwner) {
            return back()->with('error', 'Messages for this pet must include the listing owner.');
        }

        $applicantId = $isSenderOwner ? $receiverId : $senderId;
        $hasApplication = application::where('pet_id', $pet->id)
            ->where('user_id', $applicantId)
            ->exists();

        if (!$hasApplication) {
            return back()->with('error', 'You can only message users linked to this pet application.');
        }

        $createdMessage = Message::create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'pet_id' => $pet->id,
            'body' => $data['body'],
        ]);
        $createdMessage->loadMissing(['sender', 'receiver', 'pet']);

        $messagePayload = [
            'id' => $createdMessage->id,
            'sender_id' => $createdMessage->sender_id,
            'receiver_id' => $createdMessage->receiver_id,
            'pet_id' => $createdMessage->pet_id,
            'body' => $createdMessage->body,
            'sender_name' => $createdMessage->sender?->fullname,
            'receiver_name' => $createdMessage->receiver?->fullname,
            'pet_name' => $createdMessage->pet?->name,
            'created_at' => $createdMessage->created_at?->toIso8601String(),
            'created_at_human' => $createdMessage->created_at?->format('M d, Y h:i A'),
        ];

        $this->broadcastRealtimeMessage($messagePayload);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $messagePayload,
            ], 201);
        }

        return redirect('/messages?thread='.$receiverId.'|'.$pet->id);
    }

    private function broadcastRealtimeMessage(array $message): void
    {
        $socketUrl = env('CHAT_SOCKET_SERVER_URL', env('CHAT_SOCKET_URL', 'ws://127.0.0.1:8081'));
        $parts = parse_url($socketUrl);
        $scheme = $parts['scheme'] ?? 'ws';

        if (!$parts || !in_array($scheme, ['ws', 'wss'], true)) {
            return;
        }

        $host = $parts['host'] ?? null;
        if (!$host) {
            return;
        }

        $port = $parts['port'] ?? ($scheme === 'wss' ? 443 : 80);
        $path = ($parts['path'] ?? '/').(isset($parts['query']) ? '?'.$parts['query'] : '');
        $transport = $scheme === 'wss' ? 'tls' : 'tcp';
        $context = null;
        if ($scheme === 'wss') {
            $context = stream_context_create([
                'ssl' => [
                    'verify_peer' => true,
                    'verify_peer_name' => true,
                ],
            ]);
        }

        $client = @stream_socket_client(
            "{$transport}://{$host}:{$port}",
            $errno,
            $errorString,
            1,
            STREAM_CLIENT_CONNECT,
            $context
        );
        if (!$client) {
            return;
        }

        stream_set_timeout($client, 1);

        try {
            $key = base64_encode(random_bytes(16));
        } catch (\Throwable $th) {
            @fclose($client);
            return;
        }

        $handshake =
            "GET {$path} HTTP/1.1\r\n".
            "Host: {$host}:{$port}\r\n".
            "Upgrade: websocket\r\n".
            "Connection: Upgrade\r\n".
            "Sec-WebSocket-Version: 13\r\n".
            "Sec-WebSocket-Key: {$key}\r\n\r\n";

        @fwrite($client, $handshake);

        $response = '';
        for ($i = 0; $i < 10; $i++) {
            $chunk = @fread($client, 1024);
            if ($chunk === false || $chunk === '') {
                usleep(10000);
                continue;
            }
            $response .= $chunk;
            if (str_contains($response, "\r\n\r\n")) {
                break;
            }
        }

        if (!str_contains($response, '101 Switching Protocols')) {
            @fclose($client);
            return;
        }

        $payload = json_encode([
            'type' => 'message.created',
            'message' => $message,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        if (!is_string($payload)) {
            @fclose($client);
            return;
        }

        @fwrite($client, $this->toMaskedWebSocketFrame($payload));
        @fclose($client);
    }

    private function toMaskedWebSocketFrame(string $payload): string
    {
        $length = strlen($payload);
        $header = chr(0x81);

        if ($length <= 125) {
            $header .= chr(0x80 | $length);
        } elseif ($length <= 65535) {
            $header .= chr(0x80 | 126).pack('n', $length);
        } else {
            $header .= chr(0x80 | 127).pack('NN', 0, $length);
        }

        try {
            $mask = random_bytes(4);
        } catch (\Throwable $th) {
            $mask = "\x00\x00\x00\x00";
        }

        $maskedPayload = '';
        for ($i = 0; $i < $length; $i++) {
            $maskedPayload .= $payload[$i] ^ $mask[$i % 4];
        }

        return $header.$mask.$maskedPayload;
    }
}
