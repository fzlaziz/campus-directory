<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use Illuminate\Http\Request;

class CampusController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    /**
     * @OA\Get(
     *     path="/api/campuses",
     *     tags={"Campuses"},
     *     summary="Get list of campuses with details",
     *     @OA\Response(response=200, description="List of campuses with details"),
     * )
     */

    public function index()
    {
        return Campus::with(['accreditation','district.city.province','campus_rankings','campus_type','degree_levels'])
        ->get()
        ->map(function ($campus) {
            $bestRanking = $campus->campus_rankings->min('rank');
            $rankScore = $bestRanking !== null ? $bestRanking : null;
            return [
                'id' => $campus->id,
                'name' => $campus->name,
                'description' => $campus->description,
                'date_of_establishment' => $campus->date_of_establishment,
                'logo_path' => $campus->logo_path,
                'address_latitude' => (float) $campus->address_latitude,
                'address_longitude' => (float) $campus->address_longitude,
                'web_address' => $campus->web_address,
                'phone_number' => $campus->phone_number,
                'rank_score' => $rankScore,
                'number_of_graduates' => $campus->number_of_graduates,
                'number_of_registrants' => $campus->number_of_registrants,
                'min_single_tuition' => $campus->min_single_tuition,
                'max_single_tuition' => $campus->max_single_tuition,
                'accreditation_id' => $campus->accreditation_id,
                'district' => ucwords(strtolower($campus->district->name)),
                'district_id' => $campus->district->id,
                'city' => ucwords(strtolower($campus->district->city->name)),
                'city_id' => $campus->district->city->id,
                'province' => ucwords(strtolower($campus->district->city->province->name)),
                'province_id' => $campus->district->city->province->id,
                'campus_type_id' => $campus->campus_type->id,
                'campus_type' => $campus->campus_type->name,
                'accreditation' => [
                    'id' => $campus->accreditation->id,
                    'name' => $campus->accreditation->name,
                ],
                'degree_levels' => $campus->degree_levels->map(function ($degreeLevel) {
                    return [
                        'id' => $degreeLevel->id,
                        'name' => $degreeLevel->name,
                    ];
                }),
            ];
        });
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Campus $campus)
    {
        $news = $campus->news()->latest()->limit(5)->get();
        $facilities = $campus->facilities()->get();
        $galleries = $campus->galleries()->get();
        $admission_statistics = $campus->admission_statistics()->get();
        $campusData = $campus->load(['degree_levels'])->toArray();
        $campusData = $campus->load(['admission_routes'])->toArray();
        $campusData = $campus->load(['faculties'])->toArray();
        $campusData['campus_type'] = $campus->campus_type->name;
        $campusData['accreditation'] = $campus->accreditation->name;
        $campusData['news'] = $news;
        $campusData['galleries'] = $galleries;
        $campusData['facilities'] = $facilities;
        $campusData['admission_statistitcs'] = $admission_statistics;

        return $campusData;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Campus $campus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campus $campus)
    {
        //
    }

    public function faculties(Campus $campus)
    {
        $faculties = $campus->faculties()->get();
        return response()->json($faculties);
    }

    public function reviews($campusId)
    {
        $campus = Campus::with('reviews.user')->findOrFail($campusId);

        $totalReviews = $campus->reviews->count();
        $averageRating = $campus->reviews->avg('rating');

        $reviews = $campus->reviews->map(function ($review) {
            return [
                'id' => $review->id,
                'user' => $review->user->name,
                'rating' => $review->rating,
                'review' => $review->review,
                'created_at' => $review->created_at->toIso8601String(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'total_reviews' => $totalReviews,
                'average_rating' => round($averageRating, 1),
                'reviews' => $reviews,
            ],
        ]);
    }

}
