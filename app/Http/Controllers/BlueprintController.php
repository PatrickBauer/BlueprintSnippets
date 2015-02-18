<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Node;
use App\Services\BluePrintParser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class BlueprintController extends Controller
{

    function index(BluePrintParser $parser)
    {
        $file = Storage::get('Blueprint.snippet');
        $lines = preg_split("/\r\n|\n|\r/", $file);
        $lines = array_filter($lines);

        $parser->setLines($lines);
        $parser->parse();
        $objects = $parser->getObjects();

        return view('blueprint.index', compact('objects'));
    }

}
