<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MaterialController extends Controller
{
    public function create(Request $request, $clubId)
    {
        $club = Club::find($clubId);

        if (!$club) {
            return response([
                'message' => 'Club not found'
            ], 404);
        }

        $validateData = Validator::make($request->all(), [
            'title' => [
                'required'
            ],
            'details' => [
                'required'
            ],
            'duration' => [
                'required',
                'integer'
            ]
        ]);

        if ($validateData->fails()) {
            return response([
                'message' => 'Invalid Field',
                'errors' => $validateData->errors()
            ], 422);
        }

        $data = $validateData->validated();
        $data['club_id'] = $club->id;

        $material = Material::create($data);

        if (!$material) {
            return response([
                'message' => 'Something went wrong, please try again'
            ], 422);
        }

        return response([
            'message' => 'Create material success',
            'material' => $material
        ], 200);
    }

    public function getClubMaterials($clubId)
    {
        $club = Club::find($clubId);

        if (!$club) {
            return response([
                'message' => 'No club found'
            ], 404);
        }

        return response([
            'club' => [
                "id"=> $club->id,
        "name"=> $club->name,
        "description"=> $club->description,
        "club_day"=> $club->club_day,
        "tech_field"=> $club->tech_field,
        "created_at"=> $club->created_at,
        "updated_at"=> $club->updated_at

            ],
            'materials' => $club->materials,
            'message' => 'Get all material success'
        ]);
    }

    public function getSpecificMaterial($clubId, $materialId)
    {
        $material = Material::where('club_id', $clubId)->where('id', $materialId)->first();

        if (!$material) {
            return response([
                'message' => 'Material not found on the club'
            ], 404);
        }

        return response([
            'message' => "Get material success",
            'material' => $material
        ], 200);
    }
    public function removeMaterial($clubId, $materialId)
    {
        $material = Material::find($materialId);

        if (!$material) {
            return response([
                'message' => 'Material not found'
            ]);
        }

        Material::destroy($material->id);

        return response([
            'message' => 'Delete material success'
        ]);
    }
}
