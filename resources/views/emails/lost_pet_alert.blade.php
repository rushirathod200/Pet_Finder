<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Lost Pet Alert</title>
</head>
<body style="margin:0;padding:24px;background:#f3f6fb;font-family:Arial,sans-serif;color:#111827;">
@php
    $photoItems = [];
    $contactPhone = trim((string) ($lostPetRequest->contact_phone ?: $requester->phone));
    $contactDial = preg_replace('/[^0-9+]/', '', $contactPhone) ?? '';
    $rawPhotos = is_array($lostPetRequest->photos ?? null) ? $lostPetRequest->photos : [];
    foreach ($rawPhotos as $photoPath) {
        $path = trim((string) $photoPath);
        if ($path === '') {
            continue;
        }

        $isRemote = str_starts_with($path, 'http://') || str_starts_with($path, 'https://');
        $normalizedPath = ltrim($path, '/');
        if (str_starts_with($normalizedPath, 'storage/')) {
            $normalizedPath = substr($normalizedPath, strlen('storage/'));
        }

        $absolutePath = storage_path('app/public/'.$normalizedPath);
        $publicUrl = $isRemote
            ? $path
            : rtrim((string) config('app.url'), '/').'/storage/'.$normalizedPath;

        $cid = null;
        if (!$isRemote && isset($message) && is_file($absolutePath)) {
            try {
                $cid = $message->embed($absolutePath);
            } catch (\Throwable $th) {
                $cid = null;
            }
        }

        $photoItems[] = [
            'cid' => $cid,
            'url' => $publicUrl,
        ];
    }
@endphp

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
    <tr>
        <td align="center">
            <table role="presentation" width="640" cellpadding="0" cellspacing="0" style="width:100%;max-width:640px;background:#ffffff;border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;">
                <tr>
                    <td style="padding:18px 22px;background:#ef4444;color:#ffffff;">
                        <h1 style="margin:0;font-size:24px;line-height:1.2;">Lost Pet Alert</h1>
                        <p style="margin:8px 0 0 0;font-size:14px;opacity:.95;">City: {{ $lostPetRequest->city }}</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:20px 22px;">
                        <p style="margin:0 0 14px 0;font-size:14px;line-height:1.5;">
                            A pet owner in your city has submitted a lost pet request.
                        </p>
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                            <tr>
                                <td style="padding:8px 0;border-bottom:1px solid #eef2f7;"><strong>Pet Name</strong></td>
                                <td style="padding:8px 0;border-bottom:1px solid #eef2f7;">{{ $lostPetRequest->pet_name }}</td>
                            </tr>
                            <tr>
                                <td style="padding:8px 0;border-bottom:1px solid #eef2f7;"><strong>City</strong></td>
                                <td style="padding:8px 0;border-bottom:1px solid #eef2f7;">{{ $lostPetRequest->city }}</td>
                            </tr>
                            @if ($lostPetRequest->last_seen_area)
                                <tr>
                                    <td style="padding:8px 0;border-bottom:1px solid #eef2f7;"><strong>Last Seen</strong></td>
                                    <td style="padding:8px 0;border-bottom:1px solid #eef2f7;">{{ $lostPetRequest->last_seen_area }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td style="padding:8px 0;border-bottom:1px solid #eef2f7;"><strong>Contact</strong></td>
                                <td style="padding:8px 0;border-bottom:1px solid #eef2f7;">
                                    <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                                        <span>{{ $contactPhone ?: '-' }}</span>
                                        @if ($contactDial !== '')
                                            <a href="tel:{{ $contactDial }}" style="display:inline-block;background:#22c55e;color:#ffffff;text-decoration:none;font-size:12px;font-weight:700;padding:6px 10px;border-radius:8px;">
                                                Call Now
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:8px 0;"><strong>Posted By</strong></td>
                                <td style="padding:8px 0;">{{ $requester->fullname }} ({{ $requester->email }})</td>
                            </tr>
                        </table>

                        <div style="margin-top:16px;padding:14px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;">
                            <p style="margin:0 0 6px 0;font-size:13px;color:#475569;"><strong>Description</strong></p>
                            <p style="margin:0;font-size:14px;line-height:1.5;">{{ $lostPetRequest->description }}</p>
                        </div>

                        @if (count($photoItems) > 0)
                            <h2 style="margin:18px 0 10px 0;font-size:16px;">Pet Photos</h2>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                                <tr>
                                    @foreach ($photoItems as $index => $photo)
                                        @if ($index > 0 && $index % 2 === 0)
                                            </tr><tr>
                                        @endif
                                        <td style="padding:4px;vertical-align:top;" width="50%">
                                            @if ($photo['cid'])
                                                <img src="{{ $photo['cid'] }}" alt="Lost pet photo {{ $index + 1 }}" style="display:block;width:100%;height:auto;border-radius:10px;border:1px solid #e5e7eb;">
                                            @else
                                                <a href="{{ $photo['url'] }}" target="_blank" rel="noopener noreferrer" style="display:inline-block;font-size:13px;color:#2563eb;text-decoration:none;">
                                                    View photo {{ $index + 1 }}
                                                </a>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            </table>
                        @endif

                        <p style="margin:18px 0 0 0;font-size:13px;color:#64748b;">
                            If you have any lead, please contact the owner as soon as possible.
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
