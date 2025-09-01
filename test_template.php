<?php
require 'vendor/autoload.php';
use PhpOffice\PhpWord\TemplateProcessor;

try {
    echo "Testing template processor...\n";
    $tp = new TemplateProcessor('proper_template.docx');
    echo "Template loaded successfully!\n";
    
    // Test setting some values
    $tp->setValue('room_department', 'Test Room');
    $tp->setValue('date_received', date('Y-m-d'));
    $tp->setValue('person_in_charge', 'Test Person');
    $tp->setValue('quantity', '5');
    $tp->setValue('particulars', 'Test Item');
    $tp->setValue('remarks', 'Good Condition');
    
    echo "Values set successfully!\n";
    
    // Test saving
    $tp->saveAs('test_output.docx');
    echo "Test output saved as test_output.docx\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
