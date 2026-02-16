<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Messages</title>
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#2b93ee",
                        "background-light": "#f6f7f8",
                        "background-dark": "#101a22",
                    },
                    fontFamily: {
                        "display": ["Plus Jakarta Sans", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        };
    </script>
    <style>
        body {
            font-family: "Plus Jakarta Sans", sans-serif;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-[#111518] dark:text-gray-100 antialiased h-screen flex overflow-hidden">
    @include('partials.dashboard-sidebar', ['user' => $user, 'activeNav' => 'messages'])

    <main class="flex-1 h-full overflow-hidden bg-background-light dark:bg-background-dark">
        <div class="h-full max-w-[1200px] mx-auto p-6 md:p-8 flex flex-col gap-4">
            <h1 class="text-[#111518] dark:text-white tracking-tight text-3xl font-bold leading-tight">Messages</h1>

            @if (session('success'))
                <div class="shrink-0 rounded-lg border border-green-200 bg-green-50 p-3 text-green-700">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="shrink-0 rounded-lg border border-red-200 bg-red-50 p-3 text-red-700">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="shrink-0 rounded-lg border border-red-200 bg-red-50 p-3 text-red-700">{{ $errors->first() }}</div>
            @endif

            <div class="grid min-h-0 flex-1 overflow-hidden rounded-xl border border-[#dbe1e6] bg-white dark:bg-[#1a2632] dark:border-gray-700 shadow-sm md:grid-cols-[320px,1fr]">
            <aside class="flex min-h-0 flex-col border-r bg-slate-50">
                <div class="border-b px-4 py-3">
                    <h2 class="font-semibold">Threads</h2>
                </div>

                <div id="threadsList" class="min-h-0 flex-1 overflow-y-auto">
                    @forelse ($threads as $thread)
                        <a
                            href="/messages?thread={{ urlencode($thread['key']) }}"
                            data-thread-key="{{ $thread['key'] }}"
                            class="block border-b px-4 py-3 hover:bg-slate-100 {{ $selectedThread && $selectedThread['key'] === $thread['key'] ? 'bg-slate-100' : '' }}"
                        >
                            <div class="mb-1 flex items-center justify-between gap-2">
                                <p data-thread-name class="truncate font-semibold">{{ $thread['other_user']->fullname ?? 'Unknown user' }}</p>
                                @if ($thread['unread_count'] > 0)
                                    <span data-thread-unread data-unread-count="{{ $thread['unread_count'] }}" class="rounded-full bg-blue-600 px-2 py-0.5 text-xs font-bold text-white">{{ $thread['unread_count'] }}</span>
                                @else
                                    <span data-thread-unread data-unread-count="0" class="hidden rounded-full bg-blue-600 px-2 py-0.5 text-xs font-bold text-white"></span>
                                @endif
                            </div>
                            <p data-thread-pet class="truncate text-xs text-slate-500">{{ $thread['pet']?->name ? 'Pet: '.$thread['pet']->name : 'General' }}</p>
                            <p data-thread-body class="mt-1 truncate text-sm text-slate-600">{{ $thread['latest']->body }}</p>
                            <p data-thread-time class="mt-1 text-xs text-slate-400">{{ $thread['latest']->created_at->diffForHumans() }}</p>
                        </a>
                    @empty
                        <p data-empty-threads class="p-4 text-sm text-slate-500">No conversations yet.</p>
                    @endforelse
                </div>
            </aside>

            <section
                class="flex min-h-0 flex-col"
                data-current-user-id="{{ $user->id }}"
                data-selected-other-user-id="{{ $selectedThread ? $selectedThread['other_user']->id : '' }}"
                data-selected-pet-id="{{ $selectedThread && $selectedThread['pet'] ? $selectedThread['pet']->id : '' }}"
            >
                @if ($selectedThread)
                    <div class="border-b px-5 py-3">
                        <h2 class="font-semibold">{{ $selectedThread['other_user']->fullname }}</h2>
                        <p class="text-sm text-slate-500">About pet: {{ $selectedThread['pet']?->name ?? 'N/A' }}</p>
                    </div>

                    <div id="chatMessages" class="flex-1 space-y-3 overflow-y-auto p-5">
                        @forelse ($selectedMessages as $message)
                            <div class="{{ $message->sender_id === $user->id ? 'text-right' : 'text-left' }}">
                                <div class="inline-block max-w-[80%] rounded-lg px-3 py-2 {{ $message->sender_id === $user->id ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-900' }}">
                                    {{ $message->body }}
                                </div>
                                <p class="mt-1 text-xs text-slate-400">{{ $message->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        @empty
                            <p id="emptyChatPlaceholder" class="text-sm text-slate-500">No messages in this thread yet.</p>
                        @endforelse
                    </div>

                    @if ($selectedThread['pet'])
                        <form id="messageForm" method="POST" action="/messages" class="border-t p-4">
                            @csrf
                            <input type="hidden" name="receiver_id" value="{{ $selectedThread['other_user']->id }}">
                            <input type="hidden" name="pet_id" value="{{ $selectedThread['pet']->id }}">
                            <div class="flex gap-2">
                                <input id="messageBody" type="text" name="body" placeholder="Type your message" class="w-full rounded border border-slate-300 px-3 py-2" required>
                                <button type="submit" class="rounded bg-blue-600 px-4 py-2 font-semibold text-white">Send</button>
                            </div>
                        </form>
                    @endif
                @else
                    <div class="flex h-full items-center justify-center p-8 text-slate-500">
                        Select a thread to view messages.
                    </div>
                @endif
            </section>
            </div>
        </div>
    </main>
    <script>
        (function () {
            const currentUserId = Number(@json($user->id));
            const selectedThread = @json($selectedThread ? ['otherUserId' => $selectedThread['other_user']->id, 'petId' => $selectedThread['pet']?->id] : null);
            const form = document.getElementById('messageForm');
            const input = document.getElementById('messageBody');
            const chatMessages = document.getElementById('chatMessages');
            const threadsList = document.getElementById('threadsList');
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const configuredSocketBase = @json(env('CHAT_SOCKET_URL'));
            const socketBase = configuredSocketBase || `${window.location.protocol === 'https:' ? 'wss' : 'ws'}://${window.location.hostname}:8081`;
            const activeThreadKey = @json($selectedThread['key'] ?? null);

            const scrollToBottom = () => {
                if (chatMessages) {
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }
            };

            const appendMessage = (message, isMine) => {
                if (!chatMessages) return;

                const placeholder = document.getElementById('emptyChatPlaceholder');
                if (placeholder) placeholder.remove();

                const wrapper = document.createElement('div');
                wrapper.className = isMine ? 'text-right' : 'text-left';

                const bubble = document.createElement('div');
                bubble.className = `inline-block max-w-[80%] rounded-lg px-3 py-2 ${isMine ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-900'}`;
                bubble.textContent = message.body;

                const time = document.createElement('p');
                time.className = 'mt-1 text-xs text-slate-400';
                time.textContent = message.created_at_human || new Date().toLocaleString();

                wrapper.appendChild(bubble);
                wrapper.appendChild(time);
                chatMessages.appendChild(wrapper);
                scrollToBottom();
            };

            const getThreadKeyForMessage = (message) => {
                const senderId = Number(message.sender_id);
                const receiverId = Number(message.receiver_id);
                const petId = Number(message.pet_id || 0);
                const otherUserId = senderId === currentUserId ? receiverId : senderId;
                return `${otherUserId}|${petId}`;
            };

            const findThreadElement = (threadKey) => {
                if (!threadsList) return null;
                const items = threadsList.querySelectorAll('[data-thread-key]');
                for (const item of items) {
                    if (item.getAttribute('data-thread-key') === threadKey) {
                        return item;
                    }
                }
                return null;
            };

            const upsertThread = (message, isMine) => {
                if (!threadsList) return;

                const threadKey = getThreadKeyForMessage(message);
                let threadElement = findThreadElement(threadKey);

                if (!threadElement) {
                    const emptyState = threadsList.querySelector('[data-empty-threads]');
                    if (emptyState) {
                        emptyState.remove();
                    }

                    threadElement = document.createElement('a');
                    threadElement.className = 'block border-b px-4 py-3 hover:bg-slate-100';
                    threadElement.setAttribute('data-thread-key', threadKey);
                    threadElement.href = `/messages?thread=${encodeURIComponent(threadKey)}`;
                    threadElement.innerHTML = `
                        <div class="mb-1 flex items-center justify-between gap-2">
                            <p data-thread-name class="truncate font-semibold"></p>
                            <span data-thread-unread data-unread-count="0" class="hidden rounded-full bg-blue-600 px-2 py-0.5 text-xs font-bold text-white"></span>
                        </div>
                        <p data-thread-pet class="truncate text-xs text-slate-500"></p>
                        <p data-thread-body class="mt-1 truncate text-sm text-slate-600"></p>
                        <p data-thread-time class="mt-1 text-xs text-slate-400"></p>
                    `;
                } else {
                    threadElement.remove();
                }

                const otherUserName = isMine
                    ? (message.receiver_name || 'Unknown user')
                    : (message.sender_name || 'Unknown user');

                const threadName = threadElement.querySelector('[data-thread-name]');
                const threadPet = threadElement.querySelector('[data-thread-pet]');
                const threadBody = threadElement.querySelector('[data-thread-body]');
                const threadTime = threadElement.querySelector('[data-thread-time]');
                const threadUnread = threadElement.querySelector('[data-thread-unread]');

                if (threadName) threadName.textContent = otherUserName;
                if (threadPet) threadPet.textContent = `Pet: ${message.pet_name || 'N/A'}`;
                if (threadBody) threadBody.textContent = message.body || '';
                if (threadTime) threadTime.textContent = 'Just now';

                const shouldIncrementUnread = !isMine && activeThreadKey !== threadKey;
                if (threadUnread) {
                    let unread = Number(threadUnread.getAttribute('data-unread-count') || '0');
                    if (shouldIncrementUnread) {
                        unread += 1;
                    } else if (activeThreadKey === threadKey) {
                        unread = 0;
                    }

                    threadUnread.setAttribute('data-unread-count', String(unread));
                    threadUnread.textContent = unread > 0 ? String(unread) : '';
                    threadUnread.classList.toggle('hidden', unread === 0);
                }

                threadsList.prepend(threadElement);
            };

            let socket = null;
            let reconnectTimer = null;

            const connectSocket = () => {
                try {
                    socket = new WebSocket(`${socketBase}?user_id=${currentUserId}`);
                } catch (e) {
                    socket = null;
                    return;
                }

                socket.addEventListener('message', (event) => {
                    let payload = null;
                    try {
                        payload = JSON.parse(event.data);
                    } catch (e) {
                        return;
                    }

                    if (!payload || payload.type !== 'message.created' || !payload.message) {
                        return;
                    }

                    const msg = payload.message;
                    const petId = Number(msg.pet_id);
                    const senderId = Number(msg.sender_id);
                    const receiverId = Number(msg.receiver_id);
                    const isThreadOpen = (() => {
                        if (!selectedThread) return false;
                        const otherUserId = Number(selectedThread.otherUserId);
                        return (
                            petId === Number(selectedThread.petId) &&
                            [senderId, receiverId].includes(currentUserId) &&
                            [senderId, receiverId].includes(otherUserId) &&
                            senderId !== currentUserId
                        );
                    })();

                    upsertThread(msg, senderId === currentUserId);

                    if (isThreadOpen) {
                        appendMessage(msg, false);
                    }
                });

                socket.addEventListener('error', () => {
                    if (socket) {
                        socket.close();
                    }
                });

                socket.addEventListener('close', () => {
                    socket = null;
                    if (reconnectTimer) return;
                    reconnectTimer = window.setTimeout(() => {
                        reconnectTimer = null;
                        connectSocket();
                    }, 2000);
                });
            };

            connectSocket();

            if (form && input && chatMessages) {
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();

                    const body = input.value.trim();
                    if (!body) return;

                    const formData = new FormData(form);

                    try {
                        const response = await fetch('/messages', {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': csrf || '',
                            },
                            body: formData,
                        });

                        let data = null;
                        try {
                            data = await response.json();
                        } catch (jsonError) {
                            return;
                        }

                        if (!response.ok || !data.message) {
                            return;
                        }

                        appendMessage(data.message, true);
                        upsertThread(data.message, true);
                        input.value = '';
                    } catch (err) {
                        // keep existing page functional even if realtime send fails
                    }
                });
            }

            scrollToBottom();
        })();
    </script>
</body>
</html>
