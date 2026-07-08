# Bera Halı - Satış Sitesi

Laravel 12 + Filament 3 ile hazırlanmış halı satış sitesi. İyzico (sandbox) ödeme entegrasyonu hazır.

## Kurulum

Vendor klasörü projeye dahil, `composer install` gerekmez. Sadece:

```bash
# 1. MySQL'de veritabanını oluştur
mysql -u root -p -e "CREATE DATABASE berahali_satis CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 2. Proje klasöründe
cd ~/Desktop/sites/berahali_satis
php artisan migrate --seed
php artisan storage:link

# 3. Çalıştır (Herd/Valet kullanıyorsan bu adım gerekmez)
php artisan serve
```

`.env` içinde DB kullanıcı/şifre mevcut MySQL kurulumuna göre ayarlı (root). Gerekirse düzenle.

## Giriş Bilgileri

- **Admin paneli:** `/admin`
- **E-posta:** `admin@berahali.com`
- **Şifre:** `password` (ilk girişten sonra Kullanıcılar bölümünden değiştir!)

## Admin Panelinde Neler Var

- **Ürünler:** kod, kategori, ölçü, m² (m² × m² fiyatı → toplam fiyat otomatik hesaplanır), indirimli fiyat, kapak görseli + galeri, açıklama, özellikler (ad/değer), stok, öne çıkan/yayın durumu
- **Kategoriler, Slider Yönetimi** (sürükle-bırak sıralama)
- **Siparişler:** detay görüntüleme, durum güncelleme (Ödendi → Hazırlanıyor → Kargoya Verildi → Teslim Edildi)
- **İletişim Mesajları** (okunmamış rozeti)
- **Site Ayarları:** telefon, adres, e-posta, WhatsApp, kargo ücreti, ücretsiz kargo limiti, anasayfa metinleri, hakkımızda içeriği
- **Dashboard:** ciro, sipariş, ürün, mesaj istatistikleri

## Önyüz

- Anasayfa (slider, özellik kutuları, öne çıkan + yeni ürünler)
- Ürünler sayfası: kategori / m² aralığı / fiyat aralığı / indirimli filtreleri; fiyat ve m²'ye göre sıralama
- Ürün detay (galeri, özellikler, m² bilgileri)
- Sepet → misafir veya üye olarak ödeme → iyzico ödeme formu
- Üyelik: kayıt/giriş, sipariş geçmişi
- Hakkımızda, İletişim (form admin'e düşer)

## İyzico

`.env` dosyasında:

```
IYZICO_API_KEY=sandbox-apiKey        → iyzico sandbox merchant panelinden al
IYZICO_SECRET_KEY=sandbox-secretKey
IYZICO_BASE_URL=https://sandbox-api.iyzipay.com
```

Sandbox anahtarları: https://sandbox-merchant.iyzipay.com (ücretsiz sandbox hesabı aç → Ayarlar → API Anahtarları).

**Test kartı:** 5528790000000008, son kullanma 12/30, CVC 123.

Canlıya geçerken sadece bu üç değeri gerçek anahtarlarla ve `https://api.iyzipay.com` ile değiştir.

> Not: Ödeme callback'i (`/odeme/callback`) iyzico'nun sunucudan erişebileceği bir adres gerektirmez (kullanıcının tarayıcısı üzerinden döner), localhost'ta test edilebilir.

## Teknik Notlar

- Entegrasyon SDK'sız, `App\Services\IyzicoService` içinde REST API v2 (HmacSHA256) ile yazıldı.
- Sepet session tabanlı: `App\Services\CartService`.
- Kargo ücreti ve ücretsiz kargo limiti Site Ayarları'ndan yönetilir.
- Önyüz Tailwind CDN kullanır, npm build gerekmez.
- Ödeme başarılı olduğunda stok otomatik düşer, sipariş "Ödendi" olur.
