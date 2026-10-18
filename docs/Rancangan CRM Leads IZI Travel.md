# Rancangan End-to-End CRM Leads IZI Travel

**Versi:** 1.0 — 13 Oktober 2026

**Tujuan:** Mengubah alur iklan → WhatsApp yang sudah ada menjadi proses penjualan yang dapat ditindaklanjuti sampai booking, tanpa menganggap klik WhatsApp sebagai lead atau penjualan.

## 1. Kondisi awal yang teramati

Admin IZI Travel sudah memiliki Campaign dan Ads dengan tautan UTM, template pesan per Ads, empat WhatsApp CS aktif, pembagian kunjungan berdasarkan bobot, serta Lead Tracking yang mencatat kunjungan dan pengalihan ke WhatsApp. Daftar paket umrah juga tersedia. Modul tracking saat ini menampilkan status *diarahkan ke WhatsApp*; status itu tidak membuktikan pesan dikirim, identitas calon jemaah diketahui, atau booking terjadi.

Rancangan ini menambah **CRM Leads** ke admin IZI Travel yang sama. Penyimpanan utama mengikuti database aplikasi yang sudah ada; tidak perlu memindahkan data ke Google Spreadsheet. Struktur kode, versi framework, tabel booking, dan sistem akun CS harus diverifikasi dari source code sebelum implementasi.

## 2. Hasil yang ingin dicapai

1. Setiap prospek yang benar-benar menghubungi CS dapat dicatat sebagai lead dengan nama/nomor, minat paket, dan satu PIC.
2. CS tahu siapa yang harus dihubungi berikutnya dan kapan.
3. Admin dapat melihat progres dari lead baru hingga booking dan alasan kehilangan prospek.
4. Sumber Campaign/Ads dicatat ketika ada bukti penghubung yang jelas; sumber yang tidak diketahui diberi label **Tidak diketahui**, bukan ditebak.
5. Data kunjungan dan pengalihan lama tetap terpisah dari data lead dan booking.

## 3. Alur pengguna dari awal sampai akhir

| Langkah | Pengunjung / CS | Yang disimpan sistem |
| --- | --- | --- |
| 1. Kunjungan iklan | Pengunjung membuka tautan `/chat` dengan UTM Campaign dan Ads. | Catatan kunjungan yang sudah ada, Campaign/Ads yang cocok, waktu, dan referensi klik acak (`click_ref`). |
| 2. Distribusi | Sistem memilih CS aktif dengan mekanisme bobot yang sudah ada. | CS terpilih pada catatan klik. Referensi yang sama tetap terikat pada CS itu. |
| 3. Buka WhatsApp | Pengunjung menekan tombol WhatsApp. Template pesan dapat menyertakan kode pendek, misalnya `Ref: IZ-7K9P`, tanpa data pribadi. | Status **diarahkan ke WhatsApp**. Belum membuat lead. |
| 4. Percakapan masuk | CS menerima pesan. Bila calon jemaah benar-benar menghubungi, CS membuat lead dari referensi tersebut; bila kode hilang, CS membuat lead manual. | Kontak, peluang/minat paket, PIC, sumber dengan tingkat kepastian, dan riwayat pembuatan. |
| 5. Tindak lanjut | CS mencatat kebutuhan, memindahkan tahap, membuat jadwal follow-up, dan membuka WhatsApp dari detail lead. | Riwayat aktivitas dan tugas berikutnya. |
| 6. Booking / gagal | Admin/CS menandai booking yang terkonfirmasi atau menutup lead dengan alasan gagal. | Tanggal hasil, paket, nilai jika diketahui, dan alasan gagal. Jika sudah ada modul booking, hubungkan ke nomor booking sebenarnya. |
| 7. Evaluasi | Admin memfilter menurut periode, Campaign, Ads, CS, dan paket. | Laporan funnel dari kunjungan, arah WA, lead tercatat, hingga booking terkonfirmasi. |

**Aturan utama:** klik tautan WhatsApp tidak otomatis membuat lead. Pesan yang benar-benar masuk belum dapat dideteksi dari tautan `wa.me` saja; untuk MVP, CS mengonfirmasi dan mencatatnya di admin.

## 4. Modul dan layar admin

| Layar | Isi dan tindakan utama |
| --- | --- |
| **Leads — Tabel** | Cari nama/nomor, filter tahap/PIC/Campaign/Ads/paket/tanggal, lihat follow-up terlambat, tambah lead. Nomor lengkap hanya untuk peran yang berwenang. |
| **Pipeline** | Pilih satu tahap untuk melihat lead, edit data ringkas pada kartu, atau pindahkan tahap dengan alasan bila diperlukan. Admin mengatur nama, warna, urutan, penambahan, dan penghapusan tahap. Perubahan tercatat dalam riwayat. |
| **Detail Lead** | Kontak, paket diminati, sumber, PIC, catatan, aktivitas, jadwal follow-up, tombol buka WhatsApp, riwayat tahap dan handover. |
| **Tugas Saya** | Follow-up hari ini, besok, terlambat, selesai; tandai selesai dan buat jadwal berikutnya. |
| **Laporan** | Funnel dan hasil per Campaign/Ads/CS/paket; pisahkan metrik kunjungan, pengalihan WA, lead, dan booking. |
| **Dashboard** | Kartu lead baru, follow-up hari ini/terlambat, booking periode berjalan; tautan ke daftar yang sudah difilter. |

Tampilan mengikuti pola admin IZI Travel yang ada dan tetap nyaman digunakan CS di ponsel.

## 5. Tahap penjualan dan aturan perpindahan

| Tahap | Makna | Informasi minimum |
| --- | --- | --- |
| **Baru** | Prospek tercatat, belum ditindaklanjuti. | Kontak, PIC, sumber jika diketahui. |
| **Dihubungi** | CS telah merespons atau menghubungi. | Waktu kontak pertama. |
| **Konsultasi** | Kebutuhan, tanggal, jumlah jemaah, atau paket dibahas. | Ringkasan kebutuhan. |
| **Penawaran** | Opsi paket/harga telah disampaikan. | Paket atau penawaran yang dibahas. |
| **Booking** | Prospek menyatakan ingin memesan atau masuk proses pemesanan. | Paket dan tindak lanjut pemesanan. |
| **Berhasil** | Booking benar-benar dikonfirmasi menurut proses internal IZI Travel. | Referensi booking bila tersedia dan tanggal konfirmasi. |
| **Gagal** | Tidak dilanjutkan. | Alasan gagal, misalnya harga, jadwal, tidak respons, atau memilih penyedia lain. |

Lead yang gagal dapat dibuka kembali dengan riwayat tetap utuh. Tahap **Berhasil** tidak diisi otomatis hanya karena chat, janji lisan, atau klik WA. Satu kontak dapat memiliki beberapa peluang untuk perjalanan berbeda; nomor telepon yang sama memunculkan peringatan duplikat, bukan penolakan mutlak.

Admin dapat mengganti nama, warna, dan urutan semua tahap serta menambah tahap proses baru. Tahap dasar **Baru**, **Berhasil**, dan **Gagal** tidak dapat dihapus karena menjadi acuan pencatatan awal, konfirmasi booking, dan alasan batal. Saat tahap proses lain dihapus, admin memilih tahap proses pengganti; lead aktif dan arsip dipindahkan bersama riwayat perubahannya.

## 6. Data inti

Nama tabel berikut adalah rancangan logis, disesuaikan dengan nama tabel proyek saat coding.

| Entitas | Field penting | Hubungan |
| --- | --- | --- |
| `contacts` | `id`, `name`, `phone_normalized`, `email?`, `city?` | Satu kontak dapat memiliki banyak lead. |
| `leads` | `id`, `contact_id`, `package_id?`, `stage`, `assigned_cs_id`, `assigned_user_id?`, `campaign_id?`, `campaign_ad_id?`, `click_ref?`, `attribution_method`, `estimated_value?`, `booking_reference?`, `won_at?`, `lost_reason?`, timestamps | Satu peluang penjualan; sumber dapat kosong. |
| `lead_activities` | `lead_id`, `actor_user_id`, `type`, `old_value?`, `new_value?`, `note?`, `created_at` | Catatan dan audit perubahan tahap, PIC, paket, serta hasil. |
| `lead_followups` | `lead_id`, `assigned_user_id`, `due_at`, `description`, `status`, `completed_at?` | Beberapa tindak lanjut per lead. |
| `chat_logs` yang sudah ada | Tambah `click_ref` unik dan relasi ke lead jika cocok. | Data historis tetap ada; baris lama tidak diberi identitas atau konversi palsu. |
| `whatsapp_cs` yang sudah ada | Tautkan ke akun pengguna CS bila modul login staf mendukung. | Nomor tujuan WA dan PIC CRM merujuk staf yang sama. |

Normalisasi nomor dilakukan ke format yang konsisten, misalnya `628...`; gunakan indeks untuk nomor, PIC, tahap, jadwal follow-up, Campaign/Ads, dan referensi klik. Simpan `click_ref` sebagai token acak yang tidak mengandung nomor telepon atau ID berurutan. Catatan aktivitas penting tidak ditimpa saat lead diedit.

## 7. Sumber lead dan ketepatan atribusi

- **Tertaut via kode:** CS memasukkan `click_ref` dari pesan WhatsApp; sistem mengambil Campaign, Ads, dan CS dari catatan klik. Ini atribusi paling jelas untuk alur link saat ini.
- **Dipilih manual:** CS memilih sumber berdasarkan informasi yang diketahui. Laporan menandai bahwa sumber diisi manual.
- **Tidak diketahui:** Pesan tanpa kode dan tanpa sumber yang dapat diverifikasi tetap boleh dicatat.
- Kode tidak boleh membuktikan bahwa WhatsApp mengirim pesan. Lead baru dibuat hanya setelah CS menerima kontak atau secara sengaja mencatat prospek.
- Jika kode sudah terhubung ke lead, tampilkan lead yang ada untuk menghindari duplikasi. Jika calon jemaah datang lagi dari iklan berbeda, simpan sentuhan baru sebagai aktivitas tanpa diam-diam menimpa sumber awal.

## 8. Hak akses

| Peran | Akses minimum |
| --- | --- |
| **Admin / supervisor** | Melihat semua lead, mengubah PIC, mengatur tahap, melihat laporan, mengelola akun CS. |
| **CS** | Melihat dan memperbarui lead yang menjadi tanggung jawabnya, membuat lead, menyelesaikan follow-up, meminta/menerima handover jika fitur itu diaktifkan. |

Gunakan akun per CS, bukan berbagi akun admin. Perubahan PIC, status berhasil/gagal, dan catatan penting menyimpan pelaku serta waktu. Batasi akses nomor telepon dan ekspor menurut peran. Detail autentikasi serta kebijakan penyimpanan data mengikuti kemampuan dan aturan internal proyek setelah source code diperiksa.

## 9. Laporan dan definisi angka

| Metrik | Definisi |
| --- | --- |
| **Kunjungan** | Event masuk ke link Campaign `/chat`; bukan orang unik. |
| **Arah WA** | Pengunjung menekan atau diteruskan ke WhatsApp; bukan pesan masuk. |
| **Lead** | Peluang yang dibuat setelah kontak benar-benar diketahui oleh CS/admin. |
| **Lead berkode** | Lead yang terhubung ke klik lewat referensi; bisa dihitung per Ads dengan sumber terverifikasi. |
| **Booking berhasil** | Lead dengan booking terkonfirmasi; jika ada modul booking, cocokkan ke record pemesanan. |
| **Rasio lead → booking** | Jumlah lead berhasil dibagi jumlah lead yang dibuat pada kelompok/periode yang didefinisikan. Laporan perlu menyebut basis periode agar tidak rancu. |

Tampilkan angka beratribusi manual/tidak diketahui secara terpisah. Jangan membagi booking dengan seluruh kunjungan untuk mengklaim tingkat konversi iklan bila banyak lead tidak memiliki sumber terverifikasi.

## 10. Urutan pengembangan

### Fase 0 — Audit source code dan data

- Periksa framework, tabel `chat_logs`, Campaign/Ads, CS, pengguna/role, paket, dan apakah sudah ada booking/order.
- Periksa cara `/chat` memilih CS, mencatat klik, membentuk template pesan, serta titik yang aman untuk menyisipkan referensi.
- Siapkan backup database dan lingkungan uji; tentukan cara rollback migrasi/deploy pada hosting yang digunakan.

### Fase 1 — MVP operasional

- Buat kontak, lead, tahap, catatan aktivitas, follow-up, dan hak akses admin/CS.
- Buat daftar, detail, pipeline sederhana, tugas, dan ringkasan dashboard.
- Hubungkan sumber Campaign/Ads secara manual bila diketahui; semua lead boleh dibuat tanpa UTM.
- Laporan dasar: lead baru, follow-up terlambat, lead per tahap, booking terkonfirmasi, performa CS.

### Fase 2 — Penghubung klik ke lead

- Tambah `click_ref` pada alur `/chat`, sisipkan kode singkat ke template pesan tanpa menghapus pesan Ads yang ada.
- CS dapat menempel kode saat membuat lead; Campaign, Ads, dan CS terisi dari log yang cocok.
- Tampilkan metrik funnel per Ads dengan sumber terverifikasi serta tangani kode hilang/ganda/kedaluwarsa.

### Fase 3 — Perluasan setelah MVP dipakai

- Hubungkan status Berhasil ke data booking internal bila modulnya ada.
- Pengingat dalam aplikasi atau email sesuai kebutuhan operasional.
- Handover CS dengan persetujuan, template follow-up, form prospek sebelum WA, serta integrasi WhatsApp Business Platform hanya jika manfaat dan biayanya terbukti perlu.

## 11. Kasus khusus yang harus ditangani

- CS nonaktif/libur: pembagian klik baru mengikuti CS aktif; lead lama tetap punya PIC sampai dipindahkan dengan jejak riwayat.
- Klik berulang atau bot: tidak otomatis menghasilkan banyak lead; log kunjungan dan lead tetap dipisah.
- Nomor yang sama, minat berbeda: tunjukkan kontak dan peluang sebelumnya sebelum membuat peluang baru.
- Pengunjung menghapus kode pesan: CS tetap dapat membuat lead dengan sumber **Tidak diketahui**.
- Handover: PIC lama dan baru tercatat; tugas terbuka dialihkan sesuai keputusan supervisor.
- Waktu follow-up menggunakan zona waktu operasional yang disepakati (untuk tim Makassar: WITA) dan ditampilkan jelas.
- Data historis tracking tidak dikonversi massal menjadi lead karena identitas dan status percakapannya tidak tersedia.

## 12. Kriteria selesai untuk MVP

1. Admin dan CS dapat membuat lead nyata tanpa mengubah data Campaign/Ads lama.
2. Satu lead memiliki PIC, tahap, dan riwayat perubahan yang dapat diaudit.
3. Tugas follow-up hari ini dan terlambat muncul dengan benar; CS dapat menyelesaikannya.
4. CS hanya dapat membuka lead yang berhak dia tangani; admin melihat seluruhnya.
5. Nomor yang sama memunculkan peringatan serta riwayat, tetapi tetap dapat menjadi peluang perjalanan baru.
6. Laporan membedakan kunjungan, arah WA, lead, dan booking.
7. Bila `click_ref` sudah diaktifkan, kode valid memetakan Campaign/Ads/CS yang tepat; kode tidak valid tidak mengarang sumber.
8. Alur `/chat` dan pembagian CS yang sudah berjalan tetap berfungsi setelah rilis.
9. Tampilan daftar/detail/tugas dapat digunakan dari ponsel, dan migrasi/rollback telah diuji di lingkungan uji.

## 13. Keputusan yang menunggu pemeriksaan kode / pemilik bisnis

- Apakah CS sekarang sudah punya akun login sendiri? Bila belum, buat role CS dan akun per orang pada MVP.
- Apakah IZI Travel sudah punya record pemesanan yang bisa menjadi bukti Booking Berhasil? Bila belum, konfirmasi manual dengan referensi dan catatan.
- Apakah nilai penawaran/booking perlu dicatat dalam rupiah pada MVP? Field disiapkan opsional, laporan omzet hanya muncul bila datanya dapat dipercaya.
- Apakah form singkat sebelum WhatsApp layak diterapkan? Tunda sampai data dasar klik → chat → lead tersedia agar dampaknya terhadap pengunjung dapat diukur.

**Input implementasi berikutnya:** source code/repo IZI Travel dan skema database atau dump *struktur saja* (tanpa data pelanggan maupun kredensial). Rancangan ini dapat langsung dipetakan ke migration, model, route, controller, policy, dan halaman admin setelah audit tersebut.
