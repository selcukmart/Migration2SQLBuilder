<?php
/**
 * @author selcukmart
 * 10.05.2021
 * 17:17
 */


use Migration2SQLBuilder\Migration2SQLBuilder;

require(__DIR__ . '/../../vendor/autoload.php');

//$migration = new Migration();
//$migration->extractSQL();

$sql = "SELECT 
    a,
       b,
       c.s,
  gg.*, 
  so.branch_id, 
  so.auth_id, 
  so.name AS USER_NAME, 
  so.surname AS USER_SURNAME, 
  os.name PAYMENT_OPTION,  
  ei.id AS INVOICE_ID,  
  ei.trackable_job_id, 
  ei.status, 
  ei.proccess_status, 
  ei.sent, 
  ei.pdf_url 
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
 GROUP BY a.sira,b.c,d.e ORDER by az.sira,c.x LIMIT 10, 15";
$migration = new Migration2SQLBuilder();
$output = $migration->sqlBuilder($sql);
$export_filename = 'sql-migration-example.php';

include __DIR__.'/export-file.php';