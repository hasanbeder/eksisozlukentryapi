# eksisozlukentryapi

[English](README.md) | [Türkçe](README_tr.md)

Ekşi Sözlük'ten entry çekmek için basit bir PHP API'si. Bu API, basit bir GET isteği kullanarak entry'leri almanızı sağlar, sayfalama destekler ve verileri JSON formatında döndürür.

---

# eksisozlukentryapi

[![Lisans: GPL v3](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)

Ekşi Sözlük'ten entry çekmek için basit bir PHP API'si.

## İçindekiler

* [Açıklama](#açıklama)
* [Özellikler](#özellikler)
* [Kurulum](#kurulum)
* [Kullanım](#kullanım)
* [Parametreler](#parametreler)
* [Yanıt Formatı](#yanıt-formatı)
* [Hata Yönetimi](#hata-yönetimi)
* [Örnekler](#örnekler)
* [Lisans](#lisans)
* [Katkıda Bulunma](#katkıda-bulunma)


## Açıklama

Bu API, basit bir GET isteği kullanarak Ekşi Sözlük'ten entry'leri almanızı sağlar. Verileri JSON formatında döndürerek uygulamalarınıza kolayca entegre etmenizi sağlar.

## Özellikler

* Belirtilen Ekşi Sözlük başlıklarından entry'leri alır.
* Sayfalama destekler (başlangıç ve bitiş sayfalarını belirleme).
* Tüm entry'leri alma seçeneği.
* Geçersiz girdileri işler ve bilgilendirici hata mesajları sağlar.
* Verileri yapılandırılmış bir JSON formatında döndürür.


## Kurulum

1. Repoyu klonlayın: `git clone https://github.com/hasanbeder/eksisozlukentryapi.git`
2. `eksisozlukentryapi.php` dosyasını web sunucunuza yerleştirin. PHP'nin yüklü ve yapılandırılmış olduğundan emin olun.


## Kullanım

Gerekli parametrelerle `eksisozlukentryapi.php` dosyasına bir GET isteği yapın.

```
https://alan-adiniz.com/eksisozlukentryapi.php?input={eksisozluk_basligi_veya_url}&start_page={baslangic_sayfa}&end_page={bitis_sayfa}&get_all={true/false}
```


## Parametreler

* **`input` (gerekli):** Ekşi Sözlük başlığı (örneğin, `ornek-baslik`) veya tam URL (örneğin, `https://eksisozluk.com/ornek-baslik`).
* **`start_page` (isteğe bağlı):** Başlangıç sayfa numarası (varsayılan 1).
* **`end_page` (isteğe bağlı):** Bitiş sayfa numarası (varsayılan son sayfa).
* **`get_all` (isteğe bağlı):** Tüm entry'leri almak için `true` olarak ayarlayın.  Bu, `end_page` parametresini geçersiz kılar.


## Yanıt Formatı

API, aşağıdaki yapıda bir JSON yanıtı döndürür:

```json
{
  "status": "success",
  "data": [
    {
      "id": "entry_id",
      "author": "yazar_kullanici_adi",
      "date": "entry_tarihi",
      "date_link": "entry_kalıcı_bağlantı",
      "author_link": "yazar_profil_bağlantısı",
      "content": "entry_icerigi"
    },
    // ... daha fazla entry
  ]
}
```


## Hata Yönetimi

Hata durumunda (örneğin, geçersiz girdi, başlık bulunamadı), API, "error" durumunda ve açıklayıcı bir mesaj içeren bir JSON yanıtı döndürür:

```json
{
  "status": "error",
  "message": "Hata mesajı burada"
}
```


## Örnekler

* **Belirli bir başlıktan entry'leri getirme:**
  `https://alan-adiniz.com/eksisozlukentryapi.php?input=ornek-baslik`

* **Belirli bir başlıktan, 2. sayfadan başlayarak 5. sayfaya kadar entry'leri getirme:**
  `https://alan-adiniz.com/eksisozlukentryapi.php?input=ornek-baslik&start_page=2&end_page=5`

* **Belirli bir başlığın tüm entry'lerini getirme:**
  `https://alan-adiniz.com/eksisozlukentryapi.php?input=ornek-baslik&get_all=true`

* **Giriş olarak tam bir URL kullanma:**
    `https://alan-adiniz.com/eksisozlukentryapi.php?input=https://eksisozluk.com/ornek-baslik?p=1`



## Lisans

Bu proje [GNU Genel Kamu Lisansı v3](https://www.gnu.org/licenses/gpl-3.0) altında lisanslanmıştır.


## Katkıda Bulunma

Katkılara açığız! Lütfen sorun bildirmekten ve pull request göndermekten çekinmeyin.