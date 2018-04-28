<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSeriesFormRequest;
use App\Series;
use Illuminate\Http\Request;

class SeriesController extends Controller
{
    public function edit(Series $series)
    {
    	$series->load('parts'); // Eager load the parts

    	return view('series.edit', compact('series'));
    }

    public function update(UpdateSeriesFormRequest $request, Series $series)
    {
    	$series->title = $request->title;
    	$series->save();

    	$series->parts->each(function($part, $index) use ($request) {
    		// The array_only function returns only the 
    		// specified key / value pairs from the given array.
    		// So for this case just 'title' and 'sort_order'.
    		$part->update(array_only(
    			self::findObjectInArrayFromId($request->parts, $part->id), 
    			['title', 'sort_order']
    		));
    	});
    }

    protected function findObjectInArrayFromId($array, $id)
    {
    	foreach($array as $item) {
    		if($item['id'] === $id) {
    			return $item;
    		}
    	}
    }
}
