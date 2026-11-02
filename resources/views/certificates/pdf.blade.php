<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 0; size: A4 landscape; }
        html, body { margin: 0; width: 297mm; height: 210mm; background: #fff; }
        body { font-family: DejaVu Sans, sans-serif; color: #3e2d29; }
        .page { box-sizing: border-box; height: 185mm; padding: 12mm 18mm; position: relative; overflow: hidden; text-align: center; background: #fff; }
        .top-band { position: absolute; left: 0; top: 0; width: 62mm; height: 10mm; background: #b65f46; }
        .top-band-light { position: absolute; left: 0; top: 10mm; width: 49mm; height: 4mm; background: #efc3b4; }
        .bottom-band { position: absolute; right: 0; bottom: 0; width: 72mm; height: 10mm; background: #b65f46; }
        .bottom-band-light { position: absolute; right: 0; bottom: 10mm; width: 58mm; height: 4mm; background: #efc3b4; }
        .shape { position: absolute; transform: rotate(45deg); background: #f7e6df; }
        .shape.one { width: 50mm; height: 50mm; left: -37mm; top: 31mm; }
        .shape.two { width: 42mm; height: 42mm; left: -30mm; top: 85mm; background: #fbedE8; }
        .shape.three { width: 56mm; height: 56mm; right: -39mm; bottom: 25mm; }
        .pattern-line { position: absolute; border-top: 1px solid #d48769; opacity: .7; }
        .pattern-line.one { width: 45mm; left: 18mm; bottom: 18mm; }
        .pattern-line.two { width: 28mm; right: 18mm; top: 21mm; }
        .logo { position: absolute; top: 15mm; left: 20mm; width: 50mm; height: auto; }
        .certificate-number { position: absolute; top: 16mm; right: 30mm; color: #795149; font-size: 10px; line-height: 1.45; text-align: right; }
        .certificate-number strong { color: #8d442f; font-size: 11px; }
        .title { position: relative; margin-top: 31mm; color: #8d442f; font-size: 42px; font-weight: bold; letter-spacing: 8px; }
        .subtitle-row { position: relative; margin-top: 2mm; color: #b65f46; font-size: 16px; font-weight: bold; letter-spacing: 7px; }
        .subtitle-row:before, .subtitle-row:after { display: inline-block; width: 28mm; margin: 0 6mm 5px; border-top: 2px solid #d48769; content: ''; }
        .introduction { position: relative; margin: 11mm 0 4mm; color: #795149; font-size: 15px; }
        .name { position: relative; width: 194mm; margin: 0 auto 6mm; padding-bottom: 4mm; border-bottom: 2px solid #d48769; color: #603326; font-size: 28px; font-weight: bold; letter-spacing: .5px; text-transform: uppercase; }
        .body { position: relative; width: 176mm; margin: 0 auto; color: #795149; font-size: 14px; line-height: 1.62; }
        .body strong { color: #603326; }
        .recognition { position: relative; width: 165mm; margin: 6mm auto 0; color: #795149; font-size: 13px; line-height: 1.55; }
        .signature { position: absolute; bottom: 7mm; left: 50%; width: 72mm; margin-left: -36mm; color: #795149; font-size: 10px; }
        .signature-space { height: 15mm; }
        .signature-line { padding-top: 2mm; border-top: 1px solid #d48769; color: #603326; font-weight: bold; }
        .footer { position: absolute; left: 20mm; bottom: 5mm; color: #b65f46; font-size: 8px; font-weight: bold; letter-spacing: 1.5px; }
    </style>
</head>
<body>
    <div class="page">
        <div class="top-band"></div><div class="top-band-light"></div>
        <div class="bottom-band"></div><div class="bottom-band-light"></div>
        <div class="shape one"></div><div class="shape two"></div><div class="shape three"></div>
        <div class="pattern-line one"></div><div class="pattern-line two"></div>

        <img class="logo" src="{{ public_path('images/logo-upelkes.png') }}" alt="UPELKES">
        <div class="certificate-number">Nomor Sertifikat<br><strong>{{ $certificate->certificate_number }}</strong></div>

        <div class="title">SERTIFIKAT</div>
        <div class="subtitle-row">MAGANG</div>
        <p class="introduction">Dengan ini menerangkan bahwa</p>
        <div class="name">{{ $intern->name }}</div>
        <p class="body">telah menyelesaikan Program Magang di<br><strong>UPTD Pelatihan Kesehatan<br>Dinas Kesehatan Provinsi Jawa Barat</strong><br>pada bidang <strong>{{ $department }}</strong><br>selama periode <strong>{{ $start?->translatedFormat('d F Y') ?? '-' }} s.d. {{ $end?->translatedFormat('d F Y') ?? '-' }}</strong>.</p>
        <p class="recognition">Sertifikat ini diberikan sebagai penghargaan atas partisipasi, dedikasi, dan kontribusi selama mengikuti program magang.</p>
        <div class="signature"><div>Bandung, {{ $certificate->issued_at?->translatedFormat('d F Y') }}</div><div class="signature-space"></div><div class="signature-line">Kepala UPTD Pelatihan Kesehatan</div></div>
        <div class="footer">UPTD PELATIHAN KESEHATAN JAWA BARAT</div>
    </div>
</body>
</html>
