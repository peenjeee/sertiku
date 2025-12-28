<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessBlockchainCertificate;
use App\Jobs\ProcessIpfsCertificate;
use App\Mail\CertificateIssuedMail;
use App\Models\ActivityLog;
use App\Models\Certificate;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Helpers\XlsxParser;

class BulkCertificateController extends Controller
{
    /**
     * Show the bulk upload form.
     */
    public function index()
    {
        $user = Auth::user();
        $templates = $user->templates()->where('is_active', true)->latest()->get();

        // Blockchain quotas
        $canUseBlockchain = $user->canUseBlockchain();
        $blockchainLimit = $user->getBlockchainLimit();
        $blockchainUsed = $user->getBlockchainUsedThisMonth();
        // Prevent division by zero or negative
        $remainingBlockchain = max(0, $blockchainLimit - $blockchainUsed);

        // IPFS quotas
        $canUseIpfs = $user->canUseIpfs();
        $ipfsLimit = $user->getIpfsLimit();
        $ipfsUsed = $user->getIpfsUsedThisMonth();
        $remainingIpfs = max(0, $ipfsLimit - $ipfsUsed);

        return view('lembaga.sertifikat.bulk', compact(
            'templates',
            'canUseBlockchain',
            'blockchainLimit',
            'blockchainUsed',
            'remainingBlockchain',
            'canUseIpfs',
            'ipfsLimit',
            'ipfsUsed',
            'remainingIpfs'
        ));
    }


    /**
     * Download CSV template for bulk import.
     */
    public function downloadTemplateCsv()
    {
        $csvPath = public_path('template_import.csv');

        if (file_exists($csvPath)) {
            return response()->download($csvPath, 'template_import.csv', [
                'Content-Type' => 'text/csv',
            ]);
        }

        // Fallback: generate on-the-fly if file doesn't exist
        $headers = ['recipient_name', 'recipient_email', 'course_name', 'category', 'description', 'issue_date'];
        $sampleData = [
            ['John Doe', 'john@example.com', 'Web Development', 'Seminar', 'Atas partisipasinya dalam kegiatan', '2025-01-15'],
            ['Jane Smith', 'jane@example.com', 'Data Science', 'Workshop', 'Sebagai peserta workshop', '2025-01-16'],
            ['Ahmad Fauzi', 'ahmad@example.com', 'UI/UX Design', 'Sertifikasi', 'Telah menyelesaikan sertifikasi', '2025-01-17'],
        ];

        $csvContent = implode(',', $headers) . "\n";
        foreach ($sampleData as $row) {
            $csvContent .= implode(',', $row) . "\n";
        }

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="template_import.csv"');
    }

    /**
     * Download XLSX template for bulk import.
     */
    public function downloadTemplateXlsx()
    {
        $headers = ['recipient_name', 'recipient_email', 'course_name', 'category', 'description', 'issue_date'];
        $sampleData = [
            ['John Doe', 'john@example.com', 'Web Development', 'Seminar', 'Atas partisipasinya dalam kegiatan', '2025-01-15'],
            ['Jane Smith', 'jane@example.com', 'Data Science', 'Workshop', 'Sebagai peserta workshop', '2025-01-16'],
            ['Ahmad Fauzi', 'ahmad@example.com', 'UI/UX Design', 'Sertifikasi', 'Telah menyelesaikan sertifikasi', '2025-01-17'],
        ];

        // Create XLSX file using ZipArchive
        $tempFile = tempnam(sys_get_temp_dir(), 'xlsx');
        $zip = new \ZipArchive();
        $zip->open($tempFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        // [Content_Types].xml
        $zip->addFromString('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
<Default Extension="xml" ContentType="application/xml"/>
<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>
<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>
<Override PartName="/xl/sharedStrings.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sharedStrings+xml"/>
</Types>');

        // _rels/.rels
        $zip->addFromString('_rels/.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>
</Relationships>');

        // xl/_rels/workbook.xml.rels
        $zip->addFromString('xl/_rels/workbook.xml.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>
<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/sharedStrings" Target="sharedStrings.xml"/>
</Relationships>');

        // xl/workbook.xml
        $zip->addFromString('xl/workbook.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
<sheets><sheet name="Template" sheetId="1" r:id="rId1"/></sheets>
</workbook>');

        // Build shared strings and sheet data
        $allStrings = array_merge($headers, ...array_map(fn($row) => $row, $sampleData));
        $stringIndex = array_flip($allStrings);

        // xl/sharedStrings.xml
        $sharedStringsXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<sst xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" count="' . count($allStrings) . '" uniqueCount="' . count($allStrings) . '">';
        foreach ($allStrings as $str) {
            $sharedStringsXml .= '<si><t>' . htmlspecialchars($str) . '</t></si>';
        }
        $sharedStringsXml .= '</sst>';
        $zip->addFromString('xl/sharedStrings.xml', $sharedStringsXml);

        // xl/worksheets/sheet1.xml
        $sheetXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
<sheetData>';

        // Header row
        $sheetXml .= '<row r="1">';
        foreach ($headers as $col => $header) {
            $colLetter = chr(65 + $col); // A, B, C, etc.
            $sheetXml .= '<c r="' . $colLetter . '1" t="s"><v>' . $stringIndex[$header] . '</v></c>';
        }
        $sheetXml .= '</row>';

        // Data rows
        foreach ($sampleData as $rowIdx => $row) {
            $rowNum = $rowIdx + 2;
            $sheetXml .= '<row r="' . $rowNum . '">';
            foreach ($row as $col => $cell) {
                $colLetter = chr(65 + $col);
                $sheetXml .= '<c r="' . $colLetter . $rowNum . '" t="s"><v>' . $stringIndex[$cell] . '</v></c>';
            }
            $sheetXml .= '</row>';
        }

        $sheetXml .= '</sheetData></worksheet>';
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheetXml);

        $zip->close();

        return response()->download($tempFile, 'template_import.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Handle bulk certificate creation.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // 1. Initial Validation
        $request->validate([
            'template_id' => 'required|exists:templates,id',
            'file' => 'required|file|mimes:csv,txt,xlsx|max:10240',
            'default_description' => 'nullable|string|max:1000',
            'default_category' => 'nullable|string|max:50',
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();
        $extension = $file->getClientOriginalExtension();

        // 2. Parse Data
        if (in_array(strtolower($extension), ['xlsx'])) {
            try {
                $data = XlsxParser::parse($path);
            } catch (\Exception $e) {
                return back()->with('error', 'Gagal membaca file XLSX: ' . $e->getMessage());
            }
        } else {
            // CSV parsing
            $data = array_map('str_getcsv', file($path));
        }

        if (count($data) < 2) {
            return back()->with('error', 'File CSV kosong atau tidak valid (harus ada header).');
        }

        $header = array_map('trim', $data[0]); // Header row
        unset($data[0]); // Remove header

        // Standardize header keys to lower case
        $header = array_map('strtolower', $header);

        // Smart Header Mapping (Indonesian to English)
        $headerMap = [
            'nama' => 'recipient_name',
            'nama penerima' => 'recipient_name',
            'nama peserta' => 'recipient_name',
            'penerima' => 'recipient_name',
            'email' => 'recipient_email',
            'email penerima' => 'recipient_email',
            'surat elektronik' => 'recipient_email',
            'kursus' => 'course_name',
            'pelatihan' => 'course_name',
            'nama kursus' => 'course_name',
            'nama pelatihan' => 'course_name',
            'acara' => 'course_name',
            'event' => 'course_name',
            'kategori' => 'category',
            'deskripsi' => 'description',
            'keterangan' => 'description',
            'tanggal' => 'issue_date',
            'tanggal terbit' => 'issue_date',
            'tanggal sertifikat' => 'issue_date',
            'nomor' => 'certificate_number',
            'nomor sertifikat' => 'certificate_number',
        ];

        foreach ($header as $key => $value) {
            if (isset($headerMap[$value])) {
                $header[$key] = $headerMap[$value];
            }
        }

        // Required columns
        $requiredColumns = ['recipient_name', 'course_name', 'issue_date'];
        $missingColumns = array_diff($requiredColumns, $header);

        if (!empty($missingColumns)) {
            return back()->with('error', 'Kolom wajib tidak ditemukan: ' . implode(', ', $missingColumns) . '. (Support: Nama, Email, Kursus, Tanggal)');
        }

        // Map header indices
        $indices = array_flip($header);

        $successCount = 0;
        $failCount = 0;
        $errors = [];

        // Global settings - Use boolean() for strict checkbox parsing
        $sendEmail = $request->boolean('send_email');
        $blockchainEnabled = $request->boolean('blockchain_enabled');
        $ipfsEnabled = $request->boolean('ipfs_enabled');
        $defaultCategory = $request->default_category;
        $defaultDescription = $request->default_description;

        $template = Template::find($request->template_id);

        foreach ($data as $rowIndex => $row) {
            // skip empty rows
            if (empty(array_filter($row)))
                continue;

            // Pad row if shorter than header
            $row = array_pad($row, count($header), null);

            // Pre-process Date
            if (isset($indices['issue_date']) && !empty($row[$indices['issue_date']])) {
                $rawDate = $row[$indices['issue_date']];
                try {
                    if (is_numeric($rawDate)) {
                        // Excel Serial Date Conversion (Unix = (Excel - 25569) * 86400)
                        $row[$indices['issue_date']] = gmdate('Y-m-d', ($rawDate - 25569) * 86400);
                    } else {
                        // Carbon parsing for strings (e.g. "17-08-2024", "2024/12/31")
                        $row[$indices['issue_date']] = \Carbon\Carbon::parse($rawDate)->format('Y-m-d');
                    }
                } catch (\Exception $e) {
                    // Ignore error, let Validator catch invalid format
                }
            }

            // Extract data using mapped indices
            $rowData = [
                'recipient_name' => isset($indices['recipient_name']) ? ($row[$indices['recipient_name']] ?? null) : null,
                'recipient_email' => isset($indices['recipient_email']) ? ($row[$indices['recipient_email']] ?? null) : null,
                'course_name' => isset($indices['course_name']) ? ($row[$indices['course_name']] ?? null) : null,
                'category' => (isset($indices['category']) && !empty($row[$indices['category']])) ? $row[$indices['category']] : $defaultCategory,
                'description' => (isset($indices['description']) && !empty($row[$indices['description']])) ? $row[$indices['description']] : $defaultDescription,
                'issue_date' => isset($indices['issue_date']) ? ($row[$indices['issue_date']] ?? null) : null,
            ];

            // Validate Row
            $validator = Validator::make($rowData, [
                'recipient_name' => 'required|string|max:255',
                'recipient_email' => 'nullable|email|max:255',
                'course_name' => 'required|string|max:255',
                'issue_date' => 'required|date',
            ]);

            if ($validator->fails()) {
                $failCount++;
                $errors[] = "Baris " . ($rowIndex + 2) . ": " . implode(', ', $validator->errors()->all());
                continue;
            }

            // Create Certificate
            try {
                // Check limit per iteration strictly
                if (!$user->canIssueCertificate()) {
                    $errors[] = "Kuota habis pada baris " . ($rowIndex + 2);
                    break;
                }

                // Check Blockchain Limit
                if ($blockchainEnabled && !$user->canUseBlockchain()) {
                    $errors[] = "Kuota Blockchain habis pada baris " . ($rowIndex + 2);
                    break;
                }

                // Check IPFS Limit
                if ($ipfsEnabled && !$user->canUseIpfs()) {
                    $errors[] = "Kuota IPFS habis pada baris " . ($rowIndex + 2);
                    break;
                }

                $certData = array_merge($validator->validated(), [
                    'template_id' => $template->id,
                    'blockchain_enabled' => $blockchainEnabled,
                    'blockchain_status' => $blockchainEnabled ? 'pending' : 'disabled',
                    'ipfs_status' => $ipfsEnabled ? 'pending' : null, // Explicitly null if not enabled
                    'category' => $rowData['category'],
                    'description' => $rowData['description'],
                ]);

                $certificate = $user->certificates()->create($certData);

                // 1. Generate QR
                $certificate->generateQrCode();

                // 2. Generate PDF
                try {
                    $certificate->generatePdf();
                } catch (\Throwable $e) {
                    Log::error("Bulk PDF Error: " . $e->getMessage());
                }

                // 3. Generate Hashes
                $certificate->generateFileHashes();

                // 4. Increment Usage
                $template->incrementUsage();

                // 5. Send Email
                if ($sendEmail && $rowData['recipient_email']) {
                    Mail::to($rowData['recipient_email'])->queue(new CertificateIssuedMail($certificate));
                }

                // 6. Blockchain & IPFS Jobs
                // Status already set at creation, just dispatch jobs
                if ($blockchainEnabled) {
                    // Dispatch Blockchain Job (which handles IPFS internally if enabled)
                    ProcessBlockchainCertificate::dispatch($certificate, $ipfsEnabled);
                } elseif ($ipfsEnabled) {
                    // IPFS only - status already set to 'pending' at creation
                    ProcessIpfsCertificate::dispatch($certificate);
                }

                $successCount++;

            } catch (\Exception $e) {
                $failCount++;
                $errors[] = "Baris " . ($rowIndex + 2) . ": Error sistem (" . $e->getMessage() . ")";
                Log::error("Bulk Cert Error: " . $e->getMessage());
            }
        }

        // Final Report
        $msg = "Proses selesai. Berhasil: $successCount. Gagal: $failCount.";
        if ($failCount > 0) {
            return redirect()->route('lembaga.sertifikat.index')
                ->with('warning', $msg . " Cek log error.")
                ->withErrors($errors);
        }

        return redirect()->route('lembaga.sertifikat.index')
            ->with('success', $msg);
    }
}
