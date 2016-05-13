<?php namespace App\Http\Controllers\Support;


use App\Models\PluginArea;

class PluginController extends InitController {

	protected $action;
	

	public function getArea() {
		$parentId = intval(\Input::input('parent_id'));
		$areas    = PluginArea::where('parent_id', $parentId)
			->lists('area_name','area_id');
		$data = [];
		foreach($areas as $area_id => $area_name) {
			$data[$area_id] = [
				'name'=> $area_name,
			];
		}
		echo json_encode($data);
	}

}
