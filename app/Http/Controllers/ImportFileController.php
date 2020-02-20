<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportXlsxRequest;
use App\Import\BrokenFileImport;

class ImportFileController extends Controller
{
    private $fileImport;


    public function __construct(BrokenFileImport $fileImport)
    {
        $this->fileImport = $fileImport;
    }

    /**
     * Display uploading.
     *
     */
    public function index()
    {
        return view('import');
    }

    /**
     * import xlsx file with products.
     *
     * @param ImportXlsxRequest $request
     *
     */
    public function Import(ImportXlsxRequest $request)
    {
        // Get default limit
        $normalTimeLimit = ini_get('max_execution_time');

        // Set new limit
        ini_set('max_execution_time', 20);

        $file = $request->file;
        $count = $this->fileImport->ImportProductToDB($file);
        ini_set('max_execution_time', $normalTimeLimit);
        return back()->with(
            'success', 'products in file '. $count['all'] .
            ' |successfully saved '. $count['saved'].
            ' |duplicates '. $count['duplicate'].
            ' |already exist '.$count['exist']);
    }
}
