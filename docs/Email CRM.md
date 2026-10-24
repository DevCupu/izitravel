# Email CRM pada web hosting

Pengingat tugas CRM memakai SMTP `mail.izitravel.id:465`. Inbox admin memakai IMAP `mail.izitravel.id:993`. Keduanya menggunakan akun `web@izitravel.id`; `IMAP_PASSWORD` memakai `MAIL_PASSWORD` jika tidak diisi terpisah. Simpan kata sandi hanya di `.env` server, bukan di repository.

Proses deploy memasang satu cron Laravel setiap menit melalui `crontab` akun hosting. Jalur PHP diambil dari `command -v php` dan diperiksa agar memenuhi PHP 8.3 ke atas. Laravel Scheduler menjalankan `crm:send-followup-reminders` setiap menit dan `crm:sync-inbox` setiap lima menit. Tidak perlu worker queue yang terus hidup.

Sesudah deploy pertama:

1. Periksa `.env` produksi: `APP_URL`, `MAIL_MAILER=smtp`, `MAIL_SCHEME=smtps`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS`, serta pengaturan IMAP. Pastikan kata sandi benar untuk kedua protokol.
2. Periksa satu baris cron bertanda `IZITRAVEL_CRM_SCHEDULER` di cPanel → Cron Jobs. Jika fitur SSH `crontab` tidak tersedia, buat entri setiap menit melalui panel dengan perintah `cd /home/izim8295/izitravel && /jalur/php-yang-sesuai artisan schedule:run >> /dev/null 2>&1`.
3. Deploy mencoba `php artisan crm:sync-inbox` satu kali setelah migrasi untuk menetapkan batas awal; email lama tidak diimpor. Jika IMAP gagal, kesalahannya tampil di halaman Inbox Email CRM dan cron akan mencoba lagi.
4. Kirim email uji ke `web@izitravel.id`, tunggu paling lama lima menit, lalu pastikan pesan muncul satu kali di inbox admin. Tautkan ke lead uji dan pastikan hanya CS pemiliknya yang dapat melihat isi email pada detail lead.
5. Jadwalkan tugas uji beberapa menit ke depan untuk akun CS internal. Pastikan satu email diterima dan tidak dikirim ulang saat cron berikutnya berjalan.

Jika SMTP atau IMAP gagal, periksa `storage/logs/laravel.log`. Halaman inbox admin menampilkan kesalahan sinkronisasi IMAP terakhir. Sistem tidak mengubah status baca atau memindahkan email di server. Lampiran dan balasan tetap melalui webmail.
