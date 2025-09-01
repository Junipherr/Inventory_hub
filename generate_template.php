<?php
require 'vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

$phpWord = new PhpWord();

// Set paper size to Legal (8.5" × 13")
$phpWord->getSettings()->setPaperSize('Legal');

// Section with margins (in twips: 1 inch = 1440 twips)
$section = $phpWord->addSection([
    'marginTop'    => 1440, // 2.54 cm
    'marginBottom' => 0,    // 0.0 cm
    'marginLeft'   => 1440, // 2.54 cm
    'marginRight'  => 1080, // 1.905 cm
]);

// Define font styles (Times New Roman, 12pt)
$titleFontStyle  = ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'];
$boldFontStyle   = ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'];
$normalFontStyle = ['size' => 12, 'name' => 'Times New Roman'];

// Title
$section->addText('MEMORANDUM RECEIPT OF PROPERTIES', $titleFontStyle, ['alignment' => 'center']);
$section->addText('First Semester, A.Y. 2025-2026', $boldFontStyle, ['alignment' => 'center']);
$section->addTextBreak(2);

// Room/Department
$section->addText('Room/Department: ${room_department}', $normalFontStyle);
$section->addTextBreak(1);

// Create table for items
$table = $section->addTable(['borderSize' => 0, 'borderColor' => 'ffffff']); // No borders

// Table headers
$table->addRow();
$table->addCell(2000)->addText('QUANTITY', $boldFontStyle);
$table->addCell(4000)->addText('PARTICULARS', $boldFontStyle);
$table->addCell(3000)->addText('REMARKS', $boldFontStyle);

// Sample row (placeholder for cloning)
$table->addRow();
$table->addCell()->addText('${quantity}', $normalFontStyle);
$table->addCell()->addText('${particulars}', $normalFontStyle);
$table->addCell()->addText('${remarks}', $normalFontStyle);
$section->addTextBreak(2);

// Footer text (paragraphs)
$section->addText(
    "The properties listed above are dully-turned over to the undersigned for official use only. The undersigned binds himself/herself to take good care of the said properties while on his/her position/control or until this Memorandum Receipt is revoked. Any losses or damages resulting from negligence or improper use by the undersigned or the other parties under him/her will be charged to his/her account. The properties listed above shall not be used or brought outside the campus without permission from the Property Custodian and the College President.",
    $normalFontStyle
);
$section->addTextBreak(1);

$section->addText(
    "The above stated properties shall not be transferred to any office or brought outside the premises of the college compound except with the written permission from the Property Custodian and the College President.",
    $normalFontStyle
);
$section->addTextBreak(2);

// Signatures
$section->addText("Properties received by:", $boldFontStyle);
$section->addText("${person_in_charge}\t\t\t\t\t\tDate received: ${date_received}", $normalFontStyle);
$section->addText("Classroom In-charge", $normalFontStyle);
$section->addTextBreak(2);

$section->addText("Endorsed by:", $boldFontStyle);
$section->addText("VERGELIO O. CABAHUG", $normalFontStyle);
$section->addText("Property Custodian", $normalFontStyle);
$section->addTextBreak(2);

$section->addText("Approved by:", $boldFontStyle);
$section->addText("MARIANO JOAQUIN C. MACIAS JR., Ed.D", $normalFontStyle);
$section->addText("College President/Chairman, BOT", $normalFontStyle);

// Save document
$objWriter = IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('proper_template.docx');
echo "Template created successfully!\n";
?>
