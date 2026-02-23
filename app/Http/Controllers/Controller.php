<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function extractPDFToText()
    {
        try {
            // Logic to extract text from PDF
        } catch (\Exception $e) {
            // Handle exceptions
        }
    }

    public function exportToGoogleSheets()
    {
        try {
            // Logic to export data to Google Sheets
        } catch (\Exception $e) {
            // Handle exceptions
        }
    }
}
