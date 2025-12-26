<?php

namespace App\Helpers;

use ZipArchive;
use SimpleXMLElement;
use Exception;

class XlsxParser
{
    /**
     * Parse an XLSX file and return an array of rows.
     * 
     * @param string $filePath
     * @return array
     * @throws Exception
     */
    public static function parse(string $filePath): array
    {
        $zip = new ZipArchive;
        if ($zip->open($filePath) !== true) {
            throw new Exception("Unable to open XLSX file.");
        }

        // Load shared strings
        $sharedStrings = [];
        if ($zip->locateName('xl/sharedStrings.xml') !== false) {
            $xml = new SimpleXMLElement($zip->getFromName('xl/sharedStrings.xml'));
            foreach ($xml->si as $si) {
                $sharedStrings[] = (string) $si->t;
            }
        }

        // Load Worksheet 1
        $rows = [];
        if ($zip->locateName('xl/worksheets/sheet1.xml') !== false) {
            $xml = new SimpleXMLElement($zip->getFromName('xl/worksheets/sheet1.xml'));
            foreach ($xml->sheetData->row as $row) {
                $rowData = [];
                foreach ($row->c as $cell) {
                    $val = (string) $cell->v;
                    $type = (string) $cell['t'];

                    if ($type === 's' && isset($sharedStrings[$val])) {
                        $val = $sharedStrings[$val];
                    }

                    // Simple logic: if empty, add empty string. 
                    // Note: XLSX might skip empty cells, this simple parser doesn't handle full grid alignment perfectly but works for simple lists.
                    $rowData[] = $val;
                }
                if (!empty($rowData)) {
                    $rows[] = $rowData;
                }
            }
        } else {
            $zip->close();
            throw new Exception("Sheet1.xml not found in XLSX.");
        }

        $zip->close();
        return $rows;
    }
}
