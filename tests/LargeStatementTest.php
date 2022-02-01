<?php
/**
 * @author selcukmart
 * 1.02.2022
 * 08:31
 */


use Migration2SQLBuilder\Migration2SQLBuilder;
use PHPUnit\Framework\TestCase;

class LargeStatementTest extends TestCase
{

    public function testExport()
    {
        $expected = [
            1 => [
                'type' => 'SELECT',
                0 => 'a',
                1 => 'b',
                'c' => [
                    's '
                ],
                'gg' => [
                    '* '
                ],
                'so' => [
                    'branch_id ',
                    'auth_id ',
                    'name AS USER_NAME ',
                    'surname AS USER_SURNAME '
                ],
                'os' => [
                    'name PAYMENT_OPTION '
                ],
                'ei' => [
                    'id AS INVOICE_ID ',
                    'trackable_job_id ',
                    'status ',
                    'proccess_status ',
                    'sent ',
                    'pdf_url '
                ]
            ],
            2 => [
                'type' => 'FROM',
                0 => 'tes_gelir_gider',
                1 => 'AS',
                2 => 'gg'
            ],
            3 => [
                'type' => 'LEFT JOIN',
                'table' => [
                    'tes_nesne',
                    'AS',
                    'nes2'
                ],
                'ON' => [
                    'nes2' => 'id',
                    'gg' => 'kargo_firma_id'
                ],
                'WHERE' => ' AND nes2.tip = 15 AND nes2.isim = 12ssd XOR nes2.ad = sdsdsd && ( nes2.k = 12 || nes2.k = sdsd ) '
            ],
            4 => [
                'type' => 'INNER JOIN',
                'table' => [
                    'tes_kullanici',
                    'so'
                ],
                'ON' => [
                    'so' => 'id',
                    'gg' => 'kisi_id'
                ]
            ],
            5 => [
                'type' => 'LEFT JOIN',
                'table' => [
                    'tes_odeme_secenekleri',
                    'os'
                ],
                'ON' => [
                    'os' => 'id',
                    'gg' => 'odeme_secenekleri'
                ]
            ],
            6 => [
                'type' => 'LEFT JOIN',
                'table' => [
                    'tes_adresler',
                    'adr'
                ],
                'ON' => [
                    'adr' => 'id',
                    'gg' => 'teslimat_adresi_id'
                ]
            ],
            7 => [
                'type' => 'LEFT JOIN',
                'table' => [
                    'tes_adresler',
                    'adr2'
                ],
                'ON' => [
                    'adr2' => 'id',
                    'gg' => 'fatura_adresi_id'
                ]
            ],
            8 => [
                'type' => 'LEFT JOIN',
                'table' => [
                    'tes_il_ilce_semt_mahalle',
                    'iism1'
                ],
                'ON' => [
                    'iism1' => 'id',
                    'adr' => 'il'
                ],
                'WHERE' => ' AND iism1.kume_id = 0 '
            ],
            9 => [
                'type' => 'LEFT JOIN',
                'table' => [
                    'tes_il_ilce_semt_mahalle',
                    'iism5'
                ],
                'ON' => [
                    'iism5' => 'id',
                    'adr2' => 'il'
                ],
                'WHERE' => ' AND iism5.kume_id = 0 '
            ],
            10 => [
                'type' => 'LEFT JOIN',
                'table' => [
                    'tes_e_invoice',
                    'ei'
                ],
                'ON' => [
                    'ei' => 'gg_id',
                    'gg' => 'id'
                ]
            ],
            11 => [
                'type' => 'WHERE',
                'WHERE' => ' gg.durum < 4 AND ei.status BETWEEN 1 AND 10 AND ei.status NOT BETWEEN 4 AND 6 ( XOR os.durum = 1 ) OR so.ad = asdasdasd '
            ],
            12 => [
                'type' => 'GROUP BY',
                'a' => [
                    'sira '
                ],
                'b' => [
                    'c '
                ],
                'd' => [
                    'e '
                ]
            ],
            13 => [
                'type' => 'ORDER BY',
                'az' => [
                    'sira '
                ],
                'c' => [
                    'x '
                ]
            ],
            14 => [
                'type' => 'LIMIT',
                0 => '10, 15'
            ]
        ];
        $sql = "SELECT a, b, c.s, gg.*, so.branch_id,so.auth_id,so.name AS USER_NAME,so.surname AS USER_SURNAME, os.name PAYMENT_OPTION, ei.id AS INVOICE_ID,ei.trackable_job_id,ei.status,ei.proccess_status,ei.sent,ei.pdf_url FROM tes_gelir_gider AS gg LEFT JOIN tes_nesne AS nes2 ON nes2.id = gg.kargo_firma_id AND nes2.tip = 15 AND nes2.isim = 12ssd XOR nes2.ad = sdsdsd && ( nes2.k = 12 || nes2.k = sdsd ) INNER JOIN tes_kullanici so ON so.id = gg.kisi_id LEFT JOIN tes_odeme_secenekleri os ON os.id = gg.odeme_secenekleri LEFT JOIN tes_adresler adr ON adr.id = gg.teslimat_adresi_id LEFT JOIN tes_adresler adr2 ON adr2.id = gg.fatura_adresi_id LEFT JOIN tes_il_ilce_semt_mahalle iism1 ON iism1.id = adr.il AND iism1.kume_id = 0 LEFT JOIN tes_il_ilce_semt_mahalle iism5 ON iism5.id = adr2.il AND iism5.kume_id = 0 LEFT JOIN tes_e_invoice ei ON ei.gg_id = gg.id WHERE gg.durum < 4 AND ei.status BETWEEN 1 AND 10 AND ei.status NOT BETWEEN 4 AND 6 ( XOR os.durum = 1 ) OR so.ad = asdasdasd GROUP BY a.sira, b.c, d.e ORDER BY az.sira, c.x LIMIT 10, 15";
        $migration = new Migration2SQLBuilder();
        $output = $migration->sqlBuilder($sql);
        $this->assertSame($expected, $output);
    }
}
