#Migration to SQLBuilder
This library is for migration natural sql to sql builder format. It supports only first depth and general usage of sql.
```
composer require selcukmart/migration2sqlbuilder
```
## USAGE
```php

$sql = "SELECT * FROM A ";
$migration = new Migration2SQLBuilder();
$output = $migration->sqlBuilder($sql);
```

### Example
```sql
SELECT 
    a,
       b,
       c.s,
  gg.*, 
  so.sube_id, 
  so.ana_yetki_id, 
  so.ad AS KUL_AD, 
  so.soyad AS KUL_SOYAD, 
  os.isim ODEME_SECENEKLERI, 
  nes2.isim AS KARGO_FIRMASI, 
  ei.id AS INVOICE_ID, 
  ei.parasut_fatura_id, 
  ei.e_fatura_or_arsiv, 
  ei.parasut_e_fatura_id, 
  ei.trackable_job_id, 
  ei.status, 
  ei.proccess_status, 
  ei.sent, 
  ei.pdf_url, 
  ei.kargo_gonderildi, 
  ei.kargo_firmasina_bildirildi, 
  ei.bildirim_tarihi 
FROM 
  tes_gelir_gider AS gg 
      LEFT JOIN tes_nesne AS nes2 ON nes2.id = gg.kargo_firma_id 
  AND nes2.tip = '15' AND nes2.isim='12ssd' XOR nes2.ad='sdsdsd' && (nes2.k='12' || nes2.k='sdsd') 
  INNER JOIN tes_kullanici so ON so.id = gg.kisi_id 
  LEFT JOIN tes_odeme_secenekleri os ON os.id = gg.odeme_secenekleri 
  
  LEFT JOIN tes_adresler adr ON adr.id = gg.teslimat_adresi_id 
  LEFT JOIN tes_adresler adr2 ON adr2.id = gg.fatura_adresi_id 
  LEFT JOIN tes_il_ilce_semt_mahalle iism1 ON iism1.id = adr.il 
  AND iism1.kume_id = '0' 
  LEFT JOIN tes_il_ilce_semt_mahalle iism5 ON iism5.id = adr2.il 
  AND iism5.kume_id = '0' 
  LEFT JOIN tes_e_invoice ei ON ei.gg_id = gg.id 
WHERE 
  gg.durum < '4'
AND ei.status between 1 and 10
AND ei.status not between 4 and 6
(xor os.durum='1')
or so.ad='asdasdasd' 
 GROUP BY a.sira,b.c,d.e ORDER by az.sira,c.x LIMIT 10, 15
```
Export to;
```php
Array
(
    [1] => Array
        (
            [type] => SELECT
            [0] => a
            [1] => b
            [c] => Array
                (
                    [0] => s 
                )

            [gg] => Array
                (
                    [0] => * 
                )

            [so] => Array
                (
                    [0] => sube_id 
                    [1] => ana_yetki_id 
                    [2] => ad AS KUL_AD 
                    [3] => soyad AS KUL_SOYAD 
                )

            [os] => Array
                (
                    [0] => isim ODEME_SECENEKLERI 
                )

            [nes2] => Array
                (
                    [0] => isim AS KARGO_FIRMASI 
                )

            [ei] => Array
                (
                    [0] => id AS INVOICE_ID 
                    [1] => parasut_fatura_id 
                    [2] => e_fatura_or_arsiv 
                    [3] => parasut_e_fatura_id 
                    [4] => trackable_job_id 
                    [5] => status 
                    [6] => proccess_status 
                    [7] => sent 
                    [8] => pdf_url 
                    [9] => kargo_gonderildi 
                    [10] => kargo_firmasina_bildirildi 
                    [11] => bildirim_tarihi 
                )

        )

    [2] => Array
        (
            [type] => FROM
            [0] => tes_gelir_gider
            [1] => AS
            [2] => gg
        )

    [3] => Array
        (
            [type] => LEFT JOIN
            [table] => Array
                (
                    [0] => tes_nesne
                    [1] => AS
                    [2] => nes2
                )

            [ON] => Array
                (
                    [nes2] => id
                    [gg] => kargo_firma_id
                )

            [WHERE] =>  AND nes2.tip = 15 AND nes2.isim = 12ssd XOR nes2.ad = sdsdsd && ( nes2.k = 12 || nes2.k = sdsd ) 
        )

    [4] => Array
        (
            [type] => INNER JOIN
            [table] => Array
                (
                    [0] => tes_kullanici
                    [1] => so
                )

            [ON] => Array
                (
                    [so] => id
                    [gg] => kisi_id
                )

        )

    [5] => Array
        (
            [type] => LEFT JOIN
            [table] => Array
                (
                    [0] => tes_odeme_secenekleri
                    [1] => os
                )

            [ON] => Array
                (
                    [os] => id
                    [gg] => odeme_secenekleri
                )

        )

    [6] => Array
        (
            [type] => LEFT JOIN
            [table] => Array
                (
                    [0] => tes_adresler
                    [1] => adr
                )

            [ON] => Array
                (
                    [adr] => id
                    [gg] => teslimat_adresi_id
                )

        )

    [7] => Array
        (
            [type] => LEFT JOIN
            [table] => Array
                (
                    [0] => tes_adresler
                    [1] => adr2
                )

            [ON] => Array
                (
                    [adr2] => id
                    [gg] => fatura_adresi_id
                )

        )

    [8] => Array
        (
            [type] => LEFT JOIN
            [table] => Array
                (
                    [0] => tes_il_ilce_semt_mahalle
                    [1] => iism1
                )

            [ON] => Array
                (
                    [iism1] => id
                    [adr] => il
                )

            [WHERE] =>  AND iism1.kume_id = 0 
        )

    [9] => Array
        (
            [type] => LEFT JOIN
            [table] => Array
                (
                    [0] => tes_il_ilce_semt_mahalle
                    [1] => iism5
                )

            [ON] => Array
                (
                    [iism5] => id
                    [adr2] => il
                )

            [WHERE] =>  AND iism5.kume_id = 0 
        )

    [10] => Array
        (
            [type] => LEFT JOIN
            [table] => Array
                (
                    [0] => tes_e_invoice
                    [1] => ei
                )

            [ON] => Array
                (
                    [ei] => gg_id
                    [gg] => id
                )

        )

    [11] => Array
        (
            [type] => WHERE
            [WHERE] =>  gg.durum < 4 AND ei.status BETWEEN 1 AND 10 AND ei.status NOT BETWEEN 4 AND 6 ( XOR os.durum = 1 ) OR so.ad = asdasdasd 
        )

    [12] => Array
        (
            [type] => GROUP BY
            [a] => Array
                (
                    [0] => sira 
                )

            [b] => Array
                (
                    [0] => c 
                )

            [d] => Array
                (
                    [0] => e 
                )

        )

    [13] => Array
        (
            [type] => ORDER BY
            [az] => Array
                (
                    [0] => sira 
                )

            [c] => Array
                (
                    [0] => x 
                )

        )

    [14] => Array
        (
            [type] => LIMIT
            [0] => 10, 15
        )

)
```