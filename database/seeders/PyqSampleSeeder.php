<?php

namespace Database\Seeders;

use App\Models\Pyq;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class PyqSampleSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure pyqs storage directory exists
        Storage::disk('public')->makeDirectory('pyqs');

        $samplePdfContent = "%PDF-1.4\n" .
            "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n" .
            "2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n" .
            "3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >>\nendobj\n" .
            "4 0 obj\n<< /Length 480 >>\nstream\n" .
            "BT\n/F1 18 Tf\n50 730 Td\n(AMRITA VISHWA VIDYAPEETHAM) Tj\n" .
            "0 -24 Td\n/F1 14 Tf\n(AEEE 2024 - Previous Year Question Paper) Tj\n" .
            "0 -20 Td\n/F1 10 Tf\n(Total Duration: 150 Mins | Maximum Marks: 300 | PCM + English) Tj\n" .
            "0 -40 Td\n/F1 12 Tf\n(SECTION I - MATHEMATICS (40 Questions)) Tj\n" .
            "0 -25 Td\n/F1 10 Tf\n(Q1. If A and B are square matrices of same order, then (AB)' is equal to:) Tj\n" .
            "0 -18 Td\n(   [A] A'B'    [B] B'A'    [C] -A'B'    [D] AB) Tj\n" .
            "0 -30 Td\n/F1 12 Tf\n(SECTION II - PHYSICS (30 Questions)) Tj\n" .
            "0 -25 Td\n/F1 10 Tf\n(Q2. A body of mass 2 kg moving at 4 m/s hits a stationary spring of constant k = 100 N/m.) Tj\n" .
            "0 -18 Td\n(   The maximum compression in the spring is:) Tj\n" .
            "0 -18 Td\n(   [A] 0.4 m    [B] 0.57 m    [C] 0.8 m    [D] 1.2 m) Tj\n" .
            "0 -30 Td\n/F1 12 Tf\n(SECTION III - CHEMISTRY (25 Questions)) Tj\n" .
            "0 -25 Td\n/F1 10 Tf\n(Q3. Which of the following compounds exhibits optical isomerism?) Tj\n" .
            "0 -18 Td\n(   [A] 2-butanol    [B] 1-butanol    [C] 2-propanol    [D] ethanol) Tj\n" .
            "ET\nendstream\nendobj\n" .
            "5 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\nendobj\n" .
            "xref\n0 6\n0000000000 65535 f \n0000000009 00000 n \n0000000058 00000 n \n0000000115 00000 n \n0000000224 00000 n \n0000000754 00000 n \ntrailer\n<< /Size 6 /Root 1 0 R >>\nstartxref\n829\n%%EOF\n";

        $papers = [
            [
                'title' => 'AEEE 2024 Phase 1 Question Paper (Slot 1)',
                'year' => 2024,
                'subject' => 'Full Length',
                'file_name' => 'AEEE_2024_Phase1_Paper.pdf',
                'total_pages' => 32,
                'file_path' => 'pyqs/aeee_2024_phase1.pdf',
            ],
            [
                'title' => 'AEEE 2024 Mathematics High-Yield Question Paper',
                'year' => 2024,
                'subject' => 'Mathematics',
                'file_name' => 'AEEE_2024_Mathematics.pdf',
                'total_pages' => 16,
                'file_path' => 'pyqs/aeee_2024_maths.pdf',
            ],
            [
                'title' => 'AEEE 2023 Phase 2 Official Question Paper',
                'year' => 2023,
                'subject' => 'Full Length',
                'file_name' => 'AEEE_2023_Phase2_Paper.pdf',
                'total_pages' => 30,
                'file_path' => 'pyqs/aeee_2023_phase2.pdf',
            ],
            [
                'title' => 'AEEE 2022 Phase 1 Solved Question Paper',
                'year' => 2022,
                'subject' => 'Full Length',
                'file_name' => 'AEEE_2022_Phase1_Solved.pdf',
                'total_pages' => 28,
                'file_path' => 'pyqs/aeee_2022_phase1.pdf',
            ],
            [
                'title' => 'AEEE 2021 Physics & Chemistry Solved Paper',
                'year' => 2021,
                'subject' => 'Physics',
                'file_name' => 'AEEE_2021_Physics_Chemistry.pdf',
                'total_pages' => 22,
                'file_path' => 'pyqs/aeee_2021_physics.pdf',
            ],
        ];

        foreach ($papers as $paper) {
            // Write PDF file to storage/app/public/pyqs/...
            Storage::disk('public')->put($paper['file_path'], $samplePdfContent);
            $size = strlen($samplePdfContent);

            Pyq::updateOrCreate(
                ['title' => $paper['title']],
                [
                    'year' => $paper['year'],
                    'subject' => $paper['subject'],
                    'file_path' => $paper['file_path'],
                    'file_name' => $paper['file_name'],
                    'file_size_formatted' => round($size / 1024, 1) . ' KB',
                    'file_size_bytes' => $size,
                    'total_pages' => $paper['total_pages'],
                    'is_active' => true,
                ]
            );
        }
    }
}
