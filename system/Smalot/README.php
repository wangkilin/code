<?php

// Include Composer autoloader if not already done.
include 'vendor/autoload.php';

// Parse pdf file and build necessary objects.
$parser = new \Smalot\PdfParser\Parser();
$pdf    = $parser->parseFile('document.pdf');

$text = $pdf->getText();
echo $text;

?>
You can too extract text from each page handly or for a specific page.

<?php

// Include Composer autoloader if not already done.
include 'vendor/autoload.php';

// Parse pdf file and build necessary objects.
$parser = new \Smalot\PdfParser\Parser();
$pdf    = $parser->parseFile('document.pdf');

// Retrieve all pages from the pdf file.
$pages  = $pdf->getPages();

// Loop over each page to extract text.
foreach ($pages as $page) {
    echo $page->getText();
}

?>
Here a sample code to extract metadata from document (Author, Creator, CreationDate, ...).

<?php

// Include Composer autoloader if not already done.
include 'vendor/autoload.php';

// Parse pdf file and build necessary objects.
$parser = new \Smalot\PdfParser\Parser();
$pdf    = $parser->parseFile('document.pdf');

// Retrieve all details from the pdf file.
$details  = $pdf->getDetails();

// Loop over each property to extract values (string or array).
foreach ($details as $property => $value) {
    if (is_array($value)) {
        $value = implode(', ', $value);
    }
    echo $property . ' => ' . $value . "\n";
}

?>
