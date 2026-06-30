<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $surat->judul }}</title>
    <style>
        @page { margin: 1.5cm 2cm; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; line-height: 1.4; color: #000; }
        .kop-surat { text-align: center; margin-bottom: 5px; }
        .kop-surat img { width: 100%; height: auto; }
        .garis-kop { border-top: 3px solid #000; border-bottom: 1px solid #000; margin: 5px 0 12px 0; padding: 1px 0; }
        .judul-surat { text-align: center; margin-bottom: 14px; }
        .judul-surat h2 { font-size: 13pt; font-weight: bold; text-decoration: underline; margin: 0; text-transform: uppercase; }
        .judul-surat p { font-size: 12pt; margin: 3px 0 0 0; }
        .isi-surat { text-align: justify; }
        /* HTML editor output */
        .isi-surat p { margin: 6px 0; }
        .isi-surat h1 { font-size: 14pt; margin: 10px 0; }
        .isi-surat blockquote { border-left: 3px solid #ccc; margin: 8px 0; padding-left: 12px; }
        .isi-surat ul, .isi-surat ol { margin: 8px 0; padding-left: 40px; }
        .isi-surat ul { list-style-type: disc; }
        .isi-surat ol { list-style-type: decimal; }
        .isi-surat li { margin: 2px 0; }
        .isi-surat table { border-collapse: collapse; width: 100%; margin: 8px 0; }
        .isi-surat table td, .isi-surat table th { border: 1px solid #000; padding: 4px 6px; vertical-align: top; }
        .isi-surat pre { font-family: monospace; white-space: pre-wrap; }
        .ttd-container { margin-top: 24px; float: right; width: 260px; text-align: center; }
        .ttd-tempat { margin-bottom: 2px; }
        .ttd-jabatan { margin-bottom: 4px; }
        .ttd-spasi { height: 70px; position: relative; }
        .ttd-spasi img { position: absolute; left: 50%; top: 0; transform: translateX(-50%); max-height: 75px; }
        .ttd-nama { font-weight: bold; text-decoration: underline; }
        .ttd-nip { font-size: 11pt; }
        .clear { clear: both; }
    </style>
</head>

<body>
    {{-- KOP Surat: kop diupload per surat, fallback ke kop sekolah --}}
    @php $kop = $surat->kop_path ?? ($settings['kop_surat_path'] ?? null); @endphp
    @if ($kop)
        <div class="kop-surat">
            <img src="{{ public_path('storage/' . $kop) }}" alt="Kop Surat">
        </div>
    @endif

    <div class="garis-kop"></div>

    {{-- Judul --}}
    <div class="judul-surat">
        <h2>{{ $surat->judul }}</h2>
        <p>Nomor : {{ $surat->nomor_surat }}</p>
    </div>

    {{-- Isi (HTML dari Trix) --}}
    <div class="isi-surat">
        {!! $surat->isi !!}
    </div>

    {{-- Tanda Tangan --}}
    <div class="ttd-container">
        <p class="ttd-tempat">{{ $surat->tempat ?? '' }}, {{ $surat->tanggal_surat->translatedFormat('d F Y') }}</p>
        @if ($surat->ttd_jabatan)
            <p class="ttd-jabatan">{{ $surat->ttd_jabatan }}</p>
        @endif
        <div class="ttd-spasi">
            @if (!empty($settings['ttd_kepala_path']))
                <img src="{{ public_path('storage/' . $settings['ttd_kepala_path']) }}" alt="">
            @endif
        </div>
        @if ($surat->ttd_nama)
            <p class="ttd-nama">{{ $surat->ttd_nama }}</p>
        @endif
        @if ($surat->ttd_nip)
            <p class="ttd-nip">NIP. {{ $surat->ttd_nip }}</p>
        @endif
    </div>
    <div class="clear"></div>
</body>

</html>
